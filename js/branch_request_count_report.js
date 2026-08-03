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

    $('#type').change(function () {
        const type = $(this).val();
        $('#branch').show();
        if (type === '2') { //branch
            getUserNames();
        } else if(type === '3'){ //group
            getGroup();
        } else{
            $('#branch').empty().append("<option value=''>Select</option>");
            $('#branch').hide();
        }
    });            

    // 🔹 Reset / Show Button Click
    $('#reset_btn').click(function () {

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let type = $('#type').val();
        let branch = $('#branch').val();
        if (!from_date || !to_date || !type || (type !='1' && !branch)) {
            swalError('Please Select All Fields!', 'All fields are required.');
            return;
        }
        resetAllTables()
        // Load data
        requestToIssuedReportCount(from_date, to_date, type, branch);
    });

});

// Load User List
$(function () {
    // getUserNames();
});

function getUserNames() {
    $.post('manageUser/getBranchList.php',  function (response) {
        $('#branch').empty()
            .append("<option value=''>Select Branch</option>") .append("<option value='0'>All</option>")

        $.each(response, function (i, val) {
            $('#branch').append("<option value='" + val.branch_id + "'>" + val.branch_name + "</option>");
        });
    }, 'json');
}

function getGroup() {
    $.ajax({
        url: 'reportFile/customer_status_report/ajaxGetGroup.php',
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            $('#branch').empty()
                .append("<option value=''>Select Sector</option>") .append("<option value='0'>All</option>")

            $.each(response, function (i, val) {
                $('#branch').append("<option value='" + val.group_ids + "'>" + val.group_name + "</option>");
            });
        }
    });
}

function requestToIssuedReportCount(from_date, to_date, type, branch) {

    $.ajax({
        url: 'reportFile/work_count_report/getBranchRequestCount.php',
        type: 'POST',
        data: {
            from_date: from_date,
            to_date: to_date,
            branch_id: branch,
            type: type
        },
        dataType: 'json',
        success: function (res) {

            // Handle empty response
            if (!res.data || res.data.length === 0) {
                if ($.fn.DataTable.isDataTable('#request_count_table')) {
                    $('#request_count_table').DataTable().clear().draw();
                }
                return;
            }

            const totalRow = res.data[res.data.length - 1];
            const tableData = res.data.slice(0, -1);

            // Destroy existing table once
            if ($.fn.DataTable.isDataTable('#request_count_table')) {
                $('#request_count_table').DataTable().destroy();
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
                { data: 'previous.reloan' },
                { data: 'previous.total', render: d => `<b>${d}</b>` },

                /* REQUEST */
                { data: 'request.new' },
                { data: 'request.renewal' },
                { data: 'request.reactive' },
                { data: 'request.additional' },
                { data: 'request.existing_new' },
                { data: 'request.reloan' },
                { data: 'request.total', render: d => `<b>${d}</b>` },

                /* CANCEL */
                { data: 'cancel.new' },
                { data: 'cancel.renewal' },
                { data: 'cancel.reactive' },
                { data: 'cancel.additional' },
                { data: 'cancel.existing_new' },
                { data: 'cancel.reloan' },
                { data: 'cancel.total', render: d => `<b>${d}</b>` },

                /* REVOKE */
                { data: 'revoke.new' },
                { data: 'revoke.renewal' },
                { data: 'revoke.reactive' },
                { data: 'revoke.additional' },
                { data: 'revoke.existing_new' },
                { data: 'revoke.reloan' },
                { data: 'revoke.total', render: d => `<b>${d}</b>` },

                /* PROCESS */
                { data: 'process.new' },
                { data: 'process.renewal' },
                { data: 'process.reactive' },
                { data: 'process.additional' },
                { data: 'process.existing_new' },
                { data: 'process.reloan' },
                { data: 'process.total', render: d => `<b>${d}</b>` },

                /* ISSUED */
                { data: 'issued.new' },
                { data: 'issued.renewal' },
                { data: 'issued.reactive' },
                { data: 'issued.additional' },
                { data: 'issued.existing_new' },
                { data: 'issued.reloan' },
                { data: 'issued.total', render: d => `<b>${d}</b>` },
                /* STATUS */
                { data: 'status.current' },
                { data: 'status.pending' },
                { data: 'status.od' },
                { data: 'status.error' },
                { data: 'status.legal' },
                { data: 'status.total', render: d => `<b>${d}</b>` }
            ];

            const request_count_table = $('#request_count_table').DataTable({
                ...getStateSaveConfig('request_count_table'),
                data: tableData,
                columns: columns,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: 'Request_Count_Report',
                        action: function (e, dt, button, config) {
                            const file = curDateJs('Request_count_Repoet');
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
                    searchFunction('request_count_table');
                    paginationFunction('request_count_table');
                }
            });


            // Column visibility helper
            initColVisFeatures(request_count_table, 'request_count_table');

            // Footer totals
            $('#request_count_table tfoot').html(`
                <tr>
                    <td colspan="3"><b>Total</b></td>
                    <td>${totalRow.previous.new}</td>
                    <td>${totalRow.previous.renewal}</td>
                    <td>${totalRow.previous.reactive}</td>
                    <td>${totalRow.previous.additional}</td>
                    <td>${totalRow.previous.existing_new}</td>
                    <td>${totalRow.previous.reloan}</td>
                    <td><b>${totalRow.previous.total}</b></td>

                    <td>${totalRow.request.new}</td>
                    <td>${totalRow.request.renewal}</td>
                    <td>${totalRow.request.reactive}</td>
                    <td>${totalRow.request.additional}</td>
                    <td>${totalRow.request.existing_new}</td>
                    <td>${totalRow.request.reloan}</td>
                    <td><b>${totalRow.request.total}</b></td>

                    <td>${totalRow.cancel.new}</td>
                    <td>${totalRow.cancel.renewal}</td>
                    <td>${totalRow.cancel.reactive}</td>
                    <td>${totalRow.cancel.additional}</td>
                    <td>${totalRow.cancel.existing_new}</td>
                    <td>${totalRow.cancel.reloan}</td>
                    <td><b>${totalRow.cancel.total}</b></td>

                    <td>${totalRow.revoke.new}</td>
                    <td>${totalRow.revoke.renewal}</td>
                    <td>${totalRow.revoke.reactive}</td>
                    <td>${totalRow.revoke.additional}</td>
                    <td>${totalRow.revoke.existing_new}</td>
                    <td>${totalRow.revoke.reloan}</td>
                    <td><b>${totalRow.revoke.total}</b></td>

                    <td>${totalRow.process.new}</td>
                    <td>${totalRow.process.renewal}</td>
                    <td>${totalRow.process.reactive}</td>
                    <td>${totalRow.process.additional}</td>
                    <td>${totalRow.process.existing_new}</td>
                    <td>${totalRow.process.reloan}</td>
                    <td><b>${totalRow.process.total}</b></td>

                    <td>${totalRow.issued.new}</td>
                    <td>${totalRow.issued.renewal}</td>
                    <td>${totalRow.issued.reactive}</td>
                    <td>${totalRow.issued.additional}</td>
                    <td>${totalRow.issued.existing_new}</td>
                    <td>${totalRow.issued.reloan}</td>
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


function resetAllTables() {
    $("#request_count_table thead").show();
    $("#request_count_table tbody").show();
    $("#request_count_table tfoot").show();
    $("th, td").show(); // reset any hidden columns
}