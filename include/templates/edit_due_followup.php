<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
<style>
	.filter-btn-div {
		float: right;
		padding-bottom: 10px;
		padding-right: 10px;
	}

	.filter-btn {
		color: #ffffff;
		background-color: #009688;
		border-color: #009688;
		border-bottom: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 3px;
		border: 1px solid transparent;
		font-size: 17px;
		padding: 7px 12px;
	}
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Due Follow Up
	</div>
</div>
<br><br>

<input type="hidden" name="pending_sts" id="pending_sts" value="" />
<input type="hidden" name="od_sts" id="od_sts" value="" />
<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
<input type="hidden" name="closed_sts" id="closed_sts" value="" />
<input type="hidden" name="balAmnt" id="balAmnt" value="" />


<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container">
				<div class="row">
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
						<label for="sub_status_mapping">Customer Status</label><span class="required">&nbsp;*</span>
						<input type="hidden" name="customer_status"  id="customer_status" value="<?php echo isset($_GET['cussts']) ? $_GET['cussts'] : '';?>">
						<select class="form-control" id="sub_status_mapping" name="sub_status_mapping" multiple>
							<option value="">Select Customer Status</option>
						</select>
						<span class='text-danger subStatusCheck' style="display:none">Please Select Customer Status </span>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12" style="margin-top:24px">
						<button type="button" class="btn btn-primary" id="show_due_followup">Proceed</button>
					</div>
				</div>	
			<br>
				<div class="table-responsive" id='dueFollwupDiv'>
					<table id='due_followup_table' class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Line</th>
								<th>Mobile</th>
								<th>Sub Status</th>
								<th>Action</th>
								<th>Last Paid Date</th>
								<th>Hint</th>
								<th>Communication Status</th>
								<th>Commitment Date</th>
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