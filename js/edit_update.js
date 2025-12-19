$(document).ready(function () {

    $('#doc_sts_btn').click(function (event) {
        event.preventDefault();
        let doc_sts = $(this).data('filter');
        getcustomerStatustable(doc_sts);
        // Change the heading text
        $(".card-title").text("Document Pending List");
        $('#all_btn').show();
        $('#doc_sts_btn').hide();
        $("#doc_sts_id").val('');
    })

    $('#all_btn').click(function (event) {
        event.preventDefault();
        getcustomerStatustable('');
        // Reset heading text back to Collection List
        $(".card-title").text("Collection List");
        $('#all_btn').hide();
        $('#doc_sts_btn').show();
        $("#doc_sts_id").val('');
    })

});

$(function () {
    let duests = $("#doc_sts_id").val();
    if (duests == 'NO') {
        $("#doc_sts_btn").click();
    }
    else {
        getcustomerStatustable('');

    }
})

function getcustomerStatustable(doc_sts) {
    // Get the current page index before destroying the table
    var table = $('#update_table').DataTable();

    // Destroy the existing DataTable
    table.destroy();

    // Declare table variable to store the DataTable instance
    var update_table = $('#update_table').DataTable({
        ...getStateSaveConfig('update_table'),
        "order": [
            [0, "desc"]
        ],
        "displayStart": getDisplayStart('update_table'),
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxUpdateFetch.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.doc_sts = doc_sts;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Update List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Update_List'); // or any base
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
            searchFunction('update_table');
            paginationFunction('update_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(update_table, 'update_table');

    // No need to manually restore the page; it's handled automatically by stateSave
    $(".table-responsive").show();
}

