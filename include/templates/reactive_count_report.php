<style>
.reports_filter_card {
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>

<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color:#fff; font-size:20px; border-radius:5px;">
        Marudham Capitals - Renewal To Reactive Count Report
    </div>
</div>

<br><br>

<div class="main-container">
    <form name="reactive_customer_report_form" method="post">
        <div class="row gutters">
            <div class="toggle-container col-12 reports_filter_card">
                <!-- Month -->
                <input type="month" id="from_date" name="from_date" class="toggle-button">
                <!-- Branch -->
                <select class="toggle-button" id="branch" name="branch[]" multiple></select>
                <!-- Sector -->
                <select class="form-control  ms-3" id="sector_name" name="sector_name[]" multiple></select>
                <input type="button" id="search_btn" class="toggle-button" style="background-color:#009688;color:white" value="Search">
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header"> Reactive Customer List</div>
                    <div class="card-body">
                        <div id="reactive_customer_report_table_div" class="table-divs" style="overflow-x:auto;">
                            <table id="reactive_customer_table" class="table custom-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Customer ID</th>
                                        <th>Aadhaar Number</th>
                                        <th>Customer Name</th>
                                        <th>Branch</th>
                                        <th>Sector</th>
                                        <th>Area</th>
                                        <th>Sub Area</th>
                                        <th>Mobile Number</th>
                                        <th>Closing Date</th>
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