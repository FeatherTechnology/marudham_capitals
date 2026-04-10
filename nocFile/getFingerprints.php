<?php
include('../ajaxconfig.php');

if(isset($_POST['id'])){
    $id = $_POST['id'];
}
if(isset($_POST['family'])){
    $family = $_POST['family'];
}

$records = array();
if($family == 'true'){
    $qry = $connect->query("SELECT fp.ansi_template, fp.hand FROM verification_family_info fam JOIN fingerprints fp on fam.relation_aadhar = fp.adhar_num  WHERE fam.id='$id' ");
    $row = $qry->fetch();
    $records['fingerprint'] = $row['ansi_template'] ?? '';
    $records['hand'] = $row['hand'] ?? '';
}else{
    $qry = $connect->query("SELECT ansi_template, hand FROM fingerprints WHERE adhar_num='$id' ");
    $row = $qry->fetch();
    $records['fingerprint'] = $row['ansi_template'] ?? '';
    $records['hand'] = $row['hand'] ?? '';
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>