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
<table class="table custom-table" id='mortgageTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Details</th> <!-- Mortgage Process and Document will be placed if exist in td -->
            <th>Date Of NOC</th>
            <th>NOC Person</th>
            <th>Name</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $i=1;
        $qry = $connect->query("SELECT * from acknowlegement_documentation where req_id=$req_id ");
        $row = $qry->fetch();
        ?>
                <?php if($row['mortgage_process'] == '0'){
                    ?>
                <tr>
                    <td><?php echo $i;$i++;?></td>
                    <td>Mortgage Process</td> <!-- Getting table text using JS so don't give empty space -->

                    <td><span id='mort_noc_date' name='mort_noc_date' class="mort_noc_date"><?php if($row['mort_noc_date'] != ''){echo date('d-m-Y',strtotime($row['mort_noc_date']));}?></span></td>
                    <td>
                        <select id='mort_noc_per' name='mort_noc_per' class="form-control mort_noc_per" <?php if($row['mort_noc_person'] != '' && $row['mort_noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                            <option value=''>Select Type</option>
                            <option value='1' <?php if(isset($row['mort_noc_person']) && $row['mort_noc_person'] == 1){echo 'selected';}?>>Customer</option>
                            <option value='2' <?php if(isset($row['mort_noc_person']) && $row['mort_noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                        </select>
                    </td>
                    <td>
                        <?php if(isset($row['mort_noc_name']) && $row['mort_noc_name'] != null){?>
                            <input type="text" class="form-control" value='<?php if(!is_numeric($row['mort_noc_name'])){echo $row['mort_noc_name'];}else{echo getfamName($connect, $row['mort_noc_name']);}?>' readonly>
                        <?php } ?>
                    </td>

                    <td><input type='checkbox' id='mort_check' name='mort_check' class="form-control mort_check" <?php if($row['mortgage_process_noc'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of ack_documentation list table?>' data-thing='process' tabindex='26'></td>
                </tr>
                    <?php
                }?>
                <?php if($row['mortgage_document'] == '0' && $row['mortgage_document_pending'] != 'YES' && $row['mortgage_document_used'] != '1'){
                    ?>
                <tr>
                    <td></td>
                    <td>Mortgage Document</td> <!-- Getting table text using JS so don't give empty space -->
                        
                    <td><span id='mort_noc_date' name='mort_noc_date' class="mort_noc_date"><?php if($row['mort_doc_noc_date'] != ''){echo date('d-m-Y',strtotime($row['mort_doc_noc_date']));}?></span></td>
                    <td>
                        <select id='mort_noc_per' name='mort_noc_per' class="form-control mort_noc_per" <?php if($row['mort_doc_noc_person'] != '' && $row['mort_doc_noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                            <option value=''>Select Type</option>
                            <option value='1' <?php if(isset($row['mort_doc_noc_person']) && $row['mort_doc_noc_person'] == 1){echo 'selected';}?>>Customer</option>
                            <option value='2' <?php if(isset($row['mort_doc_noc_person']) && $row['mort_doc_noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                        </select>
                    </td>
                    <td>
                        <?php if(isset($row['mort_doc_noc_name']) && $row['mort_doc_noc_name'] != null){?>
                            <input type="text" class="form-control" value='<?php if(!is_numeric($row['mort_doc_noc_name'])){echo $row['mort_doc_noc_name'];}else{echo getfamName($connect, $row['mort_doc_noc_name']);}?>' readonly>
                        <?php } ?>
                    </td>

                    
                    <td><input type='checkbox' id='mort_check' name='mort_check' class="form-control mort_check" <?php if($row['mortgage_document_noc'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of ack_documentation list table?>' data-thing='document' tabindex='26'></td>
                </tr>
                    <?php
                }?>

    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>