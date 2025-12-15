<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - NOC Handover Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="noc_handover_report_form" name="noc_handover_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="noc_report_card">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Reload'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">NOC Handover Report</div>
					<div class="card-body">
						<div id="noc_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="noc_handover_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>Loan ID</th>
									<th>Doc ID</th>
									<th>Aadhaar Number</th>
									<th>Cust. ID</th>
									<th>Cust. Name</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Line</th>
									<th>Branch</th>
									<th>Handover Date</th>
									<th>User Name</th>
									<th>Received By</th>
									<th>Relationship</th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="7"></td>
										<td></td>
										<td></td>
										<td colspan="5"></td>
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