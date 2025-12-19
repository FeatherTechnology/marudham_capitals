$(document).ready(function () {

    // Reset / Search button click
    $('#reset_btn').click(function () {
        const taluk = $('#taluk').val();
        const loan_cat = $('#loan_cat').val();

        if (taluk !== "" && loan_cat !== "") {

            // Header update
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

    getTalukDropdown();
    getLoanCategoryDropdown();
});


// -------------------------------------------
//  Taluk Dropdown
// -------------------------------------------
function getTalukDropdown() {
    $.ajax({
        url: 'reportFile/area_count_report/getTalukDropdown.php',
        type: 'post',
        dataType: 'json',
        success: function (response) {

            response.sort((a, b) => a.taluk.localeCompare(b.taluk));

            $('#taluk').empty().append('<option value="">Select Taluk</option>');

            response.forEach(t => {
                $('#taluk').append(`<option value="${t.taluk}">${t.taluk}</option>`);
            });
        }
    });
}


// -------------------------------------------
//  Loan Category Dropdown
// -------------------------------------------
function getLoanCategoryDropdown() {
    $.ajax({
        url: 'manageUser/getLoanCatDropdown.php',
        type: 'post',
        dataType: 'json',
        success: function (response) {

            response.sort((a, b) => a.loan_cat_name.localeCompare(b.loan_cat_name));

            $('#loan_cat').empty()
                .append('<option value="">Select Loan Category</option>')
                .append('<option value="0">All</option>');

            response.forEach(cat => {
                $('#loan_cat').append(
                    `<option value="${cat.loan_cat_id}">${cat.loan_cat_name}</option>`
                );
            });
        }
    });
}



// -------------------------------------------
//  DataTable Load
// -------------------------------------------
function areaLoanCountReportTable() {

    const tid = 'area_loan_count_report_table';
    const tableId = '#' + tid;

    // Destroy if already exists
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    // Initialize
    // Declare table variable to store the DataTable instance
    var table = $(tableId).DataTable({
        ...getStateSaveConfig(tid),
        order: [[1, 'asc']],  // Sort by Area
        processing: true,
        serverSide: true,
        serverMethod: 'post',

        ajax: {
            url: 'reportFile/area_count_report/getAreaLoanCountReport.php',
            data: function (data) {
                data.taluk = $('#taluk').val();
                data.loan_cat = $('#loan_cat').val();
                data.search = $('input[type=search]').val(); // added for consistency
            }
        },

        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamicName = curDateJs("Area_Loan_Count_Report");
                    config.title = dynamicName;
                    config.filename = dynamicName;
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column'
            }
        ],


        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],

        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false
            },
            { data: "area_name" },
            { data: "taluk" },
            { data: "line_names" },
            { data: "group_names" },
            { data: "customer_count" },
            { data: "loan_count" },
            { data: "current" },
            { data: "pending" },
            { data: "od" },
            { data: "error" },
            { data: "legal" }
        ],

        columnDefs: [
            {
                targets: [7, 8, 9, 10, 11],
                orderable: true,
                orderData: 1   // Always sort by Area column
            }
        ],

        drawCallback: function () {
            searchFunction(tid);
            paginationFunction(tid);
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(table, 'tid');
}
