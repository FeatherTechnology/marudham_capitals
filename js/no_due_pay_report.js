$(document).ready(function () {
    //Collection Report Table
    $('#reset_btn').click(function () {
        noDuePayReportTable();
    })
});

function noDuePayReportTable(){
    $('#no_pay_due_report_table').DataTable().destroy();
    $('#no_pay_due_report_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/no_due_pay/getNoDuePayRreport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.from_date = $('#from_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "No Due Pay Report"
        },
        {
            extend: 'colvis',
            collectionLayout: 'fixed four-column',
        }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        'drawCallback': function() {
            searchFunction('no_pay_due_report_table');
        }
    });
}
