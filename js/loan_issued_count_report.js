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

    if ($.fn.DataTable.isDataTable("#issue_count_table")) {
      $("#issue_count_table").DataTable().clear().destroy();

      $("#issue_count_table tbody").empty(); // Remove old rows
      $("#issue_count_table tfoot").empty(); // Remove old footer
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

    requestIssuedReportCount(
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
    { screen: 6, user_type: user_type },
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

function requestIssuedReportCount(
  from_date,
  to_date,
  selectedType,
  user_type,
  user_id,
  selectedVal,
  loanCatVal,
) {
  $.ajax({
    url: "reportFile/work_count_report/getLoanIssuedCountReport.php",
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
      // Clear old footer before processing new response
      $("#issue_count_table tfoot").empty();

      // Handle empty response
      if (!res.data || res.data.length === 0) {
        if ($.fn.DataTable.isDataTable("#issue_count_table")) {
          $("#issue_count_table").DataTable().clear().draw();
        }
        return;
      }

      // Destroy existing table if it exists
      if ($.fn.DataTable.isDataTable("#issue_count_table")) {
        $("#issue_count_table").DataTable().destroy();
      }

      // Last row is total
      const totalRow = res.data[res.data.length - 1];
      const tableData = res.data.slice(0, -1);

      const columns = [
        { data: "sno" },
        {
          data: "fullname",
          title: selectedType == "2" ? "Sector Name" : "User Name",
        },
        { data: "loan_category" },
        { data: "agent_name" },
        { data: "new" },
        { data: "additional" },
        { data: "renewal" },
        { data: "reactive" },
        { data: "existing_new" },
        { data: "total_count", render: (d) => `<b>${d}</b>` },
        { data: "current" },
        { data: "pending" },
        { data: "od" },
        { data: "error" },
        { data: "legal" },
        { data: "status_total", render: (d) => `<b>${d}</b>` },
      ];

      const issue_count_table = $("#issue_count_table").DataTable({
        ...getStateSaveConfig("issue_count_table"),
        data: tableData,
        columns: columns,
        dom: "lBfrtip",
        buttons: [
          {
            extend: "excel",
            title: "Loan_issued_count_Repoet",
            action: function (e, dt, button, config) {
              const file = curDateJs("Loan_issued_count_Repoet");
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
        pageLength: 10,
        drawCallback: function () {
          searchFunction("issue_count_table");
          paginationFunction("issue_count_table");
        },
      });
      // Column visibility helper
      initColVisFeatures(issue_count_table, "issue_count_table");

      // Footer totals
      $("#issue_count_table tfoot").html(`
                <tr>
                    <td colspan="4"><b>Total</b></td>
                    <td>${totalRow.new}</td>
                    <td>${totalRow.additional}</td>
                    <td>${totalRow.renewal}</td>
                    <td>${totalRow.reactive}</td>
                    <td>${totalRow.existing_new}</td>
                    <td><b>${totalRow.total_count}</b></td>
                    <td>${totalRow.current}</td>
                    <td>${totalRow.pending}</td>
                    <td>${totalRow.od}</td>
                    <td>${totalRow.error}</td>
                    <td>${totalRow.legal}</td>
                    <td><b>${totalRow.status_total}</b></td>
                </tr>
            `);
    },
  });
}
