<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Agent List
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="agent_creation">
		<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Agent Creation</button>
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
								<div class="alert-text">Agent Added Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text">Agent Updated Successfully!</div>
							</div>
						<?php
						}
						if ($mscid == 3) { ?>
							<div class="alert alert-danger" role="alert">
								<div class="alert-text">Agent Inactive Successfully!</div>
							</div>
					<?php
						}
					}
					?>
					<table id="agent_creation_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Company Name</th>
								<!-- <th>Branch Name</th> -->
								<th>Agent Name</th>
								<th>Agent Group</th>
								<th>Place</th>
								<th>District</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
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