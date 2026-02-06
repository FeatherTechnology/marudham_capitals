<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');
include('../../../moneyFormatIndia.php');

$bank_id = $_POST['bank_id'];
$i=0;$records = array();

$op_date = date('Y-m-d',strtotime($_POST['op_date']));


$qry = $connect->query("SELECT bexp.*,excat.category from ct_db_bexpense bexp JOIN expense_category excat ON bexp.cat = excat.id where date(bexp.created_date) = '$op_date' and bexp.bank_id = '$bank_id' and bexp.insert_login_id = '$user_id' ");
//
while($row = $qry->fetch()){

    $records[$i]['id'] = $row['id'];
    $records[$i]['username'] = $row['username'];
    $records[$i]['usertype'] = $row['usertype'];
    $records[$i]['ref_code'] = $row['ref_code'];
    $records[$i]['bank_id']  = $row['bank_id'];   
    $records[$i]['trans_id'] = $row['trans_id']; 
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


<table class="table custom-table" id='BexpenseTable'>
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <th>Ref ID</th>
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
                <td><?php echo $records[$i]['ref_code'];?></td>
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
                    <span data-value="<?php echo $records[$i]['id']; ?>" data-bank_id="<?php echo $records[$i]['bank_id']; ?>"data-trans_id="<?php echo $records[$i]['trans_id']; ?>" title='Delete details' class='delete_bexp'><span class='icon-trash-2'></span></span>
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
        var BexpenseTable = $('#BexpenseTable').DataTable({
            ...getStateSaveConfig('BexpenseTable'),
            "title":"Bank Expense List",
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
                        var dynamic = curDateJs('Bank_Expense_List'); // or any base
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
        initColVisFeatures(BexpenseTable, 'BexpenseTable');
    });
</script>