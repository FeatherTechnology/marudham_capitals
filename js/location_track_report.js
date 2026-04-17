$(document).ready(function () {
    getUserNames();

    $('#from_date').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

        // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

    $('#user_type').change(function () {
        getUserNames();
    });

    $('#reset_btn').click(function () {
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();

        if (fromDate !== "" && toDate !== "" && $('#user_type').val() !== "" && $('#by_user').val() !== "") {
            locationTrackReportTable();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'All fields are required to generate the report.',
                confirmButtonColor: '#009688'
            });
        }
    });
});

function locationTrackReportTable(){

    let selected_user = $('#by_user').val();

    $('#location_track_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var location_track_report_table = $('#location_track_report_table').DataTable({
        ...getStateSaveConfig('location_track_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/location_track/getLocationTrackReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.user_id = selected_user;
                data.user_type = $('#user_type').val();
                data.from_date = $('#from_date').val(); 
                data.to_date = $('#to_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Location Track Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Location_Track_Report'); // or any base
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
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();

            // Remove formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

        },
        'drawCallback': function () {
            searchFunction('location_track_report_table');
            paginationFunction('location_track_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(location_track_report_table, 'location_track_report_table');
}

function getUserNames() {
    let user_type = $('#user_type').val();
    
    $.post('reportFile/location_track/user_name_list.php', { user_type: user_type }, function (response) {
        $('#by_user').empty();
        $('#by_user').append("<option value=''>Select User</option>");
         // Add "All" at last
        $('#by_user').append("<option value='all'>All</option>");
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['user_id'] + "'>" + val['fullname'] + "</option>");
        });
    }, 'json');
}