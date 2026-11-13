<?php
//Also using in Promotion Activity - New.
include('../ajaxconfig.php');
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
    $where =" cus_id = '$cus_id'";
}
if (isset($_POST['autogen_cus_id'])) {
    $autogen_cus_id = $_POST['autogen_cus_id'];
    $where =" autogen_cus_id = '$autogen_cus_id'";
}

$records = [];

$result = $connect->query("SELECT autogen_cus_id, customer_name, dob, age, gender, state, district, taluk, area, sub_area, address, mobile1, mobile2, father_name, mother_name, marital, spouse, occupation_type, occupation, loan_limit, pic, mark_as_spam FROM customer_register WHERE $where");
if ($result->rowCount() > 0) {
    $row = $result->fetch();

    $records['autogen_cus_id'] = $row['autogen_cus_id'];
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
    $records['mark_as_spam'] = $row['mark_as_spam'];

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
    $area_list = $connect->query("SELECT area_name FROM area_list_creation WHERE area_id = '$area' AND status = 0 AND area_enable = 0");
    if ($area_list->rowCount() > 0) {
        $arearow = $area_list->fetch();
        $records['area_name'] = $arearow['area_name'];
    }

    $sub_area_list = $connect->query("SELECT sub_area_name FROM sub_area_list_creation WHERE sub_area_id = '$subArea' AND status = 0 AND sub_area_enable = 0");
    if ($sub_area_list->rowCount() > 0) {
        $subArearow = $sub_area_list->fetch();
        $records['sub_area_name'] = $subArearow['sub_area_name'];
    }

} else {
    $records['message'] = "New";
}

if (isset($_POST['cus_id'])) {
    $result = $connect->query("SELECT mark_as_spam FROM verification_family_info WHERE relation_aadhar = '$cus_id'");
    $famspam = $result->fetch()['mark_as_spam'] ?? '0';
    if($famspam =='1') {$records['mark_as_spam'] = $famspam;}
}

echo json_encode($records);

// Close the database connection
$connect = null;