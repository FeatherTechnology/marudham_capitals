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
        $qry = $connect->query("SELECT mortgage_document, mortgage_document_pending, mortgage_document_used FROM acknowlegement_documentation WHERE req_id = $req_id ");
        $row = $qry->fetch();
        if($row['mortgage_document'] == '0' && $row['mortgage_document_used'] != '1'){
        ?>
            <tr>
                <td>1</td>
                <td>Mortgage Document</td>        
            </tr>
            
        <?php } ?>

    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>