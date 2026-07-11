<?php
include '../ajaxconfig.php';

@session_start();
$userid = $_SESSION['userid'];
$sql = $connect->prepare("SELECT update_doc_edit_access FROM user WHERE user_id = ?");
$sql->execute([$userid]);
$doc_edit_access = (int) $sql->fetchColumn(); //1-Yes, 2-No.
?>

<table class="table custom-table" id="chequeInfo_table_data">
    <thead>
        <tr>
            <th width="10%"> S.No </th>
            <th> Holder type </th>
            <th> Holder Name </th>
            <th> Relationship </th>
            <th> Bank Name </th>
            <th> Cheque Count </th>
            <th> Uploads </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $chequeInfo = $connect->query("SELECT ci.id, holder_type, holder_name, holder_relationship_name, cheque_relation, chequebank_name, cheque_count, vfi.famname, GROUP_CONCAT(upload_cheque_name) AS doc_name 
        FROM `cheque_info` ci 
        LEFT JOIN verification_family_info vfi ON ci.holder_relationship_name = vfi.id 
        LEFT JOIN cheque_upd cu ON ci.id = cu.cheque_table_id
        WHERE ci.req_id = '$req_id' 
        GROUP BY ci.id ORDER BY ci.id DESC");

        $i = 1;
        $holderType = ['0' => 'Customer', '1' => 'Guarantor', '2' => 'Family Members'];
        while ($cheque = $chequeInfo->fetch()) {

            $doc_upd_name = '';
            $doc = explode(',', $cheque['doc_name']);
            foreach ($doc as $docName) {
                $doc_upd_name .= "<a href='uploads/verification/cheque_upd/$docName' target='_blank' style='color: #4ba39b;'>$docName</a>,  ";
            }
        ?>

            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $holderType[$cheque['holder_type']] ?? ''; ?></td>
                <td>
                    <?php 
                        if ($cheque["holder_type"] == '0' || $cheque["holder_type"] == '1') {
                            echo $cheque["holder_name"];
                        } elseif ($cheque["holder_type"] == '2') {
                            echo $cheque["famname"];
                        } 
                    ?>
                </td>
                <td><?php echo $cheque["cheque_relation"]; ?></td>
                <td><?php echo $cheque["chequebank_name"]; ?></td>
                <td><?php echo $cheque["cheque_count"]; ?></td>
                <td><?php echo rtrim($doc_upd_name, ', '); ?></td>
                <td>
                    <?php if (empty($cheque['doc_name']) && $doc_edit_access == 2) { ?>
                        <a class="cheque_info_edit" value="<?php echo $cheque['id']; ?>" data-access="2" style="text-decoration: underline;"> Entry </a>

                    <?php } else if ($doc_edit_access == 1) { ?>
                        <a class="cheque_info_edit" value="<?php echo $cheque['id']; ?>" data-access="1"> <span class="icon-border_color"></span></a> &nbsp
                        <a class="cheque_info_delete" value="<?php echo $cheque['id']; ?>" data-reqid="<?php echo $req_id; ?>"> <span class='icon-trash-2'></span> </a>

                    <?php } ?>
                </td>
            </tr>

        <?php } ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var chequeInfo_table_data = $('#chequeInfo_table_data').DataTable({
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
                searchFunction('chequeInfo_table_data');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Cheque_info'); // or any base
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