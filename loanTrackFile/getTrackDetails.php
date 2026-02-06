<?php
// session_start();
include '../ajaxconfig.php';


$obj = new getTrackTableDetails;

if (!isset($_POST['stage'])) {
    //this is to prevent auto loading track details
    //if stage is set in post, then the class is called from getStageDetails.php , so it should not call and return table of Track details
    $obj->getBaseDetails($connect);
}

class getTrackTableDetails
{
    public $usertypeArr = ['', 'Director', 'Agent', 'Staff'];

    public function getBaseDetails($connect)
    {

        $req_id = $_POST['req_id'] ?? '';
        $i = 0;
        $data = array();

        $qry = $connect->query("SELECT cus_status, update_login_id, updated_date FROM request_creation WHERE req_id = '$req_id'");
        $row = $qry->fetch();
        $cus_status = $row['cus_status'] ?? '';
        $update_login_id = $row['update_login_id'] ?? '';
        $updated_date = $row['updated_date'] ?? '';

        if ($cus_status != '') {

            // Request
            $qry = $connect->query("SELECT cus_id,sub_area,insert_login_id,created_date,update_login_id,updated_date from request_creation where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $cus_id = $row['cus_id'];
                $branch = $this->getBranchName($connect, $row['sub_area'], 'group');
                $data[] = $this->getTrackDetails($connect, 'Request', $row['created_date'], $row['insert_login_id'], $branch);

                // ✅ If customer canceled at Request stage
                if ($cus_status == 4) {
                    $data[] = $this->getTrackDetails($connect, 'Request - Cancel', $row['updated_date'], $row['update_login_id'], $branch);
                } else if ($cus_status == 8) {  // Revoke
                    $data[] = $this->getTrackDetails($connect, 'Request - Revoke', $row['updated_date'], $row['update_login_id'], $branch);
                }
            }

            // Customer Profile
            $qry = $connect->query("SELECT area_confirm_subarea as sub_area,insert_login_id,created_date from customer_profile where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $branch = $this->getBranchName($connect, $row['sub_area'], 'group');
                $data[] = $this->getTrackDetails($connect, 'Customer Profile', $row['created_date'], $row['insert_login_id'], $branch);
            }

            // Documentation
            $qry = $connect->query("SELECT insert_login_id,created_date from verification_documentation where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $data[] = $this->getTrackDetails($connect, 'Documentation', $row['created_date'], $row['insert_login_id'], $branch);
            }

            // Loan Calculation
            $qry = $connect->query("SELECT insert_login_id,create_date from verification_loan_calculation where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $data[] = $this->getTrackDetails($connect, 'Loan Calculation', $row['create_date'], $row['insert_login_id'], $branch);
            }
            // Verification Cancel / Revoke
            if ($cus_status == 5) {
                $data[] = $this->getTrackDetails($connect, 'Verification - Cancel', $updated_date, $update_login_id, $branch);
            } else if ($cus_status == 9) {
                $data[] = $this->getTrackDetails($connect, 'Verification - Revoke', $updated_date, $update_login_id, $branch);
            } else if ($cus_status == 6) { //Appoval - Cancel
                $data[] = $this->getTrackDetails($connect, 'Approval - Cancel', $updated_date, $update_login_id, $branch);
            }

            // Approval
            $qry = $connect->query("SELECT inserted_user,inserted_date from in_acknowledgement where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $data[] = $this->getTrackDetails($connect, 'Approval', $row['inserted_date'], $row['inserted_user'], $branch);
            }
            // Acknowledgment Cancel
            if ($cus_status == 7) { 
                $data[] = $this->getTrackDetails($connect, 'Acknowledgment - Cancel', $updated_date, $update_login_id, $branch);
            }
            // Acknowledgment
            $qry = $connect->query("SELECT inserted_user,inserted_date from in_issue where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $qry1 = $connect->query("SELECT area_confirm_subarea as sub_area from acknowlegement_customer_profile where req_id = $req_id");
                $sub_area_id = $qry1->fetch()['sub_area'];
                $branch = $this->getBranchName($connect, $sub_area_id, 'group');
                $data[] = $this->getTrackDetails($connect, 'Acknowledgment', $row['inserted_date'], $row['inserted_user'], $branch);
            }

            // Loan Issue
            $qry = $connect->query("SELECT insert_login_id,created_date from loan_issue where req_id = $req_id order by `id` DESC LIMIT 1"); //limit 1 desc because that table will have multiple lines for single customer, so last would be the correct one
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $data[] = $this->getTrackDetails($connect, 'Loan Issue', $row['created_date'], $row['insert_login_id'], $branch);
            }

            // Closed
            $qry = $connect->query("SELECT insert_login_id,created_date from closed_status where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $branch = $this->getBranchName($connect, $sub_area_id, 'line');
                $data[] = $this->getTrackDetails($connect, 'Closed', $row['created_date'], $row['insert_login_id'], $branch);
            }

            // NOC
            $qry = $connect->query("SELECT insert_login_id,created_date from noc where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $branch = $this->getBranchName($connect, $sub_area_id, 'line');
                $data[] = $this->getTrackDetails($connect, 'NOC', $row['created_date'], $row['insert_login_id'], $branch);
            }
           // NOC Handover
            $qry = $connect->query("SELECT update_login_id,updated_date from noc where req_id = $req_id AND cus_status = 24");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $branch = $this->getBranchName($connect, $sub_area_id, 'line');
                $data[] = $this->getTrackDetails($connect, 'NOC Handover', $row['updated_date'], $row['update_login_id'], $branch);
            }
        }

?>

        <table class="table table-bordered">
            <thead>
                <th width="10%">S.No</th>
                <th>Loan Stage</th>
                <th>Date</th>
                <th>User Type</th>
                <th>User Name</th>
                <th>Branch</th>
                <th>Details</th>
            </thead>
            <tbody>
                <?php
                foreach ($data as $item) {
                ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $item['stage']; ?></td>
                        <td><?php echo $item['date']; ?></td>
                        <td><?php echo $item['usertype']; ?></td>
                        <td><?php echo $item['fullname']; ?></td>
                        <td><?php echo $item['branch']; ?></td>
                        <td><?php echo $item['action']; ?></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
<?php
    }
    public function getTrackDetails($connect, $stage, $date, $user_id, $branch)
    {
        $req_id = $_POST['req_id'] ?? '';
        $user_id = $connect->quote($user_id);
        $qry = $connect->query("SELECT `role`,`fullname` FROM `user` WHERE user_id=" . $user_id);
        $row = $qry->fetch();

        $date = date('d-m-Y', strtotime($date));
        $usertype = $this->usertypeArr[$row['role']];

        $response = array('stage' => $stage, 'date' => $date, 'usertype' => $usertype, 'fullname' => $row['fullname'], 'branch' => $branch);

        $response['action'] = '';

        if ($stage == 'Loan Calculation') {
            $response['action'] = "<input type='button' class='btn btn-primary stage-detail' value='View' data-toggle='modal' data-target='#stageDetails' data-req_id= '" . $req_id . "' data-stage='lc'>";
        }
        if ($stage == 'Loan Issue') {
            $response['action'] = "<input type='button' class='btn btn-primary stage-detail' value='View' data-toggle='modal' data-target='#stageDetails' data-req_id='" . $req_id . "' data-stage='li'>";
        }
        if ($stage == 'NOC Handover') {
            $response['action'] = "<input type='button' class='btn btn-primary stage-detail' value='View' data-toggle='modal' data-target='#stageDetails' data-req_id='" . $req_id . "' data-stage='noc'>";
        }
        return $response;
    }
    public function getBranchName($connect, $sub_area, $type)
    {
        if ($type == 'group') {
            $qry = $connect->query("SELECT bc.branch_name from area_group_mapping_sub_area agmsa 
            LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id 
            LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
            where agmsa.sub_area_id = $sub_area");
        } else if ($type == 'line') {
            $qry = $connect->query("SELECT bc.branch_name from area_line_mapping_sub_area almsa 
            LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id 
            LEFT JOIN branch_creation bc ON alm.branch_id = bc.branch_id 
            where almsa.sub_area_id = $sub_area");
        }
        $branch_name = $qry->fetch()['branch_name'];
        return $branch_name;
    }

  
}
?>