<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'] ?? '';
$branch = $_POST['branch'] ?? [];
$sector = $_POST['sector'] ?? [];

// Make sure branch is an array
if (!is_array($branch)) {
    $branch = [$branch];
}
// Make sure sector is an array
if (!is_array($sector)) {
    $sector = [$sector];
}
// Remove empty values
$branch = array_values(
    array_filter($branch, function ($value) {
        return $value !== '' && $value !== null;
    })
);

$sector = array_values(
    array_filter($sector, function ($value) {
        return $value !== '' && $value !== null;
    })
);

// MONTH VALIDATION
if (empty($from_date)) {
    echo json_encode([ 'data' => []]);
    exit;
}

// SELECTED MONTH
$month_start = $from_date . '-01';
$month_end = date( 'Y-m-d',strtotime($month_start . ' +1 month'));

// PARAMETERS
$params = [
    ':month_start' => $month_start,
    ':month_end'   => $month_end
];


// BRANCH CONDITION
$branchCondition = '';
if (!empty($branch)) {
    $branchPlaceholders = [];
    foreach ($branch as $key => $branchId) {
        $placeholder = ':branch_' . $key;
        $branchPlaceholders[] = $placeholder;
        $params[$placeholder] = $branchId;
    }
    $branchCondition = "AND first_reactive.branch_id IN ( " . implode(',', $branchPlaceholders) . ") ";
}

// SECTOR CONDITION

$sectorCondition = '';
if (!empty($sector)) {
    $sectorPlaceholders = [];
    foreach ($sector as $key => $sectorId) {
        $placeholder = ':sector_' . $key;
        $sectorPlaceholders[] = $placeholder;
        $params[$placeholder] = $sectorId;
    }
    $sectorCondition = "AND first_reactive.sector_id IN (" . implode(',', $sectorPlaceholders) . ")";
}

// MAIN QUERY
$sql = "SELECT 
    first_reactive.cus_id, 
    first_reactive.autogen_cus_id, 
    first_reactive.aadhaar_number, 
    first_reactive.customer_name, 
    first_reactive.branch_id, 
    first_reactive.branch_name, 
    first_reactive.sector_id, 
    first_reactive.sector_name, 
    first_reactive.area_name, 
    first_reactive.sub_area_name, 
    first_reactive.mobile_number, 
    first_reactive.closing_date, 
    first_reactive.reactive_date
FROM
(
    SELECT 
        reactive_data.*,
        ROW_NUMBER() OVER (
    PARTITION BY reactive_data.cus_id
    ORDER BY 
        reactive_data.closing_date DESC,
        reactive_data.previous_req_id DESC
) AS reactive_rank
    FROM
    (
        SELECT 
            req.cus_id,
            req.req_id AS previous_req_id,
            cr.autogen_cus_id,
            req.cus_id AS aadhaar_number,
            cr.customer_name,
            cr.mobile1 AS mobile_number,
            al.area_name,
            sal.sub_area_name,
            bc.branch_id,
            bc.branch_name,
            agm.map_id AS sector_id,
            agm.group_name AS sector_name,
            cs.created_date AS closing_date,

            DATE_ADD(
                DATE_ADD(
                    LAST_DAY(cs.created_date),
                    INTERVAL 1 DAY
                ),
                INTERVAL 6 MONTH
            ) AS reactive_date

        FROM request_creation req
        INNER JOIN
        (
            SELECT
                req_id,
                cus_id,
                MAX(created_date) AS created_date
            FROM closed_status
            WHERE closed_sts = 1
            GROUP BY req_id, cus_id
        ) cs
            ON cs.req_id = req.req_id
            AND cs.cus_id = req.cus_id
        INNER JOIN customer_register cr ON cr.cus_id = req.cus_id
        LEFT JOIN area_list_creation al ON cr.area_confirm_area = al.area_id
        LEFT JOIN sub_area_list_creation sal ON cr.area_confirm_subarea = sal.sub_area_id
        INNER JOIN area_group_mapping_area agma ON agma.area_id = req.area
        INNER JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
        LEFT JOIN branch_creation bc ON bc.branch_id = agm.branch_id
    ) reactive_data
) first_reactive
WHERE first_reactive.reactive_rank = 1
  AND first_reactive.reactive_date >= :month_start
  AND first_reactive.reactive_date < :month_end  $branchCondition
    $sectorCondition
ORDER BY first_reactive.reactive_date ASC,first_reactive.cus_id ASC";
try {

    $stmt = $connect->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    $sno = 1;
    foreach ($rows as $row) {
        $data[] = [
            'sno' => $sno++,
            'cus_id' => $row['autogen_cus_id'],
            'aadhaar_number' => $row['aadhaar_number'],
            'customer_name' => $row['customer_name'],
            'branch' => $row['branch_name'],
            'sector' => $row['sector_name'],
            'area' => $row['area_name'],
            'sub_area' => $row['sub_area_name'],
            'mobile_number' =>$row['mobile_number'],
            'closing_date' => !empty($row['closing_date']) ? date( 'd-m-Y',strtotime($row['closing_date'])): '',
        ];
    }

    echo json_encode([
        'data' => $data,
        'total' => count($data)
    ]);
    exit;

} catch (PDOException $e) {

    echo json_encode([
        'data' => [],
        'error' => true,
        'message' => $e->getMessage()
    ]);

    exit;
}
?>