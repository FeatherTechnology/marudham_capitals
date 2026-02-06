<?php
include('..\ajaxconfig.php');

    $query = "SELECT * FROM customer_register  WHERE DATE_FORMAT(`dob`,'%m-%d') = DATE_FORMAT(CURRENT_DATE(),'%m-%d') ";


if(isset($_POST['search']) && $_POST['search'] != "")
{

    $query .= "
        and (cus_id LIKE '%".$_POST['search']."%'
        OR customer_name LIKE '%".$_POST['search']."%'
        OR area_group LIKE '%".$_POST['search']."%'
        OR area_line LIKE '%".$_POST['search']."%'
        OR mobile1 LIKE '%".$_POST['search']."%' ) ";
}

$query .= " GROUP BY cus_id ";

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
    $cus_id = $row['cus_id'];
    $sub_array[] = $cus_id;
    $sub_array[] = '<input type="text" class="cusName" name="cus_name[]" value="'.$row['customer_name'].'" style="border: none; outline: 0; background: inherit;" readonly>';
    $sub_array[] = '<input type="text" name="cus_mobileno[]" value="'.$row['mobile1'].'" style="border: none; outline: 0; background: inherit;" readonly>';
    
    $areaqry = $connect->query("SELECT CASE 
    WHEN ( SELECT COUNT(*) FROM customer_profile WHERE cus_id = $cus_id ) > 0 
    THEN ( SELECT area_name FROM area_list_creation WHERE area_id = ( SELECT area_confirm_area FROM customer_profile WHERE cus_id = $cus_id ORDER BY `id` DESC LIMIT 1 ) ) 
    ELSE ( SELECT area_name FROM area_list_creation WHERE area_id = ( SELECT `area` FROM request_creation WHERE cus_id = $cus_id ORDER BY `req_id` DESC LIMIT 1 ) ) END AS `area_name`
    ");
    $sub_array[] = $areaqry->fetch()['area_name'];

    $branchqry = $connect->query("SELECT bc.branch_name FROM area_group_mapping_area agma
    JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id 
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id where agma.area_id = $row[area] ");
    $sub_array[] = $branchqry->fetch()['branch_name'];
    
    $lineqry = $connect->query("SELECT CASE WHEN ( SELECT COUNT(*) FROM customer_profile WHERE cus_id = $cus_id ) > 0 
    THEN ( SELECT alm.line_name FROM area_line_mapping alm 
        JOIN area_line_mapping_sub_area almsa ON almsa.line_map_id = alm.map_id 
        WHERE almsa.sub_area_id = ( SELECT area_confirm_subarea FROM customer_profile WHERE cus_id = $cus_id ORDER BY `id` DESC LIMIT 1 ) ) 
    ELSE ( SELECT alm.line_name FROM area_line_mapping alm 
        JOIN area_line_mapping_sub_area almsa ON almsa.line_map_id = alm.map_id
        WHERE almsa.sub_area_id = ( SELECT sub_area FROM request_creation WHERE cus_id = $cus_id ORDER BY `req_id` DESC LIMIT 1 ) )
    END AS `line_name`");
    $sub_array[] = $lineqry->fetch()['line_name'];
    
    $grpqry = $connect->query("SELECT CASE WHEN ( SELECT COUNT(*) FROM customer_profile WHERE cus_id = $cus_id ) > 0 
    THEN ( SELECT agm.group_name FROM area_group_mapping_sub_area agmsa 
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id 
        WHERE agmsa.sub_area_id = ( SELECT area_confirm_subarea FROM customer_profile WHERE cus_id = $cus_id ORDER BY `id` DESC LIMIT 1 ) ) 
    ELSE ( SELECT agm.group_name FROM area_group_mapping_sub_area agmsa 
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id 
        WHERE agmsa.sub_area_id = ( SELECT sub_area FROM request_creation WHERE cus_id = $cus_id ORDER BY `req_id` DESC LIMIT 1 ) )
    END AS `group_name`");
    $sub_array[] = $grpqry->fetch()['group_name'];

    // $action = "<a href='' title='Update'>  <span class='icon-mail'></span> </a>";
    
    // $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno+1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM customer_register";
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
