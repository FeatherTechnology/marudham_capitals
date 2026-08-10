const map_name = new Choices("#map_name", {
  removeItemButton: true,
  noChoicesText: "Select Sector",
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

    if ($.fn.DataTable.isDataTable("#request_count_table")) {
      $("#request_count_table").DataTable().clear().destroy();

      $("#request_count_table tbody").empty(); // Remove old rows
      $("#request_count_table tfoot").empty(); // Remove old footer
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

    requestToIssuedReportCount(
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
    { screen: 2, user_type: user_type },
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

function requestToIssuedReportCount(
  from_date,
  to_date,
  selectedType,
  user_type,
  user_id,
  selectedVal,
  loanCatVal,
) {
  $.ajax({
    url: "reportFile/request_count_report/requestCountReport.php",
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
      let title = "User Name";

      if (selectedType == "2") {
        title = "Sector Name";
      } else if (selectedType == "3") {
        title = "Region Name";
      } else if (selectedType == "4") {
        title = "Zone Name";
      }

      // Clear old footer before processing new response
      $("#request_count_table tfoot").empty();

      // Handle empty response
      if (!res.data || res.data.length === 0) {
        if ($.fn.DataTable.isDataTable("#request_count_table")) {
          $("#request_count_table").DataTable().clear().draw();
        }
        return;
      }

      const totalRow = res.data[res.data.length - 1];
      const tableData = res.data.slice(0, -1);

      // Destroy existing table once
      if ($.fn.DataTable.isDataTable("#request_count_table")) {
        $("#request_count_table").DataTable().destroy();
      }

      const columns = [
        /* BASIC */
        { data: "sno" },
        {
          data: "fullname",
          title: title,
        },

        { data: "loan_category" },
        /* PREVIOUS IN PROCESS */
        { data: "previous.new" },
        { data: "previous.renewal" },
        { data: "previous.reactive" },
        { data: "previous.additional" },
        { data: "previous.existing_new" },
        { data: "previous.reloan" },
        { data: "previous.total", render: (d) => `<b>${d}</b>` },

        /* REQUEST */
        { data: "request.new" },
        { data: "request.renewal" },
        { data: "request.reactive" },
        { data: "request.additional" },
        { data: "request.existing_new" },
        { data: "request.reloan" },
        { data: "request.total", render: (d) => `<b>${d}</b>` },

        /* CANCEL */
        { data: "cancel.new" },
        { data: "cancel.renewal" },
        { data: "cancel.reactive" },
        { data: "cancel.additional" },
        { data: "cancel.existing_new" },
        { data: "cancel.reloan" },
        { data: "cancel.total", render: (d) => `<b>${d}</b>` },

        /* REVOKE */
        { data: "revoke.new" },
        { data: "revoke.renewal" },
        { data: "revoke.reactive" },
        { data: "revoke.additional" },
        { data: "revoke.existing_new" },
        { data: "revoke.reloan" },
        { data: "revoke.total", render: (d) => `<b>${d}</b>` },

        /* PROCESS */
        { data: "process.new" },
        { data: "process.renewal" },
        { data: "process.reactive" },
        { data: "process.additional" },
        { data: "process.existing_new" },
        { data: "process.reloan" },
        { data: "process.total", render: (d) => `<b>${d}</b>` },

        /* ISSUED */
        { data: "issued.new" },
        { data: "issued.renewal" },
        { data: "issued.reactive" },
        { data: "issued.additional" },
        { data: "issued.existing_new" },
        { data: "issued.reloan" },
        { data: "issued.total", render: (d) => `<b>${d}</b>` },
        /* STATUS */
        { data: "status.current" },
        { data: "status.pending" },
        { data: "status.od" },
        { data: "status.error" },
        { data: "status.legal" },
        { data: "status.total", render: (d) => `<b>${d}</b>` },
      ];

      const request_count_table = $("#request_count_table").DataTable({
        ...getStateSaveConfig("request_count_table"),
        data: tableData,
        columns: columns,
        dom: "lBfrtip",
        buttons: [
          {
            extend: "excel",
            title: "Request_Count_Report",
            action: function (e, dt, button, config) {
              const file = curDateJs("Request_count_table");
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
        drawCallback: function () {
          searchFunction("request_count_table");
          paginationFunction("request_count_table");
        },
      });

      // Column visibility helper
      initColVisFeatures(request_count_table, "request_count_table");

      // Footer totals
      $("#request_count_table tfoot").html(`
                <tr>
                    <td colspan="3"><b>Total</b></td>
                    <td>${totalRow.previous.new}</td>
                    <td>${totalRow.previous.renewal}</td>
                    <td>${totalRow.previous.reactive}</td>
                    <td>${totalRow.previous.additional}</td>
                    <td>${totalRow.previous.existing_new}</td>
                    <td>${totalRow.previous.reloan}</td>
                    <td><b>${totalRow.previous.total}</b></td>

                    <td>${totalRow.request.new}</td>
                    <td>${totalRow.request.renewal}</td>
                    <td>${totalRow.request.reactive}</td>
                    <td>${totalRow.request.additional}</td>
                    <td>${totalRow.request.existing_new}</td>
                    <td>${totalRow.request.reloan}</td>
                    <td><b>${totalRow.request.total}</b></td>

                    <td>${totalRow.cancel.new}</td>
                    <td>${totalRow.cancel.renewal}</td>
                    <td>${totalRow.cancel.reactive}</td>
                    <td>${totalRow.cancel.additional}</td>
                    <td>${totalRow.cancel.existing_new}</td>
                    <td>${totalRow.cancel.reloan}</td>
                    <td><b>${totalRow.cancel.total}</b></td>

                    <td>${totalRow.revoke.new}</td>
                    <td>${totalRow.revoke.renewal}</td>
                    <td>${totalRow.revoke.reactive}</td>
                    <td>${totalRow.revoke.additional}</td>
                    <td>${totalRow.revoke.existing_new}</td>
                    <td>${totalRow.revoke.reloan}</td>
                    <td><b>${totalRow.revoke.total}</b></td>

                    <td>${totalRow.process.new}</td>
                    <td>${totalRow.process.renewal}</td>
                    <td>${totalRow.process.reactive}</td>
                    <td>${totalRow.process.additional}</td>
                    <td>${totalRow.process.existing_new}</td>
                    <td>${totalRow.process.reloan}</td>
                    <td><b>${totalRow.process.total}</b></td>

                    <td>${totalRow.issued.new}</td>
                    <td>${totalRow.issued.renewal}</td>
                    <td>${totalRow.issued.reactive}</td>
                    <td>${totalRow.issued.additional}</td>
                    <td>${totalRow.issued.existing_new}</td>
                    <td>${totalRow.issued.reloan}</td>
                    <td><b>${totalRow.issued.total}</b></td>

                    <td>${totalRow.status.current}</td>
                    <td>${totalRow.status.pending}</td>
                    <td>${totalRow.status.od}</td>
                    <td>${totalRow.status.error}</td>
                    <td>${totalRow.status.legal}</td>
                    <td><b>${totalRow.status.total}</b></td>
                </tr>
            `);
    },
  });
}
