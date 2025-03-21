<?php
session_start();
include('../../ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
    }
    $group_id = explode(',', $group_id);
    $sub_area_list = array();
    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT * FROM area_group_mapping where map_id = $group ");
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

$stage_arr = [
    0 => 'Request',
    1 => 'Verification',
    10 => 'Verification',
    11 => 'Verification',
    12 => 'Verification',
    2 => 'Approval',
    3 => 'Acknowledgement',
    13 => 'Loan Issue',
];

$search = "";
// Apply search filter if set
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $search = " AND ( rc.updated_date LIKE '%" . $search . "%' OR
                    rc.cus_id LIKE '%" . $search . "%' OR
                    rc.cus_name LIKE '%" . $search . "%' OR
                    alc.area_name LIKE '%" . $search . "%' OR
                    salc.sub_area_name LIKE '%" . $search . "%' OR
                    lcc.loan_category_creation_name LIKE '%" . $search . "%' OR
                    rc.sub_category LIKE '%" . $search . "%' OR
                    ac.ag_name LIKE '%" . $search . "%' OR
                    bc.branch_name LIKE '%" . $search . "%' OR
                    agm.group_name LIKE '%" . $search . "%' OR
                    alm.line_name LIKE '%" . $search . "%')";
}

//used two queries because some customers will not have submitted customer profile where true details will be given.
//fo those take details from request else use customer profile tables

$sql = "( SELECT rc.updated_date, rc.cus_id, rc.cus_name, alc.area_name, salc.sub_area_name, lcc.loan_category_creation_name, rc.sub_category, ac.ag_name, bc.branch_name, agm.group_name, alm.line_name, rc.req_id, rc.cus_status 
    FROM request_creation rc 
    LEFT JOIN area_list_creation alc ON rc.area = alc.area_id 
    LEFT JOIN sub_area_list_creation salc ON rc.sub_area = salc.sub_area_id 
    LEFT JOIN loan_category_creation lcc ON rc.loan_category = lcc.loan_category_creation_id 
    LEFT JOIN agent_creation ac ON rc.agent_id = ac.ag_id 
    LEFT JOIN area_group_mapping agm ON FIND_IN_SET(rc.sub_area, agm.sub_area_id) 
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
    LEFT JOIN area_line_mapping alm ON FIND_IN_SET(rc.sub_area, alm.sub_area_id) 
    WHERE rc.cus_status IN (0, 1, 10, 11) 
    AND ( rc.sub_area IN ($sub_area_list) OR 
    (SELECT area_confirm_subarea FROM customer_profile WHERE req_id = rc.req_id) IN ($sub_area_list) ) $search
)
UNION ALL
(
    SELECT rc.updated_date, cp.cus_id, cp.cus_name, alc.area_name, salc.sub_area_name, lcc.loan_category_creation_name, vlc.sub_category, ac.ag_name, bc.branch_name, agm.group_name, alm.line_name, rc.req_id, rc.cus_status 
    FROM request_creation rc 
    LEFT JOIN customer_profile cp ON cp.req_id = rc.req_id 
    LEFT JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id 
    LEFT JOIN sub_area_list_creation salc ON cp.area_confirm_subarea = salc.sub_area_id 
    LEFT JOIN verification_loan_calculation vlc ON cp.req_id = vlc.req_id 
    LEFT JOIN loan_category_creation lcc ON vlc.loan_category = lcc.loan_category_creation_id 
    LEFT JOIN agent_creation ac ON rc.agent_id = ac.ag_id 
    LEFT JOIN area_group_mapping agm ON FIND_IN_SET(cp.area_confirm_subarea, agm.sub_area_id) 
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
    LEFT JOIN area_line_mapping alm ON FIND_IN_SET(cp.area_confirm_subarea, alm.sub_area_id) 
    WHERE rc.cus_status > 1 
    AND rc.cus_status NOT IN (4, 5, 6, 7, 8, 9, 10, 11) 
    AND rc.cus_status < 14 
    AND cp.area_confirm_subarea IN ($sub_area_list) $search
)";


// Apply ordering if set
if (isset($_POST['order'])) {
    $query = ' ORDER BY ' . ($_POST['order']['0']['column'] + 1) . ' ' . $_POST['order']['0']['dir'] . ' ';
    $sql .= $query;
}

$limit = "";
// Apply pagination (LIMIT)
if ($_POST['length'] != -1) {
    $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($sql);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($sql . $limit);

$statement->execute();

$result = $statement->fetchAll();

$data   = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;
    $sub_array[] = date('d-m-Y', strtotime($row['updated_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_category_creation_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $stage_arr[$row['cus_status']];

    $action = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

    $action .= "<a class='loan-follow-chart' data-cusid='" . $row['cus_id'] . "' data-toggle='modal' data-target='#loanFollowChartModal'><span>Loan Followup Chart</span></a>
                        <a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a>
                        <a class='cust-profile' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Profile</span></a>
                        <a class='loan-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan History</span></a>
                        <a class='doc-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Document History</span></a>";

    $action .= "</div></div>";

    $sub_array[] = $action;

    //for Loan Followup edit
    $sub_array[] = "<input type='button' class='btn btn-primary loan-follow-edit' data-cusid='" . $row['cus_id'] . "' data-stage='" . $stage_arr[$row['cus_status']] . "' data-toggle='modal' data-target='#addLoanFollow' value='Follow' />";

    $qry = $connect->query("SELECT follow_date FROM loan_followup WHERE cus_id = '" . $row['cus_id'] . "' ORDER BY created_date DESC limit 1");
    //take last promotion follow up date inserted from new promotion table
    if ($qry->rowCount() > 0) {
        $fdate = $qry->fetch()['follow_date'];
        $sub_array[] = date('d-m-Y', strtotime($fdate));
    } else {
        $sub_array[] = '';
    }

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM request_creation";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
