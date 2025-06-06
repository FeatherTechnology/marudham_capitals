<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Other Transaction Report
    </div>
</div><br> <br>

<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form id="other_transaction_report_form" name="other_transaction_report_form" action="" method="post" enctype="multipart/form-data">

        <!-- <div class="row gutters" id="collection_card"> -->
        <div class="row gutters">
            <div class="col-12">
                <div class="row justify-content-center"> <!-- centers the inner row -->
                    <div class="col-md-10"> <!-- set the width of the content -->
                        <div class="row align-items-end justify-content-center"> <!-- center items in inner row -->
                            <div class="col-md-2">
                                <label for="from_date" style="margin-left: 10px;">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="toggle-button" />
                            </div>
                            <div class="col-md-2">
                                <label for="to_date" style="margin-left: 10px;">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="toggle-button" />
                            </div>
                            <div class="col-md-3">
                                <label for="sheet_type">Balance Sheet Type</label>
                                <select class="form-control" id="sheet_type" name="sheet_type" style="border-radius: 5px;">
                                    <option value="">Select Balance Sheet</option>
                                    <option value="1">Contra</option>
                                    <option value="2">Exchange</option>
                                    <option value="3">Other income</option>
                                    <option value="4">Expense</option>
                                    <option value="5">Investment</option>
                                    <option value="6">Deposit</option>
                                    <option value="7">EL</option>
                                    <option value="8">Excess Fund</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="button" id="other_report_btn" name="other_report_btn"
                                    class="toggle-button"
                                    style="background-color: #009688; color: white;"
                                    value="Search" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><br><br>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">Other Transaction Report</div>
                <div class="card-body">
                    <div id="collection_table_div" class="table-divs" style="overflow-x: auto;">
                        <table id="contra_table" class="table custom-table" style="display: none;">
                            <thead>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>Cash Type</th>
                                <th>Credit</th>
                                <th>Debit</th>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                         <table id="exchange_report_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>Cash Type</th>
                                    <th>Exchange Entry</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <table id="other_income_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>Cash Type</th>
                                    <th>Category</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                         <table id="expenses_report_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>User Type</th>
                                    <th>User Name</th>
                                    <th>Ref Code</th>
                                    <th>Category</th>
                                    <th>Particulars</th>
                                    <th>Voucher ID</th>
                                    <th>Transaction ID</th>
                                    <th>Receive Person</th>
                                    <th>Remarks</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="11"></td>
                                    <td colspan="1"></td>
                                </tr>
                            </tfoot>
                        </table>
                          <table id="investment_report_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Cash Type</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <table id="deposit_report_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Cash Type</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                         <table id="el_report_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Cash Type</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                         <table id="excess_report_table" class="table custom-table" style="display: none;">
                            <thead>
                                <tr>
                                    <th width="50">S.No</th>
                                    <th>Date</th>
                                    <th>Bank</th>
                                    <th>Ref ID</th>
                                    <th>Remark</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </form>
</div>