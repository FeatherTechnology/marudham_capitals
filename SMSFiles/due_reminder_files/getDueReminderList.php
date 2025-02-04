
<?php
@session_start();
include('../../ajaxconfig.php');
include '../../collectionFile/getLoanDetailsClass.php';

if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
}

if($userid != 1){
    
    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while($rowuser = $userQry->fetch()){
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
    }

    $line_id = explode(',',$line_id);
    $sub_area_list = array();
    foreach($line_id as $line){
        $lineQry = $connect->query("SELECT * FROM area_line_mapping where map_id = $line ");
        $row_sub = $lineQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',',$subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',',$sub_area_ids);
}

$loandate = date('Y-m-01');
$query = "SELECT alc.cus_id_loan as cp_cus_id, alc.cus_name_loan,alc.sub_category,alc.mobile_loan, ii.cus_id as ii_cus_id, ii.req_id
FROM acknowlegement_loan_calculation alc 
JOIN in_issue ii ON alc.cus_id_loan = ii.cus_id
JOIN customer_register cr ON alc.cus_id_loan = cr.cus_id
where ii.status = 0 
AND (ii.cus_status >= 14 AND ii.cus_status <= 17) 
AND alc.profit_type = '1'
AND cr.area_confirm_subarea IN ($sub_area_list) 
AND CURDATE() = '$loandate'";// GROUP BY ii.cus_id  14 and 17 means collection entries, 17 removed from issue list // alc.profit_type = '1' -- Monthly

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
?>
<!-- Monthly Loan START-->
<form name="monthly_loan_reminderList" method="POST">
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="send_monthly_loan_reminder" name="send_monthly_loan_reminder" style="padding: 5px 10px; float: right;" value="SendSMS">Send SMS</button> 
        <span id="m_alert" class="required" style="display: none;">*There is no Customer to send Due Reminder</span>
    </div>
</div>
</br>
<table id='monthly_due_followup_table' class="table custom-table">
    <thead>
        <tr><th colspan="6">Monthly Loan</th></tr>
        <tr>
            <th width="50">S.No.</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Mobile No</th>
            <th>Loan Sub category</th>
            <th>Due Amount</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $sno = 1;
    foreach ($result as $row) {
    ?>
        <tr>
            <td> <?php echo $sno; ?> </td>
            <td> <?php echo $row['cp_cus_id']; ?> </td>
            <td> <input type="text" class="m_cusName" name="m_cus_name[]" value="<?php echo $row['cus_name_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <input type="text" name="m_cus_no[]" value="<?php echo $row['mobile_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <?php echo $row['sub_category']; ?> </td>
            <td> <?php $obj = new GetLoanDetails($connect, $row['req_id'], date('Y-m-d'),''); ?>
                <input type="text" name="m_cus_due[]" value="<?php echo $obj->response['due_amt']; ?>" style="border: none; outline: 0; background: inherit;" readonly> 
            </td>
        </tr>
    <?php
        $sno++;
    }
    ?>
    </tbody>
</table>
</form>
<!-- Monthly Loan END-->
</br></br></br></br>
<!-- Scheme Monthly START-->
<form name="scheme_monthly_loan_reminderList" method="POST">
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="send_scheme_monthly_reminder" name="send_scheme_monthly_reminder" style="padding: 5px 10px; float: right;" value="sendSMS">Send SMS</button> 
        <span id="sm_alert" class="required" style="display: none;">*There is no Customer to send Due Reminder</span>
    </div>
</div>
</br>
<table id='scheme_monthly_due_followup_table' class="table custom-table">
    <thead>
        <tr><th colspan="6">Scheme Monthly Loan</th></tr>
        <tr>
            <th width="50">S.No.</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Mobile No</th>
            <th>Loan Sub category</th>
            <th>Due Amount</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $getSchemeMonthlyQry = "SELECT alc.cus_id_loan as cp_cus_id, alc.cus_name_loan,alc.sub_category,alc.mobile_loan, ii.cus_id as ii_cus_id, ii.req_id
    FROM acknowlegement_loan_calculation alc 
    JOIN in_issue ii ON alc.cus_id_loan = ii.cus_id
    JOIN customer_register cr ON alc.cus_id_loan = cr.cus_id
    where ii.status = 0 
    AND (ii.cus_status >= 14 AND ii.cus_status <= 17) 
    AND alc.profit_type = '2'
    AND alc.due_method_scheme = '1'
    AND cr.area_confirm_subarea IN ($sub_area_list) 
    AND CURDATE() = '$loandate' ";// GROUP BY ii.cus_id  14 and 17 means collection entries, 17 removed from issue list //alc.profit_type = '2' -- Scheme //alc.due_method_scheme = '1' -- Scheme Monthly
    
    $schemeMonthlyDetails = $connect->query($getSchemeMonthlyQry);
    $schemeMonthlyInfo = $schemeMonthlyDetails->fetchAll();
    $smsno = 1;
    foreach ($schemeMonthlyInfo as $schemeMonthlyRow) {
    ?>
        <tr>
            <td> <?php echo $smsno; ?> </td>
            <td> <?php echo $schemeMonthlyRow['cp_cus_id']; ?> </td>
            <td> <input type="text" class="sm_cusName" name="m_cus_name[]" value="<?php echo $schemeMonthlyRow['cus_name_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <input type="text" name="m_cus_no[]" value="<?php echo $schemeMonthlyRow['mobile_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <?php echo $schemeMonthlyRow['sub_category']; ?> </td>
            <td> <?php $obj = new GetLoanDetails($connect, $schemeMonthlyRow['req_id'], date('Y-m-d'),''); ?> 
                <input type="text" name="m_cus_due[]" value="<?php echo $obj->response['due_amt']; ?>" style="border: none; outline: 0; background: inherit;" readonly>
            </td>
        </tr>
    <?php
        $smsno++;
    }
    ?>
    </tbody>
</table>
</form>
<!-- Scheme Monthly END-->
</br></br></br></br>
<!-- scheme Weekly START-->
<form name="scheme_weekly_loan_reminderList" method="POST">
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="send_scheme_weekly_reminder" name="send_scheme_weekly_reminder" style="padding: 5px 10px; float: right;" value="sendSMS">Send SMS</button> 
        <span id="sw_alert" class="required" style="display: none;">*There is no Customer to send Due Reminder</span>
    </div>
</div>
</br>
<table id='scheme_weekly_due_followup_table' class="table custom-table">
    <thead>
        <tr><th colspan="6">Scheme Weekly Loan</th></tr>
        <tr>
            <th width="50">S.No.</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Mobile No</th>
            <th>Loan Sub category</th>
            <th>Due Amount</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $getSchemeWeeklyQry = "SELECT alc.cus_id_loan as cp_cus_id, alc.cus_name_loan,alc.sub_category,alc.mobile_loan, ii.cus_id as ii_cus_id, ii.req_id
    FROM acknowlegement_loan_calculation alc 
    JOIN in_issue ii ON alc.cus_id_loan = ii.cus_id
    JOIN customer_register cr ON alc.cus_id_loan = cr.cus_id
    where ii.status = 0 
    AND (ii.cus_status >= 14 AND ii.cus_status <= 17) 
    AND alc.profit_type = '2'
    AND alc.due_method_scheme = '2'
    AND cr.area_confirm_subarea IN ($sub_area_list) 
    AND alc.day_scheme = DAYOFWEEK(CURDATE()) ";// GROUP BY ii.cus_id  14 and 17 means collection entries, 17 removed from issue list //alc.profit_type = '2' -- Scheme //alc.due_method_scheme = '2' -- Scheme Weekly
    
    $schemeWeeklyDetails = $connect->query($getSchemeWeeklyQry);
    $schemeWeeklyInfo = $schemeWeeklyDetails->fetchAll();
    $swsno = 1;
    foreach ($schemeWeeklyInfo as $schemeWeeklyRow) {
    ?>
        <tr>
            <td> <?php echo $swsno; ?> </td>
            <td> <?php echo $schemeWeeklyRow['cp_cus_id']; ?> </td>
            <td> <input type="text" class="sw_cusName" name="m_cus_name[]" value="<?php echo $schemeWeeklyRow['cus_name_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <input type="text" name="m_cus_no[]" value="<?php echo $schemeWeeklyRow['mobile_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <?php echo $schemeWeeklyRow['sub_category']; ?> </td>
            <td> <?php $obj = new GetLoanDetails($connect, $schemeWeeklyRow['req_id'], date('Y-m-d'),''); ?> 
                <input type="text" name="m_cus_due[]" value="<?php echo $obj->response['due_amt']; ?>" style="border: none; outline: 0; background: inherit;" readonly>
            </td>
        </tr>
    <?php
        $swsno++;
    }
    ?>
    </tbody>
</table>
</form>
<!-- scheme Weekly END-->
</br></br></br></br>
<!-- Scheme Daily START-->
<form name="scheme_daily_loan_reminderList" method="POST">
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="send_scheme_daily_reminder" name="send_scheme_daily_reminder" style="padding: 5px 10px; float: right;" value="sendSMS">Send SMS</button> 
        <span id="sd_alert" class="required" style="display: none;">*There is no Customer to send Due Reminder</span>
    </div>
</div>
</br>
<table id='scheme_daily_due_followup_table' class="table custom-table">
    <thead>
        <tr><th colspan="6">Scheme Daily Loan</th></tr>
        <tr>
            <th width="50">S.No.</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Mobile No</th>
            <th>Loan Sub category</th>
            <th>Due Amount</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $getSchemeDailyQry = "SELECT alc.cus_id_loan as cp_cus_id, alc.cus_name_loan,alc.sub_category,alc.mobile_loan, ii.cus_id as ii_cus_id, ii.req_id
    FROM acknowlegement_loan_calculation alc 
    JOIN in_issue ii ON alc.cus_id_loan = ii.cus_id
    JOIN customer_register cr ON alc.cus_id_loan = cr.cus_id
    where ii.status = 0 
    AND (ii.cus_status >= 14 AND ii.cus_status <= 17) 
    AND alc.profit_type = '2'
    AND alc.due_method_scheme = '3'
    AND cr.area_confirm_subarea IN ($sub_area_list) ";// GROUP BY ii.cus_id  14 and 17 means collection entries, 17 removed from issue list //alc.profit_type = '2' -- Scheme // alc.due_method_scheme = '3' -- Scheme Daily
    
    $schemeDailyDetails = $connect->query($getSchemeDailyQry);
    $schemeDailyInfo = $schemeDailyDetails->fetchAll();
    $sdsno = 1;
    foreach ($schemeDailyInfo as $schemeDailyRow) {
    ?>
        <tr>
            <td> <?php echo $sdsno; ?> </td>
            <td> <?php echo $schemeDailyRow['cp_cus_id']; ?> </td>
            <td> <input type="text" class="sd_cusName" name="m_cus_name[]" value="<?php echo $schemeDailyRow['cus_name_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <input type="text" name="m_cus_no[]" value="<?php echo $schemeDailyRow['mobile_loan']; ?>" style="border: none; outline: 0; background: inherit;" readonly> </td>
            <td> <?php echo $schemeDailyRow['sub_category']; ?> </td>
            <td> <?php $obj = new GetLoanDetails($connect, $schemeDailyRow['req_id'], date('Y-m-d'),'');?>
                <input type="text" name="m_cus_due[]" value="<?php echo $obj->response['due_amt']; ?>" style="border: none; outline: 0; background: inherit;" readonly> 
            </td>
        </tr>
    <?php
        $sdsno++;
    }
    ?>
    </tbody>
</table>
</form>
<!-- Scheme Daily END-->

<script type="text/javascript">
    $(function() {
        $('#monthly_due_followup_table').DataTable({
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

        $('#scheme_monthly_due_followup_table').DataTable({
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

        $('#scheme_weekly_due_followup_table').DataTable({
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

        $('#scheme_daily_due_followup_table').DataTable({
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