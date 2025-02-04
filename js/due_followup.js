$(document).ready(function () {

    $('.back-button').click(function () {
        event.preventDefault();
        // window.history.back();
        let cusSts = $('#cus_sts').val();
        window.location = 'edit_due_followup&cussts='+cusSts;
    })

    $('#comm_ftype').change(function () {
        let type = $(this).val();
        let append;
        if (type == 1) {//direct
            append = `<option value="">Select Follow Up Status</option><option value='1'>Commitment</option><option value='2'>Unavailable</option>`;
        } else if (type == 2) {//mobile
            append = `<option value="">Select Follow Up Status</option><option value='1'>Commitment</option><option value='3'>RNR</option><option value='4'>Not Reachable</option>
            <option value='5'>Switch Off</option><option value='6'>Not in Use</option><option value='7'>Blocked</option>`;
        } else {
            append = `<option value="">Select Follow Up Status</option>`;
        }
        $('#comm_fstatus').empty().append(append);
    })

    $('#comm_fstatus').change(function () {
        let status = $(this).val();
        if (status == 1) {//commitment
            $('.person-div').show();
        } else {
            $('.person-div').hide();
            $('#comm_person_type,#comm_person_name,#comm_person_name1,#comm_relationship').val('');//empty values when hiding person div
        }
    })

    $('#comm_person_type').change(function () {
        let type = $(this).val();
        let req_id = $('#comm_req_id').val();
        let cus_id = $('#cusidupd').val();
        if (type == 1) {

            let cus_name = $('#cus_name').val();
            $('#comm_person_name1').hide();//select box
            $('#comm_person_name').show();
            $('#comm_person_name').val(cus_name);//storing customer name in person name
            $('#comm_relationship').val('NIL');

        } else if (type == 2) {
            type = 1;//cause in below url garentor is managed as type 1
            $.post('verificationFile/documentation/check_holder_name.php', { 'reqId': req_id, type }, function (response) {
                //if guarentor show readonly input box and hide select box
                $('#comm_person_name').show();
                $('#comm_person_name1').hide();//select box
                $('#comm_person_name1').empty();//select box

                $('#comm_person_name').val(response['name'])
                $('#comm_relationship').val(response['relationship']);
            }, 'json')
        } else if (type == 3) {
            $.post('verificationFile/verificationFam.php', { cus_id }, function (response) {
                //if Family member then show dropdown and hide input box
                $('#comm_person_name1').show();//select box
                $('#comm_person_name').hide();
                $('#comm_person_name').empty();

                $('#comm_person_name1').empty().append("<option value=''>Select Person Name</option>")
                for (var i = 0; i < response.length - 1; i++) {
                    $('#comm_person_name1').append("<option value='" + response[i]['fam_id'] + "'>" + response[i]['fam_name'] + "</option>")
                }

                //create onchange event for person name that will bring the relationship of selected customer
                $('#comm_person_name1').off('change').change(function () {
                    let person = $(this).val();
                    for (var i = 0; i < response.length - 1; i++) {
                        if (person == response[i]['fam_id']) {
                            $('#comm_relationship').val(response[i]['relationship']);
                        }
                    }
                })

            }, 'json')
        }
    });

    $('#sumit_add_comm').click(function () {
        if (validateCommitment() == true) {
            submitCommitment();
        }
    })

    $('#addCommitment').find('.closeModal').click(function () {
        $('#addCommitment').find('.modal-body input,select').not('#comm_fdate,#comm_user_type,#comm_user').val('');
        $('#addCommitment').find('.modal-body span').hide();
        $('.person-div').hide();
    })

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date in the date input to today
        $('#comm_date').attr('min', today);
    }
})//Document Ready End


//On Load Event
$(function () {

    var req_id = $('#idupd').val()
    const cus_id = $('#cusidupd').val()
    OnLoadFunctions(req_id, cus_id);

})

function OnLoadFunctions(req_id, cus_id) {

        showOverlay();//loader start

        $.ajax({
            //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
            url: 'followupFiles/dueFollowup/viewLoanList.php',
            data: { 'req_id': req_id, 'cus_id': cus_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // $('.overlay').remove();
                $('#loanListTableDiv').empty()
                $('#loanListTableDiv').html(response);
                // searchFunction();//this will call search function repair module

                $('.loan-history-window').click(function (e) {
                    e.preventDefault();

                    $('.loanlist_card').hide();
                    $('.back-button').hide();
                    $('.loan_history_card').show();
                    $('.doc_history_card').hide();
                    // let navbar = document.getElementById('navbar');
                    // navbar.classList.add('collection-card')
                    $('#close_collection_card').show();

                    $.ajax({
                        //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
                        url: 'followupFiles/dueFollowup/viewLoanHistory.php',
                        data: { 'cus_id': cus_id },
                        type: 'post',
                        cache: false,
                        success: function (response) {
                            $('#loanHistoryDiv').empty()
                            $('#loanHistoryDiv').html(response);
                        }
                    });

                });

                $('.doc-history-window').click(function (e) {
                    e.preventDefault();

                    $('.loanlist_card').hide();
                    $('.back-button').hide();
                    $('.loan_history_card').hide();
                    $('.doc_history_card').show();
                    // let navbar = document.getElementById('navbar');
                    // navbar.classList.add('collection-card')
                    $('#close_collection_card').show();

                    $.ajax({
                        //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
                        url: 'followupFiles/dueFollowup/viewDocumentHistory.php',
                        data: { 'cus_id': cus_id },
                        type: 'post',
                        cache: false,
                        success: function (response) {
                            $('#docHistoryDiv').empty()
                            $('#docHistoryDiv').html(response);
                        }
                    })

                });

                $('#close_collection_card').click(function () {

                    $('.loanlist_card').show();
                    $('.back-button').show();
                    $('.loan_history_card').hide();
                    $('.doc_history_card').hide();
                    $('#close_collection_card').hide();

                });

                $('.due-chart').click(function () {
                    var req_id = $(this).attr('value');
                    dueChartList(req_id, cus_id); // To show Due Chart List.
                    setTimeout(() => {
                        $('.print_due_coll').click(function () {
                            var id = $(this).attr('value');
                            Swal.fire({
                                title: 'Print',
                                text: 'Do you want to print this collection?',
                                imageUrl: 'img/printer.png',
                                imageWidth: 300,
                                imageHeight: 210,
                                imageAlt: 'Custom image',
                                showCancelButton: true,
                                confirmButtonColor: '#009688',
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'No',
                                confirmButtonText: 'Yes'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: 'collectionFile/print_collection.php',
                                        data: { 'coll_id': id },
                                        type: 'post',
                                        cache: false,
                                        success: function (html) {
                                            $('#printcollection').html(html)
                                            // Get the content of the div element
                                            var content = $("#printcollection").html();
                                        }
                                    })
                                }
                            })
                        })
                    }, 1000)
                })
                $('.penalty-chart').click(function () {
                    var req_id = $(this).attr('value');
                    $.ajax({
                        //to insert penalty by on click
                        url: 'collectionFile/getLoanDetails.php',
                        data: { 'req_id': req_id, 'cus_id': cus_id },
                        dataType: 'json',
                        type: 'post',
                        cache: false,
                        success: function (response) {
                            penaltyChartList(req_id, cus_id); //To show Penalty List.
                        }
                    })
                })
                $('.coll-charge-chart').click(function () {
                    var req_id = $(this).attr('value');
                    collectionChargeChartList(req_id) //To Show Fine Chart List
                })
                $('.coll-charge').click(function () {
                    var req_id = $(this).attr('value');
                    resetcollCharges(req_id);  //Fine
                })

                $('.add-commitment-chart').click(function () {
                    let req_id = $(this).data('reqid');
                    $('#comm_req_id').val(req_id)
                })
                //Commitment chart
                $('.commitment-chart').off('click').click(function () {
                    let req_id = $(this).data('reqid'); let cus_id = $('#cusidupd').val();
                    $.post('followupFiles/dueFollowup/getCommitmentChart.php', { cus_id, req_id }, function (html) {
                        $('#commChartDiv').empty().html(html);
                    })
                })
            }
        })
        hideOverlay();//loader stop

}//Auto Load function END

//Due Chart List
function dueChartList(req_id, cus_id) {
    // var req_id = $('#idupd').val()
    // const cus_id = $('#cusidupd').val()
    $.ajax({
        url: 'collectionFile/getDueChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#dueChartTableDiv').empty()
            $('#dueChartTableDiv').html(response)
        }
    }).then(function () {

        $.post('collectionFile/getDueMethodName.php', { req_id }, function (response) {
            $('#dueChartTitle').text('Due Chart ( ' + response['due_method'] + ' - ' + response['loan_type'] + ' )');
        }, 'json');
    })
}
//Penalty Chart List
function penaltyChartList(req_id, cus_id) {
    $.ajax({
        url: 'collectionFile/getPenaltyChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#penaltyChartTableDiv').empty()
            $('#penaltyChartTableDiv').html(response)
        }
    });//Ajax End.
}
//Collection Charge Chart List
function collectionChargeChartList(req_id) {
    $.ajax({
        url: 'collectionFile/getCollectionChargeList.php',
        data: { 'req_id': req_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collectionChargeDiv').empty()
            $('#collectionChargeDiv').html(response)
        }
    });//Ajax End.
}
//Fine
function resetcollCharges(req_id) {
    $.ajax({
        url: 'collectionFile/collection_charges_reset.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#collChargeTableDiv").empty();
            $("#collChargeTableDiv").html(html);
            $("#cc_req_id").val(req_id);
            $("#collectionCharge_date").val('');
            $("#collectionCharge_purpose").val('');
            $("#collectionCharge_Amnt").val('');
        }
    });
}


function submitCommitment() {
    let req_id = $('#comm_req_id').val(); let cus_id = $('#cusidupd').val();
    let ftype = $('#comm_ftype').val(); let fstatus = $('#comm_fstatus').val();
    let person_type = $('#comm_person_type').val(); let person_name = $('#comm_person_name').val(); let person_name1 = $('#comm_person_name1').val();
    let relationship = $('#comm_relationship').val(); let remark = $('#comm_remark').val(); let date = $('#comm_date').val(); let hint = $('#comm_hint').val(); let err = $('#comm_err').val();
    let args = { cus_id, req_id, ftype, fstatus, person_type, person_name, person_name1, relationship, remark, date, hint, err };

    $.post('followupFiles/dueFollowup/submitCommitment.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function(){
                $('.closeModal').trigger('click');
            });

            $('#addCommitment').find('.modal-body input,select').not('#comm_fdate,#comm_user_type,#comm_user').val('');
            $('.person-div').hide();
        }
    })
}
function validateCommitment() {
    let response = true;
    let ftype = $('#comm_ftype').val(); let fstatus = $('#comm_fstatus').val(); let person_type = $('#comm_person_type').val();
    let person_name = $('#comm_person_name').val(); let person_name1 = $('#comm_person_name1').val(); let remark = $('#comm_remark').val();
    let comm_date = $('#comm_date').val(); let hint = $('#comm_hint').val(); let err = $('#comm_err').val();

    validateField(ftype, '#comm_ftypeCheck');
    validateField(fstatus, '#comm_fstatusCheck');
    if (fstatus == 1) {

        validateField(person_type, '#comm_person_typeCheck');
        if (person_type == 3) {
            validateField(person_name1, '#comm_person_nameCheck');
        } else {
            $('#comm_person_nameCheck').hide();
        }
        validateField(comm_date, '#comm_dateCheck');
    }
    validateField(remark, '#comm_remarkCheck');
    validateField(hint, '#comm_hintCheck');
    validateField(err, '#comm_errCheck');

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
    }).then( (result) => {
        if(result.isConfirmed && typeof callback === 'function'){
            callback();
        }
    });
}

