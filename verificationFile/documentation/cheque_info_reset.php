<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="chequeInfo_table_data">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Holder type </th>
            <th> Holder Name </th>
            <th> Relationship </th>
            <th> Bank Name </th>
            <th> Cheque No </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $chequeInfo = $connect->query("SELECT * FROM `cheque_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($cheque = $chequeInfo->fetch()) {
            $fam_id = $cheque["holder_relationship_name"];
            $result = $connect->query("SELECT famname FROM `verification_family_info` where id='$fam_id'");
            $row = $result->fetch()
        ?>

            <tr>
                <td><?php echo $i; ?></td>

                <td><?php if ($cheque["holder_type"] == '0') {
                        echo 'Customer';
                    } elseif ($cheque["holder_type"] == '1') {
                        echo 'Guarantor';
                    } elseif ($cheque["holder_type"] == '2') {
                        echo 'Family Members';
                    }  ?></td>

                <td> <?php if ($cheque["holder_type"] == '0' || $cheque["holder_type"] == '1') {
                            echo $cheque["holder_name"];
                        } elseif ($cheque["holder_type"] == '2') {
                            echo $row["famname"];
                        } ?></td>
                <td><?php echo $cheque["cheque_relation"]; ?></td>
                <td><?php echo $cheque["chequebank_name"]; ?></td>
                <td><?php echo $cheque["cheque_count"]; ?></td>

                <td>
                    <a id="cheque_info_edit" value="<?php echo $cheque['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="cheque_info_delete" value="<?php echo $cheque['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>

            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#chequeInfo_table_data').DataTable({
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