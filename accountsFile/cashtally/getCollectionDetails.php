<?php
include('../../ajaxconfig.php');

if(isset($_POST['branch_id'])){
    $branch_id = explode(',',$_POST['branch_id']);
}

if(isset($_POST['op_date'])){
    $op_date = date('Y-m-d',strtotime($_POST['op_date']));
}

$records = array();
$i=0;
foreach($branch_id as $val){
    $qry = $connect->query("SELECT sum(total_paid_track) as total_paid, insert_login_id from collection where branch = '$val' and date(created_date) = '$op_date' and coll_mode = '1' GROUP BY insert_login_id");
    while($row = $qry->fetch()){
        //get user id and total paid by user by cash
        $records[$i]['user_id'] = $row['insert_login_id'];
        $records[$i]['collected_amt'] = $row['total_paid'];

        //get username by user id to shortlist
        $usernameqry = $connect->query("SELECT us.fullname,us.role,us.line_id,lm.line_name from user us LEFT JOIN area_line_mapping lm ON us.line_id = lm.map_id where us.user_id = '".strip_tags($row['insert_login_id'])."' ");
        $row1 = $usernameqry->fetch();
        if($row1['role'] != '2'){ // check if inserted person is not agent here by checking role 

            $records[$i]['user_name'] = $row1['fullname'];
            $records[$i]['user_type'] = $row1['role'];
            $records[$i]['line_id'] = $row1['line_id'];
            $records[$i]['line_name'] = $row1['line_name'];
            
            //get branchname by branch id
            $branchnameqry = $connect->query("SELECT branch_name from branch_creation where branch_id = '".strip_tags($val)."' ");
            $records[$i]['branch_name'] = $branchnameqry->fetch()['branch_name'];
            
            {
                // To get total collection amount till yesterday
                $getcolltillys = $connect->query("SELECT sum(total_paid_track) as coll_amt_ys from collection where insert_login_id = '".$row['insert_login_id']."' and coll_mode='1' and date(created_date) <= '$op_date' ");
                if($getcolltillys){
                    $row2 = $getcolltillys->fetch();
                    $total_collection_amt = $row2['coll_amt_ys'];
                }else{$total_collection_amt = 0;}
                
                //To get Total received amount till yesterday
                $getrectillys = $connect->query("SELECT sum(rec_amt) as rec_amt_ys from ct_hand_collection where user_id = '".$row['insert_login_id']."' and date(created_date) <= '$op_date' ");
                if($getrectillys){
                    $total_rec_amt = $getrectillys->fetch()['rec_amt_ys'];
                }else{$total_rec_amt = 0;}

                $records[$i]['pre_bal'] = $total_collection_amt - $total_rec_amt;
            }

            {
                // To get total collection amount till today
                $getcolltillys = $connect->query("SELECT sum(total_paid_track) as coll_amt_ys from collection where insert_login_id = '".$row['insert_login_id']."' and coll_mode='1' and date(created_date) <= '$op_date' ");
                if($getcolltillys){
                    $row2 = $getcolltillys->fetch();
                    $total_collection_amt = $row2['coll_amt_ys'];
                    $records[$i]['overall_coll'] = $total_collection_amt;
                }else{$total_collection_amt = 0;$records[$i]['overall_coll'] = $total_collection_amt;}
                
                //To get Total received amount till today
                $getrectillys = $connect->query("SELECT sum(rec_amt) as rec_amt_ys from ct_hand_collection where user_id = '".$row['insert_login_id']."' and date(created_date) <= '$op_date' ");
                if($getrectillys){
                    $total_rec_amt = $getrectillys->fetch()['rec_amt_ys'];
                    $records[$i]['overall_rec'] = $total_rec_amt;
                }else{$total_rec_amt = 0;$records[$i]['overall_rec'] = $total_rec_amt;}

                $records[$i]['tot_amt'] = $total_collection_amt - $total_rec_amt;
            }

        }
        $i++;
    }
}

// Close the database connection
$connect = null;
?>

<table class="table custom-table" id='collectionTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <th>Branch</th>
            <th>Line</th>
            <th>Pre Balance</th>
            <th>Today's Collection</th>
            <!-- <th>Overall Collection</th> -->
            <th>Total Balance</th>
            <!-- <th>Total Received</th> -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $pre_bal = 0;
            for($i=0;$i<sizeof($records);$i++){
                if(isset($records[$i]['pre_bal']) and $records[$i]['pre_bal'] != '0'){
        ?>
            <tr>
                <td></td>
                <td><?php if($records[$i]['user_type'] == '1'){echo 'Director';}elseif($records[$i]['user_type'] == '3'){echo 'Staff';}?></td>
                <td><?php echo $records[$i]['user_name'];?></td>
                <td><?php echo $records[$i]['branch_name'];?></td>
                <td><?php echo $records[$i]['line_name'];?></td>
                <td><?php echo moneyFormatIndia($records[$i]['pre_bal']) ;?></td>
                <td><?php echo moneyFormatIndia($records[$i]['collected_amt']);?></td>
                <!-- <td><?php echo moneyFormatIndia($records[$i]['overall_coll']);?></td> -->
                <td><?php echo moneyFormatIndia($records[$i]['tot_amt']);?></td>
                <!-- <td><?php echo moneyFormatIndia($records[$i]['overall_rec']);?></td> -->
                <td>
                    <input type='button' id='collect_btn1' name='collect_btn1' class="btn btn-primary collect_btn" data-value = '<?php echo $records[$i]['user_id']; ?>' data-toggle="modal" data-target=".coll_modal" value='Receive' onclick="collectBtnClick(this)">
                </td>
            </tr>
        <?php
        }}
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#collectionTable').DataTable({
            "title":"Collection List",
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
?>