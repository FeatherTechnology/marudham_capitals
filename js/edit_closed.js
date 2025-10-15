function setNOCButton() {

    $('.Move_to_noc').click(function () {
        var cus_id = $(this).data('value');
        var req_id = $(this).data('id');
        if (confirm('Do You want to Move to NOC?')) {
            $.ajax({
                url: 'closedFile/sendToNOC.php',
                dataType: 'json',
                type: 'post',
                data: { 'cus_id': cus_id, "req_id": req_id },
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
                            // Redirect only if OK is clicked
                            if (result.isConfirmed) {
                                window.location = 'edit_closed';
                            }
                        });
                    }
                }
            })
        }
    });
}


