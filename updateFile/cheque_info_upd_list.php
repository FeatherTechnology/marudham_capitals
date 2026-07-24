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
        $chequeInfo = $connect->query("SELECT ci.id, holder_type, holder_name, holder_relationship_name, cheque_relation, chequebank_name, cheque_count, vfi.famname, GROUP_CONCAT(upload_cheque_name) AS doc_name 
        FROM `cheque_info` ci 
        LEFT JOIN verification_family_info vfi ON ci.holder_relationship_name = vfi.id 
        LEFT JOIN cheque_upd cu ON ci.id = cu.cheque_table_id
        WHERE ci.req_id = '$req_id' 
        GROUP BY ci.id ORDER BY ci.id DESC");

        $i = 1;
        $holderType = ['0' => 'Customer', '1' => 'Guarantor', '2' => 'Family Members'];
        while ($cheque = $chequeInfo->fetch()) {

            $id = $cheque["id"];
            $doc_upd_name = '';
            $doc = explode(',', $cheque['doc_name']);
            foreach ($doc as $docName) {
                $doc_upd_name .= "<a href='uploads/verification/cheque_upd/$docName' target='_blank' style='color: #4ba39b;'>$docName</a>,  ";
            }
            
            $cheque_no ='';
            $updnoresult = $connect->query("SELECT cheque_no FROM `cheque_no_list` where cheque_table_id = '$id' and used_status = 0 ");
            if($updnoresult){
                while($updno = $updnoresult->fetch()){
                    $no = $updno['cheque_no'];
                    $cheque_no .= $no.', ';
                }
            }
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $holderType[$cheque['holder_type']] ?? ''; ?></td>
                <td> 
                    <?php 
                        if ($cheque["holder_type"] == '0' || $cheque["holder_type"] == '1') {
                            echo $cheque["holder_name"];
                        } elseif ($cheque["holder_type"] == '2') {
                            echo $cheque["famname"];
                        } 
                    ?>
                </td>
                <td><?php echo $cheque["cheque_relation"]; ?></td>
                <td><?php echo $cheque["chequebank_name"]; ?></td>
                <td><?php echo $cheque["cheque_count"]; ?></td>
                <td><?php echo rtrim($cheque_no,', '); // to trim the comma at end?></td>
                <td><?php echo rtrim($doc_upd_name,', ');// to trim the comma at end ?></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>


