<?php 
include('../ajaxconfig.php');
if (isset($_POST['area'])) {
    $area = $_POST['area'];
}
if (isset($_POST['map'])) {
    $map = $_POST['map'];
}
$area_array = array_map('intval',explode(',',$area));

$records = array();
$j=0;
foreach($area_array as $area1){
    $i=0;
    $result=$connect->query("SELECT * FROM sub_area_list_creation where area_id_ref='".$area1."' and status=0");
    if($result->rowCount() > 0){
        while( $row = $result->fetch()){
            $sub_area_id = $row['sub_area_id'];
            $sub_area_name = $row['sub_area_name'];
            $records[$j][$i]['sub_area_id'] = $row['sub_area_id'];
            $records[$j][$i]['sub_area_name'] = $row['sub_area_name'];
        
            if($map == 'line'){
                $checkQry=$connect->query("SELECT * FROM area_line_mapping where status=0 and FIND_IN_SET($sub_area_id,sub_area_id) ");
                if ($checkQry->rowCount()>0){
                    $disabled = true;
                    
                }
                else{
                    $disabled=false;
                }
                $records[$j][$i]['disabled'] = $disabled;
            }else if($map == 'group'){
                $checkQry=$connect->query("SELECT * FROM area_group_mapping where status=0 and FIND_IN_SET($sub_area_id,sub_area_id)");
                if ($checkQry->rowCount()>0){
                    $disabled = true;
                }
                else{
                    $disabled=false;
                }
                $records[$j][$i]['disabled'] = $disabled;
            }
            $i++;
        }
        $j++;
    }

}
echo json_encode($records);

// Close the database connection
$connect = null;
?>