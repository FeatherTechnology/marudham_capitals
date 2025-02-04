<?php
include('../../ajaxconfig.php');

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

?>
<style>
    .hr-line {
        position: relative;
        top: 20px;
        border: none;
        height: 1px;
        background: black;
    }
</style>


<!-- NOC window -->
<div class="card noc-card">
    <div class="card-body">
        <!-- Signed Document start -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:0px;margin-bottom:30px;'>Signed Document List</h5>
                            <span class="text-danger sign_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='signDocDiv'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Signed Document End -->
        <hr class='hr-line'>
        <!-- Cheque List Start -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px;margin-bottom:30px;'>Cheque List</h5>
                            <span class="text-danger cheque_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="cTol-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='chequeDiv'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cheque List End -->
        <hr class='hr-line'>
        <!-- Mortgage List Start -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="row">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px;margin-bottom:30px;'>Mortgage List</h5>
                            <span class="text-danger mort_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='mortgageDiv'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mortgage List End -->
        <hr class='hr-line'>
        <!-- Endorsement List Start -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="row">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px;margin-bottom:30px;'>Endorsement List</h5>
                            <span class="text-danger endorse_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='endorsementDiv'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Endorsement List End -->
        <hr class='hr-line'>
        <!-- Gold List Start -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px;margin-bottom:30px;'>Gold List</h5>
                            <span class="text-danger gold_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='goldDiv'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gold List End -->
        <hr class='hr-line'>
        <!-- Document Info Start -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px;margin-bottom:30px;'>Document List</h5>
                            <span class="text-danger doc_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='documentDiv'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Document Info End -->

<?php
// Close the database connection
$connect = null;
?>