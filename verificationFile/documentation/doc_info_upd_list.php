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
            <th> Document </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $qry = $connect->query("SELECT * FROM `document_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($row = $qry->fetch()) {
            $docUpd = explode(',', $row["doc_upload"]);

            if ($row["holder_name"] == '') {
                $qry1 = $connect->query("SELECT * FROM verification_family_info where id = '" . $row['relation_name'] . "' ");
                $holder_name = $qry1->fetch()['famname'];
            } else {
                $holder_name = $row["holder_name"];
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
                <td><?php $ii = 0;
                    foreach ($docUpd as $upd) {
                        if ($ii > 0) {
                            echo ',';
                        }
                        if ($upd != null) {
                            echo '<a href="uploads/verification/doc_info/' . $upd . '" target="_blank" title="View Document"> ' . $upd . '</a>';
                        }
                        $ii++;
                    } ?></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>



<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var document_table = $('#document_table').DataTable({
            ...getStateSaveConfig('document_table'),
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

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(document_table, 'document_table');
    });
</script>
<?php
// Close the database connection
$connect = null;
?>