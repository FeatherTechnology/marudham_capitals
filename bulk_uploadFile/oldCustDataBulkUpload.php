<?php

include 'bulkUploadClass.php';
require_once('../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../vendor/csvreader/SpreadsheetReader.php');

$obj = new bulkUploadClass;
$userData = $obj->getUserDetails($connect);

$allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'text/csv', 'text/xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if (in_array($_FILES["excelFile"]["type"], $allowedFileType)) {

    $excelfolder = $obj->uploadFiletoFolder();

    $Reader = new SpreadsheetReader($excelfolder);
    $sheetCount = count($Reader->sheets());

    for ($i = 0; $i < $sheetCount; $i++) {
        $Reader->ChangeSheet($i);
        $rowChange = 0;
        foreach ($Reader as $Row) {

            if ($rowChange != 0) { // omitted 0 to avoid headers

                $data = $obj->fetchAllRowData($connect, $Row);
                $data['req_code'] = $obj->getRequestCode($connect);
                $data['doc_code'] = $obj->getDocumentCode($connect);
                $data['loan_id'] = $obj->getLoanCode($connect);
                $data['area_id'] = $obj->getAreaId($connect, $data['area']);
                $data['sub_area_id'] = $obj->getSubAreaId($connect, $data['sub_area'], $data['area_id']);
                $data['loan_cat_id'] = $obj->getLoanCategoryId($connect, $data['loan_category']);
                $data['sub_categoryCheck'] = $obj->checkSubCategory($connect, $data['sub_category']);
                $data['group_name'] = $obj->getAreaGroup($connect, $data['sub_area_id']) == $data['area_group'] ? $data['area_group'] : 'Invalid';
                $data['line_name'] = $obj->getAreaLine($connect, $data['sub_area_id']) == $data['area_line'] ? $data['area_line'] : 'Invalid';
                $data['agent_id'] = $obj->checkAgent($connect, $data['agent_id']);
                $checkCustomerData = $obj->checkCustomerData($connect, $data['cus_id']);
                $data['cus_data'] = $checkCustomerData['cus_data'];
                $data['cus_reg_id'] = $checkCustomerData['cus_reg_id'];
                $data['scheme_id'] = $obj->getSchemeId($connect, $data['scheme_name']);

                $data['cus_status'] = '14';
                if ($data['closed_status'] != '0') { //other than 0 means closed status is not empty in excel so need to change customer status to NOC
                    $data['cus_status'] = '21'; //if then change the customer status to 21 for that request
                }

                $err_columns = $obj->handleError($data);
                if (empty($err_columns)) {
                    $req_id = $obj->raiseRequest($connect, $data, $userData);
                    $obj->verificationTables($connect, $data, $userData, $req_id);
                    $obj->approvalTables($connect, $req_id);
                    $obj->acknowledgementTables($connect, $data, $req_id, $userData);
                    $obj->loanIssueTables($connect, $data, $userData, $req_id);

                    if ($data['closed_status'] != '0') {
                        $obj->closedTables($connect, $data, $userData, $req_id);
                    }
                } else {
                    $errtxt = "Please Check the input given in Serial No: " . ($rowChange) . " on below. <br><br>";
                    $errtxt .= "<ul>";
                    foreach ($err_columns as $columns) {
                        $errtxt .= "<li>$columns</li>";
                    }
                    $errtxt .= "</ul><br>";
                    $errtxt .= "Insertion completed till Seiral No: " . ($rowChange - 1);
                    echo $errtxt;
                    exit();
                }
            }

            $rowChange++;
        }
    }
    $message = 'Bulk Upload Completed.';
} else {
    $message = 'File is not in Excel Format.';
}

echo $message;
