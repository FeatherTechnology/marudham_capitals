<?php
include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');
@session_start();

if(isset($_SESSION['userid'])){ //fetch if user has cash tally admin access or not
    $user_id = $_SESSION['userid'];
}


$bank_id = $_POST['bank_id'];

$response ='';$i=1;
$totalc = 0;
$totald = 0;


$qry = $connect->query("SELECT * FROM bank_stmt WHERE bank_id = '$bank_id' and clr_status = 0 ORDER BY id ASC"); // clr status 0 means uncleared transactions
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
        <th>Un Cleareared Amount</th>
    </thead>
    <tbody>
        <?php
        $bank_stmt = array();
        while($row = $qry->fetch()){    
        ?>
            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo date('d-m-Y H:i', strtotime($row['trans_date'])); ?></td>
                <td><?php echo $row['narration'];?></td>
                <td><?php echo $row['trans_id'];?></td>
                <td><?php echo moneyFormatIndia($row['credit']);?></td>
                <td><?php echo moneyFormatIndia($row['debit']);?></td>
                <td><?php echo moneyFormatIndia($row['balance']);?></td>
                <td><?php echo moneyFormatIndia($row['transaction_amount']);?></td>
            </tr>
        <?php 
            $i++; 
        }
        ?>        
    </tbody>
<?php
    
}else{
    $response = 'Given Bank Name Has No Statements!';
    echo $response;
}


// Close the database connection
$connect = null;
?>