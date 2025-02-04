<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$i=0;$records = array();
$netcash = 0;

$op_date = date('Y-m-d',strtotime($_POST['op_date']));


// $qry = $connect->query("SELECT role,fullname FROM `user` where user_id= '$user_id' ");
// $row = $qry->fetch_assoc();
// $role = $row['role'];if($role == 1){$usertype = 'Director';}else if($role==3){$usertype = 'Staff';}
// $username = $row['fullname'];


$qry = $connect->query("SELECT req_id,sum(cash) as cash,issued_to,insert_login_id,created_date FROM `loan_issue` where (agent_id = '' or agent_id = null) and ((issued_mode = 1 and payment_type = '0') or (issued_mode = 0 and cash != '')) and date(created_date) = '$op_date' GROUP BY insert_login_id ");
while($row = $qry->fetch()){

    $dbCheck = $connect->query("SELECT * from ct_db_hissued where date(created_date) = '".date('Y-m-d',strtotime($row['created_date']))."' and li_user_id = '".$row['insert_login_id']."' ");
    if($dbCheck->rowCount() == 0){ 
        // to check whether created date of loan issue is already entered in hissued table. if done, no need to show bcoz submitted hissued no need to show in table

        // $netcash = $netcash + intVal($row['cash']);
        $records[$i]['netcash'] = intVal($row['cash']);
        $records[$i]['issued_to'] = $row['issued_to'];
        $records[$i]['req_id'] = $row['req_id'];
        $records[$i]['user_id'] = $row['insert_login_id'];
        $user_id = $row['insert_login_id'];

        $qry1 = $connect->query("SELECT role,fullname FROM `user` where user_id= '$user_id' ");
        $row1 = $qry1->fetch();
        $role = $row1['role'];if($role == 1){$records[$i]['usertype'] = 'Director';}else if($role==3){$records[$i]['usertype'] = 'Staff';}
        $records[$i]['username'] = $row1['fullname'];
        $i++;
    }
}

// Close the database connection
$connect = null;
?>


<table class="table custom-table" id='HissuedTable'>
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <!-- <th>Issued To</th> -->
            <th>Net Cash</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i=0;$i<sizeof($records);$i++){
        ?>
            <tr>
                <td></td>
                <!-- <td><?php echo $usertype;?></td> -->
                <td><?php echo $records[$i]['usertype'];?></td>
                
                <!-- <td><?php echo $username;?></td> -->
                <td><?php echo $records[$i]['username'];?></td>
                
                <!-- <td><?php echo $records[$i]['issued_to'];?></td> -->
                
                <!-- <td><?php echo moneyFormatIndia($netcash);  ?></td> -->
                <td><?php echo moneyFormatIndia($records[$i]['netcash']);?></td>
                <td width='300'><input type='text' class='form-control' readonly value='<?php echo moneyFormatIndia($records[$i]['netcash']);?>'></td>
                <td>
                    <input type='button' id='' name='' class="btn btn-primary hissued_btn" data-value = '<?php echo $records[$i]['user_id']; ?>' value='Submit' >
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#HissuedTable').DataTable({
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