<?php
include "../../../ajaxconfig.php";

$qry = $connect->query("SELECT MAX(vou_id) AS vou_id FROM ct_db_hexpense WHERE 1");
$info = $qry->fetch();
$voucher_id = (!$info['vou_id']) ? 101 : intval($info['vou_id']) + 1;

echo json_encode($voucher_id);
?>