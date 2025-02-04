$(document).ready(function () {





    $('#from_date').change(function () {
        var fromDate = new Date($(this).val()); // take as date format
        var toDate = new Date($('#to_date').val()); // take as date format, if nothing selected will de invalid date

        if (fromDate > toDate) { // check if from date is greater than to date, if yes then remove to date
            $('#to_date').val('');
        }

        $('#to_date').attr('min', $(this).val()); // setting minimum date for to date, so before start date will be disabled
    });

    $('#to_date').change(function () {
        var fromDate = new Date($('#from_date').val());
        var toDate = new Date($(this).val());

        if (fromDate > toDate) { // if anyone enters to date manually in to date less than from date, it empty's the to date value
            $(this).val('');
        }
    });

    $('#view_table').click(function () {
        if (validation() == 0) {
            var bank_id = $('#bank_name').val(); var from_date = $('#from_date').val(); var to_date = $('#to_date').val();
            $.ajax({
                url: 'accountsFile/bankclearance/ajaxBankClearanceFetch.php',
                data: { 'bank_id': bank_id, 'from_date': from_date, 'to_date': to_date },
                type: 'post',
                cache: false,
                success: function (response) {
                    if (response.includes('Given Date Has No Statements!')) {
                        Swal.fire({
                            title: response,
                            text: 'Please Try Different Dates',
                            icon: 'warning',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        })
                        $('.bank_clr_table').hide();
                        return false;
                    } else {
                        $('.bank_clr_table').show();
                        $('#bank_clearance_list').empty();
                        $('#bank_clearance_list').html(response);
                        // initializeDT();
                        // tablesorting();
                    }
                }
            }).then(function () {
                clrcatClickEvent();
                getUnclearTotal();
            })
        }
    })

    //Unbind or disable all other event listeners to avoid conflict
    // $('#search').unbind('input');
    // $('#search').unbind('keypress');
    // $('#search').unbind('keyup');
    // $('#search').unbind('search');

    //new search on keyup event for search by display content
    $('#search_table').keyup(function () {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
        // Loop through the comment list
        $("table tbody tr").each(function () {
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        })
    })











})// document ready END



$(function () {

    getBankNames();//get bank names

});// auto call functions END


function getBankNames() {
    $.ajax({
        url: 'accountsFile/bankclearance/getBankNames.php',
        data: {},
        type: 'post',
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#bank_name').empty();
            $('#bank_name').append('<option value="">Select Bank Name</option>');
            $.each(response, function (index, val) {
                $('#bank_name').append("<option value='" + val['bank_id'] + "'>" + val['bank_name'] + "</option>");
            })
        }
    });
}


function validation() {
    var bank_id = $('#bank_name').val(); var from_date = $('#from_date').val(); var to_date = $('#to_date').val();
    var response = 0;

    function validateField(value, fieldId) {
        if (value === '') {
            response = 1;
            event.preventDefault();
            $(fieldId).show();
        } else {
            $(fieldId).hide();
        }
    }

    // validateField(ucl_trans_id, '#ucl_trans_id_exfCheck');
    validateField(bank_id, '#bank_nameCheck');
    validateField(from_date, '#from_dateCheck');
    validateField(to_date, '#to_dateCheck');
    return response;
}


function initializeDT() {
    var bank_clearance_list = $('#bank_clearance_list').DataTable();
    bank_clearance_list.destroy();

    $('#bank_clearance_list').DataTable({
        "title": "Bank Clearance List",
        'processing': true,
        'iDisplayLength': 10,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "createdRow": function (row, data, dataIndex) {
            $(row).find('td:first').html(dataIndex + 1);
        },
        "drawCallback": function (settings) {
            this.api().column(0).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
        },
        {
            extend: 'colvis',
            collectionLayout: 'fixed four-column',
        }
        ],
    });


}

function tablesorting() { // for sorting table
    var table = $("#bank_clearance_list");
    var tbody = table.find("tbody");
    var rows = tbody.find("tr").toArray();
    var ascending = true;

    table.on("click", "th", function () {
        var column = $(this).index();

        rows.sort(function (a, b) {
            var aValue = $(a).find("td").eq(column).text().trim();
            var bValue = $(b).find("td").eq(column).text().trim();

            if (column === 0) {
                aValue = parseInt(aValue);
                bValue = parseInt(bValue);
            }

            if (ascending) {
                if (aValue < bValue) return -1;
                if (aValue > bValue) return 1;
                return 0;
            } else {
                if (aValue > bValue) return -1;
                if (aValue < bValue) return 1;
                return 0;
            }
        });

        tbody.empty().append(rows);

        // Update serial numbers
        tbody.find("tr").each(function (index) {
            $(this).find("td:first").text(index + 1);
        });

        ascending = !ascending;
    });
}

//function for click event when user clicks on a cash tally modes to get the ref codes
function clrcatClickEvent() {
    $('.clr_cat').change(function () {
        var clr_cat = $(this).val();
        var bank_id = $(this).prev().val();
        var crdb = $(this).next().val();
        var trans_id = $(this).parent().prev().prev().prev().prev().text();
        var ref_id_box = $(this).parent().next().children();//represents ref id select box

        $.ajax({
            url: 'accountsFile/bankclearance/getRefCodetoClear.php',
            data: { 'clr_cat': clr_cat, 'bank_id': bank_id, 'crdb': crdb, 'trans_id': trans_id },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                ref_id_box.empty();
                ref_id_box.append("<option value=''>Select Ref ID</option>");
                $.each(response, function (ind, val) {
                    ref_id_box.append("<option value='" + val['ref_code'] + "'>" + val['ref_code'] + "</option>")
                })

            }
        })
    })

    $('.ref-id').change(function () {
        if ($(this).val() != '') { // only true if ref id choosen

            $(this).parent().next().children().hide();//hiding span uncleared text
            $(this).parent().next().children().after('<input type="button" class="btn btn-primary clear_btn" value="Clear" id="" name="">')//adding new button after span

            $(this).parent().prev().children().attr('disabled', true)//disabling clear category dropdown
            $(this).attr('disabled', true)//disabling ref_id dropdown

            $('.clear_btn').off('click');//turning off existing click event
            $('.clear_btn').click(function () {
                var clear_btn = $(this)
                var bank_stmt_id = $(this).parent().next().val();// to get bank statement table if which is stored inside hidden input
                $.ajax({
                    url: 'accountsFile/bankclearance/clearTransaction.php',
                    data: { 'bank_stmt_id': bank_stmt_id },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        if (response == 0) {
                            clear_btn.prev().show();
                            clear_btn.prev().text('Cleared');
                            clear_btn.prev().addClass('text-success');
                            clear_btn.prev().removeClass('text-danger');
                            clear_btn.hide();
                            getUnclearTotal();// to reset unclear total amounts
                        } else {
                            Swal.fire({
                                title: 'Not Cleared',
                                text: 'Error While Submitting',
                                icon: 'error',
                                showConfirmButton: true,
                                confirmButtonColor: '#009688'
                            })
                        }
                    }
                })
            })
        }
    })

}

function getUnclearTotal() {
    var unclear_credit = 0;
    var unclear_debit = 0;
    $('.clr-status').each(function () {
        var clr_status = $(this).text();
        if (clr_status == 'Unclear') {
            var credit = $(this).parent().prev().prev().prev().prev().prev().text(); // credit amount
            var debit = $(this).parent().prev().prev().prev().prev().text(); // debit amount
            unclear_credit += parseInt(credit) || 0;
            unclear_debit += parseInt(debit) || 0;
        }
    })
    unclear_credit = moneyFormatIndia(unclear_credit)
    unclear_debit = moneyFormatIndia(unclear_debit)
    $('#ucl_credit').text(unclear_credit).css('font-weight', 'bold');
    $('#ucl_debit').text(unclear_debit).css('font-weight', 'bold');
}