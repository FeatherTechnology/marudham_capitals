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
const areaMultiselect2 = new Choices('#area_dummy2', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true,
    shouldSort: false
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

    // Sort area dropdown
    sortDropdownAlphabetically("#area");

    // Sort area1 dropdown
    sortDropdownAlphabetically("#area1");

    // Sort area2 dropdown
    sortDropdownAlphabetically("#area2");

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

    //on submit add sub area list to hidden input
    $('#submit_area_mapping_line').click(function (event) {

        if (validateLineAreaMappingForm()) {
            // Ask confirmation only if validation passed
            let confirmAction = confirm("Are you sure you want to submit Line area mapping?");
            if (!confirmAction) {
                event.preventDefault(); // stop default form submission
                return false; // user canceled
            }
        } else {
            event.preventDefault(); // stop default form submission
            return false;
        }
    });


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

    //on submit add sub area list to hidden input
    $('#submit_area_mapping_group').click(function (event) {

        if (validateGroupAreaMappingForm()) {
            let confirmAction = confirm("Are you sure you want to submit Group Area Mapping?");
            if (!confirmAction) {
                event.preventDefault();
                return false; // user cancelled
            }
        } else {
            event.preventDefault();
            return false;
        }
    });



    // ************************************************************** Due Followup Mapping ****************************************************************** 

    $('#area_dummy2').change(function () {
        // Get values from multiselect and sort
        const area_list = areaMultiselect2.getValue(); // returns array of objects like [{value: "1", label: "Area 1"}, ...]

        // Handle Select All logic
        selectAllAreas(area_list, areaMultiselect2);

        const sortedStr = areaMultiselect2.getValue()
            .map(item => item.value)
            .filter(val => val !== 'select_all') // exclude 'select_all' from final string
            .sort((a, b) => a - b)
            .join(',');

        $('#area2').val(sortedStr);
    });

    $('.refresh_count').click(function (event) {
        event.preventDefault();
        getCusLoanCount();
    });

    //on submit add sub area list to hidden input
    $('#submit_area_mapping_duefollowup').click(function (event) {

        if (validateDueFollowupForm()) {
            // Ask confirmation only if validation passed
            let confirmAction = confirm("Are you sure you want to submit Due Followup?");
            if (!confirmAction) {
                event.preventDefault();
                return false;
            }
        } else {
            event.preventDefault();
            return false;
        }
    });

});//document ready end

//on page load for Edit page
$(function () {
    let type = $('#type').val();
    if (type == 'line') {
        getArea();
        let area = $('#area_id_upd').val();
        getAreaBasedSubArea(area);
        getBranchDropdown()
    } else if (type == 'group') {
        getArea1();
        let area = $('#area_id1_upd').val();
        getAreaBasedSubArea1(area);
        getBranchDropdown1();
    } else if (type == 'duefollowup') {
        initform();

        async function initform() {
            await getBranchDropdown2();
            await getArea2();
        }
    }
});

//Get Area 
function getArea() {
    var area_id_upd = $('#area_id_upd').val();
    var values = area_id_upd.split(',');
    var map = 'line';
    $.ajax({
        url: 'areaMapping/ajaxGetArea.php',
        type: 'post',
        data: { 'map': map, 'area_id_upd': area_id_upd },
        dataType: 'json',
        success: function (response) {

            areaMultiselect.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var selected = '';
                if (area_id_upd != '' && values.includes(area_id.toString())) {
                    selected = 'selected';
                }

                var items = [
                    {
                        value: area_id,
                        label: area_name,
                        selected: selected
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
        data: { 'map': map, 'area_id_upd': area_id_upd },
        dataType: 'json',
        success: function (response) {

            areaMultiselect1.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var selected = '';
                if (area_id_upd != '' && values.includes(area_id.toString())) {
                    selected = 'selected';
                }

                var items = [
                    {
                        value: area_id,
                        label: area_name,
                        selected: selected
                    }
                ];
                areaMultiselect1.setChoices(items);
                areaMultiselect1.init();
            }
        }
    });
}

//Get Area 
function getArea2() {
    return new Promise((resolve, reject) => {

        var area_id_upd = $('#area_id2_upd').val();
        var areaid = $('#area2').val();
        var values = area_id_upd.split(',');

        $.ajax({
            url: 'areaMapping/ajaxGetMappedArea.php',
            type: 'post',
            data: { area_id_upd },
            dataType: 'json',
            success: function (response) {
                areaMultiselect2.clearStore();

                // Start with "Select All" manually
                var items = [
                    {
                        value: 'select_all',
                        label: 'Select All',
                        selected: '',
                        disabled: ''
                    }
                ];

                var len = response.length;
                var areaItems = [];

                for (var i = 0; i < len; i++) {
                    var area_id = response[i]['area_id'];
                    var area_name = response[i]['area_name'];
                    var selected = '';

                    if (area_id_upd && values.includes(area_id.toString())) {
                        selected = 'selected';
                    }
                    if (areaid && areaid.includes(area_id.toString())) {
                        selected = 'selected';
                    }

                    areaItems.push({
                        value: area_id,
                        label: area_name,
                        selected: selected
                    });
                }

                // Sort the area items alphabetically by label
                areaItems.sort(function (a, b) {
                    return a.label.localeCompare(b.label);
                });

                // Merge "Select All" with sorted area items
                items = items.concat(areaItems);

                areaMultiselect2.setChoices(items, 'value', 'label', true);
                resolve(); // Notify completion
            },
            error: function (xhr, status, error) {
                reject(error); // Handle errors
            }
        });
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
        data: { 'area': area, 'map': map, 'sub_area_upd': sub_area_upd },
        dataType: 'json',
        success: function (response) {

            intance.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < response[i].length; j++) {

                    var sub_area_id = response[i][j]['sub_area_id'];
                    var sub_area_name = response[i][j]['sub_area_name'];
                    var selected = '';
                    if (sub_area_upd != '' && values.includes(sub_area_id.toString())) {
                        selected = 'selected';
                    }

                    var items = [
                        {
                            value: sub_area_id,
                            label: sub_area_name,
                            selected: selected
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
        data: { 'area': area, 'map': map, 'sub_area_upd': sub_area_upd },
        dataType: 'json',
        success: function (response) {

            intance1.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < response[i].length; j++) {

                    var sub_area_id = response[i][j]['sub_area_id'];
                    var sub_area_name = response[i][j]['sub_area_name'];
                    var selected = '';
                    if (sub_area_upd != '' && values.includes(sub_area_id.toString())) {
                        selected = 'selected';
                    }

                    var items = [
                        {
                            value: sub_area_id,
                            label: sub_area_name,
                            selected: selected
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
function getBranchDropdown() {
    var branch_id_upd = $('#branch_id_upd').val();
    var company_id = (!$('#company_id_upd').val()) ? $('#company_id').val() : $('#company_id_upd').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {
            $('#branch').empty();
            $('#branch').append('<option value="">Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch').append("<option value ='" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            // Sort branch dropdown
            sortDropdownAlphabetically("#branch");
        }
    })
}

//Get BranchDropdown Based on Company id
function getBranchDropdown1() {
    var branch_id_upd = $('#branch_id_upd1').val();
    var company_id = (!$('#company_id_upd1').val()) ? $('#company_id1').val() : $('#company_id_upd1').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {

            $('#branch1').empty();
            $('#branch1').append('<option value="">Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch1').append("<option value = '" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            // Sort branch1 dropdown
            sortDropdownAlphabetically("#branch1");
        }
    })
}

//Get BranchDropdown Based on Company id
function getBranchDropdown2() {
    return new Promise((resolve, reject) => {
        var branch_id_upd = $('#branch_id_upd2').val();
        var company_id = (!$('#company_id_upd2').val()) ? $('#company_id2').val() : $('#company_id_upd2').val();
        $.ajax({
            url: 'areaMapping/getBranchDropdown.php',
            type: 'post',
            dataType: 'json',
            data: { 'company_id': company_id },
            cache: false,
            success: function (response) {
                $('#branch2').empty();
                $('#branch2').append('<option value="">Select Branch</option>');
                for (var i = 0; i < response.length; i++) {
                    var selected = '';
                    if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                        selected = "selected";
                    }
                    $('#branch2').append("<option value = '" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
                }
                // Sort branch2 dropdown
                sortDropdownAlphabetically("#branch2");

                resolve(); // Notify completion
            },
            error: function (xhr, status, error) {
                reject(error); // Handle errors
            }
        });
    });
}

function getCusLoanCount() {
    let type = $('#type').val();
    let areaid = '';

    if (type == 'line') {
        areaid = areaMultiselect.getValue()
            .map(item => item.value)
            .filter(val => val !== 'select_all')
            .sort((a, b) => a - b)
            .join(',');

    } else if (type == 'group') {
        areaid = areaMultiselect1.getValue()
            .map(item => item.value)
            .filter(val => val !== 'select_all')
            .sort((a, b) => a - b)
            .join(',');

    } else if (type == 'duefollowup') {
        areaid = areaMultiselect2.getValue()
            .map(item => item.value)
            .filter(val => val !== 'select_all')
            .sort((a, b) => a - b)
            .join(',');
    }

    if (areaid) {
        $.post('areaMapping/getCusAndLoanCount.php', { areaid }, function (response) {
            // Parse JSON if needed
            let cusCnt = response.cus_count ? response.cus_count : 0;
            let loanCnt = response.loan_count ? response.loan_count : 0;
            if (type == 'line') {
                $('#cus_count1').val(cusCnt);
                $('#loan_count1').val(loanCnt);
            } else if (type == 'group') {
                $('#cus_count2').val(cusCnt);
                $('#loan_count2').val(loanCnt);
            } else if (type == 'duefollowup') {
                $('#cus_count').val(cusCnt);
                $('#loan_count').val(loanCnt);
            }
        }, 'json');
    }

}

function getSortedCommaSeparatedValues(multiselectInstance) {
    const selected = multiselectInstance.getValue();
    const values = selected.map(item => parseInt(item.value, 10));
    values.sort((a, b) => a - b);
    return values.join(',');
}

function selectAllAreas(selectedList, choicesInstance) {
    const selectedValues = selectedList.map(item => item.value);

    if (selectedValues.includes('select_all')) {
        const allChoices = choicesInstance._store.choices
            .filter(choice => choice.value !== 'select_all' && !choice.disabled);

        const allValues = allChoices.map(choice => choice.value);

        const alreadySelectedAll = allValues.every(val => selectedValues.includes(val));

        // Remove current selections
        choicesInstance.removeActiveItems();

        if (!alreadySelectedAll) {
            // Select all except 'select_all'
            choicesInstance.setChoiceByValue(allValues);
        }
    }
}

function validateLineAreaMappingForm() {
    var line_name = $('#line_name').val().trim();
    var company_name = $('#company_name').val().trim();
    var branch = $('#branch').val().trim();
    var area_list = areaMultiselect.getValue();
    var sub_area_list = intance.getValue();

    if (line_name === '' || company_name === '' || branch === '' || area_list.length === 0 || sub_area_list.length === 0) {
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Fill out Mandatory fields!',
            icon: 'error',
            showConfirmButton: true
        });
        return false; // validation failed
    }
    // Process area multi-select
    var area = area_list.map(item => item.value);
    area.sort((a, b) => a - b); // sort numerically
    $('#area').val(area.join(','));

    // Process sub-area multi-select
    var sub_area = sub_area_list.map(item => item.value);
    sub_area.sort((a, b) => a - b); // sort numerically
    $('#sub_area').val(sub_area.join(','));

    return true; // validation passed
}

function validateGroupAreaMappingForm() {
    var group_name = $('#group_name').val().trim();
    var company_name = $('#company_name1').val().trim();
    var branch = $('#branch1').val().trim();
    var area_list = areaMultiselect1.getValue();
    var sub_area_list = intance1.getValue();

    if (group_name === '' || company_name === '' || branch === '' || area_list.length === 0 || sub_area_list.length === 0) {
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Fill out Mandatory fields!',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonColor: '#009688'
        });
        return false; // validation failed
    }

    // Process area multi-select
    var area = area_list.map(item => item.value);
    area.sort((a, b) => a - b); // numeric sort
    $('#area1').val(area.join(','));

    // Process sub-area multi-select
    var sub_area = sub_area_list.map(item => item.value);
    sub_area.sort((a, b) => a - b); // numeric sort
    $('#sub_area1').val(sub_area.join(','));

    return true; // validation passed
}

function validateDueFollowupForm() {
    var duefollowup_name = $('#duefollowup_name').val().trim();
    var company_name = $('#company_name2').val().trim();
    var branch = $('#branch2').val().trim();
    var cuscnt = $('#cus_count').val().trim();
    var loancnt = $('#loan_count').val().trim();
    var area_list = areaMultiselect2.getValue();

    if (
        duefollowup_name === '' ||
        company_name === '' ||
        branch === '' ||
        area_list.length === 0 ||
        cuscnt === '' ||
        loancnt === ''
    ) {
        Swal.fire({
            timerProgressBar: true,
            title: 'Please Fill out Mandatory fields!',
            icon: 'error',
            showConfirmButton: true
        });
        return false; // validation failed
    }

    // Process area multi-select
    const area = area_list.map(item => item.value);
    area.sort((a, b) => a - b); // numeric sort
    $('#area2').val(area.join(','));

    return true; // validation passed
}


