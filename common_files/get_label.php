<?php
session_start();
require '../ajaxconfig.php';

$user_id = $_SESSION['userid'];

$screen = $_POST['screen'] ?? '';

switch ($screen) {
    case 'noc':
        $accessColumn = 'noc_mapping_access';
        break;

    case 'request':
        $accessColumn = 'req_mapping_access';
        break;

    default:
        echo json_encode([
            'label' => 'Sector'
        ]);
        exit;
}

$stmt = $connect->prepare("
    SELECT $accessColumn AS mapping_access
    FROM user
    WHERE user_id = ?
");
$stmt->execute([$user_id]);

$access = (int)$stmt->fetchColumn();

switch ($access) {
    case 2:
        $label = "Region";
        break;

    case 3:
        $label = "Zone";
        break;

    default:
        $label = "Sector";
        break;
}

echo json_encode([
    'label' => $label
]);