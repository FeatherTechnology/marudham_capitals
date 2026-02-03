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
                { data: 'sno', title: "S.No" },
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
                        return moneyFormatIndia(data);
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
                { data: 'sno', title: "S.No" },
                { data: 'fullname', title: "Loan Issue" },
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
            <td></td>
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
                { data: 'sno', title: "S.No" },
                { data: 'fullname', title: "Collection" },
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
                    <td></td>
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
                { data: 'sno', title: "S.No" },
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
                { data: 'sno', title: "S.No" },
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
                        return moneyFormatIndia(data);
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