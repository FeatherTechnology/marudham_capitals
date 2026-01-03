<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Concern Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form>
		<div class="row gutters">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Concern Report</div>
					<div class="card-body">
						<div style="overflow-x: auto;">
							<table id="concern_list_report_table" class="table custom-table">
								<thead>
                                <th>S.NO</th>
                                <th>Concern ID</th>
                                <th>Concern Date</th>
                                <th>Created User</th>
                                <th>Raised For</th>
                                <th>Raised For ID</th>
                                <th>Raised For Name</th>
                                <th>Concern Subject</th>
                                <th>Department Name</th>
                                <th>Concern Remark</th>
                                <th>Assign To</th>
                                <th>Pass To</th>
                                <th>Solution Date</th>
                                <th>Communication</th>
                                <th>Upload</th>
                                <th>Location</th>
                                <th>Participants</th>
                                <th>Solution Remark</th>
                                <th>Concern Status</th>
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