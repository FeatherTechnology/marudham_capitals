const personMultiselect = new Choices('#verification_person', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Verification Person',
    allowHTML: true
});

$(document).ready(function () {

    $('input[data-type="adhaar-number"]:not(#cus_id)').keyup(function () { /// AAdhar Validation 
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date in the date input to today
        $('#due_start_from').attr('min', today);
    }

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

    {
        //this code will change customer limit field readonly when user dont have access to approval screen 
        //or else even access is not there if the customer is new then any one can edit that filed, it will not set to readonly
        let cus_type = $('#cus_type').val();
        let approvalaccess = $('#approvalaccess').val();

        if (!(cus_type === 'New' || approvalaccess === '0')) {
            $('#cus_loan_limit').attr('readonly', true);
        }
    }

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

    function famNameList() {  // To show family name for Data Check.
        let req_id = $('#req_id').val();
        var cus_name = $('#cus_name').val();
        var cus_id = $('#cus_id').val();//customer id

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
        var mobile1 = $('#mobile1').val();
        var cus_id = $('#cus_id').val();//customer id

        $.ajax({
            url: 'verificationFile/verification_datacheck_name.php',
            type: 'POST',
            data: { "cus_id": cus_id },
            dataType: 'json',
            cache: false,
            success: function (response) {
                $("#check_mobileno").empty();
                $('#check_mobileno').append("<option value=''> Select Mobile Number </option>")
                $('#check_mobileno').append("<option value='" + mobile1 + "'> " + mobile1 + " - Customer </option>");//Current Customer Number
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
        var cus_id = $('#cus_id').val();//customer adhar for 

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



    $('#pic').change(function () {
        var pic = $('#pic')[0];
        var img = $('#imgshow');
        img.attr('src', URL.createObjectURL(pic.files[0]));
    })

    $('#guarentorpic').change(function () {
        var pic = $('#guarentorpic')[0];
        var img = $('#imgshows');
        img.attr('src', URL.createObjectURL(pic.files[0]));
    })

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
        getDistrictDropdown(StateSelected);
    });

    $('#district').change(function () {
        var DistSelected = $(this).val();
        $('#district1').val(DistSelected);
        getTalukDropdown(DistSelected);
    });

    $('#taluk').change(function () {
        var talukselected = $(this).val();
        $('#taluk1').val(talukselected);
        getTalukBasedArea(talukselected);
    })

    $('#area').change(function () {
        var areaselected = $('#area').val();
        getAreaBasedSubArea(areaselected);
    })

    $('#getlatlong').click(function () {
        event.preventDefault();
        navigator.geolocation.getCurrentPosition((position) => {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            $('#latlong').val(latitude + ',' + longitude);
        });
    })

    window.onscroll = function () {
        var navbar = document.getElementById("navbar");
        var stickyHeader = navbar.offsetTop;
        if (window.pageYOffset > 500) {
            // navbar.style.display = "block";
            $('#navbar').fadeIn('fast');
            // window.setTimeout(function () {
            //     navbar.style.opacity = 1;
            // }, 0);
            navbar.classList.add("stickyHeader")
        } else {
            // navbar.style.opacity = 0.3;
            // window.setTimeout(function () {
            // navbar.style.display = 'none';
            $('#navbar').fadeOut('fast');
            // }, 300);
            navbar.classList.remove("stickyHeader");
        }
    };

    $('#sub_area').change(function () {
        var sub_area_id = $(this).val();
        getGroupandLine(sub_area_id);
    })

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
    $('#cus_profile,#documentation,#loan_calc').click(function () {
        var verify = $('input[name=verification_type]:checked').val();

        if (verify == 'cus_profile') {
            $('#customer_profile').show(); $('#cus_document').hide(); $('#customer_loan_calc').hide();
        }
        if (verify == 'documentation') {
            $('#customer_profile').hide(); $('#cus_document').show(); $('#customer_loan_calc').hide();
            // getDocumentHistory();

        }
        if (verify == 'loan_calc') {
            $('#customer_profile').hide(); $('#cus_document').hide(); $('#customer_loan_calc').show();
            onLoadEditFunction();
            getCustomerOldData();
            getUserBasedLoanCategory();
            setTimeout(() => {
                getCategoryInfo();
                var sub_cat_id = $('#sub_category_load').val();
                console.log(sub_cat_id);
                getLoaninfo(sub_cat_id);
                profitCalculationInfo();
            }, 1000)
        }
    })

    //Open close Cards
    // $('.icon-chevron-down1').click(function(){ //$('.card-header').click(function(){
    //     $(this).parent().next('div').slideToggle(); //$(this).next('div').slideToggle();
    // })

    function getCustomerOldData() {
        let cus_id = $('#cus_id').val();
        $.post('updateFile/showCustomerOldData.php', { cus_id }, function (html) {
            // $('#cusOldDataDiv').empty().html(html);
        })
    }

    ///Documentation 

    ////Mortgage Info 
    $('#mortgageprocessCheck').hide(); $('#propertyholdertypeCheck').hide(); $('#docpropertytypeCheck').hide(); $('#docpropertymeasureCheck').hide(); $('#docpropertylocCheck').hide(); $('#docpropertyvalueCheck').hide();

    // $('#mortgagenameCheck').hide(); $('#mortgagedsgnCheck').hide(); $('#mortgagenumCheck').hide(); $('#regofficeCheck').hide(); $('#mortgagevalueCheck').hide(); $('#mortgagedocCheck').hide();

    //Endorsement Info
    $('#endorsementprocessCheck').hide(); $('#ownertypeCheck').hide(); $('#vehicletypeCheck').hide(); $('#vehicleprocessCheck').hide(); $('#enCompanyCheck').hide(); $('#enModelCheck').hide();
    // $('#vehicle_reg_noCheck').hide(); $('#endorsementnameCheck').hide(); $('#enRCCheck').hide(); $('#enKeyCheck').hide();

    //Gold Info
    $('#goldCheck').hide(); $('#GoldstatusCheck').hide(); $('#GoldtypeCheck').hide(); $('#purityCheck').hide(); $('#goldCountCheck').hide(); $('#goldWeightCheck').hide(); $('#goldValueCheck').hide();

    //Document Info
    $('#documentnameCheck').hide(); $('#documentdetailsCheck').hide(); $('#documentTypeCheck').hide(); $('#docholderCheck').hide();

    $('#sign_type').change(function () { // Signed Type 
        let type = $(this).val();

        $('#guar_name_div').hide();
        $('#relation_doc').hide();

        if (type == '1') { // if guarentor , then show guarentor name
            getGuarentorName();
        }
        if (type == '3' || type == '2') {// if type is combined or family member then show family members
            //for combined, it will represents who is signed with customer in the same document.
            $('#relation_doc').show();
            signTypeRelation();

        } else {
            $("#signType_relationship").empty();
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
            var signid = $(this).attr('value');

            $.ajax({
                url: 'verificationFile/documentation/signed_doc_delete.php',
                type: 'POST',
                data: { "signid": signid },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");
                    if (delresult) {
                        $('#signDeleteOk').show();
                        setTimeout(function () {
                            $('#signDeleteOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {

                        $('#signDeleteNotOk').show();
                        setTimeout(function () {
                            $('#signDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }

                    resetsignInfo();
                }
            });
        }
    });

    $('#holder_type').change(function () { // Cheque info 
        let type = $(this).val();
        let req_id = $('#req_id').val();

        if (type == '0') {
            $('#holder_name').show();
            $('#holder_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#holder_name').val(result['name']);
                    $('#cheque_relation').val('NIL');
                }
            });

        } else if (type == '1') {
            $('#holder_name').show();
            $('#holder_relationship_name').hide();

            $.ajax({
                type: 'POST',
                url: 'verificationFile/documentation/check_holder_name.php',
                data: { "type": type, "reqId": req_id },
                dataType: 'json',
                cache: false,
                success: function (result) {
                    $('#holder_name').val(result['name']);
                    $('#cheque_relation').val(result['relationship']);
                }
            });

        } else if (type == '2') {
            $('#holder_name').hide();
            $('#holder_relationship_name').show();
            $('#cheque_relation').val('');

            chequeHolderName(); // Holder Name From Family Table.
        } else {
            $('#holder_name').show();
            $('#holder_relationship_name').hide();
            $('#holder_name').val('');
            $('#cheque_relation').val('');
        }
    });

    $('#holder_relationship_name').change(function () {
        let fam_id = $(this).val();
        $.ajax({
            url: 'verificationFile/documentation/find_cheque_relation.php',
            type: 'POST',
            data: { "fam_id": fam_id },
            dataType: 'json',
            success: function (response) {
                $('#cheque_relation').val(response);

            }
        });
    })

    $("body").on("click", "#cheque_info_edit", function () {
        let id = $(this).attr('value');
        chequeHolderName(); // Holder Name From Family Table.

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

            }
        });

    });


    $("body").on("click", "#cheque_info_delete", function () {
        var isok = confirm("Do you want delete this Cheque Info?");
        if (isok == false) {
            return false;
        } else {
            var chequeid = $(this).attr('value');

            $.ajax({
                url: 'verificationFile/documentation/cheque_info_delete.php',
                type: 'POST',
                data: { "chequeid": chequeid },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Deleted");
                    if (delresult) {
                        $('#chequeDeleteOk').show();
                        setTimeout(function () {
                            $('#chequeDeleteOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {

                        $('#chequeDeleteNotOk').show();
                        setTimeout(function () {
                            $('#chequeDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }

                    resetchequeInfo();
                }
            });
        }
    });

    $("body").on("click", "#gold_info_edit", function () {
        let id = $(this).attr('value');
        chequeHolderName(); // Holder Name From Family Table.

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

    // Mortgage Info
    $('#mortgage_process').change(function () {

        let process = $(this).val();

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
        }
    })

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

    //Endrosement Info 
    $('#endorsement_process').change(function () {

        let process = $(this).val();

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
            // $('#vehicle_reg_no').val('');
            // $('#endorsement_name').val('');
            // $('#en_RC').val('');
            // $('#en_Key').val('');
        }
    })

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


    $('#document_holder').change(function () {
        let type = $(this).val();
        let req_id = $('#req_id').val();

        if (type == '0') {//Customer
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

        } else if (type == '1') {//Guarentor
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

        } else if (type == '2') {//Family member
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

    //To Show Relationship value on edit page.////
    let mortgage = $('#Propertyholder_type').val();
    if (mortgage == '2') {
        $('#Propertyholder_name').hide();
        $('#Propertyholder_relationship_name').show();
        mortgageHolderName();
        let mortgageHolder = $('#mortgage_relation_name').val();

        setTimeout(() => {
            $('#Propertyholder_relationship_name').val(mortgageHolder);
        }, 500);
    }

    let ot = $('#owner_type').val();
    if (ot == '2') {
        $('#owner_name').hide();
        $('#ownername_relationship_name').show();
        endorseHolderName();
        let Endorsename = $('#en_relation_name').val();

        setTimeout(() => {
            $('#ownername_relationship_name').val(Endorsename);
        }, 500);
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

});   ////////Document Ready End

$(function () {
    $('.icon-chevron-down1').parent().next('div').slideUp(); //To collapse all card on load

    getImage(); // To show customer image when window onload.

    resetFamInfo(); //Call Family Info Table Initially.
    resetFamDetails();
    closeFamModal();

    getOldGuarentorImg();//gets the guarentor name if the customer is exist or already uploaded the guarentor pic

   // resetgroupInfo(); //Group Family Modal Table Reset 
   // resetGroupDetails()
    // closeGroupModal()

    resetpropertyInfo() // Property Info Modal Table Reset.
    // closePropertyModal() //Property Info List.
    resetPropertyinfoList() //Property Info List.

    resetbankInfo(); // Bank info Modal Table Reset.
    // closeBankModal(); //Bank Info List.
    resetbankinfoList(); //Bank Info List.

    // resetkycInfo(); //KYC info Modal Table Reset.
    resetkycinfoList(); //KYC Info List.

    //Documentation
    getstaffCode(); // Atuo Generate Doc ID.

    resetsignInfo(); // Signed Doc info Reset.
    resetsigninfoList(); // Signed Doc List Reset.

    resetchequeInfo(); // Cheque Info Reset.
    chequeinfoList(); // Cheque Info List.

    resetgoldInfo(); // Gold Info Reset.
    goldinfoList(); // Gold Info List.

    resetdocInfo(); // Document Info Reset.
    docinfoList(); // Document Info List.

    resetfeedback(); //Reset Feedback Modal Table.
    feedbackList(); // Feedback List.

    getCustomerLoanCounts();//to get closed customer details

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


});

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
// Modal Box for Agent Group
$('#famnameCheck').hide(); $('#famrelationCheck').hide(); $('#famremarkCheck').hide(); $('#famaddressCheck').hide(); $('#famageCheck').hide(); $('#famaadharCheck').hide(); $('#fammobileCheck').hide(); $('#famoccCheck').hide(); $('#famincomeCheck').hide();
$(document).on("click", "#submitFamInfoBtn", function () {
    let req_id = $('#req_id').val();
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

    if (famname != "" && relationship != "" && relation_aadhar != "" && relation_Mobile != "" && relation_Mobile.length === 10 && req_id != "") {
        $.ajax({
            url: 'verificationFile/verification_family_submit.php',
            type: 'POST',
            data: { "famname": famname, "realtionship": relationship, "other_remark": other_remark, "other_address": other_address, "relation_age": relation_age, "relation_aadhar": relation_aadhar, "relation_Mobile": relation_Mobile, "relation_Occupation": relation_Occupation, "relation_Income": relation_Income, "relation_Blood": relation_Blood, "famTableId": famTableId, "reqId": req_id, "cus_id": cus_id },
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
    var guarentor_name = $('#guarentor_name_upd').val();
    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#guarentor_name").empty();
            $("#guarentor_name").append("<option value=''>" + 'Select Guarantor' + "</option>");
            for (var i = 0; i < len - 1; i++) { // -1 because this ajax's response will contain customer value at the last of the response for verification person
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                var selected = '';
                if (guarentor_name != '' && guarentor_name == fam_id) {
                    selected = 'selected';
                }
                $("#guarentor_name").append("<option value='" + fam_id + "' " + selected + ">" + fam_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#guarentor_name option:first-child");
                $("#guarentor_name").html($("#guarentor_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#guarentor_name").prepend(firstOption);
            }

            resetFamInfo();
            resetFamDetails();
            verificationPerson(); //To Select verification Person in Verification Info.////// 
        }
    });
}


function getOldGuarentorImg() {
    let cus_id = $('#cus_id').val();
    $.post('verificationFile/getOldGuarentorImg.php', { "cus_id": cus_id }, function (response) {

        if (response.length > 0) {
            var img = response[0]['img'];
            $('#imgshows').attr('src', "uploads/verification/guarentor/" + img);
            $('#guarentor_image').val(img);
            $('#guarentorpic').attr('value', img);
            $('#guarentor_relationship').val(response[0]['relation']);


            $.post('verificationFile/verificationFam.php', { "cus_id": cus_id }, function (data) {

                $("#guarentor_name").empty().append("<option value=''>" + 'Select Guarantor' + "</option>");
                for (var i = 0; i < data.length - 1; i++) { // -1 because this ajax's response will contain customer value at the last of the response for verification person
                    var fam_name = data[i]['fam_name']; var fam_id = data[i]['fam_id'];
                    var selected = '';
                    if (response[0]['fam_id'] != '' && fam_id == response[0]['fam_id']) {
                        selected = 'selected';
                    }
                    $("#guarentor_name").append("<option value='" + fam_id + "' " + selected + ">" + fam_name + "</option>");
                }

            }, 'json')

        } else {
            $('#imgshows').attr('src', 'img/avatar.png');
        }
    }, 'json');

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
                var fam_name = response[i]['fam_name'] + ' - ' + response[i]['relationship'];
                var fam_id = response[i]['fam_id'];
                var selected = '';
                if (verification_person_upd !== '' && values.includes(String(fam_id))) {
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


$('#guarentor_name').change(function () { //Select Guarantor Name relationship will show in input.

    let famId = document.querySelector("#guarentor_name").value;

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




//////////////////////////////////Group Start///////////////////////////////////////

//$('#grpnameCheck').hide(); $('#grpageCheck').hide(); $('#grpaadharCheck').hide(); $('#grpmbleCheck').hide(); $('#grpgenCheck').hide(); $('#grpdesgnCheck').hide();

// $(document).on("click", "#groupInfoBtn", function () {
//     let req_id = $('#req_id').val();
//     let cus_id = $('#cus_id').val();
//     let group_name = $("#group_name").val();
//     let group_age = $("#group_age").val();
//     let group_aadhar = $("#group_aadhar").val();
//     let group_mobile = $("#group_mobile").val();
//     let group_gender = $("#group_gender").val();
//     let group_designation = $("#group_designation").val();
//     let grpID = $("#grpID").val();

//     if (group_name != "" && group_age != "" && group_aadhar != "" && group_mobile != "" && group_mobile.length === 10 && group_gender != "" && group_designation != "" && req_id != "") {
//         $.ajax({
//             url: 'verificationFile/verification_group_submit.php',
//             type: 'POST',
//             data: { "group_name": group_name, "group_age": group_age, "group_aadhar": group_aadhar, "group_mobile": group_mobile, "group_gender": group_gender, "group_designation": group_designation, "grpTableId": grpID, "req_id": req_id, "cus_id": cus_id },
//             cache: false,
//             success: function (response) {

//                 var insresult = response.includes("Inserted");
//                 var updresult = response.includes("Updated");
//                 if (insresult) {
//                     $('#grpInsertOk').show();
//                     setTimeout(function () {
//                         $('#grpInsertOk').fadeOut('fast');
//                     }, 2000);
//                 }
//                 else if (updresult) {
//                     $('#grpUpdateok').show();
//                     setTimeout(function () {
//                         $('#grpUpdateok').fadeOut('fast');
//                     }, 2000);
//                 }
//                 else {
//                     $('#NotOk').show();
//                     setTimeout(function () {
//                         $('#NotOk').fadeOut('fast');
//                     }, 2000);
//                 }

//                 resetgroupInfo();
//                 resetGroupDetails()
//                 // closeGroupModal()
//             }
//         });
//     }
//     else {

//         if (group_name == "") {
//             $('#grpnameCheck').show();
//         } else {
//             $('#grpnameCheck').hide();
//         }

//         if (group_age == "") {
//             $('#grpageCheck').show();
//         } else {
//             $('#grpageCheck').hide();
//         }

//         if (group_aadhar == "") {
//             $('#grpaadharCheck').show();
//         } else {
//             $('#grpaadharCheck').hide();
//         }

//         if (group_mobile == "" || group_mobile.length < 10) {
//             $('#grpmbleCheck').show();
//         } else {
//             $('#grpmbleCheck').hide();
//         }

//         if (group_gender == "") {
//             $('#grpgenCheck').show();
//         } else {
//             $('#grpgenCheck').hide();
//         }

//         if (group_designation == "") {
//             $('#grpdesgnCheck').show();
//         } else {
//             $('#grpdesgnCheck').hide();
//         }

//     }

// });

// function resetgroupInfo() {
//     let cus_id = $('#cus_id').val();

//     $.ajax({
//         url: 'verificationFile/verification_grp_reset.php',
//         type: 'POST',
//         data: { "cus_id": cus_id },
//         cache: false,
//         success: function (html) {
//             $("#GroupTable").empty();
//             $("#GroupTable").html(html);

//             $("#group_name").val('');
//             $("#group_age").val('');
//             $("#group_aadhar").val('');
//             $("#group_mobile").val('');
//             $("#group_gender").val('');
//             $("#group_designation").val('');
//             $("#grpID").val('');
//         }
//     });
// }

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

// $("body").on("click", "#verification_grp_edit", function () {
//     let id = $(this).attr('value');

//     $.ajax({
//         url: 'verificationFile/verification_grp_edit.php',
//         type: 'POST',
//         data: { "id": id },
//         dataType: 'json',
//         cache: false,
//         success: function (result) {

//             $("#grpID").val(result['id']);
//             $("#group_name").val(result['gname']);
//             $("#group_age").val(result['age']);
//             $("#group_aadhar").val(result['gaadhar']);
//             $("#group_mobile").val(result['gmobile']);
//             $("#group_gender").val(result['gGen']);
//             $("#group_designation").val(result['dgsn']);



//             // $('#famnameCheck').hide(); $('#famrelationCheck').hide(); $('#famremarkCheck').hide(); $('#famaddressCheck').hide(); $('#famageCheck').hide(); $('#famaadharCheck').hide(); $('#fammobileCheck').hide(); $('#famoccCheck').hide(); $('#famincomeCheck').hide(); $('#fambgCheck').hide();
//         }
//     });

// });

// $("body").on("click", "#verification_grp_delete", function () {
//     var isok = confirm("Do you want delete this Group Info?");
//     if (isok == false) {
//         return false;
//     } else {
//         var Groupid = $(this).attr('value');

//         $.ajax({
//             url: 'verificationFile/verification_grp_delete.php',
//             type: 'POST',
//             data: { "Groupid": Groupid },
//             cache: false,
//             success: function (response) {
//                 var delresult = response.includes("Deleted");
//                 if (delresult) {
//                     $('#GroupDeleteOk').show();
//                     setTimeout(function () {
//                         $('#GroupDeleteOk').fadeOut('fast');
//                     }, 2000);
//                 }
//                 else {

//                     $('#GroupDeleteNotOk').show();
//                     setTimeout(function () {
//                         $('#GroupDeleteNotOk').fadeOut('fast');
//                     }, 2000);
//                 }

//                 resetgroupInfo();
//                 resetGroupDetails()
//             }
//         });
//     }
// });

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

$('#prtytypeCheck').hide(); $('#prtymeasureCheck').hide(); $('#prtyvalCheck').hide(); $('#prtyholdCheck').hide();

$(document).on("click", "#propertyInfoBtn", function () {
    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let property_type = $("#property_typ").val();
    let property_measurement = $("#property_measurement").val();
    let property_value = $("#property_value").val();
    let property_holder = $("#property_holder").val();
    let propertyID = $("#propertyID").val();

    if (property_type != "" && property_measurement != "" && property_value != "" && property_holder != "" && req_id != "") {
        $.ajax({
            url: 'verificationFile/verification_property_submit.php',
            type: 'POST',
            data: { "property_type": property_type, "property_measurement": property_measurement, "property_value": property_value, "property_holder": property_holder, "propertyID": propertyID, "reqId": req_id, "cus_id": cus_id },
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
                // resetGroupDetails()
                // closeGroupModal()
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
        data: { 'cus_id': cus_id },
        cache: false,
        success: function (html) {
            $("#propertyList").empty();
            $("#propertyList").html(html);
        }
    });
}

////////////////////////////// Bank Info ///////////////////////////////////////////////////////

$('#bankNameCheck').hide(); $('#branchCheck').hide(); $('#accholdCheck').hide(); $('#accnoCheck').hide(); $('#ifscCheck').hide();

$(document).on("click", "#bankInfoBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let bank_name = $("#bank_name").val();
    let branch_name = $("#branch_name").val();
    let account_holder_name = $("#account_holder_name").val();
    let account_number = $("#account_number").val();
    let Ifsc_code = $("#Ifsc_code").val();
    let bankID = $("#bankID").val();

    if (bank_name != "" && branch_name != "" && account_holder_name != "" && account_number != "" && Ifsc_code != "" && req_id != "") {
        $.ajax({
            url: 'verificationFile/verification_bank_submit.php',
            type: 'POST',
            data: { "bank_name": bank_name, "branch_name": branch_name, "account_holder_name": account_holder_name, "account_number": account_number, "Ifsc_code": Ifsc_code, "bankID": bankID, "reqId": req_id, "cus_id": cus_id },
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
        data: { 'cus_id': cus_id },
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
    // let proof_type = $('#proof_type').val();
    // if(proof_type == 1){
    //     var value = $(this).val();
    //     value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
    //     $(this).val(value);
    //     $(this).attr('maxlength','14')
    // }else if(proof_type == 3){
    //     var value = $(this).val();
    //     value = value.replace(/\D/g, "").match(/.{1,2}/g).join("/"); // Modify this line
    //     $(this).val(value);
    // }else if(proof_type == 4){
    //     var value = $(this).val();
    //     value = value.replace(/\D/g, "").match(/.{1,2}/g).join("-"); // Modify this line
    //     $(this).val(value);
    // }
    // else{
    //     $(this).removeAttr('maxlength');//remove maxlength when other than adhar due to unkown count of number 
    // }
});
$('#proof_type').change(function () {
    // $('#proof_number').val('')
})

$(document).on("click", "#kycInfoBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let proofof = $("#proofof").val();
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
    formdata.append('fam_mem', fam_mem)
    formdata.append('proof_type', proof_type)
    formdata.append('proof_number', proof_number)
    formdata.append('kycID', kycID)
    formdata.append('kyc_upload', kyc_upload)
    formdata.append('reqId', req_id)
    formdata.append('cus_id', cus_id)

    if (validateKyc() == true) {
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

});

function validateKyc() {
    let proofof = $("#proofof").val();
    let fam_mem = $("#fam_mem").val();
    let proof_type = $("#proof_type").val();
    let proof_number = $("#proof_number").val();
    let result = true;

    if (proofof == "") {
        $('#proofCheck').show();
        event.preventDefault();
        result = false;
    } else {
        $('#proofCheck').hide();
    }

    if (proof_type == "") {
        $('#proofTypeCheck').show();
        event.preventDefault();
        result = false;
    } else {
        $('#proofTypeCheck').hide();
    }

    if (proofof == '2' && fam_mem == "") {
        $('#fam_memCheck').show();
        event.preventDefault();
        result = false;
    } else {
        $('#fam_memCheck').hide();
    }

    if (proof_number == "") {
        $('#proofnoCheck').show();
        event.preventDefault();
        result = false;
    } else {
        $('#proofnoCheck').hide();
    }
    return result;

}

function resetkycInfo() {
    let cus_id = $('#cus_id').val();
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/verification_kyc_reset.php',
        type: 'POST',
        data: { "cus_id": cus_id, req_id },
        cache: false,
        success: function (html) {
            $("#kycTable").empty();
            $("#kycTable").html(html);

            $("#proofof").val('');
            $(".fam_mem_div").hide();
            $("#fam_mem").val('');
            $("#proof_type").val('');
            $("#proof_number").val('');
            $("#upload").val('');
            $("#kycID").val('');

            $('#proofCheck,#proofTypeCheck,#proofnoCheck,#proofUploadCheck,.name_div').hide();
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
            if (result['fam_mem'] != '') {
                getfamilyforKyc();
                setTimeout(() => {
                    $("#fam_mem").val(result['fam_mem']);
                }, 700);
                $('.fam_mem_div').show();
            } else {
                $("#fam_mem").val('');
                $('.fam_mem_div').hide();
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

})

function getfamilyforKyc() {
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
                $('#fam_mem').append("<option value='" + value + "'>" + value + "</option>");
            });
        }

    }).then(function () {
        $('#fam_name').unbind('click');
        $('#fam_mem').change(function () {
            let req_id = $('#req_id').val(); let proof = $('#proofof').val(); let fam_name = $(this).val();
            $.ajax({
                url: 'verificationFile/verification_proof_type.php',
                type: 'POST',
                data: { "reqId": req_id, "proof": proof, "fam_name": fam_name },
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
///////////////////////////////////////////////////////////////////////////

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

    {//To Order Alphabetically
        var firstOption = $("#district option:first-child");
        $("#district").html($("#district option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#district").prepend(firstOption);
    }
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

    {//To Order Alphabetically
        var firstOption = $("#taluk option:first-child");
        $("#taluk").html($("#taluk option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#taluk").prepend(firstOption);
    }
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

            {//To Order Alphabetically
                var firstOption = $("#area option:first-child");
                $("#area").html($("#area option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#area").prepend(firstOption);
            }
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
$('#feedbacklabelCheck').hide(); $('#feedbackCheck').hide();


$(document).on("click", "#feedbackBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let feedback_label = $("#feedback_label").val();
    let cus_feedback = $("#cus_feedback").val();
    let feedback_remark = $("#feedback_remark").val();
    let feedbackID = $("#feedbackID").val();


    if (feedback_label != "" && cus_feedback != "" && req_id != "") {
        $.ajax({
            url: 'verificationFile/customer_feedback_submit.php',
            type: 'POST',
            data: { "feedback_label": feedback_label, "cus_feedback": cus_feedback, "feedback_remark": feedback_remark, "feedbackID": feedbackID, "reqId": req_id, "cus_id": cus_id },
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
            $("#feedbackID").val('');
            $("#feedback_remark").val('');

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

////////////////////////////////////////////////Submit Verification //////////////////////////////////////////////////////////////////////////////

$('#submit_verification').click(function () {
    var submit_btn = $(this);
    submit_btn.attr('disabled', true);
    validation(submit_btn);
});

function validation(submit_btn) {
    var cus_id = $('#cus_id').val(); var cus_name = $('#cus_name').val(); var dob = $('#dob').val(); var gender = $('#gender').val(); var bloodGroup = $('#bloodGroup').val(); var state = $('#state').val()
    var district = $('#district1').val(); var taluk = $('#taluk1').val(); var area = $('#area').val(); var sub_area = $('#sub_area').val(); var pic = $('#pic').val(); var mobile1 = $('#mobile1').val(); var mobile2 = $('#mobile2').val(); var whatsapp_no = $('#whatsapp_no').val();
    var guarentor_name = $('#guarentor_name').val(); var guarentor_image = $('#guarentor_image').val(); var guarentorpic = $('#guarentorpic').val(); var area_cnfrm = $('#area_cnfrm').val(); var cus_res_type = $('#cus_res_type').val();
    var cus_res_details = $('#cus_res_details').val(); var cus_res_address = $('#cus_res_address').val(); var cus_res_native = $('#cus_res_native').val();
    var cus_occ_type = $('#cus_occ_type').val(); var cus_occ_detail = $('#cus_occ_detail').val(); var cus_occ_income = $('#cus_occ_income').val(); var cus_occ_address = $('#cus_occ_address').val(); var cus_occ_dow = $('#cus_occ_dow').val(); var cus_occ_abt = $('#cus_occ_abt').val();
    var cus_how_know = $('#cus_how_know').val(); var cus_monthly_income = $('#cus_monthly_income').val(); var cus_other_income = $('#cus_other_income').val(); var cus_support_income = $('#cus_support_income').val(); var cus_Commitment = $('#cus_Commitment').val(); var cus_monDue_capacity = $('#cus_monDue_capacity').val(); var cus_loan_limit = $('#cus_loan_limit').val(); var about_cus = $('#about_cus').val();
    var req_id = $('#req_id').val();


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
    if (whatsapp_no != '' && whatsapp_no.length < 10) {
        event.preventDefault();
        $('#whatsapp_noCheck').show();
    } else {
        $('#whatsapp_noCheck').hide();
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
    // if (cus_loan_limit == '') {
    //     event.preventDefault();
    //     $('#loanLimitCheck').show();
    // } else {
    //     $('#loanLimitCheck').hide();
    // }
    if (about_cus == '') {
        event.preventDefault();
        $('#aboutcusCheck').show();
    } else {
        $('#aboutcusCheck').hide();
    }

    $.ajax({
        url: 'verificationFile/validateModals.php',
        data: { 'cus_id': cus_id, 'table': 'verification_family_info' },
        type: 'post',
        cache: false,
        success: function (response) {
            if (response == "0") {
                event.preventDefault();
                $('#family_infoCheck').show();
            } else if (response == "1") {
                $('#family_infoCheck').hide();
            }
        }
    })
    $.ajax({
        url: 'verificationFile/validateModals.php',
        data: { 'cus_id': cus_id, 'table': 'verification_kyc_info' },
        type: 'post',
        cache: false,
        success: function (response) {
            if (response == "0") {
                event.preventDefault();
                $('#kyc_infoCheck').show();
            } else if (response == "1") {
                $('#kyc_infoCheck').hide();
            }
        }
    })


    submit_btn.removeAttr('disabled');
}

$('#Communitcation_to_cus').change(function () {
    let com = $(this).val();

    if (com == '0') {
        $('#verifyaudio').show();
    } else {
        $('#verifyaudio').hide();
    }
})

//////////////////////////////////////////////////// Documentation  Start////////////////////////////////////////


//Get DOC id 
function getstaffCode() {
    let doc_Id = $('#doc_table_id').val();
    $.ajax({
        url: 'verificationFile/documentation/doc_id_autoGen.php',
        type: "post",
        dataType: "json",
        data: { "id": doc_Id },
        cache: false,
        success: function (response) {
            var docId = response;
            $('#doc_id').val(docId);
        }
    })
}

function endorseHolderName() {

    let cus_id = $('#cus_id').val();

    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#ownername_relationship_name").empty();
            $("#ownername_relationship_name").append("<option value=''>" + 'Select Holder Name' + "</option>");
            for (var i = 0; i < len - 1; i++) {// -1 because this ajax's response will contain customer value at the last of the response for verification person
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                $("#ownername_relationship_name").append("<option value='" + fam_id + "'>" + fam_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#ownername_relationship_name option:first-child");
                $("#ownername_relationship_name").html($("#ownername_relationship_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#ownername_relationship_name").prepend(firstOption);
            }

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
            $("#Propertyholder_relationship_name").empty();
            $("#Propertyholder_relationship_name").append("<option value=''>" + 'Select Holder Name' + "</option>");
            for (var i = 0; i < len - 1; i++) {// -1 because this ajax's response will contain customer value at the last of the response for verification person
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                $("#Propertyholder_relationship_name").append("<option value='" + fam_id + "'>" + fam_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#Propertyholder_relationship_name option:first-child");
                $("#Propertyholder_relationship_name").html($("#Propertyholder_relationship_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#Propertyholder_relationship_name").prepend(firstOption);
            }

        }
    });
}

function docHolderName() {
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
            for (var i = 0; i < len - 1; i++) {// -1 because this ajax's response will contain customer value at the last of the response for verification person
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                $("#docholder_relationship_name").append("<option value='" + fam_id + "'>" + fam_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#docholder_relationship_name option:first-child");
                $("#docholder_relationship_name").html($("#docholder_relationship_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#docholder_relationship_name").prepend(firstOption);
            }

        }
    });
}

$('#docNameCheck').hide(); $('#signTypeCheck').hide(); $('#docCountCheck').hide(); //Signed Doc Validation Hide ///


$(document).on("click", "#signInfoBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let cus_profile_id = $("#cus_profile_id").val();
    let doc_name = $("#doc_name").val();
    let sign_type = $("#sign_type").val();
    let signType_relationship = $("#signType_relationship").val();
    let doc_Count = $("#doc_Count").val();
    let signedID = $("#signedID").val();

    if (sign_type != "" && doc_Count != "" && req_id != "") {
        $.ajax({
            url: 'verificationFile/documentation/signed_doc_info_submit.php',
            type: 'POST',
            data: { "doc_name": doc_name, "sign_type": sign_type, "signType_relationship": signType_relationship, "doc_Count": doc_Count, "signedID": signedID, "cus_profile_id": cus_profile_id, "reqId": req_id, "cus_id": cus_id },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#signInsertOk').show();
                    setTimeout(function () {
                        $('#signInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#signUpdateok').show();
                    setTimeout(function () {
                        $('#signUpdateok').fadeOut('fast');
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

        $('#docNameCheck').hide(); $('#signTypeCheck').hide(); $('#docCountCheck').hide();
    }
    else {

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

    }

});

function resetsignInfo() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/sign_info_reset.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#signTable").empty();
            $("#signTable").html(html);

            $("#sign_type").val('');
            $("#guar_name_div").hide();
            $("#guar_name").val('');
            $("#relation_doc").hide();
            $("#signType_relationship").val('');
            $("#doc_Count").val('');
            $("#signedID").val('');

        }
    });
}

// Signed Doc 
function signTypeRelation() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        cache: false,
        success: function (response) {

            var len = response.length;
            $("#signType_relationship").empty();
            $("#signType_relationship").append("<option value=''>" + 'Select Relationship' + "</option>");
            for (var i = 0; i < len - 1; i++) {//-1 because last name will be customer name
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                var relationship = response[i]['relationship'];
                $("#signType_relationship").append("<option value='" + fam_id + "'>" + fam_name + ' - ' + relationship + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#signType_relationship option:first-child");
                $("#signType_relationship").html($("#signType_relationship option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#signType_relationship").prepend(firstOption);
            }

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
        url: 'verificationFile/documentation/signed_doc_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#signDocResetTable").empty();
            $("#signDocResetTable").html(html);

            $("#sign_type").val('');
            $("#guar_name").val('');
            $("#guar_name_div").hide();
            $("#signType_relationship").val('');
            $("#relation_doc").hide();
            $("#doc_Count").val('');
            $("#signedID").val('');
        }
    });
}

///Cheque Info 
$('#chequebankCheck').hide(); $('#holdertypeCheck').hide(); $('#chequeCountCheck').hide();

$(document).on("click", "#chequeInfoBtn", function () {

    let req_id = $('#req_id').val();
    let cus_id = $('#cus_id').val();
    let cus_profile_id = $("#cus_profile_id").val();
    let holder_type = $("#holder_type").val();
    let holder_name = $("#holder_name").val();
    let holder_relationship_name = $("#holder_relationship_name").val();
    let cheque_relation = $("#cheque_relation").val();
    let chequebank_name = $("#chequebank_name").val();
    let cheque_count = $("#cheque_count").val();
    let chequeID = $("#chequeID").val();

    if (holder_type != "" && chequebank_name != "" && cheque_count != "" && req_id != "") {
        $.ajax({
            url: 'verificationFile/documentation/cheque_info_submit.php',
            type: 'POST',
            data: { "holder_type": holder_type, "holder_name": holder_name, "holder_relationship_name": holder_relationship_name, "cheque_relation": cheque_relation, "chequebank_name": chequebank_name, "cheque_count": cheque_count, "chequeID": chequeID, "cus_profile_id": cus_profile_id, "reqId": req_id, "cus_id": cus_id },
            cache: false,
            success: function (response) {

                var insresult = response.includes("Inserted");
                var updresult = response.includes("Updated");
                if (insresult) {
                    $('#chequeInsertOk').show();
                    setTimeout(function () {
                        $('#chequeInsertOk').fadeOut('fast');
                    }, 2000);
                }
                else if (updresult) {
                    $('#chequeUpdateok').show();
                    setTimeout(function () {
                        $('#chequeUpdateok').fadeOut('fast');
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

        $('#chequebankCheck').hide(); $('#holdertypeCheck').hide(); $('#chequeCountCheck').hide();
    }
    else {

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

    }

});

function resetchequeInfo() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/cheque_info_reset.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#chequeTable").empty();
            $("#chequeTable").html(html);

            $("#holder_type").val('');
            $("#holder_name").val('');
            $("#holder_relationship_name").val('');
            $("#cheque_relation").val('');
            $("#chequebank_name").val('');
            $("#cheque_count").val('');
            $("#chequeID").val('');

        }
    });
}

//Cheque Holder Name 
function chequeHolderName() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/verificationFam.php',
        type: 'post',
        data: { "cus_id": cus_id },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#holder_relationship_name").empty();
            $("#holder_relationship_name").append("<option value=''>" + 'Select Holder Name' + "</option>");
            for (var i = 0; i < len - 1; i++) {//-1 because last one name will be customer name
                var fam_name = response[i]['fam_name'];
                var fam_id = response[i]['fam_id'];
                $("#holder_relationship_name").append("<option value='" + fam_id + "'>" + fam_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#holder_relationship_name option:first-child");
                $("#holder_relationship_name").html($("#holder_relationship_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#holder_relationship_name").prepend(firstOption);
            }

        }
    });
}

//Cheque Info List
function chequeinfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/cheque_info_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#ChequeResetTable").empty();
            $("#ChequeResetTable").html(html);

            $("#holder_type").val('');
            $("#holder_name").val('');
            $("#holder_relationship_name").val('');
            $("#cheque_relation").val('');
            $("#chequebank_name").val('');
            $("#cheque_count").val('');
            $("#chequeID").val('');
        }
    });
}

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


    if (gold_sts != "" && gold_type != "" && Purity != "" && gold_Count != "" && gold_Weight != "" && gold_Value != "" && req_id != "" && gold_upload != '') {
        $.ajax({
            url: 'verificationFile/documentation/gold_info_submit.php',
            type: 'POST',
            data: formdata,
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


        $('#GoldstatusCheck, #GoldtypeCheck, #purityCheck, #goldCountCheck, #goldWeightCheck, #goldValueCheck, #gold_uploadCheck').hide();
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
        data: { "reqId": req_id, 'pages': 1 },
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
    });
}

//Gold Info List
function goldinfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/gold_info_list.php',
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
        }
    });
}


// ///////////////////////////  Document Info Modal //////////////////////////////

$('#documentnameCheck').hide(); $('#documentdetailsCheck').hide(); $('#documentTypeCheck').hide(); $('#docholderCheck').hide();
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

    if (doc_name != '' && doc_details != '' && doc_type != '' && doc_holder != '' && relation != '') {

        $.ajax({
            url: 'verificationFile/documentation/doc_info_submit.php',
            data: { 'cus_id': cus_id, 'req_id': req_id, 'doc_id': doc_id, 'doc_name': doc_name, 'doc_details': doc_details, 'doc_type': doc_type, 'doc_holder': doc_holder, 'holder_name': holder_name, 'relation_name': relation_name, 'relation': relation },
            dataType: 'json',
            type: 'POST',
            cache: false,
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
        $('#documentnameCheck').show(); $('#documentdetailsCheck').show(); $('#documentTypeCheck').show(); $('#docholderCheck').show();
    }
})

$("body").on("click", "#doc_info_edit", function () {
    console.log('asdf')
    let id = $(this).attr('value');
    $.ajax({
        url: 'verificationFile/documentation/doc_info_edit.php',
        type: 'POST',
        data: { "id": id },
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            docHolderName();
        },
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
        data: { "req_id": req_id, 'pages': 1 },
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

        }
    });
}
//Document Info List
function docinfoList() {
    let req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/doc_info_list.php',
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
            $("#docholder_name").val('');
            $("#docholder_relationship_name").val('');
            $("#doc_relation").val('');
        }
    });
}
// ///////////////////////////  Document Info Modal END //////////////////////////////

// $('#loanCategoryTableCheck').hide();	
let loanCategoryTableError = true;
function validateLoanCategoryTable() {
    var currentrow = $("#moduleTable tr").last();
    if (currentrow.find("#cheque_no").val() == '') {
        //   $('#loanCategoryTableCheck').show();
        loanCategoryTableError = false;
        return false;
    } else {
        // $('#loanCategoryTableCheck').hide();
        loanCategoryTableError = true;
        return true;
    }
}

// add module 
$(document).on("click", '.add_checqueNo', function () {

    validateLoanCategoryTable();

    if (loanCategoryTableError == true) {
        var appendTxt = "<tr><td><input type='text' tabindex='13' class='chosen-select form-control cheque_no' id='cheque_no' name='cheque_no[]' /></td>" +
            "<td><button type='button' tabindex='26' id='add_checqueNo' name='add_checqueNo' value='Submit' class='btn btn-primary add_checqueNo'>Add</button></td>" +
            "<td><span class='deleterow icon-trash-2' tabindex='18'></span></td>" +
            "</tr>";
    }
    else {
        return false;
    }

    $('#moduleTable').find('tbody').append(appendTxt);
});

// Delete unwanted Rows
$(document).on("click", '.deleterow', function () {
    $(this).parent().parent().remove();
});

function docHistoryTable() {
    var cus_id = $('#cus_id_doc').val(); var req_id = $('#req_id').val();
    $.ajax({
        url: 'verificationFile/documentation/docHistoryTable.php',
        data: { "cus_id": cus_id, "req_id": req_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {

        }
    })
}

function getDocumentHistory() {
    let cus_id = $('#cus_id_load').val(); let req_id = $('#req_id').val(); let cus_type = $('#cus_type').val();
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

            if (cus_type == 'Existing') {
                if (response.length > 0) {

                    for (var i = 0; i < response['pending_customer'].length; i++) {
                        pending_arr[i] = response['pending_customer'][i]
                        od_arr[i] = response['od_customer'][i]
                        due_nil_arr[i] = response['due_nil_customer'][i]
                        closed_arr[i] = response['closed_customer'][i]
                        balAmnt[i] = response['balAmnt'][i]
                    }
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
            data: { 'req_id': req_id, 'cus_id': cus_id, 'pending_sts': pending_sts, 'od_sts': od_sts, 'due_nil_sts': due_nil_sts, 'closed_sts': closed_sts, 'bal_amt': bal_amt },
            type: 'post',
            cache: false,
            success: function (response) {
                $('#docHistoryDiv').empty()
                $('#docHistoryDiv').html(response);
            }
        })
    })

}

//Documentation Submit Validation
$('#submit_documentation').click(function () {
    var submit_btn = $(this);
    submit_btn.attr('disabled', true);
    doc_submit_validation(submit_btn);
});

function doc_submit_validation(submit_btn) {

    var cus_id_doc = $('#cus_id_doc').val(); var mortgage_process = $('#mortgage_process').val(); var Propertyholder_type = $('#Propertyholder_type').val(); var doc_property_pype = $('#doc_property_pype').val(); var doc_property_measurement = $('#doc_property_measurement').val(); var doc_property_location = $('#doc_property_location').val(); var doc_property_value = $('#doc_property_value').val();
    var endorsement_process = $('#endorsement_process').val(); var owner_type = $('#owner_type').val(); var vehicle_type = $('#vehicle_type').val(); var vehicle_process = $('#vehicle_process').val();
    var en_Company = $('#en_Company').val(); var en_Model = $('#en_Model').val(); var document_name = $('#document_name').val(); var document_details = $('#document_details').val(); var document_type = $('#document_type').val(); var document_holder = $('#document_holder').val();
    var req_id = $('#req_id').val();

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
    }

    if (mortgage_process == '') {
        event.preventDefault();
        $('#mortgageprocessCheck').show();
    } else {
        $('#mortgageprocessCheck').hide();
    }

    if (mortgage_process == '0') {
        if (Propertyholder_type == '') {
            event.preventDefault();
            $('#propertyholdertypeCheck').show();
        } else {
            $('#propertyholdertypeCheck').hide();
        }
        if (doc_property_pype == '') {
            event.preventDefault();
            $('#docpropertytypeCheck').show();
        } else {
            $('#docpropertytypeCheck').hide();
        }
        if (doc_property_measurement == '') {
            event.preventDefault();
            $('#docpropertymeasureCheck').show();
        } else {
            $('#docpropertymeasureCheck').hide();
        }
        if (doc_property_location == '') {
            event.preventDefault();
            $('#docpropertylocCheck').show();
        } else {
            $('#docpropertylocCheck').hide();
        }
        if (doc_property_value == '') {
            event.preventDefault();
            $('#docpropertyvalueCheck').show();
        } else {
            $('#docpropertyvalueCheck').hide();
        }
    }


    if (endorsement_process == '') {
        event.preventDefault();
        $('#endorsementprocessCheck').show();
    } else {
        $('#endorsementprocessCheck').hide();
    }

    if (endorsement_process == '0') {
        if (owner_type == '') {
            event.preventDefault();
            $('#ownertypeCheck').show();
        } else {
            $('#ownertypeCheck').hide();
        }
        if (vehicle_type == '') {
            event.preventDefault();
            $('#vehicletypeCheck').show();
        } else {
            $('#vehicletypeCheck').hide();
        }
        if (vehicle_process == '') {
            event.preventDefault();
            $('#vehicleprocessCheck').show();
        } else {
            $('#vehicleprocessCheck').hide();
        }
        if (en_Company == '') {
            event.preventDefault();
            $('#enCompanyCheck').show();
        } else {
            $('#enCompanyCheck').hide();
        }
        if (en_Model == '') {
            event.preventDefault();
            $('#enModelCheck').show();
        } else {
            $('#enModelCheck').hide();
        }
    }

    submit_btn.removeAttr('disabled');

}
//////////////////////////////////////////////////// Documentation  END////////////////////////////////////////


//////////////////////////////////////////////////////////////////// Loan Calculation Functions Start ///////////////////////////////////////////////////////////////////////////////
function onLoadEditFunction() {//On load for Loan Calculation edit
    verificationPerson(); //To Select verification Person in Verification Info.////// 
    // getLoanHistory();//to get loan history, as same as document history but here action buttons are changing
}

$('#loan_category').change(function () {
    var loan_cat = $(this).val();
    getSubCategory(loan_cat);
})

$('#refresh_cal').click(function () {
    $('.int-diff').text('*'); $('.due-diff').text('*')


    var profit_method = $('#profit_method').val(); // if profit method changes, due type is EMI
    if (profit_method == 'after_intrest') {
        getLoanAfterInterest(); changeInttoBen();
    } else if (profit_method == 'pre_intrest') {
        getLoanPreInterest(); changeInttoBen();
    }

    var due_type = $('#due_type').val(); //If Changes not found in profit method, calculate loan amt for monthly basis
    if (due_type == 'Interest') {
        getLoanInterest(); changeInttoBen();
    }

    var scheme_profit_method = $('#scheme_profit_method').val(); // if profit method changes, due type is EMI
    if (scheme_profit_method == 'after_intrest') {
        getSchemeAfterIntreset(); changeInttoBen();
    } else if (scheme_profit_method == 'pre_intrest') {
        getSchemePreIntreset(); changeInttoBen();
    }
    // var due_method_scheme = $('#due_method_scheme').val();
    // if (due_method_scheme == '1') {//Monthly scheme as 1
    //     getLoanMonthly(); changeInttoBen();
    // } else if (due_method_scheme == '2') {//Weekly scheme as 2
    //     getLoanWeekly(); changeInttoBen();
    // } else if (due_method_scheme == '3') {//Daily scheme as 3
    //     getLoanDaily(); changeInttoBen();
    // }

    function changeInttoBen() {
        let due_type = document.getElementById('due_type');
        let int_label = document.querySelector('#int_amt_cal');
        if (due_type.value == 'Interest') {
            // Set its value to 'Benefit Amount'
            int_label.previousElementSibling.previousElementSibling.textContent = 'Benefit Amount';
        } else {
            int_label.previousElementSibling.previousElementSibling.textContent = 'Interest Amount';
        }
    }
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

        var maturityDate = moment(due_start_from, 'YYYY-MM-DD').add(due_period, 'months').subtract(1, 'month').format('YYYY-MM-DD');//subract one month because by default its showing extra one month
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

$('#submit_loan_calculation').click(function () {
    $('#refresh_cal').trigger('click'); //For calculate once again if user missed to refresh calculation

    var submit_btn = $(this);
    submit_btn.attr('disabled', true);
    loan_calc_validation(submit_btn);
})


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
    $.ajax({
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
                        getSubCategory(response[i]['loan_category_id']);
                    }
                } else {
                    if (loan_category_upd != undefined && loan_category_upd != '' && loan_category_upd == response[i]['loan_category_id']) {
                        selected = 'selected';
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
    $.ajax({
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
                    }
                } else {
                    if (sub_categoryu_upd != undefined && sub_categoryu_upd != '' && sub_categoryu_upd == response[i]['sub_category_name']) {
                        selected = 'selected';
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
    var sub_cat = $('#sub_category_load').val();
    var loan_category = $('#loan_category_load').val();
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat,'loan_category':loan_category },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            category_info = ''
            $('#moduleTable').empty();
            $('#moduleTable').append('<tbody><tr>');
            if (response.length != 0) {
                var tb = 19;
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody tr').append(`<td><label for="disabledInput">` + response[i]['loan_category_ref_name'] + `</label><span class="required">&nbsp;*</span><input type="text" class="form-control" id="category_info" name="category_info[]" 
                    value='`+ category_info + `' tabindex='` + tb + `' required placeholder='Enter ` + response[i]['loan_category_ref_name'] + `'></td>`);
                    // tb++;
                }
                $('#moduleTable tbody tr').append(`<td><button type="button" tabindex='` + tb + `' id="add_category_info[]" name="add_category_info" 
                class="btn btn-primary add_category_info">Add</button> </td><td><span class='icon-trash-2 deleterow' id='deleterow' tabindex='`+ tb + `'></span></td>
                </tr></tbody></table>`);

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
            url: 'verificationFile/LoanCalculation/getCategoryInfo.php',
            data: { 'req_id': req_id, 'sub_category_upd': sub_category_upd },
            dataType: 'json',
            type: 'post',
            cachec: false,
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
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_cat },
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
                    console.log(category_content)
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
    let cus_id = $('#cus_id_load').val();
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
                $('#loan_amt').removeAttr('readonly');
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

//////////////////////// Verification history

//loan history table contents get from closed file loan lists
function getLoanHistory() {
    let cus_id = $('#cus_id_load').val(); let req_id = $('#req_id').val(); let cus_type = $('#cus_type').val();
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
            if (cus_type == 'Existing') {
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
            url: 'verificationFile/LoanCalculation/getLoanHistory.php',
            data: { 'req_id': req_id, 'cus_id': cus_id, 'pending_sts': pending_sts, 'od_sts': od_sts, 'due_nil_sts': due_nil_sts, 'closed_sts': closed_sts, 'bal_amt': bal_amt },
            type: 'post',
            cache: false,
            success: function (response) {
                $('#loanHistoryDiv').empty()
                $('#loanHistoryDiv').html(response);
            }
        }).then(function () {
            $('.due-chart').click(function () {
                var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid');
                dueChartList(req_id, cus_id);
            });
            $('.penalty-chart').click(function () {
                var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid');
                penaltyChartList(req_id, cus_id);
            })
            $('.collcharge-chart').click(function () {
                var req_id = $(this).data('reqid');
                collectionChargeChartList(req_id);
            })
            $('.loansummary-chart').click(function () {
                var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid');
                loanSummaryList(req_id, cus_id);
            })
            $('.commitment-chart').off('click').click(function () {//Commitment chart
                let req_id = $(this).data('reqid'); let cus_id = $(this).data('cusid');
                $.post('followupFiles/dueFollowup/getCommitmentChart.php', { cus_id, req_id }, function (html) {
                    $('#commChartDiv').empty().html(html);
                })
            })
        })
    })

}
//Due Chart List
function dueChartList(req_id, cus_id) {
    $.ajax({
        url: 'collectionFile/getDueChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#dueChartTableDiv').empty()
            $('#dueChartTableDiv').html(response)
        }
    }).then(function () {// print function
        $('.print_due_coll').off('click');
        $('.print_due_coll').click(function () {
            var id = $(this).attr('value');
            Swal.fire({
                title: 'Print',
                text: 'Do you want to print this collection?',
                // icon: 'question',
                // showConfirmButton: true,
                // confirmButtonColor: '#009688',
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

                            // Create a new window
                            var w = window.open();

                            // Write the content to the new window
                            $(w.document.body).html(content);

                            // Print the new window
                            w.print();

                            // Close the new window
                            w.close();
                        }
                    })
                }
            })
        })
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
//Loan Summary Chart List
function loanSummaryList(req_id, cus_id) {
    $.ajax({
        url: 'closedFile/loan_summary_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#loanSummaryDiv").empty();
            $("#loanSummaryDiv").html(html);
            // $('#feedback_table1').DataTable().destroy();
        }
    });
}

//////////////////////// Verification history END

//to fetch Calculation based inputs
function profitCalculationInfo() {
    var sub_cat = $('#sub_category_load').val();
    var profit_type = $('#profit_type').val();
    var due_method = $('#due_method_scheme').val();
    if (profit_type != '') { //Call only if profit type autamatically set
        profitCalAjax(profit_type, sub_cat); //Call for edit
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
        $('.scheme-calculation').hide();
        $('#int_rate').val(''); $('#int_rate').attr('readonly', false);
        $('#due_period').val(''); $('#due_period').attr('readonly', false);
        $('.min-max-int').text('*');
        $('.min-max-due').text('*');
        $('.min-max-doc').text('*');
        $('.min-max-proc').text('*');

        $('#due_start_from').val('');
        $('#maturity_month').val('');

        var profit_type = $(this).val();
        var sub_cat = $('#sub_category').val();
        profitCalAjax(profit_type, sub_cat)

    });//Profit Type change event end

    $('#due_method_scheme').change(function () {
        var due_method = $(this).val();
        if (due_method == '2') { // show weekdays only if weekly due method selected
            $('.day_scheme').show();
        } else {
            $('.day_scheme').hide();
        }
        $('.scheme-calculation').hide();
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
        $('.scheme-calculation').show(); // to show calculation inputs
        var scheme_id = $(this).val();
        schemeCalAjax(scheme_id);
    })
}

//
function profitCalAjax(profit_type, sub_cat) {
    $('.scheme-calculation').hide();
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
            data: { 'sub_cat': sub_cat },
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
                    $('#int_rate').attr('onChange', `if( parseFloat($(this).val()) > '` + response['intrest_rate_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseFloat($(this).val()) < '`+ response['intrest_rate_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#int_rate').val(int_rate_upd);
                    $('.min-max-due').text('* (' + response['due_period_min'] + ' - ' + response['due_period_max'] + ') ');
                    $('#due_period').attr('onChange', `if( parseInt($(this).val()) > '` + response['due_period_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['due_period_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#due_period').val(due_period_upd);
                    if (response['doc_charge_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-doc').text('* (' + type + response['document_charge_min'] + ' - ' + type + response['document_charge_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['doc_charge_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-doc').text('* (' + response['document_charge_min'] + type + ' - ' + response['document_charge_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }
                    
                    // Setting onChange event to ensure the value is within the specified range
                    $('#doc_charge').attr('onChange', `if( parseInt($(this).val()) > '` + response['document_charge_max'] + `' ){
                            alert("Enter Lesser Value");
                            $(this).val("");
                        } else if( parseInt($(this).val()) < '`+ response['document_charge_min'] + `' && parseInt($(this).val()) != '' ){
                            alert("Enter Higher Value");
                            $(this).val("");
                        }`);
                    
                    // Set the value for the doc_charge field
                    $('#doc_charge').val(doc_charge_upd);
                    
                    // $('.min-max-doc').text('* (' + response['document_charge_min'] + '% - ' + response['document_charge_max'] + '%) ');
                    // $('#doc_charge').attr('onChange', `if( parseFloat($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['document_charge_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#doc_charge').val(doc_charge_upd);
                    if (response['proc_fee_type'] == 'amt') {
                        type = '₹';
                        $('.min-max-proc').text('* (' + type + response['processing_fee_min'] + ' - ' + type + response['processing_fee_max'] + ') '); // Set min-max values with ₹ symbol before the numbers
                    } else if (response['proc_fee_type'] == 'percentage') {
                        type = '%';
                        $('.min-max-proc').text('* (' + response['processing_fee_min'] + type + ' - ' + response['processing_fee_max'] + type + ') '); // Set min-max values with % symbol after the numbers
                    }
                    
                    // Setting onChange event to ensure the value is within the specified range
                    $('#proc_fee').attr('onChange', `if( parseInt($(this).val()) > '` + response['processing_fee_max'] + `' ){
                            alert("Enter Lesser Value");
                            $(this).val("");
                        } else if( parseInt($(this).val()) < '`+ response['processing_fee_min'] + `' && parseInt($(this).val()) != '' ){
                            alert("Enter Higher Value");
                            $(this).val("");
                        }`);
                    
                    // Set the value for the doc_charge field
                    $('#proc_fee').val(proc_fee_upd);
                    
                    // $('.min-max-proc').text('* (' + response['processing_fee_min'] + '% - ' + response['processing_fee_max'] + '%) ');
                    // $('#proc_fee').attr('onChange', `if( parseFloat($(this).val()) > '` + response['processing_fee_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['processing_fee_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#proc_fee').val(proc_fee_upd);

                } else if (response['due_type'] == 'intrest') {

                    $('.emi-calculation').hide();
                    $('.interest-calculation').show();
                    $('#due_type').val('Interest');
                    $('#profit_method').empty();
                    $('#calc_method').val(response['calculate_method']);
                    //To set min and maximum 
                    $('.min-max-int').text('* (' + response['intrest_rate_min'] + '% - ' + response['intrest_rate_max'] + '%) ');
                    $('#int_rate').attr('onChange', `if( parseFloat($(this).val()) > '` + response['intrest_rate_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseFloat($(this).val()) < '`+ response['intrest_rate_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#int_rate').val(int_rate_upd);

                    $('.min-max-due').text('* (' + response['due_period_min'] + ' - ' + response['due_period_max'] + ') ');
                    $('#due_period').attr('onChange', `if( parseInt($(this).val()) > '` + response['due_period_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['due_period_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#due_period').val(due_period_upd);
                    if (response['doc_charge_type'] == 'amt') { type = '₹' } else if (response['doc_charge_type'] == 'percentage') { type = '%'; } //Setting symbols
                    $('.min-max-doc').text('* (' + response['document_charge_min'] + ' ' + type + ' - ' + response['document_charge_max'] + ' ' + type + ') '); //setting min max values in span
                    $('#doc_charge').attr('onChange', `if( parseInt($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                            if( parseInt($(this).val()) < '`+ response['document_charge_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#doc_charge').val(doc_charge_upd);
                    // $('.min-max-doc').text('* (' + response['document_charge_min'] + '% - ' + response['document_charge_max'] + '%) ');
                    // $('#doc_charge').attr('onChange', `if( parseFloat($(this).val()) > '` + response['document_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                    //                     if( parseFloat($(this).val()) < '`+ response['document_charge_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    // $('#doc_charge').val(doc_charge_upd);

                    $('.min-max-proc').text('* (' + response['processing_fee_min'] + '% - ' + response['processing_fee_max'] + '%) ');
                    $('#proc_fee').attr('onChange', `if( parseFloat($(this).val()) > '` + response['processing_fee_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseFloat($(this).val()) < '`+ response['processing_fee_min'] + `' && parseFloat($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                    $('#proc_fee').val(proc_fee_upd);
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
                $('#int_rate').attr('onChange', `if( parseInt($(this).val()) > '` + response['intreset_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['intreset_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                $('#int_rate').val(int_rate_upd);
                if (response['doc_charge_type'] == 'amt') { type = '₹' } else if (response['doc_charge_type'] == 'percentage') { type = '%'; } //Setting symbols
                $('.min-max-doc').text('* (' + response['doc_charge_min'] + ' ' + type + ' - ' + response['doc_charge_max'] + ' ' + type + ') '); //setting min max values in span
                $('#doc_charge').attr('onChange', `if( parseInt($(this).val()) > '` + response['doc_charge_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                        if( parseInt($(this).val()) < '`+ response['doc_charge_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
                $('#doc_charge').val(doc_charge_upd);

                if (response['proc_fee_type'] == 'amt') { type = '₹' } else if (response['proc_fee_type'] == 'percentage') { type = '%'; }//Setting symbols
                $('.min-max-proc').text('* (' + response['proc_fee_min'] + ' ' + type + ' - ' + response['proc_fee_max'] + ' ' + type + ') ');//setting min max values in span
                $('#proc_fee').attr('onChange', `if( parseInt($(this).val()) > '` + response['proc_fee_max'] + `' ){ alert("Enter Lesser Value"); $(this).val(""); }else
                                    if( parseInt($(this).val()) < '`+ response['proc_fee_min'] + `' && parseInt($(this).val()) != '' ){ alert("Enter Higher Value"); $(this).val(""); } `); //To check value between rage
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
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    $('#principal_amt_cal').val(parseInt(loan_amt).toFixed(0)); // principal amt as same as loan amt for after interest

    var interest_rate = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 

    // var roundedInterest = Math.ceil(interest_rate / 5) * 5; //to increase interest rate to nearest multiple of 5
    // if (roundedInterest < interest_rate) {
    //     roundedInterest += 5;
    // }

    // $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - interest_rate) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(interest_rate));

    var tot_amt = parseInt(loan_amt) + parseFloat(interest_rate); //Calculate total amount from principal/loan amt and interest rate
    $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////
    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;
    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - interest_rate) + ')'); //To show the difference amount from old to new
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(loan_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
}

//To Get Loan Calculation for Pre Interest
function getLoanPreInterest() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();
    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card


    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100) * parseInt(due_period)).toFixed(0); //Calculate interest rate 
    // $('#int_amt_cal').val(parseInt(int_amt));

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
    // $('#principal_amt_cal').val(parseInt(princ_amt).toFixed(0)); 

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
    // $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////

    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
}

//To Get Loan Calculation for Interest due type
function getLoanInterest() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    $('#principal_amt_cal').val(parseInt(loan_amt).toFixed(0));

    $('#tot_amt_cal').val('');
    $('#due_amt_cal').val('');//Due period will be monthly by default so no need of due amt

    var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 

    var roundedInterest = Math.ceil(int_amt / 5) * 5;
    if (roundedInterest < int_amt) {
        roundedInterest += 5;
    }
    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
    var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
    if (roundeddoccharge < doc_charge) {
        roundeddoccharge += 5;
    }
    $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    // $('.princ-diff').text('* (Difference: +' + parseInt(loan_amt - new_princ) + ')'); //To show the difference amount from old to new
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
}

//To Get Loan Calculation for Monthly Scheme method
function getSchemePreIntreset() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();

    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    } 
    // $('#int_amt_cal').val(parseInt(int_amt));

    var princ_amt = parseInt(loan_amt) - parseInt(int_amt); // Calculate principal amt by subracting interest amt from loan amt
    // $('#principal_amt_cal').val(princ_amt); 

    var tot_amt = parseInt(princ_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
    // $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////

    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - princ_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: ' + parseInt(new_princ - princ_amt) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text(); //Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(princ_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
}

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

//     $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
//     $('#int_amt_cal').val(parseInt(roundedInterest));

//     var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
//     // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
//     $('#principal_amt_cal').val(new_princ);

//     //////////////////////////////////////////////////////////////////////////////////

//     var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
//     if (doc_type.includes('₹')) {
//         var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
//     } else if (doc_type.includes('%')) {
//         var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
//     }
//     var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
//     if (roundeddoccharge < doc_charge) {
//         roundeddoccharge += 5;
//     }
//     $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
//     $('#doc_charge_cal').val(parseInt(roundeddoccharge));

//     var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
//     if (proc_type.includes('₹')) {
//         var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
//     } else if (proc_type.includes('%')) {
//         var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
//     }
//     var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
//     if (roundeprocfee < proc_fee) {
//         roundeprocfee += 5;
//     }
//     $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
//     $('#proc_fee_cal').val(parseInt(roundeprocfee));

//     var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
//     $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
// }

//To Get Loan Calculation for Weekly Scheme method
function getSchemeAfterIntreset() {
    var loan_amt = $('#loan_amt').val();
    var int_rate = $('#int_rate').val();
    var due_period = $('#due_period').val();
    var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val();
    $('#loan_amt_cal').val(parseInt(loan_amt).toFixed(0)); //get loan amt from loan info card
    $('#principal_amt_cal').val(parseInt(loan_amt).toFixed(0)); // principal amt as same as loan amt for after interest
    var intreset_type = $('.min-max-int').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
    if (intreset_type.includes('₹')) {
        var int_amt = parseInt(int_rate); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
    } else if (intreset_type.includes('%')) {
        var int_amt = (parseInt(loan_amt) * (parseFloat(int_rate) / 100)).toFixed(0); //Calculate interest rate 
    } 
    // var roundedInterest = Math.ceil(int_amt / 5) * 5;
    // if (roundedInterest < int_amt) {
    //     roundedInterest += 5;
    // }
    // $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    // $('#int_amt_cal').val(parseInt(int_amt));

    var tot_amt = parseInt(loan_amt) + parseFloat(int_amt); //Calculate total amount from principal/loan amt and interest rate
    $('#tot_amt_cal').val(parseInt(tot_amt).toFixed(0));

    var due_amt = parseInt(tot_amt) / parseInt(due_period);//To calculate due amt by dividing total amount and due period given on loan info
    var roundDue = Math.ceil(due_amt / 5) * 5; //to increase Due Amt to nearest multiple of 5
    if (roundDue < due_amt) {
        roundDue += 5;
    }
    $('.due-diff').text('* (Difference: +' + parseInt(roundDue - due_amt) + ')'); //To show the difference amount
    $('#due_amt_cal').val(parseInt(roundDue).toFixed(0));

    ////////////////////recalculation of total, principal, interest///////////////////

    var new_tot = parseInt(roundDue) * due_period;
    $('#tot_amt_cal').val(new_tot)

    //to get new interest rate using round due amt 
    let new_int = (roundDue * due_period) - loan_amt;

    var roundedInterest = Math.ceil(new_int / 5) * 5;
    if (roundedInterest < new_int) {
        roundedInterest += 5;
    }

    $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
    $('#int_amt_cal').val(parseInt(roundedInterest));

    var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
    // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
    $('#principal_amt_cal').val(new_princ);

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
    $('#doc_charge_cal').val(parseInt(roundeddoccharge));

    var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
    if (proc_type.includes('₹')) {
        var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
    } else if (proc_type.includes('%')) {
        var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
    }
    var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
    if (roundeprocfee < proc_fee) {
        roundeprocfee += 5;
    }
    $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
    $('#proc_fee_cal').val(parseInt(roundeprocfee));

    var net_cash = parseInt(loan_amt) - parseFloat(roundeddoccharge) - parseFloat(roundeprocfee); //Net cash will be calculated by subracting other charges
    $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
}
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

//     $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')'); //To show the difference amount
//     $('#int_amt_cal').val(parseInt(roundedInterest));

//     var new_princ = parseInt(new_tot) - parseInt(roundedInterest);
//     // $('.princ-diff').text('* (Difference: +' + parseInt(princ_amt - new_princ) + ')'); //To show the difference amount from old to new
//     $('#principal_amt_cal').val(new_princ);

//     //////////////////////////////////////////////////////////////////////////////////

//     var doc_type = $('.min-max-doc').text(); //Scheme may have document charge in rupees or percentage . so getting symbol from span
//     if (doc_type.includes('₹')) {
//         var doc_charge = parseInt(doc_charge); //Get document charge from loan info and directly show the document charge provided because of it is in rupees
//     } else if (doc_type.includes('%')) {
//         var doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100); //Get document charge from loan info and multiply with loan amt to get actual doc charge
//     }
//     var roundeddoccharge = Math.ceil(doc_charge / 5) * 5; //to increase document charge to nearest multiple of 5
//     if (roundeddoccharge < doc_charge) {
//         roundeddoccharge += 5;
//     }
//     $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')'); //To show the difference amount from old to new
//     $('#doc_charge_cal').val(parseInt(roundeddoccharge));

//     var proc_type = $('.min-max-proc').text();//Scheme may have Processing fee in rupees or percentage . so getting symbol from span
//     if (proc_type.includes('₹')) {
//         var proc_fee = parseInt(proc_fee);//Get processing fee from loan info and directly show the Processing Fee provided because of it is in rupees
//     } else if (proc_type.includes('%')) {
//         var proc_fee = parseInt(loan_amt) * (parseInt(proc_fee) / 100);//Get processing fee from loan info and multiply with loan amt to get actual proc fee
//     }
//     var roundeprocfee = Math.ceil(proc_fee / 5) * 5; //to increase Processing fee to nearest multiple of 5
//     if (roundeprocfee < proc_fee) {
//         roundeprocfee += 5;
//     }
//     $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')'); //To show the difference amount from old to new
//     $('#proc_fee_cal').val(parseInt(roundeprocfee));

//     var net_cash = parseInt(princ_amt) - parseInt(doc_charge) - parseInt(proc_fee); //Net cash will be calculated by subracting other charges
//     $('#net_cash_cal').val(parseInt(net_cash).toFixed(0));
// }

//Validation for Loan calculation
function loan_calc_validation(submit_btn) {
    var cus_id_loan = $('#cus_id_loan').val(); //if this is empty means , customer profile is not submitted yet
    var loan_category = $('#loan_category').val(); var sub_category = $('#sub_category').val(); var tot_value = $('#tot_value').val(); var ad_amt = $('#ad_amt').val();
    var loan_amt = $('#loan_amt').val(); var due_type = $('#due_type').val();
    var profit_type = $('#profit_type').val(); var due_method_scheme = $('#due_method_scheme').val();var scheme_profit_method = $('#scheme_profit_method').val(); var day_scheme = $('#day_scheme').val(); var scheme_name = $('#scheme_name').val();
    var profit_method = $('#profit_method').val(); var int_rate = $('#int_rate').val(); var due_period = $('#due_period').val(); var doc_charge = $('#doc_charge').val();
    var proc_fee = $('#proc_fee').val(); var loan_amt_cal = $('#loan_amt_cal').val(); var principal_amt_cal = $('#principal_amt_cal').val(); var int_amt_cal = $('#int_amt_cal').val();
    var tot_amt_cal = $('#tot_amt_cal').val(); var due_amt_cal = $('#due_amt_cal').val(); var doc_charge_cal = $('#doc_charge_cal').val(); var proc_fee_cal = $('#proc_fee_cal').val();
    var net_cash_cal = $('#net_cash_cal').val(); var due_start_from = $('#due_start_from').val(); var maturity_month = $('#maturity_month').val(); var collection_method = $('#collection_method').val();
    var Communitcation_to_cus = $('#Communitcation_to_cus').val(); var verification_location = $('#verification_location').val();

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


    if (Communitcation_to_cus == '') {
        event.preventDefault();
        $('#communicationCheck').show();
    } else {
        $('#communicationCheck').hide();
    }
    if (person_list.length == 0) {
        event.preventDefault();
        $('#verificationPersonCheck').show();
    } else {
        $('#verificationPersonCheck').hide();
    }
    if (verification_location == '') {
        event.preventDefault();
        $('#verificationLocCheck').show();
    } else {
        $('#verificationLocCheck').hide();
    }

    if (cus_id_loan == '') {
        Swal.fire({
            timerProgressBar: true,
            timer: 2000,
            title: 'Please Complete Customer Profile!',
            icon: 'error',
            showConfirmButton: true,
            confirmButtonColor: '#009688'
        });
        event.preventDefault();
    }

    if (loan_category == '') {
        $('#loancategoryCheck').show();
        event.preventDefault();
    } else {
        $('#loancategoryCheck').hide();
    }

    if (sub_category == '') {
        $('#subcategoryCheck').show();
        event.preventDefault();
    } else {
        $('#subcategoryCheck').hide();
    }

    if (tot_value == '' && $('.advance_yes').css('display') != "none") {
        $('#total_valueCheck').show();
        event.preventDefault();
    } else {
        $('#total_valueCheck').hide();
    }

    if (ad_amt == '' && $('.advance_yes').css('display') != "none") {
        $('#ad_amtCheck').show();
        event.preventDefault();
    } else {
        $('#ad_amtCheck').hide();
    }

    if (loan_amt == '') {
        $('#loan_amtCheck').show();
        event.preventDefault();
    } else {
        $('#loan_amtCheck').hide();
    }

    if (profit_type == '') {
        $('#profit_typeCheck').show();
        event.preventDefault();
    } else {
        $('#profit_typeCheck').hide();
    }

    if (profit_method == '' && due_type == 'EMI') {
        $('#profit_methodCheck').show();
        event.preventDefault();
    } else {
        $('#profit_methodCheck').hide();
    }

    if (due_method_scheme == '' && $('.scheme').css('display') != 'none') {
        $('#due_method_schemeCheck').show();
        event.preventDefault();
    } else {
        $('#due_method_schemeCheck').hide();
    }
    if (scheme_profit_method == '' && $('.scheme-calculation').css('display') != 'none') {
        $('#scheme_methodCheck').show();
        event.preventDefault();
    } else {
        $('#scheme_methodCheck').hide();
    }
    if (day_scheme == '' && $('.day_scheme').css('display') != 'none') {
        $('#day_schemeCheck').show();
        event.preventDefault();
    } else {
        $('#day_schemeCheck').hide();
    }

    if (scheme_name == '' && $('.scheme').css('display') != 'none') {
        $('#scheme_nameCheck').show();
        event.preventDefault();
    } else {
        $('#scheme_nameCheck').hide();
    }

    if (int_rate == '') {
        $('#int_rateCheck').show();
        event.preventDefault();
    } else {
        $('#int_rateCheck').hide();
    }

    if (due_period == '') {
        $('#due_periodCheck').show();
        event.preventDefault();
    } else {
        $('#due_periodCheck').hide();
    }

    if (doc_charge == '') {
        $('#doc_chargeCheck').show();
        event.preventDefault();
    } else {
        $('#doc_chargeCheck').hide();
    }

    if (proc_fee == '') {
        $('#proc_feeCheck').show();
        event.preventDefault();
    } else {
        $('#proc_feeCheck').hide();
    }

    if (due_start_from == '') {
        $('#due_start_fromCheck').show();
        event.preventDefault();
    } else {
        $('#due_start_fromCheck').hide();
    }

    if (maturity_month == '') {
        $('#maturity_monthCheck').show();
        event.preventDefault();
    } else {
        $('#maturity_monthCheck').hide();
    }

    if (collection_method == '') {
        $('#collection_methodCheck').show();
        event.preventDefault();
    } else {
        $('#collection_methodCheck').hide();
    }


    submit_btn.removeAttr('disabled');

}

//////////////////////////////////////////////////////////////////// Loan Calculation Functions End ///////////////////////////////////////////////////////////////////////////////