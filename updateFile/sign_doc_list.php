<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="signed_table">
    <thead>
        <tr>
        <th width="50"> S.No </th>
		<th> Doc Name </th>
		<th> Sign Type </th>
		<th> Relationship </th>
		<th> Count </th>
		<th> Document </th>
		<!-- <th> Availability </th> -->
		<!-- <th> Action </th> -->
		<!-- <th> NOC Status </th> -->
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];        
        $signInfo = $connect->query("SELECT * FROM `signed_doc_info` where req_id = '$req_id'");
        // $signInfo = $connect->query("SELECT a.* FROM signed_doc_info a JOIN signed_doc b ON a.id = b.signed_doc_id where a.req_id = '$req_id' and b.temp_sts= 1 ");//1 means not taken for temp purpose

        $i = 1;
        while ($signedDoc = $signInfo->fetch()) {
            $fam_id = $signedDoc["signType_relationship"];
            $result = $connect->query("SELECT famname,relationship FROM `verification_family_info` where id='$fam_id'");
            $row = $result->fetch();

            $doc_upd_name = '';
            $id = $signedDoc["id"];

            $updresult = $connect->query("SELECT upload_doc_name,temp_sts FROM `signed_doc` where signed_doc_id = '$id'"); 
            // echo $updresult->queryString;
            $a = 1;
            while($upd = $updresult->fetch()){
                $docName = $upd['upload_doc_name'];
                // $temp_sts = $upd["temp_sts"];
                $doc_upd_name .= "<a href=uploads/verification/signed_doc/";
                $doc_upd_name .= $docName ;
                $doc_upd_name .= " target='_blank'>";
                $doc_upd_name .=  $docName. ' ' ;
                $doc_upd_name .= "</a>" ;
                $a++;
            }
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>

                <td>Signed Document</td>

                <td> <?php if($signedDoc["sign_type"] == '0'){ echo 'Customer';}elseif($signedDoc["sign_type"] == '1'){ echo 'Guarantor';}elseif($signedDoc["sign_type"] == '2'){ echo 'Combined';}elseif($signedDoc["sign_type"] == '3'){ echo 'Family Members';} ?></td>

                <td> <?php if($signedDoc["sign_type"] == '3' or $signedDoc["sign_type"] == '1' or $signedDoc["sign_type"] == '2'){ echo $row["famname"].' - '.$row["relationship"];}else{echo 'NIL';} ?></td>
                
                <td> <?php echo $signedDoc['doc_Count']; ?></td>
                <td><?php echo $doc_upd_name; ?></td>
                <?php 
                // if($doc_upd_name != ''){
                ?>
                    <!-- <td><?php #echo $temp_sts == 0 ? 'YES':'NO'; ?></td> -->
                    <!-- <td> -->
                        <?php #if($temp_sts == 0){//zero means document available,so show button for take out as temprory ?>
                            <!-- <button class="btn btn-danger temp-take-out" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='sign' data-toggle='modal' data-target='.temp-take-out-modal'>Take Out</button> -->
                        <?php #}else if($temp_sts == 1){//one means document not available, taken for temp purpose?>
                            <!-- <button class="btn btn-success temp-take-in" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='sign' data-toggle='modal' data-target='.temp-take-in-modal'>Take In</button> -->
                        <?php #} ?>
                    <!-- </td> -->
                <?php #}else{ ?>
                    <!-- <td></td>
                    <td></td> -->
                <?php #} ?>
                
                <!-- <td><?php if($signedDoc['noc_given'] == '1'){echo 'NOC Given';}else{echo '';} ?></td> -->
            </tr>

        <?php  } ?>
    </tbody>
</table>

