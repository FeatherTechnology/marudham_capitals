<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

if ($userid != 1) {
    $userQry = $connect->query("SELECT doc_rec_access FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $doc_rec_access = $rowuser['doc_rec_access'];
    }
}

$column = array(
    'dt.id',
    'dt.created_date',
    'ad.doc_id',
    'dt.cus_id',
    'cr.autogen_cus_id',
    'cr.customer_name',
    'bc.branch_name',
    'al.area_name',
    'sal.sub_area_name',
    'agm.group_name',
    'alm.line_name',
    'dt.id',
    'dt.id'
);

// Base query
// 1- inserted, 2- send by issued user, 3- received by doc_rec_access user, 1- return.
$query = "SELECT dt.id, dt.req_id, dt.cus_id, dt.track_status, dt.insert_login_id, dt.created_date, ad.doc_id, cr.autogen_cus_id, cr.customer_name, bc.branch_name, al.area_name, sal.sub_area_name, agm.group_name, alm.line_name, cr.sub_area, ad.noc_replace_status
        FROM document_track dt
        JOIN acknowlegement_documentation ad ON dt.req_id = ad.req_id
        JOIN customer_register cr ON dt.cus_id = cr.cus_id
        JOIN area_list_creation al ON cr.area = al.area_id
        JOIN sub_area_list_creation sal ON cr.sub_area = sal.sub_area_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = cr.sub_area
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        LEFT OUTER JOIN branch_creation bc ON agm.branch_id = bc.branch_id
        JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = cr.sub_area
        JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
        WHERE ( (dt.insert_login_id = $userid && dt.track_status <= 2) OR ($doc_rec_access = 0 && dt.track_status = 2) ) ";

// Apply search filter
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND ( dt.created_date LIKE '%$search%' OR
                dt.cus_id LIKE '%$search%' OR
                cr.autogen_cus_id LIKE '%$search%' OR
                cr.customer_name LIKE '%$search%' OR
                bc.branch_name LIKE '%$search%'  OR
                al.area_name LIKE '%$search%'  OR
                sal.sub_area_name LIKE '%$search%'  OR
                agm.group_name LIKE '%$search%'  OR
                alm.line_name LIKE '%$search%' )";
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

    $sub_array[] = $sno++;

    $sub_array[] = date('d-m-Y', strtotime($row['created_date'])); //Date column
    $sub_array[] = $row['doc_id']; //doc id column
    $sub_array[] = $row['cus_id']; //Aadhaar number column
    $sub_array[] = $row['autogen_cus_id']; //cus id column
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
    // $track_status_obj = [
    //     '1' => 'Acknowledgement', '2' => 'Acknowledgement', '3' => 'NOC', '4' => 'NOC'
    // ];
    // $sub_array[] = $track_status_obj[$track_status]; //Document For Column

    $doc_keeper_name = '';

    if ($track_status == '1') {
        // Status 1 → Raised from branch → document keeper is insert_login_id
        $doc_keeper = $row['insert_login_id'];
    } else if ($track_status == '2') {
        // Status 2 → Received in main branch
        if ($userid != $row['insert_login_id']) {
            // If received but handled by different user → show inserted user
            $doc_keeper = $row['insert_login_id'];
        } else {
            // If received by same user → show main branch
            $doc_keeper_name = 'Main Branch';
        }
    }

    // Fetch username only if needed
    if (empty($doc_keeper_name) && !empty($doc_keeper)) {
        $qry = $connect->query("SELECT fullname FROM user WHERE user_id = $doc_keeper");
        $doc_keeper_name = $qry->fetchColumn();
    }
    
    $sub_array[] = $doc_keeper_name;

    // else if ($track_status == '3') {

    //     //if status is 3, received in main branch
    //     $sub_array[] = 'Main Branch'; //document keeper column

    // } elseif ($track_status == '4') {

    //     $branchqry = $connect->query("SELECT bc.branch_name FROM area_line_mapping lm JOIN branch_creation bc ON lm.branch_id = bc.branch_id where FIND_IN_SET('" . $row['sub_area'] . "' , lm.sub_area_id) ");
    //     $sub_array[] = $branchqry->fetch()['branch_name'] . " Branch"; //document keeper column

    // }

    $replace_doc_action =[];
    if($row['noc_replace_status'] == '0'){ //ack - noc_replace_status => 0-YES/1-NO.
        
        $qry = $connect->query("SELECT ad.req_id, dri.replace_doc_id FROM acknowlegement_documentation ad JOIN doc_replace_ids dri ON ad.doc_id = dri.replace_doc_id WHERE dri.req_id = '$req_id' ");
        while($replace_info = $qry->fetchObject()){
            $replace_doc_action[] = "<a href='#' title='View Replace Doc' class='view-track' data-reqid='$replace_info->req_id' data-cusid='$cus_id' data-cusname='$cus_name'  data-toggle='modal' data-target='.viewDocModal'>$replace_info->replace_doc_id</a>";   
        }
    
    }

    $sub_array[] = $replace_doc_action;

    $id = $row['id']; //table id

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    $action .= "<a href='' title='View details' class='view-track' data-reqid='$req_id' data-cusid='$cus_id' data-cusname='$cus_name' data-toggle='modal' data-target='.viewDocModal'>View</a>";

    if ($track_status == '1' && $userid == $row['insert_login_id']) { //1 means submitted in issued and to be sent for receive.
        $action .= "<a href='' title='Mark Documents Sent' class='send-track' data-id='$id' data-reqid='$req_id'>Mark as Sent</a>";
    }

    if ($doc_rec_access == '0' && $track_status == '2' && $userid != $row['insert_login_id']) { //2 means send by user to receive
        //show receive track when sent from user
        $action .= "<a href='' title='Receive Documents' class='receive-track' data-id='$id' data-cusid='$cus_id' >Receive</a>";
        $action .= "<a href='' title='Return Documents' class='return-track' data-id='$id' data-reqid='$req_id' >Return</a>";
    }

    //Directly removed once received.
    // if ($track_status == '2' || $track_status == '4') {
    //     $action .= "<a href='' title='Remove Track' class='remove-track' data-id='$id' data-reqid='$req_id' >Remove Track</a>";
    // }

    $action .= "</div></div>";

    $sub_array[] = $action;

    $data[]      = $sub_array;
}

function count_all_data($connect)
{
    $query     = "SELECT id FROM document_track";
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
