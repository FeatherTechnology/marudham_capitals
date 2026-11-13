<?php
include "../../ajaxconfig.php";

$reqid = $_POST['req_id'] ?? '';
$response = [];

$sql = $connect->prepare("SELECT iv.user_type, iv.user_name, ac.ag_name, iv.responsible, iv.remarks, iv.declaration, alc.area_name, salc.sub_area_name, iv.req_code, iv.dor, iv.poss_type, iv.due_amt, iv.due_period FROM in_verification iv LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id LEFT JOIN area_list_creation alc ON iv.area = alc.area_id LEFT JOIN sub_area_list_creation salc ON iv.sub_area = salc.sub_area_id WHERE iv.req_id = ?");
$sql->execute([$reqid]);
$response['requestInfo'] = $sql->fetch(PDO::FETCH_ASSOC);

$sql = $connect->prepare("SELECT alc.loan_cal_id, alc.communication, alc.com_audio, alc.verification_person, alc.verification_location, alc.verify_remark, alc.loan_category, lcc.loan_category_creation_name, alc.sub_category, alc.tot_value, alc.ad_amt, alc.loan_amt, alc.profit_type, alc.due_method_calc, alc.due_type, alc.profit_method, alc.calc_method, alc.due_method_scheme, alc.profit_method_scheme, alc.day_scheme, alc.int_rate, alc.due_period, alc.doc_charge, alc.proc_fee, alc.loan_amt_cal, alc.principal_amt_cal, alc.int_amt_cal, alc.tot_amt_cal, alc.due_amt_cal, alc.doc_charge_cal, alc.proc_fee_cal, alc.net_cash_cal, alc.due_start_from, alc.maturity_month, alc.collection_method, lc.collection_info, ls.scheme_name FROM acknowlegement_loan_calculation alc JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = alc.loan_category JOIN loan_calculation lc ON alc.sub_category = lc.sub_category LEFT JOIN loan_scheme ls ON alc.scheme_name = ls.scheme_id WHERE alc.req_id = ?");
$sql->execute([$reqid]);
$response['loanCalcInfo'] = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($response);

//Close the database connection
$connect = null;
?>



