<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - User Tracking Report
    </div>

</div><br>
<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form id="due_followup_customer_count_report_form" name="due_followup_customer_count_report_form" action="" method="post" enctype="multipart/form-data">

        <div class="row gutters" id="closed_card">            
            <div class="toggle-container col-12">
                <input type="button" class="toggle-button" data-toggle='modal' data-target='#dayModal' value='Day Wise'>
                <input type="button" class="toggle-button" value='Today'>
                <select type="text" class="toggle-button" id='by_user' name='by_user'>
                    <option value=''>Select User</option>
                </select>
                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">User Tracking Report</div>
                    <div class="card-body">
                        <div id="user_tracking_report_table_div" class="table-divs" style="overflow-x: auto;">
                            
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