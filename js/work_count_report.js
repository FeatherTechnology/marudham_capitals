$(document).ready(function () {

    // 🔹 Date validation
    $('#from_date').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

        if (toDate && fromDate > toDate) {
            $('#to_date').val('');
        }
    });

    // 🔹 Reset / Show Button Click
    $('#reset_btn').click(function () {

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let selected_user = $('#by_user').val();
        let screen = $('#screen').val();

        if (!from_date || !to_date || !selected_user || !screen) {
            swalError('Please Select All Fields!', 'All fields are required.');
            return;
        }
         resetAllTables()
       // SET HEADER BASED ON SCREEN
      
        let headerText = "";
        let headerName = "";

        if (screen == "1") {
            headerName = "Request";
            headerText = "Request Count Report";
        } else if (screen == "2") {
            headerName = "Verification";
            headerText = "Verification Count Report";
        } else if (screen == "3") {
            headerName = "Approval";
            headerText = "Approval Count Report";
        } else if (screen == "4") {
            headerText = "Issued Count Report";
        } else if (screen == "5") {
            headerText = "Collection Count Report";
        }


        $('.card-header').text(headerText);


        // Refresh table
        $('#request_count_table').hide();
        $('#issue_count_table').hide();
        $('#collection_count_table').hide();
        $('.dataTables_wrapper').hide();
        if (screen == "1" || screen == "2" || screen == '3') {
            // ==========================================
            // 🔥 SHOW / HIDE THE REVOKE COLUMN (screen 3)
            // ==========================================
            if (screen == "3") {
                // hide Revoke column (6th column)
                $("th:nth-child(6)").hide();
                $("#request_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").hide();
                });
            } else {
                $("th:nth-child(6)").show();
                $("#request_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").show();
                });
            }
            $('#request_count_table').show();
            $('#request_count_wrapper').show();

            // Load data
            requestToIssuedReportCount(from_date, to_date, selected_user, screen, headerName);
        } else if (screen == "4") {
            $('#issue_count_table').show();
            $('#issue_count_wrapper').show();
            issuedReportCount(from_date, to_date, selected_user, screen, headerName);
        } else if (screen == "5") {
            $('#collection_count_table').show();
            $('#collection_count_wrapper').show();
            collectionReportCount(from_date, to_date, selected_user, screen);
        }

    });

});

// Load User List
$(function () {
    getUserNames();
});

function getUserNames() {
    $.post('reportFile/customer_status_report/getAllUserList.php', { user_track: 1 }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function requestToIssuedReportCount(from_date, to_date, selected_user, screen, headerName) {

    $.ajax({
        url: 'reportFile/work_count_report/requestToIssuedReportCount.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            user_id: selected_user,
            screen: screen
        },
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#request_count_table').DataTable().clear().draw();
                $('#request_count_table thead').html(
                    "<tr><th colspan='10'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Remove total row for body
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // DataTable Columns
            const columns = [
                { data: 'sno', title: "S.No" },
                { data: 'fullname', title: "User Name" },
                { data: 'loan_category', title: "Loan Category" },
                { data: 'total_count', title: "Request" },
                { data: 't_cancel_count', title: "Cancel" },
                { data: 't_revoke_count', title: "Revoke" },
                { data: 't_process', title: "Process" },
                { data: 't_issued', title: "Issued" }
            ];

            $('#request_count_table').DataTable().destroy();

            $('#request_count_table').DataTable({
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Request_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('Request_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('request_count_table');
                    paginationFunction('request_count_table');
                }
            });
            // Update the column header AFTER DataTable builds the table
            $('#request_count_table thead th#nameHeader').text(headerName);


            // =============================
            // 🔥 SET FOOTER (TOTAL VALUES)
            // =============================
            $('#request_count_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td><b>${totalRow.total_count}</b></td>
                    <td><b>${totalRow.t_cancel_count}</b></td>
                    <td><b>${totalRow.t_revoke_count}</b></td>
                    <td><b>${totalRow.t_process}</b></td>
                    <td><b>${totalRow.t_issued}</b></td>
                </tr>
            `);

            // Hide revoke column if required
            if (screen == "3") {
                $("th:nth-child(6)").hide();
                $("#request_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").hide();
                });
                $("#request_count_table tfoot tr td:nth-child(6)").hide();
            }
        }
    });
}

function issuedReportCount(from_date, to_date, selected_user, screen, headerName) {

    $.ajax({
        url: 'reportFile/work_count_report/issuedReportCount.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            user_id: selected_user,
            screen: screen
        },
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#issue_count_table').DataTable().clear().draw();
                $('#issue_count_table thead').html(
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
                { data: 'fullname', title: "User Name" },
                { data: 'loan_category', title: "Loan Category" },
                { data: 'agent_name', title: "Agent Name" },   // 🔥 added
                { data: 'total_count', title: "Issued Count" },
                {
                    data: 'issued_amount',
                    title: "Issued Amount",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                }
            ];


            $('#issue_count_table').DataTable().destroy();

            $('#issue_count_table').DataTable({
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Issued_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('Issued_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('issue_count_table');
                    paginationFunction('issue_count_table');
                }
            });
            // Footer (Total)
            $('#issue_count_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td><b>${totalRow.total_count}</b></td>
                    <td><b>${moneyFormatIndia(totalRow.issued_amount)}</b></td>
                </tr>
            `);
        }
    });
}


function collectionReportCount(from_date, to_date, selected_user, screen) {

    $.ajax({
        url: 'reportFile/work_count_report/collectionReportCount.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            user_id: selected_user,
            screen: screen
        },
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#collection_count_table').DataTable().clear().draw();
                $('#collection_count_table thead').html(
                    "<tr><th colspan='10'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Extract last row as total
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            const columns = [
                { data: 'sno', title: "S.No" },
                { data: 'fullname', title: "User Name" },
                { data: 'loan_category', title: "Loan Category" },
                { data: 'status', title: "Status" },
                { data: 'total_bill', title: "Total Bill" },
                {
                    data: 'total_amount',
                    title: "Total Amount",
                    render: function (data) {
                        return moneyFormatIndia(data);
                    }
                }
            ];

            $('#collection_count_table').DataTable().destroy();

            $('#collection_count_table').DataTable({
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Collection_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('Collection_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                drawCallback: function () {
                    searchFunction('collection_count_table');
                    paginationFunction('collection_count_table');
                }
            });

            // Footer Total Row
            $('#collection_count_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td><b>${totalRow.total_bill}</b></td>
                    <td><b>${moneyFormatIndia(totalRow.total_amount)}</b></td>
                </tr>
            `);
        }
    });
}

function resetAllTables() {
    $("#request_count_table thead").show();
    $("#request_count_table tbody").show();
    $("#request_count_table tfoot").show();

    $("#issue_count_table thead").show();
    $("#issue_count_table tbody").show();
    $("#issue_count_table tfoot").show();

    $("#collection_count_table thead").show();
    $("#collection_count_table tbody").show();
    $("#collection_count_table tfoot").show();

    $("th, td").show(); // reset any hidden columns
}



function swalError(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#009688',
    });
}
