<?php
session_start();
include('../ajaxconfig.php');
include('../moneyFormatIndia.php');

$req_id = $_POST['req_id'];

$qry = $connect->query("
    SELECT
    cr.cus_id, 
    cr.autogen_cus_id,
    cr.customer_name,
    req.father_name,
    fam.famname,
    alc.area_name,
    ii.loan_id,
    lcc.loan_category_creation_name AS loan_cat_name,
    loan_amt_cal AS loan_amt,
    cs.created_date as closed_date

    FROM request_creation req
    JOIN customer_register cr ON req.cus_id = cr.cus_id
    LEFT JOIN verification_family_info fam ON req.req_id = fam.req_id and fam.relationship = 'Father'
    JOIN area_list_creation alc ON cr.area_confirm_area = alc.area_id
    JOIN in_issue ii ON req.req_id = ii.req_id
    JOIN acknowlegement_loan_calculation lc ON req.req_id = lc.req_id
    JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
    JOIN closed_status cs ON req.req_id = cs.req_id

    where req.req_id = $req_id");

$row = $qry->fetch();
extract($row); // Extracts the array values into variables
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
            <p>Aadhaar Number: <?php echo $cus_id; ?></p>
            <p>Customer ID: <?php echo $autogen_cus_id; ?></p>
            <p>Customer Name:<?php echo $customer_name; ?> </p>
            <p>S/o <?php echo $famname ?? $father_name; ?>,</p>
            <p><?php echo $area_name; ?></p>
            <br>
            <p>Ref: Loan ID - <?php echo $loan_id; ?>, Loan Category - <?php echo $loan_cat_name; ?> - NOC Clearance.</p>
            <br>
            <p>Respected Sir,</p>
            <p>We are pleased to confirm that there are no outstanding dues towards the captioned loan and the loan amount (<?php echo $loan_amt; ?>) dispersed under the said loan ID: <?php echo $loan_id; ?> has been closed in our books on closed date (<?php echo date('d-m-Y',strtotime($row['closed_date'])); ?>). The agreement signed by you with this regards stands terminated. Terminated documents are enclosed with this letter.</p>
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