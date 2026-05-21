<?php
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

if (isset($_POST['user_branch_id'])) {
    $user_branch_id = $_POST['user_branch_id'];
}

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}

if (isset($_POST['branch_id'])) {
    $branch_id = $_POST['branch_id'];
}

if (isset($_POST['line_id'])) {
    $line_id = $_POST['line_id'];
}

if (isset($_POST['op_date'])) {
    $op_date = date('Y-m-d', strtotime($_POST['op_date']));
    $to_date = date('Y-m-d', strtotime($op_date . ' +1 day'));
}

$records = array();
$waiver_amt = 0;
$qry = $connect->query("SELECT SUM(pre_close_waiver) AS total_pre_close_waiver FROM collection WHERE branch IN ($branch_id) AND insert_login_id = '$user_id' AND (coll_date >= '$op_date' AND coll_date < '$to_date') AND coll_mode = '1' AND pre_close_waiver > 0 ");

$row = $qry->fetch();
//get user id and total paid by user by cash
$waiver_amt  = $row['total_pre_close_waiver'];


//get username by user id to shortlist
$usernameqry = $connect->query("SELECT us.fullname, us.role FROM user us WHERE us.user_id = '$user_id' ");
$row1 = $usernameqry->fetch();

if ($row1['role'] != '2') {

    $user_name = $row1['fullname'];

    //get branchname by branch id
    $branchnameqry = $connect->query("SELECT GROUP_CONCAT(branch_name, ' ') AS branch_name FROM branch_creation WHERE branch_id IN ($branch_id) ");
    $branch_name = $branchnameqry->fetch()['branch_name'];

    $linenameqry = $connect->query("SELECT GROUP_CONCAT(line_name, ' ') AS line_name FROM area_line_mapping WHERE map_id IN ($line_id) ");
    $line_name = $linenameqry->fetch()['line_name'];
}

// To get total collection amount till yesterday
$getwaivertillys = $connect->query("SELECT SUM(pre_close_waiver) AS waiver_amt_ys FROM collection WHERE branch IN ($user_branch_id) AND insert_login_id = '$user_id' AND coll_mode = '1' AND coll_date < '$op_date' AND pre_close_waiver > 0 ");
if ($getwaivertillys) {
    $row2 = $getwaivertillys->fetch();
    $total_waiver_amt_ys = $row2['waiver_amt_ys'];
} else {
    $total_waiver_amt_ys = 0;
}

//To get Total received amount till yesterday
$getrectillys = $connect->query("SELECT SUM(rec_amt) AS rec_amt_ys FROM ct_hand_waiver WHERE branch_id IN ($user_branch_id) AND user_id = '$user_id' AND created_date < '$op_date' ");
if ($getrectillys) {
    $total_rec_amt_ys = $getrectillys->fetch()['rec_amt_ys'];
} else {
    $total_rec_amt_ys = 0;
}

$pre_waiver = $total_waiver_amt_ys - $total_rec_amt_ys;

// To get total collection amount till today
$getwaivertilltdy = $connect->query("SELECT SUM(pre_close_waiver) AS waiver_amt_tdy FROM collection WHERE branch IN ($user_branch_id) AND insert_login_id = '$user_id' AND coll_mode = '1' AND coll_date < '$to_date' AND pre_close_waiver > 0 ");
if ($getwaivertilltdy) {
    $row2 = $getwaivertilltdy->fetch();
    $total_waiver_amt_tdy = $row2['waiver_amt_tdy'];
} else {
    $total_waiver_amt_tdy = 0;
}

//To get Total received amount till today
$getrectilltdy = $connect->query("SELECT SUM(rec_amt) AS rec_amt_tdy FROM ct_hand_waiver WHERE branch_id IN ($user_branch_id) AND user_id = '$user_id' AND created_date < '$to_date' ");
if ($getrectilltdy) {
    $total_rec_amt_tdy = $getrectilltdy->fetch()['rec_amt_tdy'];
} else {
    $total_rec_amt_tdy = 0;
}

$tot_amt = $total_waiver_amt_tdy - $total_rec_amt_tdy;
?>

<form id="waiver_rec_form" name="waiver_rec_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='user_name_rec'>User Name</label>
                    <input type="hidden" class="form-control" id='user_id_rec' name='user_id_rec' value='<?php echo $user_id ?>'>
                    <input type="text" class="form-control" id='user_name_rec' name='user_name_rec' value='<?php echo $user_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='branch_name_rec'>Branch Name</label>
                    <input type="hidden" class="form-control" id='branch_id_rec' name='branch_id_rec' value='<?php echo $branch_id ?>' readonly>
                    <input type="text" class="form-control" id='branch_name_rec' name='branch_name_rec' value='<?php echo $branch_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='line_name_rec'>Region Name</label>
                    <input type="hidden" class="form-control" id='line_id_rec' name='line_id_rec' value='<?php echo $line_id ?>' readonly>
                    <input type="text" class="form-control" id='line_name_rec' name='line_name_rec' value='<?php echo $line_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='pre_waiver_rec'>Pre Balance</label>
                    <input type="text" class="form-control" id='pre_waiver_rec' name='pre_waiver_rec' value='<?php echo moneyFormatIndia($pre_waiver); ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='waiver_amt_rec'>Waiver Amount</label>
                    <input type="text" class="form-control" id='waiver_amt_rec' name='waiver_amt_rec' value='<?php echo moneyFormatIndia($waiver_amt); ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='tot_waiver_rec'>Total Balance</label>
                    <input type="text" class="form-control" id='tot_waiver_rec' name='tot_waiver_rec' value='<?php echo moneyFormatIndia($tot_amt); ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id='submit_waiver' name='submit_waiver' value="Submit" <?php if($tot_amt <= 0) echo 'disabled';?> >
                </div>
            </div>

        </div>
    </div>
</form>

<table class="table custom-table" id='receivedWaiverTable'>
    <thead>
        <tr>
            <th width='50'>S.No</th>
            <th>Date</th>
            <th>User Name</th>
            <th>Waiver Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $qry = $connect->query("SELECT `user_name`, `created_date`, `rec_amt` FROM `ct_hand_waiver` WHERE branch_id IN ($branch_id) AND `user_id` = '$user_id' AND (MONTH(created_date) = MONTH('$op_date') AND YEAR(created_date) = YEAR('$op_date')) ORDER BY id DESC ");
        while ($row = $qry->fetch()) {
        ?>
            <tr>
                <td></td>
                <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo moneyFormatIndia($row['rec_amt']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        // Declare table variable to store the DataTable instance
        var receivedWaiverTable = $('#receivedWaiverTable').DataTable({
            ...getStateSaveConfig('receivedWaiverTable'),
            "title": "Amount Received List",
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
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('CT_Waiver_Amount_Received_List'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(receivedWaiverTable, 'receivedWaiverTable');
    });
</script>