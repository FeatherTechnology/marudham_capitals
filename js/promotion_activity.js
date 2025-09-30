const areaMultiselect = new Choices('#area_name', {
    removeItemButton: true,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true,
    shouldSort: false
});

$(document).ready(function () {
    const toggleButtons = $(".toggle-button");
    toggleButtons.on("click", function () {
        // Reset active class for all buttons
        toggleButtons.removeClass("active");
        // Add active class to the clicked button
        $(this).addClass("active");

        var typevalue = this.value;
        $('.existing_card, .new_card, .new_promo_card, .loan-history-card, .doc-history-card, #close_history_card, .repromotion_card, .filter_card').hide();
        // $('#follow_up_sts, #date_type, #follow_up_fromdate, #follow_up_todate').val('');
        if (typevalue == 'New') {
            $('.new_card, .new_promo_card').show()
             $('.event_card').hide()
            resetNewPromotionTable();
        } else if (typevalue == 'Existing') {
            $('.existing_card, .filter_card').show();
             $('.event_card').hide()
            showPromotionList('followupFiles/promotion/showPromotionList.php', 'expromotion_list', '15');
        } else if (typevalue == 'Repromotion') {
             $('.event_card').hide()
            $('.repromotion_card, .filter_card').show()
            showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '16');
        }else if (typevalue == 'Events') {
            $('.event_card').show()
            // showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '16');
            eventsTable();
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

        if (btnName == 'Existing') {
            showPromotionList('followupFiles/promotion/showPromotionList.php', 'expromotion_list', '15');

        } else if (btnName == 'Repromotion') {
            showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '16');

        }
    });
    $("#area").change(function () {
        var areaselected = $("#area").val();
        getAreaBasedSubArea(areaselected);
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
    $('#add_event').click(function (e) {
        e.preventDefault();
        $('.event_card').hide();
        $('.add_event_card').show();
        getArea();
        
    });
    $('#back').click(function (e) {
        e.preventDefault();
        $('.event_card').show();
        $('.add_event_card').hide();
        eventsTable();
        
    });

     // add module 
    var k = 30;
$(document).on("click", '.add_event_mem', function () {
    // Current date
    var today = new Date();
    var currentDate = today.getDate().toString().padStart(2, '0') + '/' +
                      (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                      today.getFullYear();

    var appendTxt = "<tr>" +
        "<td class='current_date'>" + currentDate + "</td>" +
        "<td><input type='text' name='cus_name' class='form-control cus_name'></td>" +
        "<td><input type='text' name='cus_mobile_num' class='form-control cus_mobile_num'></td>" +
        "<td><select class='form-control cus_area_name' name='cus_area_name'> <option value=''>Select Area Name</option> </select></td>" +
        "<td><select class='form-control sub_area_name' name='sub_area_name'> <option value=''>Select Sub Area Name</option> </select></td>" +
        "<td><button type='button' class='btn btn-primary add_event_mem'>Add</button></td>" +
        "<td><span class='icon-trash-2 delet_event'></span></td>" +
        "</tr>";

    $('#moduleTable tbody').append(appendTxt);

    // Fill cus_area_name with existing main select options
    const areaSelect = document.querySelector('#area_name');
    const selectedValues = Array.from(areaSelect.selectedOptions);

    const lastCusAreaSelect = $('#moduleTable').find('.cus_area_name').last();
    selectedValues.forEach(opt => {
        lastCusAreaSelect.append(
            $('<option>', { value: opt.value, text: opt.text })
        );
    });
});

$(document).on('change', '.cus_area_name', function() {
    const $this = $(this); // the select that changed
    const selectedAreas = $this.val(); // selected area IDs
    const $subAreaSelect = $this.closest('tr').find('.sub_area_name'); // sub-area in same row

    // Reset sub-area if nothing selected
    if (!selectedAreas || selectedAreas.length === 0) {
        $subAreaSelect.empty().append('<option value="">Select Sub Area Name</option>');
        return;
    }

    // AJAX call to get sub-areas
    $.ajax({
        url: 'followupFiles/promotion/getUserBasedArea.php',
        type: 'POST',
        dataType: 'json',
        data: { area_id: selectedAreas },
        success: function(response) {
            $subAreaSelect.empty().append('<option value="">Select Sub Area Name</option>');
            response.forEach(function(sub) {
                $subAreaSelect.append(
                    $('<option>', { value: sub.sub_area_id, text: sub.sub_area_name })
                );
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching sub-areas:", error);
        }
    });
});




// Delete unwanted Rows
$(document).on("click", '.delet_event', function () {
    $(this).closest('tr').remove();
});

$('.cus_area_name').on('change', function() {
    console.log("saf");
    const selectedAreas = $(this).val(); // array of selected area IDs

    if (!selectedAreas || selectedAreas.length === 0) {
        $('#sub_area_name').empty().append('<option value="">Select Sub Area Name</option>');
        return;
    }

    $.ajax({
        url: 'followupFiles/promotion/getUserBasedArea.php', // create this PHP to fetch sub-areas
        type: 'POST',
        dataType: 'json',
        data: { area_id: selectedAreas }, // send selected area IDs
        success: function(response) {
            $('#sub_area_name').empty().append('<option value="">Select Sub Area Name</option>');

            response.forEach(function(sub) {
                $('#sub_area_name').append(
                    $('<option>', { value: sub.sub_area_id, text: sub.sub_area_name })
                );
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching sub-areas:", error);
        }
    });
});

// safely set dropdown max height after Choices initializes
requestAnimationFrame(() => {
    const dropdownEl = document.querySelector('#area_name + .choices .choices__list--dropdown');
    if (dropdownEl) {
        dropdownEl.style.maxHeight = '230px';
        dropdownEl.style.overflowY = 'auto';
    }
});

const areaSelect = document.querySelector('#area_name');

areaSelect.addEventListener('change', function() {
    const cusAreaSelects = document.querySelectorAll('.cus_area_name'); // all selects in rows
    const selectedValues = Array.from(areaSelect.selectedOptions).map(opt => opt.value);

    cusAreaSelects.forEach(cusAreaSelect => {
        // Add new options if not present
        selectedValues.forEach(value => {
            if (value === '') return;
            if (!Array.from(cusAreaSelect.options).some(opt => opt.value === value)) {
                const optionText = areaSelect.querySelector(`option[value="${value}"]`).text;
                cusAreaSelect.appendChild(new Option(optionText, value));
            }
        });

        // Remove unselected options
        Array.from(cusAreaSelect.options).forEach(opt => {
            if (opt.value !== '' && !selectedValues.includes(opt.value)) {
                opt.remove();
            }
        });
    });
});


//to set the current date for the event promotion table first row
var today = new Date();
    var currentDate = ("0" + today.getDate()).slice(-2) + '/' +
                      ("0" + (today.getMonth() + 1)).slice(-2) + '/' +
                      today.getFullYear();

    // Set the date to all elements with class 'current_date'
    $('.current_date').text(currentDate);



    $('#submit_event').click(function() {
    var rows = $('#moduleTable tbody tr'); // All table rows
    var selectedAreas = $('#area_name').val() || [];
    var areaString = selectedAreas.join(',');
    var event_name = $('#event_name').val() ;
    var created_date = $('#created_date').val() ;
    var update_id = $('#update_id').val() ;
    



    rows.each(function(index, row) {
        var $row = $(row);

        var currentDate = $row.find('.current_date').val();
        var cus_name = $row.find('.cus_name').val();
        var cus_mobile_num = $row.find('.cus_mobile_num').val();
        var cus_area_name = $row.find('.cus_area_name').val();
        var sub_area_name = $row.find('.sub_area_name').val();

        // Optional: skip empty rows
        if(areaString === '' && event_name === '' && currentDate === '' && cus_name ==='' && cus_mobile_num ==='' && cus_area_name === '' && sub_area_name === '' ) return;

        // AJAX request
        $.ajax({
            url: 'submit_row.php', // Your PHP file
            type: 'POST',
            data: {
                areaString: areaString,
                event_name: event_name,
                currentDate: currentDate,
                cus_name: cus_name,
                cus_mobile_num: cus_mobile_num,
                cus_area_name: cus_area_name,
                sub_area_name: sub_area_name,
                update_id: update_id,
                created_date: created_date
            },
            success: function(response){
                $('#back').click();
               
            },
            error: function(){
                
            }
        });
    });
});

    
});

$(function () {
    getPromotionAccess()
})

function getPromotionAccess() {
    $.post('followupFiles/promotion/promotion_access.php', function (response) {
        if (Array.isArray(response) && response.length > 0) {
            let accessString = response[0].pro_aty_access;
            let accessArray = accessString.split(",").map(Number);
            $(".toggle-button").hide();
            accessArray.forEach(value => {
                if (value === 1) {
                    $("#existing_button").closest(".toggle-button").show();
                }
                if (value === 2) {
                    $("#new_button").closest(".toggle-button").show();
                }
                if (value === 3) {
                    $("#repromotion_button").closest(".toggle-button").show();
                }
                if (value === 4) {
                    $("#events_button").closest(".toggle-button").show();
                }
            });
        }
    }, 'json');

}

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

    }).then(function () {
        
        intNotintOnclick();
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
            swarlSuccessAlert(response, function () {
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
    validateField(sub_area, '#subareaCheck');

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
            swarlSuccessAlert(response, function () {
                $('#closeAddPromotionModal').trigger('click');
            });
            $('#addPromotion').find('.modal-body input').not('[readonly]').not('#orgin_table').val('');
        }
    })
}


function getUserBasedArea() {
    $.ajax({
        url: "followupFiles/promotion/getAreaId.php",
        type: "post",
        dataType: "json",
        success: function (data) {
            let $area = $("#area");
            $area.empty().append('<option value="">Select Area</option>');
            let options = '';
            $.each(data, function (i, item) {
                options += '<option value="' + item.area_id + '">' + item.area_name + '</option>';
            });
            let $options = $(options);
            $options.sort(function (a, b) {
                return $(a).text().localeCompare($(b).text());
            });
            $area.append($options);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}

function getAreaBasedSubArea(area) {
    var sub_area_upd = $("#sub_area_upd").val();
    $.ajax({
        url: "requestFile/ajaxGetEnabledSubArea.php",
        type: "post",
        data: { area: area },
        dataType: "json",
        success: function (response) {
            $("#sub_area").empty();
            $("#sub_area").append("<option value='' >Select Sub Area</option>");
            for (var i = 0; i < response.length; i++) {
                var selected = "";
                if (
                    sub_area_upd != undefined &&
                    sub_area_upd != "" &&
                    sub_area_upd == response[i]["sub_area_id"]
                ) {
                    selected = "selected";
                }
                $("#sub_area").append(
                    "<option value='" +
                    response[i]["sub_area_id"] +
                    "' " +
                    selected +
                    ">" +
                    response[i]["sub_area_name"] +
                    " </option>"
                );
            }
        },
    });
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
            swarlSuccessAlert(response, function () {
                $('#closeNewPromotionModal').trigger('click');
            });
            $('#addnewcus').find('.modal-body input').val('');
        }
    })
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
    $(document).off('click', '.intrest, .not-intrest').on('click', '.intrest, .not-intrest', function () {
        let value = $(this).children().text(); // span inner html
        let cus_id = $(this).data('id'); // customer id

        $('#promo_status').val(value);
        $('#promo_cus_id').val(cus_id);

        // get table id for reset when modal close
        let orgin_table = $(this).closest('table').data('id');
        $('#orgin_table').val(orgin_table);
    });

    // modal close button click
    $(document).off('click', '.closeModal').on('click', '.closeModal', function () {
        let orgin_table = $('#orgin_table').val();

        if (orgin_table === 'existing') {
            $(".toggle-button[value='Existing']").trigger('click');
        } else if (orgin_table === 'repromotion') {
            $(".toggle-button[value='Repromotion']").trigger('click');
        } else {
            resetNewPromotionTable();
        }
    });
}

function showPromotionList(url, tableid, colNo) {
    let followUpSts = $('#follow_up_sts').val();
    let dateType = $('#date_type').val();
    let followUpFromDate = $('#follow_up_fromdate').val();
    let followUpToDate = $('#follow_up_todate').val();

    let table = $(`#${tableid}`).DataTable();
    table.destroy();
    $(`#${tableid}`).DataTable({
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
}

function promotionListOnclick() {

    //on click for customer profile showing in next page
    $('.cust-profile').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.open('due_followup_info&upd=' + req_id + '&pgeView=1', '_blank');
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

function promotionChartColor(tableid, colNo) {
    $(`#${tableid} tbody tr`).not('th').each(function () {
        var element = $(this).find(`td:eq(${colNo})`); // Get the text content of the 15th td element (Follow date)

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
            $('.filter_card').hide();
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
            $('.filter_card').hide();
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

            $('.filter_card').show();
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
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}
function eventsTable() {
    $.post('followupFiles/promotion/eventsList.php', {}, function (data) {
        let tableData = JSON.parse(data);

        $('.event_card').show(); // Show the card
        let table = $('#event_list');
        table.DataTable().clear().destroy(); // Reset DataTable

        table.DataTable({
            data: tableData,
            columns: [
                { title: "S.No" },
                { title: "Date" },
                { title: "Event Name" },
                { title: "Area Name" },
                { title: "Total Customer" },
                { title: "Action" }
            ],
            "iDisplayLength": 10,
            "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]],
            dom: 'lBfrtip',
            buttons: [
                { extend: 'excel' },
                { extend: 'colvis', collectionLayout: 'fixed four-column' }
            ]
        });
    });
}
function getArea() {
    var event_area = "1,2"; // comma-separated selected area IDs
    var selectedAreas = event_area.split(',');

    $.ajax({
        url: 'followupFiles/promotion/getUserBasedArea.php',
        type: 'post',
        data:{area_id:" "},
        dataType: 'json',
        success: function (response) {

            areaMultiselect.clearStore(); // clear existing choices

            var items = response.map(function(area) {
                return {
                    value: area.area_id,
                    label: area.area_name,
                    selected: selectedAreas.includes(area.area_id.toString())
                };
            });

            areaMultiselect.setChoices(items, 'value', 'label', true); // add all choices at once

            // Ensure dropdown exists before trying to set style
            requestAnimationFrame(() => {
                if (areaMultiselect.dropdown && areaMultiselect.dropdown.element) {
                    areaMultiselect.dropdown.element.style.maxHeight = '230px';
                    areaMultiselect.dropdown.element.style.overflowY = 'auto';
                }
            });
        }
    });
}


