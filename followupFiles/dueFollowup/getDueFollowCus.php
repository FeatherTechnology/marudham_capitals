<?php
@session_start();
include('../../ajaxconfig.php');
include_once('../../api/config-file.php');

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}

$sub_status_mapping = '';
if (isset($_POST['cus_sts'])) {
    $sub_status_mapping = implode(',', $_POST['cus_sts']);
}

if ($user_id != 1) {

    $userQry = $connect->query("SELECT group_id, line_id FROM USER WHERE user_id = $user_id ");
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

$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search'];

$data = [];

$columns = ['cp.id', 'cp.cus_id', 'cp.cus_name', 'alc.area_name', 'salc.sub_area_name', 'cp.id', 'cp.area_line', 'cp.mobile1', 'cs.sub_status', 'cp.id', 'cp.id', 'cp.id', 'cp.id', 'cp.id'];

$orderDir = $_POST['order'][0]['dir'];
$order = $columns[$_POST['order'][0]['column']] ? "ORDER BY " . $columns[$_POST['order'][0]['column']] . " $orderDir" : "";
$search = $searchValue != '' ? "and (ii.cus_id LIKE '$searchValue%' or cp.cus_name LIKE '%$searchValue%' or alc.area_name LIKE '%$searchValue%' or salc.sub_area_name LIKE '%$searchValue%' or cp.mobile1 LIKE '$searchValue%' or cs.sub_status LIKE '%$searchValue%' )" : '';

$query = "SELECT
        cp.cus_id AS cp_cus_id,
        cp.cus_name,
        alc.area_name,
        salc.sub_area_name,
        cp.area_line,
        cp.mobile1,
        ii.cus_id AS ii_cus_id,
        ii.req_id,
        cs.sub_status
    FROM
        acknowlegement_customer_profile cp
    JOIN in_issue ii ON
        cp.cus_id = ii.cus_id
    JOIN customer_status cs ON
        cp.cus_id = cs.cus_id
    JOIN area_list_creation alc ON
        cp.area_confirm_area = alc.area_id
    JOIN sub_area_list_creation salc ON
        cp.area_confirm_subarea = salc.sub_area_id
    JOIN area_line_mapping alm ON
        cp.area_line = alm.line_name 
    WHERE cs.payable_amnt > 0 
        AND FIND_IN_SET(cs.sub_status,'$sub_status_mapping') 
        AND ii.status = 0 
        AND (ii.cus_status >= 14 AND ii.cus_status <= 17) $search 
        AND cp.area_confirm_subarea IN ($sub_area_list) 
        GROUP BY ii.cus_id, cs.cus_id $order LIMIT $start , $length"; // 14 and 17 means collection entries, 17 removed from issue list

//this will only take selected req_ids which is payable > 0
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$sno = 1;
foreach ($result as $row) {
    $cus_id = $row['cp_cus_id'];
    $cus_name = $row['cus_name'];
    $area_name = $row['area_name'];
    $sub_area_name = $row['sub_area_name'];
    $branch_name = '';
    $comm_date = '';
    $hint = '';
    $comm_err = '';

    // Fetch branch name
    $line_name = $row['area_line'];
    $qry = $connect->query("SELECT b.branch_name FROM branch_creation b JOIN area_line_mapping l ON l.branch_id = b.branch_id WHERE l.line_name = '$line_name'");
    if ($qry->rowCount() > 0) {
        $row1 = $qry->fetch();
        $branch_name = $row1['branch_name'];
    }

    // Fetch collection date range
    $collDate = $connect->query("SELECT CASE WHEN DAYOFMONTH(coll_date) BETWEEN 26 AND 31 THEN '26-30' WHEN DAYOFMONTH(coll_date) BETWEEN 21 AND 25 THEN '21-25' WHEN DAYOFMONTH(coll_date) BETWEEN 16 AND 20 THEN '16-20' WHEN DAYOFMONTH(coll_date) BETWEEN 11 AND 15 THEN '11-15' ELSE '1-10' END AS date_range FROM collection WHERE cus_id='$cus_id' ORDER BY coll_id DESC LIMIT 1");
    $coll_date_qry = $collDate->fetch();
    $date_range = isset($coll_date_qry['date_range']) ? $coll_date_qry['date_range'] : '';

    // Fetch commitment details
    $sql = $connect->query("SELECT comm_date, hint, comm_err FROM commitment WHERE cus_id='$cus_id' ORDER BY id DESC LIMIT 1");
    if ($sql->rowCount() > 0) {
        $row1 = $sql->fetch();
        $hint = $row1['hint'];
        $comm_err = ($row1['comm_err'] == '1') ? 'Error' : (($row1['comm_err'] == '2') ? 'Clear' : '');
        $comm_date = ($row1['comm_date'] != '0000-00-00') ? date('d-m-Y', strtotime($row1['comm_date'])) : '';
    }

    $data[] = [
        $finalData['sno'] = $sno,
        $finalData['cus_id'] = $cus_id,
        $finalData['cus_name'] = $cus_name,
        $finalData['area_name'] = $area_name,
        $finalData['sub_area_name'] = $sub_area_name,
        $finalData['branch_name'] = $branch_name,
        $finalData['line'] = $row['area_line'],
        $finalData['mobile'] = $row['mobile1'],
        $finalData['sub_status'] = $row['sub_status'],
        $finalData['action'] = "<a href='due_followup&upd={$row['req_id']}&cusidupd=$cus_id&cussts=$sub_status_mapping' title='Edit details'><button class='btn btn-success' style='background-color:#009688;'>View Loans</button></a>",
        $finalData['last_paid_date'] = $date_range,
        $finalData['hint'] = $hint,
        $finalData['comm_err'] = $comm_err,
        $finalData['comm_dat'] = $comm_date
    ];
    $sno++;
}

// Step 3: Return the data in JSON format
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => getTotalRecords($connect),
    "recordsFiltered" => getFilteredRecords($connect, $data, $searchValue, $sub_area_list, $sub_status_mapping),
    "data" => $data
]);

function getTotalRecords($connect)
{
    // Your database query to get the total number of records
    // For example:
    // $query = "SELECT COUNT(*) FROM customers";
    // Execute the query and return the result
    $query = $connect->query("SELECT COUNT(*) as total FROM (SELECT cp.cus_id as cp_cus_id FROM acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id where ii.status = 0 and (ii.cus_status >= 14 and ii.cus_status <= 17) GROUP BY ii.cus_id) as subquery ");
    $totals = $query->fetch()['total'];
    return $totals;
}

function getFilteredRecords($connect, $data, $searchValue, $sub_area_list, $sub_status_mapping)
{
    // Your database query to get the total number of filtered records
    // For example:
    // $query = "SELECT COUNT(*) FROM customers WHERE ... LIKE '%$searchValue%'";
    // Execute the query and return the result
    $search = $searchValue != '' ? "and (ii.cus_id LIKE '$searchValue%' or cp.cus_name LIKE '%$searchValue%' or alc.area_name LIKE '%$searchValue%' or salc.sub_area_name LIKE '%$searchValue%' or cp.mobile1 LIKE '$searchValue%' or cs.sub_status LIKE '%$searchValue%' )" : '';
    if (count($data) > 0) {
        // $query = $connect->query("SELECT COUNT(*) as total FROM (SELECT cp.cus_id as cp_cus_id FROM acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id where ii.status = 0 and (ii.cus_status >= 14 and ii.cus_status <= 17) $search and cp.area_confirm_subarea IN ($sub_area_list) GROUP BY ii.cus_id) as subquery ");
        $query = $connect->query(" SELECT COUNT(*) as total FROM ( SELECT cp.cus_id
        FROM
            acknowlegement_customer_profile cp
        JOIN in_issue ii ON
            cp.cus_id = ii.cus_id
        JOIN customer_status cs ON
            cp.cus_id = cs.cus_id
        JOIN area_list_creation alc ON
            cp.area_confirm_area = alc.area_id
        JOIN sub_area_list_creation salc ON
            cp.area_confirm_subarea = salc.sub_area_id
        JOIN area_line_mapping alm ON
            cp.area_line = alm.line_name 
        WHERE cs.payable_amnt > 0 
            AND FIND_IN_SET(cs.sub_status,'$sub_status_mapping') 
            AND ii.status = 0 
            AND (ii.cus_status >= 14 AND ii.cus_status <= 17) $search 
            AND cp.area_confirm_subarea IN ($sub_area_list) 
            GROUP BY ii.cus_id, cs.cus_id ) as subquery");

        $total = $query->fetch()['total'];

        return $total;
    } else {
        return 0;
    }
}

// Close the database connection
$connect = null;
