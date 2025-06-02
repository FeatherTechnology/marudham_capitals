<?php

include('../../../ajaxconfig.php');

$user_id = $_POST['user_id'];
$li_id = $_POST['li_id'];

$qry = $connect->query("SELECT id,cheque_no,cheque_value,transaction_id,transaction_value,issued_to,req_id,insert_login_id,bank_id FROM `loan_issue` where (agent_id = '' or agent_id IS null) and ((cheque_value!= '' or transaction_value !='') or (cheque_value!= '' and transaction_value !='')) and id='$li_id' and insert_login_id = '$user_id' ");
$row = $qry->fetch();

if($row['cheque_value'] != '' and $row['transaction_value'] == ''){
            
    $netcash = intVal($row['cheque_value']);
    $cheque_no = $row['cheque_no'];
    $trans_id = '';

}else if($row['transaction_value'] != '' and $row['cheque_value'] == ''){
    
    $netcash = intVal($row['transaction_value']);
    $trans_id = $row['transaction_id'];
    $cheque_no = '';

}else if($row['cheque_value'] != '' and $row['transaction_value'] != ''){
    $netcash = intVal($row['cheque_value']) + intVal($row['transaction_value']);
    $cheque_no = $row['cheque_no'];
    $trans_id = $row['transaction_id'];
}

$issued_to = $row['issued_to'];
$req_id = $row['req_id'];
$user_id = $row['insert_login_id'];
$bank_id = $row['bank_id'];

$qry1 = $connect->query("SELECT role,fullname FROM `user` where user_id= '$user_id' ");
$row1 = $qry1->fetch();
$role = $row1['role'];if($role == 1){$usertype = 'Director';}else if($role==3){$usertype = 'Staff';}
$username = $row1['fullname'];

///////////////////////////////////////// To get Exchange reference Code once again /////////////////////////////////////////////////
$myStr = "ISS";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bissued WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_db_bissued WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Close the database connection
$connect = null;
?>
<form id="db_bissued_form" name="db_bissued_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='ref_code'>Ref ID</label>
                    <input type="hidden" class="form-control" id= 'li_id' name='li_id' value='<?php echo $li_id;?>' readonly>
                    <input type="hidden" class="form-control" id= 'li_user_id' name='li_user_id' value='<?php echo $user_id;?>' readonly>
                    <input type="hidden" class="form-control" id= 'li_bank_id' name='li_bank_id' value='<?php echo $bank_id;?>' readonly>
                    <input type="text" class="form-control" id= 'ref_code' name='ref_code' value='<?php echo $ref_code;?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='username'>User Name</label>
                    <input type="text" class="form-control" id= 'username' name='username' value='<?php echo $username; ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='usertype'>User Type</label>
                    <input type="text" class="form-control" id= 'usertype' name='usertype' value='<?php echo $usertype; ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='cheque_no'>Cheque No.</label>
                    <input type="text" class="form-control" id= 'cheque_no' name='cheque_no' value='<?php echo $cheque_no; ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='trans_id'>Transaction ID</label>
                    <input type="text" class="form-control" id= 'trans_id' name='trans_id' value='<?php echo $trans_id;?>' <?php if($trans_id != '') echo 'readonly'; ?> placeholder="Enter Transaction ID">
                    <?php if($trans_id == ''){?>
                        <span class="text-danger" id="trans_idCheck" style="display:none">Please Enter Transaction ID</span>
                    <?php }?>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='netcash'>Net Cash</label>
                    <input type="text" class="form-control" id= 'netcash' name='netcash' value='<?php echo moneyFormatIndia($netcash);?>' readonly>
                </div>
            </div>
            <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='amt'>Amount</label>
                    <input type="text" class="form-control" id= 'amt' name='amt' value='<?php echo moneyFormatIndia($netcash) ;?>' readonly>
                </div>
            </div> -->
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id= 'submit_bissued' name='submit_bissued' value="Submit" >
                </div>
            </div>
            
        </div>
    </div>
</form>


<?php
//Format number in Indian Format
function moneyFormatIndia($num1) {
    if($num1 < 0){
        $num = str_replace("-","",$num1);
    }else{
        $num = $num1;
    }
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

    if($num1 < 0){
        $thecash = "-" . $thecash;
    }

    return $thecash;
}

?>