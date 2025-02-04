<link rel="stylesheet" type="text/css" href="css/loan_followup.css" />
<?php

$getUser = $userObj->getUser($mysqli, $_SESSION['userid']);
if (sizeof($getUser) > 0) {
	$user_name = $getUser['fullname'];
	$user_type = $getUser['role'];
	if ($user_type == '1') {
		$user_type = 'Director';
	} elseif ($user_type == '2') {
		$user_type = 'Agent';
	} elseif ($user_type == '3') {
		$user_type = 'Staff';
	}
}
?>
<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Loan Followup
	</div>

</div><br>

<div class="text-right" style="margin-right: 25px;">
	<button class="btn btn-primary" id='close_history_card' style="display: none;">&times;&nbsp;&nbsp;Cancel</button>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="loan_followup_form" name="loan_followup_form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />

		<div class="row gutters loan-list-card">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Loan Followup</div>
					<div class="card-body">
						<div id="loanListDiv" class="table-responsive">
							<table class="table custom-table" id='loan_follow_table'>
								<thead>
									<th>S.No</th>
									<th>Date</th>
									<th>Customer ID</th>
									<th>Customer Name</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Loan Category</th>
									<th>Sub Category</th>
									<th>Agent</th>
									<th>Branch</th>
									<th>Group</th>
									<th>Line</th>
									<th>View</th>
									<th>Action</th>
									<th>Follow Date</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Loan History START -->
		<div class="row gutters loan-history-card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header"> Loan History </div>
					<div class="card-body">
						<div id="loanHistoryDiv" class="table-responsive">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Loan History END -->

		<!-- Document History START -->
		<div class="row gutters doc-history-card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header"> Document History </div>
					<div class="card-body">
						<div id="docHistoryDiv" class="table-responsive">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Document History END -->
	</form>
</div>

<!-- Modal for Loan Follow Chart just view table   -->
<div class="modal fade" id="loanFollowChartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Loan Follow Chart</h5>
				<button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

					<div class="col-12">
						<div class="row">
							<div class="col-12 table-responsive" id='loanFollowChartDiv'></div>
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

<!-- Modal for Loan follow add -->
<div class="modal fade" id="addLoanFollow" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add Followup</h5>
				<button type="button" class="close closeModal" id="closeAddFollowupModal" data-dismiss="modal" aria-label="Close" >
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row">

					<div class="col-12">
						<div class="row">
							<input type="hidden" name="lfollow_cus_id" id="lfollow_cus_id">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_date">Date</label><span class="required">&nbsp;*</span>
								<input type="text" class='form-control' readonly name="lfollow_date" id="lfollow_date" tabindex="1" value='<?php echo date('d-m-Y'); ?>' />
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_stage">Stage</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_stage" id="lfollow_stage" class='form-control' tabindex="2" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_label">Label</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_label" id="lfollow_label" class='form-control' placeholder="Enter Label" tabindex="3">
								<span class="text-danger" id='lfollow_labelCheck' style="display: none;">Please Enter Label </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_remark">Remark</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_remark" id="lfollow_remark" class='form-control' placeholder="Enter Remark" tabindex="4">
								<span class="text-danger" id='lfollow_remarkCheck' style="display: none;">Please Enter Remark</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_user_type">User Type</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_user_type" id="lfollow_user_type" class='form-control' value='<?php echo $user_type; ?>' tabindex="5" readonly>
								<span class="text-danger" id='lfollow_user_typeCheck' style="display: none;">Please Enter User Type </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_user">User</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_user" id="lfollow_user" class='form-control' value="<?php echo $user_name; ?>" tabindex="6" readonly>
								<span class="text-danger" id='lfollow_userCheck' style="display: none;">Please Enter User </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="lfollow_fdate">Follow Date</label><span class="required">&nbsp;*</span>
								<input type="date" name="lfollow_fdate" id="lfollow_fdate" class='form-control' placeholder="Enter Follow Date" tabindex="7">
								<span class="text-danger" id='lfollow_fdateCheck' style="display: none;">Please Choose Follow Date </span>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button class='btn btn-primary' name="sumit_add_lfollow" id="sumit_add_lfollow" tabindex="8">Submit</button>
				<button class="btn btn-secondary closeModal" data-dismiss="modal" tabindex="9">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for Personal Info   -->
<div class="modal fade" id="personalInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Personal Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row" id='personalInfoDiv'>


				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="7">Close</button>
			</div>
		</div>
	</div>
</div>

<style>
	.dropdown-content {
		color: black;
	}

	@media (max-width: 598px) {
		#loanListDiv {
			overflow: auto;
		}
	}
</style>