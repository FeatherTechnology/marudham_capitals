<?php
require '../ajaxconfig.php';

$req_id = $_POST['req_id'];

$response = [
    'status' => 'ok',
    'message' => ''
];

/* Get Current Customer */
$stmt = $connect->prepare("
    SELECT cus_id
    FROM request_creation
    WHERE req_id = ?
");
$stmt->execute([$req_id]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$current) {
    echo json_encode($response);
    exit;
}

$cus_id = $current['cus_id'];

/*
Get previous loans of same customer
(current loan excluded)
*/
$stmt = $connect->prepare("
SELECT
    rc.req_id,
    rc.cus_status,
    n.noc_replace_status
FROM request_creation rc
LEFT JOIN noc n ON rc.req_id = n.req_id
WHERE rc.cus_id = ?
AND rc.req_id <> ?
AND rc.cus_status >= 14
ORDER BY rc.req_id DESC
");

$stmt->execute([$cus_id, $req_id]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    /*
    First previous completed loan
    */
    if ($row['noc_replace_status'] == 1) {

        $response = [
            'status' => 'warning',
            'message' => 'Previous Documents was replaced. Enable Replace Status'
        ];

        echo json_encode($response);
        exit;
    }

    /*
    If noc exists and replace not requested,
    no need to check older loans.
    */
    if ($row['noc_replace_status'] !== null) {
        break;
    }
}

echo json_encode($response);