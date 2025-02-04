<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_name'])){
    $cus_name = $_POST['cus_name'];
}

function getfamName($connect,$rel_id){
    $qry1=$connect->query("SELECT famname FROM `verification_family_info` where id=$rel_id");
    $run=$qry1->fetch();
    return $run['famname'];
}
?>
<table class="table custom-table" id='chequeTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Holder Type</th>
            <th>Holder Name</th>
            <th>Relationship</th>
            <th>Bank Name</th>
            <th>Cheque No.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        $qry = $connect->query("SELECT a.id,a.cheque_holder_type,a.cheque_holder_name,a.cheque_no,a.noc_given,a.noc_date,a.noc_person,a.noc_name,b.cheque_relation,b.chequebank_name from `cheque_no_list` a JOIN cheque_info b on a.cheque_table_id = b.id where a.req_id = $req_id  ");
        while($row = $qry->fetch()){

            if(is_numeric($row['cheque_holder_name'])){
                $qry1 = $connect->query("SELECT famname from verification_family_info where id = '".$row['cheque_holder_name']."' ");
                $row1 = $qry1->fetch();
                $holder_name = $row1['famname'];
            }else{$holder_name = $row['cheque_holder_name'];}

        ?>
            <tr>
                <td><?php echo $i;$i++;?></td>
                <td><?php if($row['cheque_holder_type'] == '0'){echo 'Customer';}elseif($row['cheque_holder_type'] == '1'){echo 'Guarentor';}elseif($row['cheque_holder_type'] == '2'){echo 'Family Member';}else ?></td>
                <td><?php echo $holder_name;?></td>
                <td><?php echo $row['cheque_relation'];?></td>
                <td><?php echo $row['chequebank_name'];?></td>
                <td><?php echo $row['cheque_no'];?></td>

            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<?php 
// Close the database connection
$connect = null;
?>