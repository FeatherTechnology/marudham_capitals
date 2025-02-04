<?php
// error_reporting(0);
@session_start();
$user_id = $_SESSION["userid"];
include("../../ajaxconfig.php");

$bank_id = $_POST['bank_id']; // bank id selected in upload modal

require_once('../../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../../vendor/csvreader/SpreadsheetReader.php');
if(isset($_FILES["file"]["type"])){
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    if(in_array($_FILES["file"]["type"],$allowedFileType)){
        
        
        $targetPath = '../../uploads/bank_stmt/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets()); 
        
        for($i=0;$i<$sheetCount;$i++)
        {
            $Reader->ChangeSheet($i);
            foreach ($Reader as $Row){ 
                if($Row[0] != "Date"){
                    

                    // $trans_date = "";
                    // if (isset($Row[0])) {
                    //     $excel_date = $Row[0];
                    //     $date = date_create_from_format('!d/m/y', $excel_date);
                    //     if ($date === false) {
                    //         $date = date_create_from_format('!m/d/y', $excel_date);
                    //     }
                    //     if ($date === false) {
                    //         $date = date_create_from_format('!m-d-y', $excel_date);
                    //     }
                    //     if ($date !== false) {
                    //         $trans_date = $date->format('Y-m-d');
                    //     }
                    // }
                    $trans_date = "";
                    if (isset($Row[0])) {
                        $excel_date = $Row[0];
                        
                        $date_formats = array(
                            'Y-m-d',        // Year-Month-Day
                            'd/m/Y',        // Day/Month/Year
                            'm/d/Y',        // Month/Day/Year
                            'd-m-Y',        // Day-Month-Year
                            'm-d-Y',        // Month-Day-Year
                            'Y/m/d',        // Year/Month/Day
                            'd-m-y',        // Day-Month-Year (short year format)
                            'm-d-y'         // Month-Day-Year (short year format)
                        );

                        foreach ($date_formats as $date_format) {
                            $date = date_create_from_format($date_format, $excel_date);
                            if ($date !== false) {
                                $trans_date = $date->format('Y-m-d');
                                break;
                            }
                        }
                    }

                    $narration = "";
                    if(isset($Row[1])) {
                        $narration = $connect->quote($Row[1]);  
                    }

                    $trans_id = "";
                    if(isset($Row[2])) {
                        $trans_id = $connect->quote($Row[2]);  
                    }
                    
                    $credit = "";
                    if(isset($Row[3])) {
                        $credit = $connect->quote($Row[3]);  
                    }
                    
                    $debit = "";
                    if(isset($Row[4])) {
                        $debit = $connect->quote($Row[4]);  
                    }
                    
                    $balance = "";
                    if(isset($Row[5])) {
                        $balance = $connect->quote($Row[5]);  
                    }
                    
                    
                    if($i==0 && $trans_date != "" && $trans_id != "" )
                    { 
                        $insert = $connect->query("INSERT INTO `bank_stmt`(`bank_id`, `trans_date`, `narration`,`trans_id`, `credit`, `debit`, `balance`, `insert_login_id`, `created_date`) VALUES ('$bank_id', '$trans_date', $narration, $trans_id, $credit, $debit, $balance, '$user_id', now() )");
                    }
                    
                }
            }
        }

        if($insert->rowCount() > 0) {
            $message = 0; // if successfully inserted
        }
        else{
            $message = 1; // if insert query not working
        }
    }
}else{
    $message = 2; //if file is not sent
}

echo $message;

// Close the database connection
$connect = null;
?>

    