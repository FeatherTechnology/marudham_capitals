<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $sql = $connect->query("SELECT ag_id FROM user where user_id = '$userid'");
    $login_user_type = $sql->fetch()['ag_id'];
    if ($login_user_type == null or $login_user_type == '') {
        $login_user_type = 0;
    }
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
    }
    $group_id = explode(',', $group_id);
    $sub_area_list = array();
    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT * FROM area_group_mapping where map_id = $group ");
        $row_sub = $groupQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',', $sub_area_ids);
}

$column = array(
    'a.req_id',
    'a.dor',
    'a.cus_id',
    'a.cus_name',
    'bc.branch_name',
    'ag.group_name',
    'alm.line_name',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'b.loan_amt',
    'a.user_type',
    'a.user_name',
    'a.agent_id',
    'a.responsible',
    'a.cus_data',
    'a.cus_status',
    'a.req_id'
);
if ($userid == 1) {
    $query = "SELECT a.dor,a.cus_id,a.cus_name,a.user_type,a.user_name,a.agent_id,a.responsible,a.cus_data,a.req_id,a.cus_status,a.req_id,b.sub_category,b.loan_amt,ac.area_name, sa.sub_area_name, ag.group_name, bc.branch_name, alm.line_name,lcc.loan_category_creation_name 
    FROM in_verification a 
    JOIN acknowlegement_loan_calculation b on a.req_id=b.req_id 
    JOIN acknowlegement_loan_calculation b on a.req_id=b.req_id 
    JOIN area_list_creation ac ON a.area = ac.area_id
    JOIN sub_area_list_creation sa ON a.sub_area = sa.sub_area_id
    JOIN area_group_mapping ag ON FIND_IN_SET(sa.sub_area_id, ag.sub_area_id)
    JOIN branch_creation bc ON ag.branch_id = bc.branch_id
    JOIN area_line_mapping alm ON FIND_IN_SET(sa.sub_area_id, alm.sub_area_id)
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = b.loan_category
    WHERE a.status = 0 and (a.cus_status = 13) and (a.issue_by = 1) "; // Move To Issue
} else {
    $query = "SELECT a.dor,a.cus_id,a.cus_name,a.user_type,a.user_name,a.agent_id,a.responsible,a.cus_data,a.req_id,a.cus_status,a.req_id,b.sub_category,b.loan_amt,ac.area_name, sa.sub_area_name, ag.group_name, bc.branch_name, alm.line_name,lcc.loan_category_creation_name 
    FROM in_verification a 
    JOIN acknowlegement_loan_calculation b on a.req_id=b.req_id 
    JOIN area_list_creation ac ON a.area = ac.area_id
    JOIN sub_area_list_creation sa ON a.sub_area = sa.sub_area_id
    JOIN area_group_mapping ag ON FIND_IN_SET(sa.sub_area_id, ag.sub_area_id)
    JOIN branch_creation bc ON ag.branch_id = bc.branch_id
    JOIN area_line_mapping alm ON FIND_IN_SET(sa.sub_area_id, alm.sub_area_id)
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = b.loan_category
    WHERE a.status = 0 and (a.cus_status = 13) and (a.issue_by = 1) and a.sub_area IN ($sub_area_list) ";  //show only Approved Verification in Acknowledgement. // 13 Move to Issue. // 14 Move To Collection.
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= " AND (a.dor LIKE '%" . $_POST['search'] . "%'
            OR a.cus_id LIKE '%" . $_POST['search'] . "%'
            OR a.cus_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR ag.group_name LIKE '%" . $_POST['search'] . "%'
            OR alm.line_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
            OR a.cus_data LIKE '%" . $_POST['search'] . "%' ) ";
}
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;

    $sub_array[] = date('d-m-Y', strtotime($row['dor']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];

    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["loan_category_creation_name"];
    $sub_array[] = $row['sub_category'];

    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = $row['user_type'];
    $sub_array[] = $row['user_name'];

    $ag_id = $row['agent_id'];
    if ($ag_id != '') {

        $qry = $connect->query("SELECT * FROM agent_creation where ag_id = $ag_id ");
        $row1 = $qry->fetch();
        $sub_array[] = $row1['ag_name'];
    } else {
        $sub_array[] = '';
    }

     if ($row['responsible'] == '0') {
        $sub_array[] = 'Yes';
    }else if (!empty($ag_id) && $row['responsible'] != '0') {
        $sub_array[] = 'No';
    } else {
        $sub_array[] = '';
    }

    $sub_array[] = $row['cus_data'];
    $id = $row['req_id'];

    $cus_status = $row['cus_status'];
    $loan_issued = $connect->query("SELECT balance_amount FROM `loan_issue` WHERE req_id='$id' order by id desc LIMIT 1 ");
    $loan_issued_db =  $loan_issued->fetch();

    if (empty($ag_id)) { // only check balance amount if request is not on agent

        if ($cus_status == '13') {

            if (isset($loan_issued_db['balance_amount']) && $loan_issued_db['balance_amount'] == '0') {
                $sub_array[] = "<button class='btn btn-outline-secondary complete_issue' value='$id'><span class = 'icon-arrow_forward'></span></button>";
            } else {
                $sub_array[] = 'In Issue';
            }
        } else if ($cus_status == '14') {
            $sub_array[] = 'Issued';
        }
    } else { //else directly show move button to collection, then it will be taken care by cash tally screen
        if ($cus_status == '14') {
            $sub_array[] = 'Issued';
        } else {
            $sub_array[] = "<button class='btn btn-outline-secondary complete_issue' value='$id'><span class = 'icon-arrow_forward'></span></button>";
        }
    }

    $id          = $row['req_id'];
    $user_type = $row['user_type'];
    $cus_id = $row['cus_id'];

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    if ($cus_status == '13' and empty($ag_id)) { // check whether agent id is empty, if yes then show edit button, so that only 'issued to customer' entries only can edit
        $action .= "<a href='loan_issue&upd=$id' class='customer_profile' value='$id' > Edit Loan Issue </a>";
    } else if ($cus_status == '14') {
        $action .= "<a href=''class='iss-remove' data-value='$id' > Remove </a>";
    }

    if ($login_user_type == 0 or $userid == 1) {
        $action .= "<a href='' data-value ='" . $cus_id . "' data-value1 = '$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'>Customer Status</a>";
    }


    $action .= "</div></div>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}
//Format number in Indian Format
function moneyFormatIndia($num)
{
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

function count_all_data($connect)
{
    $query     = "SELECT * FROM in_verification";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;