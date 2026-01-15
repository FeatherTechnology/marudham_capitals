<?php

include '../ajaxconfig.php';

if (isset($_POST['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
}
if (isset($_GET['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_GET['cus_id']);
}
$screen = '';
$req_arr = array();
if (isset($cus_id)) {
    $qry = $connect->query("SELECT req_id FROM in_issue where cus_id = $cus_id and (cus_status >= 14 and cus_status < 20) ORDER BY CAST(req_id AS UNSIGNED) ASC ");
    while ($row = $qry->fetch()) {
        $req_arr[] = $row['req_id'];
    }
}

if (isset($_POST['reqID'])) {
    $reqid = $_POST['reqID'];
    $screen = 'auto_update';// only insert the penalty for auto update
    $req_arr[] = $reqid;

    $qry = $connect->query("SELECT cus_id FROM in_issue where req_id = $reqid ");
    $row = $qry->fetch();
    $cus_id = $row['cus_id'];
}


//get Total amt from ack loan calculation (For monthly Interest total amount will not be there, so take principals)*
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

    $response['req_id'][$i] = $req_id;

    $result = $connect->query("SELECT * FROM `acknowlegement_loan_calculation` WHERE req_id = $req_id ");
    if ($result->rowCount() > 0) {
        $row = $result->fetch();
        $loan_arr = $row;

        if ($loan_arr['tot_amt_cal'] == '' || $loan_arr['tot_amt_cal'] == null) {
            //(For monthly Interest total amount will not be there, so take principals)
            $response['total_amt'] = intVal($loan_arr['principal_amt_cal']);
            $response['loan_type'] = 'Interest';
            $loan_arr['loan_type'] = 'Interest';
        } else {
            $response['total_amt'] = intVal($loan_arr['tot_amt_cal']);
            $response['loan_type'] = 'emi';
            $loan_arr['loan_type'] = 'emi';
        }

        $response['calculate_method'] = $loan_arr['calc_method'];

        if ($loan_arr['due_amt_cal'] == '' || $loan_arr['due_amt_cal'] == null) {
            //(For monthly Interest Due amount will not be there, so take Interest)
            $response['due_amt'] = intVal($loan_arr['int_amt_cal']);
        } else {
            $response['due_amt'] = intVal($loan_arr['due_amt_cal']); //Due amount will remain same
        }

        $qry = $connect->query("SELECT updated_date FROM `in_issue` WHERE req_id = $req_id ");
        $loan_arr['loan_date'] = date('Y-m-d', strtotime($qry->fetch()['updated_date']));
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
        $principal_waiver = 0;
        foreach ($coll_arr as $tot) {
            $total_paid += intVal($tot['due_amt_track']); //only calculate due amount not total paid value, because it will have penalty and coll charge also
            $total_paid_princ += intVal($tot['princ_amt_track']);
            $total_paid_int += intVal($tot['int_amt_track']);
            $pre_closure += intVal($tot['pre_close_waiver']); //get pre closure value to subract to get balance amount
            $principal_waiver += intVal($tot['principal_waiver']);
        }
        //total paid amount will be all records again request id should be summed
        $response['total_paid'] = ($loan_arr['loan_type'] == 'emi') ? $total_paid : $total_paid_princ;
        $response['total_waiver'] = ($loan_arr['loan_type'] == 'emi') ? $pre_closure : $principal_waiver;
        $response['total_paid_int'] = $total_paid_int;
        $response['pre_closure'] = $pre_closure;
        $response['principal_waiver'] = $principal_waiver;

        //total amount subracted by total paid amount and subracted with pre closure amount will be balance to be paid
        $response['balance'] = $response['total_amt'] - $response['total_paid'] - $response['total_waiver'];

        if ($loan_arr['loan_type'] == 'Interest') {
            $response['due_amt'] = calculateNewInterestAmt($loan_arr['int_rate'], $response['balance'], $response['calculate_method']);
        }

        $response = calculateOthers($loan_arr, $response, $connect, $req_id, $screen);
    } else {
        //If collection table dont have rows means there is no payment against that request, so total paid will be 0
        $response['total_paid'] = 0;
        $response['total_paid_int'] = 0;
        $response['pre_closure'] = 0;

        //If in collection table, there is no payment means balance amount still remains total amount
        $response['balance'] = $response['total_amt'];

        if ($loan_arr['loan_type'] == 'Interest') {
            $response['due_amt'] = calculateNewInterestAmt($loan_arr['int_rate'], $response['balance'], $response['calculate_method']);
        }

        $response = calculateOthers($loan_arr, $response, $connect, $req_id, $screen);
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

    $response['payable_as_req'][$i] = $response['payable'];
    $response['pending_as_req'][$i] = $response['pending'];
    $response['penalty_as_req'][$i] = $response['penalty'];

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

    $response['balAmnt'][$i] = $response['balance'];

    $i++;
}

//for knowing the customer status for due followup screen
//this will give the customer's sub status in the order of Legal, Error, OD, Due Nill, Pending, Current
$response['follow_cus_sts'] = ($i > 0) ? checkStatusOfCustomer($response, $loan_arr, $cus_id, $connect) : '';

function calculateOthers($loan_arr, $response, $connect, $req_id, $screen)
{

    $due_start_from = $loan_arr['due_start_from'];
    $maturity_month = $loan_arr['maturity_month'];

    if ($loan_arr['due_method_calc'] == 'Monthly' || $loan_arr['due_method_scheme'] == '1') {
        if ($loan_arr['loan_type'] != 'Interest') {
            //Convert Date to Year and month, because with date, it will use exact date to loop months, instead of taking end of month
            $due_start_from = date('Y-m', strtotime($due_start_from));
            $maturity_month = date('Y-m', strtotime($maturity_month));

            //If Due method is Monthly, Calculate penalty by checking the month has ended or not
            $current_date = date('Y-m');

            $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
            $end_date_obj = DateTime::createFromFormat('Y-m', $maturity_month);
            $current_date_obj = DateTime::createFromFormat('Y-m', $current_date);

            $interval = new DateInterval('P1M'); // Create a one month interval
            $penalty_counter = 0;
            $count = 0;
            // $qry = $connect->query("DELETE FROM penalty_charges where req_id = '$req_id' and (penalty_date != '' or penalty_date != NULL ) ");

            while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) {
                $start_date_obj->add($interval);
                $count++; //Count represents how many months are exceeded
            }

            $response['count_of_month'] = $count;


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
            //To check over due, if current date is greater than maturity minth, then i will be OD
            if ($current_date_obj > $end_date_obj && $due_nil_check > 0) {
                $response['od'] = true;
            } else {
                $response['od'] = false;
            }
            $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
            if ($screen == 'auto_update') {
                if ($current_date_obj > $end_date_obj) {
                    $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count in collection
                }
                while ($start_date_obj <= $current_date_obj) { // To find loan date count till now from start date.
                    $penalty_checking_date = $start_date_obj->format('Y-m-01'); // This format is for query.. month , year function accept only if (Y-m-d).
                    $penalty_date = $start_date_obj->format('Y-m');

                    $checkcollection = $connect->query("SELECT SUM(due_amt_track) as total_paid, SUM(pre_close_waiver) as total_pre 
                        FROM `collection` 
                        WHERE `req_id` = '$req_id' 
                        AND ( 
                            (YEAR(`coll_date`) != 0 AND MONTH(`coll_date`) != 0 AND 
                            (
                                (YEAR(`coll_date`) < YEAR('" . $penalty_checking_date . "')) OR 
                                (YEAR(`coll_date`) = YEAR('" . $penalty_checking_date . "') AND MONTH(`coll_date`) < MONTH('" . $penalty_checking_date . "'))
                            )) 
                            OR 
                            (YEAR(`trans_date`) != 0 AND MONTH(`trans_date`) != 0 AND 
                            (
                                (YEAR(`trans_date`) < YEAR('" . $penalty_checking_date . "')) OR 
                                (YEAR(`trans_date`) = YEAR('" . $penalty_checking_date . "') AND MONTH(`trans_date`) < MONTH('" . $penalty_checking_date . "'))
                            ))
                        )");
                    $coll_row = $checkcollection->fetch();
                    $totalPaidAmt = $coll_row['total_paid']; // Checking whether the collection are inserted on date or not by using penalty_raised_date.
                    $totalPreAmt = $coll_row['total_pre']; // Checking whether the collection are inserted on date or not by using penalty_raised_date.

                    $pending_for_penalty = $response['due_amt'] * $penalty_counter - $totalPaidAmt - $totalPreAmt;


                    if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                        $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                    } else {
                        $result =  $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' AND FIND_IN_SET('" . $loan_arr['sub_category'] . "', sub_category)");
                    }
                    $row = $result->fetch();
                    $penalty_per = $row['overdue']; //get penalty percentage to insert

                    if ($pending_for_penalty > 0) {
                        $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$penalty_date' and req_id = '$req_id' ");
                        if ($checkPenalty->rowCount() == 0) {
                            $penalty = round((($pending_for_penalty * $penalty_per) / 100));
                            if ($loan_arr['loan_type'] == 'emi') {
                                //if loan type is emi then directly apply penalty when month crossed and above conditions true
                                $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_date','$penalty',current_timestamp)");
                            }
                        }
                    }
                    $start_date_obj->add($interval); //increase one month to loop again

                    if ($penalty_counter < $count) {
                        $penalty_counter++;
                    }
                }
            }

            if ($count > 0) {


                //if Due month exceeded due amount will be as pending with how many months are exceeded
                $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];
                //Payable amount will be pending amount added with current month due amount
                $response['payable'] = $response['due_amt'] + $response['pending'];
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

                $response['penalty'] = $penalty - $row['penalty'] - $row['penalty_waiver'];
                if ($response['payable'] > $response['balance']) {
                    //if payable is greater than balance then change it as balance amt coz dont collect more than balance
                    //this case will occur when collection status becoms OD
                    $response['payable'] = $response['balance'];
                }
            } else {
                //If still current month is not ended, then pending will be same due amt // pending will be 0 if due date not exceeded
                $response['pending'] = 0; // $response['due_amt'] - $response['total_paid'] - $response['pre_closure'] ;
                //If still current month is not ended, then penalty will be 0
                $response['penalty'] = 0;
                //If still current month is not ended, then payable will be due amt
                $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
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

            $start_date_obj->add($interval);
            $count++; //Count represents how many months are exceeded
        }
        $penalty_counter = 0;

        if ($screen == 'auto_update') {

            if ($current_date_obj > $end_date_obj) {
                $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count in collection
            }
            while ($start_date_obj <= $current_date_obj) { // To find loan date count till now from start date.
                $penalty_checking_date = $start_date_obj->format('Y-m-d'); // This format is for query.. month , year function accept only if (Y-m-d).
                $penalty_date = $start_date_obj->format('Y-m-d');

                $checkcollection = $connect->query("SELECT 
                    SUM(due_amt_track) as total_paid,
                    SUM(pre_close_waiver) as total_pre 
                FROM 
                    `collection` 
                WHERE 
                    `req_id` = '$req_id' 
                    AND (YEAR(`coll_date`) <= YEAR('" . $penalty_checking_date . "') AND date(`coll_date`) <= date('" . $penalty_checking_date . "'))
                ");
                $coll_row = $checkcollection->fetch();
                $totalPaidAmt = $coll_row['total_paid']; // Checking whether the collection are inserted on date or not by using penalty_raised_date.
                $totalPreAmt = $coll_row['total_pre']; // Checking whether the collection are inserted on date or not by using penalty_raised_date.

                $pending_for_penalty = $response['due_amt'] * $penalty_counter - $totalPaidAmt - $totalPreAmt;


                if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                    $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                } else {
                    $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                }
                $row = $result->fetch();
                $penalty_per = $row['overdue']; //get penalty percentage to insert

                if ($pending_for_penalty > 0) {
                    $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$penalty_date' and req_id = '$req_id' ");
                    if ($checkPenalty->rowCount() == 0) {
                        $penalty = round((($pending_for_penalty * $penalty_per) / 100));
                        if ($loan_arr['loan_type'] == 'emi') {
                            //if loan type is emi then directly apply penalty when month crossed and above conditions true
                            $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_date','$penalty',current_timestamp)");
                        }
                    }
                }
                $start_date_obj->add($interval); //increase one month to loop again
                if ($penalty_counter < $count) {
                    $penalty_counter++;
                }
            }
        }
        $response['count_of_month'] = $count;

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
        //To check over due, if current date is greater than maturity minth, then i will be OD
        if ($current_date_obj > $end_date_obj && $due_nil_check > 0) {
            $response['od'] = true;
        } else {
            $response['od'] = false;
        }

        if ($count > 0) {

            //if Due month exceeded due amount will be as pending with how many months are exceeded
            $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

            // If due month exceeded
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

            $response['penalty'] = $penalty - $row['penalty'] - $row['penalty_waiver'];

            //Payable amount will be pending amount added with current month due amount
            $response['payable'] = $response['due_amt'] + $response['pending'];

            if ($response['payable'] > $response['balance']) {
                //if payable is greater than balance then change it as balance amt coz dont collect more than balance
                //this case will occur when collection status becoms OD
                $response['payable'] = $response['balance'];
            }
        } else {
            //If still current month is not ended, then pending will be same due amt // pending will be 0 if due date not exceeded
            $response['pending'] = 0; // $response['due_amt'] - $response['total_paid'] - $response['pre_closure'] ;
            //If still current month is not ended, then penalty will be 0
            $response['penalty'] = 0;
            //If still current month is not ended, then payable will be due amt
            $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
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
            //To raise penalty in seperate table;
            $start_date_obj->add($interval);
            $count++; //Count represents how many months are exceeded
        }
        $penalty_counter = 0;
        if ($screen == 'auto_update') {
             if ($current_date_obj > $end_date_obj) {
                    $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count in collection
                }
            while ($start_date_obj <= $current_date_obj) { // To find loan date count till now from start date.
                $penalty_checking_date = $start_date_obj->format('Y-m-d'); // This format is for query.. month , year function accept only if (Y-m-d).
                $penalty_date = $start_date_obj->format('Y-m-d');

                $checkcollection = $connect->query("SELECT 
                    SUM(due_amt_track) as total_paid,
                    SUM(pre_close_waiver) as total_pre 
                FROM 
                    `collection` 
                WHERE 
                    `req_id` = '$req_id' 
                    AND (YEAR(`coll_date`) <= YEAR('" . $penalty_checking_date . "') AND date(`coll_date`) <= date('" . $penalty_checking_date . "'))
                ");
                $coll_row = $checkcollection->fetch();
                $totalPaidAmt = $coll_row['total_paid']; // Checking whether the collection are inserted on date or not by using penalty_raised_date.
                $totalPreAmt = $coll_row['total_pre']; // Checking whether the collection are inserted on date or not by using penalty_raised_date.

                $pending_for_penalty = $response['due_amt'] * $penalty_counter - $totalPaidAmt - $totalPreAmt;


                if ($loan_arr['scheme_name'] == '' || $loan_arr['scheme_name'] == null) {
                    $result = $connect->query("SELECT overdue FROM `loan_calculation` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                } else {
                    $result = $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' and sub_category = '" . $loan_arr['sub_category'] . "' ");
                }
                $row = $result->fetch();
                $penalty_per = $row['overdue']; //get penalty percentage to insert

                if ($pending_for_penalty > 0) {
                    $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$penalty_date' and req_id = '$req_id' ");
                    if ($checkPenalty->rowCount() == 0) {
                        $penalty = round((($pending_for_penalty * $penalty_per) / 100));
                        if ($loan_arr['loan_type'] == 'emi') {
                            //if loan type is emi then directly apply penalty when month crossed and above conditions true
                            $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$req_id','$penalty_date','$penalty',current_timestamp)");
                        }
                    }
                }
                $start_date_obj->add($interval); //increase one month to loop again
                if ($penalty_counter < $count) {
                    $penalty_counter++;
                }
            }
        }
        $response['count_of_month'] = $count;


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
        //To check over due, if current date is greater than maturity minth, then i will be OD
        if ($current_date_obj > $end_date_obj && $due_nil_check > 0) {
            $response['od'] = true;
        } else {
            $response['od'] = false;
        }

        if ($count > 0) {

            //if Due month exceeded due amount will be as pending with how many months are exceeded and subract pre closure amount if available
            $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

            // If due month exceeded
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

            $response['penalty'] = $penalty - $row['penalty'] - $row['penalty_waiver'];

            //Payable amount will be pending amount added with current month due amount
            $response['payable'] = $response['due_amt'] + $response['pending'];

            if ($response['payable'] > $response['balance']) {
                //if payable is greater than balance then change it as balance amt coz dont collect more than balance
                //this case will occur when collection status becoms OD
                $response['payable'] = $response['balance'];
            }
        } else {
            //If still current month is not ended, then pending will be same due amt// pending will be 0 if due date not exceeded
            $response['pending'] = 0; //$response['due_amt'] - $response['total_paid'] - $response['pre_closure'] ;
            //If still current month is not ended, then penalty will be 0
            $response['penalty'] = 0;
            //If still current month is not ended, then payable will be due amt
            $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
        }
    }
    if ($response['pending'] < 0) {
        $response['pending'] = 0;
    }
    if ($response['payable'] < 0) {
        $response['payable'] = 0;
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
        $Interest_paid = getPaidInterest($connect, $req_id);

        $res['payable'] = payableCalculation($connect, $loan_arr, $response, $req_id) - $Interest_paid;
        $res['till_date_int'] = getTillDateInterest($loan_arr, $response, $connect, 'curmonth', $req_id) - $Interest_paid;
        $res['pending'] = pendingCalculation($connect, $loan_arr, $response, $req_id) - $Interest_paid;

        if ($res['pending'] < 0) {
            $res['pending'] = 0;
        }
        if ($res['payable'] < 0) {
            $res['payable'] = 0;
        }

        $res['penalty'] = getPenaltyCharges($connect, $req_id);
    } else {
        //in this calculate till date Interest when month are not crossed for due starting month
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
function calculateNewInterestAmt($int_rate, $balance, $calculate_method)
{
    if ($calculate_method == 'Monthly') {
        $int = $balance * ($int_rate / 100);
    } else if ($calculate_method == 'Days') {
        $int = ($balance * ($int_rate / 100) / 30);
    }

    $curInterest = ceil($int / 5) * 5; //to increase Interest to nearest multiple of 5
    if ($curInterest < $int) {
        $curInterest += 5;
    }
    $response = $curInterest;

    return $response;
}

function dueAmtCalculation($connect, $start_date, $end_date, $due_amt, $loan_arr, $status, $req_id)
{
    $start = new DateTime($start_date->format('Y-m-d'));
    $end = new DateTime($end_date->format('Y-m-d'));

    $calculate_method = $loan_arr['calc_method'];
    $int_rate = $loan_arr['int_rate'];
    $loan_category = $loan_arr['loan_category'];
    $result = 0;
    $monthly_Interest_data = [];

    $loanRow = $connect->query("SELECT loan_amt FROM acknowlegement_loan_calculation WHERE req_id = '$req_id'")->fetch(PDO::FETCH_ASSOC);
    $default_balance = $loanRow['loan_amt'];

    $collections = $connect->query("SELECT princ_amt_track, principal_waiver, coll_date FROM collection 
        WHERE req_id = '$req_id' AND (princ_amt_track != '' OR principal_waiver != '') ORDER BY coll_date ASC")->fetchAll();

    if (!empty($collections)) {

        // <---------------------------------------------------------------- IF COLLECTIONS EXIST ------------------------------------------------------------>

        $collection_index = 0;
        $current_balance = $default_balance;

        while ($start <= $end) {
            $today_str = $start->format('Y-m-d');
            $month_key = $start->format('Y-m-01');
            $paid_principal_today = 0;
            $paid_principal_waiver = 0;

            while ($collection_index < count($collections)) {
                $collection = $collections[$collection_index];
                $coll_date = (new DateTime($collection['coll_date']))->format('Y-m-d');
                if ($coll_date == $today_str) {
                    $paid_principal_today += (float)$collection['princ_amt_track'];
                    $paid_principal_waiver += (float)$collection['principal_waiver'];
                    $collection_index++;
                } else {
                    break;
                }
            }

            $current_balance = max(0, $current_balance - ($paid_principal_today + $paid_principal_waiver));

            $Interest_today = calculateNewInterestAmt($int_rate, $current_balance, $calculate_method);

            if ($calculate_method === 'Days') {
                $result += $Interest_today;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $Interest_today;
            } else {
                $days_in_month = (int)$start->format('t');
                $daily_Interest = $Interest_today / $days_in_month;
                $result += $daily_Interest;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $daily_Interest;
            }

            $start->modify('+1 day');
        }
    } else {
        $monthly_Interest_data = [];

        if ($calculate_method == 'Monthly') {
            while ($start->format('Y-m') <= $end->format('Y-m')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $due_amt / intval($start->format('t'));

                if ($status != 'pending') {
                    if ($start->format('m') != $end->format('m')) {
                        $new_end_date = clone $start;
                        $new_end_date->modify('last day of this month');
                        $cur_result = (($start->diff($new_end_date))->days + 1) * $dueperday;
                    } else {
                        $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    }
                } else {
                    $new_end = clone $start;
                    $new_end->modify("last day of this month");
                    $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                }

                $result += $cur_result;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $cur_result;
                $start->modify('+1 month');
                $start->modify('first day of this month');
            }
        } else if ($calculate_method == 'Days') {
            while ($start->format('Y-m-d') <= $end->format('Y-m-d')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $due_amt;
                $result += $dueperday;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $dueperday;

                $start->modify('+1 day');
            }
        }
    }

    // <------------------------------------------------------------------- Penalty Logic ----------------------------------------------------------------->

    if ($status === 'pending') {

        $penaltyRow = $connect->query("SELECT overdue_type, overdue FROM loan_calculation WHERE loan_category = '" . $loan_arr['loan_category'] . "' ")->fetch(PDO::FETCH_ASSOC);

        $penalty_val  = $penaltyRow['overdue'] ?? 0;
        $penalty_type = strtolower(trim($penaltyRow['overdue_type'] ?? 'percentage'));

        $monthly_unpaid = [];
        $monthly_first_date = [];

        $current_month = date('Y-m'); // current month key

        foreach ($monthly_Interest_data as $penalty_date => $cur_result) {
            $month_key = date('Y-m', strtotime($penalty_date));
            //  skip current month
            if ($month_key === $current_month) {
                continue;
            }

            $paid_Interest = getPaidInterest($connect, $req_id);
            $unpaid_interest = max(0, $cur_result - $paid_Interest);

            if ($unpaid_interest > 0) {
                if (!isset($monthly_unpaid[$month_key])) {
                    $monthly_unpaid[$month_key] = 0;
                    $monthly_first_date[$month_key] = $penalty_date;
                }
                $monthly_unpaid[$month_key] += $unpaid_interest;
            }
        }

        // Step 2: Apply penalty only for past months
        foreach ($monthly_unpaid as $month => $unpaid) {
            if ($unpaid > 0 && $penalty_val > 0) {
                $penalty = ($penalty_type === 'amt') ? round($penalty_val) : round(($unpaid * $penalty_val) / 100);

                $first_date = $monthly_first_date[$month];

                $checkPenalty = $connect->query("SELECT 1 FROM penalty_charges WHERE penalty_date = '$first_date' AND req_id = '$req_id'");

                if ($checkPenalty->rowCount() == 0) {
                    $insertQry = $connect->prepare("INSERT INTO penalty_charges (req_id, penalty_date, penalty, created_date) VALUES (?, ?, ?, NOW())");
                    $insertQry->execute([$req_id, $penalty_date, $penalty]);
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

    $result = 0;
    if ($response['calculate_method'] == "Monthly") {
        $last_month = clone $cur_date;
        $last_month->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_month->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date; // Due to mutation in function

            $result += dueAmtCalculation($connect, $start, $end_date, $response['due_amt'], $loan_arr, 'payable', $req_id);

            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
    } elseif ($response['calculate_method'] == "Days") {
        $last_date = clone $cur_date;
        $last_date->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_date->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date;

            $result += dueAmtCalculation($connect, $start, $end_date, $response['due_amt'], $loan_arr, 'payable', $req_id);
            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
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

        //to calculate till date Interest if loan is interst based
        if ($loan_arr['loan_type'] == 'Interest') {

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
    $qry = $connect->query("SELECT COALESCE(SUM(int_amt_track), 0) + COALESCE(SUM(interest_waiver), 0) AS int_paid FROM `collection` WHERE req_id = '$req_id' and (int_amt_track != '' and int_amt_track IS NOT NULL OR interest_waiver != '' and interest_waiver IS NOT NULL) ");
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

function checkStatusOfCustomer($response, $loan_arr, $cus_id, $connect)
{
    for ($i = 0; $i < count($response['pending_customer']); $i++) {

        // $pending_sts = ($response['pending_customer'][$i] == 1) ? "true" : "false";
        // $od_sts = ($response['od_customer'][$i] == 1) ? "true" : "false";
        // $due_nil_sts = ($response['due_nil_customer'][$i] == 1) ? "true" : "false";
        // $closed_sts = ($response['closed_customer'][$i] == 1) ? "true" : "false";


        $query = $connect->query("SELECT cus_status FROM in_issue WHERE cus_id = '$cus_id' and (cus_status >= 14 and cus_status < 20) ");
        $row = $query->fetch();
        $curdate = date('Y-m-d');
        // if($query->rowCount() > 0){
        //     $cus_status = ($query->fetch()['cus_status'] == '16') ? "Legal":"Error";
        //     $response['follow_cus_sts'] = $cus_status;
        // }else
        // if($pending_sts == 'true' && $od_sts == 'false'){
        //     $response['follow_cus_sts'] = 'Pending';
        // }else if($od_sts == 'true' && $due_nil_sts =='false'){
        //     $response['follow_cus_sts'] = 'OD';
        // }elseif($due_nil_sts == 'true'){
        //     $response['follow_cus_sts'] = 'Due Nil';
        // }elseif($pending_sts == 'false'){
        //     if($closed_sts == 'true'){
        //         $response['follow_cus_sts'] = "Move To Close";
        //     }else{
        //         $response['follow_cus_sts'] = 'Current';
        //     }
        // }
        // print_r($response['pending_customer']);
        if (date('Y-m-d', strtotime($loan_arr['due_start_from'])) > date('Y-m-d', strtotime($curdate))) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
            if ($row['cus_status'] == '15') {
                $response['follow_cus_sts'] = 'Error';
            } elseif ($row['cus_status'] == '16') {
                $response['follow_cus_sts'] = 'Legal';
            } else {
                $response['follow_cus_sts'] = 'Current';
            }
        } else {
            if ($response['pending_customer'][$i] == true && $response['od_customer'][$i] == false) { //using i as 1 so subract it with 1
                if ($row['cus_status'] == '15') {
                    $response['follow_cus_sts'] = 'Error';
                } elseif ($row['cus_status'] == '16') {
                    $response['follow_cus_sts'] = 'Legal';
                } else {
                    $response['follow_cus_sts'] = 'Pending';
                }
            } else if ($response['od_customer'][$i] == true && $response['due_nil_customer'][$i] == false) {
                if ($row['cus_status'] == '15') {
                    $response['follow_cus_sts'] = 'Error';
                } elseif ($row['cus_status'] == '16') {
                    $response['follow_cus_sts'] = 'Legal';
                } else {
                    $response['follow_cus_sts'] = 'OD';
                }
            } elseif ($response['due_nil_customer'][$i] == true) {
                if ($row['cus_status'] == '15') {
                    $response['follow_cus_sts'] = 'Error';
                } elseif ($row['cus_status'] == '16') {
                    $response['follow_cus_sts'] = 'Legal';
                } else {
                    $response['follow_cus_sts'] = 'Due Nil';
                }
            } elseif ($response['pending_customer'][$i] == false) {
                if ($row['cus_status'] == '15') {
                    $response['follow_cus_sts'] = 'Error';
                } elseif ($row['cus_status'] == '16') {
                    $response['follow_cus_sts'] = 'Legal';
                } else {
                    if ($response['closed_customer'][$i] == true) {
                        $response['follow_cus_sts'] = "Closed";
                    } else {
                        $response['follow_cus_sts'] = 'Current';
                    }
                }
            }
        }
    }
    return $response['follow_cus_sts'];
}


echo json_encode($response);

// Close the database connection
$connect = null;
