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


$column = array(
    'cp.id',
    'cp.cus_id',
    'cp.cus_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'al.line_name',
    'cp.mobile1',
    'cp.id'
);

if ($userid == 1) {
    $query = 'SELECT cp.cus_id as cp_cus_id,cp.cus_name,ac.area_name, sa.sub_area_name, al.line_name,bc.branch_name,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id, 0 as response 
    FROM acknowlegement_customer_profile cp 
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    where ii.status = 0 and ii.cus_status = 21 GROUP BY ii.cus_id '; // Only Issued and all lines not relying on sub area
} else {
    $query = " SELECT cp.cus_id AS cp_cus_id,
    cp.cus_name,
    ac.area_name,
    sa.sub_area_name,
    al.line_name,
    bc.branch_name,
    cp.mobile1,
    ii.cus_id AS ii_cus_id,
    ii.req_id,
    COALESCE(sd_count, 0) +
    COALESCE(cnl_count, 0) +
    COALESCE(ackd_count, 0) +
    COALESCE(ackd_endorse_count, 0) +
    COALESCE(gi_count, 0) +
    COALESCE(di_count, 0) AS response
    FROM acknowlegement_customer_profile cp
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    LEFT JOIN (
        SELECT ii.cus_id, COUNT(sd.id) AS sd_count
        FROM signed_doc_info sd
        JOIN in_issue ii ON ii.req_id = sd.req_id
        WHERE ii.cus_status = 21 AND sd.noc_given != '1'
        GROUP BY ii.cus_id
    ) AS sd_table ON ii.cus_id = sd_table.cus_id
    LEFT JOIN (
        SELECT ii.cus_id, COUNT(cnl.id) AS cnl_count
        FROM cheque_no_list cnl
        JOIN in_issue ii ON ii.req_id = cnl.req_id
        WHERE ii.cus_status = 21 AND cnl.noc_given != '1'
        GROUP BY ii.cus_id
    ) AS cnl_table ON ii.cus_id = cnl_table.cus_id
    LEFT JOIN (
        SELECT ii.cus_id, COUNT(ackd.id) AS ackd_count
        FROM acknowlegement_documentation ackd
        JOIN in_issue ii ON ii.req_id = ackd.req_id
        WHERE ii.cus_status = 21
            AND ackd.mortgage_process = 0
            AND (ackd.mortgage_process_noc != '1' 
                OR (ackd.mortgage_document = 0 
                    AND ackd.mortgage_document_upd IS NOT NULL 
                    AND ackd.mortgage_document_noc != '1'))
        GROUP BY ii.cus_id
    ) AS ackd_table ON ii.cus_id = ackd_table.cus_id
    LEFT JOIN (
        SELECT ii.cus_id, COUNT(ackd.id) AS ackd_endorse_count
        FROM acknowlegement_documentation ackd
        JOIN in_issue ii ON ii.req_id = ackd.req_id
        WHERE ii.cus_status = 21
            AND ackd.endorsement_process = 0
            AND (ackd.endorsement_process_noc != '1'
                OR (ackd.en_RC = 0 AND ackd.en_RC_noc != '1')
                OR (ackd.en_Key = 0 AND ackd.en_Key_noc != '1'))
        GROUP BY ii.cus_id
    ) AS ackd_endorse_table ON ii.cus_id = ackd_endorse_table.cus_id
    LEFT JOIN (
        SELECT ii.cus_id, COUNT(gi.id) AS gi_count
        FROM gold_info gi
        JOIN in_issue ii ON ii.req_id = gi.req_id
        WHERE ii.cus_status = 21 AND gi.noc_given != '1'
        GROUP BY ii.cus_id
    ) AS gi_table ON ii.cus_id = gi_table.cus_id
    LEFT JOIN (
        SELECT ii.cus_id, COUNT(di.id) AS di_count
        FROM document_info di
        JOIN in_issue ii ON ii.req_id = di.req_id
        WHERE ii.cus_status = 21 AND di.doc_info_upload_noc != '1'
        GROUP BY ii.cus_id
    ) AS di_table ON ii.cus_id = di_table.cus_id
    WHERE ii.status = 0
        AND ii.cus_status = 21
        AND cp.area_confirm_subarea IN ($sub_area_list) ";

    $forcount = "SELECT cp.cus_id 
        FROM acknowlegement_customer_profile cp 
        JOIN in_issue ii ON cp.cus_id = ii.cus_id
        JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
        JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
        JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
        JOIN branch_creation bc ON al.branch_id = bc.branch_id
        where ii.status = 0 and ii.cus_status = 21 and cp.area_confirm_subarea IN ($sub_area_list) ";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $search = " AND (cp.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR al.line_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
    $query .= $search;
    $forcount .= $search;
}

$query .= 'GROUP BY ii.cus_id ';
$forcount .= 'GROUP BY ii.cus_id ';

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
    $forcount .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
    $forcount .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($forcount);

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

    $sub_array[] = $row['cp_cus_id'];
    $sub_array[] = $row['cus_name'];

    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];

    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cp_cus_id'];
    $id = $row['req_id'];

    $docToIssue = $row['response'];

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    $action .= "<a href='noc&upd=$id&cusidupd=$cus_id' title='Edit details' >NOC</a>";
    if ($docToIssue == 0) {
        //if this variable contains value 0 then all document are given to customer as noc
        $action .= "<a href='' title='Remove details' class='remove-noc' data-reqid='$id' data-cusid='$cus_id' >Remove</a>";
        $action .= "<a href='' title='NOC Letter' class='noc-letter' data-reqid='$id' data-cusid='$cus_id' >NOC Letter</a>";
    }

    $action .= "</div></div>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cp.cus_id as cp_cus_id,cp.cus_name,cp.area_confirm_area,cp.area_confirm_subarea,cp.area_line,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id FROM 
    acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id
    where ii.status = 0 and ii.cus_status = 21 GROUP BY ii.cus_id ";
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

// Close the database connection
$connect = null;