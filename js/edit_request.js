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
// Document is ready
$(document).ready(function () {

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

        let branch = $("#branch_filter").val();
        let sector = $("#sector_filter").val();
        let loan_cat = $("#loan_cat_filter").val();
        if ((!branch || branch.length === 0) && (!sector || sector.length === 0) && (!loan_cat || loan_cat.length === 0)) {
            swalError('Warning', 'Please select at least one filter');
            return;
        }

        $('#request_table').DataTable().ajax.reload();
    });

    $('#branch_filter').on('change', function () {
        let branch = $(this).val();

        getSectorDropdown('request', branch);
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
            getSectorDropdown('request');
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
    $.post(
        'common_files/get_loan_category.php', { module: module },
        function (response) {
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