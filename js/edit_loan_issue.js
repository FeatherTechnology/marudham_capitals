const branchChoices = new Choices('#branch_filter', {
    removeItemButton: true,
    noChoicesText: 'No branches available',
    allowHTML: true,
});
const sectorChoices = new Choices('#sector_filter', {
    removeItemButton: true,
    noChoicesText: 'No sector available',
    allowHTML: true,
});
//Loan Category Multi select initialization
const loan_category = new Choices('#loan_cat_filter', {
    removeItemButton: true,
    noChoicesText: 'Select Loan Category',
    allowHTML: true
});
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

    $(document).on('click', 'a.customer-summary', function () {
        var cus_id = $(this).data('value');
        getCustomerSummary(cus_id);
    });

    $(document).on('click', '.complete_issue', function () {
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
                        successSwal(result.response, `<p style="font-size: 20px;"> The Loan ID is: <b>${result.loanid}</b><br> The Doc ID is: <b>${result.docid}</b> </p>`);
                    } else {
                        warningSwal('Error', result.message);
                    }
                }
            })
        }
    });

    $(document).on('click', '.iss-remove', function (event) {
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
                        successSwal('Success', response);
                    }else if (response.includes('Error')) {
                        warningSwal('Error', response);
                    }
                }
            })
        }
    });

    $('#search_loan').on('click', function () {

        let branch = $("#branch_filter").val();
        let sector = $("#sector_filter").val();
        let loan_cat = $("#loan_cat_filter").val();

        if ((!branch || branch.length === 0) && (!sector || sector.length === 0) && (!loan_cat || loan_cat.length === 0)) {
            swalError('Warning', 'Please select at least one filter');
            return;
        }

        $('#loanIssue_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();

        getSectorDropdown('common', branch);
    });
    // load each dropdown only when the user actually opens/clicks it.
    let branchLoaded = false;
    let sectorLoaded = false;
    let loanCatLoaded = false;

    branchChoices.passedElement.element.addEventListener('showDropdown', function () {
        if (!branchLoaded) {
            branchLoaded = true;
            getBranchDropdown();
        }
    });

    sectorChoices.passedElement.element.addEventListener('showDropdown', function () {
        if (!sectorLoaded) {
            sectorLoaded = true;
            getSectorDropdown('common');
        }
    });

    loan_category.passedElement.element.addEventListener('showDropdown', function () {
        if (!loanCatLoaded) {
            loanCatLoaded = true;
            getLoanCatName('common');
        }
    });

});//document ready end

$(function () {
    loadNotifications();
})

function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        showConfirmButton: true,
        confirmButtonColor: '#009688', // warning color (orange/yellow)
        confirmButtonText: 'OK'
    });
}

function successSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'success',
        showConfirmButton: true,
        confirmButtonColor: '#009688', // your success green
        confirmButtonText: 'OK'
    }).then((result) => {
        // Reload only if OK is clicked
        if (result.isConfirmed) {
            location.reload();
        }
    });
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
            $('#income_date').val(response.income_date)
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
            $("#feedback_label, #cus_feedback_department, #cus_feedback, #feedback_remark, #feedbackID, #customer_summary_uploads").val('');
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

function getBranchDropdown() {
    $.post('common_files/user_mapped_branches.php', {}, function (response) {
        branchChoices.clearStore();
        $.each(response, function (index, val) {
            let items = [
                {
                    value: val.branch_id,
                    label: val.branch_name,
                }
            ];
            branchChoices.setChoices(items); // Add choices

        });
    }, 'json');
}

function getSectorDropdown(module, branch = []) {
    sectorChoices.clearStore();
    $.ajax({
        url: 'common_files/get_sector_name.php',
        type: 'POST',
        data: {
            module: module,
            branch: branch
        },
        dataType: 'json',
        success: function (response) {

            let items = [];

            $.each(response, function (i, val) {
                items.push({
                    value: val.id,
                    label: val.name
                });
            });

            sectorChoices.setChoices(items, 'value', 'label', true);
        }
    });
}


function getLoanCatName(module) {
    $.post('common_files/get_loan_category.php',{ module: module },function (response) {
            loan_category.clearStore();
            let items = [];
            $.each(response, function (index, val) {
                items.push({
                    value: val.loan_category_creation_id,
                    label: val.loan_category_creation_name,
                });
            });
            loan_category.setChoices(items, 'value', 'label', true);
        },
        'json'
    );
}