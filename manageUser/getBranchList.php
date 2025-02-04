<?php 
include('../ajaxconfig.php');

if(isset($_POST['company_id'])){
    $company_id = $_POST['company_id'];
}

$staffArr = array();

$result=$connect->query("SELECT * FROM branch_creation where status=0 and company_name = '".$company_id."' ");
while( $row = $result->fetch()){
    $branch_id = $row['branch_id'];
    $branch_name = $row['branch_name'];
    
    $staffArr[] = array("branch_id" => $branch_id, "branch_name" => $branch_name);
}

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>