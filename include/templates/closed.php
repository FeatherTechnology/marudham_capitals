<?php

if (isset($_SESSION['userid'])) {
	$userid = $_SESSION['userid'];
}


if (isset($_POST['submit_closed']) && $_POST['submit_closed'] != '') {
	if (isset($_POST['noc_req_id'])) {
		$close_req_id = $_POST['noc_req_id'];
	}

	$userObj->addClosed($mysqli, $close_req_id, $userid);

?>
	<script>
		location.href = '<?php echo $HOSTPATH; ?>edit_closed&msc=1';
	</script>
<?php
}

$idupd = 0;
if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
	$cusidupd = $_GET['cusidupd'];
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

	.modal {
		width: 100% !important;
	}

	.modal-lg {
		max-width: 70% !important;
	}
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Closed
	</div>
</div>
<br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_closed">
		<button type="button" class="btn btn-primary back-button"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
	<button class="btn btn-primary" id='close_noc_card'>&times;&nbsp;&nbsp;Cancel</button>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="cus_Profiles" name="cus_Profiles" action="" method="post" enctype="multipart/form-data">
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
																														} ?>' readonly>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="cus_name">Customer Name</label>
											<input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cus_name)) {
																																echo $cus_name;
																															} ?>' readonly>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="area"> Area </label>
											<input type="hidden" class="form-control" id="area_id" name="area_id" value="<?php if (isset($area_id)) echo $area_id; ?>" readonly>
											<input type="text" class="form-control" id="area_name" name="area_name" value="<?php if (isset($area_name)) echo $area_name; ?>" readonly>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="sub_area"> Sub Area </label>
											<input type="hidden" class="form-control" id="sub_area_id" name="sub_area_id" value="<?php if (isset($sub_area_id)) echo $sub_area_id; ?>" readonly>
											<input type="text" class="form-control" id="sub_area_name" name="sub_area_name" value='<?php if (isset($sub_area_name)) echo $sub_area_name; ?>' readonly>
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
																																} ?>" readonly>
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
																															} ?>" readonly>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
										<div class="form-group">
											<label for="mobile1">Mobile No</label>
											<input type="number" class="form-control" id="mobile1" name="mobile1" value='<?php if (isset($mobile1)) {
																																echo $mobile1;
																															} ?>' readonly>
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
														<th>Status</th>
														<th>Sub Status</th>
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

				<!-- ///////////////////////////////////////////////// Customer Summary START ///////////////////////////////////////////////////////////// -->

				<div class="card customersummary_card">
					<div class="card-header"> Customer Summary <span style="font-weight:bold" class=""></span></div>
					<div class="card-body">
						<div class="row">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_how_know"> How to Know </label>
									<input type="text" class="form-control" name="cus_how_know" id="cus_how_know" readonly <?php if (isset($how_to_know)) echo $how_to_know; ?>>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_loan_count"> Loan Counts </label>
									<input type="text" class="form-control" name="cus_loan_count" id="cus_loan_count" value="<?php if (isset($loan_count)) {
																																	echo $loan_count;
																																} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_frst_loanDate"> First Loan Date </label>
									<input type="text" class="form-control" name="cus_frst_loanDate" id="cus_frst_loanDate" value="<?php if (isset($first_loan_date)) {
																																		echo $first_loan_date;
																																	} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_travel_cmpy"> Travel with Company </label>
									<input type="text" class="form-control" name="cus_travel_cmpy" id="cus_travel_cmpy" value="<?php if (isset($travel_with_company)) {
																																	echo $travel_with_company;
																																} ?>" readonly>
								</div>
							</div>

						</div>

						<hr>

						<div class="row">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_monthly_income"> Monthly Income </label>
									<input type="text" class="form-control" name="cus_monthly_income" id="cus_monthly_income" value="<?php if (isset($monthly_income)) {
																																			echo $monthly_income;
																																		} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_other_income"> Other Income </label>
									<input type="text" class="form-control" name="cus_other_income" id="cus_other_income" value="<?php if (isset($other_income)) {
																																		echo $other_income;
																																	} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_support_income"> Support Income </label>
									<input type="text" class="form-control" name="cus_support_income" id="cus_support_income" value="<?php if (isset($support_income)) {
																																			echo $support_income;
																																		} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_Commitment"> Commitment </label>
									<input type="text" class="form-control" name="cus_Commitment" id="cus_Commitment" value="<?php if (isset($commitment)) {
																																	echo $commitment;
																																} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_monDue_capacity"> Monthly Due Capacity </label>
									<input type="text" class="form-control" name="cus_monDue_capacity" id="cus_monDue_capacity" value="<?php if (isset($monthly_due_capacity)) {
																																			echo $monthly_due_capacity;
																																		} ?>" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_loan_limit"> Customer Limit </label>
									<input type="text" class="form-control" name="cus_loan_limit" id="cus_loan_limit" value="<?php if (isset($loan_limit)) {
																																	echo $loan_limit;
																																} ?>" readonly>
								</div>
							</div>

						</div>

						<hr>

						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group" id="OldFeedbackTable">
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
							<div class="col-xl-4 col-lg-6 col-md-8 col-sm-12 col-12">
								<div class="form-group">
									<label for="about_cus"> About Customer </label>
									<textarea class="form-control" name="about_cus" id="about_cus" readonly><?php if (isset($about_customer)) {
																												echo $about_customer;
																											} ?></textarea>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- ///////////////////////////////////////////////// Customer Summary END ///////////////////////////////////////////////////////////// -->


				<!-- NOC Window START -->
				<div class="card noc_window">
					<input type="hidden" name="noc_req_id" id="noc_req_id"> <!-- Value Set when Click NOC Button against the line item in Loan List Table -->
					<div class="card-header">
						<div class="card-title"> NOC Window</div>
					</div>
					<div class="card-body">
						<div class="row">

							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 text-center">
								<div class="form-group">
									<button type="button" class="btn btn-primary due-chart" id="due_chart" name="due_chart" data-toggle="modal" data-target=".DueChart" style="padding: 10px 35px;width: 100%;"> Due Chart </button>
								</div>
							</div>

							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 text-center">
								<div class="form-group">
									<button type="button" class="btn btn-primary penalty-chart" id="penalty_chart" name="penalty_chart" data-toggle="modal" data-target=".PenaltyChart" style="padding: 10px 35px;width: 100%;"> Penalty Chart </button>
								</div>
							</div>

							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 text-center">
								<div class="form-group">
									<button type="button" class="btn btn-primary coll-charge-chart" id="collection_charge_chart" name="collection_charge_chart" data-toggle="modal" data-target=".collectionChargeChart" style="padding: 10px 35px;width: 100%;"> Fine Chart </button>
								</div>
							</div>

							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 text-center">
								<div class="form-group">
									<button type="button" class="btn btn-primary commitment-chart" data-toggle="modal" data-target="#commitmentChart" style="padding: 10px 35px;width: 100%;"> Commitment Chart </button>
								</div>
							</div>
						</div>

						<hr>
						<div class="row">
							<div class="col-12">
								<h5> Loan summary </h5>
								<button type="button" class="btn btn-primary" id="add_cus_label" name="add_cus_label" data-toggle="modal" data-target=".addloansummary" style="padding: 5px 35px; float: right;"><span class="icon-add"></span></button>
							</div>
						</div> <br>

						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="feedbackListTable">
									<table class="table custom-table modalTable">
										<thead>
											<tr>
												<th width="20%"> S.No </th>
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

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="branch"> Closed Status </label> <span class="required">*</span>
									<select type="text" class="form-control" name="closed_Sts" id="closed_Sts">
										<option value=""> Select Closed Status </option>
										<option value="1"> Consider </option>
										<option value="2"> Waiting List </option>
										<option value="3"> Block List </option>
									</select>
									<span class="text-danger" id="closedStatusCheck" style="display:none;">Please Select Closed Status </span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" style="display: none;" id="considerlevel">
								<div class="form-group">
									<label for="branch"> Consider Level </label> <span class="required">*</span>
									<select type="text" class="form-control" name="closed_Sts_consider" id="closed_Sts_consider">
										<option value=""> Select Consider Level </option>
										<option value="1"> Bronze </option>
										<option value="2"> Silver </option>
										<option value="3"> Gold </option>
										<option value="4"> Platinum </option>
										<option value="5"> Diamond </option>
									</select>
									<span class="text-danger" id="considerLevelCheck" style="display:none;">Please Select Consider Level </span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="remark"> Remark </label> <span class="required">*</span>
									<textarea type="text" class="form-control" name="closed_Sts_remark" id="closed_Sts_remark"></textarea>
									<span class="text-danger" id="remarkCheck" style="display:none;"> Please Enter Remark </span>
								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- NOC Window END-->

				<!-- Submit Button Start -->
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_closed" id="submit_closed" class="btn btn-primary" value="Submit"><span class="icon-check"></span>&nbsp;Submit</button>
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
<!-- Add Loan Summary Modal START -->
<div class="modal fade addloansummary" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Add Loan Summary </h5>
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

					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="feedbackLabel"> Feedback Label </label> <span class="required">&nbsp;*</span>
							<input type="text" class="form-control" id="feedback_label" name="feedback_label" onkeydown="return /[a-z ]/i.test(event.key)" placeholder="Enter Feedback Label">
							<span class="text-danger" id="feedbacklabelCheck"> Enter Feedback Label </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="feedback "> Feedback Rating </label> <span class="required">&nbsp;*</span>
							<select type="text" class="form-control" id="cus_feedback" name="cus_feedback">
								<option value=""> Select Feedback </option>
								<option value="1"> Bad </option>
								<option value="2"> Poor </option>
								<option value="3"> Average </option>
								<option value="4"> Good </option>
								<option value="5"> Excellent </option>
							</select>
							<span class="text-danger" id="feedbackCheck"> Select Feedback </span>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
						<div class="form-group">
							<label for="feedback_remark"> Remark </label>
							<textarea class="form-control" name="feedback_remark" id="feedback_remark"></textarea>
						</div>
					</div>

					<div class="col-xl-12 col-lg-12 col-md-6 col-sm-6 col-12 text-right">
						<input type="hidden" name="feedbackID" id="feedbackID">
						<label style="visibility:hidden"> Submit </label><br><br>
						<button type="button" name="feedbackBtn" id="feedbackBtn" class="btn btn-primary"> Submit </button>
					</div>
				</div>
				</br>


				<div id="feedbackTable" class="table-responsive">
					<table class="table custom-table">
						<thead>
							<tr>
								<th width="20%"> S.No </th>
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
<!-- END  Add Loan Summary Modal -->

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
							<div class="col-12" id='commChartDiv'></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="2">Close</button>
			</div>
		</div>
	</div>
</div>