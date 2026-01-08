<style>
	.choices__inner {
		margin-top: 30px !important;
	}

	.choices__input {
		width: 200px !important;
	}
</style>
<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#2f958bd9; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		<!-- Marudham Capitals - Customer Status Report   name changes to  Collection Status Report-->
		Marudham Capitals - Collection Status Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="customer_status_report_form" name="customer_status_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters">

			<div class="toggle-container col-12">
				<input type="date" class="toggle-button" name='search_date' id='search_date' value=''>
				<select class="toggle-button" name='type' id='type'>
					<option value=''>Select Type</option>
					<option value='1'>Line</option>
					<option value='2'>User</option>
					<option value='3'>Group</option>
					<option value='4'>Due Followup</option>
				</select>
				<select type="text" class="form-control" id="line" name="line" multiple style="display: none;">
					<option value="">Select Line</option>
				</select>
				<select type="text" class="toggle-button" id='by_user' name='by_user' style="display: none;">
					<option value=''>Select User</option>
				</select>
				<select type="text" class="form-control" id="group_map" name="group_map" multiple style="display: none;">
					<option value="">Select Group</option>
				</select>
				<select type="text" class="form-control" id="due_followup" name="due_followup" multiple style="display: none;">
					<option value="">Select Due Followup</option>
				</select> &nbsp;&nbsp;
				<select type="text" class="form-control" id="loan_category" name="loan_category" multiple>
					<option value="">Select Loan Category</option>
				</select>
				<select class="toggle-button" name='sub_status_type' id='sub_status_type'>
					<option value=''>Select Sub Status</option>
					<option value='1'>Current</option>
					<option value='2'>Pending</option>
					<option value='3'>OD</option>
				</select>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #2f958bd9;color:white" value='Search'>
			</div>

			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<!-- Customer Status Report   name changes to  Collection Status Report-->
					<div class="card-header">Collection Status Report</div>
					<div class="card-body">
						<div id="pending_od_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="current_table" class="table custom-table" style="display: none;">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Date</th>
										<th id="nameHeader">User Name</th>
										<th>Loan Category</th>
										<th>Total Customer Count</th>
										<th>Total Current Count</th>
										<th>Payable Zero</th>
										<th>Responsible</th>
										<th>Balance Count</th>
										<th>Paid</th>
										<th>Partial Paid</th>
										<th>Total Paid</th>
										<th>Paid %</th>
										<th>Un Paid</th>
										<th>Unpaid %</th>
										<th>From Pending</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
							<table id="pending_table" class="table custom-table" style="display: none;">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Date</th>
										<th id="nameHeader">User Name</th>
										<th>Loan Category</th>
										<th>Total Customer Count</th>
										<th>Total Pending Count</th>
										<th>Today Pending Clear</th>
										<th>Total Pending Clear</th>
										<th>Partial Paid</th>
										<th>Total Paid</th>
										<th>Paid %</th>
										<th>Un Paid</th>
										<th>Unpaid %</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
							<table id="od_table" class="table custom-table" style="display: none;">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Date</th>
										<th id="nameHeader">User Name</th>
										<th>Loan Category</th>
										<th>Total Customer Count</th>
										<th>Total OD Count</th>
										<th>Today OD Clear</th>
										<th>Total OD Clear</th>
										<th>Partial Paid</th>
										<th>Total Paid</th>
										<th>Paid %</th>
										<th>Un Paid</th>
										<th>Unpaid %</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>