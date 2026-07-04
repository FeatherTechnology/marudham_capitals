<?php
include '../ajaxconfig.php';

$cusid = $_POST['cus_id'];
?>

<table class="table custom-table fingerprintTable">
    <thead>
        <tr>
            <th> S.No </th>
            <th> Name </th>
            <th> Relationship </th>
            <th> Status </th>
            <th width='600px'> Fingerprint </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $qry = $connect->prepare("SELECT
                    cr.cus_id AS aadhaar_no,
                    cr.customer_name AS name,
                    'Customer' AS relationship,
                    MAX(CASE WHEN f.hand = '1' THEN 'Added' END) AS left_hand,
                    MAX(CASE WHEN f.hand = '2' THEN 'Added' END) AS right_hand
                FROM customer_register cr
                LEFT JOIN fingerprints f
                    ON cr.cus_id = f.adhar_num
                WHERE cr.cus_id = ?
                GROUP BY cr.cus_id, cr.customer_name

                UNION ALL

                SELECT
                    fi.relation_aadhar AS aadhaar_no,
                    fi.famname AS name,
                    fi.relationship,
                    MAX(CASE WHEN f.hand = '1' THEN 'Added' END) AS left_hand,
                    MAX(CASE WHEN f.hand = '2' THEN 'Added' END) AS right_hand
                FROM verification_family_info fi
                LEFT JOIN fingerprints f
                    ON fi.relation_aadhar = f.adhar_num
                WHERE fi.cus_id = ?
                GROUP BY fi.relation_aadhar, fi.famname, fi.relationship");

            $qry->execute([$cusid, $cusid]);

            $i = 1;
            while ($row = $qry->fetch()) {

                $left = ($row['left_hand'] =='Added') ? 'badge-success' : 'badge-danger';
                $right = ($row['right_hand'] =='Added') ? 'badge-success' : 'badge-danger';
            ?>
                <tr height='70px'>
                    <td><?php echo $i++; ?></td>
                    <td><input type='hidden' id='adhar_print' name='adhar_print[]' value='<?php echo $row['name']; ?>' data-no='<?php echo $row['aadhaar_no']; ?>'><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["relationship"]; ?></td>
                    <td><span class="badge badge-pill <?= $left ?>">L</span> &nbsp;&nbsp; <span class="badge badge-pill <?= $right ?>">R</span></td>
                    <td>
                        <select type='text' id='hand_selection' name='hand_selection[]' class='btn hand_selection' style="border: #009688 1px solid;height: 38px;" tabindex='42'>
                            <option value=''>Select Hand</option>
                            <option value='1'>Left Hand</option>
                            <option value='2'>Right Hand</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class='btn btn-success scanBtn' style='background-color:#009688;' onclick="event.preventDefault()" title='Put Your Thumb' tabindex='42'><i class="material-icons" id="icon-flipped">&#xe90d;</i>&nbsp;Scan</button>
                    </td>
                </tr>
        <?php } ?>
    </tbody>
</table>

<?php
// Close the database connection
$connect = null;
?>