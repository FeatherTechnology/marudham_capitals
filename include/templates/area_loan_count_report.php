<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Area Loan Count Report
    </div>
</div><br>

<!-- Main container start -->
<div class="main-container">
    <form>
        <div class="row gutters">
            <div class="toggle-container col-12">
                <select class="toggle-button" name='taluk' id='taluk'>
                    <option value=''>Select Taluk</option>
                </select>
                <select class="toggle-button" name='loan_cat' id='loan_cat'>
                    <option value=''>Select Loan Category</option>
                </select>
                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header" id="reportTitle">Area Loan Count Report</div>
                    <div class="card-body">
                        <div style="overflow-x: auto;">
                            <table id="area_loan_count_report_table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="12" id="Loan_category" style="font-size:14px;">
                                            Loan Category :
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Area</th>
                                        <th>Taluk</th>
                                        <th>Line</th>
                                        <th>Group</th>
                                        <th>Customer Count</th>
                                        <th>Loan Count</th>
                                        <th>Current</th>
                                        <th>Pending</th>
                                        <th>OD</th>
                                        <th>Error</th>
                                        <th>Legal</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
