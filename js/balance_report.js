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

        if (reportType == '1') {//Balance
            url = 'reportFile/balance/getBalanceReport.php';
            tid = 'balance_report_table';
            colArr = [14, 15, 17, 18, 20, 21];
            $('#balance_table_div').show();
            $('#princ_intrst_table_div').hide();

        } else if (reportType == '2') { //Priciple / Interest
            url = 'reportFile/principal_interest/getBalPrincipalinterest.php';
            tid = 'princ_intrst_table';
            colArr = [14, 15, 17, 18, 19, 20, 22, 23];
            $('#balance_table_div').hide();
            $('#princ_intrst_table_div').show();

        } else {
            alert("Kindly select Report type.");
            return;
        }

        balanceReportTable(url, tid, colArr);
    })
    $('#download_btn').click(function () {
        const to_date = $('#to_date').val();
        const reportType = $('#report_type').val();
        let url = '', tableId = '', reportName = '';
        if (!to_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Dates',
                text: 'Please select To dates before downloading.',
                confirmButtonColor: '#009688'
            });
            return;
        }
        if (reportType === '1') { // Balance Report
            url = 'reportFile/balance/getBalanceReport.php';
            tableId = "balance_report_table"; // your table id
            reportName = "Balance_Report";
        } else if (reportType === '2') { // Principal / Interest Report
            url = 'reportFile/principal_interest/getBalPrincipalinterest.php';
            tableId = "princ_intrst_table"; // your table id
            reportName = "Principal_Interest_Report";
        } else {
            alert("Kindly select Report type.");
            return;
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                to_date: to_date,
                download: 1
            },
            success: function (response) {
                if (response && response.data && response.data.length > 0) {
                    exportToExcel(tableId, response.data, reportName);
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Data Found',
                        text: 'No records found for the selected date range.',
                        confirmButtonColor: '#009688'
                    });
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Response Error',
                    text: 'The server returned invalid data. Please check PHP output.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });

});

$(function () {
    getloancategorylist();
});

function balanceReportTable(url, tid, columnsToSum) {
    $('#' + tid).DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var table = $('#' + tid).DataTable({
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
                var dynamic = curDateJs('Balance_Report'); // or any base
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
            // var columnsToSum = [13, 14, 16, 17, 19, 20];

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
            searchFunction(tid);
            paginationFunction(tid);
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(table, 'tid');
}
function getloancategorylist() {
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