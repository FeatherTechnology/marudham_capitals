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
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];       
        $signInfo = $connect->query("SELECT sdi.id, sign_type, signType_relationship, doc_Count, GROUP_CONCAT(upload_doc_name) AS doc_name, vfi.famname, vfi.relationship FROM signed_doc_info sdi LEFT JOIN signed_doc sd ON sdi.id = sd.signed_doc_id LEFT JOIN verification_family_info vfi ON sdi.signType_relationship = vfi.id WHERE sdi.req_id = '$req_id' GROUP BY sdi.id ORDER BY sdi.id DESC");
   
        $i = 1;
        $signType = ['0' => 'Customer', '1' => 'Guarantor', '2' => 'Combined', '3' => 'Family Members'];
        while ($signedDoc = $signInfo->fetch()) {

            $doc_upd_name = '';
            $doc = explode(',', $signedDoc['doc_name']);
            foreach ($doc as $docName) {
                $doc_upd_name .= "<a href='uploads/verification/signed_doc/$docName' target='_blank' style='color: #4ba39b;'>$docName</a>,  ";
            }
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>
                <td>Signed Document</td>
                <td> <?php echo $signType[$signedDoc['sign_type']] ?? ''; ?></td>
                <td> 
                    <?php 
                        if($signedDoc["sign_type"] == '1' || $signedDoc["sign_type"] == '2' || $signedDoc["sign_type"] == '3'){ 
                            echo $signedDoc["famname"].' - '.$signedDoc["relationship"];
                        } else {
                            echo 'NIL';
                        } 
                    ?>
                </td>          
                <td> <?php echo $signedDoc['doc_Count']; ?></td>
                <td><?php echo rtrim($doc_upd_name, ', '); ?></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>