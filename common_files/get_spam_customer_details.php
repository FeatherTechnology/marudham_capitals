<?php
include('../ajaxconfig.php');

$cus_id = $_POST['cus_id'] ?? '';
$sts = '0';

try {

    /* 1️⃣ Get mark_as_spam */
    $stmt = $connect->prepare("SELECT mark_as_spam FROM customer_register WHERE cus_id = ?");
    $stmt->execute([$cus_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $cus_mark_as_spam = $row['mark_as_spam'] ?? '0';

    /* 2️⃣ */
    $stmt = $connect->prepare("SELECT mark_as_spam FROM verification_family_info WHERE relation_aadhar = ?");
    $stmt->execute([$cus_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $fam_mark_as_spam = $row['mark_as_spam'] ?? '0';

    $sts = ($cus_mark_as_spam =='1' || $fam_mark_as_spam =='1') ? '1' : '0';

} catch (Exception $e) {
    $sts = '2';
    echo "Transaction Failed: " . $e->getMessage();
}

echo json_encode($sts);

// Close the database connection
$connect = null;