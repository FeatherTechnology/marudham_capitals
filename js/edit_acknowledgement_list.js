
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
// load each dropdown only when the user actually opens/clicks it.
let branchLoaded = false;
let sectorLoaded = false;
let loanCatLoaded = false;
// Document is ready
$(document).ready(function () {
      // Trigger the single first load, filtered if filters were restored
    let savedFilters = getSavedAcknowledgementFilters();
    let hasSavedFilters = savedFilters && (
        (savedFilters.branch && savedFilters.branch.length) ||
        (savedFilters.sector && savedFilters.sector.length) ||
        (savedFilters.loan_cat && savedFilters.loan_cat.length)
    );

    if (hasSavedFilters) {
        restoreAcknowledgementFilters(savedFilters, function () {
            if ($.fn.DataTable.isDataTable('#acknowledge_table')) {
                $('#acknowledge_table').DataTable().ajax.reload(null, false);
            }
        });
    } else {
        if ($.fn.DataTable.isDataTable('#acknowledge_table')) {
            $('#acknowledge_table').DataTable().ajax.reload(null, false);
        }
    }
    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });

    // Acknowledgement list Actions
    $(document).on("click", '.ack-cancel', function () {
        var remark = prompt("Do you want to Cancel this Acknowledgement?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'ack' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Cancelled!', 'Acknowledgement has been Cancelled.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on('click', '.move_issue', function () {
        var req_id = $(this).val();
        var cus_id = $(this).data('cusid');
        if (confirm('Do You want to Send this for Issue?')) {
            $.ajax({
                url: 'verificationFile/sendToIssue.php',
                dataType: 'json',
                type: 'post',
                data: { 'req_id': req_id, 'cus_id': cus_id },
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
                            // Redirect only if user clicks OK
                            if (result.isConfirmed) {
                                window.location = 'edit_acknowledgement_list';
                            }
                        });
                    }
                    else {
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
         saveAcknowledgementFilters();
        $('#acknowledge_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();
        saveAcknowledgementFilters();
        getSectorDropdown('common', branch);
    });
    
    // NEW — keep storage in sync whenever sector selection changes (select or deselect)
    $('#sector_filter').on('change', function () {
        saveAcknowledgementFilters();
    });

    // NEW — keep storage in sync whenever loan category selection changes (select or deselect)
    $('#loan_cat_filter').on('change', function () {
        saveAcknowledgementFilters();
    });


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
            getSectorDropdown('common', $('#branch_filter').val() || [], currentlySelected);
        }
    });

    loan_category.passedElement.element.addEventListener('showDropdown', function () {
        if (!loanCatLoaded) {
            loanCatLoaded = true;
            let currentlySelected = $('#loan_cat_filter').val() || [];
            getLoanCatName('acknowledgement', currentlySelected);
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

const ACK_FILTER_KEY = 'acknowledgement_table_filters';

// Save value + label for each selected item, so restore doesn't need an AJAX call
function saveAcknowledgementFilters() {
    let filters = {
        branch: branchChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        sector: sectorChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        loan_cat: loan_category.getValue().map(item => ({ value: item.value, label: item.label }))
    };
    sessionStorage.setItem(ACK_FILTER_KEY, JSON.stringify(filters));
}

function getSavedAcknowledgementFilters() {
    let saved = sessionStorage.getItem(ACK_FILTER_KEY);
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
function restoreAcknowledgementFilters(filters, onDone) {
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