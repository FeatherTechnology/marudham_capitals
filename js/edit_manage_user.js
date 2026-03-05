// Document is ready
$(document).ready(function () {
    $('input[name=user_status]').click(function(event){
        let status = $(this).val();
        let sts;
        if(status =='active'){ //Active
            sts = '0';
        } else{ //In-Active
            sts = '1';
        }
        getManageUserList(sts);
    });
});

$(function(){
    let selectedSts = $('input[name="user_status"]:checked').val();
    
    if(selectedSts =='active'){ //Active
        getManageUserList('0');
        
    } else if(selectedSts =='inactive'){ //In-Active
        getManageUserList('1');
    }
});

function getManageUserList(sts){
    // Manage user datatable
    $('#manage_user_table').DataTable().destroy();
    var manage_user_table = $('#manage_user_table').DataTable({
        ...getStateSaveConfig('manage_user_table'),
        "order": [
            [0, "desc"]
        ],
        "displayStart": getDisplayStart('manage_user_table'),
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxManageUserFetch.php',
            'data': function(data) {
                data.search = $('input[type=search]').val();
                data.userStatus = sts;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                title: "User List",
                action: function(e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('User_List'); // or any base
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
            $('#manage_user_table_div').show();
            searchFunction('manage_user_table');
            paginationFunction('manage_user_table');
        }
    });
    initColVisFeatures(manage_user_table, 'manage_user_table');
}