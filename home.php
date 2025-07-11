<?php
@session_start();

if (isset($_SESSION['fullname'])) {
	$fullname  = $_SESSION['fullname'];
}
if (isset($_SESSION['userid'])) {
	$userid  = $_SESSION['userid'];
}

$msc = 0;
if (isset($_GET['msc'])) {
	$msc = $_GET['msc'];
}
$current_page = isset($_GET['page']) ? $_GET['page'] : null;
include('api/main.php'); // Database Connection File   
if (isset($getuserdetails['download_access'])) {
	define('DACC', $getuserdetails['download_access']);
}
?>

<!doctype html>
<html lang="en">

<!-- downlaod customer excel div -->
<div id="backup_customer" style="display:none"></div>
<div id="accountdata" style="display:none"></div>
<!-- end customer excel div -->

<!-- Important -->
<?php if ($current_page != 'vendorcreation' and $current_page != 'auction_details') { ?>
	<?php include "include/common/dashboardhead.php" ?>
<?php  } ?>


<?php if ($current_page == 'vendorcreation') { ?>
	<?php include "include/common/dashboardfinancedatatablehead.php" ?>
<?php } ?>
<?php if ($current_page == 'auction_details') { ?>
	<?php include "include/common/dashboardfinancedatatablehead.php" ?>
<?php } ?>

<body>
	<!-- Page wrapper start -->
	<div class="page-wrapper">
		<?php
		if ($_SESSION['userid'] == "") {
			echo "<script>location.href='index.php'</script>";
		}
		include "include/common/leftbar.php" ?>

		<!-- Page content start  -->
		<div class="page-content">

			<!-- Header start -->
			<header class="header">
				<div class="toggle-btns">
					<a id="toggle-sidebar" href="#">
						<i class="icon-list"></i>
					</a>
					<a id="pin-sidebar" href="#">
						<i class="icon-list"></i>
					</a>
				</div>
				<div class="header-items">
					<!-- Custom search start -->
					<ul class="header-actions">
						<li class="dropdown"></li>
						<li class="dropdown">
							<div class="custom-search">
								<input type="text" id="search_input_" class="search-query" placeholder="Search here ...">
								<!-- <i class="icon-search1" id="search_screens" data-toggle="dropdown" aria-haspopup="true"></i> -->
							</div>
							<div class="dropdown-menu dropdown-menu-right lrg" aria-labelledby="notifications">
								<div class="dropdown-menu-header">
									Results
								</div>
								<div class="customScroll5 quickscard">
									<ul class="header-notifications" id='search_ul'></ul>
								</div>
							</div>
						</li>
					</ul>
					<!-- Custom search end -->

					<!-- Header actions start -->
					<ul class="header-actions">
						<li class="dropdown"></li>
						<li class="dropdown">
							<a href="#" id="notifications" data-toggle="dropdown" aria-haspopup="true">
								<i class="icon-bell"></i>
								<span class="count-label"><?php //echo count($notification); // count($notificationmax); 
															?></span>
							</a>
							<div class="dropdown-menu dropdown-menu-right lrg" aria-labelledby="notifications">
								<div class="dropdown-menu-header">
									Notifications
								</div>
								<div class="customScroll5 quickscard">
									<ul class="header-notifications"></ul>
								</div>
							</div>
						</li>
						<li class="dropdown">
							<a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
								<span class="user-name"><?php echo $fullname; ?></span>
								<span class="avatar">
									<img src="img/avatar.png" alt="avatar">
									<span class="status busy"></span>
								</span>
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userSettings">
								<div class="header-profile-actions">
									<div class="header-user-profile">
										<div class="header-user">
											<img src="img/avatar.png" alt="Admin Template">
										</div>
										<h5><?php echo $fullname; ?></h5>
										<p><?php echo $fullname; ?></p>
									</div>
									<!-- <a href="#"><i class="icon-user1"></i> My Profile</a> -->
									<a href="logout.php" class="logout-link"><i class="icon-log-out1"></i>Log Out</a>
								</div>
							</div>
						</li>
					</ul>
					<!-- Header actions end -->
				</div>
			</header>
			<!-- Header end -->

			<?php if ($current_page == 'dashboard') { ?>
				<?php include "include/templates/dashboard.php" ?>
			<?php } else ?>

			<!-- Master Module-->
			<?php if ($current_page == 'company_creation') { ?>
				<?php include "include/templates/company_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_company_creation') { ?>
				<?php include "include/templates/edit_company_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'branch_creation') { ?>
				<?php include "include/templates/branch_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_branch_creation') { ?>
				<?php include "include/templates/edit_branch_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'loan_category') { ?>
				<?php include "include/templates/loan_category.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_loan_category') { ?>
				<?php include "include/templates/edit_loan_category.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'loan_calculation') { ?>
				<?php include "include/templates/loan_calculation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_loan_calculation') { ?>
				<?php include "include/templates/edit_loan_calculation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'loan_scheme') { ?>
				<?php include "include/templates/loan_scheme.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_loan_scheme') { ?>
				<?php include "include/templates/edit_loan_scheme.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'area_creation') { ?>
				<?php include "include/templates/area_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_area_creation') { ?>
				<?php include "include/templates/edit_area_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'area_mapping') { ?>
				<?php include "include/templates/area_mapping.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_area_mapping') { ?>
				<?php include "include/templates/edit_area_mapping.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'area_status') { ?>
				<?php include "include/templates/area_status.php" ?>
			<?php } else ?>

			<!-- Administration Module-->
			<?php if ($current_page == 'director_creation') { ?>
				<?php include "include/templates/director_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_director_creation') { ?>
				<?php include "include/templates/edit_director_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'agent_creation') { ?>
				<?php include "include/templates/agent_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_agent_creation') { ?>
				<?php include "include/templates/edit_agent_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'staff_creation') { ?>
				<?php include "include/templates/staff_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_staff_creation') { ?>
				<?php include "include/templates/edit_staff_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'manage_user') { ?>
				<?php include "include/templates/manage_user.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_manage_user') { ?>
				<?php include "include/templates/edit_manage_user.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'doc_mapping') { ?>
				<?php include "include/templates/doc_mapping.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_doc_mapping') { ?>
				<?php include "include/templates/edit_doc_mapping.php" ?>
			<?php } else ?>

			<!-- Request Module -->
			<?php if ($current_page == 'request') { ?>
				<?php include "include/templates/request.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_request') { ?>
				<?php include "include/templates/edit_request.php" ?>
			<?php } else ?>

			<!-- Verification -->
			<?php if ($current_page == 'verification_list') { ?>
				<?php include "include/templates/verification_list.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'verification') { ?>
				<?php include "include/templates/verification.php" ?>
			<?php } else ?>

			<!-- Approval -->
			<?php if ($current_page == 'approval_list') { ?>
				<?php include "include/templates/approval_list.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'approval') { ?>
				<?php include "include/templates/approval.php" ?>
			<?php } else ?>

			<!-- Acknowledgement -->
			<?php if ($current_page == 'edit_acknowledgement_list') { ?>
				<?php include "include/templates/edit_acknowledgement_list.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'acknowledgement_creation') { ?>
				<?php include "include/templates/acknowledgement_creation.php" ?>
			<?php } else ?>

			<!-- Loan Issue -->
			<?php if ($current_page == 'edit_loan_issue') { ?>
				<?php include "include/templates/edit_loan_issue.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'loan_issue') { ?>
				<?php include "include/templates/loan_issue.php" ?>
			<?php } else ?>

			<!-- Collection -->
			<?php if ($current_page == 'edit_collection') { ?>
				<?php include "include/templates/edit_collection.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'collection') { ?>
				<?php include "include/templates/collection.php" ?>
			<?php } else ?>

			<!-- Closed -->
			<?php if ($current_page == 'edit_closed') { ?>
				<?php include "include/templates/edit_closed.php" ?>
			<?php } else ?>
			<?php if ($current_page == 'closed') { ?>
				<?php include "include/templates/closed.php" ?>
			<?php } else ?>

			<!-- NOC -->
			<?php if ($current_page == 'edit_noc') { ?>
				<?php include "include/templates/edit_noc.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'noc') { ?>
				<?php include "include/templates/noc.php" ?>
			<?php } else ?>

			<!-- Concern Creation -->
			<?php if ($current_page == 'edit_concern_creation') { ?>
				<?php include "include/templates/edit_concern_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'concern_creation') { ?>
				<?php include "include/templates/concern_creation.php" ?>
			<?php } else ?>

			<!-- Concern Solution -->
			<?php if ($current_page == 'edit_concern_solution') { ?>
				<?php include "include/templates/edit_concern_solution.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'concern_solution') { ?>
				<?php include "include/templates/concern_solution.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'concern_solution_view') { ?>
				<?php include "include/templates/concern_solution_view.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_concern_feedback') { ?>
				<?php include "include/templates/edit_concern_feedback.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'concern_feedback') { ?>
				<?php include "include/templates/concern_feedback.php" ?>
			<?php } else ?>

			<!-- Document Track Screen -->
			<?php if ($current_page == 'document_track') { ?>
				<?php include "include/templates/document_track.php" ?>
			<?php } else ?>

			<!-- update customer status Screen -->
			<?php if ($current_page == 'update_customer_status') { ?>
				<?php include "include/templates/update_customer_status.php" ?>
			<?php } else ?>

			<!-- Update Screen -->
			<?php if ($current_page == 'edit_update') { ?>
				<?php include "include/templates/edit_update.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'update') { ?>
				<?php include "include/templates/update.php" ?>
			<?php } else ?>

			<!-- Bank Creation -->
			<?php if ($current_page == 'bank_creation') { ?>
				<?php include "include/templates/bank_creation.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_bank_creation') { ?>
				<?php include "include/templates/edit_bank_creation.php" ?>
			<?php } else ?>

			<!-- Cash Tally -->
			<?php if ($current_page == 'cash_tally') { ?>
				<?php include "include/templates/cash_tally.php" ?>
			<?php } else ?>

			<!-- Bank Clearance -->
			<?php if ($current_page == 'bank_clearance') { ?>
				<?php include "include/templates/bank_clearance.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'edit_bank_clearance') { ?>
				<?php include "include/templates/edit_bank_clearance.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'finance_insight') { ?>
				<?php include "include/templates/finance_insight.php" ?>
			<?php } else ?>
			<!--Accounts Loan Issue--->
			<?php if ($current_page == 'edit_accounts_loan_issue') { ?>
				<?php include "include/templates/edit_accounts_loan_issue.php" ?>
			<?php } else ?>
			<?php if ($current_page == 'accounts_loan_issue') { ?>
				<?php include "include/templates/accounts_loan_issue.php" ?>
			<?php } else ?>
			<!-- Follow up -->
			<?php if ($current_page == 'promotion_activity') { ?>
				<?php include "include/templates/promotion_activity.php" ?>
			<?php } else ?>

			<!-- Due Follow up -->
			<?php if ($current_page == 'edit_due_followup') { ?>
				<?php include "include/templates/edit_due_followup.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'due_followup') { ?>
				<?php include "include/templates/due_followup.php" ?>
			<?php } else ?>

			<?php if ($current_page == 'due_followup_info') { ?>
				<?php include "include/templates/due_followup_info.php" ?>
			<?php } else ?>

			<!-- Loan Follow up -->
			<?php if ($current_page == 'loan_followup') { ?>
				<?php include "include/templates/loan_followup.php" ?>
			<?php } else ?>

			<!-- Confirmation Follow up -->
			<?php if ($current_page == 'confirmation_followup') { ?>
				<?php include "include/templates/confirmation_followup.php" ?>
			<?php } else ?>

			<!-- Reports -->
			<!-- Ledger Report -->
			<?php if ($current_page == 'ledger_report') { ?>
				<?php include "include/templates/ledger_report.php" ?>
			<?php } else ?>

			<!-- Request Report -->
			<?php if ($current_page == 'request_report') { ?>
				<?php include "include/templates/request_report.php" ?>
			<?php } else ?>
			<!-- Cancel and Revoke  Report -->
			<?php if ($current_page == 'cancel_revoke_report') { ?>
				<?php include "include/templates/cancel_revoke_report.php" ?>
			<?php } else ?>
			<!-- Customer Profile Report -->
			<?php if ($current_page == 'cus_profile_report') { ?>
				<?php include "include/templates/cus_profile_report.php" ?>
			<?php } else ?>

			<!-- Loan issue Report -->
			<?php if ($current_page == 'loan_issue_report') { ?>
				<?php include "include/templates/loan_issue_report.php" ?>
			<?php } else ?>

			<!-- Collection Report -->
			<?php if ($current_page == 'collection_report') { ?>
				<?php include "include/templates/collection_report.php" ?>
			<?php } else ?>
			<!-- Principal and Interest Report -->
			<?php if ($current_page == 'principal_interest_report') { ?>
				<?php include "include/templates/principal_interest_report.php" ?>
			<?php } else ?>
			<!-- In Closed Report -->
			<?php if ($current_page == 'in_closed_report') { ?>
				<?php include "include/templates/in_closed_report.php" ?>
			<?php } else ?>
			<!-- Closed Report -->
			<?php if ($current_page == 'closed_report') { ?>
				<?php include "include/templates/closed_report.php" ?>
			<?php } else ?>
			<!-- confirmation Report -->
			<?php if ($current_page == 'confirmation_followup_report') { ?>
				<?php include "include/templates/confirmation_followup_report.php" ?>
			<?php } else ?>

			<!-- Balance Report -->
			<?php if ($current_page == 'balance_report') { ?>
				<?php include "include/templates/balance_report.php" ?>
			<?php } else ?>

			<!-- Agent Report -->
			<?php if ($current_page == 'agent_report') { ?>
				<?php include "include/templates/agent_report.php" ?>
			<?php } else ?>
			
			<!-- No Due Pay Report -->
			<?php if ($current_page == 'no_due_pay_report') { ?>
				<?php include "include/templates/no_due_pay_report.php" ?>
			<?php } else ?>
			<!-- Other Transaction Report -->
			<?php if ($current_page == 'other_transaction_report') { ?>
				<?php include "include/templates/other_transaction_report.php" ?>
			<?php } else ?>
			<!-- Day End Report -->
			<?php if ($current_page == 'day_end_report') { ?>
				<?php include "include/templates/day_end_report.php" ?>
			<?php } else ?>

			<!-- Due List -->
			<?php if ($current_page == 'due_list_report') { ?>
				<?php include "include/templates/due_list_report.php" ?>
			<?php } else ?>

			<!-- Search Module -->
			<?php if ($current_page == 'search_module') { ?>
				<?php include "include/templates/search_module.php" ?>
			<?php } else ?>

			<!-- Bulk Upload Module -->
			<?php if ($current_page == 'bulk_upload') { ?>
				<?php include "include/templates/bulk_upload.php" ?>
			<?php } else ?>

			<!-- Loan Track Module -->
			<?php if ($current_page == 'loan_track') { ?>
				<?php include "include/templates/loan_track.php" ?>
			<?php } else ?>

			<!-- Loan Track Module -->
			<?php if ($current_page == 'sms_generation') { ?>
				<?php include "include/templates/sms_generation.php" ?>
			<?php } else ?>

			<!-- 404 Not found page -->
			<?php { ?>
				<?php 	//include "include/templates/notfound.php" 
				?>
			<?php } ?>

		</div>
		<!-- Page content end -->

	</div>
	<!-- Page wrapper end -->

	<!-- Important -->
	<!-- This the important section for download excel file and script adding with our screen -->
	<?php if ($current_page != 'vendorcreation') { ?>
		<?php include "include/common/dashboardfooter.php" ?>
	<?php } ?>

	<?php
	if ($current_page == 'vendorcreation') { ?>
		<?php include "include/common/dashboardfinancedatatablefooter.php" ?>
	<?php } ?>




</body>

</html>


<script>
	$('#search_input_').keyup(function() {
		let search_content = $('#search_input_').val();
		$.post('searchScreens.php', {
			search_content
		}, function(response) {
			if (response.status == 'Fetched') {
				let append = '';
				$.each(response, function(index, val) {
					if (val.display_name != undefined) {
						append += "<li class='dropdown-contents'><a href='" + val.module_name + "'>" + val.display_name + "</a></li>";
					}
				})
				$('#search_ul').empty().append(append);
			}
		}, 'json')
	})
</script>