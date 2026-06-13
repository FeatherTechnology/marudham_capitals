<?php
@session_start();
include('..\ajaxconfig.php');

$userid = $_SESSION["userid"] ?? '';

$raising_arr = [1 => 'Myself', 3 => 'Agent', 4 => 'Customer'];
$column = array(
    'cc.id',
    'cc.com_code',
    'cc.com_date',
    'u.fullname',
    'cc.raising_for',
    'cc.raising_for',
    'cc.cus_name',
    'cdn.dep_name',
    'us.fullname',
    'cs.concern_subject',
    'cc.status',
    'cc.id'
);

$base_query = "FROM concern_creation cc
    JOIN user us ON FIND_IN_SET(
        us.user_id,
        CASE 
            WHEN cc.pass_to IS NOT NULL AND cc.pass_to != '' 
            THEN cc.pass_to
            ELSE cc.staff_assign_to
        END
    )
    JOIN concern_subject cs ON cc.com_sub = cs.concern_sub_id
    LEFT JOIN agent_creation ag ON cc.ag_name = ag.ag_id
    LEFT JOIN user u ON cc.insert_user_id = u.user_id
    LEFT JOIN concern_dept_name cdn ON cc.to_dept_name = cdn.id
    WHERE cc.status != 2  AND cc.insert_user_id = '$userid'";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $base_query .= " AND (cc.com_code LIKE '%" . $_POST['search'] . "%' 
                OR cc.com_date LIKE '%" . $_POST['search'] . "%' 
                OR cdn.dep_name LIKE '%" . $_POST['search'] . "%' 
                OR us.fullname LIKE '%" . $_POST['search'] . "%' 
                OR cs.concern_subject LIKE '%" . $_POST['search'] . "%') ";
}

$base_query .= "GROUP BY cc.id ";

/* ---------- ORDER ---------- */
$orderBy = '';
if (isset($_POST['order'])) {
    $orderBy = " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
}

/* ---------- Pagination ---------- */
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

/* ---------- Total records ---------- */
$statement = $connect->prepare("SELECT COUNT(*) FROM concern_creation WHERE status != 2");
$statement->execute();
$recordsTotal = (int) $statement->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $base_query");
$countStmt->execute();
$number_filter_row = (int) $countStmt->fetchColumn();

/* ---------- Data query ---------- */
$data_query = "SELECT cc.com_code, cc.com_date, u.fullname, cc.raising_for, cc.self_code, cc.self_name, ag.ag_code, ag.ag_name, cc.cus_id, cc.cus_name, cdn.dep_name, GROUP_CONCAT(DISTINCT us.fullname ORDER BY us.fullname SEPARATOR ', ') AS staff_name, cs.concern_subject, cc.concern_creation_uploads, cc.status, cc.id, cc.role_type $base_query $orderBy $limit ";

$statement = $connect->prepare($data_query);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno++;
    $sub_array[] = $row['com_code'];
    $sub_array[] = date('d-m-Y', strtotime($row['com_date']));
    $sub_array[] = $row['fullname'];
    $sub_array[] = $raising_arr[$row['raising_for']] ?? '';
    if ($row['raising_for'] == 1) {
        $sub_array[] = $row['self_code'] ?? '';
        $sub_array[] = $row['self_name'] ?? '';
    } else if ($row['raising_for'] == 3) {
        $sub_array[] = $row['ag_code'] ?? '';
        $sub_array[] = $row['ag_name'] ?? '';
    } else if ($row['raising_for'] == 4) {
        $sub_array[] = $row['cus_id'] ?? '';
        $sub_array[] = $row['cus_name'] ?? '';
    }
    $sub_array[] = $row['dep_name'] ?? '';
    $sub_array[] = $row['staff_name'];
    $sub_array[] = $row['concern_subject'];

    $concernCreationupload = explode(',', $row['concern_creation_uploads']) ?? '';
    $doc_upd_name = '';
    foreach ($concernCreationupload as $concernupd) {
        if ($concernupd != null) {
            $doc_upd_name .= "<a href=uploads/concern/concern_creation/".$concernupd ." target='_blank' >" . $concernupd . "</a>, " ;
        }
    }

    $sub_array[] = rtrim($doc_upd_name,', ');// to trim the comma at end

    //Status
    $con_sts = $row['status'];
    if ($con_sts == 0) {
        $sub_array[] = 'Pending';
    }
    if ($con_sts == 1) {
        $sub_array[] = 'Resolved';
    }

    $id = $row['id'];

    $action = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                <div class='dropdown-content'>";
    $action .= "<a href='concern_solution_view&upd=$id&pageId=3' class='concern_details' title='View'>View</a>";
    if ($con_sts == 1) {
        $action .= "<a href='#' class='concern_remove' title='Concern Remove'>Remove</a>";
    }
    $action .= "</div></div>";
    $sub_array[] = $action;

    $data[]      = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;