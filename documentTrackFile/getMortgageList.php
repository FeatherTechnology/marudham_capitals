<?php
include('../ajaxconfig.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$useINcondition = $_POST['useINcondition'] ?? 0;

if($useINcondition =='1'){ //if combined then show current doc + replace doc in same table to combine in document track.
    $condition = "req_id IN ($req_id)";
} else{
    $condition = "req_id = $req_id";
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
        $qry = $connect->query("SELECT mortgage_document, mortgage_document_pending, mortgage_document_used FROM acknowlegement_documentation WHERE $condition ");
        while($row = $qry->fetch()){
        if($row['mortgage_document'] == '0' && $row['mortgage_document_used'] != '1'){
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td>Mortgage Document</td>        
            </tr>
        <?php } }?>
    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>