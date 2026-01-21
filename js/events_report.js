$(document).ready(function () {

    $('#from_date').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

        // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

    //Event Report Table
    $('#reset_btn').click(function () {
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();

        if (fromDate !== "" && toDate !== "") {
            eventsReportTable();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Dates',
                text: 'Please select both From and To dates before Search.',
                confirmButtonColor: '#009688'
            });
        }
    });
});

function eventsReportTable() {
    $('#event_list_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var event_list_report_table = $('#event_list_report_table').DataTable({
        ...getStateSaveConfig('event_list_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/events/getEventsReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Events Activity List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('events_Report'); // or any base
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
            searchFunction('event_list_report_table');
            paginationFunction('event_list_report_table');
        },

    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(event_list_report_table, 'event_list_report_table');
}
