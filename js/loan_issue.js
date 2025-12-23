$(document).ready(function () {

    //Hide Cash Acknowledgement card.. show only if cash enter.
    $('#cashAck').hide();
    $('#bankInfo').hide();

    // Issue Mode
    $('#issued_mode').change(function () {
        var mode = $(this).val();
        // $('#cashAck').hide();

        $('#cash').removeAttr('readonly');
        $('#chequeValue').removeAttr('readonly');
        $('#transaction_value').removeAttr('readonly');

        if (mode == '0') {
            $('.cash_issue').hide();
            $('.checque').hide();
            $('.transaction').hide();
            $('.balance').hide();

            // $('#bankDiv').hide();//show bank id

            $('.paymentType').show();

            turnonCashKeyup();

        } else if (mode == '1') {
            $('.cash_issue').hide();
            $('.checque').hide();
            $('.transaction').hide();
            // $('#bankDiv').hide();//hide bank id
            $('.paymentType').show();
            $('.balance').hide();

        } else {
            $('.cash_issue').hide();
            $('.checque').hide();
            $('.transaction').hide();
            // $('#bankDiv').hide();//hide bank id
            $('.paymentType').hide();
            $('.balance').hide();
        }
        $('#bankInfo').hide(); // Cash Acknowledgement.
        $('#cash').val('');
        // $('#bank_id').val('');
        $('#chequeno').val('');
        $('#chequeValue').val('');
        $('#chequeRemark').val('');
        $('#transaction_id').val('');
        $('#transaction_value').val('');
        $('#transaction_remark').val('');
        $('#payment_type').val('');

        hideCheckSpan()
    })

    // Payment Type
    $('#payment_type').change(function () {
        $('#cash').val('');
        // $('#bank_id').val('');
        $('#chequeno').val('');
        $('#chequeValue').val('');
        $('#chequeRemark').val('');
        $('#transaction_id').val('');
        $('#transaction_value').val('');
        $('#transaction_remark').val('');
        var type = $(this).val();
        var netcash = $('#net_cash').val().replace(/,/g, '');
        let issue_mode = $('#issued_mode').val();
        if (issue_mode == 0) {
            if (type == '0') {
                $('.balance').show();
                $('.cash_issue').show();
                $('#cash').val('');
                $('#cash').attr('readonly', false);
                $('#balance').val('');
                // $('#bankDiv').hide();//hide bank id
                $('#bankInfo').hide();//hide bank id
                $('.checque').hide();
                $('.transaction').hide();
                var ag_id = $('#agent_id').val();
                if (ag_id == '') {
                    $('#cashAck').show(); // Cash Acknowledgement.
                    turnonCashKeyup();
                } else {
                    $('#cash').off('keyup');
                }

            } else if (type == '1') {
                $('.balance').hide();
                $('.cash_issue').hide();
                // $('#bankDiv').show();//show bank id
                $('.checque').hide();
                $('#chequeValue').val('');
                $('#chequeValue').attr('readonly', true);
                $('#balance').val('');
                $('.transaction').hide();
                $('#cashAck').hide(); // Cash Acknowledgement.
                $('#bankInfo').show(); // Cash Acknowledgement.
                resetbankinfoList()

            } else if (type == '2') {
                $('.balance').hide();
                $('.cash_issue').hide();
                // $('#bankDiv').show();//show bank id
                $('.checque').hide();
                $('.transaction').hide();
                $('#transaction_value').val('');
                $('#transaction_value').attr('readonly', true);
                $('#balance').val('');
                $('#cashAck').hide(); // Cash Acknowledgement.
                $('#bankInfo').show();
                resetbankinfoList();
            } else {
                $('.balance').hide();
                $('.cash_issue').hide();
                // $('#bankDiv').hide();//hide bank id
                $('.checque').hide();
                $('.transaction').hide();
                $('#balance').val('');
                $('#cashAck').hide(); // Cash Acknowledgement.
                $('#bankInfo').hide()
            }
        }
        else {
            $('.balance').hide();
            if (type == '0') {
                $('.cash_issue').show();
                $('#cash').val(formatIndianNumber(netcash));
                $('#cash').attr('readonly', true);
                $('#balance').val('0');
                // $('#bankDiv').hide();//hide bank id
                $('.checque').hide();
                $('.transaction').hide();
                $('#bankInfo').hide();
                var ag_id = $('#agent_id').val();
                if (ag_id == '') {
                    $('#cashAck').show(); // Cash Acknowledgement.
                    turnonCashKeyup();
                } else {
                    $('#cash').off('keyup');
                }

            } else if (type == '1') {
                $('.cash_issue').hide();
                // $('#bankDiv').show();//show bank id
                $('.checque').hide();
                $('#chequeValue').val(netcash);
                $('#chequeValue').attr('readonly', true);
                $('#balance').val('0');
                $('.transaction').hide();
                $('#cashAck').hide(); // Cash Acknowledgement.
                $('#bankInfo').show()
                resetbankinfoList()

            } else if (type == '2') {
                $('.cash_issue').hide();
                // $('#bankDiv').show();//show bank id
                $('.checque').hide();
                $('.transaction').hide();
                $('#transaction_value').val(netcash);
                $('#transaction_value').attr('readonly', true);
                $('#balance').val('0');
                $('#cashAck').hide(); // Cash Acknowledgement.
                $('#bankInfo').show()
                resetbankinfoList()

            } else {
                $('.cash_issue').hide();
                // $('#bankDiv').hide();//hide bank id
                $('.checque').hide();
                $('.transaction').hide();
                $('#balance').val('');
                $('#cashAck').hide(); // Cash Acknowledgement.
                $('#bankInfo').hide();
            }
        }

        hideCheckSpan();
    })

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date in the date input to today
        $('#due_start_from').attr('min', today);
    }

    //when cash enter the cash acknowledgement card will be show.
    $('#cash').keyup(function () {
        var cashVal = $(this).val();
        if (cashVal) {
            $('#cashAck').show();
        } else {
            $('#cashAck').hide();
        }
    });
    // Handle checkbox click event
    $(document).on('change', '.verification_bank_update', function () {
        let id = $(this).val(); // Get the ID of the current row
        let cusid = $('#cus_id').val();
        let issue_status = 0;

        // If the current checkbox is checked
        if ($(this).is(':checked')) {
            issue_status = 2; // Checked, set status to 2
            // Disable all other checkboxes except the current one
            $('#bank_data_table').find('input[type="checkbox"]').not(this).prop('disabled', true);
        } else {
            issue_status = 1; // Unchecked, set status to 1
            // Enable all checkboxes again
            $('#bank_data_table').find('input[type="checkbox"]').prop('disabled', false);
        }

        // AJAX request to update the issue_status
        $.ajax({
            url: 'loanIssueFile/updateBankStatus.php',
            type: 'POST',
            data: { "cusid": cusid, "id": id, "issue_status": issue_status },
            dataType: 'json',
            cache: false,
            success: function (response) {
                // Handle the response if needed
            }
        });
    });



    $('#cash_guarentor_name').change(function () { //Select Guarantor Name relationship will show in input.

        let famAdhaarNo = document.querySelector("#cash_guarentor_name").value;
        $('#cash_guarentor').hide();
        $('#compare_finger').val('')
        var cusId = $('#cus_id').val();
        if (famAdhaarNo == cusId) {
            var cus = '1';
        } else {
            var cus = '2';
        }

        $.ajax({
            url: 'loanIssueFile/getFamRelationship.php',
            type: 'POST',
            data: { "adhaarno": famAdhaarNo, "cus": cus, "cusId": cusId },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#relationship").val(result['relation']);
                $("#compare_finger").val(result['fpTemplate']);
                if (result['hand'] == '1') {
                    $('.scanBtn').removeAttr('disabled');
                    var hand = "Put Your Left Thumb"
                } else if (result['hand'] == '2') {
                    $('.scanBtn').removeAttr('disabled');
                    var hand = "Put Your Right Thumb"
                } else {
                    var hand = "Finger Print Not Registered";
                    $('.scanBtn').attr('disabled', true);
                }
                $("#hand_type").text(hand).attr('class', 'text-danger');

            }
        });

    });


    $('.scanBtn').click(function () {
        var g_name = $('#cash_guarentor_name').val();

        if (g_name != '') {

            $(this).attr('disabled', true);
            showOverlay();//loader start

            setTimeout(() => { //Set Timeout, because loadin animation will be intrupped by this capture event
                var quality = 60; //(1 to 100) (recommended minimum 55)
                var timeout = 10; // seconds (minimum=10(recommended), maximum=60, unlimited=0)
                var res = CaptureFinger(quality, timeout);
                if (res.httpStaus) {
                    if (res.data.ErrorCode == "0") {
                        $('#ack_fingerprint').val(res.data.AnsiTemplate); // Take ansi template that is the unique id which is passed by sensor
                    }//Error codes and alerts below
                    else if (res.data.ErrorCode == -1307) {
                        alert('Connect Your Device');
                        $(this).removeAttr('disabled');
                    } else if (res.data.ErrorCode == -1140 || res.data.ErrorCode == 700) {
                        alert('Timeout');
                        $(this).removeAttr('disabled');
                    } else if (res.data.ErrorCode == 720) {
                        alert('Reconnect Device');
                        $(this).removeAttr('disabled');
                    } else if (res.data.ErrorCode == 730) {
                        alert('Capture Finger Again');
                        $(this).removeAttr('disabled');
                    } else {
                        alert('Error Code:' + res.data.ErrorCode);
                        $(this).removeAttr('disabled');
                    }
                }
                else {
                    alert(res.err);
                }

                //Verify the finger is matched with member name
                var compare_finger = $('#compare_finger').val()
                var ack_fingerprint = $('#ack_fingerprint').val()
                var res = VerifyFinger(compare_finger, ack_fingerprint)
                if (res.httpStaus) {
                    if (res.data.Status) {
                        Swal.fire({
                            title: 'Fingerprint Matching',
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                        $('#fingerValidation').val('1');
                        $("#hand_type").text('Done').attr('class', 'text-success');
                    } else {
                        if (res.data.ErrorCode != "0") {
                            alert(res.data.ErrorDescription);
                        }
                        else {
                            Swal.fire({
                                title: 'Fingerprint Not Matching',
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                            $(this).removeAttr('disabled');
                        }
                    }
                } else {
                    alert(res.err)
                }

                hideOverlay();//loader stop

            }, 700) //Timeout End

        } else {//If End
            $('#cash_guarentor').show();
        }

    })//Scan button Onclick end
    function onLoadEditFunction() {//On load for Loan Calculation edit
        $('input#due_start_from').removeAttr('readonly');
        $('select#collection_method').removeAttr('disabled');
    }

    $('#Communitcation_to_cus').change(function () {
        let com = $(this).val();

        if (com == '0') {
            $('#verifyaudio').show();
        } else {
            $('#verifyaudio').hide();
        }
    })

    $('#loan_category').change(function () {
        var loan_cat = $(this).val();
        getSubCategory(loan_cat);
    })

    $('#refresh_cal').click(function () {
        performLoanCalculation();
    });

    $('#day_scheme').change(function () {
        $('#due_start_from').val('');
        $('#maturity_month').val('');
    })
    $('#due_start_from').change(function () {
        var due_start_from = $('#due_start_from').val(); // get start date to calculate maturity date
        var due_period = parseInt($('#due_period').val()); //get due period to calculate maturity date
        var profit_type = $('#profit_type').val()
        if (profit_type == '1') { //Based on the profit method choose due method from input box
            var due_method = $('#due_method_calc').val()
        } else if (profit_type == '2') {
            var due_method = $('#due_method_scheme').val()
        }

        if (due_method == 'Monthly' || due_method == '1') { // if due method is monthly or 1(for scheme) then calculate maturity by month

            var maturityDate = moment(due_start_from, 'YYYY-MM-DD').add(due_period, 'months').subtract(1, 'month').format('YYYY-MM-DD');
            $('#maturity_month').val(maturityDate);

        } else if (due_method == '2') {//if Due method is weekly then calculate maturity by week

            var due_day = parseInt($('#day_scheme').val());

            var momentStartDate = moment(due_start_from, 'YYYY-MM-DD').startOf('day').isoWeekday(due_day);//Create a moment.js object from the start date and set the day of the week to the due day value

            var weeksToAdd = Math.floor(due_period - 1);//Set the weeks to be added by giving due period. subract 1 because by default it taking extra 1 week

            momentStartDate.add(weeksToAdd, 'weeks'); //Add the calculated number of weeks to the start date.

            if (momentStartDate.isBefore(due_start_from)) {
                momentStartDate.add(1, 'week'); //If the resulting maturity date is before the start date, add another week.
            }

            var maturityDate = momentStartDate.format('YYYY-MM-DD'); //Get the final maturity date as a formatted string.

            $('#maturity_month').val(maturityDate);

        } else if (due_method == '3') {
            var momentStartDate = moment(due_start_from, 'YYYY-MM-DD').startOf('day');
            var daysToAdd = Math.floor(due_period - 1);
            momentStartDate.add(daysToAdd, 'days');
            var maturityDate = momentStartDate.format('YYYY-MM-DD');
            $('#maturity_month').val(maturityDate);
        }

    })


    //////////////////////////////////////////////////////////// Bank Info START ///////////////////////////////////////////////////////

    $(document).on("click", "#bankInfoBtn", function () {
        let req_id = $("#req_id").val();
        let cus_id = $("#cus_id").val();
        let bank_name = $("#bank_name").val();
        let branch_name = $("#branch_name").val();
        let account_holder_name = $("#account_holder_name").val();
        let account_number = $("#account_number").val();
        let Ifsc_code = $("#Ifsc_code").val();
        let bank_upload = $("#bank_upload")[0].files[0];
        let bank_upload_id = $("#bank_upload_id").val();
        let bankID = $("#bankID").val();

        if (bank_name != "" && branch_name != "" && account_holder_name != "" && account_number != "" && Ifsc_code != "" && req_id != "") {

            // Using FormData to send file and other data
            let formData = new FormData();
            formData.append("bank_name", bank_name);
            formData.append("branch_name", branch_name);
            formData.append("account_holder_name", account_holder_name);
            formData.append("account_number", account_number);
            formData.append("Ifsc_code", Ifsc_code);
            formData.append("bank_upload", bank_upload); // Append the file
            formData.append("bank_upload_id", bank_upload_id);
            formData.append("bankID", bankID);
            formData.append("reqId", req_id);
            formData.append("cus_id", cus_id);

            $.ajax({
                url: "verificationFile/verification_bank_submit.php",
                type: "POST",
                data: formData, // Use FormData here
                cache: false,
                contentType: false, // Important: Do not process contentType
                processData: false, // Important: Do not process data
                success: function (response) {
                    var insresult = response.includes("Inserted");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $("#bankInsertOk").show();
                        setTimeout(function () {
                            $("#bankInsertOk").fadeOut("fast");
                        }, 2000);

                    } else if (updresult) {
                        $("#bankUpdateok").show();
                        setTimeout(function () {
                            $("#bankUpdateok").fadeOut("fast");
                        }, 2000);

                    } else {
                        $("#bankNotOk").show();
                        setTimeout(function () {
                            $("#bankNotOk").fadeOut("fast");
                        }, 2000);

                    }

                    resetbankInfo();
                },
            });

            $("#bankNameCheck").hide();
            $("#branchCheck").hide();
            $("#accholdCheck").hide();
            $("#accnoCheck").hide();
            $("#ifscCheck").hide();

        } else {

            if (bank_name == "") {
                $("#bankNameCheck").show();
            } else {
                $("#bankNameCheck").hide();
            }

            if (branch_name == "") {
                $("#branchCheck").show();
            } else {
                $("#branchCheck").hide();
            }

            if (account_holder_name == "") {
                $("#accholdCheck").show();
            } else {
                $("#accholdCheck").hide();
            }

            if (account_number == "") {
                $("#accnoCheck").show();
            } else {
                $("#accnoCheck").hide();
            }

            if (Ifsc_code == "") {
                $("#ifscCheck").show();
            } else {
                $("#ifscCheck").hide();
            }

        }
    });

    $("body").on("click", "#verification_bank_edit", function () {
        let id = $(this).attr("value");

        $.ajax({
            url: "verificationFile/verification_bank_edit.php",
            type: "POST",
            data: { id: id },
            dataType: "json",
            cache: false,
            success: function (result) {
                $("#bankID").val(result["id"]);
                $("#bank_name").val(result["bankName"]);
                $("#branch_name").val(result["branch"]);
                $("#account_holder_name").val(result["accHolderName"]);
                $("#account_number").val(result["acc_no"]);
                $("#Ifsc_code").val(result["ifsc"]);
                $("#bank_upload_id").val(result["upload"]);
            },
        });
    });

    $("body").on("click", "#verification_bank_delete", function () {
        var isok = confirm("Do you want delete this Bank Info?");
        if (isok == false) {
            return false;
        } else {
            var bankid = $(this).attr("value");

            $.ajax({
                url: "verificationFile/verification_bank_delete.php",
                type: "POST",
                data: { bankid: bankid },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");

                    if (delresult) {
                        $("#bankDeleteOk").show();
                        setTimeout(function () {
                            $("#bankDeleteOk").fadeOut("fast");
                        }, 2000);

                    } else {
                        $("#bankDeleteNotOk").show();
                        setTimeout(function () {
                            $("#bankDeleteNotOk").fadeOut("fast");
                        }, 2000);
                    }

                    resetbankInfo();
                },
            });
        }
    });

    //////////////////////////////////////////////////////////// Bank Info END ///////////////////////////////////////////////////////


    $('#submit_loanIssue').click(function (e) {  // loan Issue Submit Validation.
        hideCheckSpan();

        function proceedAfterCalc() {
            // run validation
            if (!loanIssueSumitValidation()) {
                e.preventDefault();  // stop default submit until we finish everything
                return false;
            }

            // confirm
            let confirmAction = confirm("Are you sure you want to submit Loan Issue ?");
            if (!confirmAction) {
                e.preventDefault();  // stop default submit until we finish everything
                return false;
            }

            // finally submit the form
            $('#loanIssueForm').submit();
        }

        // If refresh button is visible → run calculation first
        if ($("#refresh_cal").is(":visible")) {

            performLoanCalculation(function () {
                proceedAfterCalc();
            });

        } else {
            // Directly continue without calculation
            proceedAfterCalc();
        }
    });

});


$(function () {
    turnonCashKeyup();

    getImage(); // To show customer image when window onload.
    guarentorName(); //To Show Guarentor Name.
    getLc(); // To get loan Category.

    getCategoryInfo(); //To show Category Info.
    getAgentDetails(); //To Get Agent Details.
    profitCalculationInfo();
    cashAckName(); // To show Cash Acknowledgement Name.
    $('input').not('#int_rate, #due_period, #doc_charge, #proc_fee,#due_start_from,#chequeno,#chequeRemark,#transaction_id,#transaction_remark, #bank_name, #branch_name, #account_holder_name, #account_number, #Ifsc_code, #bank_upload').attr('readonly', true);
    $('select').not('#issued_mode, #cash_guarentor_name,#payment_type,#collection_method').attr('disabled', true);
    checkBalance(); // To check in DB.
    setTimeout(() => {
        getCustomerLoanCounts();// To Get loan existing type
    }, 1000);

});


function turnonCashKeyup() {
    //Check Cash limit based on Net Cash
    $('#cash').keyup(function () {
        var cash = $("#cash").val().replace(/,/g, '');
        $('#cash').val(formatIndianNumber(cash));
        checkIssuedAmount('0');
    });
    $('#chequeValue').keyup(function () {
        checkIssuedAmount('1');
    });
    $('#transaction_value').keyup(function () {
        checkIssuedAmount('2');
    });
}

// Cus img show onload.
function getImage() {
    let imgName = $('#cus_image').val();

    $('#imgshow').attr('src', "uploads/request/customer/" + imgName + " ");

    var guarentorimg = $('#guarentor_image').val();
    $('#imgshows').attr('src', "uploads/verification/guarentor/" + guarentorimg + " ");
}

function getCustomerLoanCounts() {
    var cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/getCustomerLoanCounts.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#cus_exist_type').val(response['existing_type'])
        },
        error: function () {
            $('#cus_exist_type').val('Renewal');
        }
    })
}

function resetbankinfoList() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'loanIssueFile/bankDetails.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#bankResetTable").empty();
            $("#bankResetTable").html(html);
        }
    });
}

//Guarentor Name
function guarentorName() {
    let cus_id = $('#cus_id').val();
    var guarentor_name = $('#guarentor_name_upd').val();
    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#guarentor_name").empty();
            $("#guarentor_name").append("<option value=''>" + 'Select Guarantor' + "</option>");
            for (var i = 0; i < len; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                var selected = '';
                if (guarentor_name != '' && guarentor_name == fam_id) {
                    selected = 'selected';
                }
                $("#guarentor_name").append("<option value='" + fam_id + "' " + selected + ">" + fam_name + "</option>");
            }
            // Sort guarentor_name dropdown
            sortDropdownAlphabetically("#guarentor_name");
        }
    });
}

//Loan Category
function getLc() {
    var lc_id = $('#loan_category_lc').val();

    $.ajax({
        url: 'loanIssueFile/getLoanCategoryforIssue.php',
        type: 'POST',
        data: { "lc_id": lc_id },
        dataType: 'json',
        success: function (result) {
            $('#loan_category').val(result);
        }
    })
}
function getCategoryInfo() {
    var sub_category_upd = $('#sub_category_upd').val();
    var sub_cat = $('#sub_category').val();
    var loan_category = $('#loan_category_lc').val();
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat, 'loan_category': loan_category },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            category_info = ''
            $('#moduleTable').empty();
            $('#moduleTable').append('<tbody><tr>');
            if (response.length != 0) {
                var tb = 14;
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody tr').append(`<td><label for="disabledInput">` + response[i]['loan_category_ref_name'] + `</label><input type="text" class="form-control" id="category_info" name="category_info[]" 
                    value='`+ category_info + `' tabindex='` + tb + `'readonly required placeholder='Enter ` + response[i]['loan_category_ref_name'] + `'></td>`);
                    // tb++;
                }
                $('#moduleTable').append(`</tr></tbody>`);

                var category_content = $('#moduleTable tbody tr').html(); //To get the appended category list

                var category_count = $('#moduleTable tbody tr').find('td').length;//To find input fields count
                getCategoryInputs(category_count, category_content, sub_category_upd);

            }
        }
    });


    function getCategoryInputs(category_count, category_content, sub_category_upd) {

        var req_id = $('#req_id').val();
        $.ajax({
            url: 'loanIssueFile/getCategoryInfoForIssue.php',
            data: { 'req_id': req_id, 'sub_category_upd': sub_category_upd },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                var trCount = Math.ceil(response.length / category_count); // number of rows needed

                for (var j = 0; j < trCount - 1; j++) {
                    $('#moduleTable tbody').append('<tr>' + category_content + '</tr>');
                    // $('#moduleTable tbody tr:last input').filter(':last').val('');
                }
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody input').each(function (index) {
                        $(this).val(response[index]);
                    });
                }
            }
        })
    }

}
//Get Category info From Request
function profitCalculationInfo() {
    var sub_cat = $('#sub_category').val();
    var profit_type = $('#profit_type').val();
    var due_method = $('#due_method_scheme').val();
    var loan_cat = $('#loan_category').val();
    if (profit_type != '') { //Call only if profit type autamatically set
        profitCalAjax(profit_type, sub_cat, loan_cat); //Call for edit
    }
    if (due_method != '') {//Call only if due method autamatically set
        schemeAjax(due_method, sub_cat); //Call for edit
    }
    setTimeout(function () {
        var scheme_name = $('#scheme_upd').val();

        if (scheme_name != '') {//Call only if scheme name autamatically set
            schemeCalAjax(scheme_name); //Call for edit
        }
    }, 1000)

    $('#profit_type').change(function () {//On change evemt

        $('.calculation').hide(); // to hide calculation inputs
        $('.scheme').hide();// to hide Scheme inputs
        $('.emi-calculation').hide(); // to hide calculation inputs
        $('.interest-calculation').hide(); // to hide calculation inputs
        $('#profit_method').empty(); // to empty calculation inputs
        $('#calc_method').val(''); // to empty calculation inputs

        $('#due_method_scheme').val(''); // to clear due method selection 
        $('.day_scheme').hide(); // to Hide day shceme
        $('#day_scheme').val(''); // to clear day scheme selection 
        $('#scheme_name').val(''); // to clear scheme name selection 

        $('#int_rate').val('');
        $("#doc_charge").val("");
        $("#proc_fee").val("");
        $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');

        $('#due_start_from').val('');
        $('#maturity_month').val('');

        var profit_type = $(this).val();
        var sub_cat = $('#sub_category').val();
        var loan_cat = $('#loan_category').val();
        profitCalAjax(profit_type, sub_cat, loan_cat)

    });//Profit Type change event end

    $('#due_method_scheme').change(function () {
        var due_method = $(this).val();
        $('.scheme-calculation').hide();
        if (due_method == '2') { // show weekdays only if weekly due method selected
            $('.day_scheme').show();
        } else {
            $('.day_scheme').hide();
        }

        var sub_cat = $('#sub_category').val();
        schemeAjax(due_method, sub_cat);

        $('#int_rate').val(''); $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');

        $('#due_start_from').val('');
        $('#maturity_month').val('');
    });

    $('#scheme_name').change(function () { //Scheme Name change event
        var scheme_id = $(this).val();
        schemeCalAjax(scheme_id);
        $('.scheme-calculation').show();
    })
}

//
function profitCalAjax(profit_type, sub_cat, loan_cat) {
    var profit_method_upd = $('#profit_method_upd').val()
    if ($('#int_rate_upd').val()) { var int_rate_upd = $('#int_rate_upd').val(); } else { var int_rate_upd = ''; }
    if ($('#due_period_upd').val()) { var due_period_upd = $('#due_period_upd').val(); } else { var due_period_upd = ''; }
    if ($('#doc_charge_upd').val()) { var doc_charge_upd = $('#doc_charge_upd').val(); } else { var doc_charge_upd = ''; }
    if ($('#proc_fee_upd').val()) { var proc_fee_upd = $('#proc_fee_upd').val(); } else { var proc_fee_upd = ''; }
    if (profit_type == '1') {//if Calculation selected
        $('.calculation').show();
        $('.scheme').hide();
        $('.scheme-calculation').hide();
        $.ajax({ // To show profit calculation infos based on sub category
            url: 'verificationFile/LoanCalculation/getProfitCalculationInfo.php',
            data: { 'sub_cat': sub_cat, 'loan_cat': loan_cat },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                if (response['due_type'] == 'emi') {
                    $('.emi-calculation').show();
                    $('.interest-calculation').hide();
                    $('#due_type').val('EMI');

                    var profit_method = response['profit_method'].split(','); //Splitting into array by exploding comma (',')
                    $('#profit_method').empty();
                    $('#profit_method').append(`<option value=''>Select Profit Method</option>`);
                    for (var i = 0; i < profit_method.length; i++) {
                        if (profit_method[i] == 'pre_intrest') { valuee = 'Pre Benefit'; } else if (profit_method[i] == 'after_intrest') { valuee = 'After Benefit'; }
                        var selected = '';
                        if (profit_method_upd != '' && profit_method_upd != undefined && profit_method_upd == profit_method[i]) {
                            selected = 'selected';
                        }
                        $('#profit_method').append(`<option value='` + profit_method[i] + `' ` + selected + `>` + valuee + `</option>`);
                    }
                    $('#calc_method').val('');

                    //To set min and maximum 
                    $('.min-max-int').text('* (' + response['intrest_rate_min'] + '% - ' + response['intrest_rate_max'] + '%) ');

                    $('#int_rate').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["intrest_rate_min"])};let max = ${parseFloat(response["intrest_rate_max"])}; if (!isNaN(val)) { if (val > max) {alert("Enter Lesser Value"); $(this).val(""); } else if (val < min) { alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $('#int_rate').val(int_rate_upd);

                    $('.min-max-due').text('* (' + response['due_period_min'] + ' - ' + response['due_period_max'] + ') ');
                    $('#due_period').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["due_period_min"])};let max = ${parseFloat(response["due_period_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $('#due_period').val(due_period_upd);

                    if (response['doc_charge_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-doc').text('* (' + type + response['document_charge_min'] + ' - ' + type + response['document_charge_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['doc_charge_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-doc').text('* (' + response['document_charge_min'] + type + ' - ' + response['document_charge_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }

                    // Setting onChange event to ensure the value is within the specified range
                    $('#doc_charge').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["document_charge_min"])};let max = ${parseFloat(response["document_charge_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`);

                    // Set the value for the doc_charge field
                    $('#doc_charge').val(doc_charge_upd);

                    if (response['proc_fee_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-proc').text('* (' + type + response['processing_fee_min'] + ' - ' + type + response['processing_fee_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['proc_fee_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-proc').text('* (' + response['processing_fee_min'] + type + ' - ' + response['processing_fee_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }

                    // Setting onChange event to ensure the value is within the specified range
                    $('#proc_fee').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["processing_fee_min"])};let max = ${parseFloat(response["processing_fee_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`);

                    // Set the value for the doc_charge field
                    $('#proc_fee').val(proc_fee_upd);

                } else if (response["due_type"] == "intrest") {
                    $(".emi-calculation").hide();
                    $(".interest-calculation").show();
                    $("#due_type").val("Interest");
                    $("#profit_method").empty();

                    $("#calc_method").val(response["calculate_method"]);
                    if (response["calculate_method"] == "monthly") {
                        $("#calc_method").val("Monthly");
                    } else if (response["calculate_method"] == "days") {
                        $("#calc_method").val("Days");
                    }

                    //To set min and maximum
                    $(".min-max-int").text("* (" + response["intrest_rate_min"] + "% - " + response["intrest_rate_max"] + "%) ");

                    $("#int_rate").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["intrest_rate_min"])};let max = ${parseFloat(response["intrest_rate_max"])}; if (!isNaN(val)) { if (val > max) {alert("Enter Lesser Value"); $(this).val(""); } else if (val < min) { alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#int_rate").val(int_rate_upd);

                    $(".min-max-due").text("* (" + response["due_period_min"] + " - " + response["due_period_max"] + ") ");

                    $("#due_period").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["due_period_min"])};let max = ${parseFloat(response["due_period_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#due_period").val(due_period_upd);

                    if (response["doc_charge_type"] == "amt") {
                        type = "₹";
                        $(".min-max-doc").text("* (" + type + response["document_charge_min"] + " - " + type + response["document_charge_max"] + ") "); // Set min-max values with ₹ symbol before the numbers
                    } else if (response["doc_charge_type"] == "percentage") {
                        type = "%";
                        $(".min-max-doc").text("* (" + response["document_charge_min"] + type + " - " + response["document_charge_max"] + type + ") "); // Set min-max values with % symbol after the numbers
                    } 

                    $("#doc_charge").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["document_charge_min"])};let max = ${parseFloat(response["document_charge_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#doc_charge").val(doc_charge_upd);

                    if (response["proc_fee_type"] == "amt") {
                        type = "₹";  // Set ₹ symbol before the numbers
                        $(".min-max-proc").text("* (" + type + response["processing_fee_min"] + " - " + type + response["processing_fee_max"] + ") "); // Set min-max values with ₹ symbol before the numbers
                    } else if (response["proc_fee_type"] == "percentage") {
                        type = "%"; // Set % symbol after the numbers
                        $(".min-max-proc").text("* (" + response["processing_fee_min"] + type + " - " + response["processing_fee_max"] + type + ") "); // Set min-max values with % symbol after the numbers
                    }

                    $("#proc_fee").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["processing_fee_min"])};let max = ${parseFloat(response["processing_fee_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#proc_fee").val(proc_fee_upd);
                }
            }
        })
    } else if (profit_type == '2') { //if Scheme selected
        $('.calculation').hide(); // to hide calculation inputs
        $('.scheme').show(); // to show scheme inputs
        $('.scheme-calculation').show();
    } else {
        $('.calculation').hide(); // to hide calculation inputs
        $('.scheme').hide(); // to hide scheme inputs
        $('.scheme-calculation').hide();
    }
}

//
function schemeAjax(due_method, sub_cat) {
    var scheme_upd = $('#scheme_upd').val();
    $.ajax({ //To show scheme names based on sub category
        url: 'verificationFile/LoanCalculation/getSchemeNames.php',
        data: { 'sub_cat': sub_cat, 'due_method': due_method },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#scheme_name').empty();
            $('#scheme_name').append(`<option value=''>Select Scheme Name</option>`);
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (scheme_upd != '' && scheme_upd != undefined && scheme_upd == response[i]['scheme_id']) {
                    selected = 'selected';
                    $('#scheme_name_ack').val(response[i]['scheme_id']);
                }
                $('#scheme_name').append(`<option value='` + response[i]['scheme_id'] + `' ` + selected + `>` + response[i]['scheme_name'] + `</option>`);
            }
        }
    });
}

//
function schemeCalAjax(scheme_id) {
    if (scheme_id != '') {
        if ($('#int_rate_upd').val()) { var int_rate_upd = $('#int_rate_upd').val(); } else { var int_rate_upd = ''; }
        if ($('#due_period_upd').val()) { var due_period_upd = $('#due_period_upd').val(); } else { var due_period_upd = ''; }
        if ($('#doc_charge_upd').val()) { var doc_charge_upd = $('#doc_charge_upd').val(); } else { var doc_charge_upd = ''; }
        if ($('#proc_fee_upd').val()) { var proc_fee_upd = $('#proc_fee_upd').val(); } else { var proc_fee_upd = ''; }
        var scheme_profit_method_upd = $('#scheme_profit_method_upd').val()
        $.ajax({ //show scheme based loan info using scheme id
            url: 'verificationFile/LoanCalculation/getSchemeDetails.php',
            dataType: 'json',
            type: 'post',
            data: { 'scheme_id': scheme_id },
            cache: false,
            success: function (response) {
                //To set min and maximum 
                var profit_method = response['profit_method'].split(','); //Splitting into array by exploding comma (',')
                $('#scheme_profit_method').empty();
                $('#scheme_profit_method').append(`<option value=''>Select Profit Method</option>`);
                for (var i = 0; i < profit_method.length; i++) {
                    if (profit_method[i] == 'pre_intrest') { valuee = 'Pre Benefit'; } else if (profit_method[i] == 'after_intrest') { valuee = 'After Benefit'; }
                    var selected = '';
                    if (scheme_profit_method_upd != '' && scheme_profit_method_upd != undefined && scheme_profit_method_upd == profit_method[i]) {
                        selected = 'selected';
                    }
                    $('#scheme_profit_method').append(`<option value='` + profit_method[i] + `' ` + selected + `>` + valuee + `</option>`);
                }
                // $('#int_rate').val(response['intrest_rate']); $('#int_rate').attr('readonly', true); // setting readonly due to fixed interest

                $('#due_period').val(response['due_period']); $('#due_period').attr('readonly', true); // setting readonly due to fixed due period

                if (response['intreset_type'] == 'amt') { type = '₹' } else if (response['intreset_type'] == 'percentage') { type = '%'; } //Setting symbols

                $('.min-max-int').text('* (' + response['intreset_min'] + ' ' + type + ' - ' + response['intreset_max'] + ' ' + type + ') '); //setting min max values in span

                $('#int_rate').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["intreset_min"])};let max = ${parseFloat(response["intreset_max"])}; if (!isNaN(val)) { if (val > max) {alert("Enter Lesser Value"); $(this).val(""); } else if (val < min) { alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                $('#int_rate').val(int_rate_upd);

                if (response['doc_charge_type'] == 'amt') { type = '₹' } else if (response['doc_charge_type'] == 'percentage') { type = '%'; } //Setting symbols

                $('.min-max-doc').text('* (' + response['doc_charge_min'] + ' ' + type + ' - ' + response['doc_charge_max'] + ' ' + type + ') '); //setting min max values in span

                $('#doc_charge').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["doc_charge_min"])};let max = ${parseFloat(response["doc_charge_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                $('#doc_charge').val(doc_charge_upd);

                if (response['proc_fee_type'] == 'amt') { type = '₹' } else if (response['proc_fee_type'] == 'percentage') { type = '%'; }//Setting symbols

                $('.min-max-proc').text('* (' + response['proc_fee_min'] + ' ' + type + ' - ' + response['proc_fee_max'] + ' ' + type + ') ');//setting min max values in span

                $('#proc_fee').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["proc_fee_min"])};let max = ${parseFloat(response["proc_fee_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage
                $('#proc_fee').val(proc_fee_upd);
            }
        })
    } else {
        $('#int_rate').val(''); $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');
        $('#due_start_from').val('');
        $('#maturity_month').val('');
    }
}

//To Get Loan Calculation for After Interest
function getLoanAfterInterest() {
    var loan_amt   = $('#loan_amt').val().replace(/[, ]/g, '');
    var int_rate   = $('#int_rate').val().replace(/[, ]/g, '');
    var due_period = $('#due_period').val().replace(/[, ]/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[, ]/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[, ]/g, '');


    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card
    // principal amt as same as loan amt for after interest

    var interest_rate = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    var tot_amt = parseInt(loan_amt) + parseFloat(interest_rate); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot));

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;
    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - interest_rate) + ')'); //To show the difference amount from old to new
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(loan_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
    checkBalance()
}

//To Get Loan Calculation for Pre Interest
function getLoanPreInterest() {
    var loan_amt   = $('#loan_amt').val().replace(/[, ]/g, '');
    var int_rate   = $('#int_rate').val().replace(/[, ]/g, '');
    var due_period = $('#due_period').val().replace(/[, ]/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[, ]/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[, ]/g, '');

    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card

    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot))

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
    checkBalance()
}

//To Get Loan Calculation for Interest due type
function getLoanInterest() {
    var loan_amt   = $("#loan_amt").val().replace(/[, ]/g, '');
    var int_rate   = $("#int_rate").val().replace(/[, ]/g, '');
    var doc_charge = $("#doc_charge").val().replace(/[, ]/g, '');
    var proc_fee   = $("#proc_fee").val().replace(/[, ]/g, '');

    var calc_method = $("#calc_method").val();

    $("#loan_amt_cal").val(formatIndianNumber(loan_amt));

    let int_amt;

    if (calc_method === 'Monthly') {
        int_amt = (loan_amt * (int_rate / 100)).toFixed(0);
    } else if (calc_method === 'Days') {
        int_amt = (loan_amt * (int_rate / 100) / 30).toFixed(0);
    }

    var roundedInterest = Math.ceil(int_amt / 5) * 5;
    if (roundedInterest < int_amt) {
        roundedInterest += 5;
    }

    $(".int-diff").text("* (Difference: +" + parseInt(roundedInterest - int_amt) + ")");
    $("#int_amt_cal").val(formatIndianNumber(roundedInterest));

    var doc_type = $(".min-max-doc").text();
    if (doc_type.includes("₹")) {
        var doc_charge = parseInt(doc_charge);
    } else if (doc_type.includes("%")) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100);
    }

    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5;
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }

    $(".doc-diff").text("* (Difference: +" + parseInt(roundeddoccharge - doc_charge) + ")");
    $("#doc_charge_cal").val(formatIndianNumber(roundeddoccharge));

    var proc_type = $(".min-max-proc").text();
    if (proc_type.includes("₹")) {
        var proc_fee = parseInt(proc_fee);
    } else if (proc_type.includes("%")) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);
    }

    var roundeprocfee = Math.ceil(proc_fee / 5) * 5;
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }

    $(".proc-diff").text("* (Difference: +" + parseInt(roundeprocfee - proc_fee) + ")");
    $("#proc_fee_cal").val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseInt(doc_charge) - parseInt(proc_fee);
    $("#net_cash_cal").val(formatIndianNumber(net_cash));
}

function getSchemeAfterIntreset() {
    var loan_amt   = $('#loan_amt').val().replace(/[\s,]/g, '');
    var int_rate   = $('#int_rate').val().replace(/[\s,]/g, '');
    var due_period = $('#due_period').val().replace(/[\s,]/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[\s,]/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[\s,]/g, '');

    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card
    // principal amt as same as loan amt for after interest
    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    }
    
    var tot_amt = parseInt(loan_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot))

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
    checkBalance()
}
function getSchemePreIntreset() {
    var loan_amt   = $('#loan_amt').val().replace(/[\s,]/g, '');
    var int_rate   = $('#int_rate').val().replace(/[\s,]/g, '');
    var due_period = $('#due_period').val().replace(/[\s,]/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[\s,]/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[\s,]/g, '');


    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card

    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    }

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot))

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: ' + parseInt(new_princ - princ_amt) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
    checkBalance()
}

//Get Agent Name 
function getAgentDetails() {
    var req_id = $('#req_id').val();

    $.ajax({
        url: 'loanIssueFile/getAgentDetails.php',
        type: 'POST',
        data: { "req_id": req_id },
        dataType: 'json',
        success: function (result) {
            var ag_name = result['ag_name'];
            var lp = result['loan_payment'];
            var agent_id = result['agent_id'];

            if (agent_id != '' && lp == '0') {
                $('#agent').val(ag_name);
                $('#issue_to').val(ag_name);
                $('.issued_to_type').text('* (Agent)');
                $('#agent_id').val(agent_id);

                $('#cashAck').hide(); //hide cash acknowledgement if agent is the payer/ loan issue person

            } else {
                var cus_name = $('#cus_name').val();
                // $('#agent').val(cus_name);
                $('#issue_to').val(cus_name);
                $('.issued_to_type').text('* (Customer)');

                $('#cashAck').show();
            }

        }
    })
}

//Check Issue Amount is equal to Net Cash.
function checkIssuedAmount(type) {
    var totalValue = 0;
    var netCash = 0;

    function calcBal() {
        var cashValue = parseInt($('#cash').val().replace(/,/g, ''));
        var chequeValue = parseInt($('#chequeValue').val().replace(/,/g, ''));
        var transactionValue = parseInt($('#transaction_value').val().replace(/,/g, ''));
        totalValue = (isNaN(cashValue) ? 0 : cashValue) + (isNaN(chequeValue) ? 0 : chequeValue) + (isNaN(transactionValue) ? 0 : transactionValue);
        netCash = parseInt($('#net_cash').val().replace(/,/g, ''));
        var bal = parseInt(netCash) - parseInt(totalValue);
        if (bal >= 0) {
            $('#balance').val(formatIndianNumber(bal));
        }
    }

    calcBal();
    var issueMode = $('#issued_mode').val();

    if (issueMode == '0') { //Split payment.

        if (type == '0') { //Cash
            if (totalValue > netCash) {
                alert('Please Enter the Amount Less than "Balance To Issue!"');
                $('#cash').val('');
                calcBal()
            }
        } else if (type == '1') { //Cheque Value
            if (totalValue > netCash) {
                alert('Please Enter the Amount Less than "Balance To Issue!"!');
                $('#chequeValue').val('');
                calcBal()
            }

        } else if (type == '2') {
            if (totalValue > netCash) { //Transaction 
                alert('Please Enter the Amount Less than "Balance To Issue!"!');
                $('#transaction_value').val('');
                calcBal()
            }
        }

    }
}

//cash Acknowledgement Name 
function cashAckName() {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let cus_name = $('#cus_name').val();

    $.ajax({
        url: 'loanIssueFile/famnameForloanIssue.php',
        type: 'post',
        data: { "reqId": req_id, "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#cash_guarentor_name").empty();
            $("#cash_guarentor_name").append("<option value=''>" + 'Select Guarantor' + "</option>");
            $("#cash_guarentor_name").append("<option value='" + cus_id + "'>" + cus_name + "</option>");
            for (var i = 0; i < len; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_aadharno = response[i]['aadharno'];
                $("#cash_guarentor_name").append("<option value='" + fam_aadharno + "'>" + fam_name + "</option>");
            }
        }
    });
}

//To Check Loan Balance
function checkBalance() {
    var req_id = $('#req_id').val();
    $.ajax({
        url: 'loanIssueFile/getLoanBalance.php',
        type: 'POST',
        data: { "req_id": req_id },
        dataType: 'json',
        success: function (response) {
            if (response['rowCnt'] > '0') {
                $('#net_cash').val(moneyFormatIndia(response['balance_amount']));
                BalanceAmount = response['balance_amount'];
                if (response['balance_amount'] > '0') {
                    $('#int_rate').attr('readonly', true);
                    $('#due_period').attr('readonly', true);
                    $('#doc_charge').attr('readonly', true);
                    $('#proc_fee').attr('readonly', true);
                    $('#due_start_from').attr('readonly', true);
                    $('#refresh_cal').hide();
                }
                else if (response['balance_amount'] == '0') {
                    //Once Balance Zero then disabled all field.
                    $('#int_rate').attr('readonly', true);
                    $('#due_period').attr('readonly', true);
                    $('#doc_charge').attr('readonly', true);
                    $('#proc_fee').attr('readonly', true);
                    $('#issued_mode').attr('disabled', true);
                    $('#due_start_from').attr('disabled', true);
                    $('#cash_guarentor_name').attr('disabled', true);
                    $('#submit_loanIssue').hide();
                }
            } else {
                var netcashamnt = $('#net_cash_cal').val(); 
                $('#net_cash').val(netcashamnt);

            }

        }
    })

}

//Submit Validation
function loanIssueSumitValidation() {
    var issueMode = $('#issued_mode').val(); var paymenType = $('#payment_type').val(); var cash = $('#cash').val(); var guarentorName = $('#cash_guarentor_name').val();
    // var fingerMatch = $('#fingerValidation').val();
    var ag_id = $('#agent_id').val(); 
    // var bank_id = $('#bank_id').val();
    var validation = true ;
    //Check Issue Mode
    if (issueMode == '') {
        event.preventDefault();
        $('#issue').show();
        validation = false ;
    } else {
        $('#issue').hide();
    }

    //check payment type
    if (paymenType == '') {
        event.preventDefault();
        $('#pay_type').show();
        validation = false ;
    } else {
        $('#pay_type').hide();
    }

    //Cash
    if (paymenType == '0') {
        if (cash == '') {
            event.preventDefault();
            $('#cash_amnt').show();
            validation = false ;
        } else {
            $('#cash_amnt').hide();
        }
    }

    if (paymenType == '1' || paymenType == '2') {
        // if (bank_id == '') {
        //     event.preventDefault();
        //     $('#bank_idCheck').show();
        //     validation = false ;
        // } else {
        //     $('#bank_idCheck').hide();
        // }

        if (!isAnyCheckboxChecked()) {
            event.preventDefault(); // Prevent form submission if no checkbox is checked
            alert('Please select Bank Info.'); // Show error message
            validation = false ;
        }
    }

    if (ag_id == '') { // check only if agent id is not set/ this issue is not for agent
        if (cash != '') {
            if (guarentorName == '') {
                event.preventDefault();
                $('#cash_guarentor').show();
                validation = false ;
            } else {
                $('#cash_guarentor').hide();
            }

            // if (fingerMatch != '1') {
            //     event.preventDefault();
            //     $('#finger_check').show();
            // } else {
            //     $('#finger_check').hide();
            // }
        }
    }
    return validation;
}

//Span Hide
function hideCheckSpan() {
    $('#cheque_num').hide(); $('#cheque_val').hide(); $('#cheque_remark').hide(); $('#transact_id').hide(); $('#transact_val').hide(); $('#transact_remark').hide(); $('#pay_type').hide(); $('#cash_amnt').hide(); $('#cash_guarentor').hide(); $('#val_check').hide(); $('#bank_idCheck').hide();
}

function isAnyCheckboxChecked() {
    return $('#bank_data_table').find('input[type="checkbox"]:checked').length > 0;
}

function resetbankInfo() {
    let cus_id = $("#cus_id").val();
    $.ajax({
        url: "verificationFile/verification_bank_reset.php",
        type: "POST",
        data: { cus_id: cus_id },
        cache: false,
        success: function (html) {
            $("#bankTable").empty();
            $("#bankTable").html(html);

            $("#bank_name").val("");
            $("#branch_name").val("");
            $("#account_holder_name").val("");
            $("#account_number").val("");
            $("#Ifsc_code").val("");
            $("#bank_upload").val("");
            $("#bankID").val("");
        },
    });
}

function performLoanCalculation(callback){
    var intrest_rate = $("#int_rate").val();
    var doc_charge = $("#doc_charge").val();
    var proc_fee = $("#proc_fee").val();
    var due_period = $("#due_period").val();
    var profit_method = $("#profit_method").val();

    if( intrest_rate == "" || doc_charge == "" || proc_fee == "" || due_period == "" || profit_method == ""){
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Fill out Loan Info!',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonColor: '#009688'
        });

        event.preventDefault();
        return;
    }

    var profit_method = $('#profit_method').val(); // if profit method changes, due type is EMI
    var due_type = $('#due_type').val();

    if (profit_method == "after_intrest" && due_type == "EMI") {
        getLoanAfterInterest();
        
    } else if (profit_method == 'pre_intrest') {
        getLoanPreInterest();
        
    }

    if (due_type == 'Interest') {
        getLoanInterest();
        
    }

    var scheme_profit_method = $('#scheme_profit_method').val(); // if profit method changes, due type is EMI
    if (scheme_profit_method == 'after_intrest') {
        getSchemeAfterIntreset(); 

    } else if (scheme_profit_method == 'pre_intrest') {
        getSchemePreIntreset(); 

    }

    // var due_method_scheme = $('#due_method_scheme').val();
    // if (due_method_scheme == '1') {//Monthly scheme as 1
    //     getLoanMonthly(); changeInttoBen()
    // } else if (due_method_scheme == '2') {//Weekly scheme as 2
    //     getLoanWeekly(); changeInttoBen()
    // } else if (due_method_scheme == '3') {//Daily scheme as 3
    //     getLoanDaily(); changeInttoBen()
    // }

    changeInttoBen();

    function changeInttoBen() {
        let due_type = document.getElementById("due_type");
        let int_label = document.querySelector("#int_amt_cal");
        if (due_type.value == "Interest") {
            // Set its value to 'Benefit Amount'
            int_label.previousElementSibling.textContent = "Benefit Amount";
            $('.emi_div').hide();
        } else {
            int_label.previousElementSibling.textContent = "Interest Amount";
            $('.emi_div').show();
        }
    }

    if (typeof callback === 'function') callback();

}