<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#2f958bd9; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Day End Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="day_end_report_form" name="day_end_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="day_end_card">
			<div class="toggle-container col-12">
				<input type="date" id='search_date' name='search_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #2f958bd9;color:white" value='Search'>
			</div>
            
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div style="display: flex; align-items:centre; justify-content: space-between;">
						<div class="card-header">Day End Report</div>
						<div style="margin: 25px 30px 0px 0px;"><button type="button" id="print_day_end_report" class="toggle-button" style="background-color: #2f958bd9;color:white; display:none">Print</button></div>
					</div>
					<div class="card-body">
						<div id="day_end_div" class="table-divs" style="overflow-x: auto;"></div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>