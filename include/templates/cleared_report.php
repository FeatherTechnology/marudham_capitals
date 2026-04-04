<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Cleared Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="cleared_report_form" name="cleared_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="cleared_card">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<select type="text" class="toggle-button" id='stmt_type' name='stmt_type'>
                    <option value=''>Select Type</option>
                    <option value='0'>Bank Clearance</option>
                    <option value='1'>Cleared</option>
                </select>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Cleared Report</div>
					<div class="card-body">
						<div id="cleared_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="cleared_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>Bank Name</th>
									<th>Transaction Date</th>
									<th>Narration</th>
									<th>Transaction ID</th>
									<th>Credit</th>
									<th>Debit</th>
									<th>Balance</th>
									<th>Status</th>
									<th>Cleared date</th>
									<th>Cleared User</th>
									<th>Cleared Screen</th>
									<th>Category / Name</th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="5"></td>
										<td></td>
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