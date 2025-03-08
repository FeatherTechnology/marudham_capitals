<?php
session_start();
$user_id = $_SESSION['userid'];

include("../../ajaxconfig.php");

$clr_cat = $_POST['clr_cat'];
$bank_id = $_POST['bank_id'];
$crdb = $_POST['crdb'];
$trans_id = $_POST['trans_id'];
$trans_amt = $_POST['trans_amt'];

$paid_records = array();
$records = array();

$qry = "SELECT ";

if($crdb == 'Credit' ){
    if($clr_cat == 1){ // collection
        $qry .= "coll_code as ref_code,total_paid_track AS amt FROM collection WHERE trans_id = '$trans_id' AND bank_id = '$bank_id' ";

    }elseif($clr_cat == 3){//other income
        $qry .= "ref_code, amt FROM ct_cr_boti WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND to_bank_id = '$bank_id'";

    }elseif($clr_cat == 4){//Exchange
        $qry .= "ref_code, amt FROM ct_cr_bexchange WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND to_bank_id = '$bank_id'";

    }elseif($clr_cat == 5){//Cash deposit
        $qry .= "ref_code, amt FROM ct_cr_cash_deposit WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND to_bank_id = '$bank_id'";

    }elseif($clr_cat == 8){//Agent
        $qry .= "ref_code, amt FROM ct_cr_bag WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";
        
    }elseif($clr_cat == 9){//investment
        $qry .= "ref_code, amt FROM ct_cr_binvest WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";
        
    }elseif($clr_cat == 10){//Deposit
        $qry .= "ref_code, amt FROM ct_cr_bdeposit WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";
        
    }elseif($clr_cat == 11){//EL
        $qry .= "ref_code, amt FROM ct_cr_bel WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";

    }elseif($clr_cat == 15){// Uncleared
        $qry .= "ucl_ref_code as ref_code, amt FROM ct_db_exf WHERE insert_login_id = '$user_id' AND ucl_trans_id = '$trans_id' AND bank_id = '$bank_id'";

    }
    
}else if($crdb == 'Debit'){
    if($clr_cat == 4){ // Exchange
        $qry .= "ref_code, amt FROM ct_db_bexchange WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND from_acc_id = '$bank_id' ";

    }elseif($clr_cat == 7){//Cash Withdrawal
        $qry .= "ref_code, amt FROM ct_db_cash_withdraw WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND from_bank_id = '$bank_id'";

    }elseif($clr_cat == 8){//Agent
        $qry .= "ref_code, amt FROM ct_db_bag WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";

    }elseif($clr_cat == 9){//Investment
        $qry .= "ref_code, amt FROM ct_db_binvest WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";

    }elseif($clr_cat == 10){//Deposit
        $qry .= "ref_code, amt FROM ct_db_bdeposit WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";
        
    }elseif($clr_cat == 11){//EL
        $qry .= "ref_code, amt FROM ct_db_bel WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";
        
    }elseif($clr_cat == 12){//Excess Fund
        $qry .= "ref_code, amt FROM ct_db_exf WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";
        
    }elseif($clr_cat == 13){// issued
        $qry .= "ref_code, netcash AS amt FROM ct_db_bissued WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND li_bank_id = '$bank_id'";

    }elseif($clr_cat == 14){// Expenses
        $qry .= "ref_code, amt FROM ct_db_bexpense WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'";

    }
}

$runQry = $connect->query($qry);
    if($runQry->rowCount() > 0 ){
        $i = 0;
        $total_paid = 0;
        while($row = $runQry->fetch()){
            $total_paid += $row['amt'];
            $paid_records[$i]['ref_code'] = $row['ref_code'];
            $i++;
        }

        if($trans_amt == $total_paid){
            $records = $paid_records;
        }
    }

echo json_encode($records);

// Close the database connection
$connect = null;
?>