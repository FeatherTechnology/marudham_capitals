<?php 
@session_start();
if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
}

$id=0;
$user_id        = '';
$full_name      = '';
$user_name      = '';
$password       = '';
$role           = '';
$role_type           = '';
$dir_name           = '';
$ag_name           = '';
$staff_name           = '';
$company_id           = '';
$branch_id           = '';
$bank_access           = '';
$line_id           = '';
$group_id           = '';
$download_access = '';
$report_access = '';
$home_access = '';
$promotion_access = '';
$promotion_activity_mapping_access = '';
$mastermodule    = '';
$company_creation      = '';
$branch_creation = '';
$loan_category ='';
$loan_calculation   = '';
$loan_scheme   = '';
$area_creation        = '';
$area_mapping        = '';
$area_status        = '';
$adminmodule = '';
$director_creation = '';
$agent_creation = '';
$staff_creation = '';
$manage_user = '';
$doc_mapping = '';
$bank_creation = '';
$requestmodule = '';
$request = '';
$request_list_access = '';
$verificationmodule = '';
$verification = '';
$approvalmodule = '';
$approval = '';
$acknowledgementmodule = '';
$acknowledgement = '';
$loanissuemodule = '';
$loan_issue = '';
$collectionmodule = '';
$collection = '';
$closedmodule = '';
$closed = '';
$nocmodule = '';
$noc = '';
$noc_mapping_access = '';
$doctrackmodule = '';
$doctrack = '';
$doc_rec_access = '';
$updatemodule = '';
$update_screen = '';
$concernmodule = '';
$concern_creation = '';
$concern_solution = '';
$concern_feedback = '';
$accountsmodule = '';
$cash_tally = '';
$due_followup_lines='';
$cash_tally_admin = '';
$bank_details = '';
$bank_clearance = '';
$bnk_clr_upl_acc = '';
$finance_insight = '';
$accounts_loan_issue = '';
$followupmodule = '';
$promotion_activity = '';
$loan_followup  = '';
$conf_followup  = '';
$due_followup  = '';
$ecs_followup  = '';
$reportmodule = '';
$work_report_module = '';
$monitor_report_module = '';
$analysis_report_module = '';
$accounts_report_module = '';
$reportmodule_intrest = '';
$intrest_ledger_report = '';
$intrest_loan_issue_report = '';
$intrest_collection_report = '';
$intrest_balance_report = '';
$intrest_closed_report = '';
$ledger_report = '';
$request_report = '';
$cancel_revoke_report = '';
$cus_profile_report = '';
$loan_issue_report = '';
$collection_report = '';
$principal_interest_report = '';
$balance_report = '';
$due_list_report = '';
$in_closed_report = '';
$closed_report = '';
$confirmation_followup_report = '';
$agent_report = '';
$no_due_pay_report = '';
$other_trans_report = '';
$day_end_report = '';
$cash_tally_activity_report = '';
$due_followup_customer_count_report = '';
$commitment_report = '';
$customer_status_report = '';
$promotion_activity_report = '';
$cleared_report = '';
$events_report = '';
$area_loan_count_report = '';
$work_count_report = '';
$noc_handover_report = '';
$confirmation_count_report = '';
$concern_report  = '';
$partners_report  = '';
$search_module = '';
$search_screen = '';
$bulk_upload_module = '';
$bulk_upload = '';
// $loan_track_module = '';
$loan_track = '';
$sms_module = '';
$sms_generation = '';

$agentNameList = $userObj->getagentNameList($mysqli);

if(isset($_POST['submit_manage_user']) && $_POST['submit_manage_user'] != '')
{
    if(isset($_POST['id']) && $_POST['id'] >0 && is_numeric($_POST['id'])){		
        $id = $_POST['id']; 	
		$userObj->updateUser($mysqli,$id, $userid);  
    ?>
	<script>location.href='<?php echo $HOSTPATH;  ?>edit_manage_user&msc=2';</script>
    <?php	}
    else{   
		$userObj->addUser($mysqli, $userid);   
        ?>
    <script>location.href='<?php echo $HOSTPATH;  ?>edit_manage_user&msc=1';</script>
        <?php
    }
}

$del=0;
$costcenter=0;
if(isset($_GET['del']))
{
$del=$_GET['del'];
}
if($del>0)
{
	$userObj->deleteUser($mysqli,$del, $userid); 
?>
	<script>location.href='<?php echo $HOSTPATH;  ?>edit_manage_user&msc=3';</script>
<?php	
}

if(isset($_GET['upd']))
{
$idupd=$_GET['upd'];
}
$status =0;
if($idupd>0)
{
	$getUser = $userObj->getuser($mysqli,$idupd); 
	if (sizeof($getUser)>0) {
        for($i=0;$i<sizeof($getUser);$i++)  {			
			$user_id                 	 = $getUser['user_id'];
			$fullname          		     = $getUser['fullname'];
			$user_name          		     = $getUser['user_name'];
			$password          		     = $getUser['user_password'];
			$role          		     = $getUser['role'];
			$role_type          		     = $getUser['role_type'];
			$dir_id          		     = $getUser['dir_id'];
			$ag_id          		     = $getUser['ag_id'];
			$staff_id          		     = $getUser['staff_id'];
			$company_id          		     = $getUser['company_id'];
			$branch_id          		     = $getUser['branch_id'];
			$bank_access          		     = $getUser['bank_access'];
			$ver_loan_cat          		     = $getUser['ver_loan_cat'];
			$ver_group_id          		     = $getUser['ver_group_id'];
			$app_loan_cat          		     = $getUser['app_loan_cat'];
			$ack_loan_cat          		     = $getUser['ack_loan_cat'];
			$agentforstaff          		     = $getUser['agentforstaff'];
			$line_id          		     = $getUser['line_id'];
			$group_id          		     = $getUser['group_id'];
			$download_access          		     = $getUser['download_access'];
			$report_access          		     = $getUser['report_access'];
			$home_access          		     = $getUser['home_access'];
			$promotion_access          		     = $getUser['promotion_access'];
			$promotion_activity_mapping_access = $getUser['promotion_activity_mapping_access'];
			$mastermodule          		     = $getUser['mastermodule'];
			$company_creation          		     = $getUser['company_creation'];
			$branch_creation          		     = $getUser['branch_creation'];
			$loan_category          		     = $getUser['loan_category'];
			$loan_calculation          		     = $getUser['loan_calculation'];
			$loan_scheme          		     = $getUser['loan_scheme'];
			$area_creation          		     = $getUser['area_creation'];
			$area_mapping          		     = $getUser['area_mapping'];
			$area_status          		     = $getUser['area_approval'];
			$adminmodule          		     = $getUser['adminmodule'];
			$director_creation          		     = $getUser['director_creation'];
			$agent_creation          		     = $getUser['agent_creation'];
			$staff_creation          		     = $getUser['staff_creation'];
			$manage_user          		     = $getUser['manage_user'];
			$doc_mapping          		     = $getUser['doc_mapping'];
			$bank_creation          		     = $getUser['bank_creation'];
			$requestmodule          		     = $getUser['requestmodule'];
			$request          		     = $getUser['request'];
			$request_list_access          		     = $getUser['request_list_access'];
			$verificationmodule          		     = $getUser['verificationmodule'];
			$verification          		     = $getUser['verification'];
			$approvalmodule          		     = $getUser['approvalmodule'];
			$approval          		     = $getUser['approval'];
			$acknowledgementmodule          		     = $getUser['acknowledgementmodule'];
			$acknowledgement          		     = $getUser['acknowledgement'];
			$loanissuemodule          		     = $getUser['loanissuemodule'];
			$loan_issue          		     = $getUser['loan_issue'];
			$collectionmodule          		     = $getUser['collectionmodule'];
			$collection          		     = $getUser['collection'];
			$collection_access          		     = $getUser['collection_access'];
			$closedmodule          		     = $getUser['closedmodule'];
			$closed          		     = $getUser['closed'];
			$nocmodule          		     = $getUser['nocmodule'];
			$noc          		     	= $getUser['noc'];
			$noc_handover          		     = $getUser['noc_handover'];
			$noc_replace          		     = $getUser['noc_replace'];
			$noc_replace_access          		     = $getUser['noc_replace_access'];
			$noc_mapping_access          		     = $getUser['noc_mapping_access'];
			$doctrackmodule 				= $getUser['doctrackmodule'];
			$doctrack 				= $getUser['doctrack'];
			$doc_rec_access 				= $getUser['doc_rec_access'];
			$updatemodule 				= $getUser['updatemodule'];
			$update_screen 				= $getUser['update_screen'];
			$update_screen_id 				= $getUser['update_screen_id'];
			$concernmodule          		     = $getUser['concernmodule'];
			$concern_creation          		     = $getUser['concern_creation'];
			$concern_solution          		     = $getUser['concern_solution'];
			$concern_feedback          		     = $getUser['concern_feedback'];
			$accountsmodule          		     = $getUser['accountsmodule'];
			$cash_tally          		     = $getUser['cash_tally'];
			$cash_tally_admin          		     = $getUser['cash_tally_admin'];
			$bank_details          		     = $getUser['bank_details'];
			$bank_clearance          		     = $getUser['bank_clearance'];
			$bnk_clr_upl_acc          		     = $getUser['bnk_clr_upl_acc'];
			$finance_insight          		     = $getUser['finance_insight'];
			$accounts_loan_issue          		     = $getUser['accounts_loan_issue'];
			$followupmodule          		     = $getUser['followupmodule'];
			$promotion_activity = $getUser['promotion_activity'];
			$loan_followup = $getUser['loan_followup'];
			$conf_followup = $getUser['confirmation_followup'];
			$due_followup = $getUser['due_followup'];
			$ecs_followup = $getUser['ecs_followup'];
			$due_followup_lines = $getUser['due_followup_lines'];
			
			$reportmodule          		     = $getUser['reportmodule'];
			$work_report_module         		     = $getUser['work_report_module'];
			$monitor_report_module          		     = $getUser['monitor_report_module'];
			$analysis_report_module          		     = $getUser['analysis_report_module'];
			$accounts_report_module          		     = $getUser['accounts_report_module'];
			$reportmodule_intrest          		     = $getUser['reportmodule_intrest'];
			$intrest_ledger_report          		     = $getUser['intrest_ledger_report'];
			$intrest_loan_issue_report          		     = $getUser['intrest_loan_issue_report'];
			$intrest_collection_report          		     = $getUser['intrest_collection_report'];
			$intrest_balance_report          		     = $getUser['intrest_balance_report'];
			$intrest_closed_report          		     = $getUser['intrest_closed_report'];
			$ledger_report          		     = $getUser['ledger_report'];
			$request_report          		     = $getUser['request_report'];
			$cancel_revoke_report          		     = $getUser['cancel_revoke_report'];
			$cus_profile_report          		     = $getUser['cus_profile_report'];
			$loan_issue_report          		     = $getUser['loan_issue_report'];
			$collection_report          		     = $getUser['collection_report'];
			$principal_interest_report          		     = $getUser['principal_interest_report'];
			$balance_report          		     = $getUser['balance_report'];
			$due_list_report          		     = $getUser['due_list_report'];
			$in_closed_report          		     = $getUser['in_closed_report'];
			$closed_report          		     = $getUser['closed_report'];
			$confirmation_followup_report          		     = $getUser['confirmation_followup_report'];
			$agent_report          		     = $getUser['agent_report'];
			$no_due_pay_report          		     = $getUser['no_due_pay_report'];
			$other_trans_report          		     = $getUser['other_trans_report'];
			$day_end_report          		     = $getUser['day_end_report'];
			$cash_tally_activity_report          		     = $getUser['cash_tally_activity_report'];
			$due_followup_customer_count_report  = $getUser['due_followup_customer_count_report'];
			$commitment_report  = $getUser['commitment_report'];
			$customer_status_report  = $getUser['customer_status_report'];
			$promotion_activity_report  = $getUser['promotion_activity_report'];
			$cleared_report  = $getUser['cleared_report'];
			$events_report  = $getUser['events_report'];
			$area_loan_count_report  = $getUser['area_loan_count_report'];
			$work_count_report  = $getUser['work_count_report'];
			$noc_handover_report  = $getUser['noc_handover_report'];
			$confirmation_count_report  = $getUser['confirmation_count_report'];
			$concern_report   = $getUser['concern_report'];
			$partners_report   = $getUser['partners_report'];
			$search_module = $getUser['search_module'];
			$search_screen = $getUser['search'];
			$bulk_upload_module = $getUser['bulk_upload_module'];
			$bulk_upload = $getUser['bulk_upload'];
			// $loan_track_module = $getUser['loan_track_module'];
			$loan_track = $getUser['loan_track'];
			$sms_module = $getUser['sms_module'];
			$sms_generation = $getUser['sms_generation'];
		}
	}
}
?>

<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals -  Manage User 
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	
    <a href="edit_manage_user">
        <button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
    </a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id = "manage_user_form" name="manage_user_form" action="" method="post" enctype="multipart/form-data"> 
		<input type="hidden" class="form-control" value="<?php if(isset($idupd)) echo $idupd; ?>"  id="id" name="id">
		<input type="hidden" class="form-control" value="<?php if(isset($user_id)) echo $user_id; ?>"  id="user_id_upd" name="user_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($role)) echo $role; ?>"  id="role_upd" name="role_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($role_type)) echo $role_type; ?>"  id="role_type_upd" name="role_type_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($dir_id)) echo $dir_id; ?>"  id="dir_id_upd" name="dir_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($ag_id)) echo $ag_id; ?>"  id="ag_id_upd" name="ag_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($staff_id)) echo $staff_id; ?>"  id="staff_id_upd" name="staff_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($company_id)) echo $company_id; ?>"  id="company_id_upd" name="company_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($branch_id)) echo $branch_id; ?>"  id="branch_id_upd" name="branch_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($agentforstaff)) echo $agentforstaff; ?>"  id="agentforstaff_upd" name="agentforstaff_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($ver_loan_cat)) echo $ver_loan_cat; ?>"  id="ver_loan_cat_upd" name="ver_loan_cat_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($ver_group_id)) echo $ver_group_id; ?>"  id="ver_group_id_upd" name="ver_group_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($app_loan_cat)) echo $app_loan_cat; ?>"  id="app_loan_cat_upd" name="app_loan_cat_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($ack_loan_cat)) echo $ack_loan_cat; ?>"  id="ack_loan_cat_upd" name="ackloan_cat_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($agent_id)) echo $agent_id; ?>"  id="agent_id_upd" name="agent_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($line_id)) echo $line_id; ?>"  id="line_id_upd" name="line_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($group_id)) echo $group_id; ?>"  id="group_id_upd" name="group_id_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($bank_details)) echo $bank_details; ?>"  id="bank_details_upd" name="bank_details_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($promotion_access)) echo $promotion_access; ?>"  id="promotion_access_upd" name="promotion_access_upd">
		<input type="hidden" class="form-control" value="<?php if(isset($due_followup_lines)) echo $due_followup_lines; ?>"  id="due_followup_lines_upd" name="due_followup_lines_upd">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Add User</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 "> 
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="role">Role</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="1" type="text" class="form-control" id="role" name="role"  >
												<option value="">Select role</option>   
												<option value="1" <?php if(isset($role)) if($role == '1') echo 'selected'; ?>>Director</option>   
												<option value="2" <?php if(isset($role)) if($role == '2') echo 'selected'; ?>>Agent</option>   
												<option value="3" <?php if(isset($role)) if($role == '3') echo 'selected'; ?>>Staff</option>   
											</select> 
											<span class="text-danger" style='display:none' id='roleCheck'>Please select Role</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group role_type" style="display:none">
                                            <label for="role_type">Role Type</label>&nbsp;<span class="text-danger">*</span>
                                            <select tabindex="2" type="text" class="form-control" id="role_type" name="role_type" >
												<option value="">Select Role Type</option>
											</select> 
											<span class="text-danger" style='display:none' id='roleTypeCheck'>Please select Role Type</span>
                                        </div>
                                        <div class="form-group agent" style="display:none">
                                            <label for="ag_name">Agent Name</label>&nbsp;<span class="text-danger">*</span>
                                            <select tabindex="2" type="text" class="form-control" id="ag_name" name="ag_name" >
												<option value="">Select Agent Name</option>
												<?php if (sizeof($agentNameList)>0) { 
													for($j=0;$j<count($agentNameList);$j++) { ?>
														<option <?php if(isset($ag_id)) { if($agentNameList[$j]['ag_id'] == $ag_id )  echo 'selected'; }  ?> value="<?php echo $agentNameList[$j]['ag_id']; ?>">
														<?php echo $agentNameList[$j]['ag_name'];?></option>
												<?php }} ?> 
											</select> 
											<span class="text-danger" style='display:none' id='agnameCheck'>Please select Agent Name</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12" >
                                        <div class="form-group director" style="display:none">
                                            <label for="dir_name">Director Name</label>&nbsp;<span class="text-danger">*</span>
                                            <select tabindex="3" type="text" class="form-control" id="dir_name" name="dir_name" >
												<option value="">Select Director Name</option>
											</select> 
											<span class="text-danger" style='display:none' id='dirnameCheck'>Please select Director Name</span>
                                        </div>
										<div class="form-group staff" style="display:none">
                                            <label for="staff_name">Staff Name</label>&nbsp;<span class="text-danger">*</span>
                                            <select tabindex="3" type="text" class="form-control" id="staff_name" name="staff_name" >
												<option value="">Select Staff Name</option>
											</select> 
											<span class="text-danger" style='display:none' id='staffnameCheck'>Please select Staff Name</span>
                                        </div>
                                    </div>
									<br><br><br><br><br><br>
									<div class="col-md-12 userInfoTable" style='display:none'> 
										<div class="row">
											<div style=" width:100%; padding:12px; font-size: 17px; font-weight:bold; border-radius:5px;">User Details</div>
											<table id="userInfoTable" class="table custom-table">
												<thead>
													<tr>
														<th>ID</th>
														<th>Name</th>
														<th>Mail ID</th>
														
													</tr>
												</thead>
												
											</table>
										</div>
										<div class="row conditionalInfo" style='display:none'>
											<div style=" width:100%; padding:12px; font-size: 17px; font-weight:bold; border-radius:5px;">Conditional Info</div>
											<table id="conditionalInfo" class="table custom-table">
												<thead>
													<tr>
														<th>Loan Category</th>
														<th>Sub Category</th>
														<th>Scheme</th>
														<th>Loan Payment</th>
														<th>Responsible</th>
														<th>Collection Point</th>
													</tr>
												</thead>
												
											</table>
										</div>
										<div class="row occupationInfo" style='display:none'>
											<div style=" width:100%; padding:12px; font-size: 17px; font-weight:bold; border-radius:5px;">Occupation Info</div>
											<table id="occupationInfo" class="table custom-table">
												<thead>
													<tr>
														<th>Company Name</th>
														<th>Department</th>
														<th>Team</th>
														<th>Designation</th>
													</tr>
												</thead>
												
											</table>
										</div>
									</div>
									
									
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" class="form-control" id='full_name' name='full_name' >
				<input type="hidden" class="form-control" id='email' name='email' >
				<div class="card">
					<!-- <div class="card-header">
						<div class="card-title"></div>
					</div> -->
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 "> 
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="user_id">User ID</label>&nbsp;<span class="text-danger">*</span>
											<input type='text' class='form-control' id='user_id' name='user_id' placeholder="Enter User ID" tabindex='4' value='<?php if(isset($user_name)) echo $user_name; ?>'>
											<span class="text-danger" style='display:none' id='usernameCheck'>Please Enter UserID</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="password">Password</label>&nbsp;<span class="text-danger">*</span>
											<input type='text' class='form-control' id='password' name='password' placeholder="Enter Password" tabindex='5' value='<?php if(isset($password)) echo $password; ?>'>
											<span class="text-danger" style='display:none' id='passCheck'>Please Enter Password</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cnf_password">Confirm Password</label>&nbsp;<span class="text-danger">*</span>
											<input type='text' class='form-control' id='cnf_password' name='cnf_password' placeholder="Confirm Password" tabindex='6' value='<?php if(isset($password)) echo $password; ?>'>
											<span class="text-danger" style='display:none' id='cnfpassCheck'>Please Enter Confirm Password</span><br>
                                            <span class="text-danger" style='display:none' id='passworkCheck'>Password not matching!</span>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Mapping Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 "> 
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="company_name">Company Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='company_id' name='company_id' >
											<input type='text' class='form-control' id='company_name' name='company_name' tabindex='7' readonly>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="branch_id1">Branch Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='branch_id' name='branch_id' value="<?php if(isset($branch_id)){echo $branch_id;}?>">
                                            <select tabindex="8" type="text" class="form-control" id="branch_id1" name="branch_id1" multiple>
												<option value="">Select Branch Name</option>
											</select> 
											<span class="text-danger" style='display:none' id='BranchCheck'>Please select Branch Name</span>
                                        </div>	
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="bank_access">Bank Access</label>
											<input type='hidden' class='form-control' id='bank_access_id' name='bank_access_id' value="<?php if(isset($bank_access)){echo $bank_access;}?>">
                                            <select tabindex="9" type="text" class="form-control" id="bank_access" name="bank_access" multiple>
												<option value="">Select Branch Access</option>
											</select> 
											<span class="text-danger" style='display:none' id='bankAccessCheck'>Please select Bank Access</span>
                                        </div>	
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="group1">Group Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='group' name='group' >
											<select tabindex="9" type="text" class="form-control" id="group1" name="group1" multiple>
												<option value="">Select Group Name</option>
											</select>
											<span class="text-danger" style='display:none' id='groupCheck'>Please select Group Name</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 line_div">
                                        <div class="form-group">
                                            <label for="line1">Line Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='line' name='line' >
											<select tabindex="10" type="text" class="form-control" id="line1" name="line1" multiple>
												<option value="">Select Line Name</option>
											</select>
											<span class="text-danger" style='display:none' id='lineCheck'>Please select Line Name</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 due_followupline_div">
                                        <div class="form-group">
                                            <label class="due_follup_lines" for="due_follup_lines">Followup lines</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' id='due_follup_line_id' name='due_follup_line_id' value='<?php if(isset($due_followup_lines)) echo 	$due_followup_lines; ?>'>
                                            <select tabindex="13" class='form-control' id='due_follup_lines' name='due_follup_lines' multiple>
												<option value="">Select Followup Lines</option>
											</select>
											<span class='text-danger duefollowupCheck' style="display:none">Please Select Followup Lines</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="promotion_activity_mapping_access">Promotion Activity Mapping Access</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="12" type="text" class="form-control" id="promotion_activity_mapping_access" name="promotion_activity_mapping_access">
												<option value="">Select Promotion Mapping Activity</option>
												<option value="1" <?php if($promotion_activity_mapping_access == '1') echo 'selected';?> >Group</option>
												<option value="2" <?php if($promotion_activity_mapping_access == '2') echo 'selected';?> >Line</option>
												<option value="3" <?php if($promotion_activity_mapping_access == '3') echo 'selected';?> >Followup</option>
											</select>
											<br>
											<span class="text-danger" style='display:none' id='proMapCheck'>Please select Promotion Activity Mapping Access</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="report_access">Report Access</label>
											<select class="form-control" name="report_access" id="report_access" tabindex="13">
												<option value="">Select Report Access</option>
												<option value="1" <?php if($report_access == '1') echo 'selected';?> >Individual</option>
												<option value="2" <?php if($report_access == '2') echo 'selected';?> >Overall</option>
											</select>
											<br>
											<span class="text-danger" style='display:none' id='reportAccessCheck'>Please select Report Access</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<br>
												<label>Download Access</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input class="form-check-input" type="radio" name="download_access" id="da_yes" value="0" <?php if($idupd > 0){ if($download_access==0){ echo'checked'; }} ?>>
													<label for="download_access">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input class="form-check-input" type="radio" name="download_access" id="da_no" value="1" <?php if($idupd > 0){ if($download_access==1){ echo'checked'; }}?> >
													<label for="download_access">No</label>
                                        </div>
                                    </div>	
									  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="home_access">Home Upload Access</label>&nbsp;<span class="text-danger">*</span>
												<select class="form-control" name="home_access" id="home_access" tabindex="13">
													<option value="">Select Home Upload Access</option>
													<option value="0" <?php if($home_access == '0') echo 'selected';?> >Yes</option>
													<option value="1" <?php if($home_access == '1') echo 'selected';?> >No</option>
												</select>
												<br>
												<span class="text-danger" style='display:none' id='HomeAccessCheck'>Please select Home Upload Access</span>
											</div>
										</div>								
								</div>
							</div>
						</div>
					</div>
				</div>

                <div class="card">
					<div class="card-header">
						<div class="card-title">Screen Mapping &nbsp;<span class="text-danger">*</span> &nbsp;<span class="text-danger" style='display:none' id='screenMappingCheck'>Please select Screen Mapping</span></div> 
					</div>
                    <br>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($mastermodule==0){ echo'checked'; }} ?> tabindex="12" class="" id="mastermodule" name="mastermodule" >&nbsp;&nbsp;
                        <label class="custom-control-label" for="mastermodule">
                            <h5>Master</h5>
                        </label>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($company_creation==0){ echo'checked'; }} ?> tabindex="13" class="master-checkbox screen-validations" id="company_creation" name="company_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="company_creation">Company Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($branch_creation==0){echo'checked';}} ?> tabindex="14" class="master-checkbox screen-validations" id="branch_creation" name="branch_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="branch_creation">Branch Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_category==0){ echo'checked'; }} ?> tabindex="15" class="master-checkbox screen-validations" id="loan_category" name="loan_category" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_category">Loan Category</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_calculation==0){ echo'checked'; }} ?> tabindex="16" class="master-checkbox screen-validations" id="loan_calculation" name="loan_calculation" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_calculation">Loan Calculation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_scheme==0){ echo'checked'; }} ?> tabindex="17" class="master-checkbox screen-validations" id="loan_scheme" name="loan_scheme" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_scheme">Loan Scheme</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_creation==0){ echo'checked'; }} ?> tabindex="18" class="master-checkbox screen-validations" id="area_creation" name="area_creation" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="area_creation">Area Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_mapping==0){ echo'checked'; }} ?> tabindex="19" class="master-checkbox screen-validations" id="area_mapping" name="area_mapping" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="area_mapping">Area Mapping</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_status==0){ echo'checked'; }} ?> tabindex="20" class="master-checkbox screen-validations" id="area_status" name="area_status" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="area_status">Area Approval</label>
                            </div>
                        </div>
                    </div>
                    <!-- admin module end -->

                    <hr>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($adminmodule==0){ echo'checked'; }} ?> tabindex="21" class="" id="adminmodule" name="adminmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="adminmodule">
							<h5>Administration</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($director_creation==0){ echo'checked'; }} ?> tabindex="22" class="admin-checkbox screen-validations" id="director_creation" name="director_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="director_creation">Director Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($agent_creation==0){ echo'checked'; }} ?> tabindex="23" class="admin-checkbox screen-validations" id="agent_creation" name="agent_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="agent_creation">Agent Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($staff_creation==0){ echo'checked'; }} ?> tabindex="24" class="admin-checkbox screen-validations" id="staff_creation" name="staff_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="staff_creation">Staff Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($manage_user==0){ echo'checked'; }} ?> tabindex="25" class="admin-checkbox screen-validations" id="manage_user" name="manage_user" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="manage_user">Manage User</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bank_creation==0){ echo'checked'; }} ?> tabindex="26" class="admin-checkbox screen-validations" id="bank_creation" name="bank_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="bank_creation">Bank Creation</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($requestmodule==0){ echo'checked'; }} ?> tabindex="27" class="" id="requestmodule" name="requestmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="requestmodule">
							<h5>Request</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($request==0){ echo'checked'; }} ?> tabindex="28" class="request-checkbox screen-validations" id="request" name="request" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="request">Request</label>
                            </div>
                        </div>
					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 agent_div" style="display: none">
                        <div class="form-group">
                            <label for="agent1">Agent Name</label>
							<input type='hidden' class='form-control' id="agentforstaff" name="agentforstaff" value="<?php if(isset($agentforstaff)){echo $agentforstaff;}?>">
							<select  tabindex="9" type="text" class="form-control" id="agent1" name="agent1" multiple >
								<option value="">Select Agent Name</option>
							</select>
							<span class="text-danger" style='display:none' id='AgentCheck'>Please select Agent Name</span>
                        </div>
					</div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($request_list_access==0){ echo'checked'; }} ?> tabindex="29" class="request-checkbox screen-validations" id="request_list_access" name="request_list_access" disabled>&nbsp;&nbsp;
								<label class="custom-control-label" for="request_list_access">All Request List Access</label>
							</div>
						</div>
					</div>
					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($verificationmodule==0){ echo'checked'; }} ?> tabindex="30" class="" id="verificationmodule" name="verificationmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="verificationmodule">
							<h5>Verification</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($verification==0){ echo'checked'; }} ?> tabindex="31" class="verification-checkbox screen-validations" id="verification" name="verification" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="verification">Verification</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 ver_loancat_div" style='display:none;'>
                            <div class="form-group">
								<label for="loan_cat1">Verification Loan Category</label>&nbsp;<span class="text-danger">*</span>
								<input type='hidden' class='form-control' id='ver_loan_cat' name='ver_loan_cat' >
								<select tabindex="32" type="text" class="form-control" id="loan_cat1" name="loan_cat1" multiple>
									<option value="">Select Loan Category</option>
								</select>
								<span class="text-danger" style='display:none' id='ver_loan_catCheck'>Please select Verification Loan Category</span>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 ver_loancat_div" style='display:none;'>
                            <div class="form-group">
								<label for="ver_group_id">Verification Group Name</label>
								<input type='hidden' class='form-control' id='ver_group' name='ver_group' >
								<select tabindex="32" type="text" class="form-control" id="ver_group_id" name="ver_group_id" multiple>
									<option value="">Select Group Name</option>
								</select>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($approvalmodule==0){ echo'checked'; }} ?> tabindex="33" class="" id="approvalmodule" name="approvalmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="approvalmodule">
							<h5>Approval</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($approval==0){ echo'checked'; }} ?> tabindex="34" class="approval-checkbox screen-validations" id="approval" name="approval" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="approval">Approval</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 app_loancat_div" style='display:none;'>
                            <div class="form-group">
								<label for="loan_cat2">Approval Loan Category</label>&nbsp;<span class="text-danger">*</span>
								<input type='hidden' class='form-control' id="app_loan_cat" name="app_loan_cat" >
								<select tabindex="9" type="text" class="form-control" id="loan_cat2" name="loan_cat2" multiple>
									<option value="">Select Loan Category</option>
								</select>
								<span class="text-danger" style='display:none' id='app_loan_catCheck'>Please select Approval Loan Category</span>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($acknowledgementmodule==0){ echo'checked'; }} ?> tabindex="35" class="" id="acknowledgementmodule" name="acknowledgementmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="acknowledgementmodule">
							<h5>Acknowledgement</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($acknowledgement==0){ echo'checked'; }} ?> tabindex="36" class="acknowledgement-checkbox screen-validations" id="acknowledgement" name="acknowledgement" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="acknowledgement">Acknowledgement</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 ack_loancat_div" style='display:none;'>
                            <div class="form-group">
								<label for="loan_cat3">Acknowledgement Loan Category</label>&nbsp;<span class="text-danger">*</span>
								<input type='hidden' class='form-control' id='ack_loan_cat' name='ack_loan_cat' >
								<select tabindex="9" type="text" class="form-control" id="loan_cat3" name="loan_cat3" multiple>
									<option value="">Select Loan Category</option>
								</select>
								<span class="text-danger" style='display:none' id='ack_loan_catCheck'>Please select Acknowledgement Loan Category</span>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loanissuemodule==0){ echo'checked'; }} ?> tabindex="37" class="" id="loanissuemodule" name="loanissuemodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="loanissuemodule">
							<h5>Loan Issue</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_issue==0){ echo'checked'; }} ?> tabindex="38" class="loan_issue-checkbox screen-validations" id="loan_issue" name="loan_issue" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_issue">Loan Issue</label>
                            </div>
                        </div>
					</div>
	
					<hr>

					 <div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doctrackmodule==0){ echo'checked'; }} ?> tabindex="25" class="" id="doctrackmodule" name="doctrackmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="doctrackmodule">
							<h5>Document Track</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doctrack==0){ echo'checked'; }} ?> tabindex="25" class="doctrack-checkbox screen-validations" id="doctrack" name="doctrack" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doctrack">Document Track</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doc_rec_access==0){ echo'checked'; }} ?> tabindex="25" class="doctrack-checkbox screen-validations" id="doc_rec_access" name="doc_rec_access" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doc_rec_access">Document Receive Access</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($noc_replace==0){ echo'checked'; }} ?> tabindex="45" class="doctrack-checkbox screen-validations" id="noc_replace" name="noc_replace" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="noc_replace">DOC Replace</label>
                            </div>
                        </div>
					</div>
					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collectionmodule==0){ echo'checked'; }} ?> tabindex="39" class="" id="collectionmodule" name="collectionmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="collectionmodule">
							<h5>Collection</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collection==0){ echo'checked'; }} ?> tabindex="40" class="collection-checkbox screen-validations" id="collection" name="collection" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="collection">Collection</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collection_access==0){ echo'checked'; }} ?> tabindex="41" class="collection-checkbox" id="collection_access" name="collection_access" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="collection_access">Collection Access</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($closedmodule==0){ echo'checked'; }} ?> tabindex="42" class="" id="closedmodule" name="closedmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="closedmodule">
							<h5>Closed</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($closed==0){ echo'checked'; }} ?> tabindex="43" class="closed-checkbox screen-validations" id="closed" name="closed" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="closed">Closed</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($nocmodule==0){ echo'checked'; }} ?> tabindex="44" class="" id="nocmodule" name="nocmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="nocmodule">
							<h5>NOC</h5>
						</label> &nbsp;&nbsp; <span class="text-danger" style='display:none' id='nocCheck'>Please Check NOC </span>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($noc==0){ echo'checked'; }} ?> tabindex="45" class="noc-checkbox screen-validations" id="noc" name="noc" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="noc">NOC</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($noc_handover==0){ echo'checked'; }} ?> tabindex="45" class="noc-checkbox screen-validations" id="noc_handover" name="noc_handover" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="noc_handover">NOC Handover</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($noc_replace_access==0){ echo'checked'; }} ?> tabindex="45" class="noc-checkbox screen-validations" id="noc_replace_access" name="noc_replace_access" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="noc_replace_access">Replace Access</label>
								<br>
								<span class="text-danger" style='display:none' id='replaceCheck'>Please Check Replace Access</span>
							</div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 noc_handover_div">
							  <div class="custom-control custom-checkbox">
								<label for="noc_mapping_access">NOC Mapping Access</label>&nbsp;<span class="text-danger">*</span>
								<select tabindex="12" type="text" class="form-control noc-checkbox" id="noc_mapping_access" name="noc_mapping_access" style="width: 250px;" <?php if($noc_mapping_access =='') echo 'disabled'; ?> >
									<option value="">Select NOC Mapping Access</option>
									<option value="1" <?php if($noc_mapping_access == '1') echo 'selected';?> >Group</option>
									<option value="2" <?php if($noc_mapping_access == '2') echo 'selected';?> >Line</option>
									<option value="3" <?php if($noc_mapping_access == '3') echo 'selected';?> >Followup</option>
								</select>
								<br>
								<span class="text-danger" style='display:none' id='handoverCheck'>Please Select NOC Mapping Access</span>
							</div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($updatemodule==0){ echo'checked'; }} ?> tabindex="46" class="" id="updatemodule" name="updatemodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="updatemodule">
							<h5>Update</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($update_screen==0){ echo'checked'; }} ?> tabindex="47" class="update-checkbox screen-validations" id="update" name="update" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="update">Update</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 update_screen_div"  style='display:none;'>
                            <div class="custom-control custom-checkbox">
								<input type='hidden' id='update_screen_id' name='update_screen_id' value='<?php if(isset($update_screen_id)) echo $update_screen_id; ?>'>
                                <select class='form-control' id='update_screen' name='update_screen' multiple>
									<option value="">Select Update screen</option>
									<option value="1">Customer Profile</option>
									<option value="2">Documentation</option>
								</select>
								<span class='text-danger updateScreenCheck' style="display:none">Please Select Update Screen</span>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concernmodule==0){ echo'checked'; }} ?> tabindex="50" class="" id="concernmodule" name="concernmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="concernmodule">
							<h5>Concern</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_creation==0){ echo'checked'; }} ?> tabindex="51" class="concern-checkbox screen-validations" id="concernCreation" name="concernCreation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concernCreation">Concern Creation</label>
                            </div>
                        </div>

						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_solution==0){ echo'checked'; }} ?> tabindex="52" class="concern-checkbox screen-validations" id="concernSolution" name="concernSolution" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concernSolution">Concern Solution</label>
                            </div>
                        </div>

						<!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_feedback==0){ echo'checked'; }} ?> tabindex="53" class="concern-checkbox screen-validations" id="concernFeedback" name="concernFeedback" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concernFeedback">Concern Feedback</label>
                            </div>
                        </div> -->
						
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($accountsmodule==0){ echo'checked'; }} ?> tabindex="54" class="" id="accountsmodule" name="accountsmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="accountsmodule">
							<h5>Accounts</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cash_tally==0){ echo'checked'; }} ?> tabindex="55" class="accounts-checkbox screen-validations" id="cash_tally" name="cash_tally" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="cash_tally">Cash Tally</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 bank_details" style='display:none'>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cash_tally_admin==0){ echo'checked'; }} ?> tabindex="56" class="accounts-checkbox screen-validations" id="cash_tally_admin" name="cash_tally_admin" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="cash_tally_admin">Cash Tally Admin</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 bank_details"  style='display:none'>
                            <div class="custom-control custom-checkbox">
                                <label class="custom-control-label" for="cash_tally">Bank Name</label>
								<input type='hidden' id='bank_details' name='bank_details' value=''>
                                <select class='form-control' id='bank_details1' name='bank_details1' multiple>
									<option value="">Select Bank Account</option>
								</select>
								<span class='text-danger bankdetailsCheck' style="display:none">Please Select Bank Account</span>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bank_clearance==0){ echo'checked'; }} ?> tabindex="57" class="accounts-checkbox screen-validations" id="bank_clearance" name="bank_clearance" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="bank_clearance">Bank Clearance</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 bnk_clr_upl_acc_div"  style='display:none'>
                            <div class="custom-control custom-checkbox">
                                <label class="custom-control-label" for="cash_tally">Bank Clearance Upload Access</label>
                                <select class='form-control' id='bnk_clr_upl_acc' name='bnk_clr_upl_acc'>
									<option value="">Select Bank Clearance Upload Access</option>
									<option value="0" <?php if($bnk_clr_upl_acc == '0') echo 'selected'; ?>>Yes</option>
									<option value="1" <?php if($bnk_clr_upl_acc == '1') echo 'selected'; ?>>No</option>
								</select>
								<span class='text-danger bankclearanceuploadCheck' style="display:none">Please Select Upload Access</span>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($finance_insight==0){ echo'checked'; }} ?> tabindex="58" class="accounts-checkbox screen-validations" id="finance_insight" name="finance_insight" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="finance_insight">Financial Insights</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($accounts_loan_issue==0){ echo'checked'; }} ?> tabindex="59" class="accounts-checkbox screen-validations" id="accounts_loan_issue" name="accounts_loan_issue" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="accounts_loan_issue">Loan Issue</label>
                            </div>
                        </div>
					</div>
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($followupmodule==0){ echo'checked'; }} ?> tabindex="60" class="" id="followupmodule" name="followupmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="followupmodule">
							<h5>Follow up</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($promotion_activity==0){ echo'checked'; }} ?> tabindex="61" class="followup-checkbox screen-validations" id="promotion_activity" name="promotion_activity" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="promotion_activity">Promotion Activity</label>&nbsp;&nbsp;
								<span class='text-danger promotionActivityCheck' style="display:none">Please Select Promotion Activity </span> 
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 promotion_activity_div" style="display: none;" >
                                        <div class="form-group">
                                            <label for="pro_aty_access">Promotion Activity Access</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='pro_aty_access_id' name='pro_aty_access_id' >
											<select tabindex="62" type="text" class="form-control" id="pro_aty_access" name="pro_aty_access" multiple>
												<option value="">Select Promotion Activity</option>
												<option value="1">Renewal</option>
												<option value="5">Re-active</option>
												<option value="2">New</option>
												<option value="3">Repromotion</option>
												<option value="4">Events</option>
											</select>
											<span class="text-danger" style='display:none' id='proCheck'>Please select Promotion Activity Access</span>
                                        </div>
                                    </div>
                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php # if($idupd > 0){ if($loan_followup==0){ echo'checked'; }} ?> tabindex="63" class="followup-checkbox screen-validations" id="loan_followup" name="loan_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_followup">Loan Followup</label>
                            </div>
                        </div> -->
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($conf_followup==0){ echo'checked'; }} ?> tabindex="62" class="followup-checkbox screen-validations" id="conf_followup" name="conf_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="conf_followup">Confirmation Followup</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($due_followup==0){ echo'checked'; }} ?> tabindex="63" class="followup-checkbox screen-validations" id="due_followup" name="due_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="due_followup"> Due Followup</label>&nbsp;&nbsp;
								<span class='text-danger dueFollowupCheck' style="display:none">Please Select Due Followup </span> 
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($ecs_followup==0){ echo'checked'; }} ?> tabindex="64" class="followup-checkbox screen-validations" id="ecs_followup" name="ecs_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="ecs_followup">ECS Followup</label>&nbsp;&nbsp;
								<span class='text-danger ecsdueFollowupCheck' style="display:none">Please Select ECS Followup </span> 
                            </div>
                        </div>
					</div>
						</br></br>
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($reportmodule==0){ echo'checked'; }} ?> tabindex="65" class="" id="reportmodule" name="reportmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="reportmodule">
							<h5>Reports - EMI &nbsp;&nbsp;<span class='text-danger reportCheck' style="display:none;font-size:14px;font-weight:500">Please Select Report </span> </h5>
						</label>
					</div>
					<br>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($work_report_module==0){ echo'checked'; }} ?> tabindex="66" class="report-checkbox" id="work_report_module" name="work_report_module" disabled >&nbsp;&nbsp;
						<label class="custom-control-label" for="work_report_module">
							<h5>Work Reports &nbsp;&nbsp;<span class='text-danger work_report_module' style="display:none;font-size:14px;font-weight:500">Please Select Report Module </span><span class='text-danger workreport' style="display:none;font-size:14px;font-weight:500">Please Select Any Of These Report </span> </h5>
						</label>
						<br><br>
						<div class="row">
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($request_report==0){ echo'checked'; }} ?> tabindex="67" class="work-checkbox  screen-validations" id="request_report" name="request_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="request_report">Request</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cancel_revoke_report==0){ echo'checked'; }} ?> tabindex="68" class="work-checkbox    screen-validations" id="cancel_revoke_report" name="cancel_revoke_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="cancel_revoke_report">Cancel / Revoke</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_issue_report==0){ echo'checked'; }} ?> tabindex="69" class="work-checkbox    screen-validations" id="loan_issue_report" name="loan_issue_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="loan_issue_report">Loan Issue</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collection_report==0){ echo'checked'; }} ?> tabindex="70" class="work-checkbox   screen-validations" id="collection_report" name="collection_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="collection_report">Collection</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($in_closed_report==0){ echo'checked'; }} ?> tabindex="71" class="work-checkbox    screen-validations" id="in_closed_report" name="in_closed_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="in_closed_report">In Closed</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($closed_report==0){ echo'checked'; }} ?> tabindex="72" class="work-checkbox    screen-validations" id="closed_report" name="closed_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="closed_report">Closed</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($due_list_report==0){ echo'checked'; }} ?> tabindex="73" class="work-checkbox    screen-validations" id="due_list_report" name="due_list_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="due_list_report">Due List</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($noc_handover_report==0){ echo'checked'; }} ?> tabindex="73" class="work-checkbox    screen-validations" id="noc_handover_report" name="noc_handover_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="noc_handover_report">NOC Handover</label>
								</div>
							</div>
						</div>
					</div>
					<br>
					<br>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($monitor_report_module==0){ echo'checked'; }} ?> tabindex="64" class="report-checkbox" id="monitor_report_module" name="monitor_report_module" disabled >&nbsp;&nbsp;
						<label class="custom-control-label" for="monitor_report_module">
							<h5>Monitor Reports &nbsp;&nbsp;<span class='text-danger monitor_report_module' style="display:none;font-size:14px;font-weight:500">Please Select Report Module </span> <span class='text-danger monitorreport' style="display:none;font-size:14px;font-weight:500">Please Select Any Of These Report </span></h5>
						</label>
						<br><br>
						<div class="row">
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($promotion_activity_report==0){ echo'checked'; }} ?> tabindex="75" class="monitor-checkbox  screen-validations" id="promotion_activity_report" name="promotion_activity_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="promotion_activity_report">Promotion Activity</label>
								</div>
							</div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($events_report==0){ echo'checked'; }} ?> tabindex="76" class="monitor-checkbox   screen-validations" id="events_report" name="events_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="events_report">Events Activity</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($confirmation_followup_report==0){ echo'checked'; }} ?> tabindex="77" class="monitor-checkbox   screen-validations" id="confirmation_followup_report" name="confirmation_followup_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="confirmation_followup_report">Confirmation Follow Up</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($commitment_report==0){ echo'checked'; }} ?> tabindex="78" class="monitor-checkbox   screen-validations" id="commitment_report" name="commitment_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="commitment_report">Due Followup Activity</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($customer_status_report==0){ echo'checked'; }} ?> tabindex="79" class="monitor-checkbox   screen-validations" id="customer_status_report" name="customer_status_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="customer_status_report">Collection Status</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($due_followup_customer_count_report==0){ echo'checked'; }} ?> tabindex="80" class="monitor-checkbox   screen-validations" id="due_followup_customer_count_report" name="due_followup_customer_count_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="due_followup_customer_count_report">Due Summary</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($work_count_report==0){ echo'checked'; }} ?> tabindex="85" class="monitor-checkbox  screen-validations" id="work_count_report" name="work_count_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="work_count_report">Work Count</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($confirmation_count_report==0){ echo'checked'; }} ?> tabindex="85" class="monitor-checkbox  screen-validations" id="confirmation_count_report" name="confirmation_count_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="confirmation_count_report">Confirmation Count</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_report ==0){ echo'checked'; }} ?> tabindex="85" class="monitor-checkbox  screen-validations" id="concern_report" name="concern_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concern_report">Concern</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_track==0){ echo'checked'; }} ?> tabindex="101" class="monitor-checkbox screen-validations" id="loan_track" name="loan_track" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_track">Loan Track</label>
                            </div>
                        </div>
						</div>
					</div>
					<br>
					<br>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($analysis_report_module==0){ echo'checked'; }} ?> tabindex="81" class="report-checkbox" id="analysis_report_module" name="analysis_report_module" disabled>&nbsp;&nbsp;
						<label class="custom-control-label" for="analysis_report_module">
							<h5>Analysis  Reports &nbsp;&nbsp;<span class='text-danger analysis_report_module' style="display:none;font-size:14px;font-weight:500">Please Select Report Module </span><span class='text-danger analysisreport' style="display:none;font-size:14px;font-weight:500">Please Select Any Of These Report </span> </h5>
						</label>
						<br><br>
						<div class="row">
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($principal_interest_report==0){ echo'checked'; }} ?> tabindex="82" class="analysis-checkbox   screen-validations" id="principal_interest_report" name="principal_interest_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="principal_interest_report">Principal / Interest</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($balance_report==0){ echo'checked'; }} ?> tabindex="83" class="analysis-checkbox   screen-validations" id="balance_report" name="balance_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="balance_report">Balance</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_loan_count_report==0){ echo'checked'; }} ?> tabindex="84" class="analysis-checkbox   screen-validations" id="area_loan_count_report" name="area_loan_count_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="area_loan_count_report">Area Loan Count</label>
								</div>
							</div>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($no_due_pay_report==0){ echo'checked'; }} ?> tabindex="84" class="analysis-checkbox   screen-validations" id="no_due_pay_report" name="no_due_pay_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="no_due_pay_report">No Due Pay </label>
								</div>
							</div>
						</div>
					</div>
					<br>
					<br>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($accounts_report_module==0){ echo'checked'; }} ?> tabindex="85" class="report-checkbox" id="accounts_report_module" name="accounts_report_module" disabled>&nbsp;&nbsp;
						<label class="custom-control-label" for="accounts_report_module">
							<h5>Accounts Reports &nbsp;&nbsp;<span class='text-danger accounts_report_module' style="display:none;font-size:14px;font-weight:500">Please Select Report Module </span><span class='text-danger accountsreport' style="display:none;font-size:14px;font-weight:500">Please Select Any Of These Report </span> </h5>
						</label>
						<br><br>
						<div class="row">
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($agent_report==0){ echo'checked'; }} ?> tabindex="86" class="acounts-checkbox   screen-validations" id="agent_report" name="agent_report" disabled>&nbsp;&nbsp;
									<label class="custom-control-label" for="agent_report">Agent</label>
								</div>
							</div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($other_trans_report==0){ echo'checked'; }} ?> tabindex="87" class="acounts-checkbox    screen-validations" id="other_trans_report" name="other_trans_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="other_trans_report">Other Transaction </label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($day_end_report==0){ echo'checked'; }} ?> tabindex="88" class="acounts-checkbox    screen-validations" id="day_end_report" name="day_end_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="day_end_report">Day End</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cash_tally_activity_report==0){ echo'checked'; }} ?> tabindex="88" class="acounts-checkbox    screen-validations" id="cash_tally_activity_report" name="cash_tally_activity_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="cash_tally_activity_report">Cash Tally Activity</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($partners_report==0){ echo'checked'; }} ?> tabindex="88" class="acounts-checkbox    screen-validations" id="partners_report" name="partners_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="partners_report">Partners</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cleared_report==0){ echo'checked'; }} ?> tabindex="89" class="acounts-checkbox    screen-validations" id="cleared_report" name="cleared_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="cleared_report">Cleared</label>
                            </div>
						
                        </div>
							
						</div>
					</div>
					<hr>

					<!-- <div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php #if($idupd > 0){ if($reportmodule_intrest==0){ echo'checked'; }} ?> tabindex="90" class="" id="reportmodule_intrest" name="reportmodule_intrest" >&nbsp;&nbsp;
						<label class="custom-control-label" for="reportmodule_intrest">
							<h5>Report - INTEREST &nbsp;&nbsp;<span class='text-danger' style="display:none;font-size:14px;font-weight:500">Please Select Report </span> </h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php #if($idupd > 0){ if($intrest_ledger_report==0){ echo'checked'; }} ?> tabindex="91" class="intrest-report-checkbox screen-validations" id="intrest_ledger_report" name="intrest_ledger_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="intrest_ledger_report">Ledger View</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php #if($idupd > 0){ if($intrest_loan_issue_report==0){ echo'checked'; }} ?> tabindex="92" class="intrest-report-checkbox screen-validations" id="intrest_loan_issue_report" name="intrest_loan_issue_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="intrest_loan_issue_report">Loan Issue</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php #if($idupd > 0){ if($intrest_collection_report==0){ echo'checked'; }} ?> tabindex="93" class="intrest-report-checkbox screen-validations" id="intrest_collection_report" name="intrest_collection_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="intrest_collection_report">Collection</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php #if($idupd > 0){ if($intrest_balance_report==0){ echo'checked'; }} ?> tabindex="94" class="intrest-report-checkbox screen-validations" id="intrest_balance_report" name="intrest_balance_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="intrest_balance_report">Balance</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php #if($idupd > 0){ if($intrest_closed_report==0){ echo'checked'; }} ?> tabindex="95" class="intrest-report-checkbox screen-validations" id="intrest_closed_report" name="intrest_closed_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="intrest_closed_report">Closed</label>
                            </div>
                        </div>
					</div>
				
					<hr> -->

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($search_module==0){ echo'checked'; }} ?> tabindex="96" class="" id="searchmodule" name="searchmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="searchmodule">
							<h5>Search</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($search_screen==0){ echo'checked'; }} ?> tabindex="97" class="search-checkbox screen-validations" id="search_screen" name="search_screen" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="search_screen">Search</label>
                            </div>
                        </div>
					</div>
			
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bulk_upload_module==0){ echo'checked'; }} ?> tabindex="98" class="" id="bulk_upload_module" name="bulk_upload_module" >&nbsp;&nbsp;
						<label class="custom-control-label" for="bulk_upload_module">
							<h5>Bulk Upload</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bulk_upload==0){ echo'checked'; }} ?> tabindex="99" class="bulk_upload-checkbox screen-validations" id="bulk_upload" name="bulk_upload" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="bulk_upload">Bulk Upload</label>
                            </div>
                        </div>
					</div>
					
					<hr>

					<!-- <div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php # if($idupd > 0){ if($loan_track_module==0){ echo'checked'; }} ?> tabindex="100" class="" id="loan_track_module" name="loan_track_module" >&nbsp;&nbsp;
						<label class="custom-control-label" for="loan_track_module">
							<h5>Loan Track</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php # if($idupd > 0){ if($loan_track==0){ echo'checked'; }} ?> tabindex="101" class="loan_track-checkbox screen-validations" id="loan_track" name="loan_track" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_track">Loan Track</label>
                            </div>
                        </div>
					</div> -->
					
					<!-- <hr> -->

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($sms_module==0){ echo'checked'; }} ?> tabindex="102" id="sms_module" name="sms_module" >&nbsp;&nbsp;
						<label class="custom-control-label" for="sms_module">
							<h5>SMS</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($sms_generation==0){ echo'checked'; }} ?> tabindex="103" class="sms_generation-checkbox screen-validations" id="sms_generation" name="sms_generation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="sms_generation">SMS Generation</label>
                            </div>
                        </div>
					</div>

					<br>
					<br>
                    <!-- Modules end -->
                </div>
				
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_manage_user" id="submit_manage_user" class="btn btn-primary" value="Submit" tabindex="104"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="105" >Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>


