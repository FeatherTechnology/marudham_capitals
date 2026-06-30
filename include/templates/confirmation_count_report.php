<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Confirmation Count Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form name="confirmation_count_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters">

			<div class="toggle-container col-12 reports_filter_card">
				
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>

                <select class="toggle-button" name='type' id='type'>
					<option value='0'>Select Type</option>
					<option value='1'>User</option>
					<option value='2'>Sector</option>
					<option value='3'>Region</option>
					<option value='4'>Zone</option>
				</select>

				<select class="toggle-button hidefield" id='user_type' name='user_type'>
                    <option value=''>Select User Type</option>
                    <option value='1'>All</option>
                    <option value='2'>Active</option>
                    <option value='3'>In Active</option>
                </select>

				<select class="toggle-button hidefield" id='by_user' name='by_user'>
					<option value=''>Select User</option>
				</select>
				
                <select class="form-control hidefield" id="map_name" name="map_name" multiple>
                    <option value="">Select</option>
                </select>&nbsp;&nbsp;
	
				<select class="form-control hidefield" id="loan_category" name="loan_category" multiple>
					<option value="">Select Loan Category</option>
				</select>

				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
			</div>

			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Confirmation Count Report</div>
					<div class="card-body">
						<div id="confirmation_count_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="confirmation_count_table" class="table custom-table">
								<thead></thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>

	</form>
</div>