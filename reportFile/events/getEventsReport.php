<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$user_based = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];
    
    if($report_access =='1'){ //Report access individual.
       $user_based = "AND ep.insert_login_id = '$userid' ";
    }
}

$from_date = date('Y-m-d', strtotime($_POST['from_date']));
$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$where  = "(date(ep.event_created_date) >= '" . $from_date . "') and (date(ep.event_created_date) <= '" . $to_date . "') ";


$where  .= $user_based;

$column = array(
    'ep.id',
    'e.event_name',
    'ep.name',
    'ep.mobile_num',
    'alc.area_name',
    'slc.sub_area_name',
    'u.fullname',
    'ep.event_created_date'
);

$query = "SELECT 
    ep.event_created_date ,ep.name ,ep.mobile_num ,ep.area ,ep.sub_area ,ep.insert_login_id ,e.event_name ,alc.area_name ,slc.sub_area_name ,u.fullname
FROM 
    event_promotion ep
LEFT JOIN 
    events e ON e.id = ep.event_id
LEFT JOIN 
    area_list_creation alc ON ep.area = alc.area_id
LEFT JOIN 
    sub_area_list_creation slc ON ep.sub_area = slc.sub_area_id
LEFT JOIN 
    user u ON u.user_id = ep.insert_login_id
WHERE $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " and (e.event_name LIKE '%" . $_POST['search'] . "%' OR
                ep.name LIKE '%" . $_POST['search'] . "%' OR
                ep.mobile_num LIKE '%" . $_POST['search'] . "%' OR
                alc.area_name LIKE '%" . $_POST['search'] . "%' OR
                slc.sub_area_name LIKE '%" . $_POST['search'] . "%' OR
                ep.event_created_date LIKE '%" . $_POST['search'] . "%' OR
                u.fullname LIKE '%" . $_POST['search'] . "%' ) ";
    }
}


if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}
// echo $query;die;
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
    $sub_array[] = $row['event_name'];
    $sub_array[] = $row['name'];
    $sub_array[] = $row['mobile_num'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['fullname'];
    $sub_array[] = date('d-m-Y', strtotime($row['event_created_date']));

    $data[]      = $sub_array;
}
$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function count_all_data($connect)
{
    $query     = "SELECT id FROM event_promotion ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

// Close the database connection
$connect = null;
