<?php 
session_start();
include('../../ajaxconfig.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

if(isset($_POST['sub_category_upd'])){
    $sub_category_upd = $_POST['sub_category_upd'];
}
$detailrecords = array();

if($sub_category_upd == ''){
    
    $result=$connect->query("SELECT * FROM request_category_info where req_ref_id = $req_id ");
    $i=0;
    while($row = $result->fetch()){
        $detailrecords[$i] = $row['category_info'];
        $i++;
    }
}else{
    $result=$connect->query("SELECT * FROM acknowledgement_loan_cal_category where req_id = $req_id ");
    $i=0;
    while($row = $result->fetch()){
        $detailrecords[$i] = $row['category'];
        $i++;
    }
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;
?>