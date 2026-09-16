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
    'u.fullname',
    'dt.id',
    'dt.id'
);

// Base query
// 1- inserted, 2- send by issued user, 3- received by doc_rec_access user, 4- Doc Combined (Old + New), 1- return.
$query = "SELECT dt.id, dt.req_id, dt.cus_id, dt.track_status, dt.insert_login_id, dt.update_login_id, dt.created_date, ad.doc_id, cr.autogen_cus_id, cr.customer_name, bc.branch_name, al.area_name, sal.sub_area_name, agm.group_name, alm.line_name, ad.noc_replace_status, u.fullname as insert_login_name
    FROM document_track dt
    JOIN acknowlegement_documentation ad ON dt.req_id = ad.req_id
    JOIN customer_register cr ON dt.cus_id = cr.cus_id
    JOIN area_list_creation al ON cr.area_confirm_area = al.area_id
    JOIN sub_area_list_creation sal ON cr.area_confirm_subarea = sal.sub_area_id
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = cr.area_confirm_subarea
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    LEFT OUTER JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = cr.area_confirm_subarea
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    LEFT JOIN user u ON dt.insert_login_id = u.user_id
    WHERE ( (dt.insert_login_id = $userid && dt.track_status <= 2) OR ($doc_rec_access = 0 && (dt.track_status = 2 || dt.track_status = 4 || dt.track_status = 5)) ) ";

// Apply search filter
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND ( dt.created_date LIKE '$search%' OR
                dt.cus_id LIKE '$search%' OR
                cr.autogen_cus_id LIKE '$search%' OR
                cr.customer_name LIKE '%$search%' OR
                bc.branch_name LIKE '%$search%'  OR
                al.area_name LIKE '%$search%'  OR
                sal.sub_area_name LIKE '%$search%'  OR
                agm.group_name LIKE '%$search%'  OR
                alm.line_name LIKE '%$search%' )";
}

if (isset($_POST['order'])) {
    $query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
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

$data = [];
$sno = 1;
foreach ($result as $row) {
    $id = $row['id']; //table id
    $req_id = $row['req_id'];
    $cus_id = $row['cus_id'];
    $cus_name = $row['customer_name'];
    $track_status = $row['track_status'];
    
    $doc_keeper_name = ($track_status == '1' || $userid != $row['insert_login_id']) ? $row['insert_login_name'] : 'Main Branch';

    $replace_doc_action =[];
    $replace_doc_reqid =[];
    $noc_replace_status = $row['noc_replace_status'];

    if($noc_replace_status == '0'){ //ack - noc_replace_status => 0-YES/1-NO.
        
        $qry = $connect->query("SELECT ad.req_id, dri.replace_doc_id FROM acknowlegement_documentation ad JOIN doc_replace_ids dri ON ad.doc_id = dri.replace_doc_id WHERE dri.req_id = '$req_id' ");
        while($replace_info = $qry->fetchObject()){
            $replace_doc_reqid[] = $replace_info->req_id;
            $replace_doc_action[] = "<a href='#' title='View Replace Doc' class='view-track' data-reqid='$replace_info->req_id' data-cusname='$cus_name' data-toggle='modal' data-target='.viewDocModal'>$replace_info->replace_doc_id</a>";   
        }
        $replace_doc_reqid[] = $req_id;
    }

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>
    <a href='' title='View details' class='view-track' data-reqid='$req_id' data-cusname='$cus_name' data-toggle='modal' data-target='.viewDocModal'>View</a>";

    if ($track_status == '1' && $userid == $row['insert_login_id']) { //1 means submitted in issued and to be sent for receive.
        $action .= "<a href='' title='Mark Documents Sent' class='send-track' data-id='$id'>Mark as Sent</a>";
    }

    if ($doc_rec_access == '0' && $track_status == '2' && $userid != $row['insert_login_id']) { //2 means send by user to receive
        //show receive track when sent from user
        $action .= "<a href='' title='Receive Documents' class='receive-track' data-id='$id' data-cusid='$cus_id' data-replace-status ='$noc_replace_status' data-sts='1'>Receive</a>";
    }

    if ($doc_rec_access == '0' && $track_status == '5' && $userid != $row['insert_login_id']) { //5 means received doc but need to confirm. set replace-status = 1 because if no replace means track status = 5.
        //show receive track when sent from user
        $action .= "<a href='' title='Confirm Documents' class='receive-track' data-id='$id' data-cusid='$cus_id' data-replace-status ='1' data-sts='2'>Confirm</a>";
    }

    if ($doc_rec_access == '0' && $track_status == '4' && $userid == $row['update_login_id']) { //4 means received but replace doc have to combine. after combine status = 3.
        //show receive track when sent from user
        $replace_doc_reqid = implode(',', $replace_doc_reqid);
        $action .= "<a href='' title='Combine Documents' class='combine-doc' data-reqid='$replace_doc_reqid' data-cusname='$cus_name' data-multi-reqid= '1' data-current-req-id = '$req_id' data-toggle='modal' data-target='.viewDocModal'>Combine Doc</a>";
    }

    if ($doc_rec_access == '0' && ($track_status == '5' || $track_status == '4') && $userid != $row['insert_login_id']) {
        $action .= "<a href='' title='Return Documents' class='return-track' data-id='$id'>Return</a>";
    }

    //Directly removed once received.
    // if ($track_status == '2' || $track_status == '4') {
    //     $action .= "<a href='' title='Remove Track' class='remove-track' data-id='$id' data-reqid='$req_id' >Remove Track</a>";
    // }

    $action .= "</div></div>";


    $data[] = [
        $sno++,
        date('d-m-Y', strtotime($row['created_date'])), //Date column
        $row['doc_id'], //doc id column
        $row['cus_id'], //Aadhaar number column
        $row['autogen_cus_id'], //cus id column
        $cus_name, //cus name column
        $row['branch_name'], //Branch name column
        $row['area_name'], //area name column
        $row['sub_area_name'], //sub area name column
        $row['group_name'], //group name column
        $row['line_name'], //line name column
        $doc_keeper_name, //document keeper name column
        !empty($replace_doc_action) ? implode('<br>', $replace_doc_action) : '',
        $action
    ];
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;