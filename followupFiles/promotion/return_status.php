<?php
session_start();
$user_id = $_SESSION['userid'];
include("../../ajaxconfig.php");
try {

    $cus_id = $_POST['cus_id'] ?? '';
    $req_id = $_POST['req_id'] ?? '';

    if (empty($cus_id) || empty($req_id)) {
        echo json_encode([
            'status' => 0,
            'message' => 'Invalid customer or request ID'
        ]);
        exit;
    }

    $stmt = $connect->prepare(" UPDATE request_creation SET return_sts = 1 WHERE req_id = ? AND cus_id = ?");
    $stmt->execute([ $req_id,$cus_id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 1,
            'message' => 'Customer returned successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 0,
            'message' => 'Request not found or already returned'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 0,
        'message' => $e->getMessage()
    ]);
}

$connect = null;
?>
