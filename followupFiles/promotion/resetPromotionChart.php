<?php

include('../../ajaxconfig.php');

$cus_id = $_POST['cus_id'];
$screen = $_POST['screen'] ?? '';
$promo_arr = [ 1 => 'Direct', 2 => 'Mobile'];

if($screen =='1'){
    $table_name = 'enquiry_promotion';
}else{
    $table_name = 'new_promotion';
}

$sql = $connect->query("SELECT a.*,b.fullname, CASE b.role WHEN 1 then 'Director' when 2 then 'Agent' when 3 then 'Staff' end as role FROM $table_name a 
        JOIN user b ON a.insert_login_id = b.user_id WHERE a.cus_id = '$cus_id'  ORDER BY a.id DESC "); //order by desc will show last entered data of promotion table

//this query will take new promotion data from that table with username and user type according to inserted login id and using switch case in query for output
//Enquiry is temperary data so keep in seperate table and delete after request raised after enquiry date.
?>

<table class="table custom-table" id='promo_chart'>
    <thead>
        <th width='20'>Date</th>
        <th>Promotion Type</th>
        <th>Status</th>
        <th>Label</th>
        <th>Remark</th>
        <th>User Type</th>
        <th>User</th>
        <th>Follow Date</th>
        <th>Follow up type</th>
    </thead>
    <tbody>
        <?php while($row =  $sql->fetch()){?>
            <tr>
                <td><?php echo date('d-m-Y',strtotime($row['created_date'])) ; ?></td>
                <td><?php echo $promo_arr[$row['promo_type']] ; ?></td>
                <td><?php echo $row['status'] ; ?></td>
                <td><?php echo $row['label']; ?></td>
                <td><?php echo $row['remark']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo date('d-m-Y',strtotime($row['follow_date'])); ?></td>
                <td>
                    <?php 
                        $followup_type =''; 
                        if($row['followup_type'] =='1'){
                            $followup_type = 'Field';  
                        }else if($row['followup_type'] =='2'){
                            $followup_type = 'Telecalling';  
                        }  
                        echo $followup_type;
                    ?>
                </td>
            </tr>
        <?php } ?>

    </tbody>
</table>

<script>
    $('#promo_chart').DataTable({
        order: [], // disable default ordering
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Promotion_Chart'); // or any base
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
</script>

<style>
    @media (max-width: 598px) {
        #promoChartDiv{
            overflow: auto;
        }
    }
</style>

<?php
// Close the database connection
$connect = null;
?>