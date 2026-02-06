<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

include('..\user_based_sub_area_Ids.php');
$sub_area_list = getUserSubAreaList($connect, 'update');

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
            AND rc.req_id = latest_doc.last_req_id AND rc.cus_status >= 13";
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

    $branchqry = $connect->query("SELECT bc.branch_name FROM area_group_mapping_area agma 
    JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
    WHERE agma.area_id = '". $row ['area'] ."'");
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
    // Get latest req_id with cus_status > 13
    $qry = $connect->query("
        SELECT a.doc_sts 
        FROM acknowlegement_documentation a
        JOIN request_creation r ON a.req_id = r.req_id
        WHERE a.cus_id_doc = '$cus_id' AND r.cus_status > 13
        ORDER BY r.req_id DESC
        LIMIT 1
    ");

    if ($qry->rowCount() == 0) {
        // No valid entry → treat as pending
        return false;
    }

    $row = $qry->fetch();

    if ($row['doc_sts'] == 'NO') {
        return false; // pending
    }

    return true; // completed
}


// Close the database connection
$connect = null;
?>