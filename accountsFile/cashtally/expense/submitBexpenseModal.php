<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$response = '';

$username = $_POST['username'];
$usertype = $_POST['usertype'];
$ref_code = $_POST['ref_code'];
$bank_id = $_POST['bank_id'];
$cat = $_POST['cat'];
$part = $_POST['part'];
$vou_id = $_POST['vou_id'];
$trans_id = $_POST['trans_id'];
$rec_per = $_POST['rec_per'];
$remark = $_POST['remark'];
$amt = $_POST['amt'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


if(isset($_FILES['upd'])){
    $upd = $_FILES['upd']['name'];
    $pic_temp = $_FILES['upd']['tmp_name'];
    $picfolder="../../../uploads/expenseBill/".$upd ;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION);//get the file extention
    
    $upd = uniqid() . '.' . $fileExtension;
    while(file_exists("../../../uploads/expenseBill/".$upd)){
        //this loop will continue until it generates a unique file name
        $upd = uniqid() . '.' . $fileExtension;
    }

    move_uploaded_file($pic_temp, "../../../uploads/expenseBill/" . $upd);
}else{
    $upd='';
}
    

//////////////////////// To get Expense reference Code once again /////////////////////////
$myStr = "EXP";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bexpense WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_db_bexpense WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
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


//////////////////////////////////////////////////////////////////////////////////




$qry = $connect->query("INSERT INTO `ct_db_bexpense`(`username`, `usertype`,`ref_code`,`bank_id`, `cat`, `part`, `vou_id`,`trans_id`, `rec_per`, `remark`, `amt`, `upload`, `insert_login_id`, `created_date`) 
VALUES ('$username','$usertype','$ref_code','$bank_id','$cat','$part','$vou_id','$trans_id','$rec_per','$remark','$amt','$upd','$user_id','$op_date')");


if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submit";
}

echo $response;

// Close the database connection
$connect = null;
?>