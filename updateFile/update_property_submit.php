<?php
require '../ajaxconfig.php';

$cus_id                   = $_POST['cus_id'];
$property_type           = $_POST['property_type'];
$property_measurement    = $_POST['property_measurement'];
$property_value          = $_POST['property_value'];
$property_holder         = $_POST['property_holder'];
$propertyID              = $_POST['propertyID'];


if($propertyID == ''){

$insert_qry = $connect ->query("INSERT INTO `verification_property_info`( `cus_id`, `property_type`, `property_measurement`, `property_value`, `property_holder`) VALUES ('$cus_id','$property_type','$property_measurement','$property_value','$property_holder')");

}
else{
$update = $connect->query("UPDATE `verification_property_info` SET  `cus_id`='$cus_id',`property_type`='$property_type',`property_measurement`='$property_measurement',`property_value`='$property_value',`property_holder`='$property_holder' WHERE `id`='$propertyID'");

}

if($insert_qry){
    $result = "Property Info Inserted Successfully.";
}
elseif($update){
    $result = "Property Info Updated Successfully.";
}

echo json_encode($result);

?>