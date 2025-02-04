
<?php
session_start();
include('../../../ajaxconfig.php');

$user_id = $_SESSION['userid'];

$bank_id = $_POST['bank_id'];

$qry = $connect->query("SELECT bdep.*,bc.short_name,bc.acc_no from ct_db_bank_deposit bdep LEFT JOIN bank_creation bc on bdep.to_bank_id = bc.id where bdep.received = 1 
    and bdep.to_bank_id = $bank_id  ");
// 0 means recevied or entered in credit bank deposit. not used current date because any time can be cash deposited to bank 

?>


<table class="table custom-table" id='cdTable'>
    <thead>
        <tr>
            <th width='50'>S.No</th>
            <th>Ref ID</th>
            <th>Transaction ID</th>
            <th>Bank</th>
            <th>Account No</th>
            <th>Location</th>
            <th>Remark</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while($row = $qry->fetch()){
                $qry1 = $connect->query("SELECT * from ct_cr_cash_deposit where db_ref_id = '".$row['id']."' ");
                if($qry1->rowCount() > 0){
                    $row1 = $qry1->fetch();
                    $ref_id = $row1['ref_code'];
                    $trans_id = $row1['trans_id'];
                }else{$ref_id = '';$trans_id = '';}
        ?>
            <tr>
                <td></td>
                <td><?php if($ref_id) echo $ref_id; ?></td>
                <td><?php if($trans_id) echo $trans_id; ?></td>
                <td><?php echo $row['short_name'];?></td>
                <td><?php echo $row['acc_no'];?></td>
                <td><?php echo $row['location'];?></td>
                <td><?php echo $row['remark'];?></td>
                <td><?php echo moneyFormatIndia($row['amount']);?></td>
                <td>
                    <?php if($qry1->rowCount() == 0){ ?>
                        <input type='button' id='' name='' class="btn btn-primary receive_cd" data-value = '<?php echo $row['id']; ?>' data-toggle="modal" data-target=".cd_modal" value='Receive' onclick="receivecdBtnClick(this)">
                    <?php } ?>
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#cdTable').DataTable({
            "title":"Cash Deposit List",
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
function moneyFormatIndia($num) {
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
    return $thecash;
}

// Close the database connection
$connect = null;
?>