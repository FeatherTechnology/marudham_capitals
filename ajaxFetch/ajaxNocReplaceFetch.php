<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

if ($userid != 1) {  // super admin bypass
    $userQry = $connect->query("
            SELECT group_id, line_id, due_followup_lines, noc_mapping_access
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
    'n.noc_id',
    'n.cus_id',
    'cr.autogen_cus_id',
    'cp.cus_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'al.line_name',
    'cp.mobile1',
    'n.noc_id',
    'n.noc_id'
);

if ($userid == 1) {
    $query = "SELECT cr.autogen_cus_id, cp.cus_name, ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name, cp.mobile1, n.cus_id, n.req_id 
    FROM noc n 
    JOIN acknowlegement_customer_profile cp ON n.req_id = cp.req_id 
    JOIN customer_register cr ON cp.cus_id = cr.cus_id 
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id 
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id 
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id) 
    JOIN branch_creation bc ON al.branch_id = bc.branch_id 
    WHERE n.noc_replace_status = 1 "; 
    
} else {
    $query = "SELECT cr.autogen_cus_id, cp.cus_name, ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name, cp.mobile1, n.cus_id, n.req_id 
    FROM noc n 
    JOIN acknowlegement_customer_profile cp ON n.req_id = cp.req_id 
    JOIN customer_register cr ON cp.cus_id = cr.cus_id 
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id 
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id 
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id) 
    JOIN branch_creation bc ON al.branch_id = bc.branch_id 
    WHERE n.noc_replace_status = 1 AND $colName IN ($sub_area_list) ";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= " AND (n.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR al.line_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

//for row count execute.
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

    $sub_array[] = $sno++;

    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cus_id'];
    $cus_name = $row['cus_name'];
    $req_id = $row['req_id'];

    $cus_sts = "<a href='' data-value ='" . $cus_id . "' class='customer-status' data-toggle='modal' data-target='.customerstatus'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span></a>";
    $sub_array[] = $cus_sts;

    $sub_array[] = "<button class='btn btn-primary view-track' title='View details' data-reqid='$req_id' data-cusid='$cus_id' data-cusname='$cus_name' data-toggle='modal' data-target='.viewDocModal'>View</button>";

    $data[]      = $sub_array;
}

function count_all_data($connect)
{
    $statement = $connect->prepare("SELECT cus_id FROM noc WHERE noc_replace_status = 1 ");
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
