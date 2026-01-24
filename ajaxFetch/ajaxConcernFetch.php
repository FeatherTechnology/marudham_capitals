<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$raising_arr = [1 => 'Myself', 3 => 'Agent', 4 => 'Customer'];
$column = array(
    'cc.id',
    'cc.com_code',
    'cc.com_date',
    'u.fullname',
    'cc.raising_for',
    'cc.raising_for',
    'cc.cus_name',
    'cc.to_dept_name',
    'sc.staff_name',
    'cs.concern_subject',
    'cc.status',
    'cc.id'
);

$query = "SELECT cc.*,sc.staff_name,cs.concern_subject,ag.ag_name,u.fullname,ag.ag_code
    FROM concern_creation cc
     JOIN staff_creation sc ON sc.staff_id = COALESCE(NULLIF(cc.pass_to, ''), cc.staff_assign_to)
    JOIN concern_subject cs ON cc.com_sub = cs.concern_sub_id
    LEFT JOIN agent_creation ag ON cc.ag_name = ag.ag_id
    LEFT JOIN user u ON cc.insert_user_id = u.user_id
    WHERE cc.status != 2  && cc.insert_user_id = '" . strip_tags($userid) . "'"; // 

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= " AND (cc.com_code LIKE '%" . $_POST['search'] . "%' OR
            cc.com_date LIKE '%" . $_POST['search'] . "%' OR
            cc.to_dept_name LIKE '%" . $_POST['search'] . "%' OR
            sc.staff_name LIKE '%" . $_POST['search'] . "%' OR
            cs.concern_subject LIKE '%" . $_POST['search'] . "%') ";
}
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

    $sub_array[] = $row['com_code'];
    $sub_array[] = date('d-m-Y', strtotime($row['com_date']));
    $sub_array[] = $row['fullname'];
    $sub_array[] = isset($raising_arr[$row['raising_for']]) ? $raising_arr[$row['raising_for']] : '';
    if ($row['raising_for'] == 1) {
        $sub_array[] = isset($row['self_code']) ? $row['self_code'] : '';
        $sub_array[] = isset($row['self_name']) ? $row['self_name'] : '';
    } else if ($row['raising_for'] == 3) {
        $sub_array[] = isset($row['ag_code']) ? $row['ag_code'] : '';
        $sub_array[] = isset($row['ag_name']) ? $row['ag_name'] : '';
    } else if ($row['raising_for'] == 4) {
        $sub_array[] = isset($row['cus_id']) ? $row['cus_id'] : '';
        $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    }
    $sub_array[] = isset($row['to_dept_name']) ? $row['to_dept_name'] : '';
    $sub_array[] = $row['staff_name'];
    $sub_array[] = $row['concern_subject'];

    //Status
    $con_sts = $row['status'];
    if ($con_sts == 0) {
        $sub_array[] = 'Pending';
    }
    if ($con_sts == 1) {
        $sub_array[] = 'Resolved';
    }

    $id          = $row['id'];
    // if ($con_sts == 1) {
        // $action = "<a href='concern_solution_view&upd=$id&pageId=3' title='View Solution' >  <span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    // } else {
    //     $action = '';
    // }
    $action = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                <div class='dropdown-content'>";
    $action .= "<a href='concern_solution_view&upd=$id&pageId=3' class='concern_details' value='" . $row['id'] . "' title='View'>View</a>";
    if ($con_sts == 1) {
        $action .= "<a href='#' class='concern_remove' value='" . $row['id'] . "' title='Concern Remove'>Remove</a>";
    }
    $action .= "</div></div>";
    $sub_array[] = $action;

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cc.*
    FROM concern_creation cc
    WHERE cc.status != 2";
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