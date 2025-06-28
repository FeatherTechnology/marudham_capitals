<?php
include '../ajaxconfig.php';

if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

?>

<table class="table custom-table" id="document_table">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Document Name </th>
            <th> Document Details</th>
            <th> Document Type </th>
            <th> Document Holder</th>
            <th> Holder Name</th>
            <th> Relationship</th>
            <th> Document </th>
            <!-- <th> Availability </th> -->
            <!-- <th> Action </th> -->
        </tr>
    </thead>
    <tbody>

        <?php
        $qry = $connect->query("SELECT * FROM `document_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($row = $qry->fetch()) {
            $docUpd = explode(',',$row["doc_upload"]);

            // $temp_sts = $row['temp_sts'];
            $id = $row['id'];

            if($row["holder_name"] == ''){
                $qry1 = $connect->query("SELECT * FROM verification_family_info where id = '".$row['relation_name']."' ");
                $holder_name = $qry1->fetch()['famname'];
            }else{
                $holder_name = $row["holder_name"];
            }
        ?>
            <tr>
                <td><?php echo $i; $i++;?></td>
                <td><?php echo $row["doc_name"]; ?></td>
                <td><?php echo $row["doc_detail"]; ?></td>
                <td><?php if($row["doc_type"] == '0'){ echo 'Original';}else if($row["doc_type"] == '1'){echo 'Xerox'; } ?></td>
                <td><?php if($row["doc_holder"] == '0'){ echo 'Customer';}else if($row["doc_holder"] == '1'){echo 'Guarentor'; }elseif($row["doc_holder"] == '2'){echo 'Family Member';} ?></td>
                <td><?php echo $holder_name; ?></td>
                <td><?php echo $row["relation"]; ?></td>
                <td><?php $text='';
                foreach($docUpd as $upd){
                    $text .= '<a href="uploads/verification/doc_info/'.$upd.'" target="_blank" title="View Document" > ' .$upd.  '</a>, ';
                }
                echo rtrim($text,', ');// to trim the comma at end ?></td>

                <?php #if(!empty($row["doc_upload"])){ //check if file not receveived ?>
                    <!-- <td><?php #echo $temp_sts == 0 ? 'YES':'NO'; ?></td> -->
                    <!-- <td> -->
                        <?php #if($temp_sts == 0){//zero means document available,so show button for take out as temprory ?>
                            <!-- <button class="btn btn-danger temp-take-out" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='document' data-toggle='modal' data-target='.temp-take-out-modal'>Take Out</button> -->
                        <?php #}else if($temp_sts == 1){//one means document not available, taken for temp purpose?>
                            <!-- <button class="btn btn-success temp-take-in" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='document' data-toggle='modal' data-target='.temp-take-in-modal'>Take In</button> -->
                        <?php #} ?>
                    <!-- </td> -->
                <?php # }else{?>
                    <!-- <td></td>
                    <td></td> -->
                <?php # } ?>
            </tr>

        <?php  } ?>
    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>

