<?php
if (isset($_POST['userid'])) {
	$userid = $_POST['userid'];
}



if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
if (isset($_GET['cusidupd'])) {
	$cusidupd = $_GET['cusidupd'];
}
$getLoanList = $userObj->getLoanList($mysqli, $idupd);
if (sizeof($getLoanList) > 0) {
	for ($i = 0; $i < sizeof($getLoanList); $i++) {
		$cus_id						= $getLoanList['cus_id'];
		$cus_name					= $getLoanList['cus_name'];
		$area_id					= $getLoanList['area_confirm_area'];
		$area_name					= $getLoanList['area_name'];
		$sub_area_id				= $getLoanList['area_confirm_subarea'];
		$sub_area_name				= $getLoanList['sub_area_name'];
		$branch_id					= $getLoanList['branch_id'];
		$branch_name				= $getLoanList['branch_name'];
		$line_id					= $getLoanList['line_id'];
		$line_name					= $getLoanList['area_line'];
		$mobile					= $getLoanList['mobile1'];
		$cus_pic					= $getLoanList['cus_pic'];
	}
}

$documentationInfo = $userObj->getAcknowlegementDocument($mysqli, $idupd);
if (sizeof($documentationInfo) > 0) {
	foreach ($documentationInfo as $key => $val) {
		$$key = $val;
	}
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
		Marudham Capitals - NOC
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_noc">
		<button type="button" class="btn btn-primary" id='back-button'><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
	<button class="btn btn-primary" id='close-noc-card'>&times;&nbsp;&nbsp;Cancel</button>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">

	<!--form start-->
	<div>
		<form id="noc_form" name="noc_form" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="idupd" id="idupd" value="<?php if (isset($idupd)) {
																	echo $idupd;
																} ?>" />
			<input type="hidden" name="cusidupd" id="cusidupd" value="<?php if (isset($cusidupd)) {
																			echo $cusidupd;
																		} ?>" />
			<input type="hidden" name="cuspicupd" id="cuspicupd" value="<?php if (isset($cus_pic)) {
																			echo $cus_pic;
																		} ?>" />
			<input type="hidden" name="req_id" id="req_id" value='' />

			<input type="hidden" name="sign_checklist" id="sign_checklist" value='' />
			<input type="hidden" name="cheque_checklist" id="cheque_checklist" value='' />
			<input type="hidden" name="gold_checklist" id="gold_checklist" value='' />
			<input type="hidden" name="mort_checklist" id="mort_checklist" value='' />
			<input type="hidden" name="endorse_checklist" id="endorse_checklist" value='' />
			<input type="hidden" name="doc_checklist" id="doc_checklist" value='' />


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
												<label for="cus_id">Customer ID</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cus_id)) {
																																echo $cus_id;
																															} ?>' readonly tabindex='1'>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="cus_name">Customer Name</label><span class="required">&nbsp;*</span>
												<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cus_name)) {
																																	echo $cus_name;
																																} ?>' readonly tabindex='2'>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="area"> Area </label> <span class="required"> * </span>
												<input type="hidden" id='area_id' name='area_id' value='<?php if (isset($area_id)) echo $area_id; ?>'>
												<input type="text" class="form-control" id="area" name="area" value="<?php if (isset($area_name)) echo $area_name; ?>" readonly tabindex='3'>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="sub_area"> Sub Area </label> <span class="required"> * </span>
												<input type="hidden" id='sub_area_id' name='sub_area_id' value='<?php if (isset($sub_area_id)) echo $sub_area_id; ?>'>
												<input type="text" class="form-control" id="sub_area" name="sub_area" value='<?php if (isset($sub_area_name)) echo $sub_area_name; ?>' readonly tabindex='4'>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="branch"> Branch </label> <span class="required"> * </span>
												<input type="hidden" id='branch_id' name='branch_id' value='<?php if (isset($branch_id)) echo $branch_id; ?>'>
												<input type="text" class="form-control" id="branch" name="branch" value='<?php if (isset($branch_name)) echo $branch_name; ?>' readonly tabindex='5'>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<label for="branch"> Line </label> <span class="required"> * </span>
												<input type="hidden" class="form-control" id="line_id" name="line_id" value='<?php if (isset($line_id)) echo $line_id; ?>' readonly tabindex='6'>
												<input type="text" class="form-control" id="line" name="line" value='<?php if (isset($line_name)) echo $line_name; ?>' readonly tabindex='7'>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="mobile1">Mobile No</label><span class="required">&nbsp;*</span>
												<input type="number" class="form-control" id="mobile" name="mobile" value='<?php if (isset($mobile)) {
																																echo $mobile;
																															} ?>' readonly tabindex='8'>
											</div>
										</div>

									</div>
								</div>

								<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
									<div class="col-xl-8 col-lg-10 col-md-6 ">
										<div class="form-group" style="margin-left: 30px;">
											<label for="pic" style="margin-left: -20px;">Photo</label><span class="required">&nbsp;*</span><br>
											<input type="hidden" name="cus_pic" id="cus_pic" value="<?php if (isset($cus_pic)) {
																										echo $cus_pic;
																									} ?>">
											<img id='imgshow' class="img_show" src=<?php //if (isset($cus_pic)) {echo 'uploads/request/customer/'.$cus_pic;}else{echo 'img/avatar.png';} 
																					?> />
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- Personal info END -->

					<!-- Loan List Start -->
					<div class="card loanlist_card">
						<div class="card-header">
							<div class="card-title">Loan List</div>
						</div>
						<div class="card-body">
							<div class="row ">
								<!--Fields -->
								<div class="col-md-12 ">
									<div class="row">

										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='loanListTableDiv'>
												<table class="table custom-table" id='loanListTable'>
													<thead>
														<tr>
															<th>Loan ID</th>
															<th>Loan Category</th>
															<th>Sub Category</th>
															<th>Agent</th>
															<th>Loan date</th>
															<th>Loan Amount</th>
															<th>Closed Date</th>
															<th>Status</th>
															<th>Sub Status</th>
															<th>Level</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Loan List End -->
					<!-- Data Checking START -->
					<div class="card loanlist_card">
						<div class="card-header"> Data Checking <span style="font-weight:bold" class=""></span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="category"> Category </label>
										<select type="text" class="form-control" id="category" name="category" tabindex="9">
											<option> Select Category </option>
											<option value="0"> Name </option>
											<option value="1"> Aadhar Number </option>
											<option value="2"> Mobile Number </option>
										</select>
									</div>
								</div>

								<div id="nameCheck" style="display: none" class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<div class="form-group">
										<label for="check_name"> Name </label>
										<select type="text" class="form-control" name="check_name" id="check_name" tabindex="10">
											<option> Select Name </option>
										</select>
									</div>
								</div>

								<div id="aadharNo" style="display: none" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="check_aadhar"> Aadhar Number </label>
										<select type="text" class="form-control" name="check_aadhar" id="check_aadhar" tabindex="11">
											<option> Select Aadhar Number </option>
										</select>
									</div>
								</div>

								<div id="mobileNo" style="display: none" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="check_mobileno"> Mobile Number </label>
										<select type="text" class="form-control" name="check_mobileno" id="check_mobileno" tabindex="12">
											<option> Select Mobile Number </option>
										</select>
									</div>
								</div>

							</div>
							<div id="cus_check"></div></br>
							<div id="fam_check"></div></br>
							<div id="group_check"></div>
						</div>
					</div>
					<!-- Data Checking END -->

					<!-- NOC window -->
					<div class="card noc-card">
						<div class="card-header">NOC Summary</div>
						<div class="card-body">
							<!-- Signed Document start -->
							<div class="row signedRow">
								<div class="col-md-12 ">
									<div class="row">
										<h5 style='margin-left:18px;margin-bottom:30px;'>Signed Document List</h5>
										<span class="text-danger sign_checklistCheck" style="margin-left:18px;display: none;">Please Select atleast one</span>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='signDocDiv'>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Signed Document End -->
							<hr>
							<!-- Cheque List Start -->
							<div class="row chequeRow">
								<div class="col-md-12 ">
									<div class="row">
										<h5 style='margin-left:18px;margin-bottom:30px;'>Cheque List</h5>
										<span class="text-danger cheque_checklistCheck" style="margin-left:18px;display: none;">Please Select atleast one</span>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='chequeDiv'>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Cheque List End -->
							<hr>
							<!-- Mortgage List Start -->
							<div class="row mortRow">
								<div class="col-md-12 ">
									<div class="row">
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group">
												<h5 style='margin-top:30px;margin-bottom:30px;'>Mortgage Details</h5>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="MortgageProcess"> Mortgage Process</label>
												<select type="text" class="form-control" id="mortgage_process" name="mortgage_process" disabled tabindex='13'>
													<option value=""> Select Mortgage Process </option>
													<option value="0" <?php if (isset($mortgage_process) and $mortgage_process == '0') echo 'selected'; ?>> YES </option>
													<option value="1" <?php if (isset($mortgage_process) and $mortgage_process == '1') echo 'selected'; ?>> NO </option>
												</select>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="PropertyHoldertype "> Property Holder type </label>
												<select type="text" class="form-control" id="Propertyholder_type" name="Propertyholder_type" disabled tabindex='14'>
													<option value=""> Select Holder type </option>
													<option value="0" <?php if (isset($Propertyholder_type) and $Propertyholder_type == '0') echo 'selected'; ?>> Customer </option>
													<option value="1" <?php if (isset($Propertyholder_type) and $Propertyholder_type == '1') echo 'selected'; ?>> Guarantor </option>
													<option value="2" <?php if (isset($Propertyholder_type) and $Propertyholder_type == '2') echo 'selected'; ?>> Family Members </option>
												</select>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="PropertyHolderName "> Property Holder Name </label>
												<input type="text" class="form-control" id="Propertyholder_name" name="Propertyholder_name" value="<?php if (isset($Propertyholder_name)) echo $Propertyholder_name; ?>" readonly tabindex='15'>
												<select type="text" class="form-control" id="Propertyholder_relationship_name" name="Propertyholder_relationship_name" style="display: none;" disabled tabindex='15'>
													<option value=""> Select Relationship </option>
												</select>
											</div>
										</div>


										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="chequeRelationship"> Relationship </label>
												<input type="text" class="form-control" id="doc_property_relation" name="doc_property_relation" value="<?php if (isset($doc_property_relation)) echo $doc_property_relation; ?>" readonly tabindex='16'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="DocPropertyType"> Property Type </label>
												<input type="text" class="form-control" id="doc_property_pype" name="doc_property_pype" placeholder="Enter Property Type" value="<?php if (isset($doc_property_type)) echo $doc_property_type; ?>" readonly tabindex='17'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="DocPropertyMeasurement"> Property Measurement </label>
												<input type="text" class="form-control" id="doc_property_measurement" name="doc_property_measurement" placeholder="Enter Property Measurement" value="<?php if (isset($doc_property_measurement)) echo $doc_property_measurement; ?>" readonly tabindex='18'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="DocPropertyLocation"> Property Location </label>
												<input type="text" class="form-control" id="doc_property_location" name="doc_property_location" placeholder="Enter Property Location" value="<?php if (isset($doc_property_location)) echo $doc_property_location; ?>" readonly tabindex='19'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="PropertyValue"> Property Value </label>
												<input type="text" class="form-control" id="doc_property_value" name="doc_property_value" placeholder="Enter Property Value" value="<?php if (isset($doc_property_value)) echo $doc_property_value; ?>" readonly tabindex='20'>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="MortgageName"> Mortgage Name </label>
												<input type="text" class="form-control" id="mortgage_name" name="mortgage_name" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Mortgage Name" value="<?php if (isset($mortgage_name)) echo $mortgage_name; ?>" readonly tabindex='21'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="mortgageDesignation"> Designation </label>
												<input type="text" class="form-control" id="mortgage_dsgn" name="mortgage_dsgn" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Designation" value="<?php if (isset($mortgage_dsgn)) echo $mortgage_dsgn; ?>" readonly tabindex='22'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="MortgageNumber"> Mortgage Number </label>
												<input type="text" class="form-control" id="mortgage_nuumber" name="mortgage_nuumber" placeholder="Enter Mortgage Number" value="<?php if (isset($mortgage_nuumber)) echo $mortgage_nuumber; ?>" readonly tabindex='23'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="RegOffice"> Reg Office </label>
												<input type="text" class="form-control" id="reg_office" name="reg_office" placeholder="Enter Reg Office" value="<?php if (isset($reg_office)) echo $reg_office; ?>" readonly tabindex='24'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="MortgageValue"> Mortgage Value </label>
												<input type="text" class="form-control" id="mortgage_value" name="mortgage_value" placeholder="Enter Mortgage Value" value="<?php if (isset($mortgage_value)) echo $mortgage_value; ?>" readonly tabindex='25'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mort_proc">
											<div class="form-group">
												<label for="MortgageDocument"> Mortgage Document </label>
												<select type="text" class="form-control" id="mortgage_document" name="mortgage_document" disabled tabindex='26'>
													<option value=""> Select Mortgage Document </option>
													<option value="0" <?php if (isset($mortgage_document) and $mortgage_document == '0') echo 'selected'; ?>> YES </option>
													<option value="1" <?php if (isset($mortgage_document) and $mortgage_document == '1' or $mortgage_document == null) echo 'selected'; ?>> NO </option>
												</select>
											</div>
										</div>

										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group">
												<h5 style='margin-top:30px;margin-bottom:30px;'>Mortgage List</h5>
												<span class="text-danger mort_checklistCheck" style="display: none;">Please Select atleast one</span>
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='mortgageDiv'>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Mortgage List End -->
							<hr>
							<!-- Endorsement List Start -->
							<div class="row endRow">
								<div class="col-md-12 ">
									<div class="row">

										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group">
												<h5 style='margin-top:30px;margin-bottom:30px;'>Endorsement Details</h5>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="EndorsementProcess"> Endorsement Process</label>
												<select type="text" class="form-control" id="endorsement_process" name="endorsement_process" disabled tabindex='27'>
													<option value=""> Select Endorsement Process </option>
													<option value="0" <?php if (isset($endorsement_process) and $endorsement_process == '0') echo 'selected'; ?>> YES </option>
													<option value="1" <?php if (isset($endorsement_process) and $endorsement_process == '1') echo 'selected'; ?>> NO </option>
												</select>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="OwnerType "> Owner Type </label>
												<select type="text" class="form-control" id="owner_type" name="owner_type" disabled tabindex='28'>
													<option value=""> Select Holder type </option>
													<option value="0" <?php if (isset($owner_type) and $owner_type == '0') echo 'selected'; ?>> Customer </option>
													<option value="1" <?php if (isset($owner_type) and $owner_type == '1') echo 'selected'; ?>> Guarantor </option>
													<option value="2" <?php if (isset($owner_type) and $owner_type == '2') echo 'selected'; ?>> Family Members </option>
												</select>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="OwnerName "> Owner Name </label>
												<input type="text" class="form-control" id="owner_name" name="owner_name" value="<?php if (isset($owner_name)) echo $owner_name; ?>" readonly tabindex='29'>
												<select type="text" class="form-control" id="ownername_relationship_name" name="ownername_relationship_name" style="display: none;" disabled tabindex='29'>
													<option value=""> Select Relationship </option>
												</select>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="chequeRelationship"> Relationship </label>
												<input type="text" class="form-control" id="en_relation" name="en_relation" value="<?php if (isset($en_relation)) echo $en_relation; ?>" readonly tabindex='30'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="Vehicletype"> Vehicle type </label>
												<select type="text" class="form-control" id="vehicle_type" name="vehicle_type" disabled tabindex='31'>
													<option value=""> Select Vehicle type </option>
													<option value="0" <?php if (isset($vehicle_type) and $vehicle_type == '0') echo 'selected'; ?>> 2 Wheeler </option>
													<option value="1" <?php if (isset($vehicle_type) and $vehicle_type == '1') echo 'selected'; ?>> 4 Wheeler </option>
												</select>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="VehicleProcess"> Vehicle Process </label>
												<select type="text" class="form-control" id="vehicle_process" name="vehicle_process" disabled tabindex='32'>
													<option value=""> Select Vehicle Process </option>
													<option value="0" <?php if (isset($vehicle_process) and $vehicle_process == '0') echo 'selected'; ?>> New </option>
													<option value="1" <?php if (isset($vehicle_process) and $vehicle_process == '1') echo 'selected'; ?>> Old </option>
												</select>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="endro_Company"> Company </label>
												<input type="text" class="form-control" id="en_Company" name="en_Company" placeholder="Enter Company" value="<?php if (isset($en_Company)) echo $en_Company; ?>" readonly tabindex='33'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="enModel"> Model </label>
												<input type="text" class="form-control" id="en_Model" name="en_Model" placeholder="Enter Model" value="<?php if (isset($en_Model)) echo $en_Model; ?>" readonly tabindex='34'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="VehicleRegNo"> Vehicle Reg No. </label>
												<input type="text" class="form-control" id="vehicle_reg_no" name="vehicle_reg_no" placeholder="Enter Vehicle No" value="<?php if (isset($vehicle_reg_no)) echo $vehicle_reg_no; ?>" readonly tabindex='35'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="Endorsementname"> Endorsement name </label>
												<input type="text" class="form-control" id="endorsement_name" name="endorsement_name" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Endorsement Name" value="<?php if (isset($endorsement_name)) echo $endorsement_name; ?>" readonly tabindex='36'>
											</div>
										</div>

										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="RC"> RC </label>
												<select type="text" class="form-control" id="en_RC" name="en_RC" disabled tabindex='37'>
													<option value=""> Select RC </option>
													<option value="0" <?php if (isset($en_RC) and $en_RC == '0') echo 'selected'; ?>> YES </option>
													<option value="1" <?php if (isset($en_RC) and $en_RC == '1' or $en_RC == null) echo 'selected'; ?>> NO </option>
												</select>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 endor_proc">
											<div class="form-group">
												<label for="enKey"> Key </label>
												<select type="text" class="form-control" id="en_Key" name="en_Key" disabled tabindex='38'>
													<option value=""> Select Key </option>
													<option value="0" <?php if (isset($en_Key) and $en_Key == '0') echo 'selected'; ?>> YES </option>
													<option value="1" <?php if (isset($en_Key) and $en_Key == '1' or $en_Key == null) echo 'selected'; ?>> NO </option>
												</select>
											</div>
										</div>

										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group">
												<h5 style='margin-top:30px;margin-bottom:30px;'>Endorsement List</h5>
												<span class="text-danger endorse_checklistCheck" style="display: none;">Please Select atleast one</span>
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='endorsementDiv'>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Endorsement List End -->
							<hr>
							<!-- Gold List Start -->
							<div class="row goldRow">
								<div class="col-md-12 ">
									<div class="row">
										<h5 style='margin-left:18px;margin-bottom:30px;'>Gold List</h5>
										<span class="text-danger gold_checklistCheck" style="margin-left:18px;display: none;">Please Select atleast one</span>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='goldDiv'>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Gold List End -->
							<hr>
							<!-- Document Info Start -->
							<div class="row docRow">
								<div class="col-md-12 ">
									<div class="row">
										<h5 style='margin-left:18px;margin-bottom:30px;'>Document List</h5>
										<span class="text-danger doc_checklistCheck" style="margin-left:18px;display: none;">Please Select atleast one</span>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="form-group table-responsive" id='documentDiv'>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Document Info End -->
							<hr>
							<div class="row">
								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="noc_date">Date Of NOC</label><span class="required">&nbsp;*</span>
										<input type="text" class="form-control" id="noc_date" name="noc_date" value='<?php echo date('d-m-Y'); ?>' readonly tabindex='39'>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="noc_member">Member</label><span class="required">&nbsp;*</span>
										<select type='text' id='noc_member' name='noc_member' class="form-control" tabindex="40">
											<option value="">Select Member</option>
											<option value="1">Customer</option>
											<option value="2">Guarentor</option>
											<option value="3">Family Memeber</option>
										</select>
										<span class="text-danger noc_memberCheck" style="display:none">Please Select Member</span>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mem_relation_name">
									<div class="form-group">
										<label for="mem_relation_name">Member Name</label><span class="required">&nbsp;*</span>
										<select type='text' id='mem_relation_name' name='mem_relation_name' class="form-control" tabindex='41'>
											<option value="">Select Member Name</option>
										</select>
										<span class="text-danger mem_relation_nameCheck" style="display:none">Please Select Member Name</span>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mem_name">
									<div class="form-group">
										<label for="mem_name">Member Name</label><span class="required">&nbsp;*</span>
										<input type="hidden" id="mem_id" name="mem_id" value='' readonly>
										<input type="text" class="form-control" id="mem_name" name="mem_name" value='' readonly tabindex='42'>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
									<div class="form-group">
										<label for="ack_fingerprint">Acknowledgement</label><span class="required">&nbsp;*</span>
										<input type="hidden" class="form-control" id="compare_finger" name="compare_finger" value='' readonly>
										<input type="hidden" class="form-control" id="ack_fingerprint" name="ack_fingerprint" value='' readonly>
										<input type="text" class="form-control" value='' readonly style="visibility:hidden;"><!--Just for spacing-->
										<button type="button" class='btn btn-success scanBtn' id="" name="" style='background-color:#009688;margin-top: -50px;width: auto;' onclick="event.preventDefault()" title='Put Your Thumb' tabindex='43'><i class="material-icons" id="icon-flipped">&#xe90d;</i>&nbsp;Scan</button>
										<span class="text-danger scanBtnCheck" style="display:none">Please Scan fingerprint</span>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- NOC window List -->

				<!-- Submit Button Start -->
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_noc" id="submit_noc" class="btn btn-primary" value="Submit" tabindex='44'><span class="icon-check"></span>&nbsp;Submit</button>
					</div>
				</div>
				<!-- Submit Button End -->

			</div>
	</div>
	</form>
</div>
<!-- Form End -->

</div>
<!-- Finger print font cdn -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Finger print jquery Library -->
<script src="vendor/mfs100/Library/js/jquery-1.8.2.js" type="text/javascript"></script>
<script src="vendor/mfs100/Library/js/mfs100.js"></script>