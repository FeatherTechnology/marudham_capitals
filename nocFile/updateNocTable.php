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
            $user_id = $_SESSION['userid'];
            
            // Perform the query
            $qry = $connect->prepare("INSERT INTO `noc`(`req_id`,`cus_id`, `sign_checklist`, `cheque_checklist`, `gold_checklist`, `mort_checklist`, `endorse_checklist`, `doc_checklist`, `noc_date`, `noc_member`, `mem_name`, `cus_status`, `insert_login_id`, `created_date`) VALUES(:req_id ,:cus_id ,:sign_checklist ,:cheque_checklist ,:gold_checklist ,:mort_checklist ,:endorse_checklist ,:doc_checklist ,:noc_date ,:noc_member ,:mem_name , 21 ,:user_id ,now() ) ");

            // Bind the parameters
            $qry->bindParam(':req_id', $req_id, PDO::PARAM_STR);
            $qry->bindParam(':cus_id', $cus_id, PDO::PARAM_STR);
            $qry->bindParam(':sign_checklist', $sign_checklist, PDO::PARAM_STR);
            $qry->bindParam(':cheque_checklist', $cheque_checklist, PDO::PARAM_STR);
            $qry->bindParam(':gold_checklist', $gold_checklist, PDO::PARAM_STR);
            $qry->bindParam(':mort_checklist', $mort_checklist, PDO::PARAM_STR);
            $qry->bindParam(':endorse_checklist', $endorse_checklist, PDO::PARAM_STR);
            $qry->bindParam(':doc_checklist', $doc_checklist, PDO::PARAM_STR);
            $qry->bindParam(':noc_date', $noc_date, PDO::PARAM_STR);
            $qry->bindParam(':noc_member', $noc_member, PDO::PARAM_STR);
            $qry->bindParam(':mem_name', $mem_name, PDO::PARAM_STR);
            $qry->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            // Execute the statement           
            // Check if the query was successful
            if ($qry->execute()) {
                // Query executed successfully
                $response =  "Success";
            } else {
                // Query execution failed, get error info
                $errorInfo = $qry->errorInfo();
                $response = "Error: " . $errorInfo[2]; // Get the error message from PDO
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

