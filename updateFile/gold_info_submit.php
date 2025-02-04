<?php
include '../ajaxconfig.php';

$req_id        = $_POST['req_id'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$gold_sts        = $_POST['gold_sts'];
$gold_type              = $_POST['gold_type'];
$Purity             = $_POST['Purity'];
$gold_Count = $_POST['gold_Count'];
$gold_Weight             = $_POST['gold_Weight'];
$gold_Value             = $_POST['gold_Value'];
$goldID             = $_POST['goldID'];

$result = '';

if(isset($_FILES['gold_upload'])){
    $gold_upload = $_FILES['gold_upload']['name'];
    $pic_temp = $_FILES['gold_upload']['tmp_name'];
    $picfolder= "../uploads/gold_info/".$gold_upload ;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION);//get the file extention
    
    $gold_upload = uniqid() . '.' . $fileExtension;
    while(file_exists("../uploads/gold_info/".$gold_upload)){
        //this loop will continue until it generates a unique file name
        $gold_upload = uniqid() . '.' . $fileExtension;
    }
    // Move the file to the new file name
    move_uploaded_file($pic_temp, "../uploads/gold_info/" . $gold_upload);
}else{
    $gold_upload = $_POST['goldupload'];
}

if($goldID == ''){

    $insert_qry = $connect ->query("INSERT INTO `gold_info`(`cus_id`,`req_id`, `gold_sts`, `gold_type`, `Purity`, `gold_Count`, `gold_Weight`, `gold_Value`, `gold_upload`) VALUES ('$cus_id','$req_id','$gold_sts','$gold_type','$Purity','$gold_Count','$gold_Weight','$gold_Value', '$gold_upload')");

}
else{
    $update = $connect->query("UPDATE `gold_info` SET `gold_sts`='$gold_sts',`gold_type`='$gold_type',`Purity`='$Purity',`gold_Count`='$gold_Count',`gold_Weight`='$gold_Weight',`gold_Value`='$gold_Value', `gold_upload`='$gold_upload' WHERE `id`='$goldID' ");

}

if($insert_qry){
    $result = "Gold Info Inserted Successfully.";
}
elseif($update){
    $result = "Gold Info Updated Successfully.";
}

echo json_encode($result);
