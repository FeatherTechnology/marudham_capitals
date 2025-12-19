<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $staff_id = $rowuser['staff_id'];
    }
}
$column = array(
    'cc.id',
    'cc.com_code',
    'cc.com_date',
    'sc.staff_name',
    'cs.concern_subject',
    'cc.status',
    'cc.id'
);

$query = "SELECT cc.*,sc.staff_name,cs.concern_subject,stc.staff_type_name
    FROM concern_creation cc
    JOIN staff_creation sc ON cc.staff_assign_to = sc.staff_id
    LEFT JOIN staff_type_creation stc ON sc.staff_type = stc.staff_type_id
    JOIN concern_subject cs ON cc.com_sub = cs.concern_sub_id
    WHERE cc.status != 2 && cc.staff_assign_to = '" . strip_tags($staff_id) . "' "; // 
// echo $query;

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= " AND (cc.com_code LIKE '%" . $_POST['search'] . "%' OR
            cc.com_date LIKE '%" . $_POST['search'] . "%' OR
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

    $id = $row['id'];

    // if ($con_sts == 0) {
    //     $action = "<a href='concern_solution&upd=$id' title='Add Solution' >  <span class='icon-border_color' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    // } else if ($con_sts == 1) {
    //     $action = "<a href='concern_solution_view&upd=$id&pageId=2' title='View Solution' >  <span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    // }
    if ($con_sts == 0) {
        if ($row['staff_type_name'] == "Director" || $row['staff_type_name'] == "Manager" || $row['staff_type_name'] == "Admin" || $row['staff_type_name'] == "TL" || $row['staff_type_name'] == "Training TL" || $row['staff_type_name'] == "Executive Director") {

            $action = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                <div class='dropdown-content'>";

            $action .= "<a href='concern_solution&upd=$id&pageId=1' value='" . $row['id'] . "' title='Concern Assign'>Assign</a>";
            $action .= "<a href='concern_solution&upd=$id&pageId=2' class = 'concern_solution' value='" . $row['id'] . "' title='Concern Solution'>Solution</a>";
            $action .= "</div></div>";
        } else {
            $action = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                <div class='dropdown-content'>";
            $action .= "<a href='concern_solution&upd=$id&pageId=2' value='" . $row['id'] . "' title='Concern Solution'>Solution</a>";
            $action .= "</div></div>";
        }
    } else if ($con_sts == 1) {
        $action = "<a href='concern_solution_view&upd=$id&pageId=4' value='" . $row['id'] . "'>
                   <button class='btn btn-primary'>View</button>
               </a>";
    }

    $sub_array[] = $action;

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cc.*,sc.staff_name,cs.concern_subject
    FROM concern_creation cc
    JOIN staff_creation sc ON cc.staff_assign_to = sc.staff_id
    JOIN concern_subject cs ON cc.com_sub = cs.concern_sub_id
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
