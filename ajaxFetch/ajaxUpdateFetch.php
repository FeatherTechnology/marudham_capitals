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
    }
    $group_id = explode(',', $group_id);
    $sub_area_list = array();
    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT * FROM area_group_mapping where map_id = $group ");
        $row_sub = $groupQry->fetch();
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
    'rc.req_id',
    'rc.cus_id',
    'cr.autogen_cus_id',
    'rc.cus_name',
    'rc.mobile1',
    'cr.area_confirm_area',
    'rc.req_id',
    'cr.area_group',
    'cr.area_line',
    'rc.req_id',
    'rc.req_id',
);
$con = '';

if ($_POST["doc_sts"] != '') {
    $doc_sts = $_POST["doc_sts"];

    // Use different alias: latest_doc
    $con = " INNER JOIN (
                SELECT cus_id_doc, MAX(req_id) AS last_req_id 
                FROM acknowlegement_documentation 
                WHERE doc_sts = '$doc_sts' 
                GROUP BY cus_id_doc
            ) latest_doc 
            ON rc.cus_id = latest_doc.cus_id_doc 
            AND rc.req_id = latest_doc.last_req_id ";
}
if ($userid == 1) {
    $query = "SELECT rc.req_id, rc.cus_id, cr.autogen_cus_id, rc.cus_name, rc.mobile1, cr.area_confirm_area AS area, rc.cus_status, rc.cus_data, cr.area_group, cr.area_line 
    FROM request_creation rc
    JOIN customer_register cr ON rc.cus_id = cr.cus_id 
    INNER JOIN (
        SELECT cus_id, MAX(req_id) AS last_req_id 
        FROM request_creation  
        GROUP BY cus_id
    ) latest ON rc.cus_id = latest.cus_id AND rc.req_id = latest.last_req_id $con
    WHERE (rc.cus_data = 'Existing' AND rc.cus_status >= 1) OR (rc.cus_data = 'New' AND rc.cus_status > 13)";

} else {
    $query = "SELECT rc.req_id, rc.cus_id, cr.autogen_cus_id, rc.cus_name, rc.mobile1, cr.area_confirm_area AS area, rc.cus_status, rc.cus_data, cr.area_group, cr.area_line
    FROM request_creation rc
    JOIN customer_register cr ON rc.cus_id = cr.cus_id 
    INNER JOIN ( SELECT cus_id, MAX(req_id) AS last_req_id FROM request_creation GROUP BY cus_id) latest ON rc.cus_id = latest.cus_id AND rc.req_id = latest.last_req_id $con
    WHERE rc.sub_area IN ($sub_area_list) AND ( (rc.cus_data = 'Existing' AND rc.cus_status >= 1) OR (rc.cus_data = 'New' AND rc.cus_status > 13))";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "
        AND (rc.cus_id LIKE '%" . $_POST['search'] . "%'
        OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
        OR rc.cus_name LIKE '%" . $_POST['search'] . "%'
        OR rc.mobile1 LIKE '%" . $_POST['search'] . "%' )  ";
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} 

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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
    $cus_id     = $row['cus_id'];
    $sub_array[] = $cus_id;
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['mobile1'];

    $areaqry = $connect->query(" SELECT area_name FROM area_list_creation WHERE area_id = '". $row ['area'] ."'");
    $sub_array[] = $areaqry->fetch()['area_name'] ?? '';

    $branchqry = $connect->query("SELECT bc.branch_name FROM area_group_mapping agm JOIN branch_creation bc ON agm.branch_id = bc.branch_id WHERE FIND_IN_SET('" . $row['area'] . "' , agm.area_id) ");
    $sub_array[] = $branchqry->fetch()['branch_name'] ?? '';

    $sub_array[] = $row['area_group'];
    $sub_array[] = $row['area_line'];

    if (getDocumentStatus($connect, $cus_id) == false) {
        $sub_array[] = 'Document Pending';
    } else {
        $sub_array[] = 'Document Completed';
    }

    $id          = $row['cus_id'];
    $cus_id      = $row['cus_id'];
    if($_POST["doc_sts"]!=''){
         $action = "<a href='update&upd=$id&docstatus=NO' title='Update'> <span class='icon-border_color' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    }else{
         $action = "<a href='update&upd=$id' title='Update'> <span class='icon-border_color' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    }

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cus_reg_id FROM customer_register";
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

function getDocumentStatus($connect, $cus_id)
{

    $status = 'completed';

    $sts_qry = $connect->query("SELECT doc_sts FROM acknowlegement_documentation where cus_id_doc = '$cus_id' ");

    if ($sts_qry->rowCount() > 0) {
        while ($sts_row = $sts_qry->fetch()) { //check any one of document for mortgage or endorsement is pending then response will be pending

            if ($sts_row['doc_sts'] == 'NO') {
               
                    $status = 'pending';
                }
            
        }
    }

    if ($status == 'completed') {
        $response = true;
    } else {
        $response = false;
    }

    return $response;
}

// Close the database connection
$connect = null;
?>