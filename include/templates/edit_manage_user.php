<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Users List
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="manage_user">
		<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add User</button>
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
								<div class="alert-text">User Added Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text">User Updated Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 3) { ?>
							<div class="alert alert-danger" role="alert">
								<div class="alert-text">User Inactive Successfully!</div>
							</div>
					<?php
						}
					}
					?>
					<table id="manage_user_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Role</th>
								<th>Role Type</th>
								<th>Name</th>
								<th>User ID</th>
								<th>Company Name</th>
								<th>Branch Name</th>
								<th>Line Name</th>
								<th>Group Name</th>
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