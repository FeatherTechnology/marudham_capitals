<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$i=0;$records = array();
$qry = $connect->query("SELECT hex.*,us.fullname,us.role from ct_db_hexchange hex LEFT JOIN user us on us.user_id = hex.insert_login_id where hex.to_user_id = '$user_id' and received = 1 "); //1 means not received and 0 means already received
while($row = $qry->fetch()){
    $records[$i]['id'] = $row['id'];
    $records[$i]['to_user_id'] = $row['to_user_id'];
    $records[$i]['from_user_id'] = $row['insert_login_id'];
    $records[$i]['remark'] = $row['remark'];
    $records[$i]['amt'] = $row['amt'];
    $records[$i]['fullname'] = $row['fullname'];
    $records[$i]['role'] = $row['role'];
    $i++;
}

// Close the database connection
$connect = null;
?>


<table class="table custom-table" id='hexCollectionTable'>
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>From User</th>
            <th>User Type</th>
            <th>Remark</th>
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
                <td><?php echo $records[$i]['fullname'];?></td>
                <td><?php if($records[$i]['role'] == '1'){echo 'Director';}elseif($records[$i]['role'] == '3'){echo 'Staff';}?></td>
                <td><?php echo $records[$i]['remark'];?></td>
                <td><?php echo moneyFormatIndia($records[$i]['amt']);?></td>
                <td>
                    <input type='button' id='' name='' class="btn btn-primary collect_btn" data-value = '<?php echo $records[$i]['id']; ?>' data-toggle="modal" data-target=".hexchange_modal" value='Receive' onclick="hexCollectBtnClick(this)">
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#hexCollectionTable').DataTable({
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
?>