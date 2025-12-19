$(document).ready(function () {
    //Collection Report Table
    $('#reset_btn').click(function () {
        noDuePayReportTable();
    })
});

function noDuePayReportTable(){
    $('#no_pay_due_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var no_pay_due_report_table = $('#no_pay_due_report_table').DataTable({
        ...getStateSaveConfig('no_pay_due_report_table'),
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/no_due_pay/getNoDuePayRreport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.from_date = $('#from_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "No Due Pay Report",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('No_Due_Pay_Report'); // or any base
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
        'drawCallback': function() {
            searchFunction('no_pay_due_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(no_pay_due_report_table, 'no_pay_due_report_table');
}
