<?php
include('../ajaxconfig.php');

$adhaar_number = $_POST['adhaar_number'] ?? ''; //Auto generate Cus id added after deployment so cus_id stores adhaar number and autogen_cus_id is added as new column to store cus id. 

if (!empty($adhaar_number)) {
    $select = $connect->query("SELECT autogen_cus_id FROM customer_register WHERE cus_id = '$adhaar_number' AND autogen_cus_id !='' AND autogen_cus_id IS NOT NULL");
    if ($select && $select->rowCount() > 0) {
        $code = $select->fetch();
        $autogen_cus_id = $code['autogen_cus_id'];

    } else {
        // Find last auto ID
        $codeAvailable = $connect->query("
            SELECT MAX(CAST(autogen_cus_id AS UNSIGNED)) AS max_number 
            FROM customer_register 
            WHERE autogen_cus_id !='' AND autogen_cus_id IS NOT NULL 
        ");
        $row = $codeAvailable->fetch(PDO::FETCH_ASSOC);

        $autogen_cus_id = !empty($row['max_number']) ? ((int)$row['max_number'] + 1) : 10001;
    }

    echo json_encode($autogen_cus_id);
} else {
    echo json_encode(['error' => 'Missing adhaar_number']);
}
// Close the database connection
$connect = null;
