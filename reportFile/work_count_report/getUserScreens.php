<?php
include '../../ajaxconfig.php';

$user_id = (int)($_POST['user_id'] ?? 0);

$stmt = $connect->prepare("SELECT request, verification, approval, loan_issue, collection, closed FROM user WHERE user_id = ? ");

$stmt->execute([$user_id]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode([]);
    exit;
}

$screenMap = [
    'request'      => ['id' => 1, 'name' => 'Request'],
    'verification' => ['id' => 2, 'name' => 'Verification'],
    'approval'     => ['id' => 3, 'name' => 'Approval'],
    'loan_issue'   => ['id' => 4, 'name' => 'Loan Issue'],
    'collection'   => ['id' => 5, 'name' => 'Collection'],
    'closed'       => ['id' => 6, 'name' => 'Closed'],
];

$result = [];

foreach ($screenMap as $col => $meta) {
    if ((int)$row[$col] === 0) { // 0 = allowed
        $result[] = $meta;
    }
}

echo json_encode($result);
