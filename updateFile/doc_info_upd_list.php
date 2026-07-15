<?php
include '../ajaxconfig.php';

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
        </tr>
    </thead>
    <tbody>

        <?php
        $qry = $connect->query("SELECT di.doc_name, di.doc_detail, di.doc_type, di.doc_holder, di.holder_name, di.relation, di.doc_upload, vfi.famname 
                    FROM document_info di
                    LEFT JOIN verification_family_info vfi ON di.relation_name = vfi.id
                    WHERE di.req_id = '$req_id' ORDER BY di.id DESC");

        $i = 1;
        while ($row = $qry->fetch()) {
            $holder_name = ($row["holder_name"] == '') ? $row['famname'] : $row["holder_name"];

            $docUpd = explode(',', $row["doc_upload"]);
        ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row["doc_name"]; ?></td>
                <td><?php echo $row["doc_detail"]; ?></td>
                <td><?php if($row["doc_type"] == '0'){ echo 'Original';}else if($row["doc_type"] == '1'){echo 'Xerox'; } ?></td>
                <td><?php if($row["doc_holder"] == '0'){ echo 'Customer';}else if($row["doc_holder"] == '1'){echo 'Guarentor'; }elseif($row["doc_holder"] == '2'){echo 'Family Member';} ?></td>
                <td><?php echo $holder_name; ?></td>
                <td><?php echo $row["relation"]; ?></td>
                <td><?php $text='';
                    foreach($docUpd as $upd){
                        $text .= '<a href="uploads/verification/doc_info/'.$upd.'" target="_blank" title="View Document" style="color: #4ba39b;"> ' .$upd.  '</a>, ';
                    }
                    echo rtrim($text,', ');// to trim the comma at end ?>
                </td>
            </tr>

        <?php  } ?>
    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>