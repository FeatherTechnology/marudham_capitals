<?php
session_start();
$user_id = $_SESSION['userid'];
include('../../../ajaxconfig.php');

if(isset($_POST['hex_id'])){
    $hex_id = $_POST['hex_id'];
}

$records = array();
    
$qry = $connect->query("SELECT dhex.*,us.fullname, us.role from ct_db_hexchange dhex JOIN user us on us.user_id = dhex.insert_login_id where dhex.id = '$hex_id' ");

$row = $qry->fetch();
foreach($row as $key=>$val){
    $$key = $val;
}

if($role == '1') {$rolename =  "Director"; }else if($role == '3') { $rolename = "Staff"; }else{ $rolename = '';}

// Close the database connection
$connect = null;
?>
<form id="cr_hex_form" name="cr_hex_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='from_user_name'>From User Name</label>
                    <input type="hidden" class="form-control" id= 'hex_id' name='hex_id' value='<?php echo $id ?>' >
                    <input type="hidden" class="form-control" id= 'from_user_id' name='from_user_id' value='<?php echo $insert_login_id ?>' >
                    <input type="hidden" class="form-control" id= 'to_user_id' name='to_user_id' value='<?php echo $to_user_id ?>' >
                    <input type="text" class="form-control" id= 'from_user_name' name='from_user_name' value='<?php echo $fullname ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='user_type'>User Type</label>
                    <input type="text" class="form-control" id= 'user_type' name='user_type' value='<?php echo $rolename; ?>' readonly>
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
                    <label for='remark'>Remark</label>
                    <input type="text" class="form-control" id= 'remark' name='remark' value='' placeholder='Enter Remarks'>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id= 'submit_hex' name='submit_hex' value="Submit" >
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