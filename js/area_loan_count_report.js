$(document).ready(function () {

    getTalukDropdown();
    getLoanCategoryDropdown();

    // Reset / Search button click
    $('#reset_btn').click(function () {
        const taluk = $('#taluk').val();
        const loan_cat = $('#loan_cat').val();

        if (taluk !== "" && loan_cat !== "") {
            const loanCatText = $('#loan_cat option:selected').text();
            $('#Loan_category').text("Loan Category : " + loanCatText);

            // Reload table
            areaLoanCountReportTable();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Details',
                text: 'Please select both Taluk and Loan Category before Search.',
                confirmButtonColor: '#009688'
            });
        }
    });
});

// -------------------------------------------
// Populate Taluk Dropdown
function getTalukDropdown() {
    $.ajax({
        url: 'reportFile/area_count_report/getTalukDropdown.php',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            response.sort((a, b) => a.taluk.localeCompare(b.taluk));
            $('#taluk').empty().append('<option value="">Select Taluk</option><option value="0">All</option>');
            response.forEach(t => {
                $('#taluk').append(`<option value="${t.taluk}">${t.taluk}</option>`);
            });
        }
    });
}

// -------------------------------------------
// Populate Loan Category Dropdown
function getLoanCategoryDropdown() {
    $.ajax({
        url: 'manageUser/getLoanCatDropdown.php',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            response.sort((a, b) => a.loan_cat_name.localeCompare(b.loan_cat_name));
            $('#loan_cat').empty().append('<option value="">Select Loan Category</option><option value="0">All</option>');
            response.forEach(cat => {
                $('#loan_cat').append(`<option value="${cat.loan_cat_id}">${cat.loan_cat_name}</option>`);
            });
        }
    });
}

// -------------------------------------------
// Load DataTable
function areaLoanCountReportTable() {
    const tableId = '#area_loan_count_report_table';

    // Destroy existing table if exists
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    // Initialize DataTable
    var area_loan_count_report_table = $(tableId).DataTable({
        ...getStateSaveConfig('area_loan_count_report_table'),
        order: [[1, "asc"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'reportFile/area_count_report/getAreaLoanCountReport.php',
            data: function (data) {
                data.taluk = $('#taluk').val();
                data.loan_cat = $('#loan_cat').val();
                var search = $('input[type=search]').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Area Loan Count Report",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Area_Loan_Count_Report'); // dynamic filename
                    config.title = dynamic;
                    config.filename = dynamic;
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        lengthMenu: [[10, 25, 50, -1], 
                     [10, 25, 50, "All"] ],
        columns: [
            { data: null, render: function(data, type, row, meta){ return meta.row + meta.settings._iDisplayStart + 1; }, orderable:false },
            { data: "area_name" },
            { data: "taluk" },
            { data: "line_names" },
            { data: "group_names" },
            { data: "customer_count" },
            { data: "loan_count" },
            { data: "Current" },
            { data: "Pending" },
            { data: "OD" },
            { data: "Error" },
            { data: "Legal" },
        ],
        drawCallback: function () {
            paginationFunction('area_loan_count_report_table');
        }
    });

    // Initialize ColVis features
    initColVisFeatures(area_loan_count_report_table, 'area_loan_count_report_table');
}
