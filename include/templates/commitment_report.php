<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        <!-- Marudham Capitals - Commitment Report   name changes to  Due Followup Activity-->
        Marudham Capitals - Due Followup Activity
    </div>

</div><br>
<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form id="commitment_report_form" name="commitment_report_form" action="" method="post" enctype="multipart/form-data">

        <div class="row gutters" id="closed_card">
            <div class="toggle-container col-12">
                <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
                <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
                <select type="text" class="toggle-button" id='by_user' name='by_user'>
                    <option value=''>Select User</option>
                </select>
                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                     <!-- Commitment Report   name changes to  Due Followup Activity-->
                    <div class="card-header">Due Followup Activity</div>
                    <div class="card-body">
                        <div id="commitment_table_div" class="table-divs" style="overflow-x: auto;">
                            <table id="commitment_report_table" class="table custom-table">
                                <thead>
                                    <th>S.No</th>
                                    <th>Aadhaar Number</th>
                                    <th>Cust. ID</th>
                                    <th>Created Date</th>
                                    <th>Time</th>
                                    <th>Area</th>
                                    <th>Follow Type</th>
                                    <th>Follow Up Status</th>
                                    <th>Follow Person Type</th>
                                    <th>Person Name</th>
                                    <th>Relationship</th>
                                    <th>Remark</th>
                                    <th>Commitment Date</th>
                                    <th>User Type</th>
                                    <th>User Name</th>
                                    <th>Hint</th>
                                    <th>Communication Status</th>
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