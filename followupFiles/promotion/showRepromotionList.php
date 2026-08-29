<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$follow_up_sts = '';
$follow_up_date = '';

$Obj = new promotionListClass($connect);
$sub_area_list = $Obj->sub_area_list;
$accessType = $Obj->accessType;
$actionAccess = $Obj->actionAccess;

$status = [4 => 'Request', 5 => 'Verification', 6 => 'Approval', 7 => 'Acknowledgement', 8 => 'Request', 9 => 'Verification'];
$sub_status = [4 => 'Cancel', 5 => 'Cancel', 6 => 'Cancel', 7 => 'Cancel', 8 => 'Revoke', 9 => 'Revoke'];
$cusstatus = [21 => 'NOC Pending', 22 => 'NOC Completed', 23 => 'NOC Completed', 24 => 'NOC Handovered', 25 => 'Agent Handovered'];

$column = [
    'cp.cus_reg_id', 'cp.cus_id', 'cp.autogen_cus_id', 'cp.customer_name',
    'al.area_name', 'sl.sub_area_name', 'bc.branch_name', 'agm.group_name',
    'alm.line_name', 'cp.mobile1', 'cp.cus_reg_id', 'req.cus_status',
    'req.cus_data', 'req.updated_date', 'noc_cus_status', 'cp.cus_reg_id',
    'cp.cus_reg_id', 'np.status', 'np.follow_date', 'np.followup_type'
];

$areaColumn = ($accessType == 3) ? "cp.area" : "cp.sub_area";

$queryParams = [];

// NOTE: $sub_area_list must itself be a trusted, server-derived value (e.g. from
// promotionListClass based on session), never raw user input concatenated into SQL.
// If it originates from $_POST anywhere upstream, it needs to move to bound params too.
$baseqry = "FROM request_creation req 
LEFT JOIN customer_register cp ON req.cus_id = cp.cus_id 
LEFT JOIN area_list_creation al ON al.area_id = CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_area ELSE cp.area END
LEFT JOIN sub_area_list_creation sl ON sl.sub_area_id = CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_subarea ELSE cp.sub_area END
LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sl.sub_area_id
LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sl.sub_area_id
LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
LEFT JOIN new_promotion np ON np.cus_id = req.cus_id 
    AND np.created_date = (SELECT MAX(np1.created_date) FROM new_promotion np1 WHERE np1.cus_id = req.cus_id)
WHERE req.cus_status BETWEEN 4 AND 9 AND req.return_sts = 0
AND CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_subarea ELSE $areaColumn END IN ($sub_area_list)
AND NOT EXISTS (
    SELECT 1 FROM request_creation rc
    WHERE rc.cus_id = req.cus_id
    AND rc.cus_status NOT BETWEEN 4 AND 9
    AND rc.cus_status < 20
) ";

if (!empty($_POST['followUpSts'])) {
    $follow_up_sts = $_POST['followUpSts'];
    if ($follow_up_sts == 'tofollow') {
        $baseqry .= "AND np.status IS NULL ";
    } else {
        $baseqry .= "AND TRIM(REPLACE(np.status,' ','')) = ? ";
        $queryParams[] = $follow_up_sts;
    }
}

if (!empty($_POST['dateType'])) {
    $date_type = $_POST['dateType']; // 1 = Closed date, 2 = Followup date
    $fromDate = $_POST['followUpFromDate'] ?? '';
    $toDate = $_POST['followUpToDate'] ?? '';
    if ($date_type == '1') {
        $baseqry .= "AND DATE(req.updated_date) BETWEEN ? AND ? ";
    } else {
        $baseqry .= "AND DATE(np.follow_date) BETWEEN ? AND ? ";
    }
    $queryParams[] = $fromDate;
    $queryParams[] = $toDate;
}

if (!empty($_POST['followupType'])) {
    $baseqry .= "AND np.followup_type = ? ";
    $queryParams[] = $_POST['followupType'];
}

$search = '';
if (!empty($_POST['search'])) {
    $search = " AND (cp.cus_id LIKE ? 
    OR cp.autogen_cus_id LIKE ? 
    OR cp.customer_name LIKE ? 
    OR al.area_name LIKE ? 
    OR sl.sub_area_name LIKE ? 
    OR bc.branch_name LIKE ? 
    OR agm.group_name LIKE ? 
    OR alm.line_name LIKE ? 
    OR cp.mobile1 LIKE ? 
    OR np.status LIKE ? ) ";
    $likeVal = '%' . $_POST['search'] . '%';
    for ($i = 0; $i < 10; $i++) {
        $queryParams[] = $likeVal;
    }
}

$orderColumnIndex = (int)($_POST['order']['0']['column'] ?? 0);
$orderDir = ($_POST['order']['0']['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
$orderCol = $column[$orderColumnIndex] ?? $column[0];
$order = " ORDER BY $orderCol $orderDir ";

$baseqry .= "$search GROUP BY req.cus_id $order ";

// ---------------------- SINGLE QUERY (count + data combined) ----------------------
$sql = "SELECT COUNT(*) OVER() AS total_filtered,
    req.req_id, req.cus_data,
    CASE 
        WHEN EXISTS (SELECT 1 FROM closed_status cs WHERE cs.cus_id = req.cus_id) THEN 1
        ELSE 0
    END AS has_closed_status,
    req.cus_id, cp.autogen_cus_id, cp.customer_name, al.area_name, sl.sub_area_name,
    bc.branch_name, agm.group_name, alm.line_name, cp.mobile1,
    req.cus_status AS consider_level, req.updated_date,
    np.status AS followup_sts, np.follow_date, np.followup_type,
    (SELECT rcs.cus_status FROM request_creation rcs 
     WHERE rcs.cus_id = req.cus_id AND rcs.cus_status BETWEEN 21 AND 25 
     ORDER BY rcs.updated_date DESC LIMIT 1) AS noc_cus_status
    $baseqry";

$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : -1;
if ($length != -1) {
    $sql .= " LIMIT $start, $length";
}

$statement = $connect->prepare($sql);
$statement->execute($queryParams);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$number_filter_row = !empty($result) ? $result[0]['total_filtered'] : 0;

$sno = 1;
$data = [];
// ... existing formatting loop, now reading from $result instead of $sql->fetchAll()
foreach ($result as $row) {
    $updateddate = (isset($row['updated_date'])) ? date('d-m-Y', strtotime($row['updated_date'])) : '';

    $charts = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a><a class='customer-sts' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Status</span></a></div></div>";

    //for intrest or not intrest choice to make
    $actions = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='noc-call' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>NOC Call</span></a><a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a><a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a><a class='un-available' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Unavailable</span></a>";

    if ($row['has_closed_status'] == 1 && $actionAccess == 1) {
        $actions .= "<a class='add_close'data-toggle='modal'data-target='#addClosedModal'data-id='" . $row['cus_id'] . "'> <span>Closed Status</span></a>";
        $actions .= "<a class='return_sts' data-id='" . $row['cus_id'] . "' data-req-id='" . $row['req_id'] . "'><span>Return Status</span></a>";
    }

    $actions .= "</div></div>";

    $followdate = (isset($row['follow_date'])) ? date('d-m-Y', strtotime($row['follow_date'])) : '';

    $followup_type = '';
    if ($row['followup_type'] == '1') { 
        $followup_type = 'Field';
    } else if ($row['followup_type'] == '2') {
        $followup_type = 'Telecalling';
    }

    $data[] = [
        $sno++,
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['customer_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['group_name'],
        $row['line_name'],
        $row['mobile1'],
        $status[$row['consider_level']],
        $sub_status[$row['consider_level']],
        $row['cus_data'],
        $updateddate,
        $cusstatus[$row['noc_cus_status']] ?? '',
        $charts,
        $actions,
        $row['followup_sts'],
        $followdate,
        $followup_type
    ];
}


$output = array(
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
