const subStatusMultiselect = new Choices('#sub_status_mapping', {
    removeItemButton: true,
    noChoicesText: 'Select Customer Status',
    allowHTML: true
});

$(document).ready(function () {

    $('#show_due_followup').click(function () {
        let cusSts = $("#sub_status_mapping").val();
        let comm_date = $("#comm_date").val();
        let res_sts = $("#res_sts").val();
        let comm_sts = $("#comm_sts").val();
        let call_status = $("#call_status").val();
        let branch_id = $("#branch").val();
        let line_id = $("#region").val();
        let followup_id = $("#zone").val();

        OnLoadFunctions(cusSts, comm_date, res_sts, comm_sts, call_status,branch_id,line_id,followup_id);
    });

    $(document).on('click', '.personal-info', function (e) {
        e.preventDefault();
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/promotion/getPersonalInfo.php',{cus_id: cus_id},function (html) {
                $('#personalInfoDiv').html(html);
            }
        ).fail(function (xhr, status, error) {
            console.log("AJAX Error:", error);
            console.log(xhr.responseText);
        });
    });

    let branchLoaded = false;
    let regionLoaded = false;
    let zoneLoaded = false;

    // Branch
    $('#branch').on('focus', function () {
        if (!branchLoaded) {
            branchLoaded = true;
            getBranchList();
        }
    });

    // Region
    $('#region').on('focus', function () {
        if (!regionLoaded) {
            regionLoaded = true;
            getLineList();
        }
    });

    // Zone
    $('#zone').on('focus', function () {
        if (!zoneLoaded) {
            zoneLoaded = true;
            getFollowupList();
        }
    });
    // Save whenever the user changes a dropdown
$('#branch, #region, #zone').on('change', function () {
    saveDueFollowupFilters();
});
});

$(function () {
    getSubStsMapping();

    // Restore branch/region/zone so back-button/reload doesn't lose the selection
    let savedDueFilters = getSavedDueFollowupFilters();
    if (savedDueFilters) {
        restoreSelectValue($('#branch'), savedDueFilters.branch);
        restoreSelectValue($('#region'), savedDueFilters.region);
        restoreSelectValue($('#zone'), savedDueFilters.zone);
    }

    let cummDate = $("#cummDate").val();
    $("#comm_date").val(cummDate);
    let cus_Sts = $("#customer_status").val();
    let cusSts = cus_Sts.split(',');
    let res_sts = $("#res_sts").val();
    let comm_sts = $("#comm_sts").val();
    let call_status = $("#call_status").val();
    let branch_id = $("#branch").val();
    let line_id = $("#region").val();
    let followup_id = $("#zone").val();

    if (cusSts != '') {
        OnLoadFunctions(cusSts, cummDate, res_sts, comm_sts, call_status, branch_id, line_id, followup_id);
    }
});


function getBranchList() {
    $.ajax({
        url: 'followupFiles/promotion/getBranchList.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {
            let currentVal = $('#branch').val(); // remember restored selection

            $('#branch').html('<option value="">Select Branch</option>');
            $.each(response, function (index, value) {
                $('#branch').append(
                    '<option value="' + value.branch_id + '">' +
                    value.branch_name +
                    '</option>'
                );
            });

            if (currentVal) {
                $('#branch').val(currentVal); // re-apply it after the full list loads
            }
        }
    });
}

function getLineList() {
    $.ajax({
        url: 'followupFiles/promotion/getLineList.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            let currentVal = $('#region').val(); // remember restored/selected value

            $('#region').html('<option value="">Select Region</option>');

            $.each(response, function(index, value) {

                $('#region').append(
                    '<option value="' + value.line_id + '">' +
                    value.line_name +
                    '</option>'
                );

            });

            if (currentVal) {
                $('#region').val(currentVal); // re-apply after full list loads
            }
        }
    });
}

function getFollowupList() {
    $.ajax({
        url: 'followupFiles/promotion/getFollowupList.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            let currentVal = $('#zone').val(); // remember restored/selected value

            $('#zone').html('<option value="">Select Zone</option>');

            $.each(response, function(index, value) {

                $('#zone').append(
                    '<option value="' + value.due_followup_lines_id + '">' +
                    value.duefollowup_name +
                    '</option>'
                );

            });

            if (currentVal) {
                $('#zone').val(currentVal); // re-apply after full list loads
            }
        }
    });
}
function warningSwal(title, text) {
    Swal.fire({
        title: title,
        html: text,
        icon: 'warning',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
    });
}

function OnLoadFunctions(cusSts, comm_date, res_sts, comm_sts, call_status,branch_id,line_id,followup_id) {
    if (!cusSts) {
        warningSwal('Warning!', 'Select Customer Status.');
        return;
    }

    $('#due_followup_table').DataTable().destroy();
    var table = $('#due_followup_table').DataTable({
        ...getStateSaveConfig('due_followup_table'),
        "order": [[0, "desc"]],
        "processing": true,
        "displayStart": getDisplayStart('due_followup_table'),
        "serverSide": true,
        'infoCallback': customDataTableInfo,
        "serverMethod": 'post',
        "ajax": {
            "url": 'followupFiles/dueFollowup/getDueFollowCus.php',
            "data": function (data) {
                var search = $('#search').val();
                data.search = search;
                data.cus_sts = cusSts;
                data.comm_date = comm_date;
                data.res_sts = res_sts;
                data.comm_sts = comm_sts;
                data.call_status = call_status;
                data.branch_id = branch_id;
                data.line_id = line_id;
                data.followup_id = followup_id;
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            extend: 'excel',
            title: "Due Followup List",
            action: function (e, dt, button, config) {
                var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                var dynamic = curDateJs('Due_Followup'); // or any base
                config.title = dynamic;      // for versions that use title as filename
                config.filename = dynamic;   // for html5 filename
                defaultAction.call(this, e, dt, button, config);
            }
        },
        {
            extend: 'colvis',
            collectionLayout: 'fixed four-column'
        }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "createdRow": function (row, data, dataIndex) {
            var pageInfo = table.page.info();
            var serialNumber = pageInfo.start + dataIndex + 1;
            $('td', row).eq(0).html(serialNumber);
        },
        "pagingType": "simple_numbers",
        "drawCallback": function () {
            enableDateColoring();
            searchFunction('due_followup_table');
            paginationFunction('due_followup_table');
        }
    });
    initColVisFeatures(table, 'due_followup_table');
}

function enableDateColoring() {
    //for coloring
    $('#due_followup_table tbody tr').not('th').each(function () {
        let tddate = $(this).find('td:eq(15)').text(); // Get the text content of the 12th td element (Follow date)
        let datecorrection = tddate.split("-").reverse().join("-").replaceAll(/\s/g, ''); // Correct the date format
        let values = new Date(datecorrection); // Create a Date object from the corrected date
        values.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let curDate = new Date(); // Get the current date
        curDate.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let colors = { 'past': 'FireBrick', 'current': 'DarkGreen', 'future': 'CornflowerBlue' }; // Define colors for different date types

        if (tddate != '' && values != 'Invalid Date') { // Check if the extracted date and the created Date object are valid

            if (values < curDate) { // Compare the extracted date with the current date
                $(this).find('td:eq(15)').css({ 'background-color': colors.past, 'color': 'white' }); // Apply styling for past dates
            } else if (values > curDate) {
                $(this).find('td:eq(15)').css({ 'background-color': colors.future, 'color': 'white' }); // Apply styling for future dates
            } else {
                $(this).find('td:eq(15)').css({ 'background-color': colors.current, 'color': 'white' }); // Apply styling for the current date
            }
        }
    });
}

function getSubStsMapping() {
    let subStatus = ['Legal', 'Error', 'OD', 'Pending', 'Current'];
    let editSubStatus = $('#customer_status').val() || '';

    subStatusMultiselect.clearStore();
    $.each(subStatus, function (index, val) {
        let selected = '';
        if (editSubStatus.includes(val)) {
            selected = 'selected';
        }
        let items = [
            { value: val, label: val, selected: selected },
        ]
        subStatusMultiselect.setChoices(items);
        subStatusMultiselect.init();
    });

}

$(document).on('click', 'a.customer-summary', async function(event) {
    event.preventDefault();
    try {
        let cus_id = $(this).data('value');
        let cusid = $(this).data('cusid');
        let cusname = $(this).data('cusname');
        let mobile = $(this).data('mobile');
        $.ajax({
            url: 'verificationFile/customer_feedback_list.php',
            type: 'POST',
            data: { "cus_id": cus_id },
            cache: false,
            success: function (html) {
                $("#feedbackListTable").html(html);
                $('#myLargeModalLabel').text(`Customer Summary ( Aadhaar Number : ${cus_id} | Cus ID : ${cusid}  | Cus Name : ${cusname}  | Mobile : ${mobile} )`);
            }
        });
    } catch (err) {
        console.error(err);
        hideOverlay();
    }
});

const DUE_FOLLOWUP_FILTER_KEY = 'due_followup_filters';

// Save value + visible label so we can restore without an AJAX call
function saveDueFollowupFilters() {
    function getSelected($el) {
        let el = $el[0];
        let label = (el && el.selectedIndex >= 0) ? el.options[el.selectedIndex].text : '';
        return { value: $el.val() || '', label: label };
    }

    let filters = {
        branch: getSelected($('#branch')),
        region: getSelected($('#region')),
        zone:   getSelected($('#zone'))
    };
    sessionStorage.setItem(DUE_FOLLOWUP_FILTER_KEY, JSON.stringify(filters));
}

function getSavedDueFollowupFilters() {
    let saved = sessionStorage.getItem(DUE_FOLLOWUP_FILTER_KEY);
    if (!saved) return null;
    try {
        return JSON.parse(saved);
    } catch (e) {
        return null;
    }
}

// Injects the saved value/label directly as a selected <option> —
// no AJAX needed, so it works immediately on page load.
function restoreSelectValue($select, saved) {
    if (!saved || !saved.value) return;
    if ($select.find('option[value="' + saved.value + '"]').length === 0) {
        $select.append($('<option>', { value: saved.value, text: saved.label || saved.value }));
    }
    $select.val(saved.value);
}