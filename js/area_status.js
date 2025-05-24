
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
    })

    // $('#filter').keyup(function(){
    //     // Retrieve the input field text and reset the count to zero
    //     var filter = $(this).val(), count = 0;
    //     // Loop through the comment list
    //     $("table tbody tr").each(function(){
    //         // If the list item does not contain the text phrase fade it out
    //         if ($(this).text().search(new RegExp(filter, "i")) < 0) {
    //             $(this).fadeOut();
    //         // Show the list item if the phrase matches and increase the count by 1
    //         } else {
    //             $(this).show();
    //             count++;
    //         }
    //     })
    // })

});//document ready end
function dT1() {
    var table = $('#sub_area_status_table').DataTable();
    table.destroy();
    $('#area_status_table').DataTable({
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
                title: "Area Status List"
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
}
function dT2() {
    var table = $('#area_status_table').DataTable();
    table.destroy();

    $('#sub_area_status_table').DataTable({
        "order": [[0, "desc"]],
        'ordering': false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxGetSubAreaFetch.php',
            'data': function (data) {
                var search = $('#search').val(); console.log(search);
                data.search = search;
            }
        },

        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Sub Area StatusList"
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
                        dT2();
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
                        dT1();
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
                        dT2();
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
                        dT1();
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



