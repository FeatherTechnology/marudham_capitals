const branchChoices = new Choices("#branch", {
    removeItemButton: true,
    noChoicesText: "No branches available",
    placeholderValue: "Select Branch",
    allowHTML: true
});


const sectorChoices = new Choices("#sector_name", {
    removeItemButton: true,
    noChoicesText: "No sector available",
    placeholderValue: "Select Sector",
    allowHTML: true
});

$(document).ready(function () {
    getBranchDropdown();
      getSectorDropdown("common", []);
    // Branch change -> load sectors
    $("#branch").on("change", function () {
        let branch = $(this).val() || [];
         getSectorDropdown("common", branch);
    });

    // Search
    $("#search_btn").on("click", function () {
        let from_date = $("#from_date").val();
        let branch = $("#branch").val() || [];
        let sector = $("#sector_name").val() || [];

        if (!from_date) {
            swalError("Warning", "Please select a month");
            return;
        }

        reactiveCustomerReport( from_date, branch,sector );
    });

});


// =====================================================
// REACTIVE CUSTOMER REPORT
// =====================================================

function reactiveCustomerReport( from_date, branch, sector) {
    $.ajax({
        url: "reportFile/reactive_count_report/renewalToReactiveCountReport.php",
        type: "POST",
        data: {
            from_date: from_date,
            branch: branch,
            sector: sector
        },
        dataType: "json",
        success: function (res) {
            console.log(res);
            // Destroy existing DataTable
            if ($.fn.DataTable.isDataTable("#reactive_customer_table")) {
                $("#reactive_customer_table").DataTable().destroy();
            }
            // Clear table
            $("#reactive_customer_table tbody").empty();
            // No data
            if (!res ||!res.data ||res.data.length === 0) {
                $("#reactive_customer_table tbody").html(`
                    <tr>
                        <td colspan="10" class="text-center">
                            No Reactive Customers Found
                        </td>
                    </tr>
                `);
                return;
            }

            // DataTable
            const reactive_customer_table = $("#reactive_customer_table").DataTable({
                    ...getStateSaveConfig("reactive_customer_table"),
                    data: res.data,
                    columns: [
                        { data: "sno"},
                        { data: "cus_id"},
                        { data: "aadhaar_number"},
                        { data: "customer_name"},
                        { data: "branch"},
                        { data: "sector"},
                        { data: "area"},
                        { data: "sub_area"},
                        { data: "mobile_number"},
                        { data: "closing_date"}
                    ],
                    dom: "lBfrtip",
                    buttons: [
                        {
                            extend: "excel",
                            title: "Reactive_Customer_Report",
                            action: function (e,dt,button,config) {
                                const file =curDateJs("Reactive_Customer_Report");
                                config.title = file;
                                config.filename = file;
                                 $.fn.dataTable.ext.buttons.excelHtml5.action.call(this,e,dt, button,config);
                            }
                        },

                        {
                            extend: "colvis",
                            collectionLayout: "fixed four-column"
                        }
                    ],

                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],

                    drawCallback: function () {
                        searchFunction("reactive_customer_table");
                        paginationFunction( "reactive_customer_table");
                    }
                });

            initColVisFeatures(reactive_customer_table, "reactive_customer_table" );
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            swalError("Warning", "Unable to load Reactive Customer Report.");

        }

    });

}


// =====================================================
// BRANCH DROPDOWN
// =====================================================

function getBranchDropdown(preselect = []) {
    return $.post("common_files/user_mapped_branches.php",{},
        function (response) {
            branchChoices.clearStore();
            let items = [];
            $.each(response, function (index, val) {
                items.push({value: String(val.branch_id),
                    label: val.branch_name,
                    selected: preselect.includes( String(val.branch_id))
                });
            });

            branchChoices.setChoices(items,"value","label",true );
        },
        "json"
    );
}


// =====================================================
// SECTOR DROPDOWN
// =====================================================

function getSectorDropdown(module, branch = [], preselect = []) {
    sectorChoices.clearStore();
    $.ajax({
        url: "common_files/get_sector_name.php",
        type: "POST",
        data: {
            module: module,
            branch: branch
        },
        dataType: "json",
        success: function (response) {
            let items = [];
            $.each(response, function (i, val) {
                items.push({
                    value: String(val.id),
                    label: val.name,
                    selected: preselect.includes(String(val.id))
                });

            });
            sectorChoices.setChoices(
                items,
                "value",
                "label",
                true
            );
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
}