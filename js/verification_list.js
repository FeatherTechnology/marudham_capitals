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
var branchLoaded = false;
var sectorLoaded = false;
var loanCatLoaded = false;
$(document).ready(function () {
    // Trigger the single first load, filtered if filters were restored
    let savedFilters = getSavedVerificationFilters();
    let hasSavedFilters = savedFilters && (
        (savedFilters.branch && savedFilters.branch.length) ||
        (savedFilters.sector && savedFilters.sector.length) ||
        (savedFilters.loan_cat && savedFilters.loan_cat.length)
    );

    if (hasSavedFilters) {
        restoreVerificationFilters(savedFilters, function () {
            if ($.fn.DataTable.isDataTable('#verification_table')) {
                $('#verification_table').DataTable().ajax.reload(null, false);
            }
        });
    } else {
        if ($.fn.DataTable.isDataTable('#verification_table')) {
            $('#verification_table').DataTable().ajax.reload(null, false);
        }
    }
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
        saveVerificationFilters();
        $('#verification_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();
        getSectorDropdown('verification', branch);
        saveVerificationFilters();
    });

    // NEW — keep storage in sync whenever sector selection changes (select or deselect)
    $('#sector_filter').on('change', function () {
        saveVerificationFilters();
    });

    // NEW — keep storage in sync whenever loan category selection changes (select or deselect)
    $('#loan_cat_filter').on('change', function () {
        saveVerificationFilters();
    });

    // load each dropdown only when the user actually opens/clicks it.
    branchChoices.passedElement.element.addEventListener('showDropdown', function () {
        if (!branchLoaded) {
            branchLoaded = true;
            let currentlySelected = $('#branch_filter').val() || [];
            getBranchDropdown(currentlySelected);
        }
    });

    sectorChoices.passedElement.element.addEventListener('showDropdown', function () {
        if (!sectorLoaded) {
            sectorLoaded = true;
            let currentlySelected = $('#sector_filter').val() || [];
            getSectorDropdown('verification', $('#branch_filter').val() || [], currentlySelected);
        }
    });

    loan_category.passedElement.element.addEventListener('showDropdown', function () {
        if (!loanCatLoaded) {
            loanCatLoaded = true;
            let currentlySelected = $('#loan_cat_filter').val() || [];
            getLoanCatName('verification', currentlySelected);
        }
    });

});//document ready end

$(function () {
    // loadNotifications();
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

function getBranchDropdown(preselect = []) {
    return $.post('common_files/user_mapped_branches.php', {}, function (response) {
        branchChoices.clearStore();
        let items = [];
        $.each(response, function (index, val) {
            items.push({
                value: val.branch_id,
                label: val.branch_name,
                selected: preselect.includes(String(val.branch_id))
            });
        });
        branchChoices.setChoices(items, 'value', 'label', true);
    }, 'json');
}

function getSectorDropdown(module, branch = [], preselect = []) {
    sectorChoices.clearStore();
    return $.ajax({
        url: 'common_files/get_sector_name.php',
        type: 'POST',
        data: { module: module, branch: branch },
        dataType: 'json',
        success: function (response) {
            let items = [];
            $.each(response, function (i, val) {
                items.push({
                    value: val.id,
                    label: val.name,
                    selected: preselect.includes(String(val.id))
                });
            });
            sectorChoices.setChoices(items, 'value', 'label', true);
        }
    });
}

function getLoanCatName(module, preselect = []) {
    return $.post('common_files/get_loan_category.php', { module: module }, function (response) {
        loan_category.clearStore();
        let items = [];
        $.each(response, function (index, val) {
            items.push({
                value: val.loan_category_creation_id,
                label: val.loan_category_creation_name,
                selected: preselect.includes(String(val.loan_category_creation_id))
            });
        });
        loan_category.setChoices(items, 'value', 'label', true);
    }, 'json');
}

const VERIF_FILTER_KEY = 'verification_table_filters';

// Save value + label for each selected item, so restore doesn't need an AJAX call
function saveVerificationFilters() {
    let filters = {
        branch: branchChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        sector: sectorChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        loan_cat: loan_category.getValue().map(item => ({ value: item.value, label: item.label }))
    };
    sessionStorage.setItem(VERIF_FILTER_KEY, JSON.stringify(filters));
}

function getSavedVerificationFilters() {
    let saved = sessionStorage.getItem(VERIF_FILTER_KEY);
    if (!saved) return null;
    try {
        return JSON.parse(saved);
    } catch (e) {
        return null;
    }
}

// Restores selected chips directly from saved {value, label} pairs — no AJAX, no full list needed.
// Lazy-load flags (branchLoaded/sectorLoaded/loanCatLoaded) stay false so the full dropdown
// list still loads normally the first time the user opens it.
function restoreVerificationFilters(filters, onDone) {
    let hasBranch = filters.branch && filters.branch.length;
    let hasSector = filters.sector && filters.sector.length;
    let hasLoanCat = filters.loan_cat && filters.loan_cat.length;

    if (hasBranch) {
        let items = filters.branch.map(f => ({
            value: f.value,
            label: f.label,
            selected: true
        }));
        branchChoices.setChoices(items, 'value', 'label', true);
    }

    if (hasSector) {
        let items = filters.sector.map(f => ({
            value: f.value,
            label: f.label,
            selected: true
        }));
        sectorChoices.setChoices(items, 'value', 'label', true);
    }

    if (hasLoanCat) {
        let items = filters.loan_cat.map(f => ({
            value: f.value,
            label: f.label,
            selected: true
        }));
        loan_category.setChoices(items, 'value', 'label', true);
    }

    // No AJAX involved anymore — resolve immediately
    if (typeof onDone === 'function') onDone();
}