<?php
session_start();
include('../ajaxconfig.php');

$obj = new updateNocTable($connect);

echo $obj->response;

class updateNocTable
{
    public $response;

    function __construct($connect)
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $req_id   = $_POST['req_id'];
            $cus_id   = $_POST['cusidupd'];
            $noc_member = $_POST['noc_member'];
            $mem_name   = $_POST['mem_name'];

            $noc_handover_date = date("Y-m-d");
            $user_id  = $_SESSION['userid'];

            // --- UPDATE QUERY FIXED ---
            $qry = $connect->prepare("
                UPDATE noc 
                SET 
                    cus_id = :cus_id,
                    noc_handover_date = :noc_handover_date,
                    noc_member = :noc_member,
                    mem_name = :mem_name,
                    update_login_id = :user_id,
                    updated_date = NOW()
                WHERE req_id = :req_id
            ");

            $qry->bindParam(':cus_id', $cus_id);
            $qry->bindParam(':noc_handover_date', $noc_handover_date);
            $qry->bindParam(':noc_member', $noc_member);
            $qry->bindParam(':mem_name', $mem_name);
            $qry->bindParam(':user_id', $user_id);
            $qry->bindParam(':req_id', $req_id);

            $qry->execute();

            $this->response = "Success";
            return;

        } else {
            $this->response = "Invalid request method";
        }
    }
}

$connect = null;
?>
