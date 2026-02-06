<?php
include('../ajaxconfig.php');
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
    // $cus_id='100010001000';
    $where =" cus_id = '" . strip_tags($cus_id) . "'";
}
if (isset($_POST['autogen_cus_id'])) {
    $autogen_cus_id = $_POST['autogen_cus_id'];
     $where =" autogen_cus_id = '" . strip_tags($autogen_cus_id) . "'";
}

$records = array();

$result = $connect->query("SELECT * FROM customer_register where $where");
if ($result->rowCount() > 0) {
    $row = $result->fetch();

    $records['cus_name'] = $row['customer_name'];
    $records['dob'] = $row['dob'];
    $records['age'] = $row['age'];
    $records['gender'] = $row['gender'];
    $records['state'] = $row['state'];
    $records['district'] = $row['district'];
    $records['taluk'] = $row['taluk'];
    $records['area'] = $row['area'];
    $records['sub_area'] = $row['sub_area'];
    $records['address'] = $row['address'];
    $records['mobile1'] = $row['mobile1'];
    $records['mobile2'] = $row['mobile2'];
    $records['father_name'] = $row['father_name'];
    $records['mother_name'] = $row['mother_name'];
    $records['marital'] = $row['marital'];
    $records['spouse'] = $row['spouse'];
    $records['occupation_type'] = $row['occupation_type'];
    $records['occupation'] = $row['occupation'];
    $records['loan_limit'] = $row['loan_limit'];
    $records['pic'] = $row['pic'];

    $records['message'] = "Existing";

    $subArea = $records['sub_area'];
    $grpList = $connect->query("SELECT agm.group_name FROM area_group_mapping_sub_area agmsa 
        LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id WHERE agmsa.sub_area_id = $subArea");
    if ($grpList->rowCount() > 0) {
        $grprow = $grpList->fetch();
        $records['grp_name'] = $grprow['group_name'];
    }

    $lineList = $connect->query("SELECT alm.line_name FROM area_line_mapping_sub_area almsa 
        LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id WHERE almsa.sub_area_id = $subArea");
    if ($lineList->rowCount() > 0) {
        $linerow = $lineList->fetch();
        $records['line_name'] = $linerow['line_name'];
    }

    $area = $records['area'];
    $area_list = $connect->query("SELECT area_name FROM area_list_creation where area_id = '" . $area . "' and status=0 and area_enable = 0");
    if ($area_list->rowCount() > 0) {
        $arearow = $area_list->fetch();
        $records['area_name'] = $arearow['area_name'];
    }

    $sub_area_list = $connect->query("SELECT sub_area_name FROM sub_area_list_creation where sub_area_id ='" . $subArea . "' and status=0 and sub_area_enable = 0");
    if ($sub_area_list->rowCount() > 0) {
        $subArearow = $sub_area_list->fetch();
        $records['sub_area_name'] = $subArearow['sub_area_name'];
    }
} else {
    $records['message'] = "New";
}

echo json_encode($records);

// Close the database connection
$connect = null;