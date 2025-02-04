<?php 
include('../ajaxconfig.php');

if(isset($_POST['dir_id'])){
    $dir_id = $_POST['dir_id'];
}

$staffArr = array();

$result=$connect->query("SELECT * FROM director_creation where status=0 and dir_id = $dir_id ");
while( $row = $result->fetch()){
    $dir_code = $row['dir_code'];
    $dir_name = $row['dir_name'];
    $mail_id = $row['mail_id'];

    $company_id = $row['company_id'];
    $qry = "SELECT * From company_creation where company_id = $company_id and status = 0";
    $res = $connect->query($qry);
    $row1 = $res->fetch();
    $company_name = $row1['company_name'];
    
    $staffArr[] = array("dir_code" => $dir_code, "dir_name" => $dir_name,'mail_id'=>$mail_id,'company_id'=>$company_id,'company_name'=>$company_name);
}

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>