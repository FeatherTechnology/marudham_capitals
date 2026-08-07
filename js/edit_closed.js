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

$(document).ready(function () {

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

        let branch = $("#branch_filter").val();
        let region = $("#region_filter").val();
        let loan_cat = $("#loan_cat_filter").val();

        if ((!branch || branch.length === 0) && (!region || region.length === 0) && (!loan_cat || loan_cat.length === 0)) {
            swalError('Warning', 'Please select at least one filter');
            return;
        }

        $('#closed_table').DataTable().ajax.reload();
    });

     $('#branch_filter').on('change', function () {
        let branch = $(this).val();

        getRegionDropdown('closed', branch);
    });
});

$(function () {
    getBranchDropdown();
    getRegionDropdown('closed');
    loadNotifications();
})

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

function getRegionDropdown(module,branch = []) {
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
                    label: val.name
                });
            });
            regionChoices.setChoices(items, 'value', 'label', true);
        }
    });
}


