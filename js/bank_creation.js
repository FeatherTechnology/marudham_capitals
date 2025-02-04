const branchMultiselect = new Choices('#under_branch_dummy', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Branch Name',
    allowHTML: true
});


$(document).ready(function () {

    var companySelected = $('#company').val();
    getBranchDropdown(companySelected);

    $('#submit_bank_creation').click(function () {

        //Branch Multi select store
        var branch_list = branchMultiselect.getValue();
        var branch = '';
        for (var i = 0; i < branch_list.length; i++) {
            if (i > 0) {
                branch += ',';
            }
            branch += branch_list[i].value;
        }
        var arr = branch.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#under_branch').val(sortedStr);

        validations();
    })

})//Document ready END

//Auto call function
$(function () {
    var idupd = $('#idupd').val();
    if (idupd > 0) {
        var company_id = $('#company').val();
        getBranchDropdown(company_id);
    }
})


//Get BranchDropdown Based on Company id
function getBranchDropdown(company_id) {
    var branch_id_upd = $('#under_branch_upd').val();
    var values = branch_id_upd.split(',');
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {
            branchMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {

                var branch_id = response[i]['branch_id'];
                var branch_name = response[i]['branch_name'];
                var selected = '';
                if (branch_id_upd != '' && values.includes(branch_id)) {
                    selected = 'selected';
                    // checked = false;
                }
                var items = [
                    {
                        value: branch_id,
                        label: branch_name,
                        selected: selected,
                    }
                ];
                branchMultiselect.setChoices(items);
                branchMultiselect.init();
            }
        }
    })
}


function validations() {
    var bank_name = $('#bank_name').val(); var short_name = $('#short_name').val(); var acc_no = $('#acc_no').val(); var ifsc = $('#ifsc').val(); var branch = $('#branch').val();
    var under_branch = branchMultiselect.getValue();

    if (bank_name == '') {
        $('#banknameCheck').show();
        event.preventDefault();
    } else {
        $('#banknameCheck').hide();
    }
    if (short_name == '') {
        $('#shortnameCheck').show();
        event.preventDefault();
    } else {
        $('#shortnameCheck').hide();
    }
    if (acc_no == '') {
        $('#accnoCheck').show();
        event.preventDefault();
    } else {
        $('#accnoCheck').hide();
    }
    if (ifsc == '') {
        $('#ifscCheck').show();
        event.preventDefault();
    } else {
        $('#ifscCheck').hide();
    }
    if (branch == '') {
        $('#branchCheck').show();
        event.preventDefault();
    } else {
        $('#branchCheck').hide();
    }
    if (under_branch.length == 0) {
        // $('.choices__inner').attr('style','border-color:red');
        $('#underbranchCheck').show();
        event.preventDefault();
    } else {
        $('#underbranchCheck').hide();
    }
}