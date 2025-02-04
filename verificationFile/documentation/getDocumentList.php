<?php
include('../ajaxconfig.php');
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_name'])) {
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

?>
<table class="table custom-table" id='documentTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Document Name</th>
            <th>Document Type</th>
            <th>Document Holder</th>
            <th>Document</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // $qry = $connect->query("SELECT ac.id,ac.document_name,ac.document_type,ac.doc_info_upload,ac.document_holder,ac.docholder_name,ac.docholder_relationship_name,ac.doc_info_upload_noc,fam.famname from acknowlegement_documentation ac Left JOIN verification_family_info fam ON ac.docholder_relationship_name = fam.id where ac.req_id = $req_id and ac.doc_info_upload_used != '1' ");
        $qry = $connect->query("SELECT ac.id,ac.doc_name,ac.doc_type,ac.doc_upload,ac.doc_holder,ac.holder_name,ac.`relation_name`,ac.`doc_info_upload_noc`,fam.famname,fam.id
            from document_info ac Left JOIN verification_family_info fam ON ac.relation_name = fam.id where ac.req_id = $req_id and ac.doc_info_upload_used != '1' AND ac.doc_upload !=''");

        while ($row = $qry->fetch()) {
            $upd_arr = explode(',', $row['doc_upload']);
            for ($i = 0; $i < sizeof($upd_arr); $i++) {
        ?>
                <tr>
                    <td></td>
                    <td><?php echo $row['doc_name']; ?></td>
                    <td><?php if ($row['doc_type'] == '0') {
                            echo 'Original';
                        } elseif ($row['doc_type'] == '1') {
                            echo 'Xerox';
                        }; ?></td>
                    <td><?php if ($row['doc_holder'] != '2') {
                            echo $row['holder_name'];
                        } else {
                            echo $row['famname'];
                        } ?></td>
                    <td><a href='<?php echo 'uploads/verification/doc_info/' . $upd_arr[$i]; ?>' target="_blank"><?php echo $upd_arr[$i]; ?></a></td>
                    <td><input type='checkbox' id='doc_check' name='doc_check' class="form-control doc_check" <?php if ($row['doc_info_upload_noc'] == '1') echo 'checked disabled'; ?> data-value='<?php echo $upd_arr[$i]; //name of uploaded document
                                                                                                                                                                                                    ?>'></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>

</table>

<script type='text/javascript'>
    $(function() {
        $('#documentTable').DataTable({
            "title": "Document List",
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