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
        $ids = explode(',', $rowuser['group_id']);
        $column_name = "sub_area_id";
        $table_name = "area_group_mapping";

    } elseif ($accessType == 2) {
        // 🔹 Line-based access
        $ids = explode(',', $rowuser['line_id']);
        $column_name = "sub_area_id";
        $table_name = "area_line_mapping";

    } elseif ($accessType == 3) {
        // 🔹 Due Followup-based access
        $ids = explode(',', $rowuser['due_followup_lines']);
        $column_name = "area_id";
        $table_name = "area_duefollowup_mapping";
    }

    foreach ($ids as $id) {
        $dueQry = $connect->query("SELECT $column_name FROM $table_name WHERE map_id = $id");
        if ($row_due = $dueQry->fetchObject()) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_due->$column_name));
        }
    }

    // Remove duplicates and store final list
    $sub_area_ids = array_unique(array_filter($sub_area_ids));
    $sub_area_list = implode(',', $sub_area_ids);
    $colName = ($accessType == 3)
        ? "cr.area_confirm_area"          // Due Followup
        : "cr.area_confirm_subarea";      // Group/Line
}

$column = array(
    'cs.updated_date',
    'cr.cus_id',
    'cr.autogen_cus_id',
    'cr.customer_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'al.line_name',
    'cr.mobile1',
    'cs.updated_date',
    'cs.updated_date',
    'cs.updated_date',
    'cs.updated_date'
);

if ($userid == 1) {
    $query = "SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name, cr.mobile1
    FROM closed_status cs 
    JOIN customer_register cr ON cs.cus_id = cr.cus_id
    JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cr.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    WHERE cs.cus_sts = 23 "; // Only Issued and all lines not relying on sub area
} else {
    $query = "SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name, cr.mobile1
    FROM closed_status cs 
    JOIN customer_register cr ON cs.cus_id = cr.cus_id
    JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cr.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    WHERE cs.cus_sts = 23 
        AND $colName IN ($sub_area_list) ";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= " AND (cr.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
            OR cr.customer_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR al.line_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR cr.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
}

$query .= 'GROUP BY cs.cus_id ';

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} 

$query1 = ($_POST['length'] != -1) ? 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'] : '';

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
    $cus_id = $row['cus_id'];

    $sub_array[] = $sno++;
    $sub_array[] = $cus_id;
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['customer_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['mobile1'];

    // Fetch receive status + receive_by
    $qry = "SELECT receive_status, receive_by 
            FROM noc 
            WHERE cus_id = '$cus_id' AND cus_status = 23 GROUP BY cus_id";

    $res = $connect->query($qry);
    $rec = $res->fetch();

    $receive_status  = $rec['receive_status'];   // 0 or 1
    $receive_by      = $rec['receive_by'];       // user_id of the person who received

    // ---------------- STATUS COLUMN ----------------
    if ($receive_status == 0) {
        $sub_array[] = "Pending";
    } else {
        $sub_array[] = "Completed";
    }


    // ---------------- RECEIVE BY COLUMN ----------------
    $receive_person = "";

    if ($receive_by != "") {
        $userQry = $connect->query("
            SELECT user_name
            FROM user 
            WHERE user_id = $receive_by
        ");

        $rowuser = $userQry->fetch();
        $receive_person = $rowuser['user_name'];
    }

    $sub_array[] = ($receive_person != "") ? $receive_person : " ";
    
    $sub_array[] = "<a href='' data-value ='" . $cus_id . "' class='customer-status' data-toggle='modal' data-target='.customerstatus'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span></a>";

    // ---------------- ACTION BUTTON LOGIC ----------------
    $action = ""; // default

    if ($receive_status == 0) {

        // Status pending → show Send to everyone
        $action = "<div class='dropdown'>
            <button class='btn btn-outline-secondary'>
                <i class='fa'>&#xf107;</i>
            </button>
            <div class='dropdown-content'>
                <a href='' title='Receive details' 
                class='receive-noc'
                data-cusid='$cus_id'>Receive</a>
            </div>
        </div>";

    } else {

        // Status Completed → only show button to the person who received
        if ($receive_by == $userid) {

            $action = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'>
                    <i class='fa'>&#xf107;</i>
                </button>
                <div class='dropdown-content'>
                    <a href='noc_handover&cusidupd=$cus_id' 
                    title='NOC handover'>Handover</a>
                </div>
            </div>";

        } else {

            // Hide action from all other users
            $action = "<span class='text-muted'></span>";
        }
    }

    $sub_array[] = $action;

    $sub_array[] = $action;
    $data[]      = $sub_array;
}

function count_all_data($connect)
{
    $query     = "SELECT cus_id FROM in_issue WHERE status = 0 AND cus_status = 23 GROUP BY cus_id";
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
