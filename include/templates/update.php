<?php

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd']; //Customer ID.
}

@session_start();
if (isset($_SESSION['userid'])) {
	$userid = $_SESSION['userid'];
}

if (isset($_POST['submit_update_cus_profile']) && $_POST['submit_update_cus_profile'] != '') {

	$userObj->updateCustomerProfile($mysqli, $userid);
?>
	<script>
		alert('Customer Profile Updated');
	</script>
<?php
}


//////////////////////// Customer Profile Info ///////////////////////////////
$getCustomerReg = $userObj->getCustomerRegister($mysqli, $idupd);
if (sizeof($getCustomerReg) > 0) {
	for ($i = 0; $i < sizeof($getCustomerReg); $i++) {
		$cus_reg_id				= $getCustomerReg['cus_reg_id'];
		$req_id					= $getCustomerReg['req_id'];
		$cus_id					= $getCustomerReg['cus_id'];
		$cus_name				= $getCustomerReg['cus_name'];
		$dob					= $getCustomerReg['dob'];
		$age					= $getCustomerReg['age'];
		$gender					= $getCustomerReg['gender'];
		$state					= $getCustomerReg['state'];
		$district				= $getCustomerReg['district'];
		$taluk					= $getCustomerReg['taluk'];
		$area					= $getCustomerReg['area'];
		$sub_area				= $getCustomerReg['sub_area'];
		$address				= $getCustomerReg['address'];
		$mobile1				= $getCustomerReg['mobile1'];
		$mobile2				= $getCustomerReg['mobile2'];
		$father_name			= $getCustomerReg['father_name'];
		$mother_name			= $getCustomerReg['mother_name'];
		$marital				= $getCustomerReg['marital'];
		$spouse_name			= $getCustomerReg['spouse'];
		$occupation_type		= $getCustomerReg['occupation_type'];
		$occupation				= $getCustomerReg['occupation'];
		$pic					= $getCustomerReg['cus_pic'];
		$how_to_know 			= $getCustomerReg['how_to_know'];
		$loan_count 			= $getCustomerReg['loan_count'];
		$first_loan_date 		= $getCustomerReg['first_loan_date'];
		$travel_with_company 	= $getCustomerReg['travel_with_company'];
		$monthly_income 		= $getCustomerReg['monthly_income'];
		$other_income 			= $getCustomerReg['other_income'];
		$support_income 		= $getCustomerReg['support_income'];
		$commitment 			= $getCustomerReg['commitment'];
		$monthly_due_capacity 	= $getCustomerReg['monthly_due_capacity'];
		$loan_limit 			= $getCustomerReg['loan_limit'];
		$about_customer 		= $getCustomerReg['about_customer'];
		$residential_type 		= $getCustomerReg['residential_type'];
		$residential_details 	= $getCustomerReg['residential_details'];
		$residential_address 	= $getCustomerReg['residential_address'];
		$residential_native_address = $getCustomerReg['residential_native_address'];
		$cr_occupation_type 		= $getCustomerReg['occupation_info_occ_type'];
		$occupation_details 		= $getCustomerReg['occupation_details'];
		$occupation_income 			= $getCustomerReg['occupation_income'];
		$occupation_address 		= $getCustomerReg['occupation_address'];
		$dow 						= $getCustomerReg['dow'];
		$abt_occ 					= $getCustomerReg['abt_occ'];
		$area_confirm_type 			= $getCustomerReg['area_confirm_type'];
		$area_confirm_state 		= $getCustomerReg['area_confirm_state'];
		$area_confirm_district 		= $getCustomerReg['area_confirm_district'];
		$area_confirm_taluk 		= $getCustomerReg['area_confirm_taluk'];
		$area_confirm_area 			= $getCustomerReg['area_confirm_area'];
		$area_confirm_subarea 		= $getCustomerReg['area_confirm_subarea'];
		$latlong 		= $getCustomerReg['latlong'];
		$area_group 				= $getCustomerReg['area_group'];
		$area_line 					= $getCustomerReg['area_line'];
		$area_name 					= $getCustomerReg['area_name'];
		$sub_area_name 					= $getCustomerReg['sub_area_name'];
		// $request_id 					= $getCustomerReg['request_id'];
	}
}
$getGuarantorDetails = $userObj->getGuarantorDetails($mysqli, $idupd);

if (sizeof($getGuarantorDetails) > 0) {
	$guarentor_name = $getGuarantorDetails['guarentor_name'];
	$guarentor_relation = $getGuarantorDetails['guarentor_relation'];
	$guarentor_photo = $getGuarantorDetails['guarentor_photo'];
}
//////////////////////// Customer Profile Info END ///////////////////////////////

/////////  Documentation ////////////
$documentationInfo = $userObj->getDocumentDetails($mysqli, $idupd);

if (sizeof($documentationInfo) > 0) {
	for ($i = 0; $i < sizeof($documentationInfo); $i++) {
		$document_table_id[$i] = $documentationInfo[$i]['doc_Tableid'];
		$document_sts[$i] = $documentationInfo[$i]['cus_status'];
		$doc_id[$i] = $documentationInfo[$i]['doc_id'];
		$mortgage_process[$i] = $documentationInfo[$i]['mortgage_process'];
		$Propertyholder_type[$i] = $documentationInfo[$i]['Propertyholder_type'];
		$Propertyholder_name[$i] = $documentationInfo[$i]['Propertyholder_name'];
		$Propertyholder_relationship_name[$i] = $documentationInfo[$i]['Propertyholder_relationship_name'];
		$doc_property_relation[$i] = $documentationInfo[$i]['doc_property_relation'];
		$doc_property_type[$i] = $documentationInfo[$i]['doc_property_type'];
		$doc_property_measurement[$i] = $documentationInfo[$i]['doc_property_measurement'];
		$doc_property_location[$i] = $documentationInfo[$i]['doc_property_location'];
		$doc_property_value[$i] = $documentationInfo[$i]['doc_property_value'];
		$endorsement_process[$i] = $documentationInfo[$i]['endorsement_process'];
		$owner_type[$i] = $documentationInfo[$i]['owner_type'];
		$owner_name[$i] = $documentationInfo[$i]['owner_name'];
		$ownername_relationship_name[$i] = $documentationInfo[$i]['ownername_relationship_name'];
		$en_relation[$i] = $documentationInfo[$i]['en_relation'];
		$vehicle_type[$i] = $documentationInfo[$i]['vehicle_type'];
		$vehicle_process[$i] = $documentationInfo[$i]['vehicle_process'];
		$en_Company[$i] = $documentationInfo[$i]['en_Company'];
		$en_Model[$i] = $documentationInfo[$i]['en_Model'];
		$document_name[$i] = $documentationInfo[$i]['document_name'];
		$document_details[$i] = $documentationInfo[$i]['document_details'];
		$document_type[$i] = $documentationInfo[$i]['document_type'];
		$document_holder[$i] = $documentationInfo[$i]['document_holder'];
		$docholder_name[$i] = $documentationInfo[$i]['docholder_name'];
		$docholder_relationship_name[$i] = $documentationInfo[$i]['docholder_relationship_name'];
		$doc_relation[$i] = $documentationInfo[$i]['doc_relation'];
	}
}
////////   Documentation End ////////////
?>

<style>
	.imgshow {
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
		Marudham Capitals - Update
	</div>
</div><br>
<div class="page-header sticky-top" id="navbar" style="display: none;" data-toggle="toggle">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px; margin-top:50px;">
		Customer Name - <?php if (isset($cus_name)) {
							echo $cus_name;
						} ?>
		,&nbsp;&nbsp;Area - <?php if (isset($area_name)) {
								echo $area_name;
							} ?>
		,&nbsp;&nbsp;Sub Area - <?php if (isset($sub_area_name)) {
									echo $sub_area_name;
								} ?>
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_update">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">

	<div class="col-md-12">
		<div class="form-group" style="text-align:center">
			<input type="radio" name="verification_type" id="cus_profile" value="cus_profile"></input><label for='cus_profile'>&nbsp;&nbsp; Customer Profile </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="verification_type" id="documentation" value="documentation"></input><label for='documentation'>&nbsp;&nbsp; Documentation </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<!-- <input type="radio" name="verification_type" id="customer_old" value="customer_old"></input><label for='customer_old'>&nbsp;&nbsp; Old Data </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
		</div>
	</div>

	<!-- Customer Profile form start-->
	<div id="customer_profile" style="display: none;">
		<form id="cus_Profiles" name="cus_Profiles" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="cus_id_load" id="cus_id_load" value="<?php if (isset($idupd)) {
																				echo $idupd;
																			} ?>" />
			<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {
																		echo $req_id;
																	} ?>" />
			<input type="hidden" name="state_upd" id="state_upd" value="<?php if (isset($state)) {
																			echo $state;
																		} ?>" />
			<input type="hidden" name="district_upd" id="district_upd" value="<?php if (isset($district)) {
																					echo $district;
																				} ?>" />
			<input type="hidden" name="taluk_upd" id="taluk_upd" value="<?php if (isset($taluk)) {
																			echo $taluk;
																		} ?>" />
			<input type="hidden" name="area_upd" id="area_upd" value="<?php if (isset($area)) {
																			echo $area;
																		} ?>" />
			<input type="hidden" name="sub_area_upd" id="sub_area_upd" value="<?php if (isset($sub_area)) {
																					echo $sub_area;
																				} ?>" />
			<input type="hidden" name="guarentor_name_upd" id="guarentor_name_upd" value="<?php if (isset($guarentor_name)) {
																								echo $guarentor_name;
																							} ?>" />

			<input type="hidden" name="area_state_upd" id="area_state_upd" value="<?php if (isset($area_confirm_state)) {
																						echo $area_confirm_state;
																					} ?>" />
			<input type="hidden" name="area_district_upd" id="area_district_upd" value="<?php if (isset($area_confirm_district)) {
																							echo $area_confirm_district;
																						} ?>" />
			<input type="hidden" name="area_taluk_upd" id="area_taluk_upd" value="<?php if (isset($area_confirm_taluk)) {
																						echo $area_confirm_taluk;
																					} ?>" />
			<input type="hidden" name="area_confirm_area" id="area_confirm_area" value="<?php if (isset($area_confirm_area)) {
																							echo $area_confirm_area;
																						} ?>" />
			<input type="hidden" name="sub_area_confirm" id="sub_area_confirm" value="<?php if (isset($area_confirm_subarea)) {
																							echo $area_confirm_subarea;
																						} ?>" />

			<input type="hidden" class="form-control" value="<?php if (isset($marital)) echo $marital; ?>" id="marital_upd" name="marital_upd">


			<!-- Row start -->
			<div class="row gutters">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

					<!-- Personal info START -->
					<div class="card">
						<div class="card-header">General Info</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_id">Customer ID</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cus_id)) {
																																echo $cus_id;
																															} ?>' tabindex='1' data-type="adhaar-number" maxlength="14" placeholder="Enter Adhaar Number" readonly>
												<span class="text-danger" style='display:none' id='cusidCheck'>Please Enter Customer ID</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_name">Customer Name</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cus_name)) {
																																	echo $cus_name;
																																} ?>' tabindex='2' placeholder="Enter Customer Name" pattern="[a-zA-Z\s]+">
												<span class="text-danger" style='display:none' id='cusnameCheck'>Please Enter Customer Name</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="dob">Date of Birth</label><span class="required">&nbsp;*</span>
												<input type="date" class="form-control" id="dob" name="dob" value='<?php if (isset($dob)) {
																														echo $dob;
																													} ?>' tabindex='3'>
												<span class="text-danger" style='display:none' id='dobCheck'>Please Select DOB</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="age">Age</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="age" name="age" readonly value='<?php if (isset($age)) {
																																echo $age;
																															} ?>' tabindex='4'>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="gender">Gender&nbsp;<span class="required">&nbsp;*</span></label>
												<select tabindex="5" type="text" class="form-control" id="gender" name="gender">
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
												<label for="state">State</label>&nbsp;<span class="text-danger">*</span>
												<select type="text" class="form-control" id="state" name="state" tabindex="6">
													<option value="SelectState">Select State</option>
													<option value="TamilNadu" <?php if (isset($state) && $state == 'TamilNadu') echo 'selected' ?>>Tamil Nadu</option>
													<option value="Puducherry" <?php if (isset($state) && $state == 'Puducherry') echo 'selected' ?>>Puducherry</option>
												</select>
												<span class="text-danger" style='display:none' id='stateCheck'>Please Select State</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="district">District</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="district1" name="district1">
												<select type="text" class="form-control" id="district" name="district" tabindex='7'>
													<option value="Select District">Select District</option>
												</select>
												<span class="text-danger" style='display:none' id='districtCheck'>Please Select District</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="taluk">Taluk</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" class="form-control" id="taluk1" name="taluk1">
												<select type="text" class="form-control" id="taluk" name="taluk" tabindex="8">
													<option value="Select Taluk">Select Taluk</option>
												</select>
												<span class="text-danger" style='display:none' id='talukCheck'>Please Select Taluk</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="area">Area</label>&nbsp;<span class="text-danger">*</span>
												<select tabindex="9" type="text" class="form-control" id="area" name="area">
													<option value="">Select Area</option>

												</select>
												<span class="text-danger" style='display:none' id='areaCheck'>Please Select Area</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="sub_area">Sub Area</label>&nbsp;<span class="text-danger">*</span>
												<select tabindex="10" type="text" class="form-control" id="sub_area" name="sub_area">
													<option value=''>Select Sub Area</option>
												</select>
												<span class="text-danger" style='display:none' id='subareaCheck'>Please Select Sub Area</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="address">Address</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_address" name="cus_address" value='<?php if (isset($address)) {
																																		echo $address;
																																	} ?>' tabindex='11' placeholder="Enter Address">
												<span class="text-danger" style='display:none' id='addressCheck'>Please Enter Address</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile1">Mobile No 1</label><span class="required">&nbsp;*</span>
												<input type="number" class="form-control" id="mobile1" name="mobile1" onkeypress="if(this.value.length==10) return false;" value='<?php if (isset($mobile1)) {
																																														echo $mobile1;
																																													} ?>' tabindex='12' placeholder="Enter Mobile Number">
												<span class="text-danger" style='display:none' id='mobile1Check'>Please Enter Mobile Number</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile2">Mobile No 2</label>
												<input type="number" class="form-control" id="mobile2" name="mobile2" onkeypress="if(this.value.length==10) return false;" value='<?php if (isset($mobile2)) {
																																														echo $mobile2;
																																													} ?>' tabindex='13' placeholder="Enter Mobile Number">
												<span class="text-danger" style='display:none' id='mobile2Check'>Please Enter Mobile Number</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="father_name">Father Name</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="father_name" name="father_name" value='<?php if (isset($father_name)) {
																																		echo $father_name;
																																	} ?>' tabindex='14' placeholder="Enter Father's Name" pattern="[a-zA-Z\s]+">
												<span class="text-danger" style='display:none' id='fathernameCheck'>Please Enter Father Name</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mother_name">Mother Name</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="mother_name" name="mother_name" value='<?php if (isset($mother_name)) {
																																		echo $mother_name;
																																	} ?>' tabindex='15' placeholder="Enter Mother's Name" pattern="[a-zA-Z\s]+">
												<span class="text-danger" style='display:none' id='mothernameCheck'>Please Enter Mother Name</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="marital">Marital Status<span class="required">&nbsp;*</span></label>
												<select type="text" class="form-control" id="marital" name="marital" tabindex='15'>
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
																																	} ?>' tabindex='16' placeholder="Enter Spouse Name" pattern="[a-zA-Z\s]+">
												<span class="text-danger" style='display:none' id='spousenameCheck'>Please Enter Spouse Name</span>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="occupation_type">Occupation Type<span class="required">&nbsp;*</span></label>
												<select tabindex="17" type="text" class="form-control" id="occupation_type" name="occupation_type">
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
																																	} ?>' tabindex='18' placeholder="Enter Occupation" pattern="[a-zA-Z\s]+">
												<span class="text-danger" style='display:none' id='occupationCheck'>Please Enter Occupation</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
									<div class="col-xl-8 col-lg-10 col-md-6 ">
										<div class="form-group" style="margin-left: 30px;">
											<label style="margin-left: -20px;">Photo</label><span class="required">&nbsp;*</span><br>
											<input type="hidden" class="form-control" id="cus_image" name="cus_image" value='<?php if (isset($pic)) {
																																	echo $pic;
																																} ?>'>
											<img id='imgshow' class="imgshow" src='img/avatar.png' /><br>
											<input type="file" onchange="compressImage(this,200)" class="form-control" id="pic" name="pic" tabindex='20' value='<?php if (isset($pic)) {
																																											echo $pic;
																																										} ?>'>
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
							<button type="button" class="btn btn-primary" id="add_group" name="add_group" data-toggle="modal" data-target=".addGroup" style="padding: 5px 35px; float: right;" tabindex='21'><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='family_infoCheck'>Please Fill Family Info </span>
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
						<div class="card-header">Guarentor Info<span class="required">&nbsp;*</span><span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="guarentor_name"> Guarentor Name </label><span class="required">&nbsp;*</span>
												<select type="text" class="form-control" id="guarentor_name" name="guarentor_name" tabindex="22">
													<option> Select Guarantor </option>
												</select>
												<span class="text-danger" style='display:none' id='guarentor_nameCheck'>Please Choose Guarentor Name</span>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="guarentor_relationship"> Guarentor Relationship </label>
												<input type="text" class="form-control" id="guarentor_relationship" name="guarentor_relationship" tabindex="23" value='<?php if (isset($guarentor_relation)) {
																																											echo $guarentor_relation;
																																										} ?>' readonly>
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
											<img id='imgshows' class="imgshow" src='img/avatar.png' />
											<input type="file" onchange="compressImage(this,200)" class="form-control" id="guarentorpic" name="guarentorpic" tabindex="24" value="<?php if (isset($guarentor_photo)) {
																																															echo $guarentor_photo;
																																														} ?>">
											<span class="text-danger" style='display:none' id='guarentorpicCheck'>Please Choose Guarentor Image</span>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Guarentor END -->
					<!-- Residential  Info START -->
					<div class="card">
						<div class="card-header"> Residential Info <span style="font-weight:bold" class=""></span></div>
						<span class="text-danger" style='display:none' id='res_infoCheck'>Please Fill Residential Info </span>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_res_type"> Residential Type </label>
										<select type="text" class="form-control" name="cus_res_type" id="cus_res_type" tabindex="25">
											<option> Select Residential Type </option>
											<option value="0" <?php if (isset($residential_type) and $residential_type == '0') echo 'selected'; ?>> Own </option>
											<option value="1" <?php if (isset($residential_type) and $residential_type == '1') echo 'selected'; ?>> Rental </option>
											<option value="2" <?php if (isset($residential_type) and $residential_type == '2') echo 'selected'; ?>> Lease </option>
											<option value="3" <?php if (isset($residential_type) and $residential_type == '3') echo 'selected'; ?>> Quarters </option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_res_details"> Resident Details </label>
										<input type="text" class="form-control" name="cus_res_details" id="cus_res_details" placeholder="Enter Resident Details" value="<?php if (isset($residential_details)) {
																																											echo $residential_details;
																																										} ?>" tabindex="26">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_res_address"> Address </label>
										<input type="text" class="form-control" name="cus_res_address" id="cus_res_address" placeholder="Enter Address" value="<?php if (isset($residential_address)) {
																																									echo $residential_address;
																																								} ?>" tabindex="27">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_res_native"> Native Address </label>
										<input type="text" class="form-control" name="cus_res_native" id="cus_res_native" placeholder="Enter Native Address" value="<?php if (isset($residential_native_address)) {
																																										echo $residential_native_address;
																																									} ?>" tabindex="28">
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Residential  Info END -->

					<!-- Occupation info START -->
					<div class="card">
						<div class="card-header"> Occupation info <span style="font-weight:bold" class=""></span></div>
						<span class="text-danger" style='display:none' id='occ_infoCheck'>Please Fill Occupation Info </span>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_type"> Occupation Type </label>
										<select type="text" class="form-control" name="cus_occ_type" id="cus_occ_type" tabindex="29">
											<option value="">Select Occupation Type</option>
											<option value="1" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '1') echo 'selected'; ?>>Govt Job</option>
											<option value="2" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '2') echo 'selected'; ?>>Pvt Job</option>
											<option value="3" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '3') echo 'selected'; ?>>Business</option>
											<option value="4" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '4') echo 'selected'; ?>>Self Employed</option>
											<option value="5" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '5') echo 'selected'; ?>>Daily wages</option>
											<option value="6" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '6') echo 'selected'; ?>>Agriculture</option>
											<option value="7" <?php if (isset($cr_occupation_type) and $cr_occupation_type == '7') echo 'selected'; ?>>Others</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_detail"> Occupation Detail </label>
										<input type="text" class="form-control" name="cus_occ_detail" id="cus_occ_detail" placeholder="Enter Occupation Detail" onkeydown="return /[a-z ]/i.test(event.key)" value="<?php if (isset($occupation_details)) {
																																																						echo $occupation_details;
																																																					} ?>" tabindex="30">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_income"> Income </label>
										<input type="number" class="form-control" name="cus_occ_income" id="cus_occ_income" placeholder="Enter Income" value="<?php if (isset($occupation_income)) {
																																									echo $occupation_income;
																																								} ?>" tabindex="31">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_address"> Address </label>
										<input type="text" class="form-control" name="cus_occ_address" id="cus_occ_address" placeholder="Enter Address" value="<?php if (isset($occupation_address)) {
																																									echo $occupation_address;
																																								} ?>" tabindex="32">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_dow"> Duration of Working </label>
										<input type="text" class="form-control" name="cus_occ_dow" id="cus_occ_dow" placeholder="Enter Duration of Working" value="<?php if (isset($dow)) {
																																										echo $dow;
																																									} ?>" tabindex="33">
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_occ_abt"> About Occupation </label>
										<input type="text" class="form-control" name="cus_occ_abt" id="cus_occ_abt" placeholder="Enter About Occupation" value="<?php if (isset($abt_occ)) {
																																									echo $abt_occ;
																																								} ?>" tabindex="34">
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
										<label for="area_cnfrm"> Area confirm </label><span class="required">&nbsp;*</span>
										<select type="text" class="form-control" name="area_cnfrm" id="area_cnfrm" tabindex="35">
											<option value="">Select Area Type</option>
											<option value="0" <?php if (isset($area_confirm_type) and $area_confirm_type == '0') echo 'selected'; ?>> Residential Area </option>
											<option value="1" <?php if (isset($area_confirm_type) and $area_confirm_type == '1') echo 'selected'; ?>> Occupation Area </option>
										</select>
										<span class="text-danger" style='display:none' id='areacnfrmCheck'>Please Select Confirm Area</span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
									<div class="form-group">
										<label for="area_state">State</label>&nbsp;<span class="text-danger">*</span>
										<select type="text" class="form-control" id="area_state" name="area_state" tabindex="36">
											<option value="SelectState">Select State</option>
											<option value="TamilNadu" <?php if (isset($area_confirm_state) and $area_confirm_state == 'TamilNadu') echo 'selected'; ?>>Tamil Nadu</option>
											<option value="Puducherry" <?php if (isset($area_confirm_state) and $area_confirm_state == 'Puducherry') echo 'selected'; ?>>Puducherry</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="area_district">District</label>&nbsp;<span class="text-danger">*</span>
										<input type="hidden" class="form-control" id="area_district1" name="area_district1">
										<select type="text" class="form-control" id="area_district" name="area_district" tabindex='37'>
											<option value="Select District">Select District</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="area_taluk">Taluk</label>&nbsp;<span class="text-danger">*</span>
										<input type="hidden" class="form-control" id="area_taluk1" name="area_taluk1">
										<select type="text" class="form-control" id="area_taluk" name="area_taluk" tabindex="38">
											<option value="Select Taluk">Select Taluk</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="area_confirm">Area</label>&nbsp;<span class="text-danger">*</span>
										<select tabindex="39" type="text" class="form-control" id="area_confirm" name="area_confirm">
											<option value="">Select Area</option>
										</select>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="area_sub_area">Sub Area</label>&nbsp;<span class="text-danger">*</span>
										<select tabindex="40" type="text" class="form-control" id="area_sub_area" name="area_sub_area">
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
										<button class="btn btn-primary" id="getlatlong" name="getlatlong" style="padding: 5px 35px;"><span class="icon-my_location"></span></button>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="area_group">Group</label>
										<input type="text" class="form-control" name="area_group" id="area_group" value="<?php if (isset($area_group)) {
																																echo $area_group;
																															} ?>" readonly tabindex="41">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="area_line">Line</label>
										<input type="text" class="form-control" name="area_line" id="area_line" value="<?php if (isset($area_line)) {
																															echo $area_line;
																														} ?>" readonly tabindex="42">
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Area Confirm END -->

					<!-- Property info START -->
					<div class="card">
						<div class="card-header"> Property info <span style="font-weight:bold" class=""></span>
							<button type="button" class="btn btn-primary" id="property_add" name="property_add" data-toggle="modal" data-target=".addproperty" style="padding: 5px 35px;  float: right; " onclick="propertyHolder()" tabindex='43'><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='property_infoCheck'>Please Fill Property Info </span>
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
							<button type="button" class="btn btn-primary" id="bank_add" name="bank_add" data-toggle="modal" data-target=".addbank" style="padding: 5px 35px;  float: right;" tabindex='44'><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='bank_infoCheck'>Please Fill Bank Info </span>
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
						<div class="card-header"> KYC info<span class="required">&nbsp;*</span><span style="font-weight:bold" class=""></span>
							<button type="button" class="btn btn-primary" id="kyc_add" name="kyc_add" data-toggle="modal" data-target=".addkyc" style="padding: 5px 35px; float: right; " tabindex='45'><span class="icon-add"></span></button>
						</div>
						<span class="text-danger" style='display:none' id='kyc_infoCheck'>Please Fill KYC Info </span>
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
										<label for="cus_how_know"> How to Know </label> <span class="required">*</span>
										<select type="text" class="form-control" name="cus_how_know" id="cus_how_know" tabindex="46">
											<option value=""> Select How to Know </option>
											<option value="0" <?php if (isset($how_to_know) and $how_to_know == '0') echo 'selected'; ?>> Customer Reference </option>
											<option value="1" <?php if (isset($how_to_know) and $how_to_know == '1') echo 'selected'; ?>> Advertisement </option>
											<option value="2" <?php if (isset($how_to_know) and $how_to_know == '2') echo 'selected'; ?>> Promotion activity </option>
											<option value="3" <?php if (isset($how_to_know) and $how_to_know == '3') echo 'selected'; ?>> Agent Reference </option>
											<option value="4" <?php if (isset($how_to_know) and $how_to_know == '4') echo 'selected'; ?>> Staff Reference </option>
											<option value="5" <?php if (isset($how_to_know) and $how_to_know == '5') echo 'selected'; ?>> Other Reference </option>
										</select>
										<span class="text-danger" style='display:none' id='howToKnowCheck'>Please Select How To Know </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_loan_count"> Loan Counts </label>
										<input type="text" class="form-control" name="cus_loan_count" id="cus_loan_count" value="<?php if (isset($loan_count)) {
																																		echo $loan_count;
																																	} ?>" readonly tabindex="47">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_frst_loanDate"> First Loan Date </label>
										<input type="text" class="form-control" name="cus_frst_loanDate" id="cus_frst_loanDate" value="<?php if (isset($first_loan_date)) {
																																			echo $first_loan_date;
																																		} ?>" readonly tabindex="48">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_travel_cmpy"> Travel with Company </label>
										<input type="text" class="form-control" name="cus_travel_cmpy" id="cus_travel_cmpy" value="<?php if (isset($travel_with_company)) {
																																		echo $travel_with_company;
																																	} ?>" readonly tabindex="49">
									</div>
								</div>

							</div>

							<hr>

							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_monthly_income"> Monthly Income </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_monthly_income" id="cus_monthly_income" placeholder="Enter Monthly Income" value="<?php if (isset($monthly_income)) {
																																													echo $monthly_income;
																																												} ?>" tabindex="50">
										<span class="text-danger" style='display:none' id='monthlyIncomeCheck'>Please Enter Monthly Income </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_other_income"> Other Income </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_other_income" id="cus_other_income" placeholder="Enter Other Income" value="<?php if (isset($other_income)) {
																																											echo $other_income;
																																										} ?>" tabindex="51">
										<span class="text-danger" style='display:none' id='otherIncomeCheck'>Please Enter Other Income </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_support_income"> Support Income </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_support_income" id="cus_support_income" placeholder="Enter Support Income" value="<?php if (isset($support_income)) {
																																													echo $support_income;
																																												} ?>" tabindex="52">
										<span class="text-danger" style='display:none' id='supportIncomeCheck'>Please Enter Support Income </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_Commitment"> Commitment </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_Commitment" id="cus_Commitment" placeholder="Enter Commitment" value="<?php if (isset($commitment)) {
																																										echo $commitment;
																																									} ?>" tabindex="53">
										<span class="text-danger" style='display:none' id='commitmentCheck'>Please Enter Commitment </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_monDue_capacity"> Monthly Due Capacity </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_monDue_capacity" id="cus_monDue_capacity" placeholder="Enter Monthly Due Capacity" value="<?php if (isset($monthly_due_capacity)) {
																																															echo $monthly_due_capacity;
																																														} ?>" tabindex="54">
										<span class="text-danger" style='display:none' id='monthlyDueCapacityCheck'> Please Enter Monthly Due Capacity </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="cus_loan_limit"> Loan Limit </label> <span class="required">*</span>
										<input type="number" class="form-control" name="cus_loan_limit" id="cus_loan_limit" placeholder="Enter Loan Limit" value="<?php if (isset($loan_limit)) {
																																										echo $loan_limit;
																																									} ?>" tabindex="55">
										<span class="text-danger" style='display:none' id='loanLimitCheck'>Please Enter Loan Limit </span>
									</div>
								</div>

							</div>

							<hr>
							<div class="row">
								<div class="col-12">
									<button type="button" class="btn btn-primary" id="add_cus_label" name="add_cus_label" data-toggle="modal" data-target=".addCusLabel" style="padding: 5px 35px; float: right;" tabindex="56"><span class="icon-add"></span></button>
								</div>
							</div> <br>

							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="form-group table-responsive" id="feedbackListTable">
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
								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="about_cus"> About Customer </label> <span class="required">*</span>
										<textarea class="form-control" name="about_cus" id="about_cus" tabindex="57"><?php if (isset($about_customer)) {
																															echo $about_customer;
																														} ?></textarea>
										<span class="text-danger" style='display:none' id='aboutcusCheck'> Please Enter About Customer </span>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- ///////////////////////////////////////////////  Customer Summary  END /////////////////////////////////////////////////////////// -->


					<div class="col-md-12 ">
						<div class="text-right">
							<button type="submit" name="submit_update_cus_profile" id="submit_update_cus_profile" class="btn btn-primary" value="Submit" tabindex="58"><span class="icon-check"></span>&nbsp;Submit</button>
							<button type="reset" class="btn btn-outline-secondary" tabindex="59">Clear</button>
						</div>
					</div>

				</div>
			</div>
		</form>
	</div>
	<!-- Customer Form End -->



	<!--  ///////////////////////////////////////////////////////////////// Documentation  start ////////////////////////////////////////////////////////// -->
	<div id="cus_document" style="display: none;">
		<!-- <form id="cus_doc" name="cus_doc" action="" method="post" enctype="multipart/form-data"> -->
		<input type="hidden" name="req_id_doc" id="req_id_doc" value="">
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />

		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

				<div class="card documentation-card">
					<div class="card-header">Documentation Info</div>
					<div class="card-body">
						<div class="row">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_id_doc">Customer ID </label> <span class="required">* </span>
									<input type="text" class="form-control" id="cus_id_doc" name="cus_id_doc" value='<?php if (isset($cus_id)) echo $cus_id; ?>' readonly tabindex="1">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="Customer_name"> Customer Name </label> <span class="required"> * </span>
									<input type="text" class="form-control" id="Customer_name" name="Customer_name" value='<?php if (isset($cus_name)) echo $cus_name; ?>' readonly tabindex="2">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="doc_area"> Area </label> <span class="required"> * </span>
									<input tabindex="3" type="text" class="form-control" id="doc_area" name="doc_area" value="<?php if (isset($area_name)) echo $area_name; ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="doc_Sub_Area"> Sub Area </label> <span class="required"> * </span>
									<input type="text" class="form-control" id="doc_Sub_Area" name="doc_Sub_Area" value='<?php if (isset($sub_area_name)) echo $sub_area_name; ?>' readonly tabindex="4">
								</div>
							</div>


						</div>
					</div>
				</div>
				<!-- Documentations Info  End-->

				<!-- Document History START -->
				<div class="card documentation-card">
					<div class="card-header"> Documents History </div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="docHistoryDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Document History END -->

				<!-- Signed Doc Info START -->
				<div class="card edit-document-card" style='display:none'>
					<div class="card-header"> Signed Doc Info
						<button type="button" class="btn btn-primary" id="add_sign_doc" name="add_sign_doc" data-toggle="modal" data-target=".addSignDoc" style="padding: 5px 35px;  float: right;" tabindex="6"><span class="icon-add"></span></button>
					</div>
					<span class="text-danger" style='display:none' id='signed_infoCheck'>Please Fill Signed Doc Info </span>
					<div class="card-body">

						<div class="row">

							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="signDocResetDiv">

								</div>
							</div>

						</div>

					</div>
				</div>
				<!-- Signed Doc Info END -->

				<!-- Cheque Info START -->
				<div class="card edit-document-card" style='display:none'>
					<div class="card-header"> Cheque Info
						<button type="button" class="btn btn-primary" id="add_Cheque" name="add_Cheque" data-toggle="modal" data-target=".addCheque" style="padding: 5px 35px;  float: right;" tabindex="7"><span class="icon-add"></span></button>
					</div>
					<span class="text-danger" style='display:none' id='Cheque_infoCheck'>Please Fill Cheque Info </span>
					<div class="card-body">

						<div class="row">

							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="chequeResetDiv">

								</div>
							</div>

						</div>

					</div>
				</div>
				<!-- Cheque Info END -->

				<!-- Mortgage Info START-->
				<form id="mort_form" name="mort_form" action="" method="post" enctype="multipart/form-data">
					<div class="card edit-document-card" style='display:none'>
						<div class="card-header"> Mortgage Info </div>
						<div class="card-body" id="mortdivform">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="mortgage_process"> Mortgage Process</label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="mortgage_process" name="mortgage_process" tabindex="8">
											<option value=""> Select Mortgage Process </option>
											<option value="0"> YES </option>
											<option value="1"> NO </option>
										</select>
										<span class="text-danger" id="mortgageprocessCheck" style='display:none'> Select Mortgage Process </span>
									</div>
								</div>
							</div>

							<div id="mortgage_div" style="display:none">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label> Property Holder type </label> <span class="required">&nbsp;*</span>
											<select type="text" class="form-control" id="Propertyholder_type" name="Propertyholder_type" tabindex="9">
												<option value=""> Select Holder type </option>
												<option value="0"> Customer </option>
												<option value="1"> Guarantor </option>
												<option value="2"> Family Members </option>
											</select>
											<span class="text-danger" id="propertyholdertypeCheck" style='display:none'> Select Property Holder type </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label> Property Holder Name </label>
											<input type="text" class="form-control" id="Propertyholder_name" name="Propertyholder_name" value="" readonly tabindex="10">

											<select type="text" class="form-control" id="Propertyholder_relationship_name" name="Propertyholder_relationship_name" style="display: none;" tabindex='10'>
												<option value=""> Select Relationship </option>
											</select>
											<span class="text-danger" id="propertyholdernameCheck" style='display:none'> Select Property Holder Name </span>
										</div>
									</div>


									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_property_relation"> Relationship </label>
											<input type="text" class="form-control" id="doc_property_relation" name="doc_property_relation" value="" readonly tabindex="11">
											<span class="text-danger" id="docproprelationCheck" style='display:none'> Enter Property Holder Relation </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_property_pype"> Property Type </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_pype" name="doc_property_pype" placeholder="Enter Property Type" value="" tabindex="12">
											<span class="text-danger" id="docpropertytypeCheck" style='display:none'> Enter Property Type</span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_property_measurement"> Property Measurement </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_measurement" name="doc_property_measurement" placeholder="Enter Property Measurement" value="" tabindex="13">
											<span class="text-danger" id="docpropertymeasureCheck" style='display:none'> Enter Property Measurement </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_property_location"> Property Location </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_location" name="doc_property_location" placeholder="Enter Property Location" value="" tabindex="14">
											<span class="text-danger" id="docpropertylocCheck" style='display:none'> Enter Property Location </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_property_value"> Property Value </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="doc_property_value" name="doc_property_value" placeholder="Enter Property Value" value="" tabindex="15">
											<span class="text-danger" id="docpropertyvalueCheck" style='display:none'> Enter Property Value</span>
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="mortgage_name"> Mortgage Name </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_name" name="mortgage_name" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Mortgage Name" value="" tabindex="16">
											<span class="text-danger" id="mortgagenameCheck" style='display:none'> Enter Mortgage Name </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="mortgage_dsgn"> Designation </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_dsgn" name="mortgage_dsgn" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Designation" value="" tabindex="17">
											<span class="text-danger" id="mortgagedsgnCheck" style='display:none'> Enter Designation </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="mortgage_nuumber"> Mortgage Number </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_nuumber" name="mortgage_nuumber" placeholder="Enter Mortgage Number" value="" tabindex="18">
											<span class="text-danger" id="mortgagenumCheck" style='display:none'> Enter Mortgage Number </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="reg_office"> Reg Office </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="reg_office" name="reg_office" placeholder="Enter Reg Office" value="" tabindex="19">
											<span class="text-danger" id="regofficeCheck" style='display:none'> Enter Reg Office </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="mortgage_value"> Mortgage Value </label> <span class="required">&nbsp;*</span>
											<input type="text" class="form-control" id="mortgage_value" name="mortgage_value" placeholder="Enter Mortgage Value" value="" tabindex="20">
											<span class="text-danger" id="mortgagevalueCheck" style='display:none'> Enter Mortgage Value </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="mortgage_document"> Mortgage Document </label> <span class="required">&nbsp;*</span>
											<select type="text" class="form-control" id="mortgage_document" name="mortgage_document" tabindex="21">
												<option value=""> Select Mortgage Document </option>
												<option value="0"> YES </option>
												<option value="1"> NO </option>
											</select>
											<span class="text-danger" id="mortgagedocCheck" style='display:none'> Select Mortgage Document </span>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="mort_doc_upd" style="display: none;">
										<div class="form-group">
											<label for="mortgage_document_upd"> Mortgage Document Uploads </label> <span class="required">&nbsp;*</span>
											<input type="file" onchange="compressImage(this,400)" class="form-control" id="mortgage_document_upd" name="mortgage_document_upd" tabindex="22">
											<input type="hidden" id="mortgage_doc_upd" name="mortgage_doc_upd" value="">
											<span class="text-danger" id="mortgagedocUpdCheck" style='display:none'> Upload Mortgage Document </span>
										</div>
									</div>


									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="pendingchk"> Pending </label> <span class="required">&nbsp;*</span>
											<label class="switch">
												<input type="checkbox" value="YES" id="pendingchk" name="pendingchk" checked tabindex="23">
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>

							</div>

							<div class="col-md-12 ">
								<div class="text-right">
									<button type="button" name="update_mortgage" id="update_mortgage" class="btn btn-primary" value="Submit" tabindex="23"><span class="icon-check"></span>&nbsp;Submit</button>
								</div>
							</div>

						</div>
					</div>
				</form>
				<!-- Mortgage Info  End-->

				<!-- Endorsement Info START-->
				<form id="end_form" name="end_form" action="" method="post" enctype="multipart/form-data">
					<div class="card edit-document-card" style='display:none'>
						<div class="card-header"> Endorsement Info </div>
						<div class="card-body">
							<div class="row">

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="endorsement_process"> Endorsement Process</label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="endorsement_process" name="endorsement_process" tabindex="24">
											<option value=""> Select Endorsement Process </option>
											<option value="0"> YES </option>
											<option value="1"> NO </option>
										</select>
										<span class="text-danger" id="endorsementprocessCheck" style='display:none'> Select Endorsement Process </span>
									</div>
								</div>
							</div>

							<div class="row" id="end_process_div" style='display:none'>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label> Owner Type </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="owner_type" name="owner_type" tabindex="25">
											<option value=""> Select Holder type </option>
											<option value="0"> Customer </option>
											<option value="1"> Guarantor </option>
											<option value="2"> Family Members </option>
										</select>
										<span class="text-danger" id="ownertypeCheck" style='display:none'> Select Owner type </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label> Owner Name </label>
										<input type="text" class="form-control" id="owner_name" name="owner_name" value="" readonly tabindex='26'>

										<select type="text" class="form-control" id="ownername_relationship_name" name="ownername_relationship_name" style="display: none;" tabindex="26">
											<option value=""> Select Relationship </option>
										</select>
										<span class="text-danger" id="ownernameCheck" style='display:none'> Select Owner type </span>
									</div>
								</div>


								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="en_relation"> Relationship </label>
										<input type="text" class="form-control" id="en_relation" name="en_relation" value="" readonly tabindex="27">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="vehicle_type"> Vehicle type </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="vehicle_type" name="vehicle_type" tabindex="28">
											<option value=""> Select Vehicle type </option>
											<option value="0"> 2 Wheeler </option>
											<option value="1"> 4 Wheeler </option>
										</select>
										<span class="text-danger" id="vehicletypeCheck" style='display:none'> Enter Vehicle Type </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="vehicle_process"> Vehicle Process </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="vehicle_process" name="vehicle_process" tabindex="29">
											<option value=""> Select Vehicle Process </option>
											<option value="0"> New </option>
											<option value="1"> Old </option>
										</select>
										<span class="text-danger" id="vehicleprocessCheck" style='display:none'> Enter Vehicle Process </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="en_Company"> Company </label> <span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="en_Company" name="en_Company" placeholder="Enter Company" value="" tabindex="30">
										<span class="text-danger" id="enCompanyCheck" style='display:none'> Enter Company </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="en_Model"> Model </label> <span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="en_Model" name="en_Model" placeholder="Enter Model" value="" tabindex="31">
										<span class="text-danger" id="enModelCheck" style='display:none'> Enter Model </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="vehicle_reg_no"> Vehicle Reg No. </label>
										<input type="text" class="form-control" id="vehicle_reg_no" name="vehicle_reg_no" placeholder="Enter Vehicle No" value="" tabindex="32">
										<span class="text-danger" id="vehicle_reg_noCheck" style='display:none'> Enter Vehicle No </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="endorsement_name"> Endorsement name </label> <span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="endorsement_name" name="endorsement_name" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Endorsement Name" value="" tabindex="33">
										<span class="text-danger" id="endorsementnameCheck" style='display:none'> Enter Endorsement Name</span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="en_Key"> Key </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="en_Key" name="en_Key" tabindex="36">
											<option value=""> Select Key </option>
											<option value="0"> YES </option>
											<option value="1"> NO </option>
										</select>
										<span class="text-danger" id="enKeyCheck" style='display:none'> Select Key </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="en_RC"> RC </label> <span class="required">&nbsp;*</span>
										<select type="text" class="form-control" id="en_RC" name="en_RC" tabindex="37">
											<option value=""> Select RC </option>
											<option value="0"> YES </option>
											<option value="1"> NO </option>
										</select>
										<span class="text-danger" id="enRCCheck" style='display:none'> Select RC </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="end_doc_upd" style="display: none;">
									<div class="form-group">
										<label for="RC_document_upd"> RC Uploads </label> <span class="required">&nbsp;*</span>
										<input type="file" onchange="compressImage(this,400)" class="form-control" id="RC_document_upd" name="Rc_document_upd" tabindex="38">
										<input type="hidden" id="rc_doc_upd" name="rc_doc_upd" value="">
										<span class="text-danger" id="rcdocUpdCheck" style='display:none'> Upload RC </span>
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="endorsependingchk"> Pending </label> <span class="required">&nbsp;*</span>
										<label class="switch">
											<input type="checkbox" value="YES" id="endorsependingchk" name="endorsependingchk" checked tabindex="39">
											<span class="slider round"></span>
										</label>
									</div>
								</div>

							</div>

							<div class="col-md-12 ">
								<div class="text-right">
									<button type="button" name="update_endorsement" id="update_endorsement" class="btn btn-primary" value="Submit" tabindex="40"><span class="icon-check"></span>&nbsp;Submit</button>
								</div>
							</div>


						</div>
					</div>
				</form>
				<!-- Endorsement Info  End-->
				<!-- Gold Info Start -->
				<div class="card edit-document-card" style='display:none'>
					<div class="card-header"> Gold Info
						<button type="button" class="btn btn-primary" id="add_gold" name="add_gold" data-toggle="modal" data-target=".addGold" style="padding: 5px 35px;  float: right;" tabindex='41'><span class="icon-add"></span></button>
					</div>
					<span class="text-danger" style='display:none' id='Gold_infoCheck'>Please Fill Gold Info </span>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="goldResetDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Gold Info End -->
				<!-- Documents Info START-->

				<div class="card edit-document-card" style='display:none'>
					<div class="card-header"> Documents Info
						<button type="button" class="btn btn-primary" id="add_document" name="add_document" data-toggle="modal" data-target=".addDocument" style="padding: 5px 35px;  float: right;" tabindex="42"><span class="icon-add"></span></button>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="documentResetDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Document Info End -->

				<!-- Fingerprint Info start-->
				<div class="card edit-document-card">
					<div class="card-header"> Fingerprint Info </div><span class="text-danger fingerSpan" style="margin-left:25px;display: none;">Please Scan Customer Fingerprint</span>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive fingerprintTable">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Fingerprint Info End-->

			</div>
		</div> <!-- Row End -->
		<!-- </form> -->
	</div>

	<!--  ///////////////////////////////////////////////////////////////// Documentation  End ////////////////////////////////////////////////////////// -->

	<!--  ///////////////////////////////////////////////////////////////// Customer Old Data Start ////////////////////////////////////////////////////////// -->
	<div id="customer_old_div" style="display: none;">
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card documentation-card">
					<div class="card-header">Old Data
						<button type="button" class="btn btn-primary" id="add_cus_old_data" name="add_cus_old_data" data-toggle="modal" data-target=".add_cus_old" style="padding: 5px 35px; float: right;" tabindex='43'><span class="icon-add"></span></button>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="oldCusDataDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--  ///////////////////////////////////////////////////////////////// Customer Old Data End ////////////////////////////////////////////////////////// -->

</div>


<!-- Add Family Members Modal -->
<div class="modal fade addGroup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="">Add Family Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeFamModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="FamInsertNotOk" class="unsuccessalert"> Name Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FamInsertOk" class="successalert">Family Info Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="famUpdateok" class="successalert">Family Info Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="NotOk" class="unsuccessalert">Please Retry!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FamDeleteNotOk" class="unsuccessalert"> Please Retry to Delete Family Info!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FamDeleteOk" class="unsuccessalert"> Family Info Has been Deleted!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />

				<div class="row" id="editFam">

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

						<div class="form-group">
							<label class="label"> Name </label>&nbsp;<span class="text-danger">*</span>
							<input type="text" class="form-control" name="famname" id="famname" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Name" tabindex='1'>
							<span class="text-danger" id="famnameCheck" style='display:none'>Enter Name</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="relationship"> Relationship </label> &nbsp;<span class="text-danger">*</span>
							<select type="text" class="form-control" id="relationship" name="relationship" tabindex='1'>
								<option value=""> Select Relationship </option>
								<option value="Father"> Father </option>
								<option value="Mother"> Mother </option>
								<option value="Spouse"> Spouse </option>
								<option value="Son"> Son </option>
								<option value="Daughter"> Daughter </option>
								<option value="Brother"> Brother </option>
								<option value="Sister"> Sister </option>
								<option value="Other"> Other </option>
							</select>
							<span class="text-danger" id="famrelationCheck" style='display:none'>Select Relationship</span>
						</div>
					</div>

					<div id="remark" style="display: none" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="other_remark"> Remark</label>
							<input type="text" class="form-control" name="other_remark" id="other_remark" placeholder="Enter Remark" tabindex='1'>
							<span class="text-danger" id="famremarkCheck" style='display:none'>Enter Remark</span>
						</div>
					</div>

					<div id="address" style="display: none" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="other_address"> Address </label>
							<input type="text" class="form-control" name="other_address" id="other_address" placeholder="Enter Address" tabindex='1'>
							<span class="text-danger" id="famaddressCheck" style='display:none'>Enter Address</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label"> Aadhar No </label>&nbsp;<span class="text-danger">*</span>
							<input type="text" class="form-control" name="relation_aadhar" id="relation_aadhar" data-type="adhaar-number" maxlength="14" placeholder="Enter Aadhar No" tabindex='1'>
							<span class="text-danger" id="famaadharCheck" style='display:none'>Enter Aadhar Number</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label"> Mobile No </label>&nbsp;<span class="text-danger">*</span>
							<input type="number" class="form-control" name="relation_Mobile" id="relation_Mobile" maxlength="10" onkeypress="if(this.value.length==10) return false;" placeholder="Mobile Number" tabindex='1'>
							<span class="text-danger" id="fammobileCheck" style='display:none'>Enter Mobile Number</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label"> Age </label>
							<input type="number" class="form-control" name="relation_age" id="relation_age" placeholder="Enter Age" tabindex='1'>
							<span class="text-danger" id="famageCheck" style='display:none'>Enter Age</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label"> Occupation </label>
							<input type="text" class="form-control" name="relation_Occupation" id="relation_Occupation" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Occupation" tabindex='1'>
							<span class="text-danger" id="famoccCheck" style='display:none'>Enter Occupation</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label"> Income </label>
							<input type="number" class="form-control" name="relation_Income" id="relation_Income" placeholder="Enter Income" tabindex='1'>
							<span class="text-danger" id="famincomeCheck" style='display:none'>Enter Income</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label"> Blood Group </label>&nbsp;
							<input type="text" class="form-control" name="relation_Blood" id="relation_Blood" placeholder="Enter Blood Group" tabindex='1'>
						</div>
					</div>


					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<input type="hidden" name="famID" id="famID">
						<button type="button" tabindex="1" name="submitFamInfoBtn" id="submitFamInfoBtn" class="btn btn-primary" style="margin-top: 19px;">Submit</button>
					</div>

				</div>
				</br>

				<div id="updatedFamTable" class=" table-responsive">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="50">S.No</th>
								<th>Name</th>
								<th>Relationship</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeFamModal()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add Family Members Modal -->

<!-- Add Property Modal  START -->
<div class="modal fade addproperty" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="">Add Property Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetPropertyinfoList()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="prptyInsertOk" class="successalert"> Property Info Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="prptyUpdateok" class="successalert"> Property Info Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="prptyNotOk" class="unsuccessalert"> Something Went Wrong!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="prptyDeleteOk" class="successalert"> Property Info Deleted!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="prptyDeleteNotOk" class="unsuccessalert"> Property Info not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />

				<div class="row">

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label> Property Type </label><span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="property_typ" name="property_typ" placeholder="Enter Property Type" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='1'>
							<span class="text-danger" id="prtytypeCheck" style='display:none'>Enter Property Type</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="property_measurement"> Property Measurement </label><span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="property_measurement" name="property_measurement" placeholder="Enter Property Measurement" tabindex='1'>
							<span class="text-danger" id="prtymeasureCheck" style='display:none'>Enter Property Measurement</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="property_value"> Property Value </label><span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="property_value" name="property_value" placeholder="Enter Property Value" tabindex='1'>
							<span class="text-danger" id="prtyvalCheck" style='display:none'>Enter Property Value</span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="property_holder"> Property Holder </label><span class="required">&nbsp;*</span>
							<select type="number" class="form-control" id="property_holder" name="property_holder" tabindex='1'>
								<option> Select Property Holder </option>
							</select>
							<span class="text-danger" id="prtyholdCheck" style='display:none'>Select Property Holder </span>
						</div>
					</div>

					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<input type="hidden" name="propertyID" id="propertyID">
						<button type="button" tabindex="1" name="propertyInfoBtn" id="propertyInfoBtn" class="btn btn-primary" style="margin-top: 19px;">Submit</button>
					</div>

				</div>
				</br>

				<div id="propertyTable" class="table-responsive">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="50"> S.No </th>
								<th> Property Type </th>
								<!-- <th> Property Measurement </th> -->
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
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetPropertyinfoList()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add Property Modal -->

<!-- Add Bank info Modal  START -->
<div class="modal fade addbank" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="">Add Bank Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetbankinfoList()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="bankInsertOk" class="successalert"> Bank Info Added Successfully
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="bankUpdateok" class="successalert"> Bank Info Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="bankNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="bankDeleteOk" class="unsuccessalert"> Bank Info Deleted
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="bankDeleteNotOk" class="unsuccessalert"> Bank Info not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />

				<div class="row">

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="bank_name"> Bank Name </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='1'>
							<span class="text-danger" id="bankNameCheck" style='display:none'> Enter Bank Name </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="branch_name"> Branch Name </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='1'>
							<span class="text-danger" id="branchCheck" style='display:none'> Enter Branch Name </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="account_holder_name"> Account Holder Name </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="account_holder_name" name="account_holder_name" placeholder="Enter Account Holder Name" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='1'>
							<span class="text-danger" id="accholdCheck" style='display:none'> Enter Account Holder Name </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="account_number"> Account Number </label> <span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="account_number" name="account_number" placeholder="Enter Account Number" tabindex='1'>
							<span class="text-danger" id="accnoCheck" style='display:none'> Enter Account Number </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="Ifsc_code"> IFSC Code </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="Ifsc_code" name="Ifsc_code" placeholder="Enter IFSC Code" tabindex='1'>
							<span class="text-danger" id="ifscCheck" style='display:none'> Enter IFSC Code </span>
						</div>
					</div>

					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<input type="hidden" name="bankID" id="bankID">
						<button type="button" tabindex="1" name="bankInfoBtn" id="bankInfoBtn" class="btn btn-primary" style="margin-top: 19px;">Submit</button>
					</div>

				</div>
				</br>

				<div id="bankTable" class="table-responsive">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="50"> S.No </th>
								<th> Bank Name </th>
								<!-- <th> Branch Name </th> -->
								<th> Account Holder Name </th>
								<th> Account Number </th>
								<!-- <th> IFSC Code </th> -->
								<th> ACTION </th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetbankinfoList()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add Bank Info Modal -->

<!-- Add Signed Doc info Modal  START -->
<div class="modal fade addSignDoc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="signDocUploads">
		<input type="hidden" name="doc_req_id" id="doc_req_id" value="">
		<input type="hidden" name="doc_cus_id" id="doc_cus_id" value="">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="">Add Signed Doc Info</h5>
					<button type="button" class="close closeSignedInfo" data-dismiss="modal" aria-label="Close">
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
								<input type="hidden" name="doc_name" id="doc_name" value="0">
								<input type="text" class="form-control" name="doc_name_dummy" id="doc_name_dummy" value="Signed Document" disabled tabindex='1'>
								<span class="text-danger" id="docNameCheck" style='display:none'> Select Doc Name </span>
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
								<span class="text-danger" id="signTypeCheck" style='display:none'> Select Sign Type </span>
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
								<span class="text-danger" id="docCountCheck" style='display:none'> Enter Count </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="upd"> Uploads </label>
								<input type="file" onchange="compressImage(this,400)" class="form-control" id="signdoc_upd" name="signdoc_upd[]" multiple onchange="filesCount()" tabindex='1'>
								<span class="text-danger" id="docupdCheck" style="display: none;"> Upload Document </span>
							</div>
						</div>

						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="signedID" id="signedID">
							<button type="button" name="signInfoBtn" id="signInfoBtn" class="btn btn-primary" style="margin-top: 19px;" disabled tabindex='1'>Submit</button>
						</div>

					</div>
					</br>

					<div id="signTable" class="table-responsive">
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
					<button type="button" class="btn btn-secondary closeSignedInfo" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Signed Doc Info Modal -->

<!-- Add Cheque info Modal  START -->
<div class="modal fade addCheque" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="chequeUploads">
		<input type="hidden" name="cheque_req_id" id="cheque_req_id" value="">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="">Add Cheque Info</h5>
					<button type="button" class="close closeChequeInfo" data-dismiss="modal" aria-label="Close">
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
								<span class="text-danger" id="holdertypeCheck" style='display:none'> Select Holder type </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="HolderName "> Holder Name </label>
								<input type="text" class="form-control" id="holder_name" name="holder_name" readonly tabindex='1'>

								<select type="text" class="form-control" id="holder_relationship_name" name="holder_relationship_name" style="display: none;" disabled>
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
								<span class="text-danger" id="chequebankCheck" style='display:none'> Enter Bank Name </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="chequeNo"> Cheque Count </label> <span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="cheque_count" name="cheque_count" placeholder="Enter Cheque Count" readonly tabindex='1'>
								<span class="text-danger" id="chequeCountCheck" style='display:none'> Enter Cheque Count </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="upd"> Uploads </label>
								<input type="file" onchange="compressImage(this,400)" class="form-control" id="cheque_upd" name="cheque_upd[]" multiple onchange="chequefilesCount()" tabindex='1'>
								<span class="text-danger" id="chequeupdCheck" style='display:none'> Upload Cheque </span>
							</div>
						</div>
					</div>

					<div class="row" id="chequeColumnDiv"> </div>

					<div class="row">
						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="chequeID" id="chequeID">
							<button type="button" name="chequeInfoBtn" id="chequeInfoBtn" class="btn btn-primary" style="margin-top: 19px;" disabled tabindex='1'>Submit</button>
						</div>
					</div>
					</br>


					<div id="chequeTable" class="table-responsive">
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
					<button type="button" class="btn btn-secondary closeChequeInfo" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Cheque Info Modal -->

<!-- Add Customer Label Modal  START -->
<div class="modal fade addCusLabel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="">Add Customer Feedback </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="feedbackList()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="feedbackInsertOk" class="successalert"> Feedback Added Successfully
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="feedbackUpdateok" class="successalert"> Feedback Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="feedbackNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="feedbackDeleteOk" class="unsuccessalert"> Feedback Deleted
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="feedbackDeleteNotOk" class="unsuccessalert"> Feedback not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />

				<div class="row">

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="feedback_label"> Feedback Label </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="feedback_label" name="feedback_label" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Feedback Label" tabindex='1'>
							<span class="text-danger" id="feedbacklabelCheck" style='display:none'> Enter Feedback Label </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="cus_feedback"> Feedback </label> <span class="required">&nbsp;*</span>
							<select type="text" class="form-control" id="cus_feedback" name="cus_feedback" tabindex='1'>
								<option value=""> Select Feedback </option>
								<option value="1"> Bad </option>
								<option value="2"> Poor </option>
								<option value="3"> Average </option>
								<option value="4"> Good </option>
								<option value="5"> Excellent </option>
							</select>
							<span class="text-danger" id="feedbackCheck" style='display:none'> Select Feedback </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
						<div class="form-group">
							<label for="feedback_remark"> Remarks </label>
							<textarea class="form-control" name="feedback_remark" id="feedback_remark" tabindex='1'></textarea>
						</div>
					</div>

					<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<input type="hidden" name="feedbackID" id="feedbackID">
						<button type="button" name="feedbackBtn" id="feedbackBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='1'> Submit </button>
					</div>
				</div>
				</br>


				<div id="feedbackTable" class="table-responsive">
					<table class="table custom-table">
						<thead>
							<tr>
								<th width="50"> S.No </th>
								<th> Feedback Label </th>
								<th> Feedback </th>
								<th> ACTION </th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="feedbackList();">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add Customer Label Info Modal -->

<!-- Add Gold info Modal  START -->
<div class="modal fade addGold" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="goldform">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="">Add Gold Info</h5>
					<button type="button" class="close closeGoldInfo" data-dismiss="modal" aria-label="Close">
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
								<label for="gold_sts"> Gold Status </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="gold_sts" name="gold_sts" tabindex='1'>
									<option value=""> Select Gold Status </option>
									<option value="0"> Old </option>
									<option value="1"> New </option>
								</select>
								<span class="text-danger" id="GoldstatusCheck" style='display:none'> Select Gold Status </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="gold_type"> Gold Type (Ornament's Name)</label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="gold_type" name="gold_type" placeholder="Enter Gold Type" tabindex='1'>
								<span class="text-danger" id="GoldtypeCheck" style='display:none'> Enter Gold Type </span>
							</div>
						</div>


						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="Purity"> Purity (Carat)</label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="Purity" name="Purity" placeholder="Enter Purity" tabindex='1'>
								<span class="text-danger" id="purityCheck" style='display:none'> Enter Purity </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="gold_Count"> Count </label> <span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="gold_Count" name="gold_Count" placeholder="Enter Count" tabindex='1'>
								<span class="text-danger" id="goldCountCheck" style='display:none'> Enter Count </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="gold_Weight"> Weight (in Grams)</label> <span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="gold_Weight" name="gold_Weight" placeholder="Enter Weight in Grams" tabindex='1'>
								<span class="text-danger" id="goldWeightCheck" style='display:none'> Enter Weight </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="gold_Value"> Value </label> <span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="gold_Value" name="gold_Value" placeholder="Enter Value" tabindex='1'>
								<span class="text-danger" id="goldValueCheck" style='display:none'> Enter Value </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="gold_upload"> Upload </label>
								<input type="hidden" name="goldupload" id="goldupload">
								<input type="file" onchange="compressImage(this,400)" class="form-control" id="gold_upload" name="gold_upload" accept=".pdf,.jpg,.png,.jpeg" tabindex='1'>
								<span class="text-danger" id="gold_uploadCheck" style="display:none"> Please Upload files </span>
							</div>
						</div>

						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="goldID" id="goldID">
							<button type="button" name="goldInfoBtn" id="goldInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='1'>Submit</button>
						</div>
					</div>
					</br>


					<div id="goldTable" class="table-responsive">
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
					<button type="button" class="btn btn-secondary closeGoldInfo" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Gold Info Modal -->

<!-- Add Document info Modal  START -->
<div class="modal fade addDocument" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="docUploads">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id="">Add Document Info</h5>
					<button type="button" class="close closeDocInfo" data-dismiss="modal" aria-label="Close">
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
								<span class="text-danger" id="documentnameCheck" style='display:none'> Enter Document name </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocumentDeatails "> Document Details </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="document_details" name="document_details" placeholder="Enter Document Details" value="" tabindex="1" readonly />
								<span class="text-danger" id="documentdetailsCheck" style='display:none'> Enter Document Details </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="Documenttype"> Document Type </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="document_type" name="document_type" tabindex="1" disabled>
									<option value=''> Select Document Type </option>
									<option value='0'> Original </option>
									<option value='1'> Xerox </option>
								</select>
								<span class="text-danger" id="documentTypeCheck" style='display:none'> Select Document Type </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="DocumentHolder"> Document Holder </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="document_holder" name="document_holder" tabindex="1" disabled>
									<option value=""> Select Holder type </option>
									<option value="0"> Customer </option>
									<option value="1"> Guarantor </option>
									<option value="2"> Family Members </option>
								</select>
								<span class="text-danger" id="docholderCheck" style='display:none'> Select Document Holder </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="docholdername"> Holder Name </label>
								<input type="text" class="form-control" id="docholder_name" name="docholder_name" value="" readonly tabindex="1" readonly>

								<select type="text" class="form-control" id="docholder_relationship_name" name="docholder_relationship_name" style="display: none;" tabindex="1" disabled>
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
								<span class="text-danger" id="docinfoupdCheck" style='display:none'> Please Select Document </span>
							</div>
						</div>

						<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
							<input type="hidden" name="doc_info_id" id="doc_info_id" value=''>
							<button type="button" name="docInfoBtn" id="docInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex="1">Submit</button>
						</div>
					</div>
					</br>


					<div id="docModalDiv" class="table-responsive">
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
					<button type="button" class="btn btn-secondary closeDocInfo" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END  Add Document Info Modal -->

<!-- Add KYC info Modal  START -->
<div class="modal fade addkyc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="">Add KYC Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetkycinfoList()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->

				<div id="kycInsertOk" class="successalert"> KYC Info Added Succesfully
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="kycUpdateok" class="successalert"> KYC Info Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="kycNotOk" class="unsuccessalert"> Something Went Wrong <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="kycDeleteOk" class="unsuccessalert"> KYC Info Deleted!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="kycDeleteNotOk" class="unsuccessalert"> Kyc Info not Deleted! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />

				<div class="row">

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="proofof"> Proof of </label> <span class="required">&nbsp;*</span>
							<select type="text" class="form-control" id="proofof" name="proofof" tabindex='1'>
								<option value=""> Select Proof Of </option>
								<option value="0"> Customer </option>
								<option value="1"> Guarantor </option>
								<option value="2"> Family Members </option>
								<option value="3"> Group Members </option>
							</select>
							<span class="text-danger" id="proofCheck" style="display:none"> Select Proof </span>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 name_div" style="display:none">
						<div class="form-group">
							<label for="proofofname"> Name </label>
							<input type="text" class="form-control" id="proofofname" name="proofofname" readonly>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 fam_mem_div" style="display:none">
						<div class="form-group">
							<label for="fam_mem"> Family Member </label> <span class="required">&nbsp;*</span>
							<select type="text" class="form-control" id="fam_mem" name="fam_mem" tabindex='1'>
								<option value=""> Select Family Member </option>
							</select>
							<span class="text-danger" id="fam_memCheck" style="display:none"> Select Family Member </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="proof_type"> Proof Type </label> <span class="required">&nbsp;*</span>
							<select type="text" class="form-control proofdis" id="proof_type" name="proof_type" tabindex='1'>
								<option value=""> Select Proof Type </option>
								<option value="1"> Aadhar </option>
								<option value="2"> Smart Card </option>
								<option value="3"> Voter ID </option>
								<option value="4"> Driving License </option>
								<option value="5"> PAN Card </option>
								<option value="6"> Passport </option>
								<option value="7"> Occupation ID </option>
								<option value="8"> Salary Slip </option>
								<option value="9"> Bank statement </option>
								<option value="10"> EB Bill </option>
								<option value="11"> Business Proof </option>
								<option value="12"> Own House Proof </option>
								<option value="13"> Others </option>
							</select>
							<span class="text-danger" id="proofTypeCheck" style="display:none"> Select Proof Type </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="proof_number"> Proof Number </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="proof_number" name="proof_number" placeholder="Enter Proof Number" tabindex='1'>
							<span class="text-danger" id="proofnoCheck" style="display:none"> Enter Proof Number </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="upload"> Upload </label>
							<input type="file" onchange="compressImage(this,400)" class="form-control" id="upload" name="upload" accept=".pdf,.jpg,.png,.jpeg" tabindex='1'>
							<span class="text-danger" id="proofUploadCheck" style="display:none"> Please Upload File </span>
						</div>
					</div>

					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<input type="hidden" name="kyc_upload" id="kyc_upload">
						<input type="hidden" name="kycID" id="kycID">
						<button type="button" name="kycInfoBtn" id="kycInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='1'>Submit</button>
					</div>

				</div>
				</br>

				<div id="kycTable" class="table-responsive">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="50"> S.No </th>
								<th> Proof of </th>
								<th> Proof type </th>
								<th> Proof Number </th>
								<th> ACTION </th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetkycinfoList()" tabindex='1'>Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add KYC Info Modal -->

<!-- /////////////////////////////////////////////////////////////////// NOC Summary Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade noc-summary-modal " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id=""> NOC Summary </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="nocsummaryModal" class="table-responsive">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// NOC Summary Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Temp document OUT Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade temp-take-out-modal " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="tempoutform">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id=""> Document Take Out </h5>
					<button type="button" class="close closetempout" data-dismiss="modal" aria-label="Close" onclick="">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="doc_name_tempout"> Document name </label>
								<input type="text" class="form-control" id="doc_name_tempout" name="doc_name_tempout" value="" tabindex="1" readonly />
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="doc_tempout_link"> Document </label>
								<a href="" target='_blank' value="" tabindex="1">
									<input type='text' class="form-control" id="doc_tempout_link" name="doc_tempout_link" readonly value="">
								</a>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempout_date"> Date </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempout_date" name="tempout_date" value="<?php echo date('d-m-Y'); ?>" tabindex="1" readonly />
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempout_purpose"> Purpose </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempout_purpose" name="tempout_purpose" value="" tabindex="1" placeholder='Enter Purpose' />
								<span class="text-danger" id="tempoutpurposeCheck" style='display:none'> Please Enter Purpose </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempout_person"> Person </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempout_person" name="tempout_person" value="" tabindex="1" placeholder='Enter Person' />
								<span class="text-danger" id="tempoutpersonCheck" style='display:none'> Please Enter Person </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempout_remarks"> Remarks </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempout_remarks" name="tempout_remarks" value="" tabindex="1" placeholder='Enter Remarks' />
								<span class="text-danger" id="tempoutremarksCheck" style='display:none'> Please Enter Remarks </span>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
							<input type="hidden" name="req_id_tempout" id="req_id_tempout" value=''>
							<input type="hidden" name="cus_id_tempout" id="cus_id_tempout" value=''>
							<input type="hidden" name="table_id_tempout" id="table_id_tempout" value=''>
							<input type="hidden" name="table_name_tempout" id="table_name_tempout" value=''>
							<button type="button" name="tempout_submit" id="tempout_submit" data-type='take-out' class="btn btn-primary" style="margin-top: 19px;" tabindex="1">Submit</button>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary closetempout" data-dismiss="modal" tabindex='1'>Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- /////////////////////////////////////////////////////////////////// Temp document OUT Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Temp document IN Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade temp-take-in-modal " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<form method="POST" enctype="multipart/form-data" id="tempinform">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="background-color: white">
				<div class="modal-header">
					<h5 class="modal-title" id=""> Document Take In </h5>
					<button type="button" class="close closetempin" data-dismiss="modal" aria-label="Close" onclick="">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="doc_name_tempin"> Document name </label>
								<input type="text" class="form-control" id="doc_name_tempin" name="doc_name_tempin" value="" tabindex="1" readonly />
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="doc_tempin_link"> Document </label>
								<a href="" target='_blank' value="" tabindex="1">
									<input type='text' class="form-control" id="doc_tempin_link" name="doc_tempin_link" readonly value="">
								</a>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempin_date"> Date </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempin_date" name="tempin_date" value="<?php echo date('d-m-Y'); ?>" tabindex="1" readonly />
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempin_purpose"> Purpose </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempin_purpose" name="tempin_purpose" value="" tabindex="1" placeholder='Enter Purpose' />
								<span class="text-danger" id="tempinpurposeCheck" style='display:none'> Please Enter Purpose </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempin_person"> Person </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempin_person" name="tempin_person" value="" tabindex="1" placeholder='Enter Person' />
								<span class="text-danger" id="tempinpersonCheck" style='display:none'> Please Enter Person </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="tempin_remarks"> Remarks </label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="tempin_remarks" name="tempin_remarks" value="" tabindex="1" placeholder='Enter Remarks' />
								<span class="text-danger" id="tempinremarksCheck" style='display:none'> Please Enter Remarks </span>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
							<input type="hidden" name="req_id_tempin" id="req_id_tempin" value=''>
							<input type="hidden" name="cus_id_tempin" id="cus_id_tempin" value=''>
							<input type="hidden" name="table_id_tempin" id="table_id_tempin" value=''>
							<input type="hidden" name="table_name_tempin" id="table_name_tempin" value=''>
							<button type="button" name="tempin_submit" id="tempin_submit" data-type='take-in' class="btn btn-primary" style="margin-top: 19px;" tabindex="1">Submit</button>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary closetempin" data-dismiss="modal" onclick="" tabindex='1'>Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- /////////////////////////////////////////////////////////////////// Temp document IN Modal END ////////////////////////////////////////////////////////////////////// -->


<!-- Modal for Customer Old Data Adding   -->
<div class="modal fade add_cus_old" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add Old Data</h5>
				<button type="button" class="close closeBtn_old" data-dismiss="modal" aria-label="Close" onclick="showCustomerOldData()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" id="cus_old_form">
					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_id_old">Customer ID</label>
								<input type="text" class="form-control" id="cus_id_old" name="cus_id_old" value="<?php echo $_GET['upd']; ?>" readonly tabindex='1'>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_name_old">Customer Name</label>
								<input type="text" class="form-control" id="cus_name_old" name="cus_name_old" value="<?php echo $cus_name; ?>" readonly tabindex='1'>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="mobile_old">Mobile</label><span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="mobile_old" name="mobile_old" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="1">
								<span class="text-danger" id="mobile_oldCheck" style='display:none'> Please Enter Mobile Number </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="area_old">Area</label><span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="area_old" name="area_old" value="<?php //echo $area_name;
																												?>" placeholder="Enter Area Name" tabindex="1">
								<span class="text-danger" id="area_oldCheck" style='display:none'> Please Enter Area Name </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="sub_area_old">Sub Area</label><span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="sub_area_old" name="sub_area_old" value="<?php //echo $sub_area_name;
																														?>" placeholder="Enter Sub Area Name" tabindex="1">
								<span class="text-danger" id="sub_area_oldCheck" style='display:none'> Please Enter Sub Area Name </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="loan_cat_old">Loan Category</label><span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="loan_cat_old" name="loan_cat_old" placeholder="Enter Loan Category" tabindex="1">
								<span class="text-danger" id="loan_cat_oldCheck" style='display:none'> Please Enter Loan Category </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="sub_cat_old">Sub Category</label><span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="sub_cat_old" name="sub_cat_old" placeholder="Enter Sub Category" tabindex="1">
								<span class="text-danger" id="sub_cat_oldCheck" style='display:none'> Please Enter Sub Category </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="loan_amt_old">Loan Amount</label><span class="required">&nbsp;*</span>
								<input type="number" class="form-control" id="loan_amt_old" name="loan_amt_old" placeholder="Enter Loan Amount" tabindex="1">
								<span class="text-danger" id="loan_amt_oldCheck" style='display:none'> Please Enter Loan Amount </span>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="due_chart_old">Upload Due Chart</label><span class="required">&nbsp;*</span>
								<input type="file" class="form-control" id="due_chart_old" name="due_chart_old" tabindex="1">
								<span class="text-danger" id="due_chart_oldCheck" style='display:none'> Please Choose Due Chart File </span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="submit_old_cus_data" name="submit_old_cus_data" tabindex="1">Submit</button>
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="1" onclick="showCustomerOldData()">Close</button>
			</div>
		</div>
	</div>
</div>



<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- to get icons like fingerprint -->
<script src="vendor/mfs100/Library/js/jquery-1.8.2.js" type="text/javascript"></script><!-- to work with fingerprint sensor -->
<script src="vendor/mfs100/Library/js/mfs100.js"></script>