<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');


$i=0;$records = array();
$op_date = date('Y-m-d',strtotime($_POST['op_date']));



$qry = $connect->query("SELECT hexp.*,excat.category from ct_db_hexpense hexp JOIN expense_category excat ON hexp.cat = excat.id where date(hexp.created_date) = '$op_date' and hexp.insert_login_id = '$user_id' ");
//
while($row = $qry->fetch()){

    $records[$i]['id'] = $row['id'];
    $records[$i]['username'] = $row['username'];
    $records[$i]['usertype'] = $row['usertype'];
    // $records[$i]['cat'] = $row['cat'];
    $records[$i]['category'] = $row['category'];
    $records[$i]['part'] = $row['part'];
    $records[$i]['vou_id'] = $row['vou_id'];
    $records[$i]['rec_per'] = $row['rec_per'];
    $records[$i]['remark'] = $row['remark'];
    $records[$i]['amt'] = $row['amt'];
    $records[$i]['upload'] = $row['upload'];
    $i++;
    
}

// Close the database connection
$connect = null;
?>


<table class="table custom-table" id='HexpenseTable'>
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <th>Category</th>
            <th>Particulars</th>
            <th>Voucher ID</th>
            <th>Receive Person</th>
            <th>Remarks</th>
            <th>Amount</th>
            <!-- <th>File</th> -->
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
                <td><?php echo $records[$i]['category'];?></td>
                <td><?php echo $records[$i]['part'];?></td>
                <td><?php echo $records[$i]['vou_id'];?></td>
                <td><?php echo $records[$i]['rec_per'];?></td>
                <td><?php echo $records[$i]['remark'];?></td>
                <td><?php echo moneyFormatIndia($records[$i]['amt']);?></td>
                <!-- <td>
                    <a target='_blank' href='../../../uploads/expenseBill/'<?php echo $records[$i]['upload'];?>><?php echo $records[$i]['upload'];?></a>
                </td> -->
                <td>
                    <span data-value='<?php echo $records[$i]['id']; ?>' title='Delete details' class='delete_hexp'><span class='icon-trash-2'></span></span>
                </td>
                
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#HexpenseTable').DataTable({
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