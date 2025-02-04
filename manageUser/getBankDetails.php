<?php
include('..\ajaxconfig.php');

    $qry = $connect->query("SELECT * from bank_creation where 1");
    $records = array();
    if($qry->rowCount() > 0){
        $i=0;
        while($row = $qry->fetch()){
            $records[$i]['id'] = $row['id'];
            $records[$i]['bank_name'] = $row['bank_name'];
            $records[$i]['short_name'] = $row['short_name'];
            $records[$i]['acc_no'] = $row['acc_no'];
            $records[$i]['ifsc'] = $row['ifsc'];
            $records[$i]['branch'] = $row['branch'];
            $records[$i]['qr_code'] = $row['qr_code'];
            $records[$i]['gpay'] = $row['gpay'];
            $records[$i]['company'] = $row['company'];
            $records[$i]['under_branch'] = $row['under_branch'];
            $i++;
        }
    }	
    echo json_encode($records);

// Close the database connection
$connect = null;
?>