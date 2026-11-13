<div id="customer_loan_calc">
	<form id="cus_loancalc" name="cus_loancalc" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="loan_cal_id" id="loan_cal_id" value="" />
		<input type="hidden" name="loan_req_id" id="loan_req_id" value="" />
		<!-- Row start -->
		<div class="row gutters">
			<!-- Request Info -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Request Info <span style="font-weight:bold" class=""></span></div>
					<div class="card-body">
						<div class="row">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="user_type">User type</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="user_type" id="user_type" readonly tabindex="1">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="user_name">User Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="user_name" id="user_name" readonly tabindex="2">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 agent_div">
								<div class="form-group">
									<label for="agent_name">Agent Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="agent_name" id="agent_name" readonly tabindex='3'>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 agent_div">
								<div class="form-group">
									<label for="responsible">Responsible<span class="required">&nbsp;*</span></label>
									<input type="text" class="form-control" name="responsible" id="responsible" readonly tabindex="4">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 remarks_div" style="display: none;">
								<div class="form-group">
									<label for="remark">Remarks</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="remarks" id="remarks" readonly tabindex="5">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 declaration_div" style="display: none;">
								<div class="form-group">
									<label for="declaration">Declaration</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="declaration" id="declaration" readonly tabindex="6">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="req_area">Area</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="req_area" id="req_area" readonly tabindex="7">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="req_sub_area">Sub Area</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="req_sub_area" id="req_sub_area" readonly tabindex="8">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="req_code">Request ID</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="req_code" id="req_code" readonly tabindex="9">
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="dor">Date Of request</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="dor" id="dor" readonly tabindex="10">
								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- Possibility info -->
				<div class="card ">
					<div class="card-header">Possibilty Info</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="possibility">Possibility<span class="required">&nbsp;*</span></label>
									<input type="text" class="form-control" name="possibility" id="possibility" readonly tabindex='11'>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 due_amnt_div">
								<div class="form-group">
									<label for="">Due Amount</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="possibility_due_amount" id="possibility_due_amount" readonly tabindex='12'>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 due_period_div">
								<div class="form-group">
									<label for="">Due Period</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="possibility_due_period" id="possibility_due_period" readonly tabindex='13'>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Possibility info -->

				<!-- ///////////////////////////////////////////////// Verification Info START ///////////////////////////////////////////////////////////// -->
				<div class="card">
					<div class="card-header"> Verfication Info <span style="font-weight:bold"></span></div>
					<div class="card-body">
						<div class="row">

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="Communitcation">Communitcation</label><span class="required">&nbsp;*</span>
									<select type="text" class="form-control" name="Communitcation_to_cus" id="Communitcation_to_cus" tabindex='14'>
										<option value=""> Select Communication </option>
										<option value="0"> Phone </option>
										<option value="1"> Direct </option>
									</select>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 verify_audio_div">
								<div class="form-group">
									<label for="Communitcation"> Audio </label>
									<input type="hidden" id="verification_audio_upd" name="verification_audio_upd" value="">
									<input type="file" class="form-control" name="verification_audio[]" id="verification_audio" accept=".mp3,audio/*" tabindex="15" multiple>
									<span id="audio_files"></span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="Verificationperson">Verification person</label> <span class="required">&nbsp;*</span>
									<input type="hidden" id="verifyPerson" name="verifyPerson">
									<select type="text" class="form-control" name="verification_person" id="verification_person" multiple tabindex='16'>
										<option value=""> Select Verification Person </option>
									</select>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="Verificationlocation">Verification location</label> <span class="required">&nbsp;*</span>
									<select type="text" class="form-control" name="verification_location" id="verification_location" tabindex='17'>
										<option value=""> Select Verification location </option>
										<option value="0"> On Spot </option>
										<option value="1"> Customer Spot </option>
									</select>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="verify_remark">Remarks</label>
									<input type="text" class="form-control" name="verify_remark" id="verify_remark" tabindex="18">
								</div>
							</div>

						</div>

					</div>
				</div>
				<!-- ///////////////////////////////////////////////  Verification Info  END /////////////////////////////////////////////////////////// -->
				<!-- Loan History START -->
				<!-- <div class="card">
					<div class="card-header"> Loan History
						<button type="button" class="btn btn-primary" name="show_loan_history" id="show_loan_history" style="padding: 5px 35px;  float: right;" tabindex="9" onclick="getLoanHistory()">Show Loan History</button>
						<button type="button" class="btn btn-primary" name="hide_loan_history" id="hide_loan_history" style="padding: 5px 35px;  float: right; display: none;" tabindex="10" >Hide Loan History</button>

					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="loanHistoryDiv">

								</div>
							</div>
						</div>
					</div>
				</div> -->
				<!-- Loan History END -->

				<!-- Loan Info Start -->
				<div class="card">
					<div class="card-header">Loan Info <span style="font-weight:bold" class=""></span></div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="loan_category" id="loan_category" readonly tabindex="19">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="sub_category">Sub Category</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="sub_category" id="sub_category" readonly tabindex="20">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
									<div class="col-md-12">
										<br><br><label>Category Info</label>&nbsp;<span class="text-danger">*</span><br><br>
										<table id="moduleTable" class="table custom-table">
											<tbody>
											</tbody>
										</table><br><br>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes">
										<div class="form-group">
											<label for="tot_value">Total Value</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="tot_value" id="tot_value" readonly tabindex="21">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 advance_yes">
										<div class="form-group">
											<label for="ad_amt">Advance Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="ad_amt" id="ad_amt" readonly tabindex="22">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="loan_amt">Loan Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="loan_amt" id="loan_amt" readonly tabindex="23">
										</div>
									</div>
									<hr>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="profit_type">Profit Type</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" name="profit_type" id="profit_type" disabled tabindex="24">
												<option value="">Select Profit Type</option>
												<option value="1">Calculation</option>
												<option value="2">Scheme</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 calculation_div">
										<div class="form-group">
											<label for="due_method_calc">Due Method</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="due_method_calc" id="due_method_calc" value='Monthly' readonly tabindex="25">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 calculation_div">
										<div class="form-group">
											<label for="due_type">Due Type</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="due_type" id="due_type" readonly tabindex="26">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 calculation_div">
										<div class="form-group">
											<label for="profit_method">Profit Method</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="profit_method" id="profit_method" readonly tabindex="27">
										</div>
									</div>
									<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 interest-calculation">
										<div class="form-group">
											<label for="calc_method">Calculation Method</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="calc_method" id="calc_method" readonly tabindex="28">
										</div>
									</div> -->
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme_div">
										<div class="form-group">
											<label for="due_method_scheme">Due Method</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" name="due_method_scheme" id="due_method_scheme" disabled tabindex="29">
												<option value="">Select Due Method</option>
												<option value="1">Monthly</option>
												<option value="2">Weekly</option>
												<option value="3">Daily</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 day_scheme_div">
										<div class="form-group">
											<label for="day_scheme">Day</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" name="day_scheme" id="day_scheme" disabled tabindex="30">
												<option value="">Select a Day</option>
												<option value="1">Monday</option>
												<option value="2">Tuesday</option>
												<option value="3">Wednesday</option>
												<option value="4">Thursday</option>
												<option value="5">Friday</option>
												<option value="6">Saturday</option>
												<option value="7">Sunday</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme_div">
										<div class="form-group">
											<label for="scheme_name">Scheme Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="scheme_name" id="scheme_name" readonly tabindex="31">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 scheme_div">
										<div class="form-group">
											<label for="scheme_profit_method">Profit Method</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="scheme_profit_method" id="scheme_profit_method" readonly tabindex="32">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="int_rate">Interest Rate </label>&nbsp;<span class="text-danger min-max-int">*</span><!-- Min and max intrest rate-->
											<input type="text" class="form-control" name="int_rate" id="int_rate" readonly tabindex="33">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="due_period">Due Period </label>&nbsp;<span class="text-danger min-max-due">*</span><!-- Min and max Profit Method-->
											<input type="text" class="form-control" name="due_period" id="due_period" readonly tabindex="34">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_charge">Document Charges </label>&nbsp;<span class="text-danger min-max-doc">*</span><!-- Min and max Document charges-->
											<input type="text" class="form-control" name="doc_charge" id="doc_charge" readonly tabindex="35">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="proc_fee">Processing Fees</label>&nbsp;<span class="text-danger min-max-proc">*</span><!-- Min and max Processing fee-->
											<input type="text" class="form-control" name="proc_fee" id="proc_fee" readonly tabindex="36">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Loan info End -->
				<!-- Loan Calculation Start -->
				<div class="card">
					<div class="card-header">Loan Calculation</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="loan_amt_cal">Loan Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="loan_amt_cal" id="loan_amt_cal" readonly tabindex='37'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi_div">
										<div class="form-group">
											<label for="principal_amt_cal">Principal Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="principal_amt_cal" id="principal_amt_cal" readonly tabindex='38'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="int_amt_cal">Interest Amount</label>&nbsp;<span class="text-danger int-diff">*</span>
											<input type="text" class="form-control" name="int_amt_cal" id="int_amt_cal" readonly tabindex='39'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi_div">
										<div class="form-group">
											<label for="tot_amt_cal">Total Amount</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="tot_amt_cal" id="tot_amt_cal" readonly tabindex='40'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 emi_div">
										<div class="form-group">
											<label for="due_amt_cal">Due Amount</label>&nbsp;<span class="text-danger due-diff">*</span>
											<input type="text" class="form-control" name="due_amt_cal" id="due_amt_cal" readonly tabindex='41'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="doc_charge_cal">Document Charges</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="doc_charge_cal" id="doc_charge_cal" readonly tabindex='42'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="proc_fee_cal">Processing Fee</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="proc_fee_cal" id="proc_fee_cal" readonly tabindex='43'>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="net_cash_cal">Net Cash</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" class="form-control" name="net_cash_cal" id="net_cash_cal" readonly tabindex='44'>
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
											<label for="due_start_from">Due Start From</label>&nbsp;<span class="text-danger">*</span>
											<input type="date" class="form-control" name="due_start_from" id="due_start_from" readonly tabindex="45">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="maturity_month">Maturity Date</label>&nbsp;<span class="text-danger">*</span>
											<input type="date" class="form-control" name="maturity_month" id="maturity_month" readonly tabindex="46">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="collection_method">Collection Format</label>&nbsp;<span class="text-danger">*</span>
											<select type="text" class="form-control" name="collection_method" id="collection_method" disabled tabindex="47">
												<option value="">Select Collection Format</option>
												<option value="1">BySelf</option>
												<option value="2">On Spot</option>
												<option value="3">Cheque Collection</option>
												<option value="4">ECS</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Collection Info End -->
				<div class="col-md-12">
					<div class="text-right">
						<button type="submit" class="btn btn-primary" name="submit_loan_calculation" id="submit_loan_calculation" value="Submit" tabindex="48"><span class="icon-check"></span>&nbsp;Submit</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	
const personMultiselect = new Choices("#verification_person", {
  removeItemButton: true,
  noChoicesText: null,
  placeholder: true,
  placeholderValue: "Select Verification Person",
  allowHTML: true,
});

$(document).ready(function () {

	$(document).on("change", "#Communitcation_to_cus", function () {
        let com = $(this).val();

        if (com == "0") {
            $(".verify_audio_div").show();
        } else {
            $(".verify_audio_div").hide();
        }
    });

    $(document).on("click", "#submit_loan_calculation", function (e) {
        e.preventDefault(); // stop default form submit

        //Verification Person Multi select store
        var person_list = personMultiselect.getValue();
        var person = "";
        for (var i = 0; i < person_list.length; i++) {
            if (i > 0) {
            person += ",";
            }
            person += person_list[i].value;
        }
        var arr = person.split(",");
        arr.sort(function (a, b) {
            return a - b;
        });
        var sortedStr = arr.join(",");
        $("#verifyPerson").val(sortedStr);

        const submit_btn = $(this);
        submit_btn.prop('disabled', true);

        const form = document.getElementById("cus_loancalc");
        const formData = new FormData(form);

        $.ajax({
            url: "verificationFile/LoanCalculation/submitLoanCalcFromUpdate.php",
            type: "POST",
            data: formData,
            contentType: false, // Important for file upload
            processData: false, // Prevent jQuery from processing the data
            dataType: "json",
            success: function (response) {
                if (response["info"].includes("Success")) {
                    swalSuccess('Success', 'Loan calculation updated successfully');

                    $('.choosing-document-card, .edit-document-card, #customer_loan_calc').hide();
                } else {
                    swalError('Error', 'Error on Submit');
                }
                submit_btn.prop("disabled", false);
            },
            error: function () {
                submit_btn.prop("disabled", false);
                swalError('Error', 'Server error during submission.')
            }
        });
    });

});   ////////Document Ready End


function getCustomerLoanCalc(req_id, cus_id, dsbled) {
    return $.ajax({
        url: 'verificationFile/LoanCalculation/getCustomerLoanCalc.php',
        type: 'POST',
        data: { "req_id": req_id },
        cache: false,
        dataType: 'json',
        success: function (html) {
            $('#loan_cal_id').val(html.loanCalcInfo.loan_cal_id);

            //Request Info
            $('#user_type').val(html.requestInfo.user_type);
            $('#user_name').val(html.requestInfo.user_name);
            $('#agent_name').val(html.requestInfo.ag_name);
            $('#responsible').val((html.requestInfo.responsible == '0') ? 'Yes' : 'No');
            $('#remarks').val(html.requestInfo.remarks);
            $('#declaration').val(html.requestInfo.declaration);
            $('#req_area').val(html.requestInfo.area_name);
            $('#req_sub_area').val(html.requestInfo.sub_area_name);
            $('#req_code').val(html.requestInfo.req_code);
            $('#dor').val(moment(html.requestInfo.dor).format('DD-MM-YYYY') ?? '');

            //Possibility Info
            $('#possibility').val((html.requestInfo.poss_type == '1') ? 'Due Amount' : 'Due Period');
            $('#possibility_due_amount').val(html.requestInfo.due_amt);
            $('#possibility_due_period').val(html.requestInfo.due_period);

            //Verification Info
            $('#Communitcation_to_cus').val(html.loanCalcInfo.communication);
            $('#verification_audio_upd').val(html.loanCalcInfo.com_audio);
            $('#verification_location').val(html.loanCalcInfo.verification_location);
            $('#verify_remark').val(html.loanCalcInfo.verify_remark);

            verificationPerson(cus_id, html.loanCalcInfo.verification_person);

            //loan info
            $('#loan_category').val(html.loanCalcInfo.loan_category_creation_name);
            $('#sub_category').val(html.loanCalcInfo.sub_category);

            //Category Info
            getCategoryInfo(req_id, html.loanCalcInfo.sub_category, html.loanCalcInfo.loan_category, dsbled);

            $('#tot_value').val(html.loanCalcInfo.tot_value);
            $('#ad_amt').val(html.loanCalcInfo.ad_amt);
            $('#loan_amt').val(html.loanCalcInfo.loan_amt);
            $('#profit_type').val(html.loanCalcInfo.profit_type);
            $('#due_method_calc').val(html.loanCalcInfo.due_method_calc);
            $('#due_type').val(html.loanCalcInfo.due_type);
            $('#profit_method').val((html.loanCalcInfo.profit_method =='after_intrest') ? 'After Benefit' : 'Pre Benefit');
            $('#calc_method').val(html.loanCalcInfo.calc_method);
            $('#due_method_scheme').val(html.loanCalcInfo.due_method_scheme);
            $('#day_scheme').val(html.loanCalcInfo.day_scheme);
            $('#scheme_name').val(html.loanCalcInfo.scheme_name);
            $('#scheme_profit_method').val((html.loanCalcInfo.profit_method =='after_intrest') ? 'After Benefit' : 'Pre Benefit');
            $('#int_rate').val(html.loanCalcInfo.int_rate);
            $('#due_period').val(html.loanCalcInfo.due_period);
            $('#doc_charge').val(html.loanCalcInfo.doc_charge);
            $('#proc_fee').val(html.loanCalcInfo.proc_fee);

            //Loan Calculation Info
            $('#loan_amt_cal').val(html.loanCalcInfo.loan_amt_cal);
            $('#principal_amt_cal').val(html.loanCalcInfo.principal_amt_cal);
            $('#int_amt_cal').val(html.loanCalcInfo.int_amt_cal);
            $('#tot_amt_cal').val(html.loanCalcInfo.tot_amt_cal);
            $('#due_amt_cal').val(html.loanCalcInfo.due_amt_cal);
            $('#doc_charge_cal').val(html.loanCalcInfo.doc_charge_cal);
            $('#proc_fee_cal').val(html.loanCalcInfo.proc_fee_cal);
            $('#net_cash_cal').val(html.loanCalcInfo.net_cash_cal);
            $('#due_start_from').val(html.loanCalcInfo.due_start_from);
            $('#maturity_month').val(html.loanCalcInfo.maturity_month);
            $('#collection_method').val(html.loanCalcInfo.collection_method);


            (html.requestInfo.ag_name !='' && html.requestInfo.ag_name != null ) ? $('.agent_div').show() : $('.agent_div').hide();
            (html.requestInfo.user_type == 'Staff') ? $('.remarks_div').show() : $('.remarks_div').hide();
            (html.requestInfo.user_type == 'Staff') ? $('.declaration_div').hide() : $('.declaration_div').show();

            (html.requestInfo.poss_type == '1') ? $('.due_amnt_div').show() : $('.due_amnt_div').hide();
            (html.requestInfo.poss_type == '2') ? $('.due_period_div').show() : $('.due_period_div').hide();

            if (html.loanCalcInfo.communication == '0') {
                $('.verify_audio_div').show();

                let audioList = '';
                let audioFiles = html.loanCalcInfo.com_audio;
                // Handle comma-separated audio filenames
                if (typeof audioFiles === 'string') {
                    audioFiles = audioFiles.split(',');
                }

                if (Array.isArray(audioFiles)) {
                    audioFiles.forEach(function(audio) {
                        audio = audio.trim();
                        if (audio !== '') {
                            audioList += `<a href="uploads/verification/verifyInfo_audio/${encodeURIComponent(audio)}" target="_blank" style="color: #4ba39b;">${audio}</a>, `;
                        }
                    });
                }

                $('#audio_files').html(audioList);

            } else {
                $('.verify_audio_div').hide();
            }

            (html.loanCalcInfo.collection_info == 'Yes') ? $('.advance_yes').show() : $('.advance_yes').hide();
            
            if (html.loanCalcInfo.profit_type == '1'){ 
                $('.calculation_div').show();
                $('.scheme_div, .day_scheme_div').hide();

            } else if(html.loanCalcInfo.profit_type == '2'){
                $('.calculation_div').hide();
                $('.scheme_div').show();

                (html.loanCalcInfo.due_method_scheme == '2') ? $('.day_scheme_div').show() : $('.day_scheme_div').hide();
                
            }

        },
		error: function (xhr, status, error) {
			console.error(xhr.responseText);
		}
    });
}

// Verification Info Person
function verificationPerson(cus_id, verification_person_upd) {

  var values = verification_person_upd.split(",");

  $.ajax({
    url: "verificationFile/verificationFam.php",
    type: "post",
    data: { cus_id: cus_id },
    dataType: "json",
    success: function (response) {
      personMultiselect.clearStore();
      for (var i = 0; i < response.length; i++) {
        var fam_name = response[i]["fam_name"] + " - " + response[i]["relationship"];
        var fam_id = response[i]["fam_id"];
        var selected = "";
        if (verification_person_upd !== "" && values.includes(String(fam_id))) {
          selected = "selected";
        }
        var items = [
          {
            value: fam_id,
            label: fam_name,
            selected: selected,
          },
        ];
        personMultiselect.setChoices(items);
        personMultiselect.init();
      }
    },
  });
}

//Get Category info From Request
function getCategoryInfo(req_id, sub_category_upd, loan_category, dsbld) {
    $.ajax({
        url: 'requestFile/getCategoryInfo.php',
        data: { 'sub_cat': sub_category_upd,'loan_category':loan_category },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#moduleTable').empty().append('<tbody><tr>');
            if (response.length != 0) {
                var tb = 21;
                
                for (var i = 0; i < response.length; i++) {
                    $('#moduleTable tbody tr').append(`<td><label for="disabledInput">${response[i]["loan_category_ref_name"]}</label><span class="required">&nbsp;*</span><input type="text" class="form-control" id="category_info" name="category_info[]" value='' tabindex='${tb}' required placeholder = 'Enter ${response[i]["loan_category_ref_name"]}'></td>`);
                    tb++;
                }

                $('#moduleTable tbody tr').append(`<td><button type="button" tabindex='${tb}' id="add_category_info[]" name="add_category_info" 
                class="btn btn-primary add_category_info">Add</button></td><td><span class='icon-trash-2 deleterow' id='deleterow' tabindex='${tb}'></span></td>
                </tr></tbody></table>`);

                var category_content = $('#moduleTable tbody tr').html(); //To get the appended category list

                var category_count = $('#moduleTable tbody tr').find('td').length - 2;//To find input fields count
                getCategoryInputs(req_id, category_count, category_content, sub_category_upd);

                // Remove previously registered event and bind only once
                $(document).off('click', '.add_category_info').on('click', '.add_category_info', function () {
                    $('#moduleTable tbody').append('<tr>' + category_content + '</tr>');
                });

                // remove delete option for last child
                $('#deleterow:last').filter(':last').removeClass('deleterow');

                $(document).off('click', '.deleterow').on('click', '.deleterow', function () {
                    $(this).closest('tr').remove();
                });

				(dsbld =='1') ? $('.add_category_info').prop('disabled', true) : '';

            }
        }
    });
}

function getCategoryInputs(req_id, category_count, category_content, sub_category_upd) {
    $.ajax({
        url: 'verificationFile/LoanCalculation/getCategoryInfoForAck.php',
        data: { 'req_id': req_id, 'sub_category_upd': sub_category_upd },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            var trCount = Math.ceil(response.length / category_count); // number of rows needed

            for (var j = 0; j < trCount - 1; j++) {
                $('#moduleTable tbody').append('<tr>' + category_content + '</tr>');
            }
            for (var i = 0; i < response.length; i++) {
                $('#moduleTable tbody input').each(function (index) {
                    $(this).val(response[index]);
                });
            }
        }
    })
}

</script>