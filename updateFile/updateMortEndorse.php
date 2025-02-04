<?php
session_start();
include '../ajaxconfig.php';

$userid = $_SESSION['userid'];

$formData = $_POST;
foreach ($formData as $name => $value) {
    // Do something with each $name and $value
    $$name = $value;
}

if(isset($mortgage_process) && $mortgage_process == '1'){
    $pendingchk = 'NO';
}elseif(isset($mortgage_process) && $mortgage_process == '0' && isset($mortgage_document) && $mortgage_document == '0' ){//if document is yes(0) then it will be uploaded, then pending sts will be NO
    $pendingchk = 'NO';
}

if(isset($endorsement_process) && $endorsement_process == '1'){
    $endorsependingchk = 'NO';
}elseif(isset($endorsement_process) && $endorsement_process == '0' && isset($en_RC) && $en_RC == '0' ){//if document is yes(0) then it will be uploaded, then pending sts will be NO
    $endorsependingchk = 'NO';
}

$qry = 'UPDATE acknowlegement_documentation SET ';

if($id == 'update_mortgage'){

    $qry .= " `mortgage_process`='".strip_tags($mortgage_process)."',`Propertyholder_type`='".strip_tags($Propertyholder_type)."',
        `Propertyholder_name`='".strip_tags($Propertyholder_name)."',`Propertyholder_relationship_name`='".strip_tags($Propertyholder_relationship_name)."',
        `doc_property_relation`='".strip_tags($doc_property_relation)."',`doc_property_type`='".strip_tags($doc_property_pype)."',
        `doc_property_measurement`='".strip_tags($doc_property_measurement)."',`doc_property_location`='".strip_tags($doc_property_location)."',
        `doc_property_value`='".strip_tags($doc_property_value)."',`mortgage_name`='".strip_tags($mortgage_name)."',`mortgage_dsgn`='".strip_tags($mortgage_dsgn)."',
        `mortgage_nuumber`='".strip_tags($mortgage_nuumber)."',`reg_office`='".strip_tags($reg_office)."',`mortgage_value`='".strip_tags($mortgage_value)."',
        `mortgage_document`='".strip_tags($mortgage_document)."',`mortgage_document_upd`='".strip_tags($mortgage_doc_upd)."',
        `mortgage_document_pending`='".strip_tags($pendingchk)."',`update_login_id`='$userid',`updated_date`=now() ";

}else if($id == 'update_endorsement'){

    $qry .= " `endorsement_process`='".strip_tags($endorsement_process)."',`owner_type`='".strip_tags($owner_type)."',`owner_name`='".strip_tags($owner_name)."',
        `ownername_relationship_name`='".strip_tags($ownername_relationship_name)."',`en_relation`='".strip_tags($en_relation)."',`vehicle_type`='".strip_tags($vehicle_type)."',
        `vehicle_process`='".strip_tags($vehicle_process)."',`en_Company`='".strip_tags($en_Company)."',`en_Model`='".strip_tags($en_Model)."',
        `vehicle_reg_no`='".strip_tags($vehicle_reg_no)."',`endorsement_name`='".strip_tags($endorsement_name)."',`en_RC`='".strip_tags($en_RC)."',
        `Rc_document_pending`='".strip_tags($endorsependingchk)."',`en_Key`='".strip_tags($en_Key)."',`Rc_document_upd`='".strip_tags($rc_doc_upd)."',
        `update_login_id`='$userid',`updated_date`=now() ";
}

$qry .= " WHERE req_id = '$req_id' ";

$run = $connect->query($qry);

if($qry){
    $response = 'Updated Successfully';
}else{
    $response = 'Error While Updating';
}

echo $response;

// Close the database connection
$connect = null;
?>