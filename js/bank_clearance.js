$(document).ready(function () {

    // Download button

    $('#download_bank_stmt').click(function () {
        window.location.href = 'uploads/excel_format/bank_stmt_format.xlsx';
    });

    $("#submit_stmt_upload").click(function () {

        var bank_id = $('#bank_id_upload').val();

        if (bank_id != '') { //allows only if bank id selected
            $('#bank_id_uploadCheck').hide();

            var file_data = $('#file').prop('files')[0];
            var bank_id = $('#bank_id_upload').val();
            var area = new FormData();
            area.append('file', file_data);
            area.append('bank_id', bank_id);

            if (file.files.length == 0) { //if no file selected
                warningSwal('Please Select File!', '');
                return false;
            } else {
                $.ajax({
                    url: 'accountsFile/bankclearance/checkExcelforOverwrite.php',
                    data: area,
                    type: 'post',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        if (response == 0) {
                            submitUpload();
                        } else if (response == 1) {
                            Swal.fire({
                                title: 'Your Statement Has existing transaction Dates!',
                                text: 'Do you want to overwrite?',
                                icon: 'question',
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '#009688',
                                cancelButtonColor: '#cc4444',
                                cancelButtonText: 'No',
                                confirmButtonText: 'Yes'
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    removeAndSubmitUpload();
                                }
                            })
                        } else {
                            warningSwal('Error', 'Error occured when uploading');
                            return false;
                        }
                    }
                })

            }
        } else {
            $('#bank_id_uploadCheck').show();
            return false;
        }
    });

    $('#submit_bank_clearance').click(function () {
        event.preventDefault();
        if (submitValidation() == 0) {
            //call submit function directly with empty parameters so it will not overwrite or delete anything
            //because one day has one or more transactions
            submitTransaction('');
        }
    })

});// document ready end



$(function () {

    getBankNames();//get bank names

});// auto call functions END



function getBankNames() {
    $.ajax({
        url: 'accountsFile/bankclearance/getBankNames.php',
        data: {},
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#bank_name').empty();
            $('#bank_name').append('<option value="">Select Bank Name</option>');
            $.each(response, function (index, val) {
                $('#bank_name').append("<option value='" + val['bank_id'] + "'>" + val['bank_name'] + "</option>");
            })

            $('#bank_id_upload').empty(); // for upload modal bank id select box
            $('#bank_id_upload').append('<option value="">Select Bank Name</option>');
            $.each(response, function (index, val) {
                $('#bank_id_upload').append("<option value='" + val['bank_id'] + "'>" + val['bank_name'] + "</option>");
            })

            $('#bank_name').change(function () {
                var bank_id = $(this).val();
                if (bank_id != '') {
                    $('#acc_no').val('');
                    $.each(response, function (index, val) {
                        if (bank_id == val['bank_id']) {
                            $('#acc_no').val(val['acc_no']);
                        }
                    })
                } else {
                    $('#acc_no').val('');
                }
            })
        }
    });
}

//because to store statement in table, we need bank id
function submitValidation() {
    var bank_id = $('#bank_name').val(); var acc_no = $('#acc_no').val(); var trans_date = $('#trans_date').val(); var trans_id = $('#trans_id').val();
    var narration = $('#narration').val(); var crdb = $('#crdb').val(); var amt = $('#amt').val(); var bal = $('#bal').val();
    var response = 0;

    function validateField(value, fieldId) {
        if (value === '') {
            response = 1;
            event.preventDefault();
            $(fieldId).show();
        } else {
            $(fieldId).hide();
        }
    }

    // validateField(ucl_trans_id, '#ucl_trans_id_exfCheck');
    validateField(bank_id, '#bank_nameCheck');
    validateField(acc_no, '#acc_noCheck');
    validateField(trans_date, '#trans_dateCheck');
    validateField(trans_id, '#trans_idCheck');
    validateField(narration, '#narrationCheck');
    validateField(crdb, '#crdbCheck');
    validateField(amt, '#amtCheck');
    validateField(bal, '#balCheck');
    return response;
}

//submit transaction
function submitTransaction(mode) {
    var formData = $('#bank_clearance_form').serializeArray();
    formData.push({ name: 'mode', value: mode });

    $.ajax({
        url: 'accountsFile/bankclearance/submitBankClearance.php',
        data: formData,
        type: 'post',
        cache: false,
        success: function (response) {
            if (response == 0) {
                successSwal('Submitted Successfully', '');
                //to empty the values after successfull submition
                $('#bank_name, #acc_no, #trans_date, #trans_id, #narration, #crdb, #amt, #bal').val('')
            } else {
                warningSwal('Error', 'Error Occured while submitting')
            }
        }
    })
}

//submit uploaded excel file if those transaction dates are not exist in db
function submitUpload() {

    var file_data = $('#file').prop('files')[0];
    var bank_id = $('#bank_id_upload').val();
    var area = new FormData();
    area.append('file', file_data);
    area.append('bank_id', bank_id);

    $.ajax({
        url: 'accountsFile/bankclearance/submitUploadedBankStmt.php',
        type: 'POST',
        data: area,
        // dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file').attr("disabled", true);
            $('#submit_stmt_upload').attr("disabled", true);
        },
        success: function (data) {
            if (data == 0) {
                $("#file").val('');
                Swal.fire({
                    title: 'Statement Uploaded!',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $('#close_upd_modal').trigger('click');
                        // getBankClearanceTable();
                    }
                })
            } else if (data > 0) {
                $("#file").val('');
                warningSwal('File Not Uploaded!', 'Problem whil reading file')
            }
        },
        complete: function () {
            $('#file').attr("disabled", false);
            $('#submit_stmt_upload').attr("disabled", false);
        }
    });
}

//remove entries and submit uploaded excel file if table has transaction dates of excel file
function removeAndSubmitUpload() {

    var file_data = $('#file').prop('files')[0];
    var bank_id = $('#bank_id_upload').val();
    var area = new FormData();
    area.append('file', file_data);
    area.append('bank_id', bank_id);

    $.ajax({
        url: 'accountsFile/bankclearance/removeAndSubmitUpload.php',
        type: 'POST',
        data: area,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#bank_id_upload').attr("disabled", true);
            $('#file').attr("disabled", true);
            $('#submit_stmt_upload').attr("disabled", true);
        },
        success: function (data) {
            if (data == 0) {
                $("#file").val('');
                Swal.fire({
                    title: 'Statement Uploaded!',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        // getBankClearanceTable();
                    }
                })
            } else if (data > 0) {
                $("#file").val('');
                warningSwal('File Not Uploaded!', 'Problem while reading file')
            }
        },
        complete: function () {
            $('#bank_id_upload').attr("disabled", false);
            $('#file').attr("disabled", false);
            $('#submit_stmt_upload').attr("disabled", false);
        }
    });
}

function getBankClearanceTable() {//not used
    $('#close_upd_modal').trigger('click');
    $('#bank_details_card').hide();
    $('#bank_clearance_card').show();
    $('#back_to_band_details').show();

    var bank_id = $('#bank_name').val(); var from_date = $('#from_date').val(); var to_date = $('#to_date').val();

    $.ajax({
        url: 'accountsFile/bankclearance/getBankClearanceTable.php',
        data: { 'bank_id': bank_id, 'from_date': from_date, 'to_date': to_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#bank_clearanceDiv').empty()
            $('#bank_clearanceDiv').html(response)
        }
    })

}

function getBankStmtTable() {
    $.ajax({
        url: 'accountsFile/bankclearance/getBankStmtTable.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#bank_stmtDiv').empty();
            $('#bank_stmtDiv').html(response);
        }
    })
}

function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        showConfirmButton: true,
        confirmButtonColor: '#009688'
    });
}

function successSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'success',
        showConfirmButton: true,
        confirmButtonColor: '#009688'
    })
}