$(document).ready(function () {
    //Collection Report Table
    $('#reset_btn').click(function () {
        noDuePayReportTable();
    })
});

function noDuePayReportTable(){
    $('#no_pay_due_report_table').DataTable().destroy();
    $('#no_pay_due_report_table').DataTable({
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
            title: "Collection Report List"
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
        // "footerCallback": function (row, data, start, end, display) {
        //     var api = this.api();

        //     // Remove formatting to get integer data for summation
        //     var intVal = function (i) {
        //         return typeof i === 'string' ?
        //             i.replace(/[\$,]/g, '') * 1 :
        //             typeof i === 'number' ?
        //                 i : 0;
        //     };

        //     // Array of column indices to sum
        //     var columnsToSum = [18, 19, 20, 21];

        //     // Loop through each column index
        //     columnsToSum.forEach(function (colIndex) {
        //         // Total over all pages for the current column
        //         var total = api
        //             .column(colIndex)
        //             .data()
        //             .reduce(function (a, b) {
        //                 return intVal(a) + intVal(b);
        //             }, 0);
        //         // Update footer for the current column
        //         $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
        //     });
        // },
        'drawCallback': function() {
            searchFunction('no_pay_due_report_table');
        }
    });
}
