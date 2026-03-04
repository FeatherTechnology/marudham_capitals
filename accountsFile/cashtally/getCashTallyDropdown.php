<?php
include('..\..\ajaxconfig.php');

session_start();
if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $qry = $connect->query("SELECT cash_tally_admin from user where user_id = $userid");
    $admin_access = $qry->fetch()['cash_tally_admin'];
}else{
    $admin_access = '1';
}

if(isset($_POST['mode'])){
    $mode = $_POST['mode'];
}

$qry = "SELECT * FROM cash_tally_modes where ";

if($mode == 'hand'){
    $qry .= "(handcredit = 0 OR handdebit = 0)";

}else if($mode == 'bank'){
    $qry .= "(bankcredit = 0 OR bankdebit = 0) ";

}

if($admin_access == '1'){
    $qry .= "AND admin_access = 1 ";
}

$cashtypes = $connect->query($qry)->fetchAll(PDO::FETCH_ASSOC);

$records = [];
foreach ($cashtypes as $cashtype) {

    if ($mode == 'hand') {

        if ($cashtype['handcredit'] == 0) {
            $records[] = [
                'id' => $cashtype['id'],
                'modes' => $cashtype['modes'],
                'type' => 'credit'
            ];
        }

        if ($cashtype['handdebit'] == 0) {
            $records[] = [
                'id' => $cashtype['id'],
                'modes' => $cashtype['modes'],
                'type' => 'debit'
            ];
        }
    }

    if ($mode == 'bank') {

        if ($cashtype['bankcredit'] == 0) {
            $records[] = [
                'id' => $cashtype['id'],
                'modes' => $cashtype['modes'],
                'type' => 'credit'
            ];
        }

        if ($cashtype['bankdebit'] == 0) {
            $records[] = [
                'id' => $cashtype['id'],
                'modes' => $cashtype['modes'],
                'type' => 'debit'
            ];
        }
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>