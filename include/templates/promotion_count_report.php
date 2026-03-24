<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Promotion Count Report
	</div>
</div><br>
<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form name="promotion_count_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<select type="text" class="toggle-button" id='by_user' name='by_user'>
					<option value=''>Select User</option>
				</select>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Promotion Count Report</div>
					<div class="card-body">
						<div class="table-divs" style="overflow-x: auto;">
							<table class="table custom-table" id="promotion_count_report_table" style="width:100%">
								<thead>
									<tr>
										<th rowspan="3">User Name</th>
										<th colspan="10">Mobile</th>
										<th colspan="10">Direct</th>
									</tr>
									<tr>
										<!-- For Mobile -->
										<th colspan="5">Interest</th>
										<th colspan="5">Not Interest</th>

										<!-- For Direct -->
										<th colspan="5">Interest</th>
										<th colspan="5">Not Interest</th>
									</tr>
									<tr>
										<!-- For Mobile interest -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-Active</th>
										<th>Repromotion</th>
										<th>Total</th>

										<!-- For Mobile not interest -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-Active</th>
										<th>Repromotion</th>
										<th>Total</th>

										<!-- For Direct interest -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-Active</th>
										<th>Repromotion</th>
										<th>Total</th>

										<!-- For Direct not interest -->
										<th>New</th>
										<th>Renewal</th>
										<th>Re-Active</th>
										<th>Repromotion</th>
										<th>Total</th>
									</tr>
								</thead>

								<tbody></tbody>
								<tfoot>
									<tr>
										<td>Total</td>
										<!-- For Mobile interest -->
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>

										<!-- For Mobile not interest -->
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>

										<!-- For Direct interest -->
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>

										<!-- For Direct not interest -->
										<td></td>
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