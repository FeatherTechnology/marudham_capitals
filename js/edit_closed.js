const branchChoices = new Choices('#branch_filter', {
    removeItemButton: true,
    noChoicesText: 'No branches available',
    allowHTML: true,
});

const regionChoices = new Choices('#region_filter', {
    removeItemButton: true,
    noChoicesText: 'No Region available',
    allowHTML: true,
});
let branchLoaded = false;
let regionLoaded = false;
$(document).ready(function () {

     // Trigger the single first load, filtered if filters were restored
    let savedFilters = getSavedClosedFilters();
    let hasSavedFilters = savedFilters && (
        (savedFilters.branch && savedFilters.branch.length) ||
        (savedFilters.region && savedFilters.region.length)
    );

    if (hasSavedFilters) {
        restoreClosedFilters(savedFilters, function () {
            if ($.fn.DataTable.isDataTable('#closed_table')) {
                $('#closed_table').DataTable().ajax.reload(null, false);
            }
        });
    } else {
        if ($.fn.DataTable.isDataTable('#closed_table')) {
            $('#closed_table').DataTable().ajax.reload(null, false);
        }
    }

    $(document).on('click', '.Move_to_noc', function () {
        var cus_id = $(this).data('value');
        var req_id = $(this).data('id');
        if (confirm('Do You want to Move to NOC?')) {
            $.ajax({
                url: 'closedFile/sendToNOC.php',
                dataType: 'json',
                type: 'post',
                data: { 'cus_id': cus_id, "req_id": req_id },
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
                            // Redirect only if OK is clicked
                            if (result.isConfirmed) {
                                window.location = 'edit_closed';
                            }
                        });
                    }
                }
            })
        }
    });

    $('#search_loan').on('click', function () {
        saveClosedFilters();
        $('#closed_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();
        getRegionDropdown('closed', branch);
        saveClosedFilters();
    });
    // load each dropdown only when the user actually opens/clicks it.

// NEW — keep storage in sync whenever region selection changes (select or deselect)
    $('#region_filter').on('change', function () {
        saveClosedFilters();
    });
    branchChoices.passedElement.element.addEventListener('showDropdown', function () {
        if (!branchLoaded) {
            branchLoaded = true;
          let currentlySelected = $('#branch_filter').val() || [];
            getBranchDropdown(currentlySelected);
        }
    });

    regionChoices.passedElement.element.addEventListener('showDropdown', function () {
        if (!regionLoaded) {
            regionLoaded = true;
            let currentlySelected = $('#branch_filter').val() || [];
            getRegionDropdown('closed',$('#branch_filter').val() || currentlySelected);
        }
    });

});

$(function () {
    loadNotifications();
})

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


function getRegionDropdown(module, branch = [], preselect = []) {
    regionChoices.clearStore();
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
                    label: val.name,
                    selected: preselect.includes(String(val.id))
                });
            });
            regionChoices.setChoices(items, 'value', 'label', true);
        }
    });
}


const CLOSED_FILTER_KEY = 'closed_table_filters';

// Save value + label for each selected item, so restore doesn't need an AJAX call
function saveClosedFilters() {
    let filters = {
        branch: branchChoices.getValue().map(item => ({ value: item.value, label: item.label })),
        region: regionChoices.getValue().map(item => ({ value: item.value, label: item.label })),
    };
    sessionStorage.setItem(CLOSED_FILTER_KEY, JSON.stringify(filters));
}

function getSavedClosedFilters() {
    let saved = sessionStorage.getItem(CLOSED_FILTER_KEY);
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
function restoreClosedFilters(filters, onDone) {
    let hasBranch = filters.branch && filters.branch.length;
    let hasRegion = filters.region && filters.region.length;

    if (hasBranch) {
        let items = filters.branch.map(f => ({
            value: f.value,
            label: f.label,
            selected: true
        }));
        branchChoices.setChoices(items, 'value', 'label', true);
    }

    if (hasRegion) {
        let items = filters.region.map(f => ({
            value: f.value,
            label: f.label,
            selected: true
        }));
        regionChoices.setChoices(items, 'value', 'label', true);
    }

    // No AJAX involved anymore — resolve immediately
    if (typeof onDone === 'function') onDone();
}