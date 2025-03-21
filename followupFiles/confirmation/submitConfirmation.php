<?php
session_start();
$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');


$req_id = $_POST['req_id'];
$cus_id = $_POST['cus_id'];
$person_type = $_POST['person_type'];
if($person_type == 3){
    $person_name = $_POST['person_name1'];
}else{
    $person_name = $_POST['person_name'];
}
$relationship = $_POST['relationship'];
$mobile = $_POST['mobile'];
$status = $_POST['status'];
$sub_status = '';
$label = '';
$remark = '';
$remove_status = '';
if($status == 1){
    $remove_status = 1;
}elseif($status == 2){
    $sub_status = $_POST['sub_status'];
}elseif($status == 3){
    $label = $_POST['label'];
    $remark = $_POST['remark'];
}

if(isset($_FILES['file'])){
    $file = $_FILES['file']['name'];
    $pic_temp = $_FILES['file']['tmp_name'];
    $picfolder="../../uploads/confirmation_followup/".$file ;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION);//get the file extention
    
    $file = uniqid() . '.' . $fileExtension;
    while(file_exists("../../uploads/confirmation_followup/".$file)){
        //this loop will continue until it generates a unique file name
        $file = uniqid() . '.' . $fileExtension;
    }
    // Move the file to the new file name
    move_uploaded_file($pic_temp, "../../uploads/confirmation_followup/" . $file);

}else{
    $file='';
}

    $sql = $connect->query("INSERT INTO `confirmation_followup`(`req_id`, `cus_id`, `person_type`, `person_name`, `relationship`, `mobile`, `upload`, `status`, `sub_status`, `label`, `remark`, `remove_status`, `insert_login_id`, `created_date`) VALUES ('$req_id','$cus_id','$person_type','$person_name','$relationship','$mobile','$file','$status','$sub_status','$label','$remark','$remove_status','$userid',NOW())");
    
    if($sql){
        $response = 'Inserted Successfully';
    }else{
        $response = 'Error While Inserting';
    }

echo $response;

// Close the database connection
$connect = null;
?>