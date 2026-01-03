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

    const $screen = $('#screen');

    $('#by_user').on('change', function () {
        const userId = this.value;

        // reset dropdown
        $screen.html('<option value="">Select Screen</option>');

        if (!userId) return;

        $.ajax({
            url: 'reportFile/work_count_report/getUserScreens.php',
            type: 'POST',
            dataType: 'json',
            data: { user_id: userId },
            success(res) {
                if (!Array.isArray(res)) return;

                res.forEach(({ id, name }) => {
                    $screen.append(
                        $('<option>', { value: id, text: name })
                    );
                });
            }
        });
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
        } else if (screen == "6") {
            headerText = "Closed Count Report";
        } else if (screen == "7") {
            headerName = "NOC";
            headerText = "NOC Count Report";
        } else if (screen == "8") {
            headerName = "NOC Handover";
            headerText = "NOC Handover Count Report";
        }

        $('.card-header').text(headerText);

        // Refresh table
        $('#request_count_table').hide();
        $('#verification_count_table').hide();
        $('#issue_count_table').hide();
        $('#collection_count_table').hide();
        $('#closed_count_table').hide();
        $('#noc_count_table').hide();
        $('.dataTables_wrapper').hide();
        if (screen == "1") {
            $('#request_count_table').show();
            $('#request_count_wrapper').show();

            // Load data
            requestToIssuedReportCount(from_date, to_date, selected_user, screen, headerName);
        } if (screen == "2" || screen == '3') {
            // ==========================================
            // 🔥 SHOW / HIDE THE REVOKE COLUMN (screen 3)
            // ==========================================
            if (screen == "3") {
                // hide Revoke column (6th column)
                $("th:nth-child(6)").hide();
                $("#verification_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").hide();
                });
            } else {
                $("th:nth-child(6)").show();
                $("#verification_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").show();
                });
            }
            $('#verification_count_table').show();
            $('#verification_count_wrapper').show();

            // Load data
            verificationToIssuedReportCount(from_date, to_date, selected_user, screen, headerName);
        } else if (screen == "4") {
            $('#issue_count_table').show();
            $('#issue_count_wrapper').show();
            issuedReportCount(from_date, to_date, selected_user, screen);
        } else if (screen == "5") {
            $('#collection_count_table').show();
            $('#collection_count_wrapper').show();
            collectionReportCount(from_date, to_date, selected_user, screen);
        } else if (screen == "6") {
            $('#closed_count_table').show();
            $('#closed_count_wrapper').show();
            closedReportCount(from_date, to_date, selected_user, screen);
        } else if (screen == "7" || screen == "8") {
            $('#noc_count_table').show();
            $('#noc_count_wrapper').show();
            nocReportCount(from_date, to_date, selected_user, screen, headerName);
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

            // Handle empty response
            if (!res.data || res.data.length === 0) {
                if ($.fn.DataTable.isDataTable('#request_count_table')) {
                    $('#request_count_table').DataTable().clear().draw();
                }
                return;
            }

            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // Destroy existing table once
            if ($.fn.DataTable.isDataTable('#request_count_table')) {
                $('#request_count_table').DataTable().destroy();
            }

            const columns = [
                /* BASIC */
                { data: 'sno' },
                { data: 'fullname' },
                { data: 'loan_category' },

                /* REQUEST */
                { data: 'request.new' },
                { data: 'request.renewal' },
                { data: 'request.reactive' },
                { data: 'request.additional' },
                { data: 'request.existing_new' },
                { data: 'request.total', render: d => `<b>${d}</b>` },

                /* CANCEL */
                { data: 'cancel.new' },
                { data: 'cancel.renewal' },
                { data: 'cancel.reactive' },
                { data: 'cancel.additional' },
                { data: 'cancel.existing_new' },
                { data: 'cancel.total', render: d => `<b>${d}</b>` },

                /* REVOKE */
                { data: 'revoke.new' },
                { data: 'revoke.renewal' },
                { data: 'revoke.reactive' },
                { data: 'revoke.additional' },
                { data: 'revoke.existing_new' },
                { data: 'revoke.total', render: d => `<b>${d}</b>` },

                /* PROCESS */
                { data: 'process.new' },
                { data: 'process.renewal' },
                { data: 'process.reactive' },
                { data: 'process.additional' },
                { data: 'process.existing_new' },
                { data: 'process.total', render: d => `<b>${d}</b>` },

                /* ISSUED */
                { data: 'issued.new' },
                { data: 'issued.renewal' },
                { data: 'issued.reactive' },
                { data: 'issued.additional' },
                { data: 'issued.existing_new' },
                { data: 'issued.total', render: d => `<b>${d}</b>` }
            ];

            const request_count_table = $('#request_count_table').DataTable({
                ...getStateSaveConfig('request_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Request_Count_Report',
                        action: function (e, dt, button, config) {
                            const file = curDateJs('Request_count_table');
                            config.title = file;
                            config.filename = file;
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(
                                this, e, dt, button, config
                            );
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

            // Update grouped header text
            $('#request_count_table thead th#nameHeader').text(headerName);

            // Column visibility helper
            initColVisFeatures(request_count_table, 'request_count_table');

            // Footer totals
            $('#request_count_table tfoot').html(`
                <tr>
                    <td colspan="3"><b>Total</b></td>

                    <td>${totalRow.request.new}</td>
                    <td>${totalRow.request.renewal}</td>
                    <td>${totalRow.request.reactive}</td>
                    <td>${totalRow.request.additional}</td>
                    <td>${totalRow.request.existing_new}</td>
                    <td><b>${totalRow.request.total}</b></td>

                    <td>${totalRow.cancel.new}</td>
                    <td>${totalRow.cancel.renewal}</td>
                    <td>${totalRow.cancel.reactive}</td>
                    <td>${totalRow.cancel.additional}</td>
                    <td>${totalRow.cancel.existing_new}</td>
                    <td><b>${totalRow.cancel.total}</b></td>

                    <td>${totalRow.revoke.new}</td>
                    <td>${totalRow.revoke.renewal}</td>
                    <td>${totalRow.revoke.reactive}</td>
                    <td>${totalRow.revoke.additional}</td>
                    <td>${totalRow.revoke.existing_new}</td>
                    <td><b>${totalRow.revoke.total}</b></td>

                    <td>${totalRow.process.new}</td>
                    <td>${totalRow.process.renewal}</td>
                    <td>${totalRow.process.reactive}</td>
                    <td>${totalRow.process.additional}</td>
                    <td>${totalRow.process.existing_new}</td>
                    <td><b>${totalRow.process.total}</b></td>

                    <td>${totalRow.issued.new}</td>
                    <td>${totalRow.issued.renewal}</td>
                    <td>${totalRow.issued.reactive}</td>
                    <td>${totalRow.issued.additional}</td>
                    <td>${totalRow.issued.existing_new}</td>
                    <td><b>${totalRow.issued.total}</b></td>
                </tr>
            `);
        }
    });
}


// Verification To Loan Issue
function verificationToIssuedReportCount(from_date, to_date, selected_user, screen, headerName) {

    $.ajax({
        url: 'reportFile/work_count_report/verificationToIssuedReportCount.php',
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
                $('#verification_count_table').DataTable().clear().draw();
                $('#verification_count_table thead').html(
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

            $('#verification_count_table').DataTable().destroy();

            var verification_count_table = $('#verification_count_table').DataTable({
                ...getStateSaveConfig('verification_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Verification_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('Verification_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('verification_count_table');
                    paginationFunction('verification_count_table');
                }
            });
            // Update the column header AFTER DataTable builds the table
            $('#verification_count_table thead th#nameHeader').text(headerName);

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(verification_count_table, 'verification_count_table');

            // =============================
            // 🔥 SET FOOTER (TOTAL VALUES)
            // =============================
            $('#verification_count_table tfoot').html(`
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
                $("#verification_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").hide();
                });
                $("#verification_count_table tfoot tr td:nth-child(6)").hide();
            }
        }
    });
}



// Loan Issue
function issuedReportCount(from_date, to_date, selected_user, screen) {

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

            var issue_count_table = $('#issue_count_table').DataTable({
                ...getStateSaveConfig('issue_count_table'),
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

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(issue_count_table, 'issue_count_table');

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

// Collection 
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

            var collection_count_table = $('#collection_count_table').DataTable({
                ...getStateSaveConfig('collection_count_table'),
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

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(collection_count_table, 'collection_count_table');

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

// Closed
function closedReportCount(from_date, to_date, selected_user, screen) {

    $.ajax({
        url: 'reportFile/work_count_report/closedReportCount.php',
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
                $('#closed_count_table').DataTable().clear().draw();
                $('#closed_count_table thead').html(
                    "<tr><th colspan='12'>No data found for selected filters</th></tr>"
                );
                return;
            }

            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // DataTable Columns
            const columns = [
                { data: "sno", title: "S.No" },
                { data: "fullname", title: "User Name" },
                { data: "loan_category", title: "Loan Category" },
                { data: "closed", title: "Closed" },

                // Consider levels
                { data: "bronze", title: "Bronze" },
                { data: "silver", title: "Silver" },
                { data: "gold", title: "Gold" },
                { data: "platinum", title: "Platinum" },
                { data: "diamond", title: "Diamond" },

                { data: "total_consider", title: "Total Consider" },
                { data: "waiting", title: "Waiting List" },
                { data: "block", title: "Block List" }
            ];

            $('#closed_count_table').DataTable().destroy();

            var closed_count_table = $('#closed_count_table').DataTable({
                ...getStateSaveConfig('closed_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Closed_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('Closed_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                drawCallback: function () {
                    searchFunction('closed_count_table');
                    paginationFunction('closed_count_table');
                }
            });

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(closed_count_table, 'closed_count_table');

            // Footer Total Row
            $('#closed_count_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td><b>${totalRow.closed}</b></td>

                    <td><b>${totalRow.bronze}</b></td>
                    <td><b>${totalRow.silver}</b></td>
                    <td><b>${totalRow.gold}</b></td>
                    <td><b>${totalRow.platinum}</b></td>
                    <td><b>${totalRow.diamond}</b></td>

                    <td><b>${totalRow.total_consider}</b></td>
                    <td><b>${totalRow.waiting}</b></td>
                    <td><b>${totalRow.block}</b></td>
                </tr>
            `);
        }
    });
}

// NOC
function nocReportCount(from_date, to_date, selected_user, screen, headerName) {

    $.ajax({
        url: 'reportFile/work_count_report/nocReportCount.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            user_id: selected_user,
            screen: screen
        },
        dataType: 'json',

        success: function (res) {

            // No Data Case
            if (!res.data || res.data.length === 0) {
                $('#noc_count_table').DataTable().clear().draw();
                $('#noc_count_table thead').html(
                    "<tr><th colspan='10'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Extract last row as total
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // DATA TABLE COLUMNS
            const columns = [
                { data: 'sno', title: "S.No" },
                { data: 'fullname', title: "User Name" },
                { data: 'loan_category', title: "Loan Category" },
                { data: 'noc_count', title: "NOC" }
            ];

            // Destroy old table
            $('#noc_count_table').DataTable().destroy();

            // Create new table
            var noc_count_table = $('#noc_count_table').DataTable({
                ...getStateSaveConfig('noc_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'NOC_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('NOC_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                drawCallback: function () {
                    searchFunction('noc_count_table');
                    paginationFunction('noc_count_table');
                }
            });
            // Update the column header AFTER DataTable builds the table
            $('#noc_count_table thead th#nameHeader').text(headerName);

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(noc_count_table, 'noc_count_table');

            // FOOTER TOTAL
            $('#noc_count_table tfoot').html(`
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td><b>${totalRow.noc_count}</b></td>
                </tr>
            `);
        }
    });
}


function resetAllTables() {
    $("#request_count_table thead").show();
    $("#request_count_table tbody").show();
    $("#request_count_table tfoot").show();

     $("#verification_count_table thead").show();
    $("#verification_count_table tbody").show();
    $("#verification_count_table tfoot").show();

    $("#issue_count_table thead").show();
    $("#issue_count_table tbody").show();
    $("#issue_count_table tfoot").show();

    $("#collection_count_table thead").show();
    $("#collection_count_table tbody").show();
    $("#collection_count_table tfoot").show();

    $("#closed_count_table thead").show();
    $("#closed_count_table tbody").show();
    $("#closed_count_table tfoot").show();

    $("#noc_count_table thead").show();
    $("#noc_count_table tbody").show();
    $("#noc_count_table tfoot").show();

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
