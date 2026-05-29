const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select',
    allowHTML: true
});

$('#map_name').closest('.choices').hide();

$(document).ready(function () {

    $('#type').change(function (e) {
        let type = $(this).val();
        $('#user_type, #by_user').val('').show();
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        $('#promotion_activity_report_table').DataTable().destroy();
        $('#promotion_activity_report_table tbody').empty();
        
        if(type == '2' || type == '3' || type == '4') { //sector - group
            $('#map_name').closest('.choices').show();
            
        } else if(type == '0'){
            $('#user_type, #by_user').hide();
        }
    });

    $('#user_type').change(function () {
        let userType = $('#user_type').val();
        $('#by_user').empty().append("<option value=''>Select User</option>");  

        if(userType != ''){
            getUserNames();
        }
    });

    $('#by_user').change(function(){
        let userId = $(this).val();
        let typeVal = $('#type').val();

        if(typeVal != '1' && userId !=''){ //if type user then no need to show mapping.
            $.post('reportFile/promotion_activity/getUserMappedDetails.php', {userId, typeVal}, function (response) {

                map_name.clearStore();

                const items = response.map(row => ({
                    value: row.ids,
                    label: row.map_name
                }));

                map_name.setChoices(items);

            },'json');
        }
    });

    //commitment Report Table
    $('#reset_btn').click(function () {
        commitmentReportTable();
    });
});

function getUserNames() {
    let user_type = $('#user_type').val();

    $.post('reportFile/promotion_activity/user_list.php', { user_type: user_type }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option>");
        $.each(response, function (index, val) {
            $('#by_user').append(
                "<option value='" + val['user_ids'] + "'>" + val['fullname'] + "</option>"
            );
        });
    }, 'json');
}

function commitmentReportTable() {
    let selected_date = $('#selected_date').val();
    let selectedType = $('#type').val();
    let selected_user = $('#by_user').val();
    let user_type = $('#user_type').val();
    let selectedVal = '';

    if(selectedType == '1'){ //user
        selectedVal = '1'; //dummy
        
    } else if(selectedType == '2' || selectedType == '3' || selectedType == '4'){ //sector - group //Region - Line //Zone - Followup
        selectedVal = $('#map_name').val();
    }

    if(!selected_date || !selectedVal || !user_type || !selected_user){
        swalError('Warning', `All Fields are required.`);
        return;
    } 

    $('#promotion_activity_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var promotion_activity_report_table = $('#promotion_activity_report_table').DataTable({
        ...getStateSaveConfig('promotion_activity_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/promotion_activity/getPromotionActivityReport.php',
            'data': function (data) {
                data.search = $('input[type=search]').val();
                data.selected_date = selected_date;
                data.selectedType = selectedType;
                data.selectedVal = selectedVal;
                data.user_id = selected_user;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Promotion Activity Report List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Promotion_Activity_Report'); // or any base
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
            searchFunction('promotion_activity_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(promotion_activity_report_table, 'promotion_activity_report_table');
}