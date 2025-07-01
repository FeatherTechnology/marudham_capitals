<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bankqry = $connect->query("SELECT `bank_details` FROM `user` WHERE `user_id`= $user_id");
$bank_id = $bankqry->fetch()['bank_details'];

$sheet_type = $_POST['sheet_type'];

if (isset($_POST['exp_cat_type'])) {
    $exp_cat_type = $_POST['exp_cat_type'];
} else {
    $exp_cat_type = '';
}

if (isset($_POST['IDEtype'])) {
    $IDEtype = $_POST['IDEtype'];
} else {
    $IDEtype = '';
} // investment or Deposit or EL
if (isset($_POST['IDEview_type'])) {
    $IDEview_type = $_POST['IDEview_type'];
} else {
    $IDEview_type = '';
} // overall or individual
if (isset($_POST['IDE_name_id'])) {
    $IDE_name_id = $_POST['IDE_name_id'];
} else {
    $IDE_name_id = '';
} // Name id for IDE

if (isset($_POST['ag_view_type'])) {
    $ag_view_type = $_POST['ag_view_type'];
} else {
    $ag_view_type = '';
} // Agent view type
if (isset($_POST['ag_name'])) {
    $ag_name = $_POST['ag_name'];
} else {
    $ag_name = '';
} // Agent view by name

$tableHeaders = '';
$difference = 0;
$opening_bal = '';
$closing_bal = '';

if ($sheet_type == 1) { //1 Means contra balance sheet

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT created_date AS 'tdate', from_bank_id AS 'ctype', '' AS 'Credit', amt AS 'Debit', amt AS 'Amount' FROM ct_db_cash_withdraw WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and FIND_IN_SET(from_bank_id,'$bank_id') UNION ALL SELECT created_date AS 'tdate', 'Hand Cash' AS 'ctype', '' AS 'Credit', amount AS 'Debit', amount AS 'Amount' FROM ct_db_bank_deposit WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and FIND_IN_SET(to_bank_id,'$bank_id') UNION ALL SELECT created_date AS 'tdate', 'Hand Cash' AS 'ctype', amt AS 'Credit', '' AS 'Debit', amt AS 'Amount' FROM ct_cr_bank_withdraw WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and FIND_IN_SET(from_bank_id,'$bank_id') UNION ALL SELECT created_date AS 'tdate', to_bank_id AS 'ctype', amt AS 'Credit', '' AS 'Debit', amt AS 'Amount' FROM ct_cr_cash_deposit WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) and FIND_IN_SET(to_bank_id,'$bank_id') ORDER BY 1");


    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }
            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }
    $tabBodyEnd = "<tr><td></td><td colspan='2'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td></td><td colspan='2'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($creditSum - $debitSum) . "</td></tr>";
} else if ($sheet_type == 2) { // 2 Means Exchange Balance Sheet

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Exchange Entry</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT created_date AS tdate, 'Hand Cash' AS ctype, insert_login_id AS from_user_id, '' AS Credit, amt AS Debit, amt AS Amount 
    FROM ct_db_hexchange 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND to_user_id = '$user_id'
    
    UNION ALL 
    
    SELECT created_date AS tdate, to_bank_id AS ctype, insert_login_id AS from_user_id, '' AS Credit, amt AS Debit, amt AS Amount 
    FROM ct_db_bexchange 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(to_bank_id, '$bank_id')
    
    UNION ALL 
    
    SELECT created_date AS tdate, 'Hand Cash' AS ctype, insert_login_id AS from_user_id, amt AS Credit, '' AS Debit, amt AS Amount 
    FROM ct_cr_hexchange 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND to_user_id = '$user_id'
    
    UNION ALL 
    
    SELECT created_date AS tdate, to_bank_id AS ctype, insert_login_id AS from_user_id, amt AS Credit, '' AS Debit, amt AS Amount 
    FROM ct_cr_bexchange 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(to_bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $usernameqry = $connect->query("SELECT fullname from user where user_id = '" . $row['from_user_id'] . "' ");
            $username = $usernameqry->fetch()['fullname'];
            $tabBody .= "<td>" . $username . "</td>";


            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }

    $tabBodyEnd = "<tr><td></td><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td></td><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
} else if ($sheet_type == 3) { // 3 Means Other income Balance Sheet

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Category</th><th>Credit Amount</th>";

    $qry = $connect->query("SELECT created_date AS tdate, 'Hand Cash' AS ctype, category , amt AS Credit
    FROM ct_cr_hoti 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT created_date AS tdate, to_bank_id AS ctype, category, amt AS Credit
    FROM ct_cr_boti 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(to_bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }



            $tabBody .= "<td>" . $row['category'] . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $i++;
        }
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }

    $tabBodyEnd = "<tr><td></td><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td></tr>";
} else if ($sheet_type == 4 and $exp_cat_type == '') { //4 Means Expense Balance Sheet
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Category</th><th>Debit Amount</th>";

    $qry = $connect->query("SELECT created_date AS tdate, 'Hand Cash' AS ctype, cat , amt AS Debit
    FROM ct_db_hexpense 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT created_date AS tdate, bank_id AS ctype, cat, amt AS Debit
    FROM ct_db_bexpense 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $catqry = $connect->query("SELECT category from expense_category where id = '" . $row['cat'] . "' ");
            $category = $catqry->fetch()['category'];

            $tabBody .= "<td>" . $category . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }

    $tabBodyEnd = "<tr><td></td><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
} else if ($sheet_type == 4 and $exp_cat_type != '') { //4 Means Expense Balance Sheet and exp_cat type if has values then show category wise
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Category</th><th>Debit Amount</th>";

    $qry = $connect->query("SELECT created_date AS tdate, 'Hand Cash' AS ctype, cat , amt AS Debit
    FROM ct_db_hexpense 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id' and cat = $exp_cat_type
    
    UNION ALL 
    
    SELECT created_date AS tdate, bank_id AS ctype, cat, amt AS Debit
    FROM ct_db_bexpense 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id') and cat = $exp_cat_type
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        $tabBody = '<tr>';

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $catqry = $connect->query("SELECT category from expense_category where id = '" . $row['cat'] . "' ");
            $category = $catqry->fetch()['category'];

            $tabBody .= "<td>" . $category . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }

        $tabBodyEnd = "<tr><td></td><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }
} else if ($sheet_type == 5 and $IDEtype == 1 and $IDEview_type == 1 and $IDE_name_id == '') { //5 Means IDE Balance Sheet, 1 Means Investment Balance Sheet, 1 Means Overall Balance sheet

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Name</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name 
    FROM ct_db_hinvest cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE MONTH(cdh.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdh.created_date) = YEAR(CURRENT_DATE()) AND cdh.insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_binvest cdb
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE MONTH(cdb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(cdb.bank_id, '$bank_id')
    
    UNION ALL 
    
    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hinvest cch
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE MONTH(cch.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cch.created_date) = YEAR(CURRENT_DATE()) AND cch.insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_binvest ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE MONTH(ccb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(ccb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(ccb.bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . $row['name'] . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='4'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
} else if ($sheet_type == 5 and $IDEtype == 1 and $IDEview_type == 2 and $IDE_name_id != '') { //5 Means IDE Balance Sheet, 1 Means Investment Balance Sheet, 2 Means Individual Balance sheet

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Name</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name  
    FROM ct_db_hinvest cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE MONTH(cdh.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdh.created_date) = YEAR(CURRENT_DATE()) AND cdh.insert_login_id = '$user_id' and cdh.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_binvest cdb
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE MONTH(cdb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(cdb.bank_id, '$bank_id') and cdb.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hinvest cch
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE MONTH(cch.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cch.created_date) = YEAR(CURRENT_DATE()) AND cch.insert_login_id = '$user_id' and cch.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_binvest ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE MONTH(ccb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(ccb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(ccb.bank_id, '$bank_id') and ccb.name_id = '$IDE_name_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . $row['name'] . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='4'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
} else if ($sheet_type == 5 and $IDEtype == 2 and $IDEview_type == 1 and $IDE_name_id == '') { //5 Means IDE Balance Sheet, 2 Means Deposit Balance Sheet, 1 Means Overall Balance sheet
    {
        $opening_qry = $connect->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amt AS Debit
            FROM ct_db_hdeposit
            WHERE
                created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amt AS Credit,
                '' AS Debit
            FROM ct_cr_hdeposit
            WHERE
                created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amt AS Debit
            FROM ct_db_bdeposit
            WHERE
                created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amt AS Credit,
                '' AS Debit
            FROM ct_cr_bdeposit
            WHERE
                created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND insert_login_id = '$user_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $connect->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amt AS Debit
            FROM ct_db_hdeposit
            WHERE
                created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amt AS Credit,
                '' AS Debit
            FROM ct_cr_hdeposit
            WHERE
                created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND insert_login_id = '$user_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amt AS Debit
            FROM ct_db_bdeposit
            WHERE
                created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amt AS Credit,
                '' AS Debit
            FROM ct_cr_bdeposit
            WHERE
                created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amt AS Debit
            FROM ct_db_hdeposit
            WHERE
                MONTH(created_date) = MONTH(CURRENT_DATE())
                AND YEAR(created_date) = YEAR(CURRENT_DATE())
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amt AS Credit,
                '' AS Debit
            FROM ct_cr_hdeposit
            WHERE
                MONTH(created_date) = MONTH(CURRENT_DATE())
                AND YEAR(created_date) = YEAR(CURRENT_DATE())
                AND insert_login_id = '$user_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amt AS Debit
            FROM ct_db_bdeposit
            WHERE
                MONTH(created_date) = MONTH(CURRENT_DATE())
                AND YEAR(created_date) = YEAR(CURRENT_DATE())
                AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amt AS Credit,
                '' AS Debit
            FROM ct_cr_bdeposit
            WHERE
                MONTH(created_date) = MONTH(CURRENT_DATE())
                AND YEAR(created_date) = YEAR(CURRENT_DATE())
                AND insert_login_id = '$user_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Name</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $connect->query("SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name 
    FROM ct_db_hdeposit cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE MONTH(cdh.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdh.created_date) = YEAR(CURRENT_DATE()) AND cdh.insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_bdeposit cdb 
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE MONTH(cdb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(cdb.bank_id, '$bank_id')
    
    UNION ALL 
    
    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hdeposit cch 
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE MONTH(cch.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cch.created_date) = YEAR(CURRENT_DATE()) AND cch.insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_bdeposit ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE MONTH(ccb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(ccb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(ccb.bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . $row['name'] . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='4'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";
} else if ($sheet_type == 5 and $IDEtype == 2 and $IDEview_type == 2 and $IDE_name_id != '') { //5 Means IDE Balance Sheet, 2 Means Deposit Balance Sheet, 2 Means Individual Balance sheet
    {
        $opening_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
            FROM (
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hdeposit
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hdeposit
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bdeposit
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bdeposit
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
            ) AS opening
        ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
            FROM (
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hdeposit
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hdeposit
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
                
                UNION ALL
                
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bdeposit
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bdeposit
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hdeposit
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hdeposit
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
                
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bdeposit
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bdeposit
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
            ) AS closing
        ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Name</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name 
    FROM ct_db_hdeposit cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE MONTH(cdh.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdh.created_date) = YEAR(CURRENT_DATE()) AND cdh.insert_login_id = '$user_id' and cdh.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_bdeposit cdb 
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE MONTH(cdb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(cdb.bank_id, '$bank_id') and cdb.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hdeposit cch 
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE MONTH(cch.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cch.created_date) = YEAR(CURRENT_DATE()) AND cch.insert_login_id = '$user_id' and cch.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_bdeposit ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE MONTH(ccb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(ccb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(ccb.bank_id, '$bank_id') and ccb.name_id = '$IDE_name_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . $row['name'] . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='4'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";
} else if ($sheet_type == 5 and $IDEtype == 3 and $IDEview_type == 1 and $IDE_name_id == '') { //5 Means IDE Balance Sheet, 3 Means EL Balance Sheet, 1 Means Overall Balance sheet
    {
        $opening_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
            FROM (
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' 
            ) AS opening
        ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
            FROM (
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' 
                
                UNION ALL
                
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' 
                
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' 
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' 
            ) AS closing
        ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Name</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name 
    FROM ct_db_hel cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE MONTH(cdh.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdh.created_date) = YEAR(CURRENT_DATE()) AND cdh.insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_bel cdb 
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE MONTH(cdb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(cdb.bank_id, '$bank_id')
    
    UNION ALL 
    
    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hel cch
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE MONTH(cch.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cch.created_date) = YEAR(CURRENT_DATE()) AND cch.insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_bel ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE MONTH(ccb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(ccb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(ccb.bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . $row['name'] . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='4'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";
} else if ($sheet_type == 5 and $IDEtype == 3 and $IDEview_type == 2 and $IDE_name_id != '') { //5 Means IDE Balance Sheet, 3 Means EL Balance Sheet, 2 Means Individual Balance sheet
    {
        $opening_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
            FROM (
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bel
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
            ) AS opening
        ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
            FROM (
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
                
                UNION ALL
                
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bel
                WHERE
                    created_date <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
                
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bel
                WHERE
                    MONTH(created_date) = MONTH(CURRENT_DATE())
                    AND YEAR(created_date) = YEAR(CURRENT_DATE())
                    AND insert_login_id = '$user_id' and name_id = '$IDE_name_id'
            ) AS closing
        ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Name</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";

    $qry = $connect->query("SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name 
    FROM ct_db_hel cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE MONTH(cdh.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdh.created_date) = YEAR(CURRENT_DATE()) AND cdh.insert_login_id = '$user_id' and cdh.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_bel cdb 
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE MONTH(cdb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cdb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(cdb.bank_id, '$bank_id') and cdb.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hel cch
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE MONTH(cch.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cch.created_date) = YEAR(CURRENT_DATE()) AND cch.insert_login_id = '$user_id' and cch.name_id = '$IDE_name_id'
    
    UNION ALL 
    
    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_bel ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE MONTH(ccb.created_date) = MONTH(CURRENT_DATE()) AND YEAR(ccb.created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(ccb.bank_id, '$bank_id') and ccb.name_id = '$IDE_name_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . $row['name'] . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='4'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='4'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";
} else if ($sheet_type == 6) { //6 Means Excess Fund Balance Sheet

    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Bank</th><th>Ref ID</th><th>Remark</th><th>Transaction ID</th><th>Amount</th>";

    $qry = $connect->query(" SELECT created_date AS tdate, bank_id AS ctype, ref_code, remark, trans_id, amt AS Debit
    FROM ct_db_exf 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id')
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }


            $tabBody .= "<td>" . $row['ref_code'] . "</td>";
            $tabBody .= "<td>" . $row['remark'] . "</td>";
            $tabBody .= "<td>" . $row['trans_id'] . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }

    $tabBodyEnd = "<tr><td colspan='6'><b>Total</b></td><td><b>" . moneyFormatIndia($debitSum) . "</b></td></tr>";
} else if ($sheet_type == 7 && $ag_view_type == 1 && $ag_name == '') { //7 Means Agent Balance Sheet and 1 means overall


    //get agent user id to get data from collection
    $ag_userid_qry = $connect->query("SELECT `user_id` from user where FIND_IN_SET( `ag_id`, (SELECT `agentforstaff` from user where `user_id` = '$user_id')) ");
    $ids = array();
    while ($row = $ag_userid_qry->fetch()) {
        $ids[] = $row['user_id'];
    }
    $ag_user_id = implode(',', $ids);


    $tableHeaders = "<th width='50'>S.No</th><th>Agent</th><th>Date</th><th>Coll Amount</th><th>Net Cash</th><th>Credit</th><th>Debit</th>"; {
        $opening_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
            FROM (
                SELECT cl.total_paid_track as Credit, '' AS Debit
                FROM collection cl 
                WHERE
                    cl.created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') and FIND_IN_SET(cl.insert_login_id,'$ag_user_id')
                
                UNION ALL

                SELECT '' AS Credit, li.cash + li.cheque_value + li.transaction_value AS Debit  
                FROM loan_issue li 
                WHERE
                    li.created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') and FIND_IN_SET(li.agent_id,'$ag_user_id')
                
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id'
    
                UNION ALL

                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id'
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id'
            ) AS opening
        ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];
    }

    $qry = $connect->query("SELECT u.ag_id AS ag_id, date(cl.created_date) as tdate, cl.total_paid_track as coll_amt,'' AS netcash, '' AS Credit, '' AS Debit
    FROM collection cl JOIN user u ON cl.insert_login_id = u.user_id
    WHERE cl.total_paid_track != '' AND MONTH(cl.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cl.created_date) = YEAR(CURRENT_DATE()) and FIND_IN_SET(cl.insert_login_id,'$ag_user_id')
    
    UNION ALL

    SELECT li.agent_id AS ag_id, date(li.created_date) as tdate,'' as coll_amt,  COALESCE(li.cash, 0) + COALESCE(li.cheque_value, 0) + COALESCE(li.transaction_value, 0) AS netcash, '' AS Credit, '' AS Debit 
    FROM loan_issue li JOIN user u ON u.user_id = '$user_id'
    WHERE MONTH(li.created_date) = MONTH(CURRENT_DATE()) AND YEAR(li.created_date) = YEAR(CURRENT_DATE()) and FIND_IN_SET(li.agent_id,u.agentforstaff) AND agent_id IS NOT NULL 

    UNION ALL 

    SELECT ag_id, created_date AS tdate, '' AS coll_amt,'' AS netcash, '' AS Credit, amt AS Debit
    FROM ct_db_hag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT ag_id, created_date AS tdate,'' AS coll_amt,'' AS netcash, '' AS Credit, amt AS Debit
    FROM ct_db_bag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id')
    
    UNION ALL 
    
    SELECT ag_id, created_date AS tdate,'' AS coll_amt,'' AS netcash, amt AS Credit, '' AS Debit
    FROM ct_cr_hag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT ag_id, created_date AS tdate,'' AS coll_amt,'' AS netcash, amt AS Credit, '' AS Debit
    FROM ct_cr_bag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id') AND insert_login_id = '$user_id'
    
    ORDER BY tdate
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;
    $collSum = 0;
    $netSum = 0;
    $difference1 = 0;
    $difference2 = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";

            $agqry = $connect->query("SELECT ag_name from agent_creation where ag_id = '" . $row['ag_id'] . "' ");
            $ag_name = $agqry->fetch()['ag_name'];

            $tabBody .= "<td>" . $ag_name . "</td>";

            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['coll_amt']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['netcash']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $collSum = $collSum + intVal($row['coll_amt']);
            $netSum = $netSum + intVal($row['netcash']);
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference1 = $collSum - $netSum;
        $difference2 = $debitSum - $creditSum;
        $closing_bal = $difference1 + $difference2 + $opening_bal;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td><b>" . moneyFormatIndia($collSum) . "</b></td><td><b>" . moneyFormatIndia($netSum) . "</b></td><td><b>" . moneyFormatIndia($creditSum) . "</b></td><td><b>" . moneyFormatIndia($debitSum) . "</b></td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'><b>" . moneyFormatIndia($difference1) . "</b></td><td colspan='2'><b>" . moneyFormatIndia($difference2) . "</b></td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='4'><b>" . moneyFormatIndia($closing_bal) . "</b></td></tr>";
} else if ($sheet_type == 7 && $ag_view_type == 2 && $ag_name != '') { //7 Means Agent Balance Sheet and 2 means individual and agent id

    //get agent user id to get data from collection
    $ag_userid_qry = $connect->query("SELECT `user_id` from user where ag_id = '$ag_name' ");
    $ag_user_id = $ag_userid_qry->fetch()['user_id'] ?? '';

    $tableHeaders = "<th width='50'>S.No</th><th>Agent</th><th>Date</th><th>Coll Amount</th><th>Net Cash</th><th>Credit</th><th>Debit</th>"; {
        $opening_qry = $connect->query("SELECT
            IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
            FROM (
                SELECT cl.total_paid_track as Credit, '' AS Debit
                FROM collection cl JOIN user us 
                ON us.user_id = '$user_id' and FIND_IN_SET('$ag_name',us.agentforstaff)
                WHERE
                    cl.created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') and cl.insert_login_id = '$ag_user_id'
                
                UNION ALL

                SELECT '' AS Credit, li.cash + li.cheque_value + li.transaction_value AS Debit  
                FROM loan_issue li JOIN user us 
                ON us.user_id = '$user_id' and FIND_IN_SET('$ag_name',us.agentforstaff)
                WHERE
                    li.created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') and li.agent_id = '$ag_name'
                
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_hag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and ag_id = '$ag_name'
    
                UNION ALL

                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_hag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and ag_id = '$ag_name'
    
                UNION ALL
    
                SELECT
                    '' AS Credit,
                    amt AS Debit
                FROM ct_db_bag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and ag_id = '$ag_name'
    
                UNION ALL
    
                SELECT
                    amt AS Credit,
                    '' AS Debit
                FROM ct_cr_bag
                WHERE
                    created_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                    AND insert_login_id = '$user_id' and ag_id = '$ag_name'
            ) AS opening
        ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];
    }


    $qry = $connect->query("SELECT $ag_name AS ag_id, date(cl.created_date) as tdate, cl.total_paid_track as coll_amt,'' AS netcash, '' AS Credit, '' AS Debit
    FROM collection cl JOIN user us 
    ON us.user_id = '$user_id' and FIND_IN_SET('$ag_name',us.agentforstaff)
    WHERE cl.total_paid_track != '' AND MONTH(cl.created_date) = MONTH(CURRENT_DATE()) AND YEAR(cl.created_date) = YEAR(CURRENT_DATE()) and cl.insert_login_id = '$ag_user_id'
    
    UNION ALL

    SELECT li.agent_id AS ag_id, date(li.created_date) as tdate,'' as coll_amt, COALESCE(li.cash, 0) + COALESCE(li.cheque_value, 0) + COALESCE(li.transaction_value, 0) AS netcash, '' AS Credit, '' AS Debit 
    FROM loan_issue li JOIN user us 
    ON us.user_id = '$user_id' and FIND_IN_SET('$ag_name',us.agentforstaff)
    WHERE MONTH(li.created_date) = MONTH(CURRENT_DATE()) AND YEAR(li.created_date) = YEAR(CURRENT_DATE()) and li.agent_id = '$ag_name'

    UNION ALL

    SELECT ag_id, created_date AS tdate, '' AS coll_amt,'' AS netcash, '' AS Credit, amt AS Debit
    FROM ct_db_hag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id' and ag_id = '$ag_name'
    
    UNION ALL 
    
    SELECT ag_id, created_date AS tdate,'' AS coll_amt,'' AS netcash, '' AS Credit, amt AS Debit
    FROM ct_db_bag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id') and ag_id = '$ag_name'
    
    UNION ALL 
    
    SELECT ag_id, created_date AS tdate,'' AS coll_amt,'' AS netcash, amt AS Credit, '' AS Debit
    FROM ct_cr_hag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND insert_login_id = '$user_id' and ag_id = '$ag_name'
    
    UNION ALL 
    
    SELECT ag_id, created_date AS tdate,'' AS coll_amt,'' AS netcash, amt AS Credit, '' AS Debit
    FROM ct_cr_bag 
    WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE()) AND FIND_IN_SET(bank_id, '$bank_id') and ag_id = '$ag_name'
    
    ORDER BY tdate
    ");


    $i = 1;
    $creditSum = 0;
    $debitSum = 0;
    $collSum = 0;
    $netSum = 0;
    $difference1 = 0;
    $difference2 = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";

            $agqry = $connect->query("SELECT ag_name from agent_creation where ag_id = '" . $row['ag_id'] . "' ");
            $ag_name = $agqry->fetch()['ag_name'];

            $tabBody .= "<td>" . $ag_name . "</td>";

            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['coll_amt']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['netcash']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $collSum = $collSum + intVal($row['coll_amt']);
            $netSum = $netSum + intVal($row['netcash']);
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference1 = $collSum - $netSum;
        $difference2 = $debitSum - $creditSum;
        $closing_bal = $difference1 + $difference2 + $opening_bal;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td><b>" . moneyFormatIndia($collSum) . "</b></td><td><b>" . moneyFormatIndia($netSum) . "</b></td><td><b>" . moneyFormatIndia($creditSum) . "</b></td><td><b>" . moneyFormatIndia($debitSum) . "</b></td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'><b>" . moneyFormatIndia($difference1) . "</b></td><td colspan='2'><b>" . moneyFormatIndia($difference2) . "</b></td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='4'><b>" . moneyFormatIndia($closing_bal) . "</b></td></tr>";
} else {
    return '';
}
?>
<?php
if ($opening_bal != '') {
?>
    <div class="col-12">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12"></div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group">
                    <label for=''><b>Opening Balance: <?php echo $opening_bal; ?></b></label>
                    <!-- <input type="text" class="form-control" value='<?php echo $opening_bal; ?>' readonly> -->
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12"></div>
        </div>
    </div>
<?php } ?>
<table class="table custom-table" id='blncSheetTable'>
    <thead>
        <tr>
            <?php echo $tableHeaders; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        echo $tabBody;
        ?>
    </tbody>
    <tfoot>
        <?php
        echo $tabBodyEnd;
        ?>
    </tfoot>
</table>

<script type='text/javascript'>
    $(function() {
        $('#blncSheetTable').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
            //To change total amount dynamically
            // "footerCallback": function () {
            //     var api = this.api();
            //     var columnIdx = 4; // Replace with the index of the desired column
            //     var columnData = api.column(columnIdx, { search: 'applied' }).data();

            //     // Calculate the total value of the column
            //     var total = columnData.reduce(function (a, b) {
            //         b = b.replace(',','');
            //         return parseInt(a) + parseInt(b);
            //     }, 0);

            //     // Display the total in the table footer
            //     $(api.column(columnIdx).footer()).html( total);
            // }
        });



    });
</script>

<?php
//Format number in Indian Format
function moneyFormatIndia($num1)
{
    if ($num1 < 0) {
        $num = str_replace("-", "", $num1);
    } else {
        $num = $num1;
    }
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    if ($num1 < 0 && $num1 != '') {
        $thecash = "-" . $thecash;
    }

    return $thecash;
}


if ($sheet_type == 4) { //4 Means Expense Balance Sheet so show/hide view types

    echo "<script>$('#exp_typeDiv').show()</script>";
} else {
    echo "<script>$('#exp_typeDiv').hide()</script>";
}

// Close the database connection
$connect = null;
?>