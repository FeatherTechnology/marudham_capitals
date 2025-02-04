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
$line_id           = '';
$group_id           = '';
$download_access = '';
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
$cash_tally_admin = '';
$bank_details = '';
$bank_clearance = '';
$finance_insight = '';
$followupmodule = '';
$promotion_activity = '';
$loan_followup  = '';
$conf_followup  = '';
$due_followup  = '';
$reportmodule = '';
$ledger_report = '';
$request_report = '';
$cus_profile_report = '';
$loan_issue_report = '';
$collection_report = '';
$balance_report = '';
$due_list_report = '';
$closed_report = '';
$agent_report = '';
$search_module = '';
$search_screen = '';
$bulk_upload_module = '';
$bulk_upload = '';
$loan_track_module = '';
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
	//die;
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
	$getUser = $userObj->getUser($mysqli,$idupd); 
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
			$loan_cat          		     = $getUser['loan_cat'];
			$agentforstaff          		     = $getUser['agentforstaff'];
			$line_id          		     = $getUser['line_id'];
			$group_id          		     = $getUser['group_id'];
			$download_access          		     = $getUser['download_access'];
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
			$doctrackmodule 				= $getUser['doctrackmodule'];
			$doctrack 				= $getUser['doctrack'];
			$doc_rec_access 				= $getUser['doc_rec_access'];
			$updatemodule 				= $getUser['updatemodule'];
			$update_screen 				= $getUser['update_screen'];
			$concernmodule          		     = $getUser['concernmodule'];
			$concern_creation          		     = $getUser['concern_creation'];
			$concern_solution          		     = $getUser['concern_solution'];
			$concern_feedback          		     = $getUser['concern_feedback'];
			$accountsmodule          		     = $getUser['accountsmodule'];
			$cash_tally          		     = $getUser['cash_tally'];
			$cash_tally_admin          		     = $getUser['cash_tally_admin'];
			$bank_details          		     = $getUser['bank_details'];
			$bank_clearance          		     = $getUser['bank_clearance'];
			$finance_insight          		     = $getUser['finance_insight'];
			$followupmodule          		     = $getUser['followupmodule'];
			$promotion_activity = $getUser['promotion_activity'];
			$loan_followup = $getUser['loan_followup'];
			$conf_followup = $getUser['confirmation_followup'];
			$due_followup = $getUser['due_followup'];
			
			$reportmodule          		     = $getUser['reportmodule'];
			$ledger_report          		     = $getUser['ledger_report'];
			$request_report          		     = $getUser['request_report'];
			$cus_profile_report          		     = $getUser['cus_profile_report'];
			$loan_issue_report          		     = $getUser['loan_issue_report'];
			$collection_report          		     = $getUser['collection_report'];
			$balance_report          		     = $getUser['balance_report'];
			$due_list_report          		     = $getUser['due_list_report'];
			$closed_report          		     = $getUser['closed_report'];
			$agent_report          		     = $getUser['agent_report'];

			$search_module = $getUser['search_module'];
			$search_screen = $getUser['search'];
			$bulk_upload_module = $getUser['bulk_upload_module'];
			$bulk_upload = $getUser['bulk_upload'];
			$loan_track_module = $getUser['loan_track_module'];
			$loan_track = $getUser['loan_track'];
			$sms_module = $getUser['sms_module'];
			$sms_generation = $getUser['sms_generation'];
		}
	}
}
// print_r($getUser);


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
		<input type="hidden" class="form-control" value="<?php if(isset($idupd)) echo $idupd; ?>"  id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($user_id)) echo $user_id; ?>"  id="user_id_upd" name="user_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($role)) echo $role; ?>"  id="role_upd" name="role_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($role_type)) echo $role_type; ?>"  id="role_type_upd" name="role_type_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($dir_id)) echo $dir_id; ?>"  id="dir_id_upd" name="dir_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($ag_id)) echo $ag_id; ?>"  id="ag_id_upd" name="ag_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($staff_id)) echo $staff_id; ?>"  id="staff_id_upd" name="staff_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($company_id)) echo $company_id; ?>"  id="company_id_upd" name="company_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($branch_id)) echo $branch_id; ?>"  id="branch_id_upd" name="branch_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($agentforstaff)) echo $agentforstaff; ?>"  id="agentforstaff_upd" name="agentforstaff_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($loan_cat)) echo $loan_cat; ?>"  id="loan_cat_upd" name="loan_cat_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($agent_id)) echo $agent_id; ?>"  id="agent_id_upd" name="agent_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($line_id)) echo $line_id; ?>"  id="line_id_upd" name="line_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($group_id)) echo $group_id; ?>"  id="group_id_upd" name="group_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($bank_details)) echo $bank_details; ?>"  id="bank_details_upd" name="bank_details_upd" aria-describedby="id" placeholder="Enter id">
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
                                            <label for="disabledInput">Role</label>&nbsp;<span class="text-danger">*</span>
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
                                            <label for="disabledInput">Role Type</label>&nbsp;<span class="text-danger">*</span>
                                            <select tabindex="2" type="text" class="form-control" id="role_type" name="role_type" >
												<option value="">Select Role Type</option>
											</select> 
											<span class="text-danger" style='display:none' id='roleTypeCheck'>Please select Role Type</span>
                                        </div>
                                        <div class="form-group agent" style="display:none">
                                            <label for="disabledInput">Agent Name</label>&nbsp;<span class="text-danger">*</span>
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
                                            <label for="disabledInput">Director Name</label>&nbsp;<span class="text-danger">*</span>
                                            <select tabindex="3" type="text" class="form-control" id="dir_name" name="dir_name" >
												<option value="">Select Director Name</option>
											</select> 
											<span class="text-danger" style='display:none' id='dirnameCheck'>Please select Director Name</span>
                                        </div>
										<div class="form-group staff" style="display:none">
                                            <label for="disabledInput">Staff Name</label>&nbsp;<span class="text-danger">*</span>
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
                                            <label for="disabledInput">User ID</label>&nbsp;<span class="text-danger">*</span>
											<input type='text' class='form-control' id='user_id' name='user_id' placeholder="Enter User ID" tabindex='4' value='<?php if(isset($user_name)) echo $user_name; ?>'>
											<span class="text-danger" style='display:none' id='usernameCheck'>Please Enter UserID</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Password</label>&nbsp;<span class="text-danger">*</span>
											<input type='text' class='form-control' id='password' name='password' placeholder="Enter Password" tabindex='5' value='<?php if(isset($password)) echo $password; ?>'>
											<span class="text-danger" style='display:none' id='passCheck'>Please Enter Password</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Confirm Password</label>&nbsp;<span class="text-danger">*</span>
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
                                            <label for="disabledInput">Company Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='company_id' name='company_id' >
											<input type='text' class='form-control' id='company_name' name='company_name' tabindex='7' readonly>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Branch Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='branch_id' name='branch_id' value="<?php if(isset($branch_id)){echo $branch_id;}?>">
                                            <select tabindex="8" type="text" class="form-control" id="branch_id1" name="branch_id1" multiple>
												<option value="">Select Branch Name</option>
											</select> 
											<span class="text-danger" style='display:none' id='BranchCheck'>Please select Branch Name</span>
                                        </div>	
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 agent_div" style="display: none">
                                        <div class="form-group">
                                            <label for="disabledInput">Agent Name</label>
											<input type='hidden' class='form-control' id='agentforstaff' name='agentforstaff' value="<?php if(isset($agentforstaff)){echo $agentforstaff;}?>">
											<select  tabindex="9" type="text" class="form-control" id="agent1" name="agent1" multiple >
												<option value="">Select Agent Name</option>
											</select>
											<span class="text-danger" style='display:none' id='AgentCheck'>Please select Agent Name</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 loancat_div">
                                        <div class="form-group">
                                            <label for="disabledInput">Loan Category</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='loan_cat' name='loan_cat' >
											<select tabindex="9" type="text" class="form-control" id="loan_cat1" name="loan_cat1" multiple>
												<option value="">Select Loan Category</option>
											</select>
											<span class="text-danger" style='display:none' id='loan_catCheck'>Please select Loan Category</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 line_div">
                                        <div class="form-group">
                                            <label for="disabledInput">Line Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='line' name='line' >
											<select tabindex="9" type="text" class="form-control" id="line1" name="line1" multiple>
												<option value="">Select Line Name</option>
											</select>
											<span class="text-danger" style='display:none' id='lineCheck'>Please select Line Name</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Group Name</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' class='form-control' id='group' name='group' >
											<select tabindex="10" type="text" class="form-control" id="group1" name="group1" multiple>
												<option value="">Select Group Name</option>
											</select>
											<span class="text-danger" style='display:none' id='groupCheck'>Please select Group Name</span>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<br>
												<label>Download Access</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input class="form-check-input" type="radio" name="download_access" id="da_yes" value="0" <?php if($idupd > 0){ if($download_access==0){ echo'checked'; }} ?>>
													<label class="form-check-label" for="download_access1">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input class="form-check-input" type="radio" name="download_access" id="da_no" value="1" <?php if($idupd > 0){ if($download_access==1){ echo'checked'; }}?> >
													<label class="form-check-label" for="download_access2">No</label>
										<!-- <input type="checkbox" value="Yes"  tabindex="10" id="downloan_access" name="downloan_access">&nbsp;&nbsp; -->
										<!-- <label class="custom-control-label" for="downloan_access">Download Access</label> -->
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>

                <div class="card">
					<div class="card-header">
						<div class="card-title">Screen Mapping</div>
					</div>
                    <br>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($mastermodule==0){ echo'checked'; }} ?> tabindex="11" class="" id="mastermodule" name="mastermodule" >&nbsp;&nbsp;
                        <label class="custom-control-label" for="mastermodule">
                            <h5>Master</h5>
                        </label>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($company_creation==0){ echo'checked'; }} ?> tabindex="12" class="master-checkbox" id="company_creation" name="company_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="company_creation">Company Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($branch_creation==0){echo'checked';}} ?> tabindex="13" class=" master-checkbox" id="branch_creation" name="branch_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="branch_creation">Branch Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_category==0){ echo'checked'; }} ?> tabindex="14" class=" master-checkbox" id="loan_category" name="loan_category" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_category">Loan Category</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_calculation==0){ echo'checked'; }} ?> tabindex="15" class=" master-checkbox" id="loan_calculation" name="loan_calculation" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_calculation">Loan Calculation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_scheme==0){ echo'checked'; }} ?> tabindex="16" class=" master-checkbox" id="loan_scheme" name="loan_scheme" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_scheme">Loan Scheme</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_creation==0){ echo'checked'; }} ?> tabindex="17" class=" master-checkbox" id="area_creation" name="area_creation" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="area_creation">Area Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_mapping==0){ echo'checked'; }} ?> tabindex="18" class=" master-checkbox" id="area_mapping" name="area_mapping" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="area_mapping">Area Mapping</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($area_status==0){ echo'checked'; }} ?> tabindex="19" class=" master-checkbox" id="area_status" name="area_status" disabled >&nbsp;&nbsp;
                                <label class="custom-control-label" for="area_status">Area Approval</label>
                            </div>
                        </div>
                    </div>
                    <!-- admin module end -->

                    <hr>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($adminmodule==0){ echo'checked'; }} ?> tabindex="20" class="" id="adminmodule" name="adminmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="adminmodule">
							<h5>Administration</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($director_creation==0){ echo'checked'; }} ?> tabindex="21" class=" admin-checkbox" id="director_creation" name="director_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="director_creation">Director Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($agent_creation==0){ echo'checked'; }} ?> tabindex="22" class=" admin-checkbox" id="agent_creation" name="agent_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="agent_creation">Agent Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($staff_creation==0){ echo'checked'; }} ?> tabindex="23" class=" admin-checkbox" id="staff_creation" name="staff_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="staff_creation">Staff Creation</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($manage_user==0){ echo'checked'; }} ?> tabindex="24" class=" admin-checkbox" id="manage_user" name="manage_user" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="manage_user">Manage User</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bank_creation==0){ echo'checked'; }} ?> tabindex="24" class=" admin-checkbox" id="bank_creation" name="bank_creation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="bank_creation">Bank Creation</label>
                            </div>
                        </div>
                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doc_mapping==0){ echo'checked'; }} ?> tabindex="24" class=" admin-checkbox" id="doc_mapping" name="doc_mapping" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doc_mapping">Documentation Mapping</label>
                            </div>
                        </div> -->
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($requestmodule==0){ echo'checked'; }} ?> tabindex="25" class="" id="requestmodule" name="requestmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="requestmodule">
							<h5>Request</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($request==0){ echo'checked'; }} ?> tabindex="26" class=" request-checkbox" id="request" name="request" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="request">Request</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($request_list_access==0){ echo'checked'; }} ?> tabindex="27" class=" request-checkbox" id="request_list_access" name="request_list_access" disabled>&nbsp;&nbsp;
								<label class="custom-control-label" for="request_list_access">All Request List Access</label>
							</div>
						</div>
					</div>
					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($verificationmodule==0){ echo'checked'; }} ?> tabindex="28" class="" id="verificationmodule" name="verificationmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="verificationmodule">
							<h5>Verification</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($verification==0){ echo'checked'; }} ?> tabindex="29" class="verification-checkbox" id="verification" name="verification" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="verification">Verification</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($approvalmodule==0){ echo'checked'; }} ?> tabindex="30" class="" id="approvalmodule" name="approvalmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="approvalmodule">
							<h5>Approval</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($approval==0){ echo'checked'; }} ?> tabindex="31" class="approval-checkbox" id="approval" name="approval" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="approval">Approval</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($acknowledgementmodule==0){ echo'checked'; }} ?> tabindex="32" class="" id="acknowledgementmodule" name="acknowledgementmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="acknowledgementmodule">
							<h5>Acknowledgement</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($acknowledgement==0){ echo'checked'; }} ?> tabindex="33" class="acknowledgement-checkbox" id="acknowledgement" name="acknowledgement" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="acknowledgement">Acknowledgement</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loanissuemodule==0){ echo'checked'; }} ?> tabindex="34" class="" id="loanissuemodule" name="loanissuemodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="loanissuemodule">
							<h5>Loan Issue</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_issue==0){ echo'checked'; }} ?> tabindex="35" class="loan_issue-checkbox" id="loan_issue" name="loan_issue" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_issue">Loan Issue</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collectionmodule==0){ echo'checked'; }} ?> tabindex="36" class="" id="collectionmodule" name="collectionmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="collectionmodule">
							<h5>Collection</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collection==0){ echo'checked'; }} ?> tabindex="37" class="collection-checkbox" id="collection" name="collection" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="collection">Collection</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collection_access==0){ echo'checked'; }} ?> tabindex="38" class="collection-checkbox" id="collection_access" name="collection_access" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="collection_access">Collection Access</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($closedmodule==0){ echo'checked'; }} ?> tabindex="39" class="" id="closedmodule" name="closedmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="closedmodule">
							<h5>Closed</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($closed==0){ echo'checked'; }} ?> tabindex="40" class="closed-checkbox" id="closed" name="closed" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="closed">Closed</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($nocmodule==0){ echo'checked'; }} ?> tabindex="41" class="" id="nocmodule" name="nocmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="nocmodule">
							<h5>NOC</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($noc==0){ echo'checked'; }} ?> tabindex="42" class="noc-checkbox" id="noc" name="noc" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="noc">NOC</label>
                            </div>
                        </div>
					</div>
					
					<!--<hr>

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
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doctrack==0){ echo'checked'; }} ?> tabindex="25" class="doctrack-checkbox" id="doctrack" name="doctrack" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doctrack">Document Track</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doc_rec_access==0){ echo'checked'; }} ?> tabindex="25" class="doctrack-checkbox" id="doc_rec_access" name="doc_rec_access" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doc_rec_access">Document Receive Access</label>
                            </div>
                        </div>
					</div> -->

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($updatemodule==0){ echo'checked'; }} ?> tabindex="43" class="" id="updatemodule" name="updatemodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="updatemodule">
							<h5>Update</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($update_screen==0){ echo'checked'; }} ?> tabindex="44" class="update-checkbox" id="update" name="update" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="update">Update</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doctrack==0){ echo'checked'; }} ?> tabindex="45" class="update-checkbox" id="doctrack" name="doctrack" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doctrack">Document Track</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($doc_rec_access==0){ echo'checked'; }} ?> tabindex="46" class="update-checkbox" id="doc_rec_access" name="doc_rec_access" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="doc_rec_access">Document Receive Access</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concernmodule==0){ echo'checked'; }} ?> tabindex="47" class="" id="concernmodule" name="concernmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="concernmodule">
							<h5>Concern</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_creation==0){ echo'checked'; }} ?> tabindex="48" class="concern-checkbox" id="concernCreation" name="concernCreation" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concernCreation">Concern Creation</label>
                            </div>
                        </div>

						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_solution==0){ echo'checked'; }} ?> tabindex="49" class="concern-checkbox" id="concernSolution" name="concernSolution" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concernSolution">Concern Solution</label>
                            </div>
                        </div>

						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($concern_feedback==0){ echo'checked'; }} ?> tabindex="50" class="concern-checkbox" id="concernFeedback" name="concernFeedback" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="concernFeedback">Concern Feedback</label>
                            </div>
                        </div>
						
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($accountsmodule==0){ echo'checked'; }} ?> tabindex="51" class="" id="accountsmodule" name="accountsmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="accountsmodule">
							<h5>Accounts</h5>
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cash_tally==0){ echo'checked'; }} ?> tabindex="52" class="accounts-checkbox" id="cash_tally" name="cash_tally" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="cash_tally">Cash Tally</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 bank_details" style='display:none'>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cash_tally_admin==0){ echo'checked'; }} ?> tabindex="53" class="accounts-checkbox" id="cash_tally_admin" name="cash_tally_admin" disabled>&nbsp;&nbsp;
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
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bank_clearance==0){ echo'checked'; }} ?> tabindex="54" class="accounts-checkbox" id="bank_clearance" name="bank_clearance" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="bank_clearance">Bank Clearance</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($finance_insight==0){ echo'checked'; }} ?> tabindex="55" class="accounts-checkbox" id="finance_insight" name="finance_insight" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="finance_insight">Financial Insights</label>
                            </div>
                        </div>
					</div>

					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($followupmodule==0){ echo'checked'; }} ?> tabindex="56" class="" id="followupmodule" name="followupmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="followupmodule">
							<h5>Follow up</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($promotion_activity==0){ echo'checked'; }} ?> tabindex="57" class="followup-checkbox" id="promotion_activity" name="promotion_activity" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="promotion_activity">Promotion Activity</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_followup==0){ echo'checked'; }} ?> tabindex="58" class="followup-checkbox" id="loan_followup" name="loan_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_followup">Loan Followup</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($conf_followup==0){ echo'checked'; }} ?> tabindex="59" class="followup-checkbox" id="conf_followup" name="conf_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="conf_followup">Confirmation Followup</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($due_followup==0){ echo'checked'; }} ?> tabindex="60" class="followup-checkbox" id="due_followup" name="due_followup" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="due_followup">Due Followup</label>
                            </div>
                        </div>
					</div>
						</br></br>
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($reportmodule==0){ echo'checked'; }} ?> tabindex="61" class="" id="reportmodule" name="reportmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="reportmodule">
							<h5>Report</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($ledger_report==0){ echo'checked'; }} ?> tabindex="62" class="report-checkbox" id="ledger_report" name="ledger_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="ledger_report">Ledger View</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($request_report==0){ echo'checked'; }} ?> tabindex="63" class="report-checkbox" id="request_report" name="request_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="request_report">Request</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($cus_profile_report==0){ echo'checked'; }} ?> tabindex="64" class="report-checkbox" id="cus_profile_report" name="cus_profile_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="cus_profile_report">Customer Profile</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_issue_report==0){ echo'checked'; }} ?> tabindex="65" class="report-checkbox" id="loan_issue_report" name="loan_issue_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_issue_report">Loan Issue</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($collection_report==0){ echo'checked'; }} ?> tabindex="66" class="report-checkbox" id="collection_report" name="collection_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="collection_report">Collection</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($balance_report==0){ echo'checked'; }} ?> tabindex="67" class="report-checkbox" id="balance_report" name="balance_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="balance_report">Balance</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($due_list_report==0){ echo'checked'; }} ?> tabindex="68" class="report-checkbox" id="due_list_report" name="due_list_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="due_list_report">Due List</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($closed_report==0){ echo'checked'; }} ?> tabindex="69" class="report-checkbox" id="closed_report" name="closed_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="closed_report">Closed</label>
                            </div>
                        </div>
						<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($agent_report==0){ echo'checked'; }} ?> tabindex="69" class="report-checkbox" id="agent_report" name="agent_report" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="agent_report">Agent</label>
                            </div>
                        </div>
					</div>

					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($search_module==0){ echo'checked'; }} ?> tabindex="70" class="" id="searchmodule" name="searchmodule" >&nbsp;&nbsp;
						<label class="custom-control-label" for="searchmodule">
							<h5>Search</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($search_screen==0){ echo'checked'; }} ?> tabindex="71" class="search-checkbox" id="search_screen" name="search_screen" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="search_screen">Search</label>
                            </div>
                        </div>
					</div>

					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bulk_upload_module==0){ echo'checked'; }} ?> tabindex="72" class="" id="bulk_upload_module" name="bulk_upload_module" >&nbsp;&nbsp;
						<label class="custom-control-label" for="bulk_upload_module">
							<h5>Bulk Upload</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($bulk_upload==0){ echo'checked'; }} ?> tabindex="73" class="bulk_upload-checkbox" id="bulk_upload" name="bulk_upload" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="bulk_upload">Bulk Upload</label>
                            </div>
                        </div>
					</div>
					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_track_module==0){ echo'checked'; }} ?> tabindex="74" class="" id="loan_track_module" name="loan_track_module" >&nbsp;&nbsp;
						<label class="custom-control-label" for="loan_track_module">
							<h5>Loan Track</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($loan_track==0){ echo'checked'; }} ?> tabindex="75" class="loan_track-checkbox" id="loan_track" name="loan_track" disabled>&nbsp;&nbsp;
                                <label class="custom-control-label" for="loan_track">Loan Track</label>
                            </div>
                        </div>
					</div>
					
					<hr>

					<div class="custom-control custom-checkbox">
						<input type="checkbox" value="Yes" <?php if($idupd > 0){ if($sms_module==0){ echo'checked'; }} ?> tabindex="74" id="sms_module" name="sms_module" >&nbsp;&nbsp;
						<label class="custom-control-label" for="sms_module">
							<h5>SMS</h5>
						</label>
					</div>
					<br>
					<div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="Yes" <?php if($idupd > 0){ if($sms_generation==0){ echo'checked'; }} ?> tabindex="75" class="sms_generation-checkbox" id="sms_generation" name="sms_generation" disabled>&nbsp;&nbsp;
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
						<button type="submit" name="submit_manage_user" id="submit_manage_user" class="btn btn-primary" value="Submit" tabindex="99"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="100" >Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>


