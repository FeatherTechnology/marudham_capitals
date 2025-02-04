<?php
include '../ajaxconfig.php';

function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}
?>

<table class="table custom-table" id="goldInfo_table_data">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Gold Status </th>
            <th> Gold Type </th>
            <th> Purity </th>
            <th> Count </th>
            <th> Weight </th>
            <th> Value </th>
            <th> Upload </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $pages = $_POST['pages'];
        
        $goldInfo = $connect->query("SELECT * FROM `gold_info` where cus_id = '$cus_id' order by id desc");

        if($pages == 2){// for update screen data should be fetched using request id
            $goldInfo = $connect->query("SELECT * FROM `gold_info` where req_id = '$req_id' order by id desc");
        }

        $i = 1;
        while ($gold = $goldInfo->fetch()) {
        ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php if($gold["gold_sts"] == '0'){ echo 'Old';}else if($gold["gold_sts"] == '1'){echo 'New'; } ?></td>
                <td> <?php echo $gold["gold_type"]; ?></td>
                <td> <?php echo $gold["Purity"]; ?></td>
                <td><?php echo $gold["gold_Count"]; ?></td>
                <td><?php echo $gold["gold_Weight"]; ?></td>
                <td><?php echo moneyFormatIndia($gold["gold_Value"]); ?></td>
                <td> <a href="uploads/gold_info/<?php echo $gold['gold_upload']; ?>" target="_blank" style="color: #4ba39b;"> <?php echo $gold['gold_upload']; ?> </a></td>

                <td>
                    <a class="gold_info_edit" value="<?php echo $gold['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <?php if($pages == 1){  // Verification screen only delete option. ?>
                        <a id="gold_info_delete" value="<?php echo $gold['id']; ?>"> <span class='icon-trash-2'></span> </a>
                <?php  } ?>
                </td>

            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#goldInfo_table_data').DataTable({
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
                searchFunction('goldInfo_table_data');
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