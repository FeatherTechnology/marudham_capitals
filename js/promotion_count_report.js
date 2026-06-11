const map_name = new Choices('#map_name', {
    removeItemButton: true,
    noChoicesText: 'Select',
    allowHTML: true
});

$('#map_name').closest('.choices').hide();

$(document).ready(function () {

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
        $('#map_name').closest('.choices').hide();
        map_name.clearStore();
        $('#promotion_count_report_table').DataTable().destroy();
        $('#promotion_count_report_table tbody').empty();
        $('#promotion_count_report_table tfoot td:not(:first)').html('');
        
        if(type == '1'){ 
            $('#user_type, #by_user').val('').show();
            $('#by_user').empty().append("<option value=''>Select User</option>");

        } else if(type == '2' || type == '3' || type == '4') { //sector - group, Region - Line, Zone - Follow up
            $('#user_type, #by_user').val('').hide();
            $('#map_name').closest('.choices').show();
            getUserMappedDetails(type); //to Mapping details.
            
        } else if(type == '0'){
            $('#user_type, #by_user').val('').hide();
        }
    });

    $('#user_type').change(function () {
        let userType = $('#user_type').val();
        $('#by_user').empty().append("<option value=''>Select User</option>");  

        if(userType != ''){
            getUserNames();
        }
    });

    $('#reset_btn').click(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let selectedType = $('#type').val();
        let user_type = $('#user_type').val();
        let selected_user = $('#by_user').val();
        let selectedVal = '';

        if(selectedType == '1'){ //user
            selectedVal = '1'; //dummy
            
        } else if(selectedType == '2' || selectedType == '3' || selectedType == '4'){ //sector - group //Region - Line //Zone - Followup
            selectedVal = $('#map_name').val();
        }

        if(!from_date || !to_date || !selectedVal || (selectedType == '1' && (!user_type || !selected_user))){
            swalError('Warning', `All Fields are required.`);
            return;
        }

        getPromotionCountReport(from_date, to_date, selectedType, user_type, selected_user, selectedVal);
    });
});

function getUserNames() {
    let user_type = $('#user_type').val();

    $.post('reportFile/due_followup_count_report/getDuefollowupUser.php', { screen: 3, user_type: user_type }, function (response) {
        $('#by_user').empty().append("<option value=''>Select User</option> <option value='0'>All</option>");
        $.each(response, function (i, val) {
            $('#by_user').append("<option value='" + val.user_id + "'>" + val.username + "</option>");
        });
    }, 'json');
}

function getPromotionCountReport(from_date, to_date, selectedType, user_type, user_id, selectedVal) {

    $('#promotion_count_report_table').DataTable().destroy();
    // Declare table variable to store the DataTable instance
    var promotion_count_report_table = $('#promotion_count_report_table').DataTable({
        ...getStateSaveConfig('promotion_count_report_table'),
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/promotion_count/getPromotionCountReport.php',
            'data': function (data) {
                data.search = $('input[type=search]').val();
                data.from_date = from_date;
                data.to_date = to_date;
                data.selectedType = selectedType;
                data.user_type = user_type;
                data.user_id = user_id;
                data.selectedVal = selectedVal;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Promotion Count Report",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Promotion_Count_Report'); // or any base
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
            /* ---------------- PARSER ---------------- */
            const parseVal = (v) => {
                if (typeof v === 'string') {
                    return parseFloat(v.replace(/,/g, '')) || 0;
                }
                return v || 0;
            };

            // Array of column indices to sum
            var columnsToSum = [1, 2, 3, 4, 5,  6, 7, 8, 9, 10,  11, 12, 13, 14, 15,  16, 17, 18, 19, 20];

            // Loop through each column index
            columnsToSum.forEach(function (colIndex) {
                // Total over all pages for the current column
                var total = api
                    .column(colIndex)
                    .data()
                    .reduce(function (a, b) {
                        return parseVal(a) + parseVal(b);
                    }, 0);
                // Update footer for the current column
                $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
            });
        },
        'drawCallback': function () {
            searchFunction('promotion_count_report_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(promotion_count_report_table, 'promotion_count_report_table');
}