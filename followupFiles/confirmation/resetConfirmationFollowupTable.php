<?php
session_start();
include('../../ajaxconfig.php');

$draw = $_POST['draw'];
$start = $_POST['start'];
$searchValue = $_POST['search'];
$orderColumnIndex = $_POST['order'][0]['column'];
$orderColumn = $_POST['columns'][$orderColumnIndex]['data'];
$orderDir = $_POST['order'][0]['dir'];

$limit = '';
if ($_POST['length'] != -1) {
    $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$columns = [
    'rc.req_id',
    'rc.updated_date',
    'rc.cus_id',
    'rc.cus_name',
    'alc.area_name',
    'salc.sub_area_name',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'rc.mobile1',
    'rc.req_id',
    'rc.req_id',
    'rc.req_id'
];

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {
    $userQry = $connect->query("SELECT group_id FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
        $group_id = $rowuser['group_id'];
    
    $group_id = explode(',', $group_id);
    $sub_area_list = array();
    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT sub_area_id FROM area_group_mapping where map_id = $group ");
        $row_sub = $groupQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',', $sub_area_ids);
}

$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (rc.cus_id LIKE '%" . $searchValue . "%' 
                    OR rc.cus_name LIKE '%" . $searchValue . "%' 
                    OR alc.area_name LIKE '%" . $searchValue . "%'
                    OR salc.sub_area_name LIKE '%" . $searchValue . "%'
                    OR bc.branch_name LIKE '%" . $searchValue . "%'
                    OR agm.group_name LIKE '%" . $searchValue . "%'
                    OR alm.line_name LIKE '%" . $searchValue . "%'
                    OR rc.mobile1 LIKE '%" . $searchValue . "%')";
}

$orderQuery = " ORDER BY " . $columns[$orderColumnIndex] . " " . $orderDir;

$sql = "SELECT 
    rc.*,
    alc.area_name,
    salc.sub_area_name,
    lcc.loan_category_creation_name,
    ac.ag_name,
    bc.branch_name,
    agm.group_name,
    alm.line_name
FROM 
    request_creation rc
JOIN 
    acknowlegement_customer_profile acp ON rc.req_id = acp.req_id
LEFT JOIN 
    area_list_creation alc ON rc.area = alc.area_id
LEFT JOIN 
    sub_area_list_creation salc ON rc.sub_area = salc.sub_area_id
LEFT JOIN 
    loan_category_creation lcc ON rc.loan_category = lcc.loan_category_creation_id
LEFT JOIN 
    agent_creation ac ON rc.agent_id = ac.ag_id
LEFT JOIN 
    area_group_mapping agm ON FIND_IN_SET(rc.sub_area, agm.sub_area_id)
LEFT JOIN 
    branch_creation bc ON agm.branch_id = bc.branch_id
LEFT JOIN 
    area_line_mapping alm ON FIND_IN_SET(rc.sub_area, alm.sub_area_id)
WHERE 
    rc.cus_status >= 14 AND acp.area_confirm_subarea IN ($sub_area_list) AND NOT EXISTS (SELECT 1 FROM confirmation_followup cf WHERE cf.req_id = rc.req_id AND cf.remove_status = 1) $searchQuery $orderQuery ";

// Count query for filtered rows
$num_qry = $connect->query($sql);
$number_filter_row = $num_qry->rowCount();

$result = $connect->query($sql . $limit);
$data = [];
$sno = $start + 1;
$status_arr = [1 => 'Completed', 2 => 'Unavailable', 3 => 'Reconfirmation'];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $req_id = $row['req_id'];
    $qry = $connect->query("SELECT remove_status, status FROM confirmation_followup WHERE req_id = '" . $req_id . "' ORDER BY created_date DESC limit 1");
    $rst = $qry->fetch()['remove_status'] ?? null;
    if ($qry -> rowCount() == 0 || $rst != 1) { // show below contents only if confirmation of the request id is not removed from table already

        $action = "<div class='dropdown'><button class='btn btn-outline-secondary' onclick='event.preventDefault();'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'>
                        <a class='conf-chart' data-cusid='" . $row['cus_id'] . "' data-reqid='" . $row['req_id'] . "' data-toggle='modal' data-target='#confChartModal'><span>Confirmation Chart</span></a>
                        <a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a>
                        <a class='cust-profile' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Profile</span></a>
                        <a class='documentation' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Documentation</span></a>
                        <a class='loan-calc' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan Calculation</span></a>
                        <a class='loan-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan History</span></a>
                        <a class='doc-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Document History</span></a>
                    </div></div>";

        $actionEdit = "<div class='dropdown'><button class='btn btn-outline-secondary' onclick='event.preventDefault();'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'>";

        $status = $rst['status'] ?? null;
        if ($status == '1') { // 1 means completed
            $actionEdit .= "<a class='conf-remove' data-cusid='" . $row['cus_id'] . "' data-reqid='" . $row['req_id'] . "' ><span>Remove</span></a>";
        } else {
            $actionEdit .= "<a class='conf-edit' data-cusid='" . $row['cus_id'] . "' data-cusname='" . $row['cus_name'] . "' data-reqid='" . $row['req_id'] . "' data-toggle='modal' data-target='#addConfimation'><span>Confirmation</span></a>";
        }

        $actionEdit .= "</div></div>";

        $data[] = [
            $sno++,
            date('d-m-Y', strtotime($row['updated_date'])),
            $row['cus_id'],
            $row['cus_name'],
            $row['area_name'],
            $row['sub_area_name'],
            $row['branch_name'],
            $row['group_name'],
            $row['line_name'],
            $row['mobile1'],
            $action,
            $actionEdit,
            $status_arr[$status] ?? 'In Confirmation'
        ];
    }
}

$totalRecordsQry = $connect->query("SELECT COUNT(*) AS total FROM request_creation rc WHERE rc.cus_status >= 14 ");
$totalRecords = $totalRecordsQry->fetch()['total'];

$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($number_filter_row),
    "data" => $data
];

echo json_encode($response);

// Close the database connection
$connect = null;