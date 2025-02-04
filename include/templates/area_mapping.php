<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$areaList = $userObj->getAreaList($mysqli);
$branchList = $userObj->getBranchList($mysqli);
$companyName = $userObj->getCompanyName($mysqli);

if (isset($_POST['submit_area_mapping_line']) && $_POST['submit_area_mapping_line'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateAreaMappingLine($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_mapping&msc=2&type=line';
		</script>
	<?php	} else {
		$userObj->addAreaMappingLine($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_mapping&msc=1&type=line';
		</script>
	<?php
	}
}
if (isset($_POST['submit_area_mapping_group']) && $_POST['submit_area_mapping_group'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateAreaMappingGroup($mysqli, $id, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_mapping&msc=2&type=group';
		</script>
	<?php	} else {
		$userObj->addAreaMappingGroup($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_mapping&msc=1&type=group';
		</script>
	<?php
	}
}

$del = 0;
if (isset($_GET['del'])) {
	$del = $_GET['del'];
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}
}
if ($del > 0) {
	if ($type == 'line') {
		$userObj->deleteAreaMappingLine($mysqli, $del, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_mapping&msc=3&type=line';
		</script>
	<?php
	}
	if ($type == 'group') {
		$userObj->deleteAreaMappingGroup($mysqli, $del, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_mapping&msc=3&type=group';
		</script>
	<?php
	}
	?>

<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}
}
$status = 0;
if ($idupd > 0) {
	if ($type == 'line') {
		$getAreaMappingLine = $userObj->getAreaMappingLine($mysqli, $idupd);
		if (sizeof($getAreaMappingLine) > 0) {
			for ($i = 0; $i < sizeof($getAreaMappingLine); $i++) {
				$map_id                 	 = $getAreaMappingLine['map_id'];
				$line_name          		     = $getAreaMappingLine['line_name'];
				$area_id          		     = $getAreaMappingLine['area_id'];
				$sub_area_id      			     = $getAreaMappingLine['sub_area_id'];
				$company_id      			 = $getAreaMappingLine['company_id'];
				$branch_id      			 = $getAreaMappingLine['branch_id'];
			}
		}
		$area_array = explode(',', $area_id);
	} else if ($type == 'group') {

		$getAreaMappingGroup = $userObj->getAreaMappingGroup($mysqli, $idupd);
		if (sizeof($getAreaMappingGroup) > 0) {
			for ($i = 0; $i < sizeof($getAreaMappingGroup); $i++) {
				$map_id1                	 = $getAreaMappingGroup['map_id'];
				$group_name          		     = $getAreaMappingGroup['group_name'];
				$area_id1         		     = $getAreaMappingGroup['area_id'];
				$sub_area_id1      			     = $getAreaMappingGroup['sub_area_id'];
				$company_id1     			 = $getAreaMappingGroup['company_id'];
				$branch_id1      			 = $getAreaMappingGroup['branch_id'];
			}
		}
		$area_array = explode(',', $area_id1);
	}
}

if (isset($_GET['type'])) {
	$type = $_GET['type'];
}


?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Area Mapping
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_area_mapping">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>

<head>
</head>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="report_creation" name="report_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($idupd)) echo $idupd; ?>" id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($type)) echo $type; ?>" id="type" name="type" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($map_id)) echo $map_id; ?>" id="map_id_upd" name="map_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($map_id1)) echo $map_id1; ?>" id="map_id1_upd" name="map_id1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($line_name)) echo $line_name; ?>" id="line_name_upd" name="line_name_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($group_name)) echo $group_name; ?>" id="group_name_upd" name="group_name_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($company_id)) echo $company_id; ?>" id="company_id_upd" name="company_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($company_id1)) echo $company_id1; ?>" id="company_id_upd1" name="company_id_upd1" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($branch_id)) echo $branch_id; ?>" id="branch_id_upd" name="branch_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($branch_id1)) echo $branch_id1; ?>" id="branch_id_upd1" name="branch_id_upd1" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($area_id)) echo $area_id; ?>" id="area_id_upd" name="area_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($area_id1)) echo $area_id1; ?>" id="area_id1_upd" name="area_id1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_area_id)) echo $sub_area_id; ?>" id="sub_area_upd" name="sub_area_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_area_id1)) echo $sub_area_id1; ?>" id="sub_area_upd1" name="sub_area_upd1" aria-describedby="id" placeholder="Enter id">

		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="row">
				</div>

				<!-- ************************************************************** Line Mapping *************************************************************************************** -->
				<div class="card line_mapping" <?php if (isset($type) and $type != 'line') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">General Info (Line)</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Line Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="line_name" id="line_name" value="<?php if (isset($line_name)) echo $line_name; ?>" placeholder="Enter Line Name" class="form-control" tabindex="1">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Company Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id='company_id' name="company_id" value='<?php echo $companyName[0]['company_id'] ?>'>
											<input type="text" class="form-control" id='company_name' name="company_name" value='<?php echo $companyName[0]['company_name'] ?>' readonly tabindex='2'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Branch Name</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="branch" name="branch" tabindex='3'>
												<option value="">Select Branch</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Area</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id="area" name="area" value="">
											<select type="text" class="form-control" id="area_dummy" name="area_dummy" multiple tabindex='4'>
												<option value="">Select Area</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Area</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id="sub_area" name="sub_area" value="">
											<select type="text" class="form-control" id="sub_area_dummy" name="sub_area_dummy" multiple tabindex='5'>

											</select>
										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="col-md-12 ">
							<div class="text-right">
								<button type="submit" name="submit_area_mapping_line" id="submit_area_mapping_line" class="btn btn-primary" value="Submit" tabindex="6"><span class="icon-check"></span>&nbsp;Submit</button>
								<button type="reset" class="btn btn-outline-secondary" tabindex="7">Clear</button>
							</div>
						</div>

					</div>
				</div>

				<!-- ************************************************************** Group Mapping *************************************************************************************** -->
				<div class="card group_mapping" <?php if (isset($type) and $type != 'group') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">General Info (Group)</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Group Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="group_name" id="group_name" value="<?php if (isset($group_name)) echo $group_name; ?>" placeholder="Enter Group Name" class="form-control" tabindex="1">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Company Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id='company_id1' name="company_id1" value='<?php echo $companyName[0]['company_id'] ?>'>
											<input type="text" class="form-control" id='company_name1' name="company_name1" value='<?php echo $companyName[0]['company_name'] ?>' readonly tabindex='1'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Branch Name</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="2" type="text" class="form-control" id="branch1" name="branch1">
												<option value="">Select Branch</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Area</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id="area1" name="area1" value="">
											<select tabindex="3" type="text" class="form-control" id="area_dummy1" name="area_dummy1" multiple>
												<option value="">Select Area</option>
												<?php if (sizeof($areaList) > 0) {
													for ($j = 0; $j < count($areaList); $j++) { ?>
														<option <?php if (isset($area_id1)) {
																	for ($i = 0; $i < sizeof($area_array); $i++) {
																		if ($areaList[$j]['area_id'] == $area_array[$i])  echo 'selected';
																	}
																} ?> value="<?php echo $areaList[$j]['area_id']; ?>">
															<?php echo $areaList[$j]['area_name']; ?></option>
												<?php }
												} ?>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Area</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id="sub_area1" name="sub_area1" value="">
											<select tabindex="4" type="text" class="form-control" id="sub_area_dummy1" name="sub_area_dummy1" multiple>

											</select>
										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="col-md-12 ">
							<div class="text-right">
								<button type="submit" name="submit_area_mapping_group" id="submit_area_mapping_group" class="btn btn-primary" value="Submit" tabindex="5"><span class="icon-check"></span>&nbsp;Submit</button>
								<button type="reset" class="btn btn-outline-secondary" tabindex="6">Clear</button>
							</div>
						</div>

					</div>
				</div>


			</div>
		</div>
	</form>
</div>