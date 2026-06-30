const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select',
    allowHTML: true
});

const loanCategory = new Choices('#loan_category', {
    removeItemButton: true,
    noChoicesText: 'Select Category',
    allowHTML: true
});

$('#map_name, #loan_category').closest('.choices').hide();

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
        $('#map_name, #loan_category').closest('.choices').hide();
        map_name.clearStore();

        if ($.fn.DataTable.isDataTable('#confirmation_count_table')) {
            $('#confirmation_count_table').DataTable().destroy();
        }
        $('#confirmation_count_table thead').empty();
        $('#confirmation_count_table tbody').empty();
        $('#confirmation_count_table tfoot').empty();
        
        if(type == '1'){ 
            $('#user_type, #by_user').val('').show();
            $('#by_user').empty().append("<option value=''>Select User</option>");

        } else if(type == '2' || type == '3' || type == '4') { //sector - group, Region - Line, Zone - Follow up
            $('#map_name, #loan_category').closest('.choices').show();
            getUserMappedDetails(type); //to Mapping details. 
            getUserLoanCategories(); //to get Loan Category list.
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
        let loanCatVal = '';

        if(selectedType == '1'){ //user
            selectedVal = '1'; //dummy
            loanCatVal = '1'; //dummy
            
        } else if(selectedType == '2' || selectedType == '3' || selectedType == '4'){ //sector - group //Region - Line //Zone - Followup
            selectedVal = $('#map_name').val();
            loanCatVal = $('#loan_category').val();
        }

        if(!from_date || !to_date || !selectedVal || !loanCatVal || (selectedType == '1' && (!user_type || !selected_user))){
            swalError('Warning', `All Fields are required.`);
            return;
        }

        confirmationReportCount(from_date, to_date, selectedType, user_type, selected_user, selectedVal, loanCatVal);
    });

});

function getUserNames() {
    let user_type = $('#user_type').val();

    $.post('reportFile/customer_status_report/getAllUserList.php', { user_track: 2, user_type: user_type }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function confirmationReportCount(from_date, to_date, selectedType, user_type, user_id, selectedVal, loanCatVal) {

    $.ajax({
        url: 'reportFile/confirmation_count_report/getConfirmationCount.php',
        type: 'POST',
        data: { from_date, to_date, selectedType, user_type, user_id, selectedVal, loanCatVal },
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {
                $('#confirmation_count_table').DataTable().clear().draw();
                $('#confirmation_count_table thead').html(
                    "<tr><th colspan='8'>No data found for selected filters</th></tr>"
                );
                return;
            }

            // Remove total row for body
            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            let ttle;
            if(selectedType =='2'){
                ttle = 'Sector';
            } else if(selectedType =='3'){
                ttle = 'Region';
            } else if(selectedType =='4'){
                ttle = 'Zone';
            } else{
                ttle = 'User Name';
            }

            // DataTable Columns
            const columns = [
                { data: 'sno', title: "S.No" },
                { data: 'fullname', title: ttle },
                { data: 'loan_category', title: "Loan Category" }
            ];

            if (selectedType == '1') {
                columns.push({
                    data: 'line',
                    title: "Region Name"
                });
            }

            columns.push(
                { data: 'total_count', title: "Total Count" },
                { data: 't_completed_count', title: "Completed" },
                { data: 't_unavailable_count', title: "Unavailable" },
                { data: 't_reconfirmation', title: "Reconfirmation" }
            );

            if ($.fn.DataTable.isDataTable('#confirmation_count_table')) {
                $('#confirmation_count_table').DataTable().destroy();
            }

            $('#confirmation_count_table thead').empty();
            $('#confirmation_count_table tbody').empty();
            $('#confirmation_count_table tfoot').empty();

            var confirmation_count_table = $('#confirmation_count_table').DataTable({
                destroy: true,
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

            // =============================
            // 🔥 SET FOOTER (TOTAL VALUES)
            // =============================
            let footer = `
            <tr>
                <td></td>
                <td></td>
            `;

            if (selectedType == '1') {
                footer += `<td></td>`;
            }

            footer += `
                <td><b>Total</b></td>
                <td><b>${totalRow.total_count}</b></td>
                <td><b>${totalRow.t_completed_count}</b></td>
                <td><b>${totalRow.t_unavailable_count}</b></td>
                <td><b>${totalRow.t_reconfirmation}</b></td>
            </tr>
            `;

            $('#confirmation_count_table tfoot').html(footer);
        }
    });
}