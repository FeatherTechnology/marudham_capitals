<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = "";

if ($userid != 1) {
    $userQry = $connect->query("SELECT group_id, report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
        $group_id = $rowuser['group_id'];
        $report_access = $rowuser['report_access'];
    
    if($report_access =='1'){
        $group_id = explode(',', $group_id);
        $sub_area_list = array();
        foreach ($group_id as $group) {
            $groupQry = $connect->query("SELECT sub_area_id FROM area_group_mapping WHERE map_id = $group ");
            $row_sub = $groupQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);

        $user_based = "WHERE cp.area_confirm_subarea IN ($sub_area_list) AND cp.insert_login_id = '$userid' ";
    }
}

$statusObj = [
    '0' => "In Request",
    '1' => 'In Verification',
    '2' => 'In Approval',
    '3' => 'In Acknowledgement',
    '4' => 'Cancel - Request',
    '5' => 'Cancel - Verification',
    '6' => 'Cancel - Approval',
    '7' => 'Cancel - Acknowledgement',
    '8' => 'Revoke - Request',
    '9' => 'Revoke - Verification',
    '10' => 'In Verification',
    '11' => 'In Verification',
    '12' => 'In Verification',
    '13' => 'In Issue',
    '14' => 'Collection',
    '15' => 'Collection Error',
    '16' => 'Collection Legal',
    '17' => 'Collection',
    '20' => 'Closed',
    '21' => 'NOC',
];
$how_to_know_obj = [
    '0' => 'Customer Reference',
    '1' => 'Advertisement',
    '2' => 'Promotion Activity',
    '3' => 'Agent Reference',
    '4' => 'Staff Reference',
    '5' => 'Other Reference',
];
$occupationTypeObj = [
    0 => '', //for not mentioned occ type
    1 => 'Govt Job',
    2 => 'Pvt Job',
    3 => 'Business',
    4 => 'Self Employed',
    5 => 'Daily wages',
    6 => 'Agriculture',
    7 => 'Others'
];
$residentialTypeObj = [
    4 => '', //for not mentioned resident type
    0 => 'Own',
    1 => 'Rental',
    2 => 'Lease',
    3 => 'Quarters'
];

$column = array(
    'cp.id',
    'cp.cus_id',
    'cp.cus_name',
    'fam.famname',
    'fam.relationship',
    'al.area_name',
    'sal.sub_area_name',
    'cp.mobile1',
    'reg.loan_limit',
    'alm.line_name',
    'agm.group_name',
    'cp.id',
    'cp.occupation_type',
    'cp.occupation_details',
    'cp.residential_type',
    'cp.residential_details',
    'reg.travel_with_company',
    'reg.blood_group',
    'cp.id',
);

$query = "SELECT 
            cp.cus_id,cp.cus_name,
            cp.mobile1,
            alm.line_name,
            agm.group_name,
            cp.occupation_type,
            cp.occupation_details,
            cp.residential_type,
            cp.residential_details,
            cp.blood_group,
            fam.famname,
            fam.relationship,
            al.area_name,
            sal.sub_area_name,
            reg.loan_limit,
            reg.how_to_know,
            reg.travel_with_company,
            reg.blood_group as reg_blood,
            req.cus_status

            FROM customer_profile cp
            JOIN verification_family_info fam ON cp.guarentor_name = fam.id
            JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
            JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
            JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)
            JOIN area_group_mapping agm ON FIND_IN_SET(al.area_id, agm.area_id)
            JOIN customer_register reg ON cp.cus_id = reg.cus_id
            JOIN request_creation req ON cp.req_id = req.req_id

            $user_based ";

if ($_POST['search'] != "") {
    $query .= " and (cp.id LIKE '%" . $_POST['search'] . "%' OR
            cp.cus_id LIKE '%" . $_POST['search'] . "%' OR
            cp.cus_name LIKE '%" . $_POST['search'] . "%' OR
            cp.mobile1 LIKE '%" . $_POST['search'] . "%' OR
            alm.line_name LIKE '%" . $_POST['search'] . "%' OR
            agm.group_name LIKE '%" . $_POST['search'] . "%' OR
            cp.occupation_type LIKE '%" . $_POST['search'] . "%' OR
            cp.occupation_details LIKE '%" . $_POST['search'] . "%' OR
            cp.residential_type LIKE '%" . $_POST['search'] . "%' OR
            cp.residential_details LIKE '%" . $_POST['search'] . "%') ";
}

$query .= " GROUP BY cp.cus_id ";

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
}

$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
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
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['famname'];
    $sub_array[] = $row['relationship'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = moneyFormatIndia($row['loan_limit']);
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $how_to_know_obj[$row['how_to_know']] ?? '';

    $row['occupation_type'] = $row['occupation_type'] != '' ? $row['occupation_type'] : 0;
    $sub_array[] = $occupationTypeObj[$row['occupation_type']] ?? '';

    $sub_array[] = $row['occupation_details'];

    $row['residential_type'] = $row['residential_type'] != '' ? $row['residential_type'] : 4;
    $sub_array[] = $residentialTypeObj[$row['residential_type'] ?? 4] ?? '';

    $sub_array[] = $row['residential_details'];
    $sub_array[] = $row['travel_with_company'];
    $sub_array[] = ($row['reg_blood'] != '') ? $row['reg_blood'] : $row['blood_group'];
    $sub_array[] = $statusObj[$row['cus_status']] ?? '';

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = "SELECT COUNT(*) as count FROM customer_profile ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->fetch()['count'];
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

// Close the database connection
$connect = null;