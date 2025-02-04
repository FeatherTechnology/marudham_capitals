<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$areaList = $userObj->getAreaList($mysqli);
$subAreaList = $userObj->getSubAreaList($mysqli);

if (isset($_POST['submit_area_creation']) && $_POST['submit_area_creation'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateAreaCreation($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_creation&msc=2';
		</script>
	<?php	} else {
		$userObj->addAreaCreation($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_area_creation&msc=1';
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
	$userObj->deleteAreaCreation($mysqli, $del, $userid);
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_area_creation&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
	$getAreaCreation = $userObj->getAreaCreation($mysqli, $idupd);

	if (sizeof($getAreaCreation) > 0) {
		for ($i = 0; $i < sizeof($getAreaCreation); $i++) {
			$area_creation_id                 	 = $getAreaCreation['area_creation_id'];
			$area_id          		     = $getAreaCreation['area_name_id'];
			$sub_area      			     = $getAreaCreation['sub_area'];
			$taluk      			 = $getAreaCreation['taluk'];
			$state       			 = $getAreaCreation['state'];
			$district                	 = $getAreaCreation['district'];
			$pincode        		     = $getAreaCreation['pincode'];
			$enabledisable        		     = $getAreaCreation['enable'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Area Creation
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_area_creation">
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
		<input type="hidden" class="form-control" value="<?php if (isset($area_creation_id)) echo $area_creation_id; ?>" id="area_creation_id_upd" name="area_creation_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($state)) echo $state; ?>" id="state_upd" name="state_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($district)) echo $district; ?>" id="district_upd" name="district_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($taluk)) echo $taluk; ?>" id="taluk_upd" name="taluk_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($area_id)) echo $area_id; ?>" id="area_upd" name="area_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_area)) echo $sub_area; ?>" id="sub_area_upd" name="sub_area_upd" aria-describedby="id" placeholder="Enter id">

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
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
										<div class="form-group">
											<label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="state" name="state" tabindex="1">
												<option value="SelectState">Select State</option>
												<option value="TamilNadu" <?php if (isset($state) && $state == 'TamilNadu') echo 'selected' ?>>Tamil Nadu</option>
												<option value="Puducherry" <?php if (isset($state) && $state == 'Puducherry') echo 'selected' ?>>Puducherry</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
										<div class="form-group">
											<label for="disabledInput">District</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="district1" name="district1">
											<select type="text" class="form-control" id="district" name="district" tabindex="2">
												<option value="Select District">Select District</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
										<div class="form-group">
											<label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="taluk1" name="taluk1">
											<select type="text" class="form-control" id="taluk" name="taluk" tabindex="3">
												<option value="Select Taluk">Select Taluk</option>
											</select>
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-10 col-12">
										<div class="form-group">
											<label for="disabledInput">Area</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="area" name="area" tabindex="4">
												<option value="">Select Area</option>

											</select>
											<span id="loanCategoryCheck" class="text-danger" style="display:none">Select Area</span>
										</div>
									</div>
									<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-top: 19px;">
										<div class="form-group">
											<button type="button" tabindex="5" class="btn btn-primary" id="add_area" name="add_area" style="padding: 8px calc(1vw + 1rem);"><span class="icon-add"></span></button>
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-10 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Area</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id="sub_area" name="sub_area" value="">
											<select type="text" class="form-control " id="sub_area1" name="sub_area1" tabindex="6" multiple data-selected-text-format="count > 2" data-actions-box="true">
												<option value="">Select Sub Area</option>
											</select>
										</div>
									</div>
									<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-top: 19px;">
										<div class="form-group">
											<button type="button" tabindex="7" class="btn btn-primary" id="add_sub_area" name="add_sub_area" style="padding: 8px calc(1vw + 1rem);"><span class="icon-add"></span></button>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-10 col-12">
										<div class="form-group">
											<label for="disabledInput">Pincode</label>&nbsp;<span class="text-danger"></span>
											<input type="number" tabindex="8" onKeyPress="if(this.value.length==6) return false;" class="form-control" id="pincode" name="pincode" value="<?php if (isset($pincode)) echo $pincode; ?>" placeholder="Enter Pincode">
										</div>
									</div>
								</div>
								<br><br><br>
								<div class="row">
									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
										<button type="button" tabindex="9" id="downloadarea" name="downloadarea" class="btn btn-primary"><span class="icon-download"></span>&nbsp;Download</button>
										<button type="button" data-toggle="modal" data-target="#areaUploadModal" tabindex="10" id="uploadArea" name="uploadArea" class="btn btn-primary"><span class="icon-upload"></span>&nbsp;Upload</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_area_creation" id="submit_area_creation" class="btn btn-primary" value="Submit" tabindex="11"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="12">Clear</button>
					</div>

				</div>
			</div>
		</div>
	</form>
</div>

<!-- Add Course Category Modal -->
<div class="modal fade add_area" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Area</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="areaInsertNotOk" class="unsuccessalert">Area Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="areaInsertOk" class="successalert">Area Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="areaUpdateOk" class="successalert">Area Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="areaDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Area!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="areaDeleteOk" class="successalert">Area Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-2 col-sm-2 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
						<div class="form-group">
							<label class="label">Enter Area</label>
							<input type="hidden" name="area_id" id="area_id">
							<input type="text" name="area_name" id="area_name" class="form-control" placeholder="Enter Area">
							<span class="text-danger" tabindex="1" id="areanameCheck">Enter Area</span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
						<label class="label" style="visibility: hidden;">Area</label><br>
						<button type="button" tabindex="2" name="submiAreaBtn" id="submiAreaBtn" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<div id="updatedareaTable">
					<table class="table custom-table" id="areaTable">
						<thead>
							<tr>
								<th width="15px">S. No</th>
								<th width="100px">Area Name</th>
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

<!-- Add Course Category Modal -->
<div class="modal fade add_sub_area" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Sub Area</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeSubModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="subareaInsertNotOk" class="unsuccessalert">Sub Area Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="subareaInsertOk" class="successalert">Sub Area Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="subareaUpdateOk" class="successalert">Sub Area Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="subareaDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Sub Area!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="subareaDeleteOk" class="successalert">Sub Area Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-2 col-sm-2 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
						<div class="form-group">
							<label class="label">Enter Sub Area</label>
							<input type="hidden" name="sub_area_id" id="sub_area_id">
							<input type="text" name="sub_area_name" id="sub_area_name" class="form-control" placeholder="Enter Sub Area">
							<span class="text-danger" tabindex="1" id="subareanameCheck">Enter Sub Area</span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
						<label class="label" style="visibility: hidden;">Sub Area</label><br>
						<button type="button" tabindex="2" name="submiSubAreaBtn" id="submiSubAreaBtn" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<div id="updatedSubAreaTable">
					<table class="table custom-table" id="subAreaTable">
						<thead>
							<tr>
								<th width="15px">S. No</th>
								<th width="100px">Sub Area Name</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
							<?php if (sizeof($subAreaList) > 0) {
								for ($j = 0; $j < count($subAreaList); $j++) { ?>
									<tr>
										<td class="col-md-2 col-xl-2"><?php echo $j + 1; ?></td>
										<td><?php echo $subAreaList[$j]['sub_area_name']; ?></td>
										<td>
											<a id="edit_sub_area" value="<?php echo $subAreaList[$j]['sub_area_id'] ?>"><span class="icon-border_color"></span></a> &nbsp;
											<a id="delete_sub_area" value="<?php echo $subAreaList[$j]['sub_area_id'] ?>"><span class='icon-trash-2'></span></a>
										</td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeSubModal()">Close</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal for upload -->
<div class="modal fade" id="areaUploadModal" tabindex="-1" role="dialog" aria-labelledby="vCenterModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="vCenterModalTitle">Area Bulk Upload</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" enctype="multipart/form-data" name="areaUpload" id="areaUpload">
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12"></div>
						<div class="col-xl-6 col-lg-6 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<div id="insertsuccess" style="color: green; font-weight: bold;">Area Added Successfully</div>
								<div id="notinsertsuccess" style="color: red; font-weight: bold;">Problem Importing File or Duplicate Entry found</div>
								<label class="label">Select File</label>
								<input type="file" name="file" id="file" class="form-control">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="submitAreaUploadbtn" name="submitAreaUploadbtn">Upload</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>