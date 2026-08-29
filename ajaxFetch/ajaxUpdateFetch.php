<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

include('..\user_based_sub_area_Ids.php');
$sub_area_list = getUserSubAreaList($connect, 'group');

$column = array(
    'rc.req_id',
    'rc.cus_id',
    'cr.autogen_cus_id',
    'cr.customer_name',
    'cr.mobile1',
    'cr.area_confirm_area',
    'rc.req_id',
    'cr.area_group',
    'cr.area_line',
    'rc.req_id',
    'rc.req_id',
);

$params = array();
$con = '';

$doc_sts = isset($_POST["doc_sts"]) ? $_POST["doc_sts"] : '';
if ($doc_sts !== '') {
    $con = " INNER JOIN (
                SELECT cus_id_doc, MAX(req_id) AS last_req_id 
                FROM acknowlegement_documentation 
                WHERE doc_sts = :doc_sts 
                GROUP BY cus_id_doc
            ) latest_doc 
            ON rc.cus_id = latest_doc.cus_id_doc 
            AND rc.req_id = latest_doc.last_req_id AND rc.cus_status >= 13";
    $params[':doc_sts'] = $doc_sts;
}

if ($userid == 1) {
    $query = "SELECT rc.req_id, rc.cus_id, cr.autogen_cus_id, cr.customer_name, cr.mobile1, cr.area_confirm_area AS area, rc.cus_status, rc.cus_data, cr.area_group, cr.area_line 
    FROM request_creation rc
    JOIN customer_register cr ON rc.cus_id = cr.cus_id 
    INNER JOIN (
        SELECT cus_id, MAX(req_id) AS last_req_id 
        FROM request_creation  
        GROUP BY cus_id
    ) latest ON rc.cus_id = latest.cus_id AND rc.req_id = latest.last_req_id $con
    WHERE (rc.cus_data = 'Existing' AND rc.cus_status >= 1) OR (rc.cus_data = 'New' AND rc.cus_status > 13)";

} else {
    // sub_area_list must come pre-validated as a comma list of ints from
    // getUserSubAreaList(); check that function if it ever touches raw input.
    $query = "SELECT rc.req_id, rc.cus_id, cr.autogen_cus_id, cr.customer_name, cr.mobile1, cr.area_confirm_area AS area, rc.cus_status, rc.cus_data, cr.area_group, cr.area_line
    FROM request_creation rc
    JOIN customer_register cr ON rc.cus_id = cr.cus_id 
    INNER JOIN ( SELECT cus_id, MAX(req_id) AS last_req_id FROM request_creation GROUP BY cus_id) latest ON rc.cus_id = latest.cus_id AND rc.req_id = latest.last_req_id $con
    WHERE cr.area_confirm_subarea IN ($sub_area_list) AND ( (rc.cus_data = 'Existing' AND rc.cus_status >= 1) OR (rc.cus_data = 'New' AND rc.cus_status > 13))";
}

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= " AND (rc.cus_id LIKE :search1
        OR cr.autogen_cus_id LIKE :search2
        OR cr.customer_name LIKE :search3
        OR cr.mobile1 LIKE :search4 )  ";
    $searchTerm = '%' . $_POST['search'] . '%';
    $params[':search1'] = $searchTerm;
    $params[':search2'] = $searchTerm;
    $params[':search3'] = $searchTerm;
    $params[':search4'] = $searchTerm;
}

if (isset($_POST['order'])) {
    $colIndex = (int) $_POST['order']['0']['column'];
    $dirInput = strtoupper($_POST['order']['0']['dir']);
    // Whitelist column index + direction to prevent injection via order params.
    if (isset($column[$colIndex])) {
        $orderCol = $column[$colIndex];
        $orderDir = ($dirInput === 'DESC') ? 'DESC' : 'ASC';
        $query .= ' ORDER BY ' . $orderCol . ' ' . $orderDir . ' ';
    }
}

$query1 = '';
$limitParams = array();
if ((int) $_POST['length'] !== -1) {
    $query1 = ' LIMIT :start, :length';
    $limitParams[':start'] = (int) $_POST['start'];
    $limitParams[':length'] = (int) $_POST['length'];
}

// Count query (unchanged shape from your original - no LIMIT)
$statement = $connect->prepare($query);
foreach ($params as $key => $val) {
    $statement->bindValue($key, $val, PDO::PARAM_STR);
}
$statement->execute();
$number_filter_row = $statement->rowCount();

// Data query (with LIMIT)
$statement = $connect->prepare($query . $query1);
foreach ($params as $key => $val) {
    $statement->bindValue($key, $val, PDO::PARAM_STR);
}
foreach ($limitParams as $key => $val) {
    $statement->bindValue($key, $val, PDO::PARAM_INT);
}
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;

// Simple in-memory caches so repeated area_ids / cus_ids on the same page
// don't hit the DB twice - same logic as before, just deduplicated.
$areaCache = array();
$branchCache = array();
$docStatusCache = array();

foreach ($result as $row) {

    $sub_array = array();

    $sub_array[] = $sno;
    $cus_id = $row['cus_id'];
    $sub_array[] = $cus_id;
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['customer_name'];
    $sub_array[] = $row['mobile1'];

    $areaId = $row['area'];

    if (!array_key_exists($areaId, $areaCache)) {
        $areaStmt = $connect->prepare("SELECT area_name FROM area_list_creation WHERE area_id = :area_id");
        $areaStmt->bindValue(':area_id', $areaId, PDO::PARAM_STR);
        $areaStmt->execute();
        $areaCache[$areaId] = $areaStmt->fetch()['area_name'] ?? '';
    }
    $sub_array[] = $areaCache[$areaId];

    if (!array_key_exists($areaId, $branchCache)) {
        $branchStmt = $connect->prepare("SELECT bc.branch_name FROM area_group_mapping_area agma 
        JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
        JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
        WHERE agma.area_id = :area_id");
        $branchStmt->bindValue(':area_id', $areaId, PDO::PARAM_STR);
        $branchStmt->execute();
        $branchCache[$areaId] = $branchStmt->fetch()['branch_name'] ?? '';
    }
    $sub_array[] = $branchCache[$areaId];

    $sub_array[] = $row['area_group'];
    $sub_array[] = $row['area_line'];

    if (!array_key_exists($cus_id, $docStatusCache)) {
        $docStatusCache[$cus_id] = getDocumentStatus($connect, $cus_id);
    }
    $sub_array[] = $docStatusCache[$cus_id] ? 'Document Completed' : 'Document Pending';

    if ($doc_sts !== '') {
        $action = "<a href='update&upd=" . htmlspecialchars($cus_id) . "&docstatus=NO' title='Update'> <span class='icon-border_color' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    } else {
        $action = "<a href='update&upd=" . htmlspecialchars($cus_id) . "' title='Update'> <span class='icon-border_color' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";
    }

    $sub_array[] = $action;
    $data[] = $sub_array;
    $sno = $sno + 1;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function getDocumentStatus($connect, $cus_id)
{
    $qry = $connect->prepare("
        SELECT a.doc_sts 
        FROM acknowlegement_documentation a
        JOIN request_creation r ON a.req_id = r.req_id
        WHERE a.cus_id_doc = :cus_id AND r.cus_status > 13 AND a.doc_sts = 'NO'
    ");
    $qry->bindValue(':cus_id', $cus_id, PDO::PARAM_STR);
    $qry->execute();

    if ($qry->rowCount() > 0) {
        return false; // pending
    }

    return true; // completed
}

// Close the database connection
$connect = null;