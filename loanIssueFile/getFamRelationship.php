<?php 
include('../ajaxconfig.php');

$famList_arr = array();

$adhaarno = $_POST['adhaarno'];
$cusId = $_POST['cusId'];
$cus = $_POST['cus'];

//the fingerprint validation is removed for temporarily so relation is set in outside of while for customer and use "LEFT JOIN" instead of "JOIN". Once validation set revert this code.
if($cus == '1'){
    $result = $connect->query("SELECT fp.hand,fp.ansi_template FROM fingerprints fp where fp.adhar_num = '$cusId' ");

    $famList_arr['relation'] = 'Customer';
    while( $row = $result->fetch()){
        $famList_arr['fpTemplate'] = $row['ansi_template'];
        $famList_arr['hand'] = $row['hand'];
    }

}else{

    $result = $connect->query("SELECT fam.relationship,fp.hand,fp.ansi_template FROM `verification_family_info` fam LEFT JOIN fingerprints fp ON fam.relation_aadhar = fp.adhar_num where fam.relation_aadhar='$adhaarno' ");

    while( $row = $result->fetch()){
        $famList_arr['relation'] = $row['relationship'];
        $famList_arr['fpTemplate'] = $row['ansi_template'];
        $famList_arr['hand'] = $row['hand'];
    }
}
echo json_encode($famList_arr);
?>