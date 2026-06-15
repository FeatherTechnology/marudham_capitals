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
        $('#back_office_count_table tfoot').empty();
        $('#unpaid_btn').hide();

        if (type == '4') { //Zone - Followup
            $('#user_type, #by_user').val('').hide();
            $('#map_name').closest('.choices').show();
            getUserMappedDetails(type); //to Mapping details.

        } else if (type == '0') {
            $('#user_type, #by_user').val('').hide();
        }
    });

    $('#user_type').change(function () {
        let userType = $('#user_type').val();
        $('#by_user').empty().append("<option value=''>Select User</option>");

        if (userType != '') {
            getUserNames();
        }
        $('#unpaid_btn').hide();
    });

    // 🔹 Reset / Show Button Click
    $('#reset_btn').click(function () {

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let selectedType = $('#type').val();
        let user_type = $('#user_type').val();
        let selected_user = $('#by_user').val();
        let selectedVal = '';

        if (selectedType == '1') { //user
            selectedVal = '1'; //dummy

        } else if (selectedType == '4') { //Zone - Followup
            selectedVal = $('#map_name').val();
        }

        if (!from_date || !to_date || !selectedVal || (selectedType == '1' && (!user_type || !selected_user))) {
            swalError('Warning', `All Fields are required.`);
            return;
        }

        resetAllTables()
        $('#unpaid_btn').hide();
      
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
    if (selectedType == '4') {
        url_link = 'reportFile/back_office_count_report/backOfficeZoneCount.php';
    } else {
        url_link = 'reportFile/back_office_count_report/backOfficeCount.php';
    }

    $.ajax({
        url: url_link,
        type: 'POST',
        data: {from_date,to_date,selectedType,user_type,user_id,selectedVal },
        dataType: 'json',
        success: function (res) {
            if (!res.data || res.data.length === 0) {
                if ($.fn.DataTable.isDataTable('#back_office_count_table')) {
                    $('#back_office_count_table').DataTable().clear().draw();
                }
                $('#unpaid_btn').hide();
                return;
            }
            // Grand Total Row
            const totalRow = res.data[res.data.length - 1];
            const tableData = (selectedType == '4')
                ? res.data  // Keep Total row for type 4 (show footer)
                : res.data.filter(row => row.sno != 'Total');  // Remove Total row for others (no footer)
            // Exclude Total row for unpaid calculation
            const dataRows = res.data.filter(row => row.sno != 'Total');
            // Collect all unpaid req_ids
            let all_req_ids = [];
            dataRows.forEach(row => {
                if (Array.isArray(row.to_follow_unpaid_req_ids)) {
                    all_req_ids = all_req_ids.concat(row.to_follow_unpaid_req_ids);
                }
                if (Array.isArray(row.followed_unpaid_req_ids)) {
                    all_req_ids = all_req_ids.concat(row.followed_unpaid_req_ids);
                }
            });
            // Remove duplicate req ids
            all_req_ids = [...new Set(all_req_ids)];
            // Show / Hide unpaid button
            if (all_req_ids.length > 0) {
                $('#unpaid_btn').show();
                $('#unpaid_btn').data('req_ids', all_req_ids);
            } else {
                $('#unpaid_btn').hide();
            }
            // Destroy existing table
            if ($.fn.DataTable.isDataTable('#back_office_count_table')) {
                $('#back_office_count_table').DataTable().destroy();
            }
            const columns = [
                { data: 'sno', title: 'S.No' },
                {
                    data: 'fullname',
                    title: (selectedType == '4') ? 'Zone Name' : 'User Name'
                },

                { data: 'total_count', title: 'Total Count' },
                { data: 'payable_zero', title: 'Payable Zero' },
                { data: 'responsible_zero', title: 'Responsible' },
                { data: 'balance_count', title: 'Balance Count' },
                { data: 'to_follow_paid', title: 'Paid' },
                { data: 'to_follow_unpaid', title: 'Unpaid' },
                { data: 'followed_paid', title: 'Paid' },
                { data: 'followed_unpaid', title: 'Unpaid' },
                { data: 'mobile_commitment_paid', title: 'Paid' },
                { data: 'mobile_commitment_unpaid', title: 'Unpaid' },
                { data: 'mobile_unavailable_paid', title: 'Paid' },
                { data: 'mobile_unavailable_unpaid', title: 'Unpaid' },
                { data: 'mobile_paid', title: 'Paid' },
                { data: 'mobile_total', title: ' Total' },
                { data: 'direct_commitment_paid', title: 'Paid' },
                { data: 'direct_commitment_unpaid', title: 'Unpaid' },
                { data: 'direct_unavailable_paid', title: 'Paid' },
                { data: 'direct_unavailable_unpaid', title: 'Unpaid' },
                { data: 'direct_paid', title: 'Paid' },
                { data: 'direct_total', title: 'Total' }

            ];
            const back_office_count_table = $('#back_office_count_table').DataTable({
                ...getStateSaveConfig('back_office_count_table'),
                data: tableData,
                columns: columns,
                ordering: false, // <-- add this
                createdRow: function (row, data) {
                    if (selectedType == '4') {
                        if (data.sno == 'Total') {
                            $(row).css({
                                'font-weight': 'bold',
                                'background-color': '#f5f5f5'
                            });

                        }
                    }
                },

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
                                this,
                                e,
                                dt,
                                button,
                                config
                            );
                        }
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column'
                    }
                ],

                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],

                drawCallback: function () {
                    searchFunction('back_office_count_table');
                    paginationFunction('back_office_count_table');

                }
            });

            initColVisFeatures(back_office_count_table,'back_office_count_table' );

        },

        error: function (xhr, status, error) {

            console.error('Back Office Count Error:', error);

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