// Remove User based loan category 
// show the user who has collection and due followup Access
const loanCategory = new Choices('#loan_category', {
    removeItemButton: true,
    noChoicesText: 'Select Category',
    allowHTML: true
});
const line = new Choices('#line', {
    removeItemButton: true,
    noChoicesText: 'Select Line',
    allowHTML: true
});
const group_map = new Choices('#group_map', {
    removeItemButton: true,
    noChoicesText: 'Select Group',
    allowHTML: true
});
const due_followup = new Choices('#due_followup', {
    removeItemButton: true,
    noChoicesText: 'Select Due Followup',
    allowHTML: true
});

$('#line, #group_map, #due_followup').closest('.choices').hide();

$(document).ready(function () {

    $('#type').change(function () {
        let type = $(this).val();
        $('#line, #group_map, #due_followup').closest('.choices').hide();
        $('#by_user').hide()
        $('#by_user').val('')
        $('#pending_table').hide().find('tbody').empty();
        $('#current_table').hide().find('tbody').empty();
        $('#od_table').hide().find('tbody').empty();
        // Reset header text
        $('.card-header').text('Customer Status Report');
        // If DataTables is used, destroy previous instances to avoid duplicates
        if ($.fn.DataTable.isDataTable('#pending_table')) {
            $('#pending_table').DataTable().clear().destroy();
        }
        if ($.fn.DataTable.isDataTable('#current_table')) {
            $('#current_table').DataTable().clear().destroy();
        }
        if ($.fn.DataTable.isDataTable('#od_table')) {
            $('#od_table').DataTable().clear().destroy();
        }
        loanCategory.clearStore();
        if (type == '1') {
            document.querySelector('#line').closest('.choices').style.display = 'block';
            $('#group_map, #due_followup').closest('.choices').hide();
            $('#by_user').hide()
            getline()
            getUserLoanCategories('');
        } else if (type == '2') {
            $('#line, #group_map, #due_followup').closest('.choices').hide();
            $('#by_user').show()
            getUserNames();
            getUserLoanCategories('');
        } else if (type == '3') {
            $('#group_map').closest('.choices').show();
            $('#line,#due_followup').closest('.choices').hide();
            $('#by_user').hide()
            getGroup()
            getUserLoanCategories('');
        } else if (type == '4') {
            $('#due_followup').closest('.choices').show();
            $('#line, #group_map').closest('.choices').hide();
            $('#by_user').hide()
            getDueFollowup()
            getUserLoanCategories('');
        }
    })
    // $('#by_user').change(function () {
    //     let user_id = $(this).val();
    //     // Reset header text
    //     $('.card-header').text('Customer Status Report');

    //     // Hide all tables and wrappers
    //     $('#pending_table').hide().find('tbody').empty();
    //     $('#current_table').hide().find('tbody').empty();
    //     $('#od_table').hide().find('tbody').empty();

    //     // If DataTables is used, destroy previous instances to avoid duplicates
    //     if ($.fn.DataTable.isDataTable('#pending_table')) {
    //         $('#pending_table').DataTable().clear().destroy();
    //     }
    //     if ($.fn.DataTable.isDataTable('#current_table')) {
    //         $('#current_table').DataTable().clear().destroy();
    //     }
    //     if ($.fn.DataTable.isDataTable('#od_table')) {
    //         $('#od_table').DataTable().clear().destroy();
    //     }
    //     if (user_id) {
    //         getUserLoanCategories(user_id);
    //     } else {
    //         loanCategory.clearStore(); // clear if no user selected
    //     }
    // });

    // $('#due_followup').on('change', function () {
    //     let followup_id = $(this).val();
    //     getUserLoanCategories(null, followup_id); // user_id not needed for type=4
    // });

    $('#reset_btn').click(function () {
        let search_date = $('#search_date').val();
        let type = $('#type').val();
        let line = $('#line').val();
        let selected_user = $('#by_user').val();
        let group_map = $('#group_map').val();
        let due_followup = $('#due_followup').val();
        let loan_category = $('#loan_category').val();
        let sub_status_type = $('#sub_status_type').val();

        if (!search_date || !type || !loan_category || !sub_status_type) {
            swalError('Please Select All Fields!', 'All fields are required.');
            return;
        }

        if (
            (type == 1 && !line) ||
            (type == 2 && !selected_user) || (type == 3 && !group_map) || (type == 4 && !due_followup)
        ) {
            swalError('Please Select All Fields!', 'All fields are required.');
            return;
        }

        if (type == "1") {
            $("#nameHeader").text("Line Name");
        } else if (type == "2") {
            $("#nameHeader").text("User Name");
        } else if (type == "3") {
            $("#nameHeader").text(" Group Name");
        } else if (type == "4") {
            $("#nameHeader").text("Due Followup Name");
        }
        $('#current_table').hide();
        $('#pending_table').hide();
        $('#od_table').hide();
        $('.dataTables_wrapper').hide();
        if (sub_status_type == '1') {
            $('.card-header').text('Current Report');
            $('#current_table').show();
            currentReportCount(search_date, type, line, selected_user, group_map, due_followup, loan_category, sub_status_type);
            $('#current_table_wrapper').show();
        }
        else if (sub_status_type == '2') {
            $('.card-header').text('Pending Report');
            $('#pending_table').show();
            pendingReportCount(search_date, type, line, selected_user, group_map, due_followup, loan_category, sub_status_type);
            $('#pending_table_wrapper').show();
        } else if (sub_status_type == '3') {
            $('.card-header').text('OD Report');
            $('#od_table').show();
            $('#od_table_wrapper').show();
            odReportCount(search_date, type, line, selected_user, group_map, due_followup, loan_category, sub_status_type);

        }

    });
});


function getUserNames() {
    $.post('reportFile/customer_status_report/getAllUserList.php', function (response) {
        $('#by_user').empty();
        $('#by_user').append("<option value=''>Select User</option>");
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['user_id'] + "'>" + val['username'] + "</option>");
        });
    }, 'json');
}
// function getUserLoanCategories(user_id, followup_id = null)
function getUserLoanCategories() {
    // let type = $("#type").val();

    $.ajax({
        url: 'reportFile/customer_status_report/ajaxGetUserLoanCategory.php',
        type: 'POST',
        // data: {
        //     user_id: user_id,
        //     type: type,
        //     followup_id: followup_id // only used when type=4
        // },
        dataType: 'json',
        success: function (response) {
            loanCategory.clearStore();
            let items = [];
            for (let i = 0; i < response.length; i++) {
                items.push({
                    value: response[i]['loan_category_creation_id'],
                    label: response[i]['loan_category_creation_name']
                });
            }
            loanCategory.setChoices(items);
        }
    });
}


function getline() {
    $.ajax({
        url: 'reportFile/customer_status_report/ajaxGetLine.php', // new file for line data
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            line.clearStore(); // clear old list
            let items = [];

            for (let i = 0; i < response.length; i++) {
                items.push({
                    value: response[i]['line_ids'], // store multiple IDs
                    label: response[i]['line_name'] // show only name
                });
            }

            line.setChoices(items);
        }
    });
}
function getGroup() {
    $.ajax({
        url: 'reportFile/customer_status_report/ajaxGetGroup.php',
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            group_map.clearStore(); // clear old list
            let items = [];

            for (let i = 0; i < response.length; i++) {
                items.push({
                    value: response[i]['group_ids'], // store multiple IDs
                    label: response[i]['group_name'] // show only name
                });
            }

            group_map.setChoices(items);
        }
    });
}
function getDueFollowup() {
    $.ajax({
        url: 'reportFile/customer_status_report/ajaxGetdueFollowup.php',
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            due_followup.clearStore(); // clear old list
            let items = [];

            for (let i = 0; i < response.length; i++) {
                items.push({
                    value: response[i]['followup_ids'], // store multiple IDs
                    label: response[i]['duefollowup_name'] // show only name
                });
            }

            due_followup.setChoices(items);
        }
    });
}
function currentReportCount(search_date, type, line, selected_user, group_map, due_followup, loan_category, sub_status_type) {
    $.ajax({
        url: 'reportFile/customer_status_report/CurrentCustomerCountReport.php',
        method: 'POST',
        data: {
            search_date: search_date,
            type: type,
            line: line,
            user_id: selected_user,
            group_map: group_map,
            due_followup: due_followup,
            loan_category: loan_category,
            sub_status_type: sub_status_type
        },
        success: function (res) {
            const parsed = JSON.parse(res);

            if (!parsed.data || parsed.data.length === 0) {
                $('#current_table thead').html("<tr><th colspan='10'>No data found for the selected filters</th></tr>");
                $('#current_table').DataTable().clear().draw();
                return;
            }

            const data = parsed.data;
            const totalRow = data[data.length - 1];
            if (totalRow.fullname === 'Total') {
                data.pop();
            }

            const columns = [
                { data: 'sno' },
                { data: 'date' },
                { data: 'fullname' },
                { data: 'loan_category' },
                { data: 'total_count' },
                { data: 't_current_count' },
                { data: 'payable_zero' },
                { data: 'responsible_zero' },
                { data: 'balance_count' },
                { data: 'paid' },
                { data: 'partially_paid' },
                { data: 'totals_paid' },
                {
                    data: 'paid_percentage',
                    render: function (data) {
                        return Number(data).toFixed(1) + ' %';
                    }
                },
                { data: 'unpaid' },
                {
                    data: 'unpaid_percentage',
                    render: function (data) {
                        return Number(data).toFixed(1) + ' %';
                    }
                },
                { data: 'from_pending' }
            ];

            $('#current_table').DataTable().destroy();
            var current_table = $('#current_table').DataTable({
                ...getStateSaveConfig('current_table'),
                data: data,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excel',
                    title: "Current Customer Count Report",
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Current_Customer_Count_Report'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column'
                }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('current_table');
                    paginationFunction('current_table');
                }
            });

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(current_table, 'current_table');

            let CurrentPaidPercent = totalRow.balance_count > 0 ? (totalRow.totals_paid / totalRow.balance_count) * 100 : 0;
            let CurrentUnpaidPercent = totalRow.balance_count > 0 ? (totalRow.unpaid / totalRow.balance_count) * 100 : 0;

            let footerHtml = `<tr>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td></td>
                <td><b>${totalRow.total_count}</b></td>
                <td><b>${totalRow.t_current_count}</b></td>
                <td><b>${totalRow.payable_zero}</b></td>
                <td><b>${totalRow.responsible_zero}</b></td>
                <td><b>${totalRow.balance_count}</b></td>
                <td><b>${totalRow.paid}</b></td>
                <td><b>${totalRow.partially_paid}</b></td>
                <td><b>${totalRow.totals_paid}</b></td>
                <td><b>${CurrentPaidPercent.toFixed(1)} %</b></td>
                <td><b>${totalRow.unpaid}</b></td>
                <td><b>${CurrentUnpaidPercent.toFixed(1)} %</b></td>
                <td><b>${totalRow.from_pending}</b></td>
            </tr>`;

            $('#current_table tfoot').html(footerHtml);
        }
    });
}

function pendingReportCount(search_date, type, line, selected_user, group_map, due_followup, loan_category, sub_status_type) {
    $.ajax({
        url: 'reportFile/customer_status_report/PendingCustomerCountReport.php',
        method: 'POST',
        data: {
            search_date: search_date,
            type: type,
            line: line,
            user_id: selected_user,
            group_map: group_map,
            due_followup: due_followup,
            loan_category: loan_category,
            sub_status_type: sub_status_type
        },
        success: function (res) {
            const parsed = JSON.parse(res);

            if (!parsed.data || parsed.data.length === 0) {
                $('#pending_table thead').html("<tr><th colspan='10'>No data found for the selected filters</th></tr>");
                $('#pending_table').DataTable().clear().draw();
                return;
            }

            const data = parsed.data;
            const totalRow = data[data.length - 1];
            if (totalRow.fullname === 'Total') {
                data.pop();
            }

            const columns = [
                { data: 'sno' },
                { data: 'date' },
                { data: 'fullname' },
                { data: 'loan_category' },
                { data: 'total_count' },
                { data: 't_pending_count' },
                { data: 'today_pending_clear' },
                { data: 't_pending_clear' },
                { data: 'partially_paid' },
                { data: 'total_paid_pending' },
                {
                    data: 'paid_percentage',
                    render: function (data) {
                        return Number(data).toFixed(1) + ' %';
                    }
                },
                { data: 'unpaid' },
                {
                    data: 'unpaid_percentage',
                    render: function (data) {
                        return Number(data).toFixed(1) + ' %';
                    }
                }
            ];

            $('#pending_table').DataTable().destroy();
            $('#pending_table').DataTable({
                data: data,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excel',
                    title: "Pending Customer Count Report",
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Pending_Customer_Count_Report'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column'
                }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('pending_table');
                    paginationFunction('pending_table');
                }
            });

            let PendingPaidPercent = totalRow.t_pending_count > 0 ? (totalRow.total_paid_pending / totalRow.t_pending_count) * 100 : 0;
            let PendingUnpaidPercent = totalRow.t_pending_count > 0 ? (totalRow.unpaid / totalRow.t_pending_count) * 100 : 0;

            let footerHtml = `<tr>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td></td>
                <td><b>${totalRow.total_count}</b></td>
                <td><b>${totalRow.t_pending_count}</b></td>
                <td><b>${totalRow.today_pending_clear}</b></td>
                <td><b>${totalRow.t_pending_clear}</b></td>
                <td><b>${totalRow.partially_paid}</b></td>
                <td><b>${totalRow.total_paid_pending}</b></td>
                <td><b>${PendingPaidPercent.toFixed(1)} %</b></td>
                <td><b>${totalRow.unpaid}</b></td>
                <td><b>${PendingUnpaidPercent.toFixed(1)} %</b></td>
            </tr>`;

            $('#pending_table tfoot').html(footerHtml);
        }
    });
}

function odReportCount(search_date, type, line, selected_user, group_map, due_followup, loan_category, sub_status_type) {
    $.ajax({
        url: 'reportFile/customer_status_report/odCustomerCountReport.php',
        method: 'POST',
        data: {
            search_date: search_date,
            type: type,
            line: line,
            user_id: selected_user,
            group_map: group_map,
            due_followup: due_followup,
            loan_category: loan_category,
            sub_status_type: sub_status_type
        },
        success: function (res) {
            const parsed = JSON.parse(res);

            if (!parsed.data || parsed.data.length === 0) {
                $('#od_table thead').html("<tr><th colspan='10'>No data found for the selected filters</th></tr>");
                $('#od_table').DataTable().clear().draw();
                return;
            }

            const data = parsed.data;
            const totalRow = data[data.length - 1];
            if (totalRow.fullname === 'Total') {
                data.pop();
            }

            const columns = [
                { data: 'sno' },
                { data: 'date' },
                { data: 'fullname' },
                { data: 'loan_category' },
                { data: 'total_count' },
                { data: 't_od_count' },
                { data: 'today_od_clear' },
                { data: 't_od_clear' },
                { data: 'partially_paid' },
                { data: 'total_paid_od' },
                {
                    data: 'paid_percentage',
                    render: function (data) {
                        return Number(data).toFixed(1) + ' %';
                    }
                },
                { data: 'unpaid' },
                {
                    data: 'unpaid_percentage',
                    render: function (data) {
                        return Number(data).toFixed(1) + ' %';
                    }
                }
            ];

            $('#od_table').DataTable().destroy();
            $('#od_table').DataTable({
                data: data,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: "OD Customer Count Report",
                        action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('OD_Customer_Count_Report'); // or any base
                            config.title = dynamic;      // for versions that use title as filename
                            config.filename = dynamic;   // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column'
                    }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                drawCallback: function () {
                    searchFunction('od_table');
                    paginationFunction('od_table');
                }
            });

            let odPaidPercent = totalRow.t_od_count > 0 ? (totalRow.total_paid_od / totalRow.t_od_count) * 100 : 0;
            let odUnpaidPercent = totalRow.t_od_count > 0 ? (totalRow.unpaid / totalRow.t_od_count) * 100 : 0;

            let footerHtml = `<tr>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td></td>
                <td><b>${totalRow.total_count}</b></td>
                <td><b>${totalRow.t_od_count}</b></td>
                <td><b>${totalRow.today_od_clear}</b></td>
                <td><b>${totalRow.t_od_clear}</b></td>
                <td><b>${totalRow.partially_paid}</b></td>
                <td><b>${totalRow.total_paid_od}</b></td>
                <td><b>${odPaidPercent.toFixed(1)} %</b></td>
                <td><b>${totalRow.unpaid}</b></td>
                <td><b>${odUnpaidPercent.toFixed(1)} %</b></td>
            </tr>`;

            $('#od_table tfoot').html(footerHtml);
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
