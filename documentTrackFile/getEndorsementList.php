<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_name'])){
    $cus_name = $_POST['cus_name'];
}

?>
<table class="table custom-table" id='endorsementTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Details</th> 
        </tr>
    </thead>
    <tbody>

        <?php
        $i=1;
        $qry = $connect->query("SELECT * from acknowlegement_documentation  where req_id=$req_id");
        $row = $qry->fetch();
        ?>
                <?php if($row['en_RC'] == '0' && $row['Rc_document_pending'] != 'YES' ){
                    ?>
                <tr>
                    <td><?php echo $i;$i++;?></td>
                    <td>RC</td>

                </tr>
                    <?php
                }?>
                <?php if($row['en_Key'] == '0' ){
                    ?>
                <tr>
                    <td><?php echo $i;$i++;?></td>
                    <td>Key</td>

                </tr>
                    <?php
                }?>

    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>