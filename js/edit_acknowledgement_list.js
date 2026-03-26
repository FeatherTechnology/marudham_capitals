
// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });

    // Acknowledgement list Actions
    $(document).on("click", '.ack-cancel', function () {
        var remark = prompt("Do you want to Cancel this Acknowledgement?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'ack' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Cancelled!', 'Acknowledgement has been Cancelled.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on('click', '.move_issue', function () {
        var req_id = $(this).val();
        var cus_id = $(this).data('cusid');
        if (confirm('Do You want to Send this for Issue?')) {
            $.ajax({
                url: 'verificationFile/sendToIssue.php',
                dataType: 'json',
                type: 'post',
                data: { 'req_id': req_id, 'cus_id': cus_id },
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
                            // Redirect only if user clicks OK
                            if (result.isConfirmed) {
                                window.location = 'edit_acknowledgement_list';
                            }
                        });
                    }
                    else {
                        Swal.fire({
                            timerProgressBar: true,
                            timer: 2000,
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                }
            })
        }
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