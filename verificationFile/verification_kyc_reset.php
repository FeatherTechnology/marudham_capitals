<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="kyc_data_table">
    <thead>
        <tr>
            <th width="20%"> S.No </th>
            <th> Proof of </th>
            <th> Name </th>
            <th> Relationship </th>
            <th> Proof type </th>
            <th> Proof Number </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $kycInfo = $connect->query("SELECT * FROM `verification_kyc_info` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($kyc = $kycInfo->fetch()) {
            if ($kyc['proofOf'] == '0') {
                $proof_Of = "Customer";
            } else
            if ($kyc['proofOf'] == '1') {
                $proof_Of = "Guarantor";
            } else
            if ($kyc['proofOf'] == '2') {
                $proof_Of = "Family Members";
            } else
            if ($kyc['proofOf'] == '3') {
                $proof_Of = "Group Members";
            }

            if ($kyc['proof_type'] == '1') {
                $proof_type = "Aadhar";
            } else
            if ($kyc['proof_type'] == '2') {
                $proof_type = "Smart Card";
            } else
            if ($kyc['proof_type'] == '3') {
                $proof_type = "Voter ID";
            } else
            if ($kyc['proof_type'] == '4') {
                $proof_type = "Driving License";
            } else
            if ($kyc['proof_type'] == '5') {
                $proof_type = "PAN Card";
            } else
            if ($kyc['proof_type'] == '6') {
                $proof_type = "Passport";
            } else
            if ($kyc['proof_type'] == '7') {
                $proof_type = "Occupation ID";
            } else
            if ($kyc['proof_type'] == '8') {
                $proof_type = "Salary Slip";
            } else
            if ($kyc['proof_type'] == '9') {
                $proof_type = "Bank statement";
            } else
            if ($kyc['proof_type'] == '10') {
                $proof_type = "EB Bill";
            } else
            if ($kyc['proof_type'] == '11') {
                $proof_type = "Business Proof";
            } else
            if ($kyc['proof_type'] == '12') {
                $proof_type = "Own House Proof";
            } else
            if ($kyc['proof_type'] == '13') {
                $proof_type = "Others";
            }

            $fam_mem_id = $kyc['fam_mem'];

            $relationship = 'NIL';
            if ($kyc['proofOf'] == '2') {
                $sql = $connect->query("SELECT famname, relationship FROM `verification_family_info` where id = '$fam_mem_id'");
                $rw= $sql->fetch();
                $fam_mem = $rw['famname']?? '';
                $relationship = $rw['relationship']?? '';
            } elseif ($kyc['proofOf'] == '1') {
                $qry = $connect->query("SELECT a.famname, a.relationship from verification_family_info a 
                LEFT JOIN customer_profile b ON a.id = b.guarentor_name
                where b.req_id = '$req_id' ");
                $rw = $qry->fetch();
                $fam_mem = $rw['famname'] ?? '';
                $relationship = $rw['relationship'] ?? '';
                // mysqli_next_result($connect); // Move to the next query
            } elseif ($kyc['proofOf'] == '0') {
                $qry = $connect->query("CALL get_cus_name($cus_id)");
                $fam_mem = $qry->fetch()['customer_name'] ?? '';
                // Close the cursor to free the connection
                $qry->closeCursor();
                $relationship = 'NIL';
                // mysqli_next_result($connect); // Move to the next query
            }
        ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $proof_Of; ?></td>
                <td><?php echo $fam_mem; ?></td>
                <td><?php echo $relationship; ?></td>
                <td><?php echo $proof_type; ?></td>
                <td><?php echo $kyc["proof_no"]; ?></td>
                <td>
                    <a id="verification_kyc_edit" value="<?php echo $kyc['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="verification_kyc_delete" value="<?php echo $kyc['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#kyc_data_table').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function(settings) {
                this.api().column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
                searchFunction('kyc_data_table');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });
    });
</script>
<?php
// Close the database connection
$connect = null;
?>