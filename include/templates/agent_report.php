<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Agent Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="agent_report_form" name="agent_report_form" action="" method="post" enctype="multipart/form-data">
		<div class="row gutters" id="agent_card">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Reload'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Agent Report</div>
					<div class="card-body">
						<div id="agent_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="agent_report_table" class="table custom-table">
								<thead>
                                    <th>S.No</th>
                                    <th>Agent</th>
                                    <th>Date</th>
                                    <th>Coll Amount</th>
                                    <th>Net Cash</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="3"></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
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