<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$typeofaccount;
$companyName = $userObj->getCompanyName($mysqli);
if (isset($_POST['submit_staff_creation']) && $_POST['submit_staff_creation'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateStaffCreation($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_staff_creation&msc=2';
		</script>
	<?php	} else {
		$userObj->addStaffCreation($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_staff_creation&msc=1';
		</script>
	<?php
	}
}

$del = 0;
$costcenter = 0;
if (isset($_GET['del'])) {
	$del = $_GET['del'];
}
if ($del > 0) {
	$userObj->deleteStaffCreation($mysqli, $del, $userid);
	//die;
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_staff_creation&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
	$getStaffCreation = $userObj->getStaffCreation($mysqli, $idupd);
	if (sizeof($getStaffCreation) > 0) {
		for ($i = 0; $i < sizeof($getStaffCreation); $i++) {
			$staff_id						= $getStaffCreation['staff_id'];
			$staff_code					= $getStaffCreation['staff_code'];
			$staff_name					= $getStaffCreation['staff_name'];
			$staff_type					= $getStaffCreation['staff_type'];
			$address					= $getStaffCreation['address'];
			$state						= $getStaffCreation['state'];
			$district					= $getStaffCreation['district'];
			$taluk						= $getStaffCreation['taluk'];
			$place						= $getStaffCreation['place'];
			$pincode					= $getStaffCreation['pincode'];
			$mail					= $getStaffCreation['mail'];
			$mobile1					= $getStaffCreation['mobile1'];
			$mobile2					= $getStaffCreation['mobile2'];
			$whatsapp				= $getStaffCreation['whatsapp'];
			$cug_no				= $getStaffCreation['cug_no'];
			$company_id					= $getStaffCreation['company_id'];
			$department					= $getStaffCreation['department'];
			$team					= $getStaffCreation['team'];
			$designation					= $getStaffCreation['designation'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Staff Creation
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_staff_creation">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="staff_creation" name="staff_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($idupd)) echo $idupd; ?>" id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($staff_id)) echo $staff_id; ?>" id="staff_id_upd" name="staff_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($staff_type)) echo $staff_type; ?>" id="staff_type_upd" name="staff_type_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($company_id)) echo $company_id; ?>" id="company_id_upd" name="company_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($state)) echo $state; ?>" id="state_upd" name="state_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($district)) echo $district; ?>" id="district_upd" name="district_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($taluk)) echo $taluk; ?>" id="taluk_upd" name="taluk_upd" aria-describedby="id" placeholder="Enter id">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">General Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Staff ID</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="staff_code" name="staff_code" value="<?php if (isset($staff_code)) echo $staff_code; ?>" readonly tabindex="1">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Staff Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="staff_name" name="staff_name" value="<?php if (isset($staff_name)) echo $staff_name; ?>" placeholder="Enter Staff Name" pattern="[a-zA-Z\s]+" tabindex="2">
										</div>
									</div>
									<div class="col-xl-3 col-lg-5 col-md-5 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Staff Type</label>&nbsp;<span class="text-danger">*</span>
											<select class='form-control' type="text" id="staff_type" name="staff_type" tabindex="3">
												<option value="">Select Staff Type</option>
											</select>
										</div>
									</div>
									<div class="col-xl-1 col-lg-4 col-md-4 col-sm-4 col-12" style="margin-top: 19px;">
										<div class="form-group">
											<button type="button" tabindex="4" class="btn btn-primary" id="add_staff_type" name="add_staff_type" data-toggle="modal" data-target=".addStaffType" style="padding: 5px calc(1vw + 1rem);"><span class="icon-add"></span></button>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Address</label>
											<input type="text" class="form-control" id="address" name="address" value="<?php if (isset($address)) echo $address; ?>" placeholder="Enter Address" tabindex="5">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="state" name="state" tabindex="6">
												<option value="SelectState">Select State</option>
												<option value="TamilNadu" <?php if (isset($state) && $state == 'TamilNadu') echo 'selected' ?>>Tamil Nadu</option>
												<option value="Puducherry" <?php if (isset($state) && $state == 'Puducherry') echo 'selected' ?>>Puducherry</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">District</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="district1" name="district1">
											<select type="text" class="form-control" id="district" name="district" tabindex="7">
												<option value="Select District">Select District</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="taluk1" name="taluk1">
											<select type="text" class="form-control" id="taluk" name="taluk" tabindex="8">
												<option value="Select Taluk">Select Taluk</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Place</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="place" name="place" value="<?php if (isset($place)) echo $place; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Place" tabindex="9">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Pincode</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control" id="pincode" name="pincode" value="<?php if (isset($pincode)) echo $pincode; ?>" placeholder="Enter Pincode" tabindex="10">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Communication Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Mail ID</label>
											<input type="email" class="form-control" id="mail" name="mail" value="<?php if (isset($mail)) echo $mail; ?>" placeholder="Enter Mail ID" tabindex="11">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Mobile No. 1</label>
											<input type="number" class="form-control" id="mobile1" name="mobile1" value="<?php if (isset($mobile1)) echo $mobile1; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="12">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Mobile No. 2</label>
											<input type="number" class="form-control" id="mobile2" name="mobile2" value="<?php if (isset($mobile2)) echo $mobile2; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="13">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Whatsapp No.</label>
											<input type="number" class="form-control" id="whatsapp" name="whatsapp" value="<?php if (isset($whatsapp)) echo $whatsapp; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Whatsapp Number" tabindex="14">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">CUG No.</label>
											<input type="number" class="form-control" id="cug_no" name="cug_no" value="<?php if (isset($cug_no)) echo $cug_no; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter CUG Number" tabindex="15">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Occupation Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Company Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id='company_id' name="company_id" value='<?php echo $companyName[0]['company_id'] ?>'>
											<input type="text" class="form-control" id='company_id1' name="company_id1" value='<?php echo $companyName[0]['company_name'] ?>' readonly tabindex='16'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Department</label>
											<input type="text" class="form-control" id="department" name="department" value="<?php if (isset($department)) echo $department; ?>" placeholder="Enter Department Name" tabindex="17">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Team</label>
											<input type="text" class="form-control" id="team" name="team" value="<?php if (isset($team)) echo $team; ?>" placeholder="Enter Team Name" tabindex="18">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Designation</label>
											<input type="text" class="form-control" id="designation" name="designation" value="<?php if (isset($designation)) echo $designation; ?>" placeholder="Enter Designation Name" tabindex="19">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_staff_creation" id="submit_staff_creation" class="btn btn-primary" value="Submit" tabindex="20"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="21">Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>


<!-- Add Course Category Modal -->
<div class="modal fade addStaffType" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Staff Type</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="staffInsertNotOk" class="unsuccessalert">Staff Type Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="staffInsertOk" class="successalert">Staff Type Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="staffUpdateOk" class="successalert">Staff Type Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="staffDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Staff Type!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="staffDeleteOk" class="successalert">Staff Type Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-2 col-sm-2 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
						<div class="form-group">
							<label class="label">Enter Staff Type</label>
							<input type="hidden" name="staff_type_id" id="staff_type_id">
							<input type="text" name="staff_type_name" id="staff_type_name" class="form-control" placeholder="Enter Staff Type">
							<span class="text-danger" tabindex="1" id="staffnameCheck">Enter Staff Type</span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
						<label class="label" style="visibility:hidden">Enter Staff Type</label><br>
						<button type="button" tabindex="2" name="submiStaffBtn" id="submiStaffBtn" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<div id="updatedStaffTypeTable">
					<table class="table custom-table table-responsive" id="staffTypeTable">
						<thead>
							<tr>
								<th width="25%">S. No</th>
								<th>Staff Type</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Close</button>
			</div>
		</div>
	</div>
</div>