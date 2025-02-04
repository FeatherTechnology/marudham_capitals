<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="signed_table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Doc Name </th>
            <th> Sign Type </th>
            <th> Relationship </th>
            <th> Count </th>
            <th> Uploads </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $signInfo = $connect->query("SELECT * FROM `signed_doc_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($signedDoc = $signInfo->fetch()) {
            $fam_id = $signedDoc["signType_relationship"];
            $result = $connect->query("SELECT famname,relationship FROM `verification_family_info` where id='$fam_id'");
            $row = $result->fetch();

            $doc_upd_name = '';
            $id = $signedDoc["id"];
            $updresult = $connect->query("SELECT upload_doc_name FROM `signed_doc` where signed_doc_id = '$id'");
            $a = 1;
            while ($upd = $updresult->fetch()) {
                $docName = $upd['upload_doc_name'];
                $doc_upd_name .= "<a href=uploads/verification/signed_doc/";
                $doc_upd_name .= $docName;
                $doc_upd_name .= " target='_blank'>";
                $doc_upd_name .=  $docName . ' ';
                $doc_upd_name .= "</a>";
                $a++;
            }
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>

                <td>Signed Document</td>

                <td> <?php if ($signedDoc["sign_type"] == '0') {
                            echo 'Customer';
                        } elseif ($signedDoc["sign_type"] == '1') {
                            echo 'Guarantor';
                        } elseif ($signedDoc["sign_type"] == '2') {
                            echo 'Combined';
                        } elseif ($signedDoc["sign_type"] == '3') {
                            echo 'Family Members';
                        } ?></td>

                <td> <?php if ($signedDoc["sign_type"] == '3' or $signedDoc["sign_type"] == '1' or $signedDoc["sign_type"] == '2') {
                            echo $row["famname"] . ' - ' . $row["relationship"];
                        } else {
                            echo 'NIL';
                        } ?></td>

                <td> <?php echo $signedDoc['doc_Count']; ?></td>
                <td><?php echo $doc_upd_name; ?></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#signed_table').DataTable({
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