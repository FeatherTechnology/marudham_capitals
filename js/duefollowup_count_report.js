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
        resetAllTables()
        dueFollowupCount(from_date, to_date, selected_user);
        

    });

});

// Load User List
$(function () {
    getUserNames();
});

function getUserNames() {
    $.post('reportFile/due_followup_count_report/getDuefollowupUser.php', { screen: 1 }, function (response) {

        $('#by_user').empty()
        .append("<option value=''>Select User</option>")
        .append("<option value='all'>All</option>");

        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });

    }, 'json');
}


// Due Followup Count
function dueFollowupCount(from_date, to_date, selected_user) {

    $.ajax({
        url: 'reportFile/due_followup_count_report/dueFollowupCount.php',
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
                if ($.fn.DataTable.isDataTable('#due_followup_count_table')) {
                    $('#due_followup_count_table').DataTable().clear().draw();
                }
                return;
            }

            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // Destroy existing table once
            if ($.fn.DataTable.isDataTable('#due_followup_count_table')) {
                $('#due_followup_count_table').DataTable().destroy();
            }

              const columns = [
                /* BASIC */
                { data: 'sno' },
                { data: 'fullname' },
                { data: 'total_customer'},
                { data: 'total_entries'},

                /* Mobile */
                { data: 'mobile.commitment' },
                { data: 'mobile.unavailable' },
                { data: 'mobile.paid' },
                { data: 'mobile.total', render: d => `<b>${d}</b>` },

                /* Direct */
                { data: 'direct.commitment' },
                { data: 'direct.unavailable' },
                { data: 'direct.paid' },
                { data: 'direct.total', render: d => `<b>${d}</b>` },
            ];
            const due_followup_count_table = $('#due_followup_count_table').DataTable({
                ...getStateSaveConfig('due_followup_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Due_Followup_Count_Report',
                        action: function (e, dt, button, config) {
                            const file = curDateJs('Due_Followup_Count_Report');
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
                    searchFunction('due_followup_count_table');
                    paginationFunction('due_followup_count_table');
                }
            });

           
            // Column visibility helper
            initColVisFeatures(due_followup_count_table, 'due_followup_count_table');
           
        }
    });
}


function resetAllTables() {
    $("#due_followup_count_table thead").show();
    $("#due_followup_count_table tbody").show();
    $("#due_followup_count_table tfoot").show();

    $("th, td").show(); // reset any hidden columns
}




