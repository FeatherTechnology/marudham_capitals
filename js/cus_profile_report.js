$(document).ready(function () {

    //Customer Profile Report Table
    // Declare table variable to store the DataTable instance
    var cust_profile_report_table = $('#cust_profile_report_table').DataTable({
        ...getStateSaveConfig('cust_profile_report_table'),
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/customer_profile/getCustomerProfileReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Customer Profile Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Customer_Profile_Report'); // or any base
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
        'drawCallback': function () {
            searchFunction('cust_profile_report_table');
            paginationFunction('cust_profile_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(cust_profile_report_table, 'cust_profile_report_table');
});
