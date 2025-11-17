// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    })
});//document ready end

function callOnClickEvents() {

    showOverlay();//loader start
    setTimeout(() => {
        console.log('Called on click function')

        $('a.customer-status').click(function () {
            var cus_id = $(this).data('value');
            var req_id = $(this).data('value1');
            showOverlay();//loader start

            //this function will give the customer's status like pending od current
            callresetCustomerStatus(cus_id).then((status) => {
                //take all the values from the function then send to customer status file to fetch details
                var { pending_sts, od_sts, due_nil_sts, closed_sts } = status; // Destructure returned values
        
                $.ajax({
                    url: 'requestFile/getCustomerStatus.php',
                    type: 'POST',
                    data: { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts },
                    cache: false,
                    success: function (response) {
                        $('#cusHistoryTable').empty().html(response);
                        $('#cusHistoryTable tbody tr').each(function () {
                            var val = $(this).find('td:nth-child(6)').text().trim();
        
                            if (['Request', 'Verification', 'Approval', 'Acknowledgement', 'Issue'].includes(val)) {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(240, 0, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            } else if (val === 'Present') {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 160, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            } else if (val === 'Closed') {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 0, 255, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            }
                        });
                    },
                    complete: function () {
                        hideOverlay(); // Hide loader after completion
                    }
                });
            }).catch((error) => {
                console.error("Error fetching customer status:", error);
                hideOverlay();
            });

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
        
        $('.move_acknowledgement').click(function () {
            var req_id = $(this).val();
            let cus_id = $(this).data('cusid');
            let loan_amt = $(this).data('loan_amt');
            var button = $(this)
            $.post('approveFile/check_customer_limit.php', { cus_id }, function (response) {
                let cus_limit = response['cus_limit'];
                if (cus_limit == '') {
                    alert('Customer Limit is not set');
                } else if(cus_limit < loan_amt){
                    alert('Customer Limit is Less than the Loan amount');
                }else {
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

        hideOverlay();
    }, 1000);
}

function callresetCustomerStatus(cus_id) {
     //To get loan sub Status
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'collectionFile/resetCustomerStatus.php',
            type: 'POST',
            data: { 'cus_id': cus_id },
            dataType: 'json',
            cache: false,
            success: function (response) {
                if (!response || response.length == 0) {
                    resolve({ pending_sts: "", od_sts: "", due_nil_sts: "", closed_sts: "" });
                    return;
                }

                let pending_arr = response['pending_customer'] || [];
                let od_arr = response['od_customer'] || [];
                let due_nil_arr = response['due_nil_customer'] || [];
                let closed_arr = response['closed_customer'] || [];

                let pending_sts = pending_arr.join(',');
                let od_sts = od_arr.join(',');
                let due_nil_sts = due_nil_arr.join(',');
                let closed_sts = closed_arr.join(',');

                $('#pending_sts').val(pending_sts);
                $('#od_sts').val(od_sts);
                $('#due_nil_sts').val(due_nil_sts);
                $('#closed_sts').val(closed_sts);

                resolve({ pending_sts, od_sts, due_nil_sts, closed_sts });
            },
            error: function (xhr, status, error) {
                reject(error);
            }
        });
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
