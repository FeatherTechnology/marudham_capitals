
// Document is ready
$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });

    $(document).on("click", "#submit_feedback_lable", function () {
        submitfeedbackname();
    });

    $(document).on("click", ".closeCusModal", function () {
        $('#summary_cus_id').val('');
        setTimeout(() => {
            $('#cus_summary_div').show();
            $('#cus_feedback_div, #feedback_label_div').hide();
        }, 1000);
    });

    $(document).on("click", "#add_cus_label", function (event) {
        event.preventDefault();
        $('#cus_summary_div, #feedback_label_div').hide();
        $('#cus_feedback_div').show();
    });

    $(document).on("click", "#close_cus_label", function (event) {
        event.preventDefault();
        $('#cus_summary_div').show();
        $('#cus_feedback_div, #feedback_label_div').hide();
    });

    $(document).on("click", "#add_cus_feedback", function (event) {
        event.preventDefault();
        $('#cus_summary_div, #cus_feedback_div').hide();
        $('#feedback_label_div').show();
    });

    $(document).on("click", "#close_feedback_label", function (event) {
        event.preventDefault();
        $('#cus_summary_div, #feedback_label_div').hide();
        $('#cus_feedback_div').show();
    });

    $(document).on("click", "#feedbackBtn", function () {
        let cus_id = $('#summary_cus_id').val();
        let feedback_label = $("#feedback_label").val();
        let cus_feedback = $("#cus_feedback").val();
        let feedback_remark = $("#feedback_remark").val();
        let feedbackID = $("#feedbackID").val();


        if (feedback_label != "" && cus_feedback != "" && cus_id != "") {
            $.ajax({
                url: 'updateFile/update_cus_feedback_submit.php',
                type: 'POST',
                data: { feedback_label, cus_feedback, feedback_remark, feedbackID, cus_id },
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

            $('#feedbacklabelCheck, #feedbackCheck').hide();

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

});//document ready end


function callOnClickEvents() {

    showOverlay();//loader start
    setTimeout(() => {
        console.log('Called on click function')

        $('a.customer-status').click(function () {
            var cus_id = $(this).data('value');
            var req_id = $(this).data('value1');
            callresetCustomerStatus(cus_id);//this function will give the customer's status like pending od current
            showOverlay();//loader start
            setTimeout(() => {
                //take all the values from the function then send to customer status file to fetch details
                var pending_sts = $('#pending_sts').val(); var od_sts = $('#od_sts').val(); var due_nil_sts = $('#due_nil_sts').val(); var closed_sts = $('#closed_sts').val()
                $.ajax({
                    url: 'requestFile/getCustomerStatus.php',
                    data: { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts },
                    // dataType: 'json',
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#cusHistoryTable').empty();
                        $('#cusHistoryTable').html(response);
                        $('#cusHistoryTable tbody tr').each(function () {
                            var val = $(this).find('td:nth-child(6)').html();
                            if (['Request', 'Verification', 'Approval', 'Acknowledgement', 'Issue'].includes(val)) {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(240, 0, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            } else if (val == 'Present') {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 160, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            } else if (val == 'Closed') {
                                $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 0, 255, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                            }

                        });
                    }
                })
                hideOverlay();
            }, 1000);
        });

        $('a.customer-summary').click(function () {
            var cus_id = $(this).data('value');
            getCustomerSummary(cus_id);
        });

        $('a.loan-summary').click(function () {
            var cus_id = $(this).data('value');
            var req_id = $(this).data('value1');
            $.ajax({
                url: 'requestFile/getLoanSummary.php',
                data: { "cus_id": cus_id, "req_id": req_id },
                // dataType: 'json',
                type: 'post',
                cache: false,
                success: function (response) {
                    $('#loanSummaryTable').empty();
                    $('#loanSummaryTable').html(response);
                }
            })
        });
        $('.complete_issue').click(function () {
            var req_id = $(this).val();
            if (confirm('Do You want to Complete this Issue?')) {
                $.ajax({
                    // url: 'verificationFile/sendToIssue.php',
                    url: 'loanIssueFile/sendToCollection.php',
                    dataType: 'json',
                    type: 'post',
                    data: { 'req_id': req_id },
                    cache: false,
                    success: function (result) {
                        if (result.response.includes('Completed')) {
                            Swal.fire({
                                title: result.response,
                                html: `<p style="font-size: 20px;"> The Loan ID is: <b>${result.loanid}</b><br> The Doc ID is: <b>${result.docid}</b> </p>`,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688',
                                confirmButtonText: 'OK'
                            }).then((swalResult) => {
                                // Redirect only if user clicks OK
                                if (swalResult.isConfirmed) {
                                    window.location = 'edit_loan_issue';
                                }
                            });
                        }
                    }
                })
            }
        });

        $('.iss-remove').click(function () {
            event.preventDefault();
            let req_id = $(this).data('value');
            if (confirm('Do you want to Remove this Issue From the List?')) {
                $.ajax({
                    url: 'loanIssueFile/removeIssue.php',
                    dataType: 'json',
                    type: 'post',
                    data: { 'req_id': req_id },
                    cache: false,
                    success: function (response) {
                        if (response.includes('Removed')) {
                            Swal.fire({
                                title: response,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                // Redirect only if OK is clicked
                                if (result.isConfirmed) {
                                    window.location = 'edit_loan_issue';
                                }
                            });
                        }
                        else if (response.includes('Error')) {
                            Swal.fire({
                                timerProgressBar: true,
                                timer: 2000,
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

        hideOverlay();
    }, 1000);
}

function callresetCustomerStatus(cus_id) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];
    $.ajax({
        url: 'collectionFile/resetCustomerStatus.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {
                let pendingCnt = (response['pending_customer']) ? response['pending_customer'].length : 0;
                for (var i = 0; i < pendingCnt; i++) {
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
            };
        }
    });
}

function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
        allowOutsideClick: false
    });
}

function successSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'success',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
        allowOutsideClick: false
    })
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function getCustomerSummary(cus_id) {
    $.ajax({
        url: 'closedFile/getCustomerSummary.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#summaryTitle').text(`Customer Summary ( Aadhaar Number : ${response.cus_id} | Cus ID : ${response.autogen_cus_id}  | Cus Name : ${response.customer_name} )`);

            $('#summary_cus_id').val(response.cus_id)
            $('#cus_how_know').val(response.how_to_know)
            $('#cus_loan_count').val(response.loan_count)
            $('#cus_frst_loanDate').val(response.first_loan)
            $('#cus_travel_cmpy').val(response.travel)
            $('#cus_monthly_income').val(moneyFormatIndia(response.monthly_income))
            $('#cus_other_income').val(moneyFormatIndia(response.other_income))
            $('#cus_support_income').val(moneyFormatIndia(response.support_income))
            $('#cus_Commitment').val(moneyFormatIndia(response.commitment))
            $('#cus_monDue_capacity').val(moneyFormatIndia(response.monthly_due_capacity))
            $('#cus_loan_limit').val(moneyFormatIndia(response.loan_limit))
            $('#about_cus').val(response.about_customer)
        }
    }).then(function () {
        feedbackList();
    });;
}

function feedbackList() {
    let cus_id = $('#summary_cus_id').val();
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

//Customer Feedback Modal 
function resetfeedback() {
    let cus_id = $('#summary_cus_id').val();
    $.ajax({
        url: 'verificationFile/customer_feedback_reset.php',
        type: 'POST',
        data: { cus_id },
        cache: false,
        success: function (html) {
            $("#feedbackTable").html(html);
            $("#feedback_label, #cus_feedback, #feedback_remark, #feedbackID").val('');
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
      $("#feedbackname, #fedbackname_id").val('');
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
        cusfeedbacklist();
      },
    });
  }
}
//Customer Feedback Modal End