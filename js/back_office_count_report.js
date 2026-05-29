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
        $('#back_office_count_table').DataTable().destroy();
        $('#back_office_count_table tbody').empty();
        
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
        backOfficeCount(from_date, to_date, selectedType, user_type, selected_user, selectedVal);
    });

    $('#unpaid_btn').off('click').on('click', function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let req_ids = $(this).data('req_ids');

        if (!req_ids || req_ids.length === 0) {
            alert("No unpaid data");
            return;
        }

        unPaidInfo(req_ids, from_date, to_date);
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

// Back Office Count
function backOfficeCount(from_date, to_date, selectedType, user_type, user_id, selectedVal) {

    $.ajax({
        url: 'reportFile/back_office_count_report/backOfficeCount.php',
        type: 'POST',
        data: { from_date, to_date, selectedType, user_type, user_id, selectedVal },
        dataType: 'json',
        success: function (res) {

            // Handle empty response
            if (!res.data || res.data.length === 0) {
                if ($.fn.DataTable.isDataTable('#back_office_count_table')) {
                    $('#back_office_count_table').DataTable().clear().draw();
                }
                return;
            }

            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);
            // ✅ STEP 1: Collect ALL unpaid req_ids
            let all_req_ids = [];

            tableData.forEach(row => {

                if (row.to_follow_unpaid_req_ids && row.to_follow_unpaid_req_ids.length > 0) {
                    all_req_ids = all_req_ids.concat(row.to_follow_unpaid_req_ids);
                }

                if (row.followed_unpaid_req_ids && row.followed_unpaid_req_ids.length > 0) {
                    all_req_ids = all_req_ids.concat(row.followed_unpaid_req_ids);
                }

            });

            // Remove duplicates
            all_req_ids = [...new Set(all_req_ids)];

            // ✅ STEP 2: Show / Hide button
            if (all_req_ids.length > 0) {
                $('#unpaid_btn').show();

                // Store in button (VERY IMPORTANT)
                $('#unpaid_btn').data('req_ids', all_req_ids);
            } else {
                $('#unpaid_btn').hide();
            }

            // Destroy existing table once
            if ($.fn.DataTable.isDataTable('#back_office_count_table')) {
                $('#back_office_count_table').DataTable().destroy();
            }

            const columns = [
                /* BASIC COLUMNS - MATCH PHP OUTPUT */
                { data: 'sno' },
                { data: 'fullname' },
                { data: 'total_count' },        // Total Count
                { data: 'payable_zero' },       // Payable Zero
                { data: 'responsible_zero' },   // Responsible (Zero count)
                { data: 'balance_count' },      // Balance Count
                // To Follow (colspan=2)
                { data: 'to_follow_paid' },     // Paid
                { data: 'to_follow_unpaid' },   // Unpaid

                // Followed (colspan=2)  
                { data: 'followed_paid' },      // Paid
                { data: 'followed_unpaid' },    // Unpaid

                /* MOBILE (6 cols) */
                { data: 'mobile_commitment_paid' },      // Commitment Paid
                { data: 'mobile_commitment_unpaid' },    // Commitment Unpaid  
                { data: 'mobile_unavailable_paid' },     // Unavailable Paid
                { data: 'mobile_unavailable_unpaid' },   // Unavailable Unpaid
                { data: 'mobile_paid' },                 // Paid (fstatus=8)
                { data: 'mobile_total' },                // Total

                /* DIRECT (6 cols) */
                { data: 'direct_commitment_paid' },      // Commitment Paid
                { data: 'direct_commitment_unpaid' },    // Commitment Unpaid
                { data: 'direct_unavailable_paid' },     // Unavailable Paid
                { data: 'direct_unavailable_unpaid' },   // Unavailable Unpaid
                { data: 'direct_paid' },                 // Paid (fstatus=8)
                { data: 'direct_total' }
            ];
            const back_office_count_table = $('#back_office_count_table').DataTable({
                ...getStateSaveConfig('back_office_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Back_Office_Count_Report',
                        action: function (e, dt, button, config) {
                            const file = curDateJs('Back_Office_Count_Report');
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
                    searchFunction('back_office_count_table');
                    paginationFunction('back_office_count_table');
                }
            });


            // Column visibility helper
            initColVisFeatures(back_office_count_table, 'back_office_count_table');

        }
    });
}

function unPaidInfo(req_ids, from_date, to_date) {

    if (!req_ids || req_ids.length === 0) {
        alert('No unpaid records found!');
        return;
    }

    $.ajax({
        url: "reportFile/back_office_count_report/unpaid_info.php",
        type: "POST",
        data: {
            unpaid_req_ids: req_ids.join(','), // ✅ convert array to string
            from_date: from_date,
            to_date: to_date
        },
        cache: false,
        success: function (html) {
            $("#updatedFamTable").html(html);
            $('.unpaidModal').modal('show');
        },
        error: function () {
            alert('Error loading unpaid info');
        }
    });
}

function closeModal() {
 $('.unpaidModal').modal('hide');
}
 
function resetAllTables() {
    $("#back_office_count_table thead").show();
    $("#back_office_count_table tbody").show();
    $("#back_office_count_table tfoot").show();

    $("th, td").show(); // reset any hidden columns
}