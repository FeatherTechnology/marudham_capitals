<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id = $_POST['bank_id'];

$i=0;$records = array();

$qry = $connect->query("SELECT bex.*,bc.short_name,bc.acc_no from ct_db_bexchange bex LEFT JOIN bank_creation bc on bc.id = bex.from_acc_id where bex.to_bank_id = '$bank_id' and received = 1 "); //1 means not received and 0 means already received
while($row = $qry->fetch()){
    $records[$i]['id'] = $row['id'];
    $records[$i]['ref_code'] = $row['ref_code'];
    $records[$i]['from_bank_id'] = $row['from_acc_id'];
    $records[$i]['from_bank_name'] = $row['short_name'] .' - '. substr($row['acc_no'],-5);
    $records[$i]['to_bank_id'] = $row['to_bank_id'];
    $records[$i]['to_user_id'] = $row['to_user_id'];
    $records[$i]['from_user_id'] = $row['insert_login_id'];
    $records[$i]['trans_id'] = $row['trans_id'];
    $records[$i]['remark'] = $row['remark'];
    $records[$i]['amt'] = $row['amt'];
    $i++;
}

?>


<table class="table custom-table" id='bexCollectionTable'>
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>Ref ID</th>
            <th>From Bank Account</th>
            <th>Transaction ID</th>
            <th>Remark</th>
            <th>Exchange Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i=0;$i<sizeof($records);$i++){
        ?>
            <tr>
                <td></td>
                <td><?php echo $records[$i]['ref_code'];?></td>
                <td><?php echo $records[$i]['from_bank_name'];?></td>
                <td><?php echo $records[$i]['trans_id'];?></td>
                <td><?php echo $records[$i]['remark'];?></td>
                <td><?php echo moneyFormatIndia($records[$i]['amt']);?></td>
                <td>
                    <input type='button' id='' name='' class="btn btn-primary collect_btn" data-value = '<?php echo $records[$i]['id']; ?>' data-toggle="modal" data-target=".bexchange_modal" value='Receive' onclick="bexCollectBtnClick(this)">
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#bexCollectionTable').DataTable({
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