<?php
include '../ajaxconfig.php';

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$qry = $connect->query("SELECT profit_type,due_method_scheme,due_type FROM acknowlegement_loan_calculation where req_id = $req_id ");
$row = $qry->fetch();
$profit_type = $row['profit_type'];
$due_method_scheme = $row['due_method_scheme'];

if($profit_type == 1){
    $response['due_method'] = 'Monthly';
    $response['loan_type'] = $row['due_type'];
}else if($profit_type == 2){
    if($due_method_scheme == 1){$response['due_method'] = 'Monthly'; }else
    if($due_method_scheme == 2){$response['due_method'] = 'Weekly'; }else
    if($due_method_scheme == 3){$response['due_method'] = 'Daily';}
    $response['loan_type'] = 'Scheme';
}

echo json_encode($response);

// Close the database connection
$connect = null;
?>