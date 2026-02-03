<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Cash Tally Activity
    </div>

</div><br>
<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form action="" method="post" enctype="multipart/form-data">

        <div class="row gutters" id="closed_card">
            <div class="toggle-container col-12">
                <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
                <select type="text" class="toggle-button" id='by_user' name='by_user'>
                    <option value=''>Select User</option>
                </select>
                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">Cash Tally Activity</div>
                    <div class="card-body">
                        <div id="Cash_tally_activity_div" class="table-divs" style="overflow-x: auto;">
                            <table id="Cash_tally_activity_table" class="table custom-table">
                                 <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>User Name</th>
                                        <th>Opening Balance</th>
                                        <th>Collection</th>
                                        <th>Deposit</th>
                                        <th>Exchange</th>
                                        <th>EL</th>
                                        <th>Investment</th>
                                        <th>Other Income</th>
                                        <th>Contra</th>
                                        <th>Loan Issued</th>
                                        <th>Agent</th>
                                        <th>Expenses</th>
                                        <th>Closing Balance</th>
                                    </tr>
                                </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>