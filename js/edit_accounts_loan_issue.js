
// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    })

    $(document).on('click', '.move_customer', function(event) {
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

    $(document).on('click', '.iss-remove', function (event) {
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
  
});//document ready end



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