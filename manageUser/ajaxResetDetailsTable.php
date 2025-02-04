<?php 
include('../ajaxconfig.php');

if(isset($_POST['staff_id'])){
    $staff_id = $_POST['staff_id'];
}

$staffArr = array();

$result=$connect->query("SELECT * FROM staff_creation where status=0 and staff_id = $staff_id ");
while( $row = $result->fetch()){
    
    $staff_code = $row['staff_code'];
    $staff_name = $row['staff_name'];
    $mail = $row['mail'];
    
    $company_id = $row['company_id'];
    $qry = "SELECT * From company_creation where company_id = $company_id and status = 0";
    $res = $connect->query($qry);
    $row1 = $res->fetch();
    $company_name = $row1['company_name'];

    $department = $row['department'];
    $team = $row['team'];
    $designation = $row['designation'];

    $staffArr[] = array("staff_code" => $staff_code, "staff_name" => $staff_name,'mail'=>$mail,'company_name'=>$company_name,'department'=>$department,
    'team'=>$team,'designation'=>$designation,'company_id'=>$company_id);
}

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>