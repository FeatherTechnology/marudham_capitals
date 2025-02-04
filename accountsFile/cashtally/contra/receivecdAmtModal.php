<?php
session_start();
$user_id = $_SESSION['userid'];
include('../../../ajaxconfig.php');


if(isset($_POST['bdep_id'])){
    $bdep_id = $_POST['bdep_id'];
}

$records = array();
    
$qry = $connect->query("SELECT bdep.*,bc.short_name,bc.acc_no from ct_db_bank_deposit bdep LEFT JOIN bank_creation bc on bdep.to_bank_id = bc.id where bdep.received = 1 and bdep.id = $bdep_id ");
// 0 means recevied or entered in credit bank deposit. not used current date because any time can be cash deposited to bank 

$row = $qry->fetch();
foreach($row as $key=>$val){
    $$key = $val;
}

$myStr = "DEP";
$selectIC = $connect->query("SELECT ref_code FROM ct_cr_cash_deposit WHERE ref_code != '' ");
if($selectIC->rowCount() > 0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_cr_cash_deposit WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["ref_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $ref_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-100001";
    $ref_code = $initialapp;
}
?>
<form id="cr_cd_form" name="cr_cd_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='to_bank_cd'>Bank Name</label>
                    <input type="hidden" class="form-control" id= 'bdep_id' name='bdep_id' value='<?php echo $id ?>' >
                    <input type="hidden" class="form-control" id= 'bank_id_cd' name='bank_id_cd' value='<?php echo $to_bank_id ?>' >
                    <input type="text" class="form-control" id= 'to_bank_cd' name='to_bank_cd' value='<?php echo $short_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='acc_no_cd'>Account No</label>
                    <input type="text" class="form-control" id= 'acc_no_cd' name='acc_no_cd' value='<?php echo $acc_no ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='location_cd'>Location</label>
                    <input type="text" class="form-control" id= 'location_cd' name='location_cd' value='<?php echo $location ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='amt_cd'>Amount</label>
                    <input type="text" class="form-control" id= 'amt_cd' name='amt_cd' value='<?php echo moneyFormatIndia($amount) ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='ref_code_cd'>Ref Code</label>
                    <input type="text" class="form-control" id= 'ref_code_cd' name='ref_code_cd' value='<?php echo $ref_code; ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='trans_id_cd'>Transaction ID</label>
                    <input type="number" class="form-control" id= 'trans_id_cd' name='trans_id_cd' placeholder="Enter Transaction ID" >
                    <span class='text-danger' style='display:none' id='trans_id_cdCheck'>Please Enter Transaction ID</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='remark_cd'>Remark</label>
                    <input type="text" class="form-control" id= 'remark_cd' name='remark_cd' placeholder="Enter Remark" >
                    <span class='text-danger' style='display:none' id='remark_cdCheck'>Please Enter Remark</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id= 'submit_cd' name='submit_cd' value="Submit" >
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