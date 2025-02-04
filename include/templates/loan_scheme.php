<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$loanCategoryList = $userObj->getloanCategoryList($mysqli);

if (isset($_POST['submit_loan_scheme_monthly']) && $_POST['submit_loan_scheme_monthly'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateMonthlyLoanScheme($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=2&type=monthly';
		</script>
	<?php	} else {
		$userObj->addMonthlyLoanScheme($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=1&type=monthly';
		</script>
	<?php
	}
}
if (isset($_POST['submit_loan_scheme_weekly']) && $_POST['submit_loan_scheme_weekly'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateWeeklyLoanScheme($mysqli, $id, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=2&type=weekly';
		</script>
	<?php	} else {
		$userObj->addWeeklyLoanScheme($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=1&type=weekly';
		</script>
	<?php
	}
}
if (isset($_POST['submit_loan_scheme_daily']) && $_POST['submit_loan_scheme_daily'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateDailyLoanScheme($mysqli, $id, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=2&type=daily';
		</script>
	<?php	} else {
		$userObj->addDailyLoanScheme($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=1&type=daily';
		</script>
	<?php
	}
}

$del = 0;
if (isset($_GET['del'])) {
	$del = $_GET['del'];
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}
}
if ($del > 0) {
	$userObj->deleteLoanScheme($mysqli, $del, $userid);
	if ($type == 'monthly') {
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=3&type=monthly';
		</script>
	<?php	} elseif ($type == 'weekly') {
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=3&type=weekly';
		</script>
	<?php	} elseif ($type == 'daily') {
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_scheme&msc=3&type=daily';
		</script>
<?php	}
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}
}
$status = 0;
if ($idupd > 0) {
	$getLoanScheme = $userObj->getLoanScheme($mysqli, $idupd);
	print_r($getLoanScheme);
	if (sizeof($getLoanScheme) > 0) {
		for ($i = 0; $i < sizeof($getLoanScheme); $i++) {
			$due_method                	 = $getLoanScheme['due_method'];
			$scheme_id                 	 = $getLoanScheme['scheme_id'];

			if ($type == 'monthly') {
				$scheme_name          		     = $getLoanScheme['scheme_name'];
				$scheme_short      			     = $getLoanScheme['short_name'];
				$loan_category      			 = $getLoanScheme['loan_category'];
				$sub_category       			 = $getLoanScheme['sub_category'];
				$profit_method        		     = $getLoanScheme['profit_method'];
				// $intrest_rate        		     = $getLoanScheme['intrest_rate'];
				$total_due        		     = $getLoanScheme['total_due'];
				$advance_due        		     = $getLoanScheme['advance_due'];
				$due_period        		     = $getLoanScheme['due_period'];
				$intreset_type        		     = $getLoanScheme['intreset_type'];
				$intreset_min        		     = $getLoanScheme['intreset_min'];
				$intreset_max        		     = $getLoanScheme['intreset_max'];
				$doc_charge_type        		     = $getLoanScheme['doc_charge_type'];
				$doc_charge_min        		     = $getLoanScheme['doc_charge_min'];
				$doc_charge_max        		     = $getLoanScheme['doc_charge_max'];
				$proc_fee_type        		     = $getLoanScheme['proc_fee_type'];
				$proc_fee_min        		     = $getLoanScheme['proc_fee_min'];
				$proc_fee_max        		     = $getLoanScheme['proc_fee_max'];
				$due_date        		     = $getLoanScheme['due_date'];
				$overdue        		     = $getLoanScheme['overdue'];
				$grace_period        		     = $getLoanScheme['grace_period'];
				$penalty        		     = $getLoanScheme['penalty'];
				$profit_method = explode(',', $profit_method);
			} elseif ($type == 'weekly') {
				$scheme_name1          		     = $getLoanScheme['scheme_name'];
				$scheme_short1      			     = $getLoanScheme['short_name'];
				$loan_category1      			 = $getLoanScheme['loan_category'];
				$sub_category1       			 = $getLoanScheme['sub_category'];
				$profit_method1        		     = $getLoanScheme['profit_method'];
				// $intrest_rate1        		     = $getLoanScheme['intrest_rate'];
				$total_due1        		     = $getLoanScheme['total_due'];
				$advance_due1        		     = $getLoanScheme['advance_due'];
				$due_period1        		     = $getLoanScheme['due_period'];
				$intreset_type1        		     = $getLoanScheme['intreset_type'];
				$intreset_min1        		     = $getLoanScheme['intreset_min'];
				$intreset_max1        		     = $getLoanScheme['intreset_max'];
				$doc_charge_type1        		     = $getLoanScheme['doc_charge_type'];
				$doc_charge_min1        		     = $getLoanScheme['doc_charge_min'];
				$doc_charge_max1        		     = $getLoanScheme['doc_charge_max'];
				$proc_fee_type1        		     = $getLoanScheme['proc_fee_type'];
				$proc_fee_min1        		     = $getLoanScheme['proc_fee_min'];
				$proc_fee_max1        		     = $getLoanScheme['proc_fee_max'];
				$due_day        		     = $getLoanScheme['due_date'];
				$overdue1        		     = $getLoanScheme['overdue'];
				$grace_period1        		     = $getLoanScheme['grace_period'];
				$penalty1        		     = $getLoanScheme['penalty'];
				$profit_method1 = explode(',', $profit_method1);
			} elseif ($type == 'daily') {
				$scheme_name2          		     = $getLoanScheme['scheme_name'];
				$scheme_short2      			     = $getLoanScheme['short_name'];
				$loan_category2      			 = $getLoanScheme['loan_category'];
				$sub_category2       			 = $getLoanScheme['sub_category'];
				$profit_method2        		     = $getLoanScheme['profit_method'];
				$intrest_rate2        		     = $getLoanScheme['intrest_rate'];
				$total_due2        		     = $getLoanScheme['total_due'];
				$advance_due2        		     = $getLoanScheme['advance_due'];
				$due_period2        		     = $getLoanScheme['due_period'];
				$intreset_type2        		     = $getLoanScheme['intreset_type'];
				$intreset_min2        		     = $getLoanScheme['intreset_min'];
				$intreset_max2        		     = $getLoanScheme['intreset_max'];
				$doc_charge_type2        		     = $getLoanScheme['doc_charge_type'];
				$doc_charge_min2        		     = $getLoanScheme['doc_charge_min'];
				$doc_charge_max2        		     = $getLoanScheme['doc_charge_max'];
				$proc_fee_type2        		     = $getLoanScheme['proc_fee_type'];
				$proc_fee_min2        		     = $getLoanScheme['proc_fee_min'];
				$proc_fee_max2        		     = $getLoanScheme['proc_fee_max'];
				$due_date2        		     = $getLoanScheme['due_date'];
				$overdue2        		     = $getLoanScheme['overdue'];
				$grace_period2        		     = $getLoanScheme['grace_period'];
				$penalty2        		     = $getLoanScheme['penalty'];
				$profit_method2 = explode(',', $profit_method2);
			}
		}
	}
}


if (isset($_GET['type'])) {
	$type = $_GET['type'];
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Loan Scheme
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_loan_scheme">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>

<head>
</head>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="report_creation" name="report_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($idupd)) echo $idupd; ?>" id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($scheme_id)) echo $scheme_id; ?>" id="scheme_id_upd" name="scheme_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($loan_category)) echo $loan_category; ?>" id="loan_category_upd" name="loan_category_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($loan_category1)) echo $loan_category1; ?>" id="loan_category1_upd" name="loan_category1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($loan_category2)) echo $loan_category2; ?>" id="loan_category2_upd" name="loan_category2_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_category)) echo $sub_category; ?>" id="sub_category_upd" name="sub_category_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_category1)) echo $sub_category1; ?>" id="sub_category1_upd" name="sub_category1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($sub_category2)) echo $sub_category2; ?>" id="sub_category2_upd" name="sub_category2_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($due_method)) echo $due_method; ?>" id="due_method_upd" name="due_method_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($doc_charge_type)) echo $doc_charge_type; ?>" id="doc_charge_type_upd" name="doc_charge_type_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($intreset_type)) echo $intreset_type; ?>" id="intreset_type_upd" name="intreset_type_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($proc_fee_type)) echo $proc_fee_type; ?>" id="proc_type_upd" name="proc_type_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($doc_charge_type1)) echo $doc_charge_type1; ?>" id="doc_charge_type1_upd" name="doc_charge_type1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($intreset_type1)) echo $intreset_type1; ?>" id="intreset_type1_upd" name="intreset_type1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($proc_fee_type1)) echo $proc_fee_type1; ?>" id="proc_type1_upd" name="proc_type1_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($doc_charge_type2)) echo $doc_charge_type2; ?>" id="doc_charge_type2_upd" name="doc_charge_type2_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($intreset_type2)) echo $intreset_type2; ?>" id="intreset_type2_upd" name="intreset_type2_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if (isset($proc_fee_type2)) echo $proc_fee_type2; ?>" id="proc_fee_type2_upd" name="proc_fee_type2_upd" aria-describedby="id" placeholder="Enter id">

		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

				<!--*******************************************************************************************************************************************************************************
																		Monthly Scheme
			******************************************************************************************************************************************************************************* -->

				<!-- Monthly Scheme General Card -->
				<div class="card monthly_scheme" <?php if (isset($type) and $type != 'monthly') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">General Info (Monthly)</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="1" type="text" class="form-control" id="loan_category" name="loan_category">
												<option value="">Select Loan Category</option>
												<?php if (sizeof($loanCategoryList) > 0) {
													for ($j = 0; $j < count($loanCategoryList); $j++) { ?>
														<option <?php if (isset($loan_category)) {
																	if ($loanCategoryList[$j]['loan_category_name_id'] == $loan_category)  echo 'selected';
																}  ?> value="<?php echo $loanCategoryList[$j]['loan_category_name_id']; ?>">
															<?php echo $loanCategoryList[$j]['loan_category_name']; ?></option>
												<?php }
												} ?>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Category</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="2" type="text" class="form-control" id="sub_category" name="sub_category">
												<option value="">Select Sub Category</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="scheme_name" id="scheme_name" value="<?php if (isset($scheme_name)) echo $scheme_name; ?>" placeholder="Enter Scheme Name" class="form-control" tabindex="3">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Short Name</label>
											<input type="text" name="scheme_short" id="scheme_short" value="<?php if (isset($scheme_short)) echo $scheme_short; ?>" placeholder="Enter Scheme Short Name" class="form-control" tabindex="4">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Method</label>
											<input type="hidden" name="due_method" id="due_method" value="monthly" class="form-control">
											<input type="text" name="due_method_dummy" id="due_method_dummy" value="Monthly" readonly class="form-control" tabindex='5'>
										</div>
									</div>

									<div id="emi_method" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Profit Method</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="5" type="text" class="form-control selectpicker" id="profit_method" name="profit_method[]" data-live-search="true" multiple data-actions-box="true" title="Select Profit Method">
												<option <?php if (isset($profit_method)) {
															if ($profit_method[0] == "pre_intrest") echo 'selected';
														} ?> value="pre_intrest">Pre Benefit</option>
												<option <?php if (isset($profit_method)) {
															if ($profit_method[0] == "after_intrest") {
																echo 'selected';
															} elseif (isset($profit_method[1]) and $profit_method[1] == "after_intrest") {
																echo 'selected';
															}
														}
														?> value="after_intrest">After Benefit</option>
											</select>
										</div>
									</div>

									<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Interest Rate %</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" step="0.01" name="intrest_rate" id="intrest_rate" value="<?php if (isset($intrest_rate)) echo $intrest_rate; ?>" placeholder="Enter Interest Rate %" class="form-control" tabindex="7">
										</div>
									</div> -->

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Total Due</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" name="total_due" id="total_due" value="<?php if (isset($total_due)) echo $total_due; ?>" placeholder="Enter Total Due" class="form-control" tabindex="8">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Advance Due</label>
											<input type="number" name="advance_due" id="advance_due" value="<?php if (isset($advance_due)) echo $advance_due; ?>" placeholder="Enter Advance Due" class="form-control" tabindex="9">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Period</label>
											<input type="text" name="due_period" id="due_period" readonly value="<?php if (isset($due_period)) echo $due_period; ?>" placeholder="Enter Total & Advance Due" class="form-control" tabindex="10">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Monthly Scheme Condition info Card -->
				<div class="card monthly_scheme" <?php if (isset($type) and $type != 'monthly') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">Condition Info </div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Interest Rate: <span class="text-danger">*</span></label>
											<input type="radio" tabindex="11" name="intreset_type" id="interestamt" value="amt" <?php if (isset($intreset_type) and $intreset_type == 'amt') echo 'checked'; ?>></input><label for='interestamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="12" name="intreset_type" id="interestpercentage" value="percentage" <?php if (isset($intreset_type) and $intreset_type == 'percentage') echo 'checked'; ?>></input><label for='interestpercentage'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="intresetmin">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="13" id="intreset_min" name="intreset_min" readonly class="form-control" placeholder="Enter Minimum Interest" value="<?php if (isset($intreset_min)) echo $intreset_min; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="intersetmax">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="14" id="intreset_max" name="intreset_max" readonly class="form-control" placeholder="Enter Maximum Interest" value="<?php if (isset($intreset_max)) echo $intreset_max; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Document Charge: <span class="text-danger">*</span></label>
											<input type="radio" tabindex="11" name="doc_charge_type" id="docamt" value="amt" <?php if (isset($doc_charge_type) and $doc_charge_type == 'amt') echo 'checked'; ?>></input><label for='docamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="12" name="doc_charge_type" id="docpercentage" value="percentage" <?php if (isset($doc_charge_type) and $doc_charge_type == 'percentage') echo 'checked'; ?>></input><label for='docpercentage'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="docmin">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="13" id="doc_charge_min" name="doc_charge_min" readonly class="form-control" placeholder="Enter Document Charge Min" value="<?php if (isset($doc_charge_min)) echo $doc_charge_min; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="docmax">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="14" id="doc_charge_max" name="doc_charge_max" readonly class="form-control" placeholder="Enter Document Charge Max" value="<?php if (isset($doc_charge_max)) echo $doc_charge_max; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Processing Fee: <span class="text-danger">*</span></label>
											<input type="radio" name="proc_fee_type" id="procamt" value="amt" tabindex="15" <?php if (isset($proc_fee_type) and $proc_fee_type == 'amt') echo 'checked'; ?>></input><label for='procamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="proc_fee_type" id="procpercentage" value="percentage" tabindex="16" <?php if (isset($proc_fee_type) and $proc_fee_type == 'percentage') echo 'checked'; ?>></input><label for='procpercentage'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="procmin">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="17" id="proc_fee_min" name="proc_fee_min" readonly class="form-control" placeholder="Enter Processing Fee Min" value="<?php if (isset($proc_fee_min)) echo $proc_fee_min; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="procmax">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="18" id="proc_fee_max" name="proc_fee_max" readonly class="form-control" placeholder="Enter Processing Fee Max" value="<?php if (isset($proc_fee_max)) echo $proc_fee_max; ?>">
										</div>
									</div>
									<br><br><br><br><br><br><br><br>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
										<div class="form-group">
											<label for="disabledInput">Overdue Penalty %</label><span class='text-danger' style="font-size:11px">&nbsp;*</span>
											<input type="number" tabindex="20" id="overdue" name="overdue" class="form-control" placeholder="Enter Overdue" value="<?php if (isset($overdue)) echo $overdue; ?>" title="Penalty if Exceeded Due Date">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12"></div>

								</div>
							</div>
						</div>
						<div class="col-md-12 ">
							<div class="text-right">
								<a href="edit_loan_scheme">
									<button type="button" class="btn btn-outline-secondary" tabindex="23"><span class="icon-arrow-left"></span>&nbsp; Back</button>
								</a>
								<button type="submit" name="submit_loan_scheme_monthly" id="submit_loan_scheme_monthly" class="btn btn-primary" value="Submit" tabindex="24"><span class="icon-check"></span>&nbsp;Submit</button>
							</div>
						</div>
					</div>
				</div>

				<!--*******************************************************************************************************************************************************************************
																Weekly Scheme
******************************************************************************************************************************************************************************* -->

				<!-- Weekly Scheme General Card -->
				<div class="card weekly_scheme" <?php if (isset($type) and $type != 'weekly') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">General Info (Weekly)</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="1" type="text" class="form-control" id="loan_category1" name="loan_category1">
												<option value="">Select Loan Category</option>
												<?php if (sizeof($loanCategoryList) > 0) {
													for ($j = 0; $j < count($loanCategoryList); $j++) { ?>
														<option <?php if (isset($loan_category1)) {
																	if ($loanCategoryList[$j]['loan_category_name_id'] == $loan_category1)  echo 'selected';
																}  ?> value="<?php echo $loanCategoryList[$j]['loan_category_name_id']; ?>">
															<?php echo $loanCategoryList[$j]['loan_category_name']; ?></option>
												<?php }
												} ?>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Category</label>
											<select tabindex="2" type="text" class="form-control" id="sub_category1" name="sub_category1">
												<option value="">Select Sub Category</option>

											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="scheme_name1" id="scheme_name1" value="<?php if (isset($scheme_name1)) echo $scheme_name1; ?>" placeholder="Enter Scheme Name" class="form-control" tabindex="3">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Short Name</label>
											<input type="text" name="scheme_short1" id="scheme_short1" value="<?php if (isset($scheme_short1)) echo $scheme_short1; ?>" placeholder="Enter Scheme Short Name" class="form-control" tabindex="4">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Method</label>
											<input type="hidden" name="due_method1" id="due_method1" value="weekly" class="form-control">
											<input type="text" name="due_method_dummy" id="due_method_dummy" value="Weekly" readonly class="form-control" tabindex="5">
										</div>
									</div>

									<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Profit Method</label>
											<input type="hidden" name="profit_method1" id="profit_method1" value="pre_intrest" class="form-control">
											<input type="text" name="profit_method_dummy" id="profit_method_dummy" value="Pre Benefit" readonly class="form-control" tabindex="6">
										</div>
									</div> -->
									<div id="emi_method" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Profit Method</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="5" type="text" class="form-control selectpicker" id="profit_method1" name="profit_method1[]" data-live-search="true" multiple data-actions-box="true" title="Select Profit Method">
												<option <?php if (isset($profit_method1)) {
															if ($profit_method1[0] == "pre_intrest") echo 'selected';
														} ?> value="pre_intrest">Pre Benefit</option>
												<option <?php if (isset($profit_method1)) {
															if ($profit_method1[0] == "after_intrest") {
																echo 'selected';
															} elseif (isset($profit_method1[1]) and $profit_method1[1] == "after_intrest") {
																echo 'selected';
															}
														}
														?> value="after_intrest">After Benefit</option>
											</select>
										</div>
									</div>
									<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Interest Rate %</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" name="intrest_rate1" id="intrest_rate1" value="<?php if (isset($intrest_rate1)) echo $intrest_rate1; ?>" placeholder="Enter Interest Rate %" class="form-control" tabindex="7">
										</div>
									</div> -->

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Period</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="due_period1" id="due_period1" value="<?php if (isset($due_period1)) echo $due_period1; ?>" placeholder="Enter Due Period" class="form-control" tabindex='8'>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<!-- Weekly Scheme Condition info Card -->
				<div class="card weekly_scheme" <?php if (isset($type) and $type != 'weekly') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">Condition Info </div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Interest Rate: <span class="text-danger">*</span></label>
											<input type="radio" tabindex="11" name="intreset_type1" id="interestamt1" value="amt" <?php if (isset($intreset_type1) and $intreset_type1 == 'amt') echo 'checked'; ?>></input><label for='interestamt1'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="12" name="intreset_type1" id="interestpercentage1" value="percentage" <?php if (isset($intreset_type1) and $intreset_type1 == 'percentage') echo 'checked'; ?>></input><label for='interestpercentage1'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="intresetmin1">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="13" id="intreset_min1" name="intreset_min1" readonly class="form-control" placeholder="Enter Minimum Interest" value="<?php if (isset($intreset_min1)) echo $intreset_min1; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="intersetmax1">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="14" id="intreset_max1" name="intreset_max1" readonly class="form-control" placeholder="Enter Maximum Interest" value="<?php if (isset($intreset_max1)) echo $intreset_max1; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Document Charge: <span class="text-danger">*</span></label>
											<input type="radio" name="doc_charge_type1" id="docamt1" value="amt" <?php if (isset($doc_charge_type1) and $doc_charge_type1 == 'amt') echo 'checked'; ?> tabindex='9'></input><label for='docamt1'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="doc_charge_type1" id="docpercentage1" value="percentage" <?php if (isset($doc_charge_type1) and $doc_charge_type1 == 'percentage') echo 'checked'; ?> tabindex='10'></input><label for='docpercentage1'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="docmin1">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="11" id="doc_charge_min1" name="doc_charge_min1" readonly class="form-control" placeholder="Enter Document Charge Min" value="<?php if (isset($doc_charge_min1)) echo $doc_charge_min1; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="docmax1">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="12" id="doc_charge_max1" name="doc_charge_max1" readonly class="form-control" placeholder="Enter Document Charge Max" value="<?php if (isset($doc_charge_max1)) echo $doc_charge_max1; ?>">
										</div>
									</div>

									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Processing Fee: <span class="text-danger">*</span></label>
											<input type="radio" name="proc_fee_type1" id="procamt1" value="amt" tabindex='13' <?php if (isset($proc_fee_type1) and $proc_fee_type1 == 'amt') echo 'checked'; ?>></input><label for='procamt1'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="proc_fee_type1" id="procpercentage1" value="percentage" tabindex='14' <?php if (isset($proc_fee_type1) and $proc_fee_type1 == 'percentage') echo 'checked'; ?>></input><label for='procpercentage1'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="procmin1">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="15" id="proc_fee_min1" name="proc_fee_min1" readonly class="form-control" placeholder="Enter Processing Fee Min" value="<?php if (isset($proc_fee_min1)) echo $proc_fee_min1; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="procmax1">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="16" id="proc_fee_max1" name="proc_fee_max1" readonly class="form-control" placeholder="Enter Processing Fee Max" value="<?php if (isset($proc_fee_max1)) echo $proc_fee_max1; ?>">
										</div>
									</div>
									<br><br><br><br><br><br><br><br>
									<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Day</label>&nbsp;<span class="text-danger">*</span>
											<select class='form-control' name='due_day' id='due_day' tabindex='17' >
												<option value="">Select Due Day</option>
												<option value="1" <?php if (isset($due_day) and $due_day == '1') echo 'selected'; ?>>Sunday</option>
												<option value="2"<?php if (isset($due_day) and $due_day == '2') echo 'selected'; ?>>Monday</option>
												<option value="3"<?php if (isset($due_day) and $due_day == '3') echo 'selected'; ?>>Tuesday</option>
												<option value="4"<?php if (isset($due_day) and $due_day == '4') echo 'selected'; ?>>Wednesday</option>
												<option value="5"<?php if (isset($due_day) and $due_day == '5') echo 'selected'; ?>>Thursday</option>
												<option value="6"<?php if (isset($due_day) and $due_day == '6') echo 'selected'; ?>>Friday</option>
												<option value="7"<?php if (isset($due_day) and $due_day == '7') echo 'selected'; ?>>Saturday</option>
											</select>
										</div>
									</div> -->
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
										<div class="form-group">
											<label for="disabledInput">Overdue Penalty %</label><span class='text-danger' style="font-size:11px">&nbsp;*</span>
											<input type="number" tabindex="18" id="overdue1" name="overdue1" class="form-control" placeholder="Enter Overdue" title="Penalty if Exceeded Due Day" value="<?php if (isset($overdue1)) echo $overdue1; ?>">
										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="col-md-12 ">
							<div class="text-right">
								<a href="edit_loan_scheme">
									<button type="button" class="btn btn-outline-secondary" tabindex='19'><span class="icon-arrow-left"></span>&nbsp; Back</button>
								</a>
								<button type="submit" name="submit_loan_scheme_weekly" id="submit_loan_scheme_weekly" class="btn btn-primary" value="Submit" tabindex="20"><span class="icon-check"></span>&nbsp;Submit</button>
							</div>
						</div>
					</div>
				</div>

				<!--*******************************************************************************************************************************************************************************
																Daily Scheme
******************************************************************************************************************************************************************************* -->

				<!-- Daily Scheme General Card -->
				<div class="card daily_scheme" <?php if (isset($type) and $type != 'daily') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">General Info (Daily)</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label class="label">Loan Category</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="1" type="text" class="form-control" id="loan_category2" name="loan_category2">
												<option value="">Select Loan Category</option>
												<?php if (sizeof($loanCategoryList) > 0) {
													for ($j = 0; $j < count($loanCategoryList); $j++) { ?>
														<option <?php if (isset($loan_category2)) {
																	if ($loanCategoryList[$j]['loan_category_name_id'] == $loan_category2)  echo 'selected';
																}  ?> value="<?php echo $loanCategoryList[$j]['loan_category_name_id']; ?>">
															<?php echo $loanCategoryList[$j]['loan_category_name']; ?></option>
												<?php }
												} ?>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Category</label>
											<select tabindex="2" type="text" class="form-control" id="sub_category2" name="sub_category2">
												<option value="">Select Sub Category</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Name</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="scheme_name2" id="scheme_name2" value="<?php if (isset($scheme_name2)) echo $scheme_name2; ?>" placeholder="Enter Scheme Name" class="form-control" tabindex="3">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Short Name</label>
											<input type="text" name="scheme_short2" id="scheme_short2" value="<?php if (isset($scheme_short2)) echo $scheme_short2; ?>" placeholder="Enter Scheme Short Name" class="form-control" tabindex="4">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Method</label>
											<input type="hidden" name="due_method2" id="due_method2" value="daily" class="form-control">
											<input type="text" name="due_method_dummy" id="due_method_dummy" value="Daily" readonly class="form-control" tabindex='5'>
										</div>
									</div>
									<!-- 
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Profit Method</label>
											<input type="hidden" name="profit_method2" id="profit_method2" value="pre_intrest" class="form-control">
											<input type="text" name="profit_method_dummy" id="profit_method_dummy" value="Pre Benefit" readonly class="form-control" tabindex='6'>
										</div>
									</div> -->
									<div id="emi_method" class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Profit Method</label>&nbsp;<span class="text-danger">*</span>
											<select tabindex="5" type="text" class="form-control selectpicker" id="profit_method2" name="profit_method2[]" data-live-search="true" multiple data-actions-box="true" title="Select Profit Method">
												<option <?php if (isset($profit_method2)) {
															if ($profit_method2[0] == "pre_intrest") echo 'selected';
														} ?> value="pre_intrest">Pre Benefit</option>
												<option <?php if (isset($profit_method2)) {
															if ($profit_method2[0] == "after_intrest") {
																echo 'selected';
															} elseif (isset($profit_method2[1]) and $profit_method2[1] == "after_intrest") {
																echo 'selected';
															}
														}
														?> value="after_intrest">After Benefit</option>
											</select>
										</div>
									</div>
									<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Interest Rate %</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" name="intrest_rate2" id="intrest_rate2" value="<?php if (isset($intrest_rate2)) echo $intrest_rate2; ?>" placeholder="Enter Interest Rate %" class="form-control" tabindex='7'>
										</div>
									</div> -->

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Period</label>&nbsp;<span class="text-danger">*</span>
											<input type="text" name="due_period2" id="due_period2" value="<?php if (isset($due_period2)) echo $due_period2; ?>" placeholder="Enter Due Period" class="form-control" tabindex='8'>
										</div>
									</div>

								</div>
							</div>
						</div>

					</div>
				</div>
				<!-- Daily Scheme Condition info Card -->
				<div class="card daily_scheme" <?php if (isset($type) and $type != 'daily') { ?> style="display:none" <?php } ?>>
					<div class="card-header">
						<div class="card-title">Condition Info </div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Interest Rate: <span class="text-danger">*</span></label>
											<input type="radio" tabindex="11" name="intreset_type2" id="interestamt2" value="amt" <?php if (isset($intreset_type2) and $intreset_type2 == 'amt') echo 'checked'; ?>></input><label for='interestamt2'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex="12" name="intreset_type2" id="interestpercentage2" value="percentage" <?php if (isset($intreset_type2) and $intreset_type2 == 'percentage') echo 'checked'; ?>></input><label for='interestpercentage2'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="intresetmin2">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="13" id="intreset_min2" name="intreset_min2" readonly class="form-control" placeholder="Enter Minimum Interest" value="<?php if (isset($intreset_min2)) echo $intreset_min2; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="intersetmax2">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="14" id="intreset_max2" name="intreset_max2" readonly class="form-control" placeholder="Enter Maximum Interest" value="<?php if (isset($intreset_max2)) echo $intreset_max2; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Document Charge: <span class="text-danger">*</span></label>
											<input type="radio" name="doc_charge_type2" id="docamt2" value="amt" tabindex='9' <?php if (isset($doc_charge_type2) and $doc_charge_type2 == 'amt') echo 'checked'; ?>></input><label for='docamt2'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="doc_charge_type2" id="docpercentage2" value="percentage" tabindex='10' <?php if (isset($doc_charge_type2) and $doc_charge_type2 == 'percentage') echo 'checked'; ?>></input><label for='docpercentage2'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="docmin2">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="11" id="doc_charge_min2" name="doc_charge_min2" readonly class="form-control" placeholder="Enter Document Charge Min" value="<?php if (isset($doc_charge_min2)) echo $doc_charge_min2; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="docmax2">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="12" id="doc_charge_max2" name="doc_charge_max2" readonly class="form-control" placeholder="Enter Document Charge Max" value="<?php if (isset($doc_charge_max2)) echo $doc_charge_max2; ?>">
										</div>
									</div>

									<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group">
											<label style="font-size:1.35em;padding-right:2%">Processing Fee: <span class="text-danger">*</span></label>
											<input type="radio" name="proc_fee_type2" id="procamt2" value="amt" tabindex='13' <?php if (isset($proc_fee_type2) and $proc_fee_type2 == 'amt') echo 'checked'; ?>></input><label for='procamt2'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="proc_fee_type2" id="procpercentage2" value="percentage" tabindex='14' <?php if (isset($proc_fee_type2) and $proc_fee_type2 == 'percentage') echo 'checked'; ?>></input><label for='procpercentage2'>&nbsp;&nbsp;%</label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="procmin2">Min</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="15" id="proc_fee_min2" name="proc_fee_min2" readonly class="form-control" placeholder="Enter Processing Fee Min" value="<?php if (isset($proc_fee_min2)) echo $proc_fee_min2; ?>">
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput" id="procmax2">Max</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" step="0.01" tabindex="16" id="proc_fee_max2" name="proc_fee_max2" readonly class="form-control" placeholder="Enter Processing Fee Max" value="<?php if (isset($proc_fee_max2)) echo $proc_fee_max2; ?>">
										</div>
									</div>
									<br><br><br><br><br><br><br><br>
									<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
										<div class="form-group">
											<label for="disabledInput">Overdue Penalty %</label><span class='text-danger' style="font-size:11px">&nbsp;*</span>
											<input type="number" tabindex="17" id="overdue2" name="overdue2" class="form-control" placeholder="Enter Overdue" title="Penalty if Exceeded Due Day" value="<?php if (isset($overdue2)) echo $overdue2; ?>">
										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="col-md-12 ">
							<div class="text-right">
								<a href="edit_loan_scheme">
									<button type="button" class="btn btn-outline-secondary" tabindex='18'><span class="icon-arrow-left"></span>&nbsp; Back</button>
								</a>
								<button type="submit" name="submit_loan_scheme_daily" id="submit_loan_scheme_daily" class="btn btn-primary" value="Submit" tabindex="19"><span class="icon-check"></span>&nbsp;Submit</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>