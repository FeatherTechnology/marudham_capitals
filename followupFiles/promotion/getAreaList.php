
<?php

session_start();

include('../../ajaxconfig.php');

$sector = $_POST['sector'];

$areaList = array();

if (!empty($sector)) {

$areaQry = $connect->prepare("SELECT area_id  FROM `area_group_mapping_area`  WHERE group_map_id = ? ");

$areaQry->execute([$sector]);

$areaIds = array();

while ($row = $areaQry->fetch(PDO::FETCH_ASSOC)) {
        $areaIds[] = $row['area_id'];
}

if (!empty($areaIds)) {
        $placeholders = implode(',', array_fill(0, count($areaIds), '?'));
        $areaNameQry = $connect->prepare("SELECT area_id, area_name FROM `area_list_creation` WHERE area_id IN ($placeholders) ORDER BY area_name");
        $areaNameQry->execute($areaIds);
        while ($row = $areaNameQry->fetch(PDO::FETCH_ASSOC)) {
            $areaList[] = array(
                "area_id"   => $row['area_id'],
                "area_name" => $row['area_name']
            );
        }
    }
}
echo json_encode($areaList);

$connect = null;
?>

