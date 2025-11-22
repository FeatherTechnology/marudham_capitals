<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}


if ($userid != 1) {  // super admin bypass
    $userQry = $connect->query("
            SELECT group_id, line_id, due_followup_lines,noc_mapping_access
            FROM user 
            WHERE user_id = $userid
        ");
    $rowuser = $userQry->fetch();

    $accessType = $rowuser['noc_mapping_access'];
    $sub_area_ids = [];

    if ($accessType == 1) {
        // 🔹 Group-based access
        $group_ids = explode(',', $rowuser['group_id']);
        foreach ($group_ids as $group) {
            $groupQry = $connect->query("SELECT sub_area_id FROM area_group_mapping WHERE map_id = $group");
            if ($row_sub = $groupQry->fetch()) {
                $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_sub['sub_area_id']));
            }
        }
    } elseif ($accessType == 2) {
        // 🔹 Line-based access
        $line_ids = explode(',', $rowuser['line_id']);
        foreach ($line_ids as $line) {
            $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping WHERE map_id = $line");
            if ($row_line = $lineQry->fetch()) {
                $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_line['sub_area_id']));
            }
        }
    } elseif ($accessType == 3) {
        // 🔹 Due Followup-based access
        $due_ids = explode(',', $rowuser['due_followup_lines']);
        foreach ($due_ids as $due) {
            $dueQry = $connect->query("SELECT area_id FROM area_duefollowup_mapping WHERE map_id = $due");
            if ($row_due = $dueQry->fetch()) {
                $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_due['area_id']));
            }
        }
    }
    // Remove duplicates and store final list
    $sub_area_ids = array_unique(array_filter($sub_area_ids));
    $sub_area_list = implode(',', $sub_area_ids);
    $colName = ($accessType == 3)
        ? "cp.area_confirm_area"          // Due Followup
        : "cp.area_confirm_subarea";      // Group/Line
}
$column = array(
    'cp.id',
    'cp.cus_id',
    'cr.autogen_cus_id',
    'cp.cus_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'al.line_name',
    'cp.mobile1',
    'cp.id',
    'cp.id',
    'cp.id'
);

if ($userid == 1) {
    $query = 'SELECT cp.cus_id as cp_cus_id, cr.autogen_cus_id, cp.cus_name, ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name, cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id, ii.cus_status
    FROM acknowlegement_customer_profile cp 
    JOIN customer_register cr ON cp.cus_id = cr.cus_id
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    where ii.status = 0 and ii.cus_status IN (21,22,23) GROUP BY ii.cus_id '; // Only Issued and all lines not relying on sub area
} else {
    $query = " SELECT cp.cus_id AS cp_cus_id,
    cr.autogen_cus_id,
    cp.cus_name,
    ac.area_name,
    sa.sub_area_name,
    al.line_name,
    bc.branch_name,
    cp.mobile1,
    ii.cus_id AS ii_cus_id,
    ii.req_id,
   ii.cus_status
    FROM acknowlegement_customer_profile cp
    JOIN customer_register cr ON cp.cus_id = cr.cus_id
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    WHERE ii.status = 0
        AND ii.cus_status IN (21,22,23)
        AND $colName IN ($sub_area_list) ";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $search = " AND (cp.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR al.line_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
    $query .= $search;
}

$query .= 'GROUP BY ii.cus_id ';
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;

    $sub_array[] = $row['cp_cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];

    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];

    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cp_cus_id'];
    $id = $row['req_id'];
    $cus_name = $row['cus_name'];

    $cus_status = $row['cus_status'];
    if (in_array($cus_status, [21, 22])) {
        $noc_status = 'NOC';
    } else if ($cus_status == 23) {
        $noc_status = 'Pending';
    }
    $sub_array[] = $noc_status;
    $cus_sts = "<a href='' data-value ='" . $cus_id . "' data-value1 = '$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span></a>";
    $sub_array[] = $cus_sts;

    $action  = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'>
                    <i class='fa'>&#xf107;</i>
                </button>
                <div class='dropdown-content'>";

    $action .= "<a href='noc&upd=$id&cusidupd=$cus_id&action_type=noc' title='Edit details'>NOC</a>";

    // Fetch all NOC statuses for this customer
    $stsQry = $connect->query("SELECT cus_status FROM in_issue WHERE cus_id = '$cus_id'");
    $allStatus = $stsQry->fetchAll(PDO::FETCH_COLUMN);

    // Conditions
    $has21 = in_array(21, $allStatus);
    $all22 = !in_array(21, $allStatus) && !in_array(23, $allStatus) && in_array(22, $allStatus);
    // If status = 22 → show "Send"
    if (!$has21) {

        // If ALL loans are 22 → show SEND
        if ($all22 && $cus_status == 22) {
            $action .= "<a href='' title='Send details' class='remove-noc' data-reqid='$id' data-cusid='$cus_id'>Send</a>";
        }

        // For status 22 or 23 → show Summary + Letter
        if (in_array($cus_status, [22, 23])) {
            $action .= "<a href='noc&upd=$id&cusidupd=$cus_id&action_type=summary'>NOC Summary</a>";
            $action .= "<a href='' title='NOC Letter' class='noc-letter' data-reqid='$id' data-cusid='$cus_id'>NOC Letter</a>";
        }
    }
    $action .= "</div></div>";


    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cp.cus_id as cp_cus_id,cp.cus_name,cp.area_confirm_area,cp.area_confirm_subarea,cp.area_line,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id FROM 
    acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id
    where ii.status = 0 and ii.cus_status IN(21,22,23) GROUP BY ii.cus_id ";
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
