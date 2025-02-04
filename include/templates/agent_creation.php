<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$loanCategoryList = $userObj->getloanCategoryList($mysqli);
$schemeList = $userObj->getschemeList($mysqli);
$companyName = $userObj->getCompanyName($mysqli);
if (isset($_POST['submit_agent_creation']) && $_POST['submit_agent_creation'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateAgentCreation($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_agent_creation&msc=2';
		</script>
	<?php	} else {
		$userObj->addAgentCreation($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_agent_creation&msc=1';
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
	$userObj->deleteAgentCreation($mysqli, $del, $userid);
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_agent_creation&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
	$getAgentCreation = $userObj->getAgentCreation($mysqli, $idupd);
	if (sizeof($getAgentCreation) > 0) {
		for ($i = 0; $i < sizeof($getAgentCreation); $i++) {
			$ag_id                 	 = $getAgentCreation['ag_id'];
			$ag_code          		     = $getAgentCreation['ag_code'];
			$ag_name          		     = $getAgentCreation['ag_name'];
			$ag_group          		     = $getAgentCreation['ag_group'];
			$company_id          		     = $getAgentCreation['company_id'];
			$branch_id          		     = $getAgentCreation['branch_id'];
			$mail       			 = $getAgentCreation['mail'];
			$state       			 = $getAgentCreation['state'];
			$district                	 = $getAgentCreation['district'];
			$taluk       		    	 = $getAgentCreation['taluk'];
			$place     			     = $getAgentCreation['place'];
			$pincode        		     = $getAgentCreation['pincode'];
			$loan_category          		     = $getAgentCreation['loan_category'];
			$sub_category          		     = $getAgentCreation['sub_category'];
			$scheme          		     = $getAgentCreation['scheme'];
			$loan_payment          		     = $getAgentCreation['loan_payment'];
			$responsible          		     = $getAgentCreation['responsible'];
			$coll_point          		     = $getAgentCreation['collection_point'];
			$bank_name          		     = $getAgentCreation['bank_name'];
			$holder_name          		     = $getAgentCreation['holder_name'];
			$acc_no          		     = $getAgentCreation['acc_no'];
			$ifsc          		     = $getAgentCreation['ifsc'];
			$bank_branch_name          		     = $getAgentCreation['bank_branch_name'];
			$more_info          		     = $getAgentCreation['more_info'];
			$name          		     = $getAgentCreation['comm_name'];
			$designation          		     = $getAgentCreation['comm_designation'];
			$mobile          		     = $getAgentCreation['comm_mobile'];
			$whatsapp          		     = $getAgentCreation['comm_whatsapp'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Agent Creation
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">

	<a href="edit_agent_creation">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="agent_creation" name="agent_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($idupd)) echo $idupd; ?>" id="id" name="id">
		<input type="hidden" class="form-control" value="<?php if (isset($ag_id)) echo $ag_id; ?>" id="ag_id_upd" name="ag_id_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($company_id)) echo $company_id; ?>" id="company_id_upd" name="company_id_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($branch_id)) echo $branch_id; ?>" id="branch_id_upd" name="branch_id_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($ag_group)) echo $ag_group; ?>" id="ag_group_upd" name="ag_group_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($scheme)) echo $scheme; ?>" id="scheme_upd" name="scheme_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($state)) echo $state; ?>" id="state_upd" name="state_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($district)) echo $district; ?>" id="district_upd" name="district_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($taluk)) echo $taluk; ?>" id="taluk_upd" name="taluk_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($loan_category)) echo $loan_category; ?>" id="loan_category_upd" name="loan_category_upd">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_category)) echo $sub_category; ?>" id="sub_category_upd" name="sub_category_upd">
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
											<input type="hidden" id='company_id' name="company_id" value='<?php echo $companyName[0]['company_id'] ?>'>
											<input type="text" class="form-control" id='company_id1' name="company_id1" value='<?php echo $companyName[0]['company_name'] ?>' readonly tabindex='1'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Agent ID</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="ag_code" name="ag_code" value="<?php if (isset($ag_code)) echo $ag_code; ?>" readonly tabindex="2">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Agent Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="ag_name" name="ag_name" value="<?php if (isset($ag_name)) echo $ag_name; ?>" placeholder="Enter Agent Name" pattern="[a-zA-Z\s&]+" tabindex="3">
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Agent Group</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="ag_group" name="ag_group" tabindex="4">
												<option value=""> Select Agent Group</option>
											</select>
										</div>
									</div>
									<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-top: 19px;">
										<div class="form-group">
											<button type="button" tabindex="5" class="btn btn-primary" id="add_group" name="add_group" data-toggle="modal" data-target=".addGroup" style="padding: 5px calc(1vw + 1rem);"><span class="icon-add"></span></button>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Email ID</label>
											<input type="email" class="form-control" id="mail" name="mail" value="<?php if (isset($mail)) echo $mail; ?>" placeholder="Enter Email ID" tabindex="6">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" id="state" name="state" tabindex="7">
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
											<select type="text" class="form-control" id="district" name="district" tabindex="8">
												<option value="Select District">Select District</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="taluk1" name="taluk1">
											<select type="text" class="form-control" id="taluk" name="taluk" tabindex="9">
												<option value="Select Taluk">Select Taluk</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Place</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="place" name="place" value="<?php if (isset($place)) echo $place; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Place" tabindex="10">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Pincode</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control" id="pincode" name="pincode" value="<?php if (isset($pincode)) echo $pincode; ?>" placeholder="Enter Pincode" tabindex="11">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Communication Info&nbsp;<span class="text-danger">*</span></div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<table id="moduleTable" class="table custom-table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Designation</th>
												<th>Mobile No</th>
												<th>Whatsapp No</th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<?php if ($idupd <= 0) { ?>
											<tbody>
												<tr>
													<td>
														<input type="text" tabindex="12" name="name[]" id="name" class="form-control" pattern="[a-zA-Z\s]+">
													</td>
													<td>
														<input type="text" tabindex="13" name="designation[]" id="designation" class="form-control" pattern="[a-zA-Z\s]+">
													</td>
													<td>
														<input type="number" tabindex="14" name="mobile[]" id="mobile" class="form-control" onKeyPress="if(this.value.length==10) return false;" onblur="if(this.value != '' && this.value.length < 10) $(this).focus();">
													</td>
													<td>
														<input type="number" tabindex="15" name="whatsapp[]" id="whatsapp" class="form-control" onKeyPress="if(this.value.length==10) return false;" onblur="if(this.value != '' && this.value.length < 10) $(this).focus();">
													</td>
													<td>
														<button type="button" tabindex="16" id="add_comm[]" name="add_comm" value="Submit" class="btn btn-primary add_comm">Add</button>
													</td>
													<td>
														<span class='icon-trash-2' tabindex="17"></span>
													</td>
												</tr>
											</tbody>
											<?php }
										if ($idupd > 0) {
											if (isset($name)) { ?>
												<tbody>
													<?php $tb = 9;
													for ($i = 0; $i <= sizeof($name) - 1; $i++) { ?>
														<tr>
															<td>
																<input type="text" tabindex="<?php $tb++;
																								echo $tb; ?>" name="name[]" id="name" class="form-control" value="<?php if (isset($name)) {
																																										echo $name[$i];
																																									} ?>" pattern="[a-zA-Z\s]+">
															</td>
															<td>
																<input type="text" tabindex="<?php $tb++;
																								echo $tb; ?>" name="designation[]" id="designation" class="form-control" value="<?php if (isset($designation)) {
																																													echo $designation[$i];
																																												} ?>" pattern="[a-zA-Z\s]+">
															</td>
															<td>
																<input type="number" tabindex="<?php $tb++;
																								echo $tb; ?>" name="mobile[]" id="mobile" class="form-control" onKeyPress="if(this.value.length==10) return false;" value="<?php if (isset($mobile)) {
																																																								echo $mobile[$i];
																																																							} ?>" onblur="if(this.value != '' && this.value.length < 10) $(this).focus();">
															</td>
															<td>
																<input type="number" tabindex="<?php $tb++;
																								echo $tb; ?>" name="whatsapp[]" id="whatsapp" class="form-control" onKeyPress="if(this.value.length==10) return false;" value="<?php if (isset($whatsapp)) {
																																																									echo $whatsapp[$i];
																																																								} ?>" onblur="if(this.value != '' && this.value.length < 10) $(this).focus();">
															</td>
															<td>
																<button type="button" tabindex="<?php $tb++;
																								echo $tb; ?>" id="add_comm[]" name="add_comm" value="Submit" class="btn btn-primary add_comm">Add</button>
															</td>
															<td>
																<span class='icon-trash-2' tabindex="<?php $tb++;
																										echo $tb; ?>" <?php if ($i > 0) {
																															echo "id='deleterow'";
																														} ?>></span>
															</td>
														</tr>
													<?php } ?>
												</tbody>
										<?php }
										} ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Condition Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="loan_category" name="loan_category" value='<?php if (isset($loan_category)) echo $loan_category; ?>'>
											<select tabindex="101" type="text" class="form-control" id="loan_category1" name="loan_category1" multiple>
												<option value="">Select Loan Category</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Category</label>&nbsp;<span class="text-danger">*</span>
											<input type="hidden" class="form-control" id="sub_category" name="sub_category" value='<?php if (isset($sub_category)) echo $sub_category; ?>'>
											<select tabindex="102" type="text" class="form-control" id="sub_category1" name="sub_category1" multiple>
												<option value="">Select Sub Category</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form_group">
											<label for="disabledInput">Scheme Name</label>
											<input type="hidden" class="form-control" id="scheme" name="scheme" value='<?php if (isset($scheme)) echo $scheme; ?>'>
											<select tabindex="103" type="text" class="form-control" id="scheme1" name="scheme1" multiple>
												<option value="">Select Scheme Name</option>

											</select>
										</div>
									</div>
									<br>
									<br>
									<br>
									<br>
									<br>
									<br>
									<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label for=''>Loan Payment</label>&nbsp;<span class="text-danger">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="104" name="loan_pay" id="loan_pay0" value="0" <?php if (isset($loan_payment) and $loan_payment == '0') echo 'checked'; ?>></input><label for='loan_pay0'>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="105" name="loan_pay" id="loan_pay1" value="1" <?php if (isset($loan_payment) and $loan_payment == '1') echo 'checked'; ?>></input><label for='loan_pay1'>&nbsp;&nbsp;No</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label for=''>Responsible</label>&nbsp;<span class="text-danger">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="106" name="responsible" id="responsible0" value="0" <?php if (isset($responsible) and $responsible == '0') echo 'checked'; ?>></input><label for='responsible0'>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="107" name="responsible" id="responsible1" value="1" <?php if (isset($responsible) and $responsible == '1') echo 'checked'; ?>></input><label for='responsible1'>&nbsp;&nbsp;No</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label for=''>Collection Point</label>&nbsp;<span class="text-danger">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="108" name="coll_point" id="coll_point0" value="0" <?php if (isset($coll_point) and $coll_point == '0') echo 'checked'; ?>></input><label for='coll_point0'>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="109" name="coll_point" id="coll_point1" value="1" <?php if (isset($coll_point) and $coll_point == '1') echo 'checked'; ?>></input><label for='coll_point1'>&nbsp;&nbsp;No</label>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Bank Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Bank Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php if (isset($bank_name)) echo $bank_name; ?>" placeholder="Enter Bank Name" tabindex="110" pattern="[a-zA-Z\s]+">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Branch Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="bank_branch_name" name="bank_branch_name" value="<?php if (isset($bank_branch_name)) echo $bank_branch_name; ?>" placeholder="Enter Branch Name" tabindex="111" pattern="[a-zA-Z\s]+">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Bank Account Number</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" class="form-control" id="acc_no" name="acc_no" value="<?php if (isset($acc_no)) echo $acc_no; ?>" placeholder="Enter Account Number" tabindex="112">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">IFSC Code</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="ifsc" name="ifsc" value="<?php if (isset($ifsc)) echo $ifsc; ?>" placeholder="Enter IFSC Code" tabindex="113">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Account Holder Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="holder_name" name="holder_name" value="<?php if (isset($holder_name)) echo $holder_name; ?>" placeholder="Enter Holder Name" tabindex="114" pattern="[a-zA-Z\s]+">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">More Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-12">
										<div class="form-group">
											<textarea type="text" class="form-control" id="more_info" name="more_info" width="100%" placeholder="Enter More information..." tabindex="115"><?php if (isset($more_info)) echo $more_info; ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_agent_creation" id="submit_agent_creation" class="btn btn-primary" value="Submit" tabindex="116"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="117">Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>


<!-- Add Course Category Modal -->
<div class="modal fade addGroup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Agent Group</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="agentInsertNotOk" class="unsuccessalert">Agent Group Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="agentInsertOk" class="successalert">Agent Group Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="agentUpdateOk" class="successalert">Agent Group Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="agentDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Agent Group!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="agentDeleteOk" class="successalert">Agent Group Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-2 col-sm-2 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
						<div class="form-group">
							<label class="label">Enter Agent Group</label>
							<input type="hidden" name="agent_group_id" id="agent_group_id">
							<input type="text" name="agent_group_name" id="agent_group_name" class="form-control" placeholder="Enter Agent Group">
							<span class="text-danger" tabindex="1" id="agentnameCheck">Enter Agent Group</span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
						<label class="label" style="visibility: hidden;">submit</label><br>
						<button type="button" tabindex="2" name="submiAgentBtn" id="submiAgentBtn" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<div id="updatedagentgroupTable">
					<table class="table custom-table table-responsive" id="agentgroupTable">
						<thead>
							<tr>
								<th width="25%">S. No</th>
								<th>Agent Group Name</th>
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