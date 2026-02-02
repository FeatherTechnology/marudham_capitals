$(document).ready(function () {

    // Download button
    $('#download_bank_stmt').click(function () {
        window.location.href = 'uploads/excel_format/bank_stmt_format.xlsx';
    });

    $('#close_upd_modal').click(function () {
         $('#file').val('');
         $('#bank_id_upload').val('');
    });

    $("#submit_stmt_upload").click(function () {

        var bank_id = $('#bank_id_upload').val();

        if (bank_id != '') {

            $('#bank_id_uploadCheck').hide();

            if (file.files.length == 0) {
                warningSwal('Please Select File!', '');
                return false;
            }

            submitUpload();
        } else {
            $('#bank_id_uploadCheck').show();
            return false;
        }
    });

    $('#submit_bank_clearance').click(function (event) {
        event.preventDefault();
        if (submitValidation() == 0) {
            let confirmAction = confirm("Are you sure you want to submit Bank Clearance?");
            if (!confirmAction) {
                return false;
            } else {
                submitTransaction('');
            }
        }
    });

}); // document ready END


$(function () {
    getBankNames();
}); // auto call END


function getBankNames() {

    $.ajax({
        url: 'accountsFile/bankclearance/getBankNames.php',
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {

            $('#bank_name').empty().append('<option value="">Select Bank Name</option>');
            $('#bank_id_upload').empty().append('<option value="">Select Bank Name</option>');

            $.each(response, function (index, val) {

                $('#bank_name').append(
                     '<option value="' + val.id + '" data-short="'+ val.short_name +'">' +
                    val.bank_name +
                    '</option>'
                );

                $('#bank_id_upload').append(
                    '<option value="' + val.id + '" data-short="'+ val.short_name +'">' +
                    val.bank_name +
                    '</option>'
                );
            });

            $('#bank_name').change(function () {
                var bank_id = $(this).val();
                $('#acc_no').val('');

                $.each(response, function (index, val) {
                    if (bank_id == val.id) {
                        $('#acc_no').val(val.acc_no);
                    }
                });
            });
        }
    });
}


// VALIDATION
function submitValidation() {

    var response = 0;

    function validateField(value, fieldId) {
        if (value === '') {
            response = 1;
            $(fieldId).show();
        } else {
            $(fieldId).hide();
        }
    }

    validateField($('#bank_name').val(), '#bank_nameCheck');
    validateField($('#acc_no').val(), '#acc_noCheck');
    validateField($('#trans_date').val(), '#trans_dateCheck');
    validateField($('#narration').val(), '#narrationCheck');
    validateField($('#crdb').val(), '#crdbCheck');
    validateField($('#amt').val(), '#amtCheck');
    validateField($('#bal').val(), '#balCheck');

    return response;
}


// MANUAL SUBMIT
function submitTransaction(mode) {
    var formData = $('#bank_clearance_form').serializeArray();

    // Get the selected bank short name from the select's data attribute
    var bank_short_name = $('#bank_name option:selected').data('short');

    // Add bank_short_name to the form data
    formData.push({ name: 'bank_short_name', value: bank_short_name });

    $.ajax({
        url: 'accountsFile/bankclearance/submitBankClearance.php',
        type: 'post',
        data: formData,
        cache: false,
        success: function (response) {
            try {
                // If your PHP now returns JSON
                response = JSON.parse(response);
                if (response.status === 'success') {
                    successSwal('Submitted Successfully', '');
                    $('#bank_clearance_form')[0].reset();
                } else {
                    warningSwal('Error', response.message || 'Error Occurred while submitting');
                }
            } catch (e) {
                // fallback if PHP still returns 0/1
                if (response == 0) {
                    successSwal('Submitted Successfully', '');
                    $('#bank_clearance_form')[0].reset();
                } else {
                    warningSwal('Error', 'Error Occurred while submitting');
                }
            }
        }
    });
}



// ✅ UPDATED UPLOAD HANDLER (JSON RESPONSE)
function submitUpload() {

    var file_data = $('#file').prop('files')[0];
    var bank_id = $('#bank_id_upload').val();
    var bank_short_name = $('#bank_id_upload option:selected').data('short');
    // console.log("bank_name",bank_short_name,"ll");return;

    var area = new FormData();
    area.append('file', file_data);
    area.append('bank_id', bank_id);
    area.append('bank_short_name', bank_short_name);

    $.ajax({
        url: 'accountsFile/bankclearance/submitUploadedBankStmt.php',
        type: 'POST',
        data: area,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file, #submit_stmt_upload').prop("disabled", true);
        },
        success: function (res) {

            if (res.status === 'success') {

                $("#file").val('');
                Swal.fire({
                    title: 'Statement Uploaded!',
                    html: `Total Rows Inserted : <b>${res.inserted}</b>`,
                    icon: 'success',
                    confirmButtonColor: '#009688'
                }).then(() => {
                    $('#close_upd_modal').trigger('click');
                    location.reload();
                });
            }

            else if (res.status === 'balance_mismatch') {

                Swal.fire({
                    title: 'Balance Mismatch!',
                    html: `
                        <b>Upload stopped</b><br><br>
                        Rows inserted : <b>${res.inserted}</b><br>
                        Error at Excel Row : <b>${res.error_row}</b><br><br>
                        ${res.message}
                    `,
                    icon: 'error',
                    confirmButtonColor: '#009688'
                }).then(() => {
                    $('#close_upd_modal').trigger('click');
                });
            }

            else {
                warningSwal('Upload Failed', res.message || 'Unknown error');
            }
        },
        error: function () {
            warningSwal('Server Error', 'Unable to upload statement');
        },
        complete: function () {
            $('#file, #submit_stmt_upload').prop("disabled", false);
        }
    });
}

// ALERT HELPERS
function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        confirmButtonColor: '#009688'
    });
}

function successSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'success',
        confirmButtonColor: '#009688'
    }).then(() => location.reload());
}
