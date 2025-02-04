<style>
	#imgshow {
		height: 150px;
		width: 150px;
		border-radius: 50%;
		object-fit: cover;
		background-color: white;
	}
</style>

<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$areaList = $userObj->getArea($mysqli);

$loanCategoryList = $userObj->getloanCategoryList($mysqli);

if (isset($_POST['submit_request']) && $_POST['submit_request'] != '') {
?>
	<script>
		$('#submit_request').attr('disabled', true);
	</script>

	<?php
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateRequest($mysqli, $id, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_request&msc=2';
		</script>
	<?php	} else {
		$userObj->addRequest($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_request&msc=1';
		</script>
	<?php
	}
}

$del = 0;
if (isset($_GET['del'])) {
	$del = $_GET['del'];
}
if ($del > 0) {
	$userObj->deleteRequest($mysqli, $del, $userid);
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_request&msc=3';
	</script>
<?php
}
// $can=0;
// if(isset($_GET['can']))
// {
// $can=$_GET['can'];
// }
// if($can>0)
// {
// 	$cancelRequest = $userObj->cancelRequest($mysqli,$can, $userid);
// 	
?>
<!-- // 	<script>location.href='<?php //echo $HOSTPATH;  
									?>edit_request&msc=4';</script> -->
<?php
// }
// $rev=0;
// if(isset($_GET['rev']))
// {
// $rev=$_GET['rev'];
// }
// if($rev>0)
// {
// 	$revokeRequest = $userObj->revokeRequest($mysqli,$rev, $userid);
// 	
?>
<!-- // 	<script>location.href='<?php //echo $HOSTPATH;  
									?>edit_request&msc=8';</script> -->
<?php
// }
$idupd = 0;
// $role=0;
if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
if ($idupd > 0) {
	$getRequest = $userObj->getRequest($mysqli, $idupd);

	if (sizeof($getRequest) > 0) {
		for ($i = 0; $i < sizeof($getRequest); $i++) {
			$req_id						= $getRequest['req_id'];
			$user_type					= $getRequest['user_type'];
			if ($user_type == 'Director') {
				$role = '1';
			} else
			if ($user_type == 'Agent') {
				$role = '2';
			} else
			if ($user_type == 'Staff') {
				$role = '3';
			}
			$user_name					= $getRequest['user_name'];
			$agent_id					= $getRequest['agent_id'];
			$responsible					= $getRequest['responsible'];
			$remarks					= $getRequest['remarks'];
			$declaration					= $getRequest['declaration'];
			$req_code					= $getRequest['req_code'];
			$dor1					= $getRequest['dor'];
			$dor					= date_create_from_format('Y-m-d', $getRequest['dor']);
			$dor =  date_format($dor, 'd-m-Y');
			$cus_id					= $getRequest['cus_id'];
			$cus_data					= $getRequest['cus_data'];
			$cus_name					= $getRequest['cus_name'];
			$dob					= $getRequest['dob'];
			$age					= $getRequest['age'];
			$gender					= $getRequest['gender'];
			$state					= $getRequest['state'];
			$district					= $getRequest['district'];
			$taluk					= $getRequest['taluk'];
			$area					= $getRequest['area'];
			$sub_area					= $getRequest['sub_area'];
			$address					= $getRequest['address'];
			$mobile1					= $getRequest['mobile1'];
			$mobile2					= $getRequest['mobile2'];
			$father_name					= $getRequest['father_name'];
			$mother_name					= $getRequest['mother_name'];
			$marital					= $getRequest['marital'];
			$spouse_name					= $getRequest['spouse_name'];
			$occupation_type					= $getRequest['occupation_type'];
			$occupation					= $getRequest['occupation'];
			$pic					= $getRequest['pic'];
			$loan_category					= $getRequest['loan_category'];
			$sub_category					= $getRequest['sub_category'];
			$tot_value					= $getRequest['tot_value'];
			$ad_amt					= $getRequest['ad_amt'];
			$ad_perc					= $getRequest['ad_perc'];
			$loan_amt					= $getRequest['loan_amt'];
			$poss_type					= $getRequest['poss_type'];
			$due_amt					= $getRequest['due_amt'];
			$due_period					= $getRequest['due_period'];
			$cus_status					= $getRequest['cus_status'];
		}
	}
	$getCategoryInfo = $userObj->getCategoryInfo($mysqli, $idupd);
	$getCategoryInfo = implode(',', $getCategoryInfo);
} elseif ($idupd == 0) {
	$getUser = $userObj->getUser($mysqli, $userid);
	// print_r($getUser);die;
	if (sizeof($getUser) > 0) {
		for ($i = 0; $i < sizeof($getUser); $i++) {
			$user_id                 	 = $getUser['user_id'];
			$user_name          		     = $getUser['fullname'];
			$password          		     = $getUser['user_password'];
			$role          		     = $getUser['role'];
			if ($role == '1') {
				$user_type = 'Director';
			} else
			if ($role == '2') {
				$user_type = 'Agent';
			} else
			if ($role == '3') {
				$user_type = 'Staff';
			}
			$role_type          		     = $getUser['role_type'];
			$dir_id          		     = $getUser['dir_id'];
			$ag_id          		     = $getUser['ag_id'];
			$staff_id          		     = $getUser['staff_id'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Request
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_request">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="request" name="request" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" id="pending_sts"> <input type="hidden" id="od_sts"> <input type="hidden" id="due_nil_sts"> <input type="hidden" id="closed_sts"><input type="hidden" id="bal_amt">
		<?php if ($idupd == 0) { ?>
			<input type="hidden" class="form-control" value="<?php if (isset($user_id)) echo $user_id; ?>" id="user_id_load" name="user_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($role)) echo $role; ?>" id="role_load" name="role_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($role_type)) echo $role_type; ?>" id="role_type_load" name="role_type_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($dir_id)) echo $dir_id; ?>" id="dir_id_load" name="dir_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($ag_id)) echo $ag_id; ?>" id="ag_id_load" name="ag_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($staff_id)) echo $staff_id; ?>" id="staff_id_load" name="staff_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($company_id)) echo $company_id; ?>" id="company_id_load" name="company_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($branch_id)) echo $branch_id; ?>" id="branch_id_load" name="branch_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($agentforstaff)) echo $agentforstaff; ?>" id="agentforstaff_load" name="agentforstaff_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($agent_id)) echo $agent_id; ?>" id="agent_id_load" name="agent_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($line_id)) echo $line_id; ?>" id="line_id_load" name="line_id_load" aria-describedby="id" placeholder="Enter id">
			<input type="hidden" class="form-control" value="<?php if (isset($group_id)) echo $group_id; ?>" id="group_id_load" name="group_id_load" aria-describedby="id" placeholder="Enter id">
		<?php } elseif ($idupd > 0) { ?>
			<input type="hidden" class="form-control" value="<?php if (isset($idupd)) echo $idupd; ?>" id="id" name="id">
			<input type="hidden" class="form-control" value="<?php if (isset($userid)) echo $userid; ?>" id="userid_upd" name="userid_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($user_type)) echo $user_type; ?>" id="user_type_upd" name="user_type_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($role)) echo $role; ?>" id="role_upd" name="role_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($user_name)) echo $user_name; ?>" id="user_name_upd" name="user_name_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($agent_id)) echo $agent_id; ?>" id="ag_id_upd" name="ag_id_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($responsible)) echo $responsible; ?>" id="responsible_upd" name="responsible_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($remarks)) echo $remarks; ?>" id="remarks_upd" name="remarks_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($declaration)) echo $declaration; ?>" id="declaration_upd" name="declaration_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($req_code)) echo $req_code; ?>" id="req_code_upd" name="req_code_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($dor)) echo $dor; ?>" id="dor_upd" name="dor_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($cus_id)) echo $cus_id; ?>" id="cus_id_upd" name="cus_id_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($cus_data)) echo $cus_data; ?>" id="cus_data_upd" name="cus_data_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($cus_name)) echo $cus_name; ?>" id="cus_name_upd" name="cus_name_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($dob)) echo $dob; ?>" id="dob_upd" name="dob_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($age)) echo $age; ?>" id="age_upd" name="age_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($gender)) echo $gender; ?>" id="gender_upd" name="gender_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($state)) echo $state; ?>" id="state_upd" name="state_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($district)) echo $district; ?>" id="district_upd" name="district_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($taluk)) echo $taluk; ?>" id="taluk_upd" name="taluk_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($area)) echo $area; ?>" id="area_upd" name="area_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($sub_area)) echo $sub_area; ?>" id="sub_area_upd" name="sub_area_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($address)) echo $address; ?>" id="address_upd" name="address_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($mobile1)) echo $mobile1; ?>" id="mobile1_upd" name="mobile1_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($mobile2)) echo $mobile2; ?>" id="mobile2_upd" name="mobile2_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($father_name)) echo $father_name; ?>" id="father_name_upd" name="father_name_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($mother_name)) echo $mother_name; ?>" id="mother_name_upd" name="mother_name_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($marital)) echo $marital; ?>" id="marital_upd" name="marital_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($spouse_name)) echo $spouse_name; ?>" id="spouse_name_upd" name="spouse_name_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($occupation_type)) echo $occupation_type; ?>" id="occupation_type_upd" name="occupation_type_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($occupation)) echo $occupation; ?>" id="occupation_upd" name="occupation_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($pic)) echo $pic; ?>" id="pic_upd" name="pic_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($loan_category)) echo $loan_category; ?>" id="loan_category_upd" name="loan_category_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($sub_category)) echo $sub_category; ?>" id="sub_category_upd" name="sub_category_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($tot_value)) echo $tot_value; ?>" id="tot_value_upd" name="tot_value_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($ad_amt)) echo $ad_amt; ?>" id="ad_amt_upd" name="ad_amt_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($ad_perc)) echo $ad_perc; ?>" id="ad_perc_upd" name="ad_perc_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($loan_amt)) echo $loan_amt; ?>" id="loan_amt_upd" name="loan_amt_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($poss_type)) echo $poss_type; ?>" id="poss_type_upd" name="poss_type_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($due_amt)) echo $due_amt; ?>" id="due_amt_upd" name="due_amt_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($due_period)) echo $due_period; ?>" id="due_period_upd" name="due_period_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($cus_status)) echo $cus_status; ?>" id="cus_status_upd" name="cus_status_upd">
			<input type="hidden" class="form-control" value="<?php if (isset($getCategoryInfo)) echo $getCategoryInfo; ?>" id="getCategoryInfo_upd" name="getCategoryInfo_upd">
		<?php } ?>
		<!-- Row start -->
		<div class="row gutters">
			<!-- General Info -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Request Info</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="user_type">User type</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="user_type" name="user_type" readonly value='<?php if (isset($user_type)) echo $user_type; ?>' tabindex="1">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="user">User Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="user" name="user" readonly value='<?php if (isset($user_name)) echo $user_name; ?>' tabindex='2'>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 agent" <?php if (isset($role)) {
																								if ($role == '2') { ?>style="display: none" <?php }
																																	} ?>>
								<div class="form-group">
									<label for="agent">Agent</label>
									<select tabindex="3" type="text" class="form-control" id="agent" name="agent">
										<option value="">Select Agent Name</option>
									</select>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 responsible" <?php if (isset($role)) {
																									if ($role != '1') { ?> style="display: none" <?php }
																																			} ?>>
								<div class="form-group">
									<label for="responsible">Responsible&nbsp;<span class="required">&nbsp;*</span></label>
									<select tabindex="4" type="text" class="form-control" id="responsible" name="responsible">
										<option value="">Select Responsible</option>
										<option value="0" <?php if (isset($responsible) and $responsible == '0') echo 'selected'; ?>>Yes</option>
										<option value="1" <?php if (isset($responsible) and $responsible == '1') echo 'selected'; ?>>No</option>
									</select>
									<span class="text-danger" style='display:none' id='responsibleCheck'>Please Select Responsible</span>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 remarks" <?php if (isset($role)) {
																								if ($role != '3') { ?>style="display: none" <?php }
																																	} ?>>
								<div class="form-group">
									<label for="remark">Remarks</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="remark" name="remark" value='<?php if (isset($remarks)) echo $remarks; ?>' tabindex='5' placeholder="Enter Remarks" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='remarkCheck'>Please Enter Remarks</span>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 declaration" <?php if (isset($role)) {
																									if ($role == '3') { ?>style="display: none" <?php }
																																		} ?>>
								<div class="form-group">
									<label for="declaration">Declaration</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="declaration" name="declaration" value='<?php if (isset($declaration)) echo $declaration; ?>' tabindex='6' placeholder="Enter Declaration" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='declarationCheck'>Please Enter Declaration</span>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="req_code">Request ID</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="req_code" name="req_code" readonly value='<?php if (isset($req_code)) echo $req_code; ?>' tabindex='7'>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="dor">Date Of request</label><span class="required">&nbsp;*</span>
									<input type="hidden" class="form-control" id="dor" name="dor" readonly value='<?php if (isset($dor1)) {
																														echo $dor1;
																													} else {
																														echo date('Y-m-d');
																													} ?>'>
									<input type="text" class="form-control" id="dor1" name="dor1" readonly value='<?php if (isset($dor)) {
																														echo $dor;
																													} else {
																														echo date('d-m-Y');
																													} ?>' tabindex='8'>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">General Info <!--<input type="button" class="btn btn-outline-secondary text-right" id="cus_status" name="cus_status" value="Customer Status" style="float:right">--></div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-8">
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="cus_id">Customer ID</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cus_id)) {
																													echo $cus_id;
																												} ?>' tabindex='9' data-type="adhaar-number" maxlength="14" placeholder="Enter Adhaar Number">
									<span class="text-danger" style='display:none' id='cusidCheck'>Please Enter Customer ID</span>
								</div>
							</div>
							<?php if ($role != '2') { //customer status not for agents
							?>
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
									<div class="form-group">
										<br>
										<input type="button" class="btn btn-outline-secondary text-right" id="cus_status" name="cus_status" value="Customer Status" tabindex='10'>
									</div>
								</div>
							<?php } ?>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="cus_data">Customer Data</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="cus_data" name="cus_data" readonly value='<?php if (isset($cus_data)) {
																																echo $cus_data;
																															} ?>' tabindex='11'>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="cus_name">Customer Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cus_name)) {
																														echo $cus_name;
																													} ?>' tabindex='12' placeholder="Enter Customer Name" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='cusnameCheck'>Please Enter Customer Name</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="dob">Date of Birth</label><span class="required">&nbsp;*</span>
									<input type="date" class="form-control" id="dob" name="dob" value='<?php if (isset($dob)) {
																											echo $dob;
																										} ?>' tabindex='13'>
									<span class="text-danger" style='display:none' id='dobCheck'>Please Select DOB</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="age">Age</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="age" name="age" readonly value='<?php if (isset($age)) {
																													echo $age;
																												} ?>' tabindex='14'>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="gender">Gender&nbsp;<span class="required">&nbsp;*</span></label>
									<select tabindex="15" type="text" class="form-control" id="gender" name="gender">
										<option value="">Select Gender</option>
										<option value="1" <?php if (isset($gender) and $gender == '1') echo 'selected'; ?>>Male</option>
										<option value="2" <?php if (isset($gender) and $gender == '2') echo 'selected'; ?>>Female</option>
										<option value="3" <?php if (isset($gender) and $gender == '3') echo 'selected'; ?>>Other</option>
									</select>
									<span class="text-danger" style='display:none' id='genderCheck'>Please Select Gender</span>
								</div>
							</div>

							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
									<select type="text" class="form-control" id="state" name="state" tabindex="16">
										<option value="SelectState">Select State</option>
										<option value="TamilNadu" <?php if (isset($state) && $state == 'TamilNadu') echo 'selected' ?>>Tamil Nadu</option>
										<option value="Puducherry" <?php if (isset($state) && $state == 'Puducherry') echo 'selected' ?>>Puducherry</option>
									</select>
									<span class="text-danger" style='display:none' id='stateCheck'>Please Select State</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="disabledInput">District</label>&nbsp;<span class="text-danger">*</span>
									<input type="hidden" class="form-control" id="district1" name="district1">
									<select type="text" class="form-control" id="district" name="district" tabindex='17'>
										<option value="Select District">Select District</option>
									</select>
									<span class="text-danger" style='display:none' id='districtCheck'>Please Select District</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
									<input type="hidden" class="form-control" id="taluk1" name="taluk1">
									<select type="text" class="form-control" id="taluk" name="taluk" tabindex="18">
										<option value="Select Taluk">Select Taluk</option>
									</select>
									<span class="text-danger" style='display:none' id='talukCheck'>Please Select Taluk</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="disabledInput">Area</label>&nbsp;<span class="text-danger">*</span>
									<select tabindex="19" type="text" class="form-control" id="area" name="area">
										<option value="">Select Area</option>

									</select>
									<span class="text-danger" style='display:none' id='areaCheck'>Please Select Area</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="disabledInput">Sub Area</label>&nbsp;<span class="text-danger">*</span>
									<select tabindex="20" type="text" class="form-control" id="sub_area" name="sub_area">
										<option value=''>Select Sub Area</option>
									</select>
									<span class="text-danger" style='display:none' id='subareaCheck'>Please Select Sub Area</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="address">Address</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="address" name="address" value='<?php if (isset($address)) {
																													echo $address;
																												} ?>' tabindex='21' placeholder="Enter Address">
									<span class="text-danger" style='display:none' id='addressCheck'>Please Enter Address</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="mobile1">Mobile No 1</label><span class="required">&nbsp;*</span>
									<input type="number" class="form-control" id="mobile1" name="mobile1" onkeypress="if(this.value.length==10) return false;" value='<?php if (isset($mobile1)) {
																																											echo $mobile1;
																																										} ?>' tabindex='22' placeholder="Enter Mobile Number">
									<span class="text-danger" style='display:none' id='mobile1Check'>Please Enter Mobile Number</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="mobile2">Mobile No 2</label>
									<input type="number" class="form-control" id="mobile2" name="mobile2" onkeypress="if(this.value.length==10) return false;" value='<?php if (isset($mobile2)) {
																																											echo $mobile2;
																																										} ?>' tabindex='23' placeholder="Enter Mobile Number">
									<span class="text-danger" style='display:none' id='mobile2Check'>Please Enter Mobile Number</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="father_name">Father Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="father_name" name="father_name" value='<?php if (isset($father_name)) {
																															echo $father_name;
																														} ?>' tabindex='24' placeholder="Enter Father's Name" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='fathernameCheck'>Please Enter Father Name</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="mother_name">Mother Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="mother_name" name="mother_name" value='<?php if (isset($mother_name)) {
																															echo $mother_name;
																														} ?>' tabindex='25' placeholder="Enter Mother's Name" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='mothernameCheck'>Please Enter Mother Name</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="marital">Marital Status<span class="required">&nbsp;*</span></label>
									<select tabindex="26" type="text" class="form-control" id="marital" name="marital">
										<option value="">Select Marital Status</option>
										<option value="1" <?php if (isset($marital) and $marital == '1') echo 'selected'; ?>>Married</option>
										<option value="2" <?php if (isset($marital) and $marital == '2') echo 'selected'; ?>>Unmarried</option>
									</select>
									<span class="text-danger" style='display:none' id='maritalCheck'>Please Select Marital status</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8 spouse" style="display:none">
								<div class="form-group">
									<label for="spouse_name">Spouse Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="spouse_name" name="spouse_name" value='<?php if (isset($spouse_name)) {
																															echo $spouse_name;
																														} ?>' tabindex='27' placeholder="Enter Spouse Name" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='spousenameCheck'>Please Enter Spouse Name</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="occupation_type">Occupation Type<span class="required">&nbsp;*</span></label>
									<select tabindex="28" type="text" class="form-control" id="occupation_type" name="occupation_type">
										<option value="">Select Occupation Type</option>
										<option value="1" <?php if (isset($occupation_type) and $occupation_type == '1') echo 'selected'; ?>>Govt Job</option>
										<option value="2" <?php if (isset($occupation_type) and $occupation_type == '2') echo 'selected'; ?>>Pvt Job</option>
										<option value="3" <?php if (isset($occupation_type) and $occupation_type == '3') echo 'selected'; ?>>Business</option>
										<option value="4" <?php if (isset($occupation_type) and $occupation_type == '4') echo 'selected'; ?>>Self Employed</option>
										<option value="5" <?php if (isset($occupation_type) and $occupation_type == '5') echo 'selected'; ?>>Daily wages</option>
										<option value="6" <?php if (isset($occupation_type) and $occupation_type == '6') echo 'selected'; ?>>Agriculture</option>
										<option value="7" <?php if (isset($occupation_type) and $occupation_type == '7') echo 'selected'; ?>>Others</option>
									</select>
									<span class="text-danger" style='display:none' id='occupationtypeCheck'>Please Select Occupation Type</span>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
								<div class="form-group">
									<label for="occupation">Occupation</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="occupation" name="occupation" value='<?php if (isset($occupation)) {
																															echo $occupation;
																														} ?>' tabindex='29' placeholder="Enter Occupation" pattern="[a-zA-Z\s]+">
									<span class="text-danger" style='display:none' id='occupationCheck'>Please Enter Occupation</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
							<div class="form-group" style="margin-left: 30px;">
								<label for="pic" style="margin-left: -20px;">Photo</label><span class="required">&nbsp;*</span><br>
								<input type='hidden' id='img_exist' name='img_exist' value=''>
								<img id='imgshow' src='img/avatar.png' />
								<input type="file" onchange="compressImage(this, 200)" class="form-control" id="pic" name="pic" value='<?php if (isset($pic)) {
																																				echo $pic;
																																			} ?>' tabindex='30'>
								<span class="text-danger" style='display:none' id='picCheck'>Please Choose Image</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">Loan Info</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
							<select tabindex="31" type="text" class="form-control" id="loan_category" name="loan_category">
								<option value="">Select Loan Category</option>
								<?php if (sizeof($loanCategoryList) > 0) {
									for ($j = 0; $j < count($loanCategoryList); $j++) { ?>
										<option <?php if (isset($loan_category)) {
													if ($loanCategoryList[$j]['loan_category_name_id'] == $loan_category)  echo 'selected';
												}  ?> value="<?php echo $loanCategoryList[$j]['loan_category_name_id']; ?>">
											<?php echo $loanCategoryList[$j]['loan_category_name']; ?></option>
								<?php }
								} ?>
							</select>
							<span class="text-danger" style='display:none' id='loancategoryCheck'>Please Select Loan Category</span>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="disabledInput">Sub Category</label>&nbsp;<span class="text-danger">*</span>
							<select tabindex="32" type="text" class="form-control" id="sub_category" name="sub_category">
								<option value="">Select Sub Category</option>
							</select>
							<span class="text-danger" style='display:none' id='subcategoryCheck'>Please Select Sub Category</span>
						</div>
					</div>
					<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none"></div> -->
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none">
						<div class="form-group">
							<label for="tot_value">Total value</label><span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="tot_value" name="tot_value" value='<?php if (isset($tot_value)) {
																													echo $tot_value;
																												} ?>' tabindex='33' placeholder="Enter Total Value">
						</div>
						<span class="text-danger" style='display:none' id='totvalueCheck'>Please Enter Total Value</span>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none">
						<div class="form-group">
							<label for="ad_amt">Advance Amount</label><span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="ad_amt" name="ad_amt" value='<?php if (isset($ad_amt)) {
																											echo $ad_amt;
																										} ?>' tabindex='34' placeholder="Enter Advance Amount">
							<span class="text-danger" style='display:none' id='adamtCheck'>Please Enter Advance Amount</span>
						</div>
					</div>
					<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none"></div> -->
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none">
						<div class="form-group">
							<label for="ad_perc">Advance %</label><span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="ad_perc" name="ad_perc" readonly value='<?php if (isset($ad_perc)) {
																													echo $ad_perc;
																												} ?>' tabindex='35'>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 loan_amt" style="display:none">
						<div class="form-group">
							<label for="loan_amt">Loan Amount</label><span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="loan_amt" name="loan_amt" readonly value='<?php if (isset($loan_amt)) {
																														echo $loan_amt;
																													} ?>' tabindex='36'>
							<span class="text-danger" style='display:none' id='loanamtCheck'>Please Enter Loan Amount</span>
						</div>
					</div>
					<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_no" style="display:none">
								<div class="form-group">
									<label for="loan_amt">Loan Amount</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control " id="loan_amt" name="loan_amt" readonly value='' tabindex='35'>
								</div>
							</div> -->
				</div>
			</div>
		</div>
		<div class="card category_info" style="display:none">
			<div class="card-header">Category Info</div>
			<div class="card-body">
				<div class="row">
				</div>
			</div>
		</div>
		<div class="card ">
			<div class="card-header">Possibilty Info</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="poss_type">Possibility<span class="required">&nbsp;*</span></label>
							<select tabindex="37" type="text" class="form-control" id="poss_type" name="poss_type">
								<option value="">Select Possibility</option>
								<option value="1" <?php if (isset($poss_type) and $poss_type == '1') echo 'selected'; ?>>Due Amount</option>
								<option value="2" <?php if (isset($poss_type) and $poss_type == '2') echo 'selected'; ?>>Due Period</option>
							</select>
							<span class="text-danger" style='display:none' id='posstypeCheck'>Please Select Possibility Type</span>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 due_amt" style="display:none">
						<div class="form-group">
							<label for="due_amt">Due Amount</label><span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="due_amt" name="due_amt" value='<?php if (isset($due_amt)) {
																												echo $due_amt;
																											} ?>' tabindex='38'>
							<span class="text-danger" style='display:none' id='dueamtCheck'>Please Enter Due Amount</span>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 due_period" style="display:none">
						<div class="form-group">
							<label for="due_period">Due Period</label><span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="due_period" name="due_period" value='<?php if (isset($due_period)) {
																													echo $due_period;
																												} ?>' tabindex='39'>
							<span class="text-danger" style='display:none' id='dueperiodCheck'>Please Enter Due Period</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card ">
			<div class="card-header">EMI Calculator <!--<span style="margin-left:90em; font-weight:bold" class="icon-chevron-down1"></span>--></div>
			<div class="card-body emi_calculator">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="calc_loan_amt">Loan Amount</label>
							<input type="number" class="form-control" id="calc_loan_amt" name="calc_loan_amt" tabindex='40'>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="calc_int_rate">Rate of Interest</label>
							<input type="number" class="form-control" id="calc_int_rate" name="calc_int_rate" tabindex='41' pattern="[0-9]*\.?[0-9]+">
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="calc_due_period">Due Period</label>
							<input type="number" class="form-control" id="calc_due_period" name="calc_due_period" tabindex='42'>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group" style="text-align:center">
							<span class="text-danger" style='display:none' id='calcCheck'>Please Fill All<br><br></span>
							<button type="button" class="btn btn-primary" id="get_emi" name="get_emi" tabindex='43'>Get Due Amount&nbsp;<span class="icon-arrow-down-circle"></span></button>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
					<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" >
								<div class="form-group">
									<label for="calc_int_amt">Interest Amount</label>
									<input type="text" class="form-control" id="calc_int_amt" name="calc_int_amt" readonly>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" >
								<div class="form-group">
									<label for="calc_tot_amt">Total Amount</label>
									<input type="text" class="form-control" id="calc_tot_amt" name="calc_tot_amt" readonly>
								</div>
							</div> -->
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="calc_due_amt">Due Amount</label>
							<input type="text" class="form-control" id="calc_due_amt" name="calc_due_amt" readonly tabindex="44">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 ">
			<div class="text-right">
				<button type="submit" name="submit_request" id="submit_request" class="btn btn-primary" value="Submit" tabindex="45"><span class="icon-check"></span>&nbsp;Submit</button>
				<button type="reset" class="btn btn-outline-secondary" tabindex="46">Clear</button>
			</div>
		</div>

</div>
</div>
</form>
</div>


<!-- Customer Status Modal -->
<div class="modal fade customerstatus" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Customer Status</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="updatedcusHistoryTable">
					<table class="table custom-table" id="cusHistoryTable">

					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>