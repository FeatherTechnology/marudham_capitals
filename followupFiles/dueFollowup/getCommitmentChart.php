<?php

include('../../ajaxconfig.php');


$req_id = $_POST['req_id'];
$cus_id = $_POST['cus_id'];

$sql = $connect->query("SELECT a.*,b.fullname, CASE b.role WHEN 1 then 'Director' when 2 then 'Agent' when 3 then 'Staff' end as role FROM commitment a 
        JOIN user b ON a.insert_login_id = b.user_id WHERE a.req_id = '$req_id'  ORDER BY a.id DESC "); //order by desc will show last entered data of confirmation table

//this query will take Confirmation followup data from that table with username and user type according to inserted login id and using switch case in query for output

$ftype = [1=>'Direct',2=>'Mobile'];
$fstatus = [1=>'Commitment',2=>'Unavailable',3=>'RNR',4=>'Not Reachable',5=>'Switch Off',6=>'Not in Use',7=>'Blocked'];
$per_type_arr = [1=>'Customer',2=>'Garentor',3=>'Family Member'];
$sno = 1;

function getCustomer($connect,$cus_id){
    $result = $connect->query("SELECT customer_name from customer_register where cus_id = '$cus_id' ");
    $cus_name = $result->fetch()['customer_name'];
    return $cus_name;
}
function getGarentor($connect,$cus_id){
    $query = "SELECT cp.guarentor_name, vfi.famname, vfi.relationship FROM customer_profile cp JOIN verification_family_info vfi ON cp.guarentor_name = vfi.id WHERE cp.cus_id = '$cus_id' ORDER BY cp.id DESC LIMIT 1 ";
    $result = $connect->query($query);
    $row = $result->fetch();
    
    $response = [
        "name" => $row['famname'],
        "relationship" => $row['relationship']
    ];
    return $response;
}

function getFamilyMember($connect,$fam_id){
    
    $result = $connect->query("SELECT id,famname,relationship FROM `verification_family_info` where id='$fam_id'");

    $row = $result->fetch();
    $fam_name = $row['famname'];
    $relationship = $row['relationship'];
    $response = array("name" => $fam_name, "relationship" => $relationship);
    return $response;
}

?>


<table class="table custom-table" id='commitment_chart'>
    <thead>
        <th width='20'>S No</th>
        <th>Date</th>
        <th>Followup Type</th>
        <th>Followup Status</th>
        <th>Person Type</th>
        <th>Person Name</th>
        <th>Relationship</th>
        <th>Remark</th>
        <th>Commitment Date</th>
        <th>User Type</th>
        <th>User Name</th>
        <th>Hint</th>
        <th>Communication Status</th>
    </thead>
    <tbody>
        <?php while($row =  $sql->fetch()){?>
            <tr>
                <td><?php echo $sno;$sno++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($row['created_date'])); ?></td>
                <td><?php echo $ftype[$row['ftype']]; ?></td>
                <td><?php echo $fstatus[$row['fstatus']]; ?></td>
                <td><?php echo $per_type_arr[$row['person_type']]??''; ?></td>
                <td>
                    <?php 
                        if($row['person_type'] == 1){$person_name = getCustomer($connect,$cus_id); echo $person_name; }else
                        if($row['person_type'] == 2){$person_name = getGarentor($connect,$cus_id); echo $person_name['name']; }else
                        if($row['person_type'] == 3){$person_name = getFamilyMember($connect,$row['person_name']); echo $person_name['name'];}
                    ?>
                </td>
                <td>
                    <?php 
                        if($row['person_type'] == 1){echo 'NIL'; }else
                        if($row['person_type'] == 2){echo $person_name['relationship']; }else
                        if($row['person_type'] == 3){echo $person_name['relationship']; }
                    ?>
                </td>
                
                <td><?php echo $row['remark']; ?></td>
                <td><?php if($row['comm_date'] != '0000-00-00' && !empty($row['comm_date'])){ echo date('d-m-Y',strtotime($row['comm_date'])); }else{ echo ''; } ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['hint']; ?></td>
                <td><?php echo $row['comm_err']=='1'?'Error':($row['comm_err']=='2'?'Clear':''); ?></td>
                
            </tr>
        <?php } ?>

    </tbody>
</table>

<script>
    $('#commitment_chart').dataTable({
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
    })
    
</script>
<style>
    @media (max-width: 598px) {
        #commChartDiv{
            overflow: auto;
        }
    }
</style>

<?php
// Close the database connection
$connect = null;
?>
