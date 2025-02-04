$(document).ready(function () {

    $('#Com_for_solution').change(function () {
        let com = $(this).val();

        if (com == '1') {
            $('#solutionUploads').show();
        } else {
            $('#solutionUploads').hide();
        }
    });

    $('#submit_concern_solution').click(function () {
        solutionSubmitValidation();
    });

});

$(function () {

    getDeptName();
    DropDownCourse();

    setTimeout(() => {
        getTeamName();
    }, 500);

}); //OnLoad END.

function getDeptName() {  // To show Department Name.
    var dept = $('#staff_dept').val();
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
                var selected = '';
                if (name != '' && name == dept) {
                    selected = 'selected';
                }
                $('#staff_dept_name').append("<option value='" + name + "' " + selected + "> " + name + " </option>")
            }

        }
    });
}

function getTeamName() {  // To show Team Name.
    var deptName = $('#staff_dept_name').val();
    var staffTeam = $('#staff_team').val();
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
                var selected = '';
                if (name != '' && name == staffTeam) {
                    selected = 'selected';
                }
                $('#staff_team_name').append("<option value='" + name + "' " + selected + " > " + name + " </option>")
            }
        }
    });
}

function DropDownCourse() {
    var sub = $('#con_sub').val();
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
                var selected = '';
                if (concern_sub_id != '' && concern_sub_id == sub) {
                    selected = 'selected';
                }
                $("#com_sub").append("<option value='" + concern_sub_id + "' " + selected + " >" + concern_subject + "</option>");

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

function solutionSubmitValidation() {
    var com = $('#Com_for_solution').val(); var upd = $('#concern_upload').val(); var solutionRemark = $('#solution_remark').val();

    if (com == '') {
        event.preventDefault();
        $('#communicationCheck').show();
    } else {
        $('#communicationCheck').hide();
    }

    if (com == '1') {
        if (upd == '') {
            event.preventDefault();
            $('#updCheck').show();
        } else {
            $('#updCheck').hide();
        }
    }

    if (solutionRemark == '') {
        event.preventDefault();
        $('#solutionRemarkCheck').show();
    } else {
        $('#solutionRemarkCheck').hide();
    }

}