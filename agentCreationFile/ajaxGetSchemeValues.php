<?php 
include('../ajaxconfig.php');

if (isset($_POST['sub_cat'])) {
    $sub_cat = $_POST['sub_cat'];
}
$sub_cat_array = explode(',',$sub_cat);
// print_r($checkloanQry);die;
$detailrecords = array();
$j=0;
foreach($sub_cat_array as $sub_cat){

    $loanCatSelect = "SELECT * FROM loan_scheme WHERE (FIND_IN_SET('".strip_tags($sub_cat)."', sub_category) and sub_category !='') and status=0"; 
    $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
    if ($res->rowCount()>0)
    {$i=0;
        while($row = $res->fetchObject()){	
            $detailrecords[$j][$i]['scheme_id']      = $row->scheme_id; 
            $detailrecords[$j][$i]['scheme_name']      = $row->scheme_name; 
            $i++;
        }
        $j++;
    }
}
echo json_encode($detailrecords);


// Close the database connection
$connect = null;










































// if(sizeof($loan_cat_array) >= 1 ){
//     $j=0;
//     foreach($loan_cat_array as $loan_cat){
//         $k=0;
//         foreach($sub_cat_array as $sub_cat){
//             if($sub_cat != 0 and $sub_cat != ''){
                
//                 $checkloanQry = $connect->query("SELECT * from loan_category where sub_category_name = '".$sub_cat."' and loan_category_name =$loan_cat and status = 0");
//                 if($checkloanQry->num_rows>0){
                    
//                     print_r($loan_cat);
//                     print_r($sub_cat);
//                     $loanCatSelect = "SELECT * FROM loan_scheme WHERE sub_category = '".$sub_cat."' and status = 0 "; 
//                     $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
//                     if ($res->num_rows>0)
//                     {$i=0;print_r($loanCatSelect);
//                         while($row = $res->fetch_object()){	
//                             $detailrecords[$j][$k][$i]['scheme_id']      = $row->scheme_id; 
//                             $detailrecords[$j][$k][$i]['scheme_name']      = $row->scheme_name; 
//                             $i++;
//                         }
//                         $j++;
//                     }else{$loanDoneID = $loan_cat;}
                    
//                 }
//                 else{
//                     if($loanDoneID != $loan_cat){
//                         print_r('Loan Done ID - '.$loanDoneID);
//                         print_r(' Loan Cat ID - '.$loan_cat);
//                         $loanDoneID='';
//                         $loanCatSelect = "SELECT * FROM loan_scheme WHERE loan_category = $loan_cat and status = 0 "; 
//                         $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
//                         if ($res->num_rows>0)
//                         {$i=0;print_r($loanCatSelect);
//                             while($row = $res->fetch_object()){	
//                                 $detailrecords[$j][$k][$i]['scheme_id']      = $row->scheme_id; 
//                                 $detailrecords[$j][$k][$i]['scheme_name']      = $row->scheme_name; 
//                                 $i++;
//                             }
//                             $j++;
//                         }
//                     }
//                 }
//             }else{
//                 $loanCatSelect = "SELECT * FROM loan_scheme WHERE loan_category = $loan_cat and status = 0 "; 
//                 $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
//                 if ($res->num_rows>0)
//                 {$i=0;
//                     while($row = $res->fetch_object()){	
//                         $detailrecords[$j][$k][$i]['scheme_id']      = $row->scheme_id; 
//                         $detailrecords[$j][$k][$i]['scheme_name']      = $row->scheme_name; 
//                         $i++;
//                     }
//                     $j++;
//                 }
//             }
            
//             $k++;
//         }
        
//     }
// }
// else
// if(sizeof($loan_cat_array) > 1 and $sub_cat == ''){
//     $j=0;
//     foreach($loan_cat_array as $loan_cat){
//         $loanCatSelect = "SELECT * FROM loan_scheme WHERE loan_category = $loan_cat and status = 0 "; 
//         $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
//         if ($res->num_rows>0)
//         {$i=0;
//             while($row = $res->fetch_object()){	
//                 $detailrecords[$j][$i]['scheme_id']      = $row->scheme_id; 
//                 $detailrecords[$j][$i]['scheme_name']      = $row->scheme_name; 
//                 $i++;
//             }
//         }
//         $j++;
//     }
// }else
// if(sizeof($loan_cat_array) > 1 and $sub_cat_array[0] != 0){
//     $j=0;
//     foreach($loan_cat_array as $loan_cat){
//         $k=0;
//         foreach($sub_cat_array as $sub_cat){
//             $checkloanQry = $connect->query("SELECT * from loan_category where sub_category_name = $sub_cat and loan_category_name =$loan_cat and status = 0");
//             if($checkloanQry->num_rows>0){

//                 $loanCatSelect = "SELECT * FROM loan_scheme WHERE sub_category = $sub_cat and status = 0 "; 
//                 $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
//                 if ($res->num_rows>0)
//                 {$i=0;
//                     while($row = $res->fetch_object()){	
//                         $detailrecords[$j][$k][$i]['scheme_id']      = $row->scheme_id; 
//                         $detailrecords[$j][$k][$i]['scheme_name']      = $row->scheme_name; 
//                         $i++;
//                     }
//                 }
//             }else{
//                 $loanCatSelect = "SELECT * FROM loan_scheme WHERE loan_category = $loan_cat and status = 0 "; 
//                 $res = $connect->query($loanCatSelect) or die("Error in Get All Records");
//                 if ($res->num_rows>0)
//                 {$i=0;
//                     while($row = $res->fetch_object()){	
//                         $detailrecords[$j][$k][$i]['scheme_id']      = $row->scheme_id; 
//                         $detailrecords[$j][$k][$i]['scheme_name']      = $row->scheme_name; 
//                         $i++;
//                     }
//                 }
//             }
//             $k++;
//         }
//         $j++;
//     }
// }


?>