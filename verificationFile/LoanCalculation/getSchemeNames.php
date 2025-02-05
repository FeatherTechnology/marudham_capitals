<?php 
session_start();
include('../../ajaxconfig.php');

// if(isset($_SESSION['userid'])){
//     $userid = $_SESSION['userid'];
// }
if(isset($_POST['sub_cat'])){
    $sub_cat = $_POST['sub_cat'];
}
if(isset($_POST['due_method'])){
    $due_method = $_POST['due_method'];

    if($due_method == '1'){$due_method = 'monthly';}else
    if($due_method == '2'){$due_method = 'weekly';}else
    if($due_method == '3'){$due_method = 'daily';}
}
$detailrecords = array();

//Fetched where selected sub category and due method matches
$result=$connect->query(" SELECT * FROM loan_scheme WHERE FIND_IN_SET('".strip_tags($sub_cat)."', sub_category) AND due_method = '".strip_tags($due_method)."' "); 
$i=0;
while($row = $result->fetch()){
    $detailrecords[$i]['scheme_id'] = $row['scheme_id'];
    $detailrecords[$i]['scheme_name'] = $row['scheme_name'];
    $detailrecords[$i]['short_name'] = $row['short_name'];
    $i++;
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;
?>