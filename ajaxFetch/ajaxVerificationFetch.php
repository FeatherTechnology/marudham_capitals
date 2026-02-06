<?php
@session_start();
include('..\ajaxconfig.php');
include('..\moneyFormatIndia.php');
include('..\user_based_sub_area_Ids.php');

$userid = $_SESSION['userid'] ?? 0;
$sub_area_list = getUserSubAreaList($connect, 'verification');

if ($userid) {
    $stmt = $connect->prepare("SELECT role FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $login_user_type = $stmt->fetchColumn();
    $stmt->closeCursor();
}

if ($userid != 1) {
    $stmt = $connect->prepare("SELECT ver_loan_cat FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);
    $ver_loan_cat = $rowuser['ver_loan_cat'] ?? 0;
}

$stage_arr = [
    0   => 'Request',
    1   => 'Verification',
    10  => 'Verification',
    11  => 'Verification',
    12  => 'Verification',
    2   => 'Approval',
    3   => 'Acknowledgement',
    13  => 'Loan Issue',
];

/* ---------------- DATATABLE COLUMN MAP ---------------- */
$column = [
    'v.req_id',
    'v.dor',
    'cr.autogen_cus_id',
    'v.cus_id',
    'v.cus_name',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'v.mobile1',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'v.sub_category',
    'v.loan_amt',
    'v.user_type',
    'v.user_name',
    'v.agent_id',
    'v.responsible',
    'v.cus_data',
    'v.cus_data',
    'v.cus_status',
    'v.req_id'
];

/* ---------------- BASE QUERY ---------------- */
$query = "SELECT DISTINCT
    v.dor, 
    v.cus_name, 
    v.mobile1, 
    v.sub_category, 
    v.loan_amt, 
    v.user_type, 
    v.responsible, 
    v.cus_data, 
    v.cus_id, 
    v.req_id, 
    v.user_name, 
    v.cus_status, 
    v.agent_id,  
    cr.autogen_cus_id,  
    a.area_name,  
    sa.sub_area_name,  
    agm.group_name,  
    bc.branch_name,  
    alm.line_name, 
    lcc.loan_category_creation_name,
    ac.ag_name AS agent_name

    FROM in_verification v
    LEFT JOIN agent_creation ac ON ac.ag_id = v.agent_id
    JOIN customer_register cr ON v.cus_id = cr.cus_id
    JOIN area_list_creation a ON v.area = a.area_id
    JOIN sub_area_list_creation sa ON v.sub_area = sa.sub_area_id
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sa.sub_area_id
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = v.loan_category
    WHERE v.status = 0 and (v.cus_status NOT IN(4, 5, 6, 7, 8, 9) and v.cus_status < 14) "; //  < 14 means issued

/* user-level restriction */
if (!($userid == 1)) {
    $query .= " AND v.sub_area IN ($sub_area_list) AND v.loan_category IN ($ver_loan_cat)"; //show only moved to verification list and cancelled at verification
}

/* ---------------- SEARCH ---------------- */
if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $query .= " AND (
        v.dor LIKE '%$search%' OR
        v.cus_id LIKE '%$search%' OR
        cr.autogen_cus_id LIKE '%$search%' OR
        v.cus_name LIKE '%$search%' OR
        bc.branch_name LIKE '%$search%' OR
        agm.group_name LIKE '%$search%' OR
        alm.line_name LIKE '%$search%' OR
        v.mobile1 LIKE '%$search%' OR
        ac.ag_name LIKE '%$search%' OR
        a.area_name LIKE '%$search%' OR
        sa.sub_area_name LIKE '%$search%' OR
        lcc.loan_category_creation_name LIKE '%$search%' OR
        v.sub_category LIKE '%$search%' OR
        v.loan_amt LIKE '%$search%' OR
        v.user_type LIKE '%$search%' OR
        v.responsible LIKE '%$search%' OR
        v.cus_data LIKE '%$search%'
    )";
}

/* ---------------- ORDER ---------------- */
if (isset($_POST['order'])) {
    $col    = $column[$_POST['order'][0]['column']];
    $dir    = $_POST['order'][0]['dir'];
    $query .= " ORDER BY $col $dir ";
}

/* ---------------- PAGINATION ---------------- */
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
}

/* ---------------- EXECUTE MAIN QUERY ---------------- */
$stmt = $connect->prepare($query . $limit);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

/* ---------------- COUNT FILTERED ---------------- */
$stmt = $connect->prepare($query);
$stmt->execute();
$recordsFiltered = $stmt->rowCount();
$stmt->closeCursor();

/* ---------------- COUNT TOTAL ---------------- */
$stmt = $connect->prepare("SELECT COUNT(*) FROM in_verification");
$stmt->execute();
$recordsTotal = $stmt->fetchColumn();
$stmt->closeCursor();

$cusIds = array_unique(array_column($result, 'cus_id'));

$issueDataMap = [];

if (!empty($cusIds)) {

    $cusIdList = implode(',', array_map('intval', $cusIds));

    $issueSql = "SELECT ii.cus_id, ii.cus_status, cs.created_date AS last_created_date
        FROM in_issue ii
        LEFT JOIN closed_status cs ON cs.req_id = ii.req_id 
        WHERE ii.cus_id IN ($cusIdList) AND ii.cus_status >= 14";

    $stmt = $connect->query($issueSql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $issueDataMap[$row['cus_id']][] = $row;
    }
}

/* ---------------- DATA FORMAT ---------------- */
$data = [];
$sno = intval($_POST['start']) + 1;

foreach ($result as $row) {
    $sub = [];

    $sub[] = $sno++;
    $sub[] = date('d-m-Y', strtotime($row['dor']));
    $sub[] = $row['cus_id'];
    $sub[] = $row['autogen_cus_id'];
    $sub[] = $row['cus_name'];
    $sub[] = $row["branch_name"];
    $sub[] = $row['group_name'];
    $sub[] = $row['line_name'];
    $sub[] = $row['mobile1'];
    $sub[] = $row['area_name'];
    $sub[] = $row['sub_area_name'];
    $sub[] = $row["loan_category_creation_name"];
    $sub[] = $row['sub_category'];
    $sub[] = moneyFormatIndia($row['loan_amt']);
    $sub[] = $row['user_type'];
    $sub[] = $row['user_name'];
    $sub[] = $row['agent_name'] ?? '';
    $ag_id = $row['agent_id'];
    $sub[] = ($row['responsible'] == '0') ? 'Yes' : (!empty($ag_id) && $row['responsible'] != '0' ? 'No' : '');
    $sub[] = $row['cus_data'];

    $cus_id = $row['cus_id'];
    $issueRows = $issueDataMap[$cus_id] ?? [];

    $existing_type = '';
    if (!empty($issueRows)) {
        foreach ($issueRows as $res) {

            // 1️⃣ Additional has highest priority
            if ($res['cus_status'] >= 14 && $res['cus_status'] < 20) {
                $existing_type = 'Additional';
                break; // stop checking further rows
            }

            // 2️⃣ Renewal / Re-Active logic (only if not Additional)
            if ($res['cus_status'] >= 20 && $existing_type != 'Additional') {

                $lastDate = $res['last_created_date'];

                if (!empty($lastDate)) {
                    // End of the month of last created_date
                    $monthEnd = date('Y-m-t', strtotime($lastDate));

                    // First day of next month
                    $nextMonthStart = date('Y-m-d', strtotime($monthEnd . ' +1 day'));

                    // Add 3 months to calculate reactive date
                    $reactiveDate = date('Y-m-d', strtotime($nextMonthStart . ' +3 months'));

                    $today = date('Y-m-d');

                    // Decide Renewal or Re-Active
                    if ($today < $reactiveDate) {
                        $existing_type = 'Renewal';
                    } else {
                        $existing_type = 'Re-active';
                    }
                }
            }
        }
    } else {
        if ($row['cus_data'] == 'Existing') {
            $existing_type = 'Existing-New';
        }
    }

    $sub[] = $existing_type;
    $id = $row['req_id'];

    $cus_status = $row['cus_status'];
    if ($cus_status == '1' or $cus_status == '10' or $cus_status == '11') {
        $sub[] = "In Verification";
    } elseif ($cus_status == '12') {
        $sub[] = "<button class='btn btn-outline-secondary move_approval' value='$id' data-cusid='" . $cus_id . "' ><span class = 'icon-arrow_forward'></span></button>";
    } else
    if ($cus_status == '2') {
        $sub[] = 'In Approval';
    } else
    if ($cus_status == '3') {
        $sub[] = 'In Acknowledgement';
    } else
    if ($cus_status == '13') {
        $sub[] = 'In Issue';
    } else
    if ($cus_status == '4') {
        $sub[] = 'Cancel - Request';
    } else
    if ($cus_status == '5') {
        $sub[] = 'Cancel - Verification';
    } else
    if ($cus_status == '6') {
        $sub[] = 'Cancel - Approval';
    } else
    if ($cus_status == '7') {
        $sub[] = 'Cancel - Acknowledgement';
    } else
    if ($cus_status == '14') {
        $sub[] = 'Issued';
    }

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    if ($cus_status == '1' or $cus_status == '10' or $cus_status == '11' or $cus_status == '12') {
        $action .= "<a href='verification&upd=$id&pge=1' class='customer_profile' value='$id' >Edit Verification</a>
        <a href='#' data-reqid = '$id' class='cancelverification'>Cancel Verification</a>
        <a href='#' data-reqid = '$id' class='revokeverification'>Revoke Verification</a>";
        $action .= "<a class=' loan-follow-edit' data-cusid='" . $cus_id . "' data-stage='" . $stage_arr[$cus_status] . "' data-toggle='modal' data-target='#addLoanFollow'     value='Follow'><span>Followup </span></a>";
        $action .= "<a class='loan-follow-chart' data-cusid='"  . $cus_id . "' data-toggle='modal' data-target='#loanFollowChartModal'><span> Followup Chart</span></a>";
    } elseif ($cus_status == '5') {
        $action .= "<a href='verification&del=$id'class='removeverification'>Remove Verification</a>";
    }

    if ($login_user_type != 2 or $userid == 1) {
        $action .= "<a href='' data-value ='" . $cus_id . "' data-value1 = '$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'>Customer Status</a>";
        // $action .= "<a href='' data-value ='".$cus_id."' data-value1 = '$id' class='loan-summary' data-toggle='modal' data-target='.loansummary'>Loan Summary</a>";
    }

    $action  .= "<a data-reqid='$id' class='request-info' >Request Info</a>";
    $action  .= "</div></div>";
    $sub[]    = $action;
    $data[]   = $sub;
}

/* ---------------- RESPONSE ---------------- */
echo json_encode([
    "draw"              => intval($_POST['draw']),
    "recordsTotal"      => $recordsTotal,
    "recordsFiltered"   => $recordsFiltered,
    "data"              => $data
]);

