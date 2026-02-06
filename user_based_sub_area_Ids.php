<?php

function getUserSubAreaList(PDO $connect, string $module): string
{
    if (isset($_SESSION["userid"])) {
        $userid = $_SESSION["userid"];
    }

    // Admin or invalid module → no restriction
    if ($userid === 1 || $module === '') {
        return '';
    }

    /* ======================= ACCESS CONFIG PRESETS ======================= */

    $GROUP_SUBAREA = [
        'user_col' => 'group_id',
        'table'    => 'area_group_mapping_sub_area',
        'map_col'  => 'group_map_id',
        'id_col'   => 'sub_area_id',
    ];

    $LINE_SUBAREA = [
        'user_col' => 'line_id',
        'table'    => 'area_line_mapping_sub_area',
        'map_col'  => 'line_map_id',
        'id_col'   => 'sub_area_id',
    ];

    $DUEFOLLOWUP_AREA = [
        'user_col' => 'due_followup_lines',
        'table'    => 'area_duefollowup_mapping_area',
        'map_col'  => 'duefollowup_map_id',
        'id_col'   => 'area_id',
    ];

    /* ======================= MODULE → ACCESS MAP ======================= */

    $config = [
        // Group → Sub Area
        'verification'    => $GROUP_SUBAREA,
        'approval'        => $GROUP_SUBAREA,
        'acknowledgement' => $GROUP_SUBAREA,
        'loanissue'       => $GROUP_SUBAREA,
        'update'          => $GROUP_SUBAREA,
        'accloanissue'    => $GROUP_SUBAREA,

        // Line → Sub Area
        'collection'      => $LINE_SUBAREA,
        'closed'          => $LINE_SUBAREA,

        // Due Follow-up → Area
        'confirmFollowUp' => $DUEFOLLOWUP_AREA,
    ];

    if (!isset($config[$module])) {
        return '';
    }

    $cfg = $config[$module];

    /* ======================= FETCH USER MAPPING IDS ======================= */

    $stmt = $connect->prepare("SELECT {$cfg['user_col']} FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);

    $ids = $stmt->fetchColumn();
    if (!$ids) {
        return '';
    }

    $idArray = array_filter(array_map('intval', explode(',', $ids)));
    if (empty($idArray)) {
        return '';
    }

    /* ======================= FETCH ACCESSIBLE AREAS ======================= */

    $placeholders = implode(',', array_fill(0, count($idArray), '?'));

    $stmt = $connect->prepare("SELECT DISTINCT {$cfg['id_col']} FROM {$cfg['table']} WHERE {$cfg['map_col']} IN ($placeholders)");
    $stmt->execute($idArray);

    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return empty($result) ? '' : implode(',', $result);
}
