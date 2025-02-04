<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$response = '';

$username = $_POST['username'];
$usertype = $_POST['usertype'];
$cat = $_POST['cat'];
$part = $_POST['part'];
$vou_id = $_POST['vou_id'];
$rec_per = $_POST['rec_per'];
$remark = $_POST['remark'];
$amt = $_POST['amt'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


if(isset($_FILES['upd'])){
    $upd = $_FILES['upd']['name'];
    $pic_temp = $_FILES['upd']['tmp_name'];
    $picfolder="../../../uploads/expenseBill/".$upd ;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION);
    
    $upd = uniqid() . '.' . $fileExtension;
    while(file_exists("../../../uploads/expenseBill/".$upd)){
        //this loop will continue until it generates a unique file name
        $upd = uniqid() . '.' . $fileExtension;
    }

    move_uploaded_file($pic_temp, "../../../uploads/expenseBill/" . $upd);
}else{
    $upd='';
}
    
$qry = $connect->query("INSERT INTO `ct_db_hexpense`(`username`, `usertype`, `cat`, `part`, `vou_id`, `rec_per`, `remark`, `amt`, `upload`, `insert_login_id`, `created_date`) 
VALUES ('$username','$usertype','$cat','$part','$vou_id','$rec_per','$remark','$amt','$upd','$user_id','$op_date')");


if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submit";
}

echo $response;

// Close the database connection
$connect = null;
?>