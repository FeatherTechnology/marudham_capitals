<?php
session_start();
$user_id = $_SESSION['userid'];
include('../../../ajaxconfig.php');


if(isset($_POST['bwd_id'])){
    $bwd_id = $_POST['bwd_id'];
}

$records = array();
    
$qry = $connect->query("SELECT bwd.*,bc.short_name,bc.acc_no from ct_db_cash_withdraw bwd LEFT JOIN bank_creation bc on bwd.from_bank_id = bc.id where bwd.received = 1 and bwd.id = $bwd_id ");
// 0 means recevied or entered in credit bank deposit. not used current date because any time can be cash deposited to bank 

$row = $qry->fetch();
foreach($row as $key=>$val){
    $$key = $val;
}


?>
<form id="cr_bwd_form" name="cr_bwd_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='from_bank_bwd'>Bank Name</label>
                    <input type="hidden" class="form-control" id= 'bwd_id' name='bwd_id' value='<?php echo $id ?>' >
                    <input type="hidden" class="form-control" id= 'bank_id_bwd' name='bank_id_bwd' value='<?php echo $from_bank_id ?>' >
                    <input type="text" class="form-control" id= 'from_bank_bwd' name='from_bank_bwd' value='<?php echo $short_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='acc_no_bwd'>Account No</label>
                    <input type="text" class="form-control" id= 'acc_no_bwd' name='acc_no_bwd' value='<?php echo $acc_no ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='ref_code_bwd'>Ref Code</label>
                    <input type="text" class="form-control" id= 'ref_code_bwd' name='ref_code_bwd' value='<?php echo $ref_code; ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='trans_id_bwd'>Transaction ID</label>
                    <input type="text" class="form-control" id= 'trans_id_bwd' name='trans_id_bwd' value='<?php echo $trans_id; ?>' readonly >
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='cheque_no_bwd'>Cheque No.</label>
                    <input type="text" class="form-control" id= 'cheque_no_bwd' name='cheque_no_bwd' value='<?php echo $cheque_no; ?>' readonly >
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='amt_bwd'>Amount</label>
                    <input type="text" class="form-control" id= 'amt_bwd' name='amt_bwd' value='<?php echo moneyFormatIndia($amt) ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='remark_bwd'>Remark</label>
                    <input type="text" class="form-control" id= 'remark_bwd' name='remark_bwd' placeholder="Enter Remark" >
                    <span class='text-danger' style='display:none' id='remark_bwdCheck'>Please Enter Remark</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id= 'submit_bwd' name='submit_bwd' value="Submit" >
                </div>
            </div>
            
        </div>
    </div>
</form>


<?php
//Format number in Indian Format
function moneyFormatIndia($num) {
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

// Close the database connection
$connect = null;
?>