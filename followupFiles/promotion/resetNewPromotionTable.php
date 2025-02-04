<?php

include('../../ajaxconfig.php');

// $sql = $connect->query("SELECT a.*,b.area_name,c.sub_area_name  FROM new_promotion a JOIN area_list_creation b ON a.area = b.area_id JOIN sub_area_list_creation c ON a.sub_area = c.sub_area_id WHERE 1 ");
$sql = $connect->query("SELECT * FROM new_cus_promo WHERE cus_id NOT IN (select cus_id from customer_register) ");

?>


<table class="table custom-table" id='new_promo_table' data-id='new_promotion'>
    <thead>
        <th width="10%">Date</th>
        <th>Customer ID</th>
        <th>Customer Name</th>
        <th>Mobile No.</th>
        <th>Area</th>
        <th>Sub Area</th>
        <th>Action</th>
        <th>Promotion Chart</th>
        <th>Follow Date</th>
    </thead>
    <tbody>
        <?php while ($row =  $sql->fetch()) { ?>
            <tr>
                <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td>
                <td><?php echo $row['cus_id']; ?></td>
                <td><?php echo $row['cus_name']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['area']; ?></td>
                <td><?php echo $row['sub_area']; ?></td>
                <td>
                    <?php  //for intrest or not intrest choice to make
                    // if($row['int_status'] == '' or $row['int_status'] == NULL){

                    $action = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

                    $action .= "<a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a>
                            <a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a>";
                    $action .= "</div></div>";
                    echo $action;

                    // }elseif($row['int_status'] == '0'){
                    //     echo 'Interested';
                    // }elseif($row['int_status'] == '1'){
                    //     echo 'Not Interested';
                    // }
                    ?>
                </td>
                <td>
                    <?php //for promotion chart
                    echo "<input type='button' class='btn btn-primary promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal' value='View' />";
                    ?>
                </td>
                <td>
                    <?php
                    $qry = $connect->query("SELECT follow_date FROM new_promotion WHERE cus_id = '" . $row['cus_id'] . "' ORDER BY created_date DESC limit 1");
                    //take last promotion follow up date inserted from new promotion table
                    if ($qry->rowCount() > 0) {
                        $fdate = $qry->fetch()['follow_date'];
                        echo date('d-m-Y', strtotime($fdate));
                    } else {
                        echo '';
                    }
                    ?></td>

            </tr>
        <?php } ?>

    </tbody>
</table>

<script>
    $('#new_promo_table').dataTable({
        // 'processing': true,
        'iDisplayLength': 10,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        'drawCallback': function() {
            searchFunction('new_promo_table');
        }
    })
    $('#new_promo_table tbody tr').not('th').each(function() {
        let tddate = $(this).find('td:eq(8)').text(); // Get the text content of the 8th td element (Follow date)
        let datecorrection = tddate.split("-").reverse().join("-").replaceAll(/\s/g, ''); // Correct the date format
        let values = new Date(datecorrection); // Create a Date object from the corrected date
        values.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let curDate = new Date(); // Get the current date
        curDate.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let colors = {
            'past': 'FireBrick',
            'current': 'DarkGreen',
            'future': 'CornflowerBlue'
        }; // Define colors for different date types

        if (tddate != '' && values != 'Invalid Date') { // Check if the extracted date and the created Date object are valid

            if (values < curDate) { // Compare the extracted date with the current date
                $(this).find('td:eq(8)').css({
                    'background-color': colors.past,
                    'color': 'white'
                }); // Apply styling for past dates
            } else if (values > curDate) {
                $(this).find('td:eq(8)').css({
                    'background-color': colors.future,
                    'color': 'white'
                }); // Apply styling for future dates
            } else {
                $(this).find('td:eq(8)').css({
                    'background-color': colors.current,
                    'color': 'white'
                }); // Apply styling for the current date
            }
        }
    });
</script>
<style>
    .dropdown-content {
        color: black;
    }

    @media (max-width: 598px) {
        #new_promo_div {
            overflow: auto;
        }
    }
</style>

<?php
// Close the database connection
$connect = null;
?>