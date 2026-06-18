<?php
// Same-origin proxy for local MorFinAuth service (MFS500).
// Browser cannot call MorFinAuth directly due to CORS/preflight behavior.

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ErrorCode' => '405', 'ErrorDescription' => 'Method Not Allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$req = json_decode($raw, true);
if (!is_array($req)) $req = [];

$quality = isset($req['Quality']) ? (int)$req['Quality'] : 60;
$timeout = isset($req['TimeOut']) ? (int)$req['TimeOut'] : 10;
$templateFormat = isset($req['TmpFormat']) ? (string)$req['TmpFormat'] : 2;
$imageFormat = isset($req['ImageFormat']) ? (string)$req['ImageFormat'] : 'BMP';

$base = 'http://localhost:8030/morfinauth/';

function postJson($url, array $payload): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
    // MorFinAuth is picky: it often accepts `{}` but may reject `[]` / other bodies with 405.
    $json = empty($payload) ? '{}' : json_encode($payload);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno) {
        return ['ok' => false, 'status' => 0, 'err' => $error];
    }
    return ['ok' => ($status >= 200 && $status < 300), 'status' => $status, 'body' => $body];
}

// Some MorFinAuth builds require initdevice after each service start (else capture returns -2025).
// Run initdevice best-effort before capture; ignore failures if already initialized.
$init = postJson($base . 'initdevice', ['ConnectedDvc' => '', 'ClientKey' => '']);

// 1) Capture
$capturePayload = [
    'Quality' => $quality,
    'TimeOut' => $timeout,
    'TmpFormat' => $templateFormat,
    'ImageFormat' => $imageFormat
];

$cap = postJson($base . 'capture', $capturePayload);
if (!$cap['ok']) {
    http_response_code(502);
    echo json_encode(['ErrorCode' => '502', 'ErrorDescription' => 'MorFinAuth capture failed', 'Details' => $cap]);
    exit;
}

$capData = json_decode($cap['body'], true);
if (!is_array($capData)) {
    http_response_code(502);
    echo json_encode(['ErrorCode' => '502', 'ErrorDescription' => 'Invalid capture response', 'Raw' => $cap['body']]);
    exit;
}

// If capture itself failed, return it directly (keeps existing client behavior).
if ((string)($capData['ErrorCode'] ?? '') !== '0') {
    echo json_encode($capData);
    exit;
}

// 2) Get template (many builds require this after capture)
$tmpl = postJson($base . 'gettemplate', []); // sends `{}` due to postJson() behavior
if ($tmpl['ok']) {
    $tmplData = json_decode($tmpl['body'], true);
    if (is_array($tmplData) && (string)($tmplData['ErrorCode'] ?? '') === '0') {
        // Normalize into AnsiTemplate expected by JS
        $candidate =
            ($tmplData['AnsiTemplate'] ?? null) ??
            ($tmplData['Template'] ?? null) ??
            ($tmplData['ANSITemplate'] ?? null) ??
            ($tmplData['ImgData'] ?? null) ??  // observed in your __gettemplate payload
            (($tmplData['Biometrics'][0]['BiometricData'] ?? null) ?? null);
        if ($candidate) {
            $capData['AnsiTemplate'] = $candidate;
        }
    }
    // If still missing, return gettemplate response for debugging (client ignores unknown fields)
    if (!isset($capData['AnsiTemplate'])) {
        $capData['__gettemplate'] = $tmplData;
    }
} else {
    // Return transport-level gettemplate error for debugging
    $capData['__gettemplate'] = $tmpl;
}

echo json_encode($capData);
