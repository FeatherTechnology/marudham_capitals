<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$follow_up_sts = '';
$follow_up_date = '';

$Obj = new promotionListClass($connect);
$sub_area_list = $Obj->sub_area_list;
$accessType = $Obj->accessType;

$status = [4 => 'Request', 5 => 'Verification', 6 => 'Approval', 7 => 'Acknowledgement', 8 => 'Request', 9 => 'Verification'];
$sub_status = [4 => 'Cancel', 5 => 'Cancel', 6 => 'Cancel', 7 => 'Cancel', 8 => 'Revoke', 9 => 'Revoke'];
$cusstatus = [21 => 'NOC Pending', 22 => 'NOC Completed', 23 => 'NOC Completed', 24 => 'NOC Handovered'];

$column = array(
    'cp.cus_reg_id',                  
    'cp.cus_id',    
    'cp.autogen_cus_id',          
    'cp.customer_name',            
    'al.area_name',           
    'sl.sub_area_name',       
    'bc.branch_name',         
    'agm.group_name',                   
    'alm.line_name',           
    'cp.mobile1',
    'cp.cus_reg_id',
    'req.cus_status',
    'req.cus_data',
    'req.updated_date',
    'rcs.cus_status',
    'cp.cus_reg_id',
    'cp.cus_reg_id',
    'np.status',
    'np.follow_date',
    'np.followup_type'
);

$areaColumn = ($accessType == 3) 
    ? "cp.area" 
    : "cp.sub_area";

//to get NOC status, join request_creation second times because main table having condition to show only 4 to 9.
$baseqry = "FROM request_creation req 
LEFT JOIN customer_register cp ON req.cus_id = cp.cus_id 
LEFT JOIN (
    SELECT DISTINCT cus_id 
    FROM request_creation 
    WHERE cus_status NOT BETWEEN 4 AND 9 
    AND cus_status < 20 
) rc ON req.cus_id = rc.cus_id 
LEFT JOIN area_list_creation al ON al.area_id = CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_area ELSE cp.area END
LEFT JOIN sub_area_list_creation sl ON sl.sub_area_id = CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_subarea ELSE cp.sub_area END
LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sl.sub_area_id
LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sl.sub_area_id
LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
LEFT JOIN new_promotion np ON np.cus_id = req.cus_id AND np.created_date = (SELECT MAX(np1.created_date) FROM new_promotion np1 WHERE np1.cus_id = req.cus_id)
LEFT JOIN request_creation rcs ON req.cus_id = rcs.cus_id
WHERE req.cus_status BETWEEN 4 AND 9 
AND CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_subarea ELSE $areaColumn END IN  ($sub_area_list) AND rc.cus_id IS NULL ";

if($_POST['followUpSts']){
    $follow_up_sts = $_POST['followUpSts'];
    $baseqry .= ($follow_up_sts =='tofollow') ? "AND np.status IS NULL " : "AND TRIM(REPLACE(np.status,' ','')) = '$follow_up_sts' ";
}

if($_POST['dateType']){
    $date_type = $_POST['dateType'];//1=Closed date, 2=Followup date.
    $baseqry .= ($date_type == '1') ? "AND DATE(req.updated_date) BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' " : "AND DATE(np.follow_date) BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' ";
}     

    $baseqry .= ($_POST['followupType']) ? "AND np.followup_type = '". $_POST['followupType'] ."'" : "";   

$search = '';
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = " AND (cp.cus_id LIKE '%" . $_POST['search'] . "%' 
    OR cp.autogen_cus_id LIKE '%" . $_POST['search'] . "%' 
    OR cp.customer_name LIKE '%" . $_POST['search'] . "%' 
    OR al.area_name LIKE '%" . $_POST['search'] . "%' 
    OR sl.sub_area_name LIKE '%" . $_POST['search'] . "%' 
    OR bc.branch_name LIKE '%" . $_POST['search'] . "%' 
    OR agm.group_name LIKE '%" . $_POST['search'] . "%' 
    OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
    OR cp.mobile1 LIKE '%" . $_POST['search'] . "%' 
    OR np.status LIKE '%" . $_POST['search'] . "%' ) ";
}

$order = '';
if (isset($_POST['order'])) {
    $order = ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

$limit = '';
if ($_POST['length'] != -1) {
    $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$baseqry .= "$search GROUP BY req.cus_id $order ";

// Count query for filtered rows
$num_qry = $connect->query("SELECT COUNT(*) FROM (SELECT req.cus_id  $baseqry) AS sub_qry");
$num_qry->execute();
$number_filter_row = $num_qry->fetchColumn();

// Main query to fetch customers with specific status and filter those without recent loan requests
$sql = $connect->query("SELECT req.req_id, req.cus_data, req.cus_id, cp.autogen_cus_id, cp.customer_name, al.area_name, sl.sub_area_name, bc.branch_name, agm.group_name, alm.line_name, cp.mobile1, req.cus_status AS consider_level, req.updated_date, np.status AS followup_sts, np.follow_date, np.followup_type, rcs.cus_status AS noc_cus_status $baseqry $limit");

$sno = 1;
$data = [];
while ($row = $sql->fetch()) {
    $updateddate = (isset($row['updated_date'])) ? date('d-m-Y', strtotime($row['updated_date'])) : '';

    $charts = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a><a class='customer-sts' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Status</span></a></div></div>";

    //for intrest or not intrest choice to make
    $actions = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='noc-call' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>NOC Call</span></a><a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a><a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a><a class='un-available' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Unavailable</span></a></div></div>";

    $followdate = (isset($row['follow_date'])) ? date('d-m-Y', strtotime($row['follow_date'])) : '';
    
    $followup_type =''; 
    if($row['followup_type'] =='1'){
        $followup_type = 'Direct';  
    }else if($row['followup_type'] =='2'){
        $followup_type = 'Clear';  
    }  

    $data[] = [
        $sno++,
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['customer_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['group_name'],
        $row['line_name'],
        $row['mobile1'],
        $status[$row['consider_level']],
        $sub_status[$row['consider_level']],
        $row['cus_data'],
        $updateddate,
        $cusstatus[$row['noc_cus_status']] ?? '',
        $charts,
        $actions,
        $row['followup_sts'],
        $followdate,
        $followup_type
    ];
}

function count_all_data($connect)
{
    $statement = $connect->prepare("SELECT COUNT(*) FROM (SELECT req.cus_id FROM request_creation req LEFT JOIN customer_profile cp ON req.req_id = cp.req_id WHERE req.cus_status BETWEEN 4 AND 9 GROUP BY req.cus_id) AS sub_qry");
    $statement->execute();
    return (int) $statement->fetchColumn();
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