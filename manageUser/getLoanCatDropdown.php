<?php 
//Also using in Due followup mapping screen.
include('../ajaxconfig.php');


$records = array();

//it will have unique entries which will placed in loan category table and takes name in loan category creation table// by grouping by loan cat name it will give unique names
    $result=$connect->query("SELECT lcc.loan_category_creation_id, lcc.loan_category_creation_name
    FROM loan_category_creation lcc
    INNER JOIN loan_category lc ON lc.loan_category_name = lcc.loan_category_creation_id
    WHERE lc.status = 0
    GROUP BY lc.loan_category_name");

    while( $row = $result->fetch()){
        $loan_cat_id = $row['loan_category_creation_id'];
        $loan_cat_name = $row['loan_category_creation_name'];

        $records[] = array("loan_cat_id" => $loan_cat_id, "loan_cat_name" => $loan_cat_name);
    }

echo json_encode($records);

// Close the database connection
$connect = null;
?>