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
            BalanceSheetCalculations('today', '', '', '');
            $('#from_date, #to_date').val('');
        }
    });

    $('#submitDaywise').click(function (event) {
        let from_date = $('#from_date').val(); let to_date = $('#to_date').val();
        if (from_date != '' && to_date != '') {
            BalanceSheetCalculations('day', from_date, to_date, '');

            $('.close').trigger('click');//it will close modal
        } else {
            event.preventDefault();
            swalError('Please Fill Dates!', 'error');
        }
    });

    $('#from_date').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

        // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

});//Document Ready End

$(function () {
    let reportAccess = $('#report_access').val();
    if(reportAccess =='2'){ //Overall
        getUserNames();
    }
});// auto load functions End

function getUserNames() {
    //get user name only who has access of cash tally
    $.post('financeFile/getUsersName.php', function (response) {
        $('#by_user').append("<option value=''>Select User</option>")
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['user_id'] + "' data-branch='" + val['branch_id'] + "'>" + val['username'] + "</option> ");
        });
    }, 'json');
}

function BalanceSheetCalculations(type, from_date, to_date) {
  return new Promise((resolve, reject) => {

        var user_id 
        let reportAccess = $('#report_access').val();
        if(reportAccess == '1'){//individual
            user_id = $('#userid').val();

        }else if(reportAccess == '2'){ //Overall
            user_id = $('#by_user').val();
            
        }

        branch_id = $('#userid option:selected').data('branch');
        let args = { type, user_id, branch_id };

        if (type === 'day') {
            args.from_date = from_date;
            args.to_date = to_date;
            args.branch_id = branch_id;
        }

        //to get Hand cash balance sheet
        $.post('accountsFile/HandCashBS/getHandCashBSDetails.php', args, function (response) {
            
            let coll_record = response.collection_record;
            let circular_amt = response.circular_amount;

            //Opening balance
            $('.balance-sheet-card').find('tbody tr:first td:nth-child(2)').text(coll_record['hand_opening']);

            //Collection and Other income
            $('.balance-sheet-card').find('tbody tr:nth-child(2) td:nth-child(2)').text(coll_record['due_collection']);
            $('.balance-sheet-card').find('tbody tr:nth-child(3) td:nth-child(2)').text(coll_record['pre_close_waiver']);
            $('.balance-sheet-card').find('tbody tr:nth-child(4) td:nth-child(2)').text(coll_record['penalty']);
            $('.balance-sheet-card').find('tbody tr:nth-child(5) td:nth-child(2)').text(coll_record['fine']);
            $('.balance-sheet-card').find('tbody tr:nth-child(6) td:nth-child(2)').text(coll_record['other_income']);

            //Investment, Deposit, Exchange, EL and Contra
            $('.balance-sheet-card').find('tbody tr:nth-child(7) td:nth-child(2)').text(coll_record['cr_investment']);
            $('.balance-sheet-card').find('tbody tr:nth-child(7) td:nth-child(3)').text(coll_record['db_investment']);
            $('.balance-sheet-card').find('tbody tr:nth-child(8) td:nth-child(2)').text(coll_record['cr_deposit']);
            $('.balance-sheet-card').find('tbody tr:nth-child(8) td:nth-child(3)').text(coll_record['db_deposit']);
            $('.balance-sheet-card').find('tbody tr:nth-child(9) td:nth-child(2)').text(coll_record['cr_exchange']);
            $('.balance-sheet-card').find('tbody tr:nth-child(9) td:nth-child(3)').text(coll_record['db_exchange']);
            $('.balance-sheet-card').find('tbody tr:nth-child(10) td:nth-child(2)').text(coll_record['cr_el']);
            $('.balance-sheet-card').find('tbody tr:nth-child(10) td:nth-child(3)').text(coll_record['db_el']);
            $('.balance-sheet-card').find('tbody tr:nth-child(11) td:nth-child(2)').text(coll_record['credit_contra']);
            $('.balance-sheet-card').find('tbody tr:nth-child(11) td:nth-child(3)').text(coll_record['debit_contra']);

            //Issued, Agent and Expenses
            $('.balance-sheet-card').find('tbody tr:nth-child(12) td:nth-child(3)').text(coll_record['issued']);
            $('.balance-sheet-card').find('tbody tr:nth-child(13) td:nth-child(2)').text(coll_record['cr_agent']);
            $('.balance-sheet-card').find('tbody tr:nth-child(13) td:nth-child(3)').text(coll_record['db_agent']);
            $('.balance-sheet-card').find('tbody tr:nth-child(14) td:nth-child(3)').text(coll_record['expense']);

            //Circular Amount
            $('.balance-sheet-card').find('tbody tr:nth-child(15) td:nth-child(3)').text(moneyFormatIndia(circular_amt['total_credit']));
            $('.balance-sheet-card').find('tbody tr:nth-child(15) td:nth-child(2)').text(moneyFormatIndia(circular_amt['total_debit']));

            //Closing balance
            $('.balance-sheet-card').find('tbody tr:nth-child(16) td:nth-child(3)').text(coll_record['hand_closing']);

        }, 'json').done(function () {
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
    });

    let difference = credit - debit;
    credit = Number(credit.toFixed(2));
    debit = Number(debit.toFixed(2));
    difference = Number(difference.toFixed(2));

    $('.balance-sheet-card').find('tfoot tr:first td:nth-child(2)').text(moneyFormatIndia(credit));
    $('.balance-sheet-card').find('tfoot tr:first td:nth-child(3)').text(moneyFormatIndia(debit));
    $('.balance-sheet-card').find('tfoot tr:last td:nth-child(2)').text(moneyFormatIndia(difference));
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