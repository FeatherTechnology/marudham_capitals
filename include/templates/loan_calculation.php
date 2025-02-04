<?php
@session_start();
if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
$loanCategoryList = $userObj->getloanCategoryList($mysqli);

$id = 0;
// $emicheck = 0;
// $calcheck = 0;
if (isset($_POST['submitloan_calculation']) && $_POST['submitloan_calculation'] != '') {
    if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
        $id = $_POST['id'];
        $userObj->updateLoanCalculation($mysqli, $id, $userid);
?>
        <script>
            location.href = '<?php echo $HOSTPATH; ?>edit_loan_calculation&msc=2';
        </script>
    <?php   } else {
        $userObj->addLoanCalculation($mysqli, $userid);
    ?>
        <script>
            location.href = '<?php echo $HOSTPATH; ?>edit_loan_calculation&msc=1';
        </script>
    <?php
    }
}

$del = 0;
if (isset($_GET['del'])) {
    $del = $_GET['del'];
}
if ($del > 0) {
    $userObj->deleteLoanCalculation($mysqli, $del, $userid);
    ?>
    <script>
        location.href = '<?php echo $HOSTPATH; ?>edit_loan_calculation&msc=3';
    </script>
<?php
}

if (isset($_GET['upd'])) {
    $idupd = $_GET['upd'];
}
$status = 0;
if ($idupd > 0) {
    $getLoanCalculation = $userObj->getLoanCalculation($mysqli, $idupd);

    if (sizeof($getLoanCalculation) > 0) {
        for ($ibranch = 0; $ibranch < sizeof($getLoanCalculation); $ibranch++) {

            $loan_cal_id            = $getLoanCalculation['loan_cal_id'];
            $loan_category          = $getLoanCalculation['loan_category'];
            $sub_category           = $getLoanCalculation['sub_category'];
            $due_method             = $getLoanCalculation['due_method'];
            $due_type               = $getLoanCalculation['due_type'];
            $profit_method          = $getLoanCalculation['profit_method'];
            $calculate_method       = $getLoanCalculation['calculate_method'];
            $intrest_rate_min       = $getLoanCalculation['intrest_rate_min'];
            $intrest_rate_max       = $getLoanCalculation['intrest_rate_max'];
            $due_period_min         = $getLoanCalculation['due_period_min'];
            $due_period_max         = $getLoanCalculation['due_period_max'];
            $doc_charge_type         = $getLoanCalculation['doc_charge_type'];
            $document_charge_min    = $getLoanCalculation['document_charge_min'];
            $document_charge_max    = $getLoanCalculation['document_charge_max'];
            $proc_fee_type    = $getLoanCalculation['proc_fee_type'];
            $processing_fee_min     = $getLoanCalculation['processing_fee_min'];
            $processing_fee_max     = $getLoanCalculation['processing_fee_max'];
            $due_date               = $getLoanCalculation['due_date'];
            $grace_period           = $getLoanCalculation['grace_period'];
            $penalty                = $getLoanCalculation['penalty'];
            $overdue                = $getLoanCalculation['overdue'];
            $collection_info        = $getLoanCalculation['collection_info'];
        }
    }
    // $emicheck = strpos($due_type, 'emi') !== false;
    // $calcheck = strpos($due_type, 'intrest') !== false;


    $profit_method = explode(',', $profit_method);
}
?>
<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:10px; color: #ffff; font-size: 20px;">
        Marudham Capitals - Loan Calculation
    </div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
    <a href="edit_loan_calculation">
        <button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
    </a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
    <!-- Row start -->
    <form action="" method="post" name="vendorcreation" id="vendorcreation" enctype="multipart/form-data">
        <div class="row gutters">
            <!-- General Info -->
            <input type="hidden" name="id" id="id" class="form-control" value="<?php if (isset($loan_cal_id)) echo $loan_cal_id; ?>">
            <input type="hidden" name="loan_id_upd" id="loan_id_upd" class="form-control" value="<?php if (isset($loan_cal_id)) echo $loan_cal_id; ?>">
            <input type="hidden" name="loan_category_upd" id="loan_category_upd" class="form-control" value="<?php if (isset($loan_category)) echo $loan_category; ?>">
            <input type="hidden" name="sub_category_upd" id="sub_category_upd" class="form-control" value="<?php if (isset($sub_category)) echo $sub_category; ?>">
            <input type="hidden" class="form-control" value="<?php if (isset($doc_charge_type)) echo $doc_charge_type; ?>" id="doc_charge_type_upd" name="doc_charge_type_upd" aria-describedby="id" placeholder="Enter id">
            <input type="hidden" class="form-control" value="<?php if (isset($proc_fee_type)) echo $proc_fee_type; ?>" id="pro_fees_type_upd" name="pro_fees_type_upd" aria-describedby="id" placeholder="Enter id">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">General Info</div>
                    <div class="card-body row">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="label">Loan Category</label><span class="required">&nbsp;*</span>
                                <select tabindex="1" type="text" class="form-control" id="loan_category" name="loan_category" required>
                                    <option value="">Select Loan Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Sub Category</label><span class="required">&nbsp;*</span>
                                <select tabindex="2" type="text" class="form-control" id="sub_category" name="sub_category" required>
                                    <option value="">Select Sub Category</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Due Method</label>
                                <input type="text" readonly id="due_method" name="due_method" class="form-control" value="Monthly" tabindex='3'>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Due Type</label><span class="required">&nbsp;*</span>
                                <input type="hidden" class="form-control" id="due_type" name="due_type" value="emi">
                                <input tabindex="4" type="text" class="form-control" id="duetype" name="duetype" value="EMI" title="Select Due Type" readonly>
                                <!-- <select tabindex="4" type="text" class="form-control" id="due_type" name="due_type" title="Select Due Type" required>
                                    <option value=''>Select Due Type</option>
                                    <option <?php #if (isset($due_type)) {if ($due_type == "emi") echo 'selected';} 
                                            ?> value="emi">EMI</option>
                                    <option <?php #if (isset($due_type)) {if ($due_type == "intrest") echo 'selected';} 
                                            ?> value="intrest">Interest</option>
                                </select> -->
                            </div>
                        </div>
                        <div id="emi_method" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Profit Method</label>
                                <select tabindex="5" type="text" class="form-control selectpicker" id="profit_method" name="profit_method[]" data-live-search="true" multiple data-actions-box="true" title="Select Profit Method">
                                    <option <?php if (isset($profit_method)) {
                                                if ($profit_method[0] == "pre_intrest") echo 'selected';
                                            } ?> value="pre_intrest">Pre Benefit</option>
                                    <option <?php if (isset($profit_method)) {
                                                if ($profit_method[0] == "after_intrest") {
                                                    echo 'selected';
                                                } elseif (isset($profit_method[1]) and $profit_method[1] == "after_intrest") {
                                                    echo 'selected';
                                                }
                                            }
                                            ?> value="after_intrest">After Benefit</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div id="intrest_method" <?php #if (!$calcheck) { 
                                                        ?>style="display: none" <?php #} 
                                                                                ?> class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="inputReadOnly">Calculate Method</label>
                                <input tabindex="6" type="text" class="form-control" id="calculate_method" name="calculate_method" value="Monthly" readonly>
                            </div>
                        </div> -->
                    </div>
                    <div class="card-header">Condition Info</div>
                    <div class="card-body row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-6 col-12">
                            <div class="form-group">
                                <h5>Interest Rate %</h5>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Min</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="7" id="intrest_rate_min" name="intrest_rate_min" class="form-control" placeholder="Rate Of Interest Min" required value="<?php if (isset($intrest_rate_min)) echo $intrest_rate_min; ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Max</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="8" id="intrest_rate_max" name="intrest_rate_max" class="form-control" placeholder="Rate Of Interest Max" required value="<?php if (isset($intrest_rate_max)) echo $intrest_rate_max; ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-6 col-12">
                            <div class="form-group">
                                <h5>Due Period %</h5>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Min</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="9" id="due_period_min" name="due_period_min" class="form-control" placeholder="Due Period Min" required value="<?php if (isset($due_period_min)) echo $due_period_min; ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Max</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="10" id="due_period_max" name="due_period_max" class="form-control" placeholder="Due Period Max" required value="<?php if (isset($due_period_max)) echo $due_period_max; ?>">

                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label style="font-size:1.35em;padding-right:2%">Document Charge: <span class="text-danger">*</span></label>
                                <input type="radio" name="doc_charge_type" id="docamt" value="amt" <?php if (isset($doc_charge_type) and $doc_charge_type == 'amt') echo 'checked'; ?> tabindex='9'></input><label for='docamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="doc_charge_type" id="docpercentage" value="percentage" <?php if (isset($doc_charge_type) and $doc_charge_type == 'percentage') echo 'checked'; ?> tabindex='11'></input><label for='docpercentage'>&nbsp;&nbsp;%</label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput" id="docmin">Min</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="11" id="document_charge_min" name="document_charge_min" readonly class="form-control" placeholder="Document Charge Min" required value="<?php if (isset($document_charge_min)) echo $document_charge_min; ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput" id="docmax">Max</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="12" id="document_charge_max" name="document_charge_max" readonly class="form-control" placeholder="Document Charge Max" required value="<?php if (isset($document_charge_max)) echo $document_charge_max; ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label style="font-size:1.35em;padding-right:2%">Processing Fee: <span class="text-danger">*</span></label>
                                <input type="radio" name="proc_fee_type" id="procamt" value="amt" tabindex="15" <?php if (isset($proc_fee_type) and $proc_fee_type == 'amt') echo 'checked'; ?>></input><label for='procamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="proc_fee_type" id="procpercentage" value="percentage" tabindex="16" <?php if (isset($proc_fee_type) and $proc_fee_type == 'percentage') echo 'checked'; ?>></input><label for='procpercentage'>&nbsp;&nbsp;%</label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput" id="procmin">Min</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="13" id="processing_fee_min" name="processing_fee_min" readonly class="form-control" placeholder="Processing Fee Min" required value="<?php if (isset($processing_fee_min)) echo $processing_fee_min; ?>">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput" id="procmax">Max</label><span class="required">&nbsp;*</span>
                                <input type="number" step="0.01" tabindex="14" id="processing_fee_max" name="processing_fee_max" readonly class="form-control" placeholder="Processing Fee Max" required value="<?php if (isset($processing_fee_max)) echo $processing_fee_max; ?>">

                            </div>
                        </div>

                        <br><br><br><br><br><br>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="disabledInput">Overdue Penalty %</label><span class='text-danger' style="font-size:11px">&nbsp;*</span>
                                <input type="number" step="0.01 " tabindex="15" id="overdue" name="overdue" class="form-control" placeholder="Enter Overdue" value="<?php if (isset($overdue)) echo $overdue; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="form-group"><br>
                                <label for="disabledInput">Advance</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input checked readonly type="radio" tabindex="16" name="collection_info" id="yes" value="Yes" <?php if (isset($collection_info))
                                                                                                                                    echo ($collection_info == 'yes') ? 'checked' : '' ?>> &nbsp;&nbsp; <label for="yes">Yes </label> &nbsp;&nbsp;&nbsp;&nbsp;
                                <input readonly type="radio" tabindex='17' name="collection_info" id="no" value="No" <?php if (isset($collection_info))
                                                                                                                            echo ($collection_info == 'No') ? 'checked' : '' ?>> &nbsp;&nbsp; <label for="no">No </label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12"></div>


                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4">
                    <div class="text-right">
                        <div>
                            <button type="submit" tabindex="18" name="submitloan_calculation" id="submitloan_calculation" class="btn btn-primary" value="submit">Submit</button>&nbsp;&nbsp;&nbsp;
                            <button type="reset" tabindex="19" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>