<?php
include('../../ajaxconfig.php');
@session_start();
$user_id = $_SESSION['userid'] ?? '';

// Step 1: Fetch role_type of the user
$userRes = $connect->query("SELECT line_id, group_id, due_followup_lines, promotion_activity_mapping_access, role_type FROM user WHERE user_id = $user_id");
$userRow = $userRes->fetch();
$role_type = $userRow['role_type'];
$group_id = $userRow['group_id'];
$line_id = $userRow['line_id'];
$due_followup_lines = $userRow['due_followup_lines'];
$promotion_activity_mapping_access = $userRow['promotion_activity_mapping_access'];

if ($promotion_activity_mapping_access == 1) {
    $condition = "agm.map_id IN ($group_id)";
} elseif ($promotion_activity_mapping_access == 2) {
    $condition = "alm.map_id IN ($line_id)";
} elseif ($promotion_activity_mapping_access == 3) {
    $condition = "adfm.map_id IN ($due_followup_lines)";
}

// Step 2: Apply logic for fetching data
if ($role_type == 7 || $role_type == 3) {
    // Role 7 (Admin) and 3(Manager)→ See all records
    $sql = "
        SELECT ncp.cus_id, ncp.cus_name, ncp.mobile, ncp.insert_login_id, ncp.created_date, a.area_name, sa.sub_area_name, agm.group_name, alm.line_name, u.fullname, np.status AS followup_sts, np.follow_date, np.followup_type 
        FROM new_cus_promo ncp JOIN area_list_creation a ON ncp.area = a.area_id
        JOIN sub_area_list_creation sa ON ncp.sub_area = sa.sub_area_id
        JOIN area_group_mapping_area agma ON agma.area_id = a.area_id
        JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
        JOIN area_line_mapping_area alma ON alma.area_id = a.area_id
        JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
        JOIN user u ON ncp.insert_login_id = u.user_id
        LEFT JOIN new_promotion np ON ncp.cus_id = np.cus_id AND np.created_date = (SELECT MAX(np1.created_date) FROM new_promotion np1 WHERE np1.cus_id = ncp.cus_id)
        WHERE ncp.cus_id NOT IN (SELECT cus_id FROM customer_register)
    ";
} else {
    // Other roles → See only their own records
    $sql = "
        SELECT ncp.cus_id, ncp.cus_name, ncp.mobile, ncp.insert_login_id, ncp.created_date, a.area_name, sa.sub_area_name, agm.group_name, alm.line_name, u.fullname, np.status AS followup_sts, np.follow_date, np.followup_type
        FROM new_cus_promo ncp 
        JOIN area_list_creation a ON ncp.area = a.area_id
        JOIN sub_area_list_creation sa ON ncp.sub_area = sa.sub_area_id
        JOIN area_group_mapping_area agma ON agma.area_id = a.area_id
        JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
        JOIN area_line_mapping_area alma ON alma.area_id = a.area_id
        JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
        JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = a.area_id
        JOIN area_duefollowup_mapping adfm ON adfm.map_id = adfma.duefollowup_map_id
        JOIN user u ON ncp.insert_login_id = u.user_id
        LEFT JOIN new_promotion np ON ncp.cus_id = np.cus_id AND np.created_date = (SELECT MAX(np1.created_date) FROM new_promotion np1 WHERE np1.cus_id = ncp.cus_id)
        WHERE ncp.cus_id NOT IN (SELECT cus_id FROM customer_register)
        AND $condition 
    ";
}

if($_POST['followUpSts']){
    $follow_up_sts = $_POST['followUpSts'];
    $sql .= ($follow_up_sts =='tofollow') ? " AND np.status IS NULL " : " AND TRIM(REPLACE(np.status,' ','')) = '$follow_up_sts' ";
}

if($_POST['dateType']){
    $sql .= " AND (DATE(np.follow_date) BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."') ";
}  

    $sql .= ($_POST['followupType']) ? " AND np.followup_type = '". $_POST['followupType'] ."'" : "";   

    ($role_type != 7 || $role_type != 3) ? " GROUP BY ncp.cus_id" : "";
    
    $info = $connect->query($sql);
// $sql = $connect->query("SELECT a.*,b.area_name,c.sub_area_name  FROM new_promotion a JOIN area_list_creation b ON a.area = b.area_id JOIN sub_area_list_creation c ON a.sub_area = c.sub_area_id WHERE 1 ");
?>


<table class="table custom-table" id='new_promo_table' data-id='new_promotion'>
    <thead>
        <th width="10%">Date</th>
        <th>Aadhaar Number</th>
        <th>Customer Name</th>
        <th>Mobile No.</th>
        <th>Area</th>
        <th>Sub Area</th>
        <th>Region</th>
        <th>Sector</th>
        <th>User Name</th>
        <th>Action</th>
        <th>Promotion Chart</th>
        <th>Follow up status</th>
        <th>Follow Date</th>
        <th>Follow up Type</th>
    </thead>
    <tbody>
        <?php while ($row =  $info->fetch()) { ?>
            <tr>
                <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td>
                <td><?php echo $row['cus_id']; ?></td>
                <td><?php echo $row['cus_name']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['area_name']; ?></td>
                <td><?php echo $row['sub_area_name']; ?></td>
                <td><?php echo $row['line_name']; ?></td>
                <td><?php echo $row['group_name']; ?></td>
                <td><?php echo $row['fullname']; ?></td>               
                <td>
                    <?php
                        $action = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

                        $action .= "<a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a>
                                <a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a>
                                <a class='un-available' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Unavailable</span></a>";
                        $action .= "</div></div>";
                        echo $action;
                    ?>
                </td>
                <td>
                    <?php //for promotion chart
                        echo "<input type='button' class='btn btn-primary promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal' value='View'/>";
                    ?>
                </td>
                <td><?php echo $row['followup_sts']; ?></td> 
                <td>
                    <?php
                        echo ($row['follow_date'] !='') ? date('d-m-Y', strtotime($row['follow_date'])) : '';
                    ?>
                </td>
                <td>
                    <?php
                        $followuptype = $row['followup_type'] ?? '';
                        $followup_type =''; 
                        if($followuptype =='1'){
                            $followup_type = 'Direct';  
                        }else if($followuptype =='2'){
                            $followup_type = 'Clear';  
                        } 
                        echo $followup_type;
                    ?>
                </td>
            </tr>
        <?php } ?>

    </tbody>
</table>

<script>
    // Declare table variable to store the DataTable instance
    var new_promo_table = $('#new_promo_table').DataTable({
        ...getStateSaveConfig('new_promo_table'),
        'iDisplayLength': 10,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{ 
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('New_Promotion'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            { 
                extend: 'colvis', 
                collectionLayout: 'fixed four-column' 
            }
        ],
        'drawCallback': function() {
            searchFunction('new_promo_table');

            // apply color coding on every redraw
            $('#new_promo_table tbody tr').each(function() {
                let tddate = $(this).find('td:eq(12)').text().trim(); 
                if (tddate === '') return;

                // normalize DD-MM-YYYY to YYYY-MM-DD
                let datecorrection = tddate.split("-").reverse().join("-").replace(/\s/g, '');
                let values = new Date(datecorrection);
                values.setHours(0, 0, 0, 0);

                let curDate = new Date();
                curDate.setHours(0, 0, 0, 0);

                let colors = {
                    'past': 'FireBrick',
                    'current': 'DarkGreen',
                    'future': 'CornflowerBlue'
                };

                if (!isNaN(values)) {
                    if (values < curDate) {
                        $(this).find('td:eq(12)').css({'background-color': colors.past, 'color': 'white'});
                    } else if (values > curDate) {
                        $(this).find('td:eq(12)').css({'background-color': colors.future, 'color': 'white'});
                    } else {
                        $(this).find('td:eq(12)').css({'background-color': colors.current, 'color': 'white'});
                    }
                }
            });
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(new_promo_table, 'new_promo_table');
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