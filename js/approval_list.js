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
// Document is ready
$(document).ready(function () {
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

        let branch = $("#branch_filter").val();
        let sector = $("#sector_filter").val();
        let loan_cat = $("#loan_cat_filter").val();
        if ((!branch || branch.length === 0) && (!sector || sector.length === 0) && (!loan_cat || loan_cat.length === 0)) {
            swalError('Warning', 'Please select at least one filter');
            return;
        }

        $('#approval_table').DataTable().ajax.reload();
    });

     $('#branch_filter').on('change', function () {
        let branch = $(this).val();

        getSectorDropdown('common', branch);
    });

});//document ready end

$(function () {
    getBranchDropdown();
    getSectorDropdown('common');
    getLoanCatName('approval');
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