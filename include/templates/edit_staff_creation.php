<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Staff List
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="staff_creation">
		<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Staff Creation</button>
	</a>
</div><br><br>
<!-- Page header end -->

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
								<div class="alert-text">Staff Added Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text">Staff Updated Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 3) { ?>
							<div class="alert alert-danger" role="alert">
								<div class="alert-text">Staff Inactive Successfully!</div>
							</div>
					<?php
						}
					}
					?>
					<table id="staff_creation_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Staff Code</th>
								<th>Staff Name</th>
								<th>Staff Type</th>
								<th>Place</th>
								<th>Company Name</th>
								<th>Department</th>
								<th>Team</th>
								<th>Designation</th>
								<th>Status</th>
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