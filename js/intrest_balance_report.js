const loanCategory = new Choices('#loan_category', {
    removeItemButton: true,
    noChoicesText: 'Select Category',
    allowHTML: true
});

$(document).ready(function () {
    //Balance Report Table
    $('#reset_btn').click(function () {
        let reportType = $('#report_type').val();
        let url;
        let tid;
        let colArr;

        if(reportType =='1'){//Balance
            url = 'reportFile/intrest_loan_report/getIntrestBalanceReport.php';
            tid = 'balance_report_table';
            colArr = [16, 17, 19, 21, 22];
            $('#balance_table_div').show();
            $('#princ_intrst_table_div').hide();

        }else if(reportType =='2'){ //Priciple / Interest
            url = 'reportFile/intrest_loan_report/getIntrestBalPrincipalinterest.php';
            tid = 'princ_intrst_table';
            colArr = [16, 18, 19, 20, 21, 22];
            $('#balance_table_div').hide();
            $('#princ_intrst_table_div').show();

        }else{
            alert("Kindly select Report type.");
            return;
        }

        balanceReportTable(url, tid, colArr);
    })
});

$(function(){
    getloancategorylist();
});

function balanceReportTable(url, tid, columnsToSum){
    $('#'+tid).DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var table = $('#'+tid).DataTable({
        ...getStateSaveConfig(tid),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': url,
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
            title: "Balance Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Interest_Balance_Report'); // or any base
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
            searchFunction(tid);
            paginationFunction(tid);
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(table, tid);
}

function getloancategorylist(){
    $.ajax({
        url: 'loancategoryFile/ajaxGetLoanCategory.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            loanCategory.clearStore();
            for (var i = 0; i < response.length; i++) {
                var loan_cat_id = response[i]['loan_category_creation_id'];
                var loan_cat_name = response[i]['loan_category_creation_name'];
                var items = [{
                    value: loan_cat_id,
                    label: loan_cat_name                 
                }]
                loanCategory.setChoices(items);
                loanCategory.init();
            }
        }
    })
}