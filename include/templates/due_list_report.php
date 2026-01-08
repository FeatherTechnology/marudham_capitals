<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Due List Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="due_list_report_form" name="due_list_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="due_list_card">

			<div class="toggle-container col-12">
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Reload'>
			</div>
            
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Due List Report</div>
					<div class="card-body">
						<div id="due_list_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="due_list_report_table" class="table custom-table">
								<thead>
                                <th>S.No</th>
                                <!-- <th>Group</th> -->
                                <th>Line</th>
                                <!-- <th>Followup</th> -->
                                <th>Loan ID</th>
                                <th>Loan Date</th>
                                <th>Due start Date</th>
                                <th>Maturity Date</th>
                                <th>Aadhaar Number</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Mobile Number</th>
                                <th>Area</th>
                                <th>Sub Area</th>
                                <th>Loan Category</th>
                                <th>Sub Category</th>
                                <th>Agent</th>
                                <th>Responsible</th>
                                <th>Guarantor Name</th>
                                <th>Guarantor Relationship</th>
                                <th>Guarantor Mobile</th>
                                <th>Loan Amount</th>
                                <th>Due Amount</th>
                                <th>Number Of Dues</th>
                                <th>Total Amount</th>
                                <th>Balance Amount</th>
                                <th>No Of Balance Due</th>
                                <th>Pending Due Amount</th>
                                <th>Pending Due</th>
                                <th>OD Months</th>
                                <th>Payable Amount</th>
                                <th>Status</th>
                                <th>Sub Status</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>