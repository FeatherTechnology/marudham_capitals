$(document).ready(function () {
    getUserNames();
    $('#reset_btn').click(function () {
        if($('#to_date').val() == "" || $('#by_user').val() == ""){
            alert('Please Select The Search Date And User');
        }else{
            cashTallyActivity();
        }
    });



});

function cashTallyActivity(){
   var search_date = $('#to_date').val();
   var insert_login_id = $('#by_user').val();

    $.ajax({
        url: 'reportFile/cashtally_activity/getCashTallyActivity.php',
        data: { 'search_date': search_date , 'insert_login_id': insert_login_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#Cash_tally_activity_div').empty()
            $('#Cash_tally_activity_div').html(response)
            // $('#print_day_end_report').show();
        }
    })
}

function getUserNames() {
    $.post('reportFile/cashtally_activity/getuserName.php', function (response) {
        $('#by_user').empty();
        $('#by_user').append("<option value=''>Select User</option>");
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['user_ids'] + "'>" + val['fullname'] + "</option>");
        });
    }, 'json');
}
