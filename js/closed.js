$(document).ready(function () {
    /// noc_req_id = get particular line item request id becuase multiple request show in list against single customer.. the Customer is same but request is not so have to take particular req id to show details.
    $('#submit_closed').click(function (event) {
        if(validations()){
            let confirmAction = confirm("Are you sure you want to submit Closed ?");
            if (!confirmAction) {
                event.preventDefault(); // Stop form submission if canceled
                return false;
            }
        }else{
            event.preventDefault(); 
            scrollToFirstError('#cus_Profiles');
            return false;
        }
    });

    //closed status
    $('#closed_Sts').change(function () {
        var sts = $(this).val();
        if (sts == '1') {
            $('#considerlevel').show();
        } else {
            $('#considerlevel').hide();
        }
    });

    $('.commitment-chart').click(function () {//Commitment chart
        let req_id = $('#noc_req_id').val(); let cus_id = $('#cusidupd').val();
        $.post('followupFiles/dueFollowup/getCommitmentChart.php', { cus_id, req_id }, function (html) {
            $('#commChartDiv').empty().html(html);
        })
    });

    $(document).on("click", "#feedbackBtn", function () {
        let cus_id = $('#cus_id').val();
        let feedback_label = $("#feedback_label").val();
        let cus_feedback_dept = $("#cus_feedback_department").val();
        let cus_feedback = $("#cus_feedback").val();
        let files = $("#customer_summary_uploads")[0].files;
        let cus_summary_upload = $("#cus_summary_upload").val();
        let feedback_remark = $("#feedback_remark").val();
        let feedbackID = $("#feedbackID").val();


        if (feedback_label != "" && cus_feedback_dept !="" && cus_feedback != "" && cus_id != "") {
            // Using FormData to send file and other data
            let formData = new FormData();
            formData.append("cus_id", cus_id);
            formData.append("feedback_label", feedback_label);
            formData.append("cus_feedback", cus_feedback);
            formData.append("cus_feedback_dept", cus_feedback_dept);

            for (let i = 0; i < files.length; i++) {
                formData.append("customer_summary_uploads[]", files[i]);
            } // Append the file
            
            formData.append("cus_summary_upload", cus_summary_upload); //edit value.
            formData.append("feedback_remark", feedback_remark); 
            formData.append("feedbackID", feedbackID);

            $.ajax({
                url: 'updateFile/update_cus_feedback_submit.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false, // Important: Do not process contentType
                processData: false, // Important: Do not process data
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

            $('#feedbacklabelCheck, #departmentCheck, #feedbackCheck').hide();

        } else {

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
            
            if (cus_feedback_dept == "") {
                $('#departmentCheck').show();
            } else {
                $('#departmentCheck').hide();
            }
        }
    });

    $("body").on("click", "#cus_feedback_edit", function () {
        let id = $(this).attr('value');

        $.ajax({
            url: 'verificationFile/customer_feedback_edit.php',
            type: 'POST',
            data: { id },
            dataType: 'json',
            cache: false,
            success: function (result) {

                $("#feedbackID").val(result['id']);
                $("#feedback_label").val(result['feedback_label']);
                $("#cus_feedback_department").val(result["cus_feedback_dept"]);
                $("#cus_feedback").val(result['cus_feedback']);
                $("#feedback_remark").val(result['feedback_remark']);
                $("#cus_summary_upload").val(result["upload"]);

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
                data: { id },
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
  });

  $(document).on("click", "#add_cus_feedback", function () {
    cusfeedbacklist();
    $("#feedbackname, #fedbackname_id").val('');
  });

  $(document).on("click", "#submit_feedback_lable", function () {
    submitfeedbackname();
  });

    $(document).on('click', '.noc-window', function (event) {
        let req_id = $(this).data('value');

        checkDocumentsStatus(req_id, (result) => {
            if (result === 'completed') {//this function will check if the particular loan is completed all the document upload
                $('.loanlist_card, .customersummary_card, .back-button').hide();
                $('.noc_window, #close_noc_card, #submit_closed').show();

                $('#noc_req_id').val(req_id);

            } else {//else prevent closing the document due to not completing documents
                event.preventDefault();
                alert('Please complete pending documents to Close!');
            }
        });
    });

    $(document).on('click', '#close_noc_card', function (event) {
        $('.loanlist_card, .customersummary_card, .back-button').show();
        $('.noc_window, #close_noc_card, #submit_closed').hide();

        $('#closedStatusCheck, #considerLevelCheck, #remarkCheck').hide();
        $('#closed_Sts, #closed_Sts_consider, #closed_Sts_remark').val('');
    });

    $(document).on('click', '.due-chart', function (event) {
        var nocreq_id = $('#noc_req_id').val();
        dueChartList(nocreq_id);
    });

    $(document).on("click", ".print_due_coll", function () {
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
                        $('#printcollection').html(html);
                    }
                })
            }
        });
    });

    $(document).on('click', '.penalty-chart', function (event) {
        var noc_req_id = $('#noc_req_id').val();
        $.ajax({
            //to insert penalty by on click
            url: 'collectionFile/getLoanDetails.php',
            data: { 'req_id': noc_req_id },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                penaltyChartList(noc_req_id); //To show Penalty List.
            }
        })
    });

    $(document).on('click', '.coll-charge-chart', function (event) {
        var noc_req_id = $('#noc_req_id').val();
        collectionChargeChartList(noc_req_id) //To Show Fine Chart List
    });

    $(document).on('click', '.coll-charge', function (event) {
        var noc_req_id = $('#noc_req_id').val();
        resetcollCharges(noc_req_id);  //Fine
    });
    
})//Document Ready End

//On Load Event
$(function () {
    $('.noc_window').hide(); //Hide collection window at the starting
    $('#close_noc_card').hide();//Hide collection close button at the starting
    $('#submit_closed').hide();//Hide Submit button at the starting, because submit is only for collection

    const cus_id = $('#cusidupd').val();
    OnLoadFunctions(cus_id);

    var cus_pic = $('#cuspicupd').val();
    $('#imgshow').attr('src', 'uploads/request/customer/' + cus_pic);
});

function OnLoadFunctions(cus_id) {
    $.ajax({
        //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
        url: 'closedFile/getLoanListForClosed.php',
        data: { cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#loanListTableDiv').html(response);
            getCustomerLoanCounts(); // to get customer summary details
        }
    });
}//Auto Load function END

function getCustomerLoanCounts() {
    let cus_id = $('#cusidupd').val()
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
        }
    }).then(() => {
        getCustomerSummary();//to get income details
    });
}

function getCustomerSummary() {
    let cus_id = $('#cusidupd').val()
    $.ajax({
        url: 'closedFile/getCustomerSummary.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#cus_how_know').val(response['how_to_know'])
            $('#cus_monthly_income').val(moneyFormatIndia(response['monthly_income']))
            $('#income_date').val(moneyFormatIndia(response['income_date']))
            $('#cus_other_income').val(moneyFormatIndia(response['other_income']))
            $('#cus_support_income').val(moneyFormatIndia(response['support_income']))
            $('#cus_Commitment').val(moneyFormatIndia(response['commitment']))
            $('#cus_monDue_capacity').val(moneyFormatIndia(response['monthly_due_capacity']))
            $('#cus_loan_limit').val(moneyFormatIndia(response['loan_limit']))
            $('#about_cus').val(response['about_customer'])
        }
    }).then(function () {
        feedbackList();
    });
}

function validations() {
    var closed_Sts = $('#closed_Sts').val(); var closed_Sts_consider = $('#closed_Sts_consider').val(); var closed_Sts_remark = $('#closed_Sts_remark').val();
    var validation = true ;

    if (closed_Sts == '') {
        $('#closedStatusCheck').show();
        event.preventDefault();
        validation = false ;
    } else {
        $('#closedStatusCheck').hide();
    }

    if (closed_Sts == '1') {
        if (closed_Sts_consider == '') {
            $('#considerLevelCheck').show();
            event.preventDefault();
            validation = false ;
        } else {
            $('#considerLevelCheck').hide();
        }
    }

    if (closed_Sts_remark == '') {
        $('#remarkCheck').show();
        event.preventDefault();
        validation = false ;
    } else {
        $('#remarkCheck').hide();
    }
    return validation;
}

//Due Chart List
function dueChartList(req_id) {
    $('#dueChartTableDiv').empty()
    $.ajax({
        url: 'collectionFile/getDueChartList.php',
        data: { req_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#dueChartTableDiv').html(response);
        }
    }).then(function () {

        $.post('collectionFile/getDueMethodName.php', { req_id }, function (response) {
            $('#dueChartTitle').text(`Due Chart ( Aadhaar Number : ${response.cus_id} | Cus ID : ${response.autogen_cus_id}  | Cus Name : ${response.cus_name}  | Loan ID : ${response.loan_id}  | Loan Category : ${response.loan_category} )`);
        }, 'json');
    });
}

//Penalty Chart List
function penaltyChartList(req_id) {
    $.ajax({
        url: 'collectionFile/getPenaltyChartList.php',
        data: { req_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#penaltyChartTableDiv').html(response)
        }
    });//Ajax End.
}

//Collection Charge Chart List
function collectionChargeChartList(noc_req_id) {
    $.ajax({
        url: 'collectionFile/getCollectionChargeList.php',
        data: { 'req_id': noc_req_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collectionChargeDiv').html(response)
        }
    });//Ajax End.
}

function checkDocumentsStatus(req_id, callback) {
    let val;
    $.post('closedFile/checkDocumentsStatus.php', { req_id }, (response) => {
        if (response == true) {
            val = 'completed';
        } else {
            val = 'pending';
        }
        callback(val);
    })
}

//Customer Feedback Modal 
function resetfeedback() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/customer_feedback_reset.php',
        type: 'POST',
        data: { cus_id },
        cache: false,
        success: function (html) {
            $("#feedbackTable").html(html);
            $("#feedback_label, #cus_feedback_department, #cus_feedback, #feedback_remark, #feedbackID, #customer_summary_uploads").val('');
        }
    });
}

function feedbackList() {
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'verificationFile/customer_feedback_list.php',
        type: 'POST',
        data: { cus_id },
        cache: false,
        success: function (html) {
            $("#feedbackListTable").html(html);
        }
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
      data: { feedbackname, id },
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
        $("#feedbackname, #fedbackname_id").val('');
        cusfeedbacklist();
      },
    });

  }
}
//Customer Feedback Modal End