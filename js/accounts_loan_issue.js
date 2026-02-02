$(document).ready(function () {

    //Hide Cash Acknowledgement card.. show only if cash enter.
    $('#bankInfo').hide();

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
            return;
        }
        var profit_method = $('#profit_method').val(); // if profit method changes, due type is EMI
        if (profit_method == "after_intrest" && due_type == "EMI") {
            getLoanAfterInterest();
            
        } else if (profit_method == 'pre_intrest') {
            getLoanPreInterest(); 
            
        }

        var due_type = $('#due_type').val(); //If Changes not found in profit method, calculate loan amt for monthly basis
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
                int_label.previousElementSibling.previousElementSibling.textContent = "Benefit Amount";
                $('.emi_div').hide();
            } else {
                int_label.previousElementSibling.previousElementSibling.textContent = "Interest Amount";
                $('.emi_div').show();
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

    $('#bank_id').change(function(){
        $('#transaction_id, #trans_date, #bank_clr_bank_id, #bank_clr_trans_amnt').val('');
    });

    //Transaction id validation
    $("#transaction_id").keydown(function () { //clear transaction date if changes in trans id becuase if by chance changing trans id after gets trans date means it take while a time to reflect new date in mean time able to submit with old date.  
        $('#trans_date').val('');
    });

    $("#transaction_id").blur(async function () {
        let bankId = $('#bank_id').val();
        if(!bankId){
            swarlErrorAlert("Kindly select Bank Name!"); 
            return;
        }
        
        let paymentType = $('#payment_type').val();
        let transactionValue ='';
        if(paymentType =='1'){
            transactionValue = $('#chequeValue').val() != '' ? $('#chequeValue').val().replace(/,/g, '') : 0;
        }else if(paymentType =='2'){
            transactionValue = $('#transaction_value').val() != '' ? $('#transaction_value').val().replace(/,/g, '') : 0;
        }
        
        if(!transactionValue){
            swarlErrorAlert("Kindly Fill Value!"); 
            return;
        }

        let transId = $('#transaction_id').val();
        let response = await checkBankTransactionDetails('debit', bankId, transId, transactionValue);
        if (!response.status) {
            swarlErrorAlert(response.message);
            $('#transaction_id').val('');
            return;
        }

        let alertStatus = response.data.alert_status;
        if(alertStatus){
            swarlErrorAlert(response.data.alert);
            $('#transaction_id').val('');
        }else{
            $('#trans_date').val(response.data.trans_date);
            $('#bank_clr_bank_id').val(response.data.id);
            $('#bank_clr_trans_amnt').val(response.data.transaction_amount);
        }
    });

    $('#submit_accountsloanIssue').click(function (event) { // loan Issue Submit Validation
        event.preventDefault();
        hideCheckSpan();

        // Run validation and only proceed if it passes
        if (!loanIssueSumitValidation()) {
            return; // Exit if validation fails
        }

    // Confirmation before AJAX
    Swal.fire({
        title: 'Are you sure you want to issue this loan?',
        text: 'Once submitted, changes cannot be reverted!',
        icon: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#cc4444',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes'
    }).then(function(result) {
        if (result.isConfirmed) {
            // Collect form values
            let req_id = $('#req_id').val();
            let cus_id = $('#cus_id').val();
            let issue_to = $('#issue_to').val();
            let net_cash = $('#net_cash').val().replace(/,/g, '');
            let balance = $('#balance').val().replace(/,/g, '');
            let loan_amt_cal = $('#loan_amt_cal').val().replace(/[\s,]+/g, '');
            let issued_mode = $('#issued_mode').val();
            let payment_type = $('#payment_type').val();
            let chequeno = $('#chequeno').val();
            let chequeValue = $('#chequeValue').val().replace(/,/g, '');
            let chequeRemark = $('#chequeRemark').val();
            let transaction_id = $('#transaction_id').val();
            let trans_date = $('#trans_date').val();
            let transaction_value = $('#transaction_value').val().replace(/,/g, '');
            let transactionRemark = $('#transaction_remark').val();
            let bank_id = $('#bank_id').val();
            let bank_clr_bank_id = $('#bank_clr_bank_id').val();
            let bank_clr_trans_amnt = $('#bank_clr_trans_amnt').val();

            // AJAX call
            $.ajax({
                url: 'loanIssueFile/submitLoanIssue.php',
                type: 'POST',
                data: {
                    "req_id": req_id,
                    "cus_id": cus_id,
                    "issue_to": issue_to,
                    "net_cash": net_cash,
                    "balance": balance,
                    "loan_amt_cal": loan_amt_cal,
                    "issued_mode": issued_mode,
                    "payment_type": payment_type,
                    "chequeno": chequeno,
                    "chequeValue": chequeValue,
                    "chequeRemark": chequeRemark,
                    "transaction_id": transaction_id,
                    "trans_date": trans_date,
                    "transaction_value": transaction_value,
                    "transaction_remark": transactionRemark,
                    "bank_id": bank_id,
                    "bank_clr_bank_id": bank_clr_bank_id,
                    "bank_clr_trans_amnt": bank_clr_trans_amnt
                },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    if (result.response.includes('Completed')) {
                        Swal.fire({
                            title: result.response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688',
                            confirmButtonText: 'OK'
                        }).then((swalResult) => {
                            // Redirect only when OK is clicked
                            if (swalResult.isConfirmed) {
                                window.location.href = 'edit_accounts_loan_issue';
                            }
                        });
                    }else{
                        Swal.fire({
                            title: 'Error!',
                            text :  result.response,
                            icon : 'error',
                            confirmButtonColor: '#cc4444'
                        });
                    };
                },
                error: function (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong while submitting loan issue.',
                        icon: 'error',
                        confirmButtonColor: '#cc4444'
                    });
                }
            });
        }
    });
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
    $('input').not('#int_rate, #due_period, #doc_charge, #proc_fee,#due_start_from,#chequeno,#chequeRemark,#transaction_id,#transaction_remark').attr('readonly', true);
    $('select').not('#issued_mode, #cash_guarentor_name,#bank_id,#payment_type,#collection_method').attr('disabled', true);
    checkBalance(); // To check in DB.
    getBankDetails();

    setTimeout(() => {
        paymentType();
        getCustomerLoanCounts();// To Get loan existing type

    }, 3000);

});


function turnonCashKeyup() {
    //Check Cash limit based on Net Cash
    $('#cash').keyup(function () {
        checkIssuedAmount('0');
    });
    $('#chequeValue').keyup(function () {
         var chequeValue = $("#chequeValue").val().replace(/,/g, '');
        $('#chequeValue').val(formatIndianNumber(chequeValue));
        checkIssuedAmount('1');
    });
    $('#transaction_value').keyup(function () {
        var transaction_value = $("#transaction_value").val().replace(/,/g, '');
        $('#transaction_value').val(formatIndianNumber(transaction_value));
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
                    $("#int_rate").attr("onChange", `if( parseFloat($(this).val()) > '` + response["intrest_rate_max"] + `' ){ alert("Enter Lesser Value"); 
            $(this).val(""); }
            elseif( parseFloat($(this).val()) < '` + response["intrest_rate_min"] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); 
            $(this).val(""); } `); //To check value between rage
                    $("#int_rate").val(int_rate_upd);

                    $(".min-max-due").text("* (" + response["due_period_min"] + " - " + response["due_period_max"] + ") ");

                    $("#due_period").attr("onChange", `if( parseInt($(this).val()) > '` + response["due_period_max"] + `' ){ alert("Enter Lesser Value");
            $(this).val(""); }
            elseif( parseInt($(this).val()) < '` + response["due_period_min"] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage

                    $("#due_period").val(due_period_upd);

                    if (response["doc_charge_type"] == "amt") {
                        type = "₹";
                    } else if (response["doc_charge_type"] == "percentage") {
                        type = "%";
                    } //Setting symbols
                    $(".min-max-doc").text("* (" + response["document_charge_min"] + " " + type + " - " + response["document_charge_max"] + " " + type + ") "); //setting min max values in span

                    $("#doc_charge").attr("onChange", `if( parseInt($(this).val()) > '` + response["document_charge_max"] + `' ){ alert("Enter Lesser Value"); 
            $(this).val(""); }
            elseif( parseInt($(this).val()) < '` + response["document_charge_min"] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value");
            $(this).val(""); } `); //To check value between rage

                    $("#doc_charge").val(doc_charge_upd);

                    // $('.min-max-doc').text('* (' + response['document_charge_min'] + '% - ' + response['document_charge_max'] + '%) ');
                    // $('#doc_charge').attr('onChange', `if( parseFloat($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['document_charge_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#doc_charge').val(doc_charge_upd);

                    if (response["proc_fee_type"] == "amt") {
                        type = "₹";  // Set ₹ symbol before the numbers
                    } else if (response["proc_fee_type"] == "percentage") {
                        type = "%"; // Set % symbol after the numbers
                    }

                    $(".min-max-proc").text("* (" + response["processing_fee_min"] + " " + type + " - " + response["processing_fee_max"] + " " + type + ") "); //setting min max values in span

                    $("#proc_fee").attr("onChange", `if( parseFloat($(this).val()) > '` + response["processing_fee_max"] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }
            elseif( parseFloat($(this).val()) < '` + response["processing_fee_min"] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value");
            $(this).val(""); } `); //To check value between rage

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
    var loan_amt   = $('#loan_amt').val().replace(/[\s,]+/g, '');
    var int_rate   = $('#int_rate').val().replace(/[\s,]+/g, '');
    var due_period = $('#due_period').val().replace(/[\s,]+/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[\s,]+/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[\s,]+/g, '');


    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    // principal amt as same as loan amt for after interest

    var interest_rate = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate

    var tot_amt = parseInt(loan_amt) + parseFloat(interest_rate); //Calculate total amount from principal/loan amt and interest rate

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
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
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
    var loan_amt   = $('#loan_amt').val().replace(/[\s,]+/g, '');
    var int_rate   = $('#int_rate').val().replace(/[\s,]+/g, '');
    var due_period = $('#due_period').val().replace(/[\s,]+/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[\s,]+/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[\s,]+/g, '');

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card

    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

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
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
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
    var loan_amt   = $("#loan_amt").val().replace(/[\s,]+/g, '');
    var int_rate   = $("#int_rate").val().replace(/[\s,]+/g, '');
    var doc_charge = $("#doc_charge").val().replace(/[\s,]+/g, '');
    var proc_fee   = $("#proc_fee").val().replace(/[\s,]+/g, '');

    var calc_method = $("#calc_method").val();

    $("#loan_amt_cal").val(parseInt(loan_amt).toFixed(0));

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
    $("#int_amt_cal").val(parseInt(roundedInterest));

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
    $("#doc_charge_cal").val(parseInt(roundeddoccharge));

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
    $("#proc_fee_cal").val(parseInt(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseInt(doc_charge) - parseInt(proc_fee);
    $("#net_cash_cal").val(parseInt(net_cash).toFixed(0));
}

function getSchemeAfterIntreset() {
    var loan_amt   = $('#loan_amt').val().replace(/[\s,]+/g, '');
    var int_rate   = $('#int_rate').val().replace(/[\s,]+/g, '');
    var due_period = $('#due_period').val().replace(/[\s,]+/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[\s,]+/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[\s,]+/g, '');

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
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
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
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
    var loan_amt   = $('#loan_amt').val().replace(/[\s,]+/g, '');
    var int_rate   = $('#int_rate').val().replace(/[\s,]+/g, '');
    var due_period = $('#due_period').val().replace(/[\s,]+/g, '');
    var doc_charge = $('#doc_charge').val().replace(/[\s,]+/g, '');
    var proc_fee   = $('#proc_fee').val().replace(/[\s,]+/g, '');


    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card

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
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
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
                    $('#submit_accountsloanIssue').hide();
                }
            } else {
                var netcashamnt = parseInt($('#net_cash_cal').val().replace(/,/g, ''));
                $('#net_cash').val(moneyFormatIndia(netcashamnt));

            }

        }
    })

}

function getBankDetails() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'loanIssueFile/getSelectedBankDetails.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        dataType: 'json',
        cache: false,
        success: function (result) {
            $("#bank_name").val(result['bankName']);
            $("#branch_name").val(result['branch']);
            $("#account_holder_name").val(result['accHolderName']);
            $("#account_number").val(result['acc_no']);
            $("#Ifsc_code").val(result['ifsc']);

            if (result['upload']) {
                let fileName = result['upload'];  // Assuming the server returns the file name
                $("#viewUploadedImage")
                    .attr("href", "uploads/bankUploads/" + fileName)  // Set the link to the file
                    .text(fileName)  // Set the text to the file name
                    .show();  // Show the link
            } else {
                $("#viewUploadedImage").hide();  // Hide the link if there's no file
            }
        }
    });
}

function paymentType() {

    $('#chequeno').val('');
    $('#chequeValue').val('');
    $('#chequeRemark').val('');
    $('#transaction_id').val('');
    $('#transaction_value').val('');
    $('#transaction_remark').val('');
    var type = $('#payment_type').val();
    var netcash = $('#net_cash').val();
    let issue_mode = $('#issued_mode').val();
    if (issue_mode == 0) {
        if (type == '1') {
            $('.balance').show();
            $('.checque').show();
            $('#chequeValue').val('');
            $('#chequeValue').attr('readonly', false);
            $('.transaction').hide();
            turnonCashKeyup();
        } else if (type == '2') {
            $('.balance').show();
            $('.cash_issue').hide();
            $('.checque').hide();
            $('.transaction').show();
            $('#transaction_value').val('');
            $('#transaction_value').attr('readonly', false);
            turnonCashKeyup();
        } else {
            $('.balance').hide();
            $('.checque').hide();
            $('.transaction').hide();
            $('#balance').val('');
        }
    }
    else {
        $('.balance').hide();
        if (type == '1') {
            $('.checque').show();
            $('#chequeValue').val(netcash);
            $('#chequeValue').attr('readonly', true);
            $('#balance').val('0');
            $('.transaction').hide();

        } else if (type == '2') {
            $('.checque').hide();
            $('.transaction').show();
            $('#transaction_value').val(netcash);
            $('#transaction_value').attr('readonly', true);
            $('#balance').val('0');
        } else {
            $('.balance').hide();
            $('.checque').hide();
            $('.transaction').hide();
            $('#balance').val('');
        }
    }

    hideCheckSpan();

}

//Submit Validation
function loanIssueSumitValidation() {
    var issueMode = $('#issued_mode').val();
    var paymenType = $('#payment_type').val();
    var chequeNum = $('#chequeno').val();
    var chequeVal = $('#chequeValue').val();
    var chequeRemark = $('#chequeRemark').val();
    var transactionID = $('#transaction_id').val();
    var transDate = $('#trans_date').val();
    var transactionVal = $('#transaction_value').val();
    var transactionRemark = $('#transaction_remark').val();
    var bank_id = $('#bank_id').val();
    var isValid = true; // Track validation state

    // Check Issue Mode
    if (issueMode == '') {
        event.preventDefault();
        $('#issue').show();
        isValid = false;
    } else {
        $('#issue').hide();
    }

    // Issue Mode Single Payment
    if (issueMode == '1' || issueMode == '0') {
        if (paymenType == '') {
            event.preventDefault();
            $('#pay_type').show();
            isValid = false;
        } else {
            $('#pay_type').hide();
        }
    }

    //Transaction id.
    if (transactionID == '') {
        event.preventDefault();
        $('#transact_id').show();
        isValid = false;
    } else {
        $('#transact_id').hide();
    }

    if (transDate == '') {
        event.preventDefault();
        $('#transdateCheck').show();
        isValid = false;
    } else {
        $('#transdateCheck').hide();
    }

    // Cheque
    if (paymenType == '1') {
        if (chequeNum == '') {
            event.preventDefault();
            $('#cheque_num').show();
            isValid = false;
        } else {
            $('#cheque_num').hide();
        }

        if (chequeVal == '') {
            event.preventDefault();
            $('#cheque_val').show();
            isValid = false;
        } else {
            $('#cheque_val').hide();
        }

        if (chequeRemark == '') {
            event.preventDefault();
            $('#cheque_remark').show();
            isValid = false;
        } else {
            $('#cheque_remark').hide();
        }

        if (bank_id == '') {
            event.preventDefault();
            $('#bank_idCheck').show();
            isValid = false;
        } else {
            $('#bank_idCheck').hide();
        }
    }

    // Transaction
    if (paymenType == '2') {
        if (transactionVal == '') {
            event.preventDefault();
            $('#transact_val').show();
            isValid = false;
        } else {
            $('#transact_val').hide();
        }

        if (transactionRemark == '') {
            event.preventDefault();
            $('#transact_remark').show();
            isValid = false;
        } else {
            $('#transact_remark').hide();
        }

        if (bank_id == '') {
            event.preventDefault();
            $('#bank_idCheck').show();
            isValid = false;
        } else {
            $('#bank_idCheck').hide();
        }
    }

    return isValid; // Return validation state
}

//Span Hide
function hideCheckSpan() {
    $('#cheque_num, #cheque_val, #cheque_remark, #transact_id, #transdateCheck, #transact_val, #transact_remark, #pay_type, #cash_amnt, #cash_guarentor, #val_check, #bank_idCheck').hide();
}
function swarlErrorAlert(response) {
  Swal.fire({
    title: response,
    icon: "error",
    confirmButtonText: "Ok",
    confirmButtonColor: "#009688",
  });
}
