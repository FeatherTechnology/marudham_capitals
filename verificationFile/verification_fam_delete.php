<?php
include '../ajaxconfig.php';

$id = $_POST['famid'];

$Qry = $connect->prepare("SELECT EXISTS (
    SELECT 1 FROM customer_profile WHERE guarentor_name = ?
    UNION ALL
    SELECT 1 FROM verification_kyc_info WHERE fam_mem = ?
    UNION ALL
    SELECT 1 FROM signed_doc_info WHERE signType_relationship = ?
    UNION ALL
    SELECT 1 FROM cheque_info WHERE holder_relationship_name = ?
    UNION ALL
    SELECT 1 FROM document_info WHERE relation_name = ?
    UNION ALL
    SELECT 1 FROM acknowlegement_documentation
    WHERE Propertyholder_relationship_name = ?
       OR ownername_relationship_name = ?
    UNION ALL
    SELECT 1 FROM verification_documentation
    WHERE Propertyholder_relationship_name = ?
       OR ownername_relationship_name = ?
    LIMIT 1
) AS is_used");

$Qry->execute([
    $id,
    $id,
    $id,
    $id,
    $id,
    $id,
    $id,
    $id,
    $id
]);

$isUsed = $Qry->fetchColumn();

if ($isUsed) {
	$message = "Family member used as Guarantor or Doc Holder.";

}else{
	$delct = $connect->query("DELETE FROM `verification_family_info` WHERE id = '$id' ");
	
	if ($delct) {
		$message = " Family Info Deleted Successfully";
	}
}

echo json_encode($message);

// Close the database connection
$connect = null;