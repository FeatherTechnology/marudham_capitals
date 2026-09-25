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
// Document is ready
$(document).ready(function () {
 
        // Trigger the single first load, filtered if filters were restored
    let savedFilters = getSavedApprovalFilters();
    let hasSavedFilters = savedFilters && (
        (savedFilters.branch && savedFilters.branch.length) ||
        (savedFilters.sector && savedFilters.sector.length) ||
        (savedFilters.loan_cat && savedFilters.loan_cat.length)
    );

    if (hasSavedFilters) {
        restoreApprovalFilters(savedFilters, function () {
            if ($.fn.DataTable.isDataTable('#approval_table')) {
                $('#approval_table').DataTable().ajax.reload(null, false);
            }
        });
    } else {
        if ($.fn.DataTable.isDataTable('#approval_table')) {
            $('#approval_table').DataTable().ajax.reload(null, false);
        }
    }

    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });
    // Approval list Actions
    $(document).on("click", '.cancelapproval', function () {
        var remark = prompt("Do you want to Cancel this Approval?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'approval' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Cancelled!', 'Approval has been Cancelled.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on('click', '.move_acknowledgement', function () {
        var req_id = $(this).val();
        let cus_id = $(this).data('cusid');
        let loan_amt = $(this).data('loan_amt');
        var button = $(this)
        $.post('approveFile/check_customer_limit.php', { cus_id }, function (response) {
            let cus_limit = response['cus_limit'];
            if (cus_limit == '') {
                alert('Customer Limit is not set');
            } else if (cus_limit < loan_amt) {
                alert('Customer Limit is Less than the Loan amount');
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Customer Limit',
                    text: `Customer Limit is set to ${moneyFormatIndia(cus_limit)}. Do you want to Approve?`,
                    showCancelButton: true,
                    confirmButtonColor: '#009688',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.prop('disabled', true);
                        $.ajax({
                            url: 'approveFile/sendToAcknowledgement.php',
                            dataType: 'json',
                            type: 'post',
                            data: { 'req_id': req_id },
                            cache: false,
                            success: function (response) {

                                if (response.includes('Approved')) {
                                    Swal.fire({
                                        title: response,
                                        icon: 'success',
                                        showConfirmButton: true,
                                        confirmButtonColor: '#009688',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        // Re-enable button regardless
                                        button.prop('disabled', false);

                                        // Redirect only if user clicks OK
                                        if (result.isConfirmed) {
                                            window.location = 'approval_list';
                                        }
                                    });
                                }
                            }
                        })
                    }
                })
            }
        }, 'json')

    });
//Search Button Click Event
    $('#search_loan').on('click', function () {
        saveApprovalFilters();
        $('#approval_table').DataTable().ajax.reload();
    });

     $('#branch_filter').on('change', function () {
        let branch = $(this).val();
        saveApprovalFilters();
        getSectorDropdown('common', branch);
    });

     // NEW — keep storage in sync whenever sector selection changes (select or deselect)
    $('#sector_filter').on('change', function () {
        saveApprovalFilters();
    });

    // NEW — keep storage in sync whenever loan category selection changes (select or deselect)
    $('#loan_cat_filter').on('change', function () {
        saveApprovalFilters();
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
            getLoanCatName('approval', currentlySelected);
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

const APPROVAL_FILTER_KEY = 'approval_table_filters';

// Save value + label for each selected item, so restore doesn't need an AJAX call
function saveApprovalFilters() {
    let filters = {
        branch: branchChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        sector: sectorChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        loan_cat: loan_category.getValue().map(item => ({ value: item.value, label: item.label }))
    };
    sessionStorage.setItem(APPROVAL_FILTER_KEY, JSON.stringify(filters));
}

function getSavedApprovalFilters() {
    let saved = sessionStorage.getItem(APPROVAL_FILTER_KEY);
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
function restoreApprovalFilters(filters, onDone) {
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