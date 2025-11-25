
// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    })
});//document ready end

function callOnClickEvents() {

    showOverlay();//loader start
    setTimeout(() => {
        console.log('Called on click events')
        $('a.customer-status').click(function () {
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
                hideOverlay();

            }, 1000);
        });

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

        $('.move_approval').click(function () {
            var req_id = $(this).val();
            if (confirm('Do You want to Send this for Approval?')) {
                $.ajax({
                    url: 'verificationFile/sendToApproval.php',
                    dataType: 'json',
                    type: 'post',
                    data: { 'req_id': req_id ,'cus_id': $(this).data('cusid')},
                    cache: false,
                    success: function (response) {
                        if (response.includes('Moved')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect only when OK is clicked
                                    window.location = 'verification_list';
                                }
                            });
                        }
                    }
                })
            }
        });

        // Verification list Actions
        $(document).on("click", '.cancelverification', function () {
            var remark = prompt("Do you want to Cancel this Verification?");
            if (remark != null) {
                $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'verification' }, function (data) {
                    if (data.includes('Success')) {
                        successSwal('Cancelled!', 'Verification has been Cancelled.');
                    } else {
                        warningSwal('Error!', 'Something went wrong.');
                    }
                })
                return true;
            } else {
                return false;
            }
        });
        $(document).on("click", '.revokeverification', function () {
            var remark = prompt("Do you want to Revoke this Verification?");
            if (remark != null) {
                $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'revoke', remark, screen: 'verification' }, function (data) {
                    if (data.includes('Success')) {
                        successSwal('Revoked!', 'Verification has been Revoked.');
                    } else {
                        warningSwal('Error!', 'Something went wrong.');
                    }
                })
                return true;
            } else {
                return false;
            }
        });

        //Request info tab
        $('.request-info').off('click').click(function () {
            let req_id = $(this).data('reqid');
            window.open('request&upd=' + req_id + '&pgeView=1', '_blank');
        })

        $('.loan-follow-chart').off('click').click(function () {
            let cus_id = $(this).data('cusid');
            $.post('followupFiles/loanFollowup/getLoanFollowupChart.php', { cus_id }, function (html) {
                $('#loanFollowChartDiv').empty().html(html);
            })
        });

        $('#sumit_add_lfollow').off('click').click(function () {
            if (validateLoanfollowup() == true) {
                submitLoanfollowup();
            }
        });

        $('.loan-follow-edit').off('click').click(function () {
            let stage = $(this).data('stage');
            $('#lfollow_stage').val(stage);
            //set cus id to hidden input for submit
            let cus_id = $(this).data('cusid');
            $('#lfollow_cus_id').val(cus_id);
        });

        $("#addLoanFollow").find(".closeModal").click(function () {
            $('#addLoanFollow').find('.modal-body input').not('[readonly]').val('');
            $("#addLoanFollow").find(".modal-body span").not('.required').hide();
        });

        hideOverlay();
    }, 1000);
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
                let pendingCnt = (response['pending_customer']) ? response['pending_customer'].length : 0;
                for (var i = 0; i < pendingCnt; i++) {
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
        showConfirmButton: true,
        confirmButtonColor: '#009688', // warning color (orange/yellow)
        confirmButtonText: 'OK'
    });
}

function successSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'success',
        showConfirmButton: true,
        confirmButtonColor: '#009688', // your success green
        confirmButtonText: 'OK'
    }).then((result) => {
        // Reload only if OK is clicked
        if (result.isConfirmed) {
            location.reload();
        }
    });
}

function validateLoanfollowup() {
    let response = true;
    let stage = $('#lfollow_stage').val(); let label = $('#lfollow_label').val();
    let remark = $('#lfollow_remark').val(); let follow_date = $('#lfollow_fdate').val();

    validateField(stage, '#lfollow_stageCheck');
    validateField(label, '#lfollow_labelCheck');
    validateField(remark, '#lfollow_remarkCheck');
    validateField(follow_date, '#lfollow_fdateCheck');

    function validateField(value, fieldId) {
        if (value === '') {
            response = false;
            event.preventDefault();
            $(fieldId).show();
        } else {
            $(fieldId).hide();
        }

    }

    return response;
}

function submitLoanfollowup() {
    let cus_id = $('#lfollow_cus_id').val();
    let stage = $('#lfollow_stage').val(); let label = $('#lfollow_label').val();
    let remark = $('#lfollow_remark').val(); let follow_date = $('#lfollow_fdate').val();
    let args = { cus_id, stage, label, remark, follow_date };

    $.post('followupFiles/loanFollowup/submitLoanfollowup.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function(){
                $('#closeAddFollowupModal').trigger('click');
            });

            $('#addLoanFollow').find('.modal-body input').not('[readonly]').val('');
        }
    })
}

function swarlSuccessAlert(response, callback) {
    Swal.fire({
        title: response,
        icon: 'success',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#009688'
    }).then((result) => {
        if(result.isConfirmed && typeof callback === 'function'){
            callback();
        }
    });
}