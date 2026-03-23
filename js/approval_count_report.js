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
        // Load data
        approvalReportCount(from_date, to_date, selected_user);


    });

});

// Load User List
$(function () {
    getUserNames();
});

function getUserNames() {
    $.post('reportFile/due_followup_count_report/getDuefollowupUser.php', { screen : 5 }, function (response) {
         $('#by_user').empty()
        .append("<option value=''>Select User</option>")
        .append("<option value='all'>All</option>");

        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function approvalReportCount(from_date, to_date, selected_user) {

    $.ajax({
        url: 'reportFile/approval_count_report/approvalCountReport.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            user_id: selected_user,
        },
        dataType: 'json',
        success: function (res) {

            // Handle empty response
            if (!res.data || res.data.length === 0) {
                if ($.fn.DataTable.isDataTable('#approval_count_table')) {
                    $('#approval_count_table').DataTable().clear().draw();
                }
                return;
            }

            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // Destroy existing table once
            if ($.fn.DataTable.isDataTable('#approval_count_table')) {
                $('#approval_count_table').DataTable().destroy();
            }

            const columns = [
                /* BASIC */
                { data: 'sno' },
                { data: 'fullname' },
                { data: 'loan_category' },
                /* PREVIOUS IN PROCESS */
                { data: 'previous.new' },
                { data: 'previous.renewal' },
                { data: 'previous.reactive' },
                { data: 'previous.additional' },
                { data: 'previous.existing_new' },
                { data: 'previous.total', render: d => `<b>${d}</b>` },

                /* Approval */
                { data: 'approval.new' },
                { data: 'approval.renewal' },
                { data: 'approval.reactive' },
                { data: 'approval.additional' },
                { data: 'approval.existing_new' },
                { data: 'approval.total', render: d => `<b>${d}</b>` },

                /* CANCEL */
                { data: 'cancel.new' },
                { data: 'cancel.renewal' },
                { data: 'cancel.reactive' },
                { data: 'cancel.additional' },
                { data: 'cancel.existing_new' },
                { data: 'cancel.total', render: d => `<b>${d}</b>` },

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
                { data: 'issued.total', render: d => `<b>${d}</b>` },
                /* STATUS */
                { data: 'status.current' },
                { data: 'status.pending' },
                { data: 'status.od' },
                { data: 'status.error' },
                { data: 'status.legal' },
                { data: 'status.total', render: d => `<b>${d}</b>` }
            ];

            const approval_count_table = $('#approval_count_table').DataTable({
                ...getStateSaveConfig('approval_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Approval_Count_Report',
                        action: function (e, dt, button, config) {
                            const file = curDateJs('Approval_count_table');
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
                    searchFunction('approval_count_table');
                    paginationFunction('approval_count_table');
                }
            });


            // Column visibility helper
            initColVisFeatures(approval_count_table, 'approval_count_table');

            // Footer totals
            $('#approval_count_table tfoot').html(`
                <tr>
                    <td colspan="3"><b>Total</b></td>
                    <td>${totalRow.previous.new}</td>
                    <td>${totalRow.previous.renewal}</td>
                    <td>${totalRow.previous.reactive}</td>
                    <td>${totalRow.previous.additional}</td>
                    <td>${totalRow.previous.existing_new}</td>
                    <td><b>${totalRow.previous.total}</b></td>

                    <td>${totalRow.approval.new}</td>
                    <td>${totalRow.approval.renewal}</td>
                    <td>${totalRow.approval.reactive}</td>
                    <td>${totalRow.approval.additional}</td>
                    <td>${totalRow.approval.existing_new}</td>
                    <td><b>${totalRow.approval.total}</b></td>

                    <td>${totalRow.cancel.new}</td>
                    <td>${totalRow.cancel.renewal}</td>
                    <td>${totalRow.cancel.reactive}</td>
                    <td>${totalRow.cancel.additional}</td>
                    <td>${totalRow.cancel.existing_new}</td>
                    <td><b>${totalRow.cancel.total}</b></td>

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

                    <td>${totalRow.status.current}</td>
                    <td>${totalRow.status.pending}</td>
                    <td>${totalRow.status.od}</td>
                    <td>${totalRow.status.error}</td>
                    <td>${totalRow.status.legal}</td>
                    <td><b>${totalRow.status.total}</b></td>
                </tr>
            `);
        }
    });
}


