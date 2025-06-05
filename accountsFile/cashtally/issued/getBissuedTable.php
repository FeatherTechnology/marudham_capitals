<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id = $_POST['bank_id'];

$i=0;$records = array();
$netcash = 0;


$qry = $connect->query("SELECT id, cheque_no, cheque_value, transaction_id, transaction_value, issued_to, req_id, insert_login_id 
    FROM loan_issue li
    WHERE 
        (agent_id = '' OR agent_id IS NULL) 
        AND (cheque_value != '' OR transaction_value != '') 
        AND bank_id = '$bank_id'
        AND NOT EXISTS (
            SELECT 1 FROM ct_db_bissued 
            WHERE li_user_id = li.insert_login_id 
            AND li_id = li.id
        ) ");
//Query changed.
//not used current date, bcoz if loan issued on friday payment may take time to reflect or payer may do this cash tally at any time.
//so until this current bank account get closed it should show the amount userwise
//not used group by and sum for cheque and transaction , bcoz all are getting summed up and giving wrong cheque numbers and trans ids
//only single entries can be placed in tables 
while($row = $qry->fetch()){

    // $dbCheck = $connect->query("SELECT * from ct_db_bissued where li_user_id = '".$row['insert_login_id']."' ");
    // if($dbCheck->rowCount() == 0){ 
        // to check whether id of loan issue is already entered in bissued table. if done, no need to show bcoz submitted bissued no need to show in table

        // $netcash = $netcash + intVal($row['cash']);
        if($row['cheque_value'] != '' and $row['transaction_value'] == ''){
            
            $records[$i]['netcash'] = intVal($row['cheque_value']);
            $records[$i]['cheque_no'] = $row['cheque_no'];
            $records[$i]['transaction_id'] = '';
            
        }else if($row['transaction_value'] != '' and $row['cheque_value'] == ''){
            
            $records[$i]['netcash'] = intVal($row['transaction_value']);
            $records[$i]['transaction_id'] = $row['transaction_id'];
            $records[$i]['cheque_no'] = '';
        
        }else if($row['cheque_value'] != '' and $row['transaction_value'] != ''){
            $records[$i]['netcash'] = intVal($row['cheque_value']) + intVal($row['transaction_value']);
            $records[$i]['cheque_no'] = $row['cheque_no'];
            $records[$i]['transaction_id'] = $row['transaction_id'];
        }

        $records[$i]['issued_to'] = $row['issued_to'];
        $records[$i]['id'] = $row['id'];
        $records[$i]['user_id'] = $row['insert_login_id'];
        $user_id = $row['insert_login_id'];

        $qry1 = $connect->query("SELECT role,fullname FROM `user` where user_id= '$user_id' ");
        $row1 = $qry1->fetch();
        $role = $row1['role'];if($role == 1){$records[$i]['usertype'] = 'Director';}else if($role==3){$records[$i]['usertype'] = 'Staff';}
        $records[$i]['username'] = $row1['fullname'];
        $i++;
    // }
}

// Close the database connection
$connect = null;
?>


<table class="table custom-table" id='BissuedTable'>
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <!-- <th>Issued To</th> -->
            <th>Cheque No.</th>
            <th>Transaction ID</th>
            <th>Net Cash</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i=0;$i<sizeof($records);$i++){
        ?>
            <tr>
                <td></td>
                <td><?php echo $records[$i]['usertype'];?></td>
                <td><?php echo $records[$i]['username'];?></td>
                
                <!-- <td><?php echo $records[$i]['issued_to'];?></td> -->
                
                <td><?php echo $records[$i]['cheque_no'];?></td>
                <td><?php echo $records[$i]['transaction_id'];?></td>
                <td><?php echo moneyFormatIndia($records[$i]['netcash']);?></td>
                
                <td>
                    <input type='button' id='' name='' class="btn btn-primary bissued_btn" data-value = '<?php echo $records[$i]['user_id']; ?>' data-id="<?php echo $records[$i]['id']; ?>" data-toggle="modal" data-target='.bissued_modal' value='Enter' > <!-- onclick="bissuedBtnClick(this)"  -->
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#BissuedTable').DataTable({
            "title":"Collection List",
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function(settings) {
                this.api().column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });
    });
</script>

<?php
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

    if($num1 < 0){
        $thecash = "-" . $thecash;
    }

    return $thecash;
}
?>