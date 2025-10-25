<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Confirmation Follow up Report
    </div>

</div><br>
<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form id="confirmation_followup_report_form" name="confirmation_followup_report_form" action="" method="post" enctype="multipart/form-data">

        <div class="row gutters" id="closed_card">
            <div class="toggle-container col-12">
                <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
                <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Reload'>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">Confirmation Follow up Report</div>
                    <div class="card-body">
                        <div id="confirmation_followup_report_table_div" class="table-divs" style="overflow-x: auto;">
                            <table id="confirmation_followup_report_table" class="table custom-table">
                                <thead>
                                    <th>S.No</th>
                                    <th>Line</th>
                                    <th>Loan ID</th>
                                    <th>Loan Date</th>
                                    <th>Aadhaar Number</th>
                                    <th>Cust. ID</th>
                                    <th>Cust. Name</th>
                                    <th>Mobile</th>
                                    <th>Follow Person Type</th>
                                    <th>Person Name</th>
                                    <th>Relationship</th>
                                    <th>Status</th>
                                    <th>Sub Status</th>
                                    <th>Label</th>
                                    <th>Remark</th>
                                    <th>Confirmation Date</th>
                                    <th>User Type</th>
                                    <th>User</th>
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