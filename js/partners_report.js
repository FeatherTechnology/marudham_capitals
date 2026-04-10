$(document).ready(function () {
    // 🔹 Reset / Show Button Click
    $('#reset_btn').click(function () {

        let to_date = $('#to_date').val();
        if (!to_date) {
            swalError('Please Select Dates!', 'Dates are required.');
            return;
        }
        openingBalanceReport(to_date);
        collectReportCount(to_date);
        issuedReportCount(to_date)
        otherTransReport(to_date) 
        closingBalanceReport(to_date);
        $('#print_btn').show();
    });

   $('#print_btn').click(function () {

    var rowCount = $('#partners_card table tbody tr').length;
    if (rowCount === 0) {
        alert('No data available to print.');
        return;
    }

    var printContents = document.getElementById('partners_card').innerHTML;
    var printWindow = window.open('', '', 'height=900,width=1200');

    printWindow.document.write('<html><head><title>Partners Report</title>');

    printWindow.document.write(`
        <style>
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                display: flex;
                flex-direction: column;
            }

            .card:last-child {
                flex-grow: 1;
            }

            table {
                border-collapse: collapse;
                width: auto !important;          
                max-width: 100%;
                table-layout: auto;             
            }

            .card:last-child {
                margin-bottom: 0;
            }

            table {
                margin-bottom: 10px; /* reduce gap between tables */
            }

            th, td {
                border: 1px solid #000;
                padding: 4px 6px;
                font-size: 16px;
                text-align: center;
                white-space: nowrap;            
            }

            th:nth-child(2),
            td:nth-child(2),
            th:nth-child(3),
            td:nth-child(3) {
                text-align: center;
                white-space: normal;
                min-width: 120px;
            }

            .card {
                border: none;
                page-break-inside: avoid;
            }

            hr {
                margin: 6px 0;
            }
            .card-header {
                font-weight: 800 !important;
                font-size: 16px;
                padding: 6px 4px;
                text-align: left;
                background: none !important;
                border-bottom: 1px solid #000;
            }

            input, button {
                display: none !important;
            }
        </style>
    `);

    printWindow.document.write('</head><body>');

    /* 🔹 HEADER */
    printWindow.document.write(`
        <div style="text-align:center; margin-bottom:6px;">
            <img src="img/logo.png" style="height:100px;">
            <h3 style="margin:4px 0;font-size:20px;">Marudham Capitals</h3>
            <div style="font-weight:bold;font-size:20px;">Partners Report</div>
            <div style="font-weight:bold; margin-top:2px; font-size:20px;">
                Date :  ${$('#to_date').val().split('-').reverse().join('-')}
            </div>
        </div>
        <hr>
    `);

    /* 🔹 CONTENT */
    printWindow.document.write(printContents);

    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
});

});

// Opening Balance 
function openingBalanceReport(to_date) {
    $.ajax({
        url: 'reportFile/partners_report/openingBalanceReport.php',
        type: 'POST',
        data: { to_date: to_date },
        dataType: 'json',
        success: function (res) {
            if (!res.data || res.data.length === 0) {
                $('#opening_table').DataTable().clear().draw();
                $('#opening_table thead').html(
                    "<tr><th colspan='5'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // ✅ USE DATA DIRECTLY (ONLY ONE ROW)
            const tableData = res.data;

            const columns = [
                { data: 'sno',width: "60px", title: "S.No" },
                { data: 'opening_label', title: "Opening Balance" },
                {
                    data: 'hand_cash',
                    title: "Hand Cash",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                {
                    data: 'bank_cash',
                    title: "Bank Cash",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                {
                    data: 'total',
                    title: "Total",
                    render: function (data) {
                    return '<b style="font-weight:900;">' + moneyFormatIndia(data) + '</b>';
                    }
                }
            ];

            if ($.fn.DataTable.isDataTable('#opening_table')) {
                $('#opening_table').DataTable().destroy();
            }

            $('#opening_table').DataTable({
                data: tableData,
                columns: columns,
                lengthChange: false,
                info: false,
                paging: false,
                searching: false,
                buttons: [
                ],
                drawCallback: function () {
                }
            });

        }
    });
}



// Loan Issue
function issuedReportCount(to_date) {

    $.ajax({
        url: 'reportFile/partners_report/issuedReportCount.php',
        type: 'POST',
        data: { to_date: to_date },
        dataType: 'json',
        success: function (res) {
            if (!res.data || res.data.length === 0) {
                $('#issue_table').DataTable().clear().draw();
                $('#issue_table thead').html(
                    "<tr><th colspan='10'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Remove total row from display
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // DataTable Columns
            const columns = [
                { data: 'sno', width: "60px", title: "S.No" },
                { data: 'loan_category', title: "Loan Category" },
                {
                    data: 'today_issued_amount',
                    title: "Today Issued",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                { data: 'today_count', title: "Today Issued Count" },

                {
                    data: 'total_issued_amount',
                    title: "Total Issued",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                { data: 'total_count', title: "Total Count" },

            ];

            $('#issue_table').DataTable().destroy();

            $('#issue_table').DataTable({
                data: tableData,
                columns: columns,
                lengthChange: false,
                info: false,
                paging: false,
                searching: false,
                buttons: [
                ],
            });

            // Footer (Total)
    $('#issue_table tfoot').html(`
        <tr>
            <td></td>
            <td><b>Total</b></td>
            <td><b>${moneyFormatIndia(totalRow.today_issued_amount)}</b></td>
            <td><b>${totalRow.today_count}</b></td>
            <td><b>${moneyFormatIndia(totalRow.total_issued_amount)}</b></td>
            <td><b>${totalRow.total_count}</b></td>
        </tr>
    `);

        }
    });
}

// Collection 
function collectReportCount(to_date) {

    $.ajax({
        url: 'reportFile/partners_report/collectReportCount.php',
        type: 'POST',
        data: { to_date: to_date },
        dataType: 'json',
        success: function (res) {
            if (!res.data || res.data.length === 0) {
                $('#collect_table').DataTable().clear().draw();
                $('#collect_table thead').html(
                    "<tr><th colspan='10'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Extract last row as total
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            const columns = [
                { data: 'sno', width: "60px", title: "S.No" },
                { data: 'loan_category', title: "Loan Category" },
                {
                    data: 'today',
                    title: "Today",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                {
                    data: 'till_now',
                    title: "Till Now",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
            ];

            $('#collect_table').DataTable().destroy();
            $('#collect_table').DataTable({
                data: tableData,
                columns: columns,
                lengthChange: false,
                info: false,
                paging: false,
                searching: false,
                buttons: [
                ],
            });

            // Footer Total Row
            $('#collect_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td><b>${moneyFormatIndia(totalRow.today)}</b></td>
                    <td><b>${moneyFormatIndia(totalRow.till_now)}</b></td>
                </tr>
            `);
        }
    });
}

// Other Transation
function otherTransReport(to_date) {

    $.ajax({
        url: 'reportFile/partners_report/otherTransReport.php',
        type: 'POST',
        data: {to_date: to_date},
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#other_trans_table').DataTable().clear().draw();
                $('#other_trans_table thead').html(
                    "<tr><th colspan='10'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Extract last row as total
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            const columns = [
                { data: 'sno', width: "60px", title: "S.No" },
                { data: 'fullname', title: "Other Transaction" },
                {
                    data: 'credit',
                    title: "Credit",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                {
                    data: 'debit',
                    title: "Debit",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
            ];

            $('#other_trans_table').DataTable().destroy();
            $('#other_trans_table').DataTable({
                data: tableData,
                columns: columns,
                lengthChange: false,
                info: false,
                paging: false,
                searching: false,
                buttons: [
                ],
            });

            // Footer Total Row
            $('#other_trans_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td><b>${moneyFormatIndia(totalRow.credit)}</b></td>
                    <td><b>${moneyFormatIndia(totalRow.debit)}</b></td>
                </tr>
            `);
        }
    });
}
// Closing Balance
function closingBalanceReport(to_date) {

    $.ajax({
        url: 'reportFile/partners_report/closingBalanceReport.php',
        type: 'POST',
        data: { to_date: to_date },
        dataType: 'json',
        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#closing_table').DataTable().clear().draw();
                $('#closing_table thead').html(
                    "<tr><th colspan='5'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // ✅ USE DATA DIRECTLY (ONLY ONE ROW)
            const tableData = res.data;

            const columns = [
                { data: 'sno', width: "60px", title: "S.No" },
                { data: 'closing_label', title: "Closing Balance" },
                {
                    data: 'hand_cash',
                    title: "Hand Cash",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                {
                    data: 'bank_cash',
                    title: "Bank Cash",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                },
                {
                    data: 'total',
                    title: "Total",
                    render: function (data) {
                        return '<b style="font-weight:900;">' + moneyFormatIndia(data) + '</b>';
                    }
                }
            ];

            if ($.fn.DataTable.isDataTable('#closing_table')) {
                $('#closing_table').DataTable().destroy();
            }

            $('#closing_table').DataTable({
                data: tableData,
                columns: columns,
                lengthChange: false,
                info: false,
                paging: false,
                searching: false,
                buttons: [
                ],
            });

        }
    });
}