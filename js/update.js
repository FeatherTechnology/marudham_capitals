$(document).ready(function () {

    //Show Remark and Address when select other in Relationship.
    $('#relationship').on('change', function () {

        var relation = $('#relationship').val();

        if (relation == 'Other') {
            $("#remark").show();
            $("#address").show();
        }
        else if (relation != 'Other') {
            $("#remark").hide();
            $("#address").hide();
        }

    });

    $('#dob').change(function () {
        let dobirth = $('#dob').val();

        var dob = new Date(dobirth);
        //calculate month difference from current date in time  
        var month_diff = Date.now() - dob.getTime();

        //convert the calculated difference in date format  
        var age_dt = new Date(month_diff);

        //extract year from date      
        var year = age_dt.getUTCFullYear();

        //now calculate the age of the user  
        var age = Math.abs(year - 1970);

        $('#age').val(age); // set value to age.
    })


    $("#state").change(function () {
        var StateSelected = $(this).val();
        var optionsList = getDistrictDropdown(StateSelected);
        districtNameList(optionsList)
    });

    $('#district').change(function () {
        var DistSelected = $(this).val();
        $('#district1').val(DistSelected);
        var talukOption = getTalukDropdown(DistSelected);
        talukNameList(talukOption);
    });

    $('#taluk').change(function () {
        var talukselected = $(this).val();
        $('#taluk1').val(talukselected);
        var area_upd = '';
        getTalukBasedArea(talukselected, area_upd, '#area');
    })

    $('#area').change(function () {
        var areaselected = $('#area').val();
        var sub_area_upd = '';
        getAreaBasedSubArea(areaselected, sub_area_upd, '#sub_area');
    })

    //Area Confirm Card.
    $("#area_state").change(function () {
        var StateSelected = $(this).val();
        var districtOption = getDistrictDropdown(StateSelected);
        conformDistrictNameList(districtOption)
    });

    $('#area_district').change(function () {
        var DistSelected = $(this).val();
        $('#area_district1').val(DistSelected);
        var talukOptionList = getTalukDropdown(DistSelected);
        conformtalukNameList(talukOptionList);
    });

    $('#area_taluk').change(function () {
        var talukselected = $(this).val();
        $('#area_taluk1').val(talukselected);
        var area_upd = '';
        getTalukBasedArea(talukselected, area_upd, '#area_confirm');
    })

    $('#area_confirm').change(function () {
        var areaselected = $(this).val();
        var sub_area_upd = '';
        getAreaBasedSubArea(areaselected, sub_area_upd, '#area_sub_area');
    })

    $('#area_sub_area').change(function () {
        var sub_area_id = $(this).val();
        getGroupandLine(sub_area_id);
    })

    $('#getlatlong').click(function () {
        event.preventDefault();
        navigator.geolocation.getCurrentPosition((position) => {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            $('#latlong').val(latitude + ',' + longitude);
        });
    })

    $('#pic').change(function () {//To show after choose image
        var pic = $('#pic')[0];
        var img = $('#imgshow');
        img.attr('src', URL.createObjectURL(pic.files[0]));
    })

    $('#guarentorpic').change(function () {//To show after choose image
        var pic = $('#guarentorpic')[0];
        var img = $('#imgshows');
        img.attr('src', URL.createObjectURL(pic.files[0]));
    })

    window.onscroll = function () {
        var navbar = document.getElementById("navbar");
        var stickyHeader = navbar.offsetTop;
        if (window.pageYOffset > 500) {
            $('#navbar').fadeIn('fast');
            navbar.classList.add("stickyHeader")
        } else {
            $('#navbar').fadeOut('fast');
            navbar.classList.remove("stickyHeader");
        }
    };


    ///Customer Feedback 
    $("body").on("click", "#cus_feedback_edit", function () {
        let id = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/customer_feedback_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#feedbackID").val(result['id']);
                $("#feedback_label").val(result['feedback_label']);
                $("#cus_feedback").val(result['cus_feedback']);
                $("#feedback_remark").val(result['feedback_remark']);

            }
        });

    });

    $("body").on("click", "#cus_feedback_delete", function () {
        var isok = confirm("Do you want delete this Feedback?");
        if (isok == false) {
            return false;
        } else {
            var id = $(this).attr('value');

            $.ajax({
                url: 'verificationFile/customer_feedback_delete.php',
                type: 'POST',
                data: { "id": id },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");
                    if (delresult) {
                        $('#feedbackDeleteOk').show();
                        setTimeout(function () {
                            $('#feedbackDeleteOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {

                        $('#feedbackDeleteNotOk').show();
                        setTimeout(function () {
                            $('#feedbackDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }

                    resetfeedback();
                }
            });
        }
    });

    // Verification Tab Change Radio buttons
    $('#cus_profile,#documentation,#customer_old').click(function () {
        var verify = $('input[name=verification_type]:checked').val();

        if (verify == 'cus_profile') {
            $('#customer_profile').show(); $('#cus_document').hide(); $('#customer_loan_calc').hide(); $('#customer_old_div').hide();
            // $('.documentation-card').hide();
            $('.edit-document-card').hide();// hide edit document card when not in use
            $('.dropdown').children().css('border-color', '');// to set other dropdown buttons as normal
        }
        if (verify == 'documentation') {
            $('#customer_profile').hide(); $('#cus_document').show(); $('#customer_loan_calc').hide(); $('#customer_old_div').hide();
            // $('.documentation-card').show();
            $('.edit-document-card').hide();
        }
        if (verify == 'customer_old') {
            $('#customer_profile').hide(); $('#cus_document').hide(); $('#customer_loan_calc').hide();
            $('#customer_old_div').show();
            showCustomerOldData();
        }
    })



    ///Documentation 

    $('#Propertyholder_type').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();

        if (type == '0') {
            $('#Propertyholder_name').show();
            $('#Propertyholder_relationship_name').val('');
            $('#Propertyholder_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#Propertyholder_name').val(result['name']);
                    $('#doc_property_relation').val('NIL');
                }
            });

        } else if (type == '1') {
            $('#Propertyholder_name').show();
            $('#Propertyholder_relationship_name').val('');
            $('#Propertyholder_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#Propertyholder_name').val(result['name']);
                    $('#doc_property_relation').val(result['relationship']);
                }
            });

        } else if (type == '2') {
            $('#Propertyholder_name').hide();
            $('#Propertyholder_relationship_name').show();
            $('#Propertyholder_name').val('');
            $('#doc_property_relation').val('');

            getFamilyList('Propertyholder_relationship_name');

        } else {
            $('#Propertyholder_name').show();
            $('#Propertyholder_relationship_name').hide();
            $('#Propertyholder_name').val('');
            $('#doc_property_relation').val('');

        }
    });

    $('#Propertyholder_relationship_name').change(function () {
        let fam_id = $(this).val();
        $.ajax({
            url: 'verificationFile/documentation/find_cheque_relation.php',
            type: 'POST',
            data: { "fam_id": fam_id },
            dataType: 'json',
            success: function (response) {
                $('#doc_property_relation').val(response);

            }
        });
    });

    //Mortgage Document upload show/hide based on select YES/NO.
    $('#mortgage_document').change(function () {
        var docupd = $(this).val();

        if (docupd == '0') {
            $('#mort_doc_upd').show();
            $('#pendingchk').removeAttr('checked')

        } else {
            $('#mortgage_document_upd').val('');//remove selected file from file input box
            $('#mort_doc_upd').hide();
            $('#pendingchk').prop('checked', true);
        }
    })

    //when Mortgage Document pending is Checked then document will empty and Doc is NO////
    $('#pendingchk').click(function () {

        if (this.checked == true) {
            $('#mortgage_document_upd').val('');
            $('#mortgage_document').val('1');
            $('#mort_doc_upd').hide();
        } else {
            $('#mortgage_document').val('0');
            $('#mort_doc_upd').show();
        }
    })

    $('#mortgage_process').change(function () {

        let process = $(this).val();

        if (process == '0') {
            $('#mortgage_div').show();
        } else {
            $('#mortgage_div').hide();

            $('#Propertyholder_type').val('');
            $('#Propertyholder_name').val('');
            $('#Propertyholder_relationship_name').val('');
            $('#doc_property_relation').val('');
            $('#doc_property_pype').val('');
            $('#doc_property_measurement').val('');
            $('#doc_property_location').val('');
            $('#doc_property_value').val('');
            $('#mortgage_name').val('');
            $('#mortgage_dsgn').val('');
            $('#mortgage_nuumber').val('');
            $('#reg_office').val('');
            $('#mortgage_value').val('');
            $('#mortgage_document').val('');
            $('#mortgage_document_upd').val('');
            $('#mortgage_doc_upd').val('');//old uploaded name
        }
    })

    //Endrosement Info 
    $('#owner_type').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();

        if (type == '0') {
            $('#owner_name').show();
            $('#ownername_relationship_name').val('');
            $('#ownername_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#owner_name').val(result['name']);
                    $('#en_relation').val('NIL');
                }
            });

        } else if (type == '1') {
            $('#owner_name').show();
            $('#ownername_relationship_name').val('');
            $('#ownername_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#owner_name').val(result['name']);
                    $('#en_relation').val(result['relationship']);
                }
            });

        } else if (type == '2') {
            $('#owner_name').hide();
            $('#ownername_relationship_name').show();
            $('#owner_name').val('');
            $('#en_relation').val('');

            getFamilyList('ownername_relationship_name');
        } else {
            $('#owner_name').show();
            $('#ownername_relationship_name').hide();
            $('#owner_name').val('');
            $('#en_relation').val('');
        }
    });

    $('#ownername_relationship_name').change(function () {
        let fam_id = $(this).val();
        $.ajax({
            url: 'verificationFile/documentation/find_cheque_relation.php',
            type: 'POST',
            data: { "fam_id": fam_id },
            dataType: 'json',
            success: function (response) {
                $('#en_relation').val(response);

            }
        });
    });

    $('#en_RC').change(function () {
        var rcupd = $(this).val();

        if (rcupd == '0') {
            $('#end_doc_upd').show();
            $('#endorsependingchk').removeAttr('checked');
        } else {
            $('#RC_document_upd').val('');
            $('#end_doc_upd').hide();
            $('#endorsependingchk').prop('checked', true);
        }
    });

    $('#endorsependingchk').click(function () {

        if (this.checked == true) {
            $('#RC_document_upd').val('');
            $('#en_RC').val('1');
            $('#end_doc_upd').hide();
        } else {
            $('#en_RC').val('0');
            $('#end_doc_upd').show();
        }
    })

    $('#endorsement_process').change(function () {

        let process = $(this).val();

        if (process == '0') {
            $('#end_process_div').show();
        } else {
            $('#end_process_div').hide();

            $('#owner_type').val('');
            $('#owner_name').val('');
            $('#ownername_relationship_name').val('');
            $('#en_relation').val('');
            $('#vehicle_type').val('');
            $('#vehicle_process').val('');
            $('#en_Company').val('');
            $('#en_Model').val('');
        }
    })

    //Customer Old Data
    $('#submit_old_cus_data').click(function () {
        if (OldCusValidation()) {
            submitCustomerOldData();
        }
    })

});   ////////Document Ready End

$(function () {
    //  $('.icon-chevron-down1').parent().next('div').slideUp(); //To collapse all card on load

    getImage(); // To show customer image when window onload.

    resetFamInfo(); //Call Family Info Table Initially.
    resetFamDetails();
    closeFamModal();

    resetpropertyInfo() // Property Info Modal Table Reset.
    resetPropertyinfoList() //Property Info List.

    resetbankInfo(); // Bank info Modal Table Reset.
    resetbankinfoList(); //Bank Info List.

    resetkycinfoList(); //KYC Info List.

    //Documentation

    getDocumentHistory();//for document history table


    resetfeedback(); //Reset Feedback Modal Table.
    feedbackList(); // Feedback List.

    getCustomerLoanCounts();//to get closed customer details

    var state_upd = $('#state_upd').val();
    if (state_upd != '') {
        var optionsList = getDistrictDropdown(state_upd);
        districtNameList(optionsList)
    }

    var district_upd = $('#district_upd').val();
    if (district_upd != '') {
        var talukOption = getTalukDropdown(district_upd);
        talukNameList(talukOption);
    }

    var taluk_upd = $('#taluk_upd').val();
    if (taluk_upd != '') {
        var area_upd = $('#area_upd').val();
        getTalukBasedArea(taluk_upd, area_upd, '#area');
    }

    var area_upd = $('#area_upd').val();
    if (area_upd != '') {
        var sub_area_upd = $('#sub_area_upd').val();
        getAreaBasedSubArea(area_upd, sub_area_upd, '#sub_area');
    }

    //Area Confirm Details.
    var area_state_upd = $('#area_state_upd').val();
    if (area_state_upd != '') {
        var districtOption = getDistrictDropdown(area_state_upd);
        conformDistrictNameList(districtOption)
    }

    var area_district_upd = $('#area_district_upd').val();
    if (area_district_upd != '') {
        var talukOptionList = getTalukDropdown(area_district_upd);
        conformtalukNameList(talukOptionList);
    }

    var area_taluk_upd = $('#area_taluk_upd').val();
    if (area_taluk_upd != '') {
        var area_upd = $('#area_confirm_area').val();
        getTalukBasedArea(area_taluk_upd, area_upd, '#area_confirm');
    }

    var area_confirm_area = $('#area_confirm_area').val();
    if (area_confirm_area != '') {
        var sub_area_upd = $('#sub_area_confirm').val();
        getAreaBasedSubArea(area_confirm_area, sub_area_upd, '#area_sub_area');
    }

    var marital_upd = $('#marital_upd').val();
    if (marital_upd == 1) {
        $('.spouse').show();
    } else {
        $('.spouse').hide();
    }

    $('.modalTable').DataTable({
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "createdRow": function (row, data, dataIndex) {
            $(row).find('td:first').html(dataIndex + 1);
        },
        "drawCallback": function (settings) {
            this.api().column(0).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
        },
        {
            extend: 'colvis',
            collectionLayout: 'fixed four-column',
        }
        ],
    });


});

function getImage() { // Cus img show onload.
    let imgName = $('#cus_image').val();
    if (imgName != '') {
        $('#imgshow').attr('src', "uploads/request/customer/" + imgName + " ");
    } else { $('#imgshow').attr('src', 'img/avatar.png'); }

    var guarentorimg = $('#guarentor_image').val();
    if (guarentorimg != '') {
        $('#imgshows').attr('src', "uploads/verification/guarentor/" + guarentorimg + " ");
    } else {
        $('#imgshows').attr('src', 'img/avatar.png');
    }

    closeFamModal();
}

function getCustomerLoanCounts() {
    var cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/getCustomerLoanCounts.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#cus_loan_count').val(response['loan_count'])
            $('#cus_frst_loanDate').val(response['first_loan'])
            $('#cus_travel_cmpy').val(response['travel'])
            $('#cus_exist_type').val(response['existing_type'])
        },
        error: function () {
            $('#cus_exist_type').val('Renewal');
        }
    })
}
// Modal Box for Agent Group

$(document).on("click", "#submitFamInfoBtn", function () {
    let cus_id = $('#cus_id').val();
    let famname = $("#famname").val();
    let relationship = $("#relationship").val();
    let other_remark = $("#other_remark").val();
    let other_address = $("#other_address").val();
    let relation_age = $("#relation_age").val();
    let relation_aadhar = $("#relation_aadhar").val();
    let relation_Mobile = $("#relation_Mobile").val();
    let relation_Occupation = $("#relation_Occupation").val();
    let relation_Income = $("#relation_Income").val();
    let relation_Blood = $("#relation_Blood").val();
    let famTableId = $("#famID").val();

    if (famname != "" && relationship != "" && relation_aadhar != "" && relation_Mobile != "" && relation_Mobile.length === 10) {
        $.ajax({
            url: 'updateFile/update_family_submit.php',
            type: 'POST',
            data: { "famname": famname, "realtionship": relationship, "other_remark": other_remark, "other_address": other_address, "relation_age": relation_age, "relation_aadhar": relation_aadhar, "relation_Mobile": relation_Mobile, "relation_Occupation": relation_Occupation, "relation_Income": relation_Income, "relation_Blood": relation_Blood, "famTableId": famTableId, "cus_id": cus_id },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#FamInsertOk').show();
                    setTimeout(function () {
                        $('#FamInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#famUpdateok').show();
                    setTimeout(function () {
                        $('#famUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#NotOk').show();
                    setTimeout(function () {
                        $('#NotOk').fadeOut('fast');
                    }, 2000);
                }

                resetFamInfo();
                resetFamDetails();
                closeFamModal();
            }
        });
    }
    else {
        if (famname == "") {
            $('#famnameCheck').show();
        } else {
            $('#famnameCheck').hide();
        }

        if (relationship == "") {
            $('#famrelationCheck').show();
        } else {
            $('#famrelationCheck').hide();
        }

        if (relationship == "Other" && other_remark == "") {
            $('#famremarkCheck').show();
        } else {
            $('#famremarkCheck').hide();
        }

        if (relationship == "Other" && other_address == "") {
            $('#famaddressCheck').show();
        } else {
            $('#famaddressCheck').hide();
        }

        if (relation_aadhar == "") {
            $('#famaadharCheck').show();
        } else {
            $('#famaadharCheck').hide();
        }

        if (relation_Mobile == "" || relation_Mobile.length < 10) {
            $('#fammobileCheck').show();
        } else {
            $('#fammobileCheck').hide();
        }
    }

});

function resetFamInfo() {
    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verification_fam_reset.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#updatedFamTable").empty();
            $("#updatedFamTable").html(html);

            $("#famname").val('');
            $("#relationship").val('');
            $("#other_remark").val('');
            $("#other_address").val('');
            $("#relation_age").val('');
            $("#relation_aadhar").val('');
            $("#relation_Mobile").val('');
            $("#relation_Occupation").val('');
            $("#relation_Income").val('');
            $("#relation_Blood").val('');
            $("#famID").val('');
        }
    });
}

function resetFamDetails() {

    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verification_fam_list.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#famList").empty();
            $("#famList").html(html);
        }
    });
}

$("body").on("click", "#verification_fam_edit", function () {
    let id = $(this).attr('value');

    $.ajax({
        url: 'verificationFile/verification_fam_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        success: function (result) {

            $("#famID").val(result['id']);
            $("#famname").val(result['fname']);
            $("#relationship").val(result['relation']);
            $("#other_remark").val(result['remark']);
            $("#other_address").val(result['address']);
            $("#relation_age").val(result['age']);
            $("#relation_aadhar").val(result['aadhar']);
            $("#relation_Mobile").val(result['mobileno']);
            $("#relation_Occupation").val(result['occ']);
            $("#relation_Income").val(result['income']);
            $("#relation_Blood").val(result['bg']);
            if (result['relation'] == 'Other') {
                $('#remark').show();
                $('#address').show();
            }
            else {
                $('#remark').hide();
                $('#address').hide();
            }
            $('#famnameCheck').hide(); $('#famrelationCheck').hide(); $('#famremarkCheck').hide(); $('#famaddressCheck').hide(); $('#famageCheck').hide(); $('#famaadharCheck').hide(); $('#fammobileCheck').hide(); $('#famoccCheck').hide(); $('#famincomeCheck').hide();
        }
    });

});

$("body").on("click", "#verification_fam_delete", function () {
    var isok = confirm("Do you want delete this Family Info?");
    if (isok == false) {
        return false;
    } else {
        var famid = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/verification_fam_delete.php',
            type: 'POST',
            data: { "famid": famid },
            cache: false,
            success: function (response) {
                var delresult = response.includes("Deleted");
                if (delresult) {
                    $('#FamDeleteOk').show();
                    setTimeout(function () {
                        $('#FamDeleteOk').fadeOut('fast');
                    }, 2000);
                    resetFamInfo();
                    resetFamDetails();
                    closeFamModal();
                }
                else {

                    $('#FamDeleteNotOk').show();
                    setTimeout(function () {
                        $('#FamDeleteNotOk').fadeOut('fast');
                    }, 2000);
                    resetFamInfo();
                    resetFamDetails();
                    closeFamModal();

                }
            }
        });
    }
});



//FamilyModal Close
function closeFamModal() {

    let cus_id = $('#cus_id').val();
    $.post('verificationFile/verificationFam.php', { "cus_id": $('#cus_id').val() }, function (data) {

        let guarentor_name_upd = $('#guarentor_name_upd').val();

        $("#guarentor_name").empty().append("<option value=''>" + 'Select Guarantor' + "</option>");
        for (var i = 0; i < data.length - 1; i++) { // -1 because this ajax's response will contain customer value at the last of the response for verification person
            var fam_name = data[i]['fam_name']; var fam_id = data[i]['fam_id'];
            var selected = '';
            if (guarentor_name_upd != '' && fam_id == guarentor_name_upd) {
                selected = 'selected';
            }
            $("#guarentor_name").append("<option value='" + fam_id + "' " + selected + ">" + fam_name + "</option>");
        }

    }, 'json')
    resetFamInfo();
    resetFamDetails();
}


///////////////////////// Property Info Starts /////////////////////////////////////

function propertyHolder() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/property_holder.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#property_holder").empty();
            $("#property_holder").append("<option value=''>" + 'Select Property Holder' + "</option>");
            for (var i = 0; i < len; i++) {
                var fam_name = response[i];
                $("#property_holder").append("<option value='" + fam_name + "'>" + fam_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#property_holder option:first-child");
                $("#property_holder").html($("#property_holder option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#property_holder").prepend(firstOption);
            }
        }
    });
}



$(document).on("click", "#propertyInfoBtn", function () {
    let cus_id = $('#cus_id').val();
    let property_type = $("#property_typ").val();
    let property_measurement = $("#property_measurement").val();
    let property_value = $("#property_value").val();
    let property_holder = $("#property_holder").val();
    let propertyID = $("#propertyID").val();

    if (property_type != "" && property_measurement != "" && property_value != "" && property_holder != "") {
        $.ajax({
            url: 'updateFile/update_property_submit.php',
            type: 'POST',
            data: { "property_type": property_type, "property_measurement": property_measurement, "property_value": property_value, "property_holder": property_holder, "propertyID": propertyID, "cus_id": cus_id },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#prptyInsertOk').show();
                    setTimeout(function () {
                        $('#prptyInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#prptyUpdateok').show();
                    setTimeout(function () {
                        $('#prptyUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#prptyNotOk').show();
                    setTimeout(function () {
                        $('#NotOk').fadeOut('fast');
                    }, 2000);
                }

                resetpropertyInfo();
            }
        });
    }
    else {

        if (property_type == "") {
            $('#prtytypeCheck').show();
        } else {
            $('#prtytypeCheck').hide();
        }

        if (property_measurement == "") {
            $('#prtymeasureCheck').show();
        } else {
            $('#prtymeasureCheck').hide();
        }

        if (property_value == "") {
            $('#prtyvalCheck').show();
        } else {
            $('#prtyvalCheck').hide();
        }

        if (property_holder == "") {
            $('#prtyholdCheck').show();
        } else {
            $('#prtyholdCheck').hide();
        }

    }

});

function resetpropertyInfo() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verification_property_reset.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#propertyTable").empty();
            $("#propertyTable").html(html);

            $("#property_typ").val('');
            $("#property_measurement").val('');
            $("#property_value").val('');
            $("#property_holder").val('');
            $("#propertyID").val('');

            $('#prtytypeCheck').hide(); $('#prtymeasureCheck').hide(); $('#prtyvalCheck').hide(); $('#prtyholdCheck').hide();
        }
    });
}


$("body").on("click", "#verification_property_edit", function () {
    let id = $(this).attr('value');

    $.ajax({
        url: 'verificationFile/verification_property_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        success: function (result) {

            $("#propertyID").val(result['id']);
            $("#property_typ").val(result['type']);
            $("#property_measurement").val(result['measuree']);
            $("#property_value").val(result['pVal']);
            $("#property_holder").val(result['holder']);

        }
    });

});

$("body").on("click", "#verification_property_delete", function () {
    var isok = confirm("Do you want delete this Property Info?");
    if (isok == false) {
        return false;
    } else {
        var prptyid = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/verification_property_delete.php',
            type: 'POST',
            data: { "prptyid": prptyid },
            cache: false,
            success: function (response) {
                var delresult = response.includes("Deleted");
                if (delresult) {
                    $('#prptyDeleteOk').show();
                    setTimeout(function () {
                        $('#prptyDeleteOk').fadeOut('fast');
                    }, 2000);
                }
                else {

                    $('#prptyDeleteNotOk').show();
                    setTimeout(function () {
                        $('#prptyDeleteNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetpropertyInfo();
            }
        });
    }
});


function resetPropertyinfoList() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verification_property_list.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#propertyList").empty();
            $("#propertyList").html(html);

            $("#property_typ").val('');
            $("#property_measurement").val('');
            $("#property_value").val('');
            $("#property_holder").val('');
            $("#propertyID").val('');

            $('#prtytypeCheck').hide(); $('#prtymeasureCheck').hide(); $('#prtyvalCheck').hide(); $('#prtyholdCheck').hide();
        }
    });
}

////////////////////////////// Bank Info ///////////////////////////////////////////////////////



$(document).on("click", "#bankInfoBtn", function () {

    let cus_id = $('#cus_id').val();
    let bank_name = $("#bank_name").val();
    let branch_name = $("#branch_name").val();
    let account_holder_name = $("#account_holder_name").val();
    let account_number = $("#account_number").val();
    let Ifsc_code = $("#Ifsc_code").val();
    let bankID = $("#bankID").val();

    if (bank_name != "" && branch_name != "" && account_holder_name != "" && account_number != "" && Ifsc_code != "" && cus_id != "") {
        $.ajax({
            url: 'updateFile/update_bank_submit.php',
            type: 'POST',
            data: { "bank_name": bank_name, "branch_name": branch_name, "account_holder_name": account_holder_name, "account_number": account_number, "Ifsc_code": Ifsc_code, "bankID": bankID, "cus_id": cus_id },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#bankInsertOk').show();
                    setTimeout(function () {
                        $('#bankInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#bankUpdateok').show();
                    setTimeout(function () {
                        $('#bankUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#bankNotOk').show();
                    setTimeout(function () {
                        $('#bankNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetbankInfo();
            }
        });

        $('#bankNameCheck').hide(); $('#branchCheck').hide(); $('#accholdCheck').hide(); $('#accnoCheck').hide(); $('#ifscCheck').hide();
    }
    else {

        if (bank_name == "") {
            $('#bankNameCheck').show();
        } else {
            $('#bankNameCheck').hide();
        }

        if (branch_name == "") {
            $('#branchCheck').show();
        } else {
            $('#branchCheck').hide();
        }

        if (account_holder_name == "") {
            $('#accholdCheck').show();
        } else {
            $('#accholdCheck').hide();
        }

        if (account_number == "") {
            $('#accnoCheck').show();
        } else {
            $('#accnoCheck').hide();
        }
        if (Ifsc_code == "") {
            $('#ifscCheck').show();
        } else {
            $('#ifscCheck').hide();
        }

    }

});


function resetbankInfo() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verification_bank_reset.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#bankTable").empty();
            $("#bankTable").html(html);

            $("#bank_name").val('');
            $("#branch_name").val('');
            $("#account_holder_name").val('');
            $("#account_number").val('');
            $("#Ifsc_code").val('');
            $("#bankID").val('');

        }
    });
}


$("body").on("click", "#verification_bank_edit", function () {
    let id = $(this).attr('value');

    $.ajax({
        url: 'verificationFile/verification_bank_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        success: function (result) {

            $("#bankID").val(result['id']);
            $("#bank_name").val(result['bankName']);
            $("#branch_name").val(result['branch']);
            $("#account_holder_name").val(result['accHolderName']);
            $("#account_number").val(result['acc_no']);
            $("#Ifsc_code").val(result['ifsc']);

        }
    });

});


$("body").on("click", "#verification_bank_delete", function () {
    var isok = confirm("Do you want delete this Bank Info?");
    if (isok == false) {
        return false;
    } else {
        var bankid = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/verification_bank_delete.php',
            type: 'POST',
            data: { "bankid": bankid },
            cache: false,
            success: function (response) {
                var delresult = response.includes("Deleted");
                if (delresult) {
                    $('#bankDeleteOk').show();
                    setTimeout(function () {
                        $('#bankDeleteOk').fadeOut('fast');
                    }, 2000);
                }
                else {

                    $('#bankDeleteNotOk').show();
                    setTimeout(function () {
                        $('#bankDeleteNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetbankInfo();
            }
        });
    }
});

function resetbankinfoList() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verification_bank_list.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#bankResetTable").empty();
            $("#bankResetTable").html(html);
        }
    });
}

////////////////////////// KYC Info ////////////////////////////////////////////////

$('#proof_number').keyup(function () {
    let proof_type = $('#proof_type').val();
    if (proof_type == 1) {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
        $(this).attr('maxlength', '14')
    } else {
        $(this).removeAttr('maxlength');//remove maxlength when other than adhar due to unkown count of number 
    }
});
$('#proof_type').change(function () {
    $('#proof_number').val('')
})


$(document).on("click", "#kycInfoBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let proofof = $("#proofof").val();
    let proof_type = $("#proof_type").val();
    let proof_number = $("#proof_number").val();
    let kyc_upload = $("#kyc_upload").val();
    let kycID = $("#kycID").val();
    let upload = $("#upload")[0];
    let file = upload.files[0];


    let formdata = new FormData();
    formdata.append('upload', file)
    formdata.append('proofof', proofof)
    formdata.append('proof_type', proof_type)
    formdata.append('proof_number', proof_number)
    formdata.append('kycID', kycID)
    formdata.append('kyc_upload', kyc_upload)
    formdata.append('reqId', req_id)
    formdata.append('cus_id', cus_id)

    if (proofof != "" && proof_type != "" && proof_number != "" && (file != undefined || kyc_upload != '') && req_id != "") {
        $.ajax({
            url: 'verificationFile/verification_kyc_submit.php',
            type: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#kycInsertOk').show();
                    setTimeout(function () {
                        $('#kycInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#kycUpdateok').show();
                    setTimeout(function () {
                        $('#kycUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#kycNotOk').show();
                    setTimeout(function () {
                        $('#bankNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetkycInfo();
            }
        });

        $('#proofCheck').hide(); $('#proofTypeCheck').hide(); $('#proofnoCheck').hide(); $('#proofUploadCheck').hide();

    }
    else {

        if (proofof == "") {
            $('#proofCheck').show();
        } else {
            $('#proofCheck').hide();
        }

        if (proof_type == "") {
            $('#proofTypeCheck').show();
        } else {
            $('#proofTypeCheck').hide();
        }

        if (proof_number == "") {
            $('#proofnoCheck').show();
        } else {
            $('#proofnoCheck').hide();
        }

        if (file == undefined && kyc_upload == "") {
            $('#proofUploadCheck').show();
        } else {
            $('#proofUploadCheck').hide();
        }

    }

});

function resetkycInfo() {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verification_kyc_reset.php',
        type: 'POST',
        data: { cus_id, req_id },
        cache: false,
        success: function (html) {
            $("#kycTable").empty();
            $("#kycTable").html(html);

            $("#proofof").val('');
            $("#proof_type").val('');
            $("#proof_number").val('');
            $("#upload").val('');
            $("#kycID").val('');
            $("#kyc_upload").val('');

            $('#proofCheck').hide(); $('#proofTypeCheck').hide(); $('#proofnoCheck').hide(); $('#proofUploadCheck').hide();
        }
    });
}


$("body").on("click", "#verification_kyc_edit", function () {
    let id = $(this).attr('value');

    $.ajax({
        url: 'verificationFile/verification_kyc_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        success: function (result) {

            $("#kycID").val(result['id']);
            $("#proofof").val(result['proofOf']);
            $("#proof_type").val(result['proofType']);
            $("#proof_number").val(result['proofNo']);
            $("#kyc_upload").val(result['upload']);

            $('#proofCheck').hide(); $('#proofTypeCheck').hide(); $('#proofnoCheck').hide(); $('#proofUploadCheck').hide();
        }
    });

});


$("body").on("click", "#verification_kyc_delete", function () {
    var isok = confirm("Do you want delete this KYC Info?");
    if (isok == false) {
        return false;
    } else {
        var kycid = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/verification_kyc_delete.php',
            type: 'POST',
            data: { "kycid": kycid },
            cache: false,
            success: function (response) {
                var delresult = response.includes("Deleted");
                if (delresult) {
                    $('#kycDeleteOk').show();
                    setTimeout(function () {
                        $('#kycDeleteOk').fadeOut('fast');
                    }, 2000);
                }
                else {

                    $('#kycDeleteNotOk').show();
                    setTimeout(function () {
                        $('#kycDeleteNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetkycInfo();
            }
        });
    }
});

function resetkycinfoList() {
    let cus_id = $('#cus_id').val();
    let req_id = $('#req_id').val();

    $.ajax({
        url: 'verificationFile/verification_kyc_list.php',
        type: 'POST',
        data: { req_id, cus_id },
        cache: false,
        success: function (html) {
            $("#kycListTable").empty();
            $("#kycListTable").html(html);

            resetkycInfo();
        }
    });
}

$('#proofof').change(function () {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let proof = $('#proofof').val();

    if (proof == '0' || proof == '1') {
        $.post('verificationFile/get_proof_of_name.php', { req_id, cus_id, proof }, function (response) {
            $('.name_div').show();
            $('#proofofname').val(response);
        }, 'json')
    } else {
        $('.name_div').hide()
    }

    $.ajax({
        url: 'verificationFile/verification_proof_type.php',
        type: 'POST',
        data: { "reqId": req_id, "cus_id":cus_id,"proof": proof },
        dataType: 'json',
        cache: false,
        success: function (response) {


            $('#proof_type option').prop('disabled', false);

            $.each(response, function (index, value) {
                $('#proof_type option[value="' + value + '"]').prop('disabled', true);
            });

        }
    });

})


//get district dropdown
function getDistrictDropdown(StateSelected) {

    var optionsList;

    {
        var TamilNadu = ["Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kancheepuram", "Kanniyakumari", "Karur", "Madurai", "Nagapattinam",
            "Namakkal", "Nilgiris", "Perambalur", "Pudukottai", "Ramanathapuram", "Salem", "Sivagangai", "Thanjavur", "Theni", "Thiruvallur", "Tiruvannamalai", "Thiruvarur",
            "Thoothukudi", "Tiruchirappalli", "Thirunelveli", "Vellore", "Viluppuram", "Virudhunagar", "Ariyalur", "Krishnagiri", "Tiruppur", "Chengalpattu", "Kallakurichi",
            "Ranipet", "Tenkasi", "Tirupathur", "Mayiladuthurai"];
        var Puducherry = ["Puducherry"];
    }//District list
    switch (StateSelected) {
        case "TamilNadu":
            optionsList = TamilNadu;
            break;
        case "Puducherry":
            optionsList = Puducherry;
            break;
        case "SelectState":
            optionsList = [];
            break;
    }

    return optionsList;
}

function districtNameList(optionsList) { // To List the District
    var htmlString = "<option value='Select District'>Select District</option>";
    var district_upd = $('#district_upd').val();
    for (var i = 0; i < optionsList.length; i++) {
        var selected = '';
        if (district_upd != undefined && district_upd != '' && district_upd == optionsList[i]) { selected = "selected"; }
        htmlString = htmlString + "<option value='" + optionsList[i] + "' " + selected + " >" + optionsList[i] + "</option>";
    }
    $("#district").html(htmlString);
    $("#district1").val(district_upd);

    {//To Order Alphabetically
        var firstOption = $("#district option:first-child");
        $("#district").html($("#district option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#district").prepend(firstOption);
    }
}

function conformDistrictNameList(optionsList) { // To List the Confirm Area District
    var htmlString = "<option value='Select District'>Select District</option>";
    var district_upd = $('#area_district_upd').val();
    for (var i = 0; i < optionsList.length; i++) {
        var selected = '';
        if (district_upd != undefined && district_upd != '' && district_upd == optionsList[i]) { selected = "selected"; }
        htmlString = htmlString + "<option value='" + optionsList[i] + "' " + selected + " >" + optionsList[i] + "</option>";
    }
    $("#area_district").html(htmlString);
    $("#area_district1").val(district_upd);

    {//To Order Alphabetically
        var firstOption = $("#area_district option:first-child");
        $("#area_district").html($("#area_district option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area_district").prepend(firstOption);
    }
}

//get Taluk Dropdown
function getTalukDropdown(DistSelected) {

    var optionsList;
    {
        var Chennai = ["Alandur", "Ambattur", "Aminjikarai", "Ayanavaram", "Egmore", "Guindy", "Madhavaram", "Madhuravoyal", "Mambalam", "Mylapore", "Perambur", "Purasavakkam", "Sholinganallur", "Thiruvottriyur", "Tondiarpet", "Velacherry"];
        var Coimbatore = ["Aanaimalai", "Annur", "Coimbatore(North)", "Coimbatore(South)", "Kinathukadavu", "Madukarai", "Mettupalayam", "Perur", "Pollachi", "Sulur", "Valparai"];
        var Cuddalore = ["Cuddalore", "Bhuvanagiri", "Chidambaram", "Kattumannarkoil", "Kurinjipadi", "Panruti", "Srimushnam", "Thittakudi", "Veppur", "Virudhachalam"];
        var Dharmapuri = ["Dharmapuri", "Harur", "Karimangalam", "Nallampalli", "Palacode", "Pappireddipatti", "Pennagaram"];
        var Dindigul = ["Atthur", "Dindigul (East)", "Dindigul (West)", "Guziliyamparai", "Kodaikanal", "Natham", "Nilakottai", "Oddanchatram", "Palani", "Vedasandur"];
        var Erode = ["Erode", "Anthiyur", "Bhavani", "Gobichettipalayam", "Kodumudi", "Modakurichi", "Nambiyur", "Perundurai", "Sathiyamangalam", "Thalavadi"];
        var Kancheepuram = ["Kancheepuram", "Kundrathur", "Sriperumbudur", "Uthiramerur", "Walajabad"];
        var Kanniyakumari = ["Agasteeswaram", "Kalkulam", "Killiyur", "Thiruvatar", "Thovalai", "Vilavankodu"];
        var Karur = ["Karur", "Aravakurichi", "Kadavur", "Krishnarayapuram", "Kulithalai", "Manmangalam", "Pugalur"];
        var Madurai = ["Kallikudi", "Madurai (East)", "Madurai (North)", "Madurai (South)", "Madurai (West)", "Melur", "Peraiyur", "Thirumangalam", "Thiruparankundram", "Usilampatti", "Vadipatti"];
        var Nagapattinam = ["Nagapattinam", "Kilvelur", "Thirukkuvalai", "Vedaranyam"];
        var Namakkal = ["Namakkal", "Kholli Hills", "Kumarapalayam", "Mohanoor", "Paramathi Velur", "Rasipuram", "Senthamangalam", "Tiruchengode"];
        var Nilgiris = ["Udagamandalam", "Coonoor", "Gudalur", "Kothagiri", "Kundah", "Pandalur"];
        var Perambalur = ["Perambalur", "Alathur", "Kunnam", "Veppanthattai"];
        var Pudukottai = ["Pudukottai", "Alangudi", "Aranthangi", "Avudiyarkoil", "Gandarvakottai", "Iluppur", "Karambakudi", "Kulathur", "Manamelkudi", "Ponnamaravathi", "Thirumayam", "Viralimalai"];
        var Ramanathapuram = ["Ramanathapuram", "Kadaladi", "Kamuthi", "Kezhakarai", "Mudukulathur", "Paramakudi", "Rajasingamangalam", "Rameswaram", "Tiruvadanai"];
        var Salem = ["Salem", "Attur", "Edapadi", "Gangavalli", "Kadaiyampatti", "Mettur", "Omalur", "Pethanayakanpalayam", "Salem South", "Salem West", "Sankari", "Vazhapadi", "Yercaud"];
        var Sivagangai = ["Sivagangai", "Devakottai", "Ilayankudi", "Kalaiyarkovil", "Karaikudi", "Manamadurai", "Singampunari", "Thirupuvanam", "Tirupathur"];
        var Thanjavur = ["Thanjavur", "Boothalur", "Kumbakonam", "Orathanadu", "Papanasam", "Pattukottai", "Peravurani", "Thiruvaiyaru", "Thiruvidaimaruthur"];
        var Theni = ["Theni", "Aandipatti", "Bodinayakanur", "Periyakulam", "Uthamapalayam"];
        var Thiruvallur = ["Thiruvallur", "Avadi", "Gummidipoondi", "Pallipattu", "Ponneri", "Poonamallee", "R.K. Pet", "Tiruthani", "Uthukottai"];
        var Tiruvannamalai = ["Thiruvannamalai", "Arni", "Chengam", "Chetpet", "Cheyyar", "Jamunamarathur", "Kalasapakkam", "Kilpennathur", "Polur", "Thandramet", "Vandavasi", "Vembakkam"];
        var Thiruvarur = ["Thiruvarur", "Kodavasal", "Koothanallur", "Mannargudi", "Nannilam", "Needamangalam", "Thiruthuraipoondi", "Valangaiman"];
        var Thoothukudi = ["Thoothukudi", "Eral", "Ettayapuram", "Kayathar", "Kovilpatti", "Ottapidaram", "Sattankulam", "Srivaikundam", "Tiruchendur", "Vilathikulam"];
        var Tiruchirappalli = ["Lalgudi", "Manachanallur", "Manapparai", "Marungapuri", "Musiri", "Srirangam", "Thottiam", "Thuraiyur", "Tiruchirapalli (West)", "Tiruchirappalli (East)", "Tiruverumbur"];
        var Thirunelveli = ["Tirunelveli", "Ambasamudram", "Cheranmahadevi", "Manur", "Nanguneri", "Palayamkottai", "Radhapuram", "Thisayanvilai"];
        var Vellore = ["Vellore", "Aanikattu", "Gudiyatham", "K V Kuppam", "Katpadi", "Pernambut"];
        var Viluppuram = ["Villupuram", "Gingee", "Kandachipuram", "Marakanam", "Melmalaiyanur", "Thiruvennainallur", "Tindivanam", "Vanur", "Vikravandi"];
        var Virudhunagar = ["Virudhunagar", "Aruppukottai", "Kariyapatti", "Rajapalayam", "Sathur", "Sivakasi", "Srivilliputhur", "Tiruchuli", "Vembakottai", "Watrap"];
        var Ariyalur = ["Ariyalur", "Andimadam", "Sendurai", "Udaiyarpalayam"];
        var Krishnagiri = ["Krishnagiri", "Anjetty", "Bargur", "Hosur", "Pochampalli", "Sulagiri", "Thenkanikottai", "Uthangarai"];
        var Tiruppur = ["Avinashi", "Dharapuram", "Kangeyam", "Madathukkulam", "Oothukuli", "Palladam", "Tiruppur (North)", "Tiruppur (South)", "Udumalaipettai"];
        var Chengalpattu = ["Chengalpattu", "Cheyyur", "Maduranthakam", "Pallavaram", "Tambaram", "Thirukalukundram", "Tiruporur", "Vandalur"];
        var Kallakurichi = ["Kallakurichi", "Chinnaselam", "Kalvarayan Hills", "Sankarapuram", "Tirukoilur", "Ulundurpet"];
        var Ranipet = ["Arakkonam", "Arcot", "Kalavai", "Nemili", "Sholingur", "Walajah"];
        var Tenkasi = ["Tenkasi", "Alangulam", "Kadayanallur", "Sankarankovil", "Shenkottai", "Sivagiri", "Thiruvengadam", "Veerakeralampudur"];
        var Tirupathur = ["Tirupathur", "Ambur", "Natrampalli", "Vaniyambadi"];
        var Mayiladuthurai = ["Mayiladuthurai", "Kuthalam", "Sirkali", "Tharangambadi"];
        var Puducherry = ["Puducherry", "Oulgaret", "Villianur", "Bahour", "Karaikal", "Thirunallar", "Mahe", "Yanam"];

    }//taluk list
    switch (DistSelected) {
        case "Ariyalur":
            optionsList = Ariyalur;
            break;
        case "Chengalpattu":
            optionsList = Chengalpattu;
            break;
        case "Chennai":
            optionsList = Chennai;
            break;
        case "Coimbatore":
            optionsList = Coimbatore;
            break;
        case "Dharmapuri":
            optionsList = Dharmapuri;
            break;
        case "Erode":
            optionsList = Erode;
            break;
        case "Cuddalore":
            optionsList = Cuddalore;
            break;
        case "Dindigul":
            optionsList = Dindigul;
            break;
        case "Kallakurichi":
            optionsList = Kallakurichi;
            break;
        case "Kanniyakumari":
            optionsList = Kanniyakumari;
            break;
        case "Krishnagiri":
            optionsList = Krishnagiri;
            break;
        case "Nagapattinam":
            optionsList = Nagapattinam;
            break;
        case "Perambalur":
            optionsList = Perambalur;
            break;
        case "Ramanathapuram":
            optionsList = Ramanathapuram;
            break;
        case "Salem":
            optionsList = Salem;
            break;
        case "Tenkasi":
            optionsList = Tenkasi;
            break;
        case "Theni":
            optionsList = Theni;
            break;
        case "Thirunelveli":
            optionsList = Thirunelveli;
            break;
        case "Thiruvarur":
            optionsList = Thiruvarur;
            break;
        case "Tirupathur":
            optionsList = Tirupathur;
            break;
        case "Tiruvannamalai":
            optionsList = Tiruvannamalai;
            break;
        case "Vellore":
            optionsList = Vellore;
            break;
        case "Virudhunagar":
            optionsList = Virudhunagar;
            break;
        case "Kancheepuram":
            optionsList = Kancheepuram;
            break;
        case "Karur":
            optionsList = Karur;
            break;
        case "Madurai":
            optionsList = Madurai;
            break;
        case "Namakkal":
            optionsList = Namakkal;
            break;
        case "Pudukottai":
            optionsList = Pudukottai;
            break;
        case "Ranipet":
            optionsList = Ranipet;
            break;
        case "Sivagangai":
            optionsList = Sivagangai;
            break;
        case "Thanjavur":
            optionsList = Thanjavur;
            break;
        case "Nilgiris":
            optionsList = Nilgiris;
            break;
        case "Thiruvallur":
            optionsList = Thiruvallur;
            break;
        case "Thoothukudi":
            optionsList = Thoothukudi;
            break;
        case "Tiruppur":
            optionsList = Tiruppur;
            break;
        case "Tiruchirappalli":
            optionsList = Tiruchirappalli;
            break;
        case "Viluppuram":
            optionsList = Viluppuram;
            break;
        case "Mayiladuthurai":
            optionsList = Mayiladuthurai;
            break;
        case "Puducherry":
            optionsList = Puducherry;
            break;
        case "Select District":
            optionsList = [];
            break;
    }

    return optionsList;
}

function talukNameList(optionsList) { //To show Taluk list.
    var taluk_upd = $('#taluk_upd').val();
    var htmlString = "<option value='Select Taluk'>Select Taluk</option>";
    for (var i = 0; i < optionsList.length; i++) {
        var selected = '';
        if (taluk_upd != undefined && taluk_upd != '' && taluk_upd == optionsList[i]) { selected = "selected"; }
        htmlString = htmlString + "<option value='" + optionsList[i] + "' " + selected + " >" + optionsList[i] + "</option>";
    }
    $("#taluk").html(htmlString);
    $("#taluk1").val(taluk_upd);

    {//To Order Alphabetically
        var firstOption = $("#taluk option:first-child");
        $("#taluk").html($("#taluk option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#taluk").prepend(firstOption);
    }
}

function conformtalukNameList(optionsList) { //To show Taluk list.
    var taluk_upd = $('#area_taluk_upd').val();
    var htmlString = "<option value='Select Taluk'>Select Taluk</option>";
    for (var i = 0; i < optionsList.length; i++) {
        var selected = '';
        if (taluk_upd != undefined && taluk_upd != '' && taluk_upd == optionsList[i]) { selected = "selected"; }
        htmlString = htmlString + "<option value='" + optionsList[i] + "' " + selected + " >" + optionsList[i] + "</option>";
    }
    $("#area_taluk").html(htmlString);
    $("#area_taluk1").val(taluk_upd);

    {//To Order Alphabetically
        var firstOption = $("#area_taluk option:first-child");
        $("#area_taluk").html($("#area_taluk option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area_taluk").prepend(firstOption);
    }
}

//Get Taluk Based Area
function getTalukBasedArea(talukselected, area_upd, area) {

    $.ajax({
        url: 'requestFile/ajaxGetEnabledAreaName.php',
        type: 'post',
        data: { 'talukselected': talukselected },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $(area).empty();
            $(area).append("<option value=''>" + 'Select Area' + "</option>");
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var selected = '';
                if (area_upd != undefined && area_upd != '' && area_upd == area_id) {
                    selected = 'selected';
                }
                $(area).append("<option value='" + area_id + "' " + selected + ">" + area_name + "</option>");
            }

            {//To Order Alphabetically
                var firstOption = $(area + " option:first-child");
                $(area).html($(area + " option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $(area).prepend(firstOption);
            }
        }
    });
}

//Get Area Based Sub Area
function getAreaBasedSubArea(area, sub_area_upd, sub_area) {

    $.ajax({
        url: 'requestFile/ajaxGetEnabledSubArea.php',
        type: 'post',
        data: { 'area': area },
        dataType: 'json',
        success: function (response) {

            $(sub_area).empty();
            $(sub_area).append("<option value='' >Select Sub Area</option>");
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (sub_area_upd != undefined && sub_area_upd != '' && sub_area_upd == response[i]['sub_area_id']) {
                    selected = 'selected';
                }
                $(sub_area).append("<option value='" + response[i]['sub_area_id'] + "' " + selected + ">" + response[i]['sub_area_name'] + " </option>");
            }
        }
    });
}

function getGroupandLine(sub_area_id) {

    $.ajax({
        url: 'verificationFile/getGroupandLine.php',
        data: { 'sub_area_id': sub_area_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#area_group').val(response['group_name']);
            $('#area_line').val(response['line_name']);
        }
    })
}

$('#cus_loan_limit').change(function () { /// Loan Limit will Check the Loan Amount in Request Loan Category./////
    let loanLimit = parseInt($(this).val());
    let loanSubCat = $('#loan_sub_cat').val();

    $.ajax({
        type: 'POST',
        url: 'verificationFile/check_loan_limit.php',
        data: { 'loan_sub_id': loanSubCat },
        dataType: 'json',
        success: function (response) {
            if (loanLimit > parseInt(response)) {
                alert("Kindly Enter Loan Limit Lesser Than Loan Amount " + response);
                $('#cus_loan_limit').val('');
                return false;
            }
        }
    })



})



//Customer Feedback Modal 

$(document).on("click", "#feedbackBtn", function () {

    let cus_id = $('#cus_id').val();
    let feedback_label = $("#feedback_label").val();
    let cus_feedback = $("#cus_feedback").val();
    let feedback_remark = $("#feedback_remark").val();
    let feedbackID = $("#feedbackID").val();


    if (feedback_label != "" && cus_feedback != "" && cus_id != "") {
        $.ajax({
            url: 'updateFile/update_cus_feedback_submit.php',
            type: 'POST',
            data: { "feedback_label": feedback_label, "cus_feedback": cus_feedback, "feedback_remark": feedback_remark, "feedbackID": feedbackID, "cus_id": cus_id },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#feedbackInsertOk').show();
                    setTimeout(function () {
                        $('#feedbackInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#feedbackUpdateok').show();
                    setTimeout(function () {
                        $('#feedbackUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#feedbackNotOk').show();
                    setTimeout(function () {
                        $('#feedbackNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetfeedback();
            }
        });

        $('#feedbacklabelCheck').hide(); $('#feedbackCheck').hide();
    }
    else {

        if (feedback_label == "") {
            $('#feedbacklabelCheck').show();
        } else {
            $('#feedbacklabelCheck').hide();
        }

        if (cus_feedback == "") {
            $('#feedbackCheck').show();
        } else {
            $('#feedbackCheck').hide();
        }

    }

});

function resetfeedback() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/customer_feedback_reset.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#feedbackTable").empty();
            $("#feedbackTable").html(html);

            $("#feedback_label").val('');
            $("#cus_feedback").val('');
            $("#feedback_remark").val('');
            $("#feedbackID").val('');

        }
    });
}

function feedbackList() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/customer_feedback_list.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#feedbackListTable").empty();
            $("#feedbackListTable").html(html);

            $("#feedback_label").val('');
            $("#cus_feedback").val('');
            $("#feedback_remark").val('');
            $("#feedbackID").val('');
        }
    });
}
//Customer Feedback Modal End

$('#marital').change(function () {//To get spouse name or not
    var marital = $(this).val();
    if (marital == '1') {
        $('.spouse').show();
    } else {
        $('.spouse').hide();
    }
})

$('#guarentor_name').change(function () { //Select Guarantor Name relationship will show in input.

    let famId = $("#guarentor_name").val();
    $('#guarentor_image').val('');//empty guarentor pic when changing guarentor name, to upload new pic for new guarentor.

    $.ajax({
        url: 'verificationFile/verification_guarantor.php',
        type: 'POST',
        data: { "famid": famId },
        dataType: 'json',
        cache: false,
        success: function (result) {

            $("#guarentor_relationship").val(result['relation']);

        }
    });

});

///Customer profile submit///
$('#submit_update_cus_profile').click(function () {
    validation();
});

function validation() {
    var cus_id = $('#cus_id').val(); var cus_name = $('#cus_name').val(); var dob = $('#dob').val(); var gender = $('#gender').val(); var state = $('#state').val();
    var cus_image = $('#cus_image').val(); var pic = $('#pic').val();
    var district = $('#district1').val(); var taluk = $('#taluk1').val(); var area = $('#area').val(); var sub_area = $('#sub_area').val(); var cus_address = $('#cus_address').val();
    var mobile1 = $('#mobile1').val(); var mobile2 = $('#mobile2').val(); var father_name = $('#father_name').val(); var mother_name = $('#mother_name').val(); var marital = $('#marital').val();
    var occupation_type = $('#occupation_type').val(); var occupation = $('#occupation').val(); var area_cnfrm = $('#area_cnfrm').val(); var cus_res_type = $('#cus_res_type').val();
    var cus_res_details = $('#cus_res_details').val(); var cus_res_address = $('#cus_res_address').val(); var cus_res_native = $('#cus_res_native').val();
    var cus_occ_type = $('#cus_occ_type').val(); var cus_occ_detail = $('#cus_occ_detail').val(); var cus_occ_income = $('#cus_occ_income').val(); var cus_occ_address = $('#cus_occ_address').val(); var cus_occ_dow = $('#cus_occ_dow').val(); var cus_occ_abt = $('#cus_occ_abt').val();
    var cus_how_know = $('#cus_how_know').val(); var cus_monthly_income = $('#cus_monthly_income').val(); var cus_other_income = $('#cus_other_income').val(); var cus_support_income = $('#cus_support_income').val(); var cus_Commitment = $('#cus_Commitment').val(); var cus_monDue_capacity = $('#cus_monDue_capacity').val(); var cus_loan_limit = $('#cus_loan_limit').val(); var about_cus = $('#about_cus').val();
    var guarentor_name = $('#guarentor_name').val(); var guarentor_image = $('#guarentor_image').val(); var guarentorpic = $('#guarentorpic').val();

    if (cus_id == '') {
        event.preventDefault();
        $('#cusidCheck').show();
    } else {
        $('#cusidCheck').hide();
    }
    if (cus_name == '') {
        event.preventDefault();
        $('#cusnameCheck').show();
    } else {
        $('#cusnameCheck').hide();
    }
    if (dob == '') {
        event.preventDefault();
        $('#dobCheck').show();
    } else {
        $('#dobCheck').hide();
    }
    if (gender == '') {
        event.preventDefault();
        $('#genderCheck').show();
    } else {
        $('#genderCheck').hide();
    }
    if (state == 'SelectState') {
        event.preventDefault();
        $('#stateCheck').show();
    } else {
        $('#stateCheck').hide();
    }
    if (district == '') {
        event.preventDefault();
        $('#districtCheck').show();
    } else {
        $('#districtCheck').hide();
    }
    if (taluk == '') {
        event.preventDefault();
        $('#talukCheck').show();
    } else {
        $('#talukCheck').hide();
    }
    if (area == '') {
        event.preventDefault();
        $('#areaCheck').show();
    } else {
        $('#areaCheck').hide();
    }
    if (sub_area == '') {
        event.preventDefault();
        $('#subareaCheck').show();
    } else {
        $('#subareaCheck').hide();
    }
    if (cus_address == '') {
        event.preventDefault();
        $('#addressCheck').show();
    } else {
        $('#addressCheck').hide();
    }
    if (mobile1 == '' || mobile1.length < 10) {
        event.preventDefault();
        $('#mobile1Check').show();
    } else {
        $('#mobile1Check').hide();
    }
    if (mobile2 != '' && mobile2.length < 10) {
        event.preventDefault();
        $('#mobile2Check').show();
    } else {
        $('#mobile2Check').hide();
    }
    if (father_name == '') {
        event.preventDefault();
        $('#fathernameCheck').show();
    } else {
        $('#fathernameCheck').hide();
    }
    if (mother_name == '') {
        event.preventDefault();
        $('#mothernameCheck').show();
    } else {
        $('#mothernameCheck').hide();
    }
    if (marital == '') {
        event.preventDefault();
        $('#maritalCheck').show();
    } else {
        $('#maritalCheck').hide();
    }
    if (occupation_type == '') {
        event.preventDefault();
        $('#occupationtypeCheck').show();
    } else {
        $('#occupationtypeCheck').hide();
    }
    if (occupation == '') {
        event.preventDefault();
        $('#occupationCheck').show();
    } else {
        $('#occupationCheck').hide();
    }
    if (area_cnfrm == '0') {
        $('#areacnfrmCheck').hide();
        if (cus_res_type == '' || cus_res_details == '' || cus_res_address == '' || cus_res_native == '') {
            event.preventDefault();
            $('#occ_infoCheck').hide();
            $('#res_infoCheck').show();
        } else {
            $('#occ_infoCheck').hide();
            $('#res_infoCheck').hide();
        }
    } else if (area_cnfrm == '1') {
        $('#areacnfrmCheck').hide();
        if (cus_occ_type == '' || cus_occ_detail == '' || cus_occ_income == '' || cus_occ_address == '' || cus_occ_dow == '' || cus_occ_abt == '') {
            event.preventDefault();
            $('#res_infoCheck').hide();
            $('#occ_infoCheck').show();
        } else {
            $('#res_infoCheck').hide();
            $('#occ_infoCheck').hide();
        }
    } else {
        event.preventDefault();
        $('#areacnfrmCheck').show();
    }
    if (cus_how_know == '') {
        event.preventDefault();
        $('#howToKnowCheck').show();
    } else {
        $('#howToKnowCheck').hide();
    }
    if (cus_monthly_income == '') {
        event.preventDefault();
        $('#monthlyIncomeCheck').show();
    } else {
        $('#monthlyIncomeCheck').hide();
    }
    if (cus_other_income == '') {
        event.preventDefault();
        $('#otherIncomeCheck').show();
    } else {
        $('#otherIncomeCheck').hide();
    }
    if (cus_support_income == '') {
        event.preventDefault();
        $('#supportIncomeCheck').show();
    } else {
        $('#supportIncomeCheck').hide();
    }
    if (cus_Commitment == '') {
        event.preventDefault();
        $('#commitmentCheck').show();
    } else {
        $('#commitmentCheck').hide();
    }
    if (cus_monDue_capacity == '') {
        event.preventDefault();
        $('#monthlyDueCapacityCheck').show();
    } else {
        $('#monthlyDueCapacityCheck').hide();
    }
    if (cus_loan_limit == '') {
        event.preventDefault();
        $('#loanLimitCheck').show();
    } else {
        $('#loanLimitCheck').hide();
    }
    if (about_cus == '') {
        event.preventDefault();
        $('#aboutcusCheck').show();
    } else {
        $('#aboutcusCheck').hide();
    }

    if (guarentor_name == '') {
        event.preventDefault();
        $('#guarentor_nameCheck').show();
    } else {
        $('#guarentor_nameCheck').hide();
    }
    if (guarentor_image == '') {
        if (guarentorpic == '') {
            event.preventDefault();
            $('#guarentorpicCheck').show();
        } else {
            $('#guarentorpicCheck').hide();
        }
    }

    if (pic == '') {
        if (cus_image == '') {
            event.preventDefault();
        }
    }

} //Validation END.///

$('#Communitcation_to_cus').change(function () {
    let com = $(this).val();

    if (com == '0') {
        $('#verifyaudio').show();
    } else {
        $('#verifyaudio').hide();
    }
})


//////////////////////////////////////////////////////////// Documentation  Start/////////////////////////////////////////////////////////////////////////////


function getDocumentHistory() {
    let cus_id = $('#cus_id_load').val();
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
            // if(response.DESCRIPTION != null ){//check json response is not empty


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
            // }
        }
    }).then(function () {
        var pending_sts = $('#pending_sts').val()
        var od_sts = $('#od_sts').val()
        var due_nil_sts = $('#due_nil_sts').val()
        var closed_sts = $('#closed_sts').val()
        var bal_amt = balAmnt;
        $.ajax({
            //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
            url: 'verificationFile/documentation/getDocumentHistory.php',
            data: { 'cus_id': cus_id, 'pending_sts': pending_sts, 'od_sts': od_sts, 'due_nil_sts': due_nil_sts, 'closed_sts': closed_sts, 'bal_amt': bal_amt, screen: 'update' },
            type: 'post',
            cache: false,
            success: function (response) {
                $('#docHistoryDiv').empty()
                $('#docHistoryDiv').html(response);
            }
        }).then(function () {
            $('.edit-doc').unbind('click');
            $('.edit-doc').click(function () {

                $('.dropdown').not($(this).parent()).children().css('border-color', '');// to set other dropdown buttons as normal
                $(this).parent().prev().css('border-color', 'red');// showing selected loan's dropdown button highlighted

                $('.edit-document-card').show();
                // $('.documentation-card').hide();

                var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid'); var cus_name = $(this).data('cusname')
                getDocumentDetails(req_id, cus_id, cus_name);
                $('#req_id_doc').val(req_id);
            })
        })
    })

}

function getDocumentDetails(req_id, cus_id, cus_name) {

    resetSignedDocList(req_id, cus_id);// to reset signed document list non-modal
    resetChequeList(req_id, cus_id);// to reset signed document list non-modal
    resetGoldList(req_id, cus_id);// to reset signed document list non-modal
    resetDocmentList(req_id, cus_id);// to reset signed document list non-modal
    // getFamilyList();//to get family , it may used in mort and endorse processes
    getMortgageInfo(req_id, cus_id); // to get mortgage details
    getEndorsementInfo(req_id, cus_id); // to get mortgage details
    getFingerPrintDetails(req_id, cus_id, cus_name); // to get Fingerprint details like customer, family name and buttons


    $('#update_mortgage, #update_endorsement').off('click');
    $('#update_mortgage, #update_endorsement').click(function () {//submit events of mort and endorsement
        let id = $(this).attr('id');
        if (MEValidation(id) == true) {// if validation are done and returned true

            updateMortEndorse(id, req_id);

        }

    })

    {//signed doc modal on click events
        $('#add_sign_doc').off('click');//open event for signed info modal
        $('#add_sign_doc').click(function () {
            resetsignInfo(req_id, cus_id)
        })

        $('#signInfoBtn').off('click');//submit event for signed info modal
        $('#signInfoBtn').click(function () {
            submitSignedDoc(req_id, cus_id)
        })

        $('.closeSignedInfo').off('click');//close event for signed info modal
        $('.closeSignedInfo').click(function () {
            resetSignedDocList(req_id, cus_id)
        })
    }
    {//cheque modal on click events
        $('#add_Cheque').off('click');//open event for cheque info modal
        $('#add_Cheque').click(function () {
            resetchequeInfo(req_id, cus_id)
        })

        $('#chequeInfoBtn').off('click');//submit event for cheque info modal
        $('#chequeInfoBtn').click(function () {
            submitCheque(req_id, cus_id)
        })

        $('.closeChequeInfo').off('click');//close event for cheque info modal
        $('.closeChequeInfo').click(function () {
            resetChequeList(req_id, cus_id)
        })
    }
    {//gold modal on click events
        $('#add_gold').off('click');//open event for Gold info modal
        $('#add_gold').click(function () {
            resetgoldInfo(req_id, cus_id)
        })

        $('#goldInfoBtn').off('click');//submit event for Gold info modal
        $('#goldInfoBtn').click(function () {
            submitGoldInfo(req_id, cus_id)
        })

        $('.closeGoldInfo').off('click');//close event for Gold info modal
        $('.closeGoldInfo').click(function () {
            resetGoldList(req_id, cus_id)
        })
    }
    {//Document modal on click events
        $('#add_document').off('click');//open event for Document info modal
        $('#add_document').click(function () {
            resetdocInfo(req_id, cus_id)
        })

        $('#docInfoBtn').off('click');//submit event for Document info modal
        $('#docInfoBtn').click(function () {
            submitDocument(req_id, cus_id)
        })

        $('.closeDocInfo').off('click');//close event for Document info modal
        $('.closeDocInfo').click(function () {
            resetDocmentList(req_id, cus_id)
        })
    }


}

//Signed Doc List non-modal
function resetSignedDocList(req_id, cus_id) {

    $.ajax({
        url: 'updateFile/sign_doc_list.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#signDocResetDiv").empty();
            $("#signDocResetDiv").html(html);

            $("#sign_type").val('');
            $("#signType_relationship").val('');
            $("#doc_Count").val('');
            $("#signedID").val('');
            $("#signdoc_upd").val('');
        }
    }).then(function () {
        $('#signed_table').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
    })
}

//Cheque Info List non-modal
function resetChequeList(req_id, cus_id) {

    $.ajax({
        url: 'updateFile/cheque_info_upd_list.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#chequeResetDiv").empty();
            $("#chequeResetDiv").html(html);

            $('#chequeColumnDiv').empty();

            $("#holder_type").val('');
            $("#holder_name").val('');
            $("#holder_relationship_name").val('');
            $("#cheque_relation").val('');
            $("#chequebank_name").val('');
            $("#cheque_count").val('');
            $("#cheque_upd").val('');
            $("#chequeID").val('');
        }
    }).then(function () {
        $('#cheque_table').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
    })
}

//Gold Info List non-modal
function resetGoldList(req_id, cus_id) {

    $.ajax({
        url: 'updateFile/gold_info_list.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#goldResetDiv").empty();
            $("#goldResetDiv").html(html);

            $("#gold_sts").val('');
            $("#gold_type").val('');
            $("#Purity").val('');
            $("#gold_Count").val('');
            $("#gold_Weight").val('');
            $("#gold_Value").val('');
            $("#goldID").val('');
        }
    }).then(function () {
        $('#gold_table').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
    })
}

//Document Info List non-modal
function resetDocmentList(req_id, cus_id) {

    $.ajax({
        url: 'updateFile/doc_info_upd_list.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#documentResetDiv").empty();
            $("#documentResetDiv").html(html);

            $("#document_name").val('');
            $("#document_details").val('');
            $("#document_type").val('');
            $("#document_holder").val('');
            $("#docholder_name").val('');
            $("#docholder_relationship_name").val('');
            $("#doc_relation").val('');
            $("#document_info_upd").val('');
            $("#doc_info_id").val('');
        }
    }).then(function () {
        $('#document_table').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
    })
}

//to get Family names
function getFamilyList(id) {

    let cus_id = $('#cus_id_load').val();

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            // let id1="ownername_relationship_name";let id2="Propertyholder_relationship_name";let id3="signType_relationship";let id4="holder_relationship_name";
            $("#" + id).empty();
            $("#" + id).append("<option value=''>" + 'Select Person Name' + "</option>");
            for (var i = 0; i < len - 1; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                $("#" + id).append("<option value='" + fam_id + "'>" + fam_name + "</option>");
            }

        }
    });
}

//temp document click events
function setTempDocumentEvents() {

    $('.temp-take-out, .temp-take-in').off('click');
    $('.temp-take-out, .temp-take-in').click(function () {// to take values from table on click of buttons
        let req_id = $(this).data('req_id'); let cus_id = $(this).data('cus_id');
        let table_id = $(this).data('tableid');
        let doc_type = $(this).data('doc');
        let doc_obj = {//set of document path
            'sign': 'uploads/verification/signed_doc/',
            'cheque': 'uploads/verification/cheque_upd/',
            'gold': '',
            'document': 'uploads/verification/doc_info/',
        }
        let doc_path = doc_obj[doc_type];//assign path accoding to document type

        let doc_link = $(this).parent().prev().prev().children().text();// to take document name
        let doc_name = $(this).parent().prev().prev().prev().prev().prev().prev().text();// to take document type name

        $('#doc_name_tempout, #doc_name_tempin').val(doc_name);
        $('#doc_tempout_link, #doc_tempin_link').parent().attr('href', doc_path + doc_link);// to set the path of file
        if (doc_type == 'gold') { $('#doc_tempout_link, #doc_tempin_link').closest('div').parent().hide(); } else { $('#doc_tempout_link, #doc_tempin_link').closest('div').parent().show(); }

        $('#doc_tempout_link, #doc_tempin_link').val(doc_link);
        $('#req_id_tempout, #req_id_tempin').val(req_id);
        $('#cus_id_tempout, #cus_id_tempin').val(cus_id);
        $('#table_id_tempout, #table_id_tempin').val(table_id);
        $('#table_name_tempout, #table_name_tempin').val(doc_type);


        getFamilyList('tempout_rel_name');
        getFamilyList('tempin_rel_name');
    })

    $('.closetempout, .closetempin').off('click');
    $('.closetempout, .closetempin').click(function () {// to remove all the inputs inside the form when closing
        $("#tempoutform").find("input, select").not('#tempout_date').val("");
        $("#tempinform").find("input, select").not('#tempin_date').val("");
    })


    $('#tempout_submit, #tempin_submit').off('click');
    $('#tempout_submit, #tempin_submit').click(function () {

        let type = $(this).data('type');
        if (type == 'take-out') {
            submitForTakeOut();
        } else if (type == 'take-in') {
            submitForTakeIn();
        }

        function submitForTakeOut() {

            if (tempoutSubmitValidation() == true) {
                if (confirm('Are you sure to take this Document Out?')) {

                    let temp_person = $('#tempout_person').val(); let temp_purpose = $('#tempout_purpose').val(); let temp_remarks = $('#tempout_remarks').val();
                    let table_id = $('#table_id_tempout').val(); let table_name = $('#table_name_tempout').val();
                    let req_id = $('#req_id_tempout').val(); let cus_id = $('#cus_id_tempout').val();
                    $.ajax({
                        url: 'updateFile/submitTempDocument.php',
                        data: { "type": 'out', "table_id": table_id, "table_name": table_name, "temp_person": temp_person, "temp_purpose": temp_purpose, "temp_remarks": temp_remarks },
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            if (response.includes('Successfully')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                })
                                resetSignedDocList(req_id, cus_id);// to reset the current status of the signed list
                                resetChequeList(req_id, cus_id);// to reset the current status of the cheque list
                                resetGoldList(req_id, cus_id);// to reset the current status of the gold list
                                resetDocmentList(req_id, cus_id);// to reset the current status of the gold list
                                $('.closetempout').trigger('click');
                            } else if (response.includes('Error')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'error',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                });
                            }
                        }
                    })
                }
            }
        }

        function submitForTakeIn() {

            if (tempinSubmitValidation() == true) {
                if (confirm('Are you sure to take this Document In?')) {

                    let temp_person = $('#tempin_person').val(); let temp_purpose = $('#tempin_purpose').val(); let temp_remarks = $('#tempin_remarks').val();
                    let table_id = $('#table_id_tempin').val(); let table_name = $('#table_name_tempin').val();
                    let req_id = $('#req_id_tempin').val(); let cus_id = $('#cus_id_tempin').val();
                    $.ajax({
                        url: 'updateFile/submitTempDocument.php',
                        data: { "type": 'in', "table_id": table_id, "table_name": table_name, "temp_person": temp_person, "temp_purpose": temp_purpose, "temp_remarks": temp_remarks },
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            if (response.includes('Successfully')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                })
                                resetSignedDocList(req_id, cus_id);// to reset the current status of the document history
                                resetChequeList(req_id, cus_id);// to reset the current status of the cheque list
                                resetGoldList(req_id, cus_id);// to reset the current status of the gold list
                                resetDocmentList(req_id, cus_id);// to reset the current status of the gold list
                                $('.closetempin').trigger('click');
                            } else if (response.includes('Error')) {
                                Swal.fire({
                                    title: response,
                                    icon: 'error',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#009688'
                                });
                            }
                        }
                    })
                }
            }
        }

        function tempoutSubmitValidation() {
            let temp_person = $('#tempout_person').val(); let temp_purpose = $('#tempout_purpose').val(); let temp_remarks = $('#tempout_remarks').val();
            let response = true;
            if (temp_person == '') {
                event.preventDefault();
                $('#tempoutpersonCheck').show();
                response = false;
            } else {
                $('#tempoutpersonCheck').hide();
            }
            if (temp_purpose == '') {
                event.preventDefault();
                $('#tempoutpurposeCheck').show();
                response = false;
            } else {
                $('#tempoutpurposeCheck').hide();
            }
            if (temp_remarks == '') {
                event.preventDefault();
                $('#tempoutremarksCheck').show();
                response = false;
            } else {
                $('#tempoutremarksCheck').hide();
            }
            return response;
        }
        function tempinSubmitValidation() {
            let temp_person = $('#tempin_person').val(); let temp_purpose = $('#tempin_purpose').val(); let temp_remarks = $('#tempin_remarks').val();
            let response = true;
            if (temp_person == '') {
                event.preventDefault();
                $('#tempinpersonCheck').show();
                response = false;
            } else {
                $('#tempinpersonCheck').hide();
            }
            if (temp_purpose == '') {
                event.preventDefault();
                $('#tempinpurposeCheck').show();
                response = false;
            } else {
                $('#tempinpurposeCheck').hide();
            }
            if (temp_remarks == '') {
                event.preventDefault();
                $('#tempinremarksCheck').show();
                response = false;
            } else {
                $('#tempinremarksCheck').hide();
            }
            return response;
        }
    });

}

//Motrgage info
function getMortgageInfo(req_id, cus_id) {
    $.ajax({
        url: 'updateFile/getMortgageInfo.php',
        data: { "req_id": req_id, "cus_id": cus_id },
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#mortgage_process').val(response['mort_process']);
            // $('#mortgage_process').attr('disabled',true)
            if (response['mort_process'] == '0') {
                $('#mortgage_div').show();

                $('#Propertyholder_type').val(response['prop_holder_type']);

                if (response['prop_holder_type'] != '2') {

                    $('#Propertyholder_name').show();
                    $('#Propertyholder_name').val(response['prop_holder_name']);
                    $('#Propertyholder_relationship_name').hide();

                } else if (response['prop_holder_type'] == '2') {

                    $('#Propertyholder_relationship_name').show();
                    $('#Propertyholder_relationship_name').val(response['prop_holder_rel']);
                    $('#Propertyholder_name').hide();
                }

                $('#doc_property_relation').val(response['doc_prop_rel']);
                $('#doc_property_pype').val(response['doc_prop_type']);
                $('#doc_property_measurement').val(response['doc_prop_meas']);
                $('#doc_property_location').val(response['doc_prop_loc']);
                $('#doc_property_value').val(response['doc_prop_val']);

                $('#mortgage_name').val(response['mort_name']);
                $('#mortgage_dsgn').val(response['mort_des']);
                $('#mortgage_nuumber').val(response['mort_num']);
                $('#reg_office').val(response['reg_office']);
                $('#mortgage_value').val(response['mort_value']);
                $('#mortgage_document').val(response['mort_doc']);

                if (response['mort_doc'] == '0') {//show file input button if document already uploaded. so then user also can upload again with updated file
                    $('#mort_doc_upd').show()
                    $('#pendingchk').removeAttr('checked');
                } else {
                    $('#mort_doc_upd').hide()
                    $('#pendingchk').prop('checked', true);
                }

                $('#mortgage_doc_upd').val(response['mort_doc_upd']);//store file name inside hidden input if already uploaded

            } else {
                $('#mortgage_div').hide();
            }
        }
    })
}

//Endorsement info
function getEndorsementInfo(req_id, cus_id) {
    $.ajax({
        url: 'updateFile/getEndorsementInfo.php',
        data: { "req_id": req_id, "cus_id": cus_id },
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#endorsement_process').val(response['end_process']);

            if (response['end_process'] == '0') {
                $('#end_process_div').show();

                $('#owner_type').val(response['owner_type']);//like customer, garentor

                if (response['owner_type'] != '2') {

                    $('#owner_name').show();
                    $('#owner_name').val(response['owner_name']);
                    $('#ownername_relationship_name').hide();

                } else if (response['owner_type'] == '2') {

                    $('#ownername_relationship_name').show();
                    $('#ownername_relationship_name').val(response['owner_rel_name']);//fam id
                    $('#owner_name').hide();
                }
                // $('#owner_name').val(response['owner_name']);
                // $('#ownername_relationship_name').val(response['owner_rel_name']);//fam id


                $('#en_relation').val(response['owner_relation']);//like father, brother

                $('#vehicle_type').val(response['vehicle_type']);//new or old
                $('#vehicle_process').val(response['vehicle_process']);
                $('#en_Company').val(response['vehicle_comp']);
                $('#en_Model').val(response['vehicle_mod']);
                $('#vehicle_reg_no').val(response['vehicle_reg_no']);

                $('#endorsement_name').val(response['end_name']);
                $('#en_RC').val(response['end_rc']);
                $('#en_Key').val(response['end_key']);

                if (response['end_rc'] == '0') {//show file input button if document already uploaded. so then user also can upload again with updated file
                    $('#end_doc_upd').show()
                    $('#endorsependingchk').removeAttr('checked');
                } else {
                    $('#end_doc_upd').hide()
                    $('#endorsependingchk').prop('checked', true);
                }

                $('#rc_doc_upd').val(response['end_rc_doc_upd']);//store file name inside hidden input if already uploaded

            } else {
                $('#end_process_div').hide();
            }
        }
    })
}

//to update in table of ack documentation
function updateMortEndorse(id, req_id) {

    if (id == 'update_mortgage') {
        var formdata = $('#mort_form').serializeArray();
        var file_data = $('#mortgage_document_upd').prop('files')[0];
    } else if (id == 'update_endorsement') {
        var file_data = $('#RC_document_upd').prop('files')[0];
        var formdata = $('#end_form').serializeArray();
    }
    // var mortgage_document_upd = $('#mortgage_document_upd')[0].files;
    formdata.push({ name: 'id', value: id }, { name: 'req_id', value: req_id });

    $.ajax({
        url: 'updateFile/updateMortEndorse.php',
        data: formdata,
        type: 'post',
        cache: false,
        success: function (response) {
            if (file_data == undefined) {
                if (response.includes('Successfully')) {
                    Swal.fire({
                        title: response,
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    })
                    getDocumentHistory();// to reset the current status of the document history
                } else if (response.includes('Error')) {
                    Swal.fire({
                        title: response,
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });
                }
            }
        }
    }).then(function () {


        var filetosend = new FormData();

        if (id == 'update_mortgage') {
            var file_data = $('#mortgage_document_upd').prop('files')[0];
            filetosend.append('mortgage_document_upd', file_data)
        } else if (id == 'update_endorsement') {
            var file_data = $('#RC_document_upd').prop('files')[0];
            filetosend.append('RC_document_upd', file_data)
        }
        filetosend.append('id', id);
        filetosend.append('req_id', req_id);
        if (file_data != undefined) {//if file has been choosen then upload it
            $.ajax({
                url: 'updateFile/updateMortEndorseDocs.php',
                data: filetosend,
                contentType: false,
                processData: false,
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Successfully')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        getDocumentHistory();// to reset the current status of the document history
                    } else if (response.includes('Error')) {
                        Swal.fire({
                            title: response,
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                }
            })
        }

    })
}

//to validate mortgage and endorsement
function MEValidation(id) {
    var response = true;
    if (id == 'update_mortgage') {
        var mortgage_process = $('#mortgage_process').val(); var Propertyholder_type = $('#Propertyholder_type').val(); var Propertyholder_name = $('#Propertyholder_name').val();
        var Propertyholder_relationship_name = $('#Propertyholder_relationship_name').val(); var doc_property_relation = $('#doc_property_relation').val();
        var doc_property_pype = $('#doc_property_pype').val(); var doc_property_measurement = $('#doc_property_measurement').val();
        var doc_property_location = $('#doc_property_location').val(); var doc_property_value = $('#doc_property_value').val();
        var mortgage_name = $('#mortgage_name').val(); var mortgage_dsgn = $('#mortgage_dsgn').val(); var mortgage_nuumber = $('#mortgage_nuumber').val();
        var reg_office = $('#reg_office').val(); var mortgage_value = $('#mortgage_value').val(); var mortgage_document = $('#mortgage_document').val();
        var mortgage_doc_upd = $('#mortgage_document_upd').val(); var mortgage_old_doc_upd = $('#mortgage_doc_upd').val();

        if (mortgage_process == '') {
            event.preventDefault();
            $('#mortgageprocessCheck').show();
            response = false;
        } else {

            if (mortgage_process == '0') {// only check if mortgage process yes

                validateField(Propertyholder_type, '#propertyholdertypeCheck');

                if (Propertyholder_type != '' && Propertyholder_type == '2') {//check holder type is family
                    validateField(Propertyholder_relationship_name, '#propertyholdernameCheck');
                } else if (Propertyholder_type != '' && Propertyholder_type != '2') {//check holder type is family
                    // validateField(Propertyholder_name, '#propertyholdernameCheck');
                }

                validateField(doc_property_pype, '#docpropertytypeCheck');
                validateField(doc_property_measurement, '#docpropertymeasureCheck');
                validateField(doc_property_location, '#docpropertylocCheck');
                validateField(doc_property_value, '#docpropertyvalueCheck');
                validateField(mortgage_name, '#mortgagenameCheck');
                validateField(mortgage_dsgn, '#mortgagedsgnCheck');
                validateField(mortgage_nuumber, '#mortgagenumCheck');
                validateField(reg_office, '#regofficeCheck');
                validateField(mortgage_value, '#mortgagevalueCheck');
                validateField(mortgage_document, '#mortgagedocCheck');
                if (mortgage_document != '' && mortgage_document == '0' && mortgage_old_doc_upd == '') {// check if document is yes
                    validateField(mortgage_doc_upd, '#mortgagedocUpdCheck');//if yes then validate file uploaded or not
                }
            }
            $('#mortgageprocessCheck').hide();
        }
    } else if (id == 'update_endorsement') {
        var endorsement_process = $('#endorsement_process').val(); var owner_type = $('#owner_type').val(); var ownername_relationship_name = $('#ownername_relationship_name').val();
        var vehicle_type = $('#vehicle_type').val(); var vehicle_process = $('#vehicle_process').val(); var en_Company = $('#en_Company').val(); var en_Model = $('#en_Model').val();
        var endorsement_name = $('#endorsement_name').val(); var en_RC = $('#en_RC').val(); var en_Key = $('#en_Key').val();
        var vehicle_reg_no = $('#vehicle_reg_no').val(); var RC_document_upd = $('#RC_document_upd').val(); var RC_old_document_upd = $('#rc_doc_upd').val();

        if (endorsement_process == '') {
            event.preventDefault();
            $('#endorsementprocessCheck').show();
            response = false;
        } else {

            if (endorsement_process == '0') {// only check if Endorsement process yes
                validateField(owner_type, '#ownertypeCheck');

                if (owner_type != '' && owner_type != '2') {//check owner type is not family
                    validateField(owner_name, '#ownernameCheck');
                } else if (owner_type != '' && owner_type == '2') {//check owner type is family
                    validateField(ownername_relationship_name, '#ownernameCheck');
                }
                validateField(vehicle_type, '#vehicletypeCheck');
                validateField(vehicle_process, '#vehicleprocessCheck');
                validateField(en_Company, '#enCompanyCheck');
                validateField(en_Model, '#enModelCheck');
                // validateField(vehicle_reg_no, '#vehicle_reg_noCheck');
                validateField(endorsement_name, '#endorsementnameCheck');
                validateField(en_Key, '#enKeyCheck');
                validateField(en_RC, '#enRCCheck');
                if (en_RC != '' && en_RC == '0' && RC_old_document_upd == '') {// check if rc document is yes
                    validateField(RC_document_upd, '#rcdocUpdCheck');//if yes then validate file uploaded or not
                }
            }

            $('#endorsementprocessCheck').hide();

        }
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

// to get family details of customer to get fingerprint
function getFingerPrintDetails(req_id, cus_id, cus_name) {
    $.ajax({
        url: 'verificationFile/getNamesForFingerprint.php',
        data: { 'req_id': req_id, 'cus_name': cus_name, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (html) {
            $('.fingerprintTable').empty()
            $('.fingerprintTable').html(html)

            $('.scanBtn').click(function () {
                var hand = $(this).prev().val();
                var name = $(this).parent().prev().find('input[id="name_print"]').val(); var adhar = $(this).parent().prev().prev().find('input[id="adhar_print"]').val();
                if (hand == '') { //prevent if hand is not selected
                    $(this).prev().css('border-color', 'red');
                } else {
                    $(this).prev().css('border-color', '#009688')

                    showOverlay();//loader start

                    $(this).attr('disabled', true);

                    setTimeout(() => {
                        var quality = 60; //(1 to 100) (recommended minimum 55)
                        var timeout = 10; // seconds (minimum=10(recommended), maximum=60, unlimited=0)
                        var res = CaptureFinger(quality, timeout);
                        if (res.httpStaus) {
                            if (res.data.ErrorCode == "0") {
                                let fdata = res.data.AnsiTemplate;
                                $(this).next().val(fdata); // Take ansi template that is the unique id which is passed by sensor
                                storeFingerprints(fdata, hand, adhar, name);//stores the current finger data in database
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
                        // Hide the loading animation and remove blur effect from the body
                        hideOverlay();//loader stop

                    }, 700)
                }
            })
        }
    })

    function storeFingerprints(fdata, hand, cus_id, cus_name) {//stores the current finger data in database
        $.post('updateFile/storeFingerprints.php', { 'fdata': fdata, 'hand': hand, 'cus_id': cus_id, 'cus_name': cus_name }, function (response) {
            if (response.includes('Successfully')) {
                Swal.fire({
                    title: response, icon: 'success', confirmButtonColor: '#009688'
                })
            }
        }, 'json')
    }
}

/************************ Signed Doc Modal Events ************************/

//reset table contents of sign table modal
function resetsignInfo(req_id, cus_id) {
    $('#doc_req_id').val(req_id); $('#doc_cus_id').val(cus_id);
    $.ajax({
        url: 'updateFile/sign_info_upd_reset.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#signTable").empty();
            $("#signTable").html(html);

            $("#sign_type").val('');
            $("#signType_relationship").val('');
            $("#doc_Count").val('');
            $("#signedID").val('');
            $("#signdoc_upd").val('');

        }
    }).then(function () {
        signInfoEditEvent();//call for event listener
    })
}

//to set on click event for edit of signed document(upload button click)
function signInfoEditEvent() {

    $('.signed_doc_edit').off('click');
    $('.signed_doc_edit').click(function () {

        $('#signInfoBtn').removeAttr('disabled');// remove disabled attribute to submit button

        getFamilyList('signType_relationship');// to set family data to select box

        let id = $(this).attr('value');
        $.ajax({
            url: 'verificationFile/documentation/signed_doc_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#signedID").val(result['id']);
                $("#sign_type").val(result['sign_type']);

                if (result['sign_type'] == '3') {
                    $('#relation_doc').show();
                    $("#signType_relationship").val(result['signType_relationship']);

                } else {
                    $('#relation_doc').hide();
                }

                $("#doc_Count").val(result['doc_Count']);

            }
        });

    });

}

// to validate the count to be uploaded in signed doc
function filesCount() {
    var cnt = $('#doc_Count').val();
    var signFile = document.querySelector('#signdoc_upd');

    if (signFile.files.length <= cnt) {
        return true;
    } else {
        alert('Please select Less than or equals to ' + cnt + ' file(s).')
        $("#signdoc_upd").val('');
        return false;
    }
}

//submit signed document
function submitSignedDoc(req_id, cus_id) {
    let formdata = new FormData();

    let files = $("#signdoc_upd")[0].files;
    let signedID = $("#signedID").val();

    if (files.length > 0) {

        $('#docupdCheck').hide();

        for (var i = 0; i < files.length; i++) {
            formdata.append('signdoc_upd[]', files[i])
        }
        formdata.append('req_id', req_id)
        formdata.append('cus_id', cus_id)
        formdata.append('signedID', signedID)

        $.ajax({
            type: 'POST',
            url: 'updateFile/sign_info_doc_upload.php',
            data: formdata,
            contentType: false,
            processData: false,
            success: function (response) {

                if (response.includes("Uploaded")) {
                    $('#signInsertOk').show();
                    setTimeout(function () {
                        $('#signInsertOk').fadeOut('fast');
                    }, 2000);
                    getDocumentHistory();// to reset the current status of the document history
                } else {
                    $('#signNotOk').show();
                    setTimeout(function () {
                        $('#signNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetsignInfo(req_id, cus_id);
            }
        });
    } else {
        $('#docupdCheck').show();
    }

}
/************************ Signed Doc Modal Events ************************/


/************************ Cheque Modal Events ************************/

//reset table contents of Cheque table modal
function resetchequeInfo(req_id, cus_id) {

    $('#cheque_req_id').val(req_id);//set req id in modal form 

    $.ajax({
        url: 'updateFile/cheque_info_upd_reset.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $('#chequeColumnDiv').empty();
            $("#chequeTable").empty();
            $("#chequeTable").html(html);

            $("#holder_type").val('');
            $("#holder_name").val('');
            $("#holder_relationship_name").val('');
            $("#cheque_relation").val('');
            $("#chequebank_name").val('');
            $("#cheque_count").val('');
            $("#cheque_upd").val('');
            $("#chequeID").val('');

        }
    }).then(function () {
        chequeInfoEditEvent();//call for event listener
    })
}

//to set on click event for edit of cheque (entry button click)
function chequeInfoEditEvent() {

    $('.cheque_info_edit').off('click');
    $('.cheque_info_edit').click(function () {

        let id = $(this).attr('value');
        $('#chequeInfoBtn').removeAttr('disabled');// remove disabled attribute to submit button

        getFamilyList('holder_relationship_name'); // Holder Name From Family Table.

        $.ajax({
            url: 'verificationFile/documentation/cheque_info_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#chequeID").val(result['id']);
                $("#holder_type").val(result['holder_type']);


                if (result['holder_type'] != '2') {
                    $('#holder_name').show();
                    $('#holder_relationship_name').hide();
                    $("#holder_name").val(result['holder_name']);

                } else {
                    $('#holder_name').hide();
                    $('#holder_relationship_name').show();

                    $("#holder_relationship_name").val(result['holder_relationship_name']);
                }

                $("#cheque_relation").val(result['cheque_relation']);
                $("#chequebank_name").val(result['chequebank_name']);
                $("#cheque_count").val(result['cheque_count']);

                getChequeColumn(result['cheque_count']); // show input to insert Cheque No.
            }
        });

    });
}

//Create Div and cheque no input elements based on count of cheque need to upload
function getChequeColumn(cnt) {

    $.ajax({
        url: 'verificationFile/documentation/cheque_info_upd_column.php',
        data: { "count": cnt },
        type: 'post',
        success: function (result) {
            $('#chequeColumnDiv').empty();
            $('#chequeColumnDiv').html(result);

        }
    })

}

// to validate the count to be uploaded in cheque
function chequefilesCount() {
    var cnt = $('#cheque_count').val();
    var chequeFile = document.querySelector('#cheque_upd');

    if (chequeFile.files.length == cnt) {
        return true;
    } else {
        alert('Please select ' + cnt + ' file(s).')
        $("#cheque_upd").val('');
        return false;
    }
}

//submit cheque document
function submitCheque(req_id, cus_id) {

    let formdata = new FormData();

    let files = $("#cheque_upd")[0].files;//cheque documents
    let chequeID = $("#chequeID").val();
    let holder_type = $("#holder_type").val();
    let holder_name = $("#holder_name").val();
    let holder_relationship_name = $("#holder_relationship_name").val();
    let chequebank_name = $("#chequebank_name").val();
    let cheque_count = $("#cheque_count").val();

    var chequeArr = []; //for storing cheque no
    var i = 0;
    $('.chequeno').each(function () {//cheque numbers input box
        chequeArr[i] = $(this).val();//store each numbers in an array
        i++;
    })

    if (files.length == cheque_count && !chequeArr.includes('')) { // !chequeArr.includes('') will check if any of array values is empty

        $('#chequeupdCheck').hide();

        for (var i = 0; i < files.length; i++) {
            formdata.append('cheque_upd[]', files[i])
        }

        formdata.append('req_id', req_id)
        formdata.append('cus_id', cus_id)
        formdata.append('holder_type', holder_type)
        formdata.append('holder_name', holder_name)
        formdata.append('holder_relationship_name', holder_relationship_name)
        formdata.append('chequebank_name', chequebank_name)
        formdata.append('cheque_count', cheque_count)

        formdata.append('chequeID', chequeID)
        formdata.append('cheque_upd_no', chequeArr)

        $.ajax({
            url: 'updateFile/cheque_upload.php',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function (response) {

                var insresult = response.includes("Uploaded");
                if (insresult) {
                    $('#chequeInsertOk').show();
                    setTimeout(function () {
                        $('#chequeInsertOk').fadeOut('fast');
                    }, 2000);
                    getDocumentHistory();// to reset the current status of the document history
                }
                else {
                    $('#chequeNotOk').show();
                    setTimeout(function () {
                        $('#chequeNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetchequeInfo(req_id, cus_id);
            }
        });
    } else {
        $('#chequeupdCheck').show();
    }

}

/************************ Cheque Modal Events ************************/


/************************ Gold Modal Events ************************/

//reset table contents of gold table modal
function resetgoldInfo(req_id, cus_id) {
    $.ajax({
        url: 'updateFile/gold_info_reset.php',
        data: { "req_id": req_id, "cus_id": cus_id, "pages": 2 },
        type: 'POST',
        cache: false,
        success: function (html) {
            $("#goldTable").empty();
            $("#goldTable").html(html);

            $("#gold_sts").val('');
            $("#gold_type").val('');
            $("#Purity").val('');
            $("#gold_Count").val('');
            $("#gold_Weight").val('');
            $("#gold_Value").val('');
            $("#gold_upload").val('');
            $("#goldID").val('');
        }
    }).then(function () {
        goldInfoEditEvent();
    })
}

//to set on click event for edit of gold 
function goldInfoEditEvent() {
    $('.gold_info_edit').off().click(function () {
        let id = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/documentation/gold_info_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#goldID").val(result['id']);
                $("#gold_sts").val(result['gold_sts']);
                $("#gold_type").val(result['gold_type']);
                $("#Purity").val(result['Purity']);
                $("#gold_Count").val(result['gold_Count']);
                $("#gold_Weight").val(result['gold_Weight']);
                $("#gold_Value").val(result['gold_Value']);
                $("#goldupload").val(result['gold_upload']);

            }
        });
    });
}

//submit gold 
function submitGoldInfo(req_id, cus_id) {

    if (goldValidation() == true) {

        // let formdata = $('#goldform').serializeArray();
        // formdata.push({ name: 'req_id', value: req_id })
        // formdata.push({ name: 'cus_id', value: cus_id })

        // let req_id = $('#req_id').val();
        // let cus_id = $('#cus_id').val();
        let gold_sts = $("#gold_sts").val();
        let gold_type = $("#gold_type").val();
        let Purity = $("#Purity").val();
        let gold_Count = $("#gold_Count").val();
        let gold_Weight = $("#gold_Weight").val();
        let gold_Value = $("#gold_Value").val();
        let goldupload = $("#goldupload").val();
        let gold_upload = $("#gold_upload")[0];
        gold_upload = gold_upload.files[0];
        let goldID = $("#goldID").val();

        let formdata = new FormData();
        formdata.append('req_id', req_id);
        formdata.append('cus_id', cus_id);
        formdata.append('gold_sts', gold_sts);
        formdata.append('gold_type', gold_type);
        formdata.append('Purity', Purity);
        formdata.append('gold_Count', gold_Count);
        formdata.append('gold_Weight', gold_Weight);
        formdata.append('gold_Value', gold_Value);
        formdata.append('goldupload', goldupload);
        formdata.append('gold_upload', gold_upload);
        formdata.append('goldID', goldID);

        $.ajax({
            url: 'updateFile/gold_info_submit.php',
            type: 'POST',
            data: formdata,
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#goldInsertOk').show();
                    setTimeout(function () {
                        $('#goldInsertOk').fadeOut('fast');
                    }, 2000);
                    getDocumentHistory();// to reset the current status of the document history
                }
                else if (updresult) {
                    $('#goldUpdateok').show();
                    setTimeout(function () {
                        $('#goldUpdateok').fadeOut('fast');
                    }, 2000);
                    getDocumentHistory();// to reset the current status of the document history
                }
                else {
                    $('#goldNotOk').show();
                    setTimeout(function () {
                        $('#goldNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetgoldInfo(req_id, cus_id);
            }
        });

    }

}

//to validate Gold informations
function goldValidation() {
    var response = true;
    let gold_sts = $('#gold_sts').val(); let gold_type = $('#gold_type').val(); let Purity = $('#Purity').val();
    let gold_Count = $('#gold_Count').val(); let gold_Weight = $('#gold_Weight').val(); let gold_Value = $('#gold_Value').val();

    validateField(gold_sts, '#GoldstatusCheck');
    validateField(gold_type, '#GoldtypeCheck');
    validateField(Purity, '#purityCheck');
    validateField(gold_Count, '#goldCountCheck');
    validateField(gold_Weight, '#goldWeightCheck');
    validateField(gold_Value, '#goldValueCheck');

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

/************************ Gold Modal Events ************************/


/************************ Document Modal Events ************************/

//Document Info List Modal Table
function resetdocInfo(req_id, cus_id) {

    $.ajax({
        url: 'updateFile/doc_info_reset.php',
        type: 'POST',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#docModalDiv").empty();
            $("#docModalDiv").html(html);

            $("#document_name").val('');
            $("#document_details").val('');
            $("#document_type").val('');
            $("#document_holder").val('');
            $("#docholder_name").val('');
            $("#relation_name").val('');
            $("#doc_relation").val('');
            $("#document_info_upd").val('');

        }
    }).then(function () {
        docInfoEditEvent();
    })
}

//to set on click event for edit of gold 
function docInfoEditEvent() {
    $('.doc_info_edit').off('click')
    $('.doc_info_edit').click(function () {

        getFamilyList('docholder_relationship_name');//get member details

        let id = $(this).attr('value');
        $.ajax({
            url: 'verificationFile/documentation/doc_info_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (response) {

                $("#doc_info_id").val(response['doc_id']);
                $("#document_name").val(response['doc_name']);
                $("#document_details").val(response['doc_details']);
                $("#document_type").val(response['doc_type']);
                $("#document_holder").val(response['doc_holder']);
                if (response['doc_holder'] == '0' || response['doc_holder'] == '1') {
                    $("#docholder_name").show();
                    $("#docholder_relationship_name").hide();
                    $("#docholder_name").val(response['holder_name']);
                } else {
                    $("#docholder_name").hide();
                    $("#docholder_relationship_name").show();
                    $("#docholder_relationship_name").val(response['relation_name']);
                }
                $("#doc_relation").val(response['relation']);

            }
        });

    });
}

//submit document
function submitDocument(req_id, cus_id) {
    let formdata = new FormData();

    let files = $("#document_info_upd")[0].files;
    let doc_info_id = $("#doc_info_id").val();

    if (files.length > 0 && doc_info_id != '') {

        $('#docinfoupdCheck').hide();

        for (var i = 0; i < files.length; i++) {
            formdata.append('document_info_upd[]', files[i])
        }
        formdata.append('req_id', req_id)
        formdata.append('cus_id', cus_id)
        formdata.append('doc_info_id', doc_info_id)

        $.ajax({
            url: 'updateFile/doc_info_submit.php',
            data: formdata,
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {
                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#docInsertOk').show();
                    setTimeout(function () {
                        $('#docInsertOk').fadeOut('fast');
                    }, 2000);
                    getDocumentHistory();// to reset the current status of the document history
                }
                else if (updresult) {
                    $('#docUpdateok').show();
                    setTimeout(function () {
                        $('#docUpdateok').fadeOut('fast');
                    }, 2000);
                    getDocumentHistory();// to reset the current status of the document history
                }
                else {
                    $('#docNotOk').show();
                    setTimeout(function () {
                        $('#docNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetdocInfo(req_id, cus_id);
            }
        });
    } else {
        $('#docinfoupdCheck').show();
    }

}
/************************ Document Modal Events ************************/



////////////////////////////////////////////////////////////// Customer Old Data Functions //////////////////////////////////////////////////////////////

function OldCusValidation() {
    let response = true;
    let mobile_old = $('#mobile_old').val(); let area_old = $('#area_old').val(); let sub_area_old = $('#sub_area_old').val(); let loan_cat_old = $('#loan_cat_old').val(); let sub_cat_old = $('#sub_cat_old').val();
    let loan_amt_old = $('#loan_amt_old').val(); let due_chart_old = $('#due_chart_old').val();

    validateField(area_old, "area_old");
    validateField(sub_area_old, "sub_area_old");
    validateField(loan_cat_old, "loan_cat_old");
    validateField(sub_cat_old, "sub_cat_old");
    validateField(loan_amt_old, "loan_amt_old");
    validateField(due_chart_old, "due_chart_old");

    function validateField(value, fieldId) {
        if (value === '') {
            response = false;
            event.preventDefault();
            $("#" + fieldId + "Check").show();
        } else {
            $("#" + fieldId + "Check").hide();
        }
    }

    if (mobile_old === '' || mobile_old.length < 10) {
        response = false;
        event.preventDefault();
        $("#mobile_oldCheck").show();
    } else { $("#mobile_oldCheck").hide(); }
    return response;
}
function submitCustomerOldData() {
    let form_data = new FormData($('#cus_old_form')[0]);

    $.ajax({
        url: 'updateFile/submitCustomerOldData.php',
        type: 'POST',
        data: form_data,
        processData: false,
        contentType: false,
        success: function (response) {
            // Handle the response here
            if (response.includes('Successfully')) {
                Swal.fire({
                    icon: 'success',
                    title: response,
                    showConfirmButton: true,
                    confirmButtonColor: '#009688',
                    timer: 2000,
                    timerProgressBar: true,
                });
                $('#cus_old_form input:not([readonly])').val('');
                // $('.closeBtn_old').trigger('click');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: response,
                    showConfirmButton: true,
                    confirmButtonColor: '#009688',
                    timer: 2000,
                    timerProgressBar: true,
                })
            }
        }
    });
}

function showCustomerOldData() {
    let cus_id = $('#cus_id_old').val();
    $.post('updateFile/showCustomerOldData.php', { cus_id }, function (html) {
        $('#oldCusDataDiv').empty().html(html);
    })
}


