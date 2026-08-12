$(document).ready(function () {

    const toggleButtons = $(".toggle-button");
    toggleButtons.on("click", function () {

        // Reset active class for all buttons
        toggleButtons.removeClass("active");

        // Add active class to the clicked button
        $(this).addClass("active");

        var typevalue = this.value;
        $('.waiting_card, .block_card,.customer-status-card, .loan-history-card, .doc-history-card, #close_history_card, .repromotion_card, .filter_card').hide();

        //except new promotion all had closing date.
        $('#date_type')
            .empty()
            .append(`<option value="">Select Date</option>
                <option value="1">Closed Date</option>
                <option value="2">Followup Date</option>`);

        if (typevalue == 'Repromotion') {
            $('.repromotion_card, .filter_card').show();
            showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '18');

        } else if (typevalue == 'Waiting List') {
            $('.waiting_card, .filter_card').show();
            showPromotionList('followupFiles/promotion/showWaitingList.php', 'waiting_list', '16');

        } else if (typevalue == 'Block List') {
            $('.block_card, .filter_card').show();
            showPromotionList('followupFiles/promotion/showWaitingList.php', 'block_list', '16');
        }

    });

    $('#sumit_add_promo').click(function (e) {
        e.preventDefault();
        if (validatePromoAdd() == true) {
            submitPromotion();
        }
    });

    $('#submit_closed').click(function (e) {
        e.preventDefault();
        if (validateClosedAdd() == true) {
            submitClosed();
        }
    });

    $('#followup_search').click(function (event) {
        event.preventDefault();

        let dateType = $('#date_type').val();
        if (dateType) {
            let fromDate = $('#follow_up_fromdate').val();
            let toDate = $('#follow_up_todate').val();

            if (!fromDate || !toDate) {
                alert("Please fill the From & To date.");
                return;
            }
        } else {
            $('#follow_up_fromdate').val('');
            $('#follow_up_todate').val('');
        }

        let btnName = $(".toggle-button.active").first().val();

        if (btnName == 'Repromotion') {
            showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '18');

        } else if (btnName == 'Waiting List') {
            showPromotionList('followupFiles/promotion/showWaitingList.php', 'waiting_list', '16');

        } else if (btnName == 'Block List') {
            showPromotionList('followupFiles/promotion/showWaitingList.php', 'block_list', '16');

        }
    });

    $('#follow_up_fromdate').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#follow_up_todate').val();
        $('#follow_up_todate').attr('min', fromDate);

        // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#follow_up_todate').val(''); // Clear the invalid value
        }
    });

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date in the date input to today
        $('#promo_fdate').attr('min', today);
    }


    $("#addPromotion").find(".closeModal").click(function () {
        $('#addPromotion').find('.modal-body input').not('[readonly]').not('#orgin_table').val('');
        $('#addPromotion').find('.modal-body select').prop('selectedIndex', 0);
        $("#addPromotion").find(".modal-body span").not('.required').hide();
    });

    $("#addClosedModal").find(".addcloseModal").click(function () {
        $('#addClosedModal').find('.modal-body input').not('[readonly]').not('#orgin_closed_table').val('');
        $('#addClosedModal').find('.modal-body select').prop('selectedIndex', 0);
        $("#addClosedModal").find(".modal-body span").not('.required').hide();
    });

    $(document).on('click', '.return_sts', function (e) {
        e.preventDefault(); // Prevents default link action
        let cus_id = $(this).data('id');
        let req_id = $(this).data('req-id');

        $(document).on('click', '.return_sts', function (e) {
            e.preventDefault();
            let cus_id = $(this).data('id');
            let req_id = $(this).data('req-id');
            swarlInfoAlert("Return","Do you want to move to return status?",function () {
                    $.ajax({
                        url: 'followupFiles/promotion/return_status.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            cus_id: cus_id,
                            req_id: req_id
                        },
                        success: function (response) {
                            if (response.status == 1) {
                                swarlSuccessAlert(response.message, function () {
                                    showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '18');
                                });
                            } else {
                                swarlErrorAlert(response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Something went wrong. Please try again.',
                                icon: 'error'
                            });
                            console.error("AJAX Error:", error);
                            console.error(xhr.responseText);
                        }
                    });

                }
            );
        });
    });


}); //Document END.

$(function () {
    getPromotionAccess();
    var formattedDate = getCurrentDate();
    $('.current_date').text(formattedDate);
})

function getPromotionAccess() {
    $.post('followupFiles/promotion/promotion_access.php',{ screen: 2 },function (response) {
            if (Array.isArray(response) && response.length > 0) {
                let accessString = response[0].repro_aty_access;
                if (accessString) {
                    let accessArray = accessString.split(",").map(Number);
                    $(".toggle-button").hide();
                    accessArray.forEach(value => {
                        if (value === 1) {
                            $("#repromotion_button").closest(".toggle-button").show();
                        }
                        if (value === 2) {
                            $("#waiting_button").closest(".toggle-button").show();
                        }
                        if (value === 3) {
                            $("#block_button").closest(".toggle-button").show();
                        }
                    });
                }
            }
        },
        'json'
    );
}

function submitPromotion() {
    let cus_id = $('#promo_cus_id').val(); let promo_type = $('#promo_type').val(); let status = $('#promo_status').val(); let label = $('#promo_label').val(); let remark = $('#promo_remark').val(); let follow_date = $('#promo_fdate').val(); let followupType = $('#followup_type').val(); let orgin_table = $('#orgin_table').val();
    let args = { cus_id, promo_type, status, label, remark, follow_date, followupType, orgin_table };
    console.log(orgin_table);
    $.post('followupFiles/promotion/submitNewPromotion.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function () {
                $('#closeAddPromotionModal').trigger('click');
            });
            $('#addPromotion').find('.modal-body input').not('[readonly]').not('#orgin_table').val('');
            $('#addPromotion').find('.modal-body select').prop('selectedIndex', 0);
        }
    })
}

function submitClosed() {
    let cus_id = $('#close_cus_id').val();
    let closed_Sts = $('#closed_Sts').val();
    let args = { cus_id, closed_Sts };

    $.post('followupFiles/promotion/submitClosedStatus.php', args, function (response) {
        if (response.includes('Error')) {
            swarlErrorAlert(response);
        } else {
            swarlSuccessAlert(response, function () {
                $('#closedModal').trigger('click');
                closedModal()
            });
            $('#addClosedModal').find('.modal-body input').not('[readonly]').not('#orgin_closed_table').val('');
            $('#addClosedModal').find('.modal-body select').prop('selectedIndex', 0);
        }
    })
}


function validatePromoAdd() {
    let response = true;
    let promo_type = $('#promo_type').val();
    let status = $('#promo_status').val();
    let label = $('#promo_label').val();
    let remark = $('#promo_remark').val();
    let follow_date = $('#promo_fdate').val();

    validateField(status, '#promo_statusCheck');
    validateField(label, '#promo_labelCheck');
    validateField(promo_type, '#promo_typeCheck');
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

function validateClosedAdd() {
    let response = true;
    let closed_Sts = $('#closed_Sts').val();
    validateField(closed_Sts, '#closedStatusCheck')
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


function promoChartOnclick() { // function of on click event for promo chart
    $(document).off('click', '.promo-chart').on('click', '.promo-chart', function () {
        let cus_id = $(this).data('id');
        $.post('followupFiles/promotion/resetPromotionChart.php', { cus_id: cus_id }, function (html) {
            $('#promoChartDiv').empty().html(html);
        });
    });
}

function intNotintOnclick() {
    // click for add promotion modal
    $(document).off('click', '.intrest, .not-intrest, .un-available, .noc-call').on('click', '.intrest, .not-intrest, .un-available, .noc-call', function () {
        let value = $(this).children().text(); // span inner html
        let cus_id = $(this).data('id'); // customer id

        $('#promo_status').val(value);
        $('#promo_cus_id').val(cus_id);

        // get table id for reset when modal close
        let orgin_table = $(this).closest('table').data('id');
        $('#orgin_table').val(orgin_table);
    });

    $(document).off('click', '.add_close').on('click', '.add_close', function () {
        let customer_id = $(this).data('id'); // customer id
        let row = $(this).closest('tr');
        // TD positions based on your table
        let aadhar = row.find('td:eq(1)').text().trim();
        let cus_id = row.find('td:eq(2)').text().trim();
        let cus_name = row.find('td:eq(3)').text().trim();
        let orgin_table = $(this).closest('table').data('id');

        $('#orgin_closed_table').val(orgin_table);
        $('#close_cus_id').val(customer_id);
        $('#aadhar_num').val(aadhar);
        $('#customer_id').val(cus_id);
        $('#customer_name').val(cus_name);
    });

    // modal close button click
    $(document).off('click', '.closeModal').on('click', '.closeModal', function () {
        let orgin_table = $('#orgin_table').val();
        if (orgin_table === 'repromotion') {
            $(".toggle-button[value='Repromotion']").trigger('click');
        } else if (orgin_table === 'waiting') {
            $(".toggle-button[value='Waiting List']").trigger('click');
        } else if (orgin_table === 'block') {
            $(".toggle-button[value='Block List']").trigger('click');
        }
    });
}

function showPromotionList(url, tableid, colNo) {
    let followUpSts = $('#follow_up_sts').val();
    let dateType = $('#date_type').val();
    let followUpFromDate = $('#follow_up_fromdate').val();
    let followUpToDate = $('#follow_up_todate').val();
    let followupType = $('#followuptype').val();
    let waiting_list = "";
    if (tableid === 'waiting_list') {
        waiting_list = "waiting_list"
    }

    let table = $(`#${tableid}`).DataTable();
    table.destroy();
    // Declare table variable to store the DataTable instance
    var tables = $(`#${tableid}`).DataTable({
        ...getStateSaveConfig(tableid),
        "order": [
            [0, "desc"]
        ],
        "displayStart": getDisplayStart(tableid),
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': url,
            'data': function (data) {
                var search = $('#' + tableid + '_search').val();
                data.search = search;
                data.followUpSts = followUpSts;
                data.dateType = dateType;
                data.followUpFromDate = followUpFromDate;
                data.followUpToDate = followUpToDate;
                data.followupType = followupType;
                data.waiting_list = waiting_list;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Promotion List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs(tableid); // or any base
                config.title = dynamic;      // for versions that use title as filename
                config.filename = dynamic;   // for html5 filename
                defaultAction.call(this, e, dt, button, config);
            }
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
            let searchInput = $('#' + tableid + '_filter input');
            searchInput.attr('id', tableid + '_search').addClass('custo-search');
            searchFunction(tableid);
            paginationFunction(tableid);
            intNotintOnclick();
            promoChartOnclick();
            promotionListOnclick();
            promotionChartColor(tableid, colNo);
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(tables, 'tableid');
}

function promotionListOnclick() {

    //on click for customer profile showing in next page
    $('.cust-profile').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.open('due_followup_info&upd=' + req_id + '&pgeView=1', '_blank');
    })

    $('.customer-sts, .loan-history, .doc-history').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        let type = $(this).attr('class');

        let url;
        if (type == 'customer-sts') {
            url = 'collectionFile/resetCustomerStatus.php';
        } else {
            url = 'closedFile/resetCustomerStsForClosed.php';
        }

        historyTableContents(cus_id, type, url)
    });

    $('.personal-info').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        getPersonalInfo(cus_id);
    })
}

function promotionChartColor(tableid, colNo) {
    $(`#${tableid} tbody tr`).not('th').each(function () {
        var element = $(this).find(`td:eq(${colNo})`); // Get the text content of the td element (Follow date)
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
function historyTableContents(cus_id, type, url) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];

    $.ajax({
        url: url,
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {
                let pendingCnt = (response['pending_customer']) ? response['pending_customer'].length : 0;
                for (var i = 0; i < pendingCnt; i++) {
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
    });

    showOverlay();//loader start
    setTimeout(() => {

        var pending_sts = $('#pending_sts').val()
        var od_sts = $('#od_sts').val()
        var due_nil_sts = $('#due_nil_sts').val()
        var closed_sts = $('#closed_sts').val()
        var bal_amt = balAmnt;

        $('#close_history_card').show();
        $('.filter_card').hide();
        $('.waiting_card').hide();
        $('.re_active_card').hide();
        $('.block_card').hide();

        if (type == 'customer-sts') {
            //for customer status
            $('.customer-status-card').show();
            $('.loan-history-card').hide();
            $('.doc-history-card').hide();

            $.ajax({
                url: 'requestFile/getCustomerStatus.php',
                data: { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts, bal_amt },
                type: 'post',
                cache: false,
                success: function (response) {
                    // Clearing and updating the Customer status div with the response
                    $('#cusHistoryTable').empty().html(response);
                    $('#cusHistoryTable tbody tr').each(function () {
                        var val = $(this).find('td:nth-child(6)').text().trim();

                        if (['Request', 'Verification', 'Approval', 'Acknowledgement', 'Issue'].includes(val)) {
                            $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(240, 0, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                        } else if (val === 'Present') {
                            $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 160, 0, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                        } else if (val === 'Closed') {
                            $(this).find('td:nth-child(6)').css({ 'backgroundColor': 'rgba(0, 0, 255, 0.8)', 'color': 'white', 'fontWeight': 'Bolder' });
                        }
                    });
                }
            });

        } else if (type == 'loan-history') {
            //for loan history
            $('.loan-history-card').show();
            $('.customer-status-card').hide();
            $('.doc-history-card').hide();

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
            $('.customer-status-card').hide();
            $('.loan-history-card').hide();

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
            if (typevalue == 'Waiting List') {
                $('.waiting_card').show();
            } else if (typevalue == 'Block List') {
                $('.block_card').show();
            } else if (typevalue == 'Repromotion') {
                $('.repromotion_card').show();
            }

            $('.filter_card').show();
            $('.customer-status-card, .loan-history-card, .doc-history-card, #close_history_card').hide();
        });

        hideOverlay();//loader stop
    }, 2000);

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

function swarlInfoAlert(title, text, onConfirm) {
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
            if (typeof onConfirm === 'function') {
                onConfirm();
            }
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
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

function waitForChoices(callback) {
    const interval = setInterval(() => {
        if ($('#area_name').val() != '') {
            clearInterval(interval);
            callback();
        }
    }, 50); // check every 50ms
}

function getCurrentDate() {
    var today = new Date();
    var currentDate = ("0" + today.getDate()).slice(-2) + '-' +
        ("0" + (today.getMonth() + 1)).slice(-2) + '-' +
        today.getFullYear();
    return currentDate;
}

function closedModal() {
    let orgin_table = $('#orgin_closed_table').val();

    if (orgin_table === 'repromotion') {
        $(".toggle-button[value='Repromotion']").trigger('click');
    } else if (orgin_table === 'waiting') {
        $(".toggle-button[value='Waiting List']").trigger('click');
    } else if (orgin_table === 'block') {
        $(".toggle-button[value='Block List']").trigger('click');
    }
}