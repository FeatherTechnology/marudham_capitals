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
        $qry = $connect->query("SELECT en_RC, en_Key FROM acknowlegement_documentation WHERE $condition");
        while($row = $qry->fetch()){
        if($row['en_RC'] == '0' ){
        ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td>RC</td>
            </tr>

        <?php } if($row['en_Key'] == '0' ){ ?>
            
            <tr>
                <td><?php echo $i++;?></td>
                <td>Key</td>
            </tr>

        <?php } }?>

    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>