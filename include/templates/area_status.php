<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Area Status
	</div>
</div><br>
<br><br>
<!-- Page header end -->


<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">

		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group" style="text-align:center">
				<input type="radio" name="area_status" id="area" value="area"></input><label for='area'>&nbsp;&nbsp;Area</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="area_status" id="sub_area" value="sub_area"></input><label for='sub_area'>&nbsp;&nbsp;Sub Area</label>
			</div>
		</div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container area_status" <?php if (isset($_GET['type']) and $_GET['type'] == 'line') { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
				</div><br><br>
				<div class="table-responsive">
					<div class="alert alert-success" role="alert" id="area_enable" style="display:none">
						<div class="alert-text">Area Enabled Successfully!</div>
					</div>
					<div class="alert alert-success" role="alert" id="area_disable" style="display:none">
						<div class="alert-text">Area Disabled Successfully!</div>
					</div>
					<table id="area_status_table" class="table custom-table">
						<thead>
							<tr>
								<th width="25%">S. No.</th>
								<th>Area Name</th>
								<th>Enable / Disable</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<div class="table-container sub_area_status" <?php if (isset($_GET['type']) and $_GET['type'] == 'group') { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
				</div><br><br>
				<div class="table-responsive">
					<div class="alert alert-success" role="alert" id="sub_area_enable" style="display:none">
						<div class="alert-text">Sub Area Enabled Successfully!</div>
					</div>
					<div class="alert alert-success" role="alert" id="sub_area_disable" style="display:none">
						<div class="alert-text">Sub Area Disabled Successfully!</div>
					</div>
					<table id="sub_area_status_table" class="table custom-table">
						<thead>
							<tr>
								<th width="25%">S. No.</th>
								<th>Sub Area Name</th>
								<th>Area Name</th>
								<th>Enable / Disable</th>
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