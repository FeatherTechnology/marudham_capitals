<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Loan Scheme List
	</div>
</div><br>

<!-- Page header end -->


<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group" style="text-align:center">
				<label for=''style="font-size:16px" >Scheme Type:</label><br><br>
				<input type="radio" name="scheme_type" id="monthly" value="monthly" <?php if(isset($_GET['type']) and $_GET['type'] == 'monthly') echo 'checked';?>></input><label for='monthly' >&nbsp;&nbsp;Monthly</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="scheme_type" id="weekly" value="weekly" <?php if(isset($_GET['type']) and $_GET['type'] == 'weekly') echo 'checked';?>></input><label for='weekly'>&nbsp;&nbsp;Weekly</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="scheme_type" id="daily" value="daily" <?php if(isset($_GET['type']) and $_GET['type'] == 'daily') echo 'checked';?>></input><label for='daily'>&nbsp;&nbsp;Daily</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
		</div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<!-- ******************************************************************** Monthly Scheme List ***************************************************************************** -->
			<div class="table-container monthly_scheme" <?php if(isset($_GET['type']) and $_GET['type'] == 'monthly') {?> style="display:block"<?php }else{ ?> style="display:none"<?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
					<a href="loan_scheme&type=monthly">
						<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Monthly Scheme</button>
					</a>
				</div><br><br>
				<div class="table-responsive">
					<?php
					$mscid=0;
					if(isset($_GET['msc']))
					{
					$mscid=$_GET['msc'];
					if($mscid==1)
					{?>
					<div class="alert alert-success" role="alert">
						<div class="alert-text">Loan Scheme Added Successfully!</div>
					</div> 
					<?php
					}
					if($mscid==2)
					{?>
						<div class="alert alert-success" role="alert">
						<div class="alert-text">Loan Scheme Updated Successfully!</div>
					</div>
					<?php
					}
					if($mscid==3)
					{?>
					<div class="alert alert-danger" role="alert">
						<div class="alert-text">Loan Scheme Inactive Successfully!</div>
					</div>
					<?php
					}
					}
					?>
					<table id="loan_scheme_monthly_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S. No.</th>
								<th>Scheme Name</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
								<th>Due Method</th>
								<!-- <th>Interest Rate</th> -->
								<th>Due Period</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			
			<!-- ******************************************************************** Weekly Scheme List ***************************************************************************** -->
			<div class="table-container weekly_scheme" <?php if(isset($_GET['type']) and $_GET['type'] == 'weekly') {?> style="display:block"<?php }else{ ?> style="display:none"<?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
					<a href="loan_scheme&type=weekly">
						<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Weekly Scheme</button>
					</a>
				</div><br><br>
				<div class="table-responsive">
					<?php
					$mscid=0;
					if(isset($_GET['msc']))
					{
					$mscid=$_GET['msc'];
					if($mscid==1)
					{?>
					<div class="alert alert-success" role="alert">
						<div class="alert-text">Loan Scheme Added Successfully!</div>
					</div> 
					<?php
					}
					if($mscid==2)
					{?>
						<div class="alert alert-success" role="alert">
						<div class="alert-text">Loan Scheme Updated Successfully!</div>
					</div>
					<?php
					}
					if($mscid==3)
					{?>
					<div class="alert alert-danger" role="alert">
						<div class="alert-text">Loan Scheme Inactive Successfully!</div>
					</div>
					<?php
					}
					}
					?>
					<table id="loan_scheme_weekly_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S. No.</th>
								<th>Scheme Name</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
								<th>Due Method</th>
								<!-- <th>Interest Rate</th> -->
								<th>Due Period</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			
			<!-- ******************************************************************** Daily Scheme List ***************************************************************************** -->
			<div class="table-container daily_scheme" <?php if(isset($_GET['type']) and $_GET['type'] == 'daily') {?> style="display:block"<?php }else{ ?> style="display:none"<?php } ?>>
				<div class="text-right" style="margin-right: 25px;">
					<a href="loan_scheme&type=daily">
						<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Daily Scheme</button>
					</a>
				</div><br><br>
				<div class="table-responsive">
					<?php
					$mscid=0;
					if(isset($_GET['msc']))
					{
					$mscid=$_GET['msc'];
					if($mscid==1)
					{?>
					<div class="alert alert-success" role="alert">
						<div class="alert-text">Loan Scheme Added Successfully!</div>
					</div> 
					<?php
					}
					if($mscid==2)
					{?>
						<div class="alert alert-success" role="alert">
						<div class="alert-text">Loan Scheme Updated Successfully!</div>
					</div>
					<?php
					}
					if($mscid==3)
					{?>
					<div class="alert alert-danger" role="alert">
						<div class="alert-text">Loan Scheme Inactive Successfully!</div>
					</div>
					<?php
					}
					}
					?>
					<table id="loan_scheme_daily_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S. No.</th>
								<th>Scheme Name</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
								<th>Due Method</th>
								<!-- <th>Interest Rate</th> -->
								<th>Due Period</th>
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

	

