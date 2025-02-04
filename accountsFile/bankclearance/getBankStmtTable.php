<?php
session_start();

include("../../ajaxconfig.php");

if(isset($_SESSION['userid'])){ //fetch bank account id from user table
    $user_id = $_SESSION['userid'];
    $qry = $connect->query("SELECT bank_details from user where user_id = $user_id");
    $bank_details = $qry->fetch()['bank_details'];
}

$i= 1;

$qry = $connect->query("SELECT * from bank_stmt where bank_id = '$bank_id' and (date(trans_date) >= date('$from_date') and date(trans_date) <= date('$to_date') ) and insert_login_id = '$user_id' ");
// fetching details of bank statement where same user id and bank id,transaction date should be in range of from date and to date which is user selected last
?>


<table class="table custom-table">
    <thead>
        <th width='50'>S.No</th>
        <th>Date</th>
        <th>Transaction ID</th>
        <th>Credit</th>
        <th>Debit</th>
        <th>Balance</th>
        <th>Clear Category</th>
        <th>Ref ID</th>
        <th>Clearance</th>
    </thead>
    <tbody>
        <?php
        while($row = $qry->fetch()){ ?>
            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo date('d-m-Y',strtotime($row['trans_date']));?></td>
                <td><?php echo $row['trans_id'];?></td>
                <td><?php echo $row['credit'];?></td>
                <td><?php echo $row['debit'];?></td>
                <td><?php echo $row['balance'];?></td>
                <td><?php if($row['credit'] != ''){ echo runcreditCategories($connect,$admin_access); }elseif($row['debit'] !=''){echo rundebitCategories($connect,$admin_access); } ?></td>
                <td><?php echo "<select class='form-control ref-id' ><option value=''>Select Ref ID</option></select>"; ?></td>
                <td><?php echo "<input type='text' class='form-control clr-status' readonly placeholder='Please Choose Ref ID' value=''>"; ?></td>
            </tr>
        <?php 
            $i++; 
        }
        ?>
    </tbody>
</table>

<?php

function runcreditCategories($connect,$admin_access){

    $catqry = "SELECT * from cash_tally_modes where bankcredit = 0  ";
    if($admin_access == '1'){
        $catqry .= "and admin_access = 1 ";
    }
    
    $runqry = $connect->query($catqry);
    
    $selectTxt = "<select class='form-control clr_cat' ><option value=''>Select Category</option>";
    while($catrow = $runqry->fetch()){
        $selectTxt .= "<option value='".$catrow['id']."'>".$catrow['modes']."</option>";
    }
    $selectTxt .= "</select>";
    
    return $selectTxt;
}

function rundebitCategories($connect,$admin_access){

    $catqry = "SELECT * from cash_tally_modes where bankdebit = 0  ";
    if($admin_access == '1'){
        $catqry .= "and admin_access = 1 ";
    }
    
    $runqry = $connect->query($catqry);
    
    $selectTxt = "<select class='form-control clr_cat' ><option value=''>Select Category</option>";
    while($catrow = $runqry->fetch()){
        $selectTxt .= "<option value='".$catrow['id']."'>".$catrow['modes']."</option>";
    }
    $selectTxt .= "</select>";

    return $selectTxt;
}

// Close the database connection
$connect = null;
?>