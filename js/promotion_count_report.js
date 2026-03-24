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

    $('#reset_btn').click(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let selected_user = $('#by_user').val();

        if (!from_date || !to_date || !selected_user) {
            swalError('Please Select All Fields!', 'All fields are required.');
            return;
        }

        getPromotionCountReport(from_date, to_date, selected_user);
    });
});

$(function () {
    getUserNames();
});

function getUserNames() {
    $.post('reportFile/due_followup_count_report/getDuefollowupUser.php', { screen: 3 }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option> <option value='0'>All</option>");
        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function getPromotionCountReport(from_date, to_date, selected_user) {

    $('#promotion_count_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var promotion_count_report_table = $('#promotion_count_report_table').DataTable({
        ...getStateSaveConfig('promotion_count_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/promotion_count/getPromotionCountReport.php',
            'data': function (data) {
                data.search = $('input[type=search]').val();
                data.from_date = from_date;
                data.to_date = to_date;
                data.user_id = selected_user;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Promotion Count Report",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Promotion_Count_Report'); // or any base
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
            /* ---------------- PARSER ---------------- */
            const parseVal = (v) => {
                if (typeof v === 'string') {
                    return parseFloat(v.replace(/,/g, '')) || 0;
                }
                return v || 0;
            };

            // Array of column indices to sum
            var columnsToSum = [1, 2, 3, 4, 5,  6, 7, 8, 9, 10,  11, 12, 13, 14, 15,  16, 17, 18, 19, 20];

            // Loop through each column index
            columnsToSum.forEach(function (colIndex) {
                // Total over all pages for the current column
                var total = api
                    .column(colIndex)
                    .data()
                    .reduce(function (a, b) {
                        return parseVal(a) + parseVal(b);
                    }, 0);
                // Update footer for the current column
                $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
            });
        },
        'drawCallback': function () {
            searchFunction('promotion_count_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(promotion_count_report_table, 'promotion_count_report_table');
}