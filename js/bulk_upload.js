$(document).ready(function () {

    $('#upload_btn').change(function () {
        let fileValidation = $('#upload_btn').val();
        if (fileValidation != '') {
            $('#upload_btnCheck').hide();//hide validation
        } else {
            $('#upload_btnCheck').show();
        }

    })

    $('#bk_submit').click(function () {
        let fileValidation = $('#upload_btn').val();
        if (fileValidation != '') {
            $('#upload_btnCheck').hide();//hide validation
            $('#bk_submit').attr('disabled')
            uploadExcelToDB();

        } else {
            $('#upload_btnCheck').show();
        }


    })
})


function uploadExcelToDB() {
    let excelFile = $('#upload_btn')[0].files[0];
    let formData = new FormData();
    formData.append('excelFile', excelFile);

    $.ajax({
        url: 'bulk_uploadFile/OldCustDataBulkUpload.php',
        data: formData,
        type: 'post',
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            // $('#responseCard').empty().html(response)
            if (response.includes('Bulk')) {
                Swal.fire({
                    title: 'Success!',
                    text: response,
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688'
                });
            } else if (response.includes('completed')) {
                Swal.fire({
                    html: response,
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688'
                });
            } else if (response.includes('Format')) {
                Swal.fire({
                    title: 'Error!',
                    text: response,
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688'
                });
            }
        }

    }).then(function () {
        $('#bk_submit').removeAttr('disabled')
    })
}