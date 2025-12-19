
// Document is ready
$(document).ready(function () {

    //Mapping Type Change

    $('#area,#sub_area').click(function () {
        var area_status = $('input[name=area_status]:checked').val();
        if (area_status == 'area') {
            $('.area_status').show(); $('.sub_area_status').hide();
            dT1();
        }
        if (area_status == 'sub_area') {
            $('.area_status').hide(); $('.sub_area_status').show();
            dT2();
        }
    });
}); // document ready end

function dT1() {
    if ($.fn.DataTable.isDataTable('#area_status_table')) {
        $('#area_status_table').DataTable().clear().destroy();
    }

    // Declare table variable to store the DataTable instance
    var area_status_table = $('#area_status_table').DataTable({
        ...getStateSaveConfig('area_status_table'),
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        // 'bInfo':false, // to remove bottom paging info (showing 0 to 0 of 0),

        'ajax': {
            'url': 'ajaxFetch/ajaxGetAreaFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Area Status List",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Area_Status_List'); // or any base
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
            searchFunction('area_status_table');
            paginationFunction('area_status_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(area_status_table, 'area_status_table');
}
function dT2() {
    if ($.fn.DataTable.isDataTable('#sub_area_status_table')) {
        $('#sub_area_status_table').DataTable().clear().destroy();
    }

    // Declare table variable to store the DataTable instance
    var sub_area_status_table = $('#sub_area_status_table').DataTable({
        ...getStateSaveConfig('sub_area_status_table'),
        "order": [[0, "desc"]],
        'ordering': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxGetSubAreaFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },

        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Sub Area StatusList",
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('SubArea_Status_List'); // or any base
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
            searchFunction('sub_area_status_table');
            paginationFunction('sub_area_status_table');
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(sub_area_status_table, 'sub_area_status_table');
}

//For Enable
function enable(area_id) {
    var area_status = $('input[name=area_status]:checked').val();
    var action = "enable";
    if (area_status == 'area') {
        if (confirm('Do you want to Enable this Area?')) {
            $.ajax({
                url: 'areaStatus/enableDisableArea.php',
                data: { 'area_id': area_id, 'action': action },
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        dT1();
                        $('#area_enable').show();
                        setTimeout(function () {
                            $('#area_enable').fadeOut('fast');
                        }, 2000);
                    }
                }
            })
        }
    } else if (area_status == 'sub_area') {
        if (confirm('Do you want to Enable this Sub Area?')) {
            $.ajax({
                url: 'areaStatus/enableDisableSubArea.php',
                data: { 'area_id': area_id, 'action': action },
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        dT2();
                        $('#sub_area_enable').show();
                        setTimeout(function () {
                            $('#sub_area_enable').fadeOut('fast');
                        }, 2000);
                    }
                }
            })
        }
    }
}

//For Disable
function disable(area_id) {
    var area_status = $('input[name=area_status]:checked').val();
    var action = "disable";
    if (area_status == 'area') {
        if (confirm('Do you want to Disable this Area?')) {
            $.ajax({
                url: 'areaStatus/enableDisableArea.php',
                data: { 'area_id': area_id, 'action': action },
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        dT1(); 
                        $('#area_disable').show();
                        setTimeout(function () {
                            $('#area_disable').fadeOut('fast');
                        }, 2000);
                    }
                }
            })
        }
    } else if (area_status == 'sub_area') {
        if (confirm('Do you want to Disable this Sub Area?')) {
            $.ajax({
                url: 'areaStatus/enableDisableSubArea.php',
                data: { 'area_id': area_id, 'action': action },
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        dT2();
                        $('#sub_area_disable').show();
                        setTimeout(function () {
                            $('#sub_area_disable').fadeOut('fast');
                        }, 2000);
                    }
                }
            })
        }
    }
}



