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
        $('.renewal_card, .re_active_card, .new_card, .new_promo_card, .customer-status-card, .loan-history-card, .doc-history-card, #close_history_card, .repromotion_card, .filter_card').hide();
        // $('#follow_up_sts, #date_type, #follow_up_fromdate, #follow_up_todate').val('');
        if (typevalue == 'New') {
            $('.new_card, .new_promo_card').show()
            $('.event_card').hide()
            $('.add_event_card').hide()
            resetNewPromotionTable();
        } else if (typevalue == 'Renewal') {
            $('.renewal_card, .filter_card').show();
            $('.event_card').hide()
            $('.add_event_card').hide()
            showPromotionList('followupFiles/promotion/showPromotionList.php', 'expromotion_list', '16');
        } else if (typevalue == 'Re-active') {
            $('.re_active_card, .filter_card').show();
            $('.event_card').hide()
            $('.add_event_card').hide()
            showPromotionList('followupFiles/promotion/showPromotionList.php', 're_active_promotion_list', '16');
        } else if (typevalue == 'Repromotion') {
            $('.event_card').hide()
            $('.add_event_card').hide()
            $('.repromotion_card, .filter_card').show()
            showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '17');
        } else if (typevalue == 'Events') {
            $('.event_card').show()
            $('.add_event_card').hide()
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

        if (btnName == 'Renewal') {
            showPromotionList('followupFiles/promotion/showPromotionList.php', 'expromotion_list', '16');

        } else if (btnName == 'Repromotion') {
            showPromotionList('followupFiles/promotion/showRepromotionList.php', 'repromotion_list', '17');

        } else if (btnName == 'Re-active') {
            showPromotionList('followupFiles/promotion/showPromotionList.php', 're_active_promotion_list', '16');
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
        $('#event_area_id').val("");
        $('#event_name').val("");
        $('#event_hidden_id').val("");
        var currentDate = getCurrentDate();
        // Clear Choices multiselect
        areaMultiselect.clearStore(); // remove all items
        areaMultiselect.setChoices([], 'value', 'label', true); // optionally reset with empty choices


        // reset table body with one empty row
        var emptyRow = `
        <tr>
            <td class="current_date">${currentDate}</td>
            <td>
                <input type="text"  name="cus_name" class="form-control cus_name" value="" placeholder='Enter Customer Name'>
            </td>
            <td>
                <input type="number" class="form-control cus_mobile_num" name="cus_mobile_num" value="" placeholder="Enter Mobile Number">
            </td>
            <td>
                <select class="form-control cus_area_name" name="area_name">
                    <option value="">Select Area Name</option>
                </select>
            </td>
            <td>
                <select class="form-control sub_area_name" name="sub_area_name">
                    <option value="">Select Sub Area Name</option>
                </select>
            </td>
            <td class="user"></td>
            <td>
                <button type="button"  class="btn btn-primary add_event_mem">Add</button>
            </td>
            <td>
                <span class="icon-trash-2 delet_event"></span>
            </td>
        </tr>
    `;

        $("#moduleTable tbody").html(emptyRow);
        eventsTable();

    });

    $(document).on("click", '.add_event_mem', function () {
        // Current date
        var currentDate = getCurrentDate();

        var appendTxt = "<tr>" +
            "<td class='current_date'>" + currentDate + "</td>" +
            "<td><input type='text' name='cus_name' class='form-control cus_name' placeholder='Enter Customer Name'></td>" +
            "<td><input type='number' class='form-control cus_mobile_num' name='cus_mobile_num'  value='' placeholder='Enter Mobile Number'></td>" +
            "<td><select class='form-control cus_area_name' name='cus_area_name'> <option value=''>Select Area Name</option> </select></td>" +
            "<td><select class='form-control sub_area_name' name='sub_area_name'> <option value=''>Select Sub Area Name</option> </select></td>" +
            "<td class='user'></td>" +
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

    $(document).on('change', '.cus_area_name', function () {
        const $this = $(this);
        const selectedAreas = $this.val();
        const $row = $this.closest('tr');
        const $subAreaSelect = $row.find('.sub_area_name');
        const hiddenSubAreaId = $row.find('.hidden_area').text().trim() || "";

        if (!selectedAreas || selectedAreas.length === 0) {
            $subAreaSelect.empty().append('<option value="">Select Sub Area Name</option>');
            return;
        }

        $.ajax({
            url: 'followupFiles/promotion/getUserBasedArea.php',
            type: 'POST',
            dataType: 'json',
            data: { area_id: selectedAreas },
            success: function (response) {
                $subAreaSelect.empty().append('<option value="">Select Sub Area Name</option>');
                response.forEach(function (sub) {
                    let option = $('<option>', { value: sub.sub_area_id, text: sub.sub_area_name });
                    if (sub.sub_area_id.toString() === hiddenSubAreaId) {
                        option.prop('selected', true);
                    }

                    $subAreaSelect.append(option);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching sub-areas:", error);
            }
        });
    });

    // Delete unwanted Rows
    $(document).on("click", '.delet_event', function () {
        var $row = $(this).closest('tr');
        var hiddenId = $row.find('.cus_hidden_id').text().trim();
        var $table = $row.closest('table');

        var rowCount = $table.find('tbody tr').length;
        if (rowCount <= 1) {
            Swal.fire({
                timerProgressBar: true,
                // timer: 2000,
                title: 'Table cannot be empty.',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            return;
        }

        if (hiddenId) {
            $.ajax({
                url: 'followupFiles/promotion/deletEvent.php',
                type: 'POST',
                data: { id: hiddenId },
                success: function (response) {
                    // response is plain text
                    if (response === "Event Member Deleted") {
                        $row.remove(); // remove row from table
                    }
                    console.log(response); // show the message from PHP
                },
                error: function (xhr, status, error) {
                    alert("Error deleting record: " + error);
                }
            });
        } else {
            $row.remove();
        }
    });

    const areaSelect = document.querySelector('#area_name');

    areaSelect.addEventListener('change', function () {
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

    $('#submit_event').click(function () {
        var selectedAreas = $('#area_name').val() || [];
        var areaString = selectedAreas.join(',');
        var event_name = $('#event_name').val().trim();
        var event_hidden_id = $('#event_hidden_id').val();
        var rows = $('#moduleTable tbody tr');

        //Validate Event name & area
        if (!event_name.trim()) {
            Swal.fire({
                title: 'Please enter Event Name!',
                icon: 'error',
                confirmButtonColor: '#009688'
            });
            return;
        }
        if (!areaString) {
            Swal.fire({
                title: 'Please select at least one Area!',
                icon: 'error',
                confirmButtonColor: '#009688'
            });
            return;
        }

        // Validate rows
        var allValid = true;
        var mobileInvalid = false;

        rows.each(function () {
            var $row = $(this);
            var cus_name = $row.find('.cus_name').val().trim();
            var cus_mobile_num = $row.find('.cus_mobile_num').val().trim();
            var cus_area_name = $row.find('.cus_area_name').val();
            var sub_area_name = $row.find('.sub_area_name').val();

            if (!cus_name || !cus_mobile_num || !cus_area_name || !sub_area_name) {
                allValid = false;
                return false; // break loop
            }

            if (cus_mobile_num.length !== 10) {
                mobileInvalid = true;
                allValid = false;
                return false; // break loop
            }
        });

        if (mobileInvalid) {
            Swal.fire({
                title: 'Please enter a valid mobile number!',
                icon: 'error',
                confirmButtonColor: '#009688'
            });
            return;
        }

        if (!allValid) {
            Swal.fire({
                title: 'Please fill all fields!',
                icon: 'error',
                confirmButtonColor: '#009688'
            });
            return;
        }
        let confirmAction = confirm("Are you sure you want to submit This Event?");
        if (!confirmAction) {
            return;
        }

        // Collect all rows into an array
        var allRowsData = [];
        rows.each(function () {
            var $row = $(this);
            var cus_name = $row.find('.cus_name').val().trim();
            var cus_mobile_num = $row.find('.cus_mobile_num').val().trim();
            var cus_area_name = $row.find('.cus_area_name').val();
            var sub_area_name = $row.find('.sub_area_name').val();
            var currentDateText = $row.find('.current_date').text().trim();
            var cus_hidden_id = $row.find('.cus_hidden_id').text().trim();

            // Convert dd-mm-yyyy → yyyy-mm-dd
            var parts = currentDateText.split('-');
            var currentDate = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(2, '0');

            allRowsData.push({
                cus_name,
                cus_mobile_num,
                cus_area_name,
                sub_area_name,
                currentDate,
                cus_hidden_id
            });
        });
        // Send single AJAX request with all rows
        $.ajax({
            url: 'followupFiles/promotion/submitEvent.php',
            type: 'POST',
            data: {
                event_name,
                areaString,
                event_hidden_id,
                rowsData: JSON.stringify(allRowsData)
            },
            success: function (response) {
                var res = JSON.parse(response);
                $('#back').click();
                Swal.fire({
                    title: res.message,
                    icon: 'success',
                    confirmButtonColor: '#009688'
                });
            }
        });
    });


    $(document).on('click', '.edit_event', function (event) {
        event.preventDefault();

        $('.event_card').hide();
        $('.add_event_card').show();

        var event_id = $(this).data('event');

        $.ajax({
            url: 'followupFiles/promotion/getEventDetails.php',
            type: 'POST',
            dataType: 'json',
            data: { event_id: event_id },
            success: function (response) {
                if (!response.success) {
                    alert("Event not found!");
                    return;
                }

                var eventData = response.event;
                var rows = response.rows;

                $('#event_name').val(eventData.event_name);
                $('#event_hidden_id').val(eventData.id); // eventData.id from PHP JSON

                // Store all area IDs in hidden input
                $('#event_area_id').val(eventData.all_areas);
                getArea(); // fill main multi-select

                // Wait for Choices store to be ready
                waitForChoices(function () {

                    $('#moduleTable tbody').empty();

                    rows.forEach(function (row) {
                        var formattedDate = new Date(row.event_created_date).toLocaleDateString('en-GB').split('/').join('-');

                        var newRow = $(`
                        <tr>
                            <td class="current_date">${formattedDate}</td>
                            <td><input type="text" class="form-control cus_name" value="${row.name}" placeholder='Enter Customer Name'></td>
                            <td><input type='number' class='form-control cus_mobile_num'  name='cus_mobile_num'  value="${row.mobile_num}"  placeholder='Enter Mobile Number'></td>
                            <td><select class="form-control cus_area_name"></select></td>
                            <td>
                                <!-- sub_area will be filled by .cus_area_name change handler -->
                                <select class="form-control sub_area_name"></select>
                            </td>
                            <td class="user">${row.fullname}</td>
                            <td class="hidden_area" style="display:none;">${row.sub_area}</td>
                            <td class="cus_hidden_id" style="display:none;">${row.id}</td>
                            <td><button type="button" class="btn btn-primary add_event_mem">Add</button></td>
                            <td><span class="icon-trash-2 delet_event"></span></td>
                        </tr>
                    `);
                        $('#moduleTable tbody').append(newRow);

                        const rowAreaSelect = newRow.find('.cus_area_name');
                        rowAreaSelect.empty();
                        rowAreaSelect.append($('<option>', {
                            value: '',
                            text: 'Select Area Name'
                        }));

                        const allChoices = areaMultiselect._store.items.map(item => ({
                            value: item.value,
                            label: item.label
                        }));

                        const rowAreas = row.area ? row.area.toString().split(',') : [];

                        const selectedChoices = areaMultiselect.getValue(true);

                        selectedChoices.forEach(areaId => {
                            const choice = allChoices.find(item => item.value === areaId);
                            const areaText = choice ? choice.label : areaId;

                            rowAreaSelect.append($('<option>', {
                                value: areaId,
                                text: areaText,
                                selected: rowAreas.includes(areaId.toString())
                            }));
                        });

                        rowAreaSelect.trigger('change');
                    });

                });
            },
            error: function () {
                alert("Error while fetching event data.");
            }
        });
    });

    $(document).on('input', '.cus_mobile_num', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 10) this.value = this.value.slice(0, 10);

        if (this.value.length > 0 && parseInt(this.value[0]) < 6) {
            this.value = '';
        }
    });

    $("#addPromotion").find(".closeModal").click(function () {
        $('#addPromotion').find('.modal-body input').not('[readonly]').not('#orgin_table').val('');
        $("#addPromotion").find(".modal-body span").not('.required').hide();
    });

});

$(function () {
    getPromotionAccess();
    var formattedDate = getCurrentDate();
    $('.current_date').text(formattedDate);
})

function getPromotionAccess() {
    $.post('followupFiles/promotion/promotion_access.php', function (response) {
        if (Array.isArray(response) && response.length > 0) {
            let accessString = response[0].pro_aty_access;
            let accessArray = accessString.split(",").map(Number);
            $(".toggle-button").hide();
            accessArray.forEach(value => {
                if (value === 1) {
                    $("#renewal_button").closest(".toggle-button").show();
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
                if (value === 5) {
                    $("#reactive_button").closest(".toggle-button").show();
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
    let cus_id = $('#cus_id').val(); let cus_name = $('#new_cus_name').val(); let cus_mob = $('#cus_mob').val();
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
    let cus_id = $('#cus_id').val(); let cus_name = $('#new_cus_name').val(); let cus_mob = $('#cus_mob').val();
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
    let cus_id = $('#promo_cus_id').val(); let status = $('#promo_status').val(); let label = $('#promo_label').val(); let remark = $('#promo_remark').val(); let follow_date = $('#promo_fdate').val(); let orgin_table = $('#orgin_table').val();
    let args = { cus_id, status, label, remark, follow_date, orgin_table };

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
    let cus_id = $('#cus_id').val(); let cus_name = $('#new_cus_name').val(); let cus_mob = $('#cus_mob').val();
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

        if (orgin_table === 'renewal') {
            $(".toggle-button[value='Renewal']").trigger('click');
        } else if (orgin_table === 'repromotion') {
            $(".toggle-button[value='Repromotion']").trigger('click');
        } else if (orgin_table === 're_active') {
            $(".toggle-button[value='Re-active']").trigger('click');
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
    let re_active ="";
    if(tableid === 're_active_promotion_list'){
        re_active ="re_active_table"
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
                data.re_active = re_active;
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

    $('.customer-status, .loan-history, .doc-history').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        let type = $(this).attr('class');
        let url;
        if (type == 'customer-status') {
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
    })
    showOverlay();//loader start
    setTimeout(() => {

        var pending_sts = $('#pending_sts').val()
        var od_sts = $('#od_sts').val()
        var due_nil_sts = $('#due_nil_sts').val()
        var closed_sts = $('#closed_sts').val()
        var bal_amt = balAmnt;

        $('#close_history_card').show();
        $('.filter_card').hide();
        $('.renewal_card').hide();
        $('.re_active_card').hide();
        $('.repromotion_card').hide();

        if (type == 'customer-status') {

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
            if (typevalue == 'Renewal') { $('.renewal_card').show(); }  if(typevalue == 'Re-active'){$('.re_active_card').show();}else { $('.repromotion_card').show(); }

            $('.filter_card').show();
            $('.customer-status-card, .loan-history-card, .doc-history-card, #close_history_card').hide();
        });

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
            "order": [
                [0, "asc"]
            ],
            "iDisplayLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Event_list'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column'
            }
            ]
        });
    });
}
function getArea() {
    var event_area = $("#event_area_id").val(); // comma-separated selected area IDs
    var selectedAreas = event_area.split(',');

    $.ajax({
        url: 'followupFiles/promotion/getUserBasedArea.php',
        type: 'post',
        data: { area_id: " " },
        dataType: 'json',
        success: function (response) {

            areaMultiselect.clearStore(); // clear existing choices

            var items = response.map(function (area) {
                return {
                    value: area.area_id,
                    label: area.area_name,
                    selected: selectedAreas.includes(area.area_id.toString())
                };
            });

            areaMultiselect.setChoices(items, 'value', 'label', true); // add all choices at once
        }
    });
}

function waitForChoices(callback) {
    const interval = setInterval(() => {
        if (areaMultiselect._store.items.length > 0) {
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