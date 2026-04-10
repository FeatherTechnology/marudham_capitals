<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ErrorCode' => '405',
        'ErrorDescription' => 'Method Not Allowed'
    ]);
    exit;
}

/* ================= READ INPUT ================= */
$raw = file_get_contents('php://input');
$req = json_decode($raw, true);

if (!is_array($req)) {
    http_response_code(400);
    echo json_encode([
        'ErrorCode' => '400',
        'ErrorDescription' => 'Invalid JSON'
    ]);
    exit;
}

$quality   = isset($req['Quality']) ? (int)$req['Quality'] : 60;
$timeout   = isset($req['TimeOut']) ? (int)$req['TimeOut'] : 10;
$gallery   = $req['GalleryTemplate'] ?? '';
$tmpFormat = $req['TemplateFormat'] ?? 'ANSI';

$base = 'http://localhost:8030/morfinauth/';

/* ================= INIT DEVICE ================= */
$chInit = curl_init($base . 'initdevice');
curl_setopt($chInit, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chInit, CURLOPT_POST, true);
curl_setopt($chInit, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($chInit, CURLOPT_POSTFIELDS, json_encode([
    "ConnectedDvc" => "",
    "ClientKey" => ""
]));
curl_setopt($chInit, CURLOPT_TIMEOUT, 10);

curl_exec($chInit);
curl_close($chInit);

/* small delay (important for device ready) */
usleep(300000); // 0.3 sec

/* ================= MATCH API ================= */
$payload = [
    'Quality'         => $quality,
    'TimeOut'         => $timeout,
    'GalleryTemplate' => $gallery,
    'TemplateFormat'  => $tmpFormat   // ✅ FIXED
];

$ch = curl_init($base . 'match');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$errno    = curl_errno($ch);
$error    = curl_error($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

/* ================= ERROR HANDLING ================= */
if ($errno) {
    http_response_code(502);
    echo json_encode([
        'ErrorCode' => '502',
        'ErrorDescription' => 'Connection Error',
        'Details' => $error
    ]);
    exit;
}

if ($status < 200 || $status >= 300) {
    http_response_code(502);
    echo json_encode([
        'ErrorCode' => (string)$status,
        'ErrorDescription' => 'MorFinAuth API Error',
        'Raw' => $response
    ]);
    exit;
}

/* ================= SUCCESS ================= */
echo $response;