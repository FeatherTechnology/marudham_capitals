// Document is ready
$(document).ready(function () {

    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });

    // Request Actions
    $(document).on("click", '.cancelrequest', function (event) {
        event.preventDefault(); // Prevent the default action (if needed)
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
    
    $(document).on("click", '.revokerequest', function (event) {
        event.preventDefault(); // Prevent the default action (if needed)
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

    $(document).on('click', '.sub_verification', function () {
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
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect only when OK is clicked
                                window.location = 'edit_request';
                            }
                        });
                    }
                }
            })
        }
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