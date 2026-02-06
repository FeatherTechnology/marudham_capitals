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

    //Agent Report Table
    $('#reset_btn').click(function () {
        agentReportTable();
    })
});

function agentReportTable() {
    $('#agent_report_table').DataTable().destroy();
    var agent_report_table = $('#agent_report_table').DataTable({
        ...getStateSaveConfig('agent_report_table'),
        "order": [
            [0, "desc"]
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',

        ajax: {
            url: 'reportFile/agent/getAgentReport.php',
            data: function (d) {
                var search = $('input[type=search]').val();
                d.search = search;
                d.from_date = $('#from_date').val();
                d.to_date   = $('#to_date').val();
            }
        },

        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Agent Report List",
                action: function (e, dt, button, config) {
                    let dynamic = curDateJs('Agent_Report');
                    config.title = dynamic;
                    config.filename = dynamic;
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(
                        this, e, dt, button, config
                    );
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column'
            }
        ],

        lengthMenu: [[10, 25, 50, -1],[10, 25, 50, "All"]],

        footerCallback: function (row, data, start, end, display) {

            const api = this.api();
            const json = api.ajax.json();

            /* ---------------- OPENING BAL ---------------- */
            let openingBal = 0;
            if (json && json.opening_balance) {
                openingBal = parseFloat(
                    json.opening_balance.toString().replace(/,/g, '')
                ) || 0;  
            }
            $('#opbal').text(openingBal.toLocaleString('en-IN', { minimumFractionDigits: 0 }));

            /* ---------------- CLOSING BAL ---------------- */
            let closingBal = 0;
            if (json && json.closing_balance) {
                closingBal = parseFloat(
                    json.closing_balance.toString().replace(/,/g, '')
                ) || 0;   
            }
            const closingRow = $(api.table().footer()).find('#closingRow');

            closingRow.find('td:last').html('<b>' + closingBal.toLocaleString('en-IN', { minimumFractionDigits: 0 }) + '</b>');

            /* ---------------- PARSER ---------------- */
            const parseVal = (v) => {
                if (typeof v === 'string') {
                    return parseFloat(v.replace(/,/g, '')) || 0;
                }
                return v || 0;
            };

            /* ---------------- TOTALS ---------------- */
            // Array of column indices to sum
            var columnsToSum = [ 4, 5, 6,7];

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
        drawCallback: function () {
            searchFunction('agent_report_table');
            paginationFunction('agent_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(agent_report_table, 'agent_report_table');
}