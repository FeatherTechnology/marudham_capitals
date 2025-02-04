<?php
include('../ajaxconfig.php');

if(isset($_POST["loan_cat"])){
	$loan_cat  = $_POST["loan_cat"];
}
$records = array();
$selectIC = $connect->query("SELECT * FROM loan_category WHERE loan_category_name = '".$loan_cat."' and status =0 ");
if($selectIC->rowCount()>0)
{$i=0;
    while($row = $selectIC->fetch()){
        $records[$i]['sub_category_name'] = $row["sub_category_name"];

        $checkSub = $connect->query("SELECT * from loan_calculation where sub_category = '".$row["sub_category_name"]."'");
        if($checkSub->rowCount()>0){
            $records[$i]['disabled'] = 'disabled';
        }else{
            $records[$i]['disabled'] = '';
        }

        $i++;
	}

}

echo json_encode($records);

// Close the database connection
$connect = null;
?>