$(document).ready(function () {
    $('#ag_view_type').change(function () {
        var view_type = $(this).val();
        if (view_type == 1) { //overall
            $('#ag_namewise').hide().val('');
            getAgBalancesheet();
        } else if (view_type == 2) { //agent wise
            $('#ag_namewise').show();
            getAgentName('ag_namewise');
        } else {
            $('#ag_namewise').hide().val('');
        }
    });

    $('#ag_namewise').change(function () {
        var ag_name = $(this).val();
        if (ag_name != '') {
            getAgBalancesheet();
        }
    });

});//Document Ready End

function getAgentName() {
    $.ajax({
        url: 'accountsFile/cashtally/agent/getAgentName.php',
        data: {},
        dataType: 'json',
        type: 'POST',
        cache: false,
        success: function (response) {
            $('#ag_namewise').empty().append("<option value=''>Select Agent Name</option>");
            $.each(response, function (index, item) {
                $('#ag_namewise').append("<option value='" + item['ag_id'] + "'>" + item['ag_name'] + "</option> ")
            });
        }
    })
}

function getAgBalancesheet() {
    var view_type = $('#ag_view_type').val();//overall/Agent wise
    var ag_name = $('#ag_namewise').val();//show by agent name wise
    var sheet_type = "7"; //agent balance sheet
    var op_date = moment().format('YYYY-MM-DD'); //current date
    $.ajax({
        url: 'accountsFile/cashtally/contra/getBalanceSheet.php',
        data: { 'sheet_type': sheet_type, 'ag_view_type': view_type, 'ag_name': ag_name, 'op_date': op_date },
        type: 'POST',
        cache: false,
        success: function (response) {
            $('.blncSheetDiv').empty().html(response)
        }
    })
}