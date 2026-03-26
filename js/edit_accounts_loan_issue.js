
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
    });
  
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
                successSwal('Success', result.message);
            } else {
                warningSwal('Error', result.message);
            }
        },
        error: function () {
            warningSwal('Error!', 'Something went wrong while moving the loan.');
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