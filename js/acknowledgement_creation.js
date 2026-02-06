const personMultiselect = new Choices('#verification_person', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Verification Person',
    allowHTML: true
});
personMultiselect.disable();// to disable verficiation person dropdown

const replaceDOCMultiselect = new Choices('#replace_doc_id', {
    removeItemButton: true,
    placeholder: true,
    placeholderValue: 'Select Replace Document ID',
    allowHTML: true
});

let storeDocInfo = {};

$(document).ready(function () {

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date in the date input to today
        $('#due_start_from').attr('min', today);
    }

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

    // Verification Tab Change Radio buttons
    $('#cus_profile,#documentation,#loan_calc').click(function () {
        var verify = $('input[name=verification_type]:checked').val();

        if (verify == 'cus_profile') {
            $('#customer_profile').show(); $('#cus_document').hide(); $('#customer_loan_calc').hide();

        }
        if (verify == 'documentation') {
            $('#customer_profile').hide(); $('#cus_document').show(); $('#customer_loan_calc').hide();
            onLoadDocEditFunction();
            getDocumentFunc();

        }
        if (verify == 'loan_calc') {
            $('#customer_profile').hide(); $('#cus_document').hide(); $('#customer_loan_calc').show();
            onLoadEditFunction();
            getUserBasedLoanCategory().then(function () {
                var loan_category = $('#loan_category').val(); // Get the selected loan category
                return getSubCategory(loan_category); // Return the promise from getSubCategory
            }).then(function () {
                // After both functions are executed
                var sub_cat_id = $('#sub_category').val();
                getCategoryInfo();
                getLoaninfo(sub_cat_id);
                profitCalculationInfo();
            });

        }
    })

    ///Documentation 

    // Signed Modal Doc Upload.

    $('#add_sign_doc').click(function () {
        $('#signDocUploads input').not('#signType_cus_name, #guar_name').prop('readonly', false);
        $('#signDocUploads select').prop('disabled', false);
    });

    $('#sign_type').change(function () { // Signed Type 
        let type = $(this).val();

        $("#cus_name_div").hide();
        $('#guar_name_div').hide();
        $('#relation_doc').hide();
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

        if (type == '1') { // if guarentor , then show guarentor name
            getGuarentorName();
        }

        if (type == '3' || type == '2') {
            // if type is combined or family member then show family members
            //for combined, it will represents who is signed with customer in the same document.
            $('#relation_doc').show();
            signTypeRelation();

        } else {
            $('#signType_relationship').val('');

        }
    })

    $("body").on("click", "#signed_doc_edit", function () {
        let id = $(this).attr('value');
        signTypeRelation();

        $.ajax({
            url: 'verificationFile/documentation/signed_doc_edit.php',
            type: 'POST',
            data: { "id": id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#signedID").val(result['id']);
                $("#sign_type").val(result['sign_type']);

                if (result["sign_type"] == "0") {
                    //if Customer
                    $("#cus_name_div").show();
                    $("#signType_cus_name").val(result["signType_cus_name"]);

                } else {
                    $("#cus_name_div").hide();

                }

                if (result['sign_type'] == '1') {//if guarentor
                    $('#guar_name_div').show();
                    $("#guar_name").val(result['guar_name']);

                } else {
                    $('#guar_name_div').hide();
                }

                if (result['sign_type'] == '3' || result['sign_type'] == '2') {
                    $('#relation_doc').show();
                    $("#signType_relationship").val(result['signType_relationship']);

                } else {
                    $('#relation_doc').hide();
                }

                $("#doc_Count").val(result['doc_Count']);

            }
        });

    });

    $("body").on("click", "#signed_doc_delete", function () {
        var isok = confirm("Do you want delete this Signed Doc Info?");
        if (isok == false) {
            return false;
        } else {
            var signid = $(this).attr("value");

            $.ajax({
                url: "verificationFile/documentation/signed_doc_delete.php",
                type: "POST",
                data: { signid: signid },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");
                    if (delresult) {
                        $("#signDeleteOk").show();
                        setTimeout(function () {
                            $("#signDeleteOk").fadeOut("fast");
                        }, 2000);
                    } else {
                        $("#signDeleteNotOk").show();
                        setTimeout(function () {
                            $("#signDeleteNotOk").fadeOut("fast");
                        }, 2000);
                    }

                    resetsignInfo();
                },
            });
        }
    });

    $("#signDocUploads").on('submit', function (e) {
        e.preventDefault();

        let doc_name = $("#doc_name").val();
        let sign_type = $("#sign_type").val();
        let doc_Count = $("#doc_Count").val();
        let req_id = $('#doc_req_id').val();
        let signType_relationship = $("#signType_relationship").val();
        // let signeddoc_upd = $('#signdoc_upd').val(); //upload mandatory is removed.

        if (doc_name != "" && sign_type != "" && doc_Count != "" && req_id != "" && ((sign_type == "2" || sign_type == "3") ? signType_relationship != "" : true)) {
            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/sign_info_doc_upload.php',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function (response) {

                    var insresult = response.includes("Uploaded");
                    if (insresult) {
                        $('#signInsertOk').show();
                        setTimeout(function () {
                            $('#signInsertOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        $('#signNotOk').show();
                        setTimeout(function () {
                            $('#signNotOk').fadeOut('fast');
                        }, 2000);
                    }

                    resetsignInfo();
                }
            });

        } else {

            if (sign_type == "") {
                $('#signTypeCheck').show();
            } else {
                $('#signTypeCheck').hide();
            }

            if (doc_Count == "") {
                $('#docCountCheck').show();
            } else {
                $('#docCountCheck').hide();
            }

            // if (signeddoc_upd == "") {
            //     $('#docupdCheck').show();
            // } else {
            //     $('#docupdCheck').hide();
            // }

            if (sign_type == '2' || sign_type == '3') {
                if (signType_relationship == '') {
                    $('#signTyperRelationshipCheck').show();
                } else {
                    $('#signTyperRelationshipCheck').hide();
                }
            }

        }
    });

    ///Cheque Modal Doc Upload
    $('#add_Cheque').click(function () {
        $('#chequeUploads input').not('#holder_name, #cheque_relation').prop('readonly', false);
        $('#chequeUploads select').prop('disabled', false);
    });

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
            $("#holder_name").val("");
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

    $('#cheque_count').off().keyup(function () {
        let chequeCnt = $(this).val();
        getChequeColumn(chequeCnt, ''); // show input to insert Cheque No.
    });

    $("#chequeUploads").on('submit', function (e) {
        e.preventDefault();

        let req_id = $('#cheque_req_id').val();
        let cus_id = $('#cus_id').val();
        let holder_type = $("#holder_type").val();
        var holder_name = $("#holder_name").val();
        var holder_relationship_name = $("#holder_relationship_name").val();
        let cheque_relation = $("#cheque_relation").val();
        let chequebank_name = $("#chequebank_name").val();
        let cheque_count = $("#cheque_count").val();
        let cus_profile_id = $("#cus_profile_id").val();
        // let cheque_upd = $("#cheque_upd")[0];
        let formdata = new FormData();
        let files = $("#cheque_upd")[0].files;
        for (var i = 0; i < files.length; i++) {
            formdata.append('cheque_upd[]', files[i])
        }
        // var chequeno = $("#cheque_upd_no").val();
        var chequeArr = [];
        var i = 0;
        let checkChequeNo = true;
        $('.chequeno').each(function () {
            chequeArr[i] = $(this).val();
            checkChequeNo = ($(this).val() == '' || checkChequeNo == false) ? false : true;
            i++;
        })

        let chequeID = $("#chequeID").val();

        formdata.append('cus_id', cus_id)
        formdata.append('cheque_req_id', req_id)
        formdata.append('holder_type', holder_type)
        formdata.append('holder_name', holder_name)
        formdata.append('holder_relationship_name', holder_relationship_name)
        formdata.append('cheque_relation', cheque_relation)
        formdata.append('chequebank_name', chequebank_name)
        formdata.append('cheque_count', cheque_count)
        formdata.append('cheque_upd_no', chequeArr)
        formdata.append('chequeID', chequeID)
        formdata.append('cus_profile_id', cus_profile_id)

        if (holder_type != "" && chequebank_name != "" && cheque_count != "" && req_id != "" && checkChequeNo == true && ((holder_type == "2") ? holder_relationship_name != "" : true)) {
            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/cheque_upload.php',
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
                    }
                    else {
                        $('#chequeNotOk').show();
                        setTimeout(function () {
                            $('#chequeNotOk').fadeOut('fast');
                        }, 2000);
                    }

                    resetchequeInfo();
                }
            });

        } else {

            if (holder_type == "") {
                $('#holdertypeCheck').show();
            } else {
                $('#holdertypeCheck').hide();
            }

            if (chequebank_name == "") {
                $('#chequebankCheck').show();
            } else {
                $('#chequebankCheck').hide();
            }

            if (cheque_count == "") {
                $('#chequeCountCheck').show();
            } else {
                $('#chequeCountCheck').hide();
            }

            if (checkChequeNo == false) {
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
            // if (checkupd == 0) {
            //     $('#chequeupdCheck').show();
            // } else {
            //     $('#chequeupdCheck').hide();
            // }

        }
    });

    $("body").on("click", "#cheque_info_edit", function () {
        let id = $(this).attr('value');

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
                    chequeHolderName(result['holder_relationship_name']); // Holder Name From Family Table.
                    // $("#holder_relationship_name").val(result['holder_relationship_name']);
                }

                $("#cheque_relation").val(result['cheque_relation']);
                $("#chequebank_name").val(result['chequebank_name']);
                $("#cheque_count").val(result['cheque_count']);

                getChequeColumn(result['cheque_count'], result['cheque_no']); // show input to insert Cheque No.
            }
        });
    });

    $("body").on("click", "#cheque_info_delete", function () {
        var isok = confirm("Do you want delete this Cheque Info?");
        if (isok == false) {
            return false;
        } else {
            var chequeid = $(this).attr("value");

            $.ajax({
                url: "verificationFile/documentation/cheque_info_delete.php",
                type: "POST",
                data: { chequeid: chequeid },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");
                    if (delresult) {
                        $("#chequeDeleteOk").show();
                        setTimeout(function () {
                            $("#chequeDeleteOk").fadeOut("fast");
                        }, 2000);
                    } else {
                        $("#chequeDeleteNotOk").show();
                        setTimeout(function () {
                            $("#chequeDeleteNotOk").fadeOut("fast");
                        }, 2000);
                    }

                    resetchequeInfo();
                },
            });
        }
    });


    ////Mortgage Info  
    $('#mortgageprocessCheck').hide(); $('#propertyholdertypeCheck').hide(); $('#docpropertytypeCheck').hide(); $('#docpropertymeasureCheck').hide(); $('#docpropertylocCheck').hide(); $('#docpropertyvalueCheck').hide();
    $('#mortgagenameCheck').hide(); $('#mortgagedsgnCheck').hide(); $('#mortgagenumCheck').hide(); $('#regofficeCheck').hide(); $('#mortgagevalueCheck').hide(); $('#mortgagedocCheck').hide(); $('#mortgagedocUpdCheck').hide();$("#propertyholderNameCheck").hide();
$('#doc_remarkcheck').hide();
    $('#mortgage_process').change(function () {
        let process = $(this).val();
        $("#propertyholderNameCheck").hide();

        if (process == '0') {
            $('#Mortgageprocess').show();
        } else {
            $('#Mortgageprocess').hide();

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
            
        }
    })

    $('#Propertyholder_type').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();
        $("#propertyholderNameCheck").hide();

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

            mortgageHolderName();

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
    // var pend = document.querySelector('#pendingchk');
    // $('#mortgage_document').change(function () {
    //     var docupd = $(this).val();

    //     if (docupd == '0') {
    //         $('#docUpd').show();
    //         $('#pendingchk').removeAttr('checked')

    //     } else {
    //         $('#mortgage_document_upd').val('');
    //         $('#docUpd').hide();
    //         pend.checked = true;
    //     }
    // })

    //when Mortgage Document pending is Checked then document will empty and Doc is NO////

    // $('#pendingchk').click(function () {

    //     if (pend.checked == true) {
    //         $('#mortgage_document_upd').val('');
    //         $('#mortgage_document').val('1');
    //         $('#docUpd').hide();
    //     } else {
    //         $('#mortgage_document').val('0');
    //         $('#docUpd').show();
    //     }
    // })

    // $('#mortgage_document_upd').change(function () {
    //     var upd = $(this).val();
    //     if (upd != "") {
    //         pend.checked = false;
    //     }
    // })

    //Endorsement Info
    $('#endorsementprocessCheck').hide(); $('#ownertypeCheck').hide(); $('#vehicletypeCheck').hide(); $('#vehicleprocessCheck').hide(); $('#enCompanyCheck').hide(); $('#enModelCheck').hide(); $('#vehicle_reg_noCheck').hide(); $('#endorsementnameCheck').hide(); $('#enRCCheck').hide(); $('#enKeyCheck').hide(); $('#rcdocUpdCheck').hide();$("#ownerNameCheck").hide();

    $('#endorsement_process').change(function () {

        let process = $(this).val();
        $("#ownerNameCheck").hide();

        if (process == '0') {
            $('#endorsementprocess').show();
        } else {
            $('#endorsementprocess').hide();

            $('#owner_type').val('');
            $('#owner_name').val('');
            $('#ownername_relationship_name').val('');
            $('#en_relation').val('');
            $('#vehicle_type').val('');
            $('#vehicle_process').val('');
            $('#en_Company').val('');
            $('#en_Model').val('');
            $('#vehicle_reg_no').val('');
            $('#endorsement_name').val('');
            $('#en_RC').val('');
            $('#en_Key').val('');
            $('#RC_document_upd').val('');
            
        }
    })

    $('#owner_type').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();
        $("#ownerNameCheck").hide();
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

            endorseHolderName();
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

    // var enpend = document.querySelector('#endorsependingchk');
    // $('#endorsependingchk').click(function () {

    //     if (enpend.checked == true) {
    //         $('#RC_document_upd').val('');
    //         $('#en_RC').val('1');
    //         $('#RCdocUpd').hide();
    //     } else {
    //         $('#en_RC').val('0');
    //         $('#RCdocUpd').show();
    //     }
    // })

    // $('#RC_document_upd').change(function () {
    //     var rcupd = $(this).val();

    //     if (rcupd != "") {
    //         enpend.checked = false;
    //     }
    // })

    //RC upload.

    $('#en_RC').change(function () {
        var rcupd = $(this).val();

        if (rcupd == '0') {
            $('#RCdocUpd').show();
            enpend.checked = false;

        } else {
            $('#RC_document_upd').val('');
            $('#RCdocUpd').hide();
            enpend.checked = true;
        }
    })

    //Gold Info
    // $('#gold_info').change(function () {

    //     let gold = $(this).val();

    //     if (gold == '0') {
    //         $('#GoldInfo').show();
    //     } else {
    //         $('#GoldInfo').hide();

    //         $('#gold_sts').val('');
    //         $('#gold_type').val('');
    //         $('#Purity').val('');
    //         $('#gold_Count').val('');
    //         $('#gold_Weight').val('');
    //         $('#gold_Value').val('');
    //     }
    // })

    $('#document_holder').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();
        $("#docHolderNameCheck").hide();
        if (type == '0') {
            $('#docholder_name').show();
            $('#docholder_relationship_name').val('');
            $('#docholder_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#docholder_name').val(result['name']);
                    $('#doc_relation').val('NIL');
                }
            });

        } else if (type == '1') {
            $('#docholder_name').show();
            $('#docholder_relationship_name').val('');
            $('#docholder_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#docholder_name').val(result['name']);
                    $('#doc_relation').val(result['relationship']);
                }
            });

        } else if (type == '2') {
            $('#docholder_name').hide();
            $('#docholder_relationship_name').show();
            $('#docholder_name').val('');
            $('#doc_relation').val('');

            docHolderName();
        } else {
            $('#docholder_name').show();
            $('#docholder_relationship_name').hide();
            $('#docholder_name').val('');
            $('#doc_relation').val('');
        }
    });

    $('#docholder_relationship_name').change(function () {
        let fam_id = $(this).val();
        $.ajax({
            url: 'verificationFile/documentation/find_cheque_relation.php',
            type: 'POST',
            data: { "fam_id": fam_id },
            dataType: 'json',
            success: function (response) {
                $('#doc_relation').val(response);

            }
        });
    });

    $('#documentnameCheck').hide(); $('#documentdetailsCheck').hide(); $('#documentTypeCheck').hide(); $('#docholderCheck').hide();$("#docHolderNameCheck").hide();

    //To Show Relationship value on edit page.////
    let mortgage = $('#Propertyholder_type').val();
    if (mortgage == '2') {
        $('#Propertyholder_name').hide();
        $('#Propertyholder_relationship_name').show();
        mortgageHolderName();
    }

    let ot = $('#owner_type').val();
    if (ot == '2') {
        $('#owner_name').hide();
        $('#ownername_relationship_name').show();
        endorseHolderName();
    }

    let docHolder = $('#document_holder').val();
    if (docHolder == '2') {
        $('#docholder_name').hide();
        $('#docholder_relationship_name').show();
        docHolderName();
        let holder = $('#docrelation_name').val();

        setTimeout(() => {
            $('#docholder_relationship_name').val(holder);
        }, 500);
    }

    //Gold Info START ///
    $("body").on("click", "#gold_info_edit", function () {
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

    $("body").on("click", "#gold_info_delete", function () {
        var isok = confirm("Do you want delete this Gold Info?");
        if (isok == false) {
            return false;
        } else {
            var chequeid = $(this).attr('value');

            $.ajax({
                url: 'verificationFile/documentation/gold_info_delete.php',
                type: 'POST',
                data: { "chequeid": chequeid },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");
                    if (delresult) {
                        $('#goldDeleteOk').show();
                        setTimeout(function () {
                            $('#goldDeleteOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {

                        $('#goldDeleteNotOk').show();
                        setTimeout(function () {
                            $('#goldDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }

                    resetgoldInfo();
                }
            });
        }
    });
    //Gold Info END ///

    ///Hide AND Show doc Card START
    $('#choose_document').change(function () {
        let doc = $(this).val();

        // Hide all sections initially
        $('.doc_card').hide();

        // Show the selected document section
        switch (doc) {
            case '1': $('#signed_doc_card').show(); break;
            case '2': $('#cheque_info_card').show(); break;
            case '3': $('#mortgage_info_card').show(); break;
            case '4': $('#endorsement_info_card').show(); break;
            case '5': $('#gold_info_card').show(); break;
            case '6': $('#documents_info_card').show(); break;
            default: $('.doc_card').hide();
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

    ////NOC replace status START//////
    $('#replace_status').change(function(event){
        let sts = $(this).is(':checked');
        
        if(sts){
            getDocumentIds();

        }else{
            replaceDOCMultiselect.clearStore();
            $('.replce_doc_id').hide();
        }
    });
    ////NOC replace status END//////
    
    ////NOC Document replace START//////
    document
    .getElementById('replace_doc_id')
    .addEventListener('addItem', function (event) {

        const { value, customProperties } = event.detail;

        if (customProperties.nocstatus == '23') {
            replaceDOCMultiselect.removeActiveItemsByValue(value);

            Swal.fire({
                title: 'Warning',
                text : 'Kindly Complete NOC Handover before replace',
                icon : 'error',
                confirmButtonColor: '#cc4444'
            });
        }
    });
    ////NOC Document replace END//////

});   ////////Document Ready End

$(function () {
    $('.icon-chevron-down1').parent().next('div').slideUp(); //To collapse all card on load
    // Disable all inputs except the ones you want to keep editable
    $('input').not('#int_rate, #due_period, #doc_charge, #proc_fee').attr('readonly', true);
    $('select').not('#choose_document, #mortgage_process, #endorsement_process, #replace_doc_id').attr('disabled', true);


    getImage(); // To show customer image when window onload.

    resetFamDetails(); //Family Table List

    //  resetGroupDetails()    //Group Family Modal Table Reset 

    resetPropertyinfoList() //Property Info List.

    resetbankinfoList(); //Bank Info List.

    resetkycinfoList(); //KYC Info List.

    //Documentation
    // getstaffCode(); // Atuo Generate Doc ID.
    // resetsignInfo(); // Signed Doc info Reset.
    // resetsigninfoList(); // Signed Doc List Reset.

    // chequeinfoList(); // Cheque Info List.
    // resetchequeInfo();

    // resetgoldInfo(); // Gold Info Reset.
    // goldinfoList(); // Gold Info List.

    // resetdocInfo(); // Document Info Reset.
    // docinfoList(); // Document Info List.

    feedbackList(); // Feedback List.

    verificationPerson() //Verification Person

    getCustomerLoanCounts(); // to get customer loan details

    fingerprintTable();//To Get family member's name are required for scanning fingerprint

    var state_upd = $('#state_upd').val();
    if (state_upd != '') {
        getDistrictDropdown(state_upd);
    }
    var district_upd = $('#district_upd').val();
    if (district_upd != '') {
        getTalukDropdown(district_upd);
    }
    var taluk_upd = $('#taluk_upd').val();
    if (taluk_upd != '') {
        getTalukBasedArea(taluk_upd);
    }
    var area_upd = $('#area_upd').val();
    if (area_upd != '') {
        getAreaBasedSubArea(area_upd);
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

    // when Mortgage Doc is YES then Pending is UNCHECKED.
    var docupd = $('#mortgage_document').val();

    if (docupd == '0') {
        // $('#pendingchk').removeAttr('checked');
        $('#docUpd').show();
    }

    //When Endorsement Doc is YES then Pending is UNCHECKED.
    var endocupd = $('#en_RC').val();

    if (endocupd == '0') {
        // $('#endorsependingchk').removeAttr('checked');
        $('#RCdocUpd').show();
    }

    let agent_id = $('#agent_id').val();
    getresponsiblecolumn(agent_id);
});

//Get document ids
function getDocumentIds(){
    let cusid = $('#cus_id_doc').val();
    $.post("verificationFile/documentation/getDoumentIDsTillHandover.php", { cusid }, function(response){

        replaceDOCMultiselect.clearChoices();

        let replace_doc_id_upd = $('#replace_doc_id_upd').val();
        replace_doc_id_upd = replace_doc_id_upd
            ? replace_doc_id_upd.split(',')
            : [];

        let items = [];

        if (Array.isArray(response) && response.length > 0) {
            response.forEach(element => {
                items.push({
                    value: element.doc_id,
                    label: element.doc_id,
                    selected: replace_doc_id_upd.includes(element.doc_id),
                    customProperties: {
                        nocstatus: element.cus_status
                    }
                });
            });

            replaceDOCMultiselect.setChoices(items, 'value', 'label', false);
        }

        $('.replce_doc_id').show();

    },'json');
}

//To get Reponsible Dropdown
function getresponsiblecolumn(ag_id) {
    $.ajax({
        url: 'requestFile/getResponsiblecolumn.php',
        data: { 'ag_id': ag_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response == '0') {
                $('.responsible').show();
            } else {
                $('.responsible').hide();
            }
        }
    });
}

function getImage() { // Cus img show onload.
    let imgName = $('#cus_image').val();
    if (imgName != '') {
        $('#imgshow').attr('src', "uploads/request/customer/" + imgName + " ");
    }

    var guarentorimg = $('#guarentor_image').val();
    if (guarentorimg != '') {
        $('#imgshows').attr('src', "uploads/verification/guarentor/" + guarentorimg + " ");
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

function fingerprintTable() {//To Get family member's name are required for scanning fingerprint
    var req_id = $('#req_id').val();
    var cus_name = $('#cus_name').val();
    var cus_id = $('#cus_id_doc').val();
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
                        console.log("~ file: acknowledgement_creation.js:934 ~ setTimeout ~ (res.data.ErrorCode:", res.data.ErrorCode);
                        if (res.httpStaus) {
                            if (res.data.ErrorCode == "0") {
                                $(this).next().val(res.data.AnsiTemplate); // Take ansi template that is the unique id which is passed by sensor

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


}

function resetFamDetails() {

    let cus_id = $('#cus_id').val();
    var guarentor_name = $('#guarentor_name_upd').val();

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

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#guarentor_name").empty();
            $("#guarentor_name").append("<option value=''>" + 'Select Guarantor' + "</option>");
            for (var i = 0; i < len - 1; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                var selected = '';
                if (guarentor_name != '' && guarentor_name == fam_id) {
                    selected = 'selected';
                }
                $("#guarentor_name").append("<option value='" + fam_id + "' " + selected + ">" + fam_name + "</option>");
            }
            // Sort guarentor_name dropdown
            sortDropdownAlphabetically("#guarentor_name");

        }
    });
}

// Verification Info Person 
function verificationPerson() {
    let cus_id = $('#cus_id').val();

    var verification_person_upd = $('#verification_person_upd').val();
    var values = verification_person_upd.split(',');


    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {
            personMultiselect.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                var selected = '';
                if (verification_person_upd != '' && values.includes(String(fam_id))) {
                    selected = 'selected';
                }
                var items = [
                    {
                        value: fam_id,
                        label: fam_name,
                        selected: selected,
                    }
                ];
                personMultiselect.setChoices(items);
                personMultiselect.init();
            }
        }
    });
}

// function resetGroupDetails() {
//     let cus_id = $('#cus_id').val();
//     $.ajax({
//         url: 'verificationFile/verification_group_list.php',
//         type: 'POST',
//         data: { "cus_id": cus_id },
//         cache: false,
//         success: function (html) {
//             $("#GroupList").empty();
//             $("#GroupList").html(html);
//         }
//     });
// }

///////////////////////// Property Info Starts /////////////////////////////////////
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
        }
    });
}

////////////////////////////// Bank Info ///////////////////////////////////////////////////////
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
function resetkycinfoList() {
    let cus_id = $('#cus_id').val();
    let req_id = $('#req_id').val();

    $.ajax({
        url: 'verificationFile/verification_kyc_list.php',
        type: 'POST',
        data: { req_id, cus_id },
        // data: { "cus_id": cus_id },
        cache: false,
        success: function (html) {
            $("#kycListTable").empty();
            $("#kycListTable").html(html);

        }
    });
}

//get district dropdown
function getDistrictDropdown(StateSelected) {

    var optionsList;
    var htmlString = "<option value='Select District'>Select District</option>";
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

//get Taluk Dropdown
function getTalukDropdown(DistSelected) {

    var optionsList;
    var htmlString = "<option value='Select Taluk'>Select Taluk</option>";
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
    var taluk_upd = $('#taluk_upd').val();
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

//Get Taluk Based Area
function getTalukBasedArea(talukselected) {
    var area_upd = $('#area_upd').val();
    $.ajax({
        url: 'requestFile/ajaxGetEnabledAreaName.php',
        type: 'post',
        data: { 'talukselected': talukselected },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#area").empty();
            $("#area").append("<option value=''>" + 'Select Area' + "</option>");
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var selected = '';
                if (area_upd != undefined && area_upd != '' && area_upd == area_id) {
                    selected = 'selected';
                }
                $("#area").append("<option value='" + area_id + "' " + selected + ">" + area_name + "</option>");
            }

            $("#area_name").val('');
            $("#area_id").val('');

            // Sort area dropdown
            sortDropdownAlphabetically("#area");
        }
    });
}

//Get Area Based Sub Area
function getAreaBasedSubArea(area) {
    var sub_area_upd = $('#sub_area_upd').val();
    $.ajax({
        url: 'requestFile/ajaxGetEnabledSubArea.php',
        type: 'post',
        data: { 'area': area },
        dataType: 'json',
        success: function (response) {

            $('#sub_area').empty();
            $('#sub_area').append("<option value='' >Select Sub Area</option>");
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (sub_area_upd != undefined && sub_area_upd != '' && sub_area_upd == response[i]['sub_area_id']) {
                    selected = 'selected';
                }
                $('#sub_area').append("<option value='" + response[i]['sub_area_id'] + "' " + selected + ">" + response[i]['sub_area_name'] + " </option>");
            }
        }
    });
}

//Customer Feedback Modal 
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
            $("#feedbackID").val('');
        }
    });
}
//Customer Feedback Modal End

//////////////////////////////////////////////////// Documentation  Start////////////////////////////////////////
function onLoadDocEditFunction() {//On load for Loan Calculation edit

    // $('select#mortgage_process').removeAttr('disabled');
    $('select#Propertyholder_type').removeAttr('disabled');
    // $('input#Propertyholder_name').removeAttr('readonly');
    $('select#Propertyholder_relationship_name').removeAttr('disabled');
    $('input#doc_property_pype').removeAttr('readonly');
    $('input#doc_property_measurement').removeAttr('readonly');
    $('input#doc_property_location').removeAttr('readonly');
    $('input#doc_property_value').removeAttr('readonly');

    $('input#mortgage_name').removeAttr('readonly');
    $('input#mortgage_dsgn').removeAttr('readonly');
    $('input#mortgage_nuumber').removeAttr('readonly');
    $('input#reg_office').removeAttr('readonly');
    $('input#mortgage_value').removeAttr('readonly');
    $('#mortgage_document').removeAttr('disabled');

    $('input#vehicle_reg_no').removeAttr('readonly');
    $('input#endorsement_name').removeAttr('readonly');
    $('#en_RC').removeAttr('disabled');
    $('#en_Key').removeAttr('disabled');

    // $('select#endorsement_process').removeAttr('disabled');
    $('select#owner_type').removeAttr('disabled');
    // $('input#owner_name').removeAttr('readonly');
    $('select#ownername_relationship_name').removeAttr('disabled');
    $('select#vehicle_type').removeAttr('disabled');
    $('select#vehicle_process').removeAttr('disabled');
    $('input#en_Company').removeAttr('readonly');
    $('input#en_Model').removeAttr('readonly');

    $('select#gold_info').removeAttr('disabled');
    $('select#gold_sts').removeAttr('disabled');
    $('input#gold_type').removeAttr('readonly');
    $('input#Purity').removeAttr('readonly');
    $('input#gold_Count').removeAttr('readonly');
    $('input#gold_Weight').removeAttr('readonly');
    $('input#gold_Value').removeAttr('readonly');

}
//Get DOC id 
//Doc id will generate while Loan id generate because both id have to same for a customer.
// function getDocID() {
//     let doc_Id = $('#doc_table_id').val();
//     $.ajax({
//         url: 'verificationFile/documentation/doc_id_autoGen.php',
//         type: "post",
//         dataType: "json",
//         data: { "id": doc_Id },
//         cache: false,
//         success: function (response) {
//             var docId = response;
//             $('#doc_id').val(docId);
//         }
//     })
// }

function endorseHolderName() {

    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            let Endorsename = $("#en_relation_name").val();

            $("#ownername_relationship_name").empty();
            $("#ownername_relationship_name").append("<option value=''>Select Holder Name</option>");

            for (var i = 0; i < len - 1; i++) {
                // -1 because this ajax's response will contain customer value at the last of the response for verification person
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                let selected = '';

                if (fam_id == Endorsename) {
                    selected = 'selected';
                }

                $("#ownername_relationship_name").append(`<option value='${fam_id}' ${selected}>${fam_name}</option>`);
            }

            // Sort ownername_relationship_name dropdown
            sortDropdownAlphabetically("#ownername_relationship_name");

        }
    });
}

function mortgageHolderName() {
    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            let mortgageHolder = $("#mortgage_relation_name").val();

            $("#Propertyholder_relationship_name").empty();
            $("#Propertyholder_relationship_name").append("<option value=''>Select Holder Name</option>");

            for (var i = 0; i < len - 1; i++) {
                // -1 because this ajax's response will contain customer value at the last of the response for verification person

                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                let selected = '';
                if (fam_id == mortgageHolder) {
                    selected = 'selected';
                }

                $("#Propertyholder_relationship_name").append(`<option value='${fam_id}' ${selected}>${fam_name}</option>`);
            }

            // Sort Propertyholder_relationship_name dropdown
            sortDropdownAlphabetically("#Propertyholder_relationship_name");

        }
    });
}

function docHolderName(callback) {
    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#docholder_relationship_name").empty();
            $("#docholder_relationship_name").append("<option value=''>" + 'Select Holder Name' + "</option>");
            for (var i = 0; i < len - 1; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                $("#docholder_relationship_name").append("<option value='" + fam_id + "'>" + fam_name + "</option>");
            }
            // Sort docholder_relationship_name dropdown
            sortDropdownAlphabetically("#docholder_relationship_name");

            if (typeof callback === "function") {
                callback();
            }
        }
    });
}

function resetsignInfo() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/sign_info_upd_reset.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#signTable").empty();
            $("#signTable").html(html);

            $("#sign_type, #signType_cus_name, #guar_name, #signType_relationship, #doc_Count, #signdoc_upd, #signedID").val('');
            $('#cus_name_div, #guar_name_div, #docNameCheck, #signTypeCheck, #docCountCheck, #docupdCheck, #signTyperRelationshipCheck').hide();
        }
    });
}

function signTypeRelation() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#signType_relationship").empty();
            $("#signType_relationship").append("<option value=''>" + 'Select Relationship' + "</option>");
            for (var i = 0; i < len - 1; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                var relationship = response[i]['relationship'];
                $("#signType_relationship").append("<option value='" + fam_id + "'>" + fam_name + ' - ' + relationship + "</option>");
            }
            // Sort signType_relationship dropdown
            sortDropdownAlphabetically("#signType_relationship");

        }
    });
}

function getGuarentorName() {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/getGuarentorName.php',
        type: 'post',
        data: { "req_id": req_id, "cus_id": cus_id },
        cache: false,
        success: function (response) {
            $('#guar_name_div').show();
            $('#guar_name').val(response);
        }
    })
}

function resetsigninfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/acknowledge_sign_doc_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#signDocResetTable").empty();
            $("#signDocResetTable").html(html);

            $("#sign_type").val('');
            $("#signType_cus_name").val('');
            $("#cus_name_div").hide();
            $("#guar_name").val('');
            $("#guar_name_div").hide();
            $("#signType_relationship").val('');
            $("#doc_Count").val('');
            $("#signdoc_upd").val('');
            $("#signedID").val('');

            let hasRecords = ($('#signed_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#signed_doc_card').show();

            } else {
                $('#signed_doc_card').hide();

            }

            storeDocInfo.signDocInfo = hasRecords;
        }
    });
}

// function filesCount() {
//     var cnt = $('#doc_Count').val();
//     var signFile = document.querySelector('#signdoc_upd');

//     if (signFile.files.length <= cnt) {
//         return true;
//     } else {
//         alert('Please select Less than ' + cnt + ' files.')
//         $("#signdoc_upd").val('');
//         return false;
//     }
// }

//Cheque Info List
function chequeinfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/cheque_info_upd_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#ChequeResetTable").empty();
            $("#ChequeResetTable").html(html);

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
    });
}

function resetchequeInfo() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/cheque_info_upd_reset.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $('#chequeColumnDiv').empty();
            $("#chequeTable").empty();
            $("#chequeTable").html(html);

            $("#holder_type, #holder_name, #holder_relationship_name, #cheque_relation, #chequebank_name, #cheque_count, #cheque_upd, #chequeID").val("");
            $('#chequebankCheck, #holdertypeCheck, #chequeCountCheck, #chequeupdCheck, #holderNameCheck').hide();

        }
    });
}

function chequeHolderName(editValue) {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#holder_relationship_name").empty();
            $("#holder_relationship_name").append("<option value=''>Select Holder Name</option>");
            for (var i = 0; i < len - 1; i++) {
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                let selected = (editValue == fam_id) ? 'selected' : '';

                $("#holder_relationship_name").append(`<option value='${fam_id}' ${selected}>${fam_name}</option>`);
            }
            // Sort holder_relationship_name dropdown
            sortDropdownAlphabetically("#holder_relationship_name");

        }
    });
}

// function chequefilesCount() {
//     var cnt = $('#cheque_count').val();
//     var chequeFile = document.querySelector('#cheque_upd');

//     if (chequeFile.files.length <= cnt) {
//         return true;
//     } else {
//         alert('Please select Less than ' + cnt + ' files.')
//         $("#cheque_upd").val('');
//         return false;
//     }
// }

//Cheque No 
function getChequeColumn(cnt, nos) {

    $.ajax({
        type: 'post',
        data: { "count": cnt, "cheque_nos": JSON.stringify(nos) },
        url: 'verificationFile/documentation/cheque_info_upd_column.php',
        success: function (result) {
            $('#chequeColumnDiv').empty();
            $('#chequeColumnDiv').html(result);

        }
    })

}

/////////////////////////////////////// Gold Info Modal START/////////////////////////////////
//Gold Info 
$(document).on("click", "#goldInfoBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
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

    if (gold_sts != "" && gold_type != "" && Purity != "" && gold_Count != "" && gold_Weight != "" && gold_Value != "" && req_id != "") {
        $.ajax({
            url: 'verificationFile/documentation/gold_info_submit.php',
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
                }
                else if (updresult) {
                    $('#goldUpdateok').show();
                    setTimeout(function () {
                        $('#goldUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#goldNotOk').show();
                    setTimeout(function () {
                        $('#goldNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetgoldInfo();
            }
        });

    }
    else {

        if (gold_sts == '') {
            $('#GoldstatusCheck').show();
        } else {
            $('#GoldstatusCheck').hide();
        }
        if (gold_type == '') {
            $('#GoldtypeCheck').show();
        } else {
            $('#GoldtypeCheck').hide();
        }
        if (Purity == '') {
            $('#purityCheck').show();
        } else {
            $('#purityCheck').hide();
        }
        if (gold_Count == '') {
            $('#goldCountCheck').show();
        } else {
            $('#goldCountCheck').hide();
        }
        if (gold_Weight == '') {
            $('#goldWeightCheck').show();
        } else {
            $('#goldWeightCheck').hide();
        }
        if (gold_Value == '') {
            $('#goldValueCheck').show();
        } else {
            $('#goldValueCheck').hide();
        }
        if (gold_upload == '') {
            $('#gold_uploadCheck').show();
        } else {
            $('#gold_uploadCheck').hide();
        }
    }

});

function resetgoldInfo() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/gold_info_reset.php',
        type: 'POST',
        data: { "reqId": req_id, 'pages': 2 },
        cache: false,
        success: function (html) {
            $("#goldTable").empty();
            $("#goldTable").html(html);

            $("#gold_sts, #gold_type, #Purity, #gold_Count, #gold_Weight, #gold_Value, #gold_upload, #goldID").val('');
            $('#GoldstatusCheck, #GoldtypeCheck, #purityCheck, #goldCountCheck, #goldWeightCheck, #goldValueCheck, #gold_uploadCheck').hide();

        }
    });
}

//Gold Info List
function goldinfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/ack_gold_info_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#GoldResetTableDiv").empty();
            $("#GoldResetTableDiv").html(html);

            $("#gold_sts").val('');
            $("#gold_type").val('');
            $("#Purity").val('');
            $("#gold_Count").val('');
            $("#gold_Weight").val('');
            $("#gold_Value").val('');
            $("#gold_upload").val('');
            $("#goldID").val('');

            let hasRecords = ($('#gold_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#gold_info_card').show();

            } else {
                $('#gold_info_card').hide();

            }

            storeDocInfo.goldInfo = hasRecords;
        }
    });
}

/////////////////////////////////////// Gold Info Modal END/////////////////////////////////

// ///////////////////////////  Document Info Modal //////////////////////////////

$('#add_document').click(function () {
    $('#docUploads input').not('#docholder_name, #doc_relation').prop('readonly', false);
    $('#docUploads select').prop('disabled', false);
});

//Document info submit button action
$('#docInfoBtn').click(function () {
    let req_id = $("#req_id").val();
    let cus_id = $("#cus_id").val();
    let doc_id = $("#doc_info_id").val();
    let doc_name = $("#document_name").val();
    let doc_details = $("#document_details").val();
    let doc_type = $("#document_type").val();
    let doc_holder = $("#document_holder").val();
    let holder_name = $("#docholder_name").val();
    let relation_name = $("#docholder_relationship_name").val();
    let relation = $("#doc_relation").val();

    var formdata = new FormData();
    formdata.append('req_id', req_id)
    formdata.append('doc_id', doc_id)
    formdata.append('doc_name', doc_name)
    formdata.append('doc_details', doc_details)
    formdata.append('doc_type', doc_type)
    formdata.append('doc_holder', doc_holder)
    formdata.append('holder_name', holder_name)
    formdata.append('relation_name', relation_name)
    formdata.append('relation', relation)
    formdata.append('cus_id', cus_id)

    var files = $("#document_info_upd")[0].files;
    for (var i = 0; i < files.length; i++) {
        formdata.append('document_info_upd[]', files[i])
    }

    if (doc_name != '' && doc_details != '' && doc_type != '' && doc_holder != '' && relation != '' && ((doc_holder === "2") ? relation_name !== "" : true)) {

        $.ajax({
            url: 'verificationFile/documentation/doc_info_submit.php',
            data: formdata,
            contentType: false,
            processData: false,
            type: 'POST',
            // cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#docInsertOk').show();
                    setTimeout(function () {
                        $('#docInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#docUpdateok').show();
                    setTimeout(function () {
                        $('#docUpdateok').fadeOut('fast');
                    }, 2000);
                }
                else {
                    $('#docNotOk').show();
                    setTimeout(function () {
                        $('#docNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetdocInfo();
            }
        })
    
    } else {
      if (doc_name == "") {
      $("#documentnameCheck").show();
    } else {
      $("#documentnameCheck").hide();
    }

    if (doc_details == "") {
      $("#documentdetailsCheck").show();
    } else {
      $("#documentdetailsCheck").hide();
    }

    if (doc_type == "") {
      $("#documentTypeCheck").show();
    } else {
      $("#documentTypeCheck").hide();
    }

    if (doc_holder == "") {
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
  
    }
})

$("body").on("click", "#doc_info_edit", function () {
    let id = $(this).attr('value');
    $.ajax({
        url: 'verificationFile/documentation/doc_info_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        // beforeSend: function () {
        //     docHolderName();
        // },
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
                docHolderName(function () {
                    $("#docholder_relationship_name").val(response['relation_name'])
                });
            }
            $("#doc_relation").val(response['relation']);

        }
    });

});


$("body").on("click", "#doc_info_delete", function () {
    var isok = confirm("Do you want delete this Document Info?");
    if (isok == false) {
        return false;
    } else {
        var id = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/documentation/doc_info_delete.php',
            type: 'POST',
            data: { "id": id },
            cache: false,
            success: function (response) {
                var delresult = response.includes("Deleted");
                if (delresult) {
                    $('#docDeleteOk').show();
                    setTimeout(function () {
                        $('#docDeleteOk').fadeOut('fast');
                    }, 2000);
                }
                else {

                    $('#docDeleteNotOk').show();
                    setTimeout(function () {
                        $('#docDeleteNotOk').fadeOut('fast');
                    }, 2000);
                }

                resetdocInfo();
            }
        });
    }
});

//Document Info List Modal Table
function resetdocInfo() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/doc_info_reset.php',
        type: 'POST',
        data: { "req_id": req_id, 'pages': 2 },
        cache: false,
        success: function (html) {
            $("#docModalDiv").empty();
            $("#docModalDiv").html(html);

            $("#document_name, #document_details, #docholder_relationship_name, #document_type, #document_holder, #doc_info_id, #docholder_name, #relation_name, #doc_relation, #document_info_upd").val('');
            $("#documentnameCheck, #documentdetailsCheck, #documentTypeCheck, #docholderCheck, #docHolderNameCheck").hide();

        }
    });
}

//Document Info List
function docinfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/doc_info_upd_list.php',
        type: 'POST',
        data: { "req_id": req_id },
        cache: false,
        success: function (html) {
            $("#DocResetTableDiv").empty();
            $("#DocResetTableDiv").html(html);

            $("#document_name").val('');
            $("#document_details").val('');
            $("#document_type").val('');
            $("#document_holder").val('');
            $("#doc_info_id").val('');
            $("#docholder_name").val('');
            $("#docholder_relationship_name").val('');
            $("#doc_relation").val('');
            $("#document_info_upd").val('');

            let hasRecords = ($('#document_table').DataTable().rows().count() > 0);
            if (hasRecords) {
                $('#documents_info_card').show();

            } else {
                $('#documents_info_card').hide();

            }

            storeDocInfo.docInfo = hasRecords;
        }
    });
}
// ///////////////////////////  Document Info Modal END //////////////////////////////


//Documentation Submit Validation
$('#submit_documentation').click(function () {
    if (doc_submit_validation()) {
        let confirmAction = confirm("Are you sure you want to submit Documentation ?");
        if (!confirmAction) {
            event.preventDefault(); // Stop form submission if canceled
            return false;
        }
    } else {
        event.preventDefault();
        return false;
    }
});

function doc_submit_validation() {

    var cus_id_doc = $('#cus_id_doc').val(); var mortgage_process = $('#mortgage_process').val(); var Propertyholder_type = $('#Propertyholder_type').val(); var doc_property_pype = $('#doc_property_pype').val(); var doc_property_measurement = $('#doc_property_measurement').val(); var doc_property_location = $('#doc_property_location').val(); var doc_property_value = $('#doc_property_value').val(); var endorsement_process = $('#endorsement_process').val(); var owner_type = $('#owner_type').val(); var vehicle_type = $('#vehicle_type').val(); var vehicle_process = $('#vehicle_process').val();
    var en_Company = $('#en_Company').val(); var en_Model = $('#en_Model').val();

    var Propertyholder_relationship_name = $("#Propertyholder_relationship_name").val();
    var mortgage_document_upd = $('#mortgage_document_upd').val(); var mortgage_old_doc_upd = $('#mortgage_doc_upd').val();
    var RC_document_upd = $('#RC_document_upd').val(); var RC_old_document_upd = $('#rc_doc_upd').val();

    var mortgage_name = $('#mortgage_name').val(); var mortgage_dsgn = $('#mortgage_dsgn').val(); var mortgage_nuumber = $('#mortgage_nuumber').val(); var reg_office = $('#reg_office').val(); var mortgage_value = $('#mortgage_value').val(); var mortgage_document = $('#mortgage_document').val();

    var endorsement_name = $('#endorsement_name').val(); var en_RC = $('#en_RC').val(); var en_Key = $('#en_Key').val();
    var ownername_relationship_name = $("#ownername_relationship_name").val();
    var doc_remark = $('#doc_remark').val().trim();
    let replaceStatusChecked = $('#replace_status').is(':checked'); 
    
    // var fingerprint = $('#fingerprint').val(); var submitted = $('#submitted').val();
    
    var validation = true;
    
    if (cus_id_doc == '') {
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Complete Customer Profile!',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonColor: '#009688'
        });
        event.preventDefault();
        validation = false;
    }

    if (mortgage_process == '') {
        event.preventDefault();
        $('#mortgageprocessCheck').show();
        validation = false;
    } else {
        $('#mortgageprocessCheck').hide();
    }
    if (doc_remark == '') {
        event.preventDefault();
        $('#doc_remarkcheck').show();
        validation = false;
    } else {
        $('#doc_remarkcheck').hide();
    }

    if (mortgage_process == '0') {
        if (Propertyholder_type == '') {
            event.preventDefault();
            $('#propertyholdertypeCheck').show();
            validation = false;
        } else {
            $('#propertyholdertypeCheck').hide();
        }
        if (doc_property_pype == '') {
            event.preventDefault();
            $('#docpropertytypeCheck').show();
            validation = false;
        } else {
            $('#docpropertytypeCheck').hide();
        }
        if (doc_property_measurement == '') {
            event.preventDefault();
            $('#docpropertymeasureCheck').show();
            validation = false;
        } else {
            $('#docpropertymeasureCheck').hide();
        }
        if (doc_property_location == '') {
            event.preventDefault();
            $('#docpropertylocCheck').show();
            validation = false;
        } else {
            $('#docpropertylocCheck').hide();
        }
        if (doc_property_value == '') {
            event.preventDefault();
            $('#docpropertyvalueCheck').show();
            validation = false;
        } else {
            $('#docpropertyvalueCheck').hide();
        }
        if (mortgage_name == '') {
            event.preventDefault();
            $('#mortgagenameCheck').show();
            validation = false;
        } else {
            $('#mortgagenameCheck').hide();
        }
        if (mortgage_dsgn == '') {
            event.preventDefault();
            $('#mortgagedsgnCheck').show();
            validation = false;
        } else {
            $('#mortgagedsgnCheck').hide();
        }
        if (mortgage_nuumber == '') {
            event.preventDefault();
            $('#mortgagenumCheck').show();
            validation = false;
        } else {
            $('#mortgagenumCheck').hide();
        }
        if (reg_office == '') {
            event.preventDefault();
            $('#regofficeCheck').show();
            validation = false;
        } else {
            $('#regofficeCheck').hide();
        }
        if (mortgage_value == '') {
            event.preventDefault();
            $('#mortgagevalueCheck').show();
            validation = false;
        } else {
            $('#mortgagevalueCheck').hide();
        }
           if (Propertyholder_type == '2') {
      if (Propertyholder_relationship_name == "") {
        event.preventDefault();
        validation = false;
        $("#propertyholderNameCheck").show();
      } else {
        $("#propertyholderNameCheck").hide();
      }
    }
        if (mortgage_document == '') {// check if document dropdown selected or not
            event.preventDefault();
            $('#mortgagedocCheck').show();
            validation = false;
        } else {//if selected then check if document dropdown is YES(0) then check for document uploaded now or already
            // if (mortgage_document == '0' && (mortgage_old_doc_upd == '' && mortgage_document_upd == '')) { //if yes, then check both document upload are not empty
            //     event.preventDefault();
            //     $('#mortgagedocUpdCheck').show();
            // } else {
            //     $('#mortgagedocUpdCheck').hide();
            // }
            $('#mortgagedocCheck').hide();
        }
    }


    if (endorsement_process == '') {
        event.preventDefault();
        $('#endorsementprocessCheck').show();
        validation = false;
    } else {
        $('#endorsementprocessCheck').hide();
    }

    if (endorsement_process == '0') {
        if (owner_type == '') {
            event.preventDefault();
            $('#ownertypeCheck').show();
            validation = false;
        } else {
            $('#ownertypeCheck').hide();
        }
        if (vehicle_type == '') {
            event.preventDefault();
            $('#vehicletypeCheck').show();
            validation = false;
        } else {
            $('#vehicletypeCheck').hide();
        }
        if (vehicle_process == '') {
            event.preventDefault();
            $('#vehicleprocessCheck').show();
            validation = false;
        } else {
            $('#vehicleprocessCheck').hide();
        }
        if (en_Company == '') {
            event.preventDefault();
            $('#enCompanyCheck').show();
            validation = false;
        } else {
            $('#enCompanyCheck').hide();
        }
        if (en_Model == '') {
            event.preventDefault();
            $('#enModelCheck').show();
            validation = false;
        } else {
            $('#enModelCheck').hide();
        }
        if (endorsement_name == '') {
            event.preventDefault();
            $('#endorsementnameCheck').show();
            validation = false;
        } else {
            $('#endorsementnameCheck').hide();
        }
        if (en_Key == '') {
            event.preventDefault();
            $('#enKeyCheck').show();
            validation = false;
        } else {
            $('#enKeyCheck').hide();
        }
        if (en_RC == '') { // check if document dropdown selected or not
            event.preventDefault();
            $('#enRCCheck').show();
            validation = false;
        } else { //if selected then check if document dropdown is YES(0) then check for document uploaded now or already
            // if (en_RC == '0' && (RC_old_document_upd == '' && RC_document_upd == '')) { //if yes, then check both document upload are not empty
            //     event.preventDefault();
            //     $('#rcdocUpdCheck').show();
            // } else {
            //     $('#rcdocUpdCheck').hide();
            // }
            $('#enRCCheck').hide();
        }
         if (owner_type == '2') {
      if (ownername_relationship_name == "") {
        event.preventDefault();
        validation = false;
        $("#ownerNameCheck").show();
      } else {
        $("#ownerNameCheck").hide();
      }
    }
    }

    //signed doc
    if (!storeDocInfo.signDocInfo && !replaceStatusChecked) {
        event.preventDefault();
        $('#signed_infoCheck, #signed_doc_card').show();
        validation = false;

    } else {
        $('#signed_infoCheck').hide();

    }

    if(replaceStatusChecked){
        // Get values from multiselect and sort
        const docReplaceValues = replaceDOCMultiselect.getValue();
        if (docReplaceValues.length == 0) {  
            $('.rplce_doc_id').show(); 
            validation = false;
        } else { 
            $('.rplce_doc_id').hide(); 
        }
    }


    // if (submitted == undefined || submitted == '' || submitted == null) {
    //     if (fingerprint == '') {
    //         event.preventDefault();
    //         $('.fingerSpan').show();
    //     } else {
    //         $('.fingerSpan').hide();
    //     }
    // }
    return validation;

}

async function getDocumentFunc() {
    //Doc id will generate while Loan id generate because both id have to same for a customer.
    // getDocID(); // Atuo Generate Doc ID.

    await resetsigninfoList(); // Signed Doc List Reset.

    await chequeinfoList(); // Cheque Info List.

    await goldinfoList(); // Gold Info List.

    await docinfoList(); // Document Info List.

    let mort = ($('#mortgage_process').val() == '0') ? true : false;
    if (mort) {
        $('#mortgage_info_card').show();

    } else {
        $('#mortgage_info_card').hide();

    }
    storeDocInfo.mortgageInfo = mort;

    let endorse = ($('#endorsement_process').val() == '0') ? true : false;
    if (endorse) {
        $('#endorsement_info_card').show();

    } else {
        $('#endorsement_info_card').hide();

    }
    storeDocInfo.endorseInfo = endorse;

    let sts = $('#replace_status').is(':checked');   
    if(sts){
        getDocumentIds();
    }
}
//////////////////////////////////////////////////// Documentation  END////////////////////////////////////////


//////////////////////////////////////////////////////////////////// Loan Calculation Functions Start ///////////////////////////////////////////////////////////////////////////////
function onLoadEditFunction() {//On load for Loan Calculation edit
    $('input#due_start_from').removeAttr('readonly');
    $('select#collection_method').removeAttr('disabled');
}

$('#Communitcation_to_cus').change(function () {
    let com = $(this).val();

    if (com == '0') {
        $('#verifyaudio').show();
    } else {
        $('#verifyaudio').hide();
    }
})

$('#loan_category').change(function () {
    var loan_cat = $(this).val();
    getSubCategory(loan_cat);
})

$('#refresh_cal').click(function () {
    performLoanCalculation();
});

$('#day_scheme').change(function () {
    $('#due_start_from').val('');
    $('#maturity_month').val('');
})

$('#due_start_from').change(function () {
    var due_start_from = $('#due_start_from').val(); // get start date to calculate maturity date
    var due_period = parseInt($('#due_period').val()); //get due period to calculate maturity date
    var profit_type = $('#profit_type').val()
    if (profit_type == '1') { //Based on the profit method choose due method from input box
        var due_method = $('#due_method_calc').val()
    } else if (profit_type == '2') {
        var due_method = $('#due_method_scheme').val()
    }

    if (due_method == 'Monthly' || due_method == '1') { // if due method is monthly or 1(for scheme) then calculate maturity by month

        var maturityDate = moment(due_start_from, 'YYYY-MM-DD').add(due_period, 'months').subtract(1, 'month').format('YYYY-MM-DD');
        $('#maturity_month').val(maturityDate);

    } else if (due_method == '2') {//if Due method is weekly then calculate maturity by week

        var due_day = parseInt($('#day_scheme').val());

        var momentStartDate = moment(due_start_from, 'YYYY-MM-DD').startOf('day').isoWeekday(due_day);//Create a moment.js object from the start date and set the day of the week to the due day value

        var weeksToAdd = Math.floor(due_period - 1);//Set the weeks to be added by giving due period. subract 1 because by default it taking extra 1 week

        momentStartDate.add(weeksToAdd, 'weeks'); //Add the calculated number of weeks to the start date.

        if (momentStartDate.isBefore(due_start_from)) {
            momentStartDate.add(1, 'week'); //If the resulting maturity date is before the start date, add another week.
        }

        var maturityDate = momentStartDate.format('YYYY-MM-DD'); //Get the final maturity date as a formatted string.

        $('#maturity_month').val(maturityDate);

    } else if (due_method == '3') {
        var momentStartDate = moment(due_start_from, 'YYYY-MM-DD').startOf('day');
        var daysToAdd = Math.floor(due_period - 1);
        momentStartDate.add(daysToAdd, 'days');
        var maturityDate = momentStartDate.format('YYYY-MM-DD');
        $('#maturity_month').val(maturityDate);
    }

})

$('#submit_loan_calculation').click(function (e) {
    e.preventDefault(); // stop default form submit

    const submit_btn = $(this);
    submit_btn.prop('disabled', true);

    $('#due_start_from').trigger('change'); //For calculate once again if user missed to refresh due dates.

    // Call your calculation directly
    performLoanCalculation(function () {

        // Now validate
        const isValid = loan_calc_validation(submit_btn);

        if (isValid) {
            let confirmAction = confirm("Are you sure you want to submit Loan Calculation?");
            if (confirmAction) {
                submitLoanCalculation(submit_btn); // Proceed
            } else {
                submit_btn.prop('disabled', false); // Re-enable button if cancelled
            }
        } else {
            submit_btn.prop('disabled', false);
        }
    });
});


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

//Fetch Loan category list Based on Agent
function getUserBasedLoanCategory() {
    var loan_category = $('#loan_category_load').val();
    var loan_category_upd = $('#loan_category_upd').val();
    return $.ajax({
        url: 'verificationFile/LoanCalculation/getUserBasedLoanCategory.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#loan_category').empty();
            $('#loan_category').append("<option value='' >Select Loan Category</option>");
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (loan_category_upd == '' || loan_category_upd == undefined) { //if update is not available, then only use on load value of loan category
                    if (loan_category != undefined && loan_category != '' && loan_category == response[i]['loan_category_id']) {
                        selected = 'selected';
                        $('#loan_category_ack').val(response[i]['loan_category_id']);
                        getSubCategory(response[i]['loan_category_id']);
                    }
                } else {
                    if (loan_category_upd != undefined && loan_category_upd != '' && loan_category_upd == response[i]['loan_category_id']) {
                        selected = 'selected';
                        $('#loan_category_ack').val(response[i]['loan_category_id']);
                        getSubCategory(response[i]['loan_category_id']);
                    }
                }

                $('#loan_category').append("<option value='" + response[i]['loan_category_id'] + "' " + selected + " >" + response[i]['loan_category_name'] + " </option>");
            }
            ;
        }
    })
}

//Fetch Sub Category Based on loan category
function getSubCategory(loan_cat) {
    var sub_category = $('#sub_category_load').val();
    var sub_categoryu_upd = $('#sub_category_upd').val();
    return $.ajax({
        url: 'requestFile/getSingleSubCategory.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: { 'loan_cat': loan_cat },
        success: function (response) {

            $('#sub_category').empty();
            $('#sub_category').append("<option value='' >Select Sub Category</option>");
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (sub_categoryu_upd == '' || sub_categoryu_upd == undefined) { //if update is not available, then only use on load value of loan category
                    if (sub_category != undefined && sub_category != '' && sub_category == response[i]['sub_category_name']) {
                        selected = 'selected';
                        $('#sub_category_ack').val(response[i]['sub_category_name']);
                    }
                } else {
                    if (sub_categoryu_upd != undefined && sub_categoryu_upd != '' && sub_categoryu_upd == response[i]['sub_category_name']) {
                        selected = 'selected';
                        $('#sub_category_ack').val(response[i]['sub_category_name']);
                    }
                }
                $('#sub_category').append("<option value='" + response[i]['sub_category_name'] + "' " + selected + ">" + response[i]['sub_category_name'] + " </option>");
            }
        }
    })
}

//Get Category info From Request
function getCategoryInfo() {
    var sub_category_upd = $('#sub_category_upd').val();
    var sub_cat = $('#sub_category').val();
    var loan_category = $('#loan_category_load').val();
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat, 'loan_category': loan_category },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            category_info = ''
            $('#moduleTable').empty();
            $('#moduleTable').append('<tbody><tr>');
            if (response.length != 0) {
                var tb = 18;
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody tr').append(`<td><label for="disabledInput">` + response[i]['loan_category_ref_name'] + `</label><span class="required">&nbsp;*</span><input type="text" class="form-control" id="category_info" name="category_info[]" 
                    value='`+ category_info + `' tabindex='` + tb + `' placeholder='Enter ` + response[i]['loan_category_ref_name'] + `'readonly ></td>`);
                    // tb++;
                }
                $('#moduleTable tbody tr').append(`<td><button type="button" tabindex='` + tb + `' id="add_category_info[]" name="add_category_info" 
                class="btn btn-primary add_category_info" disabled>Add</button> </td><td><span class='icon-trash-2 ' id='' tabindex='`+ tb + `'></span></td>
                </tr></tbody></table>`);
                // $('#moduleTable tbody tr').append(`</tr></tbody></table>`);

                var category_content = $('#moduleTable tbody tr').html(); //To get the appended category list

                var category_count = $('#moduleTable tbody tr').find('td').length - 2;//To find input fields count
                getCategoryInputs(category_count, category_content, sub_category_upd);

                $(document).on('click', '.add_category_info', function () {
                    $('#moduleTable tbody').append('<tr>' + category_content + '</tr>');
                    // $('#moduleTable tbody tr:last input').filter(':last').val('');
                });

                // remove delete option for last child
                $('#deleterow:last').filter(':last').removeClass('deleterow');

                $(document).on('click', '.deleterow', function () {
                    $(this).parent().parent().remove();
                });

            }
        }
    });


    function getCategoryInputs(category_count, category_content, sub_category_upd) {

        var req_id = $('#req_id').val();
        $.ajax({
            url: 'verificationFile/LoanCalculation/getCategoryInfoForAck.php',
            data: { 'req_id': req_id, 'sub_category_upd': sub_category_upd },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                var trCount = Math.ceil(response.length / category_count); // number of rows needed

                for (var j = 0; j < trCount - 1; j++) {
                    $('#moduleTable tbody').append('<tr>' + category_content + '</tr>');
                    // $('#moduleTable tbody tr:last input').filter(':last').val('');
                }
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody input').each(function (index) {
                        $(this).val(response[index]);
                    });
                }
            }
        })
    }

}

//Get New Category Info
$('#sub_category').change(function () {
    var sub_cat = $(this).val();
    var loan_category = $('#loan_category_load').val();
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat, 'loan_category': loan_category },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#moduleTable').empty();
            $('#moduleTable').prepend('<tbody><tr>');
            if (response.length != 0) {
                var tb = 35;
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody tr').append(`<td><label for="disabledInput">` + response[i]['loan_category_ref_name'] + `</label><span class="required">&nbsp;*</span><input type="text" class="form-control" id="category_info" name="category_info[]" 
                    value='' tabindex='`+ tb + `' required placeholder='Enter ` + response[i]['loan_category_ref_name'] + `'></td>`);
                    $('.category_info').show();
                    tb++;


                }
                $('#moduleTable tbody tr').append(`<td><button type="button" tabindex='` + tb + `' id="add_category_info[]" name="add_category_info" 
                class="btn btn-primary add_category_info">Add</button> </td><td><span class='icon-trash-2 deleterow' id='deleterow' tabindex='`+ tb + `'></span></td>
                </tr></tbody>`);

                category_content = $('#moduleTable tbody').html(); //To get the appended category list

                // unbind the event handler
                $(document).off('click', '.add_category_info');
                $(document).on('click', '.add_category_info', function () {
                    $('#moduleTable tbody').append(category_content);
                });

                // remove delete option for last child
                $('#deleterow:last').filter(':last').removeClass('deleterow');

                // unbind the event handler
                $(document).off('click', '.deleterow');
                $(document).on('click', '.deleterow', function () {
                    $(this).parent().parent().remove();
                });
            } else {
                $('.category_info').hide();
            }
        }
    })
    $('#tot_value').val(''); $('#ad_amt').val(''); $('#loan_amt').val('');
    getLoaninfo(sub_cat);
})

//Fetch loan Details based on category select
function getLoaninfo(sub_cat_id) {
    let cus_id = $('#cus_id_loan').val();
    $.ajax({
        url: 'requestFile/getLoanInfo.php',
        data: { 'sub_cat_id': sub_cat_id, "cus_id": cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response['advance'] == 'Yes') {
                $('.advance_yes').show();
                $('#loan_amt').attr('readonly', true);

                $('#tot_value').unbind('blur').blur(function () {// to calculate loan amount ant advance percentage
                    var amt = $('#tot_value').val();
                    var advance = $('#ad_amt').val();
                    var loan_amt = parseInt(amt) - parseInt(advance);

                    if (amt <= parseInt(response['loan_limit'])) {
                        if (loan_amt != NaN) {
                            $('#loan_amt').val(loan_amt.toFixed(0));
                        }
                    } else {
                        alert('Please Enter Lesser amount!');
                        $('#tot_value').val('');
                        $('#loan_amt').val('');
                    }
                })

                $('#ad_amt').unbind('blur').blur(function () {//To calculate loan amount and advance percentage
                    var amt = $('#tot_value').val();
                    var advance = $('#ad_amt').val();
                    var loan_amt = amt - advance;
                    $('#loan_amt').val(loan_amt.toFixed(0));
                });

            } else {
                $('.advance_yes').hide();
                // $('#loan_amt').removeAttr('readonly');
                $('#loan_amt').unbind('blur').blur(function () {// to calculate loan amount ant advance percentage
                    var loan_amt = $(this).val();

                    if (loan_amt <= parseInt(response['loan_limit'])) {
                        $('#loan_amt').val(loan_amt.toFixed(0));
                    } else {
                        alert('Please Enter Lesser amount!');
                        $('#loan_amt').val('');
                    }
                })
            }
        }
    })
}

//to fetch Calculation based inputs
function profitCalculationInfo() {
    var sub_cat = $('#sub_category').val();
    var profit_type = $('#profit_type').val();
    var due_method = $('#due_method_scheme').val();
    var loan_cat = $('#loan_category').val();
    if (profit_type != '') { //Call only if profit type autamatically set
        profitCalAjax(profit_type, sub_cat, loan_cat); //Call for edit
    }
    if (due_method != '') {//Call only if due method autamatically set
        schemeAjax(due_method, sub_cat); //Call for edit
    }
    setTimeout(function () {
        var scheme_name = $('#scheme_upd').val();

        if (scheme_name != '') {//Call only if scheme name autamatically set
            schemeCalAjax(scheme_name); //Call for edit
        }
    }, 1000)

    $('#profit_type').change(function () {//On change evemt

        $('.calculation').hide(); // to hide calculation inputs
        $('.scheme').hide();// to hide Scheme inputs
        $('.emi-calculation').hide(); // to hide calculation inputs
        $('.interest-calculation').hide(); // to hide calculation inputs
        $('#profit_method').empty(); // to empty calculation inputs
        $('#calc_method').val(''); // to empty calculation inputs

        $('#due_method_scheme').val(''); // to clear due method selection 
        $('.day_scheme').hide(); // to Hide day shceme
        $('#day_scheme').val(''); // to clear day scheme selection 
        $('#scheme_name').val(''); // to clear scheme name selection 

        $('#int_rate').val('');
        $("#doc_charge").val("");
        $("#proc_fee").val("");
        $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');

        $('#due_start_from').val('');
        $('#maturity_month').val('');

        var profit_type = $(this).val();
        var sub_cat = $('#sub_category').val();
        var loan_cat = $('#loan_category').val();
        profitCalAjax(profit_type, sub_cat, loan_cat)

    });//Profit Type change event end

    $('#due_method_scheme').change(function () {
        var due_method = $(this).val();
        $('.scheme-calculation').hide();
        if (due_method == '2') { // show weekdays only if weekly due method selected
            $('.day_scheme').show();
        } else {
            $('.day_scheme').hide();
        }

        var sub_cat = $('#sub_category').val();
        schemeAjax(due_method, sub_cat);

        $('#int_rate').val(''); $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');

        $('#due_start_from').val('');
        $('#maturity_month').val('');
    });

    $('#scheme_name').change(function () { //Scheme Name change event
        var scheme_id = $(this).val();
        schemeCalAjax(scheme_id);
        $('.scheme-calculation').show();
    })
}

//
function profitCalAjax(profit_type, sub_cat, loan_cat) {
    var profit_method_upd = $('#profit_method_upd').val()
    if ($('#int_rate_upd').val()) { var int_rate_upd = $('#int_rate_upd').val(); } else { var int_rate_upd = ''; }
    if ($('#due_period_upd').val()) { var due_period_upd = $('#due_period_upd').val(); } else { var due_period_upd = ''; }
    if ($('#doc_charge_upd').val()) { var doc_charge_upd = $('#doc_charge_upd').val(); } else { var doc_charge_upd = ''; }
    if ($('#proc_fee_upd').val()) { var proc_fee_upd = $('#proc_fee_upd').val(); } else { var proc_fee_upd = ''; }
    if (profit_type == '1') {//if Calculation selected
        $('.calculation').show();
        $('.scheme').hide();
        $('.scheme-calculation').hide();
        $.ajax({ // To show profit calculation infos based on sub category
            url: 'verificationFile/LoanCalculation/getProfitCalculationInfo.php',
            data: { 'sub_cat': sub_cat, 'loan_cat': loan_cat },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                if (response['due_type'] == 'emi') {
                    $('.emi-calculation').show();
                    $('.interest-calculation').hide();
                    $('#due_type').val('EMI');

                    var profit_method = response['profit_method'].split(','); //Splitting into array by exploding comma (',')
                    $('#profit_method').empty();
                    $('#profit_method').append(`<option value=''>Select Profit Method</option>`);
                    for (var i = 0; i < profit_method.length; i++) {
                        if (profit_method[i] == 'pre_intrest') { valuee = 'Pre Benefit'; } else if (profit_method[i] == 'after_intrest') { valuee = 'After Benefit'; }
                        var selected = '';
                        if (profit_method_upd != '' && profit_method_upd != undefined && profit_method_upd == profit_method[i]) {
                            selected = 'selected';
                        }
                        $('#profit_method').append(`<option value='` + profit_method[i] + `' ` + selected + `>` + valuee + `</option>`);
                    }
                    $('#calc_method').val('');

                    //To set min and maximum 
                    $('.min-max-int').text('* (' + response['intrest_rate_min'] + '% - ' + response['intrest_rate_max'] + '%) ');

                    $('#int_rate').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["intrest_rate_min"])};let max = ${parseFloat(response["intrest_rate_max"])}; if (!isNaN(val)) { if (val > max) {alert("Enter Lesser Value"); $(this).val(""); } else if (val < min) { alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $('#int_rate').val(int_rate_upd);

                    $('.min-max-due').text('* (' + response['due_period_min'] + ' - ' + response['due_period_max'] + ') ');

                    $('#due_period').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["due_period_min"])};let max = ${parseFloat(response["due_period_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $('#due_period').val(due_period_upd);

                    if (response['doc_charge_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-doc').text('* (' + type + response['document_charge_min'] + ' - ' + type + response['document_charge_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['doc_charge_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-doc').text('* (' + response['document_charge_min'] + type + ' - ' + response['document_charge_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }

                    // Setting onChange event to ensure the value is within the specified range
                    $('#doc_charge').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["document_charge_min"])};let max = ${parseFloat(response["document_charge_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`);

                    // Set the value for the doc_charge field
                    $('#doc_charge').val(doc_charge_upd);

                    if (response['proc_fee_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-proc').text('* (' + type + response['processing_fee_min'] + ' - ' + type + response['processing_fee_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['proc_fee_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-proc').text('* (' + response['processing_fee_min'] + type + ' - ' + response['processing_fee_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }

                    // Setting onChange event to ensure the value is within the specified range
                    $('#proc_fee').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["processing_fee_min"])};let max = ${parseFloat(response["processing_fee_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`);

                    // Set the value for the proc_fee field
                    $('#proc_fee').val(proc_fee_upd);

                } else if (response["due_type"] == "intrest") {
                    $(".emi-calculation").hide();
                    $(".interest-calculation").show();
                    $("#due_type").val("Interest");
                    $("#profit_method").empty();

                    $("#calc_method").val(response["calculate_method"]);
                    if (response["calculate_method"] == "monthly") {
                        $("#calc_method").val("Monthly");
                    } else if (response["calculate_method"] == "days") {
                        $("#calc_method").val("Days");
                    }

                    //To set min and maximum
                    $(".min-max-int").text("* (" + response["intrest_rate_min"] + "% - " + response["intrest_rate_max"] + "%) ");

                    $("#int_rate").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["intrest_rate_min"])};let max = ${parseFloat(response["intrest_rate_max"])}; if (!isNaN(val)) { if (val > max) {alert("Enter Lesser Value"); $(this).val(""); } else if (val < min) { alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#int_rate").val(int_rate_upd);

                    $(".min-max-due").text("* (" + response["due_period_min"] + " - " + response["due_period_max"] + ") ");

                    $("#due_period").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["due_period_min"])};let max = ${parseFloat(response["due_period_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#due_period").val(due_period_upd);

                    if (response["doc_charge_type"] == "amt") {
                        type = "₹";
                        $(".min-max-doc").text("* (" + type + response["document_charge_min"] + " - " + type + response["document_charge_max"] + ") "); // Set min-max values with ₹ symbol before the numbers
                    } else if (response["doc_charge_type"] == "percentage") {
                        type = "%";
                        $(".min-max-doc").text("* (" + response["document_charge_min"] + type + " - " + response["document_charge_max"] + type + ") "); // Set min-max values with % symbol after the numbers
                    }

                    $("#doc_charge").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["document_charge_min"])};let max = ${parseFloat(response["document_charge_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#doc_charge").val(doc_charge_upd);

                    if (response["proc_fee_type"] == "amt") {
                        type = "₹";  // Set ₹ symbol before the numbers
                        $(".min-max-proc").text("* (" + type + response["processing_fee_min"] + " - " + type + response["processing_fee_max"] + ") "); // Set min-max values with ₹ symbol before the numbers
                    } else if (response["proc_fee_type"] == "percentage") {
                        type = "%"; // Set % symbol after the numbers
                        $(".min-max-proc").text("* (" + response["processing_fee_min"] + type + " - " + response["processing_fee_max"] + type + ") "); // Set min-max values with % symbol after the numbers
                    }

                    $("#proc_fee").attr("onChange", `let val = parseFloat($(this).val());let min = ${parseFloat(response["processing_fee_min"])};let max = ${parseFloat(response["processing_fee_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                    $("#proc_fee").val(proc_fee_upd);
                }
            }
        })
    } else if (profit_type == '2') { //if Scheme selected
        $('.calculation').hide(); // to hide calculation inputs
        $('.scheme').show(); // to show scheme inputs
        $('.scheme-calculation').show();
    } else {
        $('.calculation').hide(); // to hide calculation inputs
        $('.scheme').hide(); // to hide scheme inputs
        $('.scheme-calculation').hide();
    }
}

//
function schemeAjax(due_method, sub_cat) {
    var scheme_upd = $('#scheme_upd').val();
    $.ajax({ //To show scheme names based on sub category
        url: 'verificationFile/LoanCalculation/getSchemeNames.php',
        data: { 'sub_cat': sub_cat, 'due_method': due_method },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#scheme_name').empty();
            $('#scheme_name').append(`<option value=''>Select Scheme Name</option>`);
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (scheme_upd != '' && scheme_upd != undefined && scheme_upd == response[i]['scheme_id']) {
                    selected = 'selected';
                    $('#scheme_name_ack').val(response[i]['scheme_id']);
                }
                $('#scheme_name').append(`<option value='` + response[i]['scheme_id'] + `' ` + selected + `>` + response[i]['scheme_name'] + `</option>`);
            }
        }
    });
}

//
function schemeCalAjax(scheme_id) {
    if (scheme_id != '') {
        if ($('#int_rate_upd').val()) { var int_rate_upd = $('#int_rate_upd').val(); } else { var int_rate_upd = ''; }
        if ($('#due_period_upd').val()) { var due_period_upd = $('#due_period_upd').val(); } else { var due_period_upd = ''; }
        if ($('#doc_charge_upd').val()) { var doc_charge_upd = $('#doc_charge_upd').val(); } else { var doc_charge_upd = ''; }
        if ($('#proc_fee_upd').val()) { var proc_fee_upd = $('#proc_fee_upd').val(); } else { var proc_fee_upd = ''; }
        var scheme_profit_method_upd = $('#scheme_profit_method_upd').val()
        $.ajax({ //show scheme based loan info using scheme id
            url: 'verificationFile/LoanCalculation/getSchemeDetails.php',
            dataType: 'json',
            type: 'post',
            data: { 'scheme_id': scheme_id },
            cache: false,
            success: function (response) {
                //To set min and maximum 
                var profit_method = response['profit_method'].split(','); //Splitting into array by exploding comma (',')
                $('#scheme_profit_method').empty();
                $('#scheme_profit_method').append(`<option value=''>Select Profit Method</option>`);
                for (var i = 0; i < profit_method.length; i++) {
                    if (profit_method[i] == 'pre_intrest') { valuee = 'Pre Benefit'; } else if (profit_method[i] == 'after_intrest') { valuee = 'After Benefit'; }
                    var selected = '';
                    if (scheme_profit_method_upd != '' && scheme_profit_method_upd != undefined && scheme_profit_method_upd == profit_method[i]) {
                        selected = 'selected';
                    }
                    $('#scheme_profit_method').append(`<option value='` + profit_method[i] + `' ` + selected + `>` + valuee + `</option>`);
                }
                // $('#int_rate').val(response['intrest_rate']); $('#int_rate').attr('readonly', true); // setting readonly due to fixed interest

                $('#due_period').val(response['due_period']); $('#due_period').attr('readonly', true); // setting readonly due to fixed due period

                if (response['intreset_type'] == 'amt') { type = '₹' } else if (response['intreset_type'] == 'percentage') { type = '%'; } //Setting symbols

                $('.min-max-int').text('* (' + response['intreset_min'] + ' ' + type + ' - ' + response['intreset_max'] + ' ' + type + ') '); //setting min max values in span

                $('#int_rate').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["intreset_min"])};let max = ${parseFloat(response["intreset_max"])}; if (!isNaN(val)) { if (val > max) {alert("Enter Lesser Value"); $(this).val(""); } else if (val < min) { alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage          

                $('#int_rate').val(int_rate_upd);

                if (response['doc_charge_type'] == 'amt') { type = '₹' } else if (response['doc_charge_type'] == 'percentage') { type = '%'; } //Setting symbols

                $('.min-max-doc').text('* (' + response['doc_charge_min'] + ' ' + type + ' - ' + response['doc_charge_max'] + ' ' + type + ') '); //setting min max values in span

                $('#doc_charge').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["doc_charge_min"])};let max = ${parseFloat(response["doc_charge_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                $('#doc_charge').val(doc_charge_upd);

                if (response['proc_fee_type'] == 'amt') { type = '₹' } else if (response['proc_fee_type'] == 'percentage') { type = '%'; }//Setting symbols

                $('.min-max-proc').text('* (' + response['proc_fee_min'] + ' ' + type + ' - ' + response['proc_fee_max'] + ' ' + type + ') ');//setting min max values in span

                $('#proc_fee').attr('onChange', `let val = parseFloat($(this).val());let min = ${parseFloat(response["proc_fee_min"])};let max = ${parseFloat(response["proc_fee_max"])}; if (!isNaN(val)) { if(val > max){ alert("Enter Lesser Value"); $(this).val(""); } else if(val < min){ alert("Enter Higher Value"); $(this).val(""); } }else{ alert("Please enter a valid numeric value"); $(this).val("");}`); //To check value between rage

                $('#proc_fee').val(proc_fee_upd);
            }
        })
    } else {
        $('#int_rate').val(''); $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');
        $('#due_start_from').val('');
        $('#maturity_month').val('');
    }
}

//To Get Loan Calculation for After Interest
function getLoanAfterInterest() {
    var loan_amt = $('#loan_amt').val().replace(/[, ]+/g, '');
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();


    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card
    // principal amt as same as loan amt for after interest

    var interest_rate = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    var tot_amt = parseInt(loan_amt) + parseFloat(interest_rate); //Calculate total amount from principal/loan amt and interest rate
    $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot))

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;
    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - interest_rate) + ')'); //To show the difference amount from old to new
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(loan_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
}

//To Get Loan Calculation for Pre Interest
function getLoanPreInterest() {
    var loan_amt = $('#loan_amt').val().replace(/[, ]+/g, '');
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card

    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot));

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
}

//To Get Loan Calculation for Interest due type
function getLoanInterest() {
    var loan_amt = $("#loan_amt").val().replace(/[, ]+/g, '');
    var int_rate = $("#int_rate").val();
    var doc_charge = $("#doc_charge").val();
    var proc_fee = $("#proc_fee").val();

    var calc_method = $("#calc_method").val();

    $("#loan_amt_cal").val(formatIndianNumber(loan_amt));

    let int_amt;

    if (calc_method === 'Monthly') {
        int_amt = (loan_amt * (int_rate / 100)).toFixed(0);
    } else if (calc_method === 'Days') {
        int_amt = (loan_amt * (int_rate / 100) / 30).toFixed(0);
    }

    var roundedInterest = Math.ceil(int_amt / 5) * 5;
    if (roundedInterest < int_amt) {
        roundedInterest += 5;
    }

    $(".int-diff").text("* (Difference: +" + parseInt(roundedInterest - int_amt) + ")");
    $("#int_amt_cal").val(formatIndianNumber(roundedInterest));

    var doc_type = $(".min-max-doc").text();
    if (doc_type.includes("₹")) {
        var doc_charge = parseInt(doc_charge);
    } else if (doc_type.includes("%")) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100);
    }

    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5;
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }

    $(".doc-diff").text("* (Difference: +" + parseInt(roundeddoccharge - doc_charge) + ")");
    $("#doc_charge_cal").val(formatIndianNumber(roundeddoccharge));

    var proc_type = $(".min-max-proc").text();
    if (proc_type.includes("₹")) {
        var proc_fee = parseInt(proc_fee);
    } else if (proc_type.includes("%")) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);
    }

    var roundeprocfee = Math.ceil(proc_fee / 5) * 5;
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }

    $(".proc-diff").text("* (Difference: +" + parseInt(roundeprocfee - proc_fee) + ")");
    $("#proc_fee_cal").val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseInt(doc_charge) - parseInt(proc_fee);
    $("#net_cash_cal").val(formatIndianNumber(net_cash));
}

function getSchemeAfterIntreset() {
    var loan_amt = $('#loan_amt').val().replace(/[, ]+/g, '');
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card
    // principal amt as same as loan amt for after interest
    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    }

    var tot_amt = parseInt(loan_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot))

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
}

function getSchemePreIntreset() {
    var loan_amt = $('#loan_amt').val().replace(/[, ]+/g, '');
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(formatIndianNumber(loan_amt)); //get loan amt from loan info card

    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    }

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(formatIndianNumber(roundDue));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(formatIndianNumber(new_tot))

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(formatIndianNumber(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: ' + parseInt(new_princ - princ_amt) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(formatIndianNumber(new_princ));

    //////////////////////////////////////////////////////////////////////////////////

    var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (doc_type.includes('₹')) {
        var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (doc_type.includes('%')) {
        var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    }
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(formatIndianNumber(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(formatIndianNumber(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(formatIndianNumber(net_cash));
}

//To Get Loan Calculation for Monthly Scheme method
// function getLoanMonthly() {
//     var loan_amt = $('#loan_amt').val();
//     var int_rate = $('#int_rate').val();
//     var due_period = $('#due_period').val();
//     var doc_charge = $('#doc_charge').val();
//     var proc_fee = $('#proc_fee').val();

//     $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card

//     var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
//     // $('#int_amt_cal').val(parseInt(int_amt));

//     var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
//     // $('#principal_amt_cal').val(princ_amt); 

//     var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
//     // $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

//     var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
//     var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
//     if (roundDue < due_amt) {
//         roundDue += 5;
//     }
//     $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
//     $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

//     ////////////////////recalculation of total, principal, interest///////////////////

//     var new_tot = parseInt(roundDue) * due_period;
//     $('#tot_amt_cal').val(new_tot)

//     //to get new interest rate using round due amt 
//     let new_int = (roundDue * due_period) - princ_amt;

//     var roundedInterest = Math.ceil(new_int / 5) * 5;
//     if (roundedInterest < new_int) {
//         roundedInterest += 5;
//     }

//     $('.int-diff').text('* (Difference: +' + parseInt(new_int - int_amt) + ')'); //To show the difference amount
//     $('#int_amt_cal').val(parseInt(roundedInterest));

//     var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
//     $('#principal_amt_cal').val(new_princ);

//     //////////////////////////////////////////////////////////////////////////////////

//     var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
//     if (doc_type.includes('₹')) {
//         var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
//     } else if (doc_type.includes('%')) {
//         var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
//     }
//     $('#doc_charge_cal').val(parseInt(doc_charge).toFixed(0));

//     var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
//     if (proc_type.includes('₹')) {
//         var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
//     } else if (proc_type.includes('%')) {
//         var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
//     }
//     $('#proc_fee_cal').val(parseInt(proc_fee).toFixed(0));

//     var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
//     $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
// }

// //To Get Loan Calculation for Weekly Scheme method
// function getLoanWeekly() {
//     var loan_amt = $('#loan_amt').val();
//     var int_rate = $('#int_rate').val();
//     var due_period = $('#due_period').val();
//     var doc_charge = $('#doc_charge').val();
//     var proc_fee = $('#proc_fee').val();

//     $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card

//     var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
//     // var roundedInterest = Math.ceil(int_amt / 5) * 5;
//     // if (roundedInterest < int_amt) {
//     //     roundedInterest += 5;
//     // }
//     // $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
//     // $('#int_amt_cal').val(parseInt(int_amt));

//     var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
//     $('#principal_amt_cal').val(parseInt(princ_amt).toFixed(0));

//     var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
//     $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

//     var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
//     var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
//     if (roundDue < due_amt) {
//         roundDue += 5;
//     }
//     $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
//     $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

//     ////////////////////recalculation of total, principal, interest///////////////////

//     var new_tot = parseInt(roundDue) * due_period;
//     $('#tot_amt_cal').val(new_tot)

//     //to get new interest rate using round due amt 
//     let new_int = (roundDue * due_period) - princ_amt;

//     var roundedInterest = Math.ceil(new_int / 5) * 5;
//     if (roundedInterest < new_int) {
//         roundedInterest += 5;
//     }

//     $('.int-diff').text('* (Difference: +' + parseInt(new_int - int_amt) + ')'); //To show the difference amount
//     $('#int_amt_cal').val(parseInt(roundedInterest));

//     var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
//     $('#principal_amt_cal').val(new_princ);

//     //////////////////////////////////////////////////////////////////////////////////

//     var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
//     if (doc_type.includes('₹')) {
//         var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
//     } else if (doc_type.includes('%')) {
//         var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
//     }
//     $('#doc_charge_cal').val(parseInt(doc_charge).toFixed(0));

//     var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
//     if (proc_type.includes('₹')) {
//         var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
//     } else if (proc_type.includes('%')) {
//         var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
//     }
//     $('#proc_fee_cal').val(parseInt(proc_fee).toFixed(0));

//     var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
//     $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
// }

// //To Get Loan Calculation for Daily Scheme method
// function getLoanDaily() {
//     var loan_amt = $('#loan_amt').val();
//     var int_rate = $('#int_rate').val();
//     var due_period = $('#due_period').val();
//     var doc_charge = $('#doc_charge').val();
//     var proc_fee = $('#proc_fee').val();

//     $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card

//     var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
//     // var roundedInterest = Math.ceil(int_amt / 5) * 5;
//     // if (roundedInterest < int_amt) {
//     //     roundedInterest += 5;
//     // }
//     // $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
//     $('#int_amt_cal').val(parseInt(int_amt));

//     var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
//     $('#principal_amt_cal').val(parseInt(princ_amt).toFixed(0));

//     var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
//     $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

//     var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
//     var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
//     if (roundDue < due_amt) {
//         roundDue += 5;
//     }
//     $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
//     $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

//     ////////////////////recalculation of total, principal, interest///////////////////

//     var new_tot = parseInt(roundDue) * due_period;
//     $('#tot_amt_cal').val(new_tot)

//     //to get new interest rate using round due amt 
//     let new_int = (roundDue * due_period) - princ_amt;

//     var roundedInterest = Math.ceil(new_int / 5) * 5;
//     if (roundedInterest < new_int) {
//         roundedInterest += 5;
//     }

//     $('.int-diff').text('* (Difference: +' + parseInt(new_int - int_amt) + ')'); //To show the difference amount
//     $('#int_amt_cal').val(parseInt(roundedInterest));

//     var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
//     $('#principal_amt_cal').val(new_princ);

//     //////////////////////////////////////////////////////////////////////////////////

//     var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
//     if (doc_type.includes('₹')) {
//         var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
//     } else if (doc_type.includes('%')) {
//         var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
//     }
//     $('#doc_charge_cal').val(parseInt(doc_charge).toFixed(0));

//     var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
//     if (proc_type.includes('₹')) {
//         var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
//     } else if (proc_type.includes('%')) {
//         var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
//     }
//     $('#proc_fee_cal').val(parseInt(proc_fee).toFixed(0));

//     var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
//     $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
// }

function performLoanCalculation(callback) {
    var intrest_rate = $("#int_rate").val();
    var doc_charge = $("#doc_charge").val();
    var proc_fee = $("#proc_fee").val();
    var due_period = $("#due_period").val();
    var profit_method = $("#profit_method").val();

    if (intrest_rate == "" || doc_charge == "" || proc_fee == "" || due_period == "" || profit_method == "") {
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Fill out Loan Info!',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonColor: '#009688'
        });
        return;
    }
    var profit_method = $('#profit_method').val(); // if profit method changes, due type is EMI
    var due_type = $('#due_type').val();

    if (profit_method == "after_intrest" && due_type == "EMI") {
        getLoanAfterInterest();

    } else if (profit_method == 'pre_intrest') {
        getLoanPreInterest();

    }

    if (due_type == 'Interest') {
        getLoanInterest();

    }

    var scheme_profit_method = $('#scheme_profit_method').val(); // if profit method changes, due type is EMI
    if (scheme_profit_method == 'after_intrest') {
        getSchemeAfterIntreset();

    } else if (scheme_profit_method == 'pre_intrest') {
        getSchemePreIntreset();

    }

    // var due_method_scheme = $('#due_method_scheme').val();
    // if (due_method_scheme == '1') {//Monthly scheme as 1
    //     getLoanMonthly(); changeInttoBen()
    // } else if (due_method_scheme == '2') {//Weekly scheme as 2
    //     getLoanWeekly(); changeInttoBen()
    // } else if (due_method_scheme == '3') {//Daily scheme as 3
    //     getLoanDaily(); changeInttoBen()
    // }

    changeInttoBen();

    function changeInttoBen() {
        let due_type = document.getElementById("due_type");
        let int_label = document.querySelector("#int_amt_cal");
        if (due_type.value == "Interest") {
            // Set its value to 'Benefit Amount'
            int_label.previousElementSibling.previousElementSibling.textContent = "Benefit Amount";
            $('.emi_div').hide();
        } else {
            int_label.previousElementSibling.previousElementSibling.textContent = "Interest Amount";
            $('.emi_div').show();
        }
    }

    if (typeof callback === 'function') callback();

}

//Validation for Loan calculation
function loan_calc_validation(submit_btn) {
    let isValid = true;

    var cus_id_loan = $('#cus_id_loan').val(); //if this is empty means , customer profile is not submitted yet
    var loan_category = $('#loan_category').val(); var sub_category = $('#sub_category').val(); var tot_value = $('#tot_value').val(); var ad_amt = $('#ad_amt').val();
    var loan_amt = $('#loan_amt').val(); var due_type = $('#due_type').val();
    var profit_type = $('#profit_type').val(); var due_method_scheme = $('#due_method_scheme').val(); var day_scheme = $('#day_scheme').val(); var scheme_name = $('#scheme_name').val();
    var profit_method = $('#profit_method').val(); var int_rate = $('#int_rate').val(); var due_period = $('#due_period').val(); var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val(); var due_start_from = $('#due_start_from').val(); var maturity_month = $('#maturity_month').val(); var collection_method = $('#collection_method').val();

    if (cus_id_loan == '') {
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Complete Customer Profile!',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonColor: '#009688'
        });
        isValid = false;
    }

    if (loan_category == '') {
        $('#loancategoryCheck').show();
        isValid = false;
    } else {
        $('#loancategoryCheck').hide();
    }

    if (sub_category == '') {
        $('#subcategoryCheck').show();
        isValid = false;
    } else {
        $('#subcategoryCheck').hide();
    }

    if (tot_value == '' && $('.advance_yes').css('display') != "none") {
        $('#total_valueCheck').show();
        isValid = false;
    } else {
        $('#total_valueCheck').hide();
    }

    if (ad_amt == '' && $('.advance_yes').css('display') != "none") {
        $('#ad_amtCheck').show();
        isValid = false;
    } else {
        $('#ad_amtCheck').hide();
    }

    if (loan_amt == '') {
        $('#loan_amtCheck').show();
        isValid = false;
    } else {
        $('#loan_amtCheck').hide();
    }

    if (profit_type == '') {
        $('#profit_typeCheck').show();
        isValid = false;
    } else {
        $('#profit_typeCheck').hide();
    }

    if (profit_method == '' && due_type == 'EMI') {
        $('#profit_methodCheck').show();
        isValid = false;
    } else {
        $('#profit_methodCheck').hide();
    }

    if (due_method_scheme == '' && $('.scheme').css('display') != 'none') {
        $('#due_method_schemeCheck').show();
        isValid = false;
    } else {
        $('#due_method_schemeCheck').hide();
    }

    if (day_scheme == '' && $('.day_scheme').css('display') != 'none') {
        $('#day_schemeCheck').show();
        isValid = false;
    } else {
        $('#day_schemeCheck').hide();
    }

    if (scheme_name == '' && $('.scheme').css('display') != 'none') {
        $('#scheme_nameCheck').show();
        isValid = false;
    } else {
        $('#scheme_nameCheck').hide();
        // $('#scheme_name_ack').val(scheme_name);
    }

    if (int_rate == '') {
        $('#int_rateCheck').show();
        isValid = false;
    } else {
        $('#int_rateCheck').hide();
    }

    if (due_period == '') {
        $('#due_periodCheck').show();
        isValid = false;
    } else {
        $('#due_periodCheck').hide();
    }

    if (doc_charge == '') {
        $('#doc_chargeCheck').show();
        isValid = false;
    } else {
        $('#doc_chargeCheck').hide();
    }

    if (proc_fee == '') {
        $('#proc_feeCheck').show();
        isValid = false;
    } else {
        $('#proc_feeCheck').hide();
    }

    if (due_start_from == '') {
        $('#due_start_fromCheck').show();
        isValid = false;
    } else {
        $('#due_start_fromCheck').hide();
    }

    if (maturity_month == '') {
        $('#maturity_monthCheck').show();
        isValid = false;
    } else {
        $('#maturity_monthCheck').hide();
    }

    if (collection_method == '') {
        $('#collection_methodCheck').show();
        isValid = false;
    } else {
        $('#collection_methodCheck').hide();
    }

    //Verification Person Multi select store
    var person_list = personMultiselect.getValue();
    var person = '';
    for (var i = 0; i < person_list.length; i++) {
        if (i > 0) {
            person += ',';
        }
        person += person_list[i].value;
    }
    var arr = person.split(",");
    arr.sort(function (a, b) { return a - b });
    var sortedStr = arr.join(",");
    $('#verifyPerson').val(sortedStr);

    submit_btn.removeAttr('disabled');
    return isValid;

}

function submitLoanCalculation(submit_btn) {
    const form = document.getElementById("cus_loancalc");
    const formData = new FormData(form);

    $.ajax({
        url: "verificationFile/LoanCalculation/submitLoanCalculation.php",
        type: "POST",
        data: formData,
        contentType: false, // Important for file upload
        processData: false, // Prevent jQuery from processing the data
        dataType: "json",
        success: function (response) {
            if (response["info"].includes("Success")) {
                alert(response.info);
                showOverlay(); //Reload takes few milliseconds to start so in mean time showing overlay to restrict user access.
                location.reload();
            } else {
                alert("Error on Submit");
            }
            submit_btn.prop("disabled", false);
        },
        error: function () {
            alert("Server error during submission.");
            submit_btn.prop("disabled", false);
        }
    });
}

//////////////////////////////////////////////////////////////////// Loan Calculation Functions End ///////////////////////////////////////////////////////////////////////////////