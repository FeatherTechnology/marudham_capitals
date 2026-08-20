const map_name = new Choices("#map_name", {
  removeItemButton: true,
  noChoicesText: "Select",
  allowHTML: true,
});

$("#map_name").closest(".choices").hide();

$(document).ready(function () {
  $("#from_date").change(function () {
    const fromDate = $(this).val();
    const toDate = $("#to_date").val();
    $("#to_date").attr("min", fromDate);

    // Check if from_date is greater than to_date
    if (toDate && fromDate > toDate) {
      $("#to_date").val(""); // Clear the invalid value
    }
  });

  $("#type").change(function (e) {
    let type = $(this).val();
    $("#user_type, #by_user, #department, #team").val("").hide();
    $("#map_name").closest(".choices").hide();
    map_name.clearStore();

    $("#promotion_count_report_table").DataTable().destroy();
    $("#promotion_count_report_table tbody").empty();
    $("#promotion_count_report_table tfoot td:not(:first)").html("");

    if (type == "1") {
      $("#user_type, #by_user").val("").show();
      $("#by_user").empty().append("<option value=''>Select User</option>");
    } else if (type == "2" || type == "3" || type == "4") {
      //sector - group, Region - Line, Zone - Follow up
      $("#map_name").closest(".choices").show();
      getUserMappedDetails(type); //to Mapping details.
    } else if (type == "5" || type == "6") {
      // Department / Team
      $(type == "5" ? "#department" : "#team").show();
      getDepartmentTeamNames(); // Get Department & Team list
    }
  });

  $("#user_type").change(function () {
    let userType = $("#user_type").val();
    $("#by_user").empty().append("<option value=''>Select User</option>");

    if (userType != "") {
      getUserNames();
    }
  });

  // 🔹 Reset / Show Button Click
  $("#reset_btn").click(function () {
    let from_date = $("#from_date").val();
    let to_date = $("#to_date").val();
    let selectedType = $("#type").val();
    let user_type = $("#user_type").val();
    let selected_user = $("#by_user").val();

    let selectedVal = "1";

    if (["2", "3", "4"].includes(selectedType)) {
      selectedVal = $("#map_name").val();
    } else if (selectedType === "5") {
      selectedVal = $("#department").val();
    } else if (selectedType === "6") {
      selectedVal = $("#team").val();
    }

    if (!from_date || !to_date || !selectedType) {
      swalError("Warning", "From Date, To Date, and Type are required.");
      return;
    }

    if (selectedType === "1" && (!user_type || !selected_user)) {
      swalError("Warning", "User Type and User are required.");
      return;
    }

    if (["2", "3", "4"].includes(selectedType) && !selectedVal) {
      swalError("Warning", "Mapping are required.");
      return;
    }

    if (selectedType === "5" && !$("#department").val()) {
      swalError("Warning", "Department are required.");
      return;
    }

    if (selectedType === "6" && !$("#team").val()) {
      swalError("Warning", "Team are required.");
      return;
    }

    getPromotionCountReport(
      from_date,
      to_date,
      selectedType,
      user_type,
      selected_user,
      selectedVal,
    );
  });
});

function getUserNames() {
  let user_type = $("#user_type").val();

  $.post(
    "reportFile/due_followup_count_report/getDuefollowupUser.php",
    { screen: 3, user_type: user_type },
    function (response) {
      $("#by_user")
        .empty()
        .append(
          "<option value=''>Select User</option> <option value='0'>All</option>",
        );
      $.each(response, function (i, val) {
        $("#by_user").append(
          "<option value='" + val.user_id + "'>" + val.username + "</option>",
        );
      });
    },
    "json",
  );
}

function getDepartmentTeamNames() {
  let type = $("#type").val();

  let target = type == "5" ? "#department" : "#team";

  $(target).empty();

  $.post(
    "reportFile/promotion_activity/department_team_list.php",
    { type: type },
    function (response) {
      let optionText = type == "5" ? "Select Department" : "Select Team";

      $(target).append("<option value=''>" + optionText + "</option>");

      $.each(response, function (index, val) {
        $(target).append(
          "<option value='" + val.id + "'>" + val.name + "</option>",
        );
      });
    },
    "json",
  );
}

function getPromotionCountReport(
  from_date,
  to_date,
  selectedType,
  user_type,
  user_id,
  selectedVal,
) {
  $("#promotion_count_report_table").DataTable().destroy();
  // Declare table variable to store the DataTable instance
  var promotion_count_report_table = $(
    "#promotion_count_report_table",
  ).DataTable({
    ...getStateSaveConfig("promotion_count_report_table"),
    order: [[0, "asc"]],
    processing: true,
    serverSide: true,
    infoCallback: customDataTableInfo,
    serverMethod: "post",
    ajax: {
      url: "reportFile/promotion_count/getPromotionCountReport.php",
      data: function (data) {
        data.search = $("input[type=search]").val();
        data.from_date = from_date;
        data.to_date = to_date;
        data.selectedType = selectedType;
        data.user_type = user_type;
        data.user_id = user_id;
        data.selectedVal = selectedVal;
      },
    },
    dom: "lBfrtip",
    buttons: [
      {
        extend: "excel",
        title: "Promotion Count Report",
        action: function (e, dt, button, config) {
          var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
          var dynamic = curDateJs("Promotion_Count_Report"); // or any base
          config.title = dynamic; // for versions that use title as filename
          config.filename = dynamic; // for html5 filename
          defaultAction.call(this, e, dt, button, config);
        },
      },
      {
        extend: "colvis",
        collectionLayout: "fixed four-column",
      },
    ],
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      // Remove formatting to get integer data for summation
      /* ---------------- PARSER ---------------- */
      const parseVal = (v) => {
        if (typeof v === "string") {
          return parseFloat(v.replace(/,/g, "")) || 0;
        }
        return v || 0;
      };

      // Array of column indices to sum
      var columnsToSum = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
        21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38,
        39, 40,
      ];

      // Loop through each column index
      columnsToSum.forEach(function (colIndex) {
        // Total over all pages for the current column
        var total = api
          .column(colIndex)
          .data()
          .reduce(function (a, b) {
            return parseVal(a) + parseVal(b);
          }, 0);
        // Update footer for the current column
        $(api.column(colIndex).footer()).html(
          `<b>` + total.toLocaleString() + `</b>`,
        );
      });
    },
    drawCallback: function () {
      searchFunction("promotion_count_report_table");
    },
  });

  // Pass the table variable to the initColVisFeatures function
  initColVisFeatures(
    promotion_count_report_table,
    "promotion_count_report_table",
  );
}
