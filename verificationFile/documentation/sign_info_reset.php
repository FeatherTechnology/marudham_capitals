<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="signedDoc_table_data">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Doc Name </th>
            <th> Sign Type </th>
            <th> Relationship </th>
            <th> Count </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $signDocInfo = $connect->query("SELECT * FROM `signed_doc_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($signed = $signDocInfo->fetch()) {
            $fam_id = $signed["signType_relationship"];
            $result = $connect->query("SELECT famname,relationship FROM `verification_family_info` where id='$fam_id'");
            $row = $result->fetch();
        ?>

            <tr>
                <td><?php echo $i; ?></td>

                <td>Signed Document</td>

                <td><?php if ($signed["sign_type"] == '0') {
                        echo 'Customer';
                    } elseif ($signed["sign_type"] == '1') {
                        echo 'Guarantor';
                    } elseif ($signed["sign_type"] == '2') {
                        echo 'Combined';
                    } elseif ($signed["sign_type"] == '3') {
                        echo 'Family Members';
                    } ?></td>

                <td> <?php if ($signed["sign_type"] == '3' or $signed["sign_type"] == '1' or $signed["sign_type"] == '2') {
                            echo $row["famname"] . ' - ' . $row["relationship"];
                        } else {
                            echo 'NIL';
                        } ?></td>
                <td><?php echo $signed["doc_Count"]; ?></td>
                <td>
                    <a id="signed_doc_edit" value="<?php echo $signed['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="signed_doc_delete" value="<?php echo $signed['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var signedDoc_table_data = $('#signedDoc_table_data').DataTable({
            ...getStateSaveConfig('signedDoc_table_data'),
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
                searchFunction('signedDoc_table_data');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('signed_Doc_info'); // or any base
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

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(signedDoc_table_data, 'signedDoc_table_data');
    });
</script>
<?php
// Close the database connection
$connect = null;
?>