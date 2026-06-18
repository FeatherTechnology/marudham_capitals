<?php
include('../ajaxconfig.php');

header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
$req = json_decode($raw, true);

$finger = isset($req['template']) ? trim($req['template']) : '';

if ($finger === '') {
    echo json_encode(['matched' => false, 'reason' => 'empty_template']);
    exit;
}

/* ================= GET ALL STORED TEMPLATES ================= */
$sql = "SELECT adhar_num, name, ansi_template FROM fingerprints WHERE ansi_template IS NOT NULL";
$stmt = $connect->query($sql);

$base = 'http://localhost:8030/morfinauth/';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $storedTemplate = $row['ansi_template'];

    /* ================= CALL MORPHO MATCH ================= */
    $payload = [
        "Quality" => 60,
        "TimeOut" => 10,
        "GalleryTemplate" => $storedTemplate,
        "TmpFormat" => 2
    ];

    $ch = curl_init($base . 'match');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($response, true);

    /* ================= CHECK MATCH ================= */
    if (isset($res['Status']) && $res['Status'] == true) {
        echo json_encode([
            'matched' => true,
            'cus_id' => $row['adhar_num'],
            'cus_name' => $row['name']
        ]);
        exit;
    }
}

/* ================= NO MATCH ================= */
echo json_encode(['matched' => false]);