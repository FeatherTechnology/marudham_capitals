<?php
include '../ajaxconfig.php';

@session_start();
$userid = $_SESSION['userid'];
$sql = $connect->prepare("SELECT update_doc_edit_access FROM user WHERE user_id = ?");
$sql->execute([$userid]);
$doc_edit_access = (int) $sql->fetchColumn(); //1-Yes, 2-No.

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
?>

<table class="table custom-table" id="docModalTable">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Document Name </th>
            <th> Document Details</th>
            <th> Document Type </th>
            <th> Document Holder</th>
            <th> Holder Name</th>
            <th> Relationship</th>
            <th> Document</th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
            $qry = $connect->query("SELECT di.id, di.doc_name, di.doc_detail, di.doc_type, di.doc_holder, di.holder_name, di.relation, di.doc_upload, vfi.famname 
                    FROM document_info di
                    LEFT JOIN verification_family_info vfi ON di.relation_name = vfi.id
                    WHERE di.req_id = '$req_id' ORDER BY di.id DESC");

            while ($row = $qry->fetch()) {
                $holder_name = ($row["holder_name"] == '') ? $row['famname'] : $row["holder_name"];

                $docUpd = explode(',', $row["doc_upload"]);
        ?>

            <tr>
                <td></td>
                <td><?php echo $row["doc_name"]; ?></td>
                <td><?php echo $row["doc_detail"]; ?></td>
                <td>
                    <?php if ($row["doc_type"] == '0') {
                        echo 'Original';
                    } else if ($row["doc_type"] == '1') {
                        echo 'Xerox';
                    } ?>
                </td>
                <td>
                    <?php if ($row["doc_holder"] == '0') {
                        echo 'Customer';
                    } else if ($row["doc_holder"] == '1') {
                        echo 'Guarentor';
                    } elseif ($row["doc_holder"] == '2') {
                        echo 'Family Member';
                    } ?>
                </td>
                <td><?php echo $holder_name; ?></td>
                <td><?php echo $row["relation"]; ?></td>
                <td>
                    <?php 
                        $text = '';
                        foreach ($docUpd as $upd) {
                            $text .= '<a href="uploads/verification/doc_info/'. $upd .'" target="_blank" title="View Document" style="color: #4ba39b;">'. $upd .'</a>, ';
                        }
                        echo rtrim($text, ', '); // to trim the comma at end 
                    ?>
                </td>

                <td>
                    <?php
                    if (empty($docUpd[0]) && $doc_edit_access == 2) { ?>
                        <a class="doc_info_edit" value="<?php echo $row['id']; ?>" data-access="2" style="text-decoration: underline;">Upload</a> &nbsp;

                    <?php } else if ($doc_edit_access == 1) { ?>
                        <a class="doc_info_edit" value="<?php echo $row['id']; ?>" data-access="1"> <span class="icon-border_color"></span> </a> &nbsp;
                        <a class="doc_info_delete" value="<?php echo $row['id']; ?>" data-reqid="<?php echo $req_id; ?>"> <span class='icon-trash-2'></span> </a>
                        
                    <?php } ?>
                </td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        $('#docModalTable').DataTable({
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
                searchFunction('docModalTable');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Document_info'); // or any base
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