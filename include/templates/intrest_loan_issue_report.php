<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Interest Loan Issue Report
	</div>
</div><br>
<div class="row gutters">
    <div class="col-12">
        <div class="toggle-container col-12">
            <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
            <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
            <input type="button" id='loan_issue_report_btn' name='loan_issue_report_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
        </div> <br />
        <!-- Loan Issue report Start -->
        <div class="card">
            <div class="card-body overflow-x-cls">
                <div class="col-12">
                    <table id="loan_issue_report_table" class="table custom-table">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Loan ID</th>
                                <th>Customer ID</th>
                                <th>Aadhar Number</th>
                                <th>Customer Name</th>
                                <th>Gaurantor Name</th>
                                <th>Area</th>
                                <th>Line</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Loan category</th>
                                <th>Agent</th>
                                <th>Loan Date</th>
                                <th>Loan Amount</th>
                                <th>Pricipal Amount</th>
                                <th>Interest Amount</th>
                                <th>Document Charge</th>
                                <th>Processing Fee</th>
                                <th>Net Cash</th>
                                <th>Received By</th>
                                <th>Relation Name</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Loan Issue report End-->
    </div>
</div>