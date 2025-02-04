<?php 
include('../ajaxconfig.php');

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}

$records = array();

$selectIC = $connect->query("SELECT id,cheque_holder_name,cheque_no,cheque_holder_type FROM cheque_no_list WHERE req_id = '".$req_id."' and used_status = '0' "); //dont show if cheque is already used
if($selectIC->rowCount()>0)
{
    $i=0;
    while($row = $selectIC->fetch()){
        $records[$i]['cheque_no_id'] = $row["id"];
        
        if(is_numeric($row["cheque_holder_name"])){
            $selectIC = $connect->query("SELECT famname FROM verification_family_info WHERE id = '".$row["cheque_holder_name"]."' ");
            $rows = $selectIC->fetch();
            $records[$i]['cheque_holder_name'] = $rows['famname'];
        }else{
            $records[$i]['cheque_holder_name'] = $row["cheque_holder_name"];
        }

        $records[$i]['cheque_no'] = $row["cheque_no"];
        $records[$i]['cheque_holder_type'] = $row["cheque_holder_type"];
        $i++;
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>