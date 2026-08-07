<?php
require '../ajaxconfig.php';
session_start();

$user_id = $_SESSION['userid'];

$module = $_POST['module'] ?? '';
$branches = $_POST['branch'] ?? [];

if (!is_array($branches)) {
    $branches = [];
}

switch ($module) {

    case 'request':
        $accessColumn = 'req_mapping_access';
        break;

    case 'verification':
        $accessColumn = 'verification';
        break;

    case 'common':
        $accessColumn = '1';
        break;

    case 'closed':
        $accessColumn = '2';
        break;

    case 'noc':
        $accessColumn = 'noc_mapping_access';
        break;

    default:
        echo json_encode([]);
        exit;
}

$stmt = $connect->prepare("
SELECT
    $accessColumn AS mapping_access,
    group_id,
    ver_group_id,
    line_id,
    due_followup_lines
FROM user
WHERE user_id = ?
");
$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([]);
    exit;
}

$mappingAccess = (int)$user['mapping_access'];

/* Common modules */
if ($module == 'common' || $module == 'verification') {
    $mappingAccess = 1;
}

if ($module == 'closed') {
    $mappingAccess = 2;
}

$data = [];

switch ($mappingAccess) {

    /* ==========================
       Sector
    ===========================*/
    case 1:

        if ($module == 'verification') {
            $groupIds = !empty($user['ver_group_id'])
                ? $user['ver_group_id']
                : $user['group_id'];
        } else {
            $groupIds = $user['group_id'];
        }

        $sql = "
            SELECT
                agm.map_id AS id,
                agm.group_name AS name
            FROM area_group_mapping agm
            WHERE FIND_IN_SET(agm.map_id, ?)
            AND agm.status = 0
        ";

        $params = [$groupIds];

        if (!empty($branches)) {

            $placeholders = implode(',', array_fill(0, count($branches), '?'));

            $sql .= " AND agm.branch_id IN ($placeholders)";

            $params = array_merge($params, $branches);
        }

        $sql .= " ORDER BY agm.group_name";

        $stmt = $connect->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        break;

    /* ==========================
       Region
    ===========================*/
    case 2:

        $sql = "
            SELECT
                map_id AS id,
                line_name AS name
            FROM area_line_mapping
            WHERE FIND_IN_SET(map_id, ?)
            AND status = 0
        ";

        $params = [$user['line_id']];

        if (!empty($branches)) {

            $placeholders = implode(',', array_fill(0, count($branches), '?'));

            $sql .= " AND branch_id IN ($placeholders)";

            $params = array_merge($params, $branches);
        }

        $sql .= " ORDER BY line_name";

        $stmt = $connect->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        break;

    /* ==========================
       Zone
    ===========================*/
    case 3:

        $sql = "
            SELECT
                map_id AS id,
                duefollowup_name AS name
            FROM area_duefollowup_mapping
            WHERE FIND_IN_SET(map_id, ?)
            AND status = 0
        ";

        $params = [$user['due_followup_lines']];

        if (!empty($branches)) {

            $placeholders = implode(',', array_fill(0, count($branches), '?'));

            $sql .= " AND branch_id IN ($placeholders)";

            $params = array_merge($params, $branches);
        }

        $sql .= " ORDER BY duefollowup_name";

        $stmt = $connect->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        break;

    default:
        $data = [];
        break;
}

echo json_encode($data);

$connect = null;
