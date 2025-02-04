<?php
include('../ajaxconfig.php');
@session_start();

// $taluk='Vandavasi';  
if (isset($_POST['talukselected'])) {
    $taluk = $_POST['talukselected'];
}
// $userid = '25';
if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$loan_category_arr = array();

$user_area = array();

$Qry = $connect->query("SELECT * FROM user where status=0 and user_id= '" . $userid . "'"); //fetching group of current staff
$run = $Qry->fetch();
$user_group = explode(',', $run['group_id']);

foreach ($user_group as $group_id) {

    $Qry = $connect->query("SELECT * FROM area_group_mapping where status =0  and map_id = $group_id "); //fetching area id from group
    $run = $Qry->fetch();
    $user_area[] = explode(',', $run['area_id']);
}

$result = $connect->query("SELECT * FROM area_list_creation where taluk LIKE '%" . $taluk . "%' and status=0 and area_enable = 0");

while ($row = $result->fetch()) {
    $area_id = $row['area_id'];
    $area_name = $row['area_name'];

    for ($i = 0; $i < sizeof($user_area); $i++) {

        if (in_array($area_id, $user_area[$i])) {
            $loan_category_arr[] = array("area_id" => $area_id, "area_name" => $area_name);
        }
    }
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
