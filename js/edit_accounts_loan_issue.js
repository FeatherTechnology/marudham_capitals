
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
        //    callresetCustomerStatus(cus_id);//this function will give the customer's status like pending od current
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
        $('.move_customer').click(function(event) {
            event.preventDefault(); // Prevent the default action (if needed)
            let req_id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure to move to Loan Issue?',
                text: 'This action cannot be reverted!',
                icon: 'question',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonColor: '#009688',
                cancelButtonColor: '#cc4444',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes'
            }).then(function(result) {
                if (result.isConfirmed) {
                    removeLoanFromList(req_id);
                }
            });
        })

        $('.iss-remove').click(function () {
            event.preventDefault();
            let req_id = $(this).data('value');
            if (confirm('Do you want to Remove this Issue From the List?')) {
                $.ajax({
                    url: 'loanIssueFile/removeIssue.php',
                    dataType: 'json',
                    type: 'post',
                    data: { 'req_id': req_id },
                    cache: false,
                    success: function (response) {
                        if (response.includes('Removed')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                // Redirect only if OK is clicked
                                if (result.isConfirmed) {
                                    window.location = 'edit_loan_issue';
                                }
                            });
                        }
                        else if (response.includes('Error')) {
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
        })

        hideOverlay();
    }, 1000);
}


function removeLoanFromList(req_id) {
    $.ajax({
        url: 'loanIssueFile/moveLoanIssue.php',
        type: 'POST',
        data: { "req_id": req_id },
        dataType: 'json',
        cache: false,
        success: function (result) {
            if (result.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: result.message,
                    icon: 'success',
                    confirmButtonColor: '#009688',
                    timer: 1500, // Auto-close after 1.5 seconds
                    showConfirmButton: false
                }).then(function () {
                    // Reload the page or remove the loan item from the list
                    location.reload(); // Reload the page to update the list
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: result.message,
                    icon: 'error',
                    confirmButtonColor: '#cc4444'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong while moving the loan.',
                icon: 'error',
                confirmButtonColor: '#cc4444'
            });
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