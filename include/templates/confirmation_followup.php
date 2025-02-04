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
		Marudham Capitals - Confirmation Followup
	</div>

</div><br>

<div class="text-right" style="margin-right: 25px;">
	<button class="btn btn-primary" id='close_history_card' style="display: none;">&times;&nbsp;&nbsp;Cancel</button>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="confirmation_followup_form" name="confirmation_followup_form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />

		<div class="row gutters conf-list-card">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Confirmation Followup</div>
					<div class="card-body">
						<div id="confListDiv" class="table-responsive">
							<table class="table custom-table" id='conf_follow_table'>
								<thead>
									<th>S.No</th>
									<th>Date</th>
									<th>Customer ID</th>
									<th>Customer Name</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Branch</th>
									<th>Group</th>
									<th>Line</th>
									<th>Mobile No.</th>
									<th>View</th>
									<th>Action</th>
									<th>Status</th>
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
<div class="modal fade" id="confChartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Confirmation Chart</h5>
				<button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

					<div class="col-12">
						<div class="row">
							<div class="col-12 table-responsive" id='confChartDiv'></div>
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
<div class="modal fade" id="addConfimation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add Confirmation</h5>
				<button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row">

					<div class="col-12">
						<div class="row">
							<input type="hidden" name="conf_req_id" id="conf_req_id">
							<input type="hidden" name="conf_cus_id" id="conf_cus_id">
							<input type="hidden" name="conf_cus_name" id="conf_cus_name">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_date">Date</label><span class="required">&nbsp;*</span>
								<input type="text" class='form-control' readonly name="conf_date" id="conf_date" tabindex="1" value='<?php echo date('d-m-Y'); ?>' />
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_person_type">Person Type</label><span class="required">&nbsp;*</span>
								<select name="conf_person_type" id="conf_person_type" class='form-control' tabindex="2">
									<option value="">Select Person Type</option>
									<option value="1">Customer</option>
									<option value="2">Guarentor</option>
									<option value="3">Family Member</option>
								</select>
								<span class="text-danger" id="conf_person_typeCheck" style="display:none">Please Select Person Type</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_person_name">Person Name</label><span class="required">&nbsp;*</span>
								<input type="text" name="conf_person_name" id="conf_person_name" class='form-control' tabindex="3" readonly>
								<select name="conf_person_name1" id="conf_person_name1" class='form-control' tabindex="3" style="display: none;"></select>
								<span class="text-danger" id="conf_person_nameCheck" style="display:none">Please Select Person Name</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_relationship">Relationship</label><span class="required">&nbsp;*</span>
								<input type="text" name="conf_relationship" id="conf_relationship" class='form-control' tabindex="4" readonly>
								<span class="text-danger" id="conf_relationshipCheck" style="display:none">Please Select Relationship</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_mobile">Mobile</label><span class="required">&nbsp;*</span>
								<input type="text" name="conf_mobile" id="conf_mobile" class='form-control' tabindex="5" placeholder='Enter Mobile Number' readonly>
								<!-- <span class="text-danger" id='conf_mobileCheck' style="display: none;">Please Enter Mobile Number </span> -->
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_upload">Upload</label>
								<input type="file" onchange="compressImage(this,800)" name="conf_upload" id="conf_upload" class='form-control' tabindex="6" title="Upload Call records">
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="conf_status">Status</label><span class="required">&nbsp;*</span>
								<select name="conf_status" id="conf_status" class='form-control' tabindex="7">
									<option value="">Select Status</option>
									<option value="1">Completed</option>
									<option value="2">Unavailable</option>
									<option value="3">Reconfirmation</option>
								</select>
								<span class="text-danger" id='conf_statusCheck' style="display: none;">Please Select Status</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 unav-div" style="display: none">
								<label for="conf_sub_status">Sub Status</label><span class="required">&nbsp;*</span>
								<select name="conf_sub_status" id="conf_sub_status" class='form-control' tabindex="8">
									<option value="">Select Sub Status</option>
									<option value="1">RNR</option>
									<option value="2">Not Reachable</option>
									<option value="3">Switch off</option>
									<option value="4">Blocked</option>
									<option value="5">Not in use</option>
								</select>
								<span class="text-danger" id='conf_sub_statusCheck' style="display: none;">Please Select Sub Status</span>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 reconf-div" style="display: none">
								<label for="conf_label">Label</label><span class="required">&nbsp;*</span>
								<input type="text" name="conf_label" id="conf_label" class='form-control' tabindex="9" placeholder="Enter Label">
								<span class="text-danger" id='conf_labelCheck' style="display: none;">Please Enter Label</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 reconf-div" style="display: none">
								<label for="conf_remark">Remark</label><span class="required">&nbsp;*</span>
								<input type="text" name="conf_remark" id="conf_remark" class='form-control' tabindex="10" placeholder="Enter Remark">
								<span class="text-danger" id='conf_remarkCheck' style="display: none;">Please Enter Remark</span>
							</div>

						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button class='btn btn-primary' name="sumit_add_conf" id="sumit_add_conf" tabindex="11">Submit</button>
				<button class="btn btn-secondary closeModal" data-dismiss="modal" tabindex="12">Close</button>
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
    .dropdown-content{
        color: black;
    }
    @media (max-width: 598px) {
        #confChartDiv{
            overflow: auto;
        }
    }
    
</style>