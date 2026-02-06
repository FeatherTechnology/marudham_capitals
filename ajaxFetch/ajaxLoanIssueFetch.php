<?php
@session_start();
include('..\ajaxconfig.php');
include('..\moneyFormatIndia.php');
include('..\user_based_sub_area_Ids.php');

$userid = $_SESSION['userid'] ?? 0;
$sub_area_list = getUserSubAreaList($connect, 'loanissue');

if ($userid) {
    $stmt = $connect->prepare("SELECT role FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $login_user_type = $stmt->fetchColumn();
    $stmt->closeCursor();
}

/* ---------------- DATATABLE COLUMN MAP ---------------- */
$column = [
    'a.req_id',
    'a.dor',
    'a.cus_id',
    'cr.autogen_cus_id',
    'a.cus_name',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'b.sub_category',
    'b.loan_amt',
    'a.user_type',
    'a.user_name',
    'a.agent_id',
    'a.responsible',
    'a.cus_data',
    'a.cus_status',
    'a.req_id'
];

/* ---------------- BASE QUERY ---------------- */
$query = "SELECT DISTINCT
    a.dor, 
    a.cus_id, 
    cr.autogen_cus_id, 
    a.cus_name, 
    a.user_type, 
    a.user_name, 
    a.agent_id, 
    a.responsible, 
    a.cus_data, 
    a.req_id, 
    a.cus_status, 
    a.req_id, 
    b.sub_category, 
    b.loan_amt, 
    ac.area_name, 
    sa.sub_area_name, 
    agm.group_name, 
    bc.branch_name, 
    alm.line_name, 
    lcc.loan_category_creation_name, 
    a.issue_by

    FROM in_verification a 
    JOIN customer_register cr ON a.cus_id = cr.cus_id
    JOIN acknowlegement_loan_calculation b on a.req_id=b.req_id 
    JOIN area_list_creation ac ON a.area = ac.area_id
    JOIN sub_area_list_creation sa ON a.sub_area = sa.sub_area_id
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sa.sub_area_id
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = b.loan_category
    WHERE a.status = 0 and (a.cus_status = 13) and a.issue_by IN (1, 2) "; // Move To Issue

/* user-level restriction */
if (!($userid == 1)) {
    $query .= " AND a.sub_area IN ($sub_area_list)"; //show only Approved Verification in Acknowledgement. // 13 Move to Issue. // 14 Move To Collection.
}

/* ---------------- SEARCH ---------------- */
if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $query .= " AND (
        a.dor LIKE '%$search%' OR
        a.cus_id LIKE '%$search%' OR
        cr.autogen_cus_id LIKE '%$search%' OR
        a.cus_name LIKE '%$search%' OR
        bc.branch_name LIKE '%$search%' OR
        agm.group_name LIKE '%$search%' OR
        alm.line_name LIKE '%$search%' OR
        ac.area_name LIKE '%$search%' OR
        a.mobile1 LIKE '%$search%' OR
        sa.sub_area_name LIKE '%$search%' OR
        lcc.loan_category_creation_name LIKE '%$search%' OR
        a.sub_category LIKE '%$search%' OR
        a.loan_amt LIKE '%$search%' OR
        a.user_type LIKE '%$search%' OR
        a.responsible LIKE '%$search%' OR
        a.cus_data LIKE '%$search%'
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

/* ---------------- DATA FORMAT ---------------- */
$data = [];
$sno = intval($_POST['start']) + 1;

foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;
    $sub_array[] = date('d-m-Y', strtotime($row['dor']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["loan_category_creation_name"];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = moneyFormatIndia($row['loan_amt']);

    $req_id = $row['req_id'];

    $qry = $connect->query("SELECT u.role AS user_type, u.fullname AS user_name
    FROM verification_loan_calculation v
    LEFT JOIN user u ON u.user_id = v.insert_login_id
    WHERE v.req_id = $req_id");

    $row1 = $qry->fetch(PDO::FETCH_ASSOC);

    if (isset($row1['user_type'])) {
        if ($row1['user_type'] == '1') {
            $user_type = 'Director';
        } elseif ($row1['user_type'] == '2') {
            $user_type = 'Agent';
        } elseif ($row1['user_type'] == '3') {
            $user_type = 'Staff';
        }
    }

    $sub_array[] = $user_type ?? '';
    $sub_array[] = $row1['user_name'] ?? '';

    $ag_id = $row['agent_id'];
    if ($ag_id != '') {

        $qry = $connect->query("SELECT * FROM agent_creation where ag_id = $ag_id ");
        $row1 = $qry->fetch();
        $sub_array[] = $row1['ag_name'];
    } else {
        $sub_array[] = '';
    }

    if ($row['responsible'] == '0') {
        $sub_array[] = 'Yes';
    } else if (!empty($ag_id) && $row['responsible'] != '0') {
        $sub_array[] = 'No';
    } else {
        $sub_array[] = '';
    }

    $sub_array[] = $row['cus_data'];
    $id = $row['req_id'];
    $issue_by    = $row['issue_by'];

    $cus_status = $row['cus_status'];
    $loan_issued = $connect->query("SELECT balance_amount FROM `loan_issue` WHERE req_id='$id' order by id desc LIMIT 1 ");
    $loan_issued_db =  $loan_issued->fetch();

    if ($issue_by == 2) {
        // Always show In Accounts if issue_by = 2
        $sub_array[] = 'In Accounts';
    } else {
        if (empty($ag_id)) { // only check balance amount if request is not on agent
            if ($cus_status == '13') {
                if (isset($loan_issued_db['balance_amount']) && $loan_issued_db['balance_amount'] == '0') {
                    $sub_array[] = "<button class='btn btn-outline-secondary complete_issue' value='$id'><span class='icon-arrow_forward'></span></button>";
                } else {
                    $sub_array[] = 'In Issue';
                }
            } else if ($cus_status == '14') {
                $sub_array[] = 'Issued';
            }
        } else { // else directly show move button to collection
            if ($cus_status == '14') {
                $sub_array[] = 'Issued';
            } else {
                $sub_array[] = "<button class='btn btn-outline-secondary complete_issue' value='$id'><span class='icon-arrow_forward'></span></button>";
            }
        }
    }

    $id     = $row['req_id'];
    $cus_id = $row['cus_id'];

    $action = '';

    if ($issue_by == 1 || $issue_by == 2) { // Show dropdown for both cases
        $action = "<div class='dropdown'>
        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
        <div class='dropdown-content'>";

        if ($issue_by == 1) { // Only add options if issue_by = 1
            if ($cus_status == '13' and empty($ag_id)) {
                $action .= "<a href='loan_issue&upd=$id' class='customer_profile' value='$id' > Edit Loan Issue </a>";
            } else if ($cus_status == '14') {
                $action .= "<a href=''class='iss-remove' data-value='$id' > Remove </a>";
            }

            if ($login_user_type == 0 or $userid == 1) {
                $action .= "<a href='' data-value ='" . $cus_id . "' data-value1 = '$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'>Customer Status</a>";
            }
        }

        $action .= "</div></div>";
    }


    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

/* ---------------- RESPONSE ---------------- */
echo json_encode([
    "draw"              => intval($_POST['draw']),
    "recordsTotal"      => $recordsTotal,
    "recordsFiltered"   => $recordsFiltered,
    "data"              => $data
]);