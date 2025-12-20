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


        if (!from_date || !to_date || !selected_user) {
            swalError('Please Select All Fields!', 'All fields are required.');
            return;
        }
        confirmationReportCount(from_date, to_date, selected_user)

    });

});
// Load User List
$(function () {
    getUserNames();
});

function getUserNames() {
    $.post('reportFile/customer_status_report/getAllUserList.php', { user_track: 2 }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function confirmationReportCount(from_date, to_date, selected_user) {

    $.ajax({
        url: 'reportFile/confirmation_count_report/getConfirmationCount.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            user_id: selected_user,
        },
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#confirmation_count_table').DataTable().clear().draw();
                $('#confirmation_count_table thead').html(
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
                { data: 'line', title: "Line Name" },
                { data: 'total_count', title: "Total Count" },
                { data: 't_completed_count', title: "Completed" },
                { data: 't_unavailable_count', title: "Unavailable" },
                { data: 't_reconfirmation', title: "Reconfirmation" },
            ];

            $('#confirmation_count_table').DataTable().destroy();

            var confirmation_count_table = $('#confirmation_count_table').DataTable({
                ...getStateSaveConfig('confirmation_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Confirmation_Count_Report',
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var file = curDateJs('Confirmation_Count_Report');
                            config.title = file;
                            config.filename = file;
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    { extend: 'colvis', collectionLayout: 'fixed four-column' }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('confirmation_count_table');
                    paginationFunction('confirmation_count_table');
                }
            });

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(confirmation_count_table, 'confirmation_count_table');

            // =============================
            // 🔥 SET FOOTER (TOTAL VALUES)
            // =============================
            $('#confirmation_count_table tfoot').html(`
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Total</b></td>
                    <td><b>${totalRow.total_count}</b></td>
                    <td><b>${totalRow.t_completed_count}</b></td>
                    <td><b>${totalRow.t_unavailable_count}</b></td>
                    <td><b>${totalRow.t_reconfirmation}</b></td>
                </tr>
            `);

            // Hide revoke column if required
            if (screen == "3") {
                $("th:nth-child(6)").hide();
                $("#confirmation_count_table tbody tr").each(function () {
                    $(this).find("td:nth-child(6)").hide();
                });
                $("#confirmation_count_table tfoot tr td:nth-child(6)").hide();
            }
        }
    });
}

function swalError(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#009688',
    });
}
