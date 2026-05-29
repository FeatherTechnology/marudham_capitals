const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select Zone',
    allowHTML: true
});

$('#map_name').closest('.choices').hide();

$(document).ready(function () {
    
    $('#type').change(function (e) {
        let type = $(this).val();
        $('#user_type, #by_user').val('').show();
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        $('#due_followup_customer_count_report_table').DataTable().destroy();
        $('#due_followup_customer_count_report_table tbody').empty();
        
        if(type == '4') { //Zone - Followup
            $('#map_name').closest('.choices').show();
            
        } else if(type == '0'){
            $('#user_type, #by_user').hide();
        }
    });

    $('#user_type').change(function () {
        let userType = $('#user_type').val();
        $('#by_user').empty().append("<option value=''>Select User</option>");  

        if(userType != ''){
            getUserNames();
        }
    });
    
    $('#by_user').change(function(){
        let userId = $(this).val();
        let typeVal = $('#type').val();

        if(typeVal != '1' && userId !=''){ //if type user then no need to show mapping.
            $.post('reportFile/promotion_activity/getUserMappedDetails.php', {userId, typeVal}, function (response) {

                map_name.clearStore();

                const items = response.map(row => ({
                    value: row.ids,
                    label: row.map_name
                }));

                map_name.setChoices(items);

            },'json');
        }
    });

    $('#reset_btn').click(function () {
        dueFollowUpCustomerCountReportTable();
    });
});

function getUserNames() {
    let user_type = $('#user_type').val();

    $.post('reportFile/due_followup_customer_count/user_list.php', { user_type: user_type }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['user_id'] + "'>" + val['fullname'] + "</option>");
        });
    }, 'json');
}

function dueFollowUpCustomerCountReportTable() {
    let to_date = $('#to_date').val();
    let selectedType = $('#type').val();
    let user_type = $('#user_type').val();
    let selected_user = $('#by_user').val();

    let selectedVal = '';

    if(selectedType == '1'){ //user
        selectedVal = '1'; //dummy
        
    } else if(selectedType == '4'){ //Zone - Followup
        selectedVal = $('#map_name').val();
    }

    if(!to_date || !selectedVal || !user_type || !selected_user){
        swalError('Warning', `All Fields are required.`);
        return;
    } 

    // if (!to_date) {
    //     swalError('Please Select Date!', 'To Date is required.');
    //     return;
    // }

    // if (!user_type) {
    //     swalError('Please Select User Type!', 'User Type selection is required.');
    //     return;
    // }

    // if (!selected_user) {
    //     swalError('Please Select User!', 'User selection is required.');
    //     return;
    // }

    $.ajax({
        url: 'reportFile/due_followup_customer_count/dueFollowupCustomerCountReport.php',
        method: 'POST',
        data: {
            user_id: selected_user,
            to_date: to_date,
            selectedType: selectedType,
            selectedVal: selectedVal
        },
        success: function (res) {
            const parsed = JSON.parse(res);

            if (!parsed.data || parsed.data.length === 0) {
                $('#due_followup_customer_count_report_table thead').html("<tr><th colspan='13'>No data found for the selected filters</th></tr>");
                $('#due_followup_customer_count_report_table').DataTable().clear().draw();
                return;
            }

            // Extract total row (last row)
            const data = parsed.data;
            const totalRow = data[data.length - 1];
            if (totalRow.fullname === 'Total') {
                data.pop(); // Remove from table body
            }

            // Define columns
            const columns = [
                { data: 'sno' },
                { data: 'fullname' },
                { data: 'loan_category' },
                { data: 't_current_count' },
                { data: 'payable_zero' },
                { data: 'responsible_zero' },
                { data: 'balance_count' },
                { data: 'paid' },
                { data: 'partially_paid' },
                { data: 'total_paid' },
                {
                    data: 'paid_percentage',
                    render: function (data) {
                        return data + ' %';
                    }
                },
                { data: 'unpaid' },
                {
                    data: 'unpaid_percentage',
                    render: function (data) {
                        return data + ' %';
                    }
                }
            ];

            // Rebuild table
            $('#due_followup_customer_count_report_table').DataTable().destroy();
            const due_followup_customer_count_report_table = $('#due_followup_customer_count_report_table').DataTable({
                ...getStateSaveConfig('due_followup_customer_count_report_table'),
                data: data,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excel',
                    title: "Back Office Summary",
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Back_Office_summary'); // or any base
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
                    searchFunction('due_followup_customer_count_report_table');
                    paginationFunction('due_followup_customer_count_report_table');
                }
            });

            // Pass the table variable to the initColVisFeatures function
            initColVisFeatures(due_followup_customer_count_report_table, 'due_followup_customer_count_report_table');

            // Render footer
            let footerHtml = `<tr>
        <td></td>
        <td><b>Total</b></td>
        <td></td>
        <td><b>${totalRow.t_current_count}</b></td>
        <td><b>${totalRow.payable_zero}</b></td>
        <td><b>${totalRow.responsible_zero}</b></td>
        <td><b>${totalRow.balance_count}</b></td>
        <td><b>${totalRow.paid}</b></td>
        <td><b>${totalRow.partially_paid}</b></td>
        <td><b>${totalRow.total_paid}</b></td>
        <td><b>${totalRow.paid_percentage} %</b></td>
        <td><b>${totalRow.unpaid}</b></td>
        <td><b>${totalRow.unpaid_percentage} %</b></td>
    </tr>`;

            $('#due_followup_customer_count_report_table tfoot').html(footerHtml);
        }

    });
}