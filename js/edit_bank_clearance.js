$(document).ready(function () {

    $('#view_table').click(function () {
        getClearanceTable();
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
                $('#bank_name').append("<option value='" + val['id'] + "'>" + val['bank_name'] + "</option>");
            })
        }
    });
}


function validation() {
    var bank_id = $('#bank_name').val();
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
    return response;
}


function getClearanceTable() {
    if (validation() == 0) {

        var bank_id = $('#bank_name').val();

        $.ajax({
            url: 'accountsFile/bankclearance/ajaxBankClearanceFetch.php',
            data: { bank_id: bank_id },
            type: 'post',
            cache: false,
            success: function (response) {

                if (response.includes('No Statements')) {

                    Swal.fire({
                        title: response,
                        icon: 'warning',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });

                    $('.bank_clr_table').hide();
                    return;

                } else {

                    $('.bank_clr_table').show();

                    // Clear & inject table content
                    $('#bank_clearance_list').empty().html(response);

                    // 🔥 DESTROY existing DataTable (VERY IMPORTANT)
                    if ($.fn.DataTable.isDataTable('#bank_clearance_list')) {
                        $('#bank_clearance_list').DataTable().destroy();
                    }

                    // 🔥 INITIALIZE DataTable
                    var bank_clearance_list = $('#bank_clearance_list').DataTable({
                        // ...getStateSaveConfig('bank_clearance_list'),
                        processing: true,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        createdRow: function (row, data, dataIndex) {
                            $('td:eq(0)', row).html(dataIndex + 1);
                        },
                        drawCallback: function () {
                            this.api().column(0).nodes().each(function (cell, i) {
                                cell.innerHTML = i + 1;
                            });
                            searchFunction('bank_clearance_list');
                        },
                        dom: 'lBfrtip',
                        buttons: [{
                                extend: 'excel',
                                action: function(e, dt, button, config) {
                                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                                    var dynamic = curDateJs('Bank_Transation_report'); // or any base
                                    config.title = dynamic; // for versions that use title as filename
                                    config.filename = dynamic; // for html5 filename
                                    defaultAction.call(this, e, dt, button, config);
                                }
                            },
                            {
                                extend: 'colvis',
                                collectionLayout: 'fixed four-column',
                            }
                        ],
                    });
                    
                    // Pass the table variable to the initColVisFeatures function
                    initColVisFeatures(bank_clearance_list, 'bank_clearance_list');
                }
            }
        });
    }
}
