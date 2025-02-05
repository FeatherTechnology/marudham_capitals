<?php
class GetLoanDetails
{
    public $response, $req_id, $ddate, $use;
    function __construct($connect, $req_id, $ddate, $use)
    {


        $this->req_id = $req_id;
        $this->ddate = $ddate;
        $this->use = $use;

        // Caution **** Dont Touch any code below..
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

        $result = $connect->query("SELECT * FROM `acknowlegement_loan_calculation` WHERE req_id = $this->req_id ");
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $loan_arr = $row;

            if ($loan_arr['tot_amt_cal'] == '' || $loan_arr['tot_amt_cal'] == null) {
                //(For monthly interest total amount will not be there, so take principals)
                $response['total_amt'] = $loan_arr['principal_amt_cal'];
                $response['loan_type'] = 'interest';
                $loan_arr['loan_type'] = 'interest';
            } else {
                $response['total_amt'] = $loan_arr['tot_amt_cal'];
                $response['loan_type'] = 'emi';
                $loan_arr['loan_type'] = 'emi';
            }


            if ($loan_arr['due_amt_cal'] == '' || $loan_arr['due_amt_cal'] == null) {
                //(For monthly interest Due amount will not be there, so take interest)
                $response['due_amt'] = $loan_arr['int_amt_cal'];
            } else {
                $response['due_amt'] = $loan_arr['due_amt_cal']; //Due amount will remain same
            }


            $qry = $connect->query("SELECT updated_date FROM `in_issue` WHERE req_id = $this->req_id ");
            $loan_arr['loan_date'] = date('Y-m-d', strtotime($qry->fetch()['updated_date']));
        }
        $coll_arr = array();
        $coll_qry = "SELECT due_amt_track,pre_close_waiver,princ_amt_track,int_amt_track FROM `collection` WHERE req_id = $this->req_id and DATE(coll_date) <= DATE('" . date('Y-m-01', strtotime($this->ddate)) . "')";
        if ($this->use == 'Collection') {
            $coll_qry = "SELECT due_amt_track,pre_close_waiver,princ_amt_track,int_amt_track FROM `collection` WHERE req_id = $this->req_id and DATE(coll_date) <= DATE('" . date('Y-m-d', strtotime($this->ddate)) . "')";
        }
        $result = $connect->query($coll_qry);
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
                $pre_closure += intVal($tot['pre_close_waiver']); //get pre closure value to subract to get balance amount
                $total_paid_princ += intVal($tot['princ_amt_track']);
                $total_paid_int += intVal($tot['int_amt_track']);
            }
            //total paid amount will be all records again request id should be summed
            $response['total_paid'] = ($loan_arr['loan_type'] == 'emi') ? $total_paid : $total_paid_princ;
            $response['total_paid_int'] = $total_paid_int;
            $response['pre_closure'] = $pre_closure;

            //total amount subracted by total paid amount and subracted with pre closure amount will be balance to be paid
            $response['balance'] = $response['total_amt'] - $response['total_paid'] - $pre_closure;

            if ($loan_arr['loan_type'] == 'interest') {
                $response['due_amt_for1'] = $response['due_amt'];
                $response['due_amt'] = $this->calculateNewInterestAmt($loan_arr['int_rate'], $response['balance']);
            }

            $response = $this->calculateOthers($loan_arr, $response, $connect);
        } else {
            //If collection table dont have rows means there is no payment against that request, so total paid will be 0
            $response['total_paid'] = 0;
            $response['total_paid_int'] = 0;
            $response['pre_closure'] = 0;
            //If in collection table, there is no payment means balance amount still remains total amount
            $response['balance'] = $response['total_amt'];

            if ($loan_arr['loan_type'] == 'interest') {
                $response['due_amt_for1'] = $response['due_amt'];
                $response['due_amt'] = $this->calculateNewInterestAmt($loan_arr['int_rate'], $response['balance']);
            }

            $response = $this->calculateOthers($loan_arr, $response, $connect);
        }



        //To get the collection charges
        $result = $connect->query("SELECT SUM(coll_charge) as coll_charge FROM `collection_charges` WHERE req_id = '" . $this->req_id . "' ");
        $row = $result->fetch();
        if ($row['coll_charge'] != null) {

            $coll_charges = $row['coll_charge'];

            $result = $connect->query("SELECT SUM(coll_charge_track) as coll_charge_track,SUM(coll_charge_waiver) as coll_charge_waiver FROM `collection` WHERE req_id = '" . $this->req_id . "' ");
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

        $this->response = $response;
    }
    function calculateOthers($loan_arr, $response, $connect)
    {
        $due_start_from = $loan_arr['due_start_from'];
        $maturity_month = $loan_arr['maturity_month'];



        if ($loan_arr['due_method_calc'] == 'Monthly' || $loan_arr['due_method_scheme'] == '1') {

            if ($loan_arr['loan_type'] != 'interest') {
                //Convert Date to Year and month, because with date, it will use exact date to loop months, instead of taking end of month
                $due_start_from = date('Y-m', strtotime($due_start_from));
                $maturity_month = date('Y-m', strtotime($maturity_month));

                // Create a DateTime object from the given date
                $maturity_month = new DateTime($maturity_month);
                // Subtract one month from the date
                // $maturity_month->modify('-1 month');
                // Format the date as a string
                $maturity_month = $maturity_month->format('Y-m');

                //If Due method is Monthly, Calculate penalty by checking the month has ended or not
                $current_date = date('Y-m', strtotime($this->ddate));

                $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
                $end_date_obj = DateTime::createFromFormat('Y-m', $maturity_month);
                $current_date_obj = DateTime::createFromFormat('Y-m', $current_date);

                $interval = new DateInterval('P1M'); // Create a one month interval

                //condition start
                $count = 0;

                while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) { // To find loan date count till now from start date.
                    $start_date_obj->add($interval); //increase one month to loop again
                    $count++; //Count represents how many months are exceeded
                }
                if ($this->use == 'Collection' && $current_date_obj > $end_date_obj) {
                    $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count in collection
                }

                $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
                $penalty_counter = 0;
                while ($start_date_obj <= $current_date_obj) { // To find loan date count till now from start date.
                    $penalty_checking_date = $start_date_obj->format('Y-m-01'); // This format is for query.. month , year function accept only if (Y-m-d).
                    $penalty_date = $start_date_obj->format('Y-m');

                    $checkcollection = $connect->query("SELECT SUM(due_amt_track) as total_paid, SUM(pre_close_waiver) as total_pre 
                    FROM `collection` 
                    WHERE `req_id` = '$this->req_id' 
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
                        $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$penalty_date' and req_id = '$this->req_id' ");
                        if ($checkPenalty->rowCount() == 0) {
                            $penalty = round((($pending_for_penalty * $penalty_per) / 100));
                            if ($loan_arr['loan_type'] == 'emi') {
                                //if loan type is emi then directly apply penalty when month crossed and above conditions true
                                $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$this->req_id','$penalty_date','$penalty',current_timestamp)");
                            }
                        }
                    }
                    $start_date_obj->add($interval); //increase one month to loop again
                    if ($penalty_counter < $count) {
                        $penalty_counter++;
                    }
                }

                //condition END
                if ($count > 0) {

                    //if Due month exceeded due amount will be as pending with how many months are exceeded and subract pre closure amount if available
                    $response['pending'] = ($response['due_amt'] * ($count)) - $response['total_paid'] - $response['pre_closure'];

                    // to get overall penalty paid till now to show pending penalty amount
                    $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $this->req_id . "' ");
                    $row = $result->fetch();
                    if ($row['penalty'] == null) {
                        $row['penalty'] = 0;
                    }
                    if ($row['penalty_waiver'] == null) {
                        $row['penalty_waiver'] = 0;
                    }
                    //to get overall penalty raised till now for this req id
                    $result1 = $connect->query("SELECT SUM(penalty) as penalty FROM `penalty_charges` WHERE req_id = '" . $this->req_id . "' ");
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

                    //in this calculate till date interest when month are crossed for current month
                    $response['till_date_int'] = $this->getTillDateInterest($loan_arr, $response, $connect, 'from01');
                } else {
                    //If still current month is not ended, then pending will be same due amt // pending will be 0 if due date not exceeded
                    $response['pending'] = 0; // $response['due_amt'] - $response['total_paid'] - $response['pre_closure'] ;
                    //If still current month is not ended, then penalty will be 0
                    $response['penalty'] = 0;
                    //If still current month is not ended, then payable will be due amt
                    $response['payable'] = $response['due_amt'] - $response['total_paid'] - $response['pre_closure'];
                }
            } else {

                $interest_details = $this->calculateInterestLoan($connect, $loan_arr, $response);
                $all_data = array_merge($response, $interest_details);
                $response = $all_data;
            }
        } else
            if ($loan_arr['due_method_scheme'] == '2') {

            //If Due method is Weekly, Calculate penalty by checking the month has ended or not
            $current_date = date('Y-m-d', strtotime($this->ddate));

            $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_start_from);
            $end_date_obj = DateTime::createFromFormat('Y-m-d', $maturity_month);
            $current_date_obj = DateTime::createFromFormat('Y-m-d', $current_date);

            $interval = new DateInterval('P1W'); // Create a one Week interval

            // $qry = $connect->query("DELETE FROM penalty_charges where req_id = '$this->req_id' and (penalty_date != '' or penalty_date != NULL ) ");
            //condition start
            $count = 0;

            while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) { // To find loan date count till now from start date.
                $start_date_obj->add($interval); //increase one month to loop again
                $count++; //Count represents how many months are exceeded
            }
            if ($this->use == 'Collection' && $current_date_obj > $end_date_obj) {
                $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count in collection
            }

            $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_start_from);
            $penalty_counter = 0;
            while ($start_date_obj <= $current_date_obj) { // To find loan date count till now from start date.
                $penalty_checking_date = $start_date_obj->format('Y-m-d'); // This format is for query.. month , year function accept only if (Y-m-d).
                $penalty_date = $start_date_obj->format('Y-m-d');

                $checkcollection = $connect->query("SELECT 
                    SUM(due_amt_track) as total_paid,
                    SUM(pre_close_waiver) as total_pre 
                FROM 
                    `collection` 
                WHERE 
                    `req_id` = '$this->req_id' 
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
                    $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$penalty_date' and req_id = '$this->req_id' ");
                    if ($checkPenalty->rowCount() == 0) {
                        $penalty = round((($pending_for_penalty * $penalty_per) / 100));
                        if ($loan_arr['loan_type'] == 'emi') {
                            //if loan type is emi then directly apply penalty when month crossed and above conditions true
                            $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$this->req_id','$penalty_date','$penalty',current_timestamp)");
                        }
                    }
                }
                $start_date_obj->add($interval); //increase one month to loop again
                if ($penalty_counter < $count) {
                    $penalty_counter++;
                }
            }
            //condition END

            if ($count > 0) {

                //if Due month exceeded due amount will be as pending with how many months are exceeded and subract pre closure amount if available
                $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

                // to get overall penalty paid till now to show pending penalty amount
                $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $this->req_id . "' ");
                $row = $result->fetch();
                if ($row['penalty'] == null) {
                    $row['penalty'] = 0;
                }
                if ($row['penalty_waiver'] == null) {
                    $row['penalty_waiver'] = 0;
                }
                //to get overall penalty raised till now for this req id
                $result1 = $connect->query("SELECT SUM(penalty) as penalty FROM `penalty_charges` WHERE req_id = '" . $this->req_id . "' ");
                $row1 = $result1->fetch();
                if ($row1['penalty'] == null) {
                    $penalty = 0;
                } else {
                    $penalty = $row1['penalty'];
                }

                // $penalty = intval((($response['due_amt'] * $penalty_per) / 100));

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
            $current_date = date('Y-m-d', strtotime($this->ddate));

            $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_start_from);
            $end_date_obj = DateTime::createFromFormat('Y-m-d', $maturity_month);
            $current_date_obj = DateTime::createFromFormat('Y-m-d', $current_date);

            $interval = new DateInterval('P1D'); // Create a one Week interval

            // $qry = $connect->query("DELETE FROM penalty_charges where req_id = '$this->req_id' and (penalty_date != '' or penalty_date != NULL ) ");

            //condition start
            $count = 0;

            while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) { // To find loan date count till now from start date.
                $start_date_obj->add($interval); //increase one month to loop again
                $count++; //Count represents how many months are exceeded
            }
            if ($this->use == 'Collection' && $current_date_obj > $end_date_obj) {
                $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count in collection
            }

            $start_date_obj = DateTime::createFromFormat('Y-m-d', $due_start_from);
            $penalty_counter = 0;
            while ($start_date_obj <= $current_date_obj) { // To find loan date count till now from start date.
                $penalty_checking_date = $start_date_obj->format('Y-m-d'); // This format is for query.. month , year function accept only if (Y-m-d).
                $penalty_date = $start_date_obj->format('Y-m-d');

                $checkcollection = $connect->query("SELECT 
                    SUM(due_amt_track) as total_paid,
                    SUM(pre_close_waiver) as total_pre 
                FROM 
                    `collection` 
                WHERE 
                    `req_id` = '$this->req_id' 
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
                    $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$penalty_date' and req_id = '$this->req_id' ");
                    if ($checkPenalty->rowCount() == 0) {
                        $penalty = round((($pending_for_penalty * $penalty_per) / 100));
                        if ($loan_arr['loan_type'] == 'emi') {
                            //if loan type is emi then directly apply penalty when month crossed and above conditions true
                            $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('$this->req_id','$penalty_date','$penalty',current_timestamp)");
                        }
                    }
                }
                $start_date_obj->add($interval); //increase one month to loop again
                if ($penalty_counter < $count) {
                    $penalty_counter++;
                }
            }
            //condition END


            if ($count > 0) {
                //if Due month exceeded due amount will be as pending with how many months are exceeded and subract pre closure amount if available
                $response['pending'] = ($response['due_amt'] * $count) - $response['total_paid'] - $response['pre_closure'];

                // to get overall penalty paid till now to show pending penalty amount
                $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $this->req_id . "' ");
                $row = $result->fetch();
                if ($row['penalty'] == null) {
                    $row['penalty'] = 0;
                }
                if ($row['penalty_waiver'] == null) {
                    $row['penalty_waiver'] = 0;
                }
                //to get overall penalty raised till now for this req id
                $result1 = $connect->query("SELECT SUM(penalty) as penalty FROM `penalty_charges` WHERE req_id = '" . $this->req_id . "' ");
                $row1 = $result1->fetch();
                if ($row1['penalty'] == null) {
                    $penalty = 0;
                } else {
                    $penalty = $row1['penalty'];
                }

                // $penalty = intval((($response['due_amt'] * $penalty_per) / 100));

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
    function calculateInterestLoan($connect, $loan_arr, $response)
    {

        $due_start_from = $loan_arr['loan_date'];
        $maturity_month = $loan_arr['maturity_month'];



        $checkcollection = $connect->query("SELECT SUM(`int_amt_track`) as totalPaidAmt FROM `collection` WHERE `req_id` = '$this->req_id'"); // To Find total paid amount till Now.
        $checkrow = $checkcollection->fetch();
        $totalPaidAmt = $checkrow['totalPaidAmt'] ?? 0; //null collation operator
        $checkack = $connect->query("SELECT int_amt_cal,due_amt_cal FROM `acknowlegement_loan_calculation` WHERE `req_id` = '$this->req_id'"); // To Find Due Amount.
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
        $current_date = date('Y-m', strtotime($this->ddate));

        $start_date_obj = DateTime::createFromFormat('Y-m', $due_start_from);
        $end_date_obj = DateTime::createFromFormat('Y-m', $maturity_month);
        $current_date_obj = DateTime::createFromFormat('Y-m', $current_date);

        $interval = new DateInterval('P1M'); // Create a one month interval

        //condition start
        $count = 0;


        while ($start_date_obj < $end_date_obj && $start_date_obj < $current_date_obj) {

            $start_date_obj->add($interval); //increase one month to loop again
            $count++; //Count represents how many months are exceeded
        }
        if ($start_date_obj >= $end_date_obj) {
            $count++; //because if the maturity date crossed the pending amount should have the maturity month's amount also so add 1month to count
        }

        if ($count > 0) {
            $interest_paid = $this->getPaidInterest($connect);

            $res['payable'] = $this->payableCalculation($connect, $loan_arr, $response) - $interest_paid;
            $res['till_date_int'] = $this->getTillDateInterest($loan_arr, $response, $connect, 'curmonth') - $interest_paid;
            $res['pending'] = $this->pendingCalculation($connect, $loan_arr, $response) - $interest_paid;

            if ($res['pending'] < 0) {
                $res['pending'] = 0;
            }
            if ($res['payable'] < 0) {
                $res['payable'] = 0;
            }

            $res['penalty'] = $this->getPenaltyCharges($connect);
        } else {
            //in this calculate till date interest when month are not crossed for due starting month
            $res['till_date_int'] = $this->getTillDateInterest($loan_arr, $response, $connect, 'forstartmonth');
            $res['pending'] = 0;
            $res['payable'] =  0;
            $res['penalty'] = 0;
        }

        $res['payable'] = $this->ceilAmount($res['payable']);
        $res['pending'] = $this->ceilAmount($res['pending']);
        $res['till_date_int'] = $this->ceilAmount($res['till_date_int']);
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
    function dueAmtCalculation($connect, $start_date, $end_date, $due_amt, $loan_arr, $status)
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
        $qry = $connect->query("SELECT princ_amt_track FROM `collection` WHERE req_id = '" . $this->req_id . "' and princ_amt_track != '' ORDER BY coll_date ASC ");
        if ($qry->rowCount() > 0) {

            while ($start->format('m') <= $end->format('m')) {


                $penalty = 0;
                $start_for_penalty = $start->format('Y-m');

                $qry = $connect->query("SELECT princ_amt_track as princ,bal_amt, coll_date FROM `collection` WHERE req_id = '" . $this->req_id . "' and princ_amt_track != '' and month(coll_date) = month('" . $start->format('Y-m-d') . "') and year(coll_date) = year('" . $start->format('Y-m-d') . "') ORDER BY coll_date ASC ");
                if ($qry->rowCount() > 0) {

                    while ($row = $qry->fetch()) {
                        $princ = $row['princ'];
                        $bal_amt = $row['bal_amt'];
                        $coll_date = new DateTime($row['coll_date']);

                        $due_amt = $this->calculateNewInterestAmt($int_rate, $bal_amt);
                        $bal_amt = $bal_amt - $princ;
                        $dueperday = $due_amt / intval($start->format('t'));
                        $cur_result = (($start->diff($coll_date))->days) * $dueperday;
                        $result += $cur_result;

                        unset($start); //unset to remove as obj // so can reinitialize
                        $start = new DateTime($coll_date->format('Y-m-d'));
                        unset($coll_date); //unset to remove as obj // so can reinitialize
                    }
                } else {
                    $qry = $connect->query("SELECT princ_amt_track as princ,bal_amt, coll_date FROM `collection` WHERE req_id = '" . $this->req_id . "' and princ_amt_track != '' and month(coll_date) < month('" . $start->format('Y-m-d') . "') and year(coll_date) <= year('" . $start->format('Y-m-d') . "') ORDER BY coll_date ASC LIMIT 1");
                    if ($qry->rowCount() > 0) {
                        $row = $qry->fetch();
                        $princ = $row['princ'];
                        $bal_amt = $row['bal_amt'];
                        $bal_amt = $bal_amt - $princ;
                    } else {
                        $qry = $connect->query("SELECT principal_amt_cal from acknowlegement_loan_calculation where req_id = '" . $this->req_id . "' ");
                        $row = $qry->fetch();
                        $bal_amt = $row['principal_amt_cal'];
                    }
                }

                $due_amt = $this->calculateNewInterestAmt($int_rate, $bal_amt);
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
                        $ovqry =  $connect->query("SELECT overdue FROM `loan_scheme` WHERE loan_category = '" . $loan_arr['loan_category'] . "' AND FIND_IN_SET('" . $loan_arr['sub_category'] . "', sub_category)");
                    }
                    $row = $ovqry->fetch();
                    $penalty_per = $row['overdue']; //get penalty percentage to insert

                    $paid_interest = $this->getPaidInterest($connect);
                    if ($paid_interest > 0) {
                        //raise penalty if the customer paid something
                        $cur_result =  $cur_result - $paid_interest;
                        if ($cur_result < 0) { //if the cur result is negative then the interest of the month has been paid already
                            $cur_result = 0;
                        }
                    }

                    $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$start_for_penalty' and req_id = '" . $this->req_id . "' ");
                    if ($checkPenalty->rowCount() == 0) {
                        $penalty = round((($cur_result * $penalty_per) / 100) + $penalty);
                        if ($cur_result != 0) {
                            $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('" . $this->req_id . "','" . date('Y-m', strtotime($start_for_penalty)) . "','$penalty',now())");
                        }
                    }
                }
            }
        } else {
            while ($start->format('m') <= $end->format('m')) {

                $penalty = 0;
                $start_for_penalty = $start->format('Y-m');

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

                    $paid_interest = $this->getPaidInterest($connect);
                    if ($paid_interest > 0) {
                        //raise penalty if the customer paid something
                        $cur_result =  $cur_result - $paid_interest;
                        if ($cur_result < 0) { //if the cur result is negative then the interest of the month has been paid already
                            $cur_result = 0;
                        }
                    }

                    $checkPenalty = $connect->query("SELECT * from penalty_charges where penalty_date = '$start_for_penalty' and req_id = '" . $this->req_id . "' ");
                    if ($checkPenalty->rowCount() == 0) {
                        $penalty = round((($cur_result * $penalty_per) / 100) + $penalty);
                        if ($cur_result != 0) {
                            $qry = $connect->query("INSERT into penalty_charges (`req_id`,`penalty_date`, `penalty`, `created_date`) values ('" . $this->req_id . "','" . date('Y-m', strtotime($start_for_penalty)) . "','$penalty',now())");
                        }
                    }
                }
            }
        }
        return $result;
    }
    function payableCalculation($connect, $loan_arr, $response)
    {
        $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
        $cur_date = new DateTime(date('Y-m-d', strtotime($this->ddate)));
        $last_month = clone $cur_date;
        $last_month->modify('-1 month');

        $result = 0;
        $st_date = clone $issued_date;
        while ($st_date->format('m') <= $last_month->format('m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date; //because the function calling below will change the root of starting date

            $result += $this->dueAmtCalculation($connect, $start, $end_date, $response['due_amt'], $loan_arr, 'payable');

            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }

        return $result;
    }
    function pendingCalculation($connect, $loan_arr, $response)
    {
        $pending = $this->getTillDateInterest($loan_arr, $response, $connect, 'pendingmonth');
        return $pending;
    }
    function getTillDateInterest($loan_arr, $response, $connect, $data)
    {

        if ($data == 'forstartmonth') {

            //to calculate till date interest if loan is interst based
            if ($loan_arr['loan_type'] == 'interest') {

                //get the loan isued month's date count
                $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));

                //current month's total date
                $cur_date = new DateTime(date('Y-m-d', strtotime($this->ddate)));

                $result = $this->dueAmtCalculation($connect, $issued_date, $cur_date, $response['due_amt'], $loan_arr, '');
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
            $cur_date = new DateTime(date('Y-m-d', strtotime($this->ddate)));
            $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));


            $result = $this->dueAmtCalculation($connect, $issued_date, $cur_date, $response['due_amt'], $loan_arr, 'TDI');
            return $result;
        }
        if ($data == 'pendingmonth') {
            //for pending value check, goto 2 months before
            //bcoz last month value is on payable, till date int will be on cur date
            $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
            $cur_date = new DateTime(date('Y-m-d', strtotime($this->ddate)));
            $cur_date->modify('-2 months');
            $cur_date->modify('last day of this month');
            $result = 0;

            if ($issued_date->format('m') <= $cur_date->format('m')) {
                $result = $this->dueAmtCalculation($connect, $issued_date, $cur_date, $response['due_amt'], $loan_arr, 'pending');
            }
            return $result;
        }

        return $response;
    }
    function getPaidInterest($connect)
    {
        $qry = $connect->query("SELECT SUM(int_amt_track) as int_paid FROM `collection` WHERE req_id = '$this->req_id' and (int_amt_track != '' and int_amt_track IS NOT NULL) ");
        $int_paid = $qry->fetch()['int_paid'];
        return intVal($int_paid);
    }
    function getPenaltyCharges($connect)
    {
        // to get overall penalty paid till now to show pending penalty amount
        $result = $connect->query("SELECT SUM(penalty_track) as penalty,SUM(penalty_waiver) as penalty_waiver FROM `collection` WHERE req_id = '" . $this->req_id . "' ");
        $row = $result->fetch();
        if ($row['penalty'] == null) {
            $row['penalty'] = 0;
        }
        if ($row['penalty_waiver'] == null) {
            $row['penalty_waiver'] = 0;
        }
        //to get overall penalty raised till now for this req id
        $result1 = $connect->query("SELECT SUM(penalty) as penalty FROM `penalty_charges` WHERE req_id = '" . $this->req_id . "' ");
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
}
