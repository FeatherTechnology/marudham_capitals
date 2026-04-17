<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Location Track Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="location_track_report_form" name="location_track_report_form" action="" method="post" enctype="multipart/form-data">


		<div class="row gutters" id="location_track_card">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<select type="text" class="toggle-button" id='user_type' name='user_type'>
                    <option value=''>Select User Type</option>
                    <option value='1'>All</option>
                    <option value='2'>Active</option>
                    <option value='3'>In Active</option>
                </select>
                <select type="text" class="toggle-button" id='by_user' name='by_user'>
                    <option value=''>Select User</option>
                </select>
                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>    
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Location Track Report</div>
					<div class="card-body">
						<div id="location_track_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="location_track_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>User Name</th>
									<th>User Type</th>
									<th>Screens</th>
									<th>Date / Time</th>
									<th>Latitude</th>
									<th>Longitude</th>
									<th>Location</th>
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