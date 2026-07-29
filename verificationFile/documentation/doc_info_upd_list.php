<?php
include '../../ajaxconfig.php';

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
?>

<table class="table custom-table" id="document_table">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Document Name </th>
            <th> Document Details</th>
            <th> Document Type </th>
            <th> Document Holder</th>
            <th> Holder Name</th>
            <th> Relationship</th>
            <th> Uploads </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $qry = $connect->query("SELECT di.id, di.doc_name, di.doc_detail, di.doc_type, di.doc_holder, di.holder_name, di.relation, di.doc_upload, vfi.famname FROM `document_info` di LEFT JOIN verification_family_info vfi ON di.relation_name = vfi.id WHERE di.req_id = '$req_id' ORDER BY di.id DESC");

        $i = 1;
        while ($row = $qry->fetch()) {
            
            $holder_name = ($row["holder_name"] == '') ? $row['famname'] : $row["holder_name"];
            
            $doc_upd_name = '';
            $docUpd = explode(',', $row["doc_upload"]);
            foreach ($docUpd as $docName) {
                $doc_upd_name .= "<a href='uploads/verification/doc_info/$docName' target='_blank' style='color: #4ba39b;'>$docName</a>,  ";
            }
        ?>
            <tr>
                <td></td>
                <td><?php echo $row["doc_name"]; ?></td>
                <td><?php echo $row["doc_detail"]; ?></td>
                <td><?php if ($row["doc_type"] == '0') {
                        echo 'Original';
                    } else if ($row["doc_type"] == '1') {
                        echo 'Xerox';
                    } ?></td>
                <td><?php if ($row["doc_holder"] == '0') {
                        echo 'Customer';
                    } else if ($row["doc_holder"] == '1') {
                        echo 'Guarentor';
                    } elseif ($row["doc_holder"] == '2') {
                        echo 'Family Member';
                    } ?></td>
                <td><?php echo $holder_name; ?></td>
                <td><?php echo $row["relation"]; ?></td>
                <td><?php echo rtrim($doc_upd_name, ', '); ?></td>
                <td>
                    <a id="doc_info_delete" value="<?php echo $row['id']; ?>" data-screen="1"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>

        <?php  } ?>
    </tbody>
</table>



<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        $('#document_table').DataTable({
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
                searchFunction('document_table');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Document_info'); // or any base
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