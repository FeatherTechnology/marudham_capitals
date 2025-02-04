$(document).ready(function () {
    const toggleButtons = $(".toggle-button");
    toggleButtons.on("click", function () {
        // Reset active class for all buttons
        toggleButtons.removeClass("active");
        // Add active class to the clicked button
        $(this).addClass("active");

        var typevalue = this.value;
        $('.existing_card, .new_card, .new_promo_card, .loan-history-card, .doc-history-card, #close_history_card, .repromotion_card').hide();
        if (typevalue == 'New') {
            $('.new_card, .new_promo_card').show()
            resetNewPromotionTable();
        } else if (typevalue == 'Existing') {
            $('.existing_card').show();
            showPromotionList('existing', 'expromotion_list');
        } else if (typevalue == 'Repromotion') {
            $('.repromotion_card').show()
            showPromotionList('repromotion', 'repromotion_list');
        }
    })

    $('#cus_id_search, #cus_id').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    // $('button').click(function (e) { e.preventDefault(); })

    $('#search_cus').click(function (e) {
        e.preventDefault();
        if (validateCustSearch() == true) {
            searchCustomer();
        } else {
            // $('.new_promo_card').hide();
        }
    });

    $('#submit_new_cus').click(function (e) {
        e.preventDefault();
        if (validateNewCusAdd() == true) {
            submitNewCustomer();
        }
    });

    $('#sumit_add_promo').click(function (e) {
        e.preventDefault();
        if (validatePromoAdd() == true) {
            submitPromotion();
        }
    })

});

$(function () {
    $(".toggle-button[value='Existing']").trigger('click');
})

function searchCustomer() {
    let cus_id = $('#cus_id_search').val(); let cus_name = $('#cus_name_search').val(); let cus_mob = $('#cus_mob_search').val();
    var args = { 'cus_id': cus_id, 'cus_name': cus_name, 'cus_mob': cus_mob };

    $.post('followupFiles/promotion/searchCustomer.php', args, function (response) {

        if (response['status'].includes('No')) {

            $('.alert-success').show();
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 2000);

            $('.new_promo_card').show();
            resetNewPromotionTable();

        } else {

            $('.alert-danger').show();
            $('.alert-danger .alert-text').html('Customer is in ' + response['cusPromotionType'] + '!');
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 2000);

            // $('.new_promo_card').hide();
        }

    }, 'json')
}

function validateCustSearch() {
    let response = true;
    let cus_id = $('#cus_id_search').val(); let cus_name = $('#cus_name_search').val(); let cus_mob = $('#cus_mob_search').val();
    cus_id = cus_id.replaceAll(" ", "");//will remove all spaces 

    validateField(cus_id, cus_name, cus_mob, '.searchDetailsCheck');

    function validateField(cus_id, cus_name, cus_mob, fieldId) {
        if (cus_id == '' && cus_name == '' && cus_mob == '') {
            response = false;
            event.preventDefault();
            $(fieldId).show();
        } else {
            if (cus_id != '' && cus_id.length < 12) {
                response = false;
                event.preventDefault();
                $(fieldId).show();
            } else if (cus_mob != '' && cus_mob.length < 10) {
                response = false;
                event.preventDefault();
                $(fieldId).show();
            } else {
                response = true;
                $(fieldId).hide();
            }
        }
    }

    return response;
}

function resetNewPromotionTable() {
    $.post('followupFiles/promotion/resetNewPromotionTable.php', {}, function (html) {
        $('#new_promo_div').empty().html(html);

        intNotintOnclick();

    }).then(function () {
        promoChartOnclick();
    })
}

function submitNewCustomer() {
    let cus_id = $('#cus_id').val(); let cus_name = $('#cus_name').val(); let cus_mob = $('#cus_mob').val();
    let area = $('#area').val(); let sub_area = $('#sub_area').val();
    let args = { 'cus_id': cus_id, 'cus_name': cus_name, 'cus_mob': cus_mob, 'area': area, 'sub_area': sub_area }
    $.post('followupFiles/promotion/submitNewCustomer.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else if (response.includes('Added')) {
            // if this true then it will ask for confirmation to update customer details in new promotion table
            swarlInfoAlert(response, 'Do You want to Update?');
        } else {
            swarlSuccessAlert(response, function(){
                $('#closeNewPromotionModal').trigger('click');
            });
            $('#addnewcus').find('.modal-body input').val('');
        }
    });
}

function validateNewCusAdd() {
    let response = true;
    let cus_id = $('#cus_id').val(); let cus_name = $('#cus_name').val(); let cus_mob = $('#cus_mob').val();
    let area = $('#area').val(); let sub_area = $('#sub_area').val();

    validateField(cus_name, '#cus_nameCheck');
    validateField(area, '#areaCheck');
    validateField(sub_area, '#sub_areaCheck');

    function validateField(value, fieldId) {
        if (value === '') {
            response = false;
            event.preventDefault();
            $(fieldId).show();
        } else {
            $(fieldId).hide();
        }

    }
    if (cus_id === '' || cus_id.length < 12) {
        response = false;
        event.preventDefault();
        $("#cus_idCheck").show();
    } else { $("#cus_idCheck").hide(); }
    if (cus_mob === '' || cus_mob.length < 10) {
        response = false;
        event.preventDefault();
        $("#cus_mobCheck").show();
    } else { $("#cus_mobCheck").hide(); }

    return response;
}

function submitPromotion() {
    let cus_id = $('#promo_cus_id').val();
    let status = $('#promo_status').val(); let label = $('#promo_label').val(); let remark = $('#promo_remark').val(); let follow_date = $('#promo_fdate').val();
    let args = { 'cus_id': cus_id, 'status': status, 'label': label, 'remark': remark, 'follow_date': follow_date };

    $.post('followupFiles/promotion/submitNewPromotion.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function(){
                $('#closeAddPromotionModal').trigger('click');
            });
            $('#addPromotion').find('.modal-body input').not('[readonly]').not('#orgin_table').val('');
        }
    })
}

function validatePromoAdd() {
    let response = true;
    let status = $('#promo_status').val(); let label = $('#promo_label').val(); let remark = $('#promo_remark').val();
    let follow_date = $('#promo_fdate').val();

    validateField(status, '#promo_statusCheck');
    validateField(label, '#promo_labelCheck');
    validateField(remark, '#promo_remarkCheck');
    validateField(follow_date, '#promo_fdateCheck');

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

function update() {//this function will update customer details of after confirmation
    let cus_id = $('#cus_id').val(); let cus_name = $('#cus_name').val(); let cus_mob = $('#cus_mob').val();
    let area = $('#area').val(); let sub_area = $('#sub_area').val();
    let args = { 'cus_id': cus_id, 'cus_name': cus_name, 'cus_mob': cus_mob, 'area': area, 'sub_area': sub_area, 'update': 'yes' }
    $.post('followupFiles/promotion/submitNewCustomer.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function(){
                $('#closeNewPromotionModal').trigger('click');
            });
            $('#addnewcus').find('.modal-body input').val('');
        }
    })
}

function promoChartOnclick() {//function of on click event for promo chart
    $('.promo-chart').off('click').click(function () {
        let cus_id = $(this).data('id');
        $.post('followupFiles/promotion/resetPromotionChart.php', { 'cus_id': cus_id }, function (html) {
            $('#promoChartDiv').empty();
            $('#promoChartDiv').html(html);
        })

    })
}

function intNotintOnclick() {
    $('.intrest, .not-intrest').off('click').click(function () {//onclick for add promotion modal

        let value = $(this).children().text();//takes span inner html
        let cus_id = $(this).data('id');//takes customer id of new customer promotion

        $('#promo_status').val(value);//this will set status as intrested/Not intrested
        $('#promo_cus_id').val(cus_id);

        let orgin_table = $(this).closest('table').data('id');//takes table id for reset table when modal close
        $('#orgin_table').val(orgin_table);
    })

    $('.closeModal').off('click').click(function () {
        //every time use clicked on modal close button this function will call back respective tables to refresh
        //new promotion will run resetpromotiontable itself coz its added inside html tag
        let orgin_table = $('#orgin_table').val();
        if (orgin_table == 'existing') {
            $(".toggle-button[value='Existing']").trigger('click');
        } else if (orgin_table == 'repromotion') {
            $(".toggle-button[value='Repromotion']").trigger('click');
        } else {
            resetNewPromotionTable();
        }
    })
}

function showPromotionList(type, tableid) {
    let table = $(`#${tableid}`).DataTable();
    table.destroy();
    $(`#${tableid}`).DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'followupFiles/promotion/showPromotionList.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.type = type;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Promotion List"
        },
        {
            extend: 'colvis',
            collectionLayout: 'fixed four-column',
        }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        'drawCallback': function () {
            searchFunction(tableid);

            intNotintOnclick();
            promoChartOnclick();
            promotionListOnclick();
            promotionChartColor(tableid);
        }
    });
}

function promotionListOnclick() {

    //on click for customer profile showing in next page
    $('.cust-profile').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.location.href = 'due_followup_info&upd=' + req_id + '&pgeView=1';
    })

    $('.loan-history, .doc-history').off('click').click(function () {
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        let type = $(this).attr('class');
        historyTableContents(req_id, cus_id, type)
    });

    $('.personal-info').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        getPersonalInfo(cus_id);
    })
}

function promotionChartColor(tableid) {
    $(`#${tableid} tbody tr`).not('th').each(function () {
        if (tableid == 'expromotion_list') var element = $(this).find('td:eq(14)'); // Get the text content of the 14th td element (Follow date)
        if (tableid == 'repromotion_list') var element = $(this).find('td:eq(15)'); // Get the text content of the 14th td element (Follow date)

        let tddate = element.text();
        let datecorrection = tddate.split("-").reverse().join("-").replaceAll(/\s/g, ''); // Correct the date format
        let values = new Date(datecorrection); // Create a Date object from the corrected date
        values.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let curDate = new Date(); // Get the current date
        curDate.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let colors = {
            'past': 'FireBrick',
            'current': 'DarkGreen',
            'future': 'CornflowerBlue'
        }; // Define colors for different date types

        if (tddate != '' && values != 'Invalid Date') { // Check if the extracted date and the created Date object are valid

            if (values < curDate) { // Compare the extracted date with the current date
                element.css({
                    'background-color': colors.past,
                    'color': 'white'
                }); // Apply styling for past dates
            } else if (values > curDate) {
                element.css({
                    'background-color': colors.future,
                    'color': 'white'
                }); // Apply styling for future dates
            } else {
                element.css({
                    'background-color': colors.current,
                    'color': 'white'
                }); // Apply styling for the current date
            }
        }
    });
}

//Code snippet from c:\xampp\htdocs\marudham\js\due_followup.js
function historyTableContents(req_id, cus_id, type) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];
    $.ajax({
        url: 'closedFile/resetCustomerStsForClosed.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {

                for (var i = 0; i < response['pending_customer'].length; i++) {
                    pending_arr[i] = response['pending_customer'][i]
                    od_arr[i] = response['od_customer'][i]
                    due_nil_arr[i] = response['due_nil_customer'][i]
                    closed_arr[i] = response['closed_customer'][i]
                    balAmnt[i] = response['balAmnt'][i]
                }
                var pending_sts = pending_arr.join(',');
                $('#pending_sts').val(pending_sts);
                var od_sts = od_arr.join(',');
                $('#od_sts').val(od_sts);
                var due_nil_sts = due_nil_arr.join(',');
                $('#due_nil_sts').val(due_nil_sts);
                var closed_sts = closed_arr.join(',');
                $('#closed_sts').val(closed_sts);
                balAmnt = balAmnt.join(',');
            }
        }
    })
    showOverlay();//loader start
    setTimeout(() => {

        var pending_sts = $('#pending_sts').val()
        var od_sts = $('#od_sts').val()
        var due_nil_sts = $('#due_nil_sts').val()
        var closed_sts = $('#closed_sts').val()
        var bal_amt = balAmnt;

        if (type == 'loan-history') {

            //for loan history
            $('.loan-history-card').show();
            $('#close_history_card').show();
            $('.doc-history-card').hide();
            $('.existing_card').hide();
            $('.repromotion_card').hide();

            $.ajax({
                // Fetching details by customer ID instead of req ID because we need all loans from the customer
                url: 'followupFiles/dueFollowup/viewLoanHistory.php',
                data: {
                    'cus_id': cus_id,
                    'pending_sts': pending_sts,
                    'od_sts': od_sts,
                    'due_nil_sts': due_nil_sts,
                    'closed_sts': closed_sts
                },
                type: 'post',
                cache: false,
                success: function (response) {
                    // Clearing and updating the loan history div with the response
                    $('#loanHistoryDiv').empty().html(response);
                }
            });
        } else {

            //for Document history
            $('.doc-history-card').show();
            $('#close_history_card').show();
            $('.loan-history-card').hide();
            $('.existing_card').hide();
            $('.repromotion_card').hide();

            $.ajax({
                // Fetching details by customer ID instead of req ID because we need all loans from the customer
                url: 'followupFiles/dueFollowup/viewDocumentHistory.php',
                data: {
                    'cus_id': cus_id,
                    'pending_sts': pending_sts,
                    'od_sts': od_sts,
                    'due_nil_sts': due_nil_sts,
                    'closed_sts': closed_sts,
                    'bal_amt': bal_amt
                },
                type: 'post',
                cache: false,
                success: function (response) {
                    // Emptying the docHistoryDiv and adding the response
                    $('#docHistoryDiv').empty().html(response);
                }
            });
        }

        $('#close_history_card').off('click').click(() => {
            let typevalue = $(".toggle-container .active").val();//this will show back active tab's contents
            if (typevalue == 'Existing') { $('.existing_card').show(); } else { $('.repromotion_card').show(); }

            $('.loan-history-card').hide();//hides loan history card
            $('.doc-history-card').hide();//hides document history card
            $('#close_history_card').hide();// Hides the close button
        })
        hideOverlay();//loader stop
    }, 2000)

}

function getPersonalInfo(cus_id) {
    $.post('followupFiles/promotion/getPersonalInfo.php', { cus_id }, function (html) {
        $('#personalInfoDiv').empty().html(html);
    })
}

// Improved code snippet
function swarlErrorAlert(response) {
    Swal.fire({
        title: response,
        icon: 'error',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#009688'
    });
}

function swarlInfoAlert(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'info',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#cc4444',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes'
    }).then(function (result) {
        if (result.isConfirmed) {
            update();
        }
    });
}

function swarlSuccessAlert(response, callback) {
    Swal.fire({
        title: response,
        icon: 'success',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#009688'
    }).then((result) => {
        if(result.isConfirmed && typeof callback === 'function'){
            callback();
        }
    });
}