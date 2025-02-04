$(document).ready(function () {

    $('#close-noc-card').click(function () {
        $('.noc-card').hide(); //Hide collection window at the starting
        $(this).hide();//hide close button also
        $('#submit_noc').hide();//Hide Submit button at the starting, because submit is only for collection
        $('#back-button').show(); //Show Back button
        $('.loanlist_card').show(); // Show loan list
    })

    $('#noc_member').change(function () {
        $('.scanBtn').removeAttr('disabled');
        $('#compare_finger').val('')
        var noc_member = parseInt($(this).val());
        var req_id = $('#req_id').val();
        //if Noc Member is Family member or Guarentor then get member names
        if (noc_member > 1) {
            $.ajax({
                url: 'nocFile/getMemberDetails.php',
                data: { 'req_id': req_id, 'noc_member': noc_member },
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

    var mortgage_process = $('#mortgage_process').val()
    var endorsement_process = $('#endorsement_process').val()
    if (mortgage_process == '1') {
        $('.mort_proc').hide();
    }
    if (endorsement_process == '1') {
        $('.endor_proc').hide();
    }

    $('#submit_noc').click(function () {

        event.preventDefault();
        if (validations() == true) {
            updateCheckedDetails();
            updateNocTable();
        }
    })

    $('#category').on('change', function () {

        let category = $('#category').val();
        $("#check_name, #check_mobileno, #check_aadhar").empty();
        $("#cus_check, #fam_check, #group_check").empty();

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
        $("#cus_check, #fam_check, #group_check").empty();

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

            $.ajax({
                url: 'verificationFile/verification_group_datacheck.php',
                type: 'POST',
                data: { "name": name, "req_id": req_id, "category": category },
                cache: false,
                success: function (html) {
                    $("#group_check").empty();
                    $("#group_check").html(html);
                }
            });
        }

    })

})//Document Ready End


//On Load Event
$(function () {

    $('.noc-card').hide(); //Hide collection window at the starting
    $('#close-noc-card').hide();//Hide collection close button at the starting
    $('#submit_noc').hide();//Hide Submit button at the starting, because submit is only for collection

    $('.mem_relation_name').hide(); //Hide member name dropdown until chooses noc member
    $('.mem_name').hide(); //Hide member name input until chooses noc member

    var req_id = $('#idupd').val()
    const cus_id = $('#cusidupd').val()
    OnLoadFunctions(req_id, cus_id);

    var cus_pic = $('#cuspicupd').val();
    $('#imgshow').attr('src', 'uploads/request/customer/' + cus_pic);
})

function OnLoadFunctions(req_id, cus_id) {

    $.ajax({
        //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
        url: 'nocFile/getLoanListWithClosed.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
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
            $('#submit_noc').show();//show submit button

            var req_id = $(this).attr('data-value');
            $('#req_id').val(req_id) //assigning to req_id input box for getching noc members

            //To get the Signed Document List on Checklist
            const cus_name = $('#cus_name').val();
            $.ajax({
                url: 'nocFile/getSignedDocList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#signDocDiv').empty()
                    $('#signDocDiv').html(response);
                    if ($('#signDocTable tbody tr').length == 0) {
                        $('.signedRow').hide();
                        $('.signedRow').next().hide();
                    } else {
                        $('.signedRow').show();
                        $('.signedRow').next().show();
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
                        $(this).parent().prev().prev().prev().children().text(currDate);

                        //show person type choosing dropdown
                        $(this).parent().prev().prev().children().show();
                        $(this).parent().prev().prev().children().attr('required');

                    } else {
                        let indexToRemove = sign_check.indexOf($(this).attr('data-value'));
                        if (indexToRemove !== -1) {
                            sign_check.splice(indexToRemove, 1);
                        }
                        //remove date in span element
                        $(this).parent().prev().prev().prev().children().text('');
                        //hide person type choosing dropdown
                        $(this).parent().prev().prev().children().hide();
                        $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        $(this).parent().prev().prev().children().removeAttr('required');//remove required attribute
                        //empty name td
                        $(this).parent().prev().empty();
                    }

                    sign_check.sort(function (a, b) {
                        return a - b;
                    });
                    //store checked data
                    $('#sign_checklist').val(sign_check.join(','));
                })
                $('.sign_noc_per').change(function () {
                    let sign_noc_per = $(this).val(); let noc_name = $(this).parent().next();
                    if (sign_noc_per != '') {
                        if (sign_noc_per == 1) {
                            noc_name.html(`<input type='text' class='form-control' value='` + cus_name + `' readonly>`)
                            // noc_name.html(`<input type='hidden' class='sign_noc_name' value='`+cus_id+`'><input type='text' class='form-control' value='`+cus_name+`' readonly>`)
                        } else if (sign_noc_per == 2) {
                            $.ajax({
                                url: 'nocFile/getFamDetails.php',
                                data: { 'cus_id': cus_id, 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    let element = `<select id='sign_noc_name' name='sign_noc_name' class="form-control sign_noc_name" required><option value=''>Select Type</option>`;
                                    $.each(response, function (index, value) {
                                        element += `<option value='` + value['fam_id'] + `'>` + value['fam_name'] + ` - ` + value['relationship'] + `</option>`;
                                    })
                                    element += `</select>`;
                                    noc_name.html(element);
                                }
                            })
                        }
                    } else {
                        noc_name.empty();// empty td of name
                    }
                })
            })

            // To get the unused Cheque List on Checklist
            $.ajax({
                url: 'nocFile/getChequeDocList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#chequeDiv').empty()
                    $('#chequeDiv').html(response);
                    if ($('#chequeTable tbody tr').length == 0) {
                        $('.chequeRow').hide();
                        $('.chequeRow').next().hide();
                    } else {
                        $('.chequeRow').show();
                        $('.chequeRow').next().show();
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
                        $(this).parent().prev().prev().prev().children().text(currDate);

                        //show person type choosing dropdown
                        $(this).parent().prev().prev().children().show();

                    } else {
                        let indexToRemove = cheque_check.indexOf($(this).attr('data-value'));
                        if (indexToRemove !== -1) {
                            cheque_check.splice(indexToRemove, 1);
                        }

                        //remove date in span element
                        $(this).parent().prev().prev().prev().children().text('');
                        //hide person type choosing dropdown
                        $(this).parent().prev().prev().children().hide();
                        $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        //empty name td
                        $(this).parent().prev().empty();
                    }
                    cheque_check.sort(function (a, b) {
                        return a - b;
                    });
                    $('#cheque_checklist').val(cheque_check.join(','));
                });

                $('.cheque_noc_per').change(function () {
                    let cheque_noc_per = $(this).val(); let noc_name = $(this).parent().next();
                    if (cheque_noc_per != '') {
                        if (cheque_noc_per == 1) {
                            noc_name.html(`<input type='text' class='form-control' value='` + cus_name + `' readonly>`)
                        } else if (cheque_noc_per == 2) {
                            $.ajax({
                                url: 'nocFile/getFamDetails.php',
                                data: { 'cus_id': cus_id, 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    let element = `<select id='cheque_noc_name' name='cheque_noc_name' class="form-control cheque_noc_name"><option value=''>Select Type</option>`;
                                    $.each(response, function (index, value) {
                                        element += `<option value='` + value['fam_id'] + `'>` + value['fam_name'] + ` - ` + value['relationship'] + `</option>`;
                                    })
                                    element += `</select>`;
                                    noc_name.html(element);
                                }
                            })
                        }
                    } else {
                        noc_name.empty();// empty td of name
                    }
                })
            })

            // To get the Mortgage List on Checklist
            $.ajax({
                url: 'nocFile/getMortgageList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#mortgageDiv').empty()
                    $('#mortgageDiv').html(response);
                    if ($('#mortgageTable tbody tr').length == 0) {
                        $('.mortRow').hide();
                        $('.mortRow').next().hide();
                    } else {
                        $('.mortRow').show();
                        $('.mortRow').next().show();
                    }
                }
            }).then(function () {
                var mort_check = [];
                $('.mort_check').click(function () {
                    var val = $(this).parent().prev().prev().prev().prev().text();
                    if (this.checked) {
                        mort_check.push(checkvalues(val));

                        // put current date in date of noc when checked
                        let d = new Date();
                        let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                        $(this).parent().prev().prev().prev().children().text(currDate);

                        //show person type choosing dropdown
                        $(this).parent().prev().prev().children().show();
                    } else {
                        let indexToRemove = mort_check.indexOf(checkvalues(val));
                        if (indexToRemove !== -1) {
                            mort_check.splice(indexToRemove, 1);
                        }

                        //remove date in span element
                        $(this).parent().prev().prev().prev().children().text('');
                        //hide person type choosing dropdown
                        $(this).parent().prev().prev().children().hide();
                        $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        //empty name td
                        $(this).parent().prev().empty();
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
                $('.mort_noc_per').change(function () {
                    let mort_noc_per = $(this).val(); let noc_name = $(this).parent().next();
                    if (mort_noc_per != '') {
                        if (mort_noc_per == 1) {
                            noc_name.html(`<input type='text' class='form-control' value='` + cus_name + `' readonly>`)
                        } else if (mort_noc_per == 2) {
                            $.ajax({
                                url: 'nocFile/getFamDetails.php',
                                data: { 'cus_id': cus_id, 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    let element = `<select id='mort_noc_name' name='mort_noc_name' class="form-control mort_noc_name"><option value=''>Select Type</option>`;
                                    $.each(response, function (index, value) {
                                        element += `<option value='` + value['fam_id'] + `'>` + value['fam_name'] + ` - ` + value['relationship'] + `</option>`;
                                    })
                                    element += `</select>`;
                                    noc_name.html(element);
                                }
                            })
                        }
                    } else {
                        noc_name.empty();// empty td of name
                    }
                })
            })

            // To get the Endorsement List on Checklist
            $.ajax({
                url: 'nocFile/getEndorsementList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#endorsementDiv').empty()
                    $('#endorsementDiv').html(response);
                    if ($('#endorsementTable tbody tr').length == 0) {
                        $('.endRow').hide();
                        $('.endRow').next().hide();
                    } else {
                        $('.endRow').show();
                        $('.endRow').next().show();
                    }
                }
            }).then(function () {
                var endorse_check = [];
                $('.endorse_check').click(function () {
                    var val = $(this).parent().prev().prev().prev().prev().text();
                    if (this.checked) {
                        endorse_check.push(checkvalues(val));

                        // put current date in date of noc when checked
                        let d = new Date();
                        let currDate = d.getDate() + "-" + (d.getMonth() + 1).toString().padStart(2, "0") + "-" + d.getFullYear();
                        $(this).parent().prev().prev().prev().children().text(currDate);

                        //show person type choosing dropdown
                        $(this).parent().prev().prev().children().show();
                    } else {
                        let indexToRemove = endorse_check.indexOf(checkvalues(val));
                        if (indexToRemove !== -1) {
                            endorse_check.splice(indexToRemove, 1);
                        }

                        //remove date in span element
                        $(this).parent().prev().prev().prev().children().text('');
                        //hide person type choosing dropdown
                        $(this).parent().prev().prev().children().hide();
                        $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        //empty name td
                        $(this).parent().prev().empty();
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
                $('.endorse_noc_per').change(function () {
                    let endorse_noc_per = $(this).val(); let noc_name = $(this).parent().next();
                    if (endorse_noc_per != '') {
                        if (endorse_noc_per == 1) {
                            noc_name.html(`<input type='text' class='form-control' value='` + cus_name + `' readonly>`)
                        } else if (endorse_noc_per == 2) {
                            $.ajax({
                                url: 'nocFile/getFamDetails.php',
                                data: { 'cus_id': cus_id, 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    let element = `<select id='endorse_noc_name' name='endorse_noc_name' class="form-control endorse_noc_name"><option value=''>Select Type</option>`;
                                    $.each(response, function (index, value) {
                                        element += `<option value='` + value['fam_id'] + `'>` + value['fam_name'] + ` - ` + value['relationship'] + `</option>`;
                                    })
                                    element += `</select>`;
                                    noc_name.html(element);
                                }
                            })
                        }
                    } else {
                        noc_name.empty();// empty td of name
                    }
                })
            })
            // To get the Gold List on Checklist
            $.ajax({
                url: 'nocFile/getGoldList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#goldDiv').empty()
                    $('#goldDiv').html(response);
                    if ($('#goldTable tbody tr').length == 1) {
                        $('.goldRow').hide();
                        $('.goldRow').next().hide();
                    } else {
                        $('.goldRow').show();
                        $('.goldRow').next().show();
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
                        $(this).parent().prev().prev().prev().children().text(currDate);

                        //show person type choosing dropdown
                        $(this).parent().prev().prev().children().show();

                    } else {
                        let indexToRemove = gold_check.indexOf($(this).attr('data-value'));
                        if (indexToRemove !== -1) {
                            gold_check.splice(indexToRemove, 1);
                        }
                        //remove date in span element
                        $(this).parent().prev().prev().prev().children().text('');
                        //hide person type choosing dropdown
                        $(this).parent().prev().prev().children().hide();
                        $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        //empty name td
                        $(this).parent().prev().empty();
                    }
                    gold_check.sort(function (a, b) {
                        return a - b;
                    });
                    $('#gold_checklist').val(gold_check.join(','));
                });
                $('.gold_noc_per').change(function () {
                    let gold_noc_per = $(this).val(); let noc_name = $(this).parent().next();
                    if (gold_noc_per != '') {
                        if (gold_noc_per == 1) {
                            noc_name.html(`<input type='text' class='form-control' value='` + cus_name + `' readonly>`)
                        } else if (gold_noc_per == 2) {
                            $.ajax({
                                url: 'nocFile/getFamDetails.php',
                                data: { 'cus_id': cus_id, 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    let element = `<select id='gold_noc_name' name='gold_noc_name' class="form-control gold_noc_name"><option value=''>Select Type</option>`;
                                    $.each(response, function (index, value) {
                                        element += `<option value='` + value['fam_id'] + `'>` + value['fam_name'] + ` - ` + value['relationship'] + `</option>`;
                                    })
                                    element += `</select>`;
                                    noc_name.html(element);
                                }
                            })
                        }
                    } else {
                        noc_name.empty();// empty td of name
                    }
                })
            })
            // To get the Document List on Checklist
            $.ajax({
                url: 'nocFile/getDocumentList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#documentDiv').empty()
                    $('#documentDiv').html(response);
                    if ($('#documentTable tbody tr').length == 0) {
                        $('.docRow').hide();
                        $('.docRow').next().hide();
                    } else {
                        $('.docRow').show();
                        $('.docRow').next().show();
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
                        $(this).parent().prev().prev().prev().children().text(currDate);

                        //show person type choosing dropdown
                        $(this).parent().prev().prev().children().show();

                    } else {
                        let indexToRemove = doc_check.indexOf($(this).attr('data-value'));
                        if (indexToRemove !== -1) {
                            doc_check.splice(indexToRemove, 1);
                        }
                        //remove date in span element
                        $(this).parent().prev().prev().prev().children().text('');
                        //hide person type choosing dropdown
                        $(this).parent().prev().prev().children().hide();
                        $(this).parent().prev().prev().children().val(''); // empty type dropdown
                        //empty name td
                        $(this).parent().prev().empty();
                    }
                    doc_check.sort(function (a, b) {
                        return a - b;
                    });
                    $('#doc_checklist').val(doc_check.join(','));
                });
                $('.doc_noc_per').change(function () {
                    let doc_noc_per = $(this).val(); let noc_name = $(this).parent().next();
                    if (doc_noc_per != '') {
                        if (doc_noc_per == 1) {
                            noc_name.html(`<input type='text' class='form-control' value='` + cus_name + `' readonly>`)
                        } else if (doc_noc_per == 2) {
                            $.ajax({
                                url: 'nocFile/getFamDetails.php',
                                data: { 'cus_id': cus_id, 'req_id': req_id },
                                type: 'post',
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    let element = `<select id='doc_noc_name' name='doc_noc_name' class="form-control doc_noc_name"><option value=''>Select Type</option>`;
                                    $.each(response, function (index, value) {
                                        element += `<option value='` + value['fam_id'] + `'>` + value['fam_name'] + ` - ` + value['relationship'] + `</option>`;
                                    })
                                    element += `</select>`;
                                    noc_name.html(element);
                                }
                            })
                        }
                    } else {
                        noc_name.empty();// empty td of name
                    }
                })
            })

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

            setTimeout(() => {
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
            }, 1000);

        })//Window onclick end

    })//Ajax done End

}//Auto Load function END


function validations() {
    var res = true;
    var noc_member = $('#noc_member').val(); var mem_relation_name = $('#mem_relation_name').val(); var fingerprint = $('.scanBtn').attr('disabled');
    var sign_checklist = $('#sign_checklist').val(); var cheque_checklist = $('#cheque_checklist').val(); var gold_checklist = $('#gold_checklist').val();
    var mort_checklist = $('#mort_checklist').val(); var endorse_checklist = $('#endorse_checklist').val(); var doc_checklist = $('#doc_checklist').val();

    var sign_checkDisabled = $('.sign_check:disabled').length === $('.sign_check').length;
    var cheque_checkDisabled = $('.cheque_check:disabled').length === $('.cheque_check').length;
    var gold_checkDisabled = $('.gold_check:disabled').length === $('.gold_check').length;
    var mort_checkDisabled = $('.mort_check:disabled').length === $('.mort_check').length;
    var endorse_checkDisabled = $('.endorse_check:disabled').length === $('.endorse_check').length;
    var doc_checkDisabled = $('.doc_check:disabled').length === $('.doc_check').length;



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

    if (fingerprint != 'disabled') {
        $('.scanBtnCheck').show()
        event.preventDefault();
        res = false;
    } else {
        $('.scanBtnCheck').hide()
    }

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

    } else {
        $('.sign_check').each(function () {
            if (this.checked && !this.disabled) {
                let person_type = $(this).parent().prev().prev().children().val();
                let person_name = $(this).parent().prev().children().val();
                if (person_type == '' || person_name == '') {
                    event.preventDefault(); res = false;
                    $(this).parent().prev().prev().children().css('border-color', 'red')
                    $(this).parent().prev().children().css('border-color', 'red')
                } else {
                    $(this).parent().prev().prev().children().css('border-color', '')
                    $(this).parent().prev().children().css('border-color', '')
                }
            }
        })
        $('.cheque_check').each(function () {
            if (this.checked && !this.disabled) {
                let person_type = $(this).parent().prev().prev().children().val();
                let person_name = $(this).parent().prev().children().val();
                if (person_type == '' || person_name == '') {
                    event.preventDefault(); res = false;
                    $(this).parent().prev().prev().children().css('border-color', 'red')
                    $(this).parent().prev().children().css('border-color', 'red')
                } else {
                    $(this).parent().prev().prev().children().css('border-color', '')
                    $(this).parent().prev().children().css('border-color', '')
                }
            }
        })
        $('.gold_check').each(function () {
            if (this.checked && !this.disabled) {
                let person_type = $(this).parent().prev().prev().children().val();
                let person_name = $(this).parent().prev().children().val();
                if (person_type == '' || person_name == '') {
                    event.preventDefault(); res = false;
                    $(this).parent().prev().prev().children().css('border-color', 'red')
                    $(this).parent().prev().children().css('border-color', 'red')
                } else {
                    $(this).parent().prev().prev().children().css('border-color', '')
                    $(this).parent().prev().children().css('border-color', '')
                }
            }
        })
        $('.doc_check').each(function () {
            if (this.checked && !this.disabled) {
                let person_type = $(this).parent().prev().prev().children().val();
                let person_name = $(this).parent().prev().children().val();
                if (person_type == '' || person_name == '') {
                    event.preventDefault(); res = false;
                    $(this).parent().prev().prev().children().css('border-color', 'red')
                    $(this).parent().prev().children().css('border-color', 'red')
                } else {
                    $(this).parent().prev().prev().children().css('border-color', '')
                    $(this).parent().prev().children().css('border-color', '')
                }
            }
        })
        $('.mort_check').each(function () {
            if (this.checked && !this.disabled) {
                let person_type = $(this).parent().prev().prev().children().val();
                let person_name = $(this).parent().prev().children().val();
                if (person_type == '' || person_name == '') {
                    event.preventDefault(); res = false;
                    $(this).parent().prev().prev().children().css('border-color', 'red')
                    $(this).parent().prev().children().css('border-color', 'red')
                } else {
                    $(this).parent().prev().prev().children().css('border-color', '')
                    $(this).parent().prev().children().css('border-color', '')
                }
            }
        })
        $('.endorse_check').each(function () {
            if (this.checked && !this.disabled) {
                let person_type = $(this).parent().prev().prev().children().val();
                let person_name = $(this).parent().prev().children().val();
                if (person_type == '' || person_name == '') {
                    event.preventDefault(); res = false;
                    $(this).parent().prev().prev().children().css('border-color', 'red')
                    $(this).parent().prev().children().css('border-color', 'red')
                } else {
                    $(this).parent().prev().prev().children().css('border-color', '')
                    $(this).parent().prev().children().css('border-color', '')
                }
            }
        })
    }
    return res;
}

// function to update checked document's noc person and name and date to respective tables
function updateCheckedDetails() {

    ////////////////////////// For Signed Doc list
    var sign_check = [];
    var i = 0;

    var sign_deffer = $.Deferred(); // Create a deferred object

    $('.sign_check').each(function () {
        if (this.checked && !this.disabled) {
            var innerArray = [];
            innerArray.push($(this).attr('data-value')); // sign_doc_id
            innerArray.push($(this).parent().prev().prev().children().val()); // person type
            innerArray.push($(this).parent().prev().children().val()); // person name
            sign_check.push(innerArray);
            i++;
        }
    });

    // Resolve the deferred object after the each loop is completed
    sign_deffer.resolve();

    // Perform AJAX request when the deferred object is resolved
    sign_deffer.done(function () {
        let sign_ids = $('#sign_checklist').val().split(','); let req_id = $('#req_id').val();
        $.ajax({
            url: 'nocFile/updateSignDocNoc.php',
            data: { 'noc_details': sign_check, 'table_name': 'signed_doc', sign_ids, req_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // Handle the AJAX response
                if (response == "Success") {

                } else {
                    event.preventDefault();
                }
            }
        });
    });

    ////////////////////////// For Cheque Doc list
    var cheque_check = [];
    var i = 0;

    var cheque_deffer = $.Deferred(); // Create a deferred object

    $('.cheque_check').each(function () {
        if (this.checked && !this.disabled) {
            var innerArray = [];
            innerArray.push($(this).attr('data-value')); // cheque_doc_id
            innerArray.push($(this).parent().prev().prev().children().val()); // person type
            innerArray.push($(this).parent().prev().children().val()); // person name
            cheque_check.push(innerArray);
            i++;
        }
    });

    // Resolve the deferred object after the each loop is completed
    cheque_deffer.resolve();

    // Perform AJAX request when the deferred object is resolved
    cheque_deffer.done(function () {
        let cheque_ids = $('#cheque_checklist').val().split(','); let req_id = $('#req_id').val();
        $.ajax({
            url: 'nocFile/updateSignDocNoc.php',
            data: { 'noc_details': cheque_check, 'table_name': 'cheque_no_list', cheque_ids, req_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // Handle the AJAX response
                if (response == "Success") {

                } else {
                    event.preventDefault();
                }
            }
        });
    });

    ////////////////////////// For Mortgage Doc list
    var mort_check = [];
    var i = 0;

    var mort_deffer = $.Deferred(); // Create a deferred object

    $('.mort_check').each(function () {
        if (this.checked && !this.disabled) {
            var innerArray = [];
            innerArray.push($(this).attr('data-value')); // mort_doc_id
            innerArray.push($(this).attr('data-thing')); // Mot process or document
            innerArray.push($(this).parent().prev().prev().children().val()); // person type
            innerArray.push($(this).parent().prev().children().val()); // person name
            mort_check.push(innerArray);
            i++;
        }
    });

    // Resolve the deferred object after the each loop is completed
    mort_deffer.resolve();

    // Perform AJAX request when the deferred object is resolved
    mort_deffer.done(function () {
        let mort_ids = $('#mort_checklist').val().split(','); let req_id = $('#req_id').val();
        $.ajax({
            url: 'nocFile/updateSignDocNoc.php',
            data: { 'noc_details': mort_check, 'table_name': 'acknowlegement_documentation', mort_ids, req_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // Handle the AJAX response
                if (response == "Success") {

                } else {
                    event.preventDefault();
                }
            }
        });
    });

    ////////////////////////// For Endorsement Doc list
    var endorse_check = [];
    var i = 0;

    var endorse_deffer = $.Deferred(); // Create a deferred object

    $('.endorse_check').each(function () {
        if (this.checked && !this.disabled) {
            var innerArray = [];
            innerArray.push($(this).attr('data-value')); // endorse_doc_id
            innerArray.push($(this).attr('data-thing')); // Mot process or document
            innerArray.push($(this).parent().prev().prev().children().val()); // person type
            innerArray.push($(this).parent().prev().children().val()); // person name
            endorse_check.push(innerArray);
            i++;
        }
    });

    // Resolve the deferred object after the each loop is completed
    endorse_deffer.resolve();

    // Perform AJAX request when the deferred object is resolved
    endorse_deffer.done(function () {
        let endorse_ids = $('#endorse_checklist').val().split(','); let req_id = $('#req_id').val();
        $.ajax({
            url: 'nocFile/updateSignDocNoc.php',
            data: { 'noc_details': endorse_check, 'table_name': 'acknowlegement_documentation', endorse_ids, req_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // Handle the AJAX response
                if (response == "Success") {

                } else {
                    event.preventDefault();
                }
            }
        });
    });
    ////////////////////////// For Gold Doc list
    var gold_check = [];
    var i = 0;

    var gold_deffer = $.Deferred(); // Create a deferred object

    $('.gold_check').each(function () {
        if (this.checked && !this.disabled) {
            var innerArray = [];
            innerArray.push($(this).attr('data-value')); // gold_doc_id
            innerArray.push($(this).parent().prev().prev().children().val()); // person type
            innerArray.push($(this).parent().prev().children().val()); // person name
            gold_check.push(innerArray);
            i++;
        }
    });

    // Resolve the deferred object after the each loop is completed
    gold_deffer.resolve();

    // Perform AJAX request when the deferred object is resolved
    gold_deffer.done(function () {
        let gold_ids = $('#gold_checklist').val().split(','); let req_id = $('#req_id').val();
        $.ajax({
            url: 'nocFile/updateSignDocNoc.php',
            data: { 'noc_details': gold_check, 'table_name': 'gold_info', gold_ids, req_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // Handle the AJAX response
                if (response == "Success") {

                } else {
                    event.preventDefault();
                }
            }
        });
    });

    ////////////////////////// For Document list
    var doc_check = [];
    var i = 0;

    var doc_deffer = $.Deferred(); // Create a deferred object

    $('.doc_check').each(function () {
        if (this.checked && !this.disabled) {
            var innerArray = [];
            innerArray.push($(this).attr('data-value')); // doc_doc_id
            innerArray.push($(this).parent().prev().prev().children().val()); // person type
            innerArray.push($(this).parent().prev().children().val()); // person name
            doc_check.push(innerArray);
            i++;
        }
    });

    // Resolve the deferred object after the each loop is completed
    doc_deffer.resolve();

    // Perform AJAX request when the deferred object is resolved
    doc_deffer.done(function () {
        let doc_ids = $('#doc_checklist').val().split(','); let req_id = $('#req_id').val();
        $.ajax({
            url: 'nocFile/updateSignDocNoc.php',
            data: { 'noc_details': doc_check, 'table_name': 'document_info', doc_ids, req_id },
            type: 'post',
            cache: false,
            success: function (response) {
                // Handle the AJAX response
                if (response == "Success") {

                } else {
                    event.preventDefault();
                }
            }
        });
    });

}

function updateNocTable() {
    // Get the values from the form fields or variables
    let cusidupd = $('#cusidupd').val();
    let req_id = $('#req_id').val();
    let sign_checklist = $('#sign_checklist').val();
    let cheque_checklist = $('#cheque_checklist').val();
    let gold_checklist = $('#gold_checklist').val();
    let mort_checklist = $('#mort_checklist').val();
    let endorse_checklist = $('#endorse_checklist').val();
    let doc_checklist = $('#doc_checklist').val();
    let noc_date = $('#noc_date').val();
    let noc_member = $('#noc_member').val();
    let mem_name = ''; // Initialize mem_name variable

    // Determine mem_name based on noc_member value
    if (noc_member === '3') {
        mem_name = $('#mem_relation_name').val();
    } else if (noc_member === '1' || noc_member === '2') {
        mem_name = $('#mem_name').val();
    }

    // Create a FormData object and append the data
    var formData = new FormData();
    formData.append('cusidupd', cusidupd);
    formData.append('req_id', req_id);
    formData.append('sign_checklist', sign_checklist);
    formData.append('cheque_checklist', cheque_checklist);
    formData.append('gold_checklist', gold_checklist);
    formData.append('mort_checklist', mort_checklist);
    formData.append('endorse_checklist', endorse_checklist);
    formData.append('doc_checklist', doc_checklist);
    formData.append('noc_date', noc_date);
    formData.append('noc_member', noc_member);
    formData.append('mem_name', mem_name); // Append mem_name

    // Now you can send this formData using AJAX

    $.ajax({
        url: 'nocFile/updateNocTable.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success: function (response) {
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
        }
    })
}

function famNameList() {  // To show family name for Data Check.
    let req_id = $('#req_id').val();
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
    let req_id = $('#req_id').val();
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
    let req_id = $('#req_id').val();
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
            $('#check_aadhar').append("<option value='" + cus_id + "'> " + cus_name + " - Customer </option>");//Current Customer Adhaar
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