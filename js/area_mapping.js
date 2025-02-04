//Sub Area Multi select initialization
const areaMultiselect = new Choices('#area_dummy', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true
});
const areaMultiselect1 = new Choices('#area_dummy1', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true
});
const intance = new Choices('#sub_area_dummy', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Sub Area Name',
    allowHTML: true
});
const intance1 = new Choices('#sub_area_dummy1', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Sub Area Name',
    allowHTML: true
});


// Document is ready
$(document).ready(function () {

    //Mapping Type Change
    $('#line,#group').click(function () {
        var mapping_type = $('input[name=mapping_type]:checked').val();
        if (mapping_type == 'line') { $('.line_mapping').show(); $('.group_mapping').hide(); }
        if (mapping_type == 'group') { $('.line_mapping').hide(); $('.group_mapping').show(); }
    })

    {//To Order Alphabetically
        var firstOption = $("#area option:first-child");
        $("#area").html($("#area option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area").prepend(firstOption);
    }
    {//To Order Alphabetically
        var firstOption = $("#area1 option:first-child");
        $("#area1").html($("#area1 option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area1").prepend(firstOption);
    }

    // ************************************************************** Line Mapping *************************************************************************************** 



    $('#area_dummy').change(function () {
        //Area Multi select store
        var area_list = areaMultiselect.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area').val(sortedStr);
        var areaselected = $('#area').val();

        getAreaBasedSubArea(areaselected);
    })

    if ($('#type').val() == 'line') { // loan only if line
        var companySelected = $('#company_id').val();
        getBranchDropdown(companySelected);
    }

    //on submit add sub area list to hidden input
    $('#submit_area_mapping_line').click(function () {
        var sub_area_list = intance.getValue();
        var area_list = areaMultiselect.getValue();

        //Validation
        var line_name = $('#line_name').val(); var company_name = $('#company_name').val(); var branch = $('#branch').val(); var area = $('#area').val();
        if (line_name == '' || company_name == '' || branch == '' || area_list.length == 0 || sub_area_list.length == 0) {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true
            });
            event.preventDefault();
        }

        //Area Multi select store
        var area_list = areaMultiselect.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area').val(sortedStr);

        //Sub area multi select store
        var sub_area_list = intance.getValue();
        var sub_area = '';
        for (var i = 0; i < sub_area_list.length; i++) {
            if (i > 0) {
                sub_area += ',';
            }
            sub_area += sub_area_list[i].value;
        }
        var arr = sub_area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#sub_area').val(sortedStr);
    })

    // ************************************************************** Group Mapping *************************************************************************************** 

    $('#area_dummy1').change(function () {
        //Area Multi select store
        var area_list = areaMultiselect1.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area1').val(sortedStr);
        var areaselected = $('#area1').val();

        getAreaBasedSubArea1(areaselected);
    });

    // $('#company_id1').change(function(){
    if ($('#type').val() == 'group') {
        var companySelected = $('#company_id1').val();
        getBranchDropdown1(companySelected);
    }
    // })
    //on submit add sub area list to hidden input
    $('#submit_area_mapping_group').click(function () {
        var sub_area_list = intance1.getValue();
        var area_list = areaMultiselect1.getValue();
        //Validation
        var group_name = $('#group_name').val(); var company_name = $('#company_name1').val(); var branch = $('#branch1').val();
        if (group_name == '' || company_name == '' || branch == '' || area_list.length == 0 || sub_area_list.length == 0) {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            event.preventDefault();
        }
        //Area Multi select store
        var area_list = areaMultiselect1.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area1').val(sortedStr);

        //Sub Area Multi select store
        var sub_area_list = intance1.getValue();
        var sub_area = '';
        for (var i = 0; i < sub_area_list.length; i++) {
            if (i > 0) {
                sub_area += ',';
            }
            sub_area += sub_area_list[i].value;
        }
        var arr = sub_area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#sub_area1').val(sortedStr);
    })

});//document ready end

//on page load for Edit page
$(function () {
    let map_id = $('#map_id_upd').val();
    let map_id1 = $('#map_id1_upd').val();
    let type = $('#type').val();
    if (type == 'line') {
        getArea();
        let area = $('#area_id_upd').val();
        let company_id_upd = $('#company_id_upd').val();
        getAreaBasedSubArea(area);
        getBranchDropdown(company_id_upd)
    } else if (type == 'group') {
        getArea1();
        let area = $('#area_id1_upd').val();
        let company_id_upd = $('#company_id_upd1').val();
        getAreaBasedSubArea1(area);
        getBranchDropdown1(company_id_upd)
    }

})

//Get Area 
function getArea() {
    var area_id_upd = $('#area_id_upd').val();
    var values = area_id_upd.split(',');
    var map = 'line';
    $.ajax({
        url: 'areaMapping/ajaxGetArea.php',
        type: 'post',
        data: { 'map': map },
        dataType: 'json',
        success: function (response) {

            areaMultiselect.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var checked = '';
                var checked = response[i]['disabled'];
                var selected = '';
                if (area_id_upd != '' && values.includes(area_id)) {
                    selected = 'selected';
                    checked = false;
                }
                var items = [
                    {
                        value: area_id,
                        label: area_name,
                        selected: selected,
                        disabled: checked,
                    }
                ];
                areaMultiselect.setChoices(items);
                areaMultiselect.init();

            }
        }
    });
}

//Get Area 
function getArea1() {
    var area_id_upd = $('#area_id1_upd').val();
    var values = area_id_upd.split(',');
    var map = 'group';
    $.ajax({
        url: 'areaMapping/ajaxGetArea.php',
        type: 'post',
        data: { 'map': map },
        dataType: 'json',
        success: function (response) {

            areaMultiselect1.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var checked = '';
                var checked = response[i]['disabled'];
                var selected = '';
                if (area_id_upd != '' && values.includes(area_id)) {
                    selected = 'selected';
                    checked = false;
                }
                var items = [
                    {
                        value: area_id,
                        label: area_name,
                        selected: selected,
                        disabled: checked,
                    }
                ];
                areaMultiselect1.setChoices(items);
                areaMultiselect1.init();

            }
        }
    });
}

//Get Area Based Sub Area
function getAreaBasedSubArea(area) {
    var sub_area_upd = $('#sub_area_upd').val();
    var values = sub_area_upd.split(',');
    var map = 'line';
    $.ajax({
        url: 'areaMapping/ajaxGetSubArea.php',
        type: 'post',
        data: { 'area': area, 'map': map },
        dataType: 'json',
        success: function (response) {

            intance.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < response[i].length; j++) {

                    var sub_area_id = response[i][j]['sub_area_id'];
                    var sub_area_name = response[i][j]['sub_area_name'];
                    var checked = response[i][j]['disabled'];
                    var selected = '';
                    if (sub_area_upd != '' && values.includes(sub_area_id)) {
                        selected = 'selected';
                        checked = false;
                    }
                    var items = [
                        {
                            value: sub_area_id,
                            label: sub_area_name,
                            selected: selected,
                            disabled: checked,
                        }
                    ];
                    intance.setChoices(items);
                    intance.init();
                }

            }
        }
    });
}
//Get Area Based Sub Area
function getAreaBasedSubArea1(area) {
    var sub_area_upd = $('#sub_area_upd1').val();
    var values = sub_area_upd.split(',');
    var map = 'group';
    $.ajax({
        url: 'areaMapping/ajaxGetSubArea.php',
        type: 'post',
        data: { 'area': area, 'map': map },
        dataType: 'json',
        success: function (response) {

            intance1.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < response[i].length; j++) {

                    var sub_area_id = response[i][j]['sub_area_id'];
                    var sub_area_name = response[i][j]['sub_area_name'];
                    var checked = response[i][j]['disabled'];
                    var selected = '';
                    if (sub_area_upd != '' && values.includes(sub_area_id)) {
                        selected = 'selected';
                        checked = false;
                    }
                    var items = [
                        {
                            value: sub_area_id,
                            label: sub_area_name,
                            selected: selected,
                            disabled: checked,
                        }
                    ];
                    intance1.setChoices(items);
                    intance1.init();
                }

            }
        }
    });
}

//Get BranchDropdown Based on Company id
function getBranchDropdown(company_id) {
    var branch_id_upd = $('#branch_id_upd').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {
            $('#branch').empty();
            $('#branch').append('<option>Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch').append("<option value ='" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            {//To Order Alphabetically
                var firstOption = $("#branch option:first-child");
                $("#branch").html($("#branch option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#branch").prepend(firstOption);
            }
        }
    })
}
//Get BranchDropdown Based on Company id
function getBranchDropdown1(company_id) {
    var branch_id_upd = $('#branch_id_upd1').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {

            $('#branch1').empty();
            $('#branch1').append('<option>Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch1').append("<option value = '" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            {//To Order Alphabetically
                var firstOption = $("#branch1 option:first-child");
                $("#branch1").html($("#branch1 option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#branch1").prepend(firstOption);
            }
        }
    })
}