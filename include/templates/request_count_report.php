<style>
	/* Force borders for grouped headers */
/* Ensure borders are visible */
#request_count_table {
    border-collapse: collapse !important;
}

#request_count_table thead th {
    border: 1px solid #ffffff;
}

/* ===== GROUP HEADER BORDER ===== */
#request_count_table thead th.group-border {
    border-right: 1px solid #ffffff !important;
}
</style>

<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Request Count Report
	</div>
</div><br>
<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form name="request_count_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters">

			<div class="toggle-container col-12 reports_filter_card">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				
                <select class="toggle-button" name='type' id='type'>
					<option value='0'>Select Type</option>
					<option value='1'>User</option>
					<option value='2'>Sector</option>
					<!-- <option value='3'>Region</option>
					<option value='4'>Zone</option> -->
				</select>

				<select class="toggle-button hidefield" id='user_type' name='user_type'>
                    <option value=''>Select User Type</option>
                    <option value='1'>All</option>
                    <option value='2'>Active</option>
                    <option value='3'>In Active</option>
                </select>

				<select class="toggle-button hidefield" id='by_user' name='by_user'>
					<option value=''>Select User</option>
				</select>
				
                <select class="form-control hidefield" id="map_name" name="map_name" multiple>
                    <option value="">Select Sector</option>
                </select>&nbsp;&nbsp;
	
				<select class="form-control hidefield" id="loan_category" name="loan_category" multiple>
					<option value="">Select Loan Category</option>
				</select>

				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
			</div>

			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Request Count Report</div>
					<div class="card-body">
						<div id="request_count_report_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="request_count_table" class="table custom-table" style="width:100%">
								<thead>
									<!-- GROUP HEADER -->
									<tr>
										<th rowspan="2">S.No</th>
										<th rowspan="2">User Name</th>
										<th rowspan="2">Loan Category</th>
                                        <th colspan="6" class="group-border">Previous In Process</th>
										<th colspan="6" class="group-border">Request</th>
										<th colspan="6" class="group-border">Cancel</th>
										<th colspan="6" class="group-border">Revoke</th>
										<th colspan="6" class="group-border">In Process</th>
										<th colspan="6" class="group-border">Issued</th>
										<th colspan="6" class="group-border">Status</th>
									</tr>

									<!-- SUB HEADERS -->
									<tr>
										<!-- Previous In Process -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-active</th>
										<th>Additional</th>
										<th>Existing-New</th>
										<th>Total</th>
										<!-- Request -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-active</th>
										<th>Additional</th>
										<th>Existing-New</th>
										<th>Total</th>

										<!-- Cancel -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-active</th>
										<th>Additional</th>
										<th>Existing-New</th>
										<th>Total</th>

										<!-- Revoke -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-active</th>
										<th>Additional</th>
										<th>Existing-New</th>
										<th>Total</th>

										<!-- Process -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-active</th>
										<th>Additional</th>
										<th>Existing-New</th>
										<th>Total</th>

										<!-- Issued -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-active</th>
										<th>Additional</th>
										<th>Existing-New</th>
										<th>Total</th>

										<!-- Status -->
										<th>Current</th>
										<th>Pending</th>
										<th>OD</th>
										<th>Error</th>
										<th>Legal</th>
										<th>Total</th>
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