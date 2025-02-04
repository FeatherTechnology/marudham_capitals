<?php
session_start();
$userid = $_SESSION['userid'];
include '../ajaxconfig.php';

$id = $_POST['id'];
$req_id = $_POST['req_id'];

if($id == 'update_mortgage'){

    $file = $_FILES['mortgage_document_upd'];

}else if($id == 'update_mortgage'){

    $file = $_FILES['RC_document_upd'];

}

$qry = 'UPDATE acknowlegement_documentation SET ';

if($id == 'update_mortgage'){

    $mortgage_doc_upd = $_FILES['mortgage_document_upd']['name'];
    $upd_temp = $_FILES['mortgage_document_upd']['tmp_name'];
    $folder="../uploads/verification/mortgage_doc/".$mortgage_doc_upd ;
    
    $fileExtension = pathinfo($folder, PATHINFO_EXTENSION);//get the file extention
    $mortgage_doc_upd = uniqid() . '.' . $fileExtension;
    while(file_exists("../uploads/verification/mortgage_doc/".$mortgage_doc_upd)){
        //this loop will continue until it generates a unique file name
        $mortgage_doc_upd = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($upd_temp, "../uploads/verification/mortgage_doc/".$mortgage_doc_upd);
    

    $qry .= " `mortgage_document_upd`='".strip_tags($mortgage_doc_upd)."',`update_login_id`='$userid',`updated_date`=now() ";

}else if($id == 'update_endorsement'){

    $Rc_document_upd = $_FILES['RC_document_upd']['name'];
    $upd_temp = $_FILES['RC_document_upd']['tmp_name'];
    $folder="../uploads/verification/endorsement_doc/".$Rc_document_upd ;
    
    $fileExtension = pathinfo($folder, PATHINFO_EXTENSION);//get the file extention
    $Rc_document_upd = uniqid() . '.' . $fileExtension;
    while(file_exists("../uploads/verification/endorsement_doc/".$Rc_document_upd)){
        //this loop will continue until it generates a unique file name
        $Rc_document_upd = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($upd_temp, "../uploads/verification/endorsement_doc/".$Rc_document_upd);
    
    $qry .= " `Rc_document_upd`='".strip_tags($Rc_document_upd)."',`update_login_id`='$userid',`updated_date`=now() ";
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