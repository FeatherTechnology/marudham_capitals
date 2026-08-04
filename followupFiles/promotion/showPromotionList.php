<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$follow_up_sts = '';
$follow_up_date = '';

$Obj            = new promotionListClass($connect);
$sub_area_list  = $Obj->sub_area_list;
$accessType     = $Obj->accessType;
$actionAccess   = $Obj->actionAccess;

$sub_status = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
$cusstatus = [21 => 'NOC Pending', 22 => 'NOC Completed', 23 => 'NOC Completed', 24 => 'NOC Handovered', 25 => 'Agent Handovered'];

$column = array(
    'cr.cus_reg_id',
    'cr.cus_id',
    'cr.autogen_cus_id',
    'cr.customer_name',
    'al.area_name',
    'sl.sub_area_name',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'cr.mobile1',
    'cr.cus_reg_id',
    'cs.consider_level',
    'cs.created_date',
    'rc.cus_status',
    'cr.cus_reg_id',
    'cr.cus_reg_id',
    'np.status',
    'np.follow_date',
    'np.followup_type'
);

if (isset($_POST['re_active']) && $_POST['re_active'] != "") {
    $re_active = "HAVING CURDATE() >= DATE_ADD(DATE_ADD(LAST_DAY(MAX(created_date)), INTERVAL 1 DAY),INTERVAL 6 MONTH)";
} else {
    $re_active = "HAVING CURDATE() < DATE_ADD( DATE_ADD(LAST_DAY(MAX(created_date)), INTERVAL 1 DAY),INTERVAL 6 MONTH)";
}

$areaColumn = ($accessType == 3)
    ? "cr.area_confirm_area"
    : "cr.area_confirm_subarea";

//only closed customers who dont have any loans in current.
// Simplified main query to fetch closed customers without loans
$baseqry = "FROM  customer_register cr
    JOIN (
        SELECT req_id, cus_id, consider_level, MAX(created_date) AS created_date 
        FROM closed_status 
        WHERE closed_sts = 1 
        GROUP BY cus_id $re_active
    ) cs ON cs.cus_id = cr.cus_id 
    LEFT JOIN area_list_creation al ON cr.area_confirm_area = al.area_id 
    LEFT JOIN sub_area_list_creation sl ON cr.area_confirm_subarea = sl.sub_area_id 
    LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sl.sub_area_id
    LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id 
    LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sl.sub_area_id
    LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id 
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
    LEFT JOIN new_promotion np ON np.cus_id = cs.cus_id AND np.created_date = (SELECT MAX(np1.created_date) FROM new_promotion np1 WHERE np1.cus_id = cs.cus_id)
    LEFT JOIN request_creation rc ON cr.cus_id = rc.cus_id
    WHERE $areaColumn IN ($sub_area_list) AND NOT EXISTS ( SELECT 1 FROM closed_status cs2 WHERE cs2.cus_id = cr.cus_id AND cs2.closed_sts IN (2,3)) AND NOT EXISTS ( SELECT 1 FROM request_creation r WHERE r.cus_id = cs.cus_id AND ((r.cus_status IN (4,5,6,7,8,9)) OR r.cus_status <= 20)) ";

if ($_POST['followUpSts']) {
    $follow_up_sts = $_POST['followUpSts'];
    $baseqry .= ($follow_up_sts == 'tofollow') ? "AND np.status IS NULL " : "AND TRIM(REPLACE(np.status,' ','')) = '$follow_up_sts' ";
}

if ($_POST['dateType']) {
    $date_type = $_POST['dateType']; //1=Closed date, 2=Followup date.
    $baseqry .= ($date_type == '1') ? "AND DATE(cs.created_date) BETWEEN '" . $_POST['followUpFromDate'] . "' AND '" . $_POST['followUpToDate'] . "' " : "AND DATE(np.follow_date) BETWEEN '" . $_POST['followUpFromDate'] . "' AND '" . $_POST['followUpToDate'] . "' ";
}

$baseqry .= ($_POST['followupType']) ? "AND np.followup_type = '" . $_POST['followupType'] . "'" : "";

$search = '';
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = " AND (cr.cus_id LIKE '%" . $_POST['search'] . "%' 
    OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' 
    OR cr.customer_name LIKE '%" . $_POST['search'] . "%' 
    OR al.area_name LIKE '%" . $_POST['search'] . "%'
    OR sl.sub_area_name LIKE '%" . $_POST['search'] . "%' 
    OR bc.branch_name LIKE '%" . $_POST['search'] . "%' 
    OR agm.group_name LIKE '%" . $_POST['search'] . "%' 
    OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
    OR cr.mobile1 LIKE '%" . $_POST['search'] . "%'  
    OR np.status LIKE '%" . $_POST['search'] . "%' ) ";
}

$order = '';
if (isset($_POST['order'])) {
    $order = ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

$limit = '';
if ($_POST['length'] != -1) {
    $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$baseqry .= "$search GROUP BY cr.cus_id $order ";

// Count query for filtering (use the same logic but without limit)
$num_qry = $connect->query("SELECT COUNT(*) FROM (SELECT cr.cus_id  $baseqry) AS sub_qry");
$num_qry->execute();
$number_filter_row = $num_qry->fetchColumn();

$sql = $connect->query("SELECT cr.req_ref_id as req_id, cr.cus_id, cr.autogen_cus_id, cr.customer_name as cus_name, al.area_name, sl.sub_area_name, bc.branch_name, agm.group_name, alm.line_name, cr.mobile1, cs.consider_level, cs.created_date, np.status AS followup_sts, np.follow_date, np.followup_type, rc.cus_status AS noc_cus_status $baseqry $limit");

$sno = 1;
$data = [];
while ($row = $sql->fetch()) {
    //take last closed date of this customer to show when this customer added to promotion list
    $createddate = date('d-m-Y', strtotime($row['created_date']));

    $charts = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a><a class='cust-profile' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Profile</span></a><a class='customer-sts' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Status</span></a><a class='loan-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan History</span></a><a class='doc-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Document History</span></a></div></div>";

    //for intrest or not intrest choice to make


    $actions =
        "<div class='dropdown'>
        <button class='btn btn-outline-secondary'>
            <i class='fa'>&#xf107;</i>
        </button>
        <div class='dropdown-content'>
            <a class='noc-call' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>NOC Call</span></a>
            <a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a>
            <a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a>
            <a class='un-available' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Unavailable</span></a>";

    if ($actionAccess == 1) {
        $actions .= "<a class='add_close' data-toggle='modal' data-target='#addClosedModal' data-id='" . $row['cus_id'] . "'><span>Closed Status</span></a>";
    }

    $actions .= "</div></div>";

    $followupdate = (isset($row['follow_date'])) ? date('d-m-Y', strtotime($row['follow_date'])) : '';

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
        $row['cus_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['group_name'],
        $row['line_name'],
        $row['mobile1'],
        'Consider',
        $sub_status[$row['consider_level']], //fetched from closed status table above mentioned    
        $createddate,
        $cusstatus[$row['noc_cus_status']] ?? '',
        $charts,
        $actions,
        $row['followup_sts'],
        $followupdate,
        $followup_type
    ];
}

function count_all_data($connect)
{
    $statement = $connect->prepare("SELECT COUNT(*) FROM closed_status cs WHERE cs.closed_sts = 1");
    $statement->execute();
    return (int) $statement->fetchColumn();
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
