<link rel="stylesheet" type="text/css" href="css/finance_insights.css" />
<?php
$getuser = $userObj->getuser($mysqli, $userid);
$report_access = $getuser['report_access'];
?>
<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Hand Cash Balance Sheet
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="hand_cash_balance_sheet_form" name="hand_cash_balance_sheet_form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" id="report_access" value="<?php echo $report_access; ?>">
		<input type="hidden" id="userid" value="<?php echo $userid; ?>">
		<div class="row gutters" style="margin-left: 0;margin-right: 2px;">

			<div class="toggle-container col-12">
				<input type="button" class="toggle-button" data-toggle='modal' data-target='#dayModal' value='Day Wise'>
				<input type="button" class="toggle-button" value='Today'>
				<select type="text" class="toggle-button" id='by_user' name='by_user' <?php if($report_access == '1') echo "style='display: none'"; ?>>
					<option value=''>Select User</option>
				</select>
			</div>

			<div class="split-card col-12">
				<div class="card col-sm-12 col-md-12 col-lg-12 col-xl-12 col-12">
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
										<td>Waiver</td>
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
									<tr>
										<td>Agent</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Expense</td>
										<td></td>
										<td></td>
									</tr>
									<tr class='break'>
										<td>Circular Amount</td>
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