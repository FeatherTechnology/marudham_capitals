<?php
session_start();
$user_id = $_SESSION['userid'];

include("../../ajaxconfig.php");

$credit = ''; $debit = '';$response = '';

$bank_id = $_POST['bank_name'];
$acc_no = $_POST['acc_no'];
$trans_date = $_POST['trans_date'];
$trans_id = $_POST['trans_id'];
$narration = $_POST['narration'];
$crdb = $_POST['crdb'];
$amt = $_POST['amt'];
if($crdb == 1){$credit = $amt; }else if($crdb == 2){$debit = $amt; }
$balance = $_POST['bal'];

if(isset($_POST['mode'])){ //mode contains if the old data need to be deleted or not
    $mode = $_POST['mode'];
}else{
    $mode = '';
}


$qry = $connect->query("INSERT INTO `bank_stmt`(`bank_id`, `trans_date`, `narration`,`trans_id`, `credit`, `debit`, `balance`, `insert_login_id`, `created_date`) 
VALUES ('$bank_id','$trans_date','$narration','$trans_id','$credit','$debit','$balance','$user_id',now() )");

$insert_id = $connect->lastInsertId(); //last inserted id

if($mode != ''){

    $selectqry = $connect->query("SELECT trans_date from bank_stmt where insert_login_id = '$user_id' and id != '$insert_id' and trans_date = '$trans_date' ");
    if($selectqry->rowCount() > 0){
        $deleteqry = $connect->query("DELETE from bank_stmt where insert_login_id = '$user_id' and id != '$insert_id' and trans_date = '$trans_date' and created_date < now() ");
    }
}

if($qry ){
    $response = 0;
}else{
    $response = 1;
}

echo $response;

// Close the database connection
$connect = null;
?>
