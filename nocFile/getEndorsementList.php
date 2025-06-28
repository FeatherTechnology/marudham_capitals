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
<table class="table custom-table" id='endorsementTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Details</th> <!-- Endorsement Process and Rc and Key will be placed if exist in td -->
            <th>Date Of NOC</th>
            <th>NOC Person</th>
            <th>Name</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $i=1;
        $qry = $connect->query("SELECT * from acknowlegement_documentation  where req_id=$req_id");
        $row = $qry->fetch();
        ?>
                <?php if($row['endorsement_process'] == '0'){
                    ?>
                <tr>
                    <td><?php echo $i;$i++;?></td>
                    <td>Endorsement Process</td> <!-- Getting table text using JS so don't give empty space -->

                    <td><span id='endorse_noc_date' name='endorse_noc_date' class="endorse_noc_date"><?php if($row['endor_noc_date'] != ''){echo date('d-m-Y',strtotime($row['endor_noc_date']));}?></span></td>
                    <td>
                        <select id='endorse_noc_per' name='endorse_noc_per' class="form-control endorse_noc_per" <?php if($row['endor_noc_person'] != '' && $row['endor_noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                            <option value=''>Select Type</option>
                            <option value='1' <?php if(isset($row['endor_noc_person']) && $row['endor_noc_person'] == 1){echo 'selected';}?>>Customer</option>
                            <option value='2' <?php if(isset($row['endor_noc_person']) && $row['endor_noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                        </select>
                    </td>
                    <td>
                        <?php if(isset($row['endor_noc_name']) && $row['endor_noc_name'] != null){?>
                            <input type="text" class="form-control" value='<?php if(!is_numeric($row['endor_noc_name'])){echo $row['endor_noc_name'];}else{echo getfamName($connect, $row['endor_noc_name']);}?>' readonly>
                        <?php } ?>
                    </td>

                    <td><input type='checkbox' id='endorse_check' name='endorse_check' class="form-control endorse_check" <?php if($row['endorsement_process_noc'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of ack_documentation table?>' data-thing='en_process' ></td>
                </tr>
                    <?php
                }?>
                <?php if($row['en_RC'] == '0' && $row['Rc_document_pending'] != 'YES' && $row['en_RC_used'] != '1'){
                    ?>
                <tr>
                    <td><?php echo $i;$i++;?></td>
                    <td>RC</td> <!-- Getting table text using JS so don't give empty space -->

                    <td><span id='endorse_noc_date' name='endorse_noc_date' class="endorse_noc_date"><?php if($row['en_rc_noc_date'] != ''){echo date('d-m-Y',strtotime($row['en_rc_noc_date']));}?></span></td>
                    <td>
                        <select id='endorse_noc_per' name='endorse_noc_per' class="form-control endorse_noc_per" <?php if($row['en_rc_noc_person'] != '' && $row['en_rc_noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                            <option value=''>Select Type</option>
                            <option value='1' <?php if(isset($row['en_rc_noc_person']) && $row['en_rc_noc_person'] == 1){echo 'selected';}?>>Customer</option>
                            <option value='2' <?php if(isset($row['en_rc_noc_person']) && $row['en_rc_noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                        </select>
                    </td>
                    <td>
                        <?php if(isset($row['en_rc_noc_name']) && $row['en_rc_noc_name'] != null){?>
                            <input type="text" class="form-control" value='<?php if(!is_numeric($row['en_rc_noc_name'])){echo $row['en_rc_noc_name'];}else{echo getfamName($connect, $row['en_rc_noc_name']);}?>' readonly>
                        <?php } ?>
                    </td>

                    <td><input type='checkbox' id='endorse_check' name='endorse_check' class="form-control endorse_check" <?php if($row['en_RC_noc'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of ack_documentation table?>' data-thing='en_rc' ></td>
                </tr>
                    <?php
                }?>
                <?php if($row['en_Key'] == '0' && $row['en_Key_used'] != '1'){
                    ?>
                <tr>
                    <td><?php echo $i;$i++;?></td>
                    <td>Key</td> <!-- Getting table text using JS so don't give empty space -->

                    <td><span id='endorse_noc_date' name='endorse_noc_date' class="endorse_noc_date"><?php if($row['en_key_noc_date'] != ''){echo date('d-m-Y',strtotime($row['en_key_noc_date']));}?></span></td>
                    <td>
                        <select id='endorse_noc_per' name='endorse_noc_per' class="form-control endorse_noc_per" <?php if($row['en_key_noc_person'] != '' && $row['en_key_noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                            <option value=''>Select Type</option>
                            <option value='1' <?php if(isset($row['en_key_noc_person']) && $row['en_key_noc_person'] == 1){echo 'selected';}?>>Customer</option>
                            <option value='2' <?php if(isset($row['en_key_noc_person']) && $row['en_key_noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                        </select>
                    </td>
                    <td>
                        <?php if(isset($row['en_key_noc_name']) && $row['en_key_noc_name'] != null){?>
                            <input type="text" class="form-control" value='<?php if(!is_numeric($row['en_key_noc_name'])){echo $row['en_key_noc_name'];}else{echo getfamName($connect, $row['en_key_noc_name']);}?>' readonly>
                        <?php } ?>
                    </td>

                    <td><input type='checkbox' id='endorse_check' name='endorse_check' class="form-control endorse_check" <?php if($row['en_Key_noc'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of ack_documentation table?>' data-thing='en_key' tabindex='38'></td>
                </tr>
                    <?php
                }?>

    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>