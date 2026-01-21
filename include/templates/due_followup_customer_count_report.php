<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Due Summary
    </div>

</div><br>
<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form id="due_followup_customer_count_report_form" name="due_followup_customer_count_report_form" action="" method="post" enctype="multipart/form-data">

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
                    <div class="card-header">Due Summary</div>
                    <div class="card-body">
                        <div id="due_followup_customer_count_report_table_div" class="table-divs" style="overflow-x: auto;">
                            <table id="due_followup_customer_count_report_table" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>User Name</th>
                                        <th>Loan Category</th>
                                        <th>Total Count</th>
                                        <th>Payable Zero</th>
                                        <th>Responsible</th>
                                        <th>Balance Count</th>
                                        <th>Paid</th>
                                        <th>Partial Paid</th>
                                        <th>Total Paid</th>
                                        <th>Paid %</th>
                                        <th>Un Paid</th>
                                        <th>Unpaid %</th>
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