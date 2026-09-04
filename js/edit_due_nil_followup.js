
$(document).ready(function () {

    $('#show_due_nil_followup').click(function () {
        let comm_date = $("#comm_date").val();
        let res_sts = $("#res_sts").val();
        let comm_sts = $("#comm_sts").val();
        let call_status = $("#call_status").val();

        OnLoadFunctions(comm_date, res_sts, comm_sts, call_status);
    });

    $(document).on('click', '.personal-info', function (e) {
        e.preventDefault();
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/promotion/getPersonalInfo.php', { cus_id: cus_id }, function (html) {
            $('#personalInfoDiv').html(html);
        }
        ).fail(function (xhr, status, error) {
            console.log("AJAX Error:", error);
            console.log(xhr.responseText);
        });
    });

    let branchLoaded = false;
    let regionLoaded = false;
    let zoneLoaded = false;


    // Branch
    $('#branch').on('focus', function () {
        if (!branchLoaded) {
            branchLoaded = true;
            getBranchList();
        }
    });

    // Region
    $('#region').on('focus', function () {
        if (!regionLoaded) {
            regionLoaded = true;
            getLineList();
        }
    });

    // Zone
    $('#zone').on('focus', function () {
        if (!zoneLoaded) {
            zoneLoaded = true;
            getFollowupList();
        }
    });

});

$(function () {
    let cummDate = $("#cummDate").val();
    let res_sts = $("#res_sts").val();
    let comm_sts = $("#comm_sts").val();
    let call_status = $("#call_status").val();
    OnLoadFunctions(cummDate, res_sts, comm_sts, call_status);

});

function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
    });
}

function OnLoadFunctions(comm_date, res_sts, comm_sts, call_status) {
    let branch_id = $("#branch").val();
    let line_id = $("#region").val();
    let followup_id = $("#zone").val();
    $('#due_nil_followup_table').DataTable().destroy();
    var table = $('#due_nil_followup_table').DataTable({
        // ...getStateSaveConfig('due_nil_followup_table'),
        "order": [[0, "desc"]],
        "processing": true,
        "displayStart": getDisplayStart('due_nil_followup_table'),
        "serverSide": true,
        'infoCallback': customDataTableInfo,
        "serverMethod": 'post',
        "ajax": {
            "url": 'followupFiles/dueNilFollowup/getDueNilFollowCus.php',
            "data": function (data) {
                var search = $('#search').val();
                data.search = search;
                data.comm_date = comm_date;
                data.res_sts = res_sts;
                data.comm_sts = comm_sts;
                data.call_status = call_status;
                data.branch_id = branch_id;
                data.line_id = line_id;
                data.followup_id = followup_id;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Due Followup List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Due_Followup'); // or any base
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
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "createdRow": function (row, data, dataIndex) {
            var pageInfo = table.page.info();
            var serialNumber = pageInfo.start + dataIndex + 1;
            $('td', row).eq(0).html(serialNumber);
        },
        "pagingType": "simple_numbers",
        "drawCallback": function () {
            enableDateColoring();
            searchFunction('due_nil_followup_table');
            paginationFunction('due_nil_followup_table');
        }
    });
    // initColVisFeatures(table, 'due_nil_followup_table');
}

function enableDateColoring() {
    //for coloring
    $('#due_nil_followup_table tbody tr').not('th').each(function () {
        let tddate = $(this).find('td:eq(15)').text(); // Get the text content of the 12th td element (Follow date)
        let datecorrection = tddate.split("-").reverse().join("-").replaceAll(/\s/g, ''); // Correct the date format
        let values = new Date(datecorrection); // Create a Date object from the corrected date
        values.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let curDate = new Date(); // Get the current date
        curDate.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let colors = { 'past': 'FireBrick', 'current': 'DarkGreen', 'future': 'CornflowerBlue' }; // Define colors for different date types

        if (tddate != '' && values != 'Invalid Date') { // Check if the extracted date and the created Date object are valid

            if (values < curDate) { // Compare the extracted date with the current date
                $(this).find('td:eq(15)').css({ 'background-color': colors.past, 'color': 'white' }); // Apply styling for past dates
            } else if (values > curDate) {
                $(this).find('td:eq(15)').css({ 'background-color': colors.future, 'color': 'white' }); // Apply styling for future dates
            } else {
                $(this).find('td:eq(15)').css({ 'background-color': colors.current, 'color': 'white' }); // Apply styling for the current date
            }
        }
    });
}

$(document).on('click', 'a.customer-summary', async function (event) {
    event.preventDefault();
    try {
        let cus_id = $(this).data('value');
        let cusid = $(this).data('cusid');
        let cusname = $(this).data('cusname');
        let mobile = $(this).data('mobile');
        $.ajax({
            url: 'verificationFile/customer_feedback_list.php',
            type: 'POST',
            data: { "cus_id": cus_id },
            cache: false,
            success: function (html) {
                $("#feedbackListTable").html(html);
                $('#myLargeModalLabel').text(`Customer Summary ( Aadhaar Number : ${cus_id} | Cus ID : ${cusid}  | Cus Name : ${cusname}  | Mobile : ${mobile} )`);
            }
        });
    } catch (err) {
        console.error(err);
        hideOverlay();
    }
});

function getBranchList() {
    $.ajax({
        url: 'followupFiles/promotion/getBranchList.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            $('#branch').html('<option value="">Select Branch</option>');

            $.each(response, function (index, value) {

                $('#branch').append(
                    '<option value="' + value.branch_id + '">' +
                    value.branch_name +
                    '</option>'
                );

            });
        }
    });

}

function getLineList() {
    $.ajax({
        url: 'followupFiles/promotion/getLineList.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            $('#region').html('<option value="">Select Region</option>');

            $.each(response, function (index, value) {

                $('#region').append(
                    '<option value="' + value.line_id + '">' +
                    value.line_name +
                    '</option>'
                );

            });
        }
    });

}
function getFollowupList() {
    $.ajax({
        url: 'followupFiles/promotion/getFollowupList.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            $('#zone').html('<option value="">Select Zone</option>');

            $.each(response, function (index, value) {

                $('#zone').append(
                    '<option value="' + value.due_followup_lines_id + '">' +
                    value.duefollowup_name +
                    '</option>'
                );

            });
        }
    });

}

