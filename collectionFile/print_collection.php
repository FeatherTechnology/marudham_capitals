<?php
@session_start();
include '../ajaxconfig.php';

if (isset($_POST["coll_id"])) {
    $coll_id = $_POST["coll_id"];
}

$qry = $connect->query("SELECT * FROM `collection` WHERE coll_code='" . strip_tags($coll_id) . "'");
$row = $qry->fetch();

extract($row); // Extracts the array values into variables

$sql = $connect->query("SELECT alm.line_name, alc.area_name FROM `acknowlegement_customer_profile` cp JOIN area_line_mapping alm ON FIND_IN_SET(cp.area_confirm_subarea,alm.sub_area_id) JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id WHERE cp.req_id='" . strip_tags($req_id) . "'");
$rowSql = $sql->fetch();
$line_name = $rowSql['line_name'];
$area_name = $rowSql['area_name'];

$sql = $connect->query("SELECT alc.due_type, lcc.loan_category_creation_name, ii.loan_id FROM `acknowlegement_loan_calculation` alc LEFT JOIN loan_category_creation lcc ON alc.loan_category = lcc.loan_category_creation_id LEFT JOIN in_issue ii ON alc.req_id = ii.req_id WHERE alc.req_id='" . strip_tags($req_id) . "'");
$rowSql = $sql->fetch();
$loan_category = $rowSql['loan_category_creation_name'];
$loan_id = $rowSql['loan_id'];


$due_amt_track = intVal($due_amt_track != '' ? $due_amt_track : 0);
$penalty_track = intVal($penalty_track != '' ? $penalty_track : 0);
$coll_charge_track = intVal($coll_charge_track != '' ? $coll_charge_track : 0);
$net_received = $due_amt_track + $penalty_track + $coll_charge_track;
// $due_balance = ($due_amt - $due_amt_track) < 0 ? 0 : $due_amt - $due_amt_track;
$loan_balance = getBalance($connect, $req_id, $coll_date);

$user_id = $row['insert_login_id'];
$qry = $connect->query("SELECT fullname from `user` where `user_id` = $user_id ");
$user_name = $qry->fetch()['fullname'];
$coll_modes = ['1' => 'Cash', '2' => 'Cheque', '3' => 'ECS', '4' => 'IMPS/NEFT/RTGS', '5' => 'UPI Transaction' ]
?>
<style>
    @media print {
        * {
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
        }
        @page {
            margin: 0; /* Remove default print margin */
        }
        body {
            margin: 0;
            padding: 0;
        }
        #dettable {
            margin: 0;
            padding: 0;
            width: 58mm; /* Width of thermal printer roll */
            font-size: 8px;
            line-height: 1.2;
            text-align: left;
        }
        .overlap-group {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .captions, .data {
            width: 50%;
            word-wrap: break-word;
            text-align: left;
        }
        .mar-logo {
            width: 100px;
            margin: 0 auto; /* Center align logo */
            display: block;
        }
    }
</style>


<div class="frame" id="dettable" style="background-color: #ffffff; font-size: 8px; display: flex;flex-direction: column; align-items: flex-start;">

    <div style="display: flex; justify-content: center;padding-bottom:10px;"><img class="mar-logo" alt="Marudham Capitals" src="img/logo.png" style="width: 224px; height: auto;" /></div>
    <div class="overlap-group" style="display: flex; justify-content: center; gap: 10px;">

        <div class="captions" style="display: flex; flex-direction: column; align-items: flex-end;">
            <b>
                <div>Receipt No :</div>
            </b>
            <div>Date :</div>
            <div>Time :</div>
            <div>Line :</div>
            <div>Area :</div>
            <div>Customer ID :</div>
            <b>
                <div>Customer Name :</div>
            </b>
            <div>Loan Category :</div>
            <div>Loan ID :</div>
            <div>Due Receipt :</div>
            <div>Penalty :</div>
            <div>Fine :</div><br>
            <b>
                <div>Net Received :</div>
            </b><br>
            <!-- <div>Due Balance :</div> -->
            <div>Loan Balance :</div>
            <div>Collection Mode :</div>
            <div>User Name :</div>
        </div>
        <div class="data" style="display: flex; flex-direction: column; align-items: flex-start;">
            <b>
                <div><?php echo $coll_code; ?></div>
            </b>
            <div><?php echo date('d-m-Y', strtotime($coll_date)); ?></div>
            <div><?php echo date('H:s A', strtotime($coll_date)); ?></div>
            <div><?php echo $line_name; ?></div>
            <div><?php echo $area_name; ?></div>
            <div><?php echo $cus_id; ?></div>
            <b>
                <div><?php echo $cus_name; ?></div>
            </b>
            <div><?php echo $loan_category; ?></div>
            <div><?php echo $loan_id; ?></div>
            <div><?php echo moneyFormatIndia($due_amt_track); ?></div>
            <div><?php echo moneyFormatIndia($penalty_track); ?></div>
            <div><?php echo moneyFormatIndia($coll_charge_track); ?></div><br>
            <b>
                <div><?php echo moneyFormatIndia($net_received); ?></div>
            </b><br>
            <!-- <div><?php #echo moneyFormatIndia($due_balance); ?></div> -->
            <div><?php echo moneyFormatIndia($loan_balance); ?></div>
            <div><?php echo $coll_modes[$coll_mode]; ?></div>
            <div><?php echo $user_name; ?></div>
        </div>
    </div>
</div>



<button type="button" name="printpurchase" onclick="poprint()" id="printpurchase" class="btn btn-primary">Print</button>

<script type="text/javascript">
    function poprint() {
        var Bill = document.getElementById("dettable").innerHTML;
        var printWindow = window.open('', '_blank', 'height=1000;weight=1000;');
        printWindow.document.write(`<html><head></head><body>${Bill}</body></html>`);
        printWindow.document.close();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 1500);
    }
    document.getElementById("printpurchase").click();
</script>

<?php
function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

function getBalance($connect, $req_id, $coll_date)
{
    $result = $connect->query("SELECT * FROM `acknowlegement_loan_calculation` WHERE req_id = $req_id ");
    if ($result->rowCount() > 0) {
        $row = $result->fetch();
        $loan_arr = $row;

        if ($loan_arr['tot_amt_cal'] == '' || $loan_arr['tot_amt_cal'] == null) {
            //(For monthly interest total amount will not be there, so take principals)
            $response['total_amt'] = intVal($loan_arr['principal_amt_cal']);
            $response['loan_type'] = 'interest';
            $loan_arr['loan_type'] = 'interest';
        } else {
            $response['total_amt'] = intVal($loan_arr['tot_amt_cal']);
            $response['loan_type'] = 'emi';
            $loan_arr['loan_type'] = 'emi';
        }
    }
    $coll_arr = array();
    $result = $connect->query("SELECT * FROM `collection` WHERE req_id ='" . $req_id . "' and date(coll_date) <= date('" . $coll_date . "') ");
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {
            $coll_arr[] = $row;
        }
        $total_paid = 0;
        $total_paid_princ = 0;
        $total_paid_int = 0;
        $pre_closure = 0;
        foreach ($coll_arr as $tot) {
            $total_paid += intVal($tot['due_amt_track']); //only calculate due amount not total paid value, because it will have penalty and coll charge also
            $total_paid_princ += intVal($tot['princ_amt_track']);
            $total_paid_int += intVal($tot['int_amt_track']);
            $pre_closure += intVal($tot['pre_close_waiver']); //get pre closure value to subract to get balance amount
        }
        //total paid amount will be all records again request id should be summed
        $response['total_paid'] = ($loan_arr['loan_type'] == 'emi') ? $total_paid : $total_paid_princ;
        $response['total_paid_int'] = $total_paid_int;


        $response['balance'] = $response['total_amt'] - $response['total_paid'] - $pre_closure;
    } else {
        $response['balance'] = $response['total_amt'];
    }


    return $response['balance'];
}

// Close the database connection
$connect = null;
?>