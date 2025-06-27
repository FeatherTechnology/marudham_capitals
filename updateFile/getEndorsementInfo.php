<?php
include '../ajaxconfig.php';

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$records = array();

$qry = $connect->query("SELECT * from acknowlegement_documentation where req_id = '$req_id' ");
if($qry){
    $row = $qry->fetch();
    $records['end_process'] = $row['endorsement_process'];
    if($records['end_process'] == '0'){
        
        $records['end_process_noc'] = $row['endorsement_process_noc'];
        $records['end_process_noc_date'] = $row['endor_noc_date'];
        $records['end_process_noc_person'] = $row['endor_noc_person'];
        $records['end_process_noc_name'] = $row['endor_noc_name'];
        
        $records['owner_type'] = $row['owner_type'];
        $records['owner_name'] = $row['owner_name'];//actual name
        $records['owner_rel_name'] = $row['ownername_relationship_name'];//fam id
        $records['owner_relation'] = $row['en_relation'];//like father, mother,.etc, if customer NIL
        
        $records['vehicle_type'] = $row['vehicle_type'];
        $records['vehicle_process'] = $row['vehicle_process'];
        $records['vehicle_comp'] = $row['en_Company'];
        $records['vehicle_mod'] = $row['en_Model'];
        $records['vehicle_reg_no'] = $row['vehicle_reg_no'];
        $records['end_name'] = $row['endorsement_name'];
        
        $records['end_rc'] = $row['en_RC'];
        $records['end_rc_noc'] = $row['en_RC_noc'];
        $records['end_rc_noc_date'] = $row['en_rc_noc_date'];
        $records['end_rc_noc_person'] = $row['en_rc_noc_person'];
        $records['end_rc_noc_name'] = $row['en_rc_noc_name'];
        
        $records['end_rc_used'] = $row['en_RC_used'];//0 if unused
        $records['end_rc_doc_upd'] = $row['Rc_document_upd'];//document uploaded name
        $records['end_rc_doc_pend'] = $row['Rc_document_pending'];//yes if document not uploaded
        
        
        $records['end_key'] = $row['en_Key'];
        $records['end_key_noc'] = $row['en_Key_noc'];
        $records['end_key_noc_date'] = $row['en_key_noc_person'];
        $records['end_key_noc_person'] = $row['en_key_noc_name'];
        $records['end_key_noc_name'] = $row['en_Key_used'];
        
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>

