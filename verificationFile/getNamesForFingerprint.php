<?php
include '../ajaxconfig.php';

$req_id = $_POST['req_id'];
$cus_name = $_POST['cus_name'];
$cus_id = $_POST['cus_id'];

?>

<table class="table custom-table fingerprintTable">
    <thead>
        <tr>
            <th> S.No </th>
            <th> Name </th>
            <th> Relationship </th>
            <th width='700px'> Fingerprint </th>
        </tr>
    </thead>
    <tbody>

        <tr height='70px'>
            <td><?php echo '1'; ?></td>
            <td><input type='hidden' id='adhar_print' name='adhar_print[]' value='<?php echo $cus_id; ?>'><?php echo $cus_name; ?></td>
            <td><input type='hidden' id='name_print' name='name_print[]' value='<?php echo $cus_name; ?>'><?php echo 'Customer'; ?></td>
            <td>
                <select type='text' id='hand_selection' name='hand_selection[]' class='btn hand_selection' style="border: #009688 1px solid;height: 38px;" tabindex='42'>
                    <option value=''>Select Hand</option>
                    <option value='1'>Left Hand</option>
                    <option value='2'>Right Hand</option>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class='btn btn-success scanBtn' style='background-color:#009688;' onclick="event.preventDefault()" title='Put Your Thumb' tabindex='42'><i class="material-icons" id="icon-flipped">&#xe90d;</i>&nbsp;Scan</button>
                <input type='hidden' id='fingerprint' name='fingerprint[]'>
            </td>
        </tr>

        <?php

        $qry = $connect->query("SELECT * FROM `verification_family_info` WHERE `cus_id`='$cus_id' ");

        $i = 2;
        while ($row = $qry->fetch()) {
        ?>
            <tr height='70px'>
                <td><?php echo $i; ?></td>
                <td><input type='hidden' id='adhar_print' name='adhar_print[]' value='<?php echo $row['relation_aadhar']; ?>'><?php echo $row["famname"]; ?></td>
                <td><input type='hidden' id='name_print' name='name_print[]' value='<?php echo $row['famname']; ?>'><?php echo $row["relationship"]; ?></td>
                <td>
                    <select type='text' id='hand_selection' name='hand_selection[]' class='btn hand_selection' style="border: #009688 1px solid;height: 38px;" tabindex='42'>
                        <option value=''>Select Hand</option>
                        <option value='1'>Left Hand</option>
                        <option value='2'>Right Hand</option>
                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class='btn btn-success scanBtn' style='background-color:#009688;' onclick="event.preventDefault()" title='Put Your Thumb' tabindex='42'><i class="material-icons" id="icon-flipped">&#xe90d;</i>&nbsp;Scan</button>
                    <input type='hidden' id='fingerprint' name='fingerprint[]'>
                </td>

            </tr>

        <?php
            $i++;
        }
        ?>
    </tbody>
</table>
<?php
// Close the database connection
$connect = null;
?>