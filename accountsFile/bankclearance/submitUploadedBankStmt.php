<?php
@session_start();
$user_id = $_SESSION["userid"] ?? 0;
include("../../ajaxconfig.php");

// Get POST data
$bank_id   = $_POST['bank_id'] ?? '';
$bank_name = $_POST['bank_short_name'] ?? '';

// Include Excel Reader libraries
require_once('../../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../../vendor/csvreader/SpreadsheetReader.php');

// Prepare response array
$response = [
    'status' => 'error',
    'inserted' => 0,
    'error_row' => null,
    'message' => ''
];

// Check if file is uploaded
if (!isset($_FILES["file"]["type"])) {
    $response['message'] = 'File not received';
    echo json_encode($response);
    exit;
}

// Allowed Excel MIME types
$allowedFileType = [
    'application/vnd.ms-excel',
    'text/xls',
    'text/xlsx',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

// Check file type
if (!in_array($_FILES["file"]["type"], $allowedFileType)) {
    $response['message'] = 'Invalid file type';
    echo json_encode($response);
    exit;
}

// Move uploaded file
$targetPath = '../../uploads/bank_stmt/' . time() . '_' . $_FILES['file']['name'];
move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

// Initialize Excel Reader
$Reader = new SpreadsheetReader($targetPath);
$sheetCount = count($Reader->sheets());

// Initialize variables
$inserted = 1;
$excel_row_no = 0;

// ⭐ Get the last balance for this bank (NEW)
$last_balance_qry = $connect->query(" SELECT balance FROM bank_stmt   WHERE bank_id = '$bank_id' ORDER BY id DESC LIMIT 1 ");

$running_balance = 0; // default opening balance
if ($row = $last_balance_qry->fetch(PDO::FETCH_ASSOC)) {
    $running_balance = floatval($row['balance']);
}

// Loop through all sheets
for ($i = 0; $i < $sheetCount; $i++) {
    $Reader->ChangeSheet($i);

    foreach ($Reader as $Row) {
        $excel_row_no++;

        // Skip header or empty rows
        if (empty($Row[0]) || stripos($Row[0], 'date') !== false) {
            continue;
        }

        /* =============================== DATE & TIME HANDLING  =============================== */
        $value = trim($Row[0]);
        $dt = null;

        // 1️⃣ Excel numeric date
        if (is_numeric($value) && $value > 59) { 
            $unixTimestamp = ($value - 25569) * 86400;
            $dt = new DateTime("@$unixTimestamp");
            $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        } else {
            // 2️⃣ Detect MM/DD/YY or DD/MM/YYYY
            $parts = preg_split('/[\/\-: ]/', $value);
            if (count($parts) >= 3) {
                $month = intval($parts[0]);
                $day   = intval($parts[1]);
                $year  = intval($parts[2]);

                $hour = $parts[3] ?? 0;
                $min  = $parts[4] ?? 0;

                // Swap if month > 12
                if ($month > 12) {
                    $tmp = $month;
                    $month = $day;
                    $day = $tmp;
                }

                // Fix 2-digit year
                if ($year < 100) $year += 2000;

                $dt = DateTime::createFromFormat('Y-m-d H:i', "$year-$month-$day $hour:$min", new DateTimeZone('Asia/Kolkata'));
            }
        }

        // Skip invalid dates
        if (!$dt) continue;

        // Assign formats
        $trans_date        = $dt->format('Y-m-d');        
        $trans_time        = $dt->format('H:i');          
        $trans_date_for_id  = $dt->format('dmY');   
        $trans_datetime     = $dt->format('Y-m-d H:i');

        /* =============================== OTHER VALUES =============================== */
        $narration = isset($Row[1]) ? $connect->quote(trim($Row[1])) : "''";
        $credit    = isset($Row[2]) ? floatval(str_replace(',', '', $Row[2])): 0;
        $debit     = isset($Row[3]) ? floatval(str_replace(',', '', $Row[3])): 0;
        $excel_balance = isset($Row[4]) ? floatval(str_replace(',', '', $Row[4])): 0;

        if ($credit <= 0 && $debit <= 0) continue; // Skip zero entries

        /* ===============================  BALANCE VALIDATION =============================== */
        $expected_balance = $running_balance + $credit - $debit;

        if (round($expected_balance, 2) != round($excel_balance, 2)) {
            $response['status'] = 'balance_mismatch';
            $response['inserted'] = $inserted;
            $response['error_row'] = $excel_row_no;
            $response['message'] = "Balance mismatch at row {$excel_row_no}. Expected {$expected_balance}, Excel shows {$excel_balance}";
            echo json_encode($response);
            exit;
        }

        /* =============================== TRANSACTION TYPE =============================== */
        /* =============================== RUNNING NUMBER FOR TRANS ID (FIXED) =============================== */
        $type = ($credit > 0) ? 'CR' : 'DB';

        $run_qry = $connect->query("
            SELECT  MAX(CAST(SUBSTRING_INDEX(trans_id, '-', -1) AS UNSIGNED)) AS last_no
            FROM bank_stmt
            WHERE bank_id = '$bank_id'
            AND DATE(trans_date) = '$trans_date'
            AND trans_id LIKE '{$bank_name}{$type}-%'
        ");

        $last_no = $run_qry->fetch(PDO::FETCH_ASSOC)['last_no'] ?? 0;
        $run_no  = str_pad($last_no + 1, 3, '0', STR_PAD_LEFT);

        $auto_trans_id = $bank_name . $type . '-' . $trans_date_for_id . '-' . $run_no;
        $auto_trans_id = $connect->quote($auto_trans_id);


        /* ===============================
           INSERT INTO DATABASE
        =============================== */
        $transaction_amount = ($credit > 0) ? $credit : $debit;

        $insert = $connect->query("
            INSERT INTO bank_stmt (
                bank_id, trans_date, narration, trans_id,
                credit, debit, balance, transaction_amount,
                insert_login_id, created_date
            ) VALUES (
                '$bank_id', '$trans_datetime', $narration, $auto_trans_id,
                '$credit', '$debit', '$excel_balance',$transaction_amount ,
                '$user_id', NOW()
            )
        ");

        if ($insert) {
            $inserted++;
            $running_balance = $excel_balance; // Update running balance
        }
    }
}

// All rows inserted successfully
$response['status'] = 'success';
$response['inserted'] = $inserted;
$response['message'] = 'All rows inserted successfully';
echo json_encode($response);
$connect = null;
?>
