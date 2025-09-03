<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Interest Balance Report
	</div>
</div><br>
<div class="row gutters">
    <div class="col-12">
        <div class="toggle-container col-12">
            <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
            <input type="button" id='bal_report_btn' name='bal_report_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
        </div> <br />
        <!-- Balance report Start -->
        <div class="card">
            <div class="card-body overflow-x-cls">
                <div class="col-12">
                    <table id="bal_report_table" class="table custom-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Line</th>
                                <th>Loan ID</th>
                                <th>Loan Date</th>
                                <th>Maturity Date</th>
                                <th>Customer ID</th>
                                <th>Aadhar Number</th>
                                <th>Customer Name</th>
                                <th>Area</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Loan Category</th>
                                <th>Agent</th>
                                <th>Loan Amount</th>
                                <th>Due Amount</th>
                                <th>No of Due</th>
                                <th>Balance Amount</th>
                                <th>Principal Amount</th>
                                <th>Interest Amount</th>
                                <th>Status</th>
                                <th>Sub Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="12"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!--Balance report End-->
    </div>
</div>