<?php
session_start();
$user_id = $_SESSION['userid'];
include('../../../ajaxconfig.php');
include "../../../moneyFormatIndia.php";

$i=0;
$records = array();
$op_date = date('Y-m-d',strtotime($_POST['op_date']));

$qry = $connect->query("SELECT hexp.*, CONCAT(excat.exp_code ,'-', excat.category) AS category from ct_db_hexpense hexp JOIN expense_category excat ON hexp.cat = excat.id where date(hexp.created_date) = '$op_date' and hexp.insert_login_id = '$user_id' ");
while($row = $qry->fetch()){
    $records[$i]['id'] = $row['id'];
    $records[$i]['username'] = $row['username'];
    $records[$i]['usertype'] = $row['usertype'];
    $records[$i]['cat'] = $row['cat'];
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
                    <?php if($records[$i]['cat'] != '16'){ //if waiver expenses means no action to delete bcuz waiver expenses insert directly from waiver modal. ?>
                        <span data-value='<?php echo $records[$i]['id']; ?>' title='Delete details' class='delete_hexp'><span class='icon-trash-2'></span></span>
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
        // Declare table variable to store the DataTable instance
        var HexpenseTable = $('#HexpenseTable').DataTable({
            ...getStateSaveConfig('HexpenseTable'),
            "title":"Hand Expenses List",
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
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Hand_Expenses_List'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(HexpenseTable, 'HexpenseTable');
    });
</script>