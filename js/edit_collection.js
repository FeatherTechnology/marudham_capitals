const subStatusMultiselect = new Choices('#sub_status_mapping', {
    removeItemButton: true,
    noChoicesText: 'Select Customer Status',
    allowHTML: true
});

$(document).ready(function () {
    let Cus_Sts=$("#customer_status").val();
    let Customer_Sts = Cus_Sts.split(',');
    
    if(Customer_Sts!=''){
        getcustomerStatustable(Customer_Sts);
        getSubStsMapping();
    }
   
    $('#get_cus_sts_btn').click(function () {
        let Customer_Status=$("#sub_status_mapping").val();
        getcustomerStatustable(Customer_Status);
    })
   


});


$(function(){
    getSubStsMapping();
})

function getcustomerStatustable(Customer_status){
    if(Customer_status){
            
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
                        data.Customer_status=Customer_status;
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
            $(".subStatusCheck").hide();

        }
        else{
            $(".subStatusCheck").show();
        }
}

function getSubStsMapping() {
    let subStatus =['Legal','Error','OD','Pending','Current'];
    let editSubStatus = $('#customer_status').val()||'';

    subStatusMultiselect.clearStore();
    $.each(subStatus, function(index, val){
        let selected = '';
        if(editSubStatus.includes(val)){
            selected = 'selected';
        }
        let items = [
            {value: val, label: val, selected: selected},
        ]
        subStatusMultiselect.setChoices(items);
        subStatusMultiselect.init();
    });

}
