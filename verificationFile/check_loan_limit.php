<?php
require '../ajaxconfig.php';

if (isset($_POST['loan_sub_id'])) {
    $sub_category = $_POST['loan_sub_id'];
}

$limit = array();
$loanLimit = $connect->query("SELECT loan_limit FROM `loan_calculation` WHERE sub_category = '" . strip_tags($sub_category) . "' ");
$cnt = $loanLimit->rowCount();
if ($cnt > 0) {
    while ($amnt = $loanLimit->fetch()) {
        $limit[] = $amnt['loan_limit'];
    }
}
echo json_encode($limit);

// Close the database connection
$connect = null;