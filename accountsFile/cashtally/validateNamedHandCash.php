<?php
$name_id = $_POST['name'];
$amt = $_POST['amt'];
$cash_type = $_POST['cash_type'];

$obj = new ValidateNamedHandCash();
if ($cash_type == 'inv') {
    $obj->checkDebitInvestment($name_id, $amt);
} else if ($cash_type == 'dep') {
    $obj->checkDebitDeposit($name_id, $amt);
} else if ($cash_type == 'crel') {
    $obj->checkCreditEL($name_id, $amt);
} else if ($cash_type == 'dbel') {
    $obj->checkDebitEL($name_id, $amt);
}

echo json_encode($obj->response);

class ValidateNamedHandCash
{
    private $connect;
    public $response;
    function __construct()
    {
        include '../../ajaxconfig.php';
        $this->connect = $connect;
    }
    function checkDebitInvestment($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_hinvest WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_hinvest WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $total_debitable = $cr - $db;
        $this->response['info'] = $total_debitable >= $amt;
    }
    function checkDebitDeposit($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_hdeposit WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_hdeposit WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $total_debitable = $cr - $db;
        $this->response['info'] = $total_debitable >= $amt;
    }
    function checkCreditEL($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_hel WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_hel WHERE name_id = '$name_id' ");
        $crBqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_bel WHERE name_id = '$name_id' ");
        $dbBqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_bel WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $bcr = $crBqry->fetch(PDO::FETCH_NUM)[0];
        $bdb = $dbBqry->fetch(PDO::FETCH_NUM)[0];
        $total_creditable = ($bdb + $db) - ($cr +  $bcr);
        $this->response['creditable'] = $total_creditable;
        $this->response['info'] = '';
    }
    function checkDebitEL($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_hel WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_hel WHERE name_id = '$name_id' ");
        $crBqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_bel WHERE name_id = '$name_id' ");
        $dbBqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_bel WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $bcr = $crBqry->fetch(PDO::FETCH_NUM)[0];
        $bdb = $dbBqry->fetch(PDO::FETCH_NUM)[0];
        $total_debitable = ($cr +  $bcr) - ($bdb + $db);
        $this->response['debitable'] = $total_debitable;
        $this->response['info'] = '';
    }
    //Close Connection
    function __destruct()
    {
        $this->connect = null;
    }
}
