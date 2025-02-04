$(document).ready(function () {

    $('#hand_cash_radio , .bank_cash_radio').click(function () {
        hideAllCardsfunction();
        var cash_type = $('input[name=cash_type]:checked').val();
        if (cash_type == '0') {//hand cash
            appendHandCreditDropdown();
            appendHandDebitDropdown();
        } else if (cash_type > 0) {//Bank cash
            appendBankCreditDropdown();
            appendBankDebitDropdown();
        }
    })

    //On change of types other type shoult be empty
    $('#credit_type').change(function () {
        var credit_type = $(this).val();
        if (credit_type != '') {
            $('#debit_type').val('');
        }
    })
    $('#debit_type').change(function () {
        var debit_type = $(this).val();
        if (debit_type != '') {
            $('#credit_type').val('');
        }
    })


    //Credit Type on change event
    $('#credit_type').change(function () {
        hideAllCardsfunction()
        var credit_type = $(this).val();
        var cash_type = $('input[name=cash_type]:checked').val();

        if (credit_type != '') {

            /////////////////////// For Collection Credit types ////////////////////////////
            if (credit_type == 1 && cash_type == 0) {
                // 1 means Collection and cash type is hand cash
                $('.collection_card').show();
                getCollectionDetails();
            } else if (credit_type == 1 && cash_type > 0) {
                // 1 means Collection and cash type is bank cash
                $('.collection_card').show();
                getBankCollectionDetails(cash_type);
            } else if (credit_type == 5 && cash_type > 0) {
                // 5 means cash deposit and cash type is bank
                $('.contra_card').show();
                getCashDepositDetails(cash_type);
            } else if (credit_type == 2 && cash_type == 0) {
                // 2 means Bank Withdrawal and cash type is hand
                $('.contra_card').show();
                getBankWithdrawalDetails();
            } else if (credit_type == 4 && cash_type == 0) {
                //4 Means Exchange and cash type hand cash
                $('.exchange_card').show();
                getCreditHexchangeDetails();
            } else if (credit_type == 4 && cash_type > 0) {
                //4 Means Exchange and cash type Bank cash
                $('.exchange_card').show();
                getCreditBexchangeDetails();
            } else if (credit_type == 3 && cash_type == 0) {
                //3 Means Other income and cash type Hand cash
                $('.oti_card').show();
                getHotherincomeDetails();
            } else if (credit_type == 3 && cash_type > 0) {
                //3 Means Other income and cash type Bank cash
                $('.oti_card').show();
                getBotherincomeDetails();
            } else if (credit_type == 9 && cash_type == 0) {
                //9 Means Investment and cash type Hand cash
                $('.inv_card').show();
                getCHinvDetails();
            } else if (credit_type == 9 && cash_type > 0) {
                //9 Means Investment and cash type Bank cash
                $('.inv_card').show();
                getCBinvDetails();
            } else if (credit_type == 10 && cash_type == 0) {
                //10 Means Deposit and cash type Hand cash
                $('.inv_card').show();
                getCHdepDetails();
            } else if (credit_type == 10 && cash_type > 0) {
                //10 Means Deposit and cash type Bank cash
                $('.inv_card').show();
                getCBDepDetails();
            } else if (credit_type == 11 && cash_type == 0) {
                //11 Means EL and cash type Hand cash
                $('.inv_card').show();
                getCHelDetails();
            } else if (credit_type == 11 && cash_type > 0) {
                //11 Means EL and cash type Bank cash
                $('.inv_card').show();
                getCBelDetails();
            } else if (credit_type == 8 && cash_type == 0) {
                //8 Means Agent and cash type Hand cash
                $('.ag_card').show();
                getCHagDetails();
            } else if (credit_type == 8 && cash_type > 0) {
                //8 Means Agent and cash type Bank cash
                $('.ag_card').show();
                getCBagDetails();
            }



        }
    })

    $('#debit_type').change(function () {
        hideAllCardsfunction()
        var debit_type = $(this).val();
        var cash_type = $('input[name=cash_type]:checked').val();

        if (debit_type != '') {

            ////////////////////// For Contra Debit Types ///////////////////////
            if (debit_type == 6 && cash_type == 0) {
                // 6 means Bank Deposit and cash type is hand cash
                // it meanst, amount from hand has been taken for deposit into bank
                $('.contra_card').show();
                getBankDepositDetails();
            } else if (debit_type == 7 && cash_type > 0) {
                // 7 means Cash Withdrawal and cash type is Bank cash
                // it meanst, amount from bank has been withdrawal for hand use
                $('.contra_card').show();
                getCashWithdrawalDetails();
            } else if (debit_type == 4 && cash_type == 0) {
                //4 Means Exchange and cash type hand cash
                $('.exchange_card').show();
                getHandExchangeInputs();
            } else if (debit_type == 4 && cash_type > 0) {
                //4 Means Exchange and cash type Bank cash
                $('.exchange_card').show();
                getBankExchangeInputs();
            } else if (debit_type == 13 && cash_type == 0) {
                //13 Means Issued and cash type Hand cash
                $('.issued_card').show();
                getHissuedTable();
            } else if (debit_type == 13 && cash_type > 0) {
                //13 Means Issued and cash type Bank cash
                $('.issued_card').show();
                getBissuedTable();
            } else if (debit_type == 14 && cash_type == 0) {
                //14 Means Issued and cash type Hand cash
                $('.expense_card').show();
                getHexpenseTable();
            } else if (debit_type == 14 && cash_type > 0) {
                //14 Means Issued and cash type Bank cash
                $('.expense_card').show();
                getBexpenseTable();
            } else if (debit_type == 9 && cash_type == 0) {
                //9 Means Investment and cash type Hand cash
                $('.inv_card').show();
                getDHinvDetails();
            } else if (debit_type == 9 && cash_type > 0) {
                //9 Means Investment and cash type Bank cash
                $('.inv_card').show();
                getDBinvDetails();
            } else if (debit_type == 10 && cash_type == 0) {
                //10 Means Deposit and cash type Hand cash
                $('.inv_card').show();
                getDHdepDetails();
            } else if (debit_type == 10 && cash_type > 0) {
                //10 Means Deposit and cash type Bank cash
                $('.inv_card').show();
                getDBDepDetails();
            } else if (debit_type == 11 && cash_type == 0) {
                //11 Means EL and cash type Hand cash
                $('.inv_card').show();
                getDHelDetails();
            } else if (debit_type == 11 && cash_type > 0) {
                //11 Means EL and cash type Bank cash
                $('.inv_card').show();
                getDBelDetails();
            } else if (debit_type == 12 && cash_type > 0) {
                //12 Means Excess fund and cash type Bank cash
                $('.exf_card').show();
                getExfDetails();
            } else if (debit_type == 8 && cash_type == 0) {
                //8 Means Agent and cash type Hand cash
                $('.ag_card').show();
                getDHagDetails();
            } else if (debit_type == 8 && cash_type > 0) {
                //8 Means Agent and cash type Bank cash
                $('.ag_card').show();
                getDBagDetails();
            }
        }
    })

    $('#sheet_type').change(function () {
        var sheet_type = $(this).val();

        $('#exp_typeDiv').hide(); $('#exp_view_type, #exp_cat_type').val('');//hide expense view option 
        $('#IDE_Div').hide(); $('#IDE_type, #IDE_view_type, #IDE_name_list').val('');//hide IDE view option and empty values
        $('#ag_typeDiv').hide(); $('#ag_view_type,#ag_namewise').val('');//hide Agent view option and empty values

        if (sheet_type != '' && sheet_type != 4 && sheet_type != 5 && sheet_type != 7) {
            // blocking sheet type 4 beacause expense balsheet should showed after selecting view type and 5 because Inv/Dep/EL should be validated more

            $.ajax({
                url: 'accountsFile/cashtally/contra/getBalanceSheet.php',
                data: { 'sheet_type': sheet_type },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#blncSheetDiv').empty()
                    $('#blncSheetDiv').html(response)
                }
            })
        } else if (sheet_type == 4) {
            $('#blncSheetDiv').empty()
            $('#exp_typeDiv').show()

        } else if (sheet_type == 5) {
            $('#blncSheetDiv').empty()
            $('#IDE_Div').show()

            $('#IDE_type').off().change(function () {
                let IDE_type_arr = { 1: 'inv', 2: 'dep', 3: 'el' };
                let IDE_type = $(this).val();
                let opt_for = IDE_type_arr[IDE_type];
                $("#opt_for").val(opt_for);

                // to get name detail creation table 
                $.ajax({
                    url: 'accountsFile/cashtally/getNameBasedDetails.php',
                    data: { opt_for },
                    dataType: 'json',
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#IDE_name_list').empty();
                        $('#IDE_name_list').append("<option value=''>Select Name</option>");
                        $.each(response, function (index, item) {
                            $("#IDE_name_list").append("<option value='" + item['name_id'] + "'>" + item['name'] + "</option>");
                        });

                        $('#IDE_name_list').off().change(function () {
                            var name_id = $(this).val();// get the name table id
                            $.each(response, function (index, item) {
                                if (name_id == item['name_id']) {
                                    $('#IDE_name_area').val(item['area']);
                                }
                            })
                        })
                    }
                })
            })
        } else if (sheet_type == 7) {
            $('#blncSheetDiv').empty()
            $('#ag_typeDiv').show()

        }
    })

    $('.name-model-close').click(function () {
        let opt_for = $('#opt_for').val();
        resetNameDetailDropdown(opt_for);
    })

    $('#exp_view_type').change(function () {
        triggerExpViewActions();
    });

    $('#exp_cat_type').click(function () {
        var sheet_type = $('#sheet_type').val();
        var exp_cat_type = $(this).val();

        if (exp_cat_type != '') { // call balance sheet ajax with expense category type to show category wise
            $.ajax({
                url: 'accountsFile/cashtally/contra/getBalanceSheet.php',
                data: { 'sheet_type': sheet_type, 'exp_cat_type': exp_cat_type },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#blncSheetDiv').empty()
                    $('#blncSheetDiv').html(response)
                }
            })
        }
    })

    $('#IDE_type').change(function () {
        $('#blncSheetDiv').empty();
        $('.IDE_nameDiv').hide();
        $('#IDE_view_type').val(''); $('#IDE_name_list').val('');
    })
    $('#IDE_view_type').change(function () {
        $('#blncSheetDiv').empty()

        var view_type = $(this).val();//overall/Individual
        var type = $('#IDE_type').val(); //investment/Deposit/EL

        if (view_type == 1 && type != '') {
            $('#IDE_name_list').val(''); //reset name value when using overall
            $('.IDE_nameDiv').hide() // hide name list div
            getIDEBalanceSheet();
        } else if (view_type == 2 && type != '') {
            $('.IDE_nameDiv').show()
        } else {
            $('.IDE_nameDiv').hide()
        }
    })

    $('#IDE_name_list').change(function () {
        var name_id = $(this).val();
        if (name_id != '') {
            getIDEBalanceSheet();
        }
    })

    $('#ag_view_type').change(function () {
        var view_type = $(this).val();
        if (view_type == 1) {
            $('#ag_namewiseDiv').hide();
            $('#ag_namewise').val('');
            getAgBalancesheet();
        } else if (view_type == 2) {
            $('#ag_namewiseDiv').show();
            getAgentName('ag_namewise');
        } else {
            $('#ag_namewiseDiv').hide();
            $('#ag_namewise').val('');
        }
    })

    $('#ag_namewise').change(function () {
        var ag_name = $(this).val();
        if (ag_name != '') {
            getAgBalancesheet();
        }
    })

    $('#addUntracked').click(function () {
        $.ajax({
            url: 'accountsFile/cashtally/contra/getBankDetails.php',
            data: {},
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                $('#bank_id_untracked').empty()
                $('#bank_id_untracked').append(`<option value=''>Select Bank Name</option>`)
                for (var i = 0; i < response.length; i++) {
                    $('#bank_id_untracked').append(`<option value='` + response[i]['bank_id'] + `'>` + response[i]['bank_name'] + `</option>`)
                }
            }
        })
    })

    $('#submit_untracked').click(function () {
        var op_date = $('#op_date').text(); var bank_id = $('#bank_id_untracked').val(); var amt = $('#untracked_amt').val();
        if (bank_id != '' && amt != '') {
            $('#closeUntracked').trigger('click');
            $('#bank_id_untracked').val(''); $('#untracked_amt').val('')
            $('.untrkd').each(function () {
                var valu = $(this).attr('id');
                if (valu == 'untrkd' + bank_id) {
                    $('#' + valu).text('(' + amt + ')')
                }
            })
        } else {
            if (bank_id == '') { $('#bank_id_untrackedCheck').show() } else { $('#bank_id_untrackedCheck').hide() }
            if (amt == '') { $('#untracked_amtCheck').show() } else { $('#untracked_amtCheck').hide() }
        }
    })

})//Document ready END

$(function () {// auto call function for fetching Opening and closing balance

    getOpeningDate(); // to get opening date

})

function getOpeningDate() {
    $.ajax({
        url: 'accountsFile/cashtally/getOpeningDate.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#op_date').text(response['opening_date']);
            // $('#opening_balance').text(response['opening_bal']);
            // $('#hand_opening').text(response['op_hand']);
            // $('#bank_opening').text(response['op_bank']);
            // $('#agent_opening').text(response['op_agent']);
            $('#oldclosingbal').val(response['closing_bal']);
            // $('#hand_closing').text(response['cl_hand']);
            // $('#bank_closing').text(response['cl_bank']);
            // $('#agent_closing').text(response['cl_agent']);
        }
    }).then(function () {
        getOpeningBalance();
        getClosingBalance();
    })
}

function getOpeningBalance() {
    var op_date = $('#op_date').text();
    var bank_detail = $('#user_bank_details').val();
    var oldclosingbal = $('#oldclosingbal').val();
    var bank_detail_arr = $('#user_bank_details').val().split(',');
    $.ajax({
        url: 'accountsFile/cashtally/getOpeningBalance.php',
        data: { 'op_date': op_date, 'bank_detail': bank_detail },
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {

            for (var j = 0; j < bank_detail_arr.length; j++) { //reset bank opening balance to 0
                $('#bank_opening' + j).text('0')
            }

            $('#opening_balance').text(oldclosingbal)
            $('#hand_opening').text(response[0]['hand_opening'])
            var i = 0;
            $.each(response, function (index, item) {
                $('#bank_opening' + i).text(item['bank_opening'])
                i++;
            })

            // add yseterday's closing untracked amount to today's untracted opening
            if (response[0]['bank_untrkd'] != '' && response[0]['bank_untrkd'] != undefined) {

                var untrkd_ids_op = $('#untrkd_ids_op').val().split(',');
                var untrkd_op = response[0]['bank_untrkd'].split(','); var k = 0;
                $.each(untrkd_ids_op, function (ind, val) {

                    $('#' + val).text('(' + untrkd_op[k] + ')')
                    k++
                })
            }

            $('#agent_opening').text(response[0]['agent_opening'])
        }
    })
}

function getClosingBalance() {
    var op_date = $('#op_date').text();
    var opening_balance = $('#opening_balance').text()
    var bank_detail = $('#user_bank_details').val();
    $.ajax({
        url: 'accountsFile/cashtally/getClosingBalance.php',
        data: { 'op_date': op_date, 'bank_detail': bank_detail },
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {
            var closing = parseInt(response[0]['closing_balance']);
            $('#closing_balance').text(closing)
            $('#hand_closing').text(response[0]['hand_closing'])
            var i = 0;
            $.each(response, function (index, item) {
                $('#bank_closing' + i).text(item['bank_closing'])
                i++;
            })
            $('#agent_closing').text(response[0]['agent_closing'])
            submitCashTally(i);
        }
    })
}
function getAllClosingBalance() {
    var op_date = $('#op_date').text();
    var opening_balance = $('#opening_balance').text()
    var bank_detail = $('#all_bank_details').val();
    $.ajax({
        url: 'accountsFile/cashtally/getAllClosingBalance.php',
        data: { 'op_date': op_date, 'bank_detail': bank_detail },
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {
            var closing = parseInt(response[0]['closing_balance']);
            $('#all_closing_balance').text(closing)
            $('#all_hand_closing').text(response[0]['hand_closing'])
            var i = 0;
            $.each(response, function (index, item) {
                $('#all_bank_closing' + i).text(item['bank_closing'] ?? 0)
                i++;
            })
            $('#all_agent_closing').text(response[0]['agent_closing'])
        }
    })
}

function submitCashTally(i) {
    // Assuming op_date is in the format "DD-MM-YYYY"
    let op_date_str = $('#op_date').text();
    let op_date_parts = op_date_str.split("-");
    let op_date = new Date(op_date_parts[2], op_date_parts[1] - 1, op_date_parts[0]);

    let currentDate = new Date();

    if (op_date <= currentDate) {
        $('#submit_cash_tally').off('click');
        $('#submit_cash_tally').click(function () {
            event.preventDefault();
            if (getBankCollectionSubmit() == 0 && getIssuedSubmitCheck() == 0) {

                if (confirm('Are You sure to close this Day?')) {

                    var op_date = $('#op_date').text();
                    var opening_bal = $('#opening_balance').text()
                    var hand_op = $('#hand_opening').text()
                    var bank_op = '';
                    for (var j = 0; j < i; j++) {
                        bank_op += $('#bank_opening' + j).text() + ',';
                    }
                    bank_op = bank_op.slice(0, -1);
                    var agent_op = $('#agent_opening').text()
                    var closing_bal = $('#closing_balance').text()
                    var hand_cl = $('#hand_closing').text()
                    var bank_cl = '';
                    for (var j = 0; j < i; j++) {
                        bank_cl += $('#bank_closing' + j).text() + ',';
                    }
                    bank_cl = bank_cl.slice(0, -1);

                    var bank_untrkd = '';
                    var untrkd_ids = $('#untrkd_ids').val().split(',');
                    $.each(untrkd_ids, function (ind, val) {
                        bank_untrkd += $('#' + val).text() + ',';
                    })
                    bank_untrkd = bank_untrkd.slice(0, -1);

                    var agent_cl = $('#agent_closing').text()
                    var formtosend = { op_date: op_date, opening_bal: opening_bal, hand_op: hand_op, bank_op: bank_op, agent_op: agent_op, closing_bal: closing_bal, hand_cl: hand_cl, bank_cl, bank_untrkd: bank_untrkd, agent_cl: agent_cl };
                    $.ajax({
                        url: 'accountsFile/cashtally/submitCashTally.php',
                        data: formtosend,
                        type: 'post',
                        cache: false,
                        success: function (response) {
                            if (response.includes('Successfully')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                })
                                getOpeningDate();
                            } else if (response.includes('Error')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'error',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                });
                            }
                        }
                    })
                } else {
                    return false;
                }
            } else {
                Swal.fire({
                    title: 'Submittion Error',
                    html: 'Please check: <br>1.Bank Collection <br> 2.Hand & Bank Issued<br> has submitted before Closing!',
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688'
                });

            }
        })
    } else {
        $('#submit_cash_tally').off('click');
        $('#submit_cash_tally').hide();

        $('#hand_cash_radio , .bank_cash_radio').off('click')
        $('#credit_type, #debit_type').off('change')

        getFutureOpeningBalance();
    }
}

function getBankCollectionSubmit() {
    var bank_id = $('#user_bank_details').val();
    var op_date = $('#op_date').text();
    var retval = 0;
    $.ajax({
        url: 'accountsFile/cashtally/getBankCollectionSubmit.php',
        data: { 'bank_id': bank_id, 'op_date': op_date },
        type: 'post',
        cache: false,
        async: false,  // Make the request synchronous
        success: function (response) {
            if (response.includes('Already')) {
                retval = 0;
            } else if (response.includes('Not')) {
                retval = 1;
            }
        }
    });
    return retval;
}

function getIssuedSubmitCheck() {
    let op_date = $('#op_date').text();
    let retval = 0;
    $.ajax({
        url: 'accountsFile/cashtally/getIssuedSubmitCheck.php',
        data: { 'op_date': op_date },
        type: 'post',
        cache: false,
        async: false,  // Make the request synchronous
        success: function (response) {
            if (response.includes('Already')) {
                retval = 0;
            } else if (response.includes('Not')) {
                retval = 1;
            }
        }
    });
    return retval;
}

function getFutureOpeningBalance() {
    var op_date = $('#op_date').text();
    var bank_detail = $('#user_bank_details').val();
    var oldclosingbal = $('#oldclosingbal').val();
    var bank_detail_arr = $('#user_bank_details').val().split(',');
    $.ajax({
        url: 'accountsFile/cashtally/getFutureOpeningBalance.php',
        data: { 'op_date': op_date, 'bank_detail': bank_detail },
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {

            for (var j = 0; j < bank_detail_arr.length; j++) { //reset bank opening balance to 0
                $('#bank_opening' + j).text('0')
            }

            $('#opening_balance').text(oldclosingbal)
            $('#hand_opening').text(response[0]['hand_opening'])
            var i = 0;
            $.each(response, function (index, item) {
                $('#bank_opening' + i).text(item['bank_opening'])
                i++;
            })

            // add yseterday's closing untracked amount to today's untracted opening
            if (response[0]['bank_untrkd'] != '' && response[0]['bank_untrkd'] != undefined) {

                var untrkd_ids_op = $('#untrkd_ids_op').val().split(',');
                var untrkd_op = response[0]['bank_untrkd'].split(','); var k = 0;
                $.each(untrkd_ids_op, function (ind, val) {

                    $('#' + val).text('(' + untrkd_op[k] + ')')
                    k++
                })
            }
            //remove closing date's untracked amount to zero on furture date
            var untrkd_ids = $('#untrkd_ids').val().split(',');
            $.each(untrkd_ids, function (ind, val) {
                $('#' + val).text('(0)');
            })
            //hide untracked adding button when future date is opening date
            $('#addUntracked').hide();
            $('#agent_opening').text(response[0]['agent_opening'])
        }
    })
}



function appendHandCreditDropdown() {

    $.ajax({
        url: 'accountsFile/cashtally/getCashTallyDropdown.php',
        data: { 'mode': 'handcredit' },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {

            $('#credit_type').empty();
            $('#credit_type').append("<option value=''>Select Credit Type</option>");
            for (var i = 0; i < response.length; i++) {
                $('#credit_type').append("<option value='" + response[i]['id'] + "'>" + response[i]['modes'] + "</option>");
            }
            sortDropdowns()

        }
    })

}
function appendHandDebitDropdown() {
    $.ajax({
        url: 'accountsFile/cashtally/getCashTallyDropdown.php',
        data: { 'mode': 'handdebit' },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {

            $('#debit_type').empty();
            $('#debit_type').append("<option value=''>Select Debit Type</option>");
            for (var i = 0; i < response.length; i++) {
                $('#debit_type').append("<option value='" + response[i]['id'] + "'>" + response[i]['modes'] + "</option>");
            }
            sortDropdowns()
        }
    })
}

function appendBankCreditDropdown() {
    $.ajax({
        url: 'accountsFile/cashtally/getCashTallyDropdown.php',
        data: { 'mode': 'bankcredit' },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {

            $('#credit_type').empty();
            $('#credit_type').append("<option value=''>Select Credit Type</option>");
            for (var i = 0; i < response.length; i++) {
                $('#credit_type').append("<option value='" + response[i]['id'] + "'>" + response[i]['modes'] + "</option>");
            }
            sortDropdowns()
        }
    })
}
function appendBankDebitDropdown() {
    $.ajax({
        url: 'accountsFile/cashtally/getCashTallyDropdown.php',
        data: { 'mode': 'bankdebit' },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {

            $('#debit_type').empty();
            $('#debit_type').append("<option value=''>Select Debit Type</option>");
            for (var i = 0; i < response.length; i++) {
                $('#debit_type').append("<option value='" + response[i]['id'] + "'>" + response[i]['modes'] + "</option>");
            }
            sortDropdowns()
        }
    });
}

function sortDropdowns() {
    var firstOption = $("#credit_type option:first-child");
    $("#credit_type").html($("#credit_type option:not(:first-child)").sort(function (a, b) {
        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
    }));
    $("#credit_type").prepend(firstOption);

    var firstOption = $("#debit_type option:first-child");
    $("#debit_type").html($("#debit_type option:not(:first-child)").sort(function (a, b) {
        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
    }));
    $("#debit_type").prepend(firstOption);
}

function triggerExpViewActions() {
    var sheet_type = $('#sheet_type').val();
    var exp_view_type = $('#exp_view_type').val();

    $('#blncSheetDiv').empty()

    if (exp_view_type == 1) { //if balance sheet needs to show overall, then call ajax normally

        $('#exp_cat_typeDiv').hide()

        $.ajax({
            url: 'accountsFile/cashtally/contra/getBalanceSheet.php',
            data: { 'sheet_type': sheet_type },
            type: 'post',
            cache: false,
            success: function (response) {
                $('#blncSheetDiv').empty()
                $('#blncSheetDiv').html(response)
            }
        })
    } else if (exp_view_type == 2) {
        $.ajax({//fetching expense category dropdown
            url: 'accountsFile/cashtally/expense/getHexpenseModal.php',
            data: {},
            dataType: 'json',
            type: 'post',
            success: function (response) {
                $('#exp_cat_type').empty();
                $('#exp_cat_type').append("<option value=''>Select Category</option>");
                for (var i = 0; i < response.length; i++) {
                    $('#exp_cat_type').append("<option value='" + response[i]['cat_id'] + "'>" + response[i]['cat_name'] + "</option>")
                }
                $('#exp_cat_typeDiv').show();
            }
        })

    } else {
        $('#exp_cat_typeDiv').hide()
    }
}

function hideAllCardsfunction() {
    $('.collection_card').hide();
    $('#collectionTableDiv').empty();// empty the card fields when hiding
    $('#receiveAmtDiv').empty();// empty the Modal fields when hiding

    $('.contra_card').hide();
    $('#contraTableDiv').empty();// empty the card fields when hiding
    $('#receivecdAmtDiv').empty();// empty the Modal fields when hiding
    $('#receivebwdAmtDiv').empty();// empty the Modal fields when hiding

    $('#blncSheetDiv').empty();// empty the Balance sheet Modal fields when hiding

    $('.exchange_card').hide();
    $('#exchangeDiv').empty(); //empty the card fields when hiding
    $('#hexchangeDiv').empty(); //empty the Modal fields when hiding
    $('#bexchangeDiv').empty(); //empty the Modal fields when hiding

    $('.oti_card').hide();
    $('#otiDiv').empty();//empy the card 

    $('.issued_card').hide();
    $('#issuedDiv').empty();//empy the card 
    $('#bissuedDiv').empty();//empy the Modal

    $('.expense_card').hide();
    $('#expenseDiv').empty();//empy the card 
    $('#hexp_modalDiv').empty();//empy the Modal
    $('#bexp_modalDiv').empty();//empy the Modal

    $('.inv_card').hide();
    $('#invDiv').empty();//empy the card 

    $('.exf_card').hide();
    $('#exfDiv').empty();//empy the card 

    $('.ag_card').hide();
    $('#agDiv').empty();//empy the card 
}


// //////////////////////////////////////////////////// Hand Collection //////////////////////////////////////////////// //
function getCollectionDetails() {
    var user_branch_id = $('#user_branch_id').val();
    var op_date = $('#op_date').text();
    $.ajax({
        url: 'accountsFile/cashtally/getCollectionDetails.php',
        data: { 'branch_id': user_branch_id, 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collectionTableDiv').empty();
            $('#collectionTableDiv').html(response);
        }
    })
}

function collectBtnClick(user_id) {
    // $('.collect_btn').click(function(){
    var user_id = $(user_id).data('value');
    var op_date = $('#op_date').text();
    $.ajax({
        url: 'accountsFile/cashtally/receiveAmtModal.php',
        data: { 'user_id': user_id, 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#receiveAmtDiv').empty();
            $('#receiveAmtDiv').html(response);
        }
    }).then(function () {
        $('#submit_rec').click(function () {
            var formData = $('#coll_rec_form').serializeArray(); // Serialize the form inputs to send all data
            var op_date = $('#op_date').text();

            // Append op_date to the formData array
            formData.push({ name: 'op_date', value: op_date });

            if ($('#rec_amt').val() != '') {
                $('#rec_amt_check').hide();
                $.ajax({
                    url: 'accountsFile/cashtally/submitReceivedCollection.php',
                    data: formData,
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    var user_id = $('#user_id_rec').val();
                                    var op_date = $('#op_date').text();
                                    $.ajax({
                                        url: 'accountsFile/cashtally/receiveAmtModal.php',
                                        data: { 'user_id': user_id, 'op_date': op_date },
                                        type: 'post',
                                        cache: false,
                                        success: function (response) {
                                            $('#receiveAmtDiv').empty();
                                            $('#receiveAmtDiv').html(response);
                                        }
                                    })
                                }
                            })
                        } else {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getClosingBalance();
                    }
                })
            } else {
                $('#rec_amt_check').show();
            }
        })
    })
    // })
}

function closeReceiveModal() {
    var user_branch_id = $('#user_branch_id').val();
    var op_date = $('#op_date').text();
    $.ajax({
        url: 'accountsFile/cashtally/getCollectionDetails.php',
        data: { 'branch_id': user_branch_id, 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collectionTableDiv').empty();
            $('#collectionTableDiv').html(response);
        }
    })
}
// ///////////////////////////////////////////////////// Hand Collection /////////////////////////////////////////////// //


// ///////////////////////////////////////////////////// Bank Collection /////////////////////////////////////////////// //
function getBankCollectionDetails(bank_id) {
    var op_date = $('#op_date').text();
    $('#collectionTableDiv').empty();// empty the card fileds when hiding
    var fieldsAppend = `<div class='col-md-12'><div class='row'>
    <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12'><div class='form-group'>
    <input type='hidden' id='bank_id' name='bank_id' value='`+ bank_id + `'> 
    <label for='bank_credit_amt'> Bank Credit Amount</label>
    <input type='text' id='bank_credit_amt' name='bank_credit_amt' class='form-control' value ='1' title='Enter 0 if no Transaction' readonly>
    <span class='text-danger' id='bank_credit_check' style='display:none'>Please Enter Credited Amount</span></div></div>
    <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12'><div class='form-group'>
    <label for='' style='visibility:hidden'> Bank Credit Submit</label><br>
    <input type='button' id='submit_bank_credit' name='submit_bank_credit' value='Submit' class='btn btn-primary'></div></div>
    </div></div>`;
    $('#collectionTableDiv').html(fieldsAppend);

    $.ajax({ // to get today's collection amount by bank
        url: 'accountsFile/cashtally/getBankCollectionAmount.php',
        data: { 'bank_id': bank_id, 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {
            if (response == '') {
                $('#bank_credit_amt').val('0')
            } else {
                $('#bank_credit_amt').val(response)
            }
        }
    })

    $('#submit_bank_credit').click(function () {
        var bank_id = $('#bank_id').val()
        var credited_amt = $('#bank_credit_amt').val();
        var op_date = $('#op_date').text();
        if (credited_amt != '') {
            $('#bank_credit_check').hide();
            $.ajax({
                url: 'accountsFile/cashtally/submitBankCredit.php',
                data: { 'bank_id': bank_id, 'credited_amt': credited_amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    } else if (response.includes('Already')) {
                        Swal.fire({
                            title: response,
                            icon: 'info',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    // $('#bank_credit_amt').val('');
                    getBankCollectionDetails(bank_id);
                    getClosingBalance();
                }
            })
        } else {
            $('#bank_credit_check').show();
        }
    })
}
// //////////////////////////////////////////////////// Bank Collection //////////////////////////////////////////////// //


// //////////////////////////////////////////////////// Contra Start //////////////////////////////////////////////////////// //

//inputs for bank deposit 
function getBankDepositDetails() {
    var appendTxt = `
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="to_bank_bdep">To Bank</label>
                <select id="to_bank_bdep" name="to_bank_bdep" class="form-control">
                    
                </select>
                <span class="text-danger" id='to_bank_bdepCheck' style="display:none">Please Select Bank Name</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="location_bdep">Location</label>
                <input type="text" id="location_bdep" name="location_bdep" class="form-control" placeholder="Please Enter Location">
                <span class="text-danger" id='location_bdepCheck' style="display:none">Please Enter Location</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="remark_bdep">Remark</label>
                <input type="text" id="remark_bdep" name="remark_bdep" class="form-control" placeholder="Please Enter Remark">
                <span class="text-danger" id='remark_bdepCheck' style="display:none">Please Enter Remark</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="amt_bdep">Amount</label>
                <input type="number" id="amt_bdep" name="amt_bdep" class="form-control" placeholder="Please Enter Amount" onkeydown="validateHandCash(this)">
                <span class="text-danger" id='amt_bdepCheck' style="display:none">Please Enter Amount</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label style="visibility:hidden">amt</label><br>
                <input type="button" id="submit_bdep" name="submit_bdep" class="btn btn-primary" value="Submit">
            </div>
        </div>`;
    $('.contra_card_header').text('Contra - Bank Deposit')
    $('#contraTableDiv').addClass('row', !$('#contraTableDiv').hasClass('row'));
    $('#contraTableDiv').empty()
    $('#contraTableDiv').html(appendTxt);

    $.ajax({
        url: 'accountsFile/cashtally/contra/getBankDetails.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#to_bank_bdep').empty()
            $('#to_bank_bdep').append(`<option value=''>Select Bank Name</option>`)
            for (var i = 0; i < response.length; i++) {
                $('#to_bank_bdep').append(`<option value='` + response[i]['bank_id'] + `'>` + response[i]['bank_name'] + `</option>`)
            }
        }
    }).then(function () {
        $('#submit_bdep').click(function () {
            if (validationBankDeposit() == 0) {
                var to_bank_bdep = $('#to_bank_bdep').val();
                var location_bdep = $('#location_bdep').val();
                var remark_bdep = $('#remark_bdep').val();
                var amt_bdep = $('#amt_bdep').val();
                var op_date = $('#op_date').text();
                $.ajax({
                    url: 'accountsFile/cashtally/contra/submitBankDeposit.php',
                    data: { 'to_bank_bdep': to_bank_bdep, 'location_bdep': location_bdep, 'remark_bdep': remark_bdep, 'amt_bdep': amt_bdep, 'op_date': op_date },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        $('#to_bank_bdep').val('');
                        $('#location_bdep').val('');
                        $('#remark_bdep').val('');
                        $('#amt_bdep').val('');
                        getClosingBalance();
                    }
                });
            }
        })
    })
}

//Bank deposit validation
function validationBankDeposit() {
    var to_bank_bdep = $('#to_bank_bdep').val();
    var location_bdep = $('#location_bdep').val();
    var remark_bdep = $('#remark_bdep').val();
    var amt_bdep = $('#amt_bdep').val();
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

    validateField(to_bank_bdep, '#to_bank_bdepCheck');
    validateField(location_bdep, '#location_bdepCheck');
    validateField(remark_bdep, '#remark_bdepCheck');
    validateField(amt_bdep, '#amt_bdepCheck');
    return response;
}

//get BAnk deposited amount detail table for Cash Deposit 
function getCashDepositDetails(bank_id) {
    $.ajax({
        url: 'accountsFile/cashtally/contra/getCashDepositDetails.php',
        data: { 'bank_id': bank_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('.contra_card_header').text('Contra - Cash Deposit')
            $('#contraTableDiv').removeClass('row')
            $('#contraTableDiv').empty()
            $('#contraTableDiv').html(response)
        }
    })
}

//Open modal when receive button clicked
function receivecdBtnClick(bdep_id1) {
    var bdep_id = $(bdep_id1).data('value');

    $.ajax({
        url: 'accountsFile/cashtally/contra/receivecdAmtModal.php',
        data: { 'bdep_id': bdep_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#receivecdAmtDiv').empty();
            $('#receivecdAmtDiv').html(response);
        }
    }).then(function () {
        $('#submit_cd').off('click');
        $('#submit_cd').click(function () {
            var formData = $('#cr_cd_form').serializeArray(); // Serialize the form inputs to send all data
            var op_date = $('#op_date').text();

            // Append op_date to the formData array
            formData.push({ name: 'op_date', value: op_date });

            if (cdValidation() == 0) {
                $.ajax({
                    url: 'accountsFile/cashtally/contra/submitCashDeposit.php',
                    data: formData,
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        } else if (response.includes('Already')) {
                            Swal.fire({
                                title: response,
                                text: 'Please close this module',
                                icon: 'warning',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        $('#closeCdModal').trigger('click');
                        getClosingBalance();
                    }
                })
            }
        })
    })
}

//Validation for Cash Deposit
function cdValidation() {
    var trans_id = $('#trans_id_cd').val(); var remark_cd = $('#remark_cd').val(); var response = 0;
    if (trans_id == '') {
        event.preventDefault();
        $('#trans_id_cdCheck').show();
        response = 1;
    } else {
        $('#trans_id_cdCheck').hide();
    }
    if (remark_cd == '') {
        event.preventDefault();
        $('#remark_cdCheck').show();
        response = 1;
    } else {
        $('#remark_cdCheck').hide();
    }
    return response;
}

//reset Bank Deposit table when Cash Deposit modal closed
function closCdModal() {
    //reset bank deposit modal
    var cash_type = $('input[name=cash_type]:checked').val();
    getCashDepositDetails(cash_type);
}

/********************************************************* Deposit Ends *******************************************************/

//get Cash withrawal from bank account input details
function getCashWithdrawalDetails() {
    var appendTxt = `
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="ref_code_cwd">Ref Code</label>
                <input type="text" id="ref_code_cwd" name="ref_code_cwd" class="form-control" readonly>
                <span class="text-danger" id='ref_code_cwdCheck' style="display:none">Please Enter Ref Code</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="trans_id_cwd">Transaction ID</label>
                <input type="text" id="trans_id_cwd" name="trans_id_cwd" class="form-control" placeholder="Enter Transaction ID">
                <span class="text-danger" id='trans_id_cwdCheck' style="display:none">Please Enter Transaction ID</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="from_bank_cwd">From Bank</label>
                <!-- <select id="from_bank_cwd" name="from_bank_cwd" class="form-control"> -->
                <!-- </select> -->
                <input type='hidden' class='form-control' id='from_bank_id_cwd' name='from_bank_id_cwd' readonly>
                <input type='text' class='form-control' id='from_bank_cwd' name='from_bank_cwd' readonly>
                <span class="text-danger" id='from_bank_cwdCheck' style="display:none">Please Select Bank Name</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="acc_no_cwd">From Bank</label>
                <input type='text' class='form-control' id='acc_no_cwd' name='acc_no_cwd' readonly>
                <span class="text-danger" id='acc_no_cwdCheck' style="display:none">Please Select Account Number</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="cheque_cwd">Cheque No.</label>
                <input type="number" id="cheque_cwd" name="cheque_cwd" class="form-control" placeholder="Enter Cheque No.">
                <span class="text-danger" id='cheque_cwdCheck' style="display:none">Please Enter Cheque No.</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="remark_cwd">Remark</label>
                <input type="text" id="remark_cwd" name="remark_cwd" class="form-control" placeholder="Enter Remark">
                <span class="text-danger" id='remark_cwdCheck' style="display:none">Please Enter Remark</span>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
            <div class="form-group">
                <label for="amt_cwd">Amount</label>
                <input type="number" id="amt_cwd" name="amt_cwd" class="form-control" placeholder="Enter Amount">
                <span class="text-danger" id='amt_cwdCheck' style="display:none">Please Enter Amount</span>
            </div>
        </div>
        <div class="col-12">
            <div class="text-right">
                <label style="visibility:hidden"></label><br>
                <input type="button" id="submit_cwd" name="submit_cwd" class="btn btn-primary" value="Submit">
            </div>
        </div>`;
    $('.contra_card_header').text('Contra - Cash Withdrawal')
    $('#contraTableDiv').addClass('row', !$('#contraTableDiv').hasClass('row'));
    $('#contraTableDiv').empty()
    $('#contraTableDiv').html(appendTxt);

    $.ajax({
        url: 'accountsFile/cashtally/contra/getRefCodeCWD.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_cwd').empty()
            $('#ref_code_cwd').val(response)
        }
    })

    var bank_id = $('input[name=cash_type]:checked').val();

    $.ajax({
        url: 'accountsFile/cashtally/contra/getBankDetails.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#from_bank_cwd').empty()
            // $('#from_bank_cwd').append(`<option value=''>Select Bank Name</option>`)
            for (var i = 0; i < response.length; i++) {
                // $('#from_bank_cwd').append(`<option value='`+response[i]['bank_id']+`'>`+response[i]['bank_name']+`</option>`)
                if (bank_id == response[i]['bank_id']) {
                    $('#from_bank_id_cwd').val(response[i]['bank_id'])
                    $('#from_bank_cwd').val(response[i]['bank_fullname'])
                    $('#acc_no_cwd').val(response[i]['acc_no'])
                }
            }
        }
    }).then(function () {
        $('#submit_cwd').off('click');
        $('#submit_cwd').click(function () {
            if (cwdvalidation() == 0) {
                var ref_code_cwd = $('#ref_code_cwd').val();
                var trans_id_cwd = $('#trans_id_cwd').val();
                var from_bank_cwd = $('#from_bank_id_cwd').val();
                var cheque_cwd = $('#cheque_cwd').val();
                var remark_cwd = $('#remark_cwd').val();
                var amt_cwd = $('#amt_cwd').val();
                var op_date = $('#op_date').text();
                $.ajax({
                    url: 'accountsFile/cashtally/contra/submitCashWithdrawal.php',
                    data: { 'ref_code': ref_code_cwd, 'trans_id': trans_id_cwd, 'from_bank': from_bank_cwd, 'cheque': cheque_cwd, 'remark': remark_cwd, 'amt': amt_cwd, 'op_date': op_date },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getCashWithdrawalDetails();
                        getClosingBalance();
                    }
                });
            }
        })
    })
}

//Cash withdrawal validation
function cwdvalidation() {
    var ref_code_cwd = $('#ref_code_cwd').val();
    var trans_id_cwd = $('#trans_id_cwd').val();
    var from_bank_cwd = $('#from_bank_cwd').val();
    var cheque_cwd = $('#cheque_cwd').val();
    var remark_cwd = $('#remark_cwd').val();
    var amt_cwd = $('#amt_cwd').val();
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

    validateField(trans_id_cwd, '#trans_id_cwdCheck');
    validateField(cheque_cwd, '#cheque_cwdCheck');
    validateField(remark_cwd, '#remark_cwdCheck');
    validateField(amt_cwd, '#amt_cwdCheck');
    return response;
}

//get Cash withdrawal entries in table for Bank withdrawal
function getBankWithdrawalDetails() {
    $.ajax({
        url: 'accountsFile/cashtally/contra/getBankWithdrawalDetails.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('.contra_card_header').text('Contra - Bank Withdrawal')
            $('#contraTableDiv').removeClass('row')
            $('#contraTableDiv').empty()
            $('#contraTableDiv').html(response)
        }
    })
}

//To get cash withdrawal details on Bank withdrawal modal 
function receivebwdBtnClick(bwd_id1) {
    var bwd_id = $(bwd_id1).data('value');
    $.ajax({
        url: 'accountsFile/cashtally/contra/receivebwdAmtModal.php',
        data: { 'bwd_id': bwd_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#receivebwdAmtDiv').empty();
            $('#receivebwdAmtDiv').html(response);
        }
    }).then(function () {
        $('#submit_bwd').off('click');
        $('#submit_bwd').click(function () {
            var formData = $('#cr_bwd_form').serializeArray(); // Serialize the form inputs to send all data
            var op_date = $('#op_date').text();

            // Append op_date to the formData array
            formData.push({ name: 'op_date', value: op_date });

            if (bwdValidation() == 0) {
                $.ajax({
                    url: 'accountsFile/cashtally/contra/submitBankWithdrawal.php',
                    data: formData,
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        } else if (response.includes('Already')) {
                            Swal.fire({
                                title: response,
                                text: 'Please close this module',
                                icon: 'warning',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        $('#closebwdModal').trigger('click');
                        getClosingBalance();
                    }
                })
            }
        })
    })
}

//Validation for Bank Withdrawal
function bwdValidation() {
    var remark_bwd = $('#remark_bwd').val(); var response = 0;
    if (remark_bwd == '') {
        event.preventDefault();
        $('#remark_bwdCheck').show();
        response = 1;
    } else {
        $('#remark_bwdCheck').hide();
    }

    return response;
}

/******************************************************** Withdrawal End ******************************************************/

function resetBlncSheet() {
    $('#credit_type').val('');
    $('#debit_type').val('');
    $('#sheet_type').val('');
    $('#blncSheetDiv').empty();
    $('#exp_view_type').val('');
    $('#exp_cat_type').val('');
    $('#exp_cat_typeDiv').hide();
    $('#exp_typeDiv').hide();
    $('#IDE_type').val('');
    $('#IDE_view_type').val('');
    $('#IDE_name_list').val('');
    $('#IDE_name_area').val('');
    $('#IDE_Div').hide();
    $('#ag_view_type').val('');
    $('#ag_namewiseDiv').val('');
    $('#ag_typeDiv').hide();
}

function getIDEBalanceSheet() {
    var type = $('#IDE_type').val(); //investment/Deposit/EL
    var view_type = $('#IDE_view_type').val();//overall/Individual
    var IDE_name_id = $('#IDE_name_list').val();//show by name wise
    var sheet_type = $('#sheet_type').val();

    $.ajax({
        url: 'accountsFile/cashtally/contra/getBalanceSheet.php',
        data: { 'sheet_type': sheet_type, 'IDEview_type': view_type, 'IDEtype': type, 'IDE_name_id': IDE_name_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#blncSheetDiv').empty()
            $('#blncSheetDiv').html(response)
        }
    })
}

function getAgBalancesheet() {
    var view_type = $('#ag_view_type').val();//overall/Agent wise
    var ag_name = $('#ag_namewise').val();//show by agent name wise
    var sheet_type = $('#sheet_type').val();

    $.ajax({
        url: 'accountsFile/cashtally/contra/getBalanceSheet.php',
        data: { 'sheet_type': sheet_type, 'ag_view_type': view_type, 'ag_name': ag_name },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#blncSheetDiv').empty()
            $('#blncSheetDiv').html(response)
        }
    })
}

function getAgentName(selectID) {
    $.ajax({
        url: 'accountsFile/cashtally/agent/getAgentName.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#' + selectID).empty();
            $('#' + selectID).append("<option value=''>Select Agent Name</option>");
            $.each(response, function (index, item) {
                $('#' + selectID).append("<option value='" + item['ag_id'] + "'>" + item['ag_name'] + "</option> ")
            })
        }
    })
}
// //////////////////////////////////////////////////// Contra END //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// Exhange Start //////////////////////////////////////////////////////// //

// To get hand exchange inputs as html and submit action Debit
function getHandExchangeInputs() {
    var appendText = `<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="user_id_hed">User Name</label>
            <select id="user_id_hed" name="user_id_hed" class="form-control" >
                <option value=''>Select User Name</option>
            </select>
            <span class="text-danger" id='user_id_hedCheck' style="display:none">Please Select User Name</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="user_type_hed">User Type</label>
            <input type="text" id="user_type_hed" name="user_type_hed" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="remark_hed">Remark</label>
            <input type="text" id="remark_hed" name="remark_hed" class="form-control" placeholder="Enter Remarks">
            <span class="text-danger" id='remark_hedCheck' style="display:none">Please Enter Remarks</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="amt_hed">Amount</label>
            <input type="number" id="amt_hed" name="amt_hed" class="form-control" placeholder="Enter Amount" onkeydown="validateHandCash(this)">
            <span class="text-danger" id='amt_hedCheck' style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hed" name="submit_hed" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#exchangeDiv').addClass('row', !$('#exchangeDiv').hasClass('row'));
    $('#exchangeDiv').empty()
    $('#exchangeDiv').html(appendText);

    $.ajax({
        url: 'accountsFile/cashtally/exchange/getHexchangeTableforDelete.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#exchangeDiv').append("<div class='col-12'><div class='form-group'>" + response + "</div></div>");
        }
    }).then(function () {
        $('.delete_hex').click(function () {
            if (confirm('Do You want to delete this?')) {
                var hex_id = $(this).data('value');
                $.ajax({
                    url: 'accountsFile/cashtally/exchange/getHexchangeDelete.php',
                    data: { 'hex_id': hex_id },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                            getHandExchangeInputs();
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getClosingBalance();
                    }
                })
            }
        })
    })

    $.ajax({
        url: 'accountsFile/cashtally/exchange/getHandExchangeInputs.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {

            $('#user_id_hed').empty();
            $('#user_id_hed').append("<option value=''>Select User Name</option>");
            for (var i = 0; i < response.length; i++) {
                $('#user_id_hed').append("<option value='" + response[i]['user_id'] + "'>" + response[i]['user_name'] + "</option>");
            }
            $('#user_id_hed').change(function () {
                var user_id = $(this).val();
                if (user_id != '') {
                    for (var i = 0; i < response.length; i++) {
                        if (user_id == response[i]['user_id']) {
                            var role = response[i]['role'];
                            var rolename = (role == '1') ? "Director" : (role == '3') ? "Staff" : '';
                            $('#user_type_hed').val(rolename);
                        }
                    }
                }
            })

        }
    }).then(function () {
        $('#submit_hed').click(function () {
            if (handExchangeValidation() != 1) {
                var user_id = $('#user_id_hed').val(); var remark = $('#remark_hed').val(); var amt = $('#amt_hed').val(); var op_date = $('#op_date').text();
                $.ajax({
                    url: 'accountsFile/cashtally/exchange/submitdbHandExchange.php',
                    data: { 'user_id': user_id, 'remark': remark, 'amt': amt, 'op_date': op_date },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                            $('#user_id_hed').val(''); $('#user_type_hed').val(''); $('#remark_hed').val(''); $('#amt_hed').val('');
                            getHandExchangeInputs();
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        } else if (response.includes('Already')) {
                            Swal.fire({
                                title: response,
                                text: 'Please close this module',
                                icon: 'warning',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }

                    }
                })
            }

        })
    })

}

function handExchangeValidation() {
    var user_id = $('#user_id_hed').val(); var remark = $('#remark_hed').val(); var amt = $('#amt_hed').val(); var res = 0;
    if (user_id == '') {
        event.preventDefault();
        $('#user_id_hedCheck').show();
        res = 1;
    } else {
        $('#user_id_hedCheck').hide();
    }
    if (remark == '') {
        event.preventDefault();
        $('#remark_hedCheck').show();
        res = 1;
    } else {
        $('#remark_hedCheck').hide();
    }
    if (amt == '') {
        event.preventDefault();
        $('#amt_hedCheck').show();
        res = 1;
    } else {
        $('#amt_hedCheck').hide();
    }
    return res;
}


//to get hand exchange credit input table
function getCreditHexchangeDetails() {
    $.ajax({
        url: 'accountsFile/cashtally/exchange/getCreditHexchangeDetails.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#exchangeDiv').removeClass('row')
            $('#exchangeDiv').empty();
            $('#exchangeDiv').html(response);
        }
    })
}

//To trigger modal and fetch details for hand exchange Credit
function hexCollectBtnClick(hex_id1) {
    var hex_id = $(hex_id1).data('value');
    $.ajax({
        url: 'accountsFile/cashtally/exchange/getHexchangeDetailModal.php',
        data: { 'hex_id': hex_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#hexchangeDiv').empty();
            $('#hexchangeDiv').html(response);
        }
    }).then(function () {
        $('#submit_hex').click(function () {
            var formdata = $('#cr_hex_form').serializeArray();
            var op_date = $('#op_date').text();

            // Append op_date to the formData array
            formdata.push({ name: 'op_date', value: op_date });

            $.ajax({
                url: 'accountsFile/cashtally/exchange/submitcrHandExchange.php',
                data: formdata,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    } else if (response.includes('Debited')) {
                        Swal.fire({
                            title: response,
                            icon: 'info',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    $('#closeHexchangeModal').trigger('click');
                    getCreditHexchangeDetails();
                    getClosingBalance();
                }
            })
        })
    })
}

//To get bank debit exchange details and submit button
function getBankExchangeInputs() {
    var appendText = `<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="ref_code_bex">Ref ID</label>
            <input type='text' id="ref_code_bex" name="ref_code_bex" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="from_acc_bex">From Account</label>
            <input type="hidden" id="from_acc_id_bex" name="from_acc_id_bex" class="form-control" readonly>
            <input type="text" id="from_acc_bex" name="from_acc_bex" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="to_bank_bex">To Bank</label>
            <select id="to_bank_bex" name="to_bank_bex" class="form-control">
            </select>
            <span class="text-danger" id='to_bank_bexCheck' style="display:none">Please Select Bank</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="user_name_bex">User Name</label>
            <input type="hidden" id="user_id_bex" name="user_id_bex" class="form-control" readonly>
            <input type="text" id="user_name_bex" name="user_name_bex" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="trans_id_bex">Transaction ID</label>
            <input type="text" id="trans_id_bex" name="trans_id_bex" class="form-control" placeholder="Enter Transaction ID">
            <span id="trans_id_bexCheck" class="text-danger" style="display:none">Please Enter Transaction ID </span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="remark_bex">Remark</label>
            <input type="text" id="remark_bex" name="remark_bex" class="form-control" placeholder="Enter Remark">
            <span id="remark_bexCheck" class="text-danger" style="display:none">Please Enter Remarks</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="amt_bex">Amount</label>
            <input type="number" id="amt_bex" name="amt_bex" class="form-control" placeholder="Enter Amount">
            <span id="amt_bexCheck" class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_bex" name="submit_bex" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#exchangeDiv').addClass('row', !$('#exchangeDiv').hasClass('row'));
    $('#exchangeDiv').empty()
    $('#exchangeDiv').html(appendText);

    var cash_type = $('input[name=cash_type]:checked').val();

    $.ajax({
        url: 'accountsFile/cashtally/exchange/getBankExchangeInputs.php',
        data: { 'cash_type': cash_type },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_bex').val(response[0]['ref_code']);
            $('#from_acc_id_bex').val(response[0]['bank_id']);
            $('#from_acc_bex').val(response[0]['bank_name']);

            $('#to_bank_bex').empty();
            $('#to_bank_bex').append("<option value=''>Select Bank Name</option>");
            for (var i = 1; i < response.length; i++) {
                $('#to_bank_bex').append("<option value='" + response[i]['to_bank_id'] + "'>" + response[i]['to_bank_name'] + "</option>");
            }

            //to fetch user name based on to bank id selected
            $('#to_bank_bex').change(function () {
                var to_bank_id = $(this).val();
                if (to_bank_id != '') {
                    for (var i = 1; i < response.length; i++) {
                        if (to_bank_id == response[i]['to_bank_id']) {
                            $('#user_id_bex').val(response[i]['bank_user_id'])
                            $('#user_name_bex').val(response[i]['bank_user_name'])
                        }
                    }
                }
            })
        }
    }).then(function () {
        $('#submit_bex').click(function () {
            if (bankExchangeValidation() != 1) {
                var ref_code = $('#ref_code_bex').val(); var from_acc_id_bex = $('#from_acc_id_bex').val(); var from_acc_bex = $('#from_acc_bex').val(); var to_bank_bex = $('#to_bank_bex').val(); var trans_id_bex = $('#trans_id_bex').val();
                var user_id_bex = $('#user_id_bex').val(); var remark_bex = $('#remark_bex').val(); var amt_bex = $('#amt_bex').val(); var op_date = $('#op_date').text();
                var formdata = { ref_code: ref_code, from_acc_id_bex: from_acc_id_bex, from_acc_bex: from_acc_bex, to_bank_bex: to_bank_bex, trans_id_bex: trans_id_bex, user_id_bex: user_id_bex, remark_bex: remark_bex, amt_bex: amt_bex, op_date: op_date };
                $.ajax({
                    url: 'accountsFile/cashtally/exchange/submitdbBankExchange.php',
                    data: formdata,
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                            getBankExchangeInputs();
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getClosingBalance();
                    }
                })
            }
        })
    })

}

function bankExchangeValidation() {
    var to_bank_bex = $('#to_bank_bex').val(); var trans_id_bex = $('#trans_id_bex').val(); var remark_bex = $('#remark_bex').val(); var amt_bex = $('#amt_bex').val();
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

    validateField(to_bank_bex, '#to_bank_bexCheck');
    validateField(trans_id_bex, '#trans_id_bexCheck');
    validateField(remark_bex, '#remark_bexCheck');
    validateField(amt_bex, '#amt_bexCheck');
    return response;
}

//to get Bank exchange credit input table
function getCreditBexchangeDetails() {
    var bank_id = $('input[name=cash_type]:checked').val();
    $.ajax({
        url: 'accountsFile/cashtally/exchange/getCreditBexchangeDetails.php',
        data: { 'bank_id': bank_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#exchangeDiv').removeClass('row')
            $('#exchangeDiv').empty();
            $('#exchangeDiv').html(response);
        }
    })
}

// to fetch details for Bank exchange credit modal
function bexCollectBtnClick(bex_id1) {
    var bex_id = $(bex_id1).data('value');
    $.ajax({
        url: 'accountsFile/cashtally/exchange/getBexchangeDetailModal.php',
        data: { 'bex_id': bex_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#bexchangeDiv').empty();
            $('#bexchangeDiv').html(response);
        }
    }).then(function () {

        $('#submit_bex').click(function () {
            var formdata = $('#cr_bex_form').serializeArray();
            var op_date = $('#op_date').text();

            // Append op_date to the formData array
            formdata.push({ name: 'op_date', value: op_date });

            if (bexValidation() != 1) {
                $.ajax({
                    url: 'accountsFile/cashtally/exchange/submitcrBankExchange.php',
                    data: formdata,
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                            $('#closebexchangeModal').trigger('click');
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getClosingBalance();
                    }
                })
            }
        })
    })
}

function bexValidation() {
    var trans_id = $('#trans_id').val(); var remark = $('#remark').val(); var response = 0;
    if (trans_id == '') {
        event.preventDefault();
        $('#trans_idCheck').show();
        response = 1;
    } else {
        $('#trans_idCheck').hide();
    }
    if (remark == '') {
        event.preventDefault();
        $('#remarkCheck').show();
        response = 1;
    } else {
        $('#remarkCheck').hide();
    }
    return response;
}

// //////////////////////////////////////////////////// Exhange End //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// Other Income Start //////////////////////////////////////////////////////// //

//to get the hand other income inputs and submit button action
function getHotherincomeDetails() {
    var appendText = `<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="cat_info">Category</label>
            <input type='text' id="cat_info" name="cat_info" class="form-control" placeholder="Enter Category">
            <span id='cat_infoCheck' class="text-danger" style="display:none">Please Enter Category</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="remark">Remark</label>
            <input type="text" id="remark" name="remark" class="form-control" placeholder="Enter Remark">
            <span id='remarkCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="amt">Amount</label>
            <input type="number" id="amt" name="amt" class="form-control" placeholder="Enter Amount">
            <span id='amtCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-8"></div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="text-right">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hoti" name="submit_hoti" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#otiDiv').addClass('row', !$('#otiDiv').hasClass('row'));
    $('#otiDiv').empty()
    $('#otiDiv').html(appendText);

    $('#submit_hoti').click(function () {
        if (otiValidation() == 0) {
            var cat_info = $('#cat_info').val(); var remark = $('#remark').val(); var amt = $('#amt').val(); var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/otherincome/submitHotherincome.php',
                data: { 'cat_info': cat_info, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getHotherincomeDetails();
                    getClosingBalance();
                }
            })
        }
    })
}

//validation fot hand other income
function otiValidation() {
    var cat_info = $('#cat_info').val(); var remark = $('#remark').val(); var amt = $('#amt').val();
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

    validateField(cat_info, '#cat_infoCheck');
    validateField(remark, '#remarkCheck');
    validateField(amt, '#amtCheck');
    return response;
}

//to get the Bank other income inputs and submit button action
function getBotherincomeDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="ref_code_boti">Ref ID</label>
            <input type='text' id="ref_code_boti" name="ref_code_boti" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="cat_info">Category</label>
            <input type='text' id="cat_info" name="cat_info" class="form-control" placeholder="Enter Category">
            <span id='cat_infoCheck' class="text-danger" style="display:none">Please Enter Category</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="trans_id">Transaction ID</label>
            <input type='text' id="trans_id" name="trans_id" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_idCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="remark">Remark</label>
            <input type="text" id="remark" name="remark" class="form-control" placeholder="Enter Remark">
            <span id='remarkCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label for="amt">Amount</label>
            <input type="number" id="amt" name="amt" class="form-control" placeholder="Enter Amount">
            <span id='amtCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
        <div class="form-group">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_boti" name="submit_boti" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#otiDiv').addClass('row', !$('#otiDiv').hasClass('row'));
    $('#otiDiv').empty()
    $('#otiDiv').html(appendText);

    $.ajax({
        url: 'accountsFile/cashtally/otherincome/getrefcodeBoti.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_boti').val(response);
        }
    })
    $('#submit_boti').click(function () {
        if (botiValidation() == 0) {
            var ref_code = $('#ref_code_boti').val(); var cat_info = $('#cat_info').val(); var trans_id = $('#trans_id').val(); var remark = $('#remark').val(); var amt = $('#amt').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/otherincome/submitBotherincome.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'cat_info': cat_info, 'trans_id': trans_id, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getBotherincomeDetails();
                    getClosingBalance();
                }
            })
        }
    })
}

//validation fot hand other income
function botiValidation() {
    var cat_info = $('#cat_info').val(); var remark = $('#remark').val(); var amt = $('#amt').val(); var trans_id = $('#trans_id').val();
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

    validateField(cat_info, '#cat_infoCheck');
    validateField(trans_id, '#trans_idCheck');
    validateField(remark, '#remarkCheck');
    validateField(amt, '#amtCheck');
    return response;
}

// //////////////////////////////////////////////////// Other Income End //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// Issued Start //////////////////////////////////////////////////////// //

//get table Details for Hand issued from loan issue tables and submit button
function getHissuedTable() {
    var op_date = $('#op_date').text();

    $.ajax({
        url: 'accountsFile/cashtally/issued/getHissuedTable.php',
        data: { 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#issuedDiv').removeClass('row')
            $('#issuedDiv').empty();
            $('#issuedDiv').html(response);
        }
    }).then(function () {
        $('.hissued_btn').click(function () {
            var amt = $(this).parent().prev().text();
            var netcash = $(this).parent().prev().prev().text();
            var username = $(this).parent().prev().prev().prev().text();
            var usertype = $(this).parent().prev().prev().prev().prev().text();
            var user_id = $(this).data('value');
            var op_date = $('#op_date').text();

            var fomrdata = { amt: amt, netcash: netcash, username: username, usertype: usertype, user_id: user_id, op_date: op_date }
            if (confirm("Are you sure to submit this?")) {

                $.ajax({
                    url: 'accountsFile/cashtally/issued/submitHissued.php',
                    data: fomrdata,
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getHissuedTable();
                        getClosingBalance();
                    }
                })
            }
        })

    })
}

//get table Details for Bank issued from loan issue tables and submit button
function getBissuedTable() {
    var bank_id = $('input[name=cash_type]:checked').val();
    $.ajax({
        url: 'accountsFile/cashtally/issued/getBissuedTable.php',
        data: { 'bank_id': bank_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#issuedDiv').removeClass('row')
            $('#issuedDiv').empty();
            $('#issuedDiv').html(response);
        }
    }).then(function () {
        $('.bissued_btn').click(function () {
            var user_id = $(this).data('value');
            var li_id = $(this).data('id');
            $.ajax({
                url: 'accountsFile/cashtally/issued/getBissuedForModal.php',
                data: { 'user_id': user_id, 'li_id': li_id },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#bissuedDiv').empty();
                    $('#bissuedDiv').html(response);
                }
            }).then(function () {
                $('#submit_bissued').click(function () {
                    var formdata = $('#db_bissued_form').serializeArray();
                    var op_date = $('#op_date').text();

                    // Append op_date to the formData array
                    formdata.push({ name: 'op_date', value: op_date });

                    $.ajax({
                        url: 'accountsFile/cashtally/issued/submitBissued.php',
                        data: formdata,
                        type: 'post',
                        cache: false,
                        success: function (response) {
                            if (response.includes('Successfully')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                })
                            } else if (response.includes('Error')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'error',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                });
                            }
                            getBissuedTable();
                            $('#closeissuedModal').trigger('click');
                            getClosingBalance();
                        }
                    })

                })
            })
        })
    })
}

// //////////////////////////////////////////////////// Issued End //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// Expenses Start //////////////////////////////////////////////////////// //

//To get inputs Details for expense table 
function getHexpenseTable() {
    var op_date = $('#op_date').text();

    $.ajax({
        url: 'accountsFile/cashtally/expense/getHexpenseTable.php',
        data: { 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {

            $('.expense_card_header').empty();
            $('.expense_card_header').html('Expense<button type="button" class="btn btn-primary" id="" name="" data-toggle="modal" data-target=".hexp_modal" style="padding: 5px 35px; float: right;" onclick="hexpenseModalBtnClick()"><span class="icon-add"></span></button>')

            $('#expenseDiv').empty();
            $('#expenseDiv').removeClass('row');
            $('#expenseDiv').html(response);
        }
    }).then(function () {
        $('.delete_hexp').click(function () {
            if (confirm("Do you want to delete this Expense?")) {
                var hexp_id = $(this).data('value');
                $.ajax({
                    url: 'accountsFile/cashtally/expense/deleteHexpense.php',
                    data: { 'hexp_id': hexp_id },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getHexpenseTable();
                        getClosingBalance();
                    }
                })

            }
        })
    })
}

//Hand expense modal btn click and submit btn click events
function hexpenseModalBtnClick() {
    var appendTxt = `<div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="username_hexp">User Name</label>
                            <input type='hidden' id="user_id_hexp" name="user_id_hexp">
                            <input type='text' id="username_hexp" name="username_hexp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="usertype_hexp">User Type</label>
                            <input type='text' id="usertype_hexp" name="usertype_hexp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="cat_hexp">Category</label><span class='text-danger'>&nbsp;*</span>
                            <select id="cat_hexp" name="cat_hexp" class="form-control" ></select>
                            <span id='cat_hexpCheck' class="text-danger" style="display:none">Please Select Category</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="part_hexp">Particulars</label><span class='text-danger'>&nbsp;*</span>
                            <input type='text' id="part_hexp" name="part_hexp" class="form-control" placeholder="Enter Particulars">
                            <span id='part_hexpCheck' class="text-danger" style="display:none">Please Enter Particulars</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="vou_id_hexp">Voucher ID</label><span class='text-danger'>&nbsp;*</span>
                            <input type='number' id="vou_id_hexp" name="vou_id_hexp" class="form-control" placeholder="Enter Voucher ID">
                            <span id='vou_id_hexpCheck' class="text-danger" style="display:none">Please Enter Voucher ID</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="rec_per_hexp">Receive Person</label><span class='text-danger'>&nbsp;*</span>
                            <input type='text' id="rec_per_hexp" name="rec_per_hexp" class="form-control" placeholder="Enter Receive Person">
                            <span id='rec_per_hexpCheck' class="text-danger" style="display:none">Please Enter Receive Person</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="remark_hexp">Remark</label><span class='text-danger'>&nbsp;*</span>
                            <input type="text" id="remark_hexp" name="remark_hexp" class="form-control" placeholder="Enter Remark">
                            <span id='remark_hexpCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="amt_hexp">Amount</label><span class='text-danger'>&nbsp;*</span>
                            <input type="number" id="amt_hexp" name="amt_hexp" class="form-control" placeholder="Enter Amount" onkeydown="validateHandCash(this)">
                            <span id='amt_hexpCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="upd_hexp">Upload</label>
                            <input type="file" id="upd_hexp" name="upd_hexp" class="form-control" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="text-center">
                            <label style="visibility:hidden"></label><br>
                            <input type="button" id="submit_hexp" name="submit_hexp" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </div>
            </div>
            </div>`;

    $('#hexp_modalDiv').empty();
    $('#hexp_modalDiv').html(appendTxt);

    $.ajax({//fetching hexpense modal details
        url: 'accountsFile/cashtally/expense/getHexpenseModal.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#cat_hexp').empty();
            $('#cat_hexp').append("<option value=''>Select Category</option>");
            for (var i = 0; i < response.length; i++) {
                $('#cat_hexp').append("<option value='" + response[i]['cat_id'] + "'>" + response[i]['cat_name'] + "</option>")
            }
            $('#user_id_hexp').val(response[0]['user_id'])
            $('#username_hexp').val(response[0]['user_name'])
            $('#usertype_hexp').val(response[0]['user_type'])

            {
                var firstOption = $("#cat_hexp option:first-child");
                $("#cat_hexp").html($("#cat_hexp option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#cat_hexp").prepend(firstOption);
            }
        }
    }).then(function () {
        $('#submit_hexp').click(function () {
            if (hexpenseValidation() == 0) {

                var user_id = $('#user_id_hexp').val(); var username = $('#username_hexp').val(); var usertype = $('#usertype_hexp').val(); var cat_hexp = $('#cat_hexp').val();
                var part_hexp = $('#part_hexp').val(); var vou_id_hexp = $('#vou_id_hexp').val(); var rec_per_hexp = $('#rec_per_hexp').val(); var remark_hexp = $('#remark_hexp').val();
                var amt_hexp = $('#amt_hexp').val(); var upd_hexp = $('#upd_hexp')[0].files[0]; var op_date = $('#op_date').text();

                var upload = $("#upd_hexp")[0];
                var file = upload.files[0];

                var formData = new FormData();
                formData.append('upd', file);
                formData.append('user_id', user_id);
                formData.append('username', username);
                formData.append('usertype', usertype);
                formData.append('cat', cat_hexp);
                formData.append('part', part_hexp);
                formData.append('vou_id', vou_id_hexp);
                formData.append('rec_per', rec_per_hexp);
                formData.append('remark', remark_hexp);
                formData.append('amt', amt_hexp);
                formData.append('op_date', op_date);


                $.ajax({
                    url: 'accountsFile/cashtally/expense/submitHexpenseModal.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getHexpenseTable();
                        $('#closehexpModal').trigger('click');
                        getClosingBalance();
                    }
                })
            }
        })


    })
}

// validation for hand expense
function hexpenseValidation() {
    var cat_hexp = $('#cat_hexp').val(); var part_hexp = $('#part_hexp').val(); var vou_id_hexp = $('#vou_id_hexp').val(); var rec_per_hexp = $('#rec_per_hexp').val(); var remark_hexp = $('#remark_hexp').val(); var amt_hexp = $('#amt_hexp').val();
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

    validateField(cat_hexp, '#cat_hexpCheck');
    validateField(part_hexp, '#part_hexpCheck');
    validateField(vou_id_hexp, '#vou_id_hexpCheck');
    validateField(rec_per_hexp, '#rec_per_hexpCheck');
    validateField(remark_hexp, '#remark_hexpCheck');
    validateField(amt_hexp, '#amt_hexpCheck');
    return response;

}


//To get inputs Details for Bank expense table 
function getBexpenseTable() {

    var bank_id = $('input[name=cash_type]:checked').val();
    var op_date = $('#op_date').text();
    $.ajax({
        url: 'accountsFile/cashtally/expense/getBexpenseTable.php',
        data: { 'bank_id': bank_id, 'op_date': op_date },
        type: 'post',
        cache: false,
        success: function (response) {

            $('.expense_card_header').empty();
            $('.expense_card_header').html('Expense<button type="button" class="btn btn-primary" id="" name="" data-toggle="modal" data-target=".bexp_modal" style="padding: 5px 35px; float: right;" onclick="bexpenseModalBtnClick()"><span class="icon-add"></span></button>')

            $('#expenseDiv').empty();
            $('#expenseDiv').removeClass('row');
            $('#expenseDiv').html(response);
        }
    }).then(function () {
        $('.delete_bexp').click(function () {
            if (confirm("Do you want to delete this Expense?")) {
                var bexp_id = $(this).data('value');
                $.ajax({
                    url: 'accountsFile/cashtally/expense/deletebexpense.php',
                    data: { 'bexp_id': bexp_id },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getBexpenseTable();
                        getClosingBalance();
                    }
                })

            }
        })
    })
}

//Bank expense modal btn click and submit btn click events
function bexpenseModalBtnClick() {
    var appendTxt = `<div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="username_bexp">User Name</label>
                            <input type='hidden' id="user_id_bexp" name="user_id_bexp">
                            <input type='hidden' id="bank_id_bexp" name="bank_id_bexp">
                            <input type='text' id="username_bexp" name="username_bexp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="usertype_bexp">User Type</label>
                            <input type='text' id="usertype_bexp" name="usertype_bexp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="ref_code_bexp">Ref ID</label>
                            <input type='text' id="ref_code_bexp" name="ref_code_bexp" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="cat_bexp">Category</label><span class='text-danger'>&nbsp;*</span>
                            <select id="cat_bexp" name="cat_bexp" class="form-control" ></select>
                            <span id='cat_bexpCheck' class="text-danger" style="display:none">Please Select Category</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="part_bexp">Particulars</label><span class='text-danger'>&nbsp;*</span>
                            <input type='text' id="part_bexp" name="part_bexp" class="form-control" placeholder="Enter Particulars">
                            <span id='part_bexpCheck' class="text-danger" style="display:none">Please Enter Particulars</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="vou_id_bexp">Voucher ID</label><span class='text-danger'>&nbsp;*</span>
                            <input type='number' id="vou_id_bexp" name="vou_id_bexp" class="form-control" placeholder="Enter Voucher ID">
                            <span id='vou_id_bexpCheck' class="text-danger" style="display:none">Please Enter Voucher ID</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="trans_id_bexp">Transaction ID</label><span class='text-danger'>&nbsp;*</span>
                            <input type='text' id="trans_id_bexp" name="trans_id_bexp" class="form-control" placeholder="Enter Transaction ID">
                            <span id='trans_id_bexpCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="rec_per_bexp">Receive Person</label><span class='text-danger'>&nbsp;*</span>
                            <input type='text' id="rec_per_bexp" name="rec_per_bexp" class="form-control" placeholder="Enter Receive Person">
                            <span id='rec_per_bexpCheck' class="text-danger" style="display:none">Please Enter Receive Person</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="remark_bexp">Remark</label><span class='text-danger'>&nbsp;*</span>
                            <input type="text" id="remark_bexp" name="remark_bexp" class="form-control" placeholder="Enter Remark">
                            <span id='remark_bexpCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="amt_bexp">Amount</label><span class='text-danger'>&nbsp;*</span>
                            <input type="number" id="amt_bexp" name="amt_bexp" class="form-control" placeholder="Enter Amount">
                            <span id='amt_bexpCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="upd_bexp">Upload</label>
                            <input type="file" id="upd_bexp" name="upd_bexp" class="form-control" >
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="text-left">
                            <label style="visibility:hidden"></label><br>
                            <input type="button" id="submit_bexp" name="submit_bexp" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </div>
            </div>
            </div>`;

    $('#bexp_modalDiv').empty();
    $('#bexp_modalDiv').html(appendTxt);

    var bank_id = $('input[name=cash_type]:checked').val();
    $('#bank_id_bexp').val(bank_id);

    $.ajax({//For fetching Ref code
        url: 'accountsFile/cashtally/expense/geBexpenseRefCode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_bexp').val(response);
        }
    })

    $.ajax({//fetching expense modal details like username, type and categories
        url: 'accountsFile/cashtally/expense/getHexpenseModal.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#cat_bexp').empty();
            $('#cat_bexp').append("<option value=''>Select Category</option>");
            for (var i = 0; i < response.length; i++) {
                $('#cat_bexp').append("<option value='" + response[i]['cat_id'] + "'>" + response[i]['cat_name'] + "</option>")
            }
            $('#user_id_bexp').val(response[0]['user_id'])
            $('#username_bexp').val(response[0]['user_name'])
            $('#usertype_bexp').val(response[0]['user_type'])

            {
                var firstOption = $("#cat_bexp option:first-child");
                $("#cat_bexp").html($("#cat_bexp option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#cat_bexp").prepend(firstOption);
            }
        }
    }).then(function () {
        $('#submit_bexp').click(function () {
            if (bexpenseValidation() == 0) {

                var user_id = $('#user_id_bexp').val(); var username = $('#username_bexp').val(); var usertype = $('#usertype_bexp').val(); var ref_code = $('#ref_code_bexp').val(); var cat_bexp = $('#cat_bexp').val();
                var bank_id = $('#bank_id_bexp').val(); var part_bexp = $('#part_bexp').val(); var vou_id_bexp = $('#vou_id_bexp').val(); var trans_id_bexp = $('#trans_id_bexp').val(); var rec_per_bexp = $('#rec_per_bexp').val(); var remark_bexp = $('#remark_bexp').val();
                var amt_bexp = $('#amt_bexp').val(); var upd_bexp = $('#upd_bexp')[0].files[0]; var op_date = $('#op_date').text();

                var upload = $("#upd_bexp")[0];
                var file = upload.files[0];

                var formData = new FormData();
                formData.append('upd', file);
                formData.append('user_id', user_id);
                formData.append('username', username);
                formData.append('usertype', usertype);
                formData.append('bank_id', bank_id);
                formData.append('ref_code', ref_code);
                formData.append('cat', cat_bexp);
                formData.append('part', part_bexp);
                formData.append('vou_id', vou_id_bexp);
                formData.append('trans_id', trans_id_bexp);
                formData.append('rec_per', rec_per_bexp);
                formData.append('remark', remark_bexp);
                formData.append('amt', amt_bexp);
                formData.append('op_date', op_date);


                $.ajax({
                    url: 'accountsFile/cashtally/expense/submitBexpenseModal.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (response) {
                        if (response.includes('Successfully')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        } else if (response.includes('Error')) {
                            Swal.fire({
                                title: response,
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            });
                        }
                        getBexpenseTable();
                        $('#closebexpModal').trigger('click');
                        getClosingBalance();
                    }
                })
            }
        })


    })
}

// Validation for bank expenses
function bexpenseValidation() {
    var cat_bexp = $('#cat_bexp').val(); var part_bexp = $('#part_bexp').val(); var trans_id_bexp = $('#trans_id_bexp').val(); var vou_id_bexp = $('#vou_id_bexp').val(); var rec_per_bexp = $('#rec_per_bexp').val();
    var remark_bexp = $('#remark_bexp').val(); var amt_bexp = $('#amt_bexp').val();
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

    validateField(cat_bexp, '#cat_bexpCheck');
    validateField(part_bexp, '#part_bexpCheck');
    validateField(vou_id_bexp, '#vou_id_bexpCheck');
    validateField(trans_id_bexp, '#trans_id_bexpCheck');
    validateField(rec_per_bexp, '#rec_per_bexpCheck');
    validateField(remark_bexp, '#remark_bexpCheck');
    validateField(amt_bexp, '#amt_bexpCheck');
    return response;
}

// //////////////////////////////////////////////////// Expenses End //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// Investment Start //////////////////////////////////////////////////////// //

//to get input details of Hand invest card Credit
function getCHinvDetails() {
    var appendText = `<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_hinv">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_hinv" name="name_hinv" class="form-control"></select>
            <span id='name_hinvCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('inv')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_hinv">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_hinv" name="area_hinv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_hinv">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_hinv" name="ident_hinv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_hinv">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_hinv" name="remark_hinv" class="form-control" placeholder="Enter Remark">
            <span id='remark_hinvCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_hinv">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_hinv" name="amt_hinv" class="form-control" placeholder="Enter Amount">
            <span id='amt_hinvCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hinv" name="submit_hinv" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Investment');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('inv');// to get dropdown details of Name filed

    $('#submit_hinv').click(function () {
        if (hinvvalidation('cr') == 0) {
            var name = $('#name_hinv').val(); var area = $('#area_hinv').val(); var ident = $('#ident_hinv').val(); var remark = $('#remark_hinv').val(); var amt = $('#amt_hinv').val();
            var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/investment/submitCHinvestment.php',
                data: { 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCHinvDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//to get input details of investment card Debit
function getDHinvDetails() {
    var appendText = `<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_hinv">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_hinv" name="name_hinv" class="form-control"></select>
            <span id='name_hinvCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('inv')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_hinv">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_hinv" name="area_hinv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_hinv">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_hinv" name="ident_hinv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_hinv">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_hinv" name="remark_hinv" class="form-control" placeholder="Enter Remark">
            <span id='remark_hinvCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_hinv">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_hinv" name="amt_hinv" class="form-control" placeholder="Enter Amount" >
            <span id='amt_hinvCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hinv" name="submit_hinv" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Investment');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('inv');// to get dropdown details of Name filed

    $('#submit_hinv').click(function () {
        if (hinvvalidation('db') == 0) {
            var name = $('#name_hinv').val(); var area = $('#area_hinv').val(); var ident = $('#ident_hinv').val(); var remark = $('#remark_hinv').val(); var amt = $('#amt_hinv').val();
            var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/investment/submitDHinvestment.php',
                data: { 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDHinvDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//validation for hand investment Credit //Same validation can be used for Cr/Db due to same inputs
function hinvvalidation(type) {
    var name = $('#name_hinv').val(); var remark = $('#remark_hinv').val(); var amt = $('#amt_hinv').val(); var response = 0;
    if (name == '') { event.preventDefault(); $('#name_hinvCheck').show(); response = 1; } else { $('#name_hinvCheck').hide(); }
    if (remark == '') { event.preventDefault(); $('#remark_hinvCheck').show(); response = 1; } else { $('#remark_hinvCheck').hide(); }
    if (amt == '') { event.preventDefault(); $('#amt_hinvCheck').show(); response = 1; } else { $('#amt_hinvCheck').hide(); if (type == 'db' && name != '') { var validateResponse = validateNamedHandCash(name, amt, 'amt_hinv', 'inv') 
        setTimeout(() => {
            response = validateResponse;
        }, 1000);    
    } }

    return response;
}



//to get input details for bank investment card Credit
function getCBinvDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ref_code_binv">Ref ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ref_code_binv" name="ref_code_binv" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_binv">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_binv" name="name_binv" class="form-control"></select>
            <span id='name_binvCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('inv')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_binv">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_binv" name="area_binv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_binv">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_binv" name="ident_binv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="trans_id_binv">Transaction ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="trans_id_binv" name="trans_id_binv" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_id_binvCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_binv">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_binv" name="remark_binv" class="form-control" placeholder="Enter Remark">
            <span id='remark_binvCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_binv">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_binv" name="amt_binv" class="form-control" placeholder="Enter Amount">
            <span id='amt_binvCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_binv" name="submit_binv" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Investment');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('inv');// to get dropdown details of Name filed
    getBinvestRefcode();//to get the reference code for bank investment

    $('#submit_binv').click(function () {
        if (binvvalidation() == 0) {
            var ref_code = $('#ref_code_binv').val(); var name = $('#name_binv').val(); var area = $('#area_binv').val(); var ident = $('#ident_binv').val();
            var trans_id = $('#trans_id_binv').val(); var remark = $('#remark_binv').val(); var amt = $('#amt_binv').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();

            $.ajax({
                url: 'accountsFile/cashtally/investment/submitCBinvestment.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'trans_id': trans_id, 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCBinvDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//to get input details for bank investment card Debit
function getDBinvDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ref_code_binv">Ref ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ref_code_binv" name="ref_code_binv" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_binv">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_binv" name="name_binv" class="form-control"></select>
            <span id='name_binvCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('inv')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_binv">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_binv" name="area_binv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_binv">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_binv" name="ident_binv" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="trans_id_binv">Transaction ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="trans_id_binv" name="trans_id_binv" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_id_binvCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_binv">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_binv" name="remark_binv" class="form-control" placeholder="Enter Remark">
            <span id='remark_binvCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_binv">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_binv" name="amt_binv" class="form-control" placeholder="Enter Amount">
            <span id='amt_binvCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_binv" name="submit_binv" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Investment');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('inv');// to get dropdown details of Name filed
    getBinvestRefcode();// to get ref code

    $('#submit_binv').click(function () {
        if (binvvalidation() == 0) {
            var ref_code = $('#ref_code_binv').val(); var name = $('#name_binv').val(); var area = $('#area_binv').val(); var ident = $('#ident_binv').val();
            var trans_id = $('#trans_id_binv').val(); var remark = $('#remark_binv').val(); var amt = $('#amt_binv').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();

            $.ajax({
                url: 'accountsFile/cashtally/investment/submitDBinvestment.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'trans_id': trans_id, 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDBinvDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//validation for Bank investment //Same validation can be used for Cr/Db due to same inputs
function binvvalidation() {
    var name = $('#name_binv').val(); var trans_id = $('#trans_id_binv').val(); var remark = $('#remark_binv').val(); var amt = $('#amt_binv').val(); var response = 0;
    if (name == '') { event.preventDefault(); $('#name_binvCheck').show(); response = 1; } else { $('#name_binvCheck').hide(); }
    if (trans_id == '') { event.preventDefault(); $('#trans_id_binvCheck').show(); response = 1; } else { $('#trans_id_binvCheck').hide(); }
    if (remark == '') { event.preventDefault(); $('#remark_binvCheck').show(); response = 1; } else { $('#remark_binvCheck').hide(); }
    if (amt == '') { event.preventDefault(); $('#amt_binvCheck').show(); response = 1; } else { $('#amt_binvCheck').hide(); }
    return response;
}

// to get ref code for bank inestment
function getBinvestRefcode() {
    $.ajax({
        url: 'accountsFile/cashtally/investment/getBinvestRefcode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_binv').val(response);
        }
    })
}

// //////////////////////////////////////////////////// Investment End //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// Deposit Start //////////////////////////////////////////////////////// //

//to get input details of deposit card Credit
function getCHdepDetails() {
    var appendText = `<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_hdep">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_hdep" name="name_hdep" class="form-control"></select>
            <span id='name_hdepCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('dep')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_hdep">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_hdep" name="area_hdep" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_hdep">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_hdep" name="ident_hdep" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_hdep">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_hdep" name="remark_hdep" class="form-control" placeholder="Enter Remark">
            <span id='remark_hdepCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_hdep">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_hdep" name="amt_hdep" class="form-control" placeholder="Enter Amount">
            <span id='amt_hdepCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hdep" name="submit_hdep" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Deposit');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('dep');// to get dropdown details of Name filed

    $('#submit_hdep').click(function () {
        if (hdepvalidation('cr') == 0) {
            var name = $('#name_hdep').val(); var area = $('#area_hdep').val(); var ident = $('#ident_hdep').val(); var remark = $('#remark_hdep').val(); var amt = $('#amt_hdep').val();
            var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/deposit/submitCHdeposit.php',
                data: { 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCHdepDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}
//to get input details of deposit card Debit
function getDHdepDetails() {
    var appendText = `<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_hdep">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_hdep" name="name_hdep" class="form-control"></select>
            <span id='name_hdepCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('dep')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_hdep">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_hdep" name="area_hdep" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_hdep">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_hdep" name="ident_hdep" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_hdep">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_hdep" name="remark_hdep" class="form-control" placeholder="Enter Remark">
            <span id='remark_hdepCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_hdep">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_hdep" name="amt_hdep" class="form-control" placeholder="Enter Amount">
            <span id='amt_hdepCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hdep" name="submit_hdep" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Deposit');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('dep');// to get dropdown details of Name filed

    $('#submit_hdep').click(function () {
        if (hdepvalidation('db') == 0) {
            var name = $('#name_hdep').val(); var area = $('#area_hdep').val(); var ident = $('#ident_hdep').val(); var remark = $('#remark_hdep').val(); var amt = $('#amt_hdep').val();
            var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/deposit/submitDHdeposit.php',
                data: { 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDHdepDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//validation for hand Deposit Credit //Same validation can be used for Cr/Db due to same inputs
function hdepvalidation(type) {
    var name = $('#name_hdep').val(); var remark = $('#remark_hdep').val(); var amt = $('#amt_hdep').val(); var response = 0;
    if (name == '') { event.preventDefault(); $('#name_hdepCheck').show(); response = 1; } else { $('#name_hdepCheck').hide(); }
    if (remark == '') { event.preventDefault(); $('#remark_hdepCheck').show(); response = 1; } else { $('#remark_hdepCheck').hide(); }
    if (amt == '') { event.preventDefault(); $('#amt_hdepCheck').show(); response = 1; } else { $('#amt_hdepCheck').hide(); if (type == 'db' && name != '') { var validateResponse = validateNamedHandCash(name, amt, 'amt_hdep', 'dep');
        setTimeout(() => {
            response = validateResponse;
        }, 1000);
     } }
    return response;
}

//to get input details for bank Deposit card Credit
function getCBDepDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ref_code_bdeposit">Ref ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ref_code_bdeposit" name="ref_code_bdeposit" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_bdeposit">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_bdeposit" name="name_bdeposit" class="form-control"></select>
            <span id='name_bdepositCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('dep')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_bdeposit">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_bdeposit" name="area_bdeposit" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_bdeposit">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_bdeposit" name="ident_bdeposit" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="trans_id_bdeposit">Transaction ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="trans_id_bdeposit" name="trans_id_bdeposit" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_id_bdepositCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_bdeposit">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_bdeposit" name="remark_bdeposit" class="form-control" placeholder="Enter Remark">
            <span id='remark_bdepositCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_bdeposit">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_bdeposit" name="amt_bdeposit" class="form-control" placeholder="Enter Amount">
            <span id='amt_bdepositCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_bdeposit" name="submit_bdeposit" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Deposit');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('dep');// to get dropdown details of Name filed
    getBdepositRefcode();//to get the reference code for bank Deposit

    $('#submit_bdeposit').click(function () {
        if (bdepositvalidation() == 0) {
            var ref_code = $('#ref_code_bdeposit').val(); var name = $('#name_bdeposit').val(); var area = $('#area_bdeposit').val(); var ident = $('#ident_bdeposit').val();
            var trans_id = $('#trans_id_bdeposit').val(); var remark = $('#remark_bdeposit').val(); var amt = $('#amt_bdeposit').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();

            $.ajax({
                url: 'accountsFile/cashtally/deposit/submitCBdeposit.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'trans_id': trans_id, 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCBDepDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//to get input details for bank Deposit card Debit
function getDBDepDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ref_code_bdeposit">Ref ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ref_code_bdeposit" name="ref_code_bdeposit" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_bdeposit">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_bdeposit" name="name_bdeposit" class="form-control"></select>
            <span id='name_bdepositCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('dep')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_bdeposit">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_bdeposit" name="area_bdeposit" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_bdeposit">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_bdeposit" name="ident_bdeposit" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="trans_id_bdeposit">Transaction ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="trans_id_bdeposit" name="trans_id_bdeposit" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_id_bdepositCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_bdeposit">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_bdeposit" name="remark_bdeposit" class="form-control" placeholder="Enter Remark">
            <span id='remark_bdepositCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_bdeposit">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_bdeposit" name="amt_bdeposit" class="form-control" placeholder="Enter Amount">
            <span id='amt_bdepositCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_bdeposit" name="submit_bdeposit" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('Deposit');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('dep');// to get dropdown details of Name filed
    getBdepositRefcode();// to get ref code

    $('#submit_bdeposit').click(function () {
        if (bdepositvalidation() == 0) {
            var ref_code = $('#ref_code_bdeposit').val(); var name = $('#name_bdeposit').val(); var area = $('#area_bdeposit').val(); var ident = $('#ident_bdeposit').val();
            var trans_id = $('#trans_id_bdeposit').val(); var remark = $('#remark_bdeposit').val(); var amt = $('#amt_bdeposit').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();

            $.ajax({
                url: 'accountsFile/cashtally/deposit/submitDBdeposit.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'trans_id': trans_id, 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDBDepDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//validation for Bank Deposit //Same validation can be used for Cr/Db due to same inputs
function bdepositvalidation() {
    var name = $('#name_bdeposit').val(); var trans_id = $('#trans_id_bdeposit').val(); var remark = $('#remark_bdeposit').val(); var amt = $('#amt_bdeposit').val(); var response = 0;
    if (name == '') { event.preventDefault(); $('#name_bdepositCheck').show(); response = 1; } else { $('#name_bdepositCheck').hide(); }
    if (trans_id == '') { event.preventDefault(); $('#trans_id_bdepositCheck').show(); response = 1; } else { $('#trans_id_bdepositCheck').hide(); }
    if (remark == '') { event.preventDefault(); $('#remark_bdepositCheck').show(); response = 1; } else { $('#remark_bdepositCheck').hide(); }
    if (amt == '') { event.preventDefault(); $('#amt_bdepositCheck').show(); response = 1; } else { $('#amt_bdepositCheck').hide(); }
    return response;
}

// to get ref code for bank Deposit
function getBdepositRefcode() {
    $.ajax({
        url: 'accountsFile/cashtally/deposit/getBdepositRefcode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_bdeposit').val(response);
        }
    })
}
// //////////////////////////////////////////////////// Deposit End //////////////////////////////////////////////////////// //

// //////////////////////////////////////////////////// EL Start //////////////////////////////////////////////////////// //

//to get input details of EL card Credit
function getCHelDetails() {
    var appendText = `<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_hel">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_hel" name="name_hel" class="form-control"></select>
            <span id='name_helCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('el')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_hel">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_hel" name="area_hel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_hel">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_hel" name="ident_hel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_hel">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_hel" name="remark_hel" class="form-control" placeholder="Enter Remark">
            <span id='remark_helCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_hel">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_hel" name="amt_hel" class="form-control" placeholder="Enter Amount">
            <span id='amt_helCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hel" name="submit_hel" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('EL');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('el');// to get dropdown details of Name filed

    $('#submit_hel').click(function () {
        if (helvalidation() == 0) {
            var name = $('#name_hel').val(); var area = $('#area_hel').val(); var ident = $('#ident_hel').val(); var remark = $('#remark_hel').val(); var amt = $('#amt_hel').val();
            var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/el/submitCHel.php',
                data: { 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCHelDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}
//to get input details of EL card Debit
function getDHelDetails() {
    var appendText = `<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_hel">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_hel" name="name_hel" class="form-control"></select>
            <span id='name_helCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('el')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_hel">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_hel" name="area_hel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_hel">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_hel" name="ident_hel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_hel">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_hel" name="remark_hel" class="form-control" placeholder="Enter Remark">
            <span id='remark_helCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_hel">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_hel" name="amt_hel" class="form-control" placeholder="Enter Amount">
            <span id='amt_helCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_hel" name="submit_hel" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('EL');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('el');// to get dropdown details of Name filed

    $('#submit_hel').click(function () {
        if (helvalidation() == 0) {
            var name = $('#name_hel').val(); var area = $('#area_hel').val(); var ident = $('#ident_hel').val(); var remark = $('#remark_hel').val(); var amt = $('#amt_hel').val();
            var op_date = $('#op_date').text();
            $.ajax({
                url: 'accountsFile/cashtally/el/submitDHel.php',
                data: { 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDHelDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//validation for hand EL Credit //Same validation can be used for Cr/Db due to same inputs
function helvalidation() {
    var name = $('#name_hel').val(); var remark = $('#remark_hel').val(); var amt = $('#amt_hel').val(); var response = 0;
    let cash_type = $('#credit_type').val() != '' ? 'crel' : 'dbel';
    if (name == '') { event.preventDefault(); $('#name_helCheck').show(); response = 1; } else { $('#name_helCheck').hide(); }
    if (remark == '') { event.preventDefault(); $('#remark_helCheck').show(); response = 1; } else { $('#remark_helCheck').hide(); }
    if (amt == '') { event.preventDefault(); $('#amt_helCheck').show(); response = 1; } else { $('#amt_helCheck').hide(); var validateResponse = validateNamedHandCash(name, amt, 'amt_hel', cash_type);
        setInterval(() => {
            response = validateResponse;
        }, 1000);
     }
    return response;
}


//to get input details for bank Deposit card Credit
function getCBelDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ref_code_bel">Ref ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ref_code_bel" name="ref_code_bel" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_bel">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_bel" name="name_bel" class="form-control"></select>
            <span id='name_belCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('el')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_bel">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_bel" name="area_bel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_bel">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_bel" name="ident_bel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="trans_id_bel">Transaction ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="trans_id_bel" name="trans_id_bel" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_id_belCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_bel">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_bel" name="remark_bel" class="form-control" placeholder="Enter Remark">
            <span id='remark_belCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_bel">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_bel" name="amt_bel" class="form-control" placeholder="Enter Amount">
            <span id='amt_belCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_bel" name="submit_bel" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('EL');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('el');// to get dropdown details of Name filed
    getBelRefcode();//to get the reference code for bank Deposit

    $('#submit_bel').click(function () {
        if (belvalidation() == 0) {
            var ref_code = $('#ref_code_bel').val(); var name = $('#name_bel').val(); var area = $('#area_bel').val(); var ident = $('#ident_bel').val();
            var trans_id = $('#trans_id_bel').val(); var remark = $('#remark_bel').val(); var amt = $('#amt_bel').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();

            $.ajax({
                url: 'accountsFile/cashtally/el/submitCBel.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'trans_id': trans_id, 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCBelDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//to get input details for bank Deposit card Debit
function getDBelDetails() {
    var appendText = `
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ref_code_bel">Ref ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ref_code_bel" name="ref_code_bel" class="form-control" readonly>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="name_bel">Name</label><span class="text-danger">&nbsp;*</span>
            <select id="name_bel" name="name_bel" class="form-control"></select>
            <span id='name_belCheck' class="text-danger" style="display:none">Please Select Name</span>
        </div>
    </div>
    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
        <div class="form-group ">
            <label style="visibility:hidden"></label><br>
            <button type="button" class="btn btn-primary" id="add_nameDetails" name="add_nameDetails" data-toggle="modal" data-target=".add_nameDetails" style="padding: 8px 27px;position: relative;top: 4px;" onclick="resetNameDetailTable('el')"><span class="icon-add"></span></button>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="area_bel">Area</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="area_bel" name="area_bel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ident_bel">Identification</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="ident_bel" name="ident_bel" class="form-control" readonly placeholder="Choose Name">
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="trans_id_bel">Transaction ID</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="trans_id_bel" name="trans_id_bel" class="form-control" placeholder="Enter Transaction ID">
            <span id='trans_id_belCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="remark_bel">Remark</label><span class="text-danger">&nbsp;*</span>
            <input type="text" id="remark_bel" name="remark_bel" class="form-control" placeholder="Enter Remark">
            <span id='remark_belCheck' class="text-danger" style="display:none">Please Enter Remark</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="amt_bel">Amount</label><span class="text-danger">&nbsp;*</span>
            <input type="number" id="amt_bel" name="amt_bel" class="form-control" placeholder="Enter Amount">
            <span id='amt_belCheck' class="text-danger" style="display:none">Please Enter Amount</span>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="text-left">
            <label style="visibility:hidden"></label><br>
            <input type="button" id="submit_bel" name="submit_bel" class="btn btn-primary" value="Submit">
        </div>
    </div>`;

    $('#invDiv').addClass('row', !$('#invDiv').hasClass('row'));
    $('.inv_card_header').text('EL');
    $('#invDiv').empty()
    $('#invDiv').html(appendText);

    resetNameDetailDropdown('el');// to get dropdown details of Name filed
    getBelRefcode();// to get ref code

    $('#submit_bel').click(function () {
        if (belvalidation() == 0) {
            var ref_code = $('#ref_code_bel').val(); var name = $('#name_bel').val(); var area = $('#area_bel').val(); var ident = $('#ident_bel').val();
            var trans_id = $('#trans_id_bel').val(); var remark = $('#remark_bel').val(); var amt = $('#amt_bel').val();
            var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();

            $.ajax({
                url: 'accountsFile/cashtally/el/submitDBel.php',
                data: { 'bank_id': bank_id, 'ref_code': ref_code, 'trans_id': trans_id, 'name': name, 'area': area, 'ident': ident, 'remark': remark, 'amt': amt, 'op_date': op_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDBelDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//validation for Bank EL //Same validation can be used for Cr/Db due to same inputs
function belvalidation() {
    var name = $('#name_bel').val(); var trans_id = $('#trans_id_bel').val(); var remark = $('#remark_bel').val(); var amt = $('#amt_bel').val(); var response = 0;
    if (name == '') { event.preventDefault(); $('#name_belCheck').show(); response = 1; } else { $('#name_belCheck').hide(); }
    if (trans_id == '') { event.preventDefault(); $('#trans_id_belCheck').show(); response = 1; } else { $('#trans_id_belCheck').hide(); }
    if (remark == '') { event.preventDefault(); $('#remark_belCheck').show(); response = 1; } else { $('#remark_belCheck').hide(); }
    if (amt == '') { event.preventDefault(); $('#amt_belCheck').show(); response = 1; } else { $('#amt_belCheck').hide(); }
    return response;
}

// to get ref code for bank EL
function getBelRefcode() {
    $.ajax({
        url: 'accountsFile/cashtally/el/getBelRefcode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_bel').val(response);
        }
    })
}

// //////////////////////////////////////////////////// EL End //////////////////////////////////////////////////////// //


// //////////////////////////////////////////////////// Name Detail Adding Modal Start //////////////////////////////////////////////////////// //

// Modal Box for Name Detail
{
    $("#name_Check").hide(); $("#area_Check").hide(); $("#ident_Check").hide();
    $(document).on("click", "#submitNameDetailModal", function () {
        var name_id = $("#name_id").val();
        var name_ = $("#name_").val();
        var area_ = $("#area_").val();
        var ident_ = $("#ident_").val();
        var opt_for = $("#opt_for").val();
        if (name_ != "" && area_ != '' && ident_ != '') {
            $.ajax({
                url: 'accountsFile/cashtally/nameDetailModal/ajaxInsertNameDetail.php',
                type: 'POST',
                data: { "name": name_, "name_id": name_id, "area": area_, "ident": ident_, "opt_for": opt_for },
                cache: false,
                success: function (response) {
                    var insresult = response.includes("Exists");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $('#nameInsertNotOk').show();
                        setTimeout(function () {
                            $('#nameInsertNotOk').fadeOut('fast');
                        }, 2000);
                    } else if (updresult) {
                        $('#nameUpdateOk').show();
                        setTimeout(function () {
                            $('#nameUpdateOk').fadeOut('fast');
                        }, 2000);
                        $("#coursecategoryTable").remove();
                        resetNameDetailTable(opt_for);
                        $("#name_").val('');
                        $("#area_").val('');
                        $("#ident_").val('');
                        $("#name_id").val('');
                    }
                    else {
                        $('#nameInsertOk').show();
                        setTimeout(function () {
                            $('#nameInsertOk').fadeOut('fast');
                        }, 2000);
                        $("#coursecategoryTable").remove();
                        resetNameDetailTable(opt_for);
                        $("#name_").val('');
                        $("#area_").val('');
                        $("#ident_").val('');
                        $("#name_id").val('');
                    }
                }
            });
        }
        else {
            $("#name_Check").show();
            $("#area_Check").show();
            $("#ident_Check").show();
        }
    });

    function resetNameDetailDropdown(opt_for) {
        $("#opt_for").val(opt_for);
        $.ajax({
            url: 'accountsFile/cashtally/nameDetailModal/resetNameDetailDropdown.php',
            data: { opt_for },
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function (response) {
                $("#name_hinv,#name_binv,#name_hdep,#name_bdeposit,#name_hel,#name_bel").empty().append("<option value=''>Select Name</option>");

                $.each(response, function (index, item) {
                    $("#name_hinv, #name_binv, #name_hdep, #name_bdeposit,#name_hel,#name_bel").append("<option value='" + item['name_id'] + "'>" + item['name'] + "</option>");
                });

                $('#name_hinv, #name_binv, #name_hdep, #name_bdeposit,#name_hel,#name_bel').change(function () {
                    var name = $(this).val();
                    var areaId = $(this).attr("id").replace("name_", "area_");
                    var identId = $(this).attr("id").replace("name_", "ident_");

                    if (name != '') {
                        var selectedResponse = response.find(function (item) {
                            return item['name_id'] == name;
                        });

                        if (selectedResponse) {
                            $("#" + areaId).val(selectedResponse['area']);
                            $("#" + identId).val(selectedResponse['ident']);
                        }
                    } else {
                        $("#" + areaId).val('');
                        $("#" + identId).val('');
                    }
                });

            }
        })
        $('#name_hinv,#area_hinv,#ident_hinv,#remark_hinv,#amt_hinv').val('');
        $('#name_binv,#area_binv,#ident_binv,#remark_binv,#amt_binv').val('');
        $('#name_hdep,#area_hdep,#ident_hdep,#remark_hdep,#amt_hdep').val('');
        $('#name_bdeposit,#area_bdeposit,#ident_bdeposit,#remark_bdeposit,#amt_bdeposit').val('');
        $('#name_hel,#area_hel,#ident_hel,#remark_hel,#amt_hel').val('');
        $('#name_bel,#area_bel,#ident_bel,#remark_bel,#amt_bel').val('');
        $("#name_Check,#area_Check,#ident_Check").hide();
    }

    function resetNameDetailTable(opt_for) {
        $.ajax({
            url: 'accountsFile/cashtally/nameDetailModal/ajaxResetNameDetailTable.php',
            type: 'POST',
            data: { opt_for },
            cache: false,
            success: function (html) {
                $("#updateNameDetailDiv").empty();
                $("#updateNameDetailDiv").html(html);
            }
        })
        $("#name_id").val(''); $('#name_').val(''); $('#area_').val(''); $('#ident_').val('')
        $("#name_Check").hide(); $("#area_Check").hide(); $("#ident_Check").hide();
    }

    $("#name_, #area_, #ident_").on('keyup', function () {
        var CTval = $(this).val();
        if (CTval.length == '') {
            $("#" + this.id + "_Check").show();
            return false;
        } else {
            $("#" + this.id + "_Check").hide();
        }
    });


    $("body").on("click", "#edit_name", function () {
        var name_id = $(this).attr('value');
        $("#name_id").val(name_id);
        $.ajax({
            url: 'accountsFile/cashtally/nameDetailModal/ajaxEditNameDetail.php',
            data: { "name_id": name_id },
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function (response) {
                $("#name_").val(response['name']);
                $("#area_").val(response['area']);
                $("#ident_").val(response['ident']);
            }
        });
    });

    $("body").on("click", "#delete_name", function () {
        if (!confirm("Do you want delete Name Details?")) {
            return false;
        } else {
            var name_id = $(this).attr('value');
            var c_obj = $(this).parents("tr");
            $.ajax({
                url: 'accountsFile/cashtally/nameDetailModal/ajaxDeleteNameDetail.php',
                data: { "name_id": name_id },
                type: 'POST',
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Rights");
                    if (delresult) {
                        $('#nameDeleteNotOk').show();
                        setTimeout(function () {
                            $('#nameDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        c_obj.remove();
                        $('#nameDeleteOk').show();
                        setTimeout(function () {
                            $('#nameDeleteOk').fadeOut('fast');
                        }, 2000);
                    }
                }
            });
        }
    });
}
// //////////////////////////////////////////////////// Name Detail Adding Modal End //////////////////////////////////////////////////////// //



/////////////////////////////////////////////////////// Excess Fund Start ///////////////////////////////////////////////////////////////////////


//Bank expense modal btn click and submit btn click events
function getExfDetails() {
    var appendTxt = `<div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="usertype_exf">User Type</label>
                            <input type='text' id="usertype_exf" name="usertype_exf" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="username_exf">User Name</label>
                            <input type='hidden' id="user_id_exf" name="user_id_exf">
                            <input type='text' id="username_exf" name="username_exf" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="ucl_ref_code_exf">Unclear Ref ID</label>
                            <input type='text' id="ucl_ref_code_exf" name="ucl_ref_code_exf" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="ref_code_exf">Ref ID</label>
                            <input type='text' id="ref_code_exf" name="ref_code_exf" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="ucl_trans_id_exf">Unclear Transaction ID</label><span class='text-danger'>&nbsp;*</span>
                            <select id="ucl_trans_id_exf" name="ucl_trans_id_exf" class="form-control" >
                            <option value=''>Select Transaction ID</option></select>
                            <span id='ucl_trans_id_exfCheck' class="text-danger" style="display:none">Please Select Category</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="trans_id_exf">Transaction ID</label><span class='text-danger'>&nbsp;*</span>
                            <input type="text" id="trans_id_exf" name="trans_id_exf" class="form-control" placeholder="Enter Transaction ID">
                            <span id='trans_id_exfCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="remark_exf">Remark</label><span class='text-danger'>&nbsp;*</span>
                            <input type="text" id="remark_exf" name="remark_exf" class="form-control" placeholder="Enter Remark">
                            <span id='remark_exfCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                        <div class="form-group">
                            <label for="amt_exf">Amount</label><span class='text-danger'>&nbsp;*</span>
                            <input type="number" id="amt_exf" name="amt_exf" class="form-control" placeholder="Enter Amount">
                            <span id='amt_exfCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="text-left">
                            <label style="visibility:hidden"></label><br>
                            <input type="button" id="submit_exf" name="submit_exf" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </div>
            </div>
            </div>`;

    $('#exfDiv').empty();
    $('#exfDiv').html(appendTxt);
    var bank_id = $('input[name=cash_type]:checked').val();


    $.ajax({//For fetching Ref code
        url: 'accountsFile/cashtally/excessfund/getExfRefCode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_exf').val(response);
        }
    })
    $.ajax({//For fetching Unclear Ref code
        url: 'accountsFile/cashtally/excessfund/getExfUclRefCode.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#user_id_exf').val(response['user_id']);
            $('#username_exf').val(response['user_name']);
            $('#usertype_exf').val(response['user_type']);
            $('#ucl_ref_code_exf').val(response['ref_code']);
        }
    })

    $.ajax({ // to get the uncleared transacton id on this user's bank accounts
        url: 'accountsFile/cashtally/excessfund/getUnclearTransactionID.php',
        data: { 'bank_id': bank_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ucl_trans_id_exf').empty();
            $('#ucl_trans_id_exf').append("<option value=''>Select Unclear Transaction ID</option>");
            $.each(response, function (ind, val) {
                $('#ucl_trans_id_exf').append("<option value='" + val['ucl_trans_id'] + "'>" + val['ucl_trans_id'] + "</option>")
            })
        }
    })

    $('#submit_exf').click(function () {
        if (exfValidation() == 0) {
            var ucl_ref_code_exf = $('#ucl_ref_code_exf').val(); var ref_code_exf = $('#ref_code_exf').val(); var ucl_trans_id_exf = $('#ucl_trans_id_exf').val();
            var trans_id_exf = $('#trans_id_exf').val(); var remark_exf = $('#remark_exf').val(); var amt_exf = $('#amt_exf').val();
            var username_exf = $('#username_exf').val(); var usertype_exf = $('#usertype_exf').val(); var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();
            var formtosend = { bank_id: bank_id, username_exf: username_exf, usertype_exf: usertype_exf, ucl_ref_code_exf: ucl_ref_code_exf, ref_code_exf: ref_code_exf, ucl_trans_id_exf: ucl_trans_id_exf, trans_id_exf: trans_id_exf, remark_exf: remark_exf, amt_exf: amt_exf, op_date: op_date };
            $.ajax({
                url: 'accountsFile/cashtally/excessfund/submitExf.php',
                data: formtosend,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getExfDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })

}

// Validation for Bank Excess Fund
function exfValidation() {
    var ucl_trans_id = $('#ucl_trans_id_exf').val(); var trans_id = $('#trans_id_exf').val(); var remark = $('#remark_exf').val(); var amt = $('#amt_exf').val();
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

    validateField(ucl_trans_id, '#ucl_trans_id_exfCheck');
    validateField(trans_id, '#trans_id_exfCheck');
    validateField(remark, '#remark_exfCheck');
    validateField(amt, '#amt_exfCheck');
    return response;
}


/////////////////////////////////////////////////////// Excess Fund End ///////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////// Agent Start ///////////////////////////////////////////////////////////////////////

//to get agent Hand Credit input details
function getCHagDetails() {
    var appendTxt = `
    <div class="col-12">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="ag_id">Agent Name</label><span class='text-danger'>&nbsp;*</span>
                    <select id="ag_id" name="ag_id" class="form-control" ></select>
                    <span id='ag_idCheck' class="text-danger" style="display:none">Please Enter Agent Name</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="remark_ag">Remark</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="remark_ag" name="remark_ag" class="form-control" placeholder="Enter Remark">
                    <span id='remark_agCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="amt_ag">Amount</label><span class='text-danger'>&nbsp;*</span>
                    <input type="number" id="amt_ag" name="amt_ag" class="form-control" placeholder="Enter Amount">
                    <span id='amt_agCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="text-left">
                    <label style="visibility:hidden"></label><br>
                    <input type="button" id="submit_ag" name="submit_ag" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </div>
    </div>`;

    $('#agDiv').empty();
    $('#agDiv').html(appendTxt);

    getAgentName('ag_id');

    $('#submit_ag').off('click');
    $('#submit_ag').click(function () {
        if (agValidation() == 0) {
            var ag_id = $('#ag_id').val(); var remark_ag = $('#remark_ag').val(); var amt_ag = $('#amt_ag').val(); var op_date = $('#op_date').text();
            var formtosend = { ag_id: ag_id, remark: remark_ag, amt: amt_ag, op_date: op_date };
            $.ajax({
                url: 'accountsFile/cashtally/agent/submitCHag.php',
                data: formtosend,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCHagDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//to get agent Hand Debit input details
function getDHagDetails() {
    var appendTxt = `
    <div class="col-md-12">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="ag_id">Agent Name</label><span class='text-danger'>&nbsp;*</span>
                    <select id="ag_id" name="ag_id" class="form-control" ></select>
                    <span id='ag_idCheck' class="text-danger" style="display:none">Please Enter Agent Name</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="remark_ag">Remark</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="remark_ag" name="remark_ag" class="form-control" placeholder="Enter Remark">
                    <span id='remark_agCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="amt_ag">Amount</label><span class='text-danger'>&nbsp;*</span>
                    <input type="number" id="amt_ag" name="amt_ag" class="form-control" placeholder="Enter Amount" onkeydown="validateHandCash(this)">
                    <span id='amt_agCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="text-left">
                    <label style="visibility:hidden"></label><br>
                    <input type="button" id="submit_ag" name="submit_ag" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </div>
    </div>`;

    $('#agDiv').empty();
    $('#agDiv').html(appendTxt);

    getAgentName('ag_id');


    $('#submit_ag').off('click');
    $('#submit_ag').click(function () {
        if (agValidation() == 0) {
            var ag_id = $('#ag_id').val(); var remark_ag = $('#remark_ag').val(); var amt_ag = $('#amt_ag').val(); var op_date = $('#op_date').text();
            var formtosend = { ag_id: ag_id, remark: remark_ag, amt: amt_ag, op_date: op_date };
            $.ajax({
                url: 'accountsFile/cashtally/agent/submitDHag.php',
                data: formtosend,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDHagDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

// Validation for Hand Agent Credit
function agValidation() {// same validation for both credit and debit
    var ag_id = $('#ag_id').val(); var remark_ag = $('#remark_ag').val(); var amt_ag = $('#amt_ag').val();
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

    validateField(ag_id, '#ag_idCheck');
    validateField(remark_ag, '#remark_agCheck');
    validateField(amt_ag, '#amt_agCheck');
    return response;
}


//to get agent Bank Credit input details
function getCBagDetails() {
    var appendTxt = `
    <div class="col-md-12">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="ag_id">Agent Name</label><span class='text-danger'>&nbsp;*</span>
                    <select id="ag_id" name="ag_id" class="form-control" ></select>
                    <span id='ag_idCheck' class="text-danger" style="display:none">Please Enter Agent Name</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="ref_code_ag">Ref ID</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="ref_code_ag" name="ref_code_ag" class="form-control" readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="trans_id_ag">Transaction ID</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="trans_id_ag" name="trans_id_ag" class="form-control" placeholder="Enter Transaction ID">
                    <span id='trans_id_agCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="remark_ag">Remark</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="remark_ag" name="remark_ag" class="form-control" placeholder="Enter Remark">
                    <span id='remark_agCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="amt_ag">Amount</label><span class='text-danger'>&nbsp;*</span>
                    <input type="number" id="amt_ag" name="amt_ag" class="form-control" placeholder="Enter Amount">
                    <span id='amt_agCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="text-left">
                    <label style="visibility:hidden"></label><br>
                    <input type="button" id="submit_ag" name="submit_ag" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </div>
    </div>`;

    $('#agDiv').empty();
    $('#agDiv').html(appendTxt);

    getAgentName('ag_id');

    $.ajax({//For fetching Ref code
        url: 'accountsFile/cashtally/agent/getagRefCode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_ag').val(response);
        }
    })

    $('#submit_ag').off('click');
    $('#submit_ag').click(function () {
        if (agBValidation() == 0) {
            var ag_id = $('#ag_id').val(); var ref_code_ag = $('#ref_code_ag').val(); var trans_id_ag = $('#trans_id_ag').val(); var remark_ag = $('#remark_ag').val(); var amt_ag = $('#amt_ag').val(); var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();
            var formtosend = { ag_id: ag_id, bank_id: bank_id, ref_code: ref_code_ag, trans_id: trans_id_ag, remark: remark_ag, amt: amt_ag, op_date: op_date };
            $.ajax({
                url: 'accountsFile/cashtally/agent/submitCBag.php',
                data: formtosend,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getCBagDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//to get agent input details
function getDBagDetails() {
    var appendTxt = `
    <div class="col-md-12">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="ag_id">Agent Name</label><span class='text-danger'>&nbsp;*</span>
                    <select id="ag_id" name="ag_id" class="form-control" ></select>
                    <span id='ag_idCheck' class="text-danger" style="display:none">Please Enter Agent Name</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="ref_code_ag">Ref ID</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="ref_code_ag" name="ref_code_ag" class="form-control" readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="trans_id_ag">Transaction ID</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="trans_id_ag" name="trans_id_ag" class="form-control" placeholder="Enter Transaction ID">
                    <span id='trans_id_agCheck' class="text-danger" style="display:none">Please Enter Transaction ID</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="remark_ag">Remark</label><span class='text-danger'>&nbsp;*</span>
                    <input type="text" id="remark_ag" name="remark_ag" class="form-control" placeholder="Enter Remark">
                    <span id='remark_agCheck' class="text-danger" style="display:none">Please Enter Remark</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
                <div class="form-group">
                    <label for="amt_ag">Amount</label><span class='text-danger'>&nbsp;*</span>
                    <input type="number" id="amt_ag" name="amt_ag" class="form-control" placeholder="Enter Amount">
                    <span id='amt_agCheck' class="text-danger" style="display:none">Please Enter Amount</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="text-left">
                    <label style="visibility:hidden"></label><br>
                    <input type="button" id="submit_ag" name="submit_ag" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </div>
    </div>`;

    $('#agDiv').empty();
    $('#agDiv').html(appendTxt);

    getAgentName('ag_id');


    $.ajax({//For fetching Ref code
        url: 'accountsFile/cashtally/agent/getagRefCode.php',
        data: {},
        type: 'post',
        cache: false,
        success: function (response) {
            $('#ref_code_ag').val(response);
        }
    })


    $('#submit_ag').click(function () {
        if (agBValidation() == 0) {
            var ag_id = $('#ag_id').val(); var ref_code_ag = $('#ref_code_ag').val(); var trans_id_ag = $('#trans_id_ag').val(); var remark_ag = $('#remark_ag').val(); var amt_ag = $('#amt_ag').val(); var bank_id = $('input[name=cash_type]:checked').val(); var op_date = $('#op_date').text();
            var formtosend = { ag_id: ag_id, bank_id: bank_id, ref_code: ref_code_ag, trans_id: trans_id_ag, remark: remark_ag, amt: amt_ag, op_date: op_date };
            $.ajax({
                url: 'accountsFile/cashtally/agent/submitDBag.php',
                data: formtosend,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDBagDetails();
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                    getClosingBalance();
                }
            })
        }
    })
}

//this function will check the amount entered were lesser or equal to hand closing balance
function validateHandCash(amt) {
    let hand_cl = $('#hand_closing').text()
    if (parseInt(hand_cl) <= parseInt(amt.value)) {
        alert('Enter Lesser Amount !');
        $(amt).val('');
        return false;
    } else {
        return true;
    }
}

//Validate credit and debit based on the names
function validateNamedHandCash(name, amt, source, cash_type) {
    var retval = 0;
    $.ajax({
        url: 'accountsFile/cashtally/validateNamedHandCash.php',
        data: { name, amt, cash_type },
        type: 'post',
        dataType: 'JSON',
        cache: false,
        success: function (response) {
            if (cash_type != 'crel' && cash_type != 'dbel') {
                if (response['info'] != 1) {
                    event.preventDefault();
                    alert('Enter Smaller value !');
                    $(`#${source}`).val('')
                    retval = 1;
                }
            } else {
                if (cash_type == 'crel' && response['creditable'] > 0 && response['creditable'] < amt) {
                    event.preventDefault();
                    alert('Enter value between 1 and ' + response['creditable'])
                    $(`#${source}`).val('')
                    retval = 1;
                } else if (cash_type == 'dbel' && response['debitable'] > 0 && response['debitable'] < amt) {
                    event.preventDefault();
                    alert('Enter value between 1 and ' + response['debitable'])
                    $(`#${source}`).val('')
                    retval = 1;
                }
            }
        }
    }).then(() => {
        return retval;
    })
}

// Validation for Bank Agent 
function agBValidation() {// same validation for both credit and debit
    var ag_id = $('#ag_id').val(); var remark_ag = $('#remark_ag').val(); var amt_ag = $('#amt_ag').val(); var trans_id_ag = $('#trans_id_ag').val();
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

    validateField(ag_id, '#ag_idCheck');
    validateField(trans_id_ag, '#trans_id_agCheck');
    validateField(remark_ag, '#remark_agCheck');
    validateField(amt_ag, '#amt_agCheck');
    return response;
}


/////////////////////////////////////////////////////// Agent End ///////////////////////////////////////////////////////////////////////