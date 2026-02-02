$(document).ready(function () {
    const toggleButtons = $(".toggle-button");
    toggleButtons.removeClass('active'); //initially make all buttons unchecked
    toggleButtons.on("click", function () {
        // Reset active class for all buttons
        toggleButtons.removeClass("active");
        // Add active class to the clicked button
        $(this).addClass("active");

        let chosenOpt = $(this).val();
        if (chosenOpt == 'Today') {

            async function initialize() {
                await BalanceSheetCalculations('today', '', '', '');
                await BenefitCalculations('today', '', '', '');
                await BenefitCheckCalculations('today', '', '', '');
                await ProfitCalculations('today', '', '', '');
                await callCalculateFunctions();
            }

            initialize();
        }
    });

    $('#submitDaywise').click(function () {
        let from_date = $('#from_date').val(); let to_date = $('#to_date').val();
        if (from_date != '' && to_date != '') {
            async function initialize() {
                await BalanceSheetCalculations('day', from_date, to_date, '');
                await BenefitCalculations('day', from_date, to_date, '');
                await BenefitCheckCalculations('day', from_date, to_date, '');
                await ProfitCalculations('day', from_date, to_date, '');
                await callCalculateFunctions();
            }

            initialize();

            $('.close').trigger('click');//it will close modal
        } else {
            swalError('Please Fill Dates!', 'error');
            event.preventDefault();
        }
    })

    $('#submitMonthwise').click(function () {
        let for_month = $('#for_month').val()
        if (for_month != '') {

            async function initialize() {
                await clearAllContents();
                await BalanceSheetCalculations('month', '', '', for_month);
                await BenefitCalculations('month', '', '', for_month);
                await BenefitCheckCalculations('month', '', '', for_month);
                await ProfitCalculations('month', '', '', for_month);
                await callCalculateFunctions();
            }

            initialize();

            $('.close').trigger('click');//it will close modal
        } else {
            swalError('Please Choose Month!', 'error');
            event.preventDefault();
        }
    })

})//Document Ready End

$(function () {
    getUserNames();

})// auto load functions End


function getUserNames() {
    //get user name only who has access of cash tally
    $.post('financeFile/getUsersName.php', function (response) {
        $('#by_user').empty()
        $('#by_user').append("<option value=''>Select User</option>")
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['user_id'] + "'>" + val['username'] + "</option> ");
        })
    }, 'json')
}

//it will calculate for all type of searches handling by type, after ajax calls are completed then it will trigger to calculate closing details
function BalanceSheetCalculations(type, from_date, to_date, month) {
  return new Promise((resolve, reject) => {

        var user_id = $('#by_user').val();
        var bankDetail = $('#bank_detail').val();
        if (type == 'today') { var args = { 'type': 'today', 'user_id': user_id, 'bankDetail': bankDetail }; } else
            if (type == 'day') { var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id, 'bankDetail': bankDetail }; } else
                if (type == 'month') { var args = { 'type': 'month', 'month': month, 'user_id': user_id, 'bankDetail': bankDetail }; }

        // Create an array to store all the Ajax calls
        let ajaxCalls = [];

        //to get opening balance
        let ajaxCall1 = $.post('financeFile/BS/getOpeningDate.php', args, function (response) {
            let opBal = response[1][0]['opening_bal'];
            let opAgBal = response[1][0]['opening_agent'];
            $('.balance-sheet-card').find('tbody tr:first td:nth-child(2)').text(opBal) 
            $('.balance-sheet-card').find('tbody tr:nth-child(2) td:nth-child(2)').text(opAgBal) 
            $('.benefits-check-card').find('tbody tr:nth-child(2) td:nth-child(2)').text(opBal) //it will get the 2nd column value inside tbody // will take you to opening balance credit column
            $('.benefits-check-card').find('tbody tr:nth-child(3) td:nth-child(2)').text(opAgBal) 
            let clBal = response[0][0]?.closing_balance || 0;
            let agBal = response[0][0]?.agent_closing || 0;
            $('.balance-sheet-card').find('tbody tr:nth-child(14) td:nth-child(3)').text(moneyFormatIndia(agBal));
            $('.balance-sheet-card').find('tbody tr:nth-child(15) td:nth-child(3)').text(moneyFormatIndia(clBal));
            $('.benefits-check-card').find('tbody tr:nth-child(10) td:nth-child(3)').text(moneyFormatIndia(agBal));//benefit check table also will have same Agent balance
            $('.benefits-check-card').find('tbody tr:nth-child(11) td:nth-child(3)').text(moneyFormatIndia(clBal));//benefit check table also will have same closing balance

        }, 'json')

        //to get collection amount
        let ajaxCall2 = $.post('financeFile/BS/getCollectionAmount.php', args, function (response) {
            $('.balance-sheet-card').find('tbody tr:nth-child(3) td:nth-child(2)').text(response['due_collection']);
            $('.balance-sheet-card').find('tbody tr:nth-child(4) td:nth-child(2)').text(response['penalty']);
            $('.balance-sheet-card').find('tbody tr:nth-child(5) td:nth-child(2)').text(response['fine']);
        }, 'json')

        //to get Other income, bank withdrawal and cash deposit
        let ajaxCall3 = $.post('financeFile/BS/getBSCrContents.php', args, function (response) {
            $('.balance-sheet-card').find('tbody tr:nth-child(6) td:nth-child(2)').text(response['other_income']);
            $('.benefits-card').find('tbody tr:nth-child(6) td:nth-child(2)').text(response['other_income']);
            $('.profit-card').find('tbody tr:nth-child(6) td:nth-child(2)').text(response['other_income']);
        }, 'json')

        //to get Investment, Deposit, EL, Exchange and Agent
        let ajaxCall4 = $.post('financeFile/BS/getBSCrDbContents.php', args, function (response) {
            $('.balance-sheet-card').find('tbody tr:nth-child(7) td:nth-child(2)').text(response['cr_investment']);
            $('.balance-sheet-card').find('tbody tr:nth-child(7) td:nth-child(3)').text(response['db_investment']);
            $('.balance-sheet-card').find('tbody tr:nth-child(8) td:nth-child(2)').text(response['cr_deposit']);
            $('.balance-sheet-card').find('tbody tr:nth-child(8) td:nth-child(3)').text(response['db_deposit']);
            $('.balance-sheet-card').find('tbody tr:nth-child(9) td:nth-child(2)').text(response['cr_exchange']);
            $('.balance-sheet-card').find('tbody tr:nth-child(9) td:nth-child(3)').text(response['db_exchange']);
            $('.balance-sheet-card').find('tbody tr:nth-child(10) td:nth-child(2)').text(response['cr_el']);
            $('.balance-sheet-card').find('tbody tr:nth-child(10) td:nth-child(3)').text(response['db_el']);

            $('.benefits-check-card').find('tbody tr:nth-child(4) td:nth-child(2)').text(response['cr_investment']);
            $('.benefits-check-card').find('tbody tr:nth-child(4) td:nth-child(3)').text(response['db_investment']);
            $('.benefits-check-card').find('tbody tr:nth-child(5) td:nth-child(2)').text(response['cr_deposit']);
            $('.benefits-check-card').find('tbody tr:nth-child(5) td:nth-child(3)').text(response['db_deposit']);
            $('.benefits-check-card').find('tbody tr:nth-child(6) td:nth-child(2)').text(response['cr_exchange']);
            $('.benefits-check-card').find('tbody tr:nth-child(6) td:nth-child(3)').text(response['db_exchange']);
            $('.benefits-check-card').find('tbody tr:nth-child(7) td:nth-child(2)').text(response['cr_el']);
            $('.benefits-check-card').find('tbody tr:nth-child(7) td:nth-child(3)').text(response['db_el']);

        }, 'json')

        //to get Contra balance, addition of all bank transactions
        let ajaxCall5 = $.post('financeFile/BS/getContraContents.php', args, function (response) {
            $('.balance-sheet-card').find('tbody tr:nth-child(11) td:nth-child(2)').text(response['credit_contra']);
            $('.balance-sheet-card').find('tbody tr:nth-child(11) td:nth-child(3)').text(response['debit_contra']);

            $('.benefits-check-card').find('tbody tr:nth-child(8) td:nth-child(2)').text(response['credit_contra']);
            $('.benefits-check-card').find('tbody tr:nth-child(8) td:nth-child(3)').text(response['debit_contra']);

        }, 'json');

        //to get Issued, Expense, Bank Deposit and cash withdrawal
        let ajaxCall6 = $.post('financeFile/BS/getBSDbContents.php', args, function (response) {
            $('.balance-sheet-card').find('tbody tr:nth-child(12) td:nth-child(3)').text(response['issued']);
            $('.balance-sheet-card').find('tbody tr:nth-child(13) td:nth-child(3)').text(response['expense']);

            $('.benefits-card').find('tbody tr:nth-child(7) td:nth-child(3)').text(response['expense']);

            $('.profit-card').find('tbody tr:nth-child(7) td:nth-child(3)').text(response['expense']);
            
        }, 'json');

        ajaxCalls.push(ajaxCall1, ajaxCall2, ajaxCall3, ajaxCall4, ajaxCall5, ajaxCall6);

        // Now use $.when() to wait for all Ajax calls to complete
        $.when.apply($, ajaxCalls).done(function (res1, res2, res3, res4, res5, res6) {
            // This function will be executed when all Ajax calls are completed successfully
            // Put your code here for the function you want to run after all Ajax calls are completed.
            calculateClosingForBS();

            resolve(); // ✅ Finish the promise
        }).fail(function (err) {
            reject(err); // In case of error
        });
    });
}

// function to calculate closing details for balance sheet calculations
function calculateClosingForBS() {
    let credit = 0; let debit = 0;

    $('.balance-sheet-card').find('tbody tr').each(function () { //included opening balance also for credit total//only removed closing balance while summarizing debit amount for closing bal calculation
       let credit_val = $(this).find('td:nth-child(2)').text() || '0';
        credit += parseFloat(credit_val.replaceAll(',', '')) || 0;

        let debit_val = $(this).find('td:nth-child(3)').text() || '0';
        debit += parseFloat(debit_val.replaceAll(',', '')) || 0;
    })
    // console.log("🚀 ~ credit:", credit)
    // console.log("🚀 ~ debit:", debit)

    // let closing_balance = credit - debit;
    // console.log("🚀 ~ calculate ClosingForBS ~ closing_balance:", closing_balance)
    // debit = debit + clBal;//included closing balance also for debit total
    let difference = credit - debit;
    credit = Number(credit.toFixed(2));
    debit = Number(debit.toFixed(2));
    difference = Number(difference.toFixed(2));

    $('.balance-sheet-card').find('tfoot tr:first td:nth-child(2)').text(moneyFormatIndia(credit));
    $('.balance-sheet-card').find('tfoot tr:first td:nth-child(3)').text(moneyFormatIndia(debit));
    $('.balance-sheet-card').find('tfoot tr:last td:nth-child(2)').text(moneyFormatIndia(difference));
}

//it will calculate for all type of searches handling by type, after ajax calls are completed then it will trigger to calculate closing details
function BenefitCalculations(type, from_date, to_date, month) {
  return new Promise((resolve, reject) => {

        var user_id = $('#by_user').val();
        if (type == 'today') { var args = { 'type': 'today', 'user_id': user_id }; } else
            if (type == 'day') { var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id }; } else
                if (type == 'month') { var args = { 'type': 'month', 'month': month, 'user_id': user_id }; }

        // Create an array to store all the Ajax calls
        let ajaxCalls = [];

        //to get Benefit Amount
        let ajaxCall1 = $.post('financeFile/Benefits/getBenefitAmount.php', args, function (response) {
            $('.benefits-card').find('tbody tr:first td:nth-child(2)').text(response['benefit_amount']) //it will get the 2nd column value inside tbody // will take you to opening balance credit column
        }, 'json')

        //to get Document charges with processing fees
        let ajaxCall2 = $.post('financeFile/BS/getDocumentCharges.php', args, function (response) {
            $('.benefits-card').find('tbody tr:nth-child(2) td:nth-child(2)').text(response['doc_charge']);
            $('.benefits-card').find('tbody tr:nth-child(3) td:nth-child(2)').text(response['proc_charge']);

            $('.profit-card').find('tbody tr:nth-child(2) td:nth-child(2)').text(response['doc_charge']);
            $('.profit-card').find('tbody tr:nth-child(3) td:nth-child(2)').text(response['proc_charge']);

        }, 'json')

        //to get Penalty and fine 
        let ajaxCall3 = $.post('financeFile/Benefits/getPenaltyFine.php', args, function (response) {
            $('.benefits-card').find('tbody tr:nth-child(4) td:nth-child(2)').text(response['penalty']);
            $('.benefits-card').find('tbody tr:nth-child(5) td:nth-child(2)').text(response['fine']);

            $('.profit-card').find('tbody tr:nth-child(4) td:nth-child(2)').text(response['penalty']);
            $('.profit-card').find('tbody tr:nth-child(5) td:nth-child(2)').text(response['fine']);

        }, 'json');

        ajaxCalls.push(ajaxCall1, ajaxCall2, ajaxCall3);

        // Now use $.when() to wait for all Ajax calls to complete
        $.when.apply($, ajaxCalls).done(function () {
            // This function will be executed when all Ajax calls are completed successfully
            // Put your code here for the function you want to run after all Ajax calls are completed.

            resolve(); // ✅ Finish the promise
        }).fail(function (err) {
            reject(err); // In case of error
        });
    });
}

// function to calculate closing details for Benefits calculations
function calculateClosingForBenefit() {
    var credit = 0; var debit = 0;

    $('.benefits-card').find('tbody tr').each(function () {
        let credit_val = $(this).find('td:nth-child(2)').text() ? $(this).find('td:nth-child(2)').text() : '0';
        credit = credit + parseFloat(credit_val.replaceAll(',', ''));

        let debit_val = $(this).find('td:nth-child(3)').text() ? $(this).find('td:nth-child(3)').text() : '0';
        debit = debit + parseFloat(debit_val.replaceAll(',', ''));
    })

    var difference = credit - debit;
    $('.benefits-card').find('tfoot tr:first td:nth-child(2)').text(moneyFormatIndia(credit));
    $('.benefits-card').find('tfoot tr:first td:nth-child(3)').text(moneyFormatIndia(debit));
    $('.benefits-card').find('tfoot tr:last td:nth-child(2)').text(moneyFormatIndia(difference));
}

//it will calculate for all type of searches handling by type, after ajax calls are completed then it will trigger to calculate closing details
function ProfitCalculations(type, from_date, to_date, month) {
    return new Promise((resolve, reject) => {

        var user_id = $('#by_user').val();
        if (type == 'today') { var args = { 'type': 'today', 'user_id': user_id }; } else
            if (type == 'day') { var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id }; } else
                if (type == 'month') { var args = { 'type': 'month', 'month': month, 'user_id': user_id }; }

        $.ajax({
            url: 'financeFile/Profit/getProfitAmount.php',
            data: args,
            type: 'post',
            dataType: 'json',
            cache: false,
            success: function (response) {
                $('.profit-card').find('tbody tr:first td:nth-child(2)').text(response['split_interest']);
            }
        }).then(function () {

            resolve(); // ✅ Finish the promise
        }).fail(function (err) {
            reject(err); // In case of error
        });
    });
}

// function to calculate closing details for Profit calculations
function calculateClosingForProfit() {
    var credit = 0; var debit = 0;

    $('.profit-card').find('tbody tr').each(function () {
        let credit_val = $(this).find('td:nth-child(2)').text() ? $(this).find('td:nth-child(2)').text() : '0';
        credit = credit + parseInt(credit_val.replaceAll(',', ''));

        let debit_val = $(this).find('td:nth-child(3)').text() ? $(this).find('td:nth-child(3)').text() : '0';
        debit = debit + parseInt(debit_val.replaceAll(',', ''));
    })

    var difference = credit - debit;
    $('.profit-card').find('tfoot tr:first td:nth-child(2)').text(moneyFormatIndia(credit));
    $('.profit-card').find('tfoot tr:first td:nth-child(3)').text(moneyFormatIndia(debit));
    $('.profit-card').find('tfoot tr:last td:nth-child(2)').text(moneyFormatIndia(difference));
}

//it will calculate for all type of searches handling by type, after ajax calls are completed then it will trigger to calculate closing details
function BenefitCheckCalculations(type, from_date, to_date, month) {
  return new Promise((resolve, reject) => {

        var user_id = $('#by_user').val();
        if (type == 'today') { var args = { 'type': 'today', 'user_id': user_id }; } else
            if (type == 'day') { var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id }; } else
                if (type == 'month') { var args = { 'type': 'month', 'month': month, 'user_id': user_id }; }

        // Create an array to store all the Ajax calls
        let ajaxCalls = [];

        //to get opening outstanding
        let ajaxCall1 = $.post('financeFile/BenefitsCheck/getOpeningOutstanding.php', args, function (response) {
            $('.benefits-check-card').find('tbody tr:first td:nth-child(2)').text(response['opening_outstanding']) //it will get the 2nd column value inside tbody // will take you to opening outstanding credit column
        }, 'json');

        let ajaxCall5 = $.post('financeFile/BenefitsCheck/getClosingOutstanding.php', args, function (response) {
            $('.benefits-check-card').find('tbody tr:nth-child(9) td:nth-child(3)').text(response['closing_outstanding']) //it will get the 2nd column value inside tbody // will take you to opening outstanding credit column
        }, 'json');

        ajaxCalls.push(ajaxCall1, ajaxCall5);

        // Now use $.when() to wait for all Ajax calls to complete
        $.when.apply($, ajaxCalls).done(function () {
            // This function will be executed when all Ajax calls are completed successfully
            // Put your code here for the function you want to run after all Ajax calls are completed.

            resolve(); // ✅ Finish the promise
        }).fail(function (err) {
            reject(err); // In case of error
        });
    });
}

// function to calculate closing details for Benefits calculations
function calculateClosingForBenefitCheck() {
    let credit = 0; let debit = 0;

    credit = 0; debit = 0;

    $('.benefits-check-card').find('tbody tr').each(function () {
        //this will take rows from investment till end of tr's
         let credit_val = $(this).find('td:nth-child(2)').text() || '0';
        credit += parseFloat(credit_val.replaceAll(',', '')) || 0;

        let debit_val = $(this).find('td:nth-child(3)').text() || '0';
        debit += parseFloat(debit_val.replaceAll(',', '')) || 0;
    })

    let difference = debit - credit;
     credit = Number(credit.toFixed(2));
    debit = Number(debit.toFixed(2));
    difference = Number(difference.toFixed(2));

    $('.benefits-check-card').find('tfoot tr:first td:nth-child(2)').text(moneyFormatIndia(credit));
    $('.benefits-check-card').find('tfoot tr:first td:nth-child(3)').text(moneyFormatIndia(debit));
    $('.benefits-check-card').find('tfoot tr:last td:nth-child(2)').text(moneyFormatIndia(difference));

}

// to clear all contents
function clearAllContents() {
    $('.balance-sheet-card').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    })
    $('.benefits-card').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    })
    $('.benefits-check-card').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    })
    $('.profit-card').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    })
}

//alert message
function swalError(title, icon) {
    Swal.fire({
        title: title,
        icon: icon,
        showConfirmButton: true,
        confirmButtonColor: '#009688'
    })
}

//After all function completed then calculate the total.
function callCalculateFunctions(){
    calculateClosingForBenefit();
    calculateClosingForBenefitCheck();
    calculateClosingForProfit();
}