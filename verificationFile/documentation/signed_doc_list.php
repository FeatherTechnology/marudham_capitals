<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="signed_table">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Doc Name </th>
            <th> Sign Type </th>
            <th> Relationship </th>
            <th> Count </th>
        </tr>
    </thead>
    <tbody>

        <?php
            $req_id = $_POST['reqId'];
            $signInfo = $connect->query("SELECT sign_type, doc_Count, vfi.famname, vfi.relationship FROM `signed_doc_info` sdi LEFT JOIN verification_family_info vfi ON sdi.signType_relationship = vfi.id WHERE sdi.req_id = '$req_id' ORDER BY sdi.id DESC");

            $i = 1;
            $signType = ['0' => 'Customer', '1' => 'Guarantor', '2' => 'Combined', '3' => 'Family Members'];
            while ($signedDoc = $signInfo->fetch()) {
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>

                <td>Signed Document</td>

                <td> <?php echo $signType[$signedDoc['sign_type']] ?? ''; ?> </td>

                <td> 
                    <?php if(in_array($signedDoc['sign_type'], [1,2,3])){
                        echo $signedDoc["famname"] . ' - ' . $signedDoc["relationship"];
                    } else {
                        echo 'NIL';
                    } ?> 
                </td>

                <td> <?php echo $signedDoc['doc_Count']; ?></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
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
                searchFunction('signed_table');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Signed_Doc_info'); // or any base
                        config.title = dynamic; // for versions that use title as filename
                        config.filename = dynamic; // for html5 filename
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