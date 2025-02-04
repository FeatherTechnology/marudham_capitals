$(document).ready(function () {

    // $('button').click(function (e) { e.preventDefault(); })

    $('#conf_person_type').change(function () {
        let type = $(this).val();
        let req_id = $('#conf_req_id').val();
        let cus_id = $('#conf_cus_id').val();
        if (type == 1) {

            let cus_name = $('#conf_cus_name').val();
            $('#conf_person_name1').hide();//select box
            $('#conf_person_name').show();
            $('#conf_person_name').val(cus_name);//storing customer name in person name
            $('#conf_relationship').val('NIL');

        } else if (type == 2) {
            type = 1;//cause in below url garentor is managed as type 1
            $.post('verificationFile/documentation/check_holder_name.php', { 'reqId': req_id, type }, function (response) {
                //if guarentor show readonly input box and hide select box
                $('#conf_person_name').show();
                $('#conf_person_name1').hide();//select box
                $('#conf_person_name1').empty();//select box

                $('#conf_person_name').val(response['name'])
                $('#conf_relationship').val(response['relationship']);
            }, 'json')
        } else if (type == 3) {
            $.post('verificationFile/verificationFam.php', { cus_id }, function (response) {
                //if Family member then show dropdown and hide input box
                $('#conf_person_name1').show();//select box
                $('#conf_person_name').hide();
                $('#conf_person_name').empty();

                $('#conf_person_name1').empty().append("<option value=''>Select Person Name</option>")
                for (var i = 0; i < response.length - 1; i++) {
                    $('#conf_person_name1').append("<option value='" + response[i]['fam_id'] + "'>" + response[i]['fam_name'] + ' - ' + response[i]['relationship'] + "</option>")
                }

                //create onchange event for person name that will bring the relationship of selected customer
                $('#conf_person_name1').off('change').change(function () {
                    let person = $(this).val();
                    for (var i = 0; i < response.length - 1; i++) {
                        if (person == response[i]['fam_id']) {
                            $('#conf_relationship').val(response[i]['relationship']);
                        }
                    }
                })

            }, 'json')
        }
    });

    $('#conf_status').change(function () {
        let status = $(this).val();
        $('.unav-div').hide(); $('.unav-div select').val('');
        $('.reconf-div').hide(); $('.reconf-div input').val('');
        if (status == 2) {
            $('.unav-div').show();
        } else if (status == 3) {
            $('.reconf-div').show();
        }
    })

    $('.closeModal').click(function () {
        //reset modal contents back
        resetModalContents();
    })

    $('#sumit_add_conf').click(() => {
        if (validateConfirmation() == true) {
            submitConfirmation();
        }
    })

});



function submitConfirmation() {
    var formdata = new FormData();
    let cus_id = $('#conf_cus_id').val(); let req_id = $('#conf_req_id').val();
    let person_type = $('#conf_person_type').val(); let person_name = $('#conf_person_name').val(); let person_name1 = $('#conf_person_name1').val();//select box
    let relationship = $('#conf_relationship').val(); let mobile = $('#conf_mobile').val(); let file = $('#conf_upload').prop('files')[0];
    let status = $('#conf_status').val(); let sub_status = $('#conf_sub_status').val();
    let label = $('#conf_label').val(); let remark = $('#conf_remark').val();

    formdata.append('cus_id', cus_id)
    formdata.append('req_id', req_id)
    formdata.append('person_type', person_type)
    formdata.append('person_name', person_name)
    formdata.append('person_name1', person_name1)
    formdata.append('relationship', relationship)
    formdata.append('mobile', mobile)
    formdata.append('file', file)
    formdata.append('status', status)
    formdata.append('sub_status', sub_status)
    formdata.append('label', label)
    formdata.append('remark', remark)

    $.ajax({
        url: 'followupFiles/confirmation/submitConfirmation.php',
        data: formdata,
        type: 'post',
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.includes('Error')) {
                swarlErrorAlert(response);
            } else {
                swarlSuccessAlert(response, function(){
                    $('.closeModal').trigger('click');
                });
                resetModalContents();
            }
        }
    })
}
function validateConfirmation() {
    let response = true;
    let person_type = $('#conf_person_type').val(); let person_name1 = $('#conf_person_name1').val();//select box
    let mobile = $('#conf_mobile').val();
    let status = $('#conf_status').val(); let sub_status = $('#conf_sub_status').val();
    let label = $('#conf_label').val(); let remark = $('#conf_remark').val();

    validateField(person_type, '#conf_person_typeCheck');
    if (person_type == 3) {
        validateField(person_name1, '#conf_person_nameCheck');
    }

    validateField(mobile, '#conf_mobileCheck');
    validateField(status, '#conf_statusCheck');

    if (status == 2) {
        validateField(sub_status, '#conf_sub_statusCheck');
    } else if (status == 3) {
        validateField(label, '#conf_labelCheck');
        validateField(remark, '#conf_remarkCheck');
    }

    function validateField(value, fieldId) {
        if (value === '') {
            response = false;
            event.preventDefault();
            $(fieldId).show();
        } else {
            $(fieldId).hide();
        }

    }

    return response;
}

function resetModalContents() {
    $('#addConfimation').find('.modal-body input,select').not('#conf_date').val('');
    $('#conf_person_name1, .unav-div, .reconf-div').hide();
    $('#conf_person_name').show();
}


function confirmationTableOnClick() {


    //confrimation chart
    $('.conf-chart').off('click').click(function () {
        let cus_id = $(this).data('cusid'); let req_id = $(this).data('reqid');
        $.post('followupFiles/confirmation/getConfirmationChart.php', { cus_id, req_id }, function (html) {
            $('#confChartDiv').empty().html(html);
        })
    })

    //customer profile
    $('.cust-profile').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.location.href = 'due_followup_info&upd=' + req_id + '&pgeView=1';
    })
    //Documentaion
    $('.documentation').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.location.href = 'due_followup_info&upd=' + req_id + '&pgeView=2';
    })
    //Loan Calculation
    $('.loan-calc').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.location.href = 'due_followup_info&upd=' + req_id + '&pgeView=3';
    })

    //loan history and document history
    $('.loan-history, .doc-history').off('click').click(function () {
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        let type = $(this).attr('class');
        historyTableContents(req_id, cus_id, type)
    });

    //personal info
    $('.personal-info').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/promotion/getPersonalInfo.php', { cus_id }, function (html) {
            $('#personalInfoDiv').empty().html(html);
        })
    })

    //add confirmation
    $('.conf-edit').off('click').click(function () {
        //set cus id to hidden input for submit
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        let cus_name = $(this).data('cusname');
        let mobile = $(this).closest('td').prev().prev().html();

        $('#conf_req_id').val(req_id);
        $('#conf_cus_id').val(cus_id);
        $('#conf_cus_name').val(cus_name);
        $('#conf_mobile').val(mobile);
    })

    //remove confirmation
    $('.conf-remove').off('click').click(function () {
        if (confirm('Are you sure you want to delete this confirmation?')) {
            //set cus id to hidden input for submit
            let req_id = $(this).data('reqid');
            removeConfirmation(req_id);
        }
    })
}
//Code snippet from c:\xampp\htdocs\marudham\js\due_followup.js
function historyTableContents(req_id, cus_id, type) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];
    $.ajax({
        url: 'closedFile/resetCustomerStsForClosed.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {

                for (var i = 0; i < response['pending_customer'].length; i++) {
                    pending_arr[i] = response['pending_customer'][i]
                    od_arr[i] = response['od_customer'][i]
                    due_nil_arr[i] = response['due_nil_customer'][i]
                    closed_arr[i] = response['closed_customer'][i]
                    balAmnt[i] = response['balAmnt'][i]
                }
                var pending_sts = pending_arr.join(',');
                $('#pending_sts').val(pending_sts);
                var od_sts = od_arr.join(',');
                $('#od_sts').val(od_sts);
                var due_nil_sts = due_nil_arr.join(',');
                $('#due_nil_sts').val(due_nil_sts);
                var closed_sts = closed_arr.join(',');
                $('#closed_sts').val(closed_sts);
                balAmnt = balAmnt.join(',');
            }
        }
    }).then(() => {
    showOverlay();//loader start

        var pending_sts = $('#pending_sts').val()
        var od_sts = $('#od_sts').val()
        var due_nil_sts = $('#due_nil_sts').val()
        var closed_sts = $('#closed_sts').val()
        var bal_amt = balAmnt;

        if (type == 'loan-history') {

            //for loan history
            $('.loan-history-card').show();
            $('#close_history_card').show();
            $('.doc-history-card').hide();
            $('.conf-list-card').hide();//loan followup list 

            $.ajax({
                // Fetching details by customer ID instead of req ID because we need all loans from the customer
                url: 'followupFiles/dueFollowup/viewLoanHistory.php',
                data: {
                    'cus_id': cus_id,
                    'pending_sts': pending_sts,
                    'od_sts': od_sts,
                    'due_nil_sts': due_nil_sts,
                    'closed_sts': closed_sts
                },
                type: 'post',
                cache: false,
                success: function (response) {
                    // Clearing and updating the loan history div with the response
                    $('#loanHistoryDiv').empty().html(response);
                }
            });
        } else {

            //for Document history
            $('.doc-history-card').show();
            $('#close_history_card').show();
            $('.loan-history-card').hide();
            $('.conf-list-card').hide();

            $.ajax({
                // Fetching details by customer ID instead of req ID because we need all loans from the customer
                url: 'followupFiles/dueFollowup/viewDocumentHistory.php',
                data: {
                    'cus_id': cus_id,
                    'pending_sts': pending_sts,
                    'od_sts': od_sts,
                    'due_nil_sts': due_nil_sts,
                    'closed_sts': closed_sts,
                    'bal_amt': bal_amt
                },
                type: 'post',
                cache: false,
                success: function (response) {
                    // Emptying the docHistoryDiv and adding the response
                    $('#docHistoryDiv').empty().html(response);
                }
            });
        }

        $('#close_history_card').off('click').click(() => {
            $('.conf-list-card').show();//shows confirmation card
            $('.loan-history-card').hide();//hides loan history card
            $('.doc-history-card').hide();//hides document history card
            $('#close_history_card').hide();// Hides the close button
        })
        hideOverlay();//loader stop
    })

}
function removeConfirmation(req_id) {
    $.post('followupFiles/confirmation/removeConfirmation.php', { req_id }, function (response) {
        if (response.includes('Successfully')) {
            swarlSuccessAlert('Confirmation Removed Successfully');
            resetConfirmationFollowupTable();
        } else {
            swarlErrorAlert(response);
        }
    })
}




// Improved code snippet
function swarlErrorAlert(response) {
    Swal.fire({
        title: response,
        icon: 'error',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#009688'
    });
}
function swarlInfoAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'info',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#cc4444',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes'
    }).then(function (result) {
        if (result.isConfirmed) {
            update();
        }
    });
}
function swarlSuccessAlert(response, callback) {
    Swal.fire({
        title: response,
        icon: 'success',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#009688'
    }).then((result) => {
        if( result.isConfirmed && typeof callback === 'function'){
            callback();
        }
    });
}





