<?php
include('../ajaxconfig.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['noc_member'])){
    $noc_member = $_POST['noc_member'];
}
$records = array();

if($noc_member == 2){
    $qry = $connect->query("SELECT cp.guarentor_name,fam.famname,fp.ansi_template FROM acknowlegement_customer_profile cp LEFT JOIN verification_family_info fam ON fam.id = cp.guarentor_name
    LEFT JOIN fingerprints fp ON fam.relation_aadhar = fp.adhar_num WHERE cp.req_id='$req_id' && fp.ansi_template != '' ");
    $row = $qry->fetch();
    $records['guarentor_id'] = $row['guarentor_name'];
    $records['guarentor_name'] = $row['famname'];
    $records['fingerprint'] = $row['ansi_template'];
}else if($noc_member == 3){
    $qry = $connect->query("SELECT * FROM verification_family_info WHERE req_id='$req_id' ");
    $i=0;
    while($row = $qry->fetch()){
        $records['fam_id'][$i] = $row['id'];
        $records['fam_name'][$i] = $row['famname'];
        $i++;
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>