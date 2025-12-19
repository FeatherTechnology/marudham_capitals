$(document).ready(function () {
    
    $('#from_date').change(function(){
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

         // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

    //Loan Report Table
    $('#reset_btn').click(function () {
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
            if (!from_date || !to_date) {
                swalError('Please Select All Fields!', 'All fields are required.');
                return;
            }
            nocHandoverReportTable()
        })
   

});

function nocHandoverReportTable(){
   
    $('#noc_handover_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var noc_handover_report_table = $('#noc_handover_report_table').DataTable({
        ...getStateSaveConfig('noc_handover_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/noc_handover/getNocHandoverReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "NOC Handover Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('NOC_Handover_Report'); // or any base
                config.title = dynamic;      // for versions that use title as filename
                config.filename = dynamic;   // for html5 filename
                defaultAction.call(this, e, dt, button, config);
            }
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
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();

            // Remove formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Array of column indices to sum
          
        },
        'drawCallback': function () {
            searchFunction('noc_handover_report_table');
            paginationFunction('noc_handover_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(noc_handover_report_table, 'noc_handover_report_table');
}
function swalError(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#009688',
    });
}
