<?php
session_start();
include('../ajaxconfig.php');

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}

// if(isset($_POST['req_id'])){
//     $req_id = $_POST['req_id'];
// }
//****************************************************************************************************************************************
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$where = "";
if (isset($_POST['start'])) {
    $start = $_POST['start'];
    $end = $_POST['end'];

    $where = "AND (cus_status >= $start and cus_status <= $end)"; 
}

$req_arr = array();
$qry = $connect->query("SELECT req_id FROM in_issue where cus_id = $cus_id $where ORDER BY CAST(req_id AS UNSIGNED) ASC "); // and (cus_status < 21 and cus_status >= 14)"); //and (cus_status = 20)
while ($row = $qry->fetch()) {
    $req_arr[] = $row['req_id'];
}


//get Total amt from ack loan calculation (For monthly interest total amount will not be there, so take principals)*
//get Paid amt from collection table if nothing paid show 0*
//balance amount is Total amt - paid amt*
//get Due amt from ack loan calculation*
//get Pending amt from collection based on last entry against request id (Due amt - paid amt)
//get Payable amt by adding pending and due amount
//get penalty, if due date exceeded put the penalty percentage to the due amt
//get collection charges from collection charges table if exists else 0
$loan_arr = array();
$coll_arr = array();
$response = array(); //Final array to return

$req_id = 0;
$i = 0;
foreach ($req_arr as $req_id) {
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


        if ($loan_arr['due_amt_cal'] == '' || $loan_arr['due_amt_cal'] == null) {
            //(For monthly interest Due amount will not be there, so take interest)
            $response['due_amt'] = intVal($loan_arr['int_amt_cal']);
        } else {
            $response['due_amt'] = intVal($loan_arr['due_amt_cal']); //Due amount will remain same
        }
    }
    $coll_arr = array();
    $result = $connect->query("SELECT * FROM `collection` WHERE req_id ='" . $req_id . "' ");
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
        $response['pre_closure'] = $pre_closure;

        //total amount subracted by total paid amount and subracted with pre closure amount will be balance to be paid
        $response['balance'] = $response['total_amt'] - $response['total_paid'] - $pre_closure;

        if ($loan_arr['loan_type'] == 'interest') {
            $response['due_amt'] = calculateNewInterestAmt($loan_arr, $response);
        }

        $response = calculateOthers($loan_arr, $response, $connect, $req_id);
    } else {
        //If collection table dont have rows means there is no payment against that request, so total paid will be 0
        $response['total_paid'] = 0;
        $response['total_paid_int'] = 0;
        $response['pre_closure'] = 0;

        //If in collection table, there is no payment means balance amount still remains total amount
        $response['balance'] = $response['total_amt'];

        if ($loan_arr['loan_type'] == 'interest') {
            $response['due_amt'] = calculateNewInterestAmt($loan_arr, $response);
        }

        $response = calculateOthers($loan_arr, $response, $connect, $req_id);
    }
    //To get the collection charges
    $result = $connect->query("SELECT SUM(coll_charge) as coll_charge FROM `collection_charges` WHERE req_id = '" . $req_id . "' ");
    $row = $result->fetch();
    if ($row['coll_charge'] != null) {

        $coll_charges = $row['coll_charge'];

        $result = $connect->query("SELECT SUM(coll_charge_track) as coll_charge_track,SUM(coll_charge_waiver) as coll_charge_waiver FROM `collection` WHERE req_id = '" . $req_id . "' ");
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $coll_charge_track = $row['coll_charge_track'];
            $coll_charge_waiver = $row['coll_charge_waiver'];
        } else {
            $coll_charge_track = 0;
            $coll_charge_waiver = 0;
        }

        $response['coll_charge'] = $coll_charges - $coll_charge_track - $coll_charge_waiver;
    } else {
        $response['coll_charge'] = 0;
    }

    //Pending Check
    if ($response['pending'] > 0 && $response['count_of_month'] != 0) {
        $response['pending_customer'][$i] = true;
    } else {
        $response['pending_customer'][$i] = false;
    }
    //OD check
    if ($response['od'] == true) {
        $response['od_customer'][$i] = true;
    } else {
        $response['od_customer'][$i] = false;
    }

    //Due nill Check
    if ($response['due_nil'] == true) {
        $response['due_nil_customer'][$i] = true;
    } else {
        $response['due_nil_customer'][$i] = false;
    }

    //If balance equals to zero, then it should be moved to closed
    if ($response['balance'] == 0 or $response['balance'] == '0') {
        $response['closed_customer'][$i] = true;
    } else {
        $response['closed_customer'][$i] = false;
    }

    $response['balAmnt'][$i] =  $response['balance'];

    $i++;
}
function calculateOthers($loan_arr, $response, $connect, $req_id)
{

    $due_start_from = $loan_arr['due_start_from'];
    $maturity_month = $loan_arr['maturity_month'];

    if ($loan_arr['due_method_calc'] == 'Monthly' || $loan_arr['due_method_scheme'] == '1') {
        if ($loan_arr['loan_type'] != 'interest') {
            //Convert Date to Year and month, because with date, it will use exact date to loop months, instead of taking end of month
            $due_start_from = date('Y-m', strtotime($due_start_from));
            $maturity_month = date('Y-m', strtotime($maturity_month));

            //If Due method is Monthly, Calculate penalty by checking the month has ended or not
            $current_date = date('Y-m');


            $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
            $end_date_obj = DateTime::createFromFormat('Y-m', $maturity_month);
            $current_date_obj = DateTime::createFromFormat('Y-m', $current_date);

            $interval = new DateInterval('P1M'); // Create a one month interval

            $count = 0;
            // $qry = $connect->query("DELETE FROM penalty_charges where req_id = '$req_id' and (penalty_date != '' or penalty_date != NULL ) ");

            while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) {
                //To raise penalty in seperate table
                $penalty_raised_date  = $start_date_obj->format('Y-m');
                // If due month exceeded
                if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                    $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                } else {
                    $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' AND FIND_IN_SET('" . $loan_arr['sub_category'] . "', sub_category)");
                }
                $row = $result->fetch();
                $penalty_per = $row['overdue']; //get penalty percentage to insert
                $penalty = number_format(($response['due_amt'] * $penalty_per) / 100);


                // $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_raised_date','$penalty',current_timestamp)");


                $start_date_obj->add($interval);
                $count++; //Count represents how many months are exceeded
            }
            $response['count_of_month'] = $count;
            //To check over due, if current date is greater than maturity minth, then i will be OD
            if ($current_date_obj > $end_date_obj) {
                $response['od'] = true;
            } else {
                $response['od'] = false;
            }

            //To check whether due has been nil with other charges

            $qry = $connect->query("SELECT c.due_amt_track,c.pre_close_waiver,c.princ_amt_track,pc.penalty,pc.paid_amnt AS paid_amntpc,pc.waiver_amnt AS waiver_amntpc,cc.coll_charge,cc.paid_amnt AS paid_amntcc,cc.waiver_amnt AS waiver_amntcc FROM ( SELECT req_id, SUM(due_amt_track) AS due_amt_track,SUM(pre_close_waiver) AS pre_close_waiver,SUM(princ_amt_track) AS princ_amt_track FROM collection WHERE req_id = '$req_id' ) c LEFT JOIN ( SELECT req_id, SUM(penalty) AS penalty, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM penalty_charges WHERE req_id = '$req_id' GROUP BY req_id ) pc ON c.req_id = pc.req_id LEFT JOIN ( SELECT req_id, SUM(coll_charge) AS coll_charge, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM collection_charges WHERE req_id = '$req_id' GROUP BY req_id ) cc ON c.req_id = cc.req_id ");
            $row = $qry->fetch();

            $due_amt_track = intVal($row['due_amt_track']) + intVal($row['pre_close_waiver']);


            //if sum value is null or empty then assign 0 to it
            if ($row['penalty'] == '' or $row['penalty'] == null) {
                $row['penalty'] = 0;
            }
            if ($row['paid_amntpc'] == '' or $row['paid_amntpc'] == null) {
                $row['paid_amntpc'] = 0;
            }
            if ($row['waiver_amntpc'] == '' or $row['waiver_amntpc'] == null) {
                $row['waiver_amntpc'] = 0;
            }
            if ($row['coll_charge'] == '' or $row['coll_charge'] == null) {
                $row['coll_charge'] = 0;
            }
            if ($row['paid_amntcc'] == '' or $row['paid_amntcc'] == null) {
                $row['paid_amntcc'] = 0;
            }
            if ($row['waiver_amntcc'] == '' or $row['waiver_amntcc'] == null) {
                $row['waiver_amntcc'] = 0;
            }

            $curr_penalty = $row['penalty'] - $row['paid_amntpc'] - $row['waiver_amntpc'];
            $curr_charges = $row['coll_charge'] - $row['paid_amntcc'] - $row['waiver_amntcc'];

            $qry = $connect->query("SELECT SUM(principal_amt_cal) as principal_amt_cal,SUM(tot_amt_cal) as tot_amt_cal from acknowlegement_loan_calculation WHERE req_id =$req_id");
            $row = $qry->fetch();

            if ($row['tot_amt_cal'] != 0) {
                $total_for_nil = $row['tot_amt_cal'];
            } else {
                $total_for_nil = $row['principal_amt_cal'];
            }
            $due_nil_check = intVal($total_for_nil) - intVal($due_amt_track);

            if ($due_nil_check == 0) {
                if ($curr_penalty > 0 || $curr_charges > 0) {
                    $response['due_nil'] = true;
                } else {
                    $response['due_nil'] = false;
                }
            } else {
                $response['due_nil'] = false;
            }


            // //Insert Penalty once again because its showing extra one penalty in collection for current month
            // $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_raised_date','$penalty',current_timestamp)");
            if ($count > 0) {

                //if Due month exceeded due amount will be as pending with how many months are exceeded
                $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

                // If due month exceeded
                if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                    $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                } else {
                    $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' AND FIND_IN_SET('" . $loan_arr['sub_category'] . "', sub_category)");
                }
                $row = $result->fetch();
                $penalty_per = number_format($row['overdue'] * $count); //Count represents how many months are exceeded//Number format if percentage exeeded decimals then pernalty may increase

                $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $req_id . "' ");
                $row = $result->fetch();

                $penalty = number_format(($response['due_amt'] * $penalty_per) / 100);
                $response['penalty'] = intval($penalty) - (($row['penalty']) ? $row['penalty'] : 0) - (($row['penalty_waiver']) ? $row['penalty_waiver'] : 0);

                //Payable amount will be pending amount added with current month due amount
                $response['payable'] = $response['due_amt'] + $response['pending'];


            } else {
                //If still current month is not ended, then pending will be same due amt
                $response['pending'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
                //If still current month is not ended, then penalty will be 0
                $response['penalty'] = 0;
                //If still current month is not ended, then payable will be due amt
                // $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
                $response['payable'] =  0;

            }
        } else {
            $interest_details = calculateInterestLoan($connect, $loan_arr, $response, $req_id);
            $all_data = array_merge($response, $interest_details);
            $response = $all_data;
        }
    } else
    if ($loan_arr['due_method_scheme'] == '2') {

        //If Due method is Weekly, Calculate penalty by checking the month has ended or not
        $current_date = date('Y-m-d');

        $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_start_from);
        $end_date_obj = DateTime::createFromFormat('Y-m-d', $maturity_month);
        $current_date_obj = DateTime::createFromFormat('Y-m-d', $current_date);

        $interval = new DateInterval('P1W'); // Create a one Week interval

        $count = 0;
        // $qry = $connect->query("DELETE FROM penalty_charges where req_id = '$req_id' and (penalty_date != '' or penalty_date != NULL ) ");
        while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) {
            //To raise penalty in seperate table
            $penalty_raised_date  = $start_date_obj->format('Y-m-d');
            // If due month exceeded
            if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            } else {
                $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            }
            $row = $result->fetch();
            $penalty_per = $row['overdue']; //get penalty percentage to insert

            $penalty = number_format(($response['due_amt'] * $penalty_per) / 100);

            // $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_raised_date','$penalty',current_timestamp)");


            $start_date_obj->add($interval);
            $count++; //Count represents how many months are exceeded
        }
        $response['count_of_month'] = $count;

        //To check over due, if current date is greater than maturity minth, then i will be OD
        if ($current_date_obj > $end_date_obj) {
            $response['od'] = true;
        } else {
            $response['od'] = false;
        }

        //To check whether due has been nil with other charges

        $qry = $connect->query("SELECT c.due_amt_track,c.pre_close_waiver,c.princ_amt_track,pc.penalty,pc.paid_amnt AS paid_amntpc,pc.waiver_amnt AS waiver_amntpc,cc.coll_charge,cc.paid_amnt AS paid_amntcc,cc.waiver_amnt AS waiver_amntcc FROM 
        ( SELECT req_id, SUM(due_amt_track) AS due_amt_track,SUM(pre_close_waiver) AS pre_close_waiver,SUM(princ_amt_track) AS princ_amt_track FROM collection WHERE req_id = '$req_id' ) c 
        LEFT JOIN ( SELECT req_id, SUM(penalty) AS penalty, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM penalty_charges WHERE req_id = '$req_id' GROUP BY req_id ) pc ON c.req_id = pc.req_id 
        LEFT JOIN ( SELECT req_id, SUM(coll_charge) AS coll_charge, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM collection_charges WHERE req_id = '$req_id' GROUP BY req_id ) cc ON c.req_id = cc.req_id ");
        $row = $qry->fetch();

        $due_amt_track = intVal($row['due_amt_track']) + intVal($row['pre_close_waiver']);
        //if sum value is null or empty then assign 0 to it
        if ($row['penalty'] == '' or $row['penalty'] == null) {
            $row['penalty'] = 0;
        }
        if ($row['paid_amntpc'] == '' or $row['paid_amntpc'] == null) {
            $row['paid_amntpc'] = 0;
        }
        if ($row['waiver_amntpc'] == '' or $row['waiver_amntpc'] == null) {
            $row['waiver_amntpc'] = 0;
        }
        if ($row['coll_charge'] == '' or $row['coll_charge'] == null) {
            $row['coll_charge'] = 0;
        }
        if ($row['paid_amntcc'] == '' or $row['paid_amntcc'] == null) {
            $row['paid_amntcc'] = 0;
        }
        if ($row['waiver_amntcc'] == '' or $row['waiver_amntcc'] == null) {
            $row['waiver_amntcc'] = 0;
        }

        $curr_penalty = $row['penalty'] - $row['paid_amntpc'] - $row['waiver_amntpc'];
        $curr_charges = $row['coll_charge'] - $row['paid_amntcc'] - $row['waiver_amntcc'];

        $qry = $connect->query("SELECT SUM(principal_amt_cal) as principal_amt_cal,SUM(tot_amt_cal) as tot_amt_cal from acknowlegement_loan_calculation WHERE req_id =$req_id");
        $row = $qry->fetch();

        if ($row['tot_amt_cal'] != '') {
            $total_for_nil = $row['tot_amt_cal'];
        } else {
            $total_for_nil = $row['principal_amt_cal'];
        }
        $due_nil_check = intVal($total_for_nil) - intVal($due_amt_track);

        if ($due_nil_check == 0) {
            if ($curr_penalty > 0 || $curr_charges > 0) {
                $response['due_nil'] = true;
            }
        } else {
            $response['due_nil'] = false;
        }

        if ($count > 0) {

            //if Due month exceeded due amount will be as pending with how many months are exceeded
            $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

            // If due month exceeded
            if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            } else {
                $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            }
            $row = $result->fetch();
            $penalty_per = number_format($row['overdue'] * $count); //Count represents how many months are exceeded//Number format if percentage exeeded decimals then pernalty may increase

            $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $req_id . "' ");
            $row = $result->fetch();

            $penalty = number_format(($response['due_amt'] * $penalty_per) / 100);
            $response['penalty'] = $penalty - $row['penalty'] - $row['penalty_waiver'];

            //Payable amount will be pending amount added with current month due amount
            $response['payable'] = $response['due_amt'] + $response['pending'];
        } else {
            //If still current month is not ended, then pending will be same due amt
            $response['pending'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
            //If still current month is not ended, then penalty will be 0
            $response['penalty'] = 0;
            //If still current month is not ended, then payable will be due amt
            // $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
            $response['payable'] =  0;
        }
    } elseif ($loan_arr['due_method_scheme'] == '3') {
        //If Due method is Daily, Calculate penalty by checking the month has ended or not
        $current_date = date('Y-m-d');

        $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_start_from);
        $end_date_obj = DateTime::createFromFormat('Y-m-d', $maturity_month);
        $current_date_obj = DateTime::createFromFormat('Y-m-d', $current_date);

        $interval = new DateInterval('P1D'); // Create a one Week interval

        $count = 0;
        // $qry = $connect->query("DELETE FROM penalty_charges where req_id = '$req_id' and (penalty_date != '' or penalty_date != NULL ) ");
        // echo "DELETE FROM penalty_charges where req_id = '$req_id' and (penalty_date != '' or penalty_date != NULL ) ";
        while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) {
            //To raise penalty in seperate table
            $penalty_raised_date  = $start_date_obj->format('Y-m-d');
            // If due month exceeded
            if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            } else {
                $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            }
            $row = $result->fetch();
            $penalty_per = $row['overdue']; //get penalty percentage to insert

            $penalty = ($response['due_amt'] * $penalty_per) / 100;

            // $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_raised_date','$penalty',current_timestamp)");

            $start_date_obj->add($interval);
            $count++; //Count represents how many months are exceeded
        }
        $response['count_of_month'] = $count;
        //To check over due, if current date is greater than maturity minth, then i will be OD
        if ($current_date_obj > $end_date_obj) {
            $response['od'] = true;
        } else {
            $response['od'] = false;
        }

        //To check whether due has been nil with other charges

        $qry = $connect->query("SELECT c.due_amt_track,c.pre_close_waiver,c.princ_amt_track,pc.penalty,pc.paid_amnt AS paid_amntpc,pc.waiver_amnt AS waiver_amntpc,cc.coll_charge,cc.paid_amnt AS paid_amntcc,cc.waiver_amnt AS waiver_amntcc FROM 
        ( SELECT req_id, SUM(due_amt_track) AS due_amt_track,sum(pre_close_waiver) as pre_close_waiver,SUM(princ_amt_track) AS princ_amt_track FROM collection WHERE req_id = '$req_id' ) c 
        LEFT JOIN ( SELECT req_id, SUM(penalty) AS penalty, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM penalty_charges WHERE req_id = '$req_id' GROUP BY req_id ) pc ON c.req_id = pc.req_id 
        LEFT JOIN ( SELECT req_id, SUM(coll_charge) AS coll_charge, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM collection_charges WHERE req_id = '$req_id' GROUP BY req_id ) cc ON c.req_id = cc.req_id;");
        $row = $qry->fetch();

        $due_amt_track = intVal($row['due_amt_track']) + intVal($row['pre_close_waiver']);
        //if sum value is null or empty then assign 0 to it
        if ($row['penalty'] == '' or $row['penalty'] == null) {
            $row['penalty'] = 0;
        }
        if ($row['paid_amntpc'] == '' or $row['paid_amntpc'] == null) {
            $row['paid_amntpc'] = 0;
        }
        if ($row['waiver_amntpc'] == '' or $row['waiver_amntpc'] == null) {
            $row['waiver_amntpc'] = 0;
        }
        if ($row['coll_charge'] == '' or $row['coll_charge'] == null) {
            $row['coll_charge'] = 0;
        }
        if ($row['paid_amntcc'] == '' or $row['paid_amntcc'] == null) {
            $row['paid_amntcc'] = 0;
        }
        if ($row['waiver_amntcc'] == '' or $row['waiver_amntcc'] == null) {
            $row['waiver_amntcc'] = 0;
        }

        $curr_penalty = $row['penalty'] - $row['paid_amntpc'] - $row['waiver_amntpc'];
        $curr_charges = $row['coll_charge'] - $row['paid_amntcc'] - $row['waiver_amntcc'];

        $qry = $connect->query("SELECT SUM(principal_amt_cal) as principal_amt_cal,SUM(tot_amt_cal) as tot_amt_cal from acknowlegement_loan_calculation WHERE req_id =$req_id");
        $row = $qry->fetch();

        if ($row['tot_amt_cal'] != '') {
            $total_for_nil = $row['tot_amt_cal'];
        } else {
            $total_for_nil = $row['principal_amt_cal'];
        }
        $due_nil_check = intVal($total_for_nil) - intVal($due_amt_track);

        if ($due_nil_check == 0) {
            if ($curr_penalty > 0 || $curr_charges > 0) {
                $response['due_nil'] = true;
            }
        } else {
            $response['due_nil'] = false;
        }

        if ($count > 0) {

            //if Due month exceeded due amount will be as pending with how many months are exceeded and subract pre closure amount if available
            $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

            // If due month exceeded
            if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            } else {
                $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
            }
            $row = $result->fetch();
            $penalty_per = number_format($row['overdue'] * $count); //Count represents how many months are exceeded//Number format if percentage exeeded decimals then pernalty may increase

            $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $req_id . "' ");
            $row = $result->fetch();

            $penalty = number_format(($response['due_amt'] * $penalty_per) / 100);
            $response['penalty'] = intVal($penalty) - intVal($row['penalty']) - intVal($row['penalty_waiver']);

            //Payable amount will be pending amount added with current month due amount
            $response['payable'] = $response['due_amt'] + $response['pending'];
        } else {
            //If still current month is not ended, then pending will be same due amt
            $response['pending'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
            //If still current month is not ended, then penalty will be 0
            $response['penalty'] = 0;
            //If still current month is not ended, then payable will be due amt
            // $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
            $response['payable'] =  0;
        }
    }
    if ($response['pending'] < 0) {
        $response['pending'] = 0;
    }
    return $response;
}
function calculateInterestLoan($connect, $loan_arr, $response, $req_id)
{

    $due_start_from = $loan_arr['loan_date'];
    $maturity_month = $loan_arr['maturity_month'];

    $checkcollection = $connect->query("SELECT SUM(`int_amt_track`) as totalPaidAmt FROM `collection` WHERE `req_id` = '$req_id'"); // To Find total paid amount till Now.
    $checkrow = $checkcollection->fetch();
    $totalPaidAmt = $checkrow['totalPaidAmt'] ?? 0; //null collation operator
    $checkack = $connect->query("SELECT int_amt_cal,due_amt_cal FROM `acknowlegement_loan_calculation` WHERE `req_id` = '$req_id'"); // To Find Due Amount.
    $checkAckrow = $checkack->fetch();
    $int_amt_cal = $checkAckrow['int_amt_cal'];
    $due_amt = $checkAckrow['due_amt_cal'];

    //Convert Date to Year and month, because with date, it will use exact date to loop months, instead of taking end of month
    $due_start_from = date('Y-m', strtotime($due_start_from));
    $maturity_month = date('Y-m', strtotime($maturity_month));

    // Create a DateTime object from the given date
    $maturity_month = new DateTime($maturity_month);
    // Subtract one month from the date
    $maturity_month->modify('-1 month');
    // Format the date as a string
    $maturity_month = $maturity_month->format('Y-m');

    //If Due method is Monthly, Calculate penalty by checking the month has ended or not
    $current_date = date('Y-m');

    $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
    $end_date_obj = DateTime::createFromFormat('Y-m', $maturity_month);
    $current_date_obj = DateTime::createFromFormat('Y-m', $current_date);

    $interval = new DateInterval('P1M'); // Create a one month interval

    //condition start
    $count = 0;
    $loandate_tillnow = 0;
    $countForPenalty = 0;
    $penalty = 0;
    $dueCharge = ($due_amt) ? $due_amt : $int_amt_cal;
    $start = DateTime::createFromFormat('Y-m', $due_start_from);
    $current = DateTime::createFromFormat('Y-m', $current_date);

    for ($i = $start; $i < $current; $start->add($interval)) {
        $loandate_tillnow += 1;
        $toPaytilldate = intval($loandate_tillnow) * intval($dueCharge);
    }

    while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) {

        $start_date_obj->add($interval); //increase one month to loop again
        $count++; //Count represents how many months are exceeded
    }


    $res['count_of_month'] = $count;
    //To check over due, if current date is greater than maturity minth, then i will be OD
    if ($current_date_obj > $end_date_obj) {
        $res['od'] = true;
    } else {
        $res['od'] = false;
    }

    //To check whether due has been nil with other charges

    $qry = $connect->query("SELECT c.due_amt_track,c.pre_close_waiver,c.princ_amt_track,pc.penalty,pc.paid_amnt AS paid_amntpc,pc.waiver_amnt AS waiver_amntpc,cc.coll_charge,cc.paid_amnt AS paid_amntcc,cc.waiver_amnt AS waiver_amntcc FROM ( SELECT req_id, SUM(due_amt_track) AS due_amt_track,SUM(pre_close_waiver) AS pre_close_waiver,SUM(princ_amt_track) AS princ_amt_track FROM collection WHERE req_id = '$req_id' ) c LEFT JOIN ( SELECT req_id, SUM(penalty) AS penalty, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM penalty_charges WHERE req_id = '$req_id' GROUP BY req_id ) pc ON c.req_id = pc.req_id LEFT JOIN ( SELECT req_id, SUM(coll_charge) AS coll_charge, SUM(paid_amnt) AS paid_amnt, SUM(waiver_amnt) AS waiver_amnt FROM collection_charges WHERE req_id = '$req_id' GROUP BY req_id ) cc ON c.req_id = cc.req_id ");
    $row = $qry->fetch();

    $due_amt_track = intVal($row['due_amt_track']) + intVal($row['pre_close_waiver']);

    $due_amt_track = $row['princ_amt_track'] + $row['pre_close_waiver'];

    //if sum value is null or empty then assign 0 to it
    if ($row['penalty'] == '' or $row['penalty'] == null) {
        $row['penalty'] = 0;
    }
    if ($row['paid_amntpc'] == '' or $row['paid_amntpc'] == null) {
        $row['paid_amntpc'] = 0;
    }
    if ($row['waiver_amntpc'] == '' or $row['waiver_amntpc'] == null) {
        $row['waiver_amntpc'] = 0;
    }
    if ($row['coll_charge'] == '' or $row['coll_charge'] == null) {
        $row['coll_charge'] = 0;
    }
    if ($row['paid_amntcc'] == '' or $row['paid_amntcc'] == null) {
        $row['paid_amntcc'] = 0;
    }
    if ($row['waiver_amntcc'] == '' or $row['waiver_amntcc'] == null) {
        $row['waiver_amntcc'] = 0;
    }

    $curr_penalty = $row['penalty'] - $row['paid_amntpc'] - $row['waiver_amntpc'];
    $curr_charges = $row['coll_charge'] - $row['paid_amntcc'] - $row['waiver_amntcc'];

    $qry = $connect->query("SELECT SUM(principal_amt_cal) as principal_amt_cal,SUM(tot_amt_cal) as tot_amt_cal from acknowlegement_loan_calculation WHERE req_id =$req_id");
    $row = $qry->fetch();

    if ($row['tot_amt_cal'] != 0) {
        $total_for_nil = $row['tot_amt_cal'];
    } else {
        $total_for_nil = $row['principal_amt_cal'];
    }
    $due_nil_check = intVal($total_for_nil) - intVal($due_amt_track);

    if ($due_nil_check == 0) {
        if ($curr_penalty > 0 || $curr_charges > 0) {
            $res['due_nil'] = true;
        } else {
            $res['due_nil'] = false;
        }
    } else {
        $res['due_nil'] = false;
    }


    if ($count > 0) {
        $interest_paid = getPaidInterest($connect, $req_id);

        $res['payable'] = payableCalculation($connect, $loan_arr, $response, $req_id) - $interest_paid;
        $res['till_date_int'] = getTillDateInterest($loan_arr, $response, $connect, 'curmonth', $req_id) - $interest_paid;
        $res['pending'] = pendingCalculation($connect, $loan_arr, $response, $req_id) - $interest_paid;

        if ($res['pending'] < 0) {
            $res['pending'] = 0;
        }
        if ($res['payable'] < 0) {
            $res['payable'] = 0;
        }

        $res['penalty'] = getPenaltyCharges($connect, $req_id);
    } else {
        //in this calculate till date interest when month are not crossed for due starting month
        $res['till_date_int'] = getTillDateInterest($loan_arr, $response, $connect, 'forstartmonth', $req_id);
        $res['pending'] = 0;
        $res['payable'] = 0;
        $res['penalty'] = 0;
    }

    $res['payable'] = ceilAmount($res['payable']);
    $res['pending'] = ceilAmount($res['pending']);
    $res['till_date_int'] = ceilAmount($res['till_date_int']);
    return $res;
}
function calculateNewInterestAmt($int_rate, $balance)
{
    //to calculate current interest amount based on current balance value//bcoz interest will be calculated based on current balance amt only for interest loan
    $int = $balance * ($int_rate / 100);
    $curInterest = ceil($int / 5) * 5; //to increase Interest to nearest multiple of 5
    if ($curInterest < $int) {
        $curInterest += 5;
    }
    $response = $curInterest;

    return $response;
}
function dueAmtCalculation($connect, $start_date, $end_date, $due_amt, $loan_arr, $status, $req_id)
{
    // var_dump($start_date);
    $start = $start_date->format('Y-m-d');
    $start = new DateTime($start);
    $end = $end_date->format('Y-m-d');
    $end = new DateTime($end);

    $int_rate = $loan_arr['int_rate'];
    $scheme_name = $loan_arr['scheme_name'];
    $loan_category = $loan_arr['loan_category'];
    $sub_category = $loan_arr['sub_category'];

    $result = 0;
    $qry = $connect->query("SELECT princ_amt_track FROM `collection` WHERE req_id = '" . $req_id . "' and princ_amt_track != '' ORDER BY coll_date ASC ");
    if ($qry->rowCount() > 0) {

        while ($start->format('m') <= $end->format('m')) {


            $penalty = 0;
            $start_for_penalty = $start->format('Y-m-d');

            $qry = $connect->query("SELECT princ_amt_track as princ,bal_amt, coll_date FROM `collection` WHERE req_id = '" . $req_id . "' and princ_amt_track != '' and month(coll_date) = month('" . $start->format('Y-m-d') . "') and year(coll_date) = year('" . $start->format('Y-m-d') . "') ORDER BY coll_date ASC ");
            if ($qry->rowCount() > 0) {

                while ($row = $qry->fetch()) {
                    $princ = $row['princ'];
                    $bal_amt = $row['bal_amt'];
                    $coll_date = new DateTime($row['coll_date']);

                    $due_amt = calculateNewInterestAmt($int_rate, $bal_amt);
                    $bal_amt = $bal_amt - $princ;
                    $dueperday = $due_amt / intval($start->format('t'));
                    $cur_result = (($start->diff($coll_date))->days) * $dueperday;
                    $result += $cur_result;

                    unset($start); //unset to remove as obj // so can reinitialize
                    $start = new DateTime($coll_date->format('Y-m-d'));
                    unset($coll_date); //unset to remove as obj // so can reinitialize
                }
            } else {
                $qry = $connect->query("SELECT princ_amt_track as princ,bal_amt, coll_date FROM `collection` WHERE req_id = '" . $req_id . "' and princ_amt_track != '' and month(coll_date) < month('" . $start->format('Y-m-d') . "') and year(coll_date) <= year('" . $start->format('Y-m-d') . "') ORDER BY coll_date ASC LIMIT 1");
                if ($qry->rowCount() > 0) {
                    $row = $qry->fetch();
                    $princ = $row['princ'];
                    $bal_amt = $row['bal_amt'];
                    $bal_amt = $bal_amt - $princ;
                } else {
                    $qry = $connect->query("SELECT principal_amt_cal from acknowlegement_loan_calculation where req_id = '" . $req_id . "' ");
                    $row = $qry->fetch();
                    $bal_amt = $row['principal_amt_cal'];
                }
            }

            $due_amt = calculateNewInterestAmt($int_rate, $bal_amt);
            $dueperday = $due_amt / intval($start->format('t'));

            if ($start->format('m') != $end->format('m')) {
                $new_end = new DateTime($start->format("Y-m-t"));
                $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                $result += $cur_result;
                $start->modify("+1 month");
                $start->modify("first day of this month");
            } else {

                if ($status == 'payable' or $status == 'pending') {
                    $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    $result += $cur_result;
                } else {
                    $cur_result = (($start->diff($end))->days) * $dueperday;
                    $result += $cur_result;
                }
                $start->modify("+1 month");
                $start->modify("first day of this month");
            }

            if ($status == 'pending') { //raising penalty if loops for looping month

                if ($scheme_name == '' || $scheme_name == null) {
                    $ovqry = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '$loan_category' and sub_category = '$sub_category' ");
                } else {

                    $ovqry = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' AND FIND_IN_SET('" . $loan_arr['sub_category'] . "', sub_category)");
                }
                $row = $ovqry->fetch();
                $penalty_per = $row['overdue']; //get penalty percentage to insert

                $paid_interest = getPaidInterest($connect, $req_id);
                if ($paid_interest > 0) {
                    //raise penalty if the customer paid something
                    $cur_result = $cur_result - $paid_interest;
                    if ($cur_result < 0) { //if the cur result is negative then the interest of the month has been paid already
                        $cur_result = 0;
                    }
                }

                $checkPenalty = $connect->query("SELECT * from penalty_charges where month(penalty_date) = month('$start_for_penalty') AND year(penalty_date) = year('$start_for_penalty') and req_id = '" . $req_id . "' ");
                if ($checkPenalty->rowCount() == 0) {
                    $penalty = round((($cur_result * $penalty_per) / 100) + $penalty);
                    if ($cur_result != 0) {
                        $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('" . $req_id . "','" . date('Y-m', strtotime($start_for_penalty)) . "','$penalty',now())");
                    }
                }
            }
        }
    } else {
        while ($start->format('m') <= $end->format('m')) {

            $penalty = 0;
            $start_for_penalty = $start->format('Y-m-d');

            $dueperday = $due_amt / intval($start->format('t'));

            if ($status != 'pending') {
                if ($start->format('m') != $end->format('m')) {
                    $new_end_date = clone $start;
                    $new_end_date->modify('last day of this month');
                    $cur_result = (($start->diff($new_end_date))->days + 1) * $dueperday;
                    $result += $cur_result;
                } elseif ($end->format('Y-m-d') != date('Y-m-d')) {
                    $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    $result += $cur_result;
                } else {
                    $cur_result = (($start->diff($end))->days) * $dueperday;
                    $result += $cur_result;
                }
            } else {
                $new_end = clone $start;
                $new_end = $new_end->modify("last day of this month");
                $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                $result += $cur_result;
            }

            $start->modify('+1 month');
            $start->modify('first day of this month');

            if ($status == 'pending') { //raising penalty if loops for looping month

                if ($scheme_name == '' || $scheme_name == null) {
                    $ovqry = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '$loan_category' and sub_category = '$sub_category' ");
                } else {
                    $ovqry =  $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' AND FIND_IN_SET('" . $loan_arr['sub_category'] . "', sub_category)");
                }
                $row = $ovqry->fetch();
                $penalty_per = $row['overdue']; //get penalty percentage to insert

                $paid_interest = getPaidInterest($connect, $req_id);
                if ($paid_interest > 0) {
                    //raise penalty if the customer paid something
                    $cur_result = $cur_result - $paid_interest;
                    if ($cur_result < 0) { //if the cur result is negative then the interest of the month has been paid already
                        $cur_result = 0;
                    }
                }

                $checkPenalty = $connect->query("SELECT * from penalty_charges where month(penalty_date) = month('$start_for_penalty') AND year(penalty_date) = year('$start_for_penalty') and req_id = '" . $req_id . "' ");
                if ($checkPenalty->rowCount() == 0) {
                    $penalty = round((($cur_result * $penalty_per) / 100) + $penalty);
                    if ($cur_result != 0) {
                        $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('" . $req_id . "','" . date('Y-m', strtotime($start_for_penalty)) . "','$penalty',now())");
                    }
                }
            }
        }
    }
    return $result;
}
function payableCalculation($connect, $loan_arr, $response, $req_id)
{
    $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
    $cur_date = new DateTime(date('Y-m-d'));
    $last_month = clone $cur_date;
    $last_month->modify('-1 month');

    $result = 0;
    $st_date = clone $issued_date;
    while ($st_date->format('m') <= $last_month->format('m')) {
        $end_date = clone $st_date;
        $end_date->modify('last day of this month');
        $start = clone $st_date; //because the function calling below will change the root of starting date

        $result += dueAmtCalculation($connect, $start, $end_date, $response['due_amt'], $loan_arr, 'payable', $req_id);

        $st_date->modify('+1 month');
        $st_date->modify('first day of this month');
    }

    return $result;
}
function pendingCalculation($connect, $loan_arr, $response, $req_id)
{
    $pending = getTillDateInterest($loan_arr, $response, $connect, 'pendingmonth', $req_id);
    return $pending;
}
function getTillDateInterest($loan_arr, $response, $connect, $data, $req_id)
{

    if ($data == 'forstartmonth') {

        //to calculate till date interest if loan is interst based
        if ($loan_arr['loan_type'] == 'interest') {

            //get the loan isued month's date count
            $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

            //current month's total date
            $cur_date = new DateTime(date('Y-m-d'));

            $result = dueAmtCalculation($connect, $issued_date, $cur_date, $response['due_amt'], $loan_arr, '', $req_id);
            // $result = (($issued_date->diff($cur_date))->days) * $issue_month_due;

            //to increase till date Interest to nearest multiple of 5
            $cur_amt = ceil($result / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
            if ($cur_amt < $result) {
                $cur_amt += 5;
            }
            $result = $cur_amt;
        }
        return $result;
    }
    if ($data == 'curmonth') {
        $cur_date = new DateTime(date('Y-m-d'));
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));


        $result = dueAmtCalculation($connect, $issued_date, $cur_date, $response['due_amt'], $loan_arr, 'TDI', $req_id);
        return $result;
    }
    if ($data == 'pendingmonth') {
        //for pending value check, goto 2 months before
        //bcoz last month value is on payable, till date int will be on cur date
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
        $cur_date = new DateTime(date('Y-m-d'));
        $cur_date->modify('-2 months');
        $cur_date->modify('last day of this month');
        $result = 0;

        if ($issued_date->format('m') <= $cur_date->format('m')) {
            $result = dueAmtCalculation($connect, $issued_date, $cur_date, $response['due_amt'], $loan_arr, 'pending', $req_id);
        }
        return $result;
    }

    return $response;
}
function getPaidInterest($connect, $req_id)
{
    $qry = $connect->query("SELECT SUM(int_amt_track) as int_paid FROM `collection` WHERE req_id = '$req_id' and (int_amt_track != '' and int_amt_track IS NOT NULL) ");
    $int_paid = $qry->fetch()['int_paid'];
    return intVal($int_paid);
}
function getPenaltyCharges($connect, $req_id)
{
    // to get overall penalty paid till now to show pending penalty amount
    $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $req_id . "' ");
    $row = $result->fetch();
    if ($row['penalty'] == null) {
        $row['penalty'] = 0;
    }
    if ($row['penalty_waiver'] == null) {
        $row['penalty_waiver'] = 0;
    }
    //to get overall penalty raised till now for this req id
    $result1 = $connect->query("SELECT SUM(penalty) as penalty FROM `penalty_charges` WHERE req_id = '" . $req_id . "' ");
    $row1 = $result1->fetch();
    if ($row1['penalty'] == null) {
        $penalty = 0;
    } else {
        $penalty = $row1['penalty'];
    }

    return $penalty - $row['penalty'] - $row['penalty_waiver'];
}
function ceilAmount($amt)
{
    $cur_amt = ceil($amt / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
    if ($cur_amt < $amt) {
        $cur_amt += 5;
    }
    return $cur_amt;
}

echo json_encode($response);

// Close the database connection
$connect = null;