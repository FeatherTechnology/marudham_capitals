<?php
include '../ajaxconfig.php';

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$records = array();

$qry = $connect->query("SELECT * from acknowlegement_documentation where req_id = '$req_id' ");
if($qry){
    $row = $qry->fetch();
    $records['mort_process'] = $row['mortgage_process'];
    if($records['mort_process'] == '0'){
        
        $records['mort_process_noc'] = $row['mortgage_process_noc'];
        $records['mort_process_noc_date'] = $row['mort_noc_date'];
        $records['mort_process_noc_person'] = $row['mort_noc_person'];
        $records['mort_process_noc_name'] = $row['mort_noc_name'];
        
        $records['prop_holder_type'] = $row['Propertyholder_type'];
        $records['prop_holder_name'] = $row['Propertyholder_name'];
        $records['prop_holder_rel'] = $row['Propertyholder_relationship_name'];//fam id
        $records['doc_prop_rel'] = $row['doc_property_relation'];//like father, mother,.etc, if customer NIL
        $records['doc_prop_type'] = $row['doc_property_type'];
        $records['doc_prop_meas'] = $row['doc_property_measurement'];
        $records['doc_prop_loc'] = $row['doc_property_location'];
        $records['doc_prop_val'] = $row['doc_property_value'];
        
        $records['mort_name'] = $row['mortgage_name'];
        $records['mort_des'] = $row['mortgage_dsgn'];
        $records['mort_num'] = $row['mortgage_nuumber'];
        $records['reg_office'] = $row['reg_office'];
        $records['mort_value'] = $row['mortgage_value'];
        
        $records['mort_doc'] = $row['mortgage_document'];
        $records['mort_doc_noc'] = $row['mortgage_document_noc'];
        $records['mort_doc_noc_date'] = $row['mort_doc_noc_date'];
        $records['mort_doc_noc_person'] = $row['mort_doc_noc_person'];
        $records['mort_doc_noc_name'] = $row['mort_doc_noc_name'];
        
        $records['mort_doc_upd'] = $row['mortgage_document_upd'];//document uploaded name
        $records['mort_doc_used'] = $row['mortgage_document_used'];//0 if unused
        
        $records['mort_doc_pending'] = $row['mortgage_document_pending'];//yes if document not uploaded
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>

