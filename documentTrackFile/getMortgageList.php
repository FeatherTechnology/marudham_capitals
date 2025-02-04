<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_name'])){
    $cus_name = $_POST['cus_name'];
}


?>
<table class="table custom-table" id='mortgageTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Details</th> 
        </tr>
    </thead>
    <tbody>

        <?php
        $i=1;
        $qry = $connect->query("SELECT * from acknowlegement_documentation where req_id=$req_id ");
        $row = $qry->fetch();
        ?>
                <?php if($row['mortgage_document'] == '0' && $row['mortgage_document_pending'] != 'YES' && $row['mortgage_document_used'] != '1'){
                    ?>
                <tr>
                    <td></td>
                    <td>Mortgage Document</td>
                        
                </tr>
                    <?php
                }?>

    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>