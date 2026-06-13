const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select',
    allowHTML: true
});

$('#map_name').closest('.choices').hide();

$(document).ready(function () {

    //to validate from and to date. don't allow to choose todate before the date of from date.
    $('#from_date').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

         // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

    $('#type').change(function (e) {
        let type = $(this).val();
        $('#user_type, #by_user').val('').hide();
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        $('#promotion_activity_report_table').DataTable().destroy();
        $('#promotion_activity_report_table tbody').empty();
        
        if(type == '1'){ 
            $('#user_type, #by_user').val('').show();
            $('#by_user').empty().append("<option value=''>Select User</option>");

        } else if(type == '2' || type == '3' || type == '4') { //sector - group, Region - Line, Zone - Follow up
            $('#map_name').closest('.choices').show();
            getUserMappedDetails(type); //to Mapping details.
        }
    });

    $('#user_type').change(function () {
        let userType = $('#user_type').val();
        $('#by_user').empty().append("<option value=''>Select User</option>");  

        if(userType != ''){
            getUserNames();
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
    let fromDate = $('#from_date').val();
    let toDate = $('#to_date').val();
    let selectedType = $('#type').val();
    let selected_user = $('#by_user').val();
    let user_type = $('#user_type').val();
    let selectedVal = '';

    if(selectedType == '1'){ //user
        selectedVal = '1'; //dummy
        
    } else if(selectedType == '2' || selectedType == '3' || selectedType == '4'){ //sector - group //Region - Line //Zone - Followup
        selectedVal = $('#map_name').val();
    }

    if(!fromDate || !toDate || !selectedVal || (selectedType == '1' && (!user_type || !selected_user))){
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
                data.fromdate = fromDate;
                data.todate = toDate;
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