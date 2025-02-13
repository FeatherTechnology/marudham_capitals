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

        $qry = $connect->query("SELECT cus_status FROM request_creation where req_id = '$req_id' ");
        $cus_status = $qry->fetch()['cus_status'] ?? '';

        if ($cus_status != '') {

            // Request
            $qry = $connect->query("SELECT cus_id,sub_area,insert_login_id,created_date from request_creation where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $cus_id = $row['cus_id'];
                $branch = $this->getBranchName($connect, $row['sub_area'], 'group');
                $data[] = $this->getTrackDetails($connect, 'Request', $row['created_date'], $row['insert_login_id'], $branch);
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

            // Approval
            $qry = $connect->query("SELECT inserted_user,inserted_date from in_acknowledgement where req_id = $req_id");
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch();
                $data[] = $this->getTrackDetails($connect, 'Approval', $row['inserted_date'], $row['inserted_user'], $branch);
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
            $nocStatus = $this->getNocCompletedStatusbyReq($connect, $req_id); //if this variable contains value 0 then all document are given to customer as noc. so need to take latest noc submission
            if ($nocStatus == 0) {
                //if all docs are given then read which user gives the last document
                $nocDetails = $this->getLatestNOCDetails($connect, $req_id);
                if (!empty($nocDetails)) {
                    $data[] = $this->getTrackDetails($connect, 'NOC', $nocDetails['updated_date'], $nocDetails['insert_login_id'], $branch);
                }
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
                <th>Name</th>
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
                        <td><?php echo $item['username']; ?></td>
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
        $qry = $connect->query("SELECT `role`,`user_name`,`fullname` FROM `user` WHERE user_id=" . $user_id);
        $row = $qry->fetch();

        $date = date('d-m-Y', strtotime($date));
        $usertype = $this->usertypeArr[$row['role']];

        $response = array('stage' => $stage, 'date' => $date, 'usertype' => $usertype, 'username' => $row['user_name'], 'fullname' => $row['fullname'], 'branch' => $branch);

        $response['action'] = '';

        if ($stage == 'Loan Calculation') {
            $response['action'] = "<input type='button' class='btn btn-primary stage-detail' value='View' data-toggle='modal' data-target='#stageDetails' data-req_id= '" . $req_id . "' data-stage='lc'>";
        }
        if ($stage == 'Loan Issue') {
            $response['action'] = "<input type='button' class='btn btn-primary stage-detail' value='View' data-toggle='modal' data-target='#stageDetails' data-req_id='" . $req_id . "' data-stage='li'>";
        }
        if ($stage == 'NOC') {
            $response['action'] = "<input type='button' class='btn btn-primary stage-detail' value='View' data-toggle='modal' data-target='#stageDetails' data-req_id='" . $req_id . "' data-stage='noc'>";
        }
        return $response;
    }
    public function getBranchName($connect, $sub_area, $type)
    {
        if ($type == 'group') {
            $qry = $connect->query("SELECT bc.branch_name from area_group_mapping agm LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id where FIND_IN_SET($sub_area,agm.sub_area_id)");
        } else if ($type == 'line') {
            $qry = $connect->query("SELECT bc.branch_name from area_line_mapping alm LEFT JOIN branch_creation bc ON alm.branch_id = bc.branch_id where FIND_IN_SET($sub_area,alm.sub_area_id)");
        }
        $branch_name = $qry->fetch()['branch_name'];
        return $branch_name;
    }
    public function getNocCompletedStatusbyReq($connect, $req_id)
    {
        //this function is to find out whether all of the req id's documents are given to customer or not
        // also it will return values if the document is temporarly taken out for some purpose. they should mark as returned in respective screen and to give noc here
        $response = 0;

        $sql = $connect->query("SELECT sd.* From signed_doc sd JOIN in_issue ii ON ii.req_id = sd.req_id where ii.cus_status = 21 and ii.req_id = $req_id and sd.noc_given !='1' ");
        $response = $response + $sql->rowCount();

        $sql = $connect->query("SELECT cnl.* From cheque_no_list cnl JOIN in_issue ii ON ii.req_id = cnl.req_id where ii.cus_status = 21 and ii.req_id = $req_id and cnl.noc_given !='1' ");
        $response = $response + $sql->rowCount();

        $sql = $connect->query("SELECT ackd.* From acknowlegement_documentation ackd JOIN in_issue ii ON ii.req_id = ackd.req_id where ii.cus_status = 21 and ii.req_id = $req_id and ackd.mortgage_process = 0 and ( ackd.mortgage_process_noc != '1' || (ackd.mortgage_document = 0 and ackd.mortgage_document_upd IS NOT NULL and ackd.mortgage_document_noc != '1' ) ) ");
        $response = $response + $sql->rowCount();

        $sql = $connect->query("SELECT ackd.* From acknowlegement_documentation ackd JOIN in_issue ii ON ii.req_id = ackd.req_id where ii.cus_status = 21 and ii.req_id = $req_id and ackd.endorsement_process = 0 and ( (ackd.endorsement_process_noc != '1') || (ackd.en_RC = 0 && ackd.en_RC_noc != '1') || (ackd.en_Key = 0 && ackd.en_Key_noc != '1')) ");
        $response = $response + $sql->rowCount();

        $sql = $connect->query("SELECT gi.* From gold_info gi JOIN in_issue ii ON ii.req_id = gi.req_id where ii.cus_status = 21 and ii.req_id = $req_id and gi.noc_given !='1' ");
        $response = $response + $sql->rowCount();

        $sql = $connect->query("SELECT di.* From document_info di JOIN in_issue ii ON ii.req_id = di.req_id where ii.cus_status = 21 and ii.req_id = $req_id and di.doc_info_upload_noc !='1' ");
        $response = $response + $sql->rowCount();

        // echo $cus_id.' - '.$response.'***';
        return $response;
    }
    public function getLatestNOCDetails($connect, $req_id)
    {
        //this function is to find out whether all of the req id's documents are given to customer or not
        // also it will return values if the document is temporarly taken out for some purpose. they should mark as returned in respective screen and to give noc here
        $response = array();

        $sql = $connect->query("SELECT sd.* From signed_doc sd JOIN in_issue ii ON ii.req_id = sd.req_id where ii.cus_status = 21 and ii.req_id = $req_id and sd.noc_given ='1' ");
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch()) {

                $response[] = array('insert_login_id' => $row['update_login_id'], 'updated_date' => $row['updated_date'], 'table' => 'signed_doc', 'id' => $row['id']);
            }
        }

        $sql = $connect->query("SELECT cnl.* From cheque_no_list cnl JOIN in_issue ii ON ii.req_id = cnl.req_id where ii.cus_status = 21 and ii.req_id = $req_id and cnl.noc_given ='1' ");
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch()) {
                $response[] = array('insert_login_id' => $row['update_login_id'], 'updated_date' => $row['updated_date'], 'table' => 'cheque_no_list', 'id' => $row['id']);
            }
        }

        $sql = $connect->query("SELECT ackd.* From acknowlegement_documentation ackd JOIN in_issue ii ON ii.req_id = ackd.req_id where ii.cus_status = 21 and ii.req_id = $req_id and ackd.mortgage_process = 0 and ( ackd.mortgage_process_noc = '1' || (ackd.mortgage_document = 0 and ackd.mortgage_document_upd IS NOT NULL and ackd.mortgage_document_noc = '1' ) ) ");
        if ($sql->rowCount() > 0) {
            $row = $sql->fetch();
            $response[] = array('insert_login_id' => $row['update_login_id'], 'updated_date' => $row['updated_date'], 'table' => 'mort', 'id' => $row['id']);
        }

        $sql = $connect->query("SELECT ackd.* From acknowlegement_documentation ackd JOIN in_issue ii ON ii.req_id = ackd.req_id where ii.cus_status = 21 and ii.req_id = $req_id and ackd.endorsement_process = 0 and ( (ackd.endorsement_process_noc = '1') || (ackd.en_RC = 0 && ackd.en_RC_noc = '1') || (ackd.en_Key = 0 && ackd.en_Key_noc = '1')) ");
        if ($sql->rowCount() > 0) {
            $row = $sql->fetch();
            $response[] = array('insert_login_id' => $row['update_login_id'], 'updated_date' => $row['updated_date'], 'table' => 'endorse', 'id' => $row['id']);
        }

        $sql = $connect->query("SELECT gi.* From gold_info gi JOIN in_issue ii ON ii.req_id = gi.req_id where ii.cus_status = 21 and ii.req_id = $req_id and gi.noc_given ='1' ");
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch()) {

                $response[] = array('insert_login_id' => $row['update_login_id'], 'updated_date' => $row['updated_date'], 'table' => 'gold_info', 'id' => $row['id']);
            }
        }

        $sql = $connect->query("SELECT di.* From document_info di JOIN in_issue ii ON ii.req_id = di.req_id where ii.cus_status = 21 and ii.req_id = $req_id and di.doc_info_upload_noc ='1' ");
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch()) {

                $response[] = array('insert_login_id' => $row['update_login_id'], 'updated_date' => $row['updated_date'], 'table' => 'document_info', 'id' => $row['id']);
            }
        }

        // Loop through the response array to find the latest updated_date
        $latestDate = '';
        foreach ($response as $item) {
            if ($item['updated_date'] > $latestDate) {
                $latestDate = $item['updated_date'];
            }
        }

        // Create a new array with only the latest date value
        $latestResponse = array();
        foreach ($response as $item) {
            if ($item['updated_date'] == $latestDate) {
                $latestResponse = $item;
            }
        }
        return $latestResponse;
    }

    public function getNOCDetails($connect, $table_id, $table_name)
    {
        if ($table_name != 'mort' && $table_name != 'endorse') {
            $qry = $connect->query("SELECT noc_date,noc_person,noc_name from $table_name where id = $table_id");
            $row = $qry->fetch();
            $response = $row;
        } else if ($table_name == 'mort') {
            $qry = $connect->query("SELECT mort_noc_date as noc_date,mort_noc_person as noc_person,mort_noc_name as noc_name from acknowlegement_documentation where id = $table_id ");
            $row = $qry->fetch();
            $response = $row;
        } else if ($table_name == 'endorse') {
            $qry = $connect->query("SELECT endor_noc_date as noc_date, endor_noc_person as noc_person, endor_noc_person as noc_name from acknowlegement_documentation where id = $table_id ");
            $row = $qry->fetch();
            $response = $row;
        }

        if ($response['noc_person'] == '1') {
            //1 means customer
            $response['noc_person'] = $response['noc_name'];
            $response['noc_name'] = 'Customer';
        } else if ($response['noc_person'] == '2') {
            //2 means Family member
            $fam_qry = $connect->query("SELECT famname,relationship from verification_family_info where id = '" . strip_tags($response['noc_name']) . "' ");
            $fam_row = $fam_qry->fetch();
            $response['noc_person'] = $fam_row['famname'];
            $response['noc_name'] = $fam_row['relationship'];
        }

        $response['noc_date'] = date('d-m-Y', strtotime($response['noc_date']));

        return $response;
    }
}
?>