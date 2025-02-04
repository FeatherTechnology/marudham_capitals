<?php
include('../ajaxconfig.php');
if (isset($_POST['ag_id'])) {
    $ag_id = $_POST['ag_id'];
}


$result = $connect->query("SELECT * FROM agent_creation where status=0 and ag_id = $ag_id ");
while ($row = $result->fetch()) {
    $responsible = $row['responsible'];
}

echo json_encode($responsible);

// Close the database connection
$connect = null;