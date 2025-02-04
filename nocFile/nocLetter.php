<?php
session_start();
include('../ajaxconfig.php');

$req_id = $_POST['req_id'];
$cus_id = $_POST['cus_id'];


$qry = $connect->query("
    SELECT 
    cp.cus_name,
    req.father_name,
    fam.famname,
    alc.area_name,
    ii.loan_id,
    (select lcc.loan_category_creation_name from acknowlegement_loan_calculation lc JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id where lc.req_id = $req_id) as loan_cat_name,
    (select loan_amt_cal from acknowlegement_loan_calculation where req_id = $req_id) as loan_amt,
    cs.created_date as closed_date

    from acknowlegement_customer_profile cp 
    JOIN request_creation req ON cp.req_id = req.req_id
    LEFT JOIN verification_family_info fam ON cp.req_id = fam.req_id and fam.relationship = 'Father'
    JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
    JOIN in_issue ii ON cp.req_id = ii.req_id
    JOIN closed_status cs ON cp.req_id = cs.req_id
    
    where cp.req_id = $req_id");

$row = $qry->fetch();
$cus_name = $row['cus_name'];
$father_name = $row['famname'] ?? $row['father_name'];
$area = $row['area_name'];
$loan_id = $row['loan_id'];
$loan_cat = $row['loan_cat_name'];
$loan_amt = moneyFormatIndia($row['loan_amt']);
$closed_date = date('d-m-Y',strtotime($row['closed_date']));

function moneyFormatIndia($num) {
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
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body >
    <div class="container" id="noc_letter_div" style="font-family: Arial, sans-serif;  max-width: 600px;">
        <div class="header" style="text-align: center; margin-bottom: 20px;">
            <h2>No Objection Certificate</h2>
        </div>
        <div class="date" style="text-align: right; margin-bottom: 20px;">
            <p>Date: <?php echo date('d/m/Y'); ?></p>
        </div>
        <div class="body" style="margin-bottom: 20px;">
            <p>To</p>
            <p>Customer ID: <?php echo $cus_id; ?></p>
            <p>Customer Name:<?php echo $cus_name; ?> </p>
            <p>S/o <?php echo $father_name; ?>,</p>
            <p><?php echo $area; ?></p>
            <br>
            <p>Ref: Loan ID - <?php echo $loan_id; ?>, Loan Category - <?php echo $loan_cat; ?> - NOC Clearance.</p>
            <br>
            <p>Respected Sir,</p>
            <p>We are pleased to confirm that there are no outstanding dues towards the captioned loan and the loan amount (<?php echo $loan_amt; ?>) dispersed under the said loan ID: <?php echo $loan_id; ?> has been closed in our books on closed date (<?php echo $closed_date; ?>). The agreement signed by you with this regards stands terminated. Terminated documents are enclosed with this letter.</p>
            <br>
            <p>Thank you once again for selecting MARUDHAM CAPITALS as your preferred partner in helping you accomplish your financial goals.</p>
        </div>
        <div class="footer" style="text-align: right;">
            <p>Yours sincerely,</p>
            <br>
            <br>
            <p>Manager</p>
        </div>
        <button type="button" name="printletter" onclick="poprint()" id="printletter" class="btn btn-primary" style="display:none">Print</button>
    </div>

</body>

</html>


<script type="text/javascript">
    function poprint() {
        var printWindow = window.open('', '', 'height=1000,width=1000');
        printWindow.document.write('<html><head><title>NOC Letter</title></head><body style="margin:150px 20px;max-width: 100%;">');
        printWindow.document.write(document.getElementById("noc_letter_div").innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }
    document.getElementById("printletter").click();
</script>

<?php
// Close the database connection
$connect = null;
?>