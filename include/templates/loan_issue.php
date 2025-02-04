<?php
if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}

if (isset($_POST['submit_loanIssue']) && $_POST['submit_loanIssue'] != '') {

	$userObj->addloanIssue($mysqli, $userid);
?>
	<!-- <script> alert('Loan Issued Details Submitted'); </script> -->
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_loan_issue&msc=1';
	</script>
<?php
}

$getBankDetails = $userObj->getBankDetails($mysqli);

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
	$communication = $getCustomerProfile['communication'];
	$com_audio = $getCustomerProfile['com_audio'];
	$verification_person = $getCustomerProfile['verification_person'];
	$verification_location = $getCustomerProfile['verification_location'];
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
	$gold_info = $documentationInfo['gold_info'];
	$gold_sts = $documentationInfo['gold_sts'];
	$gold_type = $documentationInfo['gold_type'];
	$Purity = $documentationInfo['Purity'];
	$gold_Count = $documentationInfo['gold_Count'];
	$gold_Weight = $documentationInfo['gold_Weight'];
	$gold_Value = $documentationInfo['gold_Value'];
	$document_name = $documentationInfo['document_name'];
	$document_details = $documentationInfo['document_details'];
	$document_type = $documentationInfo['document_type'];
	$doc_info_upload = $documentationInfo['doc_info_upload'];
	$document_holder = $documentationInfo['document_holder'];
	$docholder_name = $documentationInfo['docholder_name'];
	$docholder_relationship_name = $documentationInfo['docholder_relationship_name'];
	$doc_relation = $documentationInfo['doc_relation'];
}
////////   Documentation End ////////////

///////// Loan Calculation ///////////////
$getCusInfoForLoanCal = $userObj->getAcknowlegeCusInfoForLoanCal($mysqli, $idupd);
if (sizeof($getCusInfoForLoanCal) > 0) {
	for ($i = 0; $i < sizeof($getCusInfoForLoanCal); $i++) {
		$cus_id_lc = $getCusInfoForLoanCal['cus_id'];
		$cus_name_lc = $getCusInfoForLoanCal['cus_name'];
		$cus_pic_lc = $getCusInfoForLoanCal['cus_pic'];
		$cus_data_lc = $getCusInfoForLoanCal['cus_type'];
		$mobile_lc = $getCusInfoForLoanCal['mobile'];
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
		$cus_status_lc = $getLoanCalculation['cus_status'];
	}

	//Get Loan calculation Category info for edit
	if ($loan_cal_id > 0) {
		$getLoanCalCategory = $userObj->getAckVerificationLoanCalCategory($mysqli, $loan_cal_id);
	}
}

///////// Loan Calculation End ///////////////

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
		Marudham Capitals - Loan Issue
	</div>
</div><br>
<div class="page-header sticky-top" id="navbar" style="display: none;" data-toggle="toggle">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px; margin-top:50px;">
		Customer Name - <?php if (isset($cus_name)) {
							echo $cus_name;
						} ?>
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_loan_issue">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">

	<!--form start-->
	<div>
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
			<input type="hidden" name="loan_category_lc" id="loan_category_lc" value="<?php if (isset($loan_category_lc)) {
																							echo $loan_category_lc;
																						} ?>" />
			<input type="hidden" name="sub_category_upd" id="sub_category_upd" value="<?php if (isset($sub_category_lc)) {
																							echo $sub_category_lc;
																						} ?>" />

			<!-- Row start -->
			<div class="row gutters">
				<!-- Request Info -->
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

					<!-- Personal info START -->
					<div class="card">
						<div class="card-header">Personal Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_id">Customer ID</label>
												<input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cp_cus_id)) {
																																echo $cp_cus_id;
																															} ?>' readonly tabindex='1'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_name">Customer Name</label>
												<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cp_cus_name)) {
																																	echo $cp_cus_name;
																																} ?>' readonly tabindex='2'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="name"> Customer Type </label>
												<input type="text" class="form-control" name="cus_type" id="cus_type" value="<?php if (isset($cus_type)) {
																																	echo $cus_type;
																																} ?>" readonly tabindex='3'>
											</div>
										</div>

										<div id="exist_type" <?php if (isset($cus_type)) {
																	if ($cus_type != 'Existing') { ?> style="display: none" <?php }
																													} ?> class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="ExistType"> Exist Type </label>
												<input type="text" class="form-control" name="cus_exist_type" id="cus_exist_type" value="<?php if (isset($cus_exist_type)) {
																																				echo $cus_exist_type;
																																			} ?>" readonly tabindex='4'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile1">Mobile No 1</label>
												<input type="number" class="form-control" id="mobile1" name="mobile1" value='<?php if (isset($cp_mobile1)) {
																																	echo $cp_mobile1;
																																} ?>' readonly tabindex='5'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile2">Mobile No 2</label>
												<input type="number" class="form-control" id="mobile2" name="mobile2" value='<?php if (isset($cp_mobile2)) {
																																	echo $cp_mobile2;
																																} ?>' readonly tabindex='6'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="whatsapp">Whatsapp No </label>
												<input type="number" class="form-control" id="whatsapp_no" name="whatsapp_no" value="<?php if (isset($cp_whatsapp)) {
																																			echo $cp_whatsapp;
																																		} ?>" readonly tabindex='7'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="DocArea"> Area </label>
												<input type="text" class="form-control" id="doc_area" name="doc_area" value="<?php if (isset($doc_area_name)) echo $doc_area_name; ?>" readonly tabindex='8'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="DocSubArea"> Sub Area </label>
												<input type="text" class="form-control" id="doc_Sub_Area" name="doc_Sub_Area" value='<?php if (isset($doc_sub_area_name)) echo $doc_sub_area_name; ?>' readonly tabindex='9'>
											</div>
										</div>

									</div>
								</div>

								<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
									<div class="col-xl-8 col-lg-10 col-md-6 ">
										<div class="form-group" style="margin-left: 30px;">
											<label for="pic" style="margin-left: -20px;">Photo</label><br>
											<input type="hidden" name="cus_image" id="cus_image" value="<?php if (isset($cp_cus_pic)) {
																											echo $cp_cus_pic;
																										} ?>">
											<img id='imgshow' class="img_show" src='' />
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Personal info END -->

					<!-- Guarentor info START -->
					<div class="card">
						<div class="card-header">Guarentor Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="GuarentorName"> Guarentor Name </label>
												<select type="text" class="form-control" id="guarentor_name" name="guarentor_name" disabled tabindex='10'>
													<option> Select Guarantor </option>
												</select>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="GuarentorRelationship"> Guarentor Relationship </label>
												<input type="text" class="form-control" id="guarentor_relationship" name="guarentor_relationship" value='<?php if (isset($guarentor_relation)) {
																																								echo $guarentor_relation;
																																							} ?>' readonly tabindex='11'>
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
									<div class="col-xl-8 col-lg-10 col-md-6 ">
										<div class="form-group" style="margin-left: 30px;">
											<label for="pic" style="margin-left: -20px;"> Guarentor Photo </label><br>
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

					<!-- Loan Info START -->
					<div class="card">
						<div class="card-header">Loan Info <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="LoanCategory"> Loan Category </label>
										<input type="text" class="form-control" id="loan_category" name="loan_category" readonly tabindex='12'>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Subcategory"> Sub category </label>
										<input type="text" class="form-control" id="sub_category" name="sub_category" value="<?php if (isset($sub_category_lc)) {
																																	echo $sub_category_lc;
																																} ?>" readonly tabindex='13'>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="Agent"> Agent </label>
										<input type="text" class="form-control" id="agent" name="agent" readonly tabindex='14'>
									</div>
								</div>

								<div class="col-md-12">
									<label for="disabledInput">Category Info</label><br><br>
									<table id="moduleTable" class="table custom-table">
										<tbody> </tbody>
									</table>
									<br><br>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" <?php if (isset($tot_value_lc) and $tot_value_lc == '') { ?>style="display: none;" <?php } ?>>
									<div class="form-group">
										<label for="disabledInput">Total Value</label>
										<input type="text" class="form-control" id="tot_value" name="tot_value" value='<?php if (isset($tot_value_lc)) {
																															echo $tot_value_lc;
																														} elseif (isset($tot_value)) {
																															echo $tot_value;
																														} ?>' readonly tabindex='15'>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" <?php if (isset($ad_amt_lc) and $ad_amt_lc == '') { ?>style="display: none;" <?php } ?>>
									<div class="form-group">
										<label for="disabledInput">Advance Amount</label>
										<input type="text" class="form-control" id="ad_amt" name="ad_amt" value='<?php if (isset($ad_amt_lc)) {
																														echo $ad_amt_lc;
																													} elseif (isset($ad_amt)) {
																														echo $ad_amt;
																													} ?>' readonly tabindex='16'>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="disabledInput">Loan Amount</label>
										<input type="text" class="form-control" id="loan_amt" name="loan_amt" value='<?php if (isset($loan_amt_lc)) {
																															echo $loan_amt_lc;
																														} elseif (isset($loan_amt)) {
																															echo $loan_amt;
																														} ?>' readonly tabindex='17'>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Loan Info END -->

					<!-- Loan Calculation Start -->
					<div class="card">
						<div class="card-header">Loan Calculation <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Loan Amount</label>
												<input type="text" class="form-control" readonly id="loan_amt_cal" name="loan_amt_cal" value='<?php if (isset($loan_amt_cal)) echo $loan_amt_cal; ?>' tabindex='18'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Principal Amount</label>
												<input type="text" class="form-control" readonly id="principal_amt_cal" name="principal_amt_cal" value='<?php if (isset($principal_amt_cal)) echo $principal_amt_cal; ?>' tabindex='19'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Interest Amount</label>
												<input type="text" class="form-control" readonly id="int_amt_cal" name="int_amt_cal" value='<?php if (isset($int_amt_cal)) echo $int_amt_cal; ?>' tabindex='20'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Total Amount</label>
												<input type="text" class="form-control" readonly id="tot_amt_cal" name="tot_amt_cal" value='<?php if (isset($tot_amt_cal)) echo $tot_amt_cal; ?>' tabindex='21'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Due Amount</label>
												<input type="text" class="form-control" readonly id="due_amt_cal" name="due_amt_cal" value='<?php if (isset($due_amt_cal)) echo $due_amt_cal; ?>' tabindex='22'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Document Charges</label>
												<input type="text" class="form-control" readonly id="doc_charge_cal" name="doc_charge_cal" value='<?php if (isset($doc_charge_cal)) echo $doc_charge_cal; ?>' tabindex='23'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Processing Fee</label>
												<input type="text" class="form-control" readonly id="proc_fee_cal" name="proc_fee_cal" value='<?php if (isset($proc_fee_cal)) echo $proc_fee_cal; ?>' tabindex='24'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Net Cash</label>
												<input type="text" class="form-control" readonly id="net_cash_cal" name="net_cash_cal" value='<?php if (isset($net_cash_cal)) echo $net_cash_cal; ?>' tabindex='25'>
												<!-- <input type="hidden" class="form-control"  id="net_cash" name="net_cash" > -->
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
												<input type="hidden" class="form-control" id="due_period" name="due_period" value='<?php if (isset($due_period_lc)) echo $due_period_lc; ?>'>
												<input type="hidden" class="form-control" id="profit_type" name="profit_type" value='<?php if (isset($profit_type_lc)) echo $profit_type_lc; ?>'>
												<input type="hidden" class="form-control" id="due_method_calc" name="due_method_calc" value='Monthly'>
												<input type="hidden" class="form-control" id="due_method_scheme" name="due_method_scheme" value='<?php if (isset($due_method_scheme_lc)) echo $due_method_scheme_lc; ?>'>
												<input type="hidden" class="form-control" id="day_scheme" name="day_scheme" value='<?php if (isset($day_scheme_lc)) echo $day_scheme_lc; ?>'>

												<input type="date" class="form-control" id="due_start_from" name="due_start_from" value='<?php if (isset($due_start_from)) echo $due_start_from; ?>' tabindex="26">
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Maturity Month</label>
												<input type="date" class="form-control" id="maturity_month" name="maturity_month" value='<?php if (isset($maturity_month)) echo $maturity_month; ?>' readonly tabindex='27'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Collection Method</label>
												<select type="text" class="form-control" id="collection_method" name="collection_method" disabled tabindex='28'>
													<option value="">Select Collection Method</option>
													<option value="1" <?php if (isset($collection_method) and $collection_method == '1') echo 'selected'; ?>>BySelf</option>
													<option value="2" <?php if (isset($collection_method) and $collection_method == '2') echo 'selected'; ?>>Spot Collection</option>
													<option value="3" <?php if (isset($collection_method) and $collection_method == '3') echo 'selected'; ?>>Cheque Collection</option>
													<option value="4" <?php if (isset($collection_method) and $collection_method == '4') echo 'selected'; ?>>ECS</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Collection Info End -->

					<!-- Issued Info Start -->
					<div class="card">
						<div class="card-header">Issued Info <span style="font-weight:bold" class=""></span></div>
						<span class="text-danger" style="display: none;" id="val_check"> Please Enter Any One of the Value </span>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput"> Balance To Issue </label>
												<input type="text" class="form-control" id="net_cash" name="net_cash" readonly tabindex='29'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Issued to </label>&nbsp;<span class="text-danger issued_to_type"></span>
												<input type="text" class="form-control" id="issue_to" name="issue_to" readonly tabindex='30'>
												<input type="hidden" class="form-control" id="agent_id" name="agent_id">
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Issued mode</label>&nbsp;<span class="text-danger">*</span>
												<select type="text" class="form-control" id="issued_mode" name="issued_mode" tabindex="31">
													<option value=""> Select Issued Mood</option>
													<option value="0"> Split Payment </option>
													<option value="1"> Single Payment </option>
												</select>
												<span class="text-danger" style="display: none;" id="issue"> Please Select Issued Mode </span>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 paymentType" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Payment Type </label>&nbsp;<span class="text-danger">*</span>
												<select type="text" class="form-control" id="payment_type" name="payment_type" tabindex="32">
													<option value=""> Select Payment Type</option>
													<option value="0"> Cash </option>
													<option value="1"> Cheque </option>
													<option value="2"> Account Transfer </option>
												</select>
												<span class="text-danger" style="display: none;" id="pay_type"> Please Select Payment Type </span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="bankDiv" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Bank Name </label>&nbsp;<span class="text-danger">*</span>
												<select type="text" class="form-control" id="bank_id" name="bank_id" tabindex="33">
													<option value=""> Select Bank Name</option>
													<?php if (sizeof($getBankDetails) > 0) {
														for ($i = 0; $i < sizeof($getBankDetails); $i++) {
															$bank_id = $getBankDetails[$i]['id'];
															$bank_name = $getBankDetails[$i]['short_name'] . ' - ' . substr($getBankDetails[$i]['acc_no'], -5);
													?>
															<option value="<?php echo $bank_id; ?>">
																<?php echo $bank_name; ?>
															</option>
													<?php }
													} ?>
												</select>
												<span class="text-danger" style="display: none;" id="bank_idCheck"> Please Select Bank Name </span>
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 cash_issue" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Cash</label>
												<input type="number" class="form-control" id="cash" name="cash" tabindex="34">
												<span class="text-danger" style="display: none;" id="cash_amnt"> Please Enter Cash </span>
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 checque" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Cheque number</label>
												<input type="number" class="form-control" id="chequeno" name="chequeno" tabindex="35">
												<span class="text-danger" style="display: none;" id="cheque_num"> Please Enter Cheque Number </span>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 checque" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Cheque Value</label>
												<input type="number" class="form-control" id="chequeValue" name="chequeValue" tabindex="36">
												<span class="text-danger" style="display: none;" id="cheque_val"> Please Enter Cheque Value </span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 checque" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Cheque Remark</label>
												<input type="text" class="form-control" id="chequeRemark" name="chequeRemark" tabindex="37">
												<span class="text-danger" style="display: none;" id="cheque_remark"> Please Enter Cheque Remark </span>
											</div>
										</div>

									</div>

									<div class="row">

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Transaction ID</label>
												<input type="text" class="form-control" id="transaction_id" name="transaction_id" tabindex="38">
												<span class="text-danger" style="display: none;" id="transact_id"> Please Enter Transaction ID </span>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Transaction Value </label>
												<input type="number" class="form-control" id="transaction_value" name="transaction_value" tabindex="39">
												<span class="text-danger" style="display: none;" id="transact_val"> Please Enter Transaction Value </span>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Transaction Remark </label>
												<input type="text" class="form-control" id="transaction_remark" name="transaction_remark" tabindex="40">
												<span class="text-danger" style="display: none;" id="transact_remark"> Please Enter Transaction Remark </span>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 balance" style="display:none">
											<div class="form-group">
												<label for="disabledInput">Balance Amount </label>
												<input type="text" class="form-control" id="balance" name="balance" readonly tabindex='41'>
											</div>
										</div>


									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Issued Info End -->

					<!-- Cash Acknowledgement  Start -->
					<div class="card" id="cashAck" style="display:none">
						<div class="card-header">Cash Acknowledgement <span style="font-weight:bold" class=""></span></div>
						<span class="text-danger" style="display: none;" id="finger_check"> Please Scan your Finger Print </span>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="GuarentorName"> Name </label><span class="required">&nbsp;*</span>
												<select type="text" class="form-control" id="cash_guarentor_name" name="cash_guarentor_name" tabindex="42">
													<option> Select Guarantor </option>
												</select>
												<span class="text-danger" style="display: none;" id="cash_guarentor"> Please Select the Name </span>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="GuarentorRelationship"> Relationship </label>
												<input type="text" class="form-control" id="relationship" name="relationship" readonly tabindex="43">
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<input type="hidden" class="form-control" id="compare_finger" name="compare_finger"> <!--Already Store in Finger Print Table... when select fam name above the finger print will be shown here to compare. -->
												<input type="hidden" class="form-control" id="ack_fingerprint" name="ack_fingerprint"> <!-- finger print value from Device when scanning.-->
												<input type="hidden" class="form-control" id="fingerValidation" name="fingerValidation"> <!-- set val as 1 when finger Print Matching becuz to use for finger print validation if submit click.-->
												<button type="button" class='btn btn-success scanBtn' style='background-color: #009688;margin-top: 19px;' onclick="event.preventDefault()" title='Put Your Thumb' tabindex='44'><i class="material-icons" id="icon-flipped"> &#xe90d; </i>&nbsp;Scan</button>
												<span class="text-danger" id="hand_type" style="position: relative;top: 12px;"> </span>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- cash Acknowledgement Info End -->


					<!-- Submit Button Start -->
					<div class="col-md-12 ">
						<div class="text-right">
							<button type="submit" name="submit_loanIssue" id="submit_loanIssue" class="btn btn-primary" value="Submit" tabindex="45"><span class="icon-check"></span>&nbsp;Submit</button>
							<!-- <button type="reset" class="btn btn-outline-secondary" tabindex="20">Clear</button> -->
						</div>
					</div>
					<!-- Submit Button End -->

				</div>
			</div>
		</form>
	</div>
	<!-- Form End -->

</div>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="vendor/mfs100/Library/js/jquery-1.8.2.js" type="text/javascript"></script>
<script src="vendor/mfs100/Library/js/mfs100.js"></script>