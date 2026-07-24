<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="signedDoc_upd_table_data">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Doc Name </th>
            <th> Sign Type </th>
            <th> Relationship </th>
            <th> Count </th>
            <th> Uploads </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $signDocInfo = $connect->query("SELECT sdi.id, sdi.cus_id, sign_type, signType_relationship, doc_Count, GROUP_CONCAT(upload_doc_name) AS doc_name, vfi.famname, vfi.relationship FROM `signed_doc_info` sdi LEFT JOIN signed_doc sd ON sdi.id = sd.signed_doc_id LEFT JOIN verification_family_info vfi ON sdi.signType_relationship = vfi.id WHERE sdi.req_id = '$req_id' GROUP BY sdi.id ORDER BY sdi.id DESC");

        $i = 1;
        $signType = ['0' => 'Customer', '1' => 'Guarantor', '2' => 'Combined', '3' => 'Family Members'];
        while ($signed = $signDocInfo->fetch()) {

            $doc_upd_name = '';
            $doc = explode(',', $signed['doc_name']);
            foreach ($doc as $docName) {
                $doc_upd_name .= "<a href='uploads/verification/signed_doc/$docName' target='_blank' style='color: #4ba39b;'>$docName</a>,  ";
            }
        ?>

            <tr>
                <td><?php echo $i++; ?></td>
                <td>Signed Document</td>
                <td><?php echo $signType[$signed['sign_type']] ?? ''; ?></td>
                <td> <?php if(in_array($signed['sign_type'], [1,2,3])){
                            echo $signed["famname"] . ' - ' . $signed["relationship"];
                        } else {
                            echo 'NIL';
                        } ?></td>
                <td><?php echo $signed["doc_Count"]; ?></td>
                <td><?php echo rtrim($doc_upd_name,', '); ?></td>
                <td>
                    <a id="signed_doc_edit" value="<?php echo $signed['id']; ?>" data-relationid="<?= $signed['signType_relationship']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="signed_doc_delete" value="<?php echo $signed['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>

        <?php } ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        $('#signedDoc_upd_table_data').DataTable({
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
                searchFunction('signedDoc_upd_table_data');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Signed_Doc_info'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
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