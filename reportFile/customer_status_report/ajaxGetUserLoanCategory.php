<?php
include '../../ajaxconfig.php';

// $type = isset($_POST['type']) ? $_POST['type'] : '';
// $user_ids = isset($_POST['user_id']) ? $_POST['user_id'] : '';
// $followup_id = isset($_POST['followup_id']) ? $_POST['followup_id'] : '';

$loan_category_arr = array();

// if ($type == '1' || $type == '3' || $type == '4' ) {
    // 🔹 Type = Line / Group → Show only active loan categories used in acknowledgement_loan_calculation
    $catQry = $connect->query("
        SELECT DISTINCT lcc.loan_category_creation_id, lcc.loan_category_creation_name
        FROM loan_category_creation lcc
        JOIN acknowlegement_loan_calculation alc 
            ON lcc.loan_category_creation_id = alc.loan_category
        WHERE lcc.status = 0
    ");
    while ($cat = $catQry->fetch()) {
        $loan_category_arr[] = array(
            "loan_category_creation_id" => $cat['loan_category_creation_id'],
            "loan_category_creation_name" => $cat['loan_category_creation_name']
        );
    }
// }

// elseif ($type == '2') {
//     // 🔹 Type = User → existing logic
//     if (!is_array($user_ids)) {
//         $user_ids = explode(',', $user_ids);
//     }

//     $user_ids = array_unique(array_map('trim', $user_ids));
//     $user_ids = array_filter($user_ids, 'is_numeric'); // only numeric
//     $user_ids_str = implode(',', $user_ids);

//     if (!empty($user_ids_str)) {
//         $user_query = $connect->query("SELECT loan_cat FROM user WHERE user_id IN ($user_ids_str) AND status = 0");
//         $loan_cat_ids = [];
//         while ($user_data = $user_query->fetch()) {
//             if (!empty($user_data['loan_cat'])) {
//                 $loan_cat_ids = array_merge($loan_cat_ids, explode(',', $user_data['loan_cat']));
//             }
//         }

//         $loan_cat_ids = array_unique($loan_cat_ids);

//         foreach ($loan_cat_ids as $cat_id) {
//             $checkQry = $connect->query("
//                 SELECT COUNT(*) AS cnt 
//                 FROM in_issue ii
//                 JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id
//                 WHERE alc.loan_category = '$cat_id'
//             ");
//             $checkRow = $checkQry->fetch();

//             if ($checkRow['cnt'] > 0) {
//                 $catQry = $connect->query("
//                     SELECT loan_category_creation_id, loan_category_creation_name 
//                     FROM loan_category_creation 
//                     WHERE loan_category_creation_id = '$cat_id' AND status = 0
//                 ");
//                 if ($cat = $catQry->fetch()) {
//                     $loan_category_arr[] = array(
//                         "loan_category_creation_id" => $cat['loan_category_creation_id'],
//                         "loan_category_creation_name" => $cat['loan_category_creation_name']
//                     );
//                 }
//             }
//         }
//     }
// }

// elseif ($type == '4') {
//     // 🔹 Type = Due Followup → fetch from area_duefollowup_mapping
//     if (!empty($followup_id)) {
//         if (!is_array($followup_id)) {
//             $followup_id = explode(',', $followup_id);
//         }

//         $followup_id = array_unique(array_filter($followup_id, 'is_numeric')); 
//         $followup_ids_str = implode(',', $followup_id);

//         if (!empty($followup_ids_str)) {
//             $mapQry = $connect->query("SELECT loan_category_id 
//                 FROM area_duefollowup_mapping 
//                 WHERE map_id IN ($followup_ids_str) AND status = 0");

//             $loan_cat_ids = [];

//             while ($map = $mapQry->fetch()) {
//                 if (!empty($map['loan_category_id'])) {
//                     $loan_cat_ids = array_merge($loan_cat_ids, explode(',', $map['loan_category_id']));
//                 }
//             }

//             $loan_cat_ids = array_unique(array_filter($loan_cat_ids, 'is_numeric'));

//             if (!empty($loan_cat_ids)) {
//                 $ids_str = implode(',', $loan_cat_ids);

//                 $catQry = $connect->query("SELECT loan_category_creation_id, loan_category_creation_name 
//                     FROM loan_category_creation 
//                     WHERE loan_category_creation_id IN ($ids_str) AND status = 0");

//                 while ($cat = $catQry->fetch()) {
//                     $loan_category_arr[] = array(
//                         "loan_category_creation_id" => $cat['loan_category_creation_id'],
//                         "loan_category_creation_name" => $cat['loan_category_creation_name']
//                     );
//                 }
//             }
//         }
//     }
// }

echo json_encode($loan_category_arr);
$connect = null;
?>
