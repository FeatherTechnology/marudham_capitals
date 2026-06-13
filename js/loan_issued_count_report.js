const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select Sector',
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
        $('#user_type, #by_user').val('').hide();
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        
        $('#issue_count_table').DataTable().destroy();
        $('#issue_count_table tbody').empty();
        $('#issue_count_table tfoot').empty();
        
        if(type == '1'){ 
            $('#user_type, #by_user').val('').show();
            $('#by_user').empty().append("<option value=''>Select User</option>");

        } else if(type == '2' || type == '3' || type == '4') { //sector - group, Region - Line, Zone - Follow up
            $('#map_name').closest('.choices').show();
            getUserMappedDetails(type); //to Mapping details.  
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
            
        } else if(selectedType == '2' || selectedType == '3' || selectedType == '4'){ //sector - group //Region - Line //Zone - Followup
            selectedVal = $('#map_name').val();
        }

        if(!from_date || !to_date || !selectedVal || (selectedType == '1' && (!user_type || !selected_user))){
            swalError('Warning', `All Fields are required.`);
            return;
        }

        resetAllTables();
        requestIssuedReportCount(from_date, to_date, selectedType, user_type, selected_user, selectedVal);
    });

});

function getUserNames() {
    let user_type = $('#user_type').val();

    $.post('reportFile/due_followup_count_report/getDuefollowupUser.php', { screen: 6, user_type: user_type }, function (response) {
        $('#by_user').empty()
            .append("<option value=''>Select User</option>")
            .append("<option value='all'>All</option>");
        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function requestIssuedReportCount(from_date, to_date, selectedType, user_type, user_id, selectedVal) {

    $.ajax({
        url: 'reportFile/work_count_report/getLoanIssuedCountReport.php',
        type: 'POST',
        data: { from_date, to_date, selectedType, user_type, user_id, selectedVal },
        dataType: 'json',
        success: function (res) {

            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#issue_count_table')) {
                $('#issue_count_table').DataTable().destroy();
            }

            if (!res.data || res.data.length === 0) {
                $('#issue_count_table tbody').empty();
                return;
            }

            // Last row is total
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            const columns = [
                { data: 'sno' },
                { data: 'fullname' },
                { data: 'loan_category' },
                { data: 'agent_name' },
                { data: 'new' },
                { data: 'additional' },
                { data: 'renewal' },
                { data: 'reactive' },
                { data: 'existing_new' },
                { data: 'total_count', render: d => `<b>${d}</b>` },
                { data: 'current' },
                { data: 'pending' },
                { data: 'od' },
                { data: 'error' },
                { data: 'legal' },
                { data: 'status_total', render: d => `<b>${d}</b>` }
            ];

            const issue_count_table = $('#issue_count_table').DataTable({
                ...getStateSaveConfig('issue_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Loan_issued_count_Repoet',
                        action: function (e, dt, button, config) {
                            const file = curDateJs('Loan_issued_count_Repoet');
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
                pageLength: 10,
                drawCallback: function () {
                    searchFunction('issue_count_table');
                    paginationFunction('issue_count_table');
                }
            });
             // Column visibility helper
            initColVisFeatures(issue_count_table, 'issue_count_table');

            // Footer totals
            $('#issue_count_table tfoot').html(`
                <tr>
                    <td colspan="4"><b>Total</b></td>
                    <td>${totalRow.new}</td>
                    <td>${totalRow.additional}</td>
                    <td>${totalRow.renewal}</td>
                    <td>${totalRow.reactive}</td>
                    <td>${totalRow.existing_new}</td>
                    <td><b>${totalRow.total_count}</b></td>
                    <td>${totalRow.current}</td>
                    <td>${totalRow.pending}</td>
                    <td>${totalRow.od}</td>
                    <td>${totalRow.error}</td>
                    <td>${totalRow.legal}</td>
                    <td><b>${totalRow.status_total}</b></td>
                </tr>
            `);
        }
    });
}

function resetAllTables() {
    $("#issue_count_table thead").show();
    $("#issue_count_table tbody").show();
    $("#issue_count_table tfoot").show();
    $("th, td").show();
}