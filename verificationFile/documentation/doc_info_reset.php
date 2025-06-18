<?php
include '../../ajaxconfig.php';

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['pages'])) {
    $pages = $_POST['pages'];
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
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $qry = $connect->query("SELECT * FROM document_info where req_id = '$req_id' order by id desc");

        while ($row = $qry->fetch()) {
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

                <td>
                    <?php //if ($pages == 1) {  // Verification screen only delete option. 
                    ?>
                        <a id="doc_info_edit" value="<?php echo $row['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp;
                        <a id="doc_info_delete" value="<?php echo $row['id']; ?>"> <span class='icon-trash-2'></span> </a>
                    <?php  //} elseif ($pages == 2) { ?>
                        <!-- <a id="doc_info_edit" value="<?php #echo $row['id']; ?>" style="text-decoration: underline;"> Upload</a> &nbsp; -->
                    <?php //} ?>
                </td>

            </tr>

        <?php
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
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