<?php 
include('../ajaxconfig.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$detailrecords = array();

    $qry = $connect->query("SELECT count(*) as rowcnt,a.ag_name,a.loan_payment,b.agent_id FROM `agent_creation` a left join request_creation b on a.ag_id = b.agent_id  WHERE  b.req_id='".$req_id."' ");
    $row = $qry->fetch();
    if($row['rowcnt']>0){
    $detailrecords['ag_name'] = $row['ag_name'];
    $detailrecords['loan_payment'] = $row['loan_payment'];
    $detailrecords['agent_id'] = $row['agent_id'];
    }else{
        $detailrecords['ag_name'] = '';
        $detailrecords['loan_payment'] = '';
        $detailrecords['agent_id'] = '';
    }
    
echo json_encode($detailrecords);

// Close the database connection
$connect = null;
?>