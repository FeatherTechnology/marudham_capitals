<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Interest Ledger Report
	</div>
</div><br>
<div class="row gutters">
    <div class="col-12">
        <div class="toggle-container col-12">
            <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
            <div class="col-lg-2 col-md-2">
                <div class="form-group">
                    <input type="hidden" id="loan_category2">
                    <select class="form-control" id="loan_category" name="loan_category" style="margin-top: 15px;border-radius: 5px;height: 45px;">
                        <option value="">Select Loan Category</option>
                    </select>
                </div>
            </div>
            <input type="button" id='ledger_view_report_btn' name='ledger_view_report_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
        </div> <br />
        <!-- Balance report Start -->
        <div class="card">
            <div class="card-body overflow-x-cls">
                <div class="col-12 reportDiv">

                </div>
            </div>
        </div>
        <!--Balance report End-->
    </div>
</div>

<div id="printcollection" style="display: none"></div>

<!----------------------------------------------------------------------- Due Chart Modal Start ----------------------------------------------------------------------->

<div class="modal fade bd-example-modal-lg" id="due_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Due Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="due_chart_table_div">
                    <table class="table custom-table">
                        <thead>
                            <th>Due No.</th>
                            <th>Due Month</th>
                            <th>Month</th>
                            <th>Due Amount</th>
                            <th>Pending</th>
                            <th>Payable</th>
                            <th>Collection Date</th>
                            <th>Collection Amount</th>
                            <th>Balance Amount</th>
                            <th>Pre Closure</th>
                            <th>Role</th>
                            <th>User ID</th>
                            <th>Collection Method</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>

<!-------------------------------------------------------------------------- Due Chart Modal END -------------------------------------------------------------------------->