<?php
session_start();
include('../ajaxconfig.php');

$obj = new updateNocTable($connect);

if ($obj->response == "Success" ) {
    $response = "Success";
} else {
    $response = "Error";
}

echo $response;

class updateNocTable{
    public $response;
    function __construct($connect){
        
        // Check if the request is made using POST method
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the data sent via AJAX
            $req_id = $_POST['req_id'];
            $cus_id = $_POST['cusidupd'];
            $sign_checklist = $_POST['sign_checklist'];
            $cheque_checklist = $_POST['cheque_checklist'];
            $gold_checklist = $_POST['gold_checklist'];
            $mort_checklist = $_POST['mort_checklist'];
            $endorse_checklist = $_POST['endorse_checklist'];
            $doc_checklist = $_POST['doc_checklist'];
            $noc_date = $_POST['noc_date'];
            $noc_member = $_POST['noc_member'];
            $mem_name = $_POST['mem_name'];
            
            // Sanitize the input data to prevent SQL injection
            $cus_id = $connect->real_escape_string($cus_id);
            $sign_checklist = $connect->real_escape_string($sign_checklist);
            $cheque_checklist = $connect->real_escape_string($cheque_checklist);
            $gold_checklist = $connect->real_escape_string($gold_checklist);
            $mort_checklist = $connect->real_escape_string($mort_checklist);
            $endorse_checklist = $connect->real_escape_string($endorse_checklist);
            $doc_checklist = $connect->real_escape_string($doc_checklist);
            $noc_date = $connect->real_escape_string($noc_date);
            $noc_member = $connect->real_escape_string($noc_member);
            $mem_name = $connect->real_escape_string($mem_name);
            $user_id = $_SESSION['userid'];
            
            // Perform the query
            $qry = $connect->query("INSERT INTO `noc`(`req_id`,`cus_id`, `sign_checklist`, `cheque_checklist`, `gold_checklist`, `mort_checklist`, `endorse_checklist`, `doc_checklist`, `noc_date`, `noc_member`, `mem_name`, `cus_status`, `insert_login_id`, `created_date`) VALUES('$req_id','$cus_id','$sign_checklist','$cheque_checklist','$gold_checklist','$mort_checklist','$endorse_checklist','$doc_checklist','$noc_date','$noc_member','$mem_name','21','$user_id',now()) ");
            
            // Check if the query was successful
            if ($qry) {
                // Query executed successfully
                $response =  "Success";
            } else {
                // Query execution failed
                $response =  "Error: " . $connect->error;
            }
        } else {
            // If the request method is not POST, show an error message
            $response =  "Invalid request method.";
        }

        $this->response = $response;
    }
}

// Close the database connection
$connect = null;

