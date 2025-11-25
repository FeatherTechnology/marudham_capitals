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
            $userid  = $_SESSION['userid'];

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
            $qry->bindParam(':user_id', $userid);
            $qry->bindParam(':req_id', $req_id);

            $qry->execute();

            $selectIC = $connect->query("UPDATE request_creation set cus_status = 24, update_login_id = $userid, `updated_date`= now() WHERE  cus_id = '" . $cus_id . "' and req_id = '" . $req_id . "' ") or die('Error on Request Table');
            $selectIC = $connect->query("UPDATE customer_register set cus_status = 24 WHERE cus_id = '" . $cus_id . "' and req_ref_id = '" . $req_id . "' ") or die('Error on Customer Table');
            $selectIC = $connect->query("UPDATE in_verification set cus_status = 24, update_login_id = $userid WHERE cus_id = '" . $cus_id . "' and req_id = '" . $req_id . "' ") or die('Error on inVerification Table');
            $selectIC = $connect->query("UPDATE `in_approval` SET `cus_status`= 24,`update_login_id`= $userid WHERE  cus_id = '" . $cus_id . "' and req_id = '" . $req_id . "' ") or die('Error on in_approval Table');
            $selectIC = $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 24,`update_login_id`= $userid WHERE  cus_id = '" . $cus_id . "' and req_id = '" . $req_id . "' and updated_date=now() ") or die('Error on in_acknowledgement Table');
            $selectIC = $connect->query("UPDATE `in_issue` SET `cus_status`= 24,`update_login_id` = $userid where cus_id = '" . $cus_id . "' and req_id = '" . $req_id . "' ") or die('Error on in_issue Table');
            $selectIC = $connect->query("UPDATE `closed_status` SET `cus_sts` = 24,`update_login_id`=$userid,`updated_date`= now() WHERE req_id = '" . $req_id . "' && `cus_id`='" . $cus_id . "' ") or die('Error on closed_status Table');
            $selectIC = $connect->query("UPDATE `noc` SET `cus_status` = 24,`update_login_id`=$userid,`updated_date`= now() WHERE req_id = '" . $req_id . "' && `cus_id`='" . $cus_id . "' ") or die('Error on NOC Table');

            $this->response = "Success";
            return;
        } else {
            $this->response = "Invalid request method";
        }
    }
}

$connect = null;
