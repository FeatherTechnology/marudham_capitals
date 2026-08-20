$(document).ready(function () {

    getBankNames();

    $('#from_date').change(function(){
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

         // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

    //cleared_report_table 
    $('#reset_btn').click(function () {
        clearedReportTable();
    });
});

function clearedReportTable() {
    let from_date = $('#from_date').val();
    let to_date = $('#to_date').val();
    let stmt_type = $('#stmt_type').val();
    let bank_name = $('#bank_name').val();

    if (!from_date ||!to_date || !bank_name || !stmt_type) {
        swalError('Warning', 'All Fields are required.');
        return;
    }

    $('#cleared_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var cleared_report_table = $('#cleared_report_table').DataTable({
        ...getStateSaveConfig('cleared_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'infoCallback': customDataTableInfo,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/cleared/getClearedReport.php',
            'data': function (data) {
                data.search = $('input[type=search]').val();
                data.from_date = from_date;
                data.to_date = to_date;
                data.stmt_type = stmt_type;
                data.bank_name = $('#bank_name').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Cleared Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Cleared_Report'); // or any base
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

            // Array of column indices to sum
            var columnsToSum = [5, 6, 7];
            // Loop through each column index
            columnsToSum.forEach(function (colIndex) {
                // Total over all pages for the current column
                var total = api
                    .column(colIndex)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer for the current column
                $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
            });
        },
        'drawCallback': function () {
            searchFunction('cleared_report_table');
            paginationFunction('cleared_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(cleared_report_table, 'cleared_report_table');
}

function getBankNames() {
    $.post('accountsFile/bankclearance/getBankNames.php', function (response) {
        $('#bank_name').empty();
        $('#bank_name').append("<option value=''>Select Bank Name</option>");
         // Add "All" at last
        $('#bank_name').append("<option value='all'>All</option>");
        $.each(response, function (index, val) {
            $('#bank_name').append("<option value='" + val['id'] + "'>" + val['bank_name'] + "</option>");
        });
    }, 'json');
}