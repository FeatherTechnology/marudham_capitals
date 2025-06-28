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
            <th>Date Of NOC</th>
            <th>NOC Person</th>
            <th>Name</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        $qry = $connect->query("SELECT a.id,a.cheque_holder_type,a.cheque_holder_name,a.cheque_no,a.noc_given,a.noc_date,a.noc_person,a.noc_name,a.temp_sts,b.cheque_relation,b.chequebank_name from `cheque_no_list` a JOIN cheque_info b on a.cheque_table_id = b.id where a.req_id = $req_id && a.used_status != '1' ");
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

                <td><span id='cheque_noc_date' name='cheque_noc_date' class="cheque_noc_date"><?php if($row['noc_date'] != ''){echo date('d-m-Y',strtotime($row['noc_date']));}?></span></td>
                <td>
                    <select id='cheque_noc_per' name='cheque_noc_per' class="form-control cheque_noc_per" <?php if($row['noc_person'] != '' && $row['noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                        <option value=''>Select Type</option>
                        <option value='1' <?php if(isset($row['noc_person']) && $row['noc_person'] == 1){echo 'selected';}?>>Customer</option>
                        <option value='2' <?php if(isset($row['noc_person']) && $row['noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                    </select>
                </td>
                <td>
                    <?php if(isset($row['noc_name']) && $row['noc_name'] != null){?>
                        <input type="text" class="form-control" value='<?php if(!is_numeric($row['noc_name'])){echo $row['noc_name'];}else{echo getfamName($connect, $row['noc_name']);}?>' readonly>
                    <?php } ?>
                </td>

                <td>
                    <?php #if($row['temp_sts'] == '0'){ ?>
                        <input type='checkbox' id='cheque_check' name='cheque_check' class="form-control cheque_check" <?php if($row['noc_given'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of cheque list table?>'>
                    <?php #}else if($row['temp_sts'] == '1'){?>
                        <!-- <label>Not Available</label> -->
                    <?php #} ?>
                </td>
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