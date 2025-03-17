$(document).ready(function () {
 
    $('#due_nill_btn').click(function (event) {
        event.preventDefault();
        let Customer_Status=$(this).val();
        getcustomerStatustable(Customer_Status);
        $('#all_btn').show();
        $('#due_nill_btn').hide();
        $("#duenill_id").val('');
    })

    $('#all_btn').click(function (event) {
        event.preventDefault();
        getcustomerStatustable('');
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

function getcustomerStatustable(Customer_Status){

    $('#collection_table').DataTable().destroy();
    $('#collection_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxCollectionFetch.php',
            'data': function(data) {
                var search = $('#search').val();
                data.search = search;
                data.CustomerStatus=Customer_Status;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                title: "Collection List"
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
            searchFunction('collection_table');
        }
        
    });
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
