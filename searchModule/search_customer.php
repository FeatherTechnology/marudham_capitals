<?php
include '../ajaxconfig.php';

$cus_name = $_POST['cus_name'] ?? '';
$area = $_POST['area'] ?? '';
$sub_area = $_POST['sub_area'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$loan_id = $_POST['loan_id'] ?? '';
$cus_id = $_POST['cus_id'] ?? '';
$autogen_cus_id = $_POST['autogen_cus_id'] ?? '';
$fingerprint_person_id = $_POST['fingerprint_person_id'] ?? '';

$cusid = (!empty($cus_id)) ? $cus_id : $fingerprint_person_id;

$sql = '';
$fam_sql = '';

if ($cusid != '') {
    $sql = "SELECT cus_id from customer_register WHERE cus_id LIKE '%$cusid%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE relation_aadhar LIKE '%$cusid%' ";

} else if ($autogen_cus_id != '') {
    $sql = "SELECT cus_id from customer_register WHERE autogen_cus_id LIKE '%$autogen_cus_id%' ";
    $fam_sql = "SELECT id from verification_family_info vfi JOIN customer_register cr ON vfi.relation_aadhar = cr.cus_id WHERE cr.autogen_cus_id LIKE '%$autogen_cus_id%' ";

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

$runSql = $connect->query($sql);
$i = 1;
$data = array();

if ($runSql->rowCount() > 0) {
    while ($row = $runSql->fetch()){
        $req_sql = $connect->query("SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, ac.area_name, sac.sub_area_name, bc.branch_name, alm.line_name, agm.group_name, cr.mobile1, cr.mobile2 
                    FROM customer_register cr 
                    LEFT JOIN area_list_creation ac ON ac.area_id = COALESCE(NULLIF(cr.area_confirm_area, ''), cr.area) 
                    LEFT JOIN sub_area_list_creation sac ON  sac.sub_area_id = COALESCE(NULLIF(cr.area_confirm_subarea, ''), cr.sub_area)
                    LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sac.sub_area_id
                    LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
                    LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sac.sub_area_id
                    LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id  
                    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
                    WHERE cr.cus_id = '".$row['cus_id'] . "'");

        while ($req_row = $req_sql->fetch()) {
            $sub_array = array();
            $sub_array['sno'] = $i++;
            $sub_array['cus_id'] = $req_row['cus_id'];
            $sub_array['autogen_cus_id'] = $req_row['autogen_cus_id'];
            $sub_array['cus_name'] = $req_row['customer_name'];
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
            $qry = $connect->query("SELECT fam.cus_id, cr.customer_name, fam.famname, fam.relationship, fam.relation_aadhar, fam.relation_Mobile, cr.autogen_cus_id FROM verification_family_info fam JOIN customer_register cr ON fam.cus_id = cr.cus_id WHERE fam.id = '$id' ");
            while ($row = $qry->fetch()) {
                $sub_array = array();
                $sub_array['sno'] = $i++;
                $sub_array['name'] = $row['famname'];
                $sub_array['relationship'] = $row['relationship'];
                $sub_array['adhaar'] = $row['relation_aadhar'];
                $sub_array['mobile'] = $row['relation_Mobile'];
                $sub_array['under_cus'] = $row['customer_name'];
                $sub_array['under_cus_id'] = $row['cus_id'];
                $sub_array['under_autogen_cus_id'] = $row['autogen_cus_id'];

                $data['family_data'][] = $sub_array;
            }
        }
    }
}

echo json_encode($data);

// Close the database connection
$connect = null;