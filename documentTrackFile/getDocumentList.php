<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

function getfamName($connect,$rel_id){
    $qry1=$connect->query("SELECT famname FROM `verification_family_info` WHERE id=$rel_id");
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
            <th>Uploads</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        $k=1;
            
            $qry = $connect->query("SELECT ac.doc_name, ac.doc_type, ac.doc_upload, ac.doc_holder, ac.holder_name, ac.relation_name, fam.famname FROM document_info ac LEFT JOIN verification_family_info fam ON ac.relation_name = fam.id WHERE ac.req_id IN ($req_id) ");

            while($row = $qry->fetch()){
                $upd_arr = explode(',',$row['doc_upload']);
                for($i=0;$i<sizeof($upd_arr);$i++){
                    ?>
                    <tr>
                        <td><?php echo $k++;?></td>
                        <td><?php echo $row['doc_name'];?></td>
                        <td><?php if($row['doc_type'] == '0'){echo 'Original';}elseif($row['doc_type'] == '1'){echo 'Xerox';};?></td>
                        <td><?php if($row['doc_holder'] != '2'){echo $row['holder_name'];}else{echo $row['famname'];}?></td>
                        <td><a href='<?php echo 'uploads/verification/doc_info/'.$upd_arr[$i];?>' target="_blank"><?php echo $upd_arr[$i];?></a></td>
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