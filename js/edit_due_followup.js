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

        OnLoadFunctions(cusSts, comm_date,res_sts);
    });
});

$(function () {
    getSubStsMapping(); //Call Customer status dropdown.

    let cummDate = $("#cummDate").val();
    $("#comm_date").val(cummDate);
    let cus_Sts = $("#customer_status").val();
    let cusSts = cus_Sts.split(',');
    let res_sts = $("#res_sts").val();

    if (cusSts != '') {
        OnLoadFunctions(cusSts, cummDate ,res_sts );
    }
});
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

function OnLoadFunctions(cusSts, comm_date, res_sts) {
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
        "serverMethod": 'post',
        "ajax": {
            "url": 'followupFiles/dueFollowup/getDueFollowCus.php',
            "data": function (data) {
                var search = $('#search').val();
                data.search = search;
                data.cus_sts = cusSts;
                data.comm_date = comm_date;
                data.res_sts = res_sts;
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
        let tddate = $(this).find('td:eq(17)').text(); // Get the text content of the 12th td element (Follow date)
        let datecorrection = tddate.split("-").reverse().join("-").replaceAll(/\s/g, ''); // Correct the date format
        let values = new Date(datecorrection); // Create a Date object from the corrected date
        values.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let curDate = new Date(); // Get the current date
        curDate.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let colors = { 'past': 'FireBrick', 'current': 'DarkGreen', 'future': 'CornflowerBlue' }; // Define colors for different date types

        if (tddate != '' && values != 'Invalid Date') { // Check if the extracted date and the created Date object are valid

            if (values < curDate) { // Compare the extracted date with the current date
                $(this).find('td:eq(17)').css({ 'background-color': colors.past, 'color': 'white' }); // Apply styling for past dates
            } else if (values > curDate) {
                $(this).find('td:eq(17)').css({ 'background-color': colors.future, 'color': 'white' }); // Apply styling for future dates
            } else {
                $(this).find('td:eq(17)').css({ 'background-color': colors.current, 'color': 'white' }); // Apply styling for the current date
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