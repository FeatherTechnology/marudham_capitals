<?php
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$getuser = $userObj->getuser($mysqli, $userid);
$userRole = $getuser["role"];
$request = $getuser['request'];
$verification = $getuser['verification'];
$approval = $getuser['approval'];
$acknowledgement = $getuser['acknowledgement'];
$loan_issue = $getuser['loan_issue'];
$collection = $getuser['collection'];
$closed = $getuser['closed'];
$noc = $getuser['noc'];

$getValues = $userObj->getDataForDashboard($mysqli, $userid);

?>
<!-- for fadeIn animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js" integrity="sha512-Eak/29OTpb36LLo2r47IpVzPBLXnAMPAVypbSZiZ4Qkf8p/7S/XRG5xp7OKWPPYfJT6metI+IORkR5G8F900+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="https://cdn.jsdelivr.net/npm/wowjs@1.1.3/css/libs/animate.min.css" rel="stylesheet">
<!-- for counter -->
<script src="https://unpkg.com/counterup2@2.0.2/dist/index.js"></script>
<link rel="stylesheet" href="css/dashboard.css">


<!-- Page header start -->
<br><br>
<br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="dashboard_form" name="dashboard_form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="sub_area_list" id="sub_area_list" value=''>
		<?php if ($userRole == 2) { ?>
			<!-- Row start -->
			<p class="heading-list wow fadeInUp">Request</p>

			<div class="row gutters">
				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card  wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today's Request</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['today_request']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card  wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month's Request</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['month_request']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Row start -->
			<p class="heading-list wow fadeInUp">Loan</p>
			<div class="row gutters">
				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card  wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today's Issued Loan</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['today_loan']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card  wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month's Issued Loan</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['month_loan']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Row start -->
			<p class="heading-list wow fadeInUp">Collection</p>
			<div class="row gutters">
				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today's No of Collection</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['today_collection_no']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month's No of Collection</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['month_collection_no']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today's Collection</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['today_collection']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="card wow">
						<div class="card-header">
							<div class="card-title"></div>
						</div>
						<div class="card-body" style="display:flex;justify-content:center;align-items: center;">
							<div class="row">
								<div class="col-12">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month's Collection</p>
										<p class="counter wow fadeInUp"><?php echo $getValues['month_collection']; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="d-flex justify-content-between">
				<div class="dash-input-div">
					<input type="month" name="filter_month" class="dash-input" id="filter_month">
				</div>
				<div class="dash-input-div" <?php if ($userRole != 1) { ?> style="display:none" <?php } ?>>
					<select name="branch_id" id="branch_id" class="dash-input">
						<option value="">Choose Branch</option>
					</select>
				</div>
			</div>

			<div class="card" id="request_card" <?php if ($request == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="req_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Request</div>
				</div>
				<div class="card-body" id="req_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Requests</p>
										<p class="counter wow fadeInUp" id="tot_req"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Issued</p>
										<p class="counter wow fadeInUp" id="tot_req_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Balance</p>
										<p class="counter wow fadeInUp" id="tot_req_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Requests</p>
										<p class="counter wow fadeInUp" id="today_req"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Issued</p>
										<p class="counter wow fadeInUp" id="today_req_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Balance</p>
										<p class="counter wow fadeInUp" id="today_req_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12">
							<div class="card" style="border:3px solid #009688">
								<div class="card-body">
									<div class="radio-container">
										<div class="selector">
											<div class="selector-item">
												<input type="radio" id="req_radio1" name="req_radio" class="selector-item_radio" checked>
												<label for="req_radio1" class="selector-item_label">Cancel & Revoke</label>
											</div>
											<div class="selector-item">
												<input type="radio" id="req_radio2" name="req_radio" class="selector-item_radio">
												<label for="req_radio2" class="selector-item_label">Customer Type</label>
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-6">
											<div class="charts" id="req_tot_chart"></div>
										</div>
										<div class="col-6">
											<div class="charts" id="req_today_chart"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="ver_card" <?php if ($verification == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="ver_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Verification</div>
				</div>
				<div class="card-body" id="ver_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total In Verification</p>
										<p class="counter wow fadeInUp" id="tot_in_ver"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Issued</p>
										<p class="counter wow fadeInUp" id="tot_ver_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Balance</p>
										<p class="counter wow fadeInUp" id="tot_ver_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today In Verification</p>
										<p class="counter wow fadeInUp" id="today_in_ver"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Issued</p>
										<p class="counter wow fadeInUp" id="today_ver_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Balance</p>
										<p class="counter wow fadeInUp" id="today_ver_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12">
							<div class="card" style="border:3px solid #009688">
								<div class="card-body">
									<div class="radio-container">
										<div class="selector">
											<div class="selector-item">
												<input type="radio" id="ver_radio1" name="ver_radio" class="selector-item_radio" checked>
												<label for="ver_radio1" class="selector-item_label">Cancel & Revoke</label>
											</div>
											<div class="selector-item">
												<input type="radio" id="ver_radio2" name="ver_radio" class="selector-item_radio">
												<label for="ver_radio2" class="selector-item_label">Customer Type</label>
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-6">
											<div class="charts" id="ver_tot_chart"></div>
										</div>
										<div class="col-6">
											<div class="charts" id="ver_today_chart"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="app_card" <?php if ($approval == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="app_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Approval</div>
				</div>
				<div class="card-body" id="app_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total In Approval</p>
										<p class="counter wow fadeInUp" id="tot_in_app"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Issued</p>
										<p class="counter wow fadeInUp" id="tot_app_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Balance</p>
										<p class="counter wow fadeInUp" id="tot_app_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today In Approval</p>
										<p class="counter wow fadeInUp" id="today_in_app"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Issued</p>
										<p class="counter wow fadeInUp" id="today_app_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Balance</p>
										<p class="counter wow fadeInUp" id="today_app_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12">
							<div class="card" style="border:3px solid #009688">
								<div class="card-body">
									<div class="radio-container">
										<div class="selector">
											<div class="selector-item">
												<input type="radio" id="app_radio1" name="app_radio" class="selector-item_radio" checked>
												<label for="app_radio1" class="selector-item_label">Cancel & Revoke</label>
											</div>
											<div class="selector-item">
												<input type="radio" id="app_radio2" name="app_radio" class="selector-item_radio">
												<label for="app_radio2" class="selector-item_label">Customer Type</label>
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-6">
											<div class="charts" id="app_tot_chart"></div>
										</div>
										<div class="col-6">
											<div class="charts" id="app_today_chart"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="ack_card" <?php if ($acknowledgement == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="ack_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Acknowledgment</div>
				</div>
				<div class="card-body" id="ack_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total In Ack</p>
										<p class="counter wow fadeInUp" id="tot_in_ack"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Issued</p>
										<p class="counter wow fadeInUp" id="tot_ack_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Balance</p>
										<p class="counter wow fadeInUp" id="tot_ack_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today In Ack</p>
										<p class="counter wow fadeInUp" id="today_in_ack"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Issued</p>
										<p class="counter wow fadeInUp" id="today_ack_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Balance</p>
										<p class="counter wow fadeInUp" id="today_ack_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12">
							<div class="card" style="border:3px solid #009688">
								<div class="card-body">
									<div class="radio-container">
										<div class="selector">
											<div class="selector-item">
												<input type="radio" id="ack_radio1" name="ack_radio" class="selector-item_radio" checked>
												<label for="ack_radio1" class="selector-item_label">Cancel & Revoke</label>
											</div>
											<div class="selector-item">
												<input type="radio" id="ack_radio2" name="ack_radio" class="selector-item_radio">
												<label for="ack_radio2" class="selector-item_label">Customer Type</label>
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-6">
											<div class="charts" id="ack_tot_chart"></div>
										</div>
										<div class="col-6">
											<div class="charts" id="ack_today_chart"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="li_card" <?php if ($loan_issue == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="li_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Loan Issue</div>
				</div>
				<div class="card-body" id="li_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Loan Issue</p>
										<p class="counter wow fadeInUp" id="tot_li"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Issued</p>
										<p class="counter wow fadeInUp" id="tot_li_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Balance</p>
										<p class="counter wow fadeInUp" id="tot_li_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Loan Issue</p>
										<p class="counter wow fadeInUp" id="today_li"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Issued</p>
										<p class="counter wow fadeInUp" id="today_li_issue"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Balance</p>
										<p class="counter wow fadeInUp" id="today_li_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12">
							<div class="card" style="border:3px solid #009688">
								<div class="card-body">
									<div class="radio-container">
										<div class="selector">
											<div class="selector-item">
												<input type="radio" id="li_radio1" name="li_radio" class="selector-item_radio" checked>
												<label for="li_radio1" class="selector-item_label">Issued by Modes</label>
											</div>
											<div class="selector-item">
												<input type="radio" id="li_radio2" name="li_radio" class="selector-item_radio">
												<label for="li_radio2" class="selector-item_label">Customer Type</label>
											</div>
											<div class="selector-item">
												<input type="radio" id="li_radio3" name="li_radio" class="selector-item_radio">
												<label for="li_radio3" class="selector-item_label">Issue Amount</label>
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-6">
											<div class="charts" id="li_tot_chart"></div>
										</div>
										<div class="col-6">
											<div class="charts" id="li_today_chart"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="col_card" <?php if ($collection == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="col_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Collection</div>
				</div>
				<div class="card-body" id="col_body" style="display: none;">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Paid</p>
										<p class="counter wow fadeInUp col-counter" id="tot_col_paid"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Penalty</p>
										<p class="counter wow fadeInUp col-counter" id="tot_col_pen"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Fine</p>
										<p class="counter wow fadeInUp col-counter" id="tot_col_fine"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Paid</p>
										<p class="counter wow fadeInUp col-counter" id="today_col_paid"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Penalty</p>
										<p class="counter wow fadeInUp col-counter" id="today_col_pen"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Fine</p>
										<p class="counter wow fadeInUp col-counter" id="today_col_fine"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12" id="col_chart_part" style="display: none;">
							<div class="card" style="border:3px solid #009688">
								<div class="card-body">
									<div class="radio-container">
										<div class="selector">
											<div class="selector-item">
												<input type="radio" id="col_radio1" name="col_radio" class="selector-item_radio" checked>
												<label for="col_radio1" class="selector-item_label" id="col_split_type">Collection Split Chart</label>
											</div>
										</div>
									</div>
									<br><br>
									<div class="row">
										<div class="col-6">
											<div class="charts" id="col_chart1"></div>
										</div>
										<div class="col-6">
											<div class="charts" id="col_chart2"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="cl_card" <?php if ($closed == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="cl_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Closed</div>
				</div>
				<div class="card-body" id="cl_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total In Closed</p>
										<p class="counter wow fadeInUp" id="tot_in_cl"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards month-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month In Closed</p>
										<p class="counter wow fadeInUp" id="month_in_cl"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards month-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month Status</p>
										<p class="counter wow fadeInUp" id="month_cl_status"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards month-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month Balance</p>
										<p class="counter wow fadeInUp" id="month_cl_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today In Closed</p>
										<p class="counter wow fadeInUp" id="today_in_cl"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Status</p>
										<p class="counter wow fadeInUp" id="today_cl_status"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-12">
							<div class="card" style="border:3px solid #009688;padding:10px;">
								<div class="card-header">
									<div class="card-title" >Month's Closed Status</div>
								</div>
								<div class="card-body">
									<div class="row" style="display:flex;justify-content:center;">

										<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
											<div class="card">
												<div class="card-body counter-cards">
													<div class="form-group text-center">
														<p class='counter-head wow fadeIn'>Waiting List</p>
														<p class="counter wow fadeInUp" id="cl_wl"></p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
											<div class="card">
												<div class="card-body counter-cards">
													<div class="form-group text-center">
														<p class='counter-head wow fadeIn'>Blocked List</p>
														<p class="counter wow fadeInUp" id="cl_bl"></p>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3"></div>
										<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
											<div class="card">
												<div class="card-body counter-cards" style="background-color:wheat">
													<div class="form-group text-center">
														<p class='counter-head wow fadeIn'>Consider</p>
														<p class="counter wow fadeInUp" id="cl_cn"></p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row" style="display:flex;justify-content:center;">
									<div class="col-sm-12 col-md-6 col-lg-4 col-xl-2">
										<div class="card">
											<div class="card-body counter-cards consider-cards">
												<div class="form-group text-center">
													<p class='counter-head wow fadeIn'>Bronze</p>
													<p class="counter wow fadeInUp" id="cl_bronze"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-4 col-xl-2">
										<div class="card">
											<div class="card-body counter-cards consider-cards">
												<div class="form-group text-center">
													<p class='counter-head wow fadeIn'>Silver</p>
													<p class="counter wow fadeInUp" id="cl_silver"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-4 col-xl-2">
										<div class="card">
											<div class="card-body counter-cards consider-cards">
												<div class="form-group text-center">
													<p class='counter-head wow fadeIn'>Gold</p>
													<p class="counter wow fadeInUp" id="cl_gold"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-4 col-xl-2">
										<div class="card">
											<div class="card-body counter-cards consider-cards">
												<div class="form-group text-center">
													<p class='counter-head wow fadeIn'>Platinum</p>
													<p class="counter wow fadeInUp" id="cl_platinum"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-4 col-xl-2">
										<div class="card">
											<div class="card-body counter-cards consider-cards">
												<div class="form-group text-center">
													<p class='counter-head wow fadeIn'>Diamond</p>
													<p class="counter wow fadeInUp" id="cl_diamond"></p>
												</div>
											</div>
										</div>
									</div>

								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="noc_card" <?php if ($noc == 1) { ?> style="display: none;" <?php } ?>>
				<div class="card-header" id="noc_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">NOC</div>
				</div>
				<div class="card-body" id="noc_body" style="display:none">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total NOC</p>
										<p class="counter wow fadeInUp" id="tot_noc"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Total Issued</p>
										<p class="counter wow fadeInUp" id="tot_noc_issued"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards month-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month NOC</p>
										<p class="counter wow fadeInUp" id="month_noc"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards month-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month Issued</p>
										<p class="counter wow fadeInUp" id="month_noc_issued"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards month-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Month Balance</p>
										<p class="counter wow fadeInUp" id="month_noc_bal"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today NOC</p>
										<p class="counter wow fadeInUp" id="today_noc"></p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
							<div class="card">
								<div class="card-body counter-cards today-card">
									<div class="form-group text-center">
										<p class='counter-head wow fadeIn'>Today Issued</p>
										<p class="counter wow fadeInUp" id="today_noc_issued"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</form>
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src='js/dashboard.js'></script>