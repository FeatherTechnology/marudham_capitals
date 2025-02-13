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
    
        var profit_method = $('#profit_method').val(); // if profit method changes, due type is EMI
        if (profit_method == 'after_intrest') {
            getLoanAfterInterest(); changeInttoBen()
        } else if (profit_method == 'pre_intrest') {
            getLoanPreInterest(); changeInttoBen()
        }
    
        var due_type = $('#due_type').val(); //If Changes not found in profit method, calculate loan amt for monthly basis
        if (due_type == 'Interest') {
            getLoanInterest(); changeInttoBen()
        }
        var scheme_profit_method = $('#scheme_profit_method').val(); // if profit method changes, due type is EMI
        if (scheme_profit_method == 'after_intrest') {
            getSchemeAfterIntreset(); changeInttoBen();
        } else if (scheme_profit_method == 'pre_intrest') {
            getSchemePreIntreset(); changeInttoBen();
        }
    
        // var due_method_scheme = $('#due_method_scheme').val();
        // if (due_method_scheme == '1') {//Monthly scheme as 1
        //     getLoanMonthly(); changeInttoBen()
        // } else if (due_method_scheme == '2') {//Weekly scheme as 2
        //     getLoanWeekly(); changeInttoBen()
        // } else if (due_method_scheme == '3') {//Daily scheme as 3
        //     getLoanDaily(); changeInttoBen()
        // }

        function changeInttoBen() {
            let due_type = document.getElementById('due_type');
            let int_label = document.querySelector('#int_amt_cal');
            if (due_type.value == 'Interest') {
                // Set its value to 'Benefit Amount'
                int_label.previousElementSibling.previousElementSibling.textContent = 'Benefit Amount';
            } else {
                int_label.previousElementSibling.previousElementSibling.textContent = 'Interest Amount';
            }
        }
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


    $('#submit_loanIssue').click(function () { // loan Issue Submit Validation.
        hideCheckSpan();
     //   $('#refresh_cal').trigger('click');
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
    profitCalculationInfo();
    cashAckName(); // To show Cash Acknowledgement Name.
    $('input').not('#int_rate, #due_period, #doc_charge, #proc_fee,#due_start_from,#chequeno,#chequeRemark,#transaction_id,#transaction_remark').attr('readonly', true);
    $('select').not('#issued_mode, #cash_guarentor_name,#bank_id,#payment_type,#collection_method').attr('disabled', true);
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
function getCategoryInfo() {
    var sub_category_upd = $('#sub_category_upd').val();
    var sub_cat = $('#sub_category').val();
    var loan_category = $('#loan_category_lc').val();
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat,'loan_category':loan_category },
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
        profitCalAjax(profit_type, sub_cat,loan_cat); //Call for edit
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

        $('#int_rate').val(''); $('#int_rate').attr('readonly', false);
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
        profitCalAjax(profit_type, sub_cat,loan_cat)

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
function profitCalAjax(profit_type, sub_cat,loan_cat) {
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
            data: { 'sub_cat': sub_cat ,'loan_cat':loan_cat},
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
                    $('#int_rate').attr('onChange', `if( parseFloat($(this).val()) > '` + response['intrest_rate_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseFloat($(this).val()) < '`+ response['intrest_rate_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#int_rate').val(int_rate_upd);
                    $('.min-max-due').text('* (' + response['due_period_min'] + ' - ' + response['due_period_max'] + ') ');
                    $('#due_period').attr('onChange', `if( parseInt($(this).val()) > '` + response['due_period_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['due_period_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#due_period').val(due_period_upd);
                    if (response['doc_charge_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-doc').text('* (' + type + response['document_charge_min'] + ' - ' + type + response['document_charge_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['doc_charge_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-doc').text('* (' + response['document_charge_min'] + type + ' - ' + response['document_charge_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }
                    
                    // Setting onChange event to ensure the value is within the specified range
                    $('#doc_charge').attr('onChange', `if( parseInt($(this).val()) > '` + response['document_charge_max'] + `' ){
                            alert("Enter Lesser Value");
                            $(this).val("");
                        } else if( parseInt($(this).val()) < '`+ response['document_charge_min'] + `' && parseInt($(this).val()) != '' ){
                            alert("Enter Higher Value");
                            $(this).val("");
                        }`);
                    
                    // Set the value for the doc_charge field
                    $('#doc_charge').val(doc_charge_upd);
                    
                    // $('.min-max-doc').text('* (' + response['document_charge_min'] + '% - ' + response['document_charge_max'] + '%) ');
                    // $('#doc_charge').attr('onChange', `if( parseFloat($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['document_charge_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#doc_charge').val(doc_charge_upd);
                    if (response['proc_fee_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-proc').text('* (' + type + response['processing_fee_min'] + ' - ' + type + response['processing_fee_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['proc_fee_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-proc').text('* (' + response['processing_fee_min'] + type + ' - ' + response['processing_fee_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }
                    
                    // Setting onChange event to ensure the value is within the specified range
                    $('#proc_fee').attr('onChange', `if( parseInt($(this).val()) > '` + response['processing_fee_max'] + `' ){
                            alert("Enter Lesser Value");
                            $(this).val("");
                        } else if( parseInt($(this).val()) < '`+ response['processing_fee_min'] + `' && parseInt($(this).val()) != '' ){
                            alert("Enter Higher Value");
                            $(this).val("");
                        }`);
                    
                    // Set the value for the doc_charge field
                    $('#proc_fee').val(proc_fee_upd);
                    
                    // $('.min-max-proc').text('* (' + response['processing_fee_min'] + '% - ' + response['processing_fee_max'] + '%) ');
                    // $('#proc_fee').attr('onChange', `if( parseFloat($(this).val()) > '` + response['processing_fee_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['processing_fee_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#proc_fee').val(proc_fee_upd);

                } else if (response['due_type'] == 'intrest') {

                    $('.emi-calculation').hide();
                    $('.interest-calculation').show();
                    $('#due_type').val('Interest');
                    $('#profit_method').empty();
                    $('#calc_method').val(response['calculate_method']);
                    //To set min and maximum 
                    $('.min-max-int').text('* (' + response['intrest_rate_min'] + '% - ' + response['intrest_rate_max'] + '%) ');
                    $('#int_rate').attr('onChange', `if( parseFloat($(this).val()) > '` + response['intrest_rate_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseFloat($(this).val()) < '`+ response['intrest_rate_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#int_rate').val(int_rate_upd);

                    $('.min-max-due').text('* (' + response['due_period_min'] + ' - ' + response['due_period_max'] + ') ');
                    $('#due_period').attr('onChange', `if( parseInt($(this).val()) > '` + response['due_period_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['due_period_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#due_period').val(due_period_upd);
                    if (response['doc_charge_type'] == 'amt') { type = '₹' } else if (response['doc_charge_type'] == 'percentage') { type = '%'; } //Setting symbols
                    $('.min-max-doc').text('* (' + response['document_charge_min'] + ' ' + type + ' - ' + response['document_charge_max'] + ' ' + type + ') '); //setting min max values in span
                    $('#doc_charge').attr('onChange', `if( parseInt($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                            if( parseInt($(this).val()) < '`+ response['document_charge_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#doc_charge').val(doc_charge_upd);
                    // $('.min-max-doc').text('* (' + response['document_charge_min'] + '% - ' + response['document_charge_max'] + '%) ');
                    // $('#doc_charge').attr('onChange', `if( parseFloat($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['document_charge_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#doc_charge').val(doc_charge_upd);

                    $('.min-max-proc').text('* (' + response['processing_fee_min'] + '% - ' + response['processing_fee_max'] + '%) ');
                    $('#proc_fee').attr('onChange', `if( parseFloat($(this).val()) > '` + response['processing_fee_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseFloat($(this).val()) < '`+ response['processing_fee_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#proc_fee').val(proc_fee_upd);
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
                $('#int_rate').attr('onChange', `if( parseInt($(this).val()) > '` + response['intreset_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['intreset_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                $('#int_rate').val(int_rate_upd);
                if (response['doc_charge_type'] == 'amt') { type = '₹' } else if (response['doc_charge_type'] == 'percentage') { type = '%'; } //Setting symbols
                $('.min-max-doc').text('* (' + response['doc_charge_min'] + ' ' + type + ' - ' + response['doc_charge_max'] + ' ' + type + ') '); //setting min max values in span
                $('#doc_charge').attr('onChange', `if( parseInt($(this).val()) > '` + response['doc_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['doc_charge_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                $('#doc_charge').val(doc_charge_upd);

                if (response['proc_fee_type'] == 'amt') { type = '₹' } else if (response['proc_fee_type'] == 'percentage') { type = '%'; }//Setting symbols
                $('.min-max-proc').text('* (' + response['proc_fee_min'] + ' ' + type + ' - ' + response['proc_fee_max'] + ' ' + type + ') ');//setting min max values in span
                $('#proc_fee').attr('onChange', `if( parseInt($(this).val()) > '` + response['proc_fee_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                    if( parseInt($(this).val()) < '`+ response['proc_fee_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
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
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    $('#principal_amt_cal').val(parseInt(loan_amt).toFixed(0)); // principal amt as same as loan amt for after interest

    var interest_rate = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    // var roundedInterest = Math.ceil(interest_rate / 5) * 5; //to increase interest rate to nearest multiple of 5
    // if (roundedInterest < interest_rate) {
    //     roundedInterest += 5;
    // }

    // $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - interest_rate) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(interest_rate));

    var tot_amt = parseInt(loan_amt) + parseFloat(interest_rate); //Calculate total amount from principal/loan amt and interest rate
    $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;
    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - interest_rate) + ')'); //To show the difference amount from old to new
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(loan_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
    checkBalance() 
}

//To Get Loan Calculation for Pre Interest
function getLoanPreInterest() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();
    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card


    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 
    // $('#int_amt_cal').val(parseInt(int_amt));

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
    // $('#principal_amt_cal').val(parseInt(princ_amt).toFixed(0)); 

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
    // $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////

    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
    checkBalance() 
}

//To Get Loan Calculation for Interest due type
function getLoanInterest() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    $('#principal_amt_cal').val(parseInt(loan_amt).toFixed(0));

    $('#tot_amt_cal').val('');
    $('#due_amt_cal').val('');//Due period will be monthly by default so no need of due amt

    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 

    var roundedInterest = Math.ceil(int_amt / 5) * 5;
    if (roundedInterest < int_amt) {
        roundedInterest += 5;
    }
    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    $('#doc_charge_cal').val(parseInt(doc_charge).toFixed(0));

    var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    $('#proc_fee_cal').val(parseInt(proc_fee).toFixed(0));

    var net_cash = parseInt(loan_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
}
function getSchemeAfterIntreset() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();
    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    $('#principal_amt_cal').val(parseInt(loan_amt).toFixed(0)); // principal amt as same as loan amt for after interest
    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    }
    // var roundedInterest = Math.ceil(int_amt / 5) * 5;
    // if (roundedInterest < int_amt) {
    //     roundedInterest += 5;
    // }
    // $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    // $('#int_amt_cal').val(parseInt(int_amt));

    var tot_amt = parseInt(loan_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
    $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////

    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
    checkBalance() 
}
function getSchemePreIntreset() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card

    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    }
    // $('#int_amt_cal').val(parseInt(int_amt));

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
    // $('#principal_amt_cal').val(princ_amt); 

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
    // $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////

    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: ' + parseInt(new_princ - princ_amt) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
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
                BalanceAmount = response['balance_amount'];
                if(response['balance_amount'] >'0'){
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
