let storeDocInfo = {};
let loanidResponse = {};
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
        $("#propertyholdernameCheck").hide();
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

            getFamilyList('Propertyholder_relationship_name', '');

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

            $('#Propertyholder_type, #Propertyholder_name, #Propertyholder_relationship_name, #doc_property_relation, #doc_property_pype, #doc_property_measurement, #doc_property_location, #doc_property_value, #mortgage_name, #mortgage_dsgn, #mortgage_nuumber, #reg_office, #mortgage_value, #mortgage_document, #mortgage_document_upd, #mortgage_doc_upd').val('');//old uploaded name

            $('#mort_form').find('span').not('.slider, .required, .icon-check').hide(); //to hide the span.
        }
    })

    //Endrosement Info 
    $('#owner_type').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();
        $("#ownernameCheck").hide();
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

            getFamilyList('ownername_relationship_name', '');
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

            $('#owner_type, #owner_name, #ownername_relationship_name, #en_relation, #vehicle_type, #vehicle_process, #en_Company, #en_Model').val('');
            $('#end_form').find('span').not('.slider, .required, .icon-check').hide(); //to hide the span.
        }
    })

    //Customer Old Data
    $('#submit_old_cus_data').click(function () {
        if (OldCusValidation()) {
            submitCustomerOldData();
        }
    })

    //////////////////////////////////////////// Documentation START //////////////////////////////////////////////
    ///Hide AND Show doc Card START
    $('#choose_document').change(function () {
        let doc = $(this).val();

        // Hide all sections initially
        $('.edit-document-card').hide();

        // Show the selected document section
        switch (doc) {
            case '1': $('#signed_doc_card').show(); break;
            case '2': $('#cheque_info_card').show(); break;
            case '3': $('#mortgage_info_card').show(); break;
            case '4': $('#endorsement_info_card').show(); break;
            case '5': $('#gold_info_card').show(); break;
            case '6': $('#documents_info_card').show(); break;
            default: $('.edit-document-card').hide();
        }

        // Check if previous data exists in storeDocInfo and show relevant sections
        if (storeDocInfo.signDocInfo) {
            $('#signed_doc_card').show();
        }
        if (storeDocInfo.chequeInfo) {
            $('#cheque_info_card').show();
        }
        if (storeDocInfo.mortgageInfo) {
            $('#mortgage_info_card').show();
        }
        if (storeDocInfo.endorseInfo) {
            $('#endorsement_info_card').show();
        }
        if (storeDocInfo.goldInfo) {
            $('#gold_info_card').show();
        }
        if (storeDocInfo.docInfo) {
            $('#documents_info_card').show();
        }
    });
    ///Hide AND Show doc Card END

    /* ********************************************** Sign doc ********************************************** */
    $("#sign_type").change(function () {
        // Signed Type
        let type = $(this).val();

        $("#cus_name_div").hide();
        $("#guar_name_div").hide();
        $("#relation_doc").hide();
        $('#signTyperRelationshipCheck').hide();

        if (type == "0") {
            // if customer , then show Customer name
            let req_id = $("#req_id").val();

            $.ajax({
                type: "POST",
                url: "verificationFile/documentation/check_holder_name.php",
                data: { type: 0, reqId: req_id },
                dataType: "json",
                cache: false,
                success: function (result) {
                    $("#cus_name_div").show();
                    $("#signType_cus_name").val(result["name"]);
                },
            });

        }

        if (type == "1") {
            // if guarentor , then show guarentor name
            getGuarentorName();
        }

        if (type == "3" || type == "2") {
            // if type is combined or family member then show family members
            //for combined, it will represents who is signed with customer in the same document.
            $("#relation_doc").show();
            signTypeRelation();

        } else {
            $("#signType_relationship").val('');

        }

    });
    /* ********************************************** Sign doc END********************************************** */

    /* ********************************************** cheque START ********************************************** */

    $("#holder_type").change(function () {
        // Cheque info
        let type = $(this).val();
        let req_id = $("#req_id").val();
        $("#holderNameCheck").hide();
        if (type == "0") {
            $("#holder_name").show();
            $("#holder_relationship_name").hide();

            $.ajax({
                type: "POST",
                url: "verificationFile/documentation/check_holder_name.php",
                data: { type: type, reqId: req_id },
                dataType: "json",
                cache: false,
                success: function (result) {
                    $("#holder_name").val(result["name"]);
                    $("#cheque_relation").val("NIL");
                },
            });
        } else if (type == "1") {
            $("#holder_name").show();
            $("#holder_relationship_name").hide();

            $.ajax({
                type: "POST",
                url: "verificationFile/documentation/check_holder_name.php",
                data: { type: type, reqId: req_id },
                dataType: "json",
                cache: false,
                success: function (result) {
                    $("#holder_name").val(result["name"]);
                    $("#cheque_relation").val(result["relationship"]);
                },
            });
        } else if (type == "2") {
            $("#holder_name").hide();
            $("#holder_relationship_name").show();
            $("#cheque_relation").val("");

            chequeHolderName(); // Holder Name From Family Table.
        } else {
            $("#holder_name").show();
            $("#holder_relationship_name").hide();
            $("#holder_name").val("");
            $("#cheque_relation").val("");
        }
    });

    $("#holder_relationship_name").change(function () {
        let fam_id = $(this).val();
        $.ajax({
            url: "verificationFile/documentation/find_cheque_relation.php",
            type: "POST",
            data: { fam_id: fam_id },
            dataType: "json",
            success: function (response) {
                $("#cheque_relation").val(response);
            },
        });
    });

    /* ********************************************** cheque END ********************************************** */

    /* ********************************************** Document info START ********************************************** */

    $("#document_holder").change(function () {
        let type = $(this).val();
        let req_id = $("#req_id").val();
        $("#docHolderNameCheck").hide();

        if (type == "0") {
            //Customer
            $("#docholder_name").show();
            $("#docholder_relationship_name").val("");
            $("#docholder_relationship_name").hide();

            $.ajax({
                type: "POST",
                url: "verificationFile/documentation/check_holder_name.php",
                data: { type: type, reqId: req_id },
                dataType: "json",
                cache: false,
                success: function (result) {
                    $("#docholder_name").val(result["name"]);
                    $("#doc_relation").val("NIL");
                },
            });
        } else if (type == "1") {
            //Guarentor
            $("#docholder_name").show();
            $("#docholder_relationship_name").val("");
            $("#docholder_relationship_name").hide();

            $.ajax({
                type: "POST",
                url: "verificationFile/documentation/check_holder_name.php",
                data: { type: type, reqId: req_id },
                dataType: "json",
                cache: false,
                success: function (result) {
                    $("#docholder_name").val(result["name"]);
                    $("#doc_relation").val(result["relationship"]);
                },
            });
        } else if (type == "2") {
            //Family member
            $("#docholder_name").hide();
            $("#docholder_relationship_name").show();
            $("#docholder_name").val("");
            $("#doc_relation").val("");

            docHolderName();
        } else {
            $("#docholder_name").show();
            $("#docholder_relationship_name").hide();
            $("#docholder_name").val("");
            $("#doc_relation").val("");
        }
    });

    $("#docholder_relationship_name").change(function () {
        let fam_id = $(this).val();
        $.ajax({
            url: "verificationFile/documentation/find_cheque_relation.php",
            type: "POST",
            data: { fam_id: fam_id },
            dataType: "json",
            success: function (response) {
                $("#doc_relation").val(response);
            },
        });
    });

    $("#loan_id").change(function () {
        let gurantor_id = $(this).find(':selected').attr('gurantor_id');
        let gu_pic = $(this).find(':selected').attr('gu_pic');
        closeFamModal(gurantor_id, gu_pic)

    });

    $('#cus_monthly_income ,#cus_Commitment ,#cus_other_income ,#cus_support_income ,#cus_monDue_capacity , #cus_loan_limit').on('input', function () {
        let value = $(this).val();
        $(this).val(formatIndianNumber(value));
    });

  $("body").on("click", "#feedback_edit", function () {
    let id = $(this).attr("value");

    $.ajax({
      url: "verificationFile/get_feedback_edit.php",
      type: "POST",
      data: { id: id },
      dataType: "json",
      cache: false,
      success: function (result) {
        $("#fedbackname_id").val(result["id"]);
        $("#feedbackname").val(result["feedback_name"]);
      },
    });
  });

  $("body").on("click", "#feedback_delete", function () {
    let id = $(this).attr("value");
    if (confirm('Do You want to delete this Feedback Name?')) {
      $.ajax({
        url: "verificationFile/delet_feedback_edit.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        cache: false,
        success: function (result) {
        if (result === "DELETED") {
            Swal.fire({
              title: 'Feedback Label Deleted!',
              icon: 'success',
              confirmButtonColor: '#009688'
            });
            cusfeedbacklist();

          } else if (result === "USED") {
            Swal.fire({
              title: 'Already Used!',
              text: 'This feedback label is already used in Customer Feedback.',
              icon: 'warning',
              confirmButtonColor: '#009688'
            });

          } else {
            Swal.fire({
              title: 'Error Occurred!',
              icon: 'error',
              confirmButtonColor: '#009688'
            });
          }
        },
      });
    }
  });
  $(document).on("click", "#add_cus_label", function () {
    getFeedbackLable();
  })
  $(document).on("click", "#add_cus_feedback", function () {
    cusfeedbacklist();
    $("#feedbackname").val('');
    $("#fedbackname_id").val('');
  })
  $(document).on("click", "#submit_feedback_lable", function () {
    submitfeedbackname();
  })
    /* ********************************************** Document info END ********************************************** */

    //////////////////////////////////////////// Documentation END //////////////////////////////////////////////

});   ////////Document Ready End

$(function () {
    //  $('.icon-chevron-down1').parent().next('div').slideUp(); //To collapse all card on load
    let selectedScreens = $('#selected_screens').val();

    // Convert the string to an array
    let selectedArray = selectedScreens.split(',');

    if (selectedArray.length === 1 && selectedArray.includes('1')) {
        callCustomerProfileFunctn();

    } else if (selectedArray.length === 1 && selectedArray.includes('2')) {
        getDocumentHistory();

    } else if (selectedArray.includes('1') && selectedArray.includes('2')) {
        callCustomerProfileFunctn(); getDocumentHistory();

    }
    nameFormatter('#cus_name');
    nameFormatter('#famname');

}); //OnLoad function.

function callCustomerProfileFunctn() {
    getLoanID();

    resetPropertyinfoList() //Property Info List.

    resetbankinfoList(); //Bank Info List.

    resetkycinfoList(); //KYC Info List.

    feedbackList(); // Feedback List.

    getCustomerLoanCounts();//to get closed customer details

    var cus_id = $('#cus_id').val();
    var cus_name = $('#cus_name').val();
    if (cus_name != '' && cus_id != '') {
        getFingerPrintDetails(cus_id, cus_name);
    }
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

}

function callDocFunctn() {
    //Documentation
    getDocumentHistory();//for document history table
}

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
    let authorize = $("#authorize").val();

    if (famname != "" && relationship != "" && relation_aadhar != "" && relation_Mobile != "" && relation_Mobile.length === 10) {
        $.ajax({
            url: 'updateFile/update_family_submit.php',
            type: 'POST',
            data: { "famname": famname, "realtionship": relationship, "other_remark": other_remark, "other_address": other_address, "relation_age": relation_age, "relation_aadhar": relation_aadhar, "relation_Mobile": relation_Mobile, "relation_Occupation": relation_Occupation, "relation_Income": relation_Income, "relation_Blood": relation_Blood, "famTableId": famTableId, "cus_id": cus_id ,"authorize":authorize },
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
            $("#authorize").val('');
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
    let cus_name = $('#cus_name').val();

    $.ajax({
        url: 'verificationFile/verification_fam_list.php',
        type: 'POST',
        data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#famList").empty();
            $("#famList").html(html);
            getFingerPrintDetails(cus_id, cus_name);
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
            $("#authorize").val(result['authorize']);
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
                }
                else {

                    $('#FamDeleteNotOk').show();
                    setTimeout(function () {
                        $('#FamDeleteNotOk').fadeOut('fast');
                    }, 2000);

                }

                resetFamInfo();
            }
        });
    }
});



//FamilyModal Close
function getLoanID() {

    $.post('updateFile/get_loan_id.php', { "cus_id": $('#cus_id').val() }, function (data) {

        $("#loan_id").empty().append("<option value=''>" + 'Select Loan ID' + "</option>");
        let lastGuarantorID = "";
        let guarentor_photos = "";
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                let loanId = data[i]['loan_id'];
                let guarentorID = data[i]['guarentor_name'];
                let guarentor_photo = data[i]['guarentor_photo'] ? data[i]['guarentor_photo'] : "";
                let isLast = i === data.length - 1;
                let selected = isLast ? "selected" : "";
                if (isLast) {
                    lastGuarantorID = guarentorID;
                    guarentor_photos = guarentor_photo;
                }
                $("#loan_id").append("<option value='" + loanId + "' " + selected + " gurantor_id='" + guarentorID + "' gu_pic='" + guarentor_photo + "'>" + loanId + "</option>");
                loanidResponse = "true";
            }
            if (lastGuarantorID != '') {
                closeFamModal(lastGuarantorID, guarentor_photos);
            }

        }
        else {
            $("#guarentor_name").empty().append("<option value=''>" + 'Select Guarantor' + "</option>");
            $("#guarentor_relationship").val('');
            $("#guarentor_image").val(guarentor_photos);
            getImage();
            resetFamDetails();
            loanidResponse = "false";
        }

    }, 'json');

}

function closeFamModal(lastGuarantorID, guarentor_photos) {

    $.post('verificationFile/verificationFam.php', { "cus_id": $('#cus_id').val() }, function (data) {

        let guarentor_name_upd = lastGuarantorID;
        let optionSelected = '';
        $("#guarentor_name").empty().append("<option value=''>" + 'Select Guarantor' + "</option>");
        for (var i = 0; i < data.length - 1; i++) { // -1 because this ajax's response will contain customer value at the last of the response for verification person
            var fam_name = data[i]['fam_name']; var fam_id = data[i]['fam_id'];
            var selected = '';
            if (guarentor_name_upd != '' && fam_id == guarentor_name_upd) {
                selected = 'selected';
                optionSelected = true;
            }

            $("#guarentor_name").append("<option value='" + fam_id + "' " + selected + ">" + fam_name + "</option>");
        }
        if (optionSelected) {
            $("#guarentor_name").trigger('change');
            $("#guarentor_image").val(guarentor_photos);
        }

    }, 'json')

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
            // Sort property_holder dropdown
            sortDropdownAlphabetically("#property_holder");
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


    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let bank_name = $("#bank_name").val();
    let branch_name = $("#branch_name").val();
    let account_holder_name = $("#account_holder_name").val();
    let account_number = $("#account_number").val();
    let Ifsc_code = $("#Ifsc_code").val();
    let bank_upload = $('#bank_upload')[0].files[0];
    let bank_upload_id = $('#bank_upload_id').val();
    let bankID = $("#bankID").val();

    if (bank_name != "" && branch_name != "" && account_holder_name != "" && account_number != "" && Ifsc_code != "" && req_id != "") {
        // Using FormData to send file and other data
        let formData = new FormData();
        formData.append('bank_name', bank_name);
        formData.append('branch_name', branch_name);
        formData.append('account_holder_name', account_holder_name);
        formData.append('account_number', account_number);
        formData.append('Ifsc_code', Ifsc_code);
        formData.append('bank_upload', bank_upload);  // Append the file
        formData.append('bank_upload_id', bank_upload_id);
        formData.append('bankID', bankID);
        formData.append('reqId', req_id);
        formData.append('cus_id', cus_id);

        $.ajax({
            url: 'verificationFile/verification_bank_submit.php',
            type: 'POST',
            data: formData,  // Use FormData here
            cache: false,
            contentType: false,  // Important: Do not process contentType
            processData: false,  // Important: Do not process data
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
            $("#bank_upload").val('');
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
            $("#bank_upload_id").val(result['upload']);

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
    let famId = $("#guarentor_name").val();
    let fam_mem = $("#fam_mem").val();
    let proof_type = $("#proof_type").val();
    let proof_number = $("#proof_number").val();
    let kyc_upload = $("#kyc_upload").val();
    let kycID = $("#kycID").val();
    let upload = $("#upload")[0];
    let file = upload.files[0];


    let formdata = new FormData();
    formdata.append('upload', file)
    formdata.append('proofof', proofof)
    formdata.append('proofof', proofof)
    formdata.append('famId', famId)
    formdata.append('fam_mem', fam_mem)
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
                $('.name_div').hide();
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

            $(".fam_mem_div").hide();
            $('.name_div').hide();
            $("#fam_mem").val('');
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

            if (result['proofOf'] == 1) { //Guarentor
                let famId = $("#guarentor_name").val();
                $.post('verificationFile/verification_guarantor.php', { "famid": famId }, function (response) {
                    $('.name_div').show();
                    $('#proofofname').val(response['famname']);
                }, 'json')

                $("#fam_mem").val('');
                $('.fam_mem_div').hide();

            } else if (result['proofOf'] == 2) { //Family Members
                getfamilyforKyc(result['fam_mem']);
                // setTimeout(() => {
                //     $("#fam_mem").val(result['fam_mem']);
                // }, 1000);
                $('.fam_mem_div').show();
                $('.name_div').hide();

            } else { //Customer
                $("#fam_mem").val('');
                $('.fam_mem_div').hide();
                $('.name_div').show();
                let cus_name = $('#cus_name').val();
                $('#proofofname').val(cus_name);

            }

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
        }
    });
}

$('#proofof').change(function () {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let proof = $('#proofof').val();
    let famId = document.querySelector("#guarentor_name").value;

    if (proof == '0') {
        $.post('verificationFile/get_proof_of_name.php', { req_id, cus_id, proof }, function (response) {
            $('.name_div').show();
            $('#proofofname').val(response);
        }, 'json')

    } else if (proof == '1') {
        $.post('verificationFile/verification_guarantor.php', { "famid": famId }, function (response) {
            $('.name_div').show();
            $('#proofofname').val(response['famname']);
        }, 'json')

    } else {
        $('.name_div').hide()
    }

    if (proof != '2' && proof != '') { // if proof of is not family members then check for other's proofs entered already 
        $('.fam_mem_div').hide();//hide fam div on other proof of selected
        $('#fam_mem').val('');
        $.ajax({
            url: 'verificationFile/verification_proof_type.php',
            type: 'POST',
            data: { "reqId": req_id, "cus_id": cus_id, "proof": proof },
            dataType: 'json',
            cache: false,
            success: function (response) {

                $('#proof_type option').prop('disabled', false);

                $.each(response, function (index, value) {
                    $('#proof_type option[value="' + value + '"]').prop('disabled', true);
                });

            }
        });

    } else if (proof == '2') { // if proof of is family members then show family members dropdown 
        getfamilyforKyc();

    } else {
        $('.fam_mem_div').hide();
        $('#fam_mem').val('');

    }

});

function getfamilyforKyc(famMemEdit) {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verification_proof_fam.php',
        data: { "reqId": req_id, cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('.fam_mem_div').show();
            $('#fam_mem').empty();
            $('#fam_mem').append(`<option value=""> Select Family Member </option>`);
            $.each(response, function (index, value) {
                let selected = '';
                if (famMemEdit == value.id) {
                    selected = 'selected';
                }

                $('#fam_mem').append("<option value='" + value.id + "' " + selected + ">" + value.fam_mem + "</option>");
            });
        }

    }).then(function () {
        $('#fam_mem').off().change(function () {
            let req_id = $('#req_id').val(); let proof = $('#proofof').val(); let fam_name = $(this).val();
            $.ajax({
                url: 'verificationFile/verification_proof_type.php',
                type: 'POST',
                data: { "reqId": req_id, "proof": proof, "fam_name": fam_name, "cus_id": cus_id },
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
    })
}

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

    // Sort district dropdown
    sortDropdownAlphabetically("#district");
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

    // Sort area_district dropdown
    sortDropdownAlphabetically("#area_district");
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

    // Sort taluk dropdown
    sortDropdownAlphabetically("#taluk");
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

    // Sort area_taluk dropdown
    sortDropdownAlphabetically("#area_taluk");
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

            // Sort area dropdown
            sortDropdownAlphabetically("#area");
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
    let loanLimit = parseInt($(this).val().replace(/,/g, ''));
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
            getImage();

        }
    });

});

///Customer profile submit///
$('#submit_update_cus_profile').click(function () {
    if (validation()) {
        let confirmAction = confirm("Are you sure you want to submit Loan Issue ?");
        if (!confirmAction) {
            event.preventDefault(); // Stop form submission if canceled
            return false;
        }
    } else {
        event.preventDefault();
        return false;
    }

});

function validation() {
    var cus_id = $('#cus_id').val(); var cus_name = $('#cus_name').val(); var dob = $('#dob').val(); var gender = $('#gender').val(); var state = $('#state').val();
    var cus_image = $('#cus_image').val(); var pic = $('#pic').val();
    var district = $('#district').val(); var taluk = $('#taluk').val(); var area = $('#area').val(); var sub_area = $('#sub_area').val(); var cus_address = $('#cus_address').val();
    var mobile1 = $('#mobile1').val(); var mobile2 = $('#mobile2').val(); var father_name = $('#father_name').val(); var mother_name = $('#mother_name').val(); var marital = $('#marital').val();
    var occupation_type = $('#occupation_type').val(); var occupation = $('#occupation').val(); var area_cnfrm = $('#area_cnfrm').val(); var cus_res_type = $('#cus_res_type').val();
    var cus_res_details = $('#cus_res_details').val(); var cus_res_address = $('#cus_res_address').val(); var cus_res_native = $('#cus_res_native').val();
    var cus_occ_type = $('#cus_occ_type').val(); var cus_occ_detail = $('#cus_occ_detail').val(); var cus_occ_income = $('#cus_occ_income').val(); var cus_occ_address = $('#cus_occ_address').val(); var cus_occ_dow = $('#cus_occ_dow').val(); var cus_occ_abt = $('#cus_occ_abt').val();
    var area_state = $('#area_state').val(); var area_district = $('#area_district').val(); var area_taluk = $('#area_taluk').val();
    var area_confirm = $('#area_confirm').val(); var area_sub_area = $('#area_sub_area').val();
    var cus_how_know = $('#cus_how_know').val(); var cus_monthly_income = $('#cus_monthly_income').val(); var cus_other_income = $('#cus_other_income').val(); var cus_support_income = $('#cus_support_income').val(); var cus_Commitment = $('#cus_Commitment').val(); var cus_monDue_capacity = $('#cus_monDue_capacity').val(); var cus_loan_limit = $('#cus_loan_limit').val(); var about_cus = $('#about_cus').val();
    var guarentor_name = $('#guarentor_name').val(); var guarentor_image = $('#guarentor_image').val(); var guarentorpic = $('#guarentorpic').val(); var loan_id = $('#loan_id').val(); var validation = true;

    if (cus_id == '') {
        event.preventDefault();
        validation = false;
        $('#cusidCheck').show();
    } else {
        $('#cusidCheck').hide();
    }
    if (cus_name == '') {
        event.preventDefault();
        validation = false;
        $('#cusnameCheck').show();
    } else {
        $('#cusnameCheck').hide();
    }
    if (dob == '') {
        event.preventDefault();
        validation = false;
        $('#dobCheck').show();
    } else {
        $('#dobCheck').hide();
    }
    if (gender == '') {
        event.preventDefault();
        validation = false;
        $('#genderCheck').show();
    } else {
        $('#genderCheck').hide();
    }
    if (state == 'SelectState') {
        event.preventDefault();
        validation = false;
        $('#stateCheck').show();
    } else {
        $('#stateCheck').hide();
    }
    if (district == 'Select District') {
        event.preventDefault();
        validation = false;
        $('#districtCheck').show();
    } else {
        $('#districtCheck').hide();
    }
    if (taluk == 'Select Taluk') {
        event.preventDefault();
        validation = false;
        $('#talukCheck').show();
    } else {
        $('#talukCheck').hide();
    }
    if (area == '') {
        event.preventDefault();
        validation = false;
        $('#areaCheck').show();
    } else {
        $('#areaCheck').hide();
    }
    if (sub_area == '') {
        event.preventDefault();
        validation = false;
        $('#subareaCheck').show();
    } else {
        $('#subareaCheck').hide();
    }
    if (cus_address == '') {
        event.preventDefault();
        validation = false;
        $('#addressCheck').show();
    } else {
        $('#addressCheck').hide();
    }
    if (mobile1 == '' || mobile1.length < 10) {
        event.preventDefault();
        validation = false;
        $('#mobile1Check').show();
    } else {
        $('#mobile1Check').hide();
    }
    if (mobile2 != '' && mobile2.length < 10) {
        event.preventDefault();
        validation = false;
        $('#mobile2Check').show();
    } else {
        $('#mobile2Check').hide();
    }
    if (father_name == '') {
        event.preventDefault();
        validation = false;
        $('#fathernameCheck').show();
    } else {
        $('#fathernameCheck').hide();
    }
    if (mother_name == '') {
        event.preventDefault();
        validation = false;
        $('#mothernameCheck').show();
    } else {
        $('#mothernameCheck').hide();
    }
    if (marital == '') {
        event.preventDefault();
        validation = false;
        $('#maritalCheck').show();
    } else {
        $('#maritalCheck').hide();
    }
    if (occupation_type == '') {
        event.preventDefault();
        validation = false;
        $('#occupationtypeCheck').show();
    } else {
        $('#occupationtypeCheck').hide();
    }
    if (occupation == '') {
        event.preventDefault();
        validation = false;
        $('#occupationCheck').show();
    } else {
        $('#occupationCheck').hide();
    }
    if (area_cnfrm == '0') {
        $('#areacnfrmCheck').hide();
        if (cus_res_type == '' || cus_res_details == '' || cus_res_address == '' || cus_res_native == '') {
            event.preventDefault();
            validation = false;
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
            validation = false;
            $('#res_infoCheck').hide();
            $('#occ_infoCheck').show();
        } else {
            $('#res_infoCheck').hide();
            $('#occ_infoCheck').hide();
        }
    } else {
        event.preventDefault();
        validation = false;
        $('#areacnfrmCheck').show();
    }
    if (area_state == 'SelectState') {
        event.preventDefault();
        validation = false;
        $('#areastateCheck').show();
    } else {
        $('#areastateCheck').hide();
    }
    if (area_district == 'Select District') {
        event.preventDefault();
        validation = false;
        $('#areadistrictCheck').show();
    } else {
        $('#areadistrictCheck').hide();
    }
    if (area_taluk == 'Select Taluk') {
        event.preventDefault();
        validation = false;
        $('#areatalukCheck').show();
    } else {
        $('#areatalukCheck').hide();
    }
    if (area_confirm == '') {
        event.preventDefault();
        validation = false;
        $('#areaconfirmCheck').show();
    } else {
        $('#areaconfirmCheck').hide();
    }
    if (area_sub_area == '') {
        event.preventDefault();
        validation = false;
        $('#areasubareaCheck').show();
    } else {
        $('#areasubareaCheck').hide();
    }
    if (cus_how_know == '') {
        event.preventDefault();
        validation = false;
        $('#howToKnowCheck').show();
    } else {
        $('#howToKnowCheck').hide();
    }
    if (cus_monthly_income == '') {
        event.preventDefault();
        validation = false;
        $('#monthlyIncomeCheck').show();
    } else {
        $('#monthlyIncomeCheck').hide();
    }
    if (cus_other_income == '') {
        event.preventDefault();
        validation = false;
        $('#otherIncomeCheck').show();
    } else {
        $('#otherIncomeCheck').hide();
    }
    if (cus_support_income == '') {
        event.preventDefault();
        validation = false;
        $('#supportIncomeCheck').show();
    } else {
        $('#supportIncomeCheck').hide();
    }
    if (cus_Commitment == '') {
        event.preventDefault();
        validation = false;
        $('#commitmentCheck').show();
    } else {
        $('#commitmentCheck').hide();
    }
    if (cus_monDue_capacity == '') {
        event.preventDefault();
        validation = false;
        $('#monthlyDueCapacityCheck').show();
    } else {
        $('#monthlyDueCapacityCheck').hide();
    }
    if (cus_loan_limit == '') {
        event.preventDefault();
        validation = false;
        $('#loanLimitCheck').show();
    } else {
        $('#loanLimitCheck').hide();
    }
    if (about_cus == '') {
        event.preventDefault();
        validation = false;
        $('#aboutcusCheck').show();
    } else {
        $('#aboutcusCheck').hide();
    }
    if (pic == '') {
        if (cus_image == '') {
            event.preventDefault();
            validation = false;
            $('#customerpicCheck').show();
        } else {
            $('#customerpicCheck').hide();
        }
    } else {
        $('#customerpicCheck').hide();
    }

    if (loanidResponse === "true") {
        if (guarentor_name == '') {
            event.preventDefault();
            validation = false;
            $('#guarentor_nameCheck').show();
        } else {
            $('#guarentor_nameCheck').hide();
        }
        if (guarentor_image == '') {
            if (guarentorpic == '') {
                event.preventDefault();
                validation = false;
                $('#guarentorpicCheck').show();
            } else {
                $('#guarentorpicCheck').hide();
            }
        }
        if (loan_id == '') {
            event.preventDefault();
            validation = false;
            $('#loan_idCheck').show();
        } else {
            $('#loan_idCheck').hide();
        }
    }
    return validation;
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
            if (response.length != 0) {//check json response is not empty

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
            $('.edit-doc').off('click');
            $(document).on('click', '.edit-doc', function () {
                $('.dropdown').not($(this).parent()).children().css('border-color', '');// to set other dropdown buttons as normal
                $(this).parent().prev().css('border-color', 'red');// showing selected loan's dropdown button highlighted

                $('.choosing-document-card').show();

                var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid'); var cus_name = $(this).data('cusname')
                var doc_id = $(this).data('docid')
                $('#documents_status_header').html(`Documents - Doc ID: ${doc_id}`);
                getDocumentDetails(req_id, cus_id, cus_name);
                $('#req_id_doc').val(req_id);
            });
        });
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
    getDocstatusInfo(req_id, cus_id); // to get doc details



    $('#update_mortgage, #update_endorsement,#update_doc_sts').off('click');
    $('#update_mortgage, #update_endorsement,#update_doc_sts').click(function () {//submit events of mort and endorsement
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
            $("#signType_cus_name").val('');
            $("#cus_name_div").hide();
            $("#guar_name").val("");
            $("#guar_name_div").hide();
            $("#signType_relationship").val('');
            $("#relation_doc").hide();
            $("#doc_Count").val('');
            $("#signedID").val('');
            $("#signdoc_upd").val('');

            let hasRecords = ($('#signed_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#signed_doc_card').show();

            } else {
                $('#signed_doc_card').hide();

            }

            storeDocInfo.signDocInfo = hasRecords;

        }
    }).then(function () {
        $('#signed_table').DataTable().destroy();
        // Declare table variable to store the DataTable instance
        var signed_table = $('#signed_table').DataTable({
            ...getStateSaveConfig('signed_table'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Signed_Doc_info'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(signed_table, 'signed_table');
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

            let hasRecords = ($('#cheque_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#cheque_info_card').show();

            } else {
                $('#cheque_info_card').hide();

            }

            storeDocInfo.chequeInfo = hasRecords;

        }
    }).then(function () {
        $('#cheque_table').DataTable().destroy();
        // Declare table variable to store the DataTable instance
        var cheque_table = $('#cheque_table').DataTable({
            ...getStateSaveConfig('cheque_table'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Cheque_info'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(cheque_table, 'cheque_table');
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

            let hasRecords = ($('#gold_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#gold_info_card').show();

            } else {
                $('#gold_info_card').hide();

            }

            storeDocInfo.goldInfo = hasRecords;

        }
    }).then(function () {
        $('#gold_table').DataTable().destroy();
        // Declare table variable to store the DataTable instance
        var gold_table = $('#gold_table').DataTable({
            ...getStateSaveConfig('gold_table'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Gold_info'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(gold_table, 'gold_table');
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

            let hasRecords = ($('#document_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#documents_info_card').show();

            } else {
                $('#documents_info_card').hide();

            }

            storeDocInfo.docInfo = hasRecords;

        }
    }).then(function () {
        $('#document_table').DataTable().destroy();
        // Declare table variable to store the DataTable instance
        var document_table = $('#document_table').DataTable({
            ...getStateSaveConfig('document_table'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Document_info'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
        setTempDocumentEvents();//temp document click events
        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(document_table, 'document_table');
    })
}

//to get Family names
function getFamilyList(id, hiddenValue) {

    let cus_id = $('#cus_id_load').val();

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#" + id).empty();
            $("#" + id).append("<option value=''>Select Person Name</option>");
            for (var i = 0; i < len - 1; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                let selected = '';
                if (fam_id == hiddenValue) {
                    selected = 'selected';
                }

                $("#" + id).append(`<option value='${fam_id}' ${selected}>${fam_name}</option>`);
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


        getFamilyList('tempout_rel_name', '');
        getFamilyList('tempin_rel_name', '');
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
function getMortgageInfo(req_id) {
    $.ajax({
        url: 'updateFile/getMortgageInfo.php',
        data: { "req_id": req_id },
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
                    getFamilyList('Propertyholder_relationship_name', response['prop_holder_rel'])
                    // $('#Propertyholder_relationship_name').val(response['prop_holder_rel']);
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

                let mortDocUpd = response['mort_doc_upd'];
                $('#mortgage_doc_upd').val(mortDocUpd);//store file name inside hidden input if already uploaded
                $('#mort_doc_img').attr('href', `uploads/verification/mortgage_doc/${mortDocUpd}`).text(mortDocUpd);

            } else {
                $('#mortgage_div').hide();
            }

            let mort = ($('#mortgage_process').val() == '0') ? true : false;
            if (mort) {
                $('#mortgage_info_card').show();

            } else {
                $('#mortgage_info_card').hide();

            }

            storeDocInfo.mortgageInfo = mort;

        }
    })
}
function getDocstatusInfo(req_id) {
    $.ajax({
        url: 'updateFile/getMortgageInfo.php',
        data: { "req_id": req_id },
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {

            let sts = response['doc_sts'];

            if (sts === 'YES' || sts === '' || sts === null || sts === undefined) {
                $('#doc_sts').prop('checked', true);   // checked
            } else {
                $('#doc_sts').prop('checked', false);  // unchecked
            }
            $('#doc_remark').val(response['doc_remark']);
            $('#update_remark').val(response['update_remark']);
        }
    })
}
//Endorsement info
function getEndorsementInfo(req_id) {
    $.ajax({
        url: 'updateFile/getEndorsementInfo.php',
        data: { "req_id": req_id },
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
                    getFamilyList('ownername_relationship_name', response['owner_rel_name'])
                    // $('#ownername_relationship_name').val(response['owner_rel_name']);//fam id
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

                let rcDocUpd = response['end_rc_doc_upd'];
                $('#rc_doc_upd').val(rcDocUpd);//store file name inside hidden input if already uploaded
                $('#rc_doc_img').attr('href', `uploads/verification/endorsement_doc/${rcDocUpd}`).text(rcDocUpd);

            } else {
                $('#end_process_div').hide();
            }

            let endorse = ($('#endorsement_process').val() == '0') ? true : false;
            if (endorse) {
                $('#endorsement_info_card').show();

            } else {
                $('#endorsement_info_card').hide();

            }

            storeDocInfo.endorseInfo = endorse;

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
    else if (id == 'update_doc_sts') {
        var doc_sts_val = $('#doc_sts').is(':checked') ? 'YES' : 'NO';
        var formdata = $('#doc_sts_form').serializeArray();
        // If unchecked → doc_sts is missing, so add it manually
        formdata.push({ name: 'doc_sts', value: doc_sts_val });
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
            filetosend.append('mortgage_document_old_upd', $('#mortgage_doc_upd').val())
        } else if (id == 'update_endorsement') {
            var file_data = $('#RC_document_upd').prop('files')[0];
            filetosend.append('RC_document_upd', file_data)
            filetosend.append('RC_document_old_upd', $('#rc_doc_upd').val())
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
                dataType: 'json',
                success: function (result) {
                    if (result.response.includes('Successfully')) {
                        Swal.fire({
                            title: result.response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });

                        var docUpdName = result.doc_upd_name;
                        if (id == 'update_mortgage') {
                            $('#mortgage_doc_upd').val(docUpdName);//hidden value.
                            $('#mort_doc_img').attr('href', `uploads/verification/mortgage_doc/${docUpdName}`).text(docUpdName);

                        } else if (id == 'update_endorsement') {
                            $('#rc_doc_upd').val(docUpdName);
                            $('#rc_doc_img').attr('href', `uploads/verification/endorsement_doc/${docUpdName}`).text(docUpdName);

                        }

                        getDocumentHistory();// to reset the current status of the document history
                    } else if (result.response.includes('Error')) {
                        Swal.fire({
                            title: result.response,
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
        // var mortgage_doc_upd = $('#mortgage_document_upd').val(); var mortgage_old_doc_upd = $('#mortgage_doc_upd').val();

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
                // if (mortgage_document != '' && mortgage_document == '0' && mortgage_old_doc_upd == '') {// check if document is yes
                //     validateField(mortgage_doc_upd, '#mortgagedocUpdCheck');//if yes then validate file uploaded or not
                // }
            }
            $('#mortgageprocessCheck').hide();
        }
    } else if (id == 'update_endorsement') {
        var endorsement_process = $('#endorsement_process').val(); var owner_type = $('#owner_type').val(); var ownername_relationship_name = $('#ownername_relationship_name').val();
        var vehicle_type = $('#vehicle_type').val(); var vehicle_process = $('#vehicle_process').val(); var en_Company = $('#en_Company').val(); var en_Model = $('#en_Model').val();
        var endorsement_name = $('#endorsement_name').val(); var en_RC = $('#en_RC').val(); var en_Key = $('#en_Key').val();
        // var vehicle_reg_no = $('#vehicle_reg_no').val(); var RC_document_upd = $('#RC_document_upd').val(); var RC_old_document_upd = $('#rc_doc_upd').val();

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
                // if (en_RC != '' && en_RC == '0' && RC_old_document_upd == '') {// check if rc document is yes
                //     validateField(RC_document_upd, '#rcdocUpdCheck');//if yes then validate file uploaded or not
                // }
            }

            $('#endorsementprocessCheck').hide();

        }
    }
    else if (id == 'update_doc_sts') {
        var update_remark = $('#update_remark').val().trim();
        if (update_remark == '') {
            validateField(update_remark, '#update_remarkcheck');
        } else {
            $('#update_remarkcheck').hide();

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
function getFingerPrintDetails(cus_id, cus_name) {
    $.ajax({
        url: 'verificationFile/getNamesForFingerprint.php',
        data: { 'cus_name': cus_name, 'cus_id': cus_id },
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
            $('#signDocUploads input:not(#doc_name_dummy), #signDocUploads select').attr('disabled', false);
            $("#sign_type").val('');
            $("#cus_name_div").hide();
            $("#signType_cus_name").val('');
            $("#guar_name_div").hide();
            $("#guar_name").val("");
            $("#relation_doc").hide();
            $("#signType_relationship").val('');
            $("#doc_Count").val('');
            $("#signedID").val('');
            $("#signdoc_upd").val('');

            //to hide span after submit.
            $("#signTypeCheck").hide();
            $("#docCountCheck").hide();
            $('#docupdCheck').hide();
            $("#signTyperRelationshipCheck").hide();
        }
    }).then(function () {
        signInfoEditEvent();//call for event listener
    })
}

//to set on click event for edit of signed document(upload button click)
function signInfoEditEvent() {

    $('.signed_doc_edit').off('click');
    $('.signed_doc_edit').click(function () {

        $('#signInfoBtn').removeAttr('disabled');  // enable submit button if needed

        let id = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/documentation/signed_doc_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                // FIRST remove readonly/disabled to allow setting value
                $('#signDocUploads input, #signDocUploads select').attr('disabled', false);

                $("#signedID").val(result['id']);
                $("#sign_type").val(result['sign_type']);

                if (result["sign_type"] == "0") {
                    $("#cus_name_div").show();
                    $("#signType_cus_name").val(result["signType_cus_name"]);
                } else {
                    $("#cus_name_div").hide();
                }

                if (result["sign_type"] == "1") {
                    $("#guar_name_div").show();
                    $("#guar_name").val(result["guar_name"]);
                } else {
                    $("#guar_name_div").hide();
                }

                if (result['sign_type'] == '3' || result["sign_type"] == "2") {
                    $('#relation_doc').show();
                    getFamilyList('signType_relationship', result['signType_relationship']);
                } else {
                    $('#relation_doc').hide();
                }

                $("#doc_Count").val(result['doc_Count']);

                // NOW apply readonly mode after values are set
                // Disable everything EXCEPT upload field
                $('#signDocUploads input:not(#signdoc_upd), #signDocUploads select').attr('disabled', true);


            }
        });

    });

}


// to validate the count to be uploaded in signed doc
// function filesCount() {
//     var cnt = $('#doc_Count').val();
//     var signFile = document.querySelector('#signdoc_upd');

//     if (signFile.files.length <= cnt) {
//         return true;
//     } else {
//         alert('Please select Less than or equals to ' + cnt + ' file(s).')
//         $("#signdoc_upd").val('');
//         return false;
//     }
// }

//submit signed document
function submitSignedDoc(req_id, cus_id) {
    let formdata = new FormData();

    let files = $("#signdoc_upd")[0].files;
    let signedID = $("#signedID").val();
    let sign_type = $("#sign_type").val();
    let doc_Count = $("#doc_Count").val();
    let signType_relationship = $("#signType_relationship").val();

    if (sign_type != "" && doc_Count != "" && ((sign_type == "2" || sign_type == "3") ? signType_relationship != "" : true)) {

        for (var i = 0; i < files.length; i++) {
            formdata.append('signdoc_upd[]', files[i])
        }
        formdata.append('req_id', req_id)
        formdata.append('cus_id', cus_id)
        formdata.append('signedID', signedID)
        formdata.append('sign_type', sign_type)
        formdata.append('doc_Count', doc_Count)
        formdata.append('signType_relationship', signType_relationship)

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
        $("#docNameCheck").hide();
        $("#signTypeCheck").hide();
        $("#docCountCheck").hide();
        $("#signTyperRelationshipCheck").hide();
    } else {

        if (sign_type == "") {
            $("#signTypeCheck").show();
        } else {
            $("#signTypeCheck").hide();
        }

        if (doc_Count == "") {
            $("#docCountCheck").show();
        } else {
            $("#docCountCheck").hide();
        }
        if (sign_type == '2' || sign_type == '3') {
            if (signType_relationship == '') {
                $('#signTyperRelationshipCheck').show();
            } else {
                $('#signTyperRelationshipCheck').hide();
            }
        }
        // if(files.length <= 0 || files.length != doc_Count){
        //     $('#docupdCheck').show();
        // }else{
        //     $('#docupdCheck').hide();
        // }
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

            //to hide span after submit.
            $("#holdertypeCheck").hide();
            $("#chequebankCheck").hide();
            $("#chequeCountCheck").hide();
            $('#chequeupdCheck').hide();
            $("#holderNameCheck").hide();
            $('#chequeUploads input, #chequeUploads select').attr('disabled', false);
            $('#cheque_upd_no').attr('disabled', false);

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

        $.ajax({
            url: 'verificationFile/documentation/cheque_info_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {
                $('#chequeUploads input, #chequeUploads select').attr('disabled', false);
                $("#chequeID").val(result['id']);
                $("#holder_type").val(result['holder_type']);


                if (result['holder_type'] != '2') {
                    $('#holder_name').show();
                    $('#holder_relationship_name').hide();
                    $("#holder_name").val(result['holder_name']);

                } else {
                    $('#holder_name').hide();
                    $('#holder_relationship_name').show();
                    getFamilyList('holder_relationship_name', result['holder_relationship_name']); // Holder Name From Family Table.
                    // $("#holder_relationship_name").val(result['holder_relationship_name']);
                }

                $("#cheque_relation").val(result['cheque_relation']);
                $("#chequebank_name").val(result['chequebank_name']);
                $("#cheque_count").val(result['cheque_count']);

                getChequeColumn(result['cheque_count'], result['cheque_no']); // show input to insert Cheque No.
                $('#chequeUploads input:not(#cheque_upd), #chequeUploads select').attr('disabled', true);
            }
        });

    });

    $('#cheque_count').off().keyup(function () {
        let chequeCnt = $(this).val();
        getChequeColumn(chequeCnt, ''); // show input to insert Cheque No.
    });

}

//Create Div and cheque no input elements based on count of cheque need to upload
function getChequeColumn(cnt, nos) {

    $.ajax({
        url: 'verificationFile/documentation/cheque_info_upd_column.php',
        data: { "count": cnt, "cheque_nos": JSON.stringify(nos) },
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
    let cheque_relation = $("#cheque_relation").val();
    let chequebank_name = $("#chequebank_name").val();
    let cheque_count = $("#cheque_count").val();

    var chequeArr = []; //for storing cheque no
    var i = 0;
    $('.chequeno').each(function () {//cheque numbers input box
        chequeArr[i] = $(this).val();//store each numbers in an array
        i++;
    })

    if (holder_type != "" && chequebank_name != "" && cheque_count != "" && req_id != "" && !chequeArr.includes('') && ((holder_type == "2") ? holder_relationship_name != "" : true)) { // !chequeArr.includes('') will check if any of array values is empty

        for (var i = 0; i < files.length; i++) {
            formdata.append('cheque_upd[]', files[i])
        }

        formdata.append('req_id', req_id)
        formdata.append('cus_id', cus_id)
        formdata.append('holder_type', holder_type)
        formdata.append('holder_name', holder_name)
        formdata.append('holder_relationship_name', holder_relationship_name)
        formdata.append('cheque_relation', cheque_relation)
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
        if (holder_type == "") {
            $("#holdertypeCheck").show();
        } else {
            $("#holdertypeCheck").hide();
        }

        if (chequebank_name == "") {
            $("#chequebankCheck").show();
        } else {
            $("#chequebankCheck").hide();
        }

        if (cheque_count == "") {
            $("#chequeCountCheck").show();
        } else {
            $("#chequeCountCheck").hide();
        }

        if (chequeArr.includes('')) {
            $('#chequeNoCheck').show();
        } else {
            $('#chequeNoCheck').hide();
        }
        if (holder_type == '2') {
            if (holder_relationship_name == "") {
                $("#holderNameCheck").show();
            } else {
                $("#holderNameCheck").hide();
            }
        }
        // if (files.length != cheque_count || chequeArr.includes('')) {
        //     $('#chequeupdCheck').show();
        // } else {
        //     $('#chequeupdCheck').hide();
        // }

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
            $('#goldform input, #goldform select').attr('disabled', false);
            $("#gold_sts").val('');
            $("#gold_type").val('');
            $("#Purity").val('');
            $("#gold_Count").val('');
            $("#gold_Weight").val('');
            $("#gold_Value").val('');
            $("#gold_upload").val('');
            $("#goldID").val('');

            $('#GoldstatusCheck, #GoldtypeCheck, #purityCheck, #goldCountCheck, #goldWeightCheck, #goldValueCheck').hide(); //to hide span.
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
                $('#goldform input, #goldform select').attr('disabled', false);
                $("#goldID").val(result['id']);
                $("#gold_sts").val(result['gold_sts']);
                $("#gold_type").val(result['gold_type']);
                $("#Purity").val(result['Purity']);
                $("#gold_Count").val(result['gold_Count']);
                $("#gold_Weight").val(result['gold_Weight']);
                $("#gold_Value").val(result['gold_Value']);
                $("#goldupload").val(result['gold_upload']);
                $('#goldform input:not(#gold_upload), #goldform select').attr('disabled', true);
            }
        });
    });
}

//submit gold 
function submitGoldInfo(req_id, cus_id) {

    if (goldValidation() == true) {

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
            $('#docUploads input, #docUploads select').attr('disabled', false);
            $("#document_name").val('');
            $("#document_details").val('');
            $("#document_type").val('');
            $("#document_holder").val('');
            $("#docholder_name").val('');
            $("#docholder_relationship_name").val('');
            $("#relation_name").val('');
            $("#doc_relation").val('');
            $("#document_info_upd").val('');

            $("#documentnameCheck").hide();
            $("#documentdetailsCheck").hide();
            $("#documentTypeCheck").hide();
            $("#docholderCheck").hide();
            $('#docinfoupdCheck').hide();
            $("#docHolderNameCheck").hide();

        }
    }).then(function () {
        docInfoEditEvent();
    })
}

//to set on click event for edit of gold 
function docInfoEditEvent() {
    $('.doc_info_edit').off('click')
    $('.doc_info_edit').click(function () {

        let id = $(this).attr('value');
        $.ajax({
            url: 'verificationFile/documentation/doc_info_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (response) {
                $('#docUploads input, #docUploads select').attr('disabled', false);

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
                    getFamilyList('docholder_relationship_name', response['relation_name']);//get member details
                    // $("#docholder_relationship_name").val(response['relation_name']);
                }
                $("#doc_relation").val(response['relation']);
                $('#docUploads input:not(#document_info_upd), #docUploads select').attr('disabled', true);

            }
        });

    });
}

//submit document
function submitDocument(req_id, cus_id) {
    let formdata = new FormData();

    let files = $("#document_info_upd")[0].files;
    let doc_info_id = $("#doc_info_id").val();
    let doc_name = $("#document_name").val();
    let doc_details = $("#document_details").val();
    let doc_type = $("#document_type").val();
    let doc_holder = $("#document_holder").val();
    let holder_name = $("#docholder_name").val();
    let relation_name = $("#docholder_relationship_name").val();
    let relation = $("#doc_relation").val();

    if (doc_name != "" && doc_details != "" && doc_type != "" && doc_holder != "" && ((doc_holder === "2") ? relation_name !== "" : true)) {

        for (var i = 0; i < files.length; i++) {
            formdata.append('document_info_upd[]', files[i])
        }

        formdata.append('req_id', req_id)
        formdata.append('cus_id', cus_id)
        formdata.append('doc_info_id', doc_info_id)
        formdata.append('doc_name', doc_name)
        formdata.append('doc_details', doc_details)
        formdata.append('doc_type', doc_type)
        formdata.append('doc_holder', doc_holder)
        formdata.append('holder_name', holder_name)
        formdata.append('relation_name', relation_name)
        formdata.append('relation', relation)

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

        if (!doc_name) {
            $("#documentnameCheck").show();
        } else {
            $("#documentnameCheck").hide();
        }

        if (!doc_details) {
            $("#documentdetailsCheck").show();
        } else {
            $("#documentdetailsCheck").hide();
        }

        if (!doc_type) {
            $("#documentTypeCheck").show();
        } else {
            $("#documentTypeCheck").hide();
        }

        if (!doc_holder) {
            $("#docholderCheck").show();
        } else {
            $("#docholderCheck").hide();
        }
        if (doc_holder == '2') {
            if (relation_name == "") {
                $("#docHolderNameCheck").show();
            } else {
                $("#docHolderNameCheck").hide();
            }
        }
        // if(files.length <= 0){
        //     $('#docinfoupdCheck').show();
        // } else {
        //     $('#docinfoupdCheck').hide();
        // }
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

function getGuarentorName() {
    let req_id = $("#req_id").val();
    let cus_id = $("#cus_id").val();
    $.ajax({
        url: "verificationFile/getGuarentorName.php",
        type: "post",
        data: { req_id: req_id, cus_id: cus_id },
        cache: false,
        success: function (response) {
            $("#guar_name_div").show();
            $("#guar_name").val(response);
        },
    });
}

// Signed Doc
function signTypeRelation() {
    let cus_id = $("#cus_id").val();
    $.ajax({
        url: "verificationFile/verificationFam.php",
        type: "post",
        data: { cus_id: cus_id },
        dataType: "json",
        cache: false,
        success: function (response) {
            var len = response.length;
            $("#signType_relationship").empty();
            $("#signType_relationship").append(
                "<option value=''>" + "Select Relationship" + "</option>"
            );
            for (var i = 0; i < len - 1; i++) {
                //-1 because last name will be customer name
                var fam_name = response[i]["fam_name"];
                var fam_id = response[i]["fam_id"];
                var relationship = response[i]["relationship"];
                $("#signType_relationship").append(
                    "<option value='" +
                    fam_id +
                    "'>" +
                    fam_name +
                    " - " +
                    relationship +
                    "</option>"
                );
            }
            // Sort signType_relationship dropdown
            sortDropdownAlphabetically("#signType_relationship");
        },
    });
}

//Cheque Holder Name
function chequeHolderName() {
    let cus_id = $("#cus_id").val();
    $.ajax({
        url: "verificationFile/verificationFam.php",
        type: "post",
        data: { cus_id: cus_id },
        dataType: "json",
        success: function (response) {
            var len = response.length;
            $("#holder_relationship_name").empty();
            $("#holder_relationship_name").append(
                "<option value=''>" + "Select Holder Name" + "</option>"
            );
            for (var i = 0; i < len - 1; i++) {
                //-1 because last one name will be customer name
                var fam_name = response[i]["fam_name"];
                var fam_id = response[i]["fam_id"];
                $("#holder_relationship_name").append(
                    "<option value='" + fam_id + "'>" + fam_name + "</option>"
                );
            }
            // Sort holder_relationship_name dropdown
            sortDropdownAlphabetically("#holder_relationship_name");
        },
    });
}

function docHolderName(callback) {
    let cus_id = $("#cus_id").val();

    $.ajax({
        url: "verificationFile/verificationFam.php",
        type: "post",
        data: { cus_id: cus_id },
        dataType: "json",
        success: function (response) {
            var len = response.length;
            $("#docholder_relationship_name").empty();
            $("#docholder_relationship_name").append(
                "<option value=''>" + "Select Holder Name" + "</option>"
            );
            for (var i = 0; i < len - 1; i++) {
                // -1 because this ajax's response will contain customer value at the last of the response for verification person
                var fam_name = response[i]["fam_name"];
                var fam_id = response[i]["fam_id"];
                $("#docholder_relationship_name").append(
                    "<option value='" + fam_id + "'>" + fam_name + "</option>"
                );
            }
            // Sort docholder_relationship_name dropdown
            sortDropdownAlphabetically("#docholder_relationship_name");

            if (typeof callback === "function") {
                callback();
            }
        },
    });
}
function getFeedbackLable() {
    $.post(
        "verificationFile/getFeedbackLable.php",
        function (data) {
            $("#feedback_label") .empty() .append("<option value=''>Select Feedback Label</option>");

            for (var i = 0; i < data.length; i++) {
                var feedback_name = data[i]["feedback_name"];
                var id = data[i]["id"];
                $("#feedback_label").append( "<option value='" + id + "'>" + feedback_name + "</option>"
                );
            }
        },
        "json"
    );
}

function cusfeedbacklist() {
  $.ajax({
    url: "verificationFile/getFeedbackList.php",
    type: "POST",
    cache: false,
    success: function (html) {
      $("#cus_feedbackListTable_div").empty();
      $("#cus_feedbackListTable_div").html(html);
    },
  });
}

function submitfeedbackname() {
 let feedbackname = $("#feedbackname").val();
 let id = $("#fedbackname_id").val();

  if (feedbackname != "") {
    $.ajax({
      url: "verificationFile/submitFeedbackName.php",
      data: {
        feedbackname: feedbackname,
        id:id
      },
      dataType: "json",
      type: "POST",
      cache: false,
      success: function (response) {
                if (response.includes('Inserted')) {
                    Swal.fire({
                        title: 'Feedback Label Inserted...!',
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });
                } else if (response.includes(' Updated')) {
                    Swal.fire({
                        title: 'Feedback Label Updated...!',
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });
                } else if(response.includes('Already')){
                    Swal.fire({
                        title: 'Feedback Label Already Existed',
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });
                }else if(response.includes('Failed')){
                    Swal.fire({
                        title: 'Error Occures',
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });
                }
        $("#feedbackname").val('');
        $("#fedbackname_id").val('');
        cusfeedbacklist();
      },
    });

  }
}