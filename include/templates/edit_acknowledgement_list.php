<style>
	/* Force borders for grouped headers */
/* Ensure borders are visible */
#cusHistoryTable {
    border-collapse: collapse !important;
}

#cusHistoryTable thead th {
    border: 1px solid #ffffff;
}

/* ===== GROUP HEADER BORDER ===== */
#cusHistoryTable thead th.group-border {
    border-right: 1px solid #ffffff !important;
}

</style>
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
		Marudham Capitals - Acknowledgement List
	</div>
</div><br>

<!-- Page header end -->
<input type="hidden" id="pending_sts">
<input type="hidden" id="od_sts">
<input type="hidden" id="due_nil_sts">
<input type="hidden" id="closed_sts">
<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container">

				<div class="table-responsive">
					<?php
					$mscid = 0;
					if (isset($_GET['msc'])) {
						$mscid = $_GET['msc'];
						if ($mscid == 1) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Acknowledgement Cancelled Successfully! </div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Acknowledgement Removed Successfully! </div>
							</div>
					<?php
						}
					}
					?>
					<table id="acknowledge_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Requested Date</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Branch</th>
								<th>Group</th>
								<th>Line</th>
								<th>Mobile</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
								<th>Loan Amount</th>
								<th>User Type</th>
								<th>User</th>
								<th>Agent Name</th>
								<th>Responsible</th>
								<th>Customer Data</th>
								<th>Customer Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->


<!-- Add Course Category Modal -->
<div class="modal fade customerstatus" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Customer Status</h5>
				<button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<input type="hidden" name="req_id" id="req_id">
							<!-- <label class="label">Existing Type</label>
							<input type="text" name="exist_type" id="exist_type" class="form-control" readonly > -->
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12"></div>
				</div>
				<div id="updatedcusHistoryTable" style="overflow-x: scroll;">
					<table class="table custom-table" id="cusHistoryTable">
						<thead>
							<tr>
								<th width="25" rowspan="2">S. No</th>
								<th rowspan="2" class="group-border">Date</th>
								<th rowspan="2" class="group-border">Loan Category</th>
								<th rowspan="2" class="group-border">Sub Category</th>
								<th rowspan="2" class="group-border">Amount</th>
								<th colspan="3" class="group-border">Loan Status</th>
								<th colspan="3" class="group-border">Document Status</th>
							</tr>
							<tr>
								<th>Status</th>
								<th>Sub Status</th>
								<th>Remark</th>
								<th>Status</th>
								<th>Acknowledgement Remark</th>
								<th>Update Remark</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- Loan Summary Modal -->
<div class="modal fade loansummary" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Loan Summary</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeLoanModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<input type="hidden" name="req_id" id="req_id">
							<!-- <label class="label">Existing Type</label>
							<input type="text" name="exist_type" id="exist_type" class="form-control" readonly > -->
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12"></div>
				</div>
				<div id="updatedloanSummaryTable">
					<table class="table custom-table" id="loanSummaryTable">
						<thead>
							<tr>
								<th width="25">S. No</th>
								<th>Feedback Label</th>
								<th>Feedback Rating</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeLoanModal()">Close</button>
			</div>
		</div>
	</div>
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
				<button type="button" class="close closeModal" id="closeAddFollowupModal" data-dismiss="modal" aria-label="Close">
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
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2">
								<label for="lfollow_remark">Remark</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_remark" id="lfollow_remark" class='form-control' placeholder="Enter Remark" tabindex="4">
								<span class="text-danger" id='lfollow_remarkCheck' style="display: none;">Please Enter Remark</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2">
								<label for="lfollow_user_type">User Type</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_user_type" id="lfollow_user_type" class='form-control' value='<?php echo $user_type; ?>' tabindex="5" readonly>
								<span class="text-danger" id='lfollow_user_typeCheck' style="display: none;">Please Enter User Type </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2">
								<label for="lfollow_user">User</label><span class="required">&nbsp;*</span>
								<input type="text" name="lfollow_user" id="lfollow_user" class='form-control' value="<?php echo $user_name; ?>" tabindex="6" readonly>
								<span class="text-danger" id='lfollow_userCheck' style="display: none;">Please Enter User </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2">
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