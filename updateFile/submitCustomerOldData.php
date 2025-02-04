<?php
session_start();
require '../ajaxconfig.php';

if(isset($_FILES['due_chart_old'])){
    $due_chart_old = $_FILES['due_chart_old']['name'];
    $pic_temp = $_FILES['due_chart_old']['tmp_name'];
    $picfolder= "../uploads/updateFile/cus_data_old/".$due_chart_old ;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION);//get the file extention
    
    $due_chart_old = uniqid() . '.' . $fileExtension;
    while(file_exists("../uploads/updateFile/cus_data_old/".$due_chart_old)){
        //this loop will continue until it generates a unique file name
        $due_chart_old = uniqid() . '.' . $fileExtension;
    }
    // Move the file to the new file name
    move_uploaded_file($pic_temp, "../uploads/updateFile/cus_data_old/" . $due_chart_old);
}

$qry = $connect->query("INSERT Into cus_old_data (`cus_id`, `cus_name`, `mobile`, `area`, `sub_area`, `loan_cat`, `sub_cat`, `loan_amt`, `due_chart_file`, `created_date`) 
    values ('".$_POST['cus_id_old']."', '".$_POST['cus_name_old']."', '".$_POST['mobile_old']."', '".$_POST['area_old']."', '".$_POST['sub_area_old']."', '".$_POST['loan_cat_old']."',
    '".$_POST['sub_cat_old']."', '".$_POST['loan_amt_old']."', '".$due_chart_old."',now() ) ");

if($qry){
    echo 'Submitted Successfully';
}else{
    echo 'Fell into Error';
}

// Close the database connection
$connect = null;
?>