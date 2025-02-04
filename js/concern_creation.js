$(document).ready(function () {
    console.log('sajdgjkds');

    $('#raising_for').change(function () {
        refershInput();
        var raising_for = $('#raising_for').val();

        if (raising_for == '1') { //Myself
            $('#myself').show();
            $('#staff').hide();
            $('#agent').hide();
            $('#customer').hide();
        } else if (raising_for == '2') { //Staff
            $('#myself').hide();
            $('#staff').show();
            $('#agent').hide();
            $('#customer').hide();
            getDeptName(); // To Get Department name
        } else if (raising_for == '3') { //Agent
            $('#myself').hide();
            $('#staff').hide();
            $('#agent').show();
            $('#customer').hide();
            getagentName(); //To Get Agent Name.
        } else if (raising_for == '4') { //Customer
            $('#myself').hide();
            $('#staff').hide();
            $('#agent').hide();
            $('#customer').show();
        } else {
            $('#myself').hide();
            $('#staff').hide();
            $('#agent').hide();
            $('#customer').hide();
        }
    });

    $('#staff_dept_name').change(function () {  // To show Team Name.
        var deptName = $(this).val();
        $.ajax({
            url: 'concernFile/getTeamName.php',
            type: 'POST',
            data: { 'dept': deptName },
            dataType: 'json',
            cache: false,
            success: function (response) {
                $("#staff_team_name").empty();
                $('#staff_team_name').append("<option value=''> Select Team Name </option>")
                let len = response.length;
                for (let i = 0; i < len; i++) {
                    let name = response[i]['teamName'];
                    $('#staff_team_name').append("<option value='" + name + "'> " + name + " </option>")
                }
            }
        });
    });

    $('#ag_name').change(function () { //To Agent Group
        var ag_id = $(this).val();
        $.ajax({
            url: 'concernFile/getAgGroup.php',
            type: 'POST',
            data: { 'ag_id': ag_id },
            dataType: 'json',
            cache: false,
            success: function (response) {
                $("#ag_grp").val('');
                var grp = response['agGroupName'];
                $('#ag_grp').val(grp);
            }
        })
    });

    //Raising For Customer.
    $('input[data-type="adhaar-number"]').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);

        $('#cus_name').val('');
        $('#cus_area').val('');
        $('#cus_sub_area').val('');
        $('#cus_group').val('');
        $('#cus_line').val('');
    });

    $('input[data-type="adhaar-number"]').on("change, blur", function () {
        var value = $(this).val();
        var maxLength = $(this).attr("maxLength");
        if (value.length != maxLength) {
            $(this).addClass("required");
        } else {
            $(this).removeClass("required");

            var cus_id = $(this).val();
            cus_id = cus_id.replace(/\s+/g, '');

            getCustomerDetails(cus_id);
        }
    });

    $('#concern_to').change(function () {
        var to = $(this).val();

        if (to == '1') {
            $('.dept').show();
            $('.team').hide();
            getConcernDeptName(); // To show Dept Name

        } else if (to == '2') {
            $('.dept').hide();
            $('.team').show();
            getconTeamName(); //To Show Team.

        } else {
            $('.dept').hide();
            $('.team').hide();

        }
    });

    $('#to_dept_name').change(function () { // To Staff list based on department
        var deptVal = $(this).val();
        getStaffName('1', deptVal)
    });
    $('#to_team_name').change(function () { // To Staff list based on Team.
        var teamVal = $(this).val();
        getStaffName('2', teamVal)
    });

    $(document).on("click", "#submitConSub", function () {
        var con_sub_id = $("#con_sub_id").val();
        var com_sub_add = $("#com_sub_add").val();
        if (com_sub_add != "") {
            $.ajax({
                url: 'concernFile/InsertConSubject.php',
                type: 'POST',
                data: { "com_sub_add": com_sub_add, "con_sub_id": con_sub_id },
                cache: false,
                success: function (response) {
                    var insresult = response.includes("Exists");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $('#categoryInsertNotOk').show();
                        setTimeout(function () {
                            $('#categoryInsertNotOk').fadeOut('fast');
                        }, 2000);
                    } else if (updresult) {
                        $('#categoryUpdateOk').show();
                        setTimeout(function () {
                            $('#categoryUpdateOk').fadeOut('fast');
                        }, 2000);
                        $("#coursecategoryTable").remove();
                        resetConSubTable();
                        $("#com_sub_add").val('');
                        $("#con_sub_id").val('');
                    }
                    else {
                        $('#categoryInsertOk').show();
                        setTimeout(function () {
                            $('#categoryInsertOk').fadeOut('fast');
                        }, 2000);
                        $("#coursecategoryTable").remove();
                        resetConSubTable();
                        $("#com_sub_add").val('');
                        $("#con_sub_id").val('');
                    }
                }
            });
            $("#comsubCheck").hide();
        }
        else {
            $("#comsubCheck").show();
        }
    });

    $("body").on("click", "#edit_subject", function () {
        var id = $(this).attr('value');
        $("#id").val(id);
        $.ajax({
            url: 'concernFile/editConcernSubject.php',
            type: 'POST',
            data: { "id": id },
            cache: false,
            success: function (response) {
                $("#com_sub_add").val(response);
            }
        });
    });

    $("body").on("click", "#delete_subject", function () {
        var isok = confirm("Do you want delete Concern Subject?");
        if (isok == false) {
            return false;
        } else {
            var id = $(this).attr('value');
            var c_obj = $(this).parents("tr");
            $.ajax({
                url: 'concernFile/deleteConcernSubject.php',
                type: 'POST',
                data: { "id": id },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Rights");
                    if (delresult) {
                        $('#categoryDeleteNotOk').show();
                        setTimeout(function () {
                            $('#categoryDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        c_obj.remove();
                        $('#categoryDeleteOk').show();
                        setTimeout(function () {
                            $('#categoryDeleteOk').fadeOut('fast');
                        }, 2000);
                    }
                }
            });
        }
    });

    //Submit Validation 
    $('#submit_concern').click(function () {
        submitValidation();
    });


}); //Document END.

$(function () {
    $('#coursecategoryTable').DataTable({
        'processing': true,
        'iDisplayLength': 5,
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

    getBranchName(); // To Show Branch Name List.
    DropDownCourse(); //To Show Concern Subject.
    resetConSubTable(); //To Reset.

    getConcernCode(); //Auto Generate Concern Code.

}); //OnLoad END.

function refershInput() {

    $('#staff_name').val('');
    $('#staff_team_name').val('');
    $('#ag_name').val('');
    $('#ag_grp').val('');
    $('#cus_id').val('');
    $('#cus_name').val('');
    $('#cus_area').val('');
    $('#cus_sub_area').val('');
    $('#cus_group').val('');
    $('#cus_line').val('');
}

function getDeptName() {  // To show Department Name.

    $.ajax({
        url: 'concernFile/getdepartmentname.php',
        type: 'POST',
        data: {},
        dataType: 'json',
        cache: false,
        success: function (response) { //showing all department no Restriction based on company.
            $("#staff_dept_name").empty();
            $('#staff_dept_name').append("<option value=''> Select Department Name </option>")
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let name = response[i]['deptName'];
                $('#staff_dept_name').append("<option value='" + name + "'> " + name + " </option>")
            }

        }
    });
}

function getagentName() {  // To show Department Name.
    var companyID = $('#company_id').val();
    $.ajax({
        url: 'concernFile/getAgentName.php',
        type: 'POST',
        data: { 'companyID': companyID },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#ag_name").empty();
            $('#ag_name').append("<option value=''> Select Agent Name </option>")
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var name = response[i]['agName'];
                var ag_id = response[i]['ag_id'];
                $('#ag_name').append("<option value='" + ag_id + "'> " + name + " </option>")
            }

        }
    });
}

//Get Customer Details
function getCustomerDetails(cus_id) {
    $.ajax({
        url: 'requestFile/getCustomerDetail.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response['message'] == 'Existing') {
                $('#cus_name').val(response['cus_name']); //
                $('#cus_area').val(response['area_name']);//
                $('#cus_sub_area').val(response['sub_area_name']);//
                $('#cus_group').val(response['grp_name']);//
                $('#cus_line').val(response['line_name']);//
            }
        }
    });
}

//Get Concern code 
function getConcernCode() {
    $.ajax({
        url: 'concernFile/comCode_autoGen.php',
        type: "post",
        dataType: "json",
        data: {},
        cache: false,
        success: function (response) {
            var docId = response;
            $('#com_code').val(docId);
        }
    })
}

function getBranchName() {
    var companyID = $('#company_id').val();
    $.ajax({
        url: 'concernFile/getBranchName.php',
        type: 'POST',
        data: { 'companyID': companyID },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#branch_name").empty();
            $('#branch_name').append("<option value=''> Select Branch Name </option>")
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var id = response[i]['branchID'];
                var name = response[i]['branchName'];
                $('#branch_name').append("<option value='" + id + "'> " + name + " </option>")
            }

        }
    });

}

function getConcernDeptName() {  // To show Department Name.
    var companyID = $('#company_id').val();
    $.ajax({
        url: 'concernFile/getConernDeptName.php',
        type: 'POST',
        data: { 'companyID': companyID },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#to_dept_name").empty();
            $('#to_dept_name').append("<option value=''> Select Department Name </option>")
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let name = response[i]['deptName'];
                $('#to_dept_name').append("<option value='" + name + "'> " + name + " </option>")
            }

        }
    });
}

function getconTeamName() {  // To show Team Name.
    var companyID = $('#company_id').val();
    $.ajax({
        url: 'concernFile/getConcernTeamName.php',
        type: 'POST',
        data: { 'companyID': companyID },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#to_team_name").empty();
            $('#to_team_name').append("<option value=''> Select Team Name </option>")
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let name = response[i]['teamName'];
                $('#to_team_name').append("<option value='" + name + "'> " + name + " </option>")
            }
        }
    });
}

function getStaffName(type, staffFrom) {  // To show Staff Name.

    var companyID = $('#company_id').val();
    $.ajax({
        url: 'concernFile/getStaffName.php',
        type: 'POST',
        data: { 'companyID': companyID, 'type': type, 'staffFrom': staffFrom },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $("#staff_assign_to").empty();
            $('#staff_assign_to').append("<option value=''> Select Staff Name </option>")
            let len = response.length;
            for (let i = 0; i < len; i++) {
                let id = response[i]['staffID'];
                let name = response[i]['staffName'];
                $('#staff_assign_to').append("<option value='" + id + "'> " + name + " </option>")
            }
        }
    });
}

function resetConSubTable() {
    $.ajax({
        url: 'concernFile/resetConcernSubTable.php',
        type: 'POST',
        data: {},
        cache: false,
        success: function (html) {
            $("#updatedconSubTable").empty();
            $("#updatedconSubTable").html(html);
        }
    });
}

function DropDownCourse() {
    $.ajax({
        url: 'concernFile/getConSub.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#com_sub").empty();
            $("#com_sub").append("<option value=''>" + 'Select Concern Subject' + "</option>");
            for (var i = 0; i < len; i++) {
                var concern_sub_id = response[i]['concern_sub_id'];
                var concern_subject = response[i]['concern_subject'];
                $("#com_sub").append("<option value='" + concern_sub_id + "'>" + concern_subject + "</option>");

            }
            {//To Order Alphabetically
                var firstOption = $("#com_sub option:first-child");
                $("#com_sub").html($("#com_sub option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#com_sub").prepend(firstOption);
            }

        }
    });
}

function submitValidation() {
    var raising = $('#raising_for').val();
    var staff_name = $('#staff_name').val();
    var staff_dept_name = $('#staff_dept_name').val();
    var staff_team_name = $('#staff_team_name').val();
    var ag_name = $('#ag_name').val();
    var cus_id = $('#cus_id').val();
    var branch_name = $('#branch_name').val();
    var concern_to = $('#concern_to').val(); console.log(concern_to);
    var to_dept_name = $('#to_dept_name').val();
    var to_team_name = $('#to_team_name').val();
    var com_sub = $('#com_sub').val();
    var com_remark = $('#com_remark').val();
    var com_priority = $('#com_priority').val();
    var staff_assign_to = $('#staff_assign_to').val();

    if (raising == '') {
        event.preventDefault();
        $('#raisingForCheck').show();
    } else {
        $('#raisingForCheck').hide();
    }

    if (raising == '2') {
        if (staff_name == '') {
            event.preventDefault();
            $('#staffnameCheck').show();
        } else {
            $('#staffnameCheck').hide();
        }
        if (staff_dept_name == '') {
            event.preventDefault();
            $('#staffdeptnameCheck').show();
        } else {
            $('#staffdeptnameCheck').hide();
        }
        if (staff_team_name == '') {
            event.preventDefault();
            $('#staffteamnameCheck').show();
        } else {
            $('#staffteamnameCheck').hide();
        }
    }

    if (raising == '3') {
        if (ag_name == '') {
            event.preventDefault();
            $('#agentnameCheck').show();
        } else {
            $('#agentnameCheck').hide();
        }
    }

    if (raising == '4') {
        if (cus_id == '') {
            event.preventDefault();
            $('#cusIdCheck').show();
        } else {
            $('#cusIdCheck').hide();
        }
    }

    if (branch_name == '') {
        event.preventDefault();
        $('#branchCheck').show();
    } else {
        $('#branchCheck').hide();
    }
    if (concern_to == '') {
        event.preventDefault();
        $('#comtoCheck').show();
    } else {
        $('#comtoCheck').hide();
    }

    if (concern_to == '1') {
        if (to_dept_name == '') {
            event.preventDefault();
            $('#todeptnameCheck').show();
        } else {
            $('#todeptnameCheck').hide();
        }
    }

    if (concern_to == '2') {
        if (to_team_name == '') {
            event.preventDefault();
            $('#toteamnameCheck').show();
        } else {
            $('#toteamnameCheck').hide();
        }
    }

    if (com_sub == '') {
        event.preventDefault();
        $('#concernsubCheck').show();
    } else {
        $('#concernsubCheck').hide();
    }
    if (com_remark == '') {
        event.preventDefault();
        $('#comRemarkCheck').show();
    } else {
        $('#comRemarkCheck').hide();
    }
    if (com_priority == '') {
        event.preventDefault();
        $('#conpriorityCheck').show();
    } else {
        $('#conpriorityCheck').hide();
    }
    if (staff_assign_to == '') {
        event.preventDefault();
        $('#staffAssignCheck').show();
    } else {
        $('#staffAssignCheck').hide();
    }

}