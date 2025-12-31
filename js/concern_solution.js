$(document).ready(function () {

    $('#Com_for_solution').change(function () {
        let com = $(this).val();
        $('.location-div').hide();
        $('#solutionUploads').hide();
        $('#location').val('')
        $('#concern_upload').val('')

        if (com == '1') {
            $('#solutionUploads').show();
            $('.location-div').hide();
        } else if (com == '2') {
            $('#solutionUploads').hide();
            $('.location-div').show();
        }
    });

    $('#submit_concern_solution').click(function () {
        solutionSubmitValidation();
    });

    // $('#to_dept_name').change(function () { // To Staff list based on department
    //     var deptVal = $(this).val();
    //     getStaffName('1', deptVal)
    // });
    // $('#to_team_name').change(function () { // To Staff list based on Team.
    //     var teamVal = $(this).val();
    //     getStaffName('2', teamVal)
    // });

    $('#role_type').change(function () {
        var role_type = $(this).val();
        if (role_type !== '' && role_type != 0) {
            getAssignName(role_type)
        } else {
            $('#staff_assign_to').val('');
        }
    })

});

$(function () {

    getDeptName();
    DropDownCourse();
    // let concern_to = $('#concern_to').val();
    // if (concern_to == '1') {
    //     $('#to_dept_name').trigger('change')
    // } else if (concern_to == '2') {
    // $('#to_team_name').trigger('change')
    // }
    getConcernRoleType();
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
            // Sort com_sub dropdown
            sortDropdownAlphabetically("#com_sub");

        }
    });
}
function getStaffName(type, staffFrom) {

    var companyID = $('#company_id').val();
    var selectedConcern = $('#con_against').val(); // get stored value

    $.ajax({
        url: 'concernFile/getStaffName.php',
        type: 'POST',
        data: { companyID, type, staffFrom },
        dataType: 'json',
        success: function (response) {

            $("#concern_against").empty();
            $('#concern_against').append("<option value=''>Select Concern Against</option>");

            $.each(response, function (i, row) {
                let selected = (row.staffID == selectedConcern) ? 'selected' : '';
                $('#concern_against').append(
                    `<option value="${row.staffID}" ${selected}>${row.staffName}</option>`
                );
            });
        }
    });
}
// concern Role Type
function getConcernRoleType() {
    let pg_id = $('#pg_id').val();
    let concern_role_id = '';
    if (pg_id != '1') {
        let concern_role = $('#con_role').val();
        let pass_role = $('#pass_role').val();

        // if pass_role is empty or null, use concern_role
        concern_role_id = (pass_role && pass_role.trim() !== '')
            ? pass_role
            : concern_role;
    }

    let role_type = 'Director,Admin,Manager,TL,Training TL,Executive Director';

    $.post(
        'concernFile/getConcernRoleType.php',
        {
            role_type: role_type,
            selected_id: concern_role_id
        },
        function (response) {

            let html = '<option value="">Select Role Type</option>';

            $.each(response, function (index, val) {
                let selected = (val.staff_type_id == concern_role_id) ? 'selected' : '';
                html += `<option value="${val.staff_type_id}" ${selected}>${val.staff_type_name}</option>`;
            });

            $('#role_type').html(html);

            if (concern_role_id) {
                $('#role_type').trigger('change');
            }
        },
        'json'
    );
}


// Assign Concern
function getAssignName(staff_name_id) {

    let pg_id = $('#pg_id').val();
    let staff_assign_to = $('#con_staff').val(); //  assigned staff
    let pass_to = $('#pass_to').val(); // pass assigned staff
 
     const staff_assign_id = (pass_to && pass_to.trim() !== '')
        ? pass_to
        : staff_assign_to;

    // selected id only needed in edit mode
    const selectedId = (pg_id != '1') ? staff_assign_id : '';

    return $.ajax({
        url: 'manageUser/ajaxGetStaffName.php',
        type: 'POST',
        data: { role_type: staff_name_id },
        dataType: 'json',
        cache: false
    })
        .done(function (response) {

            let html = '<option value="">Select Pass To</option>';

            $.each(response, function (index, val) {

                // ❌ Skip if same staff already assigned
                if (pg_id == '1' && val.staff_id == staff_assign_id) {
                    return true; // continue loop
                }


                let selected = (val.staff_id == selectedId) ? 'selected' : '';
                html += `<option value="${val.staff_id}" ${selected}>${val.staff_name}</option>`;
            });

            $('#staff_assign_to').html(html);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error('getAssignName failed:', textStatus, errorThrown);
        });
}




function solutionSubmitValidation() {
    let pg_id = $('#pg_id').val();
    var com = $('#Com_for_solution').val(); var upd = $('#concern_upload').val(); var solutionRemark = $('#solution_remark').val();
    var role_type = $('#role_type').val();
    var staff_assign_to = $('#staff_assign_to').val();
    var location = $('#location').val();
    var sol_participants = $('#sol_participants').val();
    if (pg_id != '1') {
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
        } else if (com == '2') {
            if (location == '') {
                event.preventDefault();
                $('#locationCheck').show();
            } else {
                $('#locationCheck').hide();
            }
        }

        if (sol_participants == '') {
            event.preventDefault();
            $('#participantsCheck').show();
        } else {
            $('#participantsCheck').hide();
        }
        if (solutionRemark == '') {
            event.preventDefault();
            $('#solutionRemarkCheck').show();
        } else {
            $('#solutionRemarkCheck').hide();
        }
    } else {
        if (role_type == '') {
            event.preventDefault();
            $('#roleTypeCheck').show();
        } else {
            $('#roleTypeCheck').hide();
        }
        if (staff_assign_to == '') {
            event.preventDefault();
            $('#staffAssignCheck').show();
        } else {
            $('#staffAssignCheck').hide();
        }
    }

}