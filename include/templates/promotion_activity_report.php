<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Promotion Activity Report
    </div>
</div><br>

<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form id="promotion_activity_report_form" name="promotion_activity_report_form" action="" method="post" enctype="multipart/form-data">

        <div class="row gutters">
            <div class="toggle-container col-12 reports_filter_card">
                <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
                <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>

                <select class="toggle-button" name='type' id='type'>
                    <option value=''>Select Type</option>
                    <option value='1'>User</option>
                    <option value='2'>Sector</option>
                    <option value='3'>Region</option>
                    <option value='4'>Zone</option>
                    <option value='5'>Department</option>
                    <option value='6'>Team</option>
                </select>

                <select class="toggle-button hidefield" id='user_type' name='user_type'>
                    <option value=''>Select User Type</option>
                    <option value='1'>All</option>
                    <option value='2'>Active</option>
                    <option value='3'>In Active</option>
                </select>

                <select class="toggle-button hidefield" id='by_user' name='by_user'>
                    <option value=''>Select User</option>
                </select>

                <select class="form-control hidefield" id="map_name" name="map_name" multiple>
                    <option value="">Select</option>
                </select>

                <select class="toggle-button hidefield" id='department' name='department'>
                    <option value=''>Select Department</option>
                </select>

                <select class="toggle-button hidefield" id='team' name='team'>
                    <option value=''>Select Team</option>
                </select>

                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">Promotion Activity Report</div>
                    <div class="card-body">
                        <div id="promotion_activity_table_div" class="table-divs" style="overflow-x: auto;">
                            <table id="promotion_activity_report_table" class="table custom-table">
                                <thead>
                                    <th>S.No</th>
                                    <th>Aadhaar Number</th>
                                    <th>Cust. ID</th>
                                    <th>Customer Name</th>
                                    <th>Created Date</th>
                                    <th>Time</th>
                                    <th>Mobile Number</th>
                                    <th>Area</th>
                                    <th>Sub Area</th>
                                    <th>Region</th>
                                    <th>Sector</th>
                                    <th>Zone</th>
                                    <th>Branch</th>
                                    <th>Promotion Type</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Follow Date</th>
                                    <th>Follow up Type</th>
                                    <th>User Type</th>
                                    <th>User Name</th>
                                    <th>Customer Status</th>
                                    <th>Status</th>
                                    <th>Chart</th>
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

<!-- Modal for promotion Chart just view table   -->
<div class="modal fade" id="promoChartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Promotion Chart</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 table-responsive" id='promoChartDiv'></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="7">Close</button>
            </div>
        </div>
    </div>
</div>