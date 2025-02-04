<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$typeofaccount;

if (isset($_POST['submit_company_creation']) && $_POST['submit_company_creation'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$bankupdatedetails = $userObj->updatecompanycreation($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_company_creation&msc=2';
		</script>
	<?php	} else {
		$addcompanydetails = $userObj->addcompanycreation($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_company_creation&msc=1';
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
	$bankdeletedetails = $userObj->deleteCompanyCreation($mysqli, $del, $userid);
	//die;
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_company_creation&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
	$getCompanyCreation = $userObj->getCompanyCreation($mysqli, $idupd);

	if (sizeof($getCompanyCreation) > 0) {
		for ($i = 0; $i < sizeof($getCompanyCreation); $i++) {
			$company_id                 	 = $getCompanyCreation['company_id'];
			$company_name          		     = $getCompanyCreation['company_name'];
			$address1      			     = $getCompanyCreation['address1'];
			$address2      			 = $getCompanyCreation['address2'];
			$state       			 = $getCompanyCreation['state'];
			$district                	 = $getCompanyCreation['district'];
			$taluk       		    	 = $getCompanyCreation['taluk'];
			$place     			     = $getCompanyCreation['place'];
			$pincode        		     = $getCompanyCreation['pincode'];
			$website     			 = $getCompanyCreation['website'];
			$mailid        		     = $getCompanyCreation['mailid'];
			$mobile	    		     = $getCompanyCreation['mobile'];
			$landline               = $getCompanyCreation['landline'];
			$whatsapp               = $getCompanyCreation['whatsapp'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Company Creation
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_company_creation">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
		<!-- <button type="button" class="btn btn-primary"><span class="icon-border_color"></span>&nbsp Edit Employee Master</button> -->
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="company_creation" name="company_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($idupd)) echo $idupd; ?>" id="id" name="id" aria-describedby="id" placeholder="Enter id">
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
											<label for="disabledInput">Company Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="company_name" name="company_name" value="<?php if (isset($company_name)) echo $company_name; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Company Name" tabindex="1">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Address</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="address1" name="address1" value="<?php if (isset($address1)) echo $address1; ?>" placeholder="Enter Address" tabindex="2">
										</div>
									</div>
									<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Address Line 2</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="address2" name="address2" value="<?php if (isset($address2)) echo $address2; ?>" placeholder="Enter Address Line 2" tabindex="3" >
                                        </div>
                                    </div> -->
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="state" name="state" tabindex="4">
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
											<select type="text" class="form-control" id="district" name="district" tabindex='5'>
												<option value="Select District">Select District</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="taluk1" name="taluk1">
											<select type="text" class="form-control" id="taluk" name="taluk" tabindex="6">
												<option value="Select Taluk">Select Taluk</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Place</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="place" name="place" value="<?php if (isset($place)) echo $place; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Place" tabindex="7">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Pincode</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control" id="pincode" name="pincode" value="<?php if (isset($pincode)) echo $pincode; ?>" placeholder="Enter Pincode" tabindex="8">
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
											<label for="disabledInput">Website</label>
											<input type="text" class="form-control" id="website" name="website" value="<?php if (isset($website)) echo $website; ?>" placeholder="Enter Website Name" tabindex="9">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Mail ID</label>
											<input type="email" class="form-control" id="mailid" name="mailid" value="<?php if (isset($mailid)) echo $mailid; ?>" placeholder="Enter Mail ID" tabindex="10">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Mobile No.</label>
											<input type="number" class="form-control" id="mobile" name="mobile" value="<?php if (isset($mobile)) echo $mobile; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="11">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Whatsapp No.</label>
											<input type="number" class="form-control" id="whatsapp" name="whatsapp" value="<?php if (isset($whatsapp)) echo $whatsapp; ?>" onKeyPress="if(this.value.length==10	) return false;" placeholder="Enter Whatsapp Number" tabindex="12">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Landline No.</label>
											<input type="number" class="form-control" id="landline" name="landline" value="<?php if (isset($landline)) echo $landline; ?>" onKeyPress="if(this.value.length==10	) return false;" placeholder="Enter Landline Number" tabindex="13">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 ">
					<div class="text-right">
						<!-- <a href="edit_company_creation">
						<button type="button" class="btn btn-outline-secondary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
					</a> -->
						<button type="submit" name="submit_company_creation" id="submit_company_creation" class="btn btn-primary" value="Submit" tabindex="14"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="15">Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>