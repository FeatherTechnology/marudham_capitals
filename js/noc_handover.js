$(document).ready(function () {

    $('#close-noc-card').click(function () {
        $('.noc-card').hide(); //Hide collection window at the starting
        $(this).hide();//hide close button also
        $('#submit_noc_handover').hide();//Hide Submit button at the starting, because submit is only for collection
        $('#back-button').show(); //Show Back button
        $('.loanlist_card').show(); // Show loan list
    })

    $('#noc_member').change(function () {
        $('.scanBtn').removeAttr('disabled');
        $('#compare_finger').val('')
        var noc_member = parseInt($(this).val());
        var cus_id = $('#cusidupd').val();
        var req_id = $('#req_id').val();
        //if Noc Member is Family member or Guarentor then get member names
        if (noc_member > 1) {
            $.ajax({
                url: 'nocFile/getMemberDetails.php',
                data: { 'cus_id': cus_id, 'req_id': req_id, 'noc_member': noc_member },
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    if (noc_member == 2) {
                        //if guarentor show readonly input box
                        $('.mem_name').show();
                        $('.mem_relation_name').hide();
                        $('#mem_relation_name').empty();

                        $('#mem_id').val(response['guarentor_id'])
                        $('#mem_name').val(response['guarentor_name'])
                        $('#compare_finger').val(response['fingerprint'])
                    } else if (noc_member == 3) {
                        //if Family member then show dropdown
                        $('.mem_relation_name').show();
                        $('.mem_name').hide();
                        $('#mem_name').val('');

                        $('#mem_relation_name').empty();
                        $('#mem_relation_name').append("<option value=''>Select Member Name</option>")
                        for (var i = 0; i < response['fam_id'].length; i++) {
                            $('#mem_relation_name').append("<option value='" + response['fam_id'][i] + "'>" + response['fam_name'][i] + "</option>")
                        }

                    }
                }
            }).error(function () {
                if (noc_member == 2) {
                    alert('Guarentor Fingerprint not Registered')
                }
            })
        } else if (noc_member == 1) {
            //if member is customer then show customer name
            $('.mem_name').show();
            $('#mem_name').val('');
            $('.mem_relation_name').hide();
            $('#mem_relation_name').empty();

            var cus_name = $('#cus_name').val();
            var cus_id = $('#cus_id').val();
            $('#mem_name').val(cus_name)

            $.ajax({
                url: 'nocFile/getFingerprints.php',
                data: { 'id': cus_id, 'family': false },
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#compare_finger').val(response['fingerprint'])
                }
            })
        } else {
            $('.mem_name').hide();
            $('#mem_name').val('');
            $('.mem_relation_name').hide();
            $('#mem_relation_name').empty();
        }
    })

    $('#mem_relation_name').change(function () {
        var id = $(this).val();
        $('.scanBtn').removeAttr('disabled');
        $.ajax({
            url: 'nocFile/getFingerprints.php',
            data: { 'id': id, 'family': true },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                $('#compare_finger').val(response['fingerprint'])
            }
        }).error(function () {
            alert('Guarentor Fingerprint not Registered')
        })
    })

    //Hide mortgage & Endorsement intially.
    $('.mort_proc').hide();
    $('.endor_proc').hide();

    $('#submit_noc_handover').click(function (event) {

        event.preventDefault();

        if (validations() == true) {
            let confirmAction = confirm("Are you sure you want to submit NOC Handover ?");
            if (!confirmAction) return;

            Promise.all([
                updateNocTable()
            ]).then(() => {
                $('#close-noc-card').trigger('click'); // now executes AFTER everything is updated
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong!");
            });

        }
    });

})//Document Ready End


//On Load Event
$(function () {

    $('.noc-card').hide(); //Hide collection window at the starting
    $('#close-noc-card').hide();//Hide collection close button at the starting
    $('#submit_noc_handover').hide();//Hide Submit button at the starting, because submit is only for collection

    $('.mem_relation_name').hide(); //Hide member name dropdown until chooses noc member
    $('.mem_name').hide(); //Hide member name input until chooses noc member

    OnLoadFunctions();

    var cus_pic = $('#cuspicupd').val();
    $('#imgshow').attr('src', 'uploads/request/customer/' + cus_pic);
})

function OnLoadFunctions() {
    const cus_id = $('#cusidupd').val();

    $.ajax({
        //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
        url: 'nocFile/getLoanListWithClosed.php',
        data: { 'cus_id': cus_id, 'screen': 'nochandover' },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#loanListTableDiv').empty()
            $('#loanListTableDiv').html(response);
        }
    }).done(function () {
        $(document).on('click', '.noc-window', function (event) {
            $('.noc-card').show(); //Show NOC window 
            $('#close-noc-card').show();// Show Cancel button
            $('#back-button').hide();// Hide Back button
            $('.loanlist_card').hide(); // hide loan list
            $('#submit_noc_handover').show();
            var req_id = $(this).attr('data-value');
            $('#req_id').val(req_id) //assigning to req_id input box for getching noc members

            //To get the Signed Document List on Checklist
            const cus_name = $('#cus_name').val();

            // Wrap each AJAX in a promise
            function getSignedDocList() {
                return $.ajax({
                    url: 'nocFile/getSignedDocList.php',
                    data: { 'req_id': req_id, 'cus_name': cus_name },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#signDoc').empty().html(response);
                        if ($('#signDocTable tbody tr').length == 0) {
                            $('.signedRow').hide().next().hide();
                        } else {
                            $('.signedRow').show().next().show();
                        }
                    }
                })
            }

            // To get the unused Cheque List on Checklist
            function getChequeDocList() {
                return $.ajax({
                    url: 'nocFile/getChequeDocList.php',
                    data: { 'req_id': req_id, 'cus_name': cus_name },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#cheque').empty().html(response);
                        if ($('#chequeTable tbody tr').length == 0) {
                            $('.chequeRow').hide().next().hide();
                        } else {
                            $('.chequeRow').show().next().show();
                        }
                    }
                })
            }

            // To get the Mortgage List on Checklist
            function getMortgageList() {
                return $.ajax({
                    url: 'nocFile/getMortgageList.php',
                    data: { 'req_id': req_id, 'cus_name': cus_name },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#mortgage').empty().html(response);
                        if ($('#mortgageTable tbody tr').length == 0) {
                            $('.mortRow').hide().next().hide();
                        } else {
                            $('.mortRow').show().next().show();
                            
                            // Fetch mortgage details for this specific req_id
                            $.ajax({
                                url: 'updateFile/getMortgageInfo.php',
                                data: { 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (mortData) {
                                    // Populate mortgage form fields
                                    if (mortData.mort_process == '0') {
                                        $('.mort_proc').show();
                                    }else{
                                        $('.mort_proc').hide();
                                    }
                                    if (mortData.mort_process !== undefined) {
                                        $('#mortgage_process').val(mortData.mort_process);
                                    }
                                    if (mortData.prop_holder_type !== undefined) {
                                        $('#Propertyholder_type').val(mortData.prop_holder_type);
                                    }
                                    if (mortData.prop_holder_name !== undefined) {
                                        $('#Propertyholder_name').val(mortData.prop_holder_name);
                                    }
                                    if (mortData.prop_holder_rel !== undefined) {
                                        $('#Propertyholder_relationship_name').val(mortData.prop_holder_rel);
                                    }
                                    if (mortData.doc_prop_rel !== undefined) {
                                        $('#doc_property_relation').val(mortData.doc_prop_rel);
                                    }
                                    if (mortData.doc_prop_type !== undefined) {
                                        $('#doc_property_pype').val(mortData.doc_prop_type);
                                    }
                                    if (mortData.doc_prop_meas !== undefined) {
                                        $('#doc_property_measurement').val(mortData.doc_prop_meas);
                                    }
                                    if (mortData.doc_prop_loc !== undefined) {
                                        $('#doc_property_location').val(mortData.doc_prop_loc);
                                    }
                                    if (mortData.doc_prop_val !== undefined) {
                                        $('#doc_property_value').val(mortData.doc_prop_val);
                                    }
                                    if (mortData.mort_name !== undefined) {
                                        $('#mortgage_name').val(mortData.mort_name);
                                    }
                                    if (mortData.mort_des !== undefined) {
                                        $('#mortgage_dsgn').val(mortData.mort_des);
                                    }
                                    if (mortData.mort_num !== undefined) {
                                        $('#mortgage_nuumber').val(mortData.mort_num);
                                    }
                                    if (mortData.reg_office !== undefined) {
                                        $('#reg_office').val(mortData.reg_office);
                                    }
                                    if (mortData.mort_value !== undefined) {
                                        $('#mortgage_value').val(mortData.mort_value);
                                    }
                                    if (mortData.mort_doc !== undefined) {
                                        $('#mortgage_document').val(mortData.mort_doc);
                                    }
                                }
                            });
                        }
                    }
                })
            }

            // To get the Endorsement List on Checklist
            function getEndorsementList() {
                return $.ajax({
                    url: 'nocFile/getEndorsementList.php',
                    data: { 'req_id': req_id, 'cus_name': cus_name },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#endorsement').empty().html(response);
                        if ($('#endorsementTable tbody tr').length == 0) {
                            $('.endRow').hide().next().hide();
                        } else {
                            $('.endRow').show().next().show();
                            
                            // Fetch endorsement details for this specific req_id
                            $.ajax({
                                url: 'updateFile/getEndorsementInfo.php',
                                data: { 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (endData) {
                                    // Populate endorsement form fields
                                    if (endData.end_process == '0') {
                                        $('.endor_proc').show();
                                    }else{
                                        $('.endor_proc').hide();
                                    }
                                    if (endData.end_process !== undefined) {
                                        $('#endorsement_process').val(endData.end_process);
                                    }
                                    if (endData.owner_type !== undefined) {
                                        $('#owner_type').val(endData.owner_type);
                                    }
                                    if (endData.owner_name !== undefined) {
                                        $('#owner_name').val(endData.owner_name);
                                    }
                                    if (endData.owner_rel_name !== undefined) {
                                        $('#ownername_relationship_name').val(endData.owner_rel_name);
                                    }
                                    if (endData.owner_relation !== undefined) {
                                        $('#en_relation').val(endData.owner_relation);
                                    }
                                    if (endData.vehicle_type !== undefined) {
                                        $('#vehicle_type').val(endData.vehicle_type);
                                    }
                                    if (endData.vehicle_process !== undefined) {
                                        $('#vehicle_process').val(endData.vehicle_process);
                                    }
                                    if (endData.vehicle_comp !== undefined) {
                                        $('#en_Company').val(endData.vehicle_comp);
                                    }
                                    if (endData.vehicle_mod !== undefined) {
                                        $('#en_Model').val(endData.vehicle_mod);
                                    }
                                    if (endData.vehicle_reg_no !== undefined) {
                                        $('#vehicle_reg_no').val(endData.vehicle_reg_no);
                                    }
                                    if (endData.end_name !== undefined) {
                                        $('#endorsement_name').val(endData.end_name);
                                    }
                                    if (endData.end_rc !== undefined) {
                                        $('#en_RC').val(endData.end_rc);
                                    }
                                    if (endData.end_key !== undefined) {
                                        $('#en_Key').val(endData.end_key);
                                    }
                                }
                            });
                        }
                    }
                })
            }

            // To get the Gold List on Checklist
            function getGoldList() {
                return $.ajax({
                    url: 'nocFile/getGoldList.php',
                    data: { 'req_id': req_id, 'cus_name': cus_name },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#gold').empty().html(response);
                        if ($('#goldTable tbody tr').length == 1) {
                            $('.goldRow').hide().next().hide();
                        } else {
                            $('.goldRow').show().next().show();
                        }
                    }
                })
            }

            // To get the Document List on Checklist
            function getDocumentList() {
                return $.ajax({
                    url: 'nocFile/getDocumentList.php',
                    data: { 'req_id': req_id, 'cus_name': cus_name },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#documentModal').empty().html(response);
                        if ($('#documentTable tbody tr').length == 0) {
                            $('.docRow').hide().next().hide();
                        } else {
                            $('.docRow').show().next().show();
                        }
                    }
                })
            }

            $('.scanBtn').click(function () {
                var mem_name = $('#mem_relation_name').val() != '' ? $('#mem_relation_name').val() : $('#mem_name').val();

                if (mem_name != '') {

                    showOverlay();//loader start
                    $(this).attr('disabled', true);

                    setTimeout(() => { //Set Timeout, because loadin animation will be intrupped by this capture event
                        var quality = 60; //(1 to 100) (recommended minimum 55)
                        var timeout = 10; // seconds (minimum=10(recommended), maximum=60, unlimited=0)
                        var res = CaptureFinger(quality, timeout);
                        if (res.httpStaus) {
                            if (res.data.ErrorCode == "0") {
                                $('#ack_fingerprint').val(res.data.AnsiTemplate); // Take ansi template that is the unique id which is passed by sensor
                            }//Error codes and alerts below
                            else if (res.data.ErrorCode == -1307) {
                                alert('Connect Your Device');
                                $(this).removeAttr('disabled');
                            } else if (res.data.ErrorCode == -1140 || res.data.ErrorCode == 700) {
                                alert('Timeout');
                                $(this).removeAttr('disabled');
                            } else if (res.data.ErrorCode == 720) {
                                alert('Reconnect Device');
                                $(this).removeAttr('disabled');
                            } else if (res.data.ErrorCode == 730) {
                                alert('Capture Finger Again');
                                $(this).removeAttr('disabled');
                            } else {
                                alert('Error Code:' + res.data.ErrorCode);
                                $(this).removeAttr('disabled');
                            }
                        }
                        else {
                            alert(res.err);
                        }

                        //Verify the finger is matched with member name
                        var compare_finger = $('#compare_finger').val()
                        var ack_fingerprint = $('#ack_fingerprint').val()
                        if (ack_fingerprint != '') {

                            var res = VerifyFinger(compare_finger, ack_fingerprint)
                            if (res.httpStaus) {
                                if (res.data.Status) {
                                    Swal.fire({
                                        title: 'Fingerprint Matching',
                                        icon: 'success',
                                        showConfirmButton: true,
                                        confirmButtonColor: '#009688'
                                    });
                                } else {
                                    if (res.data.ErrorCode != "0") {
                                        alert(res.data.ErrorDescription);
                                    }
                                    else {
                                        Swal.fire({
                                            title: 'Fingerprint Not Matching',
                                            icon: 'error',
                                            showConfirmButton: true,
                                            confirmButtonColor: '#009688'
                                        });
                                        $(this).removeAttr('disabled');
                                    }
                                }
                            } else {
                                alert(res.err)
                            }
                        }
                        hideOverlay();//loader stop
                    }, 700) //Timeout End

                }//If End

            })//Scan button Onclick end

            // Run all AJAX calls in parallel and wait for all to complete
            Promise.all([
                getSignedDocList(),
                getChequeDocList(),
                getMortgageList(),
                getEndorsementList(),
                getGoldList(),
                getDocumentList()
            ]).catch(err => {
                console.error('Error loading lists:', err);
            });

        })//Window onclick end

        $(document).on('click', '.noc-summary', function (e) {
            e.preventDefault();
            let req_id = $(this).data('reqid');
            var cus_name = $(this).data('cusname');
            $.ajax({
                url: 'verificationFile/documentation/getNOCSummary.php',
                data: { req_id, cus_name },
                type: 'post',
                cache: false,
                success: function (html) {
                    $('#nocsummaryModal').html(html);
                }
            });
        });

        $(document).on('click', '.noc-letter', function (event) {
            event.preventDefault();
            let req_id = $(this).data('reqid');
            let cus_id = $(this).data('cusid');
            $.post('nocFile/nocLetter.php', { req_id, cus_id }, function (html) {
                $('#printnocletter').html(html);
            });
        });

    })//Ajax done End

}//Auto Load function END


function validations() {
    var res = true;
     var noc_member = $('#noc_member').val(); var mem_relation_name = $('#mem_relation_name').val(); var fingerprint = $('.scanBtn').attr('disabled');
    if (noc_member == '') {
        $('.noc_memberCheck').show()
        event.preventDefault();
        res = false;
    } else {
        $('.noc_memberCheck').hide()
    }

    if (noc_member = '3' && mem_relation_name == '') {
        $('.mem_relation_nameCheck').show()
        event.preventDefault();
        res = false;
    } else {
        $('.mem_relation_nameCheck').hide()
    }

    // if (fingerprint != 'disabled') {
    //     $('.scanBtnCheck').show()
    //     event.preventDefault();
    //     res = false;
    // } else {
    //     $('.scanBtnCheck').hide()
    // }

    
    return res;
}

function updateNocTable() {

    let cusidupd = $('#cusidupd').val();
    let req_id = $('#req_id').val();
    let noc_date = $('#noc_date').val();
    let noc_member = $('#noc_member').val();
    let mem_name = ''; // Initialize mem_name variable

    // Determine mem_name based on noc_member value
    if (noc_member === '3') {
        mem_name = $('#mem_relation_name').val();
    } else if (noc_member === '1' || noc_member === '2') {
        mem_name = $('#mem_name').val();
    }

    var formData = new FormData();
    formData.append('cusidupd', cusidupd);
    formData.append('req_id', req_id);
    formData.append('noc_date', noc_date);
    formData.append('noc_member', noc_member);
    formData.append('mem_name', mem_name); // Append mem_name
    // ⭐ Return the AJAX promise
    return $.ajax({
        url: 'nocFile/updateNocHandover.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        cache: false
    }).then(function (response) {

        if (response == "Success") {
            Swal.fire({
                title: 'Submitted',
                icon: 'success',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            }).then((result) => {
                if (result.isConfirmed) {
                    OnLoadFunctions();
                }
            });
        } else {
            Swal.fire({
                title: 'Error While Submitting',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
        }

        return response; // important for promise chain
    });
}