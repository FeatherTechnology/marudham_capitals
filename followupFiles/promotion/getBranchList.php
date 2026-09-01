<?php

session_start();

include('../../ajaxconfig.php');
$userid = $_SESSION['userid'];
$branchList = array();

$userQry = $connect->query("SELECT branch_id FROM `user`WHERE user_id = '$userid'");

$userRow = $userQry->fetch();

if ($userRow && !empty($userRow['branch_id'])) {

    $branchIds = explode(',', $userRow['branch_id']);
    $branchIds = array_filter(array_map('trim', $branchIds));

    if (!empty($branchIds)) {

        $placeholders = implode(',', array_fill(0, count($branchIds), '?'));

        $branchQry = $connect->prepare(" SELECT branch_id, branch_name FROM `branch_creation` WHERE branch_id IN ($placeholders) ORDER BY branch_id ");

        $branchQry->execute($branchIds);

        while ($row = $branchQry->fetch(PDO::FETCH_ASSOC)) {
            $branchList[] = array(
                "branch_id"   => $row['branch_id'],
                "branch_name" => $row['branch_name']
            );
        }
    }
}
echo json_encode($branchList);
$connect = null;
?>