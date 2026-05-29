const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select',
    allowHTML: true
});

$('#map_name').closest('.choices').hide();

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

    $('#type').change(function (e) {
        let type = $(this).val();
        $('#user_type, #by_user').val('').show();
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        $('#due_followup_count_table').DataTable().destroy();
        $('#due_followup_count_table tbody').empty();
        
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

    // 🔹 Reset / Show Button Click
    $('#reset_btn').click(function () {

        let from_date = $('#from_date').val();
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

        if(!from_date || !to_date || !selectedVal || !user_type || !selected_user){
            swalError('Warning', `All Fields are required.`);
            return;
        }

        resetAllTables()
        dueFollowupCount(from_date, to_date, selectedType, user_type, selected_user, selectedVal);
    });

});

function getUserNames() {
    let user_type = $('#user_type').val();

    $.post('reportFile/due_followup_count_report/getDuefollowupUser.php', { screen: 1, user_type: user_type }, function (response) {
        $('#by_user').empty()
        .append("<option value=''>Select User</option>")
        .append("<option value='all'>All</option>");

        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });

    }, 'json');
}

// Due Followup Count
function dueFollowupCount(from_date, to_date, selectedType, user_type, user_id, selectedVal) {

    $.ajax({
        url: 'reportFile/due_followup_count_report/dueFollowupCount.php',
        type: 'POST',
        data: { from_date, to_date, selectedType, user_type, user_id, selectedVal },
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