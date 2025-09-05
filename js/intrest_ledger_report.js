$(document).ready(function () {

    $(".toggle-button").on("click", function () {
        // Reset active class for all buttons
        $(".toggle-button").removeClass("active");
        // Add active class to the clicked button
        $(this).addClass("active");

        let typevalue = this.value;//this will contain the selected value

        $("#daily_card").toggle(typevalue === 'Daily');//this condition will throw true to toggle function which will show cards
        $("#monthly_card").toggle(typevalue === 'Monthly');

        if (typevalue === 'Daily') { getDailyTable();}
        else if (typevalue === 'Monthly') {
            $('#monthly_date').off().change(function () {
                let monthly_date = this.value;
                getMonthlyTable(monthly_date);
            })
        }
    });

});

function getDailyTable() {
    $.post('reportFile/intrest_loan_report/getIntrestLoanLedgerViewReportDaily.php', {}, function (data) {
        $('#daily_table_div').empty().html(data);
    });
}

function getMonthlyTable(monthly_date) {
    $.post('reportFile/intrest_loan_report/getIntrestLoanLedgerViewReportMonthly.php', { monthly_date }, function (data) {
        $('#monthly_table_div').empty().html(data);
    });
}