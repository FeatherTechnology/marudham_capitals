<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#2f958bd9; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Pending OD Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="pending_od_report_form" name="pending_od_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters">

			<div class="toggle-container col-12">
				<input type="date" class="toggle-button" name='search_date' id='search_date' value=''>
				<select class="toggle-button" name='search_type' id='search_type'>
                    <option value='0'>Select Type</option>
                    <option value='1'>Pending</option>
                    <option value='2'>OD</option>
                </select>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #2f958bd9;color:white" value='Search'>
			</div>
            
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Pending OD Report</div>
					<div class="card-body">
						<div id="pending_od_table_div" class="table-divs" style="overflow-x: auto;">
							
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>