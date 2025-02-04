<?php
include('../ajaxconfig.php');
if (isset($_POST['loan_cat'])) {
    $loan_cat = $_POST['loan_cat'];
}


$records = array();
$selectIC = $connect->query("SELECT * FROM loan_category WHERE loan_category_name = '" . $loan_cat . "' and status =0 ");
if ($selectIC->rowCount() > 0) {
    $i = 0;
    while ($row = $selectIC->fetch()) {
        $sub_cat_name = $row["sub_category_name"];
        $Qry = $connect->query("SELECT * from loan_calculation where sub_category = '" . strip_tags($sub_cat_name) . "' ");
        if ($Qry->rowCount() > 0) {

            $row1 = $Qry->fetch();
            $records[$i]['sub_category_name'] = $row1["sub_category"];
            $i++;
        }
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;