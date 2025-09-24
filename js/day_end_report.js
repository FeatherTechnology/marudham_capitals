$(document).ready(function () {
    $('#reset_btn').click(function () {
        if($('#search_date').val() == ""){
            alert('Please Select The Search Date');
        }else{
            dayEndReportTable();
        }
    });

    // Print Day End Report
    $('#print_day_end_report').click(function () {
        var rowCount = $('#day_end_div table tbody tr').length;

        if (rowCount === 0) {
            alert('No data available to print.');
            return; 
        }

        var printContents = document.getElementById('day_end_div').innerHTML;
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Day End Report</title>');
        printWindow.document.write('<style>table{border-collapse: collapse; width:100%;} table, th, td {border: 1px solid black; padding: 8px;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    });

});

function dayEndReportTable(){
   var search_date = $('#search_date').val();

    $.ajax({
        url: 'reportFile/day_end_report/get_day_end_report.php',
        data: { 'search_date': search_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#day_end_div').empty()
            $('#day_end_div').html(response)
            $('#print_day_end_report').show();
        }
    })
}

