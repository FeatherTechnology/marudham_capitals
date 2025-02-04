<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals -  Ledger View 
	</div>
	
</div><br>

<div class="text-right" style="margin-right: 25px;">
	<!-- <button class="btn btn-primary" id='close_history_card' style="display: none;" >&times;&nbsp;&nbsp;Cancel</button> -->
</div>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id = "ledger_report_form" name="ledger_report_form" action="" method="post" enctype="multipart/form-data"> 
        
        <div class="row gutters">
            <div class="toggle-container col-12">
                <input type="button" class="toggle-button" value='Daily'>
                <input type="button" class="toggle-button" value= 'Weekly'>
                <input type="button" class="toggle-button" value='Monthly'>
            </div>
        </div>
        <div class="row gutters" id="daily_card" style="display: none;">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Daily Ledger Report</div>
					<div class="card-body">
						<div id="daily_table_div" class="table-divs" style="overflow-x:scroll;">
                            
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="row gutters" id="weekly_card" style="display: none;">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Weekly Ledger Report</div>
					<div class="card-body">
						<div class="dd_div">
							<input type="date" class="date-button" id="weekly_date" name="weekly_date">
						</div>
						<div id="weekly_table_div" class="table-divs" style="overflow-x:scroll;">
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="row gutters" id="monthly_card" style="display: none;">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Monthly Ledger Report</div>
					<div class="card-body">
						<div class="dd_div">
							<input type="month" class="date-button" id="monthly_date" name="monthly_date">
						</div>
						<div id="monthly_table_div" class="table-divs" style="overflow-x:scroll;"></div>
					</div>
				</div>
			</div>
		</div>

    </form>
</div>