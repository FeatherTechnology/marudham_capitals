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
    $("#user_type, #by_user").val("").hide();
    $("#department, #team").val("").hide(); // Hide both
    $("#map_name").closest(".choices").hide();
    map_name.clearStore();
    $("#commitment_report_table").DataTable().destroy();
    $("#commitment_report_table tbody").empty();

    if (type == "1") {
      $("#user_type, #by_user").val("").show();
      $("#by_user").empty().append("<option value=''>Select User</option>");
    } else if (type == "2" || type == "3" || type == "4") {
      //sector - group, Region - Line, Zone - Follow up
      $("#map_name").closest(".choices").show();
      getUserMappedDetails(type); //to Mapping details.
    } else if (type == "5") {
      $("#department").show();
      getDepartmentTeamNames();
    } else if (type == "6") {
      $("#team").show();
      getDepartmentTeamNames();
    }
  });

  $("#user_type").change(function () {
    let userType = $("#user_type").val();
    $("#by_user").empty().append("<option value=''>Select User</option>");

    if (userType != "") {
      getUserNames();
    }
  });

  //commitment Report Table
  $("#reset_btn").click(function () {
    commitmentReportTable();
  });
});

function getUserNames() {
  let user_type = $("#user_type").val();

  $.post(
    "reportFile/commitment/commitment_user_list.php",
    { user_type: user_type },
    function (response) {
      $("#by_user").empty();
      $("#by_user").append("<option value=''>Select User</option>");
      $.each(response, function (index, val) {
        $("#by_user").append(
          "<option value='" +
            val["user_ids"] +
            "'>" +
            val["fullname"] +
            "</option>",
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

function commitmentReportTable() {
  let fromDate = $("#from_date").val();
  let toDate = $("#to_date").val();
  let selectedType = $("#type").val();
  let selected_user = $("#by_user").val();
  let user_type = $("#user_type").val();
  let department = $("#department").val();
  let team = $("#team").val();

  let selectedVal = "";

  if (selectedType === "1") {
    // User
    selectedVal = "1"; // dummy value
  } else if (["2", "3", "4"].includes(selectedType)) {
    // Sector / Region / Zone
    selectedVal = $("#map_name").val();
  } else if (selectedType === "5") {
    // Department
    selectedVal = department;
  } else if (selectedType === "6") {
    // Team
    selectedVal = team;
  }

  // Common validation
  if (!fromDate || !toDate || !selectedType) {
    swalError("Warning", "From Date, To Date, and Type are required.");
    return;
  }

  // User validation
  if (selectedType === "1" && (!user_type || !selected_user)) {
    swalError("Warning", "User Type and User are required.");
    return;
  }

  // Sector / Region / Zone validation
  if (["2", "3", "4"].includes(selectedType) && !selectedVal) {
    swalError("Warning", "Mapping is required.");
    return;
  }

  // Department validation
  if (selectedType === "5" && !department) {
    swalError("Warning", "Department is required.");
    return;
  }

  // Team validation
  if (selectedType === "6" && !team) {
    swalError("Warning", "Team is required.");
    return;
  }

  $("#commitment_report_table").DataTable().destroy();
  // Declare table variable to store the DataTable instance
  var commitment_report_table = $("#commitment_report_table").DataTable({
    ...getStateSaveConfig("commitment_report_table"),
    order: [[0, "asc"]],
    processing: true,
    serverSide: true,
    serverMethod: "post",
    ajax: {
      url: "reportFile/commitment/getCommitmentReport.php",
      data: function (data) {
        data.search = $("#search").val();
        data.from_date = $("#from_date").val();
        data.to_date = $("#to_date").val();
        data.selectedType = $("#type").val();
        data.selectedVal = selectedVal;
        data.department = department;
        data.team = team;
        data.user_id = $("#by_user").val();
      },
    },
    dom: "lBfrtip",
    buttons: [
      {
        extend: "excel",
        title: "Due Followup Activity List",
        action: function (e, dt, button, config) {
          var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
          var dynamic = curDateJs("Due_Followup_Activity"); // or any base
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
    drawCallback: function () {
      searchFunction("commitment_report_table");
    },
  });

  // Pass the table variable to the initColVisFeatures function
  initColVisFeatures(commitment_report_table, "commitment_report_table");
}
