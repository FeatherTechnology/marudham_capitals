<?php
include('../../ajaxconfig.php');
@session_start();

if(isset($_SESSION['userid'])){ //fetch if user has cash tally admin access or not
    $user_id = $_SESSION['userid'];
    $qry = $connect->query("SELECT cash_tally_admin from user where user_id = $user_id");
    $admin_access = $qry->fetch()['cash_tally_admin'];
}else{
    $admin_access = '1';
}


$bank_id = $_POST['bank_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$response ='';$i=1;
$totalc = 0;
$totald = 0;


$qry = $connect->query("SELECT * FROM bank_stmt WHERE insert_login_id = '$user_id' and bank_id = '$bank_id' and (trans_date >= '$from_date' and trans_date <= '$to_date' ) and clr_status = 0"); // clr status 0 means uncleared transactions
if($qry->rowCount() > 0){
    //if statements are present in that particular dates then show it in table view
    ?>

    <thead>
        <th width='50'>S.No</th>
        <th width='100'>Date</th>
        <th>Narration</th>
        <th>Tansaction ID</th>
        <th>Credit</th>
        <th>Debit</th>
        <th>Balance</th>
        <th>Clear Category</th>
        <th>Ref ID</th>
        <th>Clearance</th>
    </thead>
    <tbody>
        <?php
        $bank_stmt = array();
        while($row = $qry->fetch()){    
            // Determine transaction type
            $type = ($row['credit'] != '') ? 'cr' : 'dr';
            $crdr = ($row['credit'] != '') ? $row['credit'] : $row['debit'];
        ?>
            <tr data-type="<?php echo $type; ?>" 
                data-trans-id="<?php echo $row['trans_id']; ?>" 
                data-crdr="<?php echo $crdr; ?>" 
                data-bank-stmt-id="<?php echo $row['id']; ?>" >
                <td><?php echo $i;?></td>
                <td><?php echo date('d-m-Y',strtotime($row['trans_date']));?></td>
                <td><?php echo $row['narration'];?></td>
                <td><?php echo $row['trans_id'];?></td>
                <td><?php echo $row['credit'];?></td>
                <td><?php echo $row['debit'];?></td>
                <td><?php echo $row['balance'];?></td>
                <td><?php if($row['credit'] != ''){ echo runcreditCategories($connect,$admin_access,$bank_id); }elseif($row['debit'] !=''){echo rundebitCategories($connect,$admin_access,$bank_id); } ?></td>
                <td><?php echo "<select class='form-control ref-id' ><option value=''>Select Ref ID</option></select>"; ?></td>
                <td><?php echo "<span class='text-danger clr-status' style='font-weight:bold'>Unclear</span>"; ?></td>
                <input type="hidden" class='bank_stmt_id' value='<?php echo $row['id'];?>' >
            </tr>
        <?php 
            $totalc = $totalc + intVal($row['credit']);
            $totald = $totald + intVal($row['debit']);
            $i++; 
        }
        ?>
        <!-- <tr>
            <td colspan="4"><b>Total</b></td>
            <td><b><?php echo moneyFormatIndia($totalc); ?></b></td>
            <td><b><?php echo moneyFormatIndia($totald); ?></b></td>
        </tr> -->
        
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"><b>Unclear Total</b></td>
            <td id='ucl_credit'></td>
            <td id="ucl_debit"></td>
        </tr>
    </tfoot>
<?php
    
}else{
    $response = 'Given Date Has No Statements!';
    echo $response;
}




function runcreditCategories($connect,$admin_access,$bank_id){

    $catqry = "SELECT * from cash_tally_modes where bankcredit = 0  ";
    if($admin_access == '1'){
        $catqry .= "and admin_access = 1 ";
    }

    $runqry = $connect->query($catqry);

    $selectTxt = "<input type='hidden' value='$bank_id'><select class='form-control clr_cat' ><option value=''>Select Category</option>";
    while($catrow = $runqry->fetch()){
        $selectTxt .= "<option value='".$catrow['id']."'>".$catrow['modes']."</option>";
    }
    $selectTxt .= "<option value='15'>Uncleared</option></select><input type='hidden' name='amnt_type[]' value='Credit'>";

    return $selectTxt;
}

function rundebitCategories($connect,$admin_access,$bank_id){

    $catqry = "SELECT * from cash_tally_modes where bankdebit = 0  ";
    if($admin_access == '1'){
        $catqry .= "and admin_access = 1 ";
    }

    $runqry = $connect->query($catqry);

    $selectTxt = "<input type='hidden' value='$bank_id'><select class='form-control clr_cat' ><option value=''>Select Category</option>";
    while($catrow = $runqry->fetch()){
        $selectTxt .= "<option value='".$catrow['id']."'>".$catrow['modes']."</option>";
    }
    $selectTxt .= "</select><input type='hidden' name='amnt_type[]' value='Debit'>";

    return $selectTxt;
}

//Format number in Indian Format
function moneyFormatIndia($num1) {
    if($num1 < 0){
        $num = str_replace("-","",$num1);
    }else{
        $num = $num1;
    }
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    if($num1 < 0 && $num1 != ''){
        $thecash = "-" . $thecash;
    }

    return $thecash;
}

// Close the database connection
$connect = null;
?>