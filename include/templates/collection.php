<?php

if (isset($_SESSION['userid'])) {
	$userid = $_SESSION['userid'];
}

$idupd = 0;
if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
	$cusidupd = $_GET['cusidupd'];
	$customerStatus = $_GET['customerStatus'];  
}
if ($idupd > 0) {
	$getLoanList = $userObj->getLoanList($mysqli, $idupd);
	// print_r($getLoanList);
	if (sizeof($getLoanList) > 0) {
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
		$mobile1					= $getLoanList['mobile1'];
		$cus_pic					= $getLoanList['cus_pic'];
	}

	$getRequestData = $userObj->getRequestForVerification($mysqli, $idupd);
	if (sizeof($getRequestData) > 0) {
		$user_type = $getRequestData['user_type'];
		if ($user_type == 'Director') {
			$role = '1';
		} else if ($user_type == 'Agent') {
			$role = '2';
		} else if ($user_type == 'Staff') {
			$role = '3';
		}
		$user_name = $getRequestData['user_name'];
		$responsible = $getRequestData['responsible'];
		$declaration = $getRequestData['declaration'];
		$remarks = $getRequestData['remarks'];
	}

	$getuser = $userObj->getuser($mysqli, $userid);
	$collection_access = $getuser['collection_access'];
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

	.modal-body label {
		padding: 10px 4px 3px 0px;
	}
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Collection
	</div>
</div>
<br>
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
</div>
<br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_collection&CustomerStatus=<?php echo $customerStatus ?>">
		<button type="button" class="btn btn-primary back-button"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
	<button class="btn btn-primary" id='close_collection_card'>&times;&nbsp;&nbsp;Cancel</button>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="collectionForm" name="collectionForm" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="idupd" id="idupd" value="<?php if (isset($idupd)) {
																echo $idupd;
															} ?>" />
		<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {
																	echo $req_id;
																} ?>" />
		<input type="hidden" name="cusidupd" id="cusidupd" value="<?php if (isset($cusidupd)) {
																		echo $cusidupd;
																	} ?>" />
		<input type="hidden" name="cuspicupd" id="cuspicupd" value="<?php if (isset($cus_pic)) {
																		echo $cus_pic;
																	} ?>" />
		<input type="hidden" name="collection_access" id="collection_access" value="<?php if (isset($collection_access)) {
																						echo $collection_access;
																					} ?>" />
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />
		<input type="hidden" name="colluserid" id="colluserid" value="<?php if (isset($userid)) {
																			echo $userid;
																		} ?>" />

		<!-- Row start -->
		<div class="row gutters">
			<!-- Request Info -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

				<!-- Personal info START -->
				<div class="card personalinfo_card">
					<div class="card-header">Personal Info <span style="font-weight:bold" class=""></span></div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
								<div class="row">
									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="cus_id">Customer ID</label>
											<input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cus_id)) {
																															echo $cus_id;
																														} ?>' readonly tabindex='1'>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="cus_name">Customer Name</label>
											<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cus_name)) {
																																echo $cus_name;
																															} ?>' readonly tabindex='2'>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="area"> Area </label>
											<input type="hidden" class="form-control" id="area_id" name="area_id" value="<?php if (isset($area_id)) echo $area_id; ?>" readonly>
											<input type="text" class="form-control" id="area_name" name="area_name" value="<?php if (isset($area_name)) echo $area_name; ?>" readonly tabindex='3'>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="sub_area"> Sub Area </label>
											<input type="hidden" class="form-control" id="sub_area_id" name="sub_area_id" value="<?php if (isset($sub_area_id)) echo $sub_area_id; ?>" readonly>
											<input type="text" class="form-control" id="sub_area_name" name="sub_area_name" value='<?php if (isset($sub_area_name)) echo $sub_area_name; ?>' readonly tabindex='4'>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="branch"> Branch </label>
											<input type="hidden" class="form-control" name="branch_id" id="branch_id" value="<?php if (isset($branch_id)) {
																																	echo $branch_id;
																																} ?>">
											<input type="text" class="form-control" name="branch_name" id="branch_name" value="<?php if (isset($branch_name)) {
																																	echo $branch_name;
																																} ?>" readonly tabindex='5'>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="line_name"> Line </label>
											<input type="hidden" class="form-control" name="line_id" id="line_id" value="<?php if (isset($line_id)) {
																																echo $line_id;
																															} ?>">
											<input type="text" class="form-control" name="line_name" id="line_name" value="<?php if (isset($line_name)) {
																																echo $line_name;
																															} ?>" readonly tabindex='6'>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="mobile1">Mobile No</label>
											<input type="number" class="form-control" id="mobile1" name="mobile1" value='<?php if (isset($mobile1)) {
																																echo $mobile1;
																															} ?>' readonly tabindex='7'>
										</div>
									</div>

								</div>
							</div>

							<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
								<div class="col-xl-8 col-lg-10 col-md-6 ">
									<div class="form-group" style="margin-left: 30px;">
										<label for="pic" style="margin-left: -20px;">Photo</label><br>
										<input type="hidden" name="cus_image" id="cus_image" value="<?php if (isset($cus_pic)) {
																										echo $cus_pic;
																									} ?>">
										<img id='imgshow' class="img_show" src=<?php //if (isset($cus_pic)){echo 'uploads/request/customer/'.$cus_pic ;}else{ echo 'img/avatar.png'; }
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
														<th width="50">Loan ID</th>
														<th>Loan Category</th>
														<th>Sub Category</th>
														<th>Agent</th>
														<th>Loan date</th>
														<th>Loan Amount</th>
														<th>Balance Amount</th>
														<th>Collection Format</th>
														<th>Status</th>
														<th>Sub Status</th>
														<th>Collect</th>
														<th>Charts</th>
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

				<!-- Collection window Start -->
				<!-- <div class="card collection_card">
					<div class="card-header">
						<div class="card-title">Personal Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="cus_id">Customer ID</label>
											<input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cus_id)) {
																															echo $cus_id;
																														} ?>' readonly>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="cus_name">Customer Name</label>
											<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cus_name)) {
																																echo $cus_name;
																															} ?>' readonly >
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="branch"> Branch </label>
											<input type="hidden" class="form-control" name="branch_id" id="branch_id" value="<?php if (isset($branch_id)) {
																																	echo $branch_id;
																																} ?>">
											<input type="text" class="form-control" name="branch_name" id="branch_name" value="<?php if (isset($branch_name)) {
																																	echo $branch_name;
																																} ?>" readonly>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="area"> Area </label>
											<input  type="hidden" class="form-control" id="area_id" name="area_id" value="<?php if (isset($area_id)) echo $area_id; ?>" readonly>
											<input  type="text" class="form-control" id="area_name" name="area_name" value="<?php if (isset($area_name)) echo $area_name; ?>" readonly>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="sub_area"> Sub Area </label>
											<input  type="hidden" class="form-control" id="sub_area_id" name="sub_area_id" value="<?php if (isset($sub_area_id)) echo $sub_area_id; ?>" readonly>
											<input type="text" class="form-control" id="sub_area_name" name="sub_area_name" value='<?php if (isset($sub_area_name)) echo $sub_area_name; ?>' readonly>
										</div>
									</div>
									
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="line_name"> Line </label>
											<input type="hidden" class="form-control" name="line_id" id="line_id" value="<?php if (isset($line_id)) {
																																echo $line_id;
																															} ?>">
											<input type="text" class="form-control" name="line_name" id="line_name" value="<?php if (isset($line_name)) {
																																echo $line_name;
																															} ?>" readonly>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="loan_category"> Loan Category </label>-->
				<input type="hidden" class="form-control" name="loan_category_id" id="loan_category_id" value="<?php if (isset($loan_category_id)) {
																													echo $loan_category_id;
																												} ?>">
				<!--<input type="text" class="form-control" name="loan_category" id="loan_category" value="<?php if (isset($loan_category)) {
																												echo $loan_category;
																											} ?>" readonly>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="sub_category"> Sub Category </label>-->
				<input type="hidden" class="form-control" name="sub_category_id" id="sub_category_id" value="<?php if (isset($sub_category_id)) {
																													echo $sub_category_id;
																												} ?>">
				<!--<input type="text" class="form-control" name="sub_category" id="sub_category" value="<?php if (isset($sub_category)) {
																												echo $sub_category;
																											} ?>" readonly>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="status"> Status</label>-->
				<input type="hidden" class="form-control" name="status" id="status" value="<?php if (isset($status)) {
																								echo $status;
																							} ?>" readonly>
				<!--</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label for="sub_status"> Sub Status</label>-->
				<input type="hidden" class="form-control" name="sub_status" id="sub_status" value="<?php if (isset($sub_status)) {
																										echo $sub_status;
																									} ?>" readonly>
				<!--</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				<!-- Request Info Start -->
				<div class="card collection_card">
					<div class="card-header">Request Info <span style="font-weight:bold" class=""></span></div>
					<div class="card-body">
						<div class="row">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 responsible" <?php if (isset($role)) {
																									if ($role == '3') { //hide if staff raised req 
																								?> style="display: none" <?php }
																													} //staff dont have responsible
																															?>>
								<div class="form-group">
									<label for="responsible">Responsible&nbsp;<span class="required">&nbsp;*</span></label>
									<input tabindex="8" type="text" class="form-control" id="responsible" name="responsible" value="<?php if (isset($responsible) and $responsible == '0') {
																																		echo 'Yes';
																																	} else {
																																		echo 'No';
																																	} ?>" readonly>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="user_type">User type</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="user_type" name="user_type" readonly value='<?php if (isset($user_type)) echo $user_type; ?>' tabindex="9">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="user">User Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="user" name="user" readonly value='<?php if (isset($user_name)) echo $user_name; ?>' tabindex='10'>
								</div>
							</div>


							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 remarks" <?php if (isset($role)) {
																								if ($role != '3') { ?>style="display: none" <?php }
																																	} //staff only have remarks
																																			?>>
								<div class="form-group">
									<label for="remark">Remarks</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="remarks" name="remarks" value='<?php if (isset($remarks)) echo $remarks; ?>' tabindex='11' placeholder="Enter Remarks" pattern="[a-zA-Z\s]+" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 declaration" <?php if (isset($role)) {
																									if ($role == '3') { ?>style="display: none" <?php }/*staff dont have declaration*/
																																		} ?>>
								<div class="form-group">
									<label for="declaration">Declaration</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="declaration" name="declaration" value='<?php if (isset($declaration)) echo $declaration; ?>' tabindex='12' placeholder="Enter Declaration" pattern="[a-zA-Z\s]+" readonly>
								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- Request Info ENd-->
				<!-- Collection Info -->
				<div class="card collection_card">
					<div class="card-header">
						<div class="card-title">Collection Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Total Amount</label>&nbsp;<span class="text-danger totspan">*</span>
											<input type="text" class="form-control" readonly id="tot_amt" name="tot_amt" value='' tabindex='13'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Paid Amount</label>&nbsp;<span class="text-danger paidspan">*</span>
											<input type="text" class="form-control" readonly id="paid_amt" name="paid_amt" value='' tabindex='14'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Balance Amount</label>&nbsp;<span class="text-danger balspan">*</span>
											<input type="text" class="form-control" readonly id="bal_amt" name="bal_amt" value='' tabindex='15'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" readonly id="due_amt" name="due_amt" value='' tabindex='16'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Pending Amount</label>&nbsp;<span class="text-danger pendingspan">*</span>
											<input type="text" class="form-control" readonly id="pending_amt" name="pending_amt" value='' tabindex='17'>
											<input type="hidden" class="form-control" readonly id="pend_amt" name="pend_amt">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Payable Amount</label>&nbsp;<span class="text-danger payablespan">*</span>
											<input type="text" class="form-control" readonly id="payable_amt" name="payable_amt" value='' tabindex='18'>
											<input type="hidden" class="form-control" readonly id="payableAmount" name="payableAmount">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 till-date-int">
										<div class="form-group">
											<label for="disabledInput">Till Date Interest</label>&nbsp;<span class="text-danger ">*</span>
											<input type="text" class="form-control" readonly id="till_date_int" name="till_date_int" value='' tabindex='19'>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Penalty</label>&nbsp;<span class="text-danger ">*</span>
											<input type="text" class="form-control" readonly id="penalty" name="penalty" value='' tabindex='20'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Fine</label>&nbsp;<span class="text-danger ">*</span>
											<input type="text" class="form-control" readonly id="coll_charge" name="coll_charge" value='' tabindex='21'>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Collection Track -->
				<div class="card collection_card">
					<div class="card-header">
						<div class="card-title">Collection Track</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emiLoanDiv">
										<div class="form-group">
											<label for="disabledInput">Due Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="due_amt_track" name="due_amt_track" value='' placeholder='Enter Due Amount' tabindex='22'>
											<span class="text-danger totalpaidCheck" style="display: none;">Please Enter any one of these<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 intLoanDiv" style="display: none;">
										<div class="form-group">
											<label for="disabledInput">Principal Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="princ_amt_track" name="princ_amt_track" value='' placeholder='Enter Principal Amount' tabindex='23'>
											<span class="text-danger totalpaidCheck" style="display: none;">Please Enter any one of these<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 intLoanDiv" style="display: none;">
										<div class="form-group">
											<label for="disabledInput">Interest Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="int_amt_track" name="int_amt_track" value='' placeholder='Enter Interest Amount' tabindex='24'>
											<span class="text-danger totalpaidCheck" style="display: none;">Please Enter any one of these<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Penalty</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="penalty_track" name="penalty_track" value='' placeholder='Enter Penalty Amount' tabindex='25'>
											<span class="text-danger totalpaidCheck" style="display: none;">Please Enter any one of these<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Fine</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="coll_charge_track" name="coll_charge_track" value='' placeholder='Enter Fine' tabindex='26'>
											<span class="text-danger totalpaidCheck" style="display: none;">Please Enter any one of these<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Total Paid</label>
											<input type="text" readonly class="form-control" id="total_paid_track" name="total_paid_track" value='' tabindex='27'>
										</div>
									</div>
								</div>

								<div class="row">

									<!-- Only if user has collection access can have the waiver details -->
									<?php if (isset($collection_access) && $collection_access == '0') { ?>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Pre Closure</label>
												<input type="text" class="form-control" id="pre_close_waiver" name="pre_close_waiver" value='' placeholder='Enter Pre Closure Amount' tabindex='28'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Penalty Waiver</label>
												<input type="text" class="form-control" id="penalty_waiver" name="penalty_waiver" value='' placeholder='Enter Penalty Waiver' tabindex='29'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Fine Waiver</label>
												<input type="text" class="form-control" id="coll_charge_waiver" name="coll_charge_waiver" value='' placeholder='Enter Fine Waiver' tabindex='30'>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
											<div class="form-group">
												<label for="disabledInput">Total Waiver</label>
												<input type="text" readonly class="form-control" id="total_waiver" name="total_waiver" value='' tabindex='31'>
											</div>
										</div>
										<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12"></div>
									<?php } ?>

									<div class="col-12">
										<hr>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Collection Method</label>&nbsp;<span class="text-danger">*</span>
											<select class='form-control' id='collection_loc' name='collection_loc' tabindex='32'>
												<option value=''>Select Collection Method</option>
												<option value='1' selected>By Self</option>
												<option value='2'>On Spot</option>
											</select>
											<span class="text-danger" id='collectionlocCheck' style="display: none;">Please Select Collection Method<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Collection Date</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" readonly class="form-control" id="collection_date" name="collection_date" value='<?php echo date('d-m-Y'); ?>' tabindex='33'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Collection ID</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" readonly class="form-control" id="collection_id" name="collection_id" value='' tabindex='34'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="disabledInput">Collection Mode</label>&nbsp;<span class="text-danger">*</span>
											<select class='form-control' id='collection_mode' name='collection_mode' tabindex='35'>
												<option value=''>Select Collection Mode</option>
												<option value='1' selected>Cash</option>
												<option value='2'>Cheque</option>
												<option value='3'>ECS</option>
												<option value='4'>IMPS/NEFT/RTGS</option>
												<option value='5'>UPI Transaction</option>
											</select>
											<span class="text-danger" id='collectionmodeCheck' style="display: none;">Please Select Collection Mode<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 cheque transaction" style="display:none">
										<div class="form-group">
											<label for="disabledInput">Bank Name</label>&nbsp;<span class="text-danger">*</span>
											<select class='form-control' id='bank_id' name='bank_id' tabindex='36'>
												<option value=''>Select Bank Name</option>
											</select>
											<span class="text-danger" id='bank_idCheck' style="display: none;">Please Select Bank Name<span>
										</div>
									</div>
								</div>
								<!-- <div class="col-8 cash"></div> -->
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 cheque" style="display:none">
										<div class="form-group">
											<label for="disabledInput">Cheque No</label>&nbsp;<span class="text-danger chequeSpan">*</span>
											<select class='form-control' id='cheque_no' name='cheque_no' tabindex='37'>
												<option value=''>Select Cheque No</option>
											</select>
											<span class="text-danger" id='chequeCheck' style="display: none;">Please Select Cheque No<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
										<div class="form-group">
											<label for="disabledInput">Transaction ID</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" id="trans_id" name="trans_id" value='' placeholder="Enter Transaction ID" tabindex='38'>
											<span class="text-danger" id='transidCheck' style="display: none;">Please Enter Transaction ID<span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
										<div class="form-group">
											<label for="disabledInput">Transaction Date</label>&nbsp;<span class="text-danger">*</span>
											<input type="date" class="form-control" id="trans_date" name="trans_date" value='' tabindex='39'>
											<span class="text-danger" id='transdateCheck' style="display: none;">Please Choose Transaction Date<span>
										</div>
									</div>
									<div class="col-4 other" style="display:none"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Collection window End -->

				<!-- Submit Button Start -->
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_collection" id="submit_collection" class="btn btn-primary" value="Submit" tabindex='40'><span class="icon-check"></span>&nbsp;Submit</button>
						<!-- <button type="reset" class="btn btn-outline-secondary" tabindex="20">Clear</button> -->
					</div>
				</div>
				<!-- Submit Button End -->

			</div>
		</div>
	</form>
	<!-- Form End -->
</div>
<div id="printcollection" style="display: none"></div>

<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade DueChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($idupd)) {
																echo $idupd;
															} ?>">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="dueChartTitle"> Due Chart</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="dueChartTableDiv">
					<table class="table custom-table">
						<thead>
							<tr>
								<th> S.No </th>
								<th> Due Month </th>
								<th> Month </th>
								<th> Due Amount </th>
								<th> Pending </th>
								<th> Payable </th>
								<th> Collection Date </th>
								<th> Collection Amount </th>
								<th> Balance Amount </th>
								<th> Collection Track </th>
								<th> Role </th>
								<th> User ID </th>
								<th> Collection Location </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Penalty Char Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade PenaltyChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($idupd)) {
																echo $idupd;
															} ?>">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Penalty Chart</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="penaltyChartTableDiv">
					<table class="table custom-table">
						<thead>
							<tr>
								<th> S.No </th>
								<th> Penalty Date </th>
								<th> Penalty </th>
								<th> Paid Date </th>
								<th> Paid Amount </th>
								<th> Balance Amount </th>
								<th> Waiver Amount </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Penalty Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Fine Chart Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade collectionChargeChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($idupd)) {
																echo $idupd;
															} ?>">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Fine Chart </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="collectionChargeDiv">
					<table class="table custom-table">
						<thead>
							<tr>
								<th> S.No </th>
								<th> Date </th>
								<th> Fine </th>
								<th> Purpose </th>
								<th> Paid Date </th>
								<th> Paid </th>
								<th> Balance </th>
								<th> Waiver </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Fine Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Fine Add Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade collectionCharges" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Fine</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="collChargeInsertOk" class="successalert"> Fine Added Successfully
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>
				<div id="collChargeNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>
				<br />
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="coll_date "> Date </label> <span class="required">&nbsp;*</span>
							<input type="hidden" class="form-control" id="cc_req_id" name="cc_req_id">
							<input type="text" class="form-control" id="collectionCharge_date" name="collectionCharge_date" readonly placeholder="<?php echo date('d-m-Y') ?>" value="<?php echo date('d-m-Y') ?>" tabindex='1'>
							<span class="text-danger" id="collectionChargeDateCheck"> Select Date </span>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="coll_purpose"> Purpose </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="collectionCharge_purpose" name="collectionCharge_purpose" placeholder="Enter Purpose" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='1'>
							<span class="text-danger" id="purposeCheck"> Enter Purpose </span>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<label for="coll_amnt"> Amount </label> <span class="required">&nbsp;*</span>
							<input type="number" class="form-control" id="collectionCharge_Amnt" name="collectionCharge_Amnt" placeholder="Enter Amount" tabindex='1'>
							<span class="text-danger" id="amntCheck"> Enter Amount </span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
						<!-- <input type="hidden" name="bankID" id="bankID"> -->
						<button type="button" tabindex="1" name="collChargeBtn" id="collChargeBtn" class="btn btn-primary" style="margin-top: 19px;">Submit</button>
					</div>
				</div>
				</br>
				<div id="collChargeTableDiv">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="15%"> S.No </th>
								<th> Date </th>
								<th> Purpose </th>
								<th> Amount </th>
								<!-- <th> ACTION </th> -->
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Fine Add Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Commitment Add Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade" id='addCommitment' tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add Commitment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row">

					<div class="col-12">
						<div class="row">
							<input type="hidden" class="form-control" id="comm_req_id" name="comm_req_id">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_fdate">Follow Up Date</label> <span class="required">&nbsp;*</span>
								<input type="text" class="form-control" id="comm_fdate" name="comm_fdate" tabindex="1" value="<?php echo date('d-m-Y'); ?>" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_ftype">Follow Type</label> <span class="required">&nbsp;*</span>
								<select class="form-control" id="comm_ftype" name="comm_ftype" tabindex="1">
									<option value="">Select Follow Type</option>
									<option value="1">Direct</option>
									<option value="2">Mobile</option>
								</select>
								<span class="text-danger" id="comm_ftypeCheck" style="display:none">Please Select Follow Type</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_fstatus">Follow Up Status</label> <span class="required">&nbsp;*</span>
								<select class="form-control" id="comm_fstatus" name="comm_fstatus" tabindex="1">
									<option value="">Select Follow Up Status</option>
								</select>
								<span class="text-danger" id="comm_fstatusCheck" style="display:none">Please Select Follow Up Status</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
								<label for="comm_person_type">Follow Person Type</label><span class="required">&nbsp;*</span>
								<select name="comm_person_type" id="comm_person_type" class='form-control' tabindex="1">
									<option value="">Select Person Type</option>
									<option value="1">Customer</option>
									<option value="2">Guarentor</option>
									<option value="3">Family Member</option>
								</select>
								<span class="text-danger" id="comm_person_typeCheck" style="display:none">Please Select Person Type</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
								<label for="comm_person_name">Person Name</label><span class="required">&nbsp;*</span>
								<input type="text" name="comm_person_name" id="comm_person_name" class='form-control' tabindex="1" readonly>
								<select name="comm_person_name1" id="comm_person_name1" class='form-control' tabindex="1" style="display: none;"></select>
								<span class="text-danger" id="comm_person_nameCheck" style="display:none">Please Select Person Name</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
								<label for="comm_relationship">Relationship</label><span class="required">&nbsp;*</span>
								<input type="text" name="comm_relationship" id="comm_relationship" class='form-control' tabindex="1" readonly>
								<span class="text-danger" id="comm_relationshipCheck" style="display:none">Please Select Relationship</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_remark">Remark</label><span class="required">&nbsp;*</span>
								<input type="text" name="comm_remark" id="comm_remark" class='form-control' tabindex="1" placeholder="Enter Remark">
								<span class="text-danger" id='comm_remarkCheck' style="display: none;">Please Enter Remark</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
								<label for="comm_date">Commitment Date</label><span class="required">&nbsp;*</span>
								<input type="date" name="comm_date" id="comm_date" class='form-control' tabindex="1">
								<span class="text-danger" id='comm_dateCheck' style="display: none;">Please Enter Commitment Date</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_user_type">User Type</label><span class="required">&nbsp;*</span>
								<input type="text" name="comm_user_type" id="comm_user_type" class='form-control' value='<?php echo $user_type; ?>' tabindex="1" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_user">User Name</label><span class="required">&nbsp;*</span>
								<input type="text" name="comm_user" id="comm_user" class='form-control' value="<?php echo $user_name; ?>" tabindex="1" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="comm_hint">Hint</label><span class="required">&nbsp;*</span>
								<input type="text" name="comm_hint" id="comm_hint" class='form-control' tabindex="1" placeholder="Enter Hint">
								<span class="text-danger" id='comm_hintCheck' style="display: none;">Please Enter Hint</span>
							</div>

						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button class='btn btn-primary' name="sumit_add_comm" id="sumit_add_comm" tabindex="1">Submit</button>
				<button class="btn btn-secondary closeModal" data-dismiss="modal" tabindex="1">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Commitment Add Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- Modal for Commitment Chart just view table   -->
<div class="modal fade" id="commitmentChart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Commitment Chart</h5>
				<button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

					<div class="col-12">
						<div class="row">
							<div class="col-12 table-responsive" id='commChartDiv'></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="1">Close</button>
			</div>
		</div>
	</div>
</div>