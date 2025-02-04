
// Document is ready
$(document).ready(function () {

    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    })


});//document ready end
function callOnClickEvents() {

    setTimeout(() => {
        console.log('Called on click events')
        $('.sub_verification').click(function () {
            var req_id = $(this).val();
            var cus_id = $(this).attr('data-value');
            if (confirm('Do You want to Send this Request for Verification?')) {
                $.ajax({
                    url: 'requestFile/sendToVerificaiton.php',
                    dataType: 'json',
                    type: 'post',
                    data: { 'req_id': req_id, "cus_id": cus_id },
                    cache: false,
                    success: function (response) {
                        if (response.includes('Moved')) {
                            Swal.fire({
                                timerProgressBar: true,
                                timer: 2000,
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                            setTimeout(function () {
                                window.location = 'edit_request';
                            }, 2000)
                        }
                    }
                })
            }
        });

        $('a.customer-status').click(function () {
            event.preventDefault();
            var cus_id = $(this).data('value');
            var req_id = $(this).data('value1');
            callresetCustomerStatus(cus_id);//this function will give the customer's status like pending od current
            showOverlay();//loader start
            setTimeout(() => {
                //take all the values from the function then send to customer status file to fetch details
                var pending_sts = $('#pending_sts').val(); var od_sts = $('#od_sts').val(); var due_nil_sts = $('#due_nil_sts').val(); var closed_sts = $('#closed_sts').val()
                $.ajax({
                    url: 'requestFile/getCustomerStatus.php',
                    data: { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts },
                    // dataType: 'json',
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#cusHistoryTable').empty();
                        $('#cusHistoryTable').html(response);
                        $('#cusHistoryTable tbody tr').each(function () {
                            var val = $(this).find('td:nth-child(6)').html();
                            if (['Request', 'Verification', 'Approval', 'Acknowledgement', 'Issue'].includes(val)) {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(240, 0, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            } else if (val == 'Present') {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 160, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            } else if (val == 'Closed') {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 0, 255, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            }

                        });
                    }
                })
                $.ajax({
                    url: 'requestFile/getCustomerStatus1.php',
                    data: { "cus_id": cus_id, "req_id": req_id },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#exist_type').val(response);
                    }
                });
                hideOverlay();

            }, 1000);
        })
        $('a.loan-summary').click(function () {
            var cus_id = $(this).data('value');
            var req_id = $(this).data('value1');
            $.ajax({
                url: 'requestFile/getLoanSummary.php',
                data: { "cus_id": cus_id, "req_id": req_id },
                // dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#loanSummaryTable').empty();
                    $('#loanSummaryTable').html(response);
                }
            })
        });

        // Request Actions
        $(document).on("click", '.cancelrequest', function () {
            var remark = prompt("Do you want to Cancel this Request?");
            if (remark != null) {
                $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'request' }, function (data) {
                    if (data.includes('Success')) {
                        successSwal('Cancelled!', 'Request has been Cancelled.');
                    } else {
                        warningSwal('Error!', 'Something went wrong.');
                    }
                })
                return true;
            } else {
                return false;
            }
        });
        $(document).on("click", '.revokerequest', function () {
            var remark = prompt("Do you want to Revoke this Request?");
            if (remark != null) {
                $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'revoke', remark, screen: 'request' }, function (data) {
                    if (data.includes('Success')) {
                        successSwal('Revoked!', 'Request has been Revoked.');
                    } else {
                        warningSwal('Error!', 'Something went wrong.');
                    }
                })
                return true;
            } else {
                return false;
            }
        });

    }, 500);
}


function callresetCustomerStatus(cus_id) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];
    $.ajax({
        url: 'collectionFile/resetCustomerStatus.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {

                for (var i = 0; i < response['pending_customer'].length; i++) {
                    pending_arr[i] = response['pending_customer'][i]
                    od_arr[i] = response['od_customer'][i]
                    due_nil_arr[i] = response['due_nil_customer'][i]
                    closed_arr[i] = response['closed_customer'][i]
                    balAmnt[i] = response['balAmnt'][i]
                }
                var pending_sts = pending_arr.join(',');
                $('#pending_sts').val(pending_sts);
                var od_sts = od_arr.join(',');
                $('#od_sts').val(od_sts);
                var due_nil_sts = due_nil_arr.join(',');
                $('#due_nil_sts').val(due_nil_sts);
                var closed_sts = closed_arr.join(',');
                $('#closed_sts').val(closed_sts);
            };
        }
    });
}

function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000
    });
}

function successSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'success',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000
    })
    setTimeout(() => {
        location.reload();
    }, 2000);
}