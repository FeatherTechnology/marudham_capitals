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
function getGuarentorName($connect,$req_id){
    $qry1=$connect->query("SELECT famname FROM `verification_family_info` a JOIN `acknowlegement_customer_profile` b on b.guarentor_name = a.id where b.req_id=$req_id");
    $run=$qry1->fetch();
    return $run['famname'];
}

?>
<table class="table custom-table" id='signDocTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Doc Name</th>
            <th>Sign Type</th>
            <th>Name</th>
            <th>Document</th>
            <th>Date Of NOC</th>
            <th>NOC Person</th>
            <th>Name</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        $qry = $connect->query("SELECT a.doc_name,a.sign_type,a.signType_relationship,b.id,b.upload_doc_name,b.noc_given,b.noc_date,b.noc_person,b.noc_name,b.temp_sts FROM `signed_doc_info` a join signed_doc b on a.id = b.signed_doc_id  where b.req_id = $req_id and b.used_status != '1'  ");//temp status equal to 0 means available
        while($row = $qry->fetch()){
            $rel_id = $row['signType_relationship'];
            $name ='';
        ?>
            <tr>
                <td><?php echo $i;$i++;?></td>
                <td>Signed Document</td>
                <td><?php if($row['sign_type'] == '0'){echo 'Customer'; $name=$cus_name;}elseif($row['sign_type'] == '1'){echo 'Guarentor';$name = getGuarentorName($connect,$req_id);}
                            elseif($row['sign_type'] == '2'){echo 'Combined';}elseif($row['sign_type'] == '3'){echo 'Family Member'; $name = getfamName($connect,$rel_id);} ?></td>
                <td><?php echo $name;?></td>
                <td><a href='<?php echo 'uploads/verification/signed_doc/'.$row['upload_doc_name'];?>' target="_blank"><?php echo $row['upload_doc_name'];?></a></td>

                <td><span id='sign_noc_date' name='sign_noc_date' class="sign_noc_date"><?php if($row['noc_date'] != ''){echo date('d-m-Y',strtotime($row['noc_date']));}?></span></td>
                <td>
                    <select id='sign_noc_per' name='sign_noc_per' class="form-control sign_noc_per" <?php if($row['noc_person'] != '' && $row['noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
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
                    <?php if($row['temp_sts'] == '0'){ ?>
                        <input type='checkbox' id='sign_check' name='sign_check' class="form-control sign_check" <?php if($row['noc_given'] == '1') {echo 'checked disabled';}?> data-value='<?php echo $row['id'];//id of docuemnts uploaded table?>'  tabindex='8'></td>
                    <?php }else if($row['temp_sts'] == '1'){?>
                        <label>Not Available</label>
                    <?php } ?>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        // $('#signDocTable').DataTable({
        //     "title":"Signed Document List",
        //     'processing': true,
        //     'iDisplayLength': 5,
        //     "lengthMenu": [
        //         [10, 25, 50, -1],
        //         [10, 25, 50, "All"]
        //     ],
        //     "createdRow": function(row, data, dataIndex) {
        //         $(row).find('td:first').html(dataIndex + 1);
        //     },
        //     "drawCallback": function(settings) {
        //         this.api().column(0).nodes().each(function(cell, i) {
        //             cell.innerHTML = i + 1;
        //         });
        //     },
        // });
    });
</script>

<?php
// Close the database connection
$connect = null;
?>