<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
    }

    $line_id = explode(',', $line_id);
    $sub_area_list = array();
    foreach ($line_id as $line) {
        $lineQry = $connect->query("SELECT * FROM area_line_mapping where map_id = $line ");
        $row_sub = $lineQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',', $sub_area_ids);
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
    where ii.status = 0 and ii.cus_status IN (21,22) GROUP BY ii.cus_id '; // Only Issued and all lines not relying on sub area
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
        AND ii.cus_status IN (21,22)
        AND cp.area_confirm_subarea IN ($sub_area_list) ";

    $forcount = "SELECT cp.cus_id 
        FROM acknowlegement_customer_profile cp 
        JOIN in_issue ii ON cp.cus_id = ii.cus_id
        JOIN customer_register cr ON cp.cus_id = cr.cus_id
        JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
        JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
        JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
        JOIN branch_creation bc ON al.branch_id = bc.branch_id
        where ii.status = 0 and ii.cus_status IN(21,22) and cp.area_confirm_subarea IN ($sub_area_list) ";
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
    $forcount .= $search;
}

$query .= 'GROUP BY ii.cus_id ';
$forcount .= 'GROUP BY ii.cus_id ';
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
    $forcount .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
    $forcount .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($forcount);

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

    $cus_status = $row['cus_status'];

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    $action .= "<a href='noc&upd=$id&cusidupd=$cus_id' title='Edit details' >NOC</a>";
    if ($cus_status == 22) {
        //if this variable contains value 0 then all document are given to customer as noc
        $action .= "<a href='' title='Remove details' class='remove-noc' data-reqid='$id' data-cusid='$cus_id' >Remove</a>";
        $action .= "<a href='' title='NOC Letter' class='noc-letter' data-reqid='$id' data-cusid='$cus_id' >NOC Letter</a>";
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
    where ii.status = 0 and ii.cus_status IN(21,22) GROUP BY ii.cus_id ";
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