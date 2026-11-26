$(document).off("click", ".manual-noc-handover").on("click", ".manual-noc-handover", function () {

    let id = $(this).data("id");

    Swal.fire({
        title: 'Confirmation',
        text: "Are you sure to Submit?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#009688",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
    }).then(function (result) {

        if (result.isConfirmed) {

            $.post(
                "reportFile/noc_handover/UpdateManualNocHandover.php",
                { id: id },
                function (response) {

                    if (response.includes('Successfully')) {

                        swalSuccess('Success', 'Submitted Successfully')
                            .then(function (res) {
                                if (res.isConfirmed) {
                                    location.reload();
                                }
                            });

                    } else {
                        swalError('Error', 'Error While Updating');
                    }

                },
                'text'
            ).fail(function () {
                swalError('Error', 'Server Error While Updating');
            });
        }
    });
});


$(function(){
    nocHandoverReportTable();
});

function nocHandoverReportTable(){
   
    $('#manual_noc_handover_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var manual_noc_handover_report_table = $('#manual_noc_handover_report_table').DataTable({
        ...getStateSaveConfig('manual_noc_handover_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'infoCallback': customDataTableInfo,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/noc_handover/getManualNocHandoverReport.php',
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
            title: "Manual NOC Handover Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Manual_NOC_Handover_Report'); // or any base
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
        'drawCallback': function () {
            searchFunction('manual_noc_handover_report_table');
            paginationFunction('manual_noc_handover_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(manual_noc_handover_report_table, 'manual_noc_handover_report_table');
}