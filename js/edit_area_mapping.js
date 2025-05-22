// Document is ready
$(document).ready(function () {
    //Mapping Type Change
    $('#line,#group, #due_followup').click(function () {
        var mapping_type = $('input[name=mapping_type]:checked').val();
        if (mapping_type == 'line') {
            $('.line_mapping').show(); $('.group_mapping').hide();$('.due_followup_mapping').hide();
            dT1();
        }
        if (mapping_type == 'group') {
            $('.line_mapping').hide(); $('.group_mapping').show();$('.due_followup_mapping').hide();
            dT2();
        }
        if (mapping_type == 'duefollowup') {
            $('.line_mapping').hide(); $('.group_mapping').hide();$('.due_followup_mapping').show();
            dT3();
        }
    })
});//document ready end

$(function () {
    var mapping_type = $('input[name=mapping_type]:checked').val();
    if (mapping_type == 'line') {
        dT1();
    } else if (mapping_type == 'group') {
        dT2();
    } else if (mapping_type == 'duefollowup') {
        dT3();
    }
})

function dT1() {
    $('#area_mapping_line_info').DataTable().destroy();
    $('#area_mapping_line_info').empty();
    $('#area_mapping_line_info').append(`<thead><tr><th width="50">S. No.</th><th>Line Name</th><th>Company Name</th><th>Branch Name</th><th>Area Name</th><th>Sub Area</th><th>Status</th><th>Action</th></tr></thead><tbody></tbody>`);

    $('#area_mapping_line_info').DataTable({

        "order": [[0, "asc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxAreaMappingLineFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Line List"
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
        "drawCallback": function () {
            searchFunction('area_mapping_line_info');
            paginationFunction('area_mapping_line_info');
        }
    });
}

function dT2(){
    $('#area_mapping_group_info').DataTable().destroy();
    $('#area_mapping_group_info').empty();
    $('#area_mapping_group_info').append(`<thead><tr><th width="50">S. No.</th><th>Group Name</th><th>Company Name</th><th>Branch Name</th><th>Area Name</th><th>Sub Area</th><th>Status</th><th>Action</th></tr></thead><tbody></tbody>`);

    $('#area_mapping_group_info').DataTable({
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxAreaMappingGroupFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },

        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Group List"
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
        "drawCallback": function () {
            searchFunction('area_mapping_group_info');
            paginationFunction('area_mapping_group_info');
        }
    });
}

function dT3() {
    $('#area_mapping_duefollowup_info').DataTable().destroy();
    $('#area_mapping_duefollowup_info').empty();
    $('#area_mapping_duefollowup_info').append(`<thead><tr><th width="50">S. No.</th><th>Due Followup Name</th><th>Company Name</th><th>Branch Name</th><th>Area Name</th><th>Status</th><th>Action</th></tr></thead><tbody></tbody>`);

    $('#area_mapping_duefollowup_info').DataTable({
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxFetch/ajaxAreaMappingDuefollowupFetch.php',
            'data': function (data) {
                var search = $('#search').val();
                data.search = search;
            }
        },

        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Group List"
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
        "drawCallback": function () {
            searchFunction('area_mapping_duefollowup_info');
            paginationFunction('area_mapping_duefollowup_info');
        }
    });
}

