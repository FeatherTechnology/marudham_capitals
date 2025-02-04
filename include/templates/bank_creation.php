<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$companyName = $userObj->getCompanyName($mysqli);

if (isset($_POST['submit_bank_creation']) && $_POST['submit_bank_creation'] != '') {
	if (isset($_POST['idupd']) && $_POST['idupd'] > 0 && is_numeric($_POST['idupd'])) {
		$id = $_POST['idupd'];
		$userObj->updateBankCreation($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_bank_creation&msc=2';
		</script>
	<?php	} else {
		$userObj->addBankCreation($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_bank_creation&msc=1';
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
	$userObj->deleteBankCreation($mysqli, $del, $userid);
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_bank_creation&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
	$getBankCreation = $userObj->getBankCreation($mysqli, $idupd);
	print_r($getBankCreation);
	if (sizeof($getBankCreation) > 0) {
		foreach ($getBankCreation as $key => $val) {
			$$key = $val;
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Bank Creation
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_bank_creation">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="bank_creation" name="bank_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" value="<?php if (isset($idupd)) echo $idupd; ?>" id="idupd" name="idupd">
		<input type="hidden" value="<?php if (isset($company)) echo $company; ?>" id="company_upd" name="company_upd">
		<input type="hidden" value="<?php if (isset($under_branch)) echo $under_branch; ?>" id="under_branch_upd" name="under_branch_upd">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Bank info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="bank_name">Bank Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php if (isset($bank_name)) echo $bank_name; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Bank Name" tabindex="1">
											<span class='text-danger' id='banknameCheck' style="display:none">Please Enter Bank Name</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="short_name">Bank Short Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="short_name" name="short_name" value="<?php if (isset($short_name)) echo $short_name; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Bank Short Name" tabindex="1">
											<span class='text-danger' id='shortnameCheck' style="display:none">Please Enter Bank Short Name</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Account Number</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="acc_no" name="acc_no" value="<?php if (isset($acc_no)) echo $acc_no; ?>" placeholder="Enter Account Number" tabindex="2">
											<span class='text-danger' id='accnoCheck' style="display:none">Please Enter Account Number</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="ifsc">IFSC Code</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="ifsc" name="ifsc" value="<?php if (isset($ifsc)) echo $ifsc; ?>" placeholder="Enter IFSC code" tabindex="3">
											<span class='text-danger' id='ifscCheck' style="display:none">Please Enter IFSC Code</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="branch">Branch Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="branch" name="branch" value="<?php if (isset($branch)) echo $branch; ?>" placeholder="Enter Branch Name" tabindex="4">
											<span class='text-danger' id='branchCheck' style="display:none">Please Enter Branch Name</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="qr_code">UPI QR Code</label>
											<input type="hidden" id="qr_code_name" name="qr_code_name" value="<?php if (isset($qr_code)) echo $qr_code; ?>">
											<input type="file" onchange="checkInputFileSize(this,200)" class="form-control" id="qr_code" name="qr_code" tabindex="5">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="gpay">Gpay Number</label>
											<input type="text" class="form-control" id="gpay" name="gpay" value="<?php if (isset($gpay)) echo $gpay; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Gpay Number" tabindex="6">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Bank Mapping Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="company">Under Company</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" id='company' name="company" value='<?php echo $companyName[0]['company_id'] ?>'>
											<input type="text" class="form-control" id='company1' name="company1" value='<?php echo $companyName[0]['company_name'] ?>' readonly tabindex='8'>
											<span class='text-danger' id='companyCheck' style="display:none">Please Choose Company Name</span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="under_branch">Under Branch</label>&nbsp;<span class="text-danger">*</span>
											<input type='hidden' id="under_branch" name="under_branch">
											<select class="form-control" id="under_branch_dummy" name="under_branch_dummy" tabindex='8' multiple>
												<option value=''>Select Branch name</option>
											</select>
											<span class='text-danger' id='underbranchCheck' style="display:none">Please Choose Branch Name</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_bank_creation" id="submit_bank_creation" class="btn btn-primary" value="Submit" tabindex="9"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="10">Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>