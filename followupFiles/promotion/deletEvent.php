<?php
include("../../ajaxconfig.php");

// Get the ID from POST
$id = isset($_POST['id']) ? $_POST['id'] : '';

if($id != '') {
        $qry = $connect->query("DELETE FROM `event_promotion` WHERE id ='$id' ");
}
if($qry){
    $response = "Event Member Deleted";
}else{
    $response = "Error While Deleting";
}

echo $response;

?>
