<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Partners Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="partners_report_form" name="partners_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="partners_card">
			<div class="toggle-container col-12">
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Reload'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Opening Balance</div>
					<div class="card-body">
						<div id="opening_table_div" class="table-divs">
							<table id="opening_table" class="table custom-table">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Opening Balance</th>
										<th>Hand Cash</th>
										<th>Bank Cash</th>
										<th>Total </th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
                <div class="card">
					<div class="card-header">Collection</div>
					<div class="card-body">
						<div id="collection_table_div" class="table-divs">
							<table id="collect_table" class="table custom-table">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Collection</th>
										<th>Loan Category</th>
										<th>Today</th>
										<th>Till Now</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
							
						</div>
					</div>
				</div>
                 <div class="card">
					<div class="card-header">Loan Issue</div>
					<div class="card-body">
						<div id="issue_table_div" class="table-divs">
							<table id="issue_table" class="table custom-table">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Loan Issue</th>
										<th>Loan Category</th>
										<th>Today Issued</th>
										<th>Today Count</th>
										<th>Till Now</th>
										<th>Total Count</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
							
						</div>
					</div>
				</div>
                <div class="card">
					<div class="card-header">Other Transaction</div>
					<div class="card-body">
						<div id="other_trans_table_div" class="table-divs">
							<table id="other_trans_table" class="table custom-table">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Other Transaction</th>
										<th>Credit</th>
										<th>Debit</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
							
						</div>
					</div>
				</div>
                <div class="card">
					<div class="card-header">Closing Balance</div>
					<div class="card-body">
						<div id="closing_table_div" class="table-divs">
							<table id="closing_table" class="table custom-table">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Closing Balance</th>
										<th>Hand Cash</th>
										<th>Bank Cash</th>
										<th>Total </th>
									</tr>
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