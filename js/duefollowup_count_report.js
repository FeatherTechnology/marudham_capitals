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
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        $('#due_followup_count_table').DataTable().destroy();
        $('#due_followup_count_table tbody').empty();
        $('#due_followup_count_table tfoot td:not(:first)').html('');
        
        if(type == '1'){ 
            $('#user_type, #by_user').val('').show();
            $('#by_user').empty().append("<option value=''>Select User</option>");

        } else if(type == '4') { //sector - group, Region - Line, Zone - Follow up
            $('#user_type, #by_user').val('').hide();
            $('#map_name').closest('.choices').show();
            getUserMappedDetails(type); //to Mapping details.
            
        } else if(type == '0'){
            $('#user_type, #by_user').val('').hide();
        }
    });

    $('#user_type').change(function () {
        let userType = $('#user_type').val();
        $('#by_user').empty().append("<option value=''>Select User</option>");  

        if(userType != ''){
            getUserNames();
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

        if(!from_date || !to_date || !selectedVal || (selectedType == '1' && (!user_type || !selected_user))){
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
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // Remove formatting to get integer data for summation
                    /* ---------------- PARSER ---------------- */
                    const parseVal = (v) => {
                        if (typeof v === 'string') {
                            return parseFloat(v.replace(/,/g, '')) || 0;
                        }
                        return v || 0;
                    };

                    // Array of column indices to sum
                    var columnsToSum = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

                    // Loop through each column index
                    columnsToSum.forEach(function (colIndex) {
                        // Total over all pages for the current column
                        var total = api
                            .column(colIndex)
                            .data()
                            .reduce(function (a, b) {
                                return parseVal(a) + parseVal(b);
                            }, 0);
                        // Update footer for the current column
                        $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
                    });
                },
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