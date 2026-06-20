// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
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

    $(document).on('click', '.move_approval', function () {
            var req_id = $(this).val();
            if (confirm('Do You want to Send this for Approval?')) {
                $.ajax({
                    url: 'verificationFile/sendToApproval.php',
                    dataType: 'json',
                    type: 'post',
                    data: { 'req_id': req_id, 'cus_id': $(this).data('cusid') },
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

    //Request info tab
    $(document).on('click', '.request-info', function () {
        let req_id = $(this).data('reqid');
        window.open('request&upd=' + req_id + '&pgeView=1', '_blank');
    });

    $(document).on('click', '#sumit_add_lfollow', function () {
        if (validateLoanfollowup() == true) {
            submitLoanfollowup();
        }
    });

    $(document).on('click', '.loan-follow-edit', function () {
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

});//document ready end

$(function () {
    loadNotifications();
})

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
                // $('#closeAddFollowupModal').trigger('click');
                location.reload(); // Refresh the page to show the Last Follow-up date in the list
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