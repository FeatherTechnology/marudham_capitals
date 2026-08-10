const map_name = new Choices("#map_name", {
  removeItemButton: true,
  noChoicesText: "Select",
  allowHTML: true,
});

const loanCategory = new Choices("#loan_category", {
  removeItemButton: true,
  noChoicesText: "Select Category",
  allowHTML: true,
});

$("#map_name, #loan_category").closest(".choices").hide();

$(document).ready(function () {
  // 🔹 Date validation
  $("#from_date").change(function () {
    const fromDate = $(this).val();
    const toDate = $("#to_date").val();
    $("#to_date").attr("min", fromDate);

    if (toDate && fromDate > toDate) {
      $("#to_date").val("");
    }
  });

  $("#type").change(function (e) {
    let type = $(this).val();
    $("#user_type, #by_user, #department, #team").val("").hide();
    $("#map_name, #loan_category").closest(".choices").hide();
    map_name.clearStore();

    if ($.fn.DataTable.isDataTable("#due_followup_count_table")) {
      $("#due_followup_count_table").DataTable().clear().destroy();

      $("#due_followup_count_table tbody").empty(); // Remove old rows
      $("#due_followup_count_table tfoot td:not(:first)").html("");
    }

    if (type == "1") {
      $("#user_type, #by_user").val("").show();
      $("#by_user").empty().append("<option value=''>Select User</option>");
    } else if (type == "2" || type == "3" || type == "4") {
      //sector - group, Region - Line, Zone - Follow up
      $("#map_name, #loan_category").closest(".choices").show();
      getUserMappedDetails(type); //to Mapping details.
      getUserLoanCategories(); //to get Loan Category list.
    } else if (type == "5" || type == "6") {
      // Department / Team
      $("#loan_category").closest(".choices").show();
      $(type == "5" ? "#department" : "#team").show();

      getUserLoanCategories(); // Get Loan Category list
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
    let loanCatVal = "1";

    if (["2", "3", "4"].includes(selectedType)) {
      selectedVal = $("#map_name").val();
      loanCatVal = $("#loan_category").val();
    } else if (selectedType === "5") {
      selectedVal = $("#department").val();
      loanCatVal = $("#loan_category").val();
    } else if (selectedType === "6") {
      selectedVal = $("#team").val();
      loanCatVal = $("#loan_category").val();
    }

    if (!from_date || !to_date || !selectedType) {
      swalError("Warning", "From Date, To Date, and Type are required.");
      return;
    }

    if (selectedType === "1" && (!user_type || !selected_user)) {
      swalError("Warning", "User Type and User are required.");
      return;
    }

    if (
      ["2", "3", "4"].includes(selectedType) &&
      (!selectedVal || !loanCatVal)
    ) {
      swalError("Warning", "Mapping and Loan Category are required.");
      return;
    }

    if (selectedType === "5" && (!$("#department").val() || !loanCatVal)) {
      swalError("Warning", "Department and Loan Category are required.");
      return;
    }

    if (selectedType === "6" && (!$("#team").val() || !loanCatVal)) {
      swalError("Warning", "Team and Loan Category are required.");
      return;
    }

    let ttle;
    if (selectedType == "2") {
      ttle = "Sector";
    } else if (selectedType == "3") {
      ttle = "Region";
    } else if (selectedType == "4") {
      ttle = "Zone";
    } else {
      ttle = "User Name";
    }

    $("#th_name").text(ttle);

    dueFollowupCount(
      from_date,
      to_date,
      selectedType,
      user_type,
      selected_user,
      selectedVal,
      loanCatVal,
    );
  });
});

function getUserNames() {
  let user_type = $("#user_type").val();

  $.post(
    "reportFile/due_followup_count_report/getDuefollowupUser.php",
    { screen: 1, user_type: user_type },
    function (response) {
      $("#by_user")
        .empty()
        .append("<option value=''>Select User</option>")
        .append("<option value='all'>All</option>");

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

// Due Followup Count
function dueFollowupCount(
  from_date,
  to_date,
  selectedType,
  user_type,
  user_id,
  selectedVal,
  loanCatVal,
) {
  $.ajax({
    url: "reportFile/due_followup_count_report/dueFollowupCount.php",
    type: "POST",
    data: {
      from_date,
      to_date,
      selectedType,
      user_type,
      user_id,
      selectedVal,
      loanCatVal,
    },
    dataType: "json",
    success: function (res) {
      // Handle empty response
      if (!res.data || res.data.length === 0) {
        if ($.fn.DataTable.isDataTable("#due_followup_count_table")) {
          $("#due_followup_count_table").DataTable().clear().draw();
        }
        return;
      }

      const totalRow = res.data[res.data.length - 1];
      const tableData = res.data.slice(0, -1);

      // Destroy existing table once
      if ($.fn.DataTable.isDataTable("#due_followup_count_table")) {
        $("#due_followup_count_table").DataTable().destroy();
      }

      const columns = [
        /* BASIC */
        { data: "sno" },
        { data: "fullname" },
        { data: "loan_category" },
        { data: "total_customer" },
        { data: "total_entries" },

        /* Mobile */
        { data: "mobile.commitment" },
        { data: "mobile.unavailable" },
        { data: "mobile.paid" },
        { data: "mobile.total", render: (d) => `<b>${d}</b>` },

        /* Direct */
        { data: "direct.commitment" },
        { data: "direct.unavailable" },
        { data: "direct.paid" },
        { data: "direct.total", render: (d) => `<b>${d}</b>` },
      ];

      const due_followup_count_table = $("#due_followup_count_table").DataTable(
        {
          ...getStateSaveConfig("due_followup_count_table"),
          data: tableData,
          columns: columns,
          dom: "lBfrtip",
          buttons: [
            {
              extend: "excel",
              title: "Due_Followup_Count_Report",
              action: function (e, dt, button, config) {
                const file = curDateJs("Due_Followup_Count_Report");
                config.title = file;
                config.filename = file;
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(
                  this,
                  e,
                  dt,
                  button,
                  config,
                );
              },
            },
            { extend: "colvis", collectionLayout: "fixed four-column" },
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
            var columnsToSum = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

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
            searchFunction("due_followup_count_table");
            paginationFunction("due_followup_count_table");
          },
        },
      );

      // Column visibility helper
      initColVisFeatures(due_followup_count_table, "due_followup_count_table");
    },
  });
}
