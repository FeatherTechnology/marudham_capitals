<?php
include "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['userid'];

$where = "";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "AND (date(c.created_date) >= '" . $from_date . "') AND (date(c.created_date) <= '" . $to_date . "') ";
}

$bankqry = $connect->query("SELECT `bank_details` FROM `user` WHERE `user_id`= $user_id");
$bank_id = $bankqry->fetch()['bank_details'];

//get agent user id to get data from collection
$ag_userid_qry = $connect->query("SELECT `user_id` from user where FIND_IN_SET( `ag_id`, (SELECT `agentforstaff` from user where `user_id` = '$user_id')) ");
$ids = array();
while ($row = $ag_userid_qry->fetch()) {
    $ids[] = $row['user_id'];
}
$ag_user_id = implode(',', $ids);

$column = array(
    'tdate',
    'ag_name',
    'created_date',
    'total_paid_track'
);

$query = "SELECT * FROM (
    SELECT 
        ac.ag_name, 
        u.ag_id AS ag_id, 
        DATE(c.created_date) as tdate, 
        c.total_paid_track as coll_amt, 
        '' AS netcash, 
        '' AS Credit, 
        '' AS Debit
    FROM 
        collection c 
    JOIN 
        user u ON c.insert_login_id = u.user_id
    JOIN 
        agent_creation ac ON u.ag_id = ac.ag_id
    WHERE 
        c.total_paid_track != '' 
        AND FIND_IN_SET(c.insert_login_id,'$ag_user_id') $where

    UNION ALL

    SELECT 
        ac.ag_name, 
        c.agent_id AS ag_id, 
        DATE(c.created_date) as tdate, 
        '' AS coll_amt, 
        c.cash + c.cheque_value + c.transaction_value AS netcash, 
        '' AS Credit, 
        '' AS Debit 
    FROM 
        loan_issue c 
    JOIN 
        user u ON u.user_id = '$user_id'
    JOIN 
        agent_creation ac ON c.agent_id = ac.ag_id
    WHERE 
        FIND_IN_SET(c.agent_id, u.agentforstaff) $where

    UNION ALL 

    SELECT 
        ac.ag_name, 
        c.ag_id, 
        c.created_date AS tdate, 
        '' AS coll_amt, 
        '' AS netcash, 
        '' AS Credit, 
        amt AS Debit 
    FROM 
        ct_db_hag c
    JOIN 
        agent_creation ac ON c.ag_id = ac.ag_id
    WHERE 
        c.insert_login_id = '$user_id' $where

    UNION ALL 
    
    SELECT 
        ac.ag_name, 
        c.ag_id, 
        c.created_date AS tdate, 
        '' AS coll_amt, 
        '' AS netcash, 
        '' AS Credit, 
        amt AS Debit 
    FROM 
        ct_db_bag c
    JOIN 
        agent_creation ac ON c.ag_id = ac.ag_id
    WHERE 
        FIND_IN_SET(bank_id, '$bank_id') $where

    UNION ALL 
    
    SELECT 
        ac.ag_name, 
        c.ag_id, 
        c.created_date AS tdate, 
        '' AS coll_amt, 
        '' AS netcash, 
        amt AS Credit, 
        '' AS Debit 
    FROM 
        ct_cr_hag c
    JOIN 
        agent_creation ac ON c.ag_id = ac.ag_id
    WHERE 
        c.insert_login_id = '$user_id' $where

    UNION ALL 
    
    SELECT 
        ac.ag_name, 
        c.ag_id, 
        c.created_date AS tdate, 
        '' AS coll_amt, 
        '' AS netcash, 
        amt AS Credit, 
        '' AS Debit 
    FROM 
        ct_cr_bag c
    JOIN 
        agent_creation ac ON c.ag_id = ac.ag_id
    WHERE 
        FIND_IN_SET(bank_id, '$bank_id') $where
) AS temp";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= " WHERE (ag_name LIKE '%" . $_POST['search'] . "%' OR 
        tdate LIKE '%" . $_POST['search'] . "%')";
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= " ORDER BY tdate DESC"; // Default ordering
}

$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();

if ($_POST['length'] != -1) {
    $statement = $connect->prepare($query . $query1);
    $statement->execute();
}
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['ag_name'];
    $sub_array[] = date('d-m-Y', strtotime($row['tdate']));
    $sub_array[] = ($row['coll_amt'] !='') ? moneyFormatIndia($row['coll_amt']) : 0;
    $sub_array[] = ($row['netcash'] !='') ? moneyFormatIndia($row['netcash']) : 0;
    $sub_array[] = ($row['Credit'] !='') ? moneyFormatIndia($row['Credit']) : 0;
    $sub_array[] = ($row['Debit'] !='') ? moneyFormatIndia($row['Debit']) : 0;

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT COUNT(*) AS ag_count FROM (
            SELECT 1 FROM collection
            UNION ALL
            SELECT 1 FROM loan_issue
            UNION ALL
            SELECT 1 FROM ct_db_hag
            UNION ALL
            SELECT 1 FROM ct_db_bag
            UNION ALL
            SELECT 1 FROM ct_cr_hag
            UNION ALL
            SELECT 1 FROM ct_cr_bag
        ) AS temp");
    $statement = $query->fetch();
    return $statement['ag_count'];
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
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

// Close the database connection
$connect = null;
?>