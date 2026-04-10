<?php
// Same-origin proxy for MorFinAuth verify endpoint.

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ErrorCode' => '405', 'ErrorDescription' => 'Method Not Allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$req = json_decode($raw, true);
if (!is_array($req)) {
    http_response_code(400);
    echo json_encode(['ErrorCode' => '400', 'ErrorDescription' => 'Invalid JSON']);
    exit;
}

$prob = $req['ProbTemplate'] ?? '';
$gallery = $req['GalleryTemplate'] ?? '';
$tmpFormat = $req['TmpFormat'] ?? 'ANSI';

$base = 'http://localhost:8030/morfinauth/';

// Try POST with JSON first, then fall back to GET with query params if HTTP 405/404.
function morfin_verify_post(string $base, array $payload): array {
    $ch = curl_init($base . 'verify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['errno' => $errno, 'error' => $error, 'status' => $status, 'body' => $body];
}

function morfin_verify_get(string $base, string $prob, string $gallery, string $tmpFormat): array {
    $query = http_build_query([
        'ProbTemplate'    => $prob,
        'GalleryTemplate' => $gallery,
        'TmpFormat'       => $tmpFormat,
        'BioType'         => $tmpFormat,
    ]);
    $url = $base . 'verify?' . $query;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['errno' => $errno, 'error' => $error, 'status' => $status, 'body' => $body, 'url' => $url];
}

$payload = [
    'ProbTemplate'    => $prob,
    'GalleryTemplate' => $gallery,
    // Support both styles some SDKs use
    'TmpFormat'       => $tmpFormat,
    'BioType'         => $tmpFormat,
];

$res = morfin_verify_post($base, $payload);

// If method not allowed or not found, fall back to GET with query params.
if ($res['errno'] === 0 && ($res['status'] === 405 || $res['status'] === 404)) {
    $res = morfin_verify_get($base, $prob, $gallery, $tmpFormat);
}

if ($res['errno'] !== 0) {
    http_response_code(502);
    echo json_encode([
        'ErrorCode' => '502',
        'ErrorDescription' => 'MorFinAuth verify transport error',
        'Details' => $res['error']
    ]);
    exit;
}

if ($res['status'] < 200 || $res['status'] >= 300) {
    http_response_code(502);
    echo json_encode([
        'ErrorCode' => (string)$res['status'],
        'ErrorDescription' => 'MorFinAuth verify HTTP error',
        'Raw' => $res['body'] ?? '',
        'Url' => $res['url'] ?? ($base . 'verify')
    ]);
    exit;
}

// Pass through device response directly
echo $res['body'];

