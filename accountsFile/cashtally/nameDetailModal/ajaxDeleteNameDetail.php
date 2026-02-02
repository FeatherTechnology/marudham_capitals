<?php
include '../../../ajaxconfig.php';

if(isset($_POST["name_id"])){
	$name_id  = $_POST["name_id"];
}
if(isset($_POST["opt_for"])){
	$opt_for  = $_POST["opt_for"];
}
// echo $opt_for;die;
$message = '';

/* ================= CREDIT - DEBIT BALANCE CHECK ================= */

if ($opt_for == 'dep') {

    // ===== Deposit =====
    $balQry = $connect->query(" SELECT 
        (IFNULL((SELECT SUM(amt) FROM ct_cr_bdeposit WHERE name_id = '$name_id'),0) +
            IFNULL((SELECT SUM(amt) FROM ct_cr_hdeposit WHERE name_id = '$name_id'),0) )
        -
        ( IFNULL((SELECT SUM(amt) FROM ct_db_bdeposit WHERE name_id = '$name_id'),0) +
            IFNULL((SELECT SUM(amt) FROM ct_db_hdeposit WHERE name_id = '$name_id'),0)) AS balance ");

} elseif ($opt_for == 'inv') {

    // ===== Investment =====
     $balQry = $connect->query( "SELECT 
        ( IFNULL((SELECT SUM(amt) FROM ct_cr_binvest WHERE name_id = '$name_id'),0) +
            IFNULL((SELECT SUM(amt) FROM ct_cr_hinvest WHERE name_id = '$name_id'),0) )
        -
        ( IFNULL((SELECT SUM(amt) FROM ct_db_binvest WHERE name_id = '$name_id'),0) +
            IFNULL((SELECT SUM(amt) FROM ct_db_hinvest WHERE name_id = '$name_id'),0) ) AS balance " );
    
} elseif ($opt_for == 'el') {

    // ===== EL =====
   $balQry = $connect->query("SELECT 
        ( IFNULL((SELECT SUM(amt) FROM ct_cr_bel WHERE name_id = '$name_id'),0) +
            IFNULL((SELECT SUM(amt) FROM ct_cr_hel WHERE name_id = '$name_id'),0) )
        -
        ( IFNULL((SELECT SUM(amt) FROM ct_db_bel WHERE name_id = '$name_id'),0) +
            IFNULL((SELECT SUM(amt) FROM ct_db_hel WHERE name_id = '$name_id'),0) ) AS balance ");

} else {
    echo "Invalid option";
    exit;
}


$row = $balQry->fetch();
$balance = $row['balance'] ?? 0;
/* ================= CONDITION ================= */

if ($balance != 0) {
    $message = "You Don't Have Rights To Delete This Name Detail";
} else {
    $delct = $connect->query("UPDATE name_detail_creation SET status = 1 WHERE name_id = '$name_id' ");

    if ($delct) {
        $message = "Name Detail Inactivated Successfully";
    }
}

echo $message;

// Close the database connection
$connect = null;
?>