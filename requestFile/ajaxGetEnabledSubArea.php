<?php
include('../ajaxconfig.php');
@session_start();
// $area ='1';
if (isset($_POST['area'])) {
    $area = $_POST['area'];
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
    $user_sub_area[] = explode(',', $run['sub_area_id']);
}


$result = $connect->query("SELECT * FROM sub_area_list_creation where area_id_ref='" . $area . "' and status=0 and sub_area_enable = 0");
while ($row = $result->fetch()) {
    $sub_area_id = $row['sub_area_id'];
    $sub_area_name = $row['sub_area_name'];
    // print_r($sub_area_id);
    for ($i = 0; $i < sizeof($user_sub_area); $i++) {

        if (in_array($sub_area_id, $user_sub_area[$i])) {

            // $checkQry=$connect->query("SELECT * FROM area_creation where status=0 and FIND_IN_SET($sub_area_id,sub_area)");
            // if ($checkQry->num_rows>0){
            //     $disabled = true;
            // }else{$disabled=false;}
            // $checkres = $checkQry->fetch();

            $loan_category_arr[] = array("sub_area_id" => $sub_area_id, "sub_area_name" => $sub_area_name);
        }
    }
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;