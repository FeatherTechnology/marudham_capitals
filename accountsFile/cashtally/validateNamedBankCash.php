<?php
$name_id    = $_POST['name'];
$amt        = $_POST['amt'];
$cash_type  = $_POST['cash_type'];

$obj        = new ValidateNamedBankCash();

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

class ValidateNamedBankCash
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
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_binvest WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_binvest WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $total_debitable = $cr - $db;
        $this->response['info'] = $total_debitable >= $amt;
    }
    function checkDebitDeposit($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_bdeposit WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_bdeposit WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $total_debitable = $cr - $db;
        $this->response['info'] = $total_debitable >= $amt;
    }
    function checkCreditEL($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_bel WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_bel WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $total_creditable = $db - $cr;
        $this->response['creditable'] = $total_creditable;
        $this->response['info'] = '';
    }
    function checkDebitEL($name_id, $amt)
    {
        $crqry = $this->connect->query("SELECT SUM(amt) FROM ct_cr_bel WHERE name_id = '$name_id' ");
        $dbqry = $this->connect->query("SELECT SUM(amt) FROM ct_db_bel WHERE name_id = '$name_id' ");
        $cr = $crqry->fetch(PDO::FETCH_NUM)[0];
        $db = $dbqry->fetch(PDO::FETCH_NUM)[0];
        $total_debitable = $cr - $db;
        $this->response['debitable'] = $total_debitable;
        $this->response['info'] = '';
    }
    //Close Connection
    function __destruct()
    {
        $this->connect = null;
    }
}
