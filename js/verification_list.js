// Branch Multi select initialization
const branchChoices = new Choices('#branch_filter', {
    removeItemButton: true,
    noChoicesText: 'No branches available',
    allowHTML: true,
});
// Sector Multi select initialization
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

$(document).ready(function () {
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });

    // Verification list Actions
    $(document).on("click", '.cancelverification', function () {
        var remark = prompt("Do you want to Cancel this Verification?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'verification' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Cancelled!', 'Verification has been Cancelled.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on("click", '.revokeverification', function () {
        var remark = prompt("Do you want to Revoke this Verification?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'revoke', remark, screen: 'verification' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Revoked!', 'Verification has been Revoked.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on('click', '.move_approval', function () {
        var req_id = $(this).val();
        if (confirm('Do You want to Send this for Approval?')) {
            $.ajax({
                url: 'verificationFile/sendToApproval.php',
                dataType: 'json',
                type: 'post',
                data: { 'req_id': req_id, 'cus_id': $(this).data('cusid') },
                cache: false,
                success: function (response) {
                    if (response.includes('Moved')) {
                        Swal.fire({
                            title: response,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect only when OK is clicked
                                window.location = 'verification_list';
                            }
                        });
                    }
                }
            })
        }
    });

    //Request info tab
    $(document).on('click', '.request-info', function () {
        let req_id = $(this).data('reqid');
        window.open('request&upd=' + req_id + '&pgeView=1', '_blank');
    });

    $(document).on('click', '#sumit_add_lfollow', function () {
        if (validateLoanfollowup() == true) {
            submitLoanfollowup();
        }
    });

    $(document).on('click', '.loan-follow-edit', function () {
        let stage = $(this).data('stage');
        $('#lfollow_stage').val(stage);
        //set cus id to hidden input for submit
        let cus_id = $(this).data('cusid');
        $('#lfollow_cus_id').val(cus_id);
    });

    $("#addLoanFollow").find(".closeModal").click(function () {
        $('#addLoanFollow').find('.modal-body input').not('[readonly]').val('');
        $("#addLoanFollow").find(".modal-body span").not('.required').hide();
    });

    // Search Button Click Event
    $('#search_loan').on('click', function () {
        let branch = $("#branch_filter").val();
        let sector = $("#sector_filter").val();
        let loan_cat = $("#loan_cat_filter").val();
        if ((!branch || branch.length === 0) && (!sector || sector.length === 0) && (!loan_cat || loan_cat.length === 0)) {
            swalError('Warning', 'Please select at least one filter');
            return;
        }
        $('#verification_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();

        getSectorDropdown('verification', branch);
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
            getSectorDropdown('verification');
        }
    });

    loan_category.passedElement.element.addEventListener('showDropdown', function () {
        if (!loanCatLoaded) {
            loanCatLoaded = true;
            getLoanCatName('verification');
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

function validateLoanfollowup() {
    let response = true;
    let stage = $('#lfollow_stage').val(); let label = $('#lfollow_label').val();
    let remark = $('#lfollow_remark').val(); let follow_date = $('#lfollow_fdate').val();

    validateField(stage, '#lfollow_stageCheck');
    validateField(label, '#lfollow_labelCheck');
    validateField(remark, '#lfollow_remarkCheck');
    validateField(follow_date, '#lfollow_fdateCheck');

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

function submitLoanfollowup() {
    let cus_id = $('#lfollow_cus_id').val();
    let stage = $('#lfollow_stage').val(); let label = $('#lfollow_label').val();
    let remark = $('#lfollow_remark').val(); let follow_date = $('#lfollow_fdate').val();
    let args = { cus_id, stage, label, remark, follow_date };

    $.post('followupFiles/loanFollowup/submitLoanfollowup.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function () {
                // $('#closeAddFollowupModal').trigger('click');
                location.reload(); // Refresh the page to show the Last Follow-up date in the list
            });

            $('#addLoanFollow').find('.modal-body input').not('[readonly]').val('');
        }
    })
}

function swarlSuccessAlert(response, callback) {
    Swal.fire({
        title: response,
        icon: 'success',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#009688'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

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
    $.post('common_files/get_loan_category.php', { module: module }, function (response) {
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