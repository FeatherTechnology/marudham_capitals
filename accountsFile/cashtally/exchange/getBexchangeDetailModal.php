<?php
session_start();
$user_id = $_SESSION['userid'];
include('../../../ajaxconfig.php');


if(isset($_POST['bex_id'])){
    $bex_id = $_POST['bex_id'];
}

$records = array();
    
// $qry = $connect->query("SELECT dbex.* from ct_db_bexchange dbex JOIN user us on us.user_id = dbex.insert_login_id where dbex.id = '$bex_id' ");
$qry = $connect->query("SELECT bex.*,bc.short_name,bc.acc_no from ct_db_bexchange bex LEFT JOIN bank_creation bc on bc.id = bex.from_acc_id where bex.id = '$bex_id'");


$row = $qry->fetch();
foreach($row as $key=>$val){
    $$key = $val;
}
$from_bank = $short_name .' - '.substr($acc_no,-5);


//////////////////////// To get Exchange reference Code once again /////////////////////////
$myStr = "EXC";
$selectIC = $connect->query("SELECT ref_code FROM ct_cr_bexchange WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_cr_bexchange WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
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
///////////////////////////////////////////////////////////////////////////////////////////

// Close the database connection
$connect = null;
?>
<form id="cr_bex_form" name="cr_bex_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='ref_code'>Ref ID</label>
                    <input type="hidden" class="form-control" id= 'bex_id' name='bex_id' value='<?php echo $id ?>' >
                    <input type="hidden" class="form-control" id= 'from_acc_id' name='from_acc_id' value='<?php echo $from_acc_id ?>' >
                    <input type="hidden" class="form-control" id= 'to_bank_id' name='to_bank_id' value='<?php echo $to_bank_id ?>' >
                    <input type="hidden" class="form-control" id= 'to_user_id' name='to_user_id' value='<?php echo $to_user_id ?>' >
                    <input type="hidden" class="form-control" id= 'from_user_id' name='from_user_id' value='<?php echo $insert_login_id ?>' >
                    <input type="text" class="form-control" id= 'ref_code' name='ref_code' value='<?php echo $ref_code;?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='from_bank_name'>From Bank</label>
                    <input type="text" class="form-control" id= 'from_bank_name' name='from_bank_name' value='<?php echo $from_bank; ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='trans_id'>Transaction ID</label>
                    <input type="text" class="form-control" id= 'trans_id' name='trans_id'  placeholder="Enter Transaction ID">
                    <span class="text-danger" id="trans_idCheck" style="display:none">Please Enter Transaction ID</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='remark'>Remark</label>
                    <input type="text" class="form-control" id= 'remark' name='remark' value='' placeholder='Enter Remarks'>
                    <span class="text-danger" id="remarkCheck" style="display:none">Please Enter Remarks</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='amt'>Amount</label>
                    <input type="text" class="form-control" id= 'amt' name='amt' value='<?php echo moneyFormatIndia($amt) ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id= 'submit_bex' name='submit_bex' value="Submit" >
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
?>