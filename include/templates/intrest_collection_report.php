<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Interest Collection Report
	</div>
</div><br>
<div class="row gutters">
    <div class="col-12">
        <div class="toggle-container col-12">
            <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
            <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
            <input type="button" id='collection_report_btn' name='collection_report_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
        </div> <br />
        <!-- Collection report Start -->
        <div class="card">
            <div class="card-body overflow-x-cls">
                <div class="col-12">
                    <table id="collection_report_table" class="table custom-table">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Line</th>
                                <th>Loan ID</th>
                                <th>Loan Date</th>
                                <th>Customer ID</th>
                                <th>Aadhar Number</th>
                                <th>Customer Name</th>
                                <th>Area</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Loan category</th>
                                <th>Agent</th>
                                <th>User Type</th>
                                <th>User</th>
                                <th>Receipt Date</th>
                                <th>Principal Amount</th>
                                <th>Interest Amount</th>
                                <th>Penalty</th>
                                <th>Fine</th>
                                <th>Total Paid</th>
                                <th>Status</th>
                                <th>Sub Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="14"></td>
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
        <!--Collection report End-->
    </div>
</div>