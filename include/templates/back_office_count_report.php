<style>
    /* Force borders for grouped headers */
    /* Ensure borders are visible */
    #back_office_count_table {
        border-collapse: collapse !important;
    }

    #back_office_count_table thead th {
        border: 1px solid #ffffff;
    }

    /* ===== GROUP HEADER BORDER ===== */
    #back_office_count_table thead th.group-border {
        border-right: 1px solid #ffffff !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Back Office Count Report
    </div>
</div><br>
<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form name="due_followup_report_form" action="" method="post" enctype="multipart/form-data">

        <div class="row gutters" id="closed_card">
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
                    <div class="card-header">Back Office Count Report
                        <button type="button" class="btn btn-primary" id="unpaid_btn" name="unpaid_btn" data-toggle="modal" data-target=".unpaidModal" style="padding: 5px 35px; float: right; display: none;" tabindex='20'>Unpaid</span></button>
                    </div>
                    <div class="card-body">
                        <div id="back_office_count_table_div" class="table-divs" style="overflow-x: auto;">
                            <table id="back_office_count_table" class="table custom-table" style="width:100%;">
                                <thead>
                                    <!-- ROW 1 -->
                                    <tr>
                                        <th rowspan="3">S.No</th>
                                        <th rowspan="3">User Name</th>
                                        <th rowspan="3">Total Count</th>
                                        <th rowspan="3">Payable Zero</th>
                                        <th rowspan="3">Responsible</th>
                                        <th rowspan="3">Balance Count</th>

                                        <th colspan="2" class="group-border">To Follow</th>
                                        <th colspan="2" class="group-border">Followed</th>

                                        <th colspan="6" class="group-border">Mobile</th>
                                        <th colspan="6" class="group-border">Direct</th>
                                    </tr>

                                    <!-- ROW 2 -->

                                    <tr>
                                        <!-- To Follow -->
                                        <th rowspan="2">Paid</th>
                                        <th rowspan="2">UnPaid</th>

                                        <!-- Followed -->
                                        <th rowspan="2">Paid</th>
                                        <th rowspan="2">UnPaid</th>

                                        <!-- Mobile -->
                                        <th colspan="2">Commitment</th>
                                        <th colspan="2">Unavailable</th>
                                        <th rowspan="2">Paid</th>
                                        <th rowspan="2">Total</th>

                                        <!-- Direct -->
                                        <th colspan="2">Commitment</th>
                                        <th colspan="2">Unavailable</th>
                                        <th rowspan="2">Paid</th>
                                        <th rowspan="2">Total</th>
                                    </tr>

                                    <!-- ROW 3 -->
                                    <tr>
                                        <!-- Mobile -->
                                        <th>Paid</th>
                                        <th>UnPaid</th>
                                        <th>Paid</th>
                                        <th>UnPaid</th>

                                        <!-- Direct -->
                                        <th>Paid</th>
                                        <th>UnPaid</th>
                                        <th>Paid</th>
                                        <th>UnPaid</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<!--Unpaid Modal -->
<div class="modal fade unpaidModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">UnPaid Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="updatedFamTable" class="table-responsive">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="50">S.No</th>
								<th>Aadhar Number</th>
								<th>Customer ID</th>
								<th>Loan ID</th>
								<th>Customer Name</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Unpaid Modal -->