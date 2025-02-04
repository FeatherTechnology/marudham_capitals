$(document).ready(function () {

    //Hide Cash Acknowledgement card.. show only if cash enter.
    $('#cashAck').hide();

    // Issue Mode
    $('#issued_mode').change(function () {
        var mode = $(this).val();
        // $('#cashAck').hide();

        $('#cash').removeAttr('readonly');
        $('#chequeValue').removeAttr('readonly');
        $('#transaction_value').removeAttr('readonly');

        if (mode == '0') {
            $('.cash_issue').show();
            $('.checque').show();
            $('.transaction').show();
            $('.balance').show();

            $('#bankDiv').show();//show bank id

            $('.paymentType').hide();

            turnonCashKeyup();

        } else if (mode == '1') {
            $('.cash_issue').hide();
            $('.checque').hide();
            $('.transaction').hide();
            $('#bankDiv').hide();//hide bank id
            $('.paymentType').show();
            $('.balance').hide();

        } else {
            $('.cash_issue').hide();
            $('.checque').hide();
            $('.transaction').hide();
            $('#bankDiv').hide();//hide bank id
            $('.paymentType').hide();
            $('.balance').hide();
        }

        $('#cash').val('');
        $('#bank_id').val('');
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
        $('#bank_id').val('');
        $('#chequeno').val('');
        $('#chequeValue').val('');
        $('#chequeRemark').val('');
        $('#transaction_id').val('');
        $('#transaction_value').val('');
        $('#transaction_remark').val('');
        var type = $(this).val();
        var netcash = $('#net_cash').val();

        if (type == '0') {
            $('.cash_issue').show();
            $('#cash').val(netcash);
            $('#cash').attr('readonly', true);
            $('#balance').val('0');
            $('#bankDiv').hide();//hide bank id
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
            $('.cash_issue').hide();
            $('#bankDiv').show();//show bank id
            $('.checque').show();
            $('#chequeValue').val(netcash);
            $('#chequeValue').attr('readonly', true);
            $('#balance').val('0');
            $('.transaction').hide();
            $('#cashAck').hide(); // Cash Acknowledgement.

        } else if (type == '2') {
            $('.cash_issue').hide();
            $('#bankDiv').show();//show bank id
            $('.checque').hide();
            $('.transaction').show();
            $('#transaction_value').val(netcash);
            $('#transaction_value').attr('readonly', true);
            $('#balance').val('0');
            $('#cashAck').hide(); // Cash Acknowledgement.

        } else {
            $('.cash_issue').hide();
            $('#bankDiv').hide();//hide bank id
            $('.checque').hide();
            $('.transaction').hide();
            $('#balance').val('');
            $('#cashAck').hide(); // Cash Acknowledgement.
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


    $('#submit_loanIssue').click(function () { // loan Issue Submit Validation.
        hideCheckSpan();

        loanIssueSumitValidation();

    });
});


$(function () {
    turnonCashKeyup();

    getImage(); // To show customer image when window onload.
    guarentorName(); //To Show Guarentor Name.
    getLc(); // To get loan Category.

    getCategoryInfo(); //To show Category Info.
    getAgentDetails(); //To Get Agent Details.

    cashAckName(); // To show Cash Acknowledgement Name.
    checkBalance(); // To check in DB.

    setTimeout(() => {
        getCustomerLoanCounts();// To Get loan existing type
    }, 1000);

});


function turnonCashKeyup() {
    //Check Cash limit based on Net Cash
    $('#cash').keyup(function () {
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
            {//To Order ag_group Alphabetically
                var firstOption = $("#guarentor_name option:first-child");
                $("#guarentor_name").html($("#guarentor_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#guarentor_name").prepend(firstOption);
            }
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

//Get Category info From Request
function getCategoryInfo() {
    var sub_category_upd = $('#sub_category_upd').val();
    var sub_cat = $('#sub_category').val();
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat },
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
        var cashValue = parseInt($('#cash').val());
        var chequeValue = parseInt($('#chequeValue').val());
        var transactionValue = parseInt($('#transaction_value').val());
        totalValue = (isNaN(cashValue) ? 0 : cashValue) + (isNaN(chequeValue) ? 0 : chequeValue) + (isNaN(transactionValue) ? 0 : transactionValue);
        netCash = parseInt($('#net_cash').val());
        var bal = parseInt(netCash) - parseInt(totalValue);
        if (bal >= 0) {
            $('#balance').val(bal);
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
                $('#net_cash').val(response['balance_amount']);
                if (response['balance_amount'] == '0') {
                    //Once Balance Zero then disabled all field.
                    $('#issued_mode').attr('disabled', true);
                    $('#due_start_from').attr('disabled', true);
                    $('#cash_guarentor_name').attr('disabled', true);
                    $('#submit_loanIssue').hide();
                }
            } else {
                var netcashamnt = parseInt($('#net_cash_cal').val());
                $('#net_cash').val(netcashamnt);

            }

        }
    })

}

//Submit Validation
function loanIssueSumitValidation() {
    var issueMode = $('#issued_mode').val(); var paymenType = $('#payment_type').val(); var cash = $('#cash').val(); var chequeNum = $('#chequeno').val(); var chequeVal = $('#chequeValue').val(); var chequeRemark = $('#chequeRemark').val(); var transactionID = $('#transaction_id').val(); var transactionVal = $('#transaction_value').val(); var transactionRemark = $('#transaction_remark').val(); var guarentorName = $('#cash_guarentor_name').val(); 
    // var fingerMatch = $('#fingerValidation').val();
    var ag_id = $('#agent_id').val(); var bank_id = $('#bank_id').val();
    //Check Issue Mode
    if (issueMode == '') {
        event.preventDefault();
        $('#issue').show();
    } else {
        $('#issue').hide();
    }

    //Issue Mode Split
    if (issueMode == '0') {
        //Check cheque If Cheque details enter
        if (chequeNum != '' || chequeVal != '' || chequeRemark != '') {
            if (chequeNum == '') {
                event.preventDefault();
                $('#cheque_num').show();
            } else {
                $('#cheque_num').hide();
            }
            if (chequeVal == '') {
                event.preventDefault();
                $('#cheque_val').show();
            } else {
                $('#cheque_val').hide();
            }
            if (chequeRemark == '') {
                event.preventDefault();
                $('#cheque_remark').show();
            } else {
                $('#cheque_remark').hide();
            }
            if (bank_id == '') {
                event.preventDefault();
                $('#bank_idCheck').show();
            } else {
                $('#bank_idCheck').hide();
            }

        }

        //Check Transaction If Transaction details enter
        if (transactionID != '' || transactionVal != '' || transactionRemark != '') {
            if (transactionID == '') {
                event.preventDefault();
                $('#transact_id').show();
            } else {
                $('#transact_id').hide();
            }
            if (transactionVal == '') {
                event.preventDefault();
                $('#transact_val').show();
            } else {
                $('#transact_val').hide();
            }
            if (transactionRemark == '') {
                event.preventDefault();
                $('#transact_remark').show();
            } else {
                $('#transact_remark').hide();
            }
            if (bank_id == '') {
                event.preventDefault();
                $('#bank_idCheck').show();
            } else {
                $('#bank_idCheck').hide();
            }
        }

        if (cash != '' || chequeVal != '' || transactionVal != '') {
            $('#val_check').hide();
        } else {
            event.preventDefault();
            $('#val_check').show();
        }
    } //Split END//

    //Issue Mode Single Payment
    if (issueMode == '1') {
        if (paymenType == '') {
            event.preventDefault();
            $('#pay_type').show();
        } else {
            $('#pay_type').hide();
        }
    }
    //Cash
    if (issueMode == '1' && paymenType == '0') {
        if (cash == '') {
            event.preventDefault();
            $('#cash_amnt').show();
        } else {
            $('#cash_amnt').hide();
        }
    }

    //Cheque
    if (issueMode == '1' && paymenType == '1') {
        if (chequeNum == '') {
            event.preventDefault();
            $('#cheque_num').show();
        } else {
            $('#cheque_num').hide();
        }

        if (chequeVal == '') {
            event.preventDefault();
            $('#cheque_val').show();
        } else {
            $('#cheque_val').hide();
        }

        if (chequeRemark == '') {
            event.preventDefault();
            $('#cheque_remark').show();
        } else {
            $('#cheque_remark').hide();
        }
        if (bank_id == '') {
            event.preventDefault();
            $('#bank_idCheck').show();
        } else {
            $('#bank_idCheck').hide();
        }
    }

    //Transaction
    if (issueMode == '1' && paymenType == '2') {
        if (transactionID == '') {
            event.preventDefault();
            $('#transact_id').show();
        } else {
            $('#transact_id').hide();
        }

        if (transactionVal == '') {
            event.preventDefault();
            $('#transact_val').show();
        } else {
            $('#transact_val').hide();
        }

        if (transactionRemark == '') {
            event.preventDefault();
            $('#transact_remark').show();
        } else {
            $('#transact_remark').hide();
        }

        if (bank_id == '') {
            event.preventDefault();
            $('#bank_idCheck').show();
        } else {
            $('#bank_idCheck').hide();
        }
    }

    if (ag_id == '') { // check only if agent id is not set/ this issue is not for agent
        if (cash != '') {
            if (guarentorName == '') {
                event.preventDefault();
                $('#cash_guarentor').show();
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


}

//Span Hide
function hideCheckSpan() {
    $('#cheque_num').hide(); $('#cheque_val').hide(); $('#cheque_remark').hide(); $('#transact_id').hide(); $('#transact_val').hide(); $('#transact_remark').hide(); $('#pay_type').hide(); $('#cash_amnt').hide(); $('#cash_guarentor').hide(); $('#val_check').hide(); $('#bank_idCheck').hide();
}
