<?php
session_start();
$user_id = $_SESSION['userid'];

include("../../ajaxconfig.php");

$clr_cat = $_POST['clr_cat'];
$bank_id = $_POST['bank_id'];
$crdb = $_POST['crdb'];
$trans_id = $_POST['trans_id'];

$records = array();

$qry = "SELECT ";

if($crdb == 'Credit' ){
    if($clr_cat == 1){ // collection
        $qry .= "coll_code as ref_code from collection where trans_id = '$trans_id' and bank_id = '$bank_id' ";

    }elseif($clr_cat == 3){//other income
        $qry .= "ref_code from ct_cr_boti where insert_login_id = '$user_id' and trans_id = '$trans_id' and to_bank_id = '$bank_id'";

    }elseif($clr_cat == 4){//Exchange
        $qry .= "ref_code from ct_cr_bexchange where insert_login_id = '$user_id' and trans_id = '$trans_id' and to_bank_id = '$bank_id'";

    }elseif($clr_cat == 5){//Cash deposit
        $qry .= "ref_code from ct_cr_cash_deposit where insert_login_id = '$user_id' and trans_id = '$trans_id' and to_bank_id = '$bank_id'";

    }elseif($clr_cat == 8){//Agent
        $qry .= "ref_code from ct_cr_bag where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";
        
    }elseif($clr_cat == 9){//investment
        $qry .= "ref_code from ct_cr_binvest where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";
        
    }elseif($clr_cat == 10){//Deposit
        $qry .= "ref_code from ct_cr_bdeposit where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";
        
    }elseif($clr_cat == 11){//EL
        $qry .= "ref_code from ct_cr_bel where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";

    }elseif($clr_cat == 15){// Uncleared
        $qry .= "ucl_ref_code as ref_code from ct_db_exf where insert_login_id = '$user_id' and ucl_trans_id = '$trans_id' and bank_id = '$bank_id'";

    }
    
}else if($crdb == 'Debit'){
    if($clr_cat == 4){ // Exchange
        $qry .= "ref_code from ct_db_bexchange where insert_login_id = '$user_id' and trans_id = '$trans_id' and from_acc_id = '$bank_id' ";

    }elseif($clr_cat == 7){//Cash Withdrawal
        $qry .= "ref_code from ct_db_cash_withdraw where insert_login_id = '$user_id' and trans_id = '$trans_id' and from_bank_id = '$bank_id'";

    }elseif($clr_cat == 8){//Agent
        $qry .= "ref_code from ct_db_bag where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";

    }elseif($clr_cat == 9){//Investment
        $qry .= "ref_code from ct_db_binvest where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";

    }elseif($clr_cat == 10){//Deposit
        $qry .= "ref_code from ct_db_bdeposit where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";
        
    }elseif($clr_cat == 11){//EL
        $qry .= "ref_code from ct_db_bel where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";
        
    }elseif($clr_cat == 12){//Excess Fund
        $qry .= "ref_code from ct_db_exf where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";
        
    }elseif($clr_cat == 13){// issued
        $qry .= "ref_code from ct_db_bissued where insert_login_id = '$user_id' and trans_id = '$trans_id' and li_bank_id = '$bank_id'";

    }elseif($clr_cat == 14){// Expenses
        $qry .= "ref_code from ct_db_bexpense where insert_login_id = '$user_id' and trans_id = '$trans_id' and bank_id = '$bank_id'";

    }
}

$runQry = $connect->query($qry);
    if($runQry->rowCount() >0 ){
        $i=0;
        while($row = $runQry->fetch()){
            $records[$i]['ref_code'] = $row['ref_code'];
        }
    }

echo json_encode($records);

// Close the database connection
$connect = null;
?>