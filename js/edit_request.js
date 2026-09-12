//Branch Multi select initialization
const branchChoices = new Choices('#branch_filter', {
    removeItemButton: true,
    noChoicesText: 'No branches available',
    allowHTML: true,
});
//Sector Multi select initialization
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
    let savedFilters = getSavedRequestFilters();
    let hasSavedFilters = savedFilters && (
        (savedFilters.branch && savedFilters.branch.length) ||
        (savedFilters.sector && savedFilters.sector.length) ||
        (savedFilters.loan_cat && savedFilters.loan_cat.length)
    );

    if (hasSavedFilters) {
        restoreRequestFilters(savedFilters, function () {
            if ($.fn.DataTable.isDataTable('#request_table')) {
                $('#request_table').DataTable().ajax.reload(null, false);
            }
        });
    } else {
        if ($.fn.DataTable.isDataTable('#request_table')) {
            $('#request_table').DataTable().ajax.reload(null, false);
        }
    }

    $('.closeModal').click(function () {
        $('#cusHistoryTable tbody').empty();
    });

    // Request Actions
    $(document).on("click", '.cancelrequest', function (event) {
        event.preventDefault(); // Prevent the default action (if needed)
        var remark = prompt("Do you want to Cancel this Request?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'cancel', remark, screen: 'request' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Cancelled!', 'Request has been Cancelled.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });
    $(document).on("click", '.revokerequest', function (event) {
        event.preventDefault(); // Prevent the default action (if needed)
        var remark = prompt("Do you want to Revoke this Request?");
        if (remark != null) {
            $.post('requestFile/changeRequestState.php', { req_id: $(this).data('reqid'), state: 'revoke', remark, screen: 'request' }, function (data) {
                if (data.includes('Success')) {
                    successSwal('Revoked!', 'Request has been Revoked.');
                } else {
                    warningSwal('Error!', 'Something went wrong.');
                }
            })
            return true;
        } else {
            return false;
        }
    });

    $(document).on('click', '.sub_verification', function () {
        var req_id = $(this).val();
        var cus_id = $(this).attr('data-value');
        if (confirm('Do You want to Send this Request for Verification?')) {
            $.ajax({
                url: 'requestFile/sendToVerificaiton.php',
                dataType: 'json',
                type: 'post',
                data: { 'req_id': req_id, "cus_id": cus_id },
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
                                window.location = 'edit_request';
                            }
                        });
                    }
                }
            })
        }
    });

    // Search Button Click Event
    $('#search_loan').on('click', function () {

      saveRequestFilters();

        $('#request_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();
        saveRequestFilters();
        getSectorDropdown('request', branch);
    });
 // NEW — keep storage in sync whenever sector selection changes (select or deselect)
    $('#sector_filter').on('change', function () {
        saveRequestFilters();
    });

    // NEW — keep storage in sync whenever loan category selection changes (select or deselect)
    $('#loan_cat_filter').on('change', function () {
        saveRequestFilters();
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
            getSectorDropdown('request',$('#branch_filter').val() || [], currentlySelected);
        }
    });

    loan_category.passedElement.element.addEventListener('showDropdown', function () {
        if (!loanCatLoaded) {
            loanCatLoaded = true;
            let currentlySelected = $('#loan_cat_filter').val() || [];
            getLoanCatName('common', currentlySelected);
        }
    });

});//document ready end
$(function () {
    setSectorLabel('request');
});
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

function setSectorLabel(screen) {
    $.ajax({
        url: 'common_files/get_label.php',
        type: 'POST',
        dataType: 'json',
        data: {
            screen: screen
        },
        success: function (response) {
            // Update the label text
            $('#sector_label').text(response.label);

            // Update underlying select option (for consistency)
            $('#sector_filter option:first').text('Select ' + response.label);

            // Update the Choices.js placeholder input
            const placeholderText = 'Select ' + response.label;

            // For Choices.js, set placeholder on the search input/container:
            const input = sectorChoices.input.element;
            if (input) {
                input.placeholder = placeholderText;
            }
        }
    });
}
const  REQUEST_FILTER_KEY = 'request_table_filters';

// Save value + label for each selected item, so restore doesn't need an AJAX call
function saveRequestFilters() {
    let filters = {
        branch: branchChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        sector: sectorChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        loan_cat: loan_category.getValue().map(item => ({ value: item.value, label: item.label }))
    };
    sessionStorage.setItem(REQUEST_FILTER_KEY, JSON.stringify(filters));
}

function getSavedRequestFilters() {
    let saved = sessionStorage.getItem(REQUEST_FILTER_KEY);
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
function restoreRequestFilters(filters, onDone) {
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