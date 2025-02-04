<?php
include '../ajaxconfig.php';
?>

<h5> Family Data </h5>
<table class="table custom-table " id="fam_datacheck">
    <thead>
        <tr>
            <th width='100'>S.No</th>
            <th> Customer ID </th>
            <th> Name</th>
            <th> Relationship </th>
            <th width='300'> Under Customer Name </th>
            <th width='300'> Under Customer ID </th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($_POST['name'])) {
            $name =  preg_replace('/\s+/', '', $_POST['name']);
        }
        if (isset($_POST['req_id'])) {
            $req_id = $_POST['req_id'];
        }
        if (isset($_POST['category'])) {
            $category = $_POST['category'];
            if ($category == '0') {
                $category = "famname";
            }
            if ($category == '1') {
                $category = "relation_aadhar";
            }
            if ($category == '2') {
                $category = "relation_Mobile";
            }
        }

        $cusInfo = $connect->query("SELECT a.`famname`,a.`relationship`,a.`relation_aadhar`,b.`cus_id`,b.`customer_name` FROM `verification_family_info` a left join `customer_register` b 
        on a.req_id = b.req_ref_id  WHERE a.`req_id` != '$req_id' && a.$category = '$name' order by a.id desc");

        $i = 1;
        while ($cus = $cusInfo->fetch()) {
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>
                <td> <?php echo $cus['relation_aadhar']; ?></td>
                <td> <?php echo $cus['famname']; ?></td>
                <td> <?php echo $cus['relationship']; ?></td>
                <td> <?php echo $cus['customer_name']; ?></td>
                <td> <?php echo $cus['cus_id']; ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    // $(function() {
    //     $('#fam_datacheck').DataTable({
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