<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Area Mapping List
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
				<label for=''style="font-size:16px" >Mapping Type:</label><br><br>
				<input type="radio" name="mapping_type" id="group" value="group" <?php if(isset($_GET['type']) and $_GET['type'] == 'group') echo 'checked';?>></input><label for='group'>&nbsp;&nbsp;Group</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mapping_type" id="line" value="line" <?php if(isset($_GET['type']) and $_GET['type'] == 'line') echo 'checked';?>></input><label for='line' >&nbsp;&nbsp;Line</label>
			</div>
		</div>
		<!-- <div class="col-md-12 "> 
			<div class="row">
				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12"></div>
				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
					<div class="form-group" style="text-align:center;">
						<label >Search</label><input type="text" id="filter" name="filter" class='form-control' width="50px">
					</div>
				</div>
			</div>
		</div> -->
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container line_mapping" <?php if(isset($_GET['type']) and $_GET['type'] == 'line') {?> style="display:block"<?php }else{ ?> style="display:none"<?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
					<a href="area_mapping&type=line">
						<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Line Mapping</button>
					</a>
				</div><br><br>
				
				<div class="table-responsive">
					<?php
					$mscid=0;
					if(isset($_GET['msc']))
					{
					$mscid=$_GET['msc'];
					if($mscid==1)
					{ ?>
					<!-- <script>alert('Area Mapping Added Successfully!')</script> -->
					<div class="alert alert-success" role="alert">
						<div class="alert-text">Area Mapping Added Successfully!</div>
					</div> 
					<?php
					}
					if($mscid==2)
					{?>
					<!-- <script>alert('Area Mapping Updated Successfully!')</script> -->
					<div class="alert alert-success" role="alert">
						<div class="alert-text">Area Mapping Updated Successfully!</div>
					</div>
					<?php
					}
					if($mscid==3)
					{?>
					<!-- <script>alert('Area Mapping Inactive Successfully!')</script> -->
					<div class="alert alert-danger" role="alert">
						<div class="alert-text">Area Mapping Inactive Successfully!</div>
					</div>
					<?php
					}
					}
					?>
					<table id="area_mapping_line_info" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S. No.</th>
								<th>Line Name</th>
								<th>Company Name</th>
								<th>Branch Name</th>
								<th>Area Name</th>
								<th>Sub Area</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="table-container group_mapping" <?php if(isset($_GET['type']) and $_GET['type'] == 'group') {?> style="display:block"<?php }else{ ?> style="display:none"<?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
					<a href="area_mapping&type=group">
						<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Group Mapping</button>
					</a>
				</div><br><br>
				<!-- <div class='form-control' style="background-color:#009688;color:white;text-align:center;font-size:17px;border-radius:3px;">Group List</div><br> -->
				<div class="table-responsive">
					<?php
					$mscid=0;
					if(isset($_GET['msc']))
					{
					$mscid=$_GET['msc'];
					if($mscid==1)
					{?>
					<div class="alert alert-success" role="alert">
						<div class="alert-text">Area Mapping Added Successfully!</div>
					</div> 
					<?php
					}
					if($mscid==2)
					{?>
						<div class="alert alert-success" role="alert">
						<div class="alert-text">Area Mapping Updated Successfully!</div>
					</div>
					<?php
					}
					if($mscid==3)
					{?>
					<div class="alert alert-danger" role="alert">
						<div class="alert-text">Area Mapping Inactive Successfully!</div>
					</div>
					<?php
					}
					}
					?>
					<table id="area_mapping_group_info" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S. No.</th>
								<th>Group Name</th>
								<th>Company Name</th>
								<th>Branch Name</th>
								<th>Area Name</th>
								<th>Sub Area</th>
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

	

