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

            $cus_id = $_POST['cusidupd'];
            $req_id = $_POST['req_id'];

            $sign_checklist     = $_POST['sign_checklist'];
            $cheque_checklist   = $_POST['cheque_checklist'];
            $gold_checklist     = $_POST['gold_checklist'];
            $mort_checklist     = $_POST['mort_checklist'];
            $endorse_checklist  = $_POST['endorse_checklist'];
            $doc_checklist      = $_POST['doc_checklist'];

            $noc_date = date("Y-m-d");
            $user_id  = $_SESSION['userid'];

            $check = $connect->prepare("SELECT noc_id FROM noc WHERE req_id = :req_id");
            $check->bindParam(':req_id', $req_id);
            $check->execute();

            if ($check->rowCount() > 0) {

                // NOC ALREADY EXISTS → UPDATE RECORD
                $row = $check->fetch(PDO::FETCH_ASSOC);
                $noc_id = $row['noc_id'];

                $qry = $connect->prepare("
                    UPDATE noc 
                    SET cus_id = :cus_id,
                        noc_date = :noc_date,
                        update_login_id = :user_id,
                        updated_date = NOW()
                    WHERE noc_id = :noc_id
                ");

                $qry->bindParam(':cus_id', $cus_id);
                $qry->bindParam(':noc_date', $noc_date);
                $qry->bindParam(':user_id', $user_id);
                $qry->bindParam(':noc_id', $noc_id);
                $qry->execute();

            } else {

                // INSERT NEW NOC
                $qry = $connect->prepare("
                    INSERT INTO noc 
                    (req_id, cus_id, noc_date, cus_status, insert_login_id, created_date) 
                    VALUES 
                    (:req_id, :cus_id, :noc_date, 21, :user_id, NOW())
                ");

                $qry->bindParam(':req_id', $req_id);
                $qry->bindParam(':cus_id', $cus_id);
                $qry->bindParam(':noc_date', $noc_date);
                $qry->bindParam(':user_id', $user_id);
                $qry->execute();

                $noc_id = $connect->lastInsertId();
            }
            
            // ---------------------------------------
            // INSERT CHECKLIST INTO CHILD TABLES
            // ---------------------------------------

            $this->insertChecklist($connect, "noc_sign_checklist", "sign_id", $noc_id, $sign_checklist);
            $this->insertChecklist($connect, "noc_cheque_checklist", "cheque_id", $noc_id, $cheque_checklist);
            $this->insertChecklist($connect, "noc_mort_checklist", "mort_id", $noc_id, $mort_checklist);
            $this->insertChecklist($connect, "noc_endorse_checklist", "endorse_id", $noc_id, $endorse_checklist);
            $this->insertChecklist($connect, "noc_gold_checklist", "gold_id", $noc_id, $gold_checklist);
            $this->insertChecklist($connect, "noc_doc_checklist", "doc_id", $noc_id, $doc_checklist);

            $this->response = "Success";
            return;
        } else {
            $this->response = "Invalid request method";
        }
    }


    // Function to insert multiple checklist values
    function insertChecklist($connect, $table, $col_name, $noc_id, $values)
    {

        if (empty($values)) return;

        // Convert CSV → array
        if (!is_array($values)) {
            $values = explode(",", $values);
        }

        $sql = "INSERT INTO $table (noc_id, $col_name) VALUES (:noc_id, :value)";
        $stmt = $connect->prepare($sql);

        foreach ($values as $val) {
            $val = trim($val);
            if ($val == "") continue;

            $stmt->execute([
                ':noc_id' => $noc_id,
                ':value' => $val
            ]);
        }
    }
}

$connect = null;
