const loanCategory = new Choices('#loan_category', {
    removeItemButton: true,
    noChoicesText: 'Select Category',
    allowHTML: true
});

$(document).ready(function () {

    //Balance Report Table
    // var balance_report_table = 
    $('#reset_btn').click(function () {
        // balance_report_table.ajax.reload();
        balanceReportTable();
    })
});

$(function(){
    getloancategorylist();
});

function balanceReportTable(){
    console.log( $('#loan_category').val());
    $('#balance_report_table').DataTable().destroy();
    $('#balance_report_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/balance/getBalanceReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.to_date = $('#to_date').val();
                data.loan_cat = $('#loan_category').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Balance Report List"
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
            var columnsToSum = [12, 13, 15, 16, 17, 18];

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
        'drawCallback': function() {
            searchFunction('balance_report_table');
        }
    });
}

function getloancategorylist(){
    $.ajax({
        url: 'reportFile/balance/getLoanCategory.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            loanCategory.clearStore();
            for (var i = 0; i < response.length; i++) {
                var loan_cat_name = response[i]['loan_category_creation_name'];
                var items = [{
                    value: loan_cat_name,
                    label: loan_cat_name
                    
                }]
                loanCategory.setChoices(items);
                loanCategory.init();
            }
        }
    })
}