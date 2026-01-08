<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - No Due Pay Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="no_pay_due_report_form" name="no_pay_due_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="report_card">
			<div class="toggle-container col-12">
				<input type="month" id="from_date" name="from_date" class="toggle-button" value="">
				<input type="button" id="reset_btn" name="reset_btn" class="toggle-button" style="background-color: #009688; color: white" value="Reload">
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">No Due Pay Report</div>
					<div class="card-body">
						<div id="report_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="no_pay_due_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<!-- <th>Group</th> -->
									<th>Line</th>
									<!-- <th>Followup</th> -->
									<th>Loan ID</th>
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
									<th>User Type</th>
									<th>User</th>
									<th>Due Amount</th>
									<th>Payable</th>
									<th>Status</th>
									<th>Sub Status</th>
									<th>One Month</th>
									<th>Two Month</th>
									<th>Three Month</th>
									<th>Four Month</th>
									<th>Five Month</th>
									<th>Above Five Month</th>
									<th>Balance Amount</th>
								</thead>
								<tbody>
								</tbody>

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>