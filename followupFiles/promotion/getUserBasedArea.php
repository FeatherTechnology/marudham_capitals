<?php
include('../../ajaxconfig.php');
@session_start();

$user_id = $_SESSION['userid'] ?? 0;
$area_id = $_POST['area_id'] ?? '';

$detailrecords = [];

/* ========================================================= CASE 1: Area not selected → Load areas based on user access ========================================================= */
if ($user_id && empty(trim($area_id))) {

    // Fetch user access details
    $stmt = $connect->prepare("
        SELECT promotion_activity_mapping_access, group_id, line_id, due_followup_lines
        FROM user
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        // Decide table & column based on access type
        if ($user['promotion_activity_mapping_access'] == 1) {
            $ids   = array_filter(explode(',', $user['group_id']));
            $table = 'area_group_mapping_area';
            $col   = 'group_map_id';

        } elseif ($user['promotion_activity_mapping_access'] == 2) {
            $ids   = array_filter(explode(',', $user['line_id']));
            $table = 'area_line_mapping_area';
            $col   = 'line_map_id';

        } elseif ($user['promotion_activity_mapping_access'] == 3) {
            $ids   = array_filter(explode(',', $user['due_followup_lines']));
            $table = 'area_duefollowup_mapping_area';
            $col   = 'duefollowup_map_id';
        }

        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // ✅ Single optimized query
            $sql = " SELECT DISTINCT a.area_id, a.area_name FROM $table m JOIN area_list_creation a ON a.area_id = m.area_id WHERE m.$col IN ($placeholders) ORDER BY a.area_name ASC";
            $stmt = $connect->prepare($sql);
            $stmt->execute($ids);

            $detailrecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

/* ========================================================= CASE 2: Area selected → Load sub-areas ========================================================= */
else {

    if (!empty($area_id)) {

        $sql = "
            SELECT sub_area_id, sub_area_name
            FROM sub_area_list_creation
            WHERE area_id_ref IN ($area_id)
              AND status = 0
            ORDER BY sub_area_name ASC
        ";

        $stmt = $connect->query($sql);
        $detailrecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

echo json_encode($detailrecords);
$connect = null;
?>
