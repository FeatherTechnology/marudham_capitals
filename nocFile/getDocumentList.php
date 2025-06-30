<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_name'])){
    $cus_name = $_POST['cus_name'];
}
function moneyFormatIndia($num)
{
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
    return $thecash;
}
function getfamName($connect,$rel_id){
    $qry1=$connect->query("SELECT famname FROM `verification_family_info` where id=$rel_id");
    $run=$qry1->fetch();
    return $run['famname'];
}
?>
<table class="table custom-table" id='documentTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Document Name</th> 
            <th>Document Type</th> 
            <th>Document Holder</th> 
            <th>Document</th> 
            <th>Date Of NOC</th>
            <th>NOC Person</th>
            <th>Name</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $k=1;
            // $qry = $connect->query("SELECT ac.id,ac.document_name,ac.document_type,ac.doc_info_upload,ac.document_holder,ac.docholder_name,ac.docholder_relationship_name,ac.doc_info_upload_noc,fam.famname from acknowlegement_documentation ac Left JOIN verification_family_info fam ON ac.docholder_relationship_name = fam.id where ac.req_id = $req_id and ac.doc_info_upload_used != '1' ");
            $qry = $connect->query("SELECT ac.id as doc_id,ac.doc_name,ac.doc_type,ac.doc_upload,ac.doc_holder,ac.holder_name,ac.`relation_name`,ac.`doc_info_upload_noc`,ac.noc_date,ac.noc_person,ac.noc_name,ac.temp_sts,fam.famname,fam.id
            from document_info ac Left JOIN verification_family_info fam ON ac.relation_name = fam.id where ac.req_id = $req_id and ac.doc_info_upload_used != '1' ");

            while($row = $qry->fetch()){
                $upd_arr = explode(',',$row['doc_upload']);
                for($i=0;$i<sizeof($upd_arr);$i++){
                    ?>
                    <tr>
                        <td><?php echo $k;$k++;?></td>
                        <td><?php echo $row['doc_name'];?></td>
                        <td><?php if($row['doc_type'] == '0'){echo 'Original';}elseif($row['doc_type'] == '1'){echo 'Xerox';};?></td>
                        <td><?php if($row['doc_holder'] != '2'){echo $row['holder_name'];}else{echo $row['famname'];}?></td>
                        <td><a href='<?php echo 'uploads/verification/doc_info/'.$upd_arr[$i];?>' target="_blank"><?php echo $upd_arr[$i];?></a></td>
                        
                        <td><span id='doc_noc_date' name='doc_noc_date' class="doc_noc_date"><?php if($row['noc_date'] != ''){echo date('d-m-Y',strtotime($row['noc_date']));}?></span></td>
                        <td>
                            <select id='doc_noc_per' name='doc_noc_per' class="form-control doc_noc_per" <?php if($row['noc_person'] != '' && $row['noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
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
                                <input type='checkbox' id='doc_check' name='doc_check' class="form-control doc_check"  <?php if($row['doc_info_upload_noc'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['doc_id'];//name of uploaded document?>' tabindex='38'></td>
                            <?php #}else if($row['temp_sts'] == '1'){?>
                                <!-- <label>Not Available</label> -->
                            <?php #} ?>
                    </tr>
                <?php
                }
            }
        ?>
    </tbody>
    
</table>

<?php 
// Close the database connection
$connect = null;
?>