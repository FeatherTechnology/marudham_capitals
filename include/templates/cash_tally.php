<?php

if (isset($_SESSION['userid'])) {
	$userid = $_SESSION['userid'];
}


$getuser = $userObj->getuser($mysqli, $userid);
$bank_details = $getuser['bank_details'];
$branch_id = $getuser['branch_id'];
$role_type = $getuser['role_type'];

//To get bank name details for mentioning it on opening and closing balance
if ($bank_details != null) {
	$bank_details_arr = explode(',', $getuser['bank_details']);
	foreach ($bank_details_arr as $val) {
		$qry11 = $mysqli->query("SELECT id,short_name, acc_no from bank_creation where id = '" . strip_tags($val) . "' ");
		$row = $qry11->fetch_assoc();
		$bank_name_arr[] = $row['short_name'] . ' - ' . substr($row['acc_no'], -5);
		$bank_id_arr[] = $row['id'];
	}
}
$all_bank_name_arr = [];
$all_bank_id_arr = [];
$all_bank_id = '';
$bank_qry = $mysqli->query("SELECT id, short_name, acc_no from bank_creation ");
if ($bank_qry->num_rows > 0) {
	while ($bank_row = $bank_qry->fetch_assoc()) {
		$all_bank_name_arr[] = $bank_row['short_name'] . ' - ' . substr($bank_row['acc_no'], -5);
		$all_bank_id_arr[] = $bank_row['id'];
	}
	$all_bank_id = implode(',', $all_bank_id_arr);
}

?>

<style>
	.modal {
		width: 100% !important;
	}

	.modal-lg {
		max-width: 70% !important;
	}

	.lable-style {
		font-size: 15px;
		/* font-weight:normal; */
		font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
	}

	input[type="radio"] {
		top: 2px;
	}

	.radio-style {
		font-size: 15px;
		font-weight: normal;
	}
</style>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Cash Tally
	</div>
</div>
<br>
<br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="cash_tally" name="cash_tally" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" id='user_branch_id' name='user_branch_id' value='<?php if (isset($branch_id)) echo $branch_id; ?>'>
		<input type="hidden" id='user_bank_details' name='user_bank_details' value='<?php if (isset($bank_details)) echo $bank_details; ?>'>
		<input type="hidden" id='all_bank_details' name='all_bank_details' value='<?php if (isset($all_bank_id)) echo $all_bank_id; ?>'>
		<input type="hidden" id='oldclosingbal' name='oldclosingbal' value=''>
		<!-- Row start -->
		<div class="row gutters">
			<!-- Request Info -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

				<!-- //////////////////////////////////////////////////////////// Opening Balance Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card">
					<!-- <div class="card-header">Cash Tally</div> -->
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
										<div class="form-group">
											<label style='font-size:18px;font-weight:bold;'>Opening Balance</label>
										</div>
									</div>
									<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12"></div>
									<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12" style="max-width: 140px;text-align:right">
										<div class="form-group">
											<label for="op_date" class="lable-style">Opening Date: </label><br>
										</div>
									</div>
									<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
										<div class="form-group">
											<label class="lable-style" id='op_date'><?php //echo date('d-m-Y');
																					?></label><br>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 198px;">
										<div class="form-group">
											<label class="lable-style">Hand Cash</label><br>
											<?php if (isset($bank_name_arr)) {
												for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
													<label class="lable-style"><?php echo $bank_name_arr[$i]; ?></label><br>
											<?php }
											} ?>
											<label class="lable-style">Agent Cash</label><br><br>
											<hr>
											<label class="lable-style">Total Opening Balance</label>
										</div>
									</div>
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 20px;">
										<div class="form-group">
											<label class="lable-style">:</label><br>
											<?php if (isset($bank_name_arr)) {
												for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
													<label class="lable-style">:</label><br>
											<?php }
											} ?>
											<label class="lable-style">:</label><br><br>
											<hr>
											<label class="lable-style">:</label>
										</div>
									</div>

									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 150px;">
										<div class="form-group">
											<label class="lable-style" id='hand_opening'></label><br>
											<input type='hidden' id='untrkd_ids_op' name='untrkd_ids_op' value='<?php $untrkd0 = '';
																												if (isset($bank_name_arr)) {
																													for ($i = 0; $i < sizeof($bank_name_arr); $i++) {
																														$untrkd0 .= 'untrkd0' . $i . ',';
																													}
																												}
																												echo rtrim($untrkd0, ','); ?>'>
											<?php if (isset($bank_name_arr)) {
												for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
													<label class="lable-style" id='bank_opening<?php echo $i; ?>'></label>&nbsp;<label class="lable-style untrkd_op" id='<?php echo 'untrkd0' . $i; ?>'>(0)</label><br>
											<?php }
											} ?>
											<label class="lable-style" id='agent_opening'></label><br><br>
											<hr>
											<label class="lable-style" id='opening_balance'></label>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Opening Balance Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Cash tally Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card">
					<div class="card-header" style='font-size:18px;font-weight:bold;'>Cash Tally</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"></div>
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
										<div class="form-group">
											<input type="radio" id="hand_cash_radio" name="cash_type" value='0' />&emsp;<label class='radio-style'>Hand Cash</label>&emsp;
										</div>
									</div>
									<?php if (isset($bank_details) && $bank_details != null) {
										for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
											<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 30%;">
												<div class="form-group">
													<input type="radio" id="bank_cash_radio" name="cash_type" value="<?php echo $bank_id_arr[$i]; ?>" class="bank_cash_radio" />&emsp;<label class='radio-style'><?php echo $bank_name_arr[$i]; ?></label>
												</div>
											</div>
									<?php }
									} ?>
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
										<div class="form-group">
											<!-- <button type="button" name="blnc_sheet_btn" id="blnc_sheet_btn" class="btn btn-primary" data-toggle='modal' data-target='.blncModal' onclick="hideAllCardsfunction()">Balance Sheet</button> -->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
									<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
										<div class="form-group">
											<label for='credit_type'>Credit</label>
											<select class="form-control" id='credit_type' name='credit_type'>
												<option value=''>Select Credit Type</option>
											</select>
										</div>
									</div>
									<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
										<div class="form-group">
											<label for='debit_type'>Debit</label>
											<select class="form-control" id='debit_type' name='debit_type'>
												<option value=''>Select Debit Type</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Cash tally Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Collection Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card collection_card" style='display:none'>
					<div class="card-header" style='font-size:18px;font-weight:bold;'>Collection</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">

									<div class="modal-body">
										<div id="collectionTableDiv">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Collection Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Contra Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card contra_card" style='display:none'>
					<div class="card-header contra_card_header" style='font-size:18px;font-weight:bold;'>Contra</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="contraTableDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Contra Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Exchange Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card exchange_card" style='display:none'>
					<div class="card-header exchange_card_header" style='font-size:18px;font-weight:bold;'>Exchange</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="exchangeDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Exchange Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Other income Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card oti_card" style='display:none'>
					<div class="card-header oti_card_header" style='font-size:18px;font-weight:bold;'>Other Income</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="otiDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Other income Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Issued Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card issued_card" style='display:none'>
					<div class="card-header issued_card_header" style='font-size:18px;font-weight:bold;'>Issued</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="issuedDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Other income Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Expense Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card expense_card" style='display:none'>
					<div class="card-header expense_card_header" style='font-size:18px;font-weight:bold;'>Expense</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="expenseDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Expense Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Investment/Deposit/EL Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card inv_card" style='display:none'>
					<div class="card-header inv_card_header" style='font-size:18px;font-weight:bold;'>Investment</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="invDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Investment/Deposit/EL Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Excess fund Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card exf_card" style='display:none'>
					<div class="card-header exf_card_header" style='font-size:18px;font-weight:bold;'>Excess Fund</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="exfDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Excess fund Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Agent Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card ag_card" style='display:none'>
					<div class="card-header ag_card_header" style='font-size:18px;font-weight:bold;'>Agent</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row modal-body" id="agDiv">

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Agent Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- //////////////////////////////////////////////////////////// Closing Balance Card ////////////////////////////////////////////////////////////////////////////-->
				<div class="card">
					<!-- <div class="card-header">Cash Tally</div> -->
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="form-group">
											<label style='font-size:18px;font-weight:bold;'>Closing Balance</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8"></div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
										<div class="text-right">
											<button type="button" data-toggle="modal" data-target=".super_closing" id="super_closing" name="super_closing" class="btn btn-primary" onclick="getAllClosingBalance()" <?php if ($role_type != '12') { ?> style="display:none" <?php } ?>>All Closing</button>
											<button type="button" data-toggle="modal" data-target=".addUntracked" id="addUntracked" name="addUntracked" class="btn btn-primary">Untracked</button>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 198px;">
										<div class="form-group">
											<label class="lable-style"> Hand Cash</label><br>
											<?php if (isset($bank_name_arr)) {
												for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
													<label class="lable-style"><?php echo $bank_name_arr[$i]; ?></label><br>
											<?php }
											} ?>
											<label class="lable-style">Agent Cash</label><br><br>
											<hr>
											<label class="lable-style">Total Closing Balance</label>
										</div>
									</div>
									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 20px;">
										<div class="form-group">
											<label class="lable-style">:</label><br>
											<?php if (isset($bank_name_arr)) {
												for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
													<label class="lable-style">:</label><br>
											<?php }
											} ?>
											<label class="lable-style">:</label><br><br>
											<hr>
											<label class="lable-style">:</label>
										</div>
									</div>

									<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="max-width: 150px;">
										<div class="form-group">

											<input type='hidden' id='untrkd_ids' name='untrkd_ids' value='<?php if (isset($bank_name_arr)) {
																												for ($i = 0; $i < sizeof($bank_name_arr); $i++) {
																													echo 'untrkd' . $bank_id_arr[$i] . ',';
																												}
																											} ?>'>
											<label class="lable-style" id='hand_closing'></label><br>
											<?php if (isset($bank_name_arr)) {
												for ($i = 0; $i < sizeof($bank_name_arr); $i++) { ?>
													<label class="lable-style" id='bank_closing<?php echo $i; ?>'></label>&nbsp;<label class="lable-style untrkd" id='<?php echo 'untrkd' . $bank_id_arr[$i]; ?>'>(0)</label><br>
											<?php }
											} ?>
											<label class="lable-style" id='agent_closing'></label><br><br>
											<hr>
											<label class="lable-style" id='closing_balance'></label>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<!-- //////////////////////////////////////////////////////////// Closing Balance Card ////////////////////////////////////////////////////////////////////////////-->

				<!-- Submit Button Start -->
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="button" name="blnc_sheet_btn" id="blnc_sheet_btn" class="btn btn-primary" data-toggle='modal' data-target='.blncModal' onclick="hideAllCardsfunction()">Balance Sheet</button>
						<button name="submit_cash_tally" id="submit_cash_tally" class="btn btn-primary" value="Submit">Submit</button>
						<!-- <button type="reset" class="btn btn-outline-secondary" tabindex="20">Clear</button> -->
					</div>
				</div>
				<!-- Submit Button End -->

			</div>
		</div>
	</form>
	<!-- Form End -->
</div>

<!-- /////////////////////////////////////////////////////////////////// Collection Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade coll_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Receive Collection</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeReceiveModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="receiveAmtDiv">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeReceiveModal()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Collection Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Credit Cash Deposit Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade cd_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Cash Deposit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closCdModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="receivecdAmtDiv">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closeCdModal' data-dismiss="modal" onclick="closCdModal()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Credit Cash Deposit Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Credit Bank Withdrawal Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade bwd_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Bank Withdrawal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getBankWithdrawalDetails()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="receivebwdAmtDiv">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closebwdModal' data-dismiss="modal" onclick="getBankWithdrawalDetails()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Credit Bank Withdrawal Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Balance Sheet Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade blncModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Balance Sheet</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetBlncSheet()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8"></div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8">
								<div class="form-group">
									<label for='sheet_type'>Balance Sheet Type</label>
									<select class="form-control" id='sheet_type' name='sheet_type'>
										<option value=''>Select Balance Sheet</option>
										<option value='1'>Contra</option>
										<option value='2'>Exchange</option>
										<option value='3'>Other income</option>
										<option value='4'>Expense</option>
										<option value='5'>Inv/Dep/EL</option>
										<option value='6'>Excess Fund</option>
										<option value='7'>Agent</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row" id="exp_typeDiv" style="display:none">
							<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for='exp_view_type'>View</label>
									<select class="form-control" id='exp_view_type' name='exp_view_type'>
										<option value=''>Select View type</option>
										<option value='1'>Overall</option>
										<option value='2'>Category wise</option>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12" id='exp_cat_typeDiv' style="display: none;">
								<div class="form-group">
									<label for='exp_cat_type'>Category</label>
									<select class="form-control" id='exp_cat_type' name='exp_cat_type'>
									</select>
								</div>
							</div>
						</div>
						<div class="row" id="IDE_Div" style="display:none">
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for='IDE_type'>Sheet Sub type</label>
									<select class="form-control" id='IDE_type' name='IDE_type'>
										<option value=''>Select Sheet type</option>
										<option value='1'>Investment</option>
										<option value='2'>Deposit</option>
										<option value='3'>EL</option>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for='IDE_view_type'>View</label>
									<select class="form-control" id='IDE_view_type' name='IDE_view_type'>
										<option value=''>Select Sheet type</option>
										<option value='1'>Overall</option>
										<option value='2'>Individual</option>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 IDE_nameDiv" style="display:none">
								<div class="form-group">
									<label for='IDE_name_list'>Name</label>
									<select class="form-control" id='IDE_name_list' name='IDE_name_list'>
										<option value=''>Select Name</option>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 IDE_nameDiv" style="display:none">
								<div class="form-group">
									<label for='IDE_name_area'>Area</label>
									<input type='text' class="form-control" id='IDE_name_area' name='IDE_name_area' readonly placeholder='Please Select Name'>
								</div>
							</div>
						</div>
						<div class="row" id="ag_typeDiv" style="display:none">
							<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for='ag_view_type'>View</label>
									<select class="form-control" id='ag_view_type' name='ag_view_type'>
										<option value=''>Select View type</option>
										<option value='1'>Overall</option>
										<option value='2'>Agent wise</option>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12" id='ag_namewiseDiv' style="display: none;">
								<div class="form-group">
									<label for='ag_namewise'>Category</label>
									<select class="form-control" id='ag_namewise' name='ag_namewise'>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div id="blncSheetDiv">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='' data-dismiss="modal" onclick="resetBlncSheet()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Balance Sheet Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Exchange Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade hexchange_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Exchange Credit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getCreditHexchangeDetails()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="hexchangeDiv">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closeHexchangeModal' data-dismiss="modal" onclick="getCreditHexchangeDetails()">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade bexchange_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Exchange Credit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getCreditBexchangeDetails()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="bexchangeDiv">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closeBexchangeModal' data-dismiss="modal" onclick="getCreditBexchangeDetails()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Exchange Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Issued Modal END ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade bissued_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Issued </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getBissuedTable()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="bissuedDiv">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closeissuedModal' data-dismiss="modal" onclick="getBissuedTable()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Issued Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Expense Modal Start ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade hexp_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Expense </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getHexpenseTable()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="hexp_modalDiv">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closehexpModal' data-dismiss="modal" onclick="getHexpenseTable()">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade bexp_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Expense </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getBexpenseTable()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="bexp_modalDiv">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id='closebexpModal' data-dismiss="modal" onclick="getBexpenseTable()">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Expense Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Inv/Dep/EL Name Creation modal END ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade add_nameDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Name Creation </h5>
				<button type="button" class="close name-model-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="nameInsertNotOk" class="unsuccessalert">Name Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="nameInsertOk" class="successalert">Name Detail Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="nameUpdateOk" class="successalert">Name Detail Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="nameDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Name Detail!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="nameDeleteOk" class="successalert">Name Detail Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br />
				<div class="row">
					<div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12"></div>
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
						<div class="form-group">
							<label class="label">Name</label><span class="text-danger">&nbsp;*</span>
							<input type="hidden" name="opt_for" id="opt_for">
							<input type="hidden" name="name_id" id="name_id">
							<input type="text" name="name_" id="name_" class="form-control" placeholder="Enter Name">
							<span class="text-danger" id="name_Check">Enter Name</span>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
						<div class="form-group">
							<label class="label">Area</label><span class="text-danger">&nbsp;*</span>
							<input type="text" name="area_" id="area_" class="form-control" placeholder="Enter Area">
							<span class="text-danger" id="area_Check">Enter Area Name</span>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
						<div class="form-group">
							<label class="label">Identification</label><span class="text-danger">&nbsp;*</span>
							<input type="text" name="ident_" id="ident_" class="form-control" placeholder="Enter Identification">
							<span class="text-danger" id="ident_Check">Enter Identification</span>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<label class="label" style="visibility: hidden;"></label><br>
						<button type="button" name="submitNameDetailModal" id="submitNameDetailModal" class="btn btn-primary">Submit</button>
					</div>
				</div>

				<div id="updateNameDetailDiv">
					<table class="table custom-table" id="nameDetailTable">
						<thead>
							<tr>
								<th width="50">S.No</th>
								<th>Name</th>
								<th>Area</th>
								<th>Identification</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<a id="edit_name" value=""><span class="icon-border_color"></span></a> &nbsp;
								<a id="delete_name" value=""><span class='icon-trash-2'></span></a>
							</td>
						</tbody>
					</table>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary name-model-close" id='' data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Inv/Dep/EL Name Creation modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Untracked modal start ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade addUntracked" id="" tabindex="-1" role="dialog" aria-labelledby="vCenterModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="vCenterModalTitle">Add Untracked</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" enctype="multipart/form-data" name="untracked_form" id="untracked_form">
					<div class="row">
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label class="label">Bank Name</label>
								<select name="bank_id_untracked" id="bank_id_untracked" class="form-control"></select>
								<span class="text-danger" style='display:none' id='bank_id_untrackedCheck'>Please Select Bank Name</span>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label class="label">Untracked Amount</label>
								<input type="number" name="untracked_amt" id="untracked_amt" class="form-control" placeholder="Enter Untracked Amount">
								<span class="text-danger" style='display:none' id='untracked_amtCheck'>Please Select Amount</span>
								<div id="insertsuccess" style="color: green; font-weight: bold; display:none">Untracked Amount Added Successfully</div>
								<div id="updatesuccess" style="color: red; font-weight: bold;display:none">Untracked Updated Succesfully</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="submit_untracked" name="submit_untracked">Submit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeUntracked">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Untracked modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Super Closing modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade super_closing" id="" tabindex="-1" role="dialog" aria-labelledby="vCenterModalTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="vCenterModalTitle">All Closing Balance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-12">
					<div class="row justify-content-center">
						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
							<div class="form-group">
								<label class="lable-style"> Hand Cash</label><br>
								<?php if (isset($all_bank_name_arr)) {
									for ($i = 0; $i < sizeof($all_bank_name_arr); $i++) { ?>
										<label class="lable-style"><?php echo $all_bank_name_arr[$i]; ?></label><br>
								<?php }
								} ?>
								<label class="lable-style">Agent Cash</label><br><br>
								<hr>
								<label class="lable-style">Total Closing Balance</label>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
							<div class="form-group">
								<label class="lable-style">:</label><br>
								<?php if (isset($all_bank_name_arr)) {
									for ($i = 0; $i < sizeof($all_bank_name_arr); $i++) { ?>
										<label class="lable-style">:</label><br>
								<?php }
								} ?>
								<label class="lable-style">:</label><br><br>
								<label class="lable-style">:</label>
							</div>
						</div>
						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
							<div class="form-group">
								<label class="lable-style" id='all_hand_closing'></label><br>
								<?php if (isset($all_bank_name_arr)) {
									for ($i = 0; $i < sizeof($all_bank_name_arr); $i++) { ?>
										<label class="lable-style" id='all_bank_closing<?php echo $i; ?>'></label><br>
								<?php }
								} ?>
								<label class="lable-style" id='all_agent_closing'></label><br><br>
								<hr>
								<label class="lable-style" id='all_closing_balance'></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>