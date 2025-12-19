$(document).ready(function () {

    $('#reset_btn').click(function () {
        requestReportTable();
    })

});

function requestReportTable(){
    $('#due_list_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var due_list_report_table = $('#due_list_report_table').DataTable({
        ...getStateSaveConfig('due_list_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/due_list/getDueListReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.to_date = $('#to_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Due List Report",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Due_List_Report'); // or any base
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
            searchFunction('due_list_report_table');
            paginationFunction('due_list_report_table');
        },
        
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(due_list_report_table, 'due_list_report_table');
}
