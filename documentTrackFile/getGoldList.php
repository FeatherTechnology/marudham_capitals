<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_name'])){
    $cus_name = $_POST['cus_name'];
}
function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
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
        $qry = $connect->query("SELECT * FROM `gold_info` where req_id = $req_id and used_status != '1' ");
        $cnt = 0;
        $weight = 0;
        $goldVal = 0;
        while($row = $qry->fetch()){
            $cnt = $cnt + intval($row['gold_Count']);
            $weight = $weight + intval($row["gold_Weight"]);
            $goldVal = $goldVal + intval($row["gold_Value"]);
        ?>
            <tr>
                <td><?php echo $i;$i++;?></td>
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