<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$typeofaccount;
$loanCategoryCreationList = $userObj->getLoanCategoryCreation($mysqli);

if (isset($_POST['submitLoanCategory']) && $_POST['submitLoanCategory'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateLoanCategoryDetails($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_category&msc=2';
		</script>
	<?php	} else {
		$userObj->addLoanCategoryDetails($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_category&msc=1';
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
	$userObj->deleteLoanCategoryDetails($mysqli, $del, $userid);
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_loan_category&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
	$getLoanCategoryDetails = $userObj->getLoanCategoryDetails($mysqli, $idupd);
	if (sizeof($getLoanCategoryDetails) > 0) {
		for ($i = 0; $i < sizeof($getLoanCategoryDetails); $i++) {
			$loan_category_id                 	 = $getLoanCategoryDetails['loan_category_id'];
			$loan_category_name          		     = $getLoanCategoryDetails['loan_category_name'];
			$sub_category_name      			     = $getLoanCategoryDetails['sub_category_name'];
			$loan_limit      			     = $getLoanCategoryDetails['loan_limit'];
			$loan_category_ref_id       			 = $getLoanCategoryDetails['loan_category_ref_id'];
			$loan_category_ref_name                	 = $getLoanCategoryDetails['loan_category_ref_name'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Loan Category
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_loan_category">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="report_creation" name="report_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($loan_category_id)) echo $loan_category_id; ?>" id="id" name="id" aria-describedby="id" placeholder="Enter id">
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
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Loan Category</label>&nbsp;<span class="required">*</span>
											<select type="text" class="form-control" id="loan_category_name" name="loan_category_name" tabindex="1">
												<option value="">Select Loan Category</option>
												<?php if (sizeof($loanCategoryCreationList) > 0) {
													for ($j = 0; $j < count($loanCategoryCreationList); $j++) { ?>
														<option <?php if (isset($loan_category_name)) {
																	if ($loanCategoryCreationList[$j]['loan_category_creation_id'] == $loan_category_name)  echo 'selected';
																}  ?> value="<?php echo $loanCategoryCreationList[$j]['loan_category_creation_id']; ?>">
															<?php echo $loanCategoryCreationList[$j]['loan_category_creation_name']; ?></option>
												<?php }
												} ?>
											</select>
											<span id="loanCategoryCheck" class="text-danger" style="display: none;">Select Loan Category</span>
										</div>
									</div>
									<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-top: 19px;">
										<div class="form-group">
											<button type="button" tabindex="2" class="btn btn-primary" id="add_loanCategoryDetails" name="add_loanCategoryDetails" data-toggle="modal" data-target=".addloanCategoryModal" style="padding: 5px 35px;"><span class="icon-add"></span></button>
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Category</label>&nbsp;<span class="required">*</span>
											<input type="text" class="form-control" id="sub_category_name" name="sub_category_name" value="<?php if (isset($sub_category_name)) echo $sub_category_name; ?>" tabindex="3" placeholder="Enter Sub Category">
											<span id="subCategoryCheck" class="text-danger" style="display: none;">Enter Sub Category</span>
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Loan Limit</label><span class="required">&nbsp;*</span>
											<input type="text" tabindex="4" id="loan_limit" name="loan_limit" class="form-control" placeholder="Enter Loan Limit" value="<?php if (isset($loan_limit)) echo $loan_limit; ?>">
											<span id="loan_limitCheck" class="text-danger" style="display: none;">Enter Loan limit</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Category Info&nbsp;<span class="required">*</span></div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-md-12">
										<!-- <label><span class="text-danger" id="loanCategoryTableCheck">Category Info Mandatory Field</span></label> -->
										<table id="moduleTable" class="table custom-table">
											<thead>
												<tr>
													<th>Category Info</th>
													<th></th>
													<th>Action</th>
												</tr>
											</thead>
											<?php if ($idupd <= 0) { ?>
												<tbody>
													<tr>
														<td>
															<input type="text" tabindex="5" name="loan_category_ref_name[]" id="loan_category_ref_name" class="form-control" value="<?php if (isset($loan_category_ref_name)) {
																																														echo $loan_category_ref_name[$i];
																																													} ?>">
														</td>
														<td>
															<button type="button" tabindex="5" id="add_category_ref[]" name="add_category_ref" value="Submit" class="btn btn-primary add_category_ref">Add</button>
														</td>
														<td>
															<span class='icon-trash-2' tabindex="5"></span>
														</td>
													</tr>
												</tbody>
												<?php }
											if ($idupd > 0) {
												if (isset($loan_category_ref_name)) {
													$k = 30; ?>
													<tbody>
														<?php for ($i = 0; $i <= sizeof($loan_category_ref_name) - 1; $i++) { ?>
															<tr>
																<input type="hidden" name="loan_category_ref_id[]" id="loan_category_ref_id" value="<?php if (isset($loan_category_ref_id)) {
																																						echo $loan_category_ref_id[$i];
																																					} ?>">
																<td>
																	<input type="text" tabindex="<?php echo $k; ?>" name="loan_category_ref_name[]" id="loan_category_ref_name" class="form-control" value="<?php if (isset($loan_category_ref_name)) {
																																																				echo $loan_category_ref_name[$i];
																																																			} ?>">
																</td>
																<td>
																	<button type="button" tabindex="<?php echo $k; ?>" id="add_category_ref[]" name="add_category_ref" value="Submit" class="btn btn-primary add_category_ref">Add</button>
																</td>
																<td>
																	<span class='deleterow icon-trash-2' tabindex="<?php echo $k; ?>" id="delete_row"></span>
																</td>
															</tr>
														<?php $k++;
														} ?>
													</tbody>
											<?php }
											} ?>
										</table>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 ">
					<div class="text-right">
						<!-- <a href="edit_loan_category">
						<button type="button" class="btn btn-outline-secondary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
					</a> -->
						<button type="submit" name="submitLoanCategory" id="submitLoanCategory" class="btn btn-primary" value="Submit" tabindex="50"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="51">Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>


<!-- Add Course Category Modal -->
<div class="modal fade addloanCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Loan Category</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="DropDownCourse()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="categoryInsertNotOk" class="unsuccessalert">Category Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="categoryInsertOk" class="successalert">Loan Category Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="categoryUpdateOk" class="successalert">Loan Category Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="categoryDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Category!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="categoryDeleteOk" class="successalert">Loan Category Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label class="label">Enter Loan Category</label>
							<input type="hidden" name="loan_category_creation_id" id="loan_category_creation_id">
							<input type="text" name="loan_category_creation_name" id="loan_category_creation_name" class="form-control" placeholder="Enter Category">
							<span class="text-danger" id="loancategorynameCheck">Enter Loan Category</span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<label class="label" style="visibility: hidden;">Category</label><br>
						<button type="button" name="submitLoanCategoryModal" id="submitLoanCategoryModal" class="btn btn-primary">Submit</button>
					</div>
				</div>

				<div id="updatedloancategoryTable">
					<table class="table custom-table" id="coursecategoryTable">
						<thead>
							<tr>
								<th width="15px">S. No</th>
								<th>LOAN CATEGORY</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
							<?php if (sizeof($loanCategoryCreationList) > 0) {
								for ($j = 0; $j < count($loanCategoryCreationList); $j++) { ?>
									<tr>
										<td class="col-md-2 col-xl-2"></td>
										<td><?php echo $loanCategoryCreationList[$j]['loan_category_creation_name']; ?></td>
										<td>
											<a id="edit_category" value="<?php echo $loanCategoryCreationList[$j]['loan_category_creation_id'] ?>"><span class="icon-border_color"></span></a> &nbsp
											<a id="delete_category" value="<?php echo $loanCategoryCreationList[$j]['loan_category_creation_id'] ?>"><span class='icon-trash-2'></span></a>
										</td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="DropDownCourse()">Close</button>
			</div>

		</div>
	</div>
</div>