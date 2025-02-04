<?php
include '../ajaxconfig.php';
?>

<h5> Customer Data </h5>
<table class="table custom-table " id="cus_datacheck">
    <thead>
        <tr>
            <th width='50'>S.No</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Mobile Number</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($_POST['category'])) {
            $category = $_POST['category'];
            if ($category == '0') {
                $category = "customer_name";
                $category1 = "customer_name";
            }
            if ($category == '1') {
                $category = "cus_id";
                $category1 = 'cus_id';
            }
            if ($category == '2') {
                $category = "mobile1";
                $category1 = 'mobile2';
            }
        }
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }
        if (isset($_POST['req_id'])) {
            $req_id = $_POST['req_id'];
        }

        $cusInfo = $connect->query("SELECT cus_id,customer_name,mobile1 FROM `customer_register` where ($category = '" . strip_tags($name) . "' or $category1 = '" . strip_tags($name) . "') && req_ref_id != '" . strip_tags($req_id) . "' order by cus_reg_id desc");

        $i = 1;
        while ($cus = $cusInfo->fetch()) {
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>
                <td> <?php echo $cus['cus_id']; ?></td>
                <td> <?php echo $cus['customer_name']; ?></td>
                <td> <?php echo $cus['mobile1']; ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    // $(function() {
    //     $('#cus_datacheck').DataTable({
    //         'processing': true,
    //         'iDisplayLength': 5,
    //         "lengthMenu": [
    //             [10, 25, 50, -1],
    //             [10, 25, 50, "All"]
    //         ],
    //         "createdRow": function(row, data, dataIndex) {
    //             $(row).find('td:first').html(dataIndex + 1);
    //         },
    //         "drawCallback": function(settings) {
    //             this.api().column(0).nodes().each(function(cell, i) {
    //                 cell.innerHTML = i + 1;
    //             });
    //         },
    //     });
    // });
</script>
<?php
// Close the database connection
$connect = null;
?>