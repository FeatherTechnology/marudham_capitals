<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Accounts Loan Issue
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
								<div class="alert-text"> Loan Issued Details Submitted Successfully! </div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Approval Removed Successfully! </div>
							</div>
					<?php
						}
					}
					?>
					<table id="accountsloanIssue_table" class="table custom-table">
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


