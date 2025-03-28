<?php

session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = "";

if ($userid != 1) {

    $userQry = $connect->query("SELECT line_id, report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $line_id = $rowuser['line_id'];
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') {
        $line_id = explode(',', $line_id);
        $sub_area_list = array();
        foreach ($line_id as $line) {
            $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping where map_id = $line ");
            $row_sub = $lineQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);

        $user_based = " AND cp.area_confirm_subarea IN ($sub_area_list) AND c.insert_login_id = '$userid' ";
    }
}

$where = "";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "AND (date(c.comm_date) >= '" . $from_date . "') AND (date(c.comm_date) <= '" . $to_date . "') ";
}

$where .= $user_based;

$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];
$ftype = [1 => 'Direct', 2 => 'Mobile'];
$fstatus = [1 => 'Commitment', 2 => 'Unavailable', 3 => 'RNR', 4 => 'Not Reachable', 5 => 'Switch Off', 6 => 'Not in Use', 7 => 'Blocked'];
$per_type_arr = [1 => 'Customer', 2 => 'Garentor', 3 => 'Family Member'];

$column = array(
    'c.id',
    'cp.area_line',
    'ii.loan_id',
    'ii.updated_date',
    'c.cus_id',
    'cp.cus_name',
    'c.ftype',
    'c.fstatus',
    'c.person_type',
    'c.person_name',
    'c.relationship',
    'c.remark',
    'c.comm_date',
    'u.role',
    'u.user_name',
    'c.hint'
);

$query = "SELECT 
    cp.area_line AS line,
    ii.loan_id,
    ii.updated_date AS loan_date,
    c.cus_id,
    cp.cus_name,
    c.ftype,
    c.fstatus,
    c.person_type,
    c.person_name,
    c.relationship,
    c.remark,
    c.comm_date,
    u.role,
    u.user_name,
    c.hint
FROM 
    commitment c
LEFT JOIN 
    user u ON u.user_id = c.insert_login_id
JOIN 
    acknowlegement_customer_profile cp ON c.req_id = cp.req_id
JOIN 
    in_issue ii ON ii.req_id = c.req_id
WHERE 1
    $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (cp.area_line LIKE '%" . $_POST['search'] . "%' OR
            ii.loan_id LIKE '%" . $_POST['search'] . "%' OR
            ii.updated_date LIKE '%" . $_POST['search'] . "%' OR
            c.cus_id LIKE '%" . $_POST['search'] . "%' OR
            cp.cus_name LIKE '%" . $_POST['search'] . "%' OR
            c.ftype LIKE '%" . $_POST['search'] . "%' OR
            c.fstatus LIKE '%" . $_POST['search'] . "%' OR
            c.person_type LIKE '%" . $_POST['search'] . "%' OR
            c.person_name LIKE '%" . $_POST['search'] . "%' OR
            c.relationship LIKE '%" . $_POST['search'] . "%' OR
            c.remark LIKE '%" . $_POST['search'] . "%' OR
            c.comm_date LIKE '%" . $_POST['search'] . "%' OR
            u.role LIKE '%" . $_POST['search'] . "%' OR
            u.user_name LIKE '%" . $_POST['search'] . "%' OR
            c.hint LIKE '%" . $_POST['search'] . "%' OR";
    }
}

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

if ($_POST['length'] != -1) {
    $statement = $connect->prepare($query . $query1);
    $statement->execute();
}
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $ftype[$row['ftype']];
    $sub_array[] = $fstatus[$row['fstatus']];
    $sub_array[] = $per_type_arr[$row['person_type']];

    // Fetch person name based on person type
    if ($row['person_type'] == 1) {
        $name = getCustomer($connect, $row['cus_id']);
        $relationship = "NIL";

    } elseif ($row['person_type'] == 2) {
        $person_name = getGarentor($connect, $row['cus_id']);
        $name =  $person_name['name'];
        $relationship = $person_name['relationship'];

    }elseif ($row['person_type'] == 3) {
        $person_name = getFamilyMember($connect, $row['person_name']);
        $name =  $person_name['name'];
        $relationship = $person_name['relationship'];
        
    }

    $sub_array[] = $name;
    $sub_array[] = $relationship;
    $sub_array[] = $row['remark'];
    $sub_array[] = date('d-m-Y', strtotime($row['comm_date']));
    $sub_array[] = $role_arr[$row['role']];
    $sub_array[] = $row['user_name'];
    $sub_array[] = $row['hint'];
    $data[] = $sub_array;
    $sno = $sno + 1;
}


function getCustomer($connect, $cus_id)
{
    $result = $connect->query("SELECT customer_name from customer_register where cus_id = '$cus_id' ");
    $cus_name = $result->fetch()['customer_name'];
    return $cus_name;
}

function getGarentor($connect, $cus_id)
{
    $query = "SELECT cp.guarentor_name, vfi.famname, vfi.relationship FROM customer_profile cp JOIN verification_family_info vfi ON cp.guarentor_name = vfi.id WHERE cp.cus_id = '$cus_id' ORDER BY cp.id DESC LIMIT 1 ";
    $result = $connect->query($query);
    $row = $result->fetch();
    $response = [
        "name" => $row['famname'],
        "relationship" => $row['relationship']
    ];
    return $response;
}

function getFamilyMember($connect, $fam_id)
{
    $result = $connect->query("SELECT id,famname,relationship FROM `verification_family_info` where id='$fam_id'");
    $row = $result->fetch();
    $fam_name = $row['famname'];
    $relationship = $row['relationship'];
    $response = array("name" => $fam_name, "relationship" => $relationship);
    return $response;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT count(id) as count FROM commitment where 1 ");
    $statement = $query->fetch();
    return $statement['count'];
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
