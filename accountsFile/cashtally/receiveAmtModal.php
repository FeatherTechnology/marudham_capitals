<?php
include('../../ajaxconfig.php');


if(isset($_POST['user_id'])){
    $user_id1 = $_POST['user_id'];
}

if(isset($_POST['op_date'])){
    $op_date = date('Y-m-d',strtotime($_POST['op_date']));
}


// $user_id = '28';
$records = array();

    $qry = $connect->query("SELECT sum(total_paid_track) as total_paid,branch, insert_login_id from collection where insert_login_id = '$user_id1' and date(created_date) = '$op_date' and coll_mode = '1' GROUP BY insert_login_id");
    while($row = $qry->fetch()){
        //get user id and total paid by user by cash
        $branch_id = $row['branch'];
        $user_id = $row['insert_login_id'];
        $collected_amt = $row['total_paid'];

        //get username by user id to shortlist
        $usernameqry = $connect->query("SELECT us.fullname,us.role,us.line_id,lm.line_name from user us JOIN area_line_mapping lm ON us.line_id = lm.map_id where us.user_id = '".strip_tags($row['insert_login_id'])."' ");
        $row1 = $usernameqry->fetch();
        if($row1['role'] != '2'){

            $user_name = $row1['fullname'];
            $user_type = $row1['role'];
            $line_id = $row1['line_id'];
            $line_name = $row1['line_name'];
            
            //get branchname by branch id
            $branchnameqry = $connect->query("SELECT branch_name from branch_creation where branch_id = '".strip_tags($row['branch'])."' ");
            $branch_name = $branchnameqry->fetch()['branch_name'];
            
        }
    }

    // To get total collection amount till yesterday
    $getcolltillys = $connect->query("SELECT sum(total_paid_track) as coll_amt_ys from collection where insert_login_id = '".$user_id."' and coll_mode='1' and date(created_date) <= '$op_date'");
    if($getcolltillys){
        $row2 = $getcolltillys->fetch();
        $total_collection_amt = $row2['coll_amt_ys'];
    }else{$total_collection_amt = 0;}

    //To get Total received amount till yesterday
    $getrectillys = $connect->query("SELECT sum(rec_amt) as rec_amt_ys from ct_hand_collection where user_id = '".$user_id."' and date(created_date) <= '$op_date' ");
    if($getrectillys){
        $total_rec_amt = $getrectillys->fetch()['rec_amt_ys'];
    }else{$total_rec_amt = 0;}

    $pre_bal = $total_collection_amt - $total_rec_amt;



    // To get total collection amount till today
    $getcolltillys = $connect->query("SELECT sum(total_paid_track) as coll_amt_ys from collection where insert_login_id = '".$user_id."' and coll_mode='1' and date(created_date) <= '$op_date'");
    if($getcolltillys){
        $row2 = $getcolltillys->fetch();
        $total_collection_amt = $row2['coll_amt_ys'];
    }else{$total_collection_amt = 0;}
    //To get Total received amount till today
    $getrectillys = $connect->query("SELECT sum(rec_amt) as rec_amt_ys from ct_hand_collection where user_id = '".$user_id."' and date(created_date) <= '$op_date' ");
    if($getrectillys){
        $total_rec_amt = $getrectillys->fetch()['rec_amt_ys'];
    }else{$total_rec_amt = 0;}

    $tot_amt = $total_collection_amt - $total_rec_amt;


?>
<form id="coll_rec_form" name="coll_rec_form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='user_name_rec'>User Name</label>
                    <input type="hidden" class="form-control" id= 'user_id_rec' name='user_id_rec' value='<?php echo $user_id ?>' >
                    <input type="text" class="form-control" id= 'user_name_rec' name='user_name_rec' value='<?php echo $user_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='branch_name_rec'>Branch Name</label>
                    <input type="hidden" class="form-control" id= 'branch_id_rec' name='branch_id_rec' value='<?php echo $branch_id ?>' readonly>
                    <input type="text" class="form-control" id= 'branch_name_rec' name='branch_name_rec' value='<?php echo $branch_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='line_name_rec'>Line Name</label>
                    <input type="hidden" class="form-control" id= 'line_id_rec' name='line_id_rec' value='<?php echo $line_id ?>' readonly>
                    <input type="text" class="form-control" id= 'line_name_rec' name='line_name_rec' value='<?php echo $line_name ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='pre_bal_rec'>Pre Balance</label>
                    <input type="text" class="form-control" id= 'pre_bal_rec' name='pre_bal_rec' value='<?php echo moneyFormatIndia($pre_bal); ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='collected_amt_rec'>Collected Amount</label>
                    <input type="text" class="form-control" id= 'collected_amt_rec' name='collected_amt_rec' value='<?php echo moneyFormatIndia($collected_amt); ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='tot_amt_rec'>Total Balance</label>
                    <input type="text" class="form-control" id= 'tot_amt_rec' name='tot_amt_rec' value='<?php echo moneyFormatIndia($tot_amt); ?>' readonly>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label for='rec_amt'>Received Amount</label>
                    <input type="number" class="form-control" id= 'rec_amt' name='rec_amt' placeholder="Enter Receiving Amount" onkeyup="if(parseInt($(this).val()) > <?php echo $tot_amt;?>) {alert('Enter Lesser Amount');$(this).val('')}">
                    <span id='rec_amt_check' class="text-danger" style='display:none'>Please Enter Value</span>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class='form-group'>
                    <label style="visibility: hidden;">Submit button</label><br>
                    <input type="button" class="btn btn-primary" id= 'submit_rec' name='submit_rec' value="Submit" >
                </div>
            </div>
            
        </div>
    </div>
</form>

<table class="table custom-table" id='receivedTempTable'>
    <thead>
        <tr>
            <th width='50'>S.No</th>
            <th>Date</th>
            <th>User Name</th>
            <th>Received Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php

            $qry=$connect->query("SELECT `user_name`, `created_date`,`rec_amt` from `ct_hand_collection` where `user_id` = '$user_id1' ");
            while($row = $qry->fetch()){
        ?>
            <tr>
                <td></td>
                <td><?php echo date('d-m-Y',strtotime($row['created_date']));?></td>
                <td><?php echo $row['user_name'];?></td>
                <td><?php echo moneyFormatIndia($row['rec_amt']);?></td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#receivedTempTable').DataTable({
            "title":"Amount Received List",
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
//Format number in Indian Format
function moneyFormatIndia($num) {
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

// Close the database connection
$connect = null;
?>