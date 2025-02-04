<?php
include '../ajaxconfig.php';

$cus_id = $_POST['cus_id'] ?? '';
$cus_name = $_POST['cus_name'] ?? '';
$area = $_POST['area'] ?? '';
$sub_area = $_POST['sub_area'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$loan_id = $_POST['loan_id'] ?? '';

$statusLabels = [
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
$fam_sql = '';

if ($cus_id != '') {
    $sql = "SELECT cus_id from customer_register WHERE cus_id LIKE '%$cus_id%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE relation_aadhar LIKE '%$cus_id%' ";
} else if ($cus_name != '') {
    $sql = "SELECT cus_id from customer_register WHERE customer_name LIKE '%$cus_name%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE famname LIKE '%$cus_name%' ";
} else if ($mobile != '') {
    $sql = "SELECT cus_id from customer_register WHERE mobile1 LIKE '%$mobile%' or mobile2 LIKE '%$mobile%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE relation_Mobile LIKE '%$mobile%' ";
} else if ($area != '') {
    $sql = "SELECT cr.cus_id from area_list_creation ac 
        JOIN customer_register cr ON 
        CASE 
        WHEN (cr.area_confirm_area IS NOT NULL OR cr.area_confirm_area != '') THEN ac.area_id = cr.area_confirm_area 
        ELSE ac.area_id = cr.area 
        END
        WHERE ac.area_name LIKE '%$area%' GROUP BY cr.cus_id ";
} else if ($sub_area != '') {
    $sql = "SELECT cr.cus_id from sub_area_list_creation sac 
        JOIN customer_register cr ON 
        CASE 
        WHEN (cr.area_confirm_subarea IS NOT NULL OR cr.area_confirm_subarea != '') THEN sac.sub_area_id = cr.area_confirm_subarea 
        ELSE sac.sub_area_id = cr.sub_area
        END
        WHERE sac.sub_area_name LIKE '%$sub_area%' GROUP BY cr.cus_id ";
} else if ($loan_id != '') {
    $sql = "SELECT cus_id from in_issue where loan_id = '$loan_id' ";
}

// echo $sql;
$runSql = $connect->query($sql);
if ($runSql->rowCount() > 0) {
    while ($row = $runSql->fetch())
        $cus_id_fetched[] = $row['cus_id'];
} else {
    $cus_id_fetched = [];
}

if (!empty($cus_id_fetched)) {
    foreach ($cus_id_fetched as $cus_id) {
        $sql = $connect->query("SELECT req_id,cus_id,cus_status From request_creation where cus_id = $cus_id ORDER BY req_id DESC LIMIT 1 ");
        $row = $sql->fetch();
        $req_id[] = $row['req_id'];
        $cus_status[] = $row['cus_status'];
    }
}
$i = 1;
$x = 0;
$data = array();
if (!empty($req_id)) {
    foreach ($req_id as $req) {
        if ($cus_status[$x] == '0' || $cus_status[$x] == '1' || $cus_status[$x] == '4' || $cus_status[$x] == '5' || $cus_status[$x] == '8' || $cus_status[$x] == '9') {
            $req_sql = $connect->query("SELECT req.cus_id,req.cus_name,ac.area_name,sac.sub_area_name,bc.branch_name,alm.line_name,agm.group_name,req.mobile1,req.mobile2 
                        From request_creation req 
                        LEFT JOIN area_list_creation ac ON req.area = ac.area_id 
                        LEFT JOIN sub_area_list_creation sac ON req.sub_area = sac.sub_area_id 
                        LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sac.sub_area_id,alm.sub_area_id)
                        LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sac.sub_area_id,agm.sub_area_id)
                        LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
                        where req.req_id = $req ");
        } else {
            $req_sql = $connect->query("SELECT cp.cus_id,cp.cus_name,ac.area_name,sac.sub_area_name,bc.branch_name,alm.line_name,agm.group_name,cp.mobile1,cp.mobile2 
                    FROM customer_profile cp
                    LEFT JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id 
                    LEFT JOIN sub_area_list_creation sac ON cp.area_confirm_subarea = sac.sub_area_id 
                    LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sac.sub_area_id,alm.sub_area_id)
                    LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sac.sub_area_id,agm.sub_area_id)
                    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
                    WHERE cp.req_id = $req  ");
        }
        $x++;
        while ($req_row = $req_sql->fetch()) {
            $sub_array = array();
            $sub_array['sno'] = $i++;
            $sub_array['cus_id'] = $req_row['cus_id'];
            $sub_array['cus_name'] = $req_row['cus_name'];
            $sub_array['area'] = $req_row['area_name'];
            $sub_array['sub_area'] = $req_row['sub_area_name'];
            $sub_array['branch'] = $req_row['branch_name'];
            $sub_array['line'] = $req_row['line_name'];
            $sub_array['group'] = $req_row['group_name'];
            $sub_array['mobile1'] = $req_row['mobile1'];
            $sub_array['mobile2'] = $req_row['mobile2'];
            $action = '<input type="button" class="view_cust btn btn-primary" value="View" data-toggle="modal" data-target="#customerStatusModal" data-cusid=' . $req_row['cus_id'] . '>';
            $sub_array['action'] = $action;

            $data['customer_data'][] = $sub_array;
        }
    }
}


//for family data fetching
if ($fam_sql != '') {

    $runSql = $connect->query($fam_sql);
    $fam_id_arr = [];
    if ($runSql->rowCount() > 0) {
        while ($row = $runSql->fetch()) {
            $fam_id_arr[] = $row['id'];
        }
    }

    if (!empty($fam_id_arr)) {
        $i = 1;
        foreach ($fam_id_arr as $id) {
            $qry = $connect->query("SELECT fam.cus_id,cr.customer_name,fam.famname,fam.relationship,fam.relation_aadhar,fam.relation_Mobile FROM verification_family_info fam JOIN customer_register cr ON fam.cus_id = cr.cus_id WHERE fam.id = '$id' ");
            while ($row = $qry->fetch()) {
                $sub_array = array();
                $sub_array['sno'] = $i++;
                $sub_array['name'] = $row['famname'];
                $sub_array['relationship'] = $row['relationship'];
                $sub_array['adhaar'] = $row['relation_aadhar'];
                $sub_array['mobile'] = $row['relation_Mobile'];
                $sub_array['under_cus'] = $row['customer_name'];
                $sub_array['under_cus_id'] = $row['cus_id'];

                $data['family_data'][] = $sub_array;
            }
        }
    }
}


echo json_encode($data);

// Close the database connection
$connect = null;