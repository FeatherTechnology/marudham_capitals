<?php
@session_start();
include '../ajaxconfig.php';


class bulkUploadClass
{

    //File Handling Part
    function uploadFiletoFolder()
    {
        $excel = $_FILES['excelFile']['name'];
        $excel_temp = $_FILES['excelFile']['tmp_name'];
        $excelfolder = "../uploads/bulk_upload/" . $excel;
        $fileExtension = pathinfo($excelfolder, PATHINFO_EXTENSION); //get the file extention

        $excel = uniqid() . '.' . $fileExtension;
        while (file_exists("../uploads/bulk_upload/" . $excel)) {
            //this loop will continue until it generates a unique file name
            $excel = uniqid() . '.' . $fileExtension;
        }
        $excelfolder = "../uploads/bulk_upload/" . $excel;
        move_uploaded_file($excel_temp, "../uploads/bulk_upload/" . $excel);
        return $excelfolder;
    }
    function fetchAllRowData($connect, $Row)
    {
        $dataArray = array(
            'dor' =>isset($Row[1]) ?  substr($connect->quote($Row[1]), 1, -1) : "",
            'cus_id' => isset($Row[2]) ? substr($connect->quote( $Row[2]), 1, -1) : "",
            'cus_data' => isset($Row[3]) ? substr($connect->quote( $Row[3]), 1, -1) : "",
            'cus_exist_type' => isset($Row[4]) ? substr($connect->quote( $Row[4]), 1, -1) : "",
            'cus_name' => isset($Row[5]) ? substr($connect->quote( $Row[5]), 1, -1) : "",
            'dob' => isset($Row[6]) ? substr($connect->quote( $Row[6]), 1, -1) : "",
            'age' => isset($Row[7]) ? substr($connect->quote( $Row[7]), 1, -1) : "",
            'gender' => isset($Row[8]) ? substr($connect->quote( $Row[8]), 1, -1) : "",
            'state' => isset($Row[9]) ? substr($connect->quote( $Row[9]), 1, -1) : "",
            'district' => isset($Row[10]) ? substr($connect->quote( $Row[10]), 1, -1) : "",
            'taluk' => isset($Row[11]) ? substr($connect->quote( $Row[11]), 1, -1) : "",
            'area' => isset($Row[12]) ? substr($connect->quote( $Row[12]), 1, -1) : "",
            'sub_area' => isset($Row[13]) ? substr($connect->quote( $Row[13]), 1, -1) : "",
            'address' => isset($Row[14]) ? substr($connect->quote( $Row[14]), 1, -1) : "",
            'mobile1' => isset($Row[15]) ? substr($connect->quote( $Row[15]), 1, -1) : "",
            'father_name' => isset($Row[16]) ? substr($connect->quote( $Row[16]), 1, -1) : "",
            'mother_name' => isset($Row[17]) ? substr($connect->quote( $Row[17]), 1, -1) : "",
            'marital' => isset($Row[18]) ? substr($connect->quote( $Row[18]), 1, -1) : "",
            'spouse' => isset($Row[19]) ? substr($connect->quote( $Row[19]), 1, -1) : "",
            'guarantor_name' => isset($Row[20]) ? substr($connect->quote( $Row[20]), 1, -1) : "",
            'guarantor_relationship' => isset($Row[21]) ? substr($connect->quote( $Row[21]), 1, -1) : "",
            'guarantor_adhar' => isset($Row[22]) ? substr($connect->quote( $Row[22]), 1, -1) : "",
            'guarantor_age' => isset($Row[23]) ? substr($connect->quote( $Row[23]), 1, -1) : "",
            'guarantor_mobile' => isset($Row[24]) ? substr($connect->quote( $Row[24]), 1, -1) : "",
            'guarantor_occupation' => isset($Row[25]) ? substr($connect->quote( $Row[25]), 1, -1) : "",
            'guarantor_income' => isset($Row[26]) ? substr($connect->quote( $Row[26]), 1, -1) : "",
            'loan_category' => isset($Row[27]) ? substr($connect->quote( $Row[27]), 1, -1) : "",
            'sub_category' => isset($Row[28]) ? substr($connect->quote( $Row[28]), 1, -1) : "",
            'tot_amt' => isset($Row[29]) ? substr($connect->quote( $Row[29]), 1, -1) : "",
            'adv_amt' => isset($Row[30]) ? substr($connect->quote( $Row[30]), 1, -1) : "",
            'loan_amt' => isset($Row[31]) ? substr($connect->quote( $Row[31]), 1, -1) : "",
            'poss_type' => isset($Row[32]) ? substr($connect->quote( $Row[32]), 1, -1) : "",
            'poss_due_amt' => isset($Row[33]) ? substr($connect->quote( $Row[33]), 1, -1) : "",
            'poss_due_period' => isset($Row[34]) ? substr($connect->quote( $Row[34]), 1, -1) : "",
            'cal_category1' => isset($Row[35]) ? substr($connect->quote( $Row[35]), 1, -1) : "",
            'cal_category2' => isset($Row[36]) ? substr($connect->quote( $Row[36]), 1, -1) : "",
            'cal_category3' => isset($Row[37]) ? substr($connect->quote( $Row[37]), 1, -1) : "",
            'how_to_know' => isset($Row[38]) ? substr($connect->quote( $Row[38]), 1, -1) : "",
            'loan_count' => isset($Row[39]) ? substr($connect->quote( $Row[39]), 1, -1) : "",
            'first_loan_date' => isset($Row[40]) ? substr($connect->quote( $Row[40]), 1, -1) : "",
            'travel_with_company' => isset($Row[41]) ? substr($connect->quote( $Row[41]), 1, -1) : "",
            'monthly_income' => isset($Row[42]) ? substr($connect->quote( $Row[42]), 1, -1) : "",
            'other_income' => isset($Row[43]) ? substr($connect->quote( $Row[43]), 1, -1) : "",
            'support_income' => isset($Row[44]) ? substr($connect->quote( $Row[44]), 1, -1) : "",
            'commitment' => isset($Row[45]) ? substr($connect->quote( $Row[45]), 1, -1) : "",
            'monthly_due_capacity' => isset($Row[46]) ? substr($connect->quote( $Row[46]), 1, -1) : "",
            'loan_limit' => isset($Row[47]) ? substr($connect->quote( $Row[47]), 1, -1) : "",
            'about_customer' => isset($Row[48]) ? substr($connect->quote( $Row[48]), 1, -1) : "",
            'residential_type' => isset($Row[49]) ? substr($connect->quote( $Row[49]), 1, -1) : "",
            'residential_details' => isset($Row[50]) ? substr($connect->quote( $Row[50]), 1, -1) : "",
            'residential_address' => isset($Row[51]) ? substr($connect->quote( $Row[51]), 1, -1) : "",
            'residential_native_address' => isset($Row[52]) ? substr($connect->quote( $Row[52]), 1, -1) : "",
            'occupation_type' => isset($Row[53]) ? substr($connect->quote( $Row[53]), 1, -1) : "",
            'occupation_details' => isset($Row[54]) ? substr($connect->quote( $Row[54]), 1, -1) : "",
            'area_confirm_type' => isset($Row[55]) ? substr($connect->quote( $Row[55]), 1, -1) : "",
            'area_group' => isset($Row[56]) ? substr($connect->quote( $Row[56]), 1, -1) : "",
            'area_line' => isset($Row[57]) ? substr($connect->quote( $Row[57]), 1, -1) : "",
            'mortgage_process' => isset($Row[58]) ? substr($connect->quote( $Row[58]), 1, -1) : "",
            'endorsement_process' => isset($Row[59]) ? substr($connect->quote( $Row[59]), 1, -1) : "",
            'loan_date' => isset($Row[60]) ? substr($connect->quote( $Row[60]), 1, -1) : "",
            'profit_type' => isset($Row[61]) ? substr($connect->quote( $Row[61]), 1, -1) : "",
            'due_method_calc' => isset($Row[62]) ? substr($connect->quote( $Row[62]), 1, -1) : "",
            'due_type' => isset($Row[63]) ? substr($connect->quote( $Row[63]), 1, -1) : "",
            'profit_method' => isset($Row[64]) ? substr($connect->quote( $Row[64]), 1, -1) : "",
            'due_method_scheme' => isset($Row[65]) ? substr($connect->quote( $Row[65]), 1, -1) : "",
            'scheme_name' => isset($Row[66]) ? substr($connect->quote( $Row[66]), 1, -1) : "",
            'int_rate' => isset($Row[67]) ? substr($connect->quote( $Row[67]), 1, -1) : "",
            'due_period' => isset($Row[68]) ? substr($connect->quote( $Row[68]), 1, -1) : "",
            'doc_charge' => isset($Row[69]) ? substr($connect->quote( $Row[69]), 1, -1) : "",
            'proc_fee' => isset($Row[70]) ? substr($connect->quote( $Row[70]), 1, -1) : "",
            'loan_amt_cal' => isset($Row[71]) ? substr($connect->quote( $Row[71]), 1, -1) : "",
            'principal_amt_cal' => isset($Row[72]) ? substr($connect->quote( $Row[72]), 1, -1) : "",
            'int_amt_cal' => isset($Row[73]) ? substr($connect->quote( $Row[73]), 1, -1) : "",
            'tot_amt_cal' => isset($Row[74]) ? substr($connect->quote( $Row[74]), 1, -1) : "",
            'due_amt_cal' => isset($Row[75]) ? substr($connect->quote( $Row[75]), 1, -1) : "",
            'doc_charge_cal' => isset($Row[76]) ? substr($connect->quote( $Row[76]), 1, -1) : "",
            'proc_fee_cal' => isset($Row[77]) ? substr($connect->quote( $Row[77]), 1, -1) : "",
            'net_cash_cal' => isset($Row[78]) ? substr($connect->quote( $Row[78]), 1, -1) : "",
            'due_start_from' => isset($Row[79]) ? substr($connect->quote( $Row[79]), 1, -1) : "",
            'maturity_month' => isset($Row[80]) ? substr($connect->quote( $Row[80]), 1, -1) : "",
            'collection_method' => isset($Row[81]) ? substr($connect->quote( $Row[81]), 1, -1) : "",
            'communication' => isset($Row[82]) ? substr($connect->quote( $Row[82]), 1, -1) : "",
            'verification_person' => isset($Row[83]) ? substr($connect->quote( $Row[83]), 1, -1) : "",
            'verification_location' => isset($Row[84]) ? substr($connect->quote( $Row[84]), 1, -1) : "",
            'issued_to' => isset($Row[85]) ? substr($connect->quote( $Row[85]), 1, -1) : "",
            'agent_id' => isset($Row[86]) ? substr($connect->quote( $Row[86]), 1, -1) : "",
            'issued_mode' => isset($Row[87]) ? substr($connect->quote( $Row[87]), 1, -1) : "",
            'payment_type' => isset($Row[88]) ? substr($connect->quote( $Row[88]), 1, -1) : "",
            'cash' => isset($Row[89]) ? substr($connect->quote( $Row[89]), 1, -1) : "",
            'balance_amt' => isset($Row[90]) ? substr($connect->quote( $Row[90]), 1, -1) : "",
            'cash_guarantor_id' => isset($Row[91]) ? substr($connect->quote( $Row[91]), 1, -1) : "",
            'cash_guarantor_rel' => isset($Row[92]) ? substr($connect->quote( $Row[92]), 1, -1) : "",
            'closed_status' => isset($Row[93]) ? substr($connect->quote( $Row[93]), 1, -1) : "",
            'consider_level' => isset($Row[94]) ? substr($connect->quote( $Row[94]), 1, -1) : "",
            'closed_remark' => isset($Row[95]) ? substr($connect->quote( $Row[95]), 1, -1) : "",
            'closed_date' => isset($Row[96]) ? substr($connect->quote( $Row[96]), 1, -1) : ""
        );

        $dataArray['cus_id'] = strlen($dataArray['cus_id']) == 12 ? $dataArray['cus_id'] : 'Invalid';

        $cus_dataArray = ['New' => 'New', 'Existing' => 'Existing'];
        $dataArray['cus_data'] = $this->arrayItemChecker($cus_dataArray, $dataArray['cus_data']);

        $cus_exist_typeArray = ['Additional' => 'Additional', 'Renewal' => 'Renewal'];
        $cus_exist_type = $this->arrayItemChecker($cus_exist_typeArray, $dataArray['cus_exist_type']);
        $dataArray['cus_exist_type'] = ($cus_exist_type == 'Not Found') ? '' : $cus_exist_type; //cause cus_exist_type may not be available

        $dataArray['mobile1'] = strlen($dataArray['mobile1']) == 10 ? $dataArray['mobile1'] : 'Invalid';

        $dataArray['dor'] = $this->dateFormatChecker($dataArray['dor']);

        $dob = $this->dateFormatChecker($dataArray['dob']);
        $dataArray['dob'] = ($dob == 'Invalid Date') ? '' : $dob; //cause dob may not be available

        $genderArray = ['Male' => '1', 'Female' => '2', 'Others' => '3'];
        $dataArray['gender'] = $this->arrayItemChecker($genderArray, $dataArray['gender']);

        $stateArray = ['TamilNadu' => 'TamilNadu', 'Puducherry' => 'Puducherry'];
        $dataArray['state'] = $this->arrayItemChecker($stateArray, $dataArray['state']);

        $districts = ["Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kancheepuram", "Kanniyakumari", "Karur", "Madurai", "Nagapattinam", "Namakkal", "Nilgiris", "Perambalur", "Pudukottai", "Ramanathapuram", "Salem", "Sivagangai", "Thanjavur", "Theni", "Thiruvallur", "Tiruvannamalai", "Thiruvarur", "Thoothukudi", "Tiruchirappalli", "Thirunelveli", "Vellore", "Viluppuram", "Virudhunagar", "Ariyalur", "Krishnagiri", "Tiruppur", "Chengalpattu", "Kallakurichi", "Ranipet", "Tenkasi", "Tirupathur", "Mayiladuthurai"];
        $districtArray = [];
        foreach ($districts as $district) { //this is for arranging index name same as the content
            $districtArray[$district] = $district;
        }
        $dataArray['district'] = $this->arrayItemChecker($districtArray, $dataArray['district']);

        $dataArray['guarantor_adhar'] = strlen($dataArray['guarantor_adhar']) == 12 ? $dataArray['guarantor_adhar'] : 'Invalid';

        $guarantor_relationshipArray = ['Father' => 'Father', 'Mother' => 'Mother', 'Spouse' => 'Spouse'];
        $dataArray['guarantor_relationship'] = $this->arrayItemChecker($guarantor_relationshipArray, $dataArray['guarantor_relationship']);

        $dataArray['guarantor_mobile'] = strlen($dataArray['guarantor_mobile']) == 10 ? $dataArray['guarantor_mobile'] : 'Invalid';

        $maritalArray = ['Yes' => '1', 'No' => '2'];
        $dataArray['marital'] = $this->arrayItemChecker($maritalArray, $dataArray['marital']);

        $poss_typeArray = ['Due Amount' => '1', 'Due Period' => '2'];
        $dataArray['poss_type'] = $this->arrayItemChecker($poss_typeArray, $dataArray['poss_type']);

        $occupationArray = ['Govt Job' => '1', 'Pvt Job' => '2', 'Business' => '3', 'Self Employed' => '4', 'Daily wages' => '5', 'Agriculture' => '6', 'Others' => '7'];
        $occupation_type = $this->arrayItemChecker($occupationArray, $dataArray['occupation_type']);
        $dataArray['occupation_type'] = ($occupation_type == 'Not Found') ? '' : $occupation_type; //cause occupation_type may not be available

        $how_to_know_Array = ['Customer Reference' => '0', 'Advertisement' => '1', 'Promotion Activity' => '2', 'Agent Reference' => '3', 'Staff Reference' => '4', 'Other Reference' => '5'];
        $dataArray['how_to_know'] = $this->arrayItemChecker($how_to_know_Array, $dataArray['how_to_know']);

        $residential_typeArray = ['Own' => '0', 'Rental' => '1', 'Lease' => '2', 'Quarters' => '3'];
        $residential_type = $this->arrayItemChecker($residential_typeArray, $dataArray['residential_type']);
        $dataArray['residential_type'] = ($residential_type == 'Not Found') ? '' : $residential_type; //cause residential_type may not be available

        $area_confirm_typeArray = ['Resident' => '0', 'Occupation' => '1'];
        $dataArray['area_confirm_type'] = $this->arrayItemChecker($area_confirm_typeArray, $dataArray['area_confirm_type']);

        $mortgage_processArray = ['Yes' => '0', 'No' => '1'];
        $dataArray['mortgage_process'] = $this->arrayItemChecker($mortgage_processArray, $dataArray['mortgage_process']);

        $endorsement_processArray = ['Yes' => '0', 'No' => '1'];
        $dataArray['endorsement_process'] = $this->arrayItemChecker($endorsement_processArray, $dataArray['endorsement_process']);

        $profit_typeArray = ['Calculation' => '1', 'Scheme' => '2'];
        $dataArray['profit_type'] = $this->arrayItemChecker($profit_typeArray, $dataArray['profit_type']);

        $due_method_calcArray = ['Monthly' => 'Monthly', 'Weekly' => 'Weekly', 'Daily' => 'Daily'];
        $dataArray['due_method_calc'] = $this->arrayItemChecker($due_method_calcArray, $dataArray['due_method_calc']);

        $due_typeArray = ['EMI' => 'EMI', 'Interest' => 'Interest'];
        $dataArray['due_type'] = $this->arrayItemChecker($due_typeArray, $dataArray['due_type']);

        $profit_methodArray = ['Pre Interest' => 'pre_intrest', 'After Interest' => 'after_intrest'];
        $dataArray['profit_method'] = $this->arrayItemChecker($profit_methodArray, $dataArray['profit_method']);

        $due_method_schemeArray = ['Monthly' => '1', 'Weekly' => '2', 'Daily' => '3'];
        $due_method_scheme = $this->arrayItemChecker($due_method_schemeArray, $dataArray['due_method_scheme']);
        $dataArray['due_method_scheme'] = ($due_method_scheme == 'Not Found') ? '' : $due_method_scheme; //cause due_method_scheme may not be available

        $dataArray['loan_date'] = $this->dateFormatChecker($dataArray['loan_date']);

        $dataArray['due_start_from'] = $this->dateFormatChecker($dataArray['due_start_from']);
        $dataArray['maturity_month'] = $this->dateFormatChecker($dataArray['maturity_month']);

        $collection_methodArray = ['Byself' => '1', 'On Spot' => '2'];
        $dataArray['collection_method'] = $this->arrayItemChecker($collection_methodArray, $dataArray['collection_method']);

        $communicationArray = ['Phone' => '0', 'Direct' => '1'];
        $dataArray['communication'] = $this->arrayItemChecker($communicationArray, $dataArray['communication']);

        $dataArray['verification_person'] = strlen($dataArray['verification_person']) == 12 ? $dataArray['verification_person'] : 'Invalid';

        $verification_locationArray = ['On Spot' => '0', 'Customer Spot' => '1'];
        $dataArray['verification_location'] = $this->arrayItemChecker($verification_locationArray, $dataArray['verification_location']);

        $issued_modeArray = ['Split' => '0', 'Single' => '1'];
        $dataArray['issued_mode'] = $this->arrayItemChecker($issued_modeArray, $dataArray['issued_mode']);

        $payment_typeArray = ['Cash' => '0', 'Cheque' => '1', 'Account Transfer' => '2'];
        $dataArray['payment_type'] = $this->arrayItemChecker($payment_typeArray, $dataArray['payment_type']);

        $dataArray['cash_guarantor_id'] = strlen($dataArray['cash_guarantor_id']) == 12 ? $dataArray['cash_guarantor_id'] : 'Invalid';

        $closed_statusArray = ['' => '0', 'Consider' => '1', 'Waiting List' => '2', 'Block List' => '3'];
        $dataArray['closed_status'] = $this->arrayItemChecker($closed_statusArray, $dataArray['closed_status']);

        $consider_levelArray = ['Bronze' => '1', 'Silver' => '2', 'Gold' => '3', 'Platinum' => '4', 'Diamond' => '5'];
        $dataArray['consider_level'] = $this->arrayItemChecker($consider_levelArray, $dataArray['consider_level']);

        $dataArray['closed_date'] = $this->dateFormatChecker($dataArray['closed_date']);

        return $dataArray;
    }

    //Format Checking Part
    function dateFormatChecker($checkdate)
    {
        // Attempt to create a DateTime object from the provided date
        $dateTime = DateTime::createFromFormat('Y-m-d', $checkdate);

        // Check if the date is in the correct format
        if ($dateTime !== false && $dateTime->format('Y-m-d') === $checkdate) {
            // Date is in the correct format, no need to change anything
            return $checkdate;
        } else if ($checkdate == '' || preg_match("/^[A-Za-z\s]$/", $checkdate)) {
            return 'Invalid Date';
        } else {
            // Date is not in the correct format, reformat it
            $formattedDor = date('Y-m-d', strtotime($checkdate));
            return $formattedDor;
        }
    }
    function arrayItemChecker($arrayList, $arrayItem)
    {
        if (array_key_exists($arrayItem, $arrayList)) {
            $arrayItem = $arrayList[$arrayItem];
        } else {
            $arrayItem = 'Not Found';
        }
        return $arrayItem;
    }

    //Data collection from DB Part
    function getUserDetails($connect)
    {

        //get User Data
        $data['user_id'] = $_SESSION["userid"];
        $qry = $connect->query("SELECT fullname,role from user where user_id = '" . $data['user_id'] . "' ");
        $row = $qry->fetch();
        $data['user_name'] = $row['fullname'];
        if ($row['role'] == '1') {
            $data['user_type'] = 'Director';
        } elseif ($row['role'] == '2') {
            $data['user_type'] = 'Agent';
        } else {
            $data['user_type'] = 'Staff';
        }

        return $data;
    }
    function getRequestCode($connect)
    {
        $myStr = "REQ";
        $selectIC = $connect->query("SELECT req_code FROM request_creation WHERE req_code != '' ");
        if ($selectIC->rowCount() > 0) {
            $codeAvailable = $connect->query("SELECT req_code FROM request_creation WHERE req_code != '' ORDER BY req_id DESC LIMIT 1");
            while ($row = $codeAvailable->fetch()) {
                $ac2 = $row["req_code"];
            }
            $appno2 = ltrim(strstr($ac2, '-'), '-');
            $appno2 = $appno2 + 1;
            $req_code = $myStr . "-" . "$appno2";
        } else {
            $initialapp = $myStr . "-101";
            $req_code = $initialapp;
        }
        return $req_code;
    }
    function getDocumentCode($connect)
    {
        $myStr = "DOC";
        
        $codeAvailable = $connect->query("SELECT MAX(CAST(SUBSTRING_INDEX(doc_id, '-', -1) AS UNSIGNED)) AS max_number FROM acknowlegement_documentation WHERE doc_id REGEXP '^DOC-[0-9]+'");
        if ($codeAvailable->rowCount() > 0) {
            $row = $codeAvailable->fetch_assoc(); 
            $maxNumber = isset($row["max_number"]) ? (int)$row["max_number"] : 0;
            
            $nextNumber = $maxNumber + 1;
            $doc_code = $myStr . "-" . $nextNumber;
        } else {
            $initialapp = $myStr . "-101";
            $doc_code = $initialapp;
        }
        return $doc_code;
    }
    function getLoanCode($connect)
    {
        $selectIC = $connect->query("SELECT loan_id FROM in_issue WHERE loan_id != '' ");
        if ($selectIC->rowCount() > 0) {
            $codeAvailable = $connect->query("SELECT loan_id FROM in_issue WHERE (loan_id != '' or loan_id != NULL) ORDER BY id DESC LIMIT 1");
            while ($row = $codeAvailable->fetch()) {
                $ac2 = $row["loan_id"];
            }
            $loan_id = intval($ac2) + 1;
        } else {
            $initialapp = "101";
            $loan_id = $initialapp;
        }
        return $loan_id;
    }

    //Data Checking Part
    function checkCustomerData($connect, $cus_id)
    {

        $new_cus_check = $connect->query("SELECT cus_reg_id from customer_register where cus_id = '" . strip_tags($cus_id) . "' ");

        if ($new_cus_check->rowCount() == 0) {
            $response['cus_data'] = 'New';
            $response['cus_reg_id'] = '';
        } else {
            $row = $new_cus_check->fetch();
            $response['cus_data'] = 'Existing';
            $response['cus_reg_id'] = $row['cus_reg_id'];
        }
        return $response;
    }
    function getAreaId($connect, $area_name)
    {
        $query = "SELECT * FROM area_list_creation where area_name = '" . $area_name . "' ";
        $result1 = $connect->query($query) or die("Error ");
        if ($result1->rowCount() > 0) {
            $row = $result1->fetch();
            $area_id = $row["area_id"];
        } else {
            $area_id = 'Not Found';
        }
        return $area_id;
    }
    function getSubAreaId($connect, $sub_area_name, $area_id)
    {
        $query = "SELECT * FROM sub_area_list_creation where sub_area_name = '" . $sub_area_name . "' and area_id_ref = '" . $area_id . "' ";
        $result1 = $connect->query($query) or die("Error ");
        if ($result1->rowCount() > 0) {
            $row = $result1->fetch();
            $sub_area_id = $row["sub_area_id"];
        } else {
            $sub_area_id = 'Not Found';
        }
        return $sub_area_id;
    }
    function getLoanCategoryId($connect, $loan_category_name)
    {
        $query = "SELECT * FROM loan_category_creation where loan_category_creation_name = '" . $loan_category_name . "' ";
        $result1 = $connect->query($query) or die("Error ");
        if ($result1->rowCount() > 0) {
            $row = $result1->fetch();
            $loan_cat_id = $row["loan_category_creation_id"];
        } else {
            $loan_cat_id = 'Not Found';
        }
        return $loan_cat_id;
    }
    function checkSubCategory($connect, $sub_cat_name)
    {
        $query = "SELECT * FROM loan_category where sub_category_name = '" . $sub_cat_name . "' ";
        $result1 = $connect->query($query) or die("Error ");
        if ($result1->rowCount() > 0) {
            $sub_categoryCheck = 'Available';
        } else {
            $sub_categoryCheck = 'Not Found';
        }
        return $sub_categoryCheck;
    }
    function getAreaGroup($connect, $sub_area_id)
    {
        $group_name = 'Invalid';
        if ($sub_area_id != 'Not Found') {
            $qry = $connect->query("SELECT group_name FROM area_group_mapping WHERE FIND_IN_SET($sub_area_id, sub_area_id) ");
            if ($qry->rowCount() > 0) {
                $group_name = $qry->fetch()['group_name'];
            }
        }
        return $group_name;
    }
    function getAreaLine($connect, $sub_area_id)
    {
        $line_name = 'Invalid';
        if ($sub_area_id != 'Not Found') {
            $qry = $connect->query("SELECT line_name FROM area_line_mapping WHERE FIND_IN_SET( $sub_area_id, sub_area_id ) ");
            if ($qry->rowCount() > 0) {
                $line_name = $qry->fetch()['line_name'];
            }
        }
        return $line_name;
    }
    function checkAgent($connect, $agent_name)
    {
        //check if agent available
        if ($agent_name != '') { //because its not mandatory

            $query = "SELECT * FROM agent_creation where ag_name = '" . $agent_name . "' ";
            $result1 = $connect->query($query) or die("Error ");
            if ($result1->rowCount() > 0) {
                $row = $result1->fetch();
                $agentCheck = $row["ag_id"];
            } else {
                $agentCheck = 'Not Found';
            }
        } else {
            $agentCheck = '';
        }
        return $agentCheck;
    }

    function getSchemeId($connect, $scheme_name)
    {
        $qry = $connect->query("SELECT scheme_id from loan_scheme where scheme_name = '$scheme_name' ");
        if ($qry->rowCount() > 0) {
            $scheme_id = $qry->fetch()['scheme_id'];
        } else {
            $scheme_id = '';
        }
        return $scheme_id;
    }


    //Query Part
    function raiseRequest($connect, $data, $userData)
    {
        $reqQry = "INSERT INTO `request_creation`(`user_type`, `user_name`, `agent_id`, `responsible`, `remarks`, `declaration`, `req_code`, `dor`, `cus_reg_id`, `cus_id`, `cus_data`, `cus_name`, `dob`, `age`, `gender`, `state`, `district`, `taluk`, `area`, `sub_area`, `address`, `mobile1`, `mobile2`, `father_name`, `mother_name`, `marital`, `spouse_name`, `occupation_type`, `occupation`, `pic`, `loan_category`, `sub_category`, `tot_value`, `ad_amt`, `ad_perc`, `loan_amt`, `poss_type`, `due_amt`, `due_period`, `cus_status`, `prompt_remark`, `status`, `insert_login_id`, `update_login_id`, `created_date`, `updated_date` ) VALUES ( '" . $userData['user_type'] . "', '" . $userData['user_name'] . "', '" . $data['agent_id'] . "', '', '' , '' , '" . $data['req_code'] . "', '" . $data['dor'] . "', '',  '" . $data['cus_id'] . "', '" . $data['cus_data'] . "', '" . $data['cus_name'] . "', '" . $data['dob'] . "', '" . $data['age'] . "', '" . $data['gender'] . "', '" . $data['state'] . "',  '" . $data['district'] . "',  '" . $data['taluk'] . "', '" . $data['area_id'] . "', '" . $data['sub_area_id'] . "', '" . $data['address'] . "', '" . $data['mobile1'] . "', '', '" . $data['father_name'] . "', '" . $data['mother_name'] . "', '" . $data['marital'] . "', '" . $data['spouse'] . "', '" . $data['occupation_type'] . "', '" . $data['occupation_details'] . "', '', '" . $data['loan_cat_id'] . "', '" . $data['sub_category'] . "', '" . $data['tot_amt'] . "', '" . $data['adv_amt'] . "', '', '" . $data['loan_amt'] . "', '" . $data['poss_type'] . "', '" . $data['poss_due_amt'] . "', '" . $data['poss_due_period'] . "', '" . $data['cus_status'] . "', '', '0', '" . $userData['user_id'] . "','" . $userData['user_id'] . "', '" . $data['dor'] . "', '" . $data['dor'] . "'  ) ";
        $connect->query($reqQry);
        $req_id = $connect->lastInsertId();

        //store calculation category if anyone present(min 1)
        $req_cat_qry1 = "INSERT INTO `request_category_info`( `req_ref_id`, `category_info`) VALUES ('$req_id','" . $data['cal_category1'] . "')";
        $req_cat_qry2 = "INSERT INTO `request_category_info`( `req_ref_id`, `category_info`) VALUES ('$req_id','" . $data['cal_category2'] . "')";
        $req_cat_qry3 = "INSERT INTO `request_category_info`( `req_ref_id`, `category_info`) VALUES ('$req_id','" . $data['cal_category3'] . "')";
        if ($data['cal_category1'] != '') {
            $connect->query($req_cat_qry1);
        }
        if ($data['cal_category2'] != '') {
            $connect->query($req_cat_qry2);
        }
        if ($data['cal_category3'] != '') {
            $connect->query($req_cat_qry3);
        }


        if ($data['cus_data'] == 'New') {
            $crQry = "INSERT INTO `customer_register`( `req_ref_id`, `cus_id`, `customer_name`, `dob`, `age`, `gender`, `blood_group`, `state`, `district`, `taluk`, `area`, `sub_area`, `address`, `mobile1`, `mobile2`, `father_name`, `mother_name`, `marital`, `spouse`, `occupation_type`, `occupation`, `pic`, `how_to_know`, `loan_count`, `first_loan_date`, `travel_with_company`, `monthly_income`, `other_income`, `support_income`, `commitment`, `monthly_due_capacity`, `loan_limit`, `about_customer`, `residential_type`, `residential_details`, `residential_address`, `residential_native_address`, `occupation_info_occ_type`, `occupation_details`, `occupation_income`, `occupation_address`, `dow`, `abt_occ`, `area_confirm_type`, `area_confirm_state`, `area_confirm_district`, `area_confirm_taluk`, `area_confirm_area`, `area_confirm_subarea`, `area_group`, `area_line`, `cus_status`, `create_time`,`updated_date` ) VALUES ('$req_id','" . $data['cus_id'] . "', '" . $data['cus_name'] . "', '" . $data['dob'] . "', '" . $data['age'] . "', '" . $data['gender'] . "', '', '" . $data['state'] . "',  '" . $data['district'] . "',  '" . $data['taluk'] . "', '" . $data['area_id'] . "', '" . $data['sub_area_id'] . "', '" . $data['address'] . "', '" . $data['mobile1'] . "', '', '" . $data['father_name'] . "', '" . $data['mother_name'] . "', '" . $data['marital'] . "', '" . $data['spouse'] . "', '" . $data['occupation_type'] . "', '" . $data['occupation_details'] . "', '','" . $data['how_to_know'] . "','" . $data['loan_count'] . "','" . $data['first_loan_date'] . "','" . $data['travel_with_company'] . "','" . $data['monthly_income'] . "','" . $data['other_income'] . "','" . $data['support_income'] . "','" . $data['commitment'] . "','" . $data['monthly_due_capacity'] . "','" . $data['loan_limit'] . "','" . $data['about_customer'] . "','" . $data['residential_type'] . "','" . $data['residential_details'] . "','" . $data['residential_address'] . "','" . $data['residential_native_address'] . "','" . $data['occupation_type'] . "','" . $data['occupation_details'] . "', '', '', '', '','" . $data['area_confirm_type'] . "', '" . $data['state'] . "',  '" . $data['district'] . "',  '" . $data['taluk'] . "', '" . $data['area_id'] . "', '" . $data['sub_area_id'] . "', '" . $data['area_group'] . "', '" . $data['area_line'] . "', '" . $data['cus_status'] . "', '" . $data['dor'] . "', '" . $data['dor'] . "' )";

            $connect->query($crQry);
            $data['cus_reg_id'] = $connect->lastInsertId();
        }

        $updateQry = "UPDATE `request_creation` SET `cus_reg_id`='" . $data['cus_reg_id'] . "' where req_id = '$req_id' ";
        $connect->query($updateQry);

        return $req_id;
    }

    function verificationTables($connect, $data, $userData, $req_id)
    {
        $insert_inv = $connect->query("INSERT INTO in_verification (`req_id`,`user_type`, `user_name`, `agent_id`, `responsible`, `remarks`, `declaration`,`req_code`, `dor`,`cus_reg_id`, `cus_id`, `cus_data`, `cus_name`, `dob`, `age`, `gender`, `state`, `district`, `taluk`, `area`, `sub_area`, `address`,`mobile1`, `mobile2`, `father_name`, `mother_name`, `marital`, `spouse_name`, `occupation_type`, `occupation`, `pic`, `loan_category`, `sub_category`, `tot_value`, `ad_amt`, `ad_perc`, `loan_amt`, `poss_type`, `due_amt`, `due_period`, `cus_status`,`prompt_remark`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date` ) SELECT * from request_creation where req_id = '" . $req_id . "' ");

        $qry = $connect->query("SELECT id FROM verification_family_info WHERE TRIM(LOWER(cus_id)) = TRIM(LOWER('" . $data['cus_id'] . "')) AND TRIM(LOWER(famname)) = TRIM(LOWER('" . $data['guarantor_name'] . "')) AND TRIM(LOWER(relationship)) = TRIM(LOWER('" . $data['guarantor_relationship'] . "')) ");
        if($qry -> rowCount() > 0){
            $last_id = $qry->fetch()['id'];
        } else{
            $insert_fam = $connect->query("INSERT INTO `verification_family_info`(`cus_id`,`req_id`, `famname`, `relationship`, `relation_age`, `relation_aadhar`, `relation_Mobile`, `relation_Occupation`, `relation_Income`, `relation_Blood`) VALUES ('" . $data['cus_id'] . "','$req_id','" . $data['guarantor_name'] . "','" . $data['guarantor_relationship'] . "','" . $data['guarantor_age'] . "','" . $data['guarantor_adhar'] . "','" . $data['guarantor_mobile'] . "','" . $data['guarantor_occupation'] . "','" . $data['guarantor_income'] . "','')");
            $last_id = $connect -> lastInsertId(); 
        }
        $guarantor_id = $last_id;

        $insert_cp = $connect->query("INSERT INTO `customer_profile`( `req_id`, `cus_id`, `cus_name`, `gender`, `dob`, `age`, `blood_group`, `mobile1`, `mobile2`, `whatsapp`,`cus_pic`, `guarentor_name`, `guarentor_relation`, `guarentor_photo`, `cus_type`, `cus_exist_type`, `residential_type`, `residential_details`, `residential_address`, `residential_native_address`, `occupation_type`, `occupation_details`, `occupation_income`, `occupation_address`, `dow`, `abt_occ`, `area_confirm_type`, `area_confirm_state`, `area_confirm_district`, `area_confirm_taluk`, `area_confirm_area`, `area_confirm_subarea` , `area_group`, `area_line`, `cus_status`, `insert_login_id`,`created_date`,`updated_date`) VALUES('" . strip_tags($req_id) . "','" . strip_tags($data['cus_id']) . "','" . strip_tags($data['cus_name']) . "','" . strip_tags($data['gender']) . "','" . strip_tags($data['dob']) . "', '" . strip_tags($data['age']) . "', '', '" . strip_tags($data['mobile1']) . "','','" . strip_tags($data['mobile1']) . "','','" . strip_tags($guarantor_id) . "', '" . strip_tags($data['guarantor_relationship']) . "', '', '" . strip_tags($data['cus_data']) . "','" . strip_tags($data['cus_exist_type']) . "','" . strip_tags($data['residential_type']) . "','" . strip_tags($data['residential_details']) . "','" . strip_tags($data['residential_address']) . "', '', '" . strip_tags($data['occupation_type']) . "','" . strip_tags($data['occupation_details']) . "','','','','','" . strip_tags($data['area_confirm_type']) . "','" . strip_tags($data['state']) . "','" . strip_tags($data['district']) . "','" . strip_tags($data['taluk']) . "','" . strip_tags($data['area_id']) . "','" . strip_tags($data['sub_area_id']) . "','" . strip_tags($data['group_name']) . "','" . strip_tags($data['line_name']) . "','10','" . $userData['user_id'] . "','" . $data['dor'] . "','" . $data['dor'] . "' )");
        $cus_profile_id = $connect->lastInsertId();

        $insert_vdoc = $connect->query("INSERT INTO `verification_documentation`( `req_id`, `cus_id_doc`, `customer_name`, `cus_profile_id`, `mortgage_process`, `endorsement_process`, `cus_status`, `insert_login_id`,`update_login_id`,`created_date`,`updated_date`) VALUES('" . strip_tags($req_id) . "','" . strip_tags($data['cus_id']) . "','" . strip_tags($data['cus_name']) . "','" . strip_tags($cus_profile_id) . "', '" . strip_tags($data['mortgage_process']) . "', '" . strip_tags($data['endorsement_process']) . "', '11','" . $userData['user_id'] . "','" . $userData['user_id'] . "','" . $data['dor'] . "','" . $data['dor'] . "' )");

        //checking verification person if its customer or not //if yes directly can place cus_id into table else need to set the fam_id from veri_fam_info
        $verification_person = $data['verification_person'] == $data['cus_id'] ? $data['verification_person'] : $guarantor_id;

        $insert_vlc = $connect->query("INSERT INTO verification_loan_calculation (`req_id`, `cus_id_loan`, `cus_name_loan`,`cus_data_loan`, `mobile_loan`, `pic_loan`, `loan_category`, `sub_category`, `tot_value`, `ad_amt`, `loan_amt`, `profit_type`, `due_method_calc`, `due_type`, `profit_method`, `calc_method`, `due_method_scheme`, `profit_method_scheme`,`day_scheme`, `scheme_name`,  `int_rate`, `due_period`, `doc_charge`, `proc_fee`, `loan_amt_cal`, `principal_amt_cal`, `int_amt_cal`, `tot_amt_cal`, `due_amt_cal`, `doc_charge_cal`, `proc_fee_cal`, `net_cash_cal`, `due_start_from`, `maturity_month`, `collection_method`,  `communication`, `com_audio`, `verification_person`, `verification_location`,`verify_remark`, `cus_status`, `insert_login_id`,`update_login_id`,`create_date`, `update_date`)  VALUES ('" . strip_tags($req_id) . "', '" . strip_tags($data['cus_id']) . "',  '" . strip_tags($data['cus_name']) . "', '" . strip_tags($data['cus_data']) . "','" . strip_tags($data['mobile1']) . "', '', '" . strip_tags($data['loan_cat_id']) . "',  '" . strip_tags($data['sub_category']) . "', '" . strip_tags($data['tot_amt']) . "', '" . strip_tags($data['adv_amt']) . "', '" . strip_tags($data['loan_amt']) . "', '" . strip_tags($data['profit_type']) . "',  '" . strip_tags($data['due_method_calc']) . "', '" . strip_tags($data['due_type']) . "', '" . strip_tags($data['profit_method']) . "', '', '" . strip_tags($data['due_method_scheme']) . "', '', '', '" . strip_tags($data['scheme_id']) . "', '" . strip_tags($data['int_rate']) . "', '" . strip_tags($data['due_period']) . "', '" . strip_tags($data['doc_charge']) . "',  '" . strip_tags($data['proc_fee']) . "', '" . strip_tags($data['loan_amt_cal']) . "', '" . strip_tags($data['principal_amt_cal']) . "', '" . strip_tags($data['int_amt_cal']) . "', '" . strip_tags($data['tot_amt_cal']) . "',  '" . strip_tags($data['due_amt_cal']) . "', '" . strip_tags($data['doc_charge_cal']) . "', '" . strip_tags($data['proc_fee_cal']) . "', '" . strip_tags($data['net_cash_cal']) . "', '" . strip_tags($data['due_start_from']) . "',  '" . strip_tags($data['maturity_month']) . "', '" . strip_tags($data['collection_method']) . "', '" . strip_tags($data['communication']) . "','','" . strip_tags($verification_person) . "','" . strip_tags($data['verification_location']) . "', '',12, '" . $userData['user_id'] . "','" . $userData['user_id'] . "','" . $data['dor'] . "','" . $data['dor'] . "'  ) ");

        $loan_cal_id = $connect->lastInsertId();

        $categoryArray = [$data['cal_category1'], $data['cal_category2'], $data['cal_category3']];

        foreach ($categoryArray as $category) {
            if (!empty($category)) {
                $insert_vcat = $connect->query("INSERT INTO `verif_loan_cal_category`(`req_id`, `loan_cal_id`, `category`) VALUES ('" . strip_tags($req_id) . "','" . strip_tags($loan_cal_id) . "','" . strip_tags($category) . "' )");
            }
        }
    }

    function approvalTables($connect, $req_id)
    {
        $insert_inap = $connect->query("INSERT INTO in_approval (`req_id`, `cus_id`, `cus_status`, `status`,`insert_login_id`,`update_login_id` ) SELECT req_id,cus_id,cus_status,status,insert_login_id,update_login_id from in_verification where req_id = '" . $req_id . "' ");
    }

    function acknowledgementTables($connect, $data, $req_id, $userData)
    {
        $insert_inack = $connect->query("INSERT INTO `in_acknowledgement`(`req_id`, `cus_id`, `cus_status`, `status`, `insert_login_id`,`update_login_id`,`created_on`,`updated_date`) SELECT req_id,cus_id,cus_status,status,insert_login_id,update_login_id,created_date,'" . $data['loan_date'] . "' from in_verification where req_id = '" . $req_id . "' ") or die('Error in in_acknowledgement');
        $ack_id = $connect->lastInsertId();
        $qry = $connect->query("UPDATE in_acknowledgement set inserted_user = '" . $userData['user_id'] . "', inserted_date = '" . $data['dor'] . "' where id = '$ack_id' ");

        $insert_ackcp = $connect->query("INSERT INTO `acknowlegement_customer_profile`(`id`, `req_id`, `cus_id`, `cus_name`, `gender`, `dob`, `age`, `blood_group`, `mobile1`, `mobile2`, `whatsapp`, `cus_pic`, `guarentor_name`, `guarentor_relation`, `guarentor_photo`, `cus_type`, `cus_exist_type`, `residential_type`, `residential_details`, `residential_address`, `residential_native_address`, `occupation_type`, `occupation_details`, `occupation_income`, `occupation_address`,`dow`,`abt_occ`, `area_confirm_type`, `area_confirm_state`, `area_confirm_district`, `area_confirm_taluk`, `area_confirm_area`, `area_confirm_subarea`,`latlong`, `area_group`, `area_line`, `communication`, `com_audio`, `verification_person`, `verification_location`, `cus_status`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) SELECT * FROM `customer_profile` WHERE `req_id`='$req_id' ") or die('Error in acknowlegement_customer_profile');

        $insert_ackdoc = $connect->query("INSERT INTO `acknowlegement_documentation`(`id`, `req_id`, `cus_id_doc`, `customer_name`, `cus_profile_id`, `mortgage_process`, `Propertyholder_type`, `Propertyholder_name`, `Propertyholder_relationship_name`, `doc_property_relation`, `doc_property_type`, `doc_property_measurement`, `doc_property_location`, `doc_property_value`, `mortgage_name`, `mortgage_dsgn`, `mortgage_nuumber`, `reg_office`, `mortgage_value`, `mortgage_document`, `mortgage_document_upd`, `mortgage_document_pending`, `endorsement_process`, `owner_type`, `owner_name`, `ownername_relationship_name`, `en_relation`, `vehicle_type`, `vehicle_process`, `en_Company`, `en_Model`, `vehicle_reg_no`, `endorsement_name`, `en_RC`, `Rc_document_upd`, `Rc_document_pending`, `en_Key`,`document_name`, `document_details`, `document_type`,  `document_holder`, `docholder_name`, `docholder_relationship_name`, `doc_relation`, `cus_status`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) SELECT `id`, `req_id`, `cus_id_doc`, `customer_name`, `cus_profile_id`, `mortgage_process`, `Propertyholder_type`, `Propertyholder_name`, `Propertyholder_relationship_name`, `doc_property_relation`, `doc_property_type`, `doc_property_measurement`, `doc_property_location`, `doc_property_value`, `mortgage_name`, `mortgage_dsgn`, `mortgage_nuumber`, `reg_office`, `mortgage_value`, `mortgage_document`, `mortgage_document_upd`, `mortgage_document_pending`, `endorsement_process`, `owner_type`, `owner_name`, `ownername_relationship_name`, `en_relation`, `vehicle_type`, `vehicle_process`, `en_Company`, `en_Model`, `vehicle_reg_no`, `endorsement_name`, `en_RC`, `Rc_document_upd`, `Rc_document_pending`, `en_Key`,`document_name`, `document_details`, `document_type`, `document_holder`, `docholder_name`, `docholder_relationship_name`, `doc_relation`, `cus_status`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date` FROM `verification_documentation` WHERE `req_id` ='$req_id'") or die('Error in acknowlegement_documentation');

        //Design changed. doc id insert removed from verification documentation, doc id generate in acknowledgement documentation updating here.
        $connect->query("UPDATE acknowledgement_documentation SET doc_id = '" . $data['doc_code'] . "' WHERE req_id = '$req_id' ") or die('Error in acknowlegement_documentation update.');

        $insert_acklc = $connect->query("INSERT INTO `acknowlegement_loan_calculation`(`loan_cal_id`, `req_id`, `cus_id_loan`, `cus_name_loan`, `cus_data_loan`, `mobile_loan`, `pic_loan`, `loan_category`, `sub_category`, `tot_value`, `ad_amt`, `loan_amt`, `profit_type`, `due_method_calc`, `due_type`, `profit_method`, `calc_method`, `due_method_scheme`,`profit_method_scheme`, `day_scheme`, `scheme_name`, `int_rate`, `due_period`, `doc_charge`, `proc_fee`, `loan_amt_cal`, `principal_amt_cal`, `int_amt_cal`, `tot_amt_cal`, `due_amt_cal`, `doc_charge_cal`, `proc_fee_cal`, `net_cash_cal`, `due_start_from`, `maturity_month`, `collection_method`,`communication`, `com_audio`, `verification_person`, `verification_location`, `verify_remark`,`cus_status`, `insert_login_id`, `update_login_id`, `create_date`, `update_date`) SELECT * FROM `verification_loan_calculation` WHERE `req_id`='$req_id' ") or die('Error in acknowlegement_loan_calculation');

        $insert_ackcat = $connect->query("INSERT INTO `acknowledgement_loan_cal_category`( `req_id`, `loan_cal_id`, `category`) SELECT `req_id`, `loan_cal_id`, `category` FROM `verif_loan_cal_category` WHERE `req_id`='$req_id'") or die('Error in acknowledgement_loan_cal_category');
    }

    function loanIssueTables($connect, $data, $userData, $req_id)
    {
        $insert_ii = $connect->query("INSERT INTO `in_issue`(`loan_id`,`req_id`, `cus_id`, `cus_status`, `status`, `insert_login_id`,`update_login_id`, `created_date`, `updated_date`)  SELECT '" . $data['loan_id'] . "', req_id,cus_id,cus_status,status,insert_login_id,update_login_id,created_date,'" . $data['loan_date'] . "' from in_verification where req_id = '" . $req_id . "' ");
        $ii_id = $connect->lastInsertId();
        $qry = $connect->query("UPDATE in_issue set inserted_user = '" . $userData['user_id'] . "', inserted_date = '" . $data['dor'] . "' where `id` = '$ii_id' ");

        $insert_dt = $connect->query("INSERT INTO `document_track`(`req_id`, `cus_id`, `track_status`, `insert_login_id`, `created_date`,`updated_date`) VALUES('" . strip_tags($req_id) . "','" . strip_tags($data['cus_id']) . "','1','" . $userData['user_id'] . "', '" . $data['dor'] . "','" . $data['dor'] . "' ) ");

        if ($data['agent_id'] == '') {

            $insert_li = $connect->query("INSERT INTO `loan_issue`( `req_id`, `cus_id`, `issued_to`, `agent_id`, `issued_mode`, `payment_type`, `cash`,`bank_id`, `cheque_no`, `cheque_value`, `cheque_remark`, `transaction_id`, `transaction_value`, `transaction_remark`, `balance_amount`,`loan_amt`, `net_cash`,`cash_guarentor_name`,`relationship`, `status`, `insert_login_id`, `created_date`)  VALUES('" . strip_tags($req_id) . "','" . strip_tags($data['cus_id']) . "','" . strip_tags($data['issued_to']) . "','','" . strip_tags($data['issued_mode']) . "', '" . strip_tags($data['payment_type']) . "', '" . strip_tags($data['cash']) . "','', '','','','','', '', '0', '" . strip_tags($data['loan_amt_cal']) . "','" . strip_tags($data['net_cash_cal']) . "','" . strip_tags($data['cash_guarantor_id']) . "','" . strip_tags($data['cash_guarantor_rel']) . "','0','" . $userData['user_id'] . "', '" . $data['loan_date'] . "' )");
        } else {
            $insert_li = $connect->query("INSERT INTO `loan_issue` (`req_id`, `cus_id`, `issued_to`, `agent_id`, `cash`, `balance_amount`, `loan_amt`, `net_cash`, `insert_login_id`,`created_date`) VALUES ('$req_id', '" . $data['cus_id'] . "', 'Agent', '" . $data['agent_id'] . "', '" . $data['cash'] . "', '0', '" . $data['loan_amt_cal'] . "', '" . $data['net_cash_cal'] . "', '" . $userData['user_id'] . "', '" . $data['loan_date'] . "') ");
        }

        $current_date = date('Y-m-d');
        $connect->query(" INSERT INTO `customer_status`( `req_id`, `cus_id`, `sub_status`, `payable_amnt`, `bal_amnt`, `insert_login_id`, `created_date`) VALUES ('$req_id', '".$data['cus_id']."', 'Current', '" . strip_tags($data['tot_amt_cal']) . "', '" . strip_tags($data['tot_amt_cal']) . "', '" . $userData['user_id'] . "', '$current_date' ) ");
    }
    function closedTables($connect, $data, $userData, $req_id)
    {
        $connect->query("INSERT INTO `closed_status`( `req_id`, `cus_id`, `closed_sts`, `consider_level`, `remark`,`cus_sts`,`insert_login_id`,`created_date`) VALUES ('" . strip_tags($req_id) . "','" . strip_tags($data['cus_id']) . "','" . strip_tags($data['closed_status']) . "','" . strip_tags($data['consider_level']) . "','" . strip_tags($data['closed_remark']) . "', '" . $data['cus_status'] . "'," . $userData['user_id'] . ",'" . $data['closed_date'] . "' )");

        $connect->query("UPDATE request_creation SET updated_date = " . $data['closed_date'] . " WHERE req_id = '" . $req_id . "' ");
        $connect->query("UPDATE in_verification SET updated_date = " . $data['closed_date'] . " WHERE req_id = '" . $req_id . "' ");
        $connect->query("UPDATE in_acknowledgement SET updated_date = " . $data['closed_date'] . " WHERE req_id = '" . $req_id . "' ");
        $connect->query("UPDATE in_approval SET updated_date = " . $data['closed_date'] . " WHERE req_id = '" . $req_id . "' ");

        $connect->query("INSERT INTO `document_track`(`req_id`, `cus_id`, `track_status`, `insert_login_id`, `created_date`) VALUES('" . strip_tags($req_id) . "','" . strip_tags($data['cus_id']) . "','3'," . $userData['user_id'] . ", '" . $data['closed_date'] . "') ");
    }

    function NOCTables($connect, $data, $userData, $req_id)
    {
        $connect->query("INSERT INTO `noc`(`req_id`,`cus_id`, `noc_date`, `noc_member`, `mem_name`, `cus_status`, `insert_login_id`, `created_date`) VALUES('$req_id'," . $data['cus_id'] . ", '" . $data['closed_date'] . "','1'," . $data['cus_name'] . ",'" . $data['cus_status'] . "'," . $userData['user_id'] . "," . $data['closed_date'] . "') ");
    }


    //Error Handling Part
    function handleError($data)
    {
        $errcolumns = array();

        if ($data['dor'] == 'Invalid Date') {
            $errcolumns[] = 'Date of Request';
        }

        if ($data['cus_id'] == 'Invalid') {
            $errcolumns[] = 'Customer ID';
        }

        if ($data['cus_data'] == 'Not Found') {
            $errcolumns[] = 'Customer Data';
        }

        if ($data['cus_data'] == 'Existing' && (!preg_match('/^[A-Za-z]+$/', $data['cus_exist_type']) || $data['cus_exist_type'] == '')) {
            $errcolumns[] = 'Customer Existence Type';
        }

        if ($data['cus_name'] == '') {
            $errcolumns[] = 'Customer Name';
        }

        // if ($data['dob'] == 'Invalid Date') {
        //     $errcolumns[] = 'Date of Birth';
        // }

        // if (!preg_match('/^[0-9]+$/', $data['age'])) {
        //     $errcolumns[] = 'Age';
        // }

        if ($data['gender'] == 'Not Found') {
            $errcolumns[] = 'Gender';
        }

        if ($data['state'] == 'Not Found') {
            $errcolumns[] = 'State';
        }

        if ($data['district'] == 'Not Found') {
            $errcolumns[] = 'District';
        }

        if ($data['area_id'] == 'Not Found') {
            $errcolumns[] = 'Area ID';
        }

        if ($data['sub_area_id'] == 'Not Found') {
            $errcolumns[] = 'Sub Area ID';
        }

        if ($data['address'] == '') {
            $errcolumns[] = 'Address';
        }

        if ($data['mobile1'] == 'Invalid') {
            $errcolumns[] = 'Mobile Number';
        }

        if ($data['father_name'] == '') {
            $errcolumns[] = 'Father Name';
        }

        if ($data['mother_name'] == '') {
            $errcolumns[] = 'Mother Name';
        }

        if ($data['marital'] == 'Not Found') {
            $errcolumns[] = 'Marital Status';
        }

        if ($data['marital'] == '1' && $data['spouse'] == '') {
            $errcolumns[] = 'Spouse Name';
        }

        if ($data['guarantor_name'] == '') {
            $errcolumns[] = 'Guarantor Name';
        }

        if ($data['guarantor_adhar'] == 'Invalid') {
            $errcolumns[] = 'Guarantor Aadhar';
        }

        if (!preg_match('/^[0-9]+$/', $data['guarantor_age'])) {
            $errcolumns[] = 'Guarantor Age';
        }

        if ($data['guarantor_mobile'] == 'Invalid') {
            $errcolumns[] = 'Guarantor Mobile Number';
        }

        if (!preg_match('/^[A-Za-z0-9]+$/', $data['guarantor_occupation'])) {
            $errcolumns[] = 'Guarantor Occupation';
        }

        if (!preg_match('/^[0-9]+$/', $data['guarantor_income'])) {
            $errcolumns[] = 'Guarantor Income';
        }

        if ($data['loan_cat_id'] == 'Not Found') {
            $errcolumns[] = 'Loan Category ID';
        }

        if ($data['sub_categoryCheck'] == 'Not Found') {
            $errcolumns[] = 'Sub Category Check';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['tot_amt'])) {
            $errcolumns[] = 'Total Amount';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['adv_amt'])) {
            $errcolumns[] = 'Advance Amount';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['loan_amt'])) {
            $errcolumns[] = 'Loan Amount';
        }

        if ($data['poss_type'] == 'Not Found') {
            $errcolumns[] = 'Possibility Type';
        }

        if ($data['poss_type'] == '1' && !preg_match('/^\d+(\.\d{1,2})?$/', $data['poss_due_amt'])) {
            $errcolumns[] = 'Possibility Due Amount';
        }

        if ($data['poss_type'] == '2' && !preg_match('/^\d+(\.\d{1,2})?$/', $data['poss_due_period'])) {
            $errcolumns[] = 'Possibility Due Period';
        }

        if ($data['cal_category1'] == '' && $data['cal_category2'] == '' && $data['cal_category3'] == '') {
            $errcolumns[] = 'Calculation Categories';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['loan_limit'])) {
            $errcolumns[] = 'Loan Limit';
        }

        // Condition 1
        if ($data['area_confirm_type'] != 'Not Found') {
            // Subcondition 1.1
            if ($data['area_confirm_type'] == '0') {
                if (
                    $data['residential_type'] == ''
                    || $data['residential_details'] == ''
                    || $data['residential_address'] == ''
                ) {
                    $errcolumns[] = 'Residential Type or Details or Address';
                }
            }
        } else {
            $errcolumns[] = 'Area Confirm Type';
        }

        if ($data['occupation_type'] == 'Not Found') {
            $errcolumns[] = 'Occupation Type';
        }

        if ($data['occupation_details'] == '') {
            $errcolumns[] = 'Occupation Details';
        }

        // Condition 2
        if ($data['sub_area_id'] != 'Not Found' && $data['group_name'] == 'Invalid') {
            $errcolumns[] = 'Group Name';
        }

        // Condition 3
        if ($data['sub_area_id'] != 'Not Found' && $data['line_name'] == 'Invalid') {
            $errcolumns[] = 'Line Name';
        }

        // Condition 4
        if ($data['mortgage_process'] == 'Not Found') {
            $errcolumns[] = 'Mortgage Process';
        }

        // Condition 5
        if ($data['endorsement_process'] == 'Not Found') {
            $errcolumns[] = 'Endorsement Process';
        }

        // Condition 6
        if ($data['loan_date'] == 'Invalid Date') {
            $errcolumns[] = 'Loan Date';
        }

        // Condition 7
        if ($data['profit_type'] != 'Not Found') {
            // Subcondition 7.1
            if ($data['profit_type'] == '1') {
                if (
                    $data['due_method_calc'] == 'Not Found'
                    || $data['due_type'] == 'Not Found'
                    || $data['profit_method'] == 'Not Found'
                ) {
                    $errcolumns[] = 'Due Method Calc or Due Type or Profit Method';
                }
            }

            // Subcondition 7.2
            if ($data['profit_type'] == '2') {
                if ($data['due_method_scheme'] == '' || $data['scheme_id'] == '') {
                    $errcolumns[] = 'Due Method Scheme or Scheme Name';
                }
            }
        } else {
            $errcolumns[] = 'Profit Type';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['int_rate'])) {
            $errcolumns[] = 'Interest Rate';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['due_period'])) {
            $errcolumns[] = 'Due Period';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['doc_charge'])) {
            $errcolumns[] = 'Document Charge';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['proc_fee'])) {
            $errcolumns[] = 'Processing Fee';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['loan_amt_cal'])) {
            $errcolumns[] = 'Loan Amount Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['principal_amt_cal'])) {
            $errcolumns[] = 'Principal Amount Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['int_amt_cal'])) {
            $errcolumns[] = 'Interest Amount Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['tot_amt_cal'])) {
            $errcolumns[] = 'Total Amount Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['due_amt_cal'])) {
            $errcolumns[] = 'Due Amount Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['doc_charge_cal'])) {
            $errcolumns[] = 'Document Charge Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['proc_fee_cal'])) {
            $errcolumns[] = 'Processing Fee Calculation';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['net_cash_cal'])) {
            $errcolumns[] = 'Net Cash Calculation';
        }

        if ($data['due_start_from'] == 'Invalid Date') {
            $errcolumns[] = 'Due Start From';
        }

        if ($data['maturity_month'] == 'Invalid Date') {
            $errcolumns[] = 'Maturity Month';
        }

        if ($data['collection_method'] == 'Not Found') {
            $errcolumns[] = 'Collection Method';
        }

        if ($data['communication'] == 'Not Found') {
            $errcolumns[] = 'Communication';
        }

        if ($data['verification_person'] == 'Invalid') {
            $errcolumns[] = 'Verification Person';
        }

        if ($data['verification_location'] == 'Not Found') {
            $errcolumns[] = 'Verification Location';
        }

        if ($data['issued_to'] == '') {
            $errcolumns[] = 'Issued To';
        }

        if ($data['agent_id'] == 'Not Found') {
            $errcolumns[] = 'Agent ID';
        }

        if ($data['issued_mode'] == 'Not Found') {
            $errcolumns[] = 'Issued Mode';
        }

        if ($data['payment_type'] == 'Not Found') {
            $errcolumns[] = 'Payment Type';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['cash'])) {
            $errcolumns[] = 'Cash';
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['balance_amt'])) {
            $errcolumns[] = 'Balance Amount';
        }

        if ($data['cash_guarantor_id'] == 'Invalid') {
            $errcolumns[] = 'Cash Guarantor ID';
        }

        if (!preg_match('/^[A-Za-z]+$/', $data['cash_guarantor_rel'])) {
            $errcolumns[] = 'Cash Guarantor Relationship';
        }

        if ($data['closed_status'] != '0') { //0 means closed status is empty in excel so no need to verify others

            if ($data['closed_status'] == 'Not Found') {
                $errcolumns[] = 'Closed Status';
            }
            if ($data['closed_status'] == '1') {

                if ($data['consider_level']  == 'Not Found') {
                    $errcolumns[] = 'Consider Level';
                }

                if ($data['closed_remark'] == '') {
                    $errcolumns[] = 'Closed Remark';
                }

                if ($data['closed_date'] == 'Invalid Date') {
                    $errcolumns[] = 'Closed Date';
                }
            }
        }

        return $errcolumns;
    }
}
