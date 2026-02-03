<?php
session_start();
require_once 'moneyFormatIndia.php';
if (isset($_GET['upd'])) {
    $idupd = $_GET['upd'];
}
if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}

$getBankDetails = $userObj->getBankDetails($mysqli,$user_id);

$getRequestData = $userObj->getRequestForVerification($mysqli, $idupd);
if (sizeof($getRequestData) > 0) {
    for ($i = 0; $i < sizeof($getRequestData); $i++) {
        $req_id              = $getRequestData['req_id'];
        $cus_id              = $getRequestData['cus_id'];
        $cus_name            = $getRequestData['cus_name'];
        $sub_category        = $getRequestData['sub_category'];
        $tot_value           = $getRequestData['tot_value'];
        $ad_amt              = $getRequestData['ad_amt'];
        $loan_amt            = $getRequestData['loan_amt'];
        $issue_mode          = $getRequestData['issue_mode'];
        $payment_type        = $getRequestData['payment_type'];
        //$selected_bank_id    = $getRequestData['bank_id'];
    }
}

$getCustomerReg = $userObj->getCustomerRegister($mysqli, $cus_id);
if (sizeof($getCustomerReg) > 0) { 
	$autogen_cus_id 			= $getCustomerReg['autogen_cus_id'];
}

//////////////////////// Personal Info ///////////////////////////////

$getCustomerProfile = $userObj->getAcknowlegeCustomerProfile($mysqli, $idupd);

if (sizeof($getCustomerProfile) > 0) {
    $cus_Tableid = $getCustomerProfile['cus_Tableid'];
    $cp_cus_id = $getCustomerProfile['cus_id'];
    $cp_cus_name = $getCustomerProfile['cus_name'];
    $cp_mobile1  = $getCustomerProfile['mobile1'];
    $cp_mobile2 = $getCustomerProfile['mobile2'];
    $cp_whatsapp = $getCustomerProfile['whatsapp'];
    $cp_cus_pic = $getCustomerProfile['cus_pic'];
    $guarentor_name = $getCustomerProfile['guarentor_name'];
    $guarentor_relation = $getCustomerProfile['guarentor_relation'];
    $guarentor_photo = $getCustomerProfile['guarentor_photo'];
    $cus_type = $getCustomerProfile['cus_type'];
    $cus_exist_type = $getCustomerProfile['cus_exist_type'];
    $area_confirm_state = $getCustomerProfile['area_confirm_state'];
    $area_confirm_district = $getCustomerProfile['area_confirm_district'];
    $area_confirm_taluk = $getCustomerProfile['area_confirm_taluk'];
    $area_confirm_area = $getCustomerProfile['area_confirm_area'];
    $area_confirm_subarea = $getCustomerProfile['area_confirm_subarea'];
    $verification_person = $getCustomerProfile['verification_person'];
}

$getcusInfoForDoc = $userObj->getAckcusInfoForDoc($mysqli, $idupd);
if (sizeof($getcusInfoForDoc) > 0) {

    $doc_area_name = $getcusInfoForDoc['area_name'];
    $doc_sub_area_name = $getcusInfoForDoc['sub_area_name'];
}
//////////////////////// Personal Info END ///////////////////////////////

///////// Loan Calculation ///////////////
$emicheck = 0;

//Get Loan Calculation info for edit
$getLoanCalculation = $userObj->getAckLoanCalculationForVerification($mysqli, $req_id);
if (sizeof($getLoanCalculation) > 0) {
    for ($i = 0; $i < sizeof($getLoanCalculation); $i++) {
        $loan_category_lc = $getLoanCalculation['loan_category'];
        $sub_category_lc = $getLoanCalculation['sub_category'];
        $tot_value_lc = $getLoanCalculation['tot_value'];
        $ad_amt_lc = $getLoanCalculation['ad_amt'];
        $loan_amt_lc = $getLoanCalculation['loan_amt'];
        $profit_type_lc = $getLoanCalculation['profit_type'];
        $due_type_lc = $getLoanCalculation['due_type'];
        $profit_method_lc = $getLoanCalculation['profit_method'];
        $due_method_scheme_lc = $getLoanCalculation['due_method_scheme'];
        $profit_method_scheme_lc = $getLoanCalculation['scheme_profit_method'];
        $day_scheme_lc = $getLoanCalculation['day_scheme'];
        $scheme_name_lc = $getLoanCalculation['scheme_name'];
        $int_rate_lc = $getLoanCalculation['int_rate'];
        $due_period_lc = $getLoanCalculation['due_period'];
        $doc_charge_lc = $getLoanCalculation['doc_charge'];
        $proc_fee_lc = $getLoanCalculation['proc_fee'];
        $loan_amt_cal = $getLoanCalculation['loan_amt_cal'];
        $principal_amt_cal = $getLoanCalculation['principal_amt_cal'];
        $int_amt_cal = $getLoanCalculation['int_amt_cal'];
        $tot_amt_cal = $getLoanCalculation['tot_amt_cal'];
        $due_amt_cal = $getLoanCalculation['due_amt_cal'];
        $doc_charge_cal = $getLoanCalculation['doc_charge_cal'];
        $proc_fee_cal = $getLoanCalculation['proc_fee_cal'];
        $net_cash_cal = $getLoanCalculation['net_cash_cal'];
        $due_start_from = $getLoanCalculation['due_start_from'];
        $maturity_month = $getLoanCalculation['maturity_month'];
        $collection_method = $getLoanCalculation['collection_method'];
    }

   $emicheck = strpos($due_type_lc, 'Interest') === false; 
}

///////// Loan Calculation End ///////////////
?>

<style>
    .img_show {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
        background-color: white;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        left: 10px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #009688;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
        Marudham Capitals - Loan Issue
    </div>
</div><br>
<div class="page-header sticky-top" id="navbar" style="display: none;" data-toggle="toggle">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px; margin-top:50px;">
        Customer Name - <?php if (isset($cus_name)) {
                            echo $cus_name;
                        } ?>
    </div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
    <a href="edit_accounts_loan_issue">
        <button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
    </a>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">

    <!--form start-->
    <div>
        <form id="cus_Profiles" name="cus_Profiles" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {
                                                                        echo $req_id;
                                                                    } ?>" />
            <input type="hidden" name="loan_sub_cat" id="loan_sub_cat" value="<?php if (isset($sub_category)) {
                                                                                    echo $sub_category;
                                                                                } ?>" />
            <input type="hidden" name="guarentor_name_upd" id="guarentor_name_upd" value="<?php if (isset($guarentor_name)) {
                                                                                                echo $guarentor_name;
                                                                                            } ?>" />
            <input type="hidden" name="state_upd" id="state_upd" value="<?php if (isset($area_confirm_state)) {
                                                                            echo $area_confirm_state;
                                                                        } ?>" />
            <input type="hidden" name="district_upd" id="district_upd" value="<?php if (isset($area_confirm_district)) {
                                                                                    echo $area_confirm_district;
                                                                                } ?>" />
            <input type="hidden" name="taluk_upd" id="taluk_upd" value="<?php if (isset($area_confirm_taluk)) {
                                                                            echo $area_confirm_taluk;
                                                                        } ?>" />
            <input type="hidden" name="area_upd" id="area_upd" value="<?php if (isset($area_confirm_area)) {
                                                                            echo $area_confirm_area;
                                                                        } ?>" />
            <input type="hidden" name="sub_area_upd" id="sub_area_upd" value="<?php if (isset($area_confirm_subarea)) {
                                                                                    echo $area_confirm_subarea;
                                                                                } ?>" />
            <input type="hidden" name="verification_person_upd" id="verification_person_upd" value="<?php if (isset($verification_person)) {
                                                                                                        echo $verification_person;
                                                                                                    } ?>" />
            <input type="hidden" name="cus_Tableid" id="cus_Tableid" value="<?php if (isset($cus_Tableid)) {
                                                                                echo $cus_Tableid;
                                                                            } ?>" />
            <input type="hidden" name="loan_category_lc" id="loan_category_lc" value="<?php if (isset($loan_category_lc)) {
                                                                                            echo $loan_category_lc;
                                                                                        } ?>" />
            <input type="hidden" name="sub_category_upd" id="sub_category_upd" value="<?php if (isset($sub_category_lc)) {
                                                                                            echo $sub_category_lc;
                                                                                        } ?>" />
            <input type="hidden" name="profit_type_upd" id="profit_type_upd" value="<?php if (isset($profit_type_lc)) {
                                                                                        echo $profit_type_lc;
                                                                                    } ?>" />
            <input type="hidden" name="due_method_scheme_upd" id="due_method_scheme_upd" value="<?php if (isset($due_method_scheme_lc)) {
                                                                                                    echo $due_method_scheme_lc;
                                                                                                } ?>" />
            <input type="hidden" name="day_scheme_upd" id="day_scheme_upd" value="<?php if (isset($day_scheme_lc)) {
                                                                                        echo $day_scheme_lc;
                                                                                    } ?>" />
            <input type="hidden" name="scheme_upd" id="scheme_upd" value="<?php if (isset($scheme_name_lc)) {
                                                                                echo $scheme_name_lc;
                                                                            } ?>" />
            <input type="hidden" name="scheme_profit_method_upd" id="scheme_profit_method_upd" value="<?php if (isset($profit_method_scheme_lc)) {
                                                                                                            echo $profit_method_scheme_lc;
                                                                                                        } ?>" />
            <input type="hidden" name="profit_method_upd" id="profit_method_upd" value="<?php if (isset($profit_method_lc)) {
                                                                                            echo $profit_method_lc;
                                                                                        } ?>" />
            <input type="hidden" name="int_rate_upd" id="int_rate_upd" value="<?php if (isset($int_rate_lc)) {
                                                                                    echo $int_rate_lc;
                                                                                } ?>" />
            <input type="hidden" name="due_period_upd" id="due_period_upd" value="<?php if (isset($due_period_lc)) {
                                                                                        echo $due_period_lc;
                                                                                    } ?>" />
            <input type="hidden" name="doc_charge_upd" id="doc_charge_upd" value="<?php if (isset($doc_charge_lc)) {
                                                                                        echo $doc_charge_lc;
                                                                                    } ?>" />
            <input type="hidden" name="proc_fee_upd" id="proc_fee_upd" value="<?php if (isset($proc_fee_lc)) {
                                                                                    echo $proc_fee_lc;
                                                                                } ?>" />
            <input type="hidden" name="cus_profile_id" id="cus_profile_id" value="<?php if (isset($cus_Tableid)) {
                                                                                        echo $cus_Tableid;
                                                                                    } ?>" />
            <input type="hidden" name="bank_clr_bank_id" id="bank_clr_bank_id"/>
            <input type="hidden" name="bank_clr_trans_amnt" id="bank_clr_trans_amnt"/>

            <!-- Row start -->
            <div class="row gutters">
                <!-- Request Info -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    <!-- Personal info START -->
                    <div class="card">
                        <div class="card-header">Personal Info <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="cus_id">Aadhaar Number</label>
                                                <input type="text" class="form-control" id="cus_id" name="cus_id" value='<?php if (isset($cp_cus_id)) {
                                                                                                                                echo $cp_cus_id;
                                                                                                                            } ?>' readonly tabindex='1'>
                                            </div>
                                        </div>
                                        
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
											<div class="form-group">
												<label for="autogen_cus_id">Customer ID</label>
												<input type="text" class="form-control" id="autogen_cus_id" name="autogen_cus_id" tabindex='2' value='<?php if (isset($autogen_cus_id)) { echo $autogen_cus_id; } ?>' readonly>
											</div>
										</div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="cus_name">Customer Name</label>
                                                <input type="text" class="form-control" id="cus_name" name="cus_name" value='<?php if (isset($cp_cus_name)) {
                                                                                                                                    echo $cp_cus_name;
                                                                                                                                } ?>' readonly tabindex='3'>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="name"> Customer Type </label>
                                                <input type="text" class="form-control" name="cus_type" id="cus_type" value="<?php if (isset($cus_type)) {
                                                                                                                                    echo $cus_type;
                                                                                                                                } ?>" readonly tabindex='4'>
                                            </div>
                                        </div>

                                        <div id="exist_type" <?php if (isset($cus_type)) {
                                                                    if ($cus_type != 'Existing') { ?> style="display: none" <?php }
                                                                                                                    } ?> class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="ExistType"> Exist Type </label>
                                                <input type="text" class="form-control" name="cus_exist_type" id="cus_exist_type" value="<?php if (isset($cus_exist_type)) {
                                                                                                                                                echo $cus_exist_type;
                                                                                                                                            } ?>" readonly tabindex='5'>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="mobile1">Mobile No 1</label>
                                                <input type="number" class="form-control" id="mobile1" name="mobile1" value='<?php if (isset($cp_mobile1)) {
                                                                                                                                    echo $cp_mobile1;
                                                                                                                                } ?>' readonly tabindex='6'>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="mobile2">Mobile No 2</label>
                                                <input type="number" class="form-control" id="mobile2" name="mobile2" value='<?php if (isset($cp_mobile2)) {
                                                                                                                                    echo $cp_mobile2;
                                                                                                                                } ?>' readonly tabindex='7'>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="whatsapp">Whatsapp No </label>
                                                <input type="number" class="form-control" id="whatsapp_no" name="whatsapp_no" value="<?php if (isset($cp_whatsapp)) {
                                                                                                                                            echo $cp_whatsapp;
                                                                                                                                        } ?>" readonly tabindex='8'>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="DocArea"> Area </label>
                                                <input type="text" class="form-control" id="doc_area" name="doc_area" value="<?php if (isset($doc_area_name)) echo $doc_area_name; ?>" readonly tabindex='9'>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="DocSubArea"> Sub Area </label>
                                                <input type="text" class="form-control" id="doc_Sub_Area" name="doc_Sub_Area" value='<?php if (isset($doc_sub_area_name)) echo $doc_sub_area_name; ?>' readonly tabindex='10'>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                    <div class="col-xl-8 col-lg-10 col-md-6 ">
                                        <div class="form-group" style="margin-left: 30px;">
                                            <label for="pic" style="margin-left: -20px;">Photo</label><br>
                                            <input type="hidden" name="cus_image" id="cus_image" value="<?php if (isset($cp_cus_pic)) {
                                                                                                            echo $cp_cus_pic;
                                                                                                        } ?>">
                                            <img id='imgshow' class="img_show" src='img/avatar.png' />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Personal info END -->

                    <!-- Guarentor info START -->
                    <div class="card">
                        <div class="card-header">Guarentor Info <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="GuarentorName"> Guarentor Name </label>
                                                <select type="text" class="form-control" id="guarentor_name" name="guarentor_name" disabled tabindex='11'>
                                                    <option> Select Guarantor </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-8">
                                            <div class="form-group">
                                                <label for="GuarentorRelationship"> Guarentor Relationship </label>
                                                <input type="text" class="form-control" id="guarentor_relationship" name="guarentor_relationship" value='<?php if (isset($guarentor_relation)) {
                                                                                                                                                                echo $guarentor_relation;
                                                                                                                                                            } ?>' readonly tabindex='12'>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                    <div class="col-xl-8 col-lg-10 col-md-6 ">
                                        <div class="form-group" style="margin-left: 30px;">
                                            <label for="pic" style="margin-left: -20px;"> Guarentor Photo </label><br>
                                            <input type="hidden" name="guarentor_image" id="guarentor_image" value="<?php if (isset($guarentor_photo)) {
                                                                                                                        echo $guarentor_photo;
                                                                                                                    } ?>">
                                            <img id='imgshows' class="img_show" src='img/avatar.png' />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Guarentor END -->

                    <!-- Loan Info START -->
                    <div class="card">
                        <div class="card-header">Loan Info <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="LoanCategory"> Loan Category </label>
                                        <input type="text" class="form-control" id="loan_category" name="loan_category" value="<?php if (isset($loan_category_lc)) {
                                                                                                                                    echo $loan_category_lc;
                                                                                                                                } ?>" readonly tabindex='13'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="Subcategory"> Sub category </label>
                                        <input type="text" class="form-control" id="sub_category" name="sub_category" value="<?php if (isset($sub_category_lc)) {
                                                                                                                                    echo $sub_category_lc;
                                                                                                                                } ?>" readonly tabindex='14'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="Agent"> Agent </label>
                                        <input type="text" class="form-control" id="agent" name="agent" readonly tabindex='15'>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="disabledInput">Category Info</label><br><br>
                                    <table id="moduleTable" class="table custom-table">
                                        <tbody> </tbody>
                                    </table>
                                    <br><br>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" <?php if (isset($tot_value_lc) and $tot_value_lc == '') { ?>style="display: none;" <?php } ?>>
                                    <div class="form-group">
                                        <label for="disabledInput">Total Value</label>
                                        <input type="text" class="form-control" id="tot_value" name="tot_value" value='<?php if (isset($tot_value_lc)) {
                                                                                                                            echo moneyFormatIndia($tot_value_lc);
                                                                                                                        } elseif (isset($tot_value)) {
                                                                                                                            echo moneyFormatIndia($tot_value);
                                                                                                                        } ?>' readonly tabindex='16'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes" <?php if (isset($ad_amt_lc) and $ad_amt_lc == '') { ?>style="display: none;" <?php } ?>>
                                    <div class="form-group">
                                        <label for="disabledInput">Advance Amount</label>
                                        <input type="text" class="form-control" id="ad_amt" name="ad_amt" value='<?php if (isset($ad_amt_lc)) {
                                                                                                                        echo moneyFormatIndia($ad_amt_lc);
                                                                                                                    } elseif (isset($ad_amt)) {
                                                                                                                        echo moneyFormatIndia($ad_amt);
                                                                                                                    } ?>' readonly tabindex='17'>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Loan Amount</label>
                                        <input type="text" class="form-control" id="loan_amt" name="loan_amt" value='<?php if (isset($loan_amt_lc)) {
                                                                                                                            echo moneyFormatIndia($loan_amt_lc);
                                                                                                                        } elseif (isset($loan_amt)) {
                                                                                                                            echo moneyFormatIndia($loan_amt);
                                                                                                                        } ?>' readonly tabindex='18'>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Profit Type</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="hidden" class="form-control" id="profit_type_ack" name="profit_type_ack" value="<?php echo $profit_type_lc; ?>">
                                        <select tabindex="19" type="text" class="form-control" id="profit_type" name="profit_type">
                                            <option value="">Select Profit Type</option>
                                            <option value="1" <?php if (isset($profit_type_lc) and $profit_type_lc == '1') echo 'selected'; ?>>Calculation</option>
                                            <option value="2" <?php if (isset($profit_type_lc) and $profit_type_lc == '2') echo 'selected'; ?>>Scheme</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='profit_typeCheck'>Please Select Profit Type</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi-calculation" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Due Method</label>&nbsp;<span class="text-danger">*</span>
                                        <input tabindex="20" type="text" class="form-control" id="due_method_calc" name="due_method_calc" readonly value='Monthly'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 calculation" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Due Type</label>&nbsp;<span class="text-danger">*</span>
                                        <input tabindex="21" type="text" class="form-control" id="due_type" name="due_type" readonly value='<?php if (isset($due_type)) echo $due_type; ?>'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi-calculation" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Profit Method</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="hidden" class="form-control" id="profit_method_ack" name="profit_method_ack" value='<?php if (isset($profit_method_lc)) echo $profit_method_lc; ?>'>
                                        <select tabindex="22" type="text" class="form-control" id="profit_method" name="profit_method">
                                            <option value="">Select Profit Method</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='profit_methodCheck'>Please Select Profit Method</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 interest-calculation" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Calculation Method</label>&nbsp;<span class="text-danger">*</span>
                                        <input tabindex="23" type="text" class="form-control" id="calc_method" name="calc_method" readonly value='<?php if (isset($calc_method)) echo $calc_method; ?>'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Due Method</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="hidden" class="form-control" id="due_method_scheme_ack" name="due_method_scheme_ack" value="<?php echo $due_method_scheme_lc; ?>">
                                        <select tabindex="24" type="text" class="form-control" id="due_method_scheme" name="due_method_scheme">
                                            <option value="">Select Due Method</option>
                                            <option value="1" <?php if (isset($due_method_scheme_lc) and $due_method_scheme_lc == '1') echo 'selected'; ?>>Monthly</option>
                                            <option value="2" <?php if (isset($due_method_scheme_lc) and $due_method_scheme_lc == '2') echo 'selected'; ?>>Weekly</option>
                                            <option value="3" <?php if (isset($due_method_scheme_lc) and $due_method_scheme_lc == '3') echo 'selected'; ?>>Daily</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='due_method_schemeCheck'>Please Select Due Method</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 day_scheme" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Day</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="hidden" class="form-control" id="day_scheme_ack" name="day_scheme_ack" value="<?php echo $day_scheme_lc; ?>">
                                        <select tabindex="25" type="text" class="form-control" id="day_scheme" name="day_scheme">
                                            <option value="">Select a Day</option>
                                            <option value="1" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '1') echo 'selected'; ?>>Monday</option>
                                            <option value="2" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '2') echo 'selected'; ?>>Tuesday</option>
                                            <option value="3" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '3') echo 'selected'; ?>>Wednesday</option>
                                            <option value="4" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '4') echo 'selected'; ?>>Thursday</option>
                                            <option value="5" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '5') echo 'selected'; ?>>Friday</option>
                                            <option value="6" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '6') echo 'selected'; ?>>Saturday</option>
                                            <option value="7" <?php if (isset($day_scheme_lc) and $day_scheme_lc == '7') echo 'selected'; ?>>Sunday</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='day_schemeCheck'>Please Select Day</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Scheme Name</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="hidden" class="form-control" id="scheme_name_ack" name="scheme_name_ack" value="<?php echo $scheme_name_lc; ?>">
                                        <select tabindex="26" type="text" class="form-control" id="scheme_name" name="scheme_name">
                                            <option value="">Select Scheme Name</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='scheme_nameCheck'>Please Select Scheme Name</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme-calculation" style="display:none">
                                    <div class="form-group">
                                        <label for="scheme_profit_method">Profit Method</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="hidden" class="form-control" id="profit_method_scheme_ack" name="profit_method_scheme_ack" value="<?php echo $profit_method_scheme_lc; ?>">
                                        <select tabindex="27" type="text" class="form-control" id="scheme_profit_method" name="scheme_profit_method">
                                            <option value="">Select Profit Method</option>
                                        </select>
                                        <span class="text-danger" style='display:none' id='scheme_methodCheck'>Please Select Profit Method</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Interest Rate </label>&nbsp;<span class="text-danger min-max-int">*</span><!-- Min and max intrest rate-->
                                        <input tabindex="28" type="number" class="form-control" id="int_rate" name="int_rate" readonly value='<?php if (isset($int_rate)) echo $int_rate; ?>'>

                                        <span class="text-danger" style='display:none' id='int_rateCheck'>Please Enter Interest Rate</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Due Period </label>&nbsp;<span class="text-danger min-max-due">*</span><!-- Min and max Profit Method-->
                                        <input tabindex="29" type="number" class="form-control" id="due_period" name="due_period" readonly>

                                        <span class="text-danger" style='display:none' id='due_periodCheck'>Please Enter Due Period</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Document Charges </label>&nbsp;<span class="text-danger min-max-doc">*</span><!-- Min and max Document charges-->
                                        <input tabindex="30" type="number" class="form-control" id="doc_charge" name="doc_charge" readonly value='<?php if (isset($doc_charge)) echo $doc_charge; ?>'>

                                        <span class="text-danger" style='display:none' id='doc_chargeCheck'>Please Enter Document Charge</span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Processing Fees</label>&nbsp;<span class="text-danger min-max-proc">*</span><!-- Min and max Processing fee-->
                                        <input tabindex="31" type="number" class="form-control" id="proc_fee" name="proc_fee" readonly value='<?php if (isset($proc_fee)) echo $proc_fee; ?>'>

                                        <span class="text-danger" style='display:none' id='proc_feeCheck'>Please Enter Processing fee</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Loan Info END -->

                    <!-- Loan Calculation Start -->
                    <div class="card">
                        <div class="card-header">Loan Calculation <span style="font-weight:bold" class=""></span><input type="button" class="btn btn-outline-secondary text-right" id="refresh_cal" name="refresh_cal" value='Calculate' style="float:right"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Loan Amount</label>
                                                <input type="text" class="form-control" readonly id="loan_amt_cal" name="loan_amt_cal" value='<?php if (isset($loan_amt_cal)) echo moneyFormatIndia($loan_amt_cal); ?>' tabindex='32'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi_div" style="display: <?php echo ($emicheck ? 'block' : 'none'); ?>;">
                                            <div class="form-group">
                                                <label for="disabledInput">Principal Amount</label>
                                                <input type="text" class="form-control" readonly id="principal_amt_cal" name="principal_amt_cal" value='<?php if (isset($principal_amt_cal)) echo moneyFormatIndia($principal_amt_cal); ?>' tabindex='33'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Interest Amount</label>
                                                <input type="text" class="form-control" readonly id="int_amt_cal" name="int_amt_cal" value='<?php if (isset($int_amt_cal)) echo moneyFormatIndia($int_amt_cal); ?>' tabindex='34'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi_div" style="display: <?php echo ($emicheck ? 'block' : 'none'); ?>;">
                                            <div class="form-group">
                                                <label for="disabledInput">Total Amount</label>
                                                <input type="text" class="form-control" readonly id="tot_amt_cal" name="tot_amt_cal" value='<?php if (isset($tot_amt_cal)) echo moneyFormatIndia($tot_amt_cal); ?>' tabindex='35'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi_div" style="display: <?php echo ($emicheck ? 'block' : 'none'); ?>;">
                                            <div class="form-group">
                                                <label for="disabledInput">Due Amount</label>
                                                <input type="text" class="form-control" readonly id="due_amt_cal" name="due_amt_cal" value='<?php if (isset($due_amt_cal)) echo moneyFormatIndia($due_amt_cal); ?>' tabindex='36'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Document Charges</label>
                                                <input type="text" class="form-control" readonly id="doc_charge_cal" name="doc_charge_cal" value='<?php if (isset($doc_charge_cal)) echo moneyFormatIndia($doc_charge_cal); ?>' tabindex='37'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Processing Fee</label>
                                                <input type="text" class="form-control" readonly id="proc_fee_cal" name="proc_fee_cal" value='<?php if (isset($proc_fee_cal)) echo moneyFormatIndia($proc_fee_cal); ?>' tabindex='38'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Net Cash</label>
                                                <input type="text" class="form-control" readonly id="net_cash_cal" name="net_cash_cal" value='<?php if (isset($net_cash_cal)) echo moneyFormatIndia($net_cash_cal); ?>' tabindex='39'>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Loan info End -->

                    <!-- Collection Info Start -->
                    <div class="card">
                        <div class="card-header">Collection Info <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Due Start From</label>&nbsp;<span class="text-danger">*</span>

                                                <input type="hidden" class="form-control" id="profit_type" name="profit_type" value='<?php if (isset($profit_type_lc)) echo $profit_type_lc; ?>'>
                                                <input type="hidden" class="form-control" id="due_method_calc" name="due_method_calc" value='Monthly'>
                                                <input type="hidden" class="form-control" id="due_method_scheme" name="due_method_scheme" value='<?php if (isset($due_method_scheme_lc)) echo $due_method_scheme_lc; ?>'>
                                                <input type="hidden" class="form-control" id="day_scheme" name="day_scheme" value='<?php if (isset($day_scheme_lc)) echo $day_scheme_lc; ?>'>

                                                <input type="date" class="form-control" id="due_start_from" name="due_start_from" readonly value='<?php if (isset($due_start_from)) echo $due_start_from; ?>' tabindex="40">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Maturity Month</label>
                                                <input type="date" class="form-control" id="maturity_month" name="maturity_month" value='<?php if (isset($maturity_month)) echo $maturity_month; ?>' readonly tabindex='41'>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Collection Method</label>
                                                <select type="text" class="form-control" id="collection_method" name="collection_method" disabled tabindex='42'>
                                                    <option value="">Select Collection Method</option>
                                                    <option value="1" <?php if (isset($collection_method) and $collection_method == '1') echo 'selected'; ?>>BySelf</option>
                                                    <option value="2" <?php if (isset($collection_method) and $collection_method == '2') echo 'selected'; ?>>Spot Collection</option>
                                                    <option value="3" <?php if (isset($collection_method) and $collection_method == '3') echo 'selected'; ?>>Cheque Collection</option>
                                                    <option value="4" <?php if (isset($collection_method) and $collection_method == '4') echo 'selected'; ?>>ECS</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Collection Info End -->

                    <!-- Issued Info Start -->
                    <div class="card">
                        <div class="card-header">Issued Info <span style="font-weight:bold" class=""></span></div>
                        <span class="text-danger" style="display: none;" id="val_check"> Please Enter Any One of the Value </span>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput"> Balance To Issue </label>
                                                <input type="text" class="form-control" id="net_cash" name="net_cash" readonly tabindex='43'>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Issued to </label>&nbsp;<span class="text-danger issued_to_type"></span>
                                                <input type="text" class="form-control" id="issue_to" name="issue_to" readonly tabindex='44'>
                                                <input type="hidden" class="form-control" id="agent_id" name="agent_id">
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Issued mode</label>&nbsp;<span class="text-danger">*</span>
                                                <select type="text" class="form-control" id="issued_mode" name="issued_mode" tabindex="45" disabled>
                                                    <option value=""> Select Issued Mood</option>
                                                    <option value="0" <?php if (isset($issue_mode) and $issue_mode == '0') echo 'selected'; ?>>Split Payment</option>
                                                    <option value="1" <?php if (isset($issue_mode) and $issue_mode == '1') echo 'selected'; ?>>Single Payment</option>
                                                </select>
                                                <span class="text-danger" style="display: none;" id="issue"> Please Select Issued Mode </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 ">
                                            <div class="form-group">
                                                <label for="disabledInput">Payment Type </label>&nbsp;<span class="text-danger">*</span>
                                                <select type="text" class="form-control" id="payment_type" name="payment_type" tabindex="46" disabled>
                                                    <option value=""> Select Payment Type</option>
                                                    <option value="0" <?php if (isset($payment_type) and $payment_type == '0') echo 'selected'; ?>>Cash</option>
                                                    <option value="1" <?php if (isset($payment_type) and $payment_type == '1') echo 'selected'; ?>>Cheque</option>
                                                    <option value="2" <?php if (isset($payment_type) and $payment_type == '2') echo 'selected'; ?>>Account Transfer</option>
                                                </select>
                                                <span class="text-danger" style="display: none;" id="pay_type"> Please Select Payment Type </span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="bankDiv">
                                            <div class="form-group">
                                                <label for="disabledInput">Bank Name </label>&nbsp;<span class="text-danger">*</span>
                                                <select type="text" class="form-control" id="bank_id" name="bank_id" tabindex="47">
                                                    <option value="">Select Bank Name</option>
                                                    <?php
                                                    if (sizeof($getBankDetails) > 0) {
                                                        for ($i = 0; $i < sizeof($getBankDetails); $i++) {
                                                            $bank_id = $getBankDetails[$i]['id'];
                                                            $bank_name = $getBankDetails[$i]['short_name'] . ' - ' . substr($getBankDetails[$i]['acc_no'], -5);

                                                            // // Check if this is the selected bank ID
                                                            // $selected = ($bank_id == $selected_bank_id) ? 'selected' : '';
                                                    ?>
                                                            <option value="<?php echo $bank_id; ?>">
                                                                <?php echo $bank_name; ?>
                                                            </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger" style="display: none;" id="bank_idCheck">Please Select Bank Name</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Issued Info End -->

                    <!-- Bank Info  Start -->
                    <div class="card" id="bankInfos">
                        <div class="card-header">Bank Info <span style="font-weight:bold" class=""></span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="bank_name "> Bank Name </label>
                                                <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter Bank Name" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='48'>

                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="branch_name"> Branch Name </label>
                                                <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Branch Name" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='49'>

                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="account_holder_name"> Account Holder Name </label>
                                                <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" placeholder="Enter Account Holder Name" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='50'>

                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="account_number"> Account Number </label>
                                                <input type="number" class="form-control" id="account_number" name="account_number" placeholder="Enter Account Number" tabindex='51'>

                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="Ifsc_code"> IFSC Code </label>
                                                <input type="text" class="form-control" id="Ifsc_code" name="Ifsc_code" placeholder="Enter IFSC Code" tabindex='52'>

                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="bank_upload">Upload</label>
                                                <input type="file" class="form-control" id="bank_upload" name="bank_upload" tabindex="53" disabled>

                                                <!-- Link to view uploaded image -->
                                                <a href="#" id="viewUploadedImage" target="_blank" style="display: none;"></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bank info END -->
                    <div class="card" id="transaction_detail">
                        <div class="card-header">
                            <?php
                            $payment_text = '';
                            if ($payment_type == 1) {
                                $payment_text = 'Cheque';
                            } elseif ($payment_type == 2) {
                                $payment_text = 'Bank Transfer';
                            }
                            echo $payment_text;
                            ?>
                            <span style="font-weight:bold" class=""></span>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                     <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Transaction ID</label>
                                                <input type="text" class="form-control" id="transaction_id" name="transaction_id" tabindex="54">
                                                <span class="text-danger" style="display: none;" id="transact_id"> Please Enter Transaction ID </span>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="form-group">
                                                <label for="disabledInput">Transaction Date</label>&nbsp;<span class="text-danger">*</span>
                                                <input type="date" class="form-control" id="trans_date" name="trans_date" value='' tabindex='55' readonly>
                                                <span class="text-danger" id='transdateCheck' style="display: none;">Need Transaction Date<span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 checque" style="display:none">
                                            <div class="form-group">
                                                <label for="disabledInput">Cheque number</label>
                                                <input type="number" class="form-control" id="chequeno" name="chequeno" tabindex="56">
                                                <span class="text-danger" style="display: none;" id="cheque_num"> Please Enter Cheque Number </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 checque" style="display:none">
                                            <div class="form-group">
                                                <label for="disabledInput">Cheque Value</label>
                                                <input type="text" class="form-control" id="chequeValue" name="chequeValue" tabindex="57" oninput="validateInputNumber(this,'withOutDot')">
                                                <span class="text-danger" style="display: none;" id="cheque_val"> Please Enter Cheque Value </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 checque" style="display:none">
                                            <div class="form-group">
                                                <label for="disabledInput">Cheque Remark</label>
                                                <input type="text" class="form-control" id="chequeRemark" name="chequeRemark" tabindex="58">
                                                <span class="text-danger" style="display: none;" id="cheque_remark"> Please Enter Cheque Remark </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
                                            <div class="form-group">
                                                <label for="disabledInput">Transaction Value </label>
                                                <input type="text" class="form-control" id="transaction_value" name="transaction_value" tabindex="59" oninput="validateInputNumber(this,'withOutDot')">
                                                <span class="text-danger" style="display: none;" id="transact_val"> Please Enter Transaction Value </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
                                            <div class="form-group">
                                                <label for="disabledInput">Transaction Remark </label>
                                                <input type="text" class="form-control" id="transaction_remark" name="transaction_remark" tabindex="60">
                                                <span class="text-danger" style="display: none;" id="transact_remark"> Please Enter Transaction Remark </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 balance" style="display:none">
                                            <div class="form-group">
                                                <label for="disabledInput">Balance Amount </label>
                                                <input type="text" class="form-control" id="balance" name="balance" readonly tabindex='61'>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button Start -->
                    <div class="col-md-12 ">
                        <div class="text-right">
                            <button type="submit" name="submit_accountsloanIssue" id="submit_accountsloanIssue" class="btn btn-primary" value="Submit" tabindex="62"><span class="icon-check"></span>&nbsp;Submit</button>
                        </div>
                    </div>
                    <!-- Submit Button End -->

                </div>
            </div>
        </form>
    </div>
    <!-- Form End -->
</div>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">