<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Closed
	</div>
</div><br>
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
					$id = 0;
					if (isset($_GET['msc'])) {
						$mscid = $_GET['msc'];
						if ($mscid == 1) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Closed Submitted Successfully! </div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Collection Removed Successfully! </div>
							</div>
					<?php
						}
					}
					?>
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="branch_filter">Branch</label>
							<select class="" id="branch_filter" name="branch_filter" multiple>
								<option value=''>Select Branch name</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="region_filter">Region</label>
							<select class="" id="region_filter" name="region_filter" multiple>
								<option value=''>Select Region</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<button type="button" class="btn btn-primary" id="search_loan" style="margin-top:20px;">Search</button>
						</div>
					</div>
					<hr>
					<table id="closed_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>In Closed Date</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Region</th>
								<th>Mobile</th>
								
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
<div id="printcollection" style="display: none"></div>