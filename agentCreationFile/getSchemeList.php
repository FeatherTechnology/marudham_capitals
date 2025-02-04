<?php
include('../ajaxconfig.php');

$selectQry = "SELECT * FROM loan_scheme WHERE 1 and status = 0 "; 
try {
    $res = $connect->query($selectQry);
} catch (PDOException $e) {
    die("Error in Get All Records: " . $e->getMessage());
}
$detailrecords = array();
if ($res->rowCount() >0)
{$i=0;
    while($row = $res->fetchObject()){	
        $detailrecords[$i]['scheme_id']      = $row->scheme_id; 
        $detailrecords[$i]['scheme_name']      = $row->scheme_name; 
        $i++;
    }
}
// Close the database connection
$connect = null;

return $detailrecords;
?>