<?php
include('../ajaxconfig.php');
include('../moneyFormatIndia.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

function getfamName($connect,$rel_id){
    $qry1=$connect->query("SELECT famname FROM `verification_family_info` where id=$rel_id");
    $run=$qry1->fetch();
    return $run['famname'];
}
?>
<table class="table custom-table" id='goldTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Gold Type</th>
            <th>Purity</th>
            <th>Count</th>
            <th>Weight</th>
            <th>Value</th>
            <th>Upload</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        $qry = $connect->query("SELECT gold_Count, gold_Weight, gold_Value, gold_type, Purity, gold_upload FROM `gold_info` WHERE req_id IN ($req_id) and used_status != '1' ");
        $cnt = 0;
        $weight = 0;
        $goldVal = 0;
        while($row = $qry->fetch()){
            $cnt += intval($row['gold_Count']);
            $weight += intval($row["gold_Weight"]);
            $goldVal += intval($row["gold_Value"]);
        ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['gold_type'];?></td>
                <td><?php echo $row['Purity'];?></td>
                <td><?php echo $row['gold_Count'];?></td>
                <td><?php echo $row['gold_Weight'];?></td>
                <td><?php echo moneyFormatIndia($row['gold_Value']);?></td>
                <td><a href='<?php echo 'uploads/gold_info/'.$row['gold_upload'];?>' target="_blank"><?php echo $row['gold_upload'];?></a></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
    <tr>
        <td> <b> Total </b> </td>
        <td> </td>
        <td> </td>
        <td> <b> <?php echo $cnt; ?> </b> </td>
        <td> <b> <?php echo $weight; ?> </b> </td>
        <td> <b> <?php echo moneyFormatIndia($goldVal); ?> </b> </td>
    </tr>
</table>

<?php
// Close the database connection
$connect = null;
?>