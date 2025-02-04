<?php 
include('../ajaxconfig.php');
if (isset($_POST['company_id'])) {
    $company_id = $_POST['company_id'];
}

$branchDetails = array();

$selectIC = $connect->query("SELECT * FROM branch_creation WHERE company_name = '".$company_id."' && status = '0' ");
if($selectIC->rowCount()>0)
{$i=0;
    while($row = $selectIC->fetch()){
        $branchDetails[$i]['branch_id'] = $row["branch_id"];
        $branchDetails[$i]['branch_name'] = $row["branch_name"];
        $i++;
	}

}

echo json_encode($branchDetails);

// Close the database connection
$connect = null;
?>