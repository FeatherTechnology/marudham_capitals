<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Area List
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="area_creation">
		<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Area Creation</button>
		<!-- <button type="button" class="btn btn-primary"><span class="icon-border_color"></span>&nbsp Edit Employee Master</button> -->
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
								<div class="alert-text">Area Creation Added Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text">Area Creation Updated Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 3) { ?>
							<div class="alert alert-danger" role="alert">
								<div class="alert-text">Area Creation Inactive Successfully!</div>
							</div>
					<?php
						}
					}
					?>
					<table id="area_creation_info" class="table custom-table">
						<thead>
							<tr>
								<th>S. No.</th>
								<th>Area Name</th>
								<th>Sub Area</th>
								<th>Taluk</th>
								<th>District</th>
								<th>State</th>
								<th>Pincode</th>
								<!-- <th width="100px">Enabled / Disabled</th> -->
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