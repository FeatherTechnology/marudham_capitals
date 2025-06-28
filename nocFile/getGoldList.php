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
            <th>Date Of NOC</th>
            <th>NOC Person</th>
            <th>Name</th>
            <th>Checklist</th>
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

                <td><span id='gold_noc_date' name='gold_noc_date' class="gold_noc_date"><?php if($row['noc_date'] != ''){echo date('d-m-Y',strtotime($row['noc_date']));}?></span></td>
                <td>
                    <select id='gold_noc_per' name='gold_noc_per' class="form-control gold_noc_per" <?php if($row['noc_person'] != '' && $row['noc_person'] != null){echo 'disabled';}else{?>style="display:none" <?php }?>>
                        <option value=''>Select Type</option>
                        <option value='1' <?php if(isset($row['noc_person']) && $row['noc_person'] == 1){echo 'selected';}?>>Customer</option>
                        <option value='2' <?php if(isset($row['noc_person']) && $row['noc_person'] == 2){echo 'selected';}?>>Family Member</option>
                    </select>
                </td>
                <td>
                    <?php if(isset($row['noc_name']) && $row['noc_name'] != null){?>
                        <input type="text" class="form-control" value='<?php if(!is_numeric($row['noc_name'])){echo $row['noc_name'];}else{echo getfamName($connect, $row['noc_name']);}?>' readonly>
                    <?php } ?>
                </td>

                <td>
                    <?php #if($row['temp_sts'] == '0'){ ?>
                        <input type='checkbox' id='gold_check' name='gold_check' class="form-control gold_check"  <?php if($row['noc_given'] == '1') echo 'checked disabled';?> data-value='<?php echo $row['id'];//id of docuemnts uploaded table?>' ></td>
                    <?php #}else if($row['temp_sts'] == '1'){?>
                        <!-- <label>Not Available</label> -->
                    <?php #} ?>
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
        <td> </td>
    </tr>
</table>

<?php
// Close the database connection
$connect = null;
?>