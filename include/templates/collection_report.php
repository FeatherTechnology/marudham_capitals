<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Collection Report
	</div>

</div><br>

<div class="text-right" style="margin-right: 25px;">
	<!-- <button class="btn btn-primary" id='close_history_card' style="display: none;" >&times;&nbsp;&nbsp;Cancel</button> -->
</div>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="collection_report_form" name="collection_report_form" action="" method="post" enctype="multipart/form-data">


		<div class="row gutters" id="collection_card">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Reload'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Collection Report</div>
					<div class="card-body">
						<div id="collection_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="collection_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>Line</th>
									<th>Loan ID</th>
									<th>Loan Date</th>
									<th>Cust. ID</th>
									<th>Cust. Name</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Loan Category</th>
									<th>Sub Category</th>
									<th>Agent</th>
									<th>User Type</th>
									<th>User</th>
									<th>Receipt Date</th>
									<th>Collection Mode</th>
									<th>Due Amount</th>
									<th>Principal Amount</th>
									<th>Interest Amount</th>
									<th>Penalty</th>
									<th>Fine</th>
									<th>Total Paid</th>
									<th>Status</th>
									<th>Sub Status</th>
								</thead>
								<tbody>
								</tbody>
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