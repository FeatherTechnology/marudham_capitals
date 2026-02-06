<?php
$idupd = 0;
$pageId = 0;
if (isset($_GET['upd'])) {
    $idupd = $_GET['upd'];
}
if (isset($_GET['pageId'])) {
    $pageId = $_GET['pageId'];
}
$getUserDetails = $userObj->getUserDetails($mysqli, $userid);
if ($getUserDetails) {
    $company_id = $getUserDetails['company_id'];
    $user_name = $getUserDetails['fullname'];
    $staff_code = $getUserDetails['staff_code'];
}


$getConcernCreation = $userObj->getConcernCreation($mysqli, $idupd, $userid);
if (count($getConcernCreation) > 0) {
    $id             = $getConcernCreation['id'];
    $raisingFor     = $getConcernCreation['raising_for'];
    $selfName       = $getConcernCreation['self_name'];
    $selfCode       = $getConcernCreation['self_code'];
    $staffName      = $getConcernCreation['staff_name'];
    $staffDept      = $getConcernCreation['staff_dept_name'];
    $staffTeam      = $getConcernCreation['staff_team_name'];
    $agentName      = $getConcernCreation['ag_name'];
    $ag_grp         = $getConcernCreation['ag_grp'];
    $cus_id         = $getConcernCreation['cus_id'];
    $cus_name       = $getConcernCreation['cus_name'];
    $cus_area       = $getConcernCreation['cus_area'];
    $cus_sub_area   = $getConcernCreation['cus_sub_area'];
    $cus_grp        = $getConcernCreation['cus_group'];
    $cus_line       = $getConcernCreation['cus_line'];
    $conDate        = $getConcernCreation['com_date'];
    $conCode        = $getConcernCreation['com_code'];
    // $branchName     = $getConcernCreation['branch_name'];
    // $concernTo      = $getConcernCreation['concern_to'];
    $toDeptName     = $getConcernCreation['to_dept_name'];
    // $toTeamName     = $getConcernCreation['to_team_name'];
    // $concernAgainst     = $getConcernCreation['concern_against'];
    $conSub         = $getConcernCreation['com_sub'];
    $conRemark      = $getConcernCreation['com_remark'];
    // $conPriority    = $getConcernCreation['com_priority'];
    $roleType    = $getConcernCreation['role_type'];
    $assignStaffName      = $getConcernCreation['staff_assign_to'];
    $passRole    = $getConcernCreation['pass_role'];
    $passTo    = $getConcernCreation['pass_to'];
    $solution_date        = $getConcernCreation['solution_date'];
    $communication          = $getConcernCreation['communication'];
    $location          = $getConcernCreation['location'];
    $sol_participants          = $getConcernCreation['sol_participants'];

    $solution_remark      = $getConcernCreation['solution_remark'];
    $insert_user_name      = $getConcernCreation['insert_user_name'];

    $uploads      = $getConcernCreation['uploads'];
    $upds = explode(',', $uploads);
}
?>

<style>
    .img_show {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
        background-color: white;
    }
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Concern Solution
    </div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
    <?php if ($pageId == '3') { ?> <a href="edit_concern_creation"> <?php } elseif ($pageId == '4') { ?><a href="edit_concern_solution"> <?php } ?>
            <button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
            </a>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">

    <!-- Concern Creation form start-->
    <div id="concernDiv">
        <form id="concern_form" name="concern_form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?php if (isset($idupd)) echo $idupd; ?>">
            <input type="hidden" name="pg_id" id="pg_id" value="<?php if (isset($pgid)) echo $pgid; ?>">
            <input type="hidden" name="staff_dept" id="staff_dept" value="<?php if (isset($staffDept)) echo $staffDept; ?>">
            <input type="hidden" name="staff_team" id="staff_team" value="<?php if (isset($staffTeam)) echo $staffTeam; ?>">
            <input type="hidden" name="con_sub" id="con_sub" value="<?php if (isset($conSub)) echo $conSub; ?>">
            <input type="hidden" name="con_against" id="con_against" value="<?php if (isset($concernAgainst)) echo $concernAgainst; ?>">
            <input type="hidden" name="con_staff" id="con_staff" value="<?php if (isset($assignStaffName)) echo $assignStaffName; ?>">
            <input type="hidden" name="con_role" id="con_role" value="<?php if (isset($roleType)) echo $roleType; ?>">
            <input type="hidden" name="company_id" id="company_id" value="<?php if (isset($company_id)) echo $company_id; ?>">
              <input type="hidden" name="pass_role" id="pass_role" value="<?php if (isset($passRole)) echo $passRole; ?>">
            <input type="hidden" name="pass_to" id="pass_to" value="<?php if (isset($passTo)) echo $passTo; ?>">
            <!-- Row start -->
            <div class="row gutters">
                <!-- Concern Creation Start -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">Concern Creation <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="raising">Raising For</label><span class="required">&nbsp;*</span>
                                        <select type="text" class="form-control" id="raising_for" name="raising_for" tabindex='4' disabled>
                                            <option value="">Select Raising For</option>
                                            <option value="1" <?php if (isset($raisingFor) and $raisingFor == '1') echo 'selected'; ?>>Myself</option>
                                            <option value="2" <?php if (isset($raisingFor) and $raisingFor == '2') echo 'selected'; ?>>staff</option>
                                            <option value="3" <?php if (isset($raisingFor) and $raisingFor == '3') echo 'selected'; ?>>Agent</option>
                                            <option value="4" <?php if (isset($raisingFor) and $raisingFor == '4') echo 'selected'; ?>>Customer</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='raisingForCheck'>Please Select Raising For</span>
                                    </div>
                                </div>

                                <?php if (isset($raisingFor) and $raisingFor != '1') { ?>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="created-username">Created User Name</label><span class="required">&nbsp;*</span>
                                            <input type="text" class="form-control" id="created_user_name" name="created_user_name" tabindex='4' value='<?php if (isset($insert_user_name)) echo $insert_user_name; ?>' readonly>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>

                            <div class="row" id="myself" <?php if (isset($raisingFor) and $raisingFor == '1') {
                                                            } else {
                                                                echo 'style="display: none;"';
                                                            } ?>> <!-- When Raising For is Myself Means Myself will show -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="name">Name</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="self_name" name="self_name" tabindex='5' value="<?php if (isset($selfName)) echo $selfName; ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="code">Staff Code</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="self_code" name="self_code" tabindex='6' value="<?php if (isset($selfCode)) echo $selfCode; ?>" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="row" id="staff" <?php if (isset($raisingFor) and $raisingFor == '2') {
                                                        } else {
                                                            echo 'style="display: none;"';
                                                        } ?>> <!-- When Raising For is staff Means staff will show -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="staff_name">Staff Name</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="staff_name" name="staff_name" tabindex='5' value="<?php if (isset($staffName)) echo $staffName; ?>" readonly>
                                        <span class="text-danger" style='display:none' id='staffnameCheck'>Please Enter Staff Name</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="deptname">Department Name </label> <span class="required">&nbsp;*</span>
                                        <select tabindex="6" type="text" class="form-control" id="staff_dept_name" name="staff_dept_name" disabled>
                                            <option value="">Select Department Name</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='staffdeptnameCheck'>Please Select Department</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="teamname">Team Name </label> <span class="required">&nbsp;*</span>
                                        <select tabindex="6" type="text" class="form-control" id="staff_team_name" name="staff_team_name" disabled>
                                            <option value="">Select Team Name</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='staffteamnameCheck'>Please Select Team</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row" id="agent" <?php if (isset($raisingFor) and $raisingFor == '3') {
                                                        } else {
                                                            echo 'style="display: none;"';
                                                        } ?>> <!-- When Raising For is Agent Means Agent will show -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="ag-name">Agent Name</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="ag_name" name="ag_name" tabindex='5' value="<?php if (isset($agentName)) echo $agentName; ?>" readonly>
                                        <span class="text-danger" style='display:none' id='agentnameCheck'>Please Select Agent Name</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="ag-grp">Agent Group</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="ag_grp" name="ag_grp" tabindex='6' value="<?php if (isset($ag_grp)) echo $ag_grp; ?>" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="row" id="customer" <?php if (isset($raisingFor) and $raisingFor == '4') {
                                                            } else {
                                                                echo 'style="display: none;"';
                                                            } ?>> <!-- When Raising For is customer Means customer will show -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="cus-id">Customer ID</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="cus_id" name="cus_id"  maxlength="14" tabindex='5' value="<?php if (isset($cus_id)) echo $cus_id; ?>" readonly>
                                        <span class="text-danger" style='display:none' id='cusIdCheck'>Please Enter Customer ID</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="cus_name">Customer Name</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="cus_name" name="cus_name" tabindex='6' value="<?php if (isset($cus_name)) echo $cus_name; ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="area">Area</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="cus_area" name="cus_area" value="<?php if (isset($cus_area)) echo $cus_area; ?>" readonly tabindex='7'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="subarea">Sub Area</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="cus_sub_area" name="cus_sub_area" value="<?php if (isset($cus_sub_area)) echo $cus_sub_area; ?>" readonly tabindex='8'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="group">Group</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="cus_group" name="cus_group" value="<?php if (isset($cus_grp)) echo $cus_grp; ?>" readonly tabindex='9'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="line">Line</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="cus_line" name="cus_line" value="<?php if (isset($cus_line)) echo $cus_line; ?>" readonly tabindex='10'>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Concern Creation End -->

                    <!-- Concern Assign START -->
                    <div class="card">
                        <div class="card-header">Concern Assign<span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="comdate">Concern Date </label><span class="required">&nbsp;*</span>
                                        <input type="date" class="form-control" id="com_date" name="com_date" tabindex='11' value="<?php if (isset($conDate)) echo $conDate; ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="comcode">Concern Code</label><span class="required">&nbsp;*</span>
                                        <input type="text" class="form-control" id="com_code" name="com_code" value="<?php if (isset($conCode)) echo $conCode; ?>" readonly tabindex='12'>
                                    </div>
                                </div>

                                <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="to">Concern To</label><span class="required">&nbsp;*</span>
                                        <select type="text" class="form-control" id="concern_to" name="concern_to" tabindex='14' disabled>
                                            <option value=""> Select Concern To </option>
                                            <option value="1" <?php if (isset($concernTo) and $concernTo == '1') echo 'selected'; ?>> Department </option>
                                            <option value="2" <?php if (isset($concernTo) and $concernTo == '2') echo 'selected'; ?>> Team </option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='comtoCheck'>Please Select Concern To</span>
                                    </div>
                                </div> -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 dept">
                                    <div class="form-group">
                                        <label for="toname">Department Name </label> <span class="required">&nbsp;*</span>
                                        <input tabindex="15" type="text" class="form-control" id="to_dept_name" name="to_dept_name" value="<?php if (isset($toDeptName)) echo $toDeptName; ?>" readonly>
                                        <span class="text-danger" style='display:none' id='todeptnameCheck'>Please Select Department Name</span>
                                    </div>
                                </div>
                                <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 team">
                                    <div class="form-group">
                                        <label for="toname">Team Name </label> <span class="required">&nbsp;*</span>
                                        <input tabindex="15" type="text" class="form-control" id="to_team_name" name="to_team_name" value="<?php if (isset($toTeamName)) echo $toTeamName; ?>" readonly>
                                        <span class="text-danger" style='display:none' id='toteamnameCheck'>Please Select Team Name</span>
                                    </div>
                                </div> -->

                                <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="concern_against">Concern Against</label><span class="required">&nbsp;*</span>
                                        <select class="form-control" id="concern_against" name="concern_against" tabindex='19' disabled>
                                            <option value="">Select Concern Against</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='concernAgainstcheck'>Please Select Concern Against</span>
                                    </div>
                                </div> -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="comsub">Concern Subject</label><span class="required">&nbsp;*</span>
                                        <select type="text" class="form-control" id="com_sub" name="com_sub" tabindex='16' disabled>
                                            <option value=""> Select Concern Subject </option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='concernsubCheck'>Please Select Concern Subject</span>
                                    </div>
                                </div>

                                <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="com-priority">Concern Priority</label><span class="required">&nbsp;*</span>
                                        <select class="form-control" id="com_priority" name="com_priority" tabindex='18' disabled>
                                            <option value="">Select Concern Priority</option>
                                            <option value='1' <?php if (isset($conPriority) and $conPriority == '1') echo 'selected'; ?>>High</option>
                                            <option value='2' <?php if (isset($conPriority) and $conPriority == '2') echo 'selected'; ?>>Medium</option>
                                            <option value='3' <?php if (isset($conPriority) and $conPriority == '3') echo 'selected'; ?>>Low</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='conpriorityCheck'>Please Select Concern Priority</span>
                                    </div>
                                </div> -->

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="comremark">Concern Remark</label><span class="required">&nbsp;*</span>
                                        <textarea class="form-control" id="com_remark" name="com_remark" tabindex='17' onkeydown="return /[a-z ]/i.test(event.key)" readonly><?php if (isset($conRemark)) echo $conRemark; ?></textarea>
                                        <span class="text-danger" style='display:none' id='comRemarkCheck'>Please Enter Concern Remark</span>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="role_type">Role Type</label><span class="required">&nbsp;*</span>
                                        <select class="form-control" id="role_type" name="role_type" style="<?php echo (!isset($pgid) || $pgid != '1') ? 'pointer-events:none;background:#e9ecef;' : ''; ?>" tabindex='19'>
                                            <option value="">Select Role Type</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='roleTypeCheck'>Please Select Role Type</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="assign-to">Assign To</label><span class="required">&nbsp;*</span>
                                        <select class="form-control" id="staff_assign_to" name="staff_assign_to" style="<?php echo (!isset($pgid) || $pgid != '1') ? 'pointer-events:none;background:#e9ecef;' : ''; ?>" tabindex='19'>
                                            <option value="">Select Assign To</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='staffAssignCheck'>Please Select Staff Assign</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Concern Assign END -->

                    <!-- Consern Solution START-->
                    <div class="card" <?php if (isset($communication) and $communication != '') {
                                                    } else {
                                                        echo 'style="display: none;"';
                                                    } ?>>
                        <div class="card-header"> Concern Solution <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="sol-date"> Solution Date </label> <span class="required">*</span>
                                        <input type="date" class="form-control" name="solution_date" id="solution_date" tabindex="23" value="<?php if (isset($solution_date)) echo date('Y-m-d', strtotime($solution_date)); ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="Communitcation"> Communication </label> <span class="required">*</span>
                                        <select type="text" class="form-control" name="Com_for_solution" id="Com_for_solution" tabindex="20" disabled>
                                            <option value=""> Select Communication </option>
                                            <option value="1" <?php if (isset($communication) && $communication == '1') echo 'selected'; ?>> Phone </option>
                                            <option value="2" <?php if (isset($communication) && $communication == '2') echo 'selected'; ?>> Direct </option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='communicationCheck'>Please Select communication </span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 location-div" <?php if (isset($communication) && $communication == '2') {
                                                                                        } else {
                                                                                            echo 'style="display: none;"';
                                                                                        } ?> >
                                    <div class="form-group">
                                        <label for="location">Location </label><span class="text-danger">*</span>
                                        <select type="text" class="form-control" id="location" name="location" tabindex="8" disabled>
                                            <option value="">Select Location</option>
                                            <option value="1" <?php if (isset($location) && $location == '1') echo 'selected'; ?>>Office</option>
                                            <option value="2" <?php if (isset($location) && $location == '2') echo 'selected'; ?>>On Spot</option>
                                            <option value="3" <?php if (isset($location) && $location == '3') echo 'selected'; ?>>Customer Spot</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='locationCheck'>Please Select Location </span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="sol_participants">Participants</label><span class="text-danger">*</span>
                                        <textarea class="form-control" name="sol_participants" id="sol_participants" placeholder="Enter Participants" disabled tabindex="12"><?php if (isset($sol_participants)) echo $sol_participants; ?></textarea></textarea>
                                        <span class="text-danger" style='display:none' id='participantsCheck'>Please Enter Participants </span>

                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="reamrk"> Solution Remark </label> <span class="required">*</span>
                                        <textarea type="text" class="form-control" name="solution_remark" id="solution_remark" tabindex="22" readonly><?php if (isset($solution_remark)) echo $solution_remark; ?></textarea>
                                        <span class="text-danger" style='display:none' id='solutionRemarkCheck'>Please Enter Solution Remark </span>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" <?php if (isset($communication) && $communication == '1') {
                                                                                        } else {
                                                                                            echo 'style="display: none;"';
                                                                                        } ?> id="solutionUploads">
                                    <div class="form-group">
                                        <label for="Communitcation"> Uploads </label><span class="text-danger">*</span><br>
                                        <?php foreach ($upds as $fileupd) {
                                            if ($fileupd != null) {
                                        ?>
                                                <a href="<?php echo "uploads/concern/" . $fileupd; ?>" target="_blank" download>Click Here To Download Your <?php if (isset($fileupd)) echo $fileupd; ?> File </a> <br><br>
                                        <?php }
                                        } ?>

                                        <span class="text-danger" style='display:none' id='updCheck'>Please Upload </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- Consern Solution END-->

                </div>
            </div>
        </form>
    </div>
    <!-- Concern Creation Form End -->

</div>