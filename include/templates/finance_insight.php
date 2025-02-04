<link rel="stylesheet" type="text/css" href="css/finance_insights.css" />
<?php
$getuser = $userObj->getuser($mysqli, $userid);
$bank_details = $getuser['bank_details'];
?>
<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Financial Insights
	</div>
</div><br>


<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="finance_insight_form" name="finance_insight_form" action="" method="post" enctype="multipart/form-data">

		<input type="hidden" id='bank_detail' name='bank_detail' value='<?php if (isset($bank_details)) echo $bank_details; ?>'><!-- to get user bank detail -->

		<div class="row gutters" style="margin-left: 0;margin-right: 2px;">

			<div class="toggle-container col-12">
				<input type="button" class="toggle-button" data-toggle='modal' data-target='#dayModal' value='Day Wise'>
				<input type="button" class="toggle-button" value='Today'>
				<input type="button" class="toggle-button" data-toggle='modal' data-target='#monthModal' value='Month Wise'>
				<select type="text" class="toggle-button" id='by_user' name='by_user'>
					<option value=''>Select User</option>
				</select>
			</div>


			<div class="split-card col-12">
				<div class="card col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="card-header">
						<div class="card-title">Balance Sheet</div>
					</div>
					<div class="card-body">
						<div class="row balance-sheet-card">
							<table>
								<thead class='break'>
									<td></td>
									<td>Credit</td>
									<td>Debit</td>
								</thead>
								<tbody>
									<tr class='break'>
										<td>Total Opening Balance</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Due Collection</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Principal Collection</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Interest Collection</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Penalty</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Fine</td>
										<td></td>
										<td></td>
									</tr>
									<!-- <tr>
										<td>Collection</td>
										<td></td>
										<td></td>
									</tr> -->
									<tr class='break'>
										<td>Other Income</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Investment</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Deposit</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Exchange</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>EL</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Contra</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Issued</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Expense</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Total Closing Balance</td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
								<tfoot>
									<tr>
										<td>Totals</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Diiference</td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div class="card col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="card-header">
						<div class="card-title">Benefit</div>
					</div>
					<div class="card-body">
						<div class="row benefits-card">
							<table>
								<thead class='break'>
									<td></td>
									<td>Credit</td>
									<td>Debit</td>
								</thead>
								<tbody>
									<tr>
										<td>Benefit Amount</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Interest Amount</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Document Charges</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Processing Charges</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Penalty</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Fine</td>
										<td></td>
										<td></td>
									</tr>
									<tr class="break">
										<td>Other Income</td>
										<td></td>
										<td></td>
									</tr>
									<tr class="break">
										<td>Expenses</td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
								<tfoot>
									<tr>
										<td>Totals</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Total Benefit</td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
							</table>
							<!-- <label class="BBDiff" id='BBDiff' name='BBDiff'>Difference: Benefit - Benefit Check</label> -->
						</div>
						<br>
						<div class="card-header">
							<div class="inside-title">Profit</div>
						</div>
						<div class='row profit-card'>
							<table>
								<thead class='break'>
									<td></td>
									<td>Credit</td>
									<td>Debit</td>
								</thead>
								<tbody>
									<tr>
										<td>Due Interest</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Interest Amount</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Document Charges</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Processing Charges</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Penalty</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Fine</td>
										<td></td>
										<td></td>
									</tr>
									<tr class="break">
										<td>Other Income</td>
										<td></td>
										<td></td>
									</tr>
									<tr class="break">
										<td>Expenses</td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
								<tfoot>
									<tr>
										<td>Totals</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Total Profit</td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div class="card col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="card-header">
						<div class="card-title">Benefit Check</div>
					</div>
					<div class="card-body">
						<div class="row benefits-check-card">
							<table>
								<thead class='break'>
									<td></td>
									<td>Credit</td>
									<td>Debit</td>
								</thead>
								<tbody>
									<tr>
										<td>Opening Outstanding</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Opening Balance</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Investment</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Deposit</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>EL</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Exchange</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Contra</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Closing Outstanding</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Closing Balance</td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td>Totals</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Difference</td>
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


<!-- Modal for Day Choose -->
<div class="modal fade" id="dayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Day Wise</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row container">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<label for="to_date">From Date</label>
						<input type="date" name="from_date" id="from_date" class='form-control'>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<label for="to_date">To Date</label>
						<input type="date" name="to_date" id="to_date" class='form-control'>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id='submitDaywise'>Submit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for Month Choose -->
<div class="modal fade" id="monthModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Month Wise</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row container">
					<div class="col-12">
						<label for="for_month">Month of</label>
						<input type="month" name="for_month" id="for_month" class='form-control'>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id='submitMonthwise'>Submit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>