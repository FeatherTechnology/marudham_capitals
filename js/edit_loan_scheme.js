$(document).ready(function () {

    //Scheme Type Change
    $('#monthly,#weekly,#daily').click(function () {
        var scheme_type = $('input[name=scheme_type]:checked').val();
        if (scheme_type == 'monthly') {
            $('.monthly_scheme').show(); $('.weekly_scheme').hide(); $('.daily_scheme').hide();
            mothlyDT();
        }
        if (scheme_type == 'weekly') {
            $('.monthly_scheme').hide(); $('.weekly_scheme').show(); $('.daily_scheme').hide();
            weeklyDT();
        }
        if (scheme_type == 'daily') {
            $('.monthly_scheme').hide(); $('.weekly_scheme').hide(); $('.daily_scheme').show();
            dailyDT();
        }
    })



})//Ready state End

$(function () {
    var scheme_type = $('input[name=scheme_type]:checked').val();
    if (scheme_type == 'monthly') {
        mothlyDT();
    } else if (scheme_type == 'weekly') {
        weeklyDT();
    } else if (scheme_type == 'daily') {
        dailyDT();
    }

})

// Loan Scheme datatable
function mothlyDT() {

    var table = $('#loan_scheme_weekly_table').DataTable();
    table.destroy();
    var table = $('#loan_scheme_daily_table').DataTable();
    table.destroy();

    // Declare table variable to store the DataTable instance
    var loan_scheme_monthly_table = $('#loan_scheme_monthly_table').DataTable({
        ...getStateSaveConfig('loan_scheme_monthly_table'),
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxLoanSchemeMonthlyFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Loan Scheme List",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Loan_Scheme_Monthly_List'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "drawCallback": function () {
            searchFunction('loan_scheme_monthly_table');
            paginationFunction('loan_scheme_monthly_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(loan_scheme_monthly_table, 'loan_scheme_monthly_table');
}
// Loan Scheme datatable
function weeklyDT() {

    var table = $('#loan_scheme_monthly_table').DataTable();
    table.destroy();
    var table = $('#loan_scheme_daily_table').DataTable();
    table.destroy();

    // Declare table variable to store the DataTable instance
    var loan_scheme_weekly_table = $('#loan_scheme_weekly_table').DataTable({
        ...getStateSaveConfig('loan_scheme_weekly_table'),
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxLoanSchemeWeeklyFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Loan Scheme List",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Loan_Scheme_Weekly_List'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "drawCallback": function () {
            searchFunction('loan_scheme_weekly_table');
            paginationFunction('loan_scheme_weekly_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(loan_scheme_weekly_table, 'loan_scheme_weekly_table');
}
// Loan Scheme datatable
function dailyDT() {

    var table = $('#loan_scheme_monthly_table').DataTable();
    table.destroy();
    var table = $('#loan_scheme_weekly_table').DataTable();
    table.destroy();

    // Declare table variable to store the DataTable instance
    var loan_scheme_daily_table = $('#loan_scheme_daily_table').DataTable({
        ...getStateSaveConfig('loan_scheme_daily_table'),
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxLoanSchemeDailyFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Loan Scheme List",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Loan_Scheme_Daily_List'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "drawCallback": function () {
            searchFunction('loan_scheme_daily_table');
            paginationFunction('loan_scheme_daily_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(loan_scheme_daily_table, 'loan_scheme_daily_table');
}
