$(document).ready(function () {

    //Scheme Type Change
    $('#monthly,#weekly,#daily').click(function () {
        var scheme_type = $('input[name=scheme_type]:checked').val();
        if (scheme_type == 'monthly') { $('.monthly_scheme').show(); $('.weekly_scheme').hide(); $('.daily_scheme').hide(); }
        if (scheme_type == 'weekly') { $('.monthly_scheme').hide(); $('.weekly_scheme').show(); $('.daily_scheme').hide(); }
        if (scheme_type == 'daily') { $('.monthly_scheme').hide(); $('.weekly_scheme').hide(); $('.daily_scheme').show(); }
    })

    {//To Order Alphabetically
        var firstOption = $("#loan_category option:first-child");
        $("#loan_category").html($("#loan_category option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#loan_category").prepend(firstOption);
    }
    {//To Order Alphabetically
        var firstOption = $("#loan_category1 option:first-child");
        $("#loan_category1").html($("#loan_category1 option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#loan_category1").prepend(firstOption);
    }
    {//To Order Alphabetically
        var firstOption = $("#loan_category2 option:first-child");
        $("#loan_category2").html($("#loan_category2 option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#loan_category2").prepend(firstOption);
    }

    // ***************************************************************************************************************************************************************************
    //Monthly Scheme
    // ***************************************************************************************************************************************************************************

    //change sub category based on Loan category
    $('.monthly_scheme #loan_category').change(function () {
        var loan_cat = $('#loan_category').val();
        getSubCategory(loan_cat);
    })

    //Due period calculation
    $('.monthly_scheme #total_due').keyup(function () {
        var total_due = $('#total_due').val();
        var advance_due = $('#advance_due').val();
        if (total_due != '' && advance_due == '') {
            $('#due_period').val(total_due);
        } else if (total_due != '' && advance_due != '') {
            var due_period = total_due - advance_due; console.log(due_period)
            $('#due_period').val(due_period);
        } else if (total_due == '' && advance_due == '') {
            $('#due_period').val('');
        } else if (total_due == '' && advance_due != '') {
            $('#due_period').val(advance_due);
        }
    })

    $('.monthly_scheme #advance_due').keyup(function () {
        var total_due = $('#total_due').val();
        var advance_due = $('#advance_due').val();
        if (total_due != '' && advance_due != '') {
            var due_period = total_due - advance_due;
            $('#due_period').val(due_period);
        } else if (total_due != '' && advance_due == '') {
            $('#due_period').val(total_due);
        } else if (total_due == '' && advance_due == '') {
            $('#due_period').val('');
        } else if (total_due == '' && advance_due != '') {
            $('#due_period').val(advance_due);
        }
    })

    // Amount or percentage change on fields
    $('.monthly_scheme #docamt,#docpercentage').click(function () {
        var doc_charge_type = $('input[name=doc_charge_type]:checked').val();
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin', 'docmax', 'doc_charge_min', 'doc_charge_max');
        }
        if (doc_charge_type == 'percentage') {
            changePercentinput('docmin', 'docmax', 'doc_charge_min', 'doc_charge_max');
        }
    })

    // Amount or percentage change on fields
    $('.monthly_scheme #procamt,#procpercentage').click(function () {
        var proc_fee_type = $('input[name=proc_fee_type]:checked').val();
        if (proc_fee_type == 'amt') {
            changeAmtinput('procmin', 'procmax', 'proc_fee_min', 'proc_fee_max');
        }
        if (proc_fee_type == 'percentage') {
            changePercentinput('procmin', 'procmax', 'proc_fee_min', 'proc_fee_max');
        }
    })
    $('.monthly_scheme #interestamt,#interestpercentage').click(function () {
        var intreset_type = $('input[name=intreset_type]:checked').val();
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin', 'intersetmax', 'intreset_min', 'intreset_max');
        }
        if (intreset_type == 'percentage') {
            changePercentinput('intresetmin', 'intersetmax', 'intreset_min', 'intreset_max');
        }
    })
    //Validation on submit
    $('#submit_loan_scheme_monthly').click(function () {
        var loan_category = $('#loan_category').val();
        var sub_category = $('#sub_category').val();
        var scheme_name = $('#scheme_name').val();
        // var intrest_rate = $('#intrest_rate').val();
        var total_due = $('#total_due').val();
        var profit_method = $('#profit_method').val();
        var profit_method = $('#profit_method').val();
        var intreset_type = $('#intreset_type').val();
        var intreset_min = $('#intreset_min').val();
        var intreset_max = $('#intreset_max').val();
        var doc_charge_type = $('#doc_charge_type').val();
        var doc_charge_min = $('#doc_charge_min').val();
        var doc_charge_max = $('#doc_charge_max').val();
        var proc_fee_type = $('#proc_fee_type').val();
        var proc_fee_min = $('#proc_fee_min').val();
        var proc_fee_max = $('#proc_fee_max').val();
        var due_date = $('#due_date').val();
        var grace_period = $('#grace_period').val();
        var overdue = $('#overdue').val();
        if (loan_category != '' && sub_category != '' && scheme_name != '' && intreset_type != '' && intreset_min != '' && intreset_max != '' && total_due != '' && doc_charge_type != '' && doc_charge_min != '' && doc_charge_max != '' && proc_fee_type != '' && proc_fee_min != '' && proc_fee_max != '' && due_date != '' && grace_period != '' && overdue != '' && profit_method != '' && profit_method != null) {
            return true;
        } else {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            return false;
        }

    })

    // ***************************************************************************************************************************************************************************
    //Weekly Scheme
    // ***************************************************************************************************************************************************************************

    //change sub category based on Loan category
    $('.weekly_scheme #loan_category1').change(function () {
        var loan_cat = $('#loan_category1').val();
        getSubCategory1(loan_cat);
    })

    // Amount or percentage change on fields
    $('.weekly_scheme #docamt1,#docpercentage1').click(function () {
        var doc_charge_type = $('input[name=doc_charge_type1]:checked').val();
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin1', 'docmax1', 'doc_charge_min1', 'doc_charge_max1');
        }
        if (doc_charge_type == 'percentage') {
            changePercentinput('docmin1', 'docmax1', 'doc_charge_min1', 'doc_charge_max1');
        }
    })

    // Amount or percentage change on fields
    $('.weekly_scheme #procamt1,#procpercentage1').click(function () {
        var proc_fee_type = $('input[name=proc_fee_type1]:checked').val();
        if (proc_fee_type == 'amt') {
            changeAmtinput('procmin1', 'procmax1', 'proc_fee_min1', 'proc_fee_max1');
        }
        if (proc_fee_type == 'percentage') {
            changePercentinput('procmin1', 'procmax1', 'proc_fee_min1', 'proc_fee_max1');
        }
    })
    $('.weekly_scheme #interestamt1,#interestpercentage1').click(function () {
        var intreset_type = $('input[name=intreset_type1]:checked').val();
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin1', 'intersetmax1', 'intreset_min1', 'intreset_max1');
        }
        if (intreset_type == 'percentage') {
            changePercentinput('intresetmin1', 'intersetmax1', 'intreset_min1', 'intreset_max1');
        }
    })
    //Validation on submit
    $('#submit_loan_scheme_weekly').click(function () {
        var loan_category = $('#loan_category1').val();
        var sub_category = $('#sub_category1').val();
        var scheme_name = $('#scheme_name1').val();
        // var intrest_rate = $('#intrest_rate1').val();
        var profit_method = $('#profit_method1').val();
        var due_period = $('#due_period1').val();
        var intreset_type = $('#intreset_type1').val();
        var intreset_min = $('#intreset_min1').val();
        var intreset_max = $('#intreset_max1').val();
        var doc_charge_type = $('#doc_charge_type1').val();
        var doc_charge_min = $('#doc_charge_min1').val();
        var doc_charge_max = $('#doc_charge_max1').val();
        var proc_fee_type = $('#proc_fee_type1').val();
        var proc_fee_min = $('#proc_fee_min1').val();
        var proc_fee_max = $('#proc_fee_max1').val();
        var due_day = $('#due_day').val();
        var overdue = $('#overdue1').val();
        if (loan_category != '' && sub_category != '' && scheme_name != '' && intreset_type != '' && intreset_min != '' && intreset_max != '' && due_period != '' && doc_charge_type != '' && doc_charge_min != '' && doc_charge_max != '' && proc_fee_type != '' && proc_fee_min != '' && proc_fee_max != '' && due_day != '' && overdue != '' && profit_method != '' && profit_method != null) {
            return true;
        } else {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            return false;
        }

    })

    // ***************************************************************************************************************************************************************************
    //Daily Scheme
    // ***************************************************************************************************************************************************************************

    //change sub category based on Loan category
    $('.daily_scheme #loan_category2').change(function () {
        var loan_cat = $('#loan_category2').val();
        getSubCategory2(loan_cat);
    })

    // Amount or percentage change on fields
    $('.daily_scheme #docamt2,#docpercentage2').click(function () {
        var doc_charge_type = $('input[name=doc_charge_type2]:checked').val();
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin2', 'docmax2', 'doc_charge_min2', 'doc_charge_max2');
        }
        if (doc_charge_type == 'percentage') {
            changePercentinput('docmin2', 'docmax2', 'doc_charge_min2', 'doc_charge_max2');
        }
    })
    $('.daily_scheme #interestamt2,#interestpercentage2').click(function () {
        var intreset_type = $('input[name=intreset_type2]:checked').val();
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin2', 'intersetmax2', 'intreset_min2', 'intreset_max2');
        }
        if (intreset_type == 'percentage') {
            changePercentinput('intresetmin2', 'intersetmax2', 'intreset_min2', 'intreset_max2');
        }
    })
    // Amount or percentage change on fields
    $('.daily_scheme #procamt2,#procpercentage2').click(function () {
        var proc_fee_type = $('input[name=proc_fee_type2]:checked').val();
        if (proc_fee_type == 'amt') {
            changeAmtinput('procmin2', 'procmax2', 'proc_fee_min2', 'proc_fee_max2');
        }
        if (proc_fee_type == 'percentage') {
            changePercentinput('procmin2', 'procmax2', 'proc_fee_min2', 'proc_fee_max2');
        }
    })

    //Validation on submit
    $('#submit_loan_scheme_daily').click(function () {
        var loan_category = $('#loan_category2').val();
        var sub_category = $('#sub_category2').val();
        var scheme_name = $('#scheme_name2').val();
        var intrest_rate = $('#intrest_rate2').val();
        var profit_method = $('#profit_method2').val();
        var due_period = $('#due_period2').val();
        var intreset_type = $('#intreset_type2').val();
        var intreset_min = $('#intreset_min2').val();
        var intreset_max = $('#intreset_max2').val();
        var doc_charge_type = $('#doc_charge_type2').val();
        var doc_charge_min = $('#doc_charge_min2').val();
        var doc_charge_max = $('#doc_charge_max2').val();
        var proc_fee_type = $('#proc_fee_type2').val();
        var proc_fee_min = $('#proc_fee_min2').val();
        var proc_fee_max = $('#proc_fee_max2').val();
        var overdue = $('#overdue2').val();
        if (loan_category != '' && sub_category != '' && scheme_name != '' && intreset_type != '' && intreset_min != '' && intreset_max != '' && due_period != '' && doc_charge_type != '' && doc_charge_min != '' && doc_charge_max != '' && proc_fee_type != '' && proc_fee_min != '' && proc_fee_max != '' && overdue != ''&& profit_method != '' && profit_method != null) {
            return true;
        } else {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            return false;
        }

    })

})//Ready state End

// var secondElement = new Choices('#sub_category', { allowSearch: true });


//autoload function for edit
$(function () {
    var scheme_id_upd = $('#scheme_id_upd').val();
    var due_method_upd = $('#due_method_upd').val();

    if (scheme_id_upd != '' && due_method_upd == 'monthly') {
        var loan_category_upd = $('#loan_category_upd').val();
        var doc_charge_type = $('#doc_charge_type_upd').val();
        var intreset_type = $('#intreset_type_upd').val();
        var proc_fee_type = $('#proc_type_upd').val();
        getSubCategory(loan_category_upd);

        //Scheme Type Change
        $('.monthly_scheme').show(); $('.weekly_scheme').hide(); $('.daily_scheme').hide();

        //for document charge
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin', 'docmax', 'doc_charge_min', 'doc_charge_max');
        } else if (doc_charge_type == 'percentage') {
            changePercentinput('docmin', 'docmax', 'doc_charge_min', 'doc_charge_max');
        }
        //for processing fee
        if (proc_fee_type == 'amt') {
            changeAmtinput('procmin', 'procmax', 'proc_fee_min', 'proc_fee_max');
        } else if (proc_fee_type == 'percentage') {
            changePercentinput('procmin', 'procmax', 'proc_fee_min', 'proc_fee_max');
        }
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin', 'intersetmax', 'intreset_min', 'intreset_max');
        } else if (intreset_type == 'percentage') {
            changePercentinput('intresetmin', 'intersetmax', 'intreset_min', 'intreset_max');
        }
    }
    if (scheme_id_upd != '' && due_method_upd == 'weekly') {
        var loan_category_upd = $('#loan_category1_upd').val();
        var doc_charge_type = $('#doc_charge_type1_upd').val();
        var intreset_type = $('#intreset_type1_upd').val();
        var proc_fee_type = $('#proc_type1_upd').val();
        getSubCategory1(loan_category_upd);

        //Scheme Type Change
        $('.monthly_scheme').hide(); $('.weekly_scheme').show(); $('.daily_scheme').hide();

        //for document charge
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin1', 'docmax1', 'doc_charge_min1', 'doc_charge_max1');
        } else if (doc_charge_type == 'percentage') {
            changePercentinput('docmin1', 'docmax1', 'doc_charge_min1', 'doc_charge_max1');
        }
        //for processing fee
        if (proc_fee_type == 'amt') {
            changeAmtinput('procmin1', 'procmax1', 'proc_fee_min1', 'proc_fee_max1');
        } else if (proc_fee_type == 'percentage') {
            changePercentinput('procmin1', 'procmax1', 'proc_fee_min1', 'proc_fee_max1');
        }
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin1', 'intersetmax1', 'intreset_min1', 'intreset_max1');
        } else if (intreset_type == 'percentage') {
            changePercentinput('intresetmin1', 'intersetmax1', 'intreset_min1', 'intreset_max1');
        }
    }
    if (scheme_id_upd != '' && due_method_upd == 'daily') {
        var loan_category_upd = $('#loan_category2_upd').val();
        var doc_charge_type = $('#doc_charge_type2_upd').val();
        var intreset_type = $('#intreset_type2_upd').val();
        var proc_fee_type = $('#proc_type2_upd').val();
        getSubCategory2(loan_category_upd);

        //Scheme Type Change
        $('.monthly_scheme').hide(); $('.weekly_scheme').hide(); $('.daily_scheme').show();

        //for document charge
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin2', 'docmax2', 'doc_charge_min2', 'doc_charge_max2');
        } else if (doc_charge_type == 'percentage') {
            changePercentinput('docmin2', 'docmax2', 'doc_charge_min2', 'doc_charge_max2');
        }
        //for processing fee
        if (doc_charge_type == 'amt') {
            changeAmtinput('procmin2', 'procmax2', 'proc_fee_min2', 'proc_fee_max2');
        } else if (doc_charge_type == 'percentage') {
            changePercentinput('procmin2', 'procmax2', 'proc_fee_min2', 'proc_fee_max2');
        }
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin2', 'intersetmax2', 'intreset_min2', 'intreset_max2');
        } else if (intreset_type == 'percentage') {
            changePercentinput('intresetmin2', 'intersetmax2', 'intreset_min2', 'intreset_max2');
        }
    }
});

//Fetch Sub Category Based on loan category for Monthly
function getSubCategory(loan_cat) {
    var sub_category_upd = $('#sub_category_upd').val()
    $.ajax({
        url: 'loanCalculationFile/getLoanSubCategory.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: { 'loan_cat': loan_cat },
        success: function (response) {

            // secondElement.clearStore();

            $('#sub_category').empty();
            $('#sub_category').append(`<option value=''>Select Sub Category</option>`);
            for (var i = 0; i < response.length; i++) {
                if (response[i]['sub_category_name'] != '' && response[i]['sub_category_name'] != null) {
                    var selected = '';
                    if (sub_category_upd == response[i]['sub_category_name']) {
                        selected = 'selected';
                    }
                    // var items= [
                    //     {
                    //     value: response[i]['sub_category_name'],
                    //     label: response[i]['sub_category_name'],
                    //     selected: false,
                    //     disabled: false,
                    //   }
                    // ];
                    // secondElement.setChoices(items);
                    // secondElement.init();
                    $('#sub_category').append("<option value= '" + response[i]['sub_category_name'] + "' " + selected + " > " + response[i]['sub_category_name'] + " </option>")
                }
            }
            {//To Order Alphabetically
                var firstOption = $("#sub_category option:first-child");
                $("#sub_category").html($("#sub_category option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#sub_category").prepend(firstOption);
            }
        }
    })
}
//Fetch Sub Category Based on loan category for Weekly
function getSubCategory1(loan_cat) {
    var sub_category_upd = $('#sub_category1_upd').val()
    $.ajax({
        url: 'loanCalculationFile/getLoanSubCategory.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: { 'loan_cat': loan_cat },
        success: function (response) {
            $('#sub_category1').empty();
            $('#sub_category1').append(`<option value=''>Select Sub Category</option>`);
            for (var i = 0; i < response.length; i++) {
                if (response[i]['sub_category_name'] != '' && response[i]['sub_category_name'] != null) {
                    var selected = '';
                    if (sub_category_upd == response[i]['sub_category_name']) {
                        selected = 'selected';
                    }
                    $('#sub_category1').append("<option value= '" + response[i]['sub_category_name'] + "' " + selected + " > " + response[i]['sub_category_name'] + " </option>")
                }
            }
            {//To Order Alphabetically
                var firstOption = $("#sub_category1 option:first-child");
                $("#sub_category1").html($("#sub_category1 option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#sub_category1").prepend(firstOption);
            }
        }
    })
}
//Fetch Sub Category Based on loan category for Daily
function getSubCategory2(loan_cat) {
    var sub_category_upd = $('#sub_category2_upd').val()
    $.ajax({
        url: 'loanCalculationFile/getLoanSubCategory.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: { 'loan_cat': loan_cat },
        success: function (response) {
            $('#sub_category2').empty();
            $('#sub_category2').append(`<option value=''>Select Sub Category</option>`);
            for (var i = 0; i < response.length; i++) {
                if (response[i]['sub_category_name'] != '' && response[i]['sub_category_name'] != null) {
                    var selected = '';
                    if (sub_category_upd == response[i]['sub_category_name']) {
                        selected = 'selected';
                    }
                    $('#sub_category2').append("<option value= '" + response[i]['sub_category_name'] + "' " + selected + " > " + response[i]['sub_category_name'] + " </option>")
                }
            }
            {//To Order Alphabetically
                var firstOption = $("#sub_category2 option:first-child");
                $("#sub_category2").html($("#sub_category2 option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#sub_category2").prepend(firstOption);
            }
        }
    })
}


//Change Document charge & Processing fee input field not readonly
function changeAmtinput(docmin, docmax, doc_charge_min, doc_charge_max) {
    $('#' + docmin).text('Min ₹');
    $('#' + docmax).text('Max ₹');
    $('#' + doc_charge_min).attr('readonly', false);
    $('#' + doc_charge_max).attr('readonly', false);
}
//Change Document charge & Processing fee input field not readonly
function changePercentinput(docmin, docmax, doc_charge_min, doc_charge_max) {
    $('#' + docmin).text('Min %');
    $('#' + docmax).text('Max %');
    $('#' + doc_charge_min).attr('readonly', false);
    $('#' + doc_charge_max).attr('readonly', false);
}