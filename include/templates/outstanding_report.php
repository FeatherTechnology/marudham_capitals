<style>
    /* Force borders for grouped headers */
    /* Ensure borders are visible */
    #outstanding_table {
        border-collapse: collapse !important;
    }

    #outstanding_table thead th {
        border: 1px solid #ffffff;
    }

    /* ===== GROUP HEADER BORDER ===== */
    #outstanding_table thead th.group-border {
        border-right: 1px solid #ffffff !important;
    }
</style>

<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Outstanding Report
    </div>
</div><br>

<!-- Main container start -->
<div class="main-container">
    <!--form start-->
    <form name="outstanding_report_form" action="" method="post" enctype="multipart/form-data">

        <div class="row gutters">

            <div class="toggle-container col-12 reports_filter_card">

                <input type="month" id='from_month' name='from_month' class="toggle-button" value=''>

                <select class="toggle-button" name='type' id='type'>
                    <option value=''>Select Type</option>
                    <option value='1'>Branch</option>
                    <option value='2'>Agent</option>
                </select>

                <select class="toggle-button" id='branch_id' name='branch_id' style="display:none;">
                    <option value=''>Select Branch</option>
                </select>

                <select class="form-control" id="loan_category" name="loan_category"  multiple>
                    <option value="">Select</option>
                </select>

                 <select class="toggle-button" id='agent' name='agent' style="display:none;">
                    <option value=''>Select Agent</option>
                </select>

                <input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #009688;color:white" value='Search'>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">Outstanding Report
                        <button type="button" class="btn btn-primary" id="unpaid_btn" name="unpaid_btn" data-toggle="modal" data-target=".unpaidModal" style="padding: 5px 35px; float: right; display: none;" tabindex='20'>Unpaid</span></button>
                    </div>
                    <div class="card-body">
                        <div id="outstanding_table_div" class="table-divs" style="overflow-x: auto;">
                            <table id="outstanding_table" class="table table-bordered">
                                <thead id="outstanding_thead"></thead>
                                <tbody id="outstanding_tbody"></tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>