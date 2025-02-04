<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $doc_rec_access = $rowuser['doc_rec_access'];
    }
}

$column = array(
    'dt.id',
    'dt.cus_id',
    'cr.customer_name',
    'bc.branch_name',
    'al.area_name',
    'sal.sub_area_name',
    'agm.group_name',
    'alm.line_name',
    'dt.id',
    'dt.id',
    'dt.id'
);

// Base query
$query = "SELECT dt.*, cr.customer_name, bc.branch_name, al.area_name, sal.sub_area_name, agm.group_name, alm.line_name, cr.sub_area
        FROM document_track dt
        JOIN customer_register cr ON dt.cus_id = cr.cus_id
        JOIN area_list_creation al ON cr.area = al.area_id
        JOIN sub_area_list_creation sal ON cr.sub_area = sal.sub_area_id
        LEFT JOIN area_group_mapping agm ON FIND_IN_SET(cr.sub_area, agm.sub_area_id)
        LEFT OUTER JOIN branch_creation bc ON agm.branch_id = bc.branch_id
        LEFT OUTER JOIN area_line_mapping alm ON FIND_IN_SET(cr.sub_area, alm.sub_area_id)
        WHERE dt.track_status != 5";

// Apply user-specific filter
if ($doc_rec_access != '0') {
    $query .= " AND dt.insert_login_id = $userid";
}

// Apply search filter
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND ( dt.cus_id LIKE '%$search%' OR
                cr.customer_name LIKE '%$search%'  )";
}

if (isset($_POST['order'])) {
    $query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}


$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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

    $sub_array[] = date('d-m-Y', strtotime($row['created_date'])); //Date column
    $sub_array[] = $row['cus_id']; //cus id column
    $cus_name = $row['customer_name']; //cus name column
    $sub_array[] = $cus_name; //cus name column
    $sub_array[] = $row['branch_name']; //Branch name column
    $sub_array[] = $row['area_name']; //area name column
    $sub_array[] = $row['sub_area_name']; //sub area name column
    $sub_array[] = $row['group_name']; //group name column
    $sub_array[] = $row['line_name']; //line name column

    $cus_id = $row['cus_id'];
    $req_id = $row['req_id'];
    $track_status = $row['track_status'];
    $track_status_obj = [
        '1' => 'Acknowledgement', '2' => 'Acknowledgement', '3' => 'NOC', '4' => 'NOC'
    ];
    $sub_array[] = $track_status_obj[$track_status]; //Document For Column


    if ($track_status == '1') { //if 1 then raised from branch for submitting ack

        //then document keeper will be inser login id
        $doc_keeper = $row['insert_login_id'];

        $qry = $connect->query("SELECT fullname FROM user WHERE user_id = $doc_keeper ");
        $sub_array[] = $qry->fetch()['fullname']; //document keeper column

    } else if ($track_status == '2') {

        //if status id 2 means, received in main branch
        $sub_array[] = 'Main Branch'; //document keeper column

    } else if ($track_status == '3') {

        //if status is 3, then documents not yet moved from main branch for noc
        $sub_array[] = 'Main Branch'; //document keeper column

    } elseif ($track_status == '4') {

        $branchqry = $connect->query("SELECT bc.branch_name FROM area_line_mapping lm JOIN branch_creation bc ON lm.branch_id = bc.branch_id where FIND_IN_SET('" . $row['sub_area'] . "' , lm.sub_area_id) ");
        $sub_array[] = $branchqry->fetch()['branch_name'] . " Branch"; //document keeper column

    }

    $id = $row['id']; //table id

    $action = '';
    $action .= "<button class='btn btn-info'><i class='icon-eye'></i></button>";
    if ($doc_rec_access == '0') {
        $action .= "<button class='btn btn-success'>Receive</button>";
    }

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    $action .= "<a href='' title='View details' class='view-track' data-reqid='$req_id' data-cusid='$cus_id' data-cusname='$cus_name' data-toggle='modal' data-target='.viewDocModal'>View</a>";

    if ($doc_rec_access == '0') {

        if ($track_status == '1' and $userid != $row['insert_login_id']) { //1 means submitted in ack
            //show receive track when sent from ack
            $action .= "<a href='' title='Receive Documents' class='receive-track' data-id='$id' data-reqid='$req_id' >Receive</a>";
        } else if ($track_status == '3') { //3 means to be sent for noc
            //show Mark Sent when Moveing from main branch to branch for noc
            $action .= "<a href='' title='Mark Documents Sent' class='send-track' data-id='$id' data-reqid='$req_id'>Mark as Sent</a>";
        }
    }

    if ($track_status == '2' || $track_status == '4') {

        $action .= "<a href='' title='Remove Track' class='remove-track' data-id='$id' data-reqid='$req_id' >Remove Track</a>";
    }

    $action .= "</div></div>";

    $sub_array[] = $action;


    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM document_track";
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