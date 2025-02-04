<?php 
// include('../ajaxconfig.php');


// $loanCatSelect = "SELECT * FROM loan_category GROUP BY loan_category_name"; 
// $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
// $detailrecords = array();
// if ($res->num_rows>0)
// {$i=0;
// while($row = $res->fetch_object()){	
//     $detailrecords[$i]['loan_category_id']      = $row->loan_category_id; 
//     $detailrecords[$i]['loan_category_name_id']    = $row->loan_category_name;
//     $detailrecords[$i]['sub_category_name']    = $row->sub_category_name; 

//     $Qry = "SELECT * FROM loan_category_creation WHERE loan_category_creation_id= '".$detailrecords[$i]['loan_category_name_id']."' and status = 0 "; 
//     $res1 = $connect->query($Qry) or die("Error in Get All Records");
//     $row1 = $res1->fetch_object();
//     $detailrecords[$i]['loan_category_name'] = $row1->loan_category_creation_name;
    
//     $i++;
// }
// }



// echo json_encode($detailrecords);
// ?>


<?php 
include('../ajaxconfig.php');

$loan_category_arr = array();

$Qry = $connect->query("SELECT * from loan_calculation where status=0 Group By loan_category");
$i=0;
while($row = $Qry->fetch()){
    $loan_category[$i] = $row['loan_category'];
    $i++;
}
// print_r($loan_category);
foreach($loan_category as $cat ){
    
    $result=$connect->query("SELECT * FROM loan_category_creation where loan_category_creation_id = '".$cat."' and status=0");
    while( $row = $result->fetch()){
        $loan_category_creation_id = $row['loan_category_creation_id'];
        $loan_category_creation_name = $row['loan_category_creation_name'];
        $loan_category_arr[] = array("loan_category_name_id" => $loan_category_creation_id, "loan_category_name" => $loan_category_creation_name);
    }
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
?>