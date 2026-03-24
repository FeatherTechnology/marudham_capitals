// Document is ready
$(document).ready(function () {
    $('input[name=staff_status]').click(function(event){
        let status = $(this).val();
        let sts;
        if(status =='active'){ //Active
            sts = '0';
        } else{ //In-Active
            sts = '1';
        }
        getStaffCreationList(sts);
    });
});

$(function(){
    let selectedSts = $('input[name="staff_status"]:checked').val();
    
    if(selectedSts =='active'){ //Active
        getStaffCreationList('0');
        
    } else if(selectedSts =='inactive'){ //In-Active
        getStaffCreationList('1');
    }
});

function getStaffCreationList(sts){

    // Staff Creation datatable
    $('#staff_creation_table').DataTable().destroy();
    var staff_creation_table = $('#staff_creation_table').DataTable({
        ...getStateSaveConfig('staff_creation_table'),
        "order": [
            [0, "desc"]
        ],
        "displayStart": getDisplayStart('staff_creation_table'),
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxStaffCreationFetch.php',
            'data': function(data) {
                data.search = $('input[type=search]').val();
                data.staffStatus = sts;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                title: "Staff List",
                action: function(e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Staff_List'); // or any base
                    config.title = dynamic; // for versions that use title as filename
                    config.filename = dynamic; // for html5 filename
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
        'drawCallback': function() {
            $('#staff_creation_table_div').show();
            searchFunction('staff_creation_table');
            paginationFunction('staff_creation_table');
        }
    });
    initColVisFeatures(staff_creation_table, 'staff_creation_table');
}