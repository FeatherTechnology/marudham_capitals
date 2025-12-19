$(document).ready(function () {
 
    $('#due_nill_btn').click(function (event) {
        event.preventDefault();
        let Customer_Status=$(this).data('filter');
        getcustomerStatustable(Customer_Status);
        // Change the heading text
        $(".card-title").text("Due Nil List");
        $('#all_btn').show();
        $('#due_nill_btn').hide();
        $("#duenill_id").val('');
    })

    $('#all_btn').click(function (event) {
        event.preventDefault();
        getcustomerStatustable('');
        // Reset heading text back to Collection List
        $(".card-title").text("Collection List");
        $('#all_btn').hide();
        $('#due_nill_btn').show();
        $("#duenill_id").val('');
    })
   
});

$(function(){
    getCollectionAccess();
    let duests=$("#duenill_id").val();
    if(duests=='due_nill'){
        $("#due_nill_btn").click();  
    }
    else{
        getcustomerStatustable('');

    }
})

function getcustomerStatustable(Customer_Status) {
    // Get the current page index before destroying the table
    var table = $('#collection_table').DataTable();

    // Destroy the existing DataTable
    table.destroy();

    // Reinitialize the DataTable with stateSave option to retain state
    var collection_table = $('#collection_table').DataTable({
        ...getStateSaveConfig('collection_table'),
        "order": [[0, "desc"]],
        'processing': true,
        "displayStart": getDisplayStart('collection_table'),
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxCollectionFetch.php',
            'data': function(data) {
                var search = $('#search').val();
                data.search = search;
                data.CustomerStatus = Customer_Status;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Collection List",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Collection_List'); // or any base
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
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "pageLength": 10, // Default 10 rows per page
        "paging": true,
        "pagingType": "full_numbers", // Show all page numbers
        "language": {
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        'drawCallback': function() {
            searchFunction('collection_table');
            paginationFunction('collection_table');
        }
    });
    initColVisFeatures(collection_table, 'collection_table');

    // No need to manually restore the page; it's handled automatically by stateSave
    $(".table-responsive").show();
}

function getCollectionAccess() {
    $.ajax({
        url: 'collectionFile/getCollectionAccess.php',
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if(response === 1){
                $('#dull_nill_div').hide();
            }
        }
    })
}
