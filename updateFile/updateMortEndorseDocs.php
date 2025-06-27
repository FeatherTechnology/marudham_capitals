<?php
session_start();
$userid = $_SESSION['userid'];
include '../ajaxconfig.php';

$id = $_POST['id'];
$req_id = $_POST['req_id'];

$qry = 'UPDATE acknowlegement_documentation SET ';

if($id == 'update_mortgage'){

    if (!empty($_FILES['mortgage_document_upd'])) { //upload file

        //remove old file
        if (!empty($_POST['mortgage_document_old_upd'])) {
            $filePath = "../uploads/verification/mortgage_doc/" . $_POST['mortgage_document_old_upd'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $mortgage_doc_upd = $_FILES['mortgage_document_upd']['name'];
        $upd_temp = $_FILES['mortgage_document_upd']['tmp_name'];
        $folder="../uploads/verification/mortgage_doc/".$mortgage_doc_upd ;
        
        $fileExtension = pathinfo($folder, PATHINFO_EXTENSION);//get the file extention

        $doc_upd = uniqid() . '.' . $fileExtension;
        while(file_exists("../uploads/verification/mortgage_doc/".$doc_upd)){
            //this loop will continue until it generates a unique file name
            $doc_upd = uniqid() . '.' . $fileExtension;
        }

        move_uploaded_file($upd_temp, "../uploads/verification/mortgage_doc/".$doc_upd);
    
    } else{ //hidden value
        $doc_upd = $_POST['mortgage_document_old_upd'] ?? '';

    }

    $qry .= " `mortgage_document_upd`='".strip_tags($doc_upd)."',`update_login_id`='$userid',`updated_date`=now() ";

}else if($id == 'update_endorsement'){

    if (!empty($_FILES['RC_document_upd'])) {

        //remove old file
        if (!empty($_POST['RC_document_old_upd'])) {
            $filePath = "../uploads/verification/endorsement_doc/" . $_POST['RC_document_old_upd'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $Rc_document_upd = $_FILES['RC_document_upd']['name'];
        $upd_temp = $_FILES['RC_document_upd']['tmp_name'];
        $folder="../uploads/verification/endorsement_doc/".$Rc_document_upd ;
        
        $fileExtension = pathinfo($folder, PATHINFO_EXTENSION);//get the file extention

        $doc_upd = uniqid() . '.' . $fileExtension;
        while(file_exists("../uploads/verification/endorsement_doc/".$doc_upd)){
            //this loop will continue until it generates a unique file name
            $doc_upd = uniqid() . '.' . $fileExtension;
        }
        
        move_uploaded_file($upd_temp, "../uploads/verification/endorsement_doc/".$doc_upd);

    } else {
        $doc_upd = $_POST['RC_document_old_upd'] ?? '';

    }
    
    $qry .= " `Rc_document_upd`='".strip_tags($doc_upd)."',`update_login_id`='$userid',`updated_date`=now() ";
}

$qry .= " WHERE req_id = '$req_id' ";

$run = $connect->query($qry);

if($qry){
    $response = 'Updated Successfully';
}else{
    $response = 'Error While Updating';
}

echo json_encode(array("response" => $response, "doc_upd_name" => $doc_upd ));

// Close the database connection
$connect = null;
?>