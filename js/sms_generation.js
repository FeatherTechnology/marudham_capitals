$(document).ready(function(){
    $(document).on("click", "#sendDueReminder", function(event){
        event.preventDefault();
        let currentRow = $(this).closest('tr');
        let cus_id = currentRow.find('td:eq(1)').text();
        $.ajax({
            type:'POST',
            data: {'cus_id': cus_id},
            url:"SMSFiles/ajaxSendSMSToCustomer.php",
            success: function (data) {  
                alert("SMS Sent Successfully"); 
            },
            error: function(xhr, status, error){
                console.log(error);
            }
        })
    });

    $(document).on('click', '#send_birthdayWishes_SMS', function(event){
        let cusName = $('.cusName').val();
        if(cusName =='' || cusName ==undefined){
            event.preventDefault();
            $('#b_alert').show();
        }else{
            $("#b_alert").hide();
        }
    });

    $(document).on('click', '#send_festivalWishes_SMS', function(event){
        let festivalName = $('.festivalName').val();
        if(festivalName =='' || festivalName ==undefined){
            event.preventDefault();
            $('#f_alert').show();
        }else{
            $("#f_alert").hide();
        }
    });

    $(document).on('click', '#send_monthly_loan_reminder', function(event){
        let mCusName = $('.m_cusName').val();
        if(mCusName =='' || mCusName ==undefined){
            event.preventDefault();
            $('#m_alert').show();
        }else{
            $("#m_alert").hide();
        }
    });

    $(document).on('click', '#send_scheme_monthly_reminder', function(event){
        let smCusName = $('.sm_cusName').val();
        if(smCusName =='' || smCusName ==undefined){
            event.preventDefault();
            $('#sm_alert').show();
        }else{
            $("#sm_alert").hide();
        }
    });

    $(document).on('click', '#send_scheme_weekly_reminder', function(event){
        let swCusName = $('.sw_cusName').val();
        if(swCusName =='' || swCusName ==undefined){
            event.preventDefault();
            $('#sw_alert').show();
        }else{
            $("#sw_alert").hide();
        }
    });

    $(document).on('click', '#send_scheme_daily_reminder', function(event){
        let sdCusName = $('.sd_cusName').val();
        if(sdCusName =='' || sdCusName ==undefined){
            event.preventDefault();
            $('#sd_alert').show();
        }else{
            $("#sd_alert").hide();
        }
    })

});//document END

$(function () {
    resetFestivalList(); //Call Festival Info Table Initially.
    resetFestivalModalTableInfo();

    getDueReminderList();
});


// Modal Box for Festival Info
$(document).on("click", "#submitFestivalInfoBtn", function () {
    let holiday_date = $('#holiday_date').val();
    let holiday = $("#holiday").val();
    let holiday_comment = $("#holiday_comment").val();
    let festivalID = $("#festivalID").val();

    if (holiday_date != "" && holiday != "" ) {
        $.ajax({
            url: 'SMSFiles/holiday_creation/festival_submit.php',
            type: 'POST',
            data: { "holiday_date": holiday_date, "holiday": holiday, "holiday_comment": holiday_comment, "festivalID": festivalID },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#FestivalInsertOk').show();
                    setTimeout(function () {
                        $('#FestivalInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#FestivalUpdateok').show();
                    setTimeout(function () {
                        $('#FestivalUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#NotOk').show();
                    setTimeout(function () {
                        $('#NotOk').fadeOut('fast');
                    }, 2000);
                }

                resetFestivalModalTableInfo();
                $('#holidayDateCheck').hide();$('#holidayCheck').hide();
            }
        });
    }
    else {
        if (holiday_date == "") {
            $('#holidayDateCheck').show();
        } else {
            $('#holidayDateCheck').hide();
        }

        if (holiday == "") {
            $('#holidayCheck').show();
        } else {
            $('#holidayCheck').hide();
        }
    }

});

function resetFestivalModalTableInfo() {
    $.ajax({
        url: 'SMSFiles/holiday_creation/festival_reset.php',
        type: 'POST',
        data: {},
        cache: false,
        success: function (html) {
            $("#updatedFestivalTable").empty();
            $("#updatedFestivalTable").html(html);

            $("#holiday_date").val('');
            $("#holiday").val('');
            $("#holiday_comment").val('');
            $("#festivalID").val('');
        }
    });
}

function resetFestivalList() {
    $.ajax({
        url: 'SMSFiles/holiday_creation/festival_list.php',
        type: 'POST',
        data: {},
        cache: false,
        success: function (html) {
            $("#festivalList").empty();
            $("#festivalList").html(html);
        }
    });
}

$("body").on("click", "#festival_edit", function () {
    let id = $(this).attr('value');
    $.ajax({
        url: 'SMSFiles/holiday_creation/festival_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        success: function (result) {
            $("#festivalID").val(result['holiday_id']);
            $("#holiday").val(result['holiday_name']);
            $("#holiday_date").val(result['holiday_date']);
            $("#holiday_comment").val(result['comments']);
        }
    });
});

$("body").on("click", "#festival_delete", function () {
    var isok = confirm("Do you want delete this Festival Info?");
    if (isok == false) {
        return false;
    } else {
        var festivalId = $(this).attr('value');

        $.ajax({
            url: 'SMSFiles/holiday_creation/festival_delete.php',
            type: 'POST',
            data: { "festivalId": festivalId },
            cache: false,
            success: function (response) {
                var delresult = response.includes("Deleted");
                if (delresult) {
                    $('#FestivalDeleteOk').show();
                    setTimeout(function () {
                        $('#FestivalDeleteOk').fadeOut('fast');
                    }, 2000);
                    resetFestivalModalTableInfo();
                }
                else {

                    $('#FestivalDeleteNotOk').show();
                    setTimeout(function () {
                        $('#FestivalDeleteNotOk').fadeOut('fast');
                    }, 2000);
                    resetFestivalModalTableInfo();
                }
            }
        });
    }
});

//FamilyModal Close
function closeFestivalModal() {
    resetFestivalModalTableInfo();
    resetFestivalList();
}

//Due Reminder
function getDueReminderList(){
    $.ajax({
        type:'POST',
        data: {},
        url:'SMSFiles/due_reminder_files/getDueReminderList.php',
        success: function(response){
            $('#due_reminder_table').empty();
            $('#due_reminder_table').html(response);
        }
    })
}