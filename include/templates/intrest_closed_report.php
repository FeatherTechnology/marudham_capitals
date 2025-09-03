<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Interest Closed Report
	</div>
</div><br>
<div class="row gutters">
    <div class="col-12">
        <div class="toggle-container col-12">
            <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
            <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
            <input type="button" id='closed_report_btn' name='closed_report_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
        </div> <br />
        <!-- Closed report Start -->
        <div class="card">
            <div class="card-body">
                <div class="col-12">
                    <table id="closed_report_table" class="table custom-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Line</th>
                                <th>Loan ID</th>
                                <th>Loan Date</th>
                                <th>Customer ID</th>
                                <th>Aadhar Number</th>
                                <th>Customer Name</th>
                                <th>Area</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Loan Category</th>
                                <th>Loan Amount</th>
                                <th>Maturity Date</th>
                                <th>Closed Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="11"></td>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!--Closed report End-->
    </div>
</div>