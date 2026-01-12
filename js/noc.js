$(document).ready(function () {

    $('#close-noc-card').click(function () {
        $('.noc-card').hide(); //Hide collection window at the starting
        $(this).hide();//hide close button also
        $('#submit_noc').hide();//Hide Submit button at the starting, because submit is only for collection
        $('#back-button').show(); //Show Back button
        $('.loanlist_card').show(); // Show loan list
    })

    //Hide mortgage & Endorsement intially.
    $('.mort_proc').hide();
    $('.endor_proc').hide();

    $('#submit_noc').click(function (event) {
        event.preventDefault();
        let req_id = $('#req_id').val();

        if (validations() == true) {
            let confirmAction = confirm("Are you sure you want to submit NOC ?");
            if (!confirmAction) return;

            // 1. Update NOC master (wrap jqXHR in a real Promise)
            Promise.resolve(updateNocTable())
                // 2. Update individual document / sign / gold tables
                .then(() => {
                    return updateCheckedDetails();
                })
                // 3. Reload loan list and try to re-open same NOC row
                .then(() => {
                    if (req_id !== null) {
                        const cus_id = $('#cusidupd').val();

                        return $.ajax({
                            // in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
                            url: 'nocFile/getLoanListWithClosed.php',
                            data: { 'cus_id': cus_id, 'screen': 'noc' },
                            type: 'post',
                            cache: false,
                            success: function (response) {
                                $('#loanListTableDiv').html(response);

                                // After refresh, try to open the same NOC row
                                const $nocRow = $(`.noc-window[data-value='${req_id}']`);
                                if ($nocRow.length) {
                                    // Still has NOC action (status 21 / 23)
                                    $nocRow.trigger('click');
                                } else {
                                    // NOC action removed (likely fully submitted / status 22)
                                    // Hide submit button and lock all checklist controls in current view
                                    $('#submit_noc').hide();

                                    // Disable all checklist checkboxes so they cannot be changed anymore
                                    $('.sign_check, .cheque_check, .mort_check, .endorse_check, .gold_check, .doc_check')
                                        .prop('disabled', true)
                                        .off('click');
                                }
                            }
                        });
                    }
                })
                // 4. Clear checklist hidden fields
                .then(() => {
                    $('#sign_checklist').val('');
                    $('#cheque_checklist').val('');
                    $('#mort_checklist').val('');
                    $('#endorse_checklist').val('');
                    $('#gold_checklist').val('');
                    $('#doc_checklist').val('');
                })
                .catch(err => {
                    console.error(err);
                    alert("Something went wrong!");
                });
        }
    });

    $('#category').on('change', function () {

        let category = $('#category').val();
        $("#check_name, #check_mobileno, #check_aadhar").empty();
        $("#cus_check, #fam_check").empty();

        if (category == 0) {
            $('#nameCheck').show();
            $('#aadharNo').hide();
            $('#mobileNo').hide();

            famNameList(); /// To show family name for Data Check.

        } else if (category == 1) {
            $('#aadharNo').show();
            $('#nameCheck').hide();
            $('#mobileNo').hide();

            aadharList()//// To show Aadhar No for Data Checking.

        } else if (category == 2) {
            $('#mobileNo').show();
            $('#nameCheck').hide();
            $('#aadharNo').hide();

            mobileList(); //// To show Mobile No for Data Checking.

        } else {
            $('#nameCheck').hide();
            $('#aadharNo').hide();
            $('#mobileNo').hide();
        }

    })

    $('#check_name, #check_aadhar, #check_mobileno').on('change', function () {

        let name = $(this).val();
        let category = $('#category').val();
        let req_id = $('#req_id').val();
        $("#cus_check, #fam_check").empty();

        if (name != '') {
            $.ajax({
                url: 'verificationFile/verification_cus_datacheck.php',
                type: 'POST',
                data: { "name": name, "req_id": req_id, "category": category },
                cache: false,
                success: function (html) {
                    $("#cus_check").empty();
                    $("#cus_check").html(html);
                }
            });

            $.ajax({
                url: 'verificationFile/verification_fam_datacheck.php',
                type: 'POST',
                data: { "name": name, "req_id": req_id, "category": category },
                cache: false,
                success: function (html) {
                    $("#fam_check").empty();
                    $("#fam_check").html(html);
                }
            });

        }
    });
    
    $(document).on("click", "#hide_cus_data", function () {
        $('#cus_datacheck').hide();
        $('#hide_cus_data').hide();
        $('#show_cus_data ').show();
    });

    $(document).on("click", "#show_cus_data", function () {
        $('#hide_cus_data').show();
        $('#show_cus_data').hide();
        $('#cus_datacheck').show();
    });

    $(document).on("click", "#hide_fam_data", function () {
        $('#fam_datacheck').hide();
        $('#hide_fam_data').hide();
        $('#show_fam_data').show();
    });
    
    $(document).on("click", "#show_fam_data", function () {
        $('#hide_fam_data').show();
        $('#show_fam_data').hide();
        $('#fam_datacheck').show();
    });

})//Document Ready End


//On Load Event
$(function () {

    $('.noc-card').hide(); //Hide collection window at the starting
    $('#close-noc-card').hide();//Hide collection close button at the starting
    $('#submit_noc').hide();//Hide Submit button at the starting, because submit is only for collection

    $('.mem_relation_name').hide(); //Hide member name dropdown until chooses noc member
    $('.mem_name').hide(); //Hide member name input until chooses noc member

    OnLoadFunctions();

    var cus_pic = $('#cuspicupd').val();
    $('#imgshow').attr('src', 'uploads/request/customer/' + cus_pic);
});


function OnLoadFunctions() {
    const cus_id = $('#cusidupd').val();

    $.ajax({
        //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
        url: 'nocFile/getLoanListWithClosed.php',
        data: { 'cus_id': cus_id, 'screen': 'noc' },
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
            $('#submit_noc').hide();
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
                }).then(function () {
                    var sign_check = [];
                    $('.sign_check').click(function () {
                        if (this.checked) {

                            sign_check.push($(this).attr('data-value'));

                            // put current date in date of noc when checked
                            let d = new Date();
                            let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                            $(this).parent().prev().children().text(currDate);
                            //show person type choosing dropdown
                            $(this).parent().prev().prev().children().attr('required');

                        } else {
                            let indexToRemove = sign_check.indexOf($(this).attr('data-value'));
                            if (indexToRemove !== -1) {
                                sign_check.splice(indexToRemove, 1);
                            }
                            //remove date in span element
                            $(this).parent().prev().children().text('');
                            //hide person type choosing dropdown
                            $(this).parent().prev().prev().children().removeAttr('required');//remove required attribute
                            //empty name td
                        }

                        sign_check.sort(function (a, b) {
                            return a - b;
                        });
                        //store checked data
                        $('#sign_checklist').val(sign_check.join(','));
                    });
                });
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
                }).then(function () {
                    var cheque_check = [];
                    $('.cheque_check').click(function () {
                        if (this.checked) {
                            cheque_check.push($(this).attr('data-value'));

                            // put current date in date of noc when checked
                            let d = new Date();
                            let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                            $(this).parent().prev().children().text(currDate);

                            //show person type choosing dropdown
                            $(this).parent().prev().prev().children().show();

                        } else {
                            let indexToRemove = cheque_check.indexOf($(this).attr('data-value'));
                            if (indexToRemove !== -1) {
                                cheque_check.splice(indexToRemove, 1);
                            }

                            //remove date in span element
                            $(this).parent().prev().children().text('');
                            //hide person type choosing dropdown
                            $(this).parent().prev().prev().children().hide();
                            $(this).parent().prev().prev().children().val(''); // empty type dropdown
                            //empty name td
                        }
                        cheque_check.sort(function (a, b) {
                            return a - b;
                        });
                        $('#cheque_checklist').val(cheque_check.join(','));
                    });
                });
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
                }).then(function () {
                    var mort_check = [];
                    $('.mort_check').click(function () {
                        var val = $(this).parent().prev().prev().text();
                        if (this.checked) {
                            mort_check.push(checkvalues(val));

                            // put current date in date of noc when checked
                            let d = new Date();
                            let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                            $(this).parent().prev().children().text(currDate);

                            //show person type choosing dropdown
                            $(this).parent().prev().prev().children().show();
                        } else {
                            let indexToRemove = mort_check.indexOf(checkvalues(val));
                            if (indexToRemove !== -1) {
                                mort_check.splice(indexToRemove, 1);
                            }

                            //remove date in span element
                            $(this).parent().prev().children().text('');
                            //hide person type choosing dropdown
                            $(this).parent().prev().prev().children().hide();
                            $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        }
                        function checkvalues(val) {
                            if (val == 'Mortgage Process') {
                                var noc = 'Mortgage Process noc';
                            } else if (val == 'Mortgage Document') {
                                var noc = 'Mortgage Document noc';
                            }
                            return noc;
                        }
                        $('#mort_checklist').val(mort_check.join(','));
                    });
                });
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
                }).then(function () {
                    var endorse_check = [];
                    $('.endorse_check').click(function () {
                        var val = $(this).parent().prev().prev().text();
                        if (this.checked) {
                            endorse_check.push(checkvalues(val));

                            // put current date in date of noc when checked
                            let d = new Date();
                            let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                            $(this).parent().prev().children().text(currDate);

                            //show person type choosing dropdown
                            $(this).parent().prev().prev().children().show();
                        } else {
                            let indexToRemove = endorse_check.indexOf(checkvalues(val));
                            if (indexToRemove !== -1) {
                                endorse_check.splice(indexToRemove, 1);
                            }

                            //remove date in span element
                            $(this).parent().prev().children().text('');
                            //hide person type choosing dropdown
                            $(this).parent().prev().prev().children().hide();
                            $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        }
                        function checkvalues(val) {
                            if (val == 'Endorsement Process') {
                                var noc = 'Endorsement Process noc';
                            } else if (val == 'RC') {
                                var noc = 'RC noc';
                            } else if (val == 'Key') {
                                var noc = 'Key noc';
                            }
                            return noc;
                        }
                        $('#endorse_checklist').val(endorse_check.join(','));
                    });
                });
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
                }).then(function () {
                    var gold_check = [];
                    $('.gold_check').click(function () {
                        if (this.checked) {
                            gold_check.push($(this).attr('data-value'));
                            // put current date in date of noc when checked
                            let d = new Date();
                            let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                            $(this).parent().prev().children().text(currDate);

                            //show person type choosing dropdown
                            $(this).parent().prev().prev().children().show();

                        } else {
                            let indexToRemove = gold_check.indexOf($(this).attr('data-value'));
                            if (indexToRemove !== -1) {
                                gold_check.splice(indexToRemove, 1);
                            }
                            //remove date in span element
                            $(this).parent().prev().children().text('');
                            //hide person type choosing dropdown
                            $(this).parent().prev().prev().children().hide();
                            $(this).parent().prev().prev().children().val(''); // empty type dropdown
                            //empty name td
                        }
                        gold_check.sort(function (a, b) {
                            return a - b;
                        });
                        $('#gold_checklist').val(gold_check.join(','));
                    });
                });
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
                }).then(function () {
                    var doc_check = [];
                    $('.doc_check').click(function () {
                        if (this.checked) {
                            doc_check.push($(this).attr('data-value'));

                            // put current date in date of noc when checked
                            let d = new Date();
                            let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                            $(this).parent().prev().children().text(currDate);

                            //show person type choosing dropdown
                            $(this).parent().prev().prev().children().show();

                        } else {
                            let indexToRemove = doc_check.indexOf($(this).attr('data-value'));
                            if (indexToRemove !== -1) {
                                doc_check.splice(indexToRemove, 1);
                            }
                            //remove date in span element
                            $(this).parent().prev().children().text('');
                            //hide person type choosing dropdown
                            $(this).parent().prev().prev().children().hide();
                            $(this).parent().prev().prev().children().val(''); // empty type dropdown
                            //empty name td

                        }
                        doc_check.sort(function (a, b) {
                            return a - b;
                        });
                        $('#doc_checklist').val(doc_check.join(','));
                    });
                });
            }

            // Run all AJAX calls in parallel and wait for all to complete
            Promise.all([
                getSignedDocList(),
                getChequeDocList(),
                getMortgageList(),
                getEndorsementList(),
                getGoldList(),
                getDocumentList()
            ]).then(() => {
                // ✅ All lists are loaded — safe to do final check
                var sign_checkDisabled = $('.sign_check:disabled').length === $('.sign_check').length;
                var cheque_checkDisabled = $('.cheque_check:disabled').length === $('.cheque_check').length;
                var gold_checkDisabled = $('.gold_check:disabled').length === $('.gold_check').length;
                var mort_checkDisabled = $('.mort_check:disabled').length === $('.mort_check').length;
                var endorse_checkDisabled = $('.endorse_check:disabled').length === $('.endorse_check').length;
                var doc_checkDisabled = $('.doc_check:disabled').length === $('.doc_check').length;

                if (sign_checkDisabled && cheque_checkDisabled && gold_checkDisabled && mort_checkDisabled && endorse_checkDisabled && doc_checkDisabled) {
                    $('#submit_noc').hide();
                } else {
                    $('#submit_noc').show();
                }

            }).catch(err => {
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

        $(document).on('click', '.noc-replace', function(event){
            event.preventDefault();
            let reqId = $(this).data('value');
            let cusId = $('#cusidupd').val();

            Swal.fire({
				title: 'Are your sure to replace this NOC?',
				text: 'This action cannot be reverted!',
				icon: 'question',
				showConfirmButton: true,
				showCancelButton: true,
				confirmButtonColor: '#009688',
				cancelButtonColor: '#cc4444',
				cancelButtonText: 'No',
				confirmButtonText: 'Yes'
			}).then(function(result) {
				if (result.isConfirmed) {
                    $.post('nocFile/nocReplace.php', {reqId, cusId}, function(response){
                        if(response.status =='success'){
                            OnLoadFunctions();

                        } else{
                            alert("Replace Failed.");
                            
                        }
                    },'json');
				}
			});

        });

    })//Ajax done End

}//Auto Load function END

function validations() {
    var res = true;
    var sign_checklist = $('#sign_checklist').val(); var cheque_checklist = $('#cheque_checklist').val(); var gold_checklist = $('#gold_checklist').val();
    var mort_checklist = $('#mort_checklist').val(); var endorse_checklist = $('#endorse_checklist').val(); var doc_checklist = $('#doc_checklist').val();
    var sign_checkDisabled = $('.sign_check:disabled').length === $('.sign_check').length;
    var cheque_checkDisabled = $('.cheque_check:disabled').length === $('.cheque_check').length;
    var gold_checkDisabled = $('.gold_check:disabled').length === $('.gold_check').length;
    var mort_checkDisabled = $('.mort_check:disabled').length === $('.mort_check').length;
    var endorse_checkDisabled = $('.endorse_check:disabled').length === $('.endorse_check').length;
    var doc_checkDisabled = $('.doc_check:disabled').length === $('.doc_check').length;

    if (sign_checklist == '' && cheque_checklist == '' && gold_checklist == '' && mort_checklist == '' && endorse_checklist == '' && doc_checklist == '') {
        if (sign_checkDisabled != true) {
            $('.sign_checklistCheck').show()
            event.preventDefault();
            res = false;
        } else {
            $('.sign_checklistCheck').hide()
        }

        if (cheque_checkDisabled != true) {
            $('.cheque_checklistCheck').show()
            event.preventDefault();
            res = false;
        } else {
            $('.cheque_checklistCheck').hide()
        }

        if (gold_checkDisabled != true) {
            $('.gold_checklistCheck').show()
            event.preventDefault();
            res = false;
        } else {
            $('.gold_checklistCheck').hide()
        }

        if (mort_checkDisabled != true) {
            $('.mort_checklistCheck').show()
            event.preventDefault();
            res = false;
        } else {
            $('.mort_checklistCheck').hide()
        }

        if (endorse_checkDisabled != true) {
            $('.endorse_checklistCheck').show()
            event.preventDefault();
            res = false;
        } else {
            $('.endorse_checklistCheck').hide()
        }

        if (doc_checkDisabled != true) {
            $('.endorse_checklistCheck').show()
            event.preventDefault();
            res = false;
        } else {
            $('.doc_checklistCheck').hide()
        }

    } 
    return res;
}

// function to update checked document's noc person and name and date to respective tables
function updateCheckedDetails() {

    let req_id = $('#req_id').val();

    let payload = {
        req_id: req_id,
        // id lists (needed for noc_given update)
        sign: [],
        cheque: [],
        mort: [],
        endorse: [],
        gold: [],
        other: []
    };

    // SIGN
    $('.sign_check').each(function () {
        if (this.checked && !this.disabled) {
            payload.sign.push([$(this).attr('data-value')]);
        }
    });

    // CHEQUE
    $('.cheque_check').each(function () {
        if (this.checked && !this.disabled) {
            payload.cheque.push([$(this).attr('data-value')]);
        }
    });

    // MORTGAGE
    $('.mort_check').each(function () {
        if (this.checked && !this.disabled) {
            payload.mort.push([
                $(this).attr('data-value'),
                $(this).attr('data-thing')
            ]);
        }
    });

    // ENDORSE
    $('.endorse_check').each(function () {
        if (this.checked && !this.disabled) {
            payload.endorse.push([
                $(this).attr('data-value'),
                $(this).attr('data-thing')
            ]);
        }
    });

    // GOLD
    $('.gold_check').each(function () {
        if (this.checked && !this.disabled) {
            payload.gold.push([
                $(this).attr('data-value'),
                $(this).parent().prev().prev().children().val(),
                $(this).parent().prev().children().val()
            ]);
        }
    });

    // OTHER DOCS
    $('.doc_check').each(function () {
        if (this.checked && !this.disabled) {
            payload.other.push([$(this).attr('data-value')]);
        }
    });

    // NOW SEND ONE AJAX
    return $.ajax({
        url: "nocFile/updateSignDocNoc.php",
        type: "POST",
        data: { data: JSON.stringify(payload) },
        cache: false
    });
}

function updateNocTable() {

    let cusidupd = $('#cusidupd').val();
    let req_id = $('#req_id').val();
    let sign_checklist = $('#sign_checklist').val();
    let cheque_checklist = $('#cheque_checklist').val();
    let gold_checklist = $('#gold_checklist').val();
    let mort_checklist = $('#mort_checklist').val();
    let endorse_checklist = $('#endorse_checklist').val();
    let doc_checklist = $('#doc_checklist').val();

    var formData = new FormData();
    formData.append('cusidupd', cusidupd);
    formData.append('req_id', req_id);
    formData.append('sign_checklist', sign_checklist);
    formData.append('cheque_checklist', cheque_checklist);
    formData.append('gold_checklist', gold_checklist);
    formData.append('mort_checklist', mort_checklist);
    formData.append('endorse_checklist', endorse_checklist);
    formData.append('doc_checklist', doc_checklist);

    // ⭐ Return the AJAX promise
    return $.ajax({
        url: 'nocFile/updateNocTable.php',
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

function famNameList() {  // To show family name for Data Check.
    var cus_name = $('#cus_name').val();
    var cus_id = $('#cusidupd').val();//customer id

    $.ajax({
        url: 'verificationFile/verification_datacheck_name.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#check_name").empty();
            $('#check_name').append("<option value=''> Select Name </option>")
            $('#check_name').append("<option value='" + cus_name + "'> " + cus_name + " - Customer </option>");//Current Customer Name
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let name = response[i]['fam_name'];
                let relationship = response[i]['relationship'];
                $('#check_name').append("<option value='" + name + "'> " + name + " - " + relationship + " </option>")
            }
        }
    });
}

function mobileList() { // To show Mobile No for Data Checking.
    var mobile1 = $('#mobile').val();
    var cus_id = $('#cusidupd').val();//customer id

    $.ajax({
        url: 'verificationFile/verification_datacheck_name.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#check_mobileno").empty();
            $('#check_mobileno').append("<option value=''> Select Mobile Number </option>")
            $('#check_mobileno').append("<option value='" + mobile1 + "'> " + mobile1 + " - Customer  </option>");//Current Customer Number
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let no = response[i]['mobile'];
                let relationship = response[i]['relationship'];
                $('#check_mobileno').append("<option value='" + no + "'> " + no + " - " + relationship + " </option>")
            }
        }
    });
}

function aadharList() {   // To show Aadhar No for Data Checking.
    var cus_name = $('#cus_name').val();//Customer name for display
    var cus_id = $('#cusidupd').val();//customer adhar for 

    $.ajax({
        url: 'verificationFile/verification_datacheck_name.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#check_aadhar").empty();
            $('#check_aadhar').append("<option value=''> Select Aadhar Number</option>")
            $('#check_aadhar').append("<option value='" + cus_id + "'> " + cus_name + " - Customer </option>");//Current Customer Aadhaar
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let aadhar = response[i]['aadhar'];
                let fam_name = response[i]['fam_name'];
                let relationship = response[i]['relationship'];
                $('#check_aadhar').append("<option value='" + aadhar + "'> " + fam_name + " - " + relationship + " </option>")
            }
        }
    });
}