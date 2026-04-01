// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });
    
    // Approval list Actions
    $(document).on("click", '.cancelapproval', function () {
        var remark = prompt("Do you want to Cancel this Approval?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'approval' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Cancelled!', 'Approval has been Cancelled.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on('click', '.move_acknowledgement', function () {
        var req_id = $(this).val();
        let cus_id = $(this).data('cusid');
        let loan_amt = $(this).data('loan_amt');
        var button = $(this)
        $.post('approveFile/check_customer_limit.php', { cus_id }, function (response) {
            let cus_limit = response['cus_limit'];
            if (cus_limit == '') {
                alert('Customer Limit is not set');
            } else if (cus_limit < loan_amt) {
                alert('Customer Limit is Less than the Loan amount');
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Customer Limit',
                    text: `Customer Limit is set to ${moneyFormatIndia(cus_limit)}. Do you want to Approve?`,
                    showCancelButton: true,
                    confirmButtonColor: '#009688',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.prop('disabled', true);
                        $.ajax({
                            url: 'approveFile/sendToAcknowledgement.php',
                            dataType: 'json',
                            type: 'post',
                            data: { 'req_id': req_id },
                            cache: false,
                            success: function (response) {

                                if (response.includes('Approved')) {
                                    Swal.fire({
                                        title: response,
                                        icon: 'success',
                                        showConfirmButton: true,
                                        confirmButtonColor: '#009688',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        // Re-enable button regardless
                                        button.prop('disabled', false);

                                        // Redirect only if user clicks OK
                                        if (result.isConfirmed) {
                                            window.location = 'approval_list';
                                        }
                                    });
                                }
                            }
                        })
                    }
                })
            }
        }, 'json')

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
