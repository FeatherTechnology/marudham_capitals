<?php
include('../ajaxconfig.php');

$responsible = "1"; //No. if ag_id is empty return 1.
if (isset($_POST['ag_id']) && !empty($_POST['ag_id'])) {
    $ag_id = $_POST['ag_id'];

    $result = $connect->query("SELECT responsible FROM agent_creation where status = 0 AND ag_id = $ag_id ");
    $row = $result->fetch();
        $responsible = $row['responsible'];
}

echo json_encode($responsible);

// Close the database connection
$connect = null;