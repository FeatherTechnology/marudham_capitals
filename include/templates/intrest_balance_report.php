<style>
	#report_type{
		width: 200px;
		height: 43px;
		margin-bottom: 25px;
	}
</style>

<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Balance Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="balance_report_form" name="balance_report_form" action="" method="post" enctype="multipart/form-data">
		<div class="row gutters" id="balance_card">
			<div class="toggle-container col-12">

				<input type="date" id='to_date' name='to_date' class="toggle-button" value='' style="margin-bottom: 25px;">
				
				<select type="text" class="form-control" id="loan_category" name="loan_category" multiple >
					<option value="">Select Loan Category</option>
				</select>

				<select type="text" class="toggle-button" name="report_type" id="report_type">
					<option value="">Select report type</option>
					<option value="1">Balance</option>
					<option value="2">Principal/Interest</option>
				</select>

				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white; margin-bottom:25px" value='Reload'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Balance Report</div>
					<div class="card-body">

						<div id="balance_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="balance_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>Group</th>
									<th>Line</th>
									<th>Followup</th>
									<th>Loan ID</th>
									<th>Doc ID</th>
									<th>Loan Date</th>
									<th>Maturity Date</th>
									<th>Aadhaar Number</th>
									<th>Cust. ID</th>
									<th>Cust. Name</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Loan Category</th>
									<th>Sub Category</th>
									<th>Agent</th>
									<th>Loan Amount</th>
									<th>Interest Amount</th>
									<th>No of Due</th>
									<th>Balance Amount</th>
									<th>No of Balance Due</th>
									<th>Penalty</th>
									<th>Fine</th>
									<th>Status</th>
									<th>Sub Status</th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="13"></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
							</table>
						</div>


						<div id="princ_intrst_table_div" class="table-divs" style="overflow-x: auto; display: none;">
							<table id="princ_intrst_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>Group</th>
									<th>Line</th>
									<th>Followup</th>
									<th>Loan ID</th>
									<th>Doc ID</th>
									<th>Loan Date</th>
									<th>Maturity Date</th>
									<th>Aadhaar Number</th>
									<th>Cust. ID</th>
									<th>Cust. Name</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Loan Category</th>
									<th>Sub Category</th>
									<th>Agent</th>
									<th>Loan Amount</th>
									<th>No of Due</th>
									<th>Balance Amount</th>
									<th>Principal Amount</th>
									<th>Interest Amount</th>
									<th>Penalty</th>
									<th>Fine</th>
									<th>Status</th>
									<th>Sub Status</th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="14"></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
							</table>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</form>
</div>