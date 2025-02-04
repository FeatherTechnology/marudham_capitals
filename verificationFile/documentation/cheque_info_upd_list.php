<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="cheque_table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Holder type </th>
            <th> Holder Name </th>
            <th> Relationship </th>
            <th> Bank Name </th>
            <th> Cheque Count </th>
            <th> Cheque No </th>
            <th> Uploads </th>
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
            $row = $result->fetch();

            $doc_upd_name = '';
            $id = $cheque["id"];
            $updresult = $connect->query("SELECT upload_cheque_name FROM `cheque_upd` where cheque_table_id = '$id'");
            $a = 1;
            while($upd = $updresult->fetch()){
            $docName = $upd['upload_cheque_name'];
                $doc_upd_name .= "<a href=uploads/verification/cheque_upd/";
                $doc_upd_name .= $docName ;
                $doc_upd_name .= " target='_blank'>";
                $doc_upd_name .=  $docName. ' ' ;
                $doc_upd_name .= "</a>" ;
                $a++;
            }
            
            $cheque_no ='';
            $updnoresult = $connect->query("SELECT cheque_no FROM `cheque_no_list` where cheque_table_id = '$id'");
            while($updno = $updnoresult->fetch()){
            $no = $updno['cheque_no'];
            $cheque_no .= $no.', ';

            }
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
                <td><?php echo rtrim($cheque_no,','); ?></td>
                <td><?php echo $doc_upd_name; ?></td>

            </tr>

        <?php  } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#cheque_table').DataTable({
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
                searchFunction('cheque_table');
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