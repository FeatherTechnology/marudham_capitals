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

		<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12"></div>
		<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
			<div class="form-group" style="text-align:center">
				<label for=''style="font-size:16px" >Staff Status:</label><br><br>
				<input type="radio" name="staff_status" id="active" value="active" <?php if(isset($_GET['sts']) and $_GET['sts'] == '0') echo 'checked';?>></input><label for='active'>&nbsp;&nbsp;Active</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="staff_status" id="inactive" value="inactive" <?php if(isset($_GET['sts']) and $_GET['sts'] == '1') echo 'checked';?>></input><label for='inactive'>&nbsp;&nbsp;In-Active</label>
			</div>
		</div>
		<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12"></div>

		<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-12">
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
		</div>

		<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-12" id="staff_creation_table_div" style="display: none;">
			<div class="table-container">
				<div class="table-responsive">
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
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->