<?php
include('../../ajaxconfig.php');
include "../../moneyFormatIndia.php";
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

//Enquiry list show until the customer request loan. 
$sql = "
    WITH latest_enquiry AS (
        SELECT e.*
        FROM enquiry e
        INNER JOIN (
            SELECT cus_id, MAX(created_date) AS latest_enquiry_date
            FROM enquiry
            GROUP BY cus_id
        ) le
            ON le.cus_id = e.cus_id
            AND le.latest_enquiry_date = e.created_date
    )

    SELECT 
        cr.autogen_cus_id ,
        e.id,
        e.cus_id,
        e.cus_name,
        e.cus_data,
        e.mobile,
        e.insert_login_id,
        e.created_date,
        a.area_name,
        sa.sub_area_name,
        agm.group_name,
        alm.line_name,
        e.loan_amount,
        e.remarks,
        u.fullname,
        ep.status AS followup_sts,
        ep.follow_date,
        ep.followup_type

    FROM latest_enquiry e

    JOIN customer_register cr ON  e.cus_id = cr.cus_id

    JOIN area_list_creation a 
        ON e.area = a.area_id

    JOIN sub_area_list_creation sa 
        ON e.sub_area = sa.sub_area_id

    JOIN area_group_mapping_area agma 
        ON agma.area_id = a.area_id

    JOIN area_group_mapping agm 
        ON agm.map_id = agma.group_map_id

    JOIN area_line_mapping_area alma 
        ON alma.area_id = a.area_id

    JOIN area_line_mapping alm 
        ON alm.map_id = alma.line_map_id

    JOIN area_duefollowup_mapping_area adfma 
        ON adfma.area_id = a.area_id

    JOIN area_duefollowup_mapping adfm 
        ON adfm.map_id = adfma.duefollowup_map_id

    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 

    JOIN user u 
        ON e.insert_login_id = u.user_id

    LEFT JOIN enquiry_promotion ep 
        ON e.cus_id = ep.cus_id
        AND ep.created_date = (
            SELECT MAX(np1.created_date)
            FROM enquiry_promotion np1
            WHERE np1.cus_id = e.cus_id
        )

    WHERE NOT EXISTS (
        SELECT 1
        FROM request_creation rc2
        WHERE rc2.cus_id = e.cus_id
        AND rc2.dor > e.created_date
    )";

// Step 2: Apply logic for fetching data
// Role 7 (Admin) and 3(Manager)→ See all records
// Other roles → See only their own records
if ($role_type != 7 && $role_type != 3) {
    $sql .= " AND $condition";
}

if($_POST['followUpSts']){
    $follow_up_sts = $_POST['followUpSts'];
    $sql .= ($follow_up_sts =='tofollow') ? " AND ep.status IS NULL " : " AND TRIM(REPLACE(ep.status,' ','')) = '$follow_up_sts' ";
}

if($_POST['dateType']){
    $sql .= " AND (DATE(ep.follow_date) BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."') ";
}  

$sql .= ($_POST['followupType']) ? " AND ep.followup_type = '". $_POST['followupType'] ."'" : "";   
$sql .= ($_POST['branch_id']) ? " AND bc.branch_id = '". $_POST['branch_id'] ."'" : "";   
$sql .= ($_POST['group_id']) ? " AND agm.map_id = '". $_POST['group_id'] ."'" : "";   
$sql .= ($_POST['area_id']) ? " AND a.area_id = '". $_POST['area_id'] ."'" : "";   

$sql .= " GROUP BY e.cus_id ORDER BY e.id ASC";

$info = $connect->query($sql);
?>

<table class="table custom-table" id='enquiry_table' data-id='enquiry'>
    <thead>
        <th width="10%">Date</th>
        <th>Aadhaar Number</th>
        <th>Customer ID</th>
        <th>Customer Name</th>
        <th>Customer Data</th>
        <th>Mobile No</th>
        <th>Area</th>
        <th>Sub Area</th>
        <th>Region</th>
        <th>Sector</th>
        <th>Loan Amount</th>
        <th>Remarks</th>
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
                <td><?php echo $row['autogen_cus_id']; ?></td>
                <td><?php echo $row['cus_name']; ?></td>
                <td><?php echo $row['cus_data']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['area_name']; ?></td>
                <td><?php echo $row['sub_area_name']; ?></td>
                <td><?php echo $row['line_name']; ?></td>
                <td><?php echo $row['group_name']; ?></td>
                <td><?php echo moneyFormatIndia($row['loan_amount']); ?></td>
                <td>
                    <?php  echo "<a href='#' class='enq-remarks' data-toggle='modal' data-target='#remarksModal' data-cusid='" .$row['autogen_cus_id']. "' data-remarks='" .$row['remarks'] . "'>
                        <span class='icon-eye' style='font-size: 12px; position: relative; top: 2px;'></span> </a>"; ?>
                </td>
                <td><?php echo $row['fullname']; ?></td>               
                <td>
                    <?php
                        echo "<div class='dropdown'>
                                <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                                <div class='dropdown-content'>
                                    <a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "' data-screen='1'><span>Interested</span></a>
                                    <a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "' data-screen='1'><span>Not Interested</span></a>
                                    <a class='un-available' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "' data-screen='1'><span>Unavailable</span></a>
                                </div>
                            </div>";
                    ?>
                </td>
                <td>
                    <?php //for promotion chart
                        echo "<input type='button' class='btn btn-primary promo-chart' data-id='" . $row['cus_id'] . "' data-screen='1' data-toggle='modal' data-target='#promoChartModal' value='View'/>";
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
                            $followup_type = 'Field';  
                        }else if($followuptype =='2'){
                            $followup_type = 'Telecalling';  
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
    var enquiry_table = $('#enquiry_table').DataTable({
        ...getStateSaveConfig('enquiry_table'),
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
            searchFunction('enquiry_table');

            // apply color coding on every redraw
            $('#enquiry_table tbody tr').each(function() {
                let tddate = $(this).find('td:eq(14)').text().trim(); 
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
                        $(this).find('td:eq(14)').css({'background-color': colors.past, 'color': 'white'});
                    } else if (values > curDate) {
                        $(this).find('td:eq(14)').css({'background-color': colors.future, 'color': 'white'});
                    } else {
                        $(this).find('td:eq(14)').css({'background-color': colors.current, 'color': 'white'});
                    }
                }
            });
        }
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(enquiry_table, 'enquiry_table');
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