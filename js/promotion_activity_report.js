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

    //commitment Report Table
    $('#reset_btn').click(function () {
        commitmentReportTable();
    })
});

function getUserNames() {
    $.post('reportFile/promotion_activity/user_list.php', function (response) {
        $('#by_user').empty();
        $('#by_user').append("<option value=''>Select User</option>");
        $.each(response, function (index, val) {
            $('#by_user').append(
                "<option value='" + val['user_ids'] + "'>" + val['fullname'] + "</option>"
            );
        });
    }, 'json');
}

function commitmentReportTable() {
    let selected_date = $('#selected_date').val();
    let selected_user = $('#by_user').val();

    if (!selected_date) {
        swalError('Please Select Date!', 'From Date and To Date is required.');
        return;
    }

    if (!selected_user) {
        swalError('Please Select User!', 'User selection is required.');
        return;
    }

    $('#promotion_activity_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var promotion_activity_report_table = $('#promotion_activity_report_table').DataTable({
        ...getStateSaveConfig('promotion_activity_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/promotion_activity/getPromotionActivityReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.selected_date = $('#selected_date').val();
                data.user_id = $('#by_user').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Promotion Activity Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Promotion_Activity_Report'); // or any base
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
            searchFunction('promotion_activity_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(promotion_activity_report_table, 'promotion_activity_report_table');
}

function swalError(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#009688',
    });
}