<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="cheque_table">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Holder type </th>
            <th> Holder Name </th>
            <th> Relationship </th>
            <th> Bank Name </th>
            <th> Cheque Count </th>
            <th> Cheque No </th>
            <th> Document </th>
            <!-- <th> Availability </th> -->
            <!-- <th> Action </th> -->
            
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $chequeInfo = $connect->query("SELECT * FROM `cheque_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($cheque = $chequeInfo->fetch()) {
            $fam_id = $cheque["holder_relationship_name"];
            $result = $connect->query("SELECT famname FROM `verification_family_info` where id='$fam_id'");
            $row = $result->fetch();

            $doc_upd_name = '';
            $id = $cheque["id"];
            $updresult = $connect->query("SELECT upload_cheque_name FROM `cheque_upd` where cheque_table_id = '$id'");
            $a = 1;
            while($upd = $updresult->fetch()){
                $docName = $upd['upload_cheque_name'];
                $doc_upd_name .= "<a href=uploads/verification/cheque_upd/ ";
                $doc_upd_name .= $docName ;
                $doc_upd_name .= " target='_blank' >";
                $doc_upd_name .=  $docName. ' ' ;
                $doc_upd_name .= "</a>, " ;
                $a++;
            }
            
            $cheque_no ='';
            // $temp_sts = '';
            $updnoresult = $connect->query("SELECT cheque_no,noc_given,temp_sts FROM `cheque_no_list` where cheque_table_id = '$id' and used_status = 0 ");
            if($updnoresult){
                // $temp_sts = '0';

                while($updno = $updnoresult->fetch()){
                    // $temp_sts = ($temp_sts=='0') ? $updno["temp_sts"] : $temp_sts;
                    $no = $updno['cheque_no'];
                    $noc_given[] = $updno['noc_given'];
                    $cheque_no .= $no.', ';
                }
            }
        ?>
            <tr>
                <td><?php echo $i; ?></td>

                <td><?php if ($cheque["holder_type"] == '0') {
                        echo 'Customer';
                    } elseif ($cheque["holder_type"] == '1') {
                        echo 'Guarantor';
                    } elseif ($cheque["holder_type"] == '2') {
                        echo 'Family Members';
                    }  ?></td>

                <td> <?php if ($cheque["holder_type"] == '0' || $cheque["holder_type"] == '1') {
                            echo $cheque["holder_name"];
                        } elseif ($cheque["holder_type"] == '2') {
                            echo $row["famname"];
                        } ?></td>
                <td><?php echo $cheque["cheque_relation"]; ?></td>
                <td><?php echo $cheque["chequebank_name"]; ?></td>
                <td><?php echo $cheque["cheque_count"]; ?></td>
                <td><?php echo rtrim($cheque_no,', '); // to trim the comma at end?></td>
                <td><?php echo rtrim($doc_upd_name,', ');// to trim the comma at end ?></td>
                
                <?php 
                // if(!empty($doc_upd_name)){ //show temp contents only if document received already ?>
                    <!-- <td><?php #echo $temp_sts == 0 ? 'YES':'NO'; ?></td> -->
                    <!-- <td> -->
                        <?php #if($temp_sts == 0){//zero means document available,so show button for take out as temprory ?>
                            <!-- <button class="btn btn-danger temp-take-out" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='cheque' data-toggle='modal' data-target='.temp-take-out-modal'>Take Out</button> -->
                        <?php #}else if($temp_sts == 1){//one means document not available, taken for temp purpose?>
                            <!-- <button class="btn btn-success temp-take-in" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='cheque' data-toggle='modal' data-target='.temp-take-in-modal'>Take In</button> -->
                        <?php #} ?>
                    <!-- </td> -->
                <?php #}else{ ?>
                    <!-- <td></td>
                    <td></td> -->
                <?php #} ?>

            </tr>

        <?php  } ?>
    </tbody>
</table>


