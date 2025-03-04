<?php
if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
if (isset($_GET['pge'])) {
	$pge = $_GET['pge']; // 1 = page edit Documentation. // 2 = View Page.
}
$rem = 0;
if (isset($_GET['rem'])) //remove for Acknowledgement
{
	$rem = $_GET['rem'];
}
if ($rem > 0) {
	$userObj->removeAcknowledgement($mysqli, $rem, $userid);
?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_acknowledgement_list&msc=2';
	</script>
<?php
}

if (isset($_POST['submit_documentation']) && $_POST['submit_documentation'] != '') {

?>
	<script>
		$('#submit_documentation').attr('disabled', true);
	</script>

	<?php
	$userObj->addAcknowlegeDocumentation($mysqli, $userid);
	?>
	<script>
		alert('Documentation Details Submitted');
	</script>
	<script>
		$('#submit_documentation').removeAttr('disabled');
	</script>
<?php
}


$getRequestData = $userObj->getRequestForVerification($mysqli, $idupd);
if (sizeof($getRequestData) > 0) {
	for ($i = 0; $i < sizeof($getRequestData); $i++) {
		$req_id						= $getRequestData['req_id'];
		$user_type					= $getRequestData['user_type'];
		if ($user_type == 'Director') {
			$role = '1';
		} else
			if ($user_type == 'Agent') {
			$role = '2';
		} else
			if ($user_type == 'Staff') {
			$role = '3';
		}
		$user_name					= $getRequestData['user_name'];
		$agent_id					= $getRequestData['agent_id'];
		$responsible					= $getRequestData['responsible'];
		$remarks					= $getRequestData['remarks'];
		$declaration					= $getRequestData['declaration'];
		$req_code					= $getRequestData['req_code'];
		$dor					= date('d-m-Y', strtotime($getRequestData['dor']));
		$cus_id					= $getRequestData['cus_id'];
		$cus_data					= $getRequestData['cus_data'];
		$cus_name					= $getRequestData['cus_name'];
		$dob					= $getRequestData['dob'];
		$age					= $getRequestData['age'];
		$gender					= $getRequestData['gender'];
		$blood_group					= $getRequestData['blood_group'];
		$state					= $getRequestData['state'];
		$district					= $getRequestData['district'];
		$taluk					= $getRequestData['taluk'];
		$area					= $getRequestData['area'];
		$sub_area					= $getRequestData['sub_area'];
		$address					= $getRequestData['address'];
		$mobile1					= $getRequestData['mobile1'];
		$mobile2					= $getRequestData['mobile2'];
		$father_name					= $getRequestData['father_name'];
		$mother_name					= $getRequestData['mother_name'];
		$marital					= $getRequestData['marital'];
		$spouse_name					= $getRequestData['spouse_name'];
		$occupation_type					= $getRequestData['occupation_type'];
		$occupation					= $getRequestData['occupation'];
		$pic					= $getRequestData['pic'];
		$loan_category					= $getRequestData['loan_category'];
		$sub_category					= $getRequestData['sub_category'];
		$tot_value					= $getRequestData['tot_value'];
		$ad_amt					= $getRequestData['ad_amt'];
		$ad_perc					= $getRequestData['ad_perc'];
		$loan_amt					= $getRequestData['loan_amt'];
		$poss_type					= $getRequestData['poss_type'];
		$due_amt					= $getRequestData['due_amt'];
		$due_period					= $getRequestData['due_period'];
		$cus_status					= $getRequestData['cus_status'];
		$area_nameforhead 					= $getRequestData['area_name'];
		$sub_area_nameforhead 					= $getRequestData['sub_area_name'];
		$agent_name 					= $getRequestData['agent_name'];
	}
}

//////////////////////// Customer Profile Info ///////////////////////////////

$getCustomerProfile = $userObj->getAcknowlegeCustomerProfile($mysqli, $idupd);

if (sizeof($getCustomerProfile) > 0) {
	$cus_Tableid = $getCustomerProfile['cus_Tableid'];
	$cus_req_id = $getCustomerProfile['req_id'];
	$cp_cus_id = $getCustomerProfile['cus_id'];
	$cp_cus_name = $getCustomerProfile['cus_name'];
	$cp_gender = $getCustomerProfile['gender'];
	$cp_dob = $getCustomerProfile['dob'];
	$cp_age = $getCustomerProfile['age'];
	$cp_blood_group = $getCustomerProfile['blood_group'];
	$cp_mobile1  = $getCustomerProfile['mobile1'];
	$cp_mobile2 = $getCustomerProfile['mobile2'];
	$cp_whatsapp = $getCustomerProfile['whatsapp'];
	$cp_cus_pic = $getCustomerProfile['cus_pic'];
	$guarentor_name = $getCustomerProfile['guarentor_name'];
	$guarentor_relation = $getCustomerProfile['guarentor_relation'];
	$guarentor_photo = $getCustomerProfile['guarentor_photo'];
	$cus_type = $getCustomerProfile['cus_type'];
	$cus_exist_type = $getCustomerProfile['cus_exist_type'];
	$residential_type = $getCustomerProfile['residential_type'];
	$residential_details = $getCustomerProfile['residential_details'];
	$residential_address = $getCustomerProfile['residential_address'];
	$residential_native_address = $getCustomerProfile['residential_native_address'];
	$cp_occupation_type = $getCustomerProfile['occupation_type'];
	$occupation_details = $getCustomerProfile['occupation_details'];
	$occupation_income = $getCustomerProfile['occupation_income'];
	$occupation_address = $getCustomerProfile['occupation_address'];
	$area_confirm_type = $getCustomerProfile['area_confirm_type'];
	$area_confirm_state = $getCustomerProfile['area_confirm_state'];
	$area_confirm_district = $getCustomerProfile['area_confirm_district'];
	$area_confirm_taluk = $getCustomerProfile['area_confirm_taluk'];
	$area_confirm_area = $getCustomerProfile['area_confirm_area'];
	$area_confirm_subarea = $getCustomerProfile['area_confirm_subarea'];
	$latlong = $getCustomerProfile['latlong'];
	$area_group = $getCustomerProfile['area_group'];
	$area_line = $getCustomerProfile['area_line'];
	// $communication = $getCustomerProfile['communication'];
	// $com_audio = $getCustomerProfile['com_audio'];
	// $verification_person = $getCustomerProfile['verification_person'];
	// $verification_location = $getCustomerProfile['verification_location'];
	$cp_cus_status = $getCustomerProfile['cus_status'];
	$how_to_know = $getCustomerProfile['how_to_know'];
	$loan_count = $getCustomerProfile['loan_count'];
	$first_loan_date = $getCustomerProfile['first_loan_date'];
	$travel_with_company = $getCustomerProfile['travel_with_company'];
	$monthly_income = $getCustomerProfile['monthly_income'];
	$other_income = $getCustomerProfile['other_income'];
	$support_income = $getCustomerProfile['support_income'];
	$commitment = $getCustomerProfile['commitment'];
	$monthly_due_capacity = $getCustomerProfile['monthly_due_capacity'];
	$loan_limit = $getCustomerProfile['loan_limit'];
	// $cus_character = $getCustomerProfile['cus_character'];
	// $approach = $getCustomerProfile['approach'];
	// $relationship = $getCustomerProfile['relationship'] ;
	// $attitude = $getCustomerProfile['attitude'] ;
	// $behavior = $getCustomerProfile['behavior'] ;
	// $incident_remark  = $getCustomerProfile['incident_remark'] ;
	$about_customer = $getCustomerProfile['about_customer'];
	$dow 					= $getCustomerProfile['dow'];
	$abt_occ 					= $getCustomerProfile['abt_occ'];
}

//////////////////////// Customer Profile Info END ///////////////////////////////

////////  Document Customer Info ///// 
$getcusInfoForDoc = $userObj->getAckcusInfoForDoc($mysqli, $idupd);
if (sizeof($getcusInfoForDoc) > 0) {

	$cus_profile_id = $getcusInfoForDoc['cus_profile_id'];
	$doc_cus_id = $getcusInfoForDoc['cus_id'];
	$doc_cus_name = $getcusInfoForDoc['cus_name'];
	$doc_area_name = $getcusInfoForDoc['area_name'];
	$doc_sub_area_name = $getcusInfoForDoc['sub_area_name'];
	$customer_profile_sts = $getcusInfoForDoc['cus_status'];
}

////   Documentation ////////////
$documentationInfo = $userObj->getAcknowlegementDocument($mysqli, $req_id);

if (sizeof($documentationInfo) > 0) {
	$document_table_id = $documentationInfo['doc_Tableid'];
	$document_sts = $documentationInfo['cus_status'];
	$mortgage_process = $documentationInfo['mortgage_process'];
	$Propertyholder_type = $documentationInfo['Propertyholder_type'];
	$Propertyholder_name = $documentationInfo['Propertyholder_name'];
	$Propertyholder_relationship_name = $documentationInfo['Propertyholder_relationship_name'];
	$doc_property_relation = $documentationInfo['doc_property_relation'];
	$doc_property_type = $documentationInfo['doc_property_type'];
	$doc_property_measurement = $documentationInfo['doc_property_measurement'];
	$doc_property_location = $documentationInfo['doc_property_location'];
	$doc_property_value = $documentationInfo['doc_property_value'];
	$mortgage_name = $documentationInfo['mortgage_name'];
	$mortgage_dsgn = $documentationInfo['mortgage_dsgn'];
	$mortgage_nuumber = $documentationInfo['mortgage_nuumber'];
	$reg_office = $documentationInfo['reg_office'];
	$mortgage_value = $documentationInfo['mortgage_value'];
	$mortgage_document = $documentationInfo['mortgage_document'];
	$mortgage_document_upd = $documentationInfo['mortgage_document_upd'];
	$mortgage_document_pending = $documentationInfo['mortgage_document_pending'];
	$endorsement_process = $documentationInfo['endorsement_process'];
	$owner_type = $documentationInfo['owner_type'];
	$owner_name = $documentationInfo['owner_name'];
	$ownername_relationship_name = $documentationInfo['ownername_relationship_name'];
	$en_relation = $documentationInfo['en_relation'];
	$vehicle_type = $documentationInfo['vehicle_type'];
	$vehicle_process = $documentationInfo['vehicle_process'];
	$en_Company = $documentationInfo['en_Company'];
	$en_Model = $documentationInfo['en_Model'];
	$vehicle_reg_no = $documentationInfo['vehicle_reg_no'];
	$endorsement_name = $documentationInfo['endorsement_name'];
	$en_RC = $documentationInfo['en_RC'];
	$Rc_document_upd = $documentationInfo['Rc_document_upd'];
	$Rc_document_pending = $documentationInfo['Rc_document_pending'];
	$en_Key = $documentationInfo['en_Key'];
	$document_name = $documentationInfo['document_name'];
	$document_details = $documentationInfo['document_details'];
	$document_type = $documentationInfo['document_type'];
	$doc_info_upload = $documentationInfo['doc_info_upload'];
	$document_holder = $documentationInfo['document_holder'];
	$docholder_name = $documentationInfo['docholder_name'];
	$docholder_relationship_name = $documentationInfo['docholder_relationship_name'];
	$doc_relation = $documentationInfo['doc_relation'];
	$submitted = $documentationInfo['submitted'];
} { //to find the documentation has been submitted in acknowledgement screen or not
	$cus_doc = $mysqli->query("SELECT submitted FROM `acknowlegement_documentation` WHERE `req_id` ='$idupd'");
	$cus_doc_row =  $cus_doc->fetch_assoc();

	if (isset($cus_doc_row['submitted']) && $cus_doc_row['submitted'] == '1') {
		$document_sts = 11;
	} else {
		$document_sts = '';
	}
}
////////   Documentation End ////////////

///////// Loan Calculation ///////////////
if (isset($_POST['submit_loan_calculation']) && $_POST['submit_loan_calculation'] != '') {
	$userObj->addAcknowledgementLoanCalculation($mysqli, $userid);

?>
	<script>
		alert('Loan Calculation Details Submitted');
	</script>

<?php
}

$getCusInfoForLoanCal = $userObj->getAcknowlegeCusInfoForLoanCal($mysqli, $idupd);
if (sizeof($getCusInfoForLoanCal) > 0) {
	for ($i = 0; $i < sizeof($getCusInfoForLoanCal); $i++) {
		$cus_id_lc = $getCusInfoForLoanCal['cus_id'];
		$cus_name_lc = $getCusInfoForLoanCal['cus_name'];
		$cus_pic_lc = $getCusInfoForLoanCal['cus_pic'];
		$cus_data_lc = $getCusInfoForLoanCal['cus_type'];
		$mobile_lc = $getCusInfoForLoanCal['mobile'];
		$communication = $getCusInfoForLoanCal['communication'];
		$com_audio = $getCusInfoForLoanCal['com_audio'];
		$verification_person = $getCusInfoForLoanCal['verification_person'];
		$verification_location = $getCusInfoForLoanCal['verification_location'];
	}
}
//Get Loan Calculation info for edit
$getLoanCalculation = $userObj->getAckLoanCalculationForVerification($mysqli, $req_id);
if (sizeof($getLoanCalculation) > 0) {
	for ($i = 0; $i < sizeof($getLoanCalculation); $i++) {
		$loan_cal_id = $getLoanCalculation['loan_cal_id'];
		$cus_id_loan = $getLoanCalculation['cus_id_loan'];
		$cus_name_loan = $getLoanCalculation['cus_name_loan'];
		$cus_data_loan = $getLoanCalculation['cus_data_loan'];
		$mobile_loan = $getLoanCalculation['mobile_loan'];
		$pic_loan = $getLoanCalculation['pic_loan'];
		$loan_category_lc = $getLoanCalculation['loan_category'];
		$sub_category_lc = $getLoanCalculation['sub_category'];
		$tot_value_lc = $getLoanCalculation['tot_value'];
		$ad_amt_lc = $getLoanCalculation['ad_amt'];
		$loan_amt_lc = $getLoanCalculation['loan_amt'];
		$profit_type_lc = $getLoanCalculation['profit_type'];
		$due_method_calc_lc = $getLoanCalculation['due_method_calc'];
		$due_type_lc = $getLoanCalculation['due_type'];
		$profit_method_lc = $getLoanCalculation['profit_method'];
		$calc_method_lc = $getLoanCalculation['calc_method'];
		$due_method_scheme_lc = $getLoanCalculation['due_method_scheme'];
		$day_scheme_lc = $getLoanCalculation['day_scheme'];
		$scheme_name_lc = $getLoanCalculation['scheme_name'];
		$profit_method_scheme_lc = $getLoanCalculation['scheme_profit_method'];
		$int_rate_lc = $getLoanCalculation['int_rate'];
		$due_period_lc = $getLoanCalculation['due_period'];
		$doc_charge_lc = $getLoanCalculation['doc_charge'];
		$proc_fee_lc = $getLoanCalculation['proc_fee'];
		$loan_amt_cal = $getLoanCalculation['loan_amt_cal'];
		$principal_amt_cal = $getLoanCalculation['principal_amt_cal'];
		$int_amt_cal = $getLoanCalculation['int_amt_cal'];
		$tot_amt_cal = $getLoanCalculation['tot_amt_cal'];
		$due_amt_cal = $getLoanCalculation['due_amt_cal'];
		$doc_charge_cal = $getLoanCalculation['doc_charge_cal'];
		$proc_fee_cal = $getLoanCalculation['proc_fee_cal'];
		$net_cash_cal = $getLoanCalculation['net_cash_cal'];
		$due_start_from = $getLoanCalculation['due_start_from'];
		$maturity_month = $getLoanCalculation['maturity_month'];
		$collection_method = $getLoanCalculation['collection_method'];
		$communication = $getLoanCalculation['communication'];
		$com_audio = $getLoanCalculation['com_audio'];
		$verification_person = $getLoanCalculation['verification_person'];
		$verification_location = $getLoanCalculation['verification_location'];
		$verify_remark = $getLoanCalculation['verify_remark'];
		$cus_status_lc = $getLoanCalculation['cus_status'];
	}

	//Get Loan calculation Category info for edit
	if ($loan_cal_id > 0) {
		$getLoanCalCategory = $userObj->getAckVerificationLoanCalCategory($mysqli, $loan_cal_id);
	}
}

///////// Loan Calculation End ///////////////

$area_topbar = isset($doc_area_name) && $doc_area_name != '' ? $doc_area_name : $area_nameforhead;
$sub_area_topbar = isset($doc_sub_area_name) && $doc_sub_area_name != '' ? $doc_sub_area_name : $sub_area_nameforhead;

?>


<style>
	.img_show {
		height: 150px;
		width: 150px;
		border-radius: 50%;
		object-fit: cover;
		background-color: white;
	}

	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
		left: 10px;
	}

	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked+.slider {
		background-color: #009688;
	}

	input:focus+.slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked+.slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Acknowledgement
	</div>
</div><br>
<div class="page-header sticky-top" id="navbar" style="display: none;" data-toggle="toggle">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px; margin-top:50px;">
		Customer Name - <?php if (isset($cus_name)) {
							echo $cus_name;
						} ?>
		,&nbsp;&nbsp;Area - <?php echo $area_topbar; ?>
		,&nbsp;&nbsp;Sub Area - <?php echo $sub_area_topbar; ?>
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_acknowledgement_list">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">

	<div class="col-md-12">
		<div class="form-group" style="text-align:center">
			<!-- <label for=''style="font-size:16px" >Verification:</label><br><br> -->
			<input type="radio" name="verification_type" id="cus_profile" value="cus_profile"></input><label for='cus_profile'>&nbsp;&nbsp; Customer Profile <?php if (isset($customer_profile_sts)) {
																																									if ($customer_profile_sts == 10) { ?> <span class="icon-done"></span> <?php }
																																																									} ?> </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="verification_type" id="documentation" value="documentation"></input><label for='documentation'>&nbsp;&nbsp; Documentation <?php if (isset($document_sts)) {
																																									if ($document_sts == 11) { ?> <span class="icon-done"></span> <?php }
																																																							} ?> </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="verification_type" id="loan_calc" value="loan_calc"></input><label for='loan_calc'>&nbsp;&nbsp; Loan Calculation <?php if (isset($cus_status_lc)) {
																																							if ($cus_status_lc == 12) { ?> <span class="icon-done"></span> <?php }
																																																					} ?> </label>
		</div>
	</div>

	<!-- Customer Profile form start-->
	<div id="customer_profile" style="display: none;">
		<form id="cus_Profiles" name="cus_Profiles" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {
																		echo $req_id;
																	} ?>" />
			<input type="hidden" name="loan_sub_cat" id="loan_sub_cat" value="<?php if (isset($sub_category)) {
																					echo $sub_category;
																				} ?>" />
			<input type="hidden" name="guarentor_name_upd" id="guarentor_name_upd" value="<?php if (isset($guarentor_name)) {
																								echo $guarentor_name;
																							} ?>" />
			<input type="hidden" name="state_upd" id="state_upd" value="<?php if (isset($area_confirm_state)) {
																			echo $area_confirm_state;
																		} ?>" />
			<input type="hidden" name="district_upd" id="district_upd" value="<?php if (isset($area_confirm_district)) {
																					echo $area_confirm_district;
																				} ?>" />
			<input type="hidden" name="taluk_upd" id="taluk_upd" value="<?php if (isset($area_confirm_taluk)) {
																			echo $area_confirm_taluk;
																		} ?>" />
			<input type="hidden" name="area_upd" id="area_upd" value="<?php if (isset($area_confirm_area)) {
																			echo $area_confirm_area;
																		} ?>" />
			<input type="hidden" name="sub_area_upd" id="sub_area_upd" value="<?php if (isset($area_confirm_subarea)) {
																					echo $area_confirm_subarea;
																				} ?>" />
			<input type="hidden" name="verification_person_upd" id="verification_person_upd" value="<?php if (isset($verification_person)) {
																										echo $verification_person;
																									} ?>" />
			<input type="hidden" name="cus_Tableid" id="cus_Tableid" value="<?php if (isset($cus_Tableid)) {
																				echo $cus_Tableid;
																			} ?>" />

			<!-- Row start -->
			<div class="row gutters">
				<!-- Request Info -->
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="card">
						<div class="card-header">Request Info <span style="font-weight:bold" class=""></span></div>
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
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="user">Agent Name</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="agent_name" name="agent_name" readonly value='<?php if (isset($agent_name)) echo $agent_name; ?>' tabindex='2'>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 responsible" <?php if (isset($role)) {
																										if ($role == '3') { ?> style="display: none" <?php }
																																				} ?>>
									<div class="form-group">
										<label for="responsible">Responsible&nbsp;<span class="required">&nbsp;*</span></label>
										<input tabindex="3" type="text" class="form-control" id="responsible" name="responsible" value="<?php if (isset($responsible) and $responsible == '0') {
																																			echo 'Yes';
																																		} else {
																																			echo 'No';
																																		} ?>" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 remarks" <?php if (isset($role)) {
																									if ($role != '3') { ?>style="display: none" <?php }
																																		} ?>>
									<div class="form-group">
										<label for="remark">Remarks</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="remarks" name="remarks" value='<?php if (isset($remarks)) echo $remarks; ?>' tabindex='4' placeholder="Enter Remarks" pattern="[a-zA-Z\s]+" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 declaration" <?php if (isset($role)) {
																										if ($role == '3') { ?>style="display: none" <?php }
																																			} ?>>
									<div class="form-group">
										<label for="declaration">Declaration</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="declaration" name="declaration" value='<?php if (isset($declaration)) echo $declaration; ?>' tabindex='5' placeholder="Enter Declaration" pattern="[a-zA-Z\s]+" readonly>
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
										<input type="text" class="form-control" id="dor" name="dor" readonly value='<?php if (isset($dor)) {
																														echo $dor;
																													} ?>' tabindex='8'>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Personal info START -->
					<div class="card">
						<div class="card-header">Personal Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_id">Customer ID</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_id" name="cus_id" tabindex='9' data-type="adhaar-number" maxlength="14" placeholder="Enter Adhaar Number" value='<?php if (isset($cus_id)) {
																																																						echo $cus_id;
																																																					} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_name">Customer Name</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_name" name="cus_name" tabindex='10' placeholder="Enter Customer Name" onkeydown="return /[a-z ]/i.test(event.key)" value='<?php if (isset($cus_name)) {
																																																								echo $cus_name;
																																																							} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="gender">Gender&nbsp;<span class="required">&nbsp;*</span></label>
												<select type="text" class="form-control" id="gender" name="gender" tabindex="11">
													<option value="">Select Gender</option>
													<option value="1" <?php if (isset($gender) and $gender == '1') echo 'selected'; ?>>Male</option>
													<option value="2" <?php if (isset($gender) and $gender == '2') echo 'selected'; ?>>Female</option>
													<option value="3" <?php if (isset($gender) and $gender == '3') echo 'selected'; ?>>Other</option>
												</select>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="dob">Date of Birth</label><span class="required">&nbsp;*</span>
												<input type="date" class="form-control" id="dob" name="dob" tabindex='12' value='<?php if (isset($dob)) {
																																		echo $dob;
																																	} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="age">Age</label>
												<input type="text" class="form-control" id="age" name="age" readonly tabindex='13' placeholder="Select Date of Birth" value='<?php if (isset($age)) {
																																													echo $age;
																																												} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="BloodGroup">Blood Group&nbsp;<span class="required">&nbsp;*</span></label>
												<input type="text" class="form-control" id="bloodGroup" name="bloodGroup" tabindex='14' placeholder="Enter Blood Group" value='<?php if (isset($cp_blood_group)) {
																																													echo $cp_blood_group;
																																												} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile1">Mobile No 1</label><span class="required">&nbsp;*</span>
												<input type="number" class="form-control" id="mobile1" name="mobile1" tabindex='15' placeholder="Enter Mobile Number" maxlength="10" onkeypress="if(this.value.length==10) return false;" value='<?php if (isset($mobile1)) {
																																																														echo $mobile1;
																																																													} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile2">Mobile No 2</label>
												<input type="number" class="form-control" id="mobile2" name="mobile2" tabindex='16' placeholder="Enter Mobile Number" maxlength="10" onKeypress="if(this.value.length==10) return false;" value='<?php if (isset($mobile2)) {
																																																														echo $mobile2;
																																																													} ?>'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="whatsapp">Whatsapp No </label>
												<input type="number" class="form-control" id="whatsapp_no" name="whatsapp_no" tabindex='17' placeholder="Enter WhatsApp Number" maxlength="10" onkeypress="if(this.value.length==10) return false;" value="<?php if (isset($cp_whatsapp)) {
																																																																echo $cp_whatsapp;
																																																															} ?>">
											</div>
										</div>

									</div>
								</div>

								<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
									<div class="col-xl-8 col-lg-10 col-md-6 ">
										<div class="form-group" style="margin-left: 30px;">
											<label for="pic" style="margin-left: -20px;">Photo</label><span class="required">&nbsp;*</span><br>
											<input type="hidden" name="cus_image" id="cus_image" value="<?php if (isset($pic)) {
																											echo $pic;
																										} ?>">
											<img id='imgshow' class="img_show" src='img/avatar.png' />
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Personal info END -->

					<!-- Family info START -->
					<div class="card">
						<div class="card-header">Family Info <span style="font-weight:bold" class=""></span>
						</div>
						<div class="card-body">

							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="famList">
										<table class="table custom-table">
											<thead>
												<tr>
													<th>S.No</th>
													<th>Name</th>
													<th>Relationship</th>
													<th>Age</th>
													<th>Aadhar No</th>
													<th>Mobile No</th>
													<th>Occupation</th>
													<th>Income</th>
													<th>Blood Group</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>

						</div>
					</div>
					<!-- Family info END -->

					<!-- Guarentor info START -->
					<div class="card">
						<div class="card-header">Guarentor Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="GuarentorName"> Guarentor Name </label><span class="required">&nbsp;*</span>
												<select type="text" class="form-control" id="guarentor_name" name="guarentor_name" tabindex="18">
													<option> Select Guarantor </option>
												</select>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="GuarentorRelationship"> Guarentor Relationship </label>
												<input type="text" class="form-control" id="guarentor_relationship" name="guarentor_relationship" value='<?php if (isset($guarentor_relation)) {
																																								echo $guarentor_relation;
																																							} ?>' readonly tabindex="19">
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
									<div class="col-xl-8 col-lg-10 col-md-6 ">
										<div class="form-group" style="margin-left: 30px;">
											<label for="pic" style="margin-left: -20px;"> Guarentor Photo </label><span class="required">&nbsp;*</span><br>
											<input type="hidden" name="guarentor_image" id="guarentor_image" value="<?php if (isset($guarentor_photo)) {
																														echo $guarentor_photo;
																													} ?>">
											<img id='imgshows' class="img_show" src='img/avatar.png' />
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Guarentor END -->

					<!-- Group Info START -->
					<!-- <div class="card">
						<div class="card-header"> Group Info <span style="font-weight:bold" class=""></span>
						</div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="GroupList">
										<table class="table custom-table modalTable">
											<thead>
												<tr>
													<th>S.No</th>
													<th>Name</th>
													<th>Age</th>
													<th>Aadhar No</th>
													<th>Mobile No</th>
													<th>Gender</th>
													<th>Designation</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>

						</div>
					</div> -->
					<!-- Group Info END -->


					<!-- Customer Data START -->
					<div class="card">
						<div class="card-header"> Customer Data <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="name"> Customer Type </label>
										<input type="text" class="form-control" name="cus_type" id="cus_type" value="<?php if (isset($cus_data)) {
																															echo $cus_data;
																														} ?>" readonly tabindex="20">
									</div>
								</div>

								<div id="exist_type" <?php if (isset($cus_data)) {
															if ($cus_data != 'Existing') { ?> style="display: none" <?php }
																											} ?> class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="ExistType"> Exist Type </label>
										<input type="text" class="form-control" name="cus_exist_type" id="cus_exist_type" value="<?php if (isset($cus_exist_type)) {
																																		echo $cus_exist_type;
																																	} ?>" readonly tabindex="21">
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Customer Data END -->

					<!-- Residential  Info START -->
					<div class="card">
						<div class="card-header"> Residential Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="resType"> Residential Type </label>
										<select type="text" class="form-control" name="cus_res_type" id="cus_res_type" tabindex="22">
											<option value=''> Select Residential Type </option>
											<option value="0" <?php if (isset($residential_type) and $residential_type == '0') echo 'selected'; ?>> Own </option>
											<option value="1" <?php if (isset($residential_type) and $residential_type == '1') echo 'selected'; ?>> Rental </option>
											<option value="2" <?php if (isset($residential_type) and $residential_type == '2') echo 'selected'; ?>> Lease </option>
											<option value="3" <?php if (isset($residential_type) and $residential_type == '3') echo 'selected'; ?>> Quarters </option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="ResidentDetails"> Resident Details </label>
										<input type="text" class="form-control" name="cus_res_details" id="cus_res_details" placeholder="Enter Resident Details" value="<?php if (isset($residential_details)) {
																																											echo $residential_details;
																																										} ?>" tabindex="23">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="resAddress"> Address </label>
										<input type="text" class="form-control" name="cus_res_address" id="cus_res_address" placeholder="Enter Address" value="<?php if (isset($residential_address)) {
																																									echo $residential_address;
																																								} ?>" tabindex="24">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="resnativeAddress"> Native Address </label>
										<input type="text" class="form-control" name="cus_res_native" id="cus_res_native" placeholder="Enter Native Address" value="<?php if (isset($residential_native_address)) {
																																										echo $residential_native_address;
																																									} ?>" tabindex="25">
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Residential  Info END -->

					<!-- Occupation info START -->
					<div class="card">
						<div class="card-header"> Occupation info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="occType"> Occupation Type </label>
										<select type="text" class="form-control" name="cus_occ_type" id="cus_occ_type" tabindex="26">
											<option value="">Select Occupation Type</option>
											<option value="1" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '1') echo 'selected'; ?>>Govt Job</option>
											<option value="2" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '2') echo 'selected'; ?>>Pvt Job</option>
											<option value="3" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '3') echo 'selected'; ?>>Business</option>
											<option value="4" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '4') echo 'selected'; ?>>Self Employed</option>
											<option value="5" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '5') echo 'selected'; ?>>Daily wages</option>
											<option value="6" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '6') echo 'selected'; ?>>Agriculture</option>
											<option value="7" <?php if (isset($cp_occupation_type) and $cp_occupation_type == '7') echo 'selected'; ?>>Others</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="occDetails"> Occupation Detail </label>
										<input type="text" class="form-control" name="cus_occ_detail" id="cus_occ_detail" placeholder="Enter Occupation Detail" onkeydown="return /[a-z ]/i.test(event.key)" value="<?php if (isset($occupation_details)) {
																																																						echo $occupation_details;
																																																					} ?>" tabindex="27">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="occIncome"> Income </label>
										<input type="number" class="form-control" name="cus_occ_income" id="cus_occ_income" placeholder="Enter Income" value="<?php if (isset($occupation_income)) {
																																									echo $occupation_income;
																																								} ?>" tabindex="28">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="occAddress"> Address </label>
										<input type="text" class="form-control" name="cus_occ_address" id="cus_occ_address" placeholder="Enter Address" value="<?php if (isset($occupation_address)) {
																																									echo $occupation_address;
																																								} ?>" tabindex="29">
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_dow"> Duration of Working </label>
										<input type="text" class="form-control" name="cus_occ_dow" id="cus_occ_dow" placeholder="Enter Duration of Working" value="<?php if (isset($dow)) {
																																										echo $dow;
																																									} ?>" tabindex="30">
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_abt"> About Occupation </label>
										<input type="text" class="form-control" name="cus_occ_abt" id="cus_occ_abt" placeholder="Enter About Occupation" value="<?php if (isset($abt_occ)) {
																																									echo $abt_occ;
																																								} ?>" tabindex="31">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Occupation info END -->

					<!-- Area Confirm START -->
					<div class="card">
						<div class="card-header"> Area Confirm <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="areaCnfirm"> Area confirm </label><span class="required">&nbsp;*</span>
										<select type="text" class="form-control" name="area_cnfrm" id="area_cnfrm" tabindex="32">
											<option value="">Select Area Type</option>
											<option value="0" <?php if (isset($area_confirm_type) and $area_confirm_type == '0') echo 'selected'; ?>> Residential Area </option>
											<option value="1" <?php if (isset($area_confirm_type) and $area_confirm_type == '1') echo 'selected'; ?>> Occupation Area </option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
									<div class="form-group">
										<label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
										<select type="text" class="form-control" id="state" name="state" tabindex="33">
											<option value="SelectState">Select State</option>
											<option value="TamilNadu" <?php if (isset($area_confirm_state) and $area_confirm_state == 'TamilNadu') echo 'selected'; ?>>Tamil Nadu</option>
											<option value="Puducherry" <?php if (isset($area_confirm_state) and $area_confirm_state == 'Puducherry') echo 'selected'; ?>>Puducherry</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">District</label>&nbsp;<span class="text-danger">*</span>
										<input type="hidden" class="form-control" id="district1" name="district1">
										<select type="text" class="form-control" id="district" name="district" tabindex='34'>
											<option value="Select District">Select District</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
										<input type="hidden" class="form-control" id="taluk1" name="taluk1">
										<select type="text" class="form-control" id="taluk" name="taluk" tabindex="35">
											<option value="Select Taluk">Select Taluk</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">Area</label>&nbsp;<span class="text-danger">*</span>
										<select tabindex="36" type="text" class="form-control" id="area" name="area">
											<option value="">Select Area</option>

										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">Sub Area</label>&nbsp;<span class="text-danger">*</span>
										<select tabindex="37" type="text" class="form-control" id="sub_area" name="sub_area">
											<option value=''>Select Sub Area</option>
										</select>
									</div>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
									<div class="form-group">
										<label for="latlong">Location</label>
										<input type="text" class="form-control" name="latlong" id="latlong" placeholder="Enter Latitude Longitude" value="<?php echo $latlong; ?>">
									</div>
								</div>
								<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
									<div class="form-group">
										<label style="visibility:hidden">Location</label>
										<button class="btn btn-primary" id="getlatlong" name="getlatlong" style="padding: 5px 35px;" disabled><span class="icon-my_location"></span></button>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">Group</label>
										<input type="text" class="form-control" name="area_group" id="area_group" value="<?php if (isset($area_group)) {
																																echo $area_group;
																															} ?>" readonly tabindex="38">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">Line</label>
										<input type="text" class="form-control" name="area_line" id="area_line" value="<?php if (isset($area_line)) {
																															echo $area_line;
																														} ?>" readonly tabindex="39">
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Area Confirm END -->

					<!-- Property info START -->
					<div class="card">
						<div class="card-header"> Property info <span style="font-weight:bold" class=""></span>
						</div>
						<div class="card-body">

							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="propertyList">
										<table class="table custom-table modalTable">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Property Type </th>
													<th> Property Measurement </th>
													<th> Property Value </th>
													<th> Property Holder </th>
													<th> ACTION </th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Property info END -->

					<!-- Bank info START -->
					<div class="card">
						<div class="card-header"> Bank info <span style="font-weight:bold" class=""></span>
						</div>
						<div class="card-body">

							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="bankResetTable">
										<table class="table custom-table modalTable">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Bank Name </th>
													<th> Branch Name </th>
													<th> Account Holder Name </th>
													<th> Account Number </th>
													<th> IFSC Code </th>
													<th> ACTION </th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>

						</div>
					</div>
					<!-- Bank info END -->

					<!-- KYC info START -->
					<div class="card">
						<div class="card-header"> KYC info <span style="font-weight:bold" class=""></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="kycListTable">
										<table class="table custom-table modalTable">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Proof of </th>
													<th> Proof type </th>
													<th> Proof Number </th>
													<th> Upload </th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- KYC info END -->

					<!-- ///////////////////////////////////////////////// Customer Summary START ///////////////////////////////////////////////////////////// -->
					<div class="card">
						<div class="card-header"> Customer Summary <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="summarykmnw"> How to Know </label> <span class="required">*</span>
										<select type="text" class="form-control" name="cus_how_know" id="cus_how_know" tabindex="40">
											<option value=""> Select How to Know </option>
											<option value="0" <?php if (isset($how_to_know) and $how_to_know == '0') echo 'selected'; ?>> Customer Reference </option>
											<option value="1" <?php if (isset($how_to_know) and $how_to_know == '1') echo 'selected'; ?>> Advertisement </option>
											<option value="2" <?php if (isset($how_to_know) and $how_to_know == '2') echo 'selected'; ?>> Promotion activity </option>
											<option value="3" <?php if (isset($how_to_know) and $how_to_know == '3') echo 'selected'; ?>> Agent Reference </option>
											<option value="4" <?php if (isset($how_to_know) and $how_to_know == '4') echo 'selected'; ?>> Staff Reference </option>
											<option value="5" <?php if (isset($how_to_know) and $how_to_know == '5') echo 'selected'; ?>> Other Reference </option>
											<option value="6" <?php if (isset($how_to_know) and $how_to_know == '6') echo 'selected'; ?>> Renewal </option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="loancnt"> Loan Counts </label>
										<input type="text" class="form-control" name="cus_loan_count" id="cus_loan_count" value="<?php if (isset($loan_count)) {
																																		echo $loan_count;
																																	} ?>" readonly tabindex="41">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="loandate"> First Loan Date </label>
										<input type="text" class="form-control" name="cus_frst_loanDate" id="cus_frst_loanDate" value="<?php if (isset($first_loan_date)) {
																																			echo $first_loan_date;
																																		} ?>" readonly tabindex="42">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="travel"> Travel with Company </label>
										<input type="text" class="form-control" name="cus_travel_cmpy" id="cus_travel_cmpy" value="<?php if (isset($travel_with_company)) {
																																		echo $travel_with_company;
																																	} ?>" readonly tabindex="43">
									</div>
								</div>

							</div>

							<hr>

							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="minvcome"> Monthly Income </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_monthly_income" id="cus_monthly_income" placeholder="Enter Monthly Income" value="<?php if (isset($monthly_income)) {
																																													echo $monthly_income;
																																												} ?>" tabindex="44">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="otherincome"> Other Income </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_other_income" id="cus_other_income" placeholder="Enter Other Income" value="<?php if (isset($other_income)) {
																																											echo $other_income;
																																										} ?>" tabindex="45">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="suppincome"> Support Income </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_support_income" id="cus_support_income" placeholder="Enter Support Income" value="<?php if (isset($support_income)) {
																																													echo $support_income;
																																												} ?>" tabindex="46">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="commit"> Commitment </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_Commitment" id="cus_Commitment" placeholder="Enter Commitment" value="<?php if (isset($commitment)) {
																																										echo $commitment;
																																									} ?>" tabindex="47">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="duecapacity"> Monthly Due Capacity </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_monDue_capacity" id="cus_monDue_capacity" placeholder="Enter Monthly Due Capacity" value="<?php if (isset($monthly_due_capacity)) {
																																															echo $monthly_due_capacity;
																																														} ?>" tabindex="48">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="loanlimit"> Customer Limit </label>
										<input type="number" class="form-control" name="cus_loan_limit" id="cus_loan_limit" placeholder="Enter Customer Limit" value="<?php if (isset($loan_limit)) {
																																											echo $loan_limit;
																																										} ?>" tabindex="49">
									</div>
								</div>

							</div>

							<hr>
							<br>

							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group" id="feedbackListTable">
										<table class="table custom-table modalTable">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Feedback Label </th>
													<th> Feedback </th>
													<th> Remarks </th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>

							<hr>

							<div class="row">
								<div class="col-xl-4 col-lg-6 col-md-8 col-sm-12 col-12">
									<div class="form-group">
										<label for="abtCustomer"> About Customer </label> <span class="required">*</span>
										<textarea class="form-control" name="about_cus" id="about_cus" readonly tabindex="50"> <?php if (isset($about_customer)) {
																																	echo $about_customer;
																																} ?> </textarea>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- ///////////////////////////////////////////////  Customer Summary  END /////////////////////////////////////////////////////////// -->



				</div>
			</div>
		</form>
	</div>
	<!-- Customer Form End -->


	<!--  ///////////////////////////////////////////////////////////////// Documentation  start ////////////////////////////////////////////////////////// -->
	<div id="cus_document" style="display: none;">
		<form id="cus_doc" name="cus_doc" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {
																		echo $req_id;
																	} ?>">
			<input type="hidden" name="cus_profile_id" id="cus_profile_id" value="<?php if (isset($cus_profile_id)) {
																						echo $cus_profile_id;
																					} ?>">
			<input type="hidden" name="doc_table_id" id="doc_table_id" value="<?php if (isset($document_table_id)) {
																					echo $document_table_id;
																				} ?>">
			<input type="hidden" name="en_relation_name" id="en_relation_name" value="<?php if (isset($ownername_relationship_name)) {
																							echo $ownername_relationship_name;
																						} ?>">
			<input type="hidden" name="mortgage_relation_name" id="mortgage_relation_name" value="<?php if (isset($Propertyholder_relationship_name)) {
																										echo $Propertyholder_relationship_name;
																									} ?>">
			<input type="hidden" name="docrelation_name" id="docrelation_name" value="<?php if (isset($docholder_relationship_name)) {
																							echo $docholder_relationship_name;
																						} ?>">
			<input type="hidden" name="submitted" id="submitted" value="<?php if (isset($submitted)) {
																			echo $submitted;
																		} ?>">

			<!-- Row start -->
			<div class="row gutters">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">


					<div class="card">
						<div class="card-header">Documentation Info</div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="CustomerId">Customer ID </label> <span class="required">* </span>
										<input type="text" class="form-control" id="cus_id_doc" name="cus_id_doc" value='<?php if (isset($doc_cus_id)) echo $doc_cus_id; ?>' readonly tabindex="1">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="CustomerName"> Customer Name </label> <span class="required"> * </span>
										<input type="text" class="form-control" id="Customer_name" name="Customer_name" value='<?php if (isset($doc_cus_name)) echo $doc_cus_name; ?>' readonly tabindex="2">
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 responsible" <?php if (isset($role)) {
																										if ($role == '3') { ?> style="display: none" <?php }
																																				} ?>>
									<div class="form-group">
										<label for="responsible">Responsible&nbsp;<span class="required">&nbsp;*</span></label>
										<input tabindex="3" type="text" class="form-control" id="responsible" name="responsible" value="<?php if (isset($responsible) and $responsible == '0') {
																																			echo 'Yes';
																																		} else {
																																			echo 'No';
																																		} ?>" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 remarks" <?php if (isset($role)) {
																									if ($role != '3') { ?>style="display: none" <?php }
																																		} ?>>
									<div class="form-group">
										<label for="remark">Remarks</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="remarks" name="remarks" value='<?php if (isset($remarks)) echo $remarks; ?>' tabindex='4' placeholder="Enter Remarks" pattern="[a-zA-Z\s]+" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 declaration" <?php if (isset($role)) {
																										if ($role == '3') { ?>style="display: none" <?php }
																																			} ?>>
									<div class="form-group">
										<label for="declaration">Declaration</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="declaration" name="declaration" value='<?php if (isset($declaration)) echo $declaration; ?>' tabindex='5' placeholder="Enter Declaration" pattern="[a-zA-Z\s]+" readonly>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="DocArea"> Area </label> <span class="required"> * </span>
										<input tabindex="6" type="text" class="form-control" id="doc_area" name="doc_area" value="<?php if (isset($doc_area_name)) echo $doc_area_name; ?>" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="DocSubArea"> Sub Area </label> <span class="required"> * </span>
										<input type="text" class="form-control" id="doc_Sub_Area" name="doc_Sub_Area" value='<?php if (isset($doc_sub_area_name)) echo $doc_sub_area_name; ?>' readonly tabindex="7">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="DocID">Document ID</label> <span class="required"> * </span>
										<input type="text" class="form-control" id="doc_id" name="doc_id" readonly tabindex="8">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Documentations Info  End-->

					<!-- Signed Doc Info START -->
					<div class="card">
						<div class="card-header"> Signed Doc Info
							<button type="button" class="btn btn-primary" id="add_sign_doc" name="add_sign_doc" data-toggle="modal" data-target=".addSignDoc" style="padding: 5px 35px;  float: right;" tabindex="9"><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='signed_infoCheck'>Please Fill Signed Doc Info </span>
						<div class="card-body">

							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="signDocResetTable">
										<table class="table custom-table">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Doc Name </th>
													<th> Sign Type </th>
													<th> Count </th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>

						</div>
					</div>
					<!-- Signed Doc Info END -->

					<!-- Cheque Info START -->
					<div class="card">
						<div class="card-header"> Cheque Info
							<button type="button" class="btn btn-primary" id="add_Cheque" name="add_Cheque" data-toggle="modal" data-target=".addCheque" style="padding: 5px 35px;  float: right;" tabindex="10"><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='Cheque_infoCheck'>Please Fill Cheque Info </span>
						<div class="card-body">

							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="ChequeResetTable">
										<table class="table custom-table">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Holder type </th>
													<th> Holder Name </th>
													<th> Relationship </th>
													<th> Bank Name </th>
													<th> Cheque No </th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>

						</div>
					</div>
					<!-- Cheque Info END -->

					<!-- Mortgage Info START-->

					<div class="card">
						<div class="card-header"> Mortgage Info </div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="MortgageProcess"> Mortgage Process</label> <span class="required">&nbsp;*</span>
										<input type="hidden" class="form-control" id="mortgage_process_post" name="mortgage_process_post" value="<?php echo $mortgage_process; ?>">
										<select type="text" class="form-control" id="mortgage_process" name="mortgage_process" tabindex="11">
											<option value=""> Select Mortgage Process </option>
											<option value="0" <?php if (isset($mortgage_process) and $mortgage_process == '0') echo 'selected'; ?>> YES </option>
											<option value="1" <?php if (isset($mortgage_process) and $mortgage_process == '1') echo 'selected'; ?>> NO </option>
										</select>
										<span class="text-danger" id="mortgageprocessCheck"> Select Mortgage Process </span>
									</div>
								</div>
							</div>

							<div id="Mortgageprocess" <?php if (isset($mortgage_process)) {
															if ($mortgage_process != '0') { ?> style="display: none;" <?php }
																												} else { ?> style="display: none;" <?php } ?>>
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="PropertyHoldertype "> Property Holder type </label> <span class="required">&nbsp;*</span>
											<select type="text" class="form-control" id="Propertyholder_type" name="Propertyholder_type" tabindex="12">
												<option value=""> Select Holder type </option>
												<option value="0" <?php if (isset($Propertyholder_type) and $Propertyholder_type == '0') echo 'selected'; ?>> Customer </option>
												<option value="1" <?php if (isset($Propertyholder_type) and $Propertyholder_type == '1') echo 'selected'; ?>> Guarantor </option>
												<option value="2" <?php if (isset($Propertyholder_type) and $Propertyholder_type == '2') echo 'selected'; ?>> Family Members </option>
											</select>
											<span class="text-danger" id="propertyholdertypeCheck"> Select Property Holder type </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="PropertyHolderName "> Property Holder Name </label>
											<input type="text" class="form-control" id="Propertyholder_name" name="Propertyholder_name" value="<?php if (isset($Propertyholder_name)) echo $Propertyholder_name; ?>" readonly tabindex="13">

											<select type="text" class="form-control" id="Propertyholder_relationship_name" name="Propertyholder_relationship_name" style="display: none;" tabindex='13'>
												<option value=""> Select Relationship </option>
											</select>
										</div>
									</div>


									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="chequeRelationship"> Relationship </label>
											<input type="text" class="form-control" id="doc_property_relation" name="doc_property_relation" value="<?php if (isset($doc_property_relation)) echo $doc_property_relation; ?>" readonly tabindex="14">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="DocPropertyType"> Property Type </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_pype" name="doc_property_pype" placeholder="Enter Property Type" value="<?php if (isset($doc_property_type)) echo $doc_property_type; ?>" tabindex="15">
											<span class="text-danger" id="docpropertytypeCheck"> Enter Property Type </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="DocPropertyMeasurement"> Property Measurement </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_measurement" name="doc_property_measurement" placeholder="Enter Property Measurement" value="<?php if (isset($doc_property_measurement)) echo $doc_property_measurement; ?>" tabindex="16">
											<span class="text-danger" id="docpropertymeasureCheck"> Enter Property Measurement </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="DocPropertyLocation"> Property Location </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_location" name="doc_property_location" placeholder="Enter Property Location" value="<?php if (isset($doc_property_location)) echo $doc_property_location; ?>" tabindex="17">
											<span class="text-danger" id="docpropertylocCheck"> Enter Property Location </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="PropertyValue"> Property Value </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_value" name="doc_property_value" placeholder="Enter Property Value" value="<?php if (isset($doc_property_value)) echo $doc_property_value; ?>" tabindex="18">
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="MortgageName"> Mortgage Name </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_name" name="mortgage_name" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Mortgage Name" value="<?php if (isset($mortgage_name)) echo $mortgage_name; ?>" tabindex="19">
											<span class="text-danger" id="mortgagenameCheck"> Enter Mortgage Name </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="mortgageDesignation"> Designation </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_dsgn" name="mortgage_dsgn" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Designation" value="<?php if (isset($mortgage_dsgn)) echo $mortgage_dsgn; ?>" tabindex="20">
											<span class="text-danger" id="mortgagedsgnCheck"> Enter Designation </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="MortgageNumber"> Mortgage Number </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_nuumber" name="mortgage_nuumber" placeholder="Enter Mortgage Number" value="<?php if (isset($mortgage_nuumber)) echo $mortgage_nuumber; ?>" tabindex="21">
											<span class="text-danger" id="mortgagenumCheck"> Enter Mortgage Number </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="RegOffice"> Reg Office </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="reg_office" name="reg_office" placeholder="Enter Reg Office" value="<?php if (isset($reg_office)) echo $reg_office; ?>" tabindex="22">
											<span class="text-danger" id="regofficeCheck"> Enter Reg Office </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="MortgageValue"> Mortgage Value </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_value" name="mortgage_value" placeholder="Enter Mortgage Value" value="<?php if (isset($mortgage_value)) echo $mortgage_value; ?>" tabindex="23">
											<span class="text-danger" id="mortgagevalueCheck"> Enter Mortgage Value </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="MortgageDocument"> Mortgage Document </label> <span class="required">&nbsp;*</span>
											<select type="text" class="form-control" id="mortgage_document" name="mortgage_document" tabindex="24">
												<option value=""> Select Mortgage Document </option>
												<option value="0" <?php if (isset($mortgage_document) and $mortgage_document == '0') echo 'selected'; ?>> YES </option>
												<option value="1" <?php if (isset($mortgage_document) and $mortgage_document == '1') echo 'selected'; ?>> NO </option>
											</select>
											<span class="text-danger" id="mortgagedocCheck"> Select Mortgage Document </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="docUpd" style="display: none;">
										<div class="form-group">
											<label for="MortgageDocumentUpd"> Mortgage Document Uploads </label> <span class="required">&nbsp;*</span>
											<input type="file" onchange="compressImage(this,400)" class="form-control" id="mortgage_document_upd" name="mortgage_document_upd" tabindex="25">
											<input type="hidden" id="mortgage_doc_upd" name="mortgage_doc_upd" value="<?php if (isset($mortgage_document_upd)) echo $mortgage_document_upd; ?>">
											<!-- <a href="<?php echo "uploads/verification/mortgage_doc/" . $mortgage_document_upd; ?>" target="_blank" >  <?php if (isset($mortgage_document_upd)) echo $mortgage_document_upd; ?> </a> -->
											<span class="text-danger" id="mortgagedocUpdCheck"> Upload Mortgage Document </span>
										</div>
									</div>


									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="pendingDocument"> Pending </label> <span class="required">&nbsp;*</span>
											<label class="switch">
												<input type="checkbox" value="YES" id="pendingchk" name="pendingchk" checked tabindex="26">
												<span class="slider round"></span>
											</label>
										</div>
									</div>


								</div>

							</div>
						</div>
					</div>
					<!-- Mortgage Info  End-->

					<!-- Endorsement Info START-->

					<div class="card">
						<div class="card-header"> Endorsement Info </div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="EndorsementProcess"> Endorsement Process</label> <span class="required">&nbsp;*</span>
										<input type="hidden" class="form-control" id="endorsement_process_post" name="endorsement_process_post" value="<?php echo $endorsement_process; ?>">
										<select type="text" class="form-control" id="endorsement_process" name="endorsement_process" tabindex="27">
											<option value=""> Select Endorsement Process </option>
											<option value="0" <?php if (isset($endorsement_process) and $endorsement_process == '0') echo 'selected'; ?>> YES </option>
											<option value="1" <?php if (isset($endorsement_process) and $endorsement_process == '1') echo 'selected'; ?>> NO </option>
										</select>
										<span class="text-danger" id="endorsementprocessCheck"> Select Endorsement Process </span>
									</div>
								</div>
							</div>

							<div class="row" id="endorsementprocess" <?php if (isset($endorsement_process)) {
																			if ($endorsement_process != '0') { ?> style="display: none;" <?php }
																																	} else { ?> style="display: none;" <?php } ?>>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="OwnerType "> Owner Type </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="owner_type" name="owner_type" tabindex="28">
											<option value=""> Select Holder type </option>
											<option value="0" <?php if (isset($owner_type) and $owner_type == '0') echo 'selected'; ?>> Customer </option>
											<option value="1" <?php if (isset($owner_type) and $owner_type == '1') echo 'selected'; ?>> Guarantor </option>
											<option value="2" <?php if (isset($owner_type) and $owner_type == '2') echo 'selected'; ?>> Family Members </option>
										</select>
										<span class="text-danger" id="ownertypeCheck"> Select Owner type </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="OwnerName "> Owner Name </label>
										<input type="text" class="form-control" id="owner_name" name="owner_name" value="<?php if (isset($owner_name)) echo $owner_name; ?>" readonly>

										<select type="text" class="form-control" id="ownername_relationship_name" name="ownername_relationship_name" style="display: none;" tabindex="29">
											<option value=""> Select Relationship </option>
										</select>
									</div>
								</div>


								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="chequeRelationship"> Relationship </label>
										<input type="text" class="form-control" id="en_relation" name="en_relation" value="<?php if (isset($en_relation)) echo $en_relation; ?>" readonly tabindex="30">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Vehicletype"> Vehicle type </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="vehicle_type" name="vehicle_type" tabindex="31">
											<option value=""> Select Vehicle type </option>
											<option value="0" <?php if (isset($vehicle_type) and $vehicle_type == '0') echo 'selected'; ?>> 2 Wheeler </option>
											<option value="1" <?php if (isset($vehicle_type) and $vehicle_type == '1') echo 'selected'; ?>> 4 Wheeler </option>
										</select>
										<span class="text-danger" id="vehicletypeCheck"> Enter Vehicle Type </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="VehicleProcess"> Vehicle Process </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="vehicle_process" name="vehicle_process" tabindex="32">
											<option value=""> Select Vehicle Process </option>
											<option value="0" <?php if (isset($vehicle_process) and $vehicle_process == '0') echo 'selected'; ?>> New </option>
											<option value="1" <?php if (isset($vehicle_process) and $vehicle_process == '1') echo 'selected'; ?>> Old </option>
										</select>
										<span class="text-danger" id="vehicleprocessCheck"> Enter Vehicle Process </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="endro_Company"> Company </label> <span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="en_Company" name="en_Company" placeholder="Enter Company" value="<?php if (isset($en_Company)) echo $en_Company; ?>" tabindex="33">
										<span class="text-danger" id="enCompanyCheck"> Enter Company </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="enModel"> Model </label> <span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="en_Model" name="en_Model" placeholder="Enter Model" value="<?php if (isset($en_Model)) echo $en_Model; ?>" tabindex="34">
										<span class="text-danger" id="enModelCheck"> Enter Model </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="VehicleRegNo"> Vehicle Reg No. </label>
										<input type="text" class="form-control" id="vehicle_reg_no" name="vehicle_reg_no" placeholder="Enter Vehicle No" value="<?php if (isset($vehicle_reg_no)) echo $vehicle_reg_no; ?>" tabindex="35">
										<span class="text-danger" id="vehicle_reg_noCheck"> Enter Vehicle No </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Endorsementname"> Endorsement name </label> <span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="endorsement_name" name="endorsement_name" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Endorsement Name" value="<?php if (isset($endorsement_name)) echo $endorsement_name; ?>" tabindex="36">
										<span class="text-danger" id="endorsementnameCheck"> Enter Endorsement Name</span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="enKey"> Key </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="en_Key" name="en_Key" tabindex="37">
											<option value=""> Select Key </option>
											<option value="0" <?php if (isset($en_Key) and $en_Key == '0') echo 'selected'; ?>> YES </option>
											<option value="1" <?php if (isset($en_Key) and $en_Key == '1') echo 'selected'; ?>> NO </option>
										</select>
										<span class="text-danger" id="enKeyCheck"> Select Key </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="RC"> RC </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="en_RC" name="en_RC" tabindex="38">
											<option value=""> Select RC </option>
											<option value="0" <?php if (isset($en_RC) and $en_RC == '0') echo 'selected'; ?>> YES </option>
											<option value="1" <?php if (isset($en_RC) and $en_RC == '1') echo 'selected'; ?>> NO </option>
										</select>
										<span class="text-danger" id="enRCCheck"> Select RC </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="RCdocUpd" style="display: none;">
									<div class="form-group">
										<label for="RCDocumentUpd"> RC Uploads </label> <span class="required">&nbsp;*</span>
										<input type="file" onchange="compressImage(this,400)" class="form-control" id="RC_document_upd" name="Rc_document_upd" tabindex="39">
										<input type="hidden" id="rc_doc_upd" name="rc_doc_upd" value="<?php if (isset($Rc_document_upd)) echo $Rc_document_upd; ?>">
										<!-- <a href="<?php echo "uploads/verification/endorsement_doc/" . $Rc_document_upd; ?>" target="_blank" > <?php if (isset($Rc_document_upd)) echo $Rc_document_upd; ?>  </a> -->
										<span class="text-danger" id="rcdocUpdCheck"> Upload RC </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="pendingendorse"> Pending </label> <span class="required">&nbsp;*</span>
										<label class="switch">
											<input type="checkbox" value="YES" id="endorsependingchk" name="endorsependingchk" checked tabindex="40">
											<span class="slider round"></span>
										</label>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Endorsement Info  End-->
					<!-- Gold Info Start -->
					<div class="card">
						<div class="card-header"> Gold Info
							<button type="button" class="btn btn-primary" id="add_gold" name="add_gold" data-toggle="modal" data-target=".addGold" style="padding: 5px 35px;  float: right;" tabindex='41'><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='Gold_infoCheck'>Please Fill Gold Info </span>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="GoldResetTableDiv">
										<table class="table custom-table">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Gold type </th>
													<th> Purity </th>
													<th> Count </th>
													<th> Weight </th>
													<th> Value </th>
													<th> Upload </th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Gold Info End -->
					<!-- Documents Info START-->

					<div class="card">
						<div class="card-header"> Documents Info
							<button type="button" class="btn btn-primary" id="add_document" name="add_document" data-toggle="modal" data-target=".addDocument" style="padding: 5px 35px;  float: right;" tabindex="42"><span class="icon-add"></span></button>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="overflow-x: auto;">
									<div class="form-group table-responsive" id="DocResetTableDiv">
										<table class="table custom-table">
											<thead>
												<tr>
													<th width="50"> S.No </th>
													<th> Document Name </th>
													<th> Document Details</th>
													<th> Document Type </th>
													<th> Document Holder</th>
													<th> Holder Name</th>
													<th> Relationship</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Document Info End -->

					<!-- Fingerprint Info start-->
					<div class="card">
						<div class="card-header"> Fingerprint Info </div><span class="text-danger fingerSpan" style="margin-left:25px;display: none;">Please Scan Customer Fingerprint</span>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group fingerprintTable">

									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Fingerprint Info End-->

					<div class="col-md-12 ">
						<div class="text-right">
							<button type="submit" name="submit_documentation" id="submit_documentation" class="btn btn-primary" value="Submit" tabindex="43"><span class="icon-check"></span>&nbsp;Submit</button>
							<button type="reset" class="btn btn-outline-secondary" tabindex="44">Clear</button>
						</div>
					</div>

				</div>
			</div> <!-- Row End -->
		</form>
	</div>

	<!--  ///////////////////////////////////////////////////////////////// Documentation  End ////////////////////////////////////////////////////////// -->


	<!--  ///////////////////////////////////////////////////////////////// Loan Calculation starts ////////////////////////////////////////////////////////// -->

	<div id="customer_loan_calc" style="display: none;">
		<form id="cus_loancalc" name="cus_loancalc" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="loan_cal_id" id="loan_cal_id" value="<?php if (isset($loan_cal_id)) {
																				echo $loan_cal_id;
																			} ?>" />
			<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {
																		echo $req_id;
																	} ?>" />
			<input type="hidden" name="loan_category_load" id="loan_category_load" value="<?php if (isset($loan_category)) {
																								echo $loan_category;
																							} ?>" />
			<input type="hidden" name="sub_category_load" id="sub_category_load" value="<?php if (isset($sub_category)) {
																							echo $sub_category;
																						} ?>" />
			<input type="hidden" name="loan_category_upd" id="loan_category_upd" value="<?php if (isset($loan_category_lc)) {
																							echo $loan_category_lc;
																						} ?>" />
			<input type="hidden" name="sub_category_upd" id="sub_category_upd" value="<?php if (isset($sub_category_lc)) {
																							echo $sub_category_lc;
																						} ?>" />
			<input type="hidden" name="profit_type_upd" id="profit_type_upd" value="<?php if (isset($profit_type_lc)) {
																						echo $profit_type_lc;
																					} ?>" />
			<input type="hidden" name="due_method_scheme_upd" id="due_method_scheme_upd" value="<?php if (isset($due_method_scheme_lc)) {
																									echo $due_method_scheme_lc;
																								} ?>" />
			<input type="hidden" name="day_scheme_upd" id="day_scheme_upd" value="<?php if (isset($day_scheme_lc)) {
																						echo $day_scheme_lc;
																					} ?>" />
			<input type="hidden" name="scheme_upd" id="scheme_upd" value="<?php if (isset($scheme_name_lc)) {
																				echo $scheme_name_lc;
																			} ?>" />
			<input type="hidden" name="scheme_profit_method_upd" id="scheme_profit_method_upd" value="<?php if (isset($profit_method_scheme_lc)) {
																											echo $profit_method_scheme_lc;
																										} ?>" />
			<input type="hidden" name="profit_method_upd" id="profit_method_upd" value="<?php if (isset($profit_method_lc)) {
																							echo $profit_method_lc;
																						} ?>" />
			<input type="hidden" name="int_rate_upd" id="int_rate_upd" value="<?php if (isset($int_rate_lc)) {
																					echo $int_rate_lc;
																				} ?>" />
			<input type="hidden" name="due_period_upd" id="due_period_upd" value="<?php if (isset($due_period_lc)) {
																						echo $due_period_lc;
																					} ?>" />
			<input type="hidden" name="doc_charge_upd" id="doc_charge_upd" value="<?php if (isset($doc_charge_lc)) {
																						echo $doc_charge_lc;
																					} ?>" />
			<input type="hidden" name="proc_fee_upd" id="proc_fee_upd" value="<?php if (isset($proc_fee_lc)) {
																					echo $proc_fee_lc;
																				} ?>" />
			<input type="hidden" name="cus_profile_id" id="cus_profile_id" value="<?php if (isset($cus_Tableid)) {
																						echo $cus_Tableid;
																					} ?>" />
			<!-- Row start -->
			<div class="row gutters">
				<!-- Request Info -->
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="card">
						<div class="card-header">Request Info <span style="font-weight:bold" class=""></span></div>
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
										<input type="text" class="form-control" id="user" name="user" readonly value='<?php if (isset($user_name)) echo $user_name; ?>' tabindex="2">
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="user">Agent Name</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="agent_name" name="agent_name" readonly value='<?php if (isset($agent_name)) echo $agent_name; ?>' tabindex='2'>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 responsible" <?php if (isset($role)) {
																										if ($role == '3') { ?> style="display: none" <?php }
																																				} ?>>
									<div class="form-group">
										<label for="responsible">Responsible&nbsp;<span class="required">&nbsp;*</span></label>
										<input tabindex="3" type="text" class="form-control" id="responsible" name="responsible" value="<?php if (isset($responsible) and $responsible == '0') {
																																			echo 'Yes';
																																		} else {
																																			echo 'No';
																																		} ?>" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 remarks" <?php if (isset($role)) {
																									if ($role != '3') { ?>style="display: none" <?php }
																																		} ?>>
									<div class="form-group">
										<label for="remark">Remarks</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="remarks" name="remarks" value='<?php if (isset($remarks)) echo $remarks; ?>' tabindex='4' placeholder="Enter Remarks" pattern="[a-zA-Z\s]+" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 declaration" <?php if (isset($role)) {
																										if ($role == '3') { ?>style="display: none" <?php }
																																			} ?>>
									<div class="form-group">
										<label for="declaration">Declaration</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="declaration" name="declaration" value='<?php if (isset($declaration)) echo $declaration; ?>' tabindex='5' placeholder="Enter Declaration" pattern="[a-zA-Z\s]+" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="DocArea"> Area </label> <span class="required"> * </span>
										<input tabindex="6" type="text" class="form-control" id="doc_area" name="doc_area" value="<?php if (isset($doc_area_name)) echo $doc_area_name; ?>" readonly>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="DocSubArea"> Sub Area </label> <span class="required"> * </span>
										<input type="text" class="form-control" id="doc_Sub_Area" name="doc_Sub_Area" value='<?php if (isset($doc_sub_area_name)) echo $doc_sub_area_name; ?>' readonly tabindex="7">
									</div>
								</div>


								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="req_code">Request ID</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="req_code" name="req_code" readonly value='<?php if (isset($req_code)) echo $req_code; ?>' tabindex="8">
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="dor">Date Of request</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="dor" name="dor" readonly value='<?php if (isset($dor)) {
																														echo $dor;
																													} else {
																														echo date('Y-m-d');
																													} ?>' tabindex="9">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Possibility info -->
					<div class="card ">
						<div class="card-header">Possibilty Info</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="">Possibility<span class="required">&nbsp;*</span></label>
										<input type="text" class="form-control" id="" name="" readonly value='<?php if (isset($poss_type)) {
																													echo $poss_type == '1' ? 'Due Amount' : 'Due Period';
																												} ?>' tabindex='10'>
									</div>
								</div>
								<?php if (isset($poss_type) and $poss_type == '1') { ?>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="">Due Amount</label><span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="" name="" value='<?php if (isset($due_amt)) {
																												echo $due_amt;
																											} ?>' tabindex='11' readonly>
										</div>
									</div>
								<?php } else { ?>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="">Due Period</label><span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="" name="" value='<?php if (isset($due_period)) {
																												echo $due_period;
																											} ?>' tabindex='11'>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<!-- Possibility info -->
					<!-- Personal info START -->
					<!-- <div class="card">
						<div class="card-header">Personal Info <span style="font-weight:bold" class="" ></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-8">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="">Customer ID</label><span class="required">&nbsp;*</span> -->
					<input type="hidden" class="form-control" id="cus_id_loan" name="cus_id_loan" readonly value='<?php if (isset($cus_id_loan)) {
																														echo $cus_id_loan;
																													} elseif (isset($cus_id_lc)) {
																														echo $cus_id_lc;
																													} ?>' tabindex="10">
					<!--</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="">Customer Name</label><span class="required">&nbsp;*</span> -->
					<input type="hidden" class="form-control" id="cus_name_loan" name="cus_name_loan" readonly value='<?php if (isset($cus_name_loan)) {
																															echo $cus_name_loan;
																														} elseif (isset($cus_name_lc)) {
																															echo $cus_name_lc;
																														} ?>' tabindex="11">
					<!--</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="">Customer Data</label><span class="required">&nbsp;*</span> -->
					<input type="hidden" class="form-control" id="cus_data_loan" name="cus_data_loan" readonly value='<?php if (isset($cus_data_loan)) {
																															echo $cus_data_loan;
																														} elseif (isset($cus_data_lc)) {
																															echo $cus_data_lc;
																														} ?>' tabindex="12">
					<!--</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="">Mobile No</label><span class="required">&nbsp;*</span> -->
					<input type="hidden" class="form-control" id="mobile_loan" name="mobile_loan" readonly value='<?php if (isset($mobile_loan)) {
																														echo $mobile_loan;
																													} elseif (isset($mobile_lc)) {
																														echo $mobile_lc;
																													} ?>' tabindex="13">
					<!--</div>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
										<div class="form-group" style="margin-left: 30px;margin-top:-20px;">
											<label for="pic" style="margin-left: -20px;">Photo</label><span class="required">&nbsp;*</span><br> -->
					<input type="hidden" name="pic_loan" id="pic_loan" value="<?php if (isset($pic_loan)) {
																					echo $pic_loan;
																				} elseif (isset($cus_pic_lc)) {
																					echo $cus_pic_lc;
																				} ?>">
					<!-- <img id='imgshow' class="img_show" src='img/avatar.png' /> -->
					<!-- <img id='imgshow' class="img_show" src='<?php if (isset($pic_loan)) {
																		echo 'uploads/request/customer/' . $pic_loan;
																	} elseif (isset($cus_pic_lc)) {
																		echo 'uploads/request/customer/' . $cus_pic_lc;
																	} else {
																		echo 'img/avatar.png';
																	} ?>' />
										</div>
									</div>
								</div>

							</div>
						</div>
					</div> -->
					<!-- Personal info END -->

					<!-- ///////////////////////////////////////////////// Verification Info START ///////////////////////////////////////////////////////////// -->
					<div class="card">
						<div class="card-header"> Verfication Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Communitcation"> Communitcation </label> <span class="required">*</span>
										<input type="hidden" class="form-control" id="Communitcation_to_cus_ack" name="Communitcation_to_cus_ack" value="<?php if (isset($communication)) {
																																								echo $communication;
																																							} ?>">
										<select type="text" class="form-control" name="Communitcation_to_cus" id="Communitcation_to_cus" tabindex='12'>
											<option value=""> Select Communication </option>
											<option value="0" <?php if (isset($communication) and $communication == '0') echo 'selected'; ?>> Phone </option>
											<option value="1" <?php if (isset($communication) and $communication == '1') echo 'selected'; ?>> Direct </option>
										</select>
										<span class="text-danger" style='display:none' id='communicationCheck'>Please Select communication </span>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" <?php if (isset($communication)) {
																							if ($communication == '1') { ?> style="display: none;" <?php }
																																			} else { ?> style="display: none;" <?php } ?> id="verifyaudio">
									<div class="form-group">
										<label for="Communitcation"> Audio </label>
										<input type="hidden" id="verification_audio_upd" name="verification_audio_upd" value="<?php if (isset($com_audio)) {
																																	echo $com_audio;
																																} ?>">
										<input type="file" onchange="compressImage(this,800)" class="form-control" name="verification_audio" id="verification_audio" accept=".mp3,audio/*" tabindex="13">
										<?php if (isset($communication)) {
											if ($communication == '0') { ?>
												<a href="<?php echo "uploads/verification/verifyInfo_audio/" . $com_audio; ?>" target="_blank" download>Click Here To Download Your <?php if (isset($com_audio)) echo $com_audio; ?> Audio </a>
										<?php }
										} ?>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Verificationperson"> Verification person </label> <span class="required">*</span>
										<input type="hidden" id="verifyPerson" name="verifyPerson">
										<select type="text" class="form-control" name="verification_person" id="verification_person" multiple tabindex='14'>
											<option value=""> Select Verification Person </option>
										</select>
										<span class="text-danger" style='display:none' id='verificationPersonCheck'>Please Select Verification Person </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Verificationlocation"> Verification location </label> <span class="required">*</span>
										<input type="hidden" class="form-control" id="verification_location_ack" name="verification_location_ack" value="<?php if (isset($verification_location)) {
																																								echo $verification_location;
																																							} ?>">
										<select type="text" class="form-control" name="verification_location" id="verification_location" tabindex='15'>
											<option value=""> Select Verification location </option>
											<option value="0" <?php if (isset($verification_location) and $verification_location == '0') echo 'selected'; ?>> On Spot </option>
											<option value="1" <?php if (isset($verification_location) and $verification_location == '1') echo 'selected'; ?>> Customer Spot </option>
										</select>
										<span class="text-danger" style='display:none' id='verificationLocCheck'>Please Select Verification Location </span>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="verify_remark">Remarks</label>
										<input type="hidden" class="form-control" id="verify_remark_ack" name="verify_remark_ack" value="<?php if (isset($verify_remark)) {
																																								echo $verify_remark;
																																							} ?>">
										<input type="text" class="form-control" id="verify_remark" name="verify_remark" value='<?php if (isset($verify_remark)) echo $verify_remark; ?>' tabindex='17' placeholder="Enter Remarks" pattern="[a-zA-Z\s]+">
									</div>
								</div>

							</div>

						</div>
					</div>
					<!-- ///////////////////////////////////////////////  Verification Info  END /////////////////////////////////////////////////////////// -->

					<!-- Loan Info Start -->
					<div class="card">
						<div class="card-header">Loan Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
											<div class="form-group">
												<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="loan_category_ack" name="loan_category_ack">
												<select tabindex="16" type="text" class="form-control" id="loan_category" name="loan_category">
													<option value="">Select Loan Category</option>
												</select>
												<span class="text-danger" style='display:none' id='loancategoryCheck'>Please Select Loan Category</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Sub Category</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="sub_category_ack" name="sub_category_ack">
												<select tabindex="17" type="text" class="form-control" id="sub_category" name="sub_category">
													<option value="">Select Sub Category</option>
												</select>
												<span class="text-danger" style='display:none' id='subcategoryCheck'>Please Select Sub Category</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
										<div class="col-md-12">
											<br><br><label for="disabledInput">Category Info</label>&nbsp;<span class="text-danger">*</span><br><br>
											<table id="moduleTable" class="table custom-table">
												<tbody>
												</tbody>
											</table>
											<span class="text-danger" style='display:none' id='cat_infoCheck'>Please Enter Category Info</span><br><br>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Total Value</label>&nbsp;<span class="text-danger">*</span>
												<input tabindex="18" type="text" class="form-control" id="tot_value" name="tot_value" value='<?php if (isset($tot_value_lc)) {
																																					echo $tot_value_lc;
																																				} elseif (isset($tot_value)) {
																																					echo $tot_value;
																																				} ?>'>
												<span class="text-danger" style='display:none' id='total_valueCheck'>Please Enter Total Value</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Advance Amount</label>&nbsp;<span class="text-danger">*</span>
												<input tabindex="19" type="text" class="form-control" id="ad_amt" name="ad_amt" value='<?php if (isset($ad_amt_lc)) {
																																			echo $ad_amt_lc;
																																		} elseif (isset($ad_amt)) {
																																			echo $ad_amt;
																																		} ?>'>
												<span class="text-danger" style='display:none' id='ad_amtCheck'>Please Enter Advance Amount</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Loan Amount</label>&nbsp;<span class="text-danger">*</span>
												<input tabindex="20" type="text" class="form-control" id="loan_amt" name="loan_amt" value='<?php if (isset($loan_amt_lc)) {
																																				echo $loan_amt_lc;
																																			} elseif (isset($loan_amt)) {
																																				echo $loan_amt;
																																			} ?>'>
												<span class="text-danger" style='display:none' id='loan_amtCheck'>Please Enter Loan Amount</span>
											</div>
										</div>
										<hr>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Profit Type</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="profit_type_ack" name="profit_type_ack" value="<?php echo $profit_type_lc; ?>">
												<select tabindex="21" type="text" class="form-control" id="profit_type" name="profit_type">
													<option value="">Select Profit Type</option>
													<option value="1" <?php if (isset($profit_type_lc) and $profit_type_lc == '1') echo 'selected'; ?>>Calculation</option>
													<option value="2" <?php if (isset($profit_type_lc) and $profit_type_lc == '2') echo 'selected'; ?>>Scheme</option>
												</select>
												<span class="text-danger" style='display:none' id='profit_typeCheck'>Please Select Profit Type</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 calculation" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Due Method</label>&nbsp;<span class="text-danger">*</span>
												<input tabindex="22" type="text" class="form-control" id="due_method_calc" name="due_method_calc" readonly value='Monthly'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 calculation" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Due Type</label>&nbsp;<span class="text-danger">*</span>
												<input tabindex="23" type="text" class="form-control" id="due_type" name="due_type" readonly value='<?php if (isset($due_type)) echo $due_type; ?>'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi-calculation" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Profit Method</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="profit_method_ack" name="profit_method_ack" value='<?php if (isset($profit_method_lc)) echo $profit_method_lc;?>'>
												<select tabindex="24" type="text" class="form-control" id="profit_method" name="profit_method">
													<option value="">Select Profit Method</option>
												</select>
												<span class="text-danger" style='display:none' id='profit_methodCheck'>Please Select Profit Method</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 interest-calculation" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Calculation Method</label>&nbsp;<span class="text-danger">*</span>
												<input tabindex="25" type="text" class="form-control" id="calc_method" name="calc_method" readonly value='<?php if (isset($calc_method)) echo $calc_method; ?>'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Due Method</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="due_method_scheme_ack" name="due_method_scheme_ack" value="<?php echo $due_method_scheme_lc; ?>">
												<select tabindex="26" type="text" class="form-control" id="due_method_scheme" name="due_method_scheme">
													<option value="">Select Due Method</option>
													<option value="1" <?php if (isset($due_method_scheme_lc) and $due_method_scheme_lc == '1') echo 'selected'; ?>>Monthly</option>
													<option value="2" <?php if (isset($due_method_scheme_lc) and $due_method_scheme_lc == '2') echo 'selected'; ?>>Weekly</option>
													<option value="3" <?php if (isset($due_method_scheme_lc) and $due_method_scheme_lc == '3') echo 'selected'; ?>>Daily</option>
												</select>
												<span class="text-danger" style='display:none' id='due_method_schemeCheck'>Please Select Due Method</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 day_scheme" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Day</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="day_scheme_ack" name="day_scheme_ack" value="<?php echo $day_scheme_lc; ?>">
												<select tabindex="27" type="text" class="form-control" id="day_scheme" name="day_scheme">
													<option value="">Select a Day</option>
													<option value="1" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '1') echo 'selected'; ?>>Monday</option>
													<option value="2" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '2') echo 'selected'; ?>>Tuesday</option>
													<option value="3" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '3') echo 'selected'; ?>>Wednesdat</option>
													<option value="4" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '4') echo 'selected'; ?>>Thursday</option>
													<option value="5" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '5') echo 'selected'; ?>>Friday</option>
													<option value="6" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '6') echo 'selected'; ?>>Saturday</option>
													<option value="7" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '7') echo 'selected'; ?>>Sunday</option>
												</select>
												<span class="text-danger" style='display:none' id='day_schemeCheck'>Please Select Day</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Scheme Name</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="scheme_name_ack" name="scheme_name_ack" value="<?php echo $scheme_name_lc; ?>">
												<select tabindex="28" type="text" class="form-control" id="scheme_name" name="scheme_name">
													<option value="">Select Scheme Name</option>
												</select>
												<span class="text-danger" style='display:none' id='scheme_nameCheck'>Please Select Scheme Name</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme-calculation" style="display:none">
											<div class="form-group">
												<label for="scheme_profit_method">Profit Method</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="profit_method_scheme_ack" name="profit_method_scheme_ack" value="<?php echo $profit_method_scheme_lc; ?>">
												<select tabindex="25" type="text" class="form-control" id="scheme_profit_method" name="scheme_profit_method">
													<option value="">Select Profit Method</option>
												</select>
												<span class="text-danger" style='display:none' id='scheme_methodCheck'>Please Select Profit Method</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Interest Rate </label>&nbsp;<span class="text-danger min-max-int">*</span><!-- Min and max intrest rate-->
												<input tabindex="29" type="text" class="form-control" id="int_rate" name="int_rate" value='<?php if (isset($int_rate)) echo $int_rate; ?>'>
												<span class="text-danger" style='display:none' id='int_rateCheck'>Please Enter Interest Rate</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Due Period </label>&nbsp;<span class="text-danger min-max-due">*</span><!-- Min and max Profit Method-->
												<input tabindex="30" type="text" class="form-control" id="due_period" name="due_period" value=''>
												<span class="text-danger" style='display:none' id='due_periodCheck'>Please Enter Due Period</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Document Charges </label>&nbsp;<span class="text-danger min-max-doc">*</span><!-- Min and max Document charges-->
												<input tabindex="31" type="text" class="form-control" id="doc_charge" name="doc_charge" value='<?php if (isset($doc_charge)) echo $doc_charge; ?>'>
												<span class="text-danger" style='display:none' id='doc_chargeCheck'>Please Enter Document Charge</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Processing Fees</label>&nbsp;<span class="text-danger min-max-proc">*</span><!-- Min and max Processing fee-->
												<input tabindex="32" type="text" class="form-control" id="proc_fee" name="proc_fee" value='<?php if (isset($proc_fee)) echo $proc_fee; ?>'>
												<span class="text-danger" style='display:none' id='proc_feeCheck'>Please Enter Processing fee</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Loan info End -->
					<!-- Loan Calculation Start -->
					<div class="card">
						<div class="card-header">Loan Calculation <span style="font-weight:bold" class=""></span><input type="button" class="btn btn-outline-secondary text-right" id="refresh_cal" name="refresh_cal" value='Calculate' style="float:right"></div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Loan Amount</label>&nbsp;<span class="text-danger">*</span>
												<input type="text" class="form-control" readonly id="loan_amt_cal" name="loan_amt_cal" value='<?php if (isset($loan_amt_cal)) echo $loan_amt_cal; ?>' tabindex='33'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Principal Amount</label>&nbsp;<span class="text-danger">*</span>
												<input type="text" class="form-control" readonly id="principal_amt_cal" name="principal_amt_cal" value='<?php if (isset($principal_amt_cal)) echo $principal_amt_cal; ?>' tabindex='34'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Interest Amount</label>&nbsp;<span class="text-danger int-diff">*</span>
												<input type="text" class="form-control" readonly id="int_amt_cal" name="int_amt_cal" value='<?php if (isset($int_amt_cal)) echo $int_amt_cal; ?>' tabindex='35'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Total Amount</label>&nbsp;<span class="text-danger">*</span>
												<input type="text" class="form-control" readonly id="tot_amt_cal" name="tot_amt_cal" value='<?php if (isset($tot_amt_cal)) echo $tot_amt_cal; ?>' tabindex='36'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Due Amount</label>&nbsp;<span class="text-danger due-diff">*</span>
												<input type="text" class="form-control" readonly id="due_amt_cal" name="due_amt_cal" value='<?php if (isset($due_amt_cal)) echo $due_amt_cal; ?>' tabindex='37'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Document Charges</label>&nbsp;<span class="text-danger">*</span>
												<input type="text" class="form-control" readonly id="doc_charge_cal" name="doc_charge_cal" value='<?php if (isset($doc_charge_cal)) echo $doc_charge_cal; ?>' tabindex='38'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Processing Fee</label>&nbsp;<span class="text-danger">*</span>
												<input type="text" class="form-control" readonly id="proc_fee_cal" name="proc_fee_cal" value='<?php if (isset($proc_fee_cal)) echo $proc_fee_cal; ?>' tabindex='39'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Net Cash</label>&nbsp;<span class="text-danger">*</span>
												<input type="text" class="form-control" readonly id="net_cash_cal" name="net_cash_cal" value='<?php if (isset($net_cash_cal)) echo $net_cash_cal; ?>' tabindex='40'>
											</div>
										</div>
										<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
											<div class="text-right">
												<label for="disabledInput" style="visibility:hidden">Net Cash</label>
												<input type="hidden" class="form-control btn btn-outline-secondary" id="refresh_cal" name="refresh_cal" value='Refresh' tabindex='41'>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Loan info End -->
					<!-- Collection Info Start -->
					<div class="card">
						<div class="card-header">Collection Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Due Start From</label>&nbsp;<span class="text-danger">*</span>
												<input type="date" class="form-control" id="due_start_from" name="due_start_from" value='<?php if (isset($due_start_from)) echo $due_start_from; ?>' tabindex="42">
												<span class="text-danger" style='display:none' id='due_start_fromCheck'>Please Select Due Start Month</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Maturity Date</label>&nbsp;<span class="text-danger">*</span>
												<input type="date" class="form-control" id="maturity_month" name="maturity_month" value='<?php if (isset($maturity_month)) echo $maturity_month; ?>' readonly tabindex="43">
												<span class="text-danger" style='display:none' id='maturity_monthCheck'>Please Select Maturity Month</span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Collection Format</label>&nbsp;<span class="text-danger">*</span>
												<select type="text" class="form-control" id="collection_method" name="collection_method" tabindex="44">
													<option value="">Select Collection Format</option>
													<option value="1" <?php if (isset($collection_method) and $collection_method == '1') echo 'selected'; ?>>BySelf</option>
													<option value="2" <?php if (isset($collection_method) and $collection_method == '2') echo 'selected'; ?>>On Spot</option>
													<option value="3" <?php if (isset($collection_method) and $collection_method == '3') echo 'selected'; ?>>Cheque Collection</option>
													<option value="4" <?php if (isset($collection_method) and $collection_method == '4') echo 'selected'; ?>>ECS</option>
												</select>
												<span class="text-danger" style='display:none' id='collection_methodCheck'>Please Select Collection Format</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Collection Info End -->
					<div class="col-md-12 ">
						<div class="text-right">
							<button type="submit" name="submit_loan_calculation" id="submit_loan_calculation" class="btn btn-primary" value="Submit" tabindex="45"><span class="icon-check"></span>&nbsp;Submit</button>
							<button type="reset" class="btn btn-outline-secondary" tabindex="46">Clear</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!--  ///////////////////////////////////////////////////////////////// Loan Calculation Ends ////////////////////////////////////////////////////////// -->

</div>


<!-- Add Signed Doc info Modal  START -->
<div class="modal fade addSignDoc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="signDocUploads">
		<input type="hidden" name="doc_req_id" id="doc_req_id" value="<?php if (isset($req_id)) {
																			echo $req_id;
																		} ?>">
		<input type="hidden" name="doc_cus_id" id="doc_cus_id" value="<?php if (isset($cus_id)) {
																			echo $cus_id;
																		} ?>">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="myLargeModalLabel">Add Signed Doc Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetsigninfoList()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- alert messages -->
					<div id="signInsertOk" class="successalert"> Signed Doc Info Uploaded Successfully
						<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="signUpdateok" class="successalert"> Signed Doc Info Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="signNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="signDeleteOk" class="unsuccessalert"> Signed Doc Info Deleted
						<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="signDeleteNotOk" class="unsuccessalert"> Signed Doc Info not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<br />

					<div class="row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocName "> Doc Name </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" name="doc_name" id="doc_name" value="Signed Document" disabled tabindex='1'>
								<!-- <select type="text" class="form-control" id="doc_name" name="doc_name" disabled tabindex='1'>
									<option value=""> Select Doc Name </option>
									<option value="0"> Promissory Note </option>
									<option value="1"> Stamp Paper </option>
									<option value="2"> P Additional </option>
									<option value="3"> S Additional </option>
								</select> -->
								<span class="text-danger" id="docNameCheck"> Select Doc Name </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="SignType"> Sign Type </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="sign_type" name="sign_type" disabled tabindex='1'>
									<option value=""> Select Sign Type </option>
									<option value="0"> Customer </option>
									<option value="1"> Guarantor </option>
									<option value="2"> Combined </option>
									<option value="3"> Family Members </option>
								</select>
								<span class="text-danger" id="signTypeCheck"> Select Sign Type </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" style="display: none;" id="guar_name_div">
							<div class="form-group">
								<label for="guar_name"> Guarentor Name </label>
								<input type="text" class="form-control" id="guar_name" name="guar_name" readonly tabindex='1'>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" style="display: none;" id="relation_doc">
							<div class="form-group">
								<label for="signRelationship"> Relationship </label>
								<select type="text" class="form-control" id="signType_relationship" name="signType_relationship" disabled tabindex='1'>
									<option value=""> Select Relationship </option>
								</select>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="Count"> Count </label> <span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="doc_Count" name="doc_Count" placeholder="Enter Count" readonly tabindex='1'>
								<span class="text-danger" id="docCountCheck"> Enter Count </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="upd"> Uploads </label>
								<input type="file" onchange="compressImage(this,400)" class="form-control" id="signdoc_upd" name="signdoc_upd[]" multiple onchange="filesCount()" tabindex='1'>
								<span class="text-danger" id="docupdCheck"> Upload Document </span>
							</div>
						</div>

						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="signedID" id="signedID">
							<button type="submit" name="signInfoBtn" id="signInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='1'>Submit</button>
						</div>

					</div>
					</br>

					<div id="signTable" style="overflow-x: auto;">
						<table class="table custom-table modalTable">
							<thead>
								<tr>
									<th width="50"> S.No </th>
									<th> Doc Name </th>
									<th> Sign Type </th>
									<th> Relationship </th>
									<th> Count </th>
									<th> ACTION </th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetsigninfoList()" tabindex='1'>Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Signed Doc Info Modal -->


<!-- Add Cheque info Modal  START -->
<div class="modal fade addCheque" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="chequeUploads">
		<input type="hidden" name="cheque_req_id" id="cheque_req_id" value="<?php if (isset($req_id)) {
																				echo $req_id;
																			} ?>">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="myLargeModalLabel">Add Cheque Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="chequeinfoList()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- alert messages -->
					<div id="chequeInsertOk" class="successalert"> Cheque Info Uploaded Successfully
						<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="chequeUpdateok" class="successalert"> Cheque Info Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="chequeNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="chequeDeleteOk" class="unsuccessalert"> Cheque Info Deleted
						<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="chequeDeleteNotOk" class="unsuccessalert"> Cheque Info not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<br />

					<div class="row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="Holdertype "> Holder type </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="holder_type" name="holder_type" disabled tabindex='1'>
									<option value=""> Select Holder type </option>
									<option value="0"> Customer </option>
									<option value="1"> Guarantor </option>
									<option value="2"> Family Members </option>
								</select>
								<span class="text-danger" id="holdertypeCheck"> Select Holder type </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="HolderName "> Holder Name </label>
								<input type="text" class="form-control" id="holder_name" name="holder_name" readonly tabindex='1'>

								<select type="text" class="form-control" id="holder_relationship_name" name="holder_relationship_name" style="display: none;" disabled tabindex='1'>
									<option value=""> Select Holder Name </option>
								</select>
							</div>
						</div>


						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="chequeRelationship"> Relationship </label>
								<input type="text" class="form-control" id="cheque_relation" name="cheque_relation" readonly tabindex='1'>

							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="BankName"> Bank Name </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="chequebank_name" name="chequebank_name" placeholder="Enter Bank Name" onkeydown="return /[a-z ]/i.test(event.key)" readonly tabindex='1'>
								<span class="text-danger" id="chequebankCheck"> Enter Bank Name </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="chequeNo"> Cheque Count </label> <span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="cheque_count" name="cheque_count" placeholder="Enter Cheque Count" readonly tabindex='1'>
								<span class="text-danger" id="chequeCountCheck"> Enter Cheque Count </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="upd"> Uploads </label>
								<input type="file" onchange="compressImage(this,400)" class="form-control" id="cheque_upd" name="cheque_upd[]" multiple onchange="chequefilesCount()" tabindex='1'>
								<span class="text-danger" id="chequeupdCheck"> Upload Cheque </span>
							</div>
						</div>
					</div>

					<div class="row" id="chequeColumnDiv"> </div>

					<div class="row">
						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="chequeID" id="chequeID">
							<button type="submit" name="chequeInfoBtn" id="chequeInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='1'>Submit</button>
						</div>
					</div>
					</br>


					<div id="chequeTable" style="overflow-x: auto;">
						<table class="table custom-table">
							<thead>
								<tr>
									<th width="50"> S.No </th>
									<th> Holder type </th>
									<th> Holder Name </th>
									<th> Relationship </th>
									<th> Bank Name </th>
									<th> Cheque No </th>
									<th> ACTION </th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="chequeinfoList();" tabindex='1'>Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Cheque Info Modal -->


<!-- Add Gold info Modal  START -->
<div class="modal fade addGold" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Gold Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="goldinfoList()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="goldInsertOk" class="successalert"> Gold Info Added Successfully
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="goldUpdateok" class="successalert"> Gold Info Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="goldNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="goldDeleteOk" class="unsuccessalert"> Gold Info Deleted
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="goldDeleteNotOk" class="unsuccessalert"> Gold Info not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />

				<div class="row">

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="GoldStatus "> Gold Status </label> <span class="required">&nbsp;*</span>
							<select type="text" class="form-control" id="gold_sts" name="gold_sts" tabindex='1'>
								<option value=""> Select Gold Status </option>
								<option value="0"> Old </option>
								<option value="1"> New </option>
							</select>
							<span class="text-danger" id="GoldstatusCheck"> Select Gold Status </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="Goldtype "> Gold Type (Ornament's Name)</label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="gold_type" name="gold_type" placeholder="Enter Gold Type" tabindex='1'>
							<span class="text-danger" id="GoldtypeCheck"> Enter Gold Type </span>
						</div>
					</div>


					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="Purity "> Purity (Carat)</label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="Purity" name="Purity" placeholder="Enter Purity" tabindex='1'>
							<span class="text-danger" id="purityCheck"> Enter Purity </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="Count"> Count </label> <span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="gold_Count" name="gold_Count" placeholder="Enter Count" tabindex='1'>
							<span class="text-danger" id="goldCountCheck"> Enter Count </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="Weight"> Weight (in Grams)</label> <span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="gold_Weight" name="gold_Weight" placeholder="Enter Weight in Grams" tabindex='1'>
							<span class="text-danger" id="goldWeightCheck"> Enter Weight </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="Value"> Value </label> <span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="gold_Value" name="gold_Value" placeholder="Enter Value" tabindex='1'>
							<span class="text-danger" id="goldValueCheck"> Enter Value </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="gold_upload"> Upload </label>
							<input type="hidden" name="goldupload" id="goldupload">
							<input type="file" onchange="compressImage(this,400)" class="form-control" id="gold_upload" name="gold_upload" accept=".pdf,.jpg,.png,.jpeg" tabindex='1'>
							<span class="text-danger" id="gold_uploadCheck" style="display:none"> Please Upload file </span>
						</div>
					</div>

					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<input type="hidden" name="goldID" id="goldID">
						<button type="button" name="goldInfoBtn" id="goldInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='1'>Submit</button>
					</div>
				</div>
				</br>


				<div id="goldTable" style="overflow-x: auto;">
					<table class="table custom-table">
						<thead>
							<tr>
								<th width="50"> S.No </th>
								<th> Gold Status </th>
								<th> Purity </th>
								<th> Count </th>
								<th> Weight </th>
								<th> Value </th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="goldinfoList()" tabindex='1'>Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add Gold Info Modal -->


<!-- Add Document info Modal  START -->
<div class="modal fade addDocument" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="docUploads">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="myLargeModalLabel">Add Document Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="docinfoList()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- alert messages -->
					<div id="docInsertOk" class="successalert"> Document Info Added Successfully
						<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="docUpdateok" class="successalert"> Document Info Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="docNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="docDeleteOk" class="unsuccessalert"> Document Info Deleted
						<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<div id="docDeleteNotOk" class="unsuccessalert"> Document Info not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
					</div>

					<br />

					<div class="row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="Documentname "> Document name </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="document_name" name="document_name" placeholder="Enter Document name" value="" tabindex="1" readonly />
								<span class="text-danger" id="documentnameCheck"> Enter Document name </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocumentDeatails "> Document Details </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="document_details" name="document_details" placeholder="Enter Document Details" value="" tabindex="1" readonly />
								<span class="text-danger" id="documentdetailsCheck"> Enter Document Details </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="Documenttype"> Document Type </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="document_type" name="document_type" tabindex="1" readonly>
									<option value=''> Select Document Type </option>
									<option value='0'> Original </option>
									<option value='1'> Xerox </option>
								</select>
								<span class="text-danger" id="documentTypeCheck"> Select Document Type </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocumentHolder"> Document Holder </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="document_holder" name="document_holder" tabindex="1" readonly>
									<option value=""> Select Holder type </option>
									<option value="0"> Customer </option>
									<option value="1"> Guarantor </option>
									<option value="2"> Family Members </option>
								</select>
								<span class="text-danger" id="docholderCheck"> Select Document Holder </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="docholdername"> Holder Name </label>
								<input type="text" class="form-control" id="docholder_name" name="docholder_name" value="" readonly tabindex="1" readonly>

								<select type="text" class="form-control" id="docholder_relationship_name" name="docholder_relationship_name" style="display: none;" tabindex="1" readonly>
									<option value=""> Select Relationship </option>
								</select>
							</div>
						</div>


						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocRelationship"> Relationship </label>
								<input type="text" class="form-control" id="doc_relation" name="doc_relation" value="" readonly tabindex="1">
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocumentUpd"> Document Uploads </label>
								<input type="file" onchange="compressImage(this,400)" class="form-control" id="document_info_upd" name="document_info_upd[]" multiple tabindex="1">
							</div>
						</div>

						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="doc_info_id" id="doc_info_id" value=''>
							<button type="button" name="docInfoBtn" id="docInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex="1">Submit</button>
						</div>
					</div>
					</br>


					<div id="docModalDiv" style="overflow-x: auto;">
						<table class="table custom-table">
							<thead>
								<tr>
									<th width="50"> S.No </th>
									<th> Document Name </th>
									<th> Document Details</th>
									<th> Document Type </th>
									<th> Document Holder</th>
									<th> Holder Name</th>
									<th> Relationship</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="docinfoList()" tabindex='1'>Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Document Info Modal -->

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="vendor/mfs100/Library/js/jquery-1.8.2.js" type="text/javascript"></script>
<script src="vendor/mfs100/Library/js/mfs100.js"></script>