<?php
include "../ajaxconfig.php";

if(isset($_POST['cus_id'])){
echo    $cus_id = $_POST['cus_id'];
}

    // $qry = $connect->query("SELECT customer_name, mobile1 from customer_register where req_ref_id = '$req_id' ");
    // $row = $qry->fetch();
    // $customer_name = $row['customer_name'];
    // $cus_mobile1 = $row['mobile1'];

    // $message = "";
    // $templateid	= ''; //FROM DLT PORTAL.
    // // Account details
    // $apiKey = '';
    // // Message details
    // $sender = '';
    // // Prepare data for POST request
    // $data = 'access_token='.$apiKey.'&to='.$cus_mobile1.'&message='.$message.'&service=T&sender='.$sender.'&template_id='.$templateid;
    // // Send the GET request with cURL
    // $url = 'https://sms.messagewall.in/api/v2/sms/send?'.$data; 
    // $response = file_get_contents($url);  
    // // Process your response here
    // return $response; 
    
// Close the database connection
// $connect = null;
?>