<?php
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}
$current_page = isset($_GET['page']) ? $_GET['page'] : null;
$verif_check = isset($_GET['pge']) ? $_GET['pge'] : null;

if (
	$current_page == 'edit_company_creation' || $current_page == 'company_creation' || $current_page == 'edit_branch_creation' || $current_page == 'branch_creation' ||
	$current_page == 'edit_loan_category' || $current_page == 'loan_category' || $current_page == 'edit_loan_calculation' || $current_page == 'loan_calculation' ||
	$current_page == 'edit_loan_scheme' || $current_page == 'loan_scheme' || $current_page == 'edit_area_creation' || $current_page == 'area_creation' ||
	$current_page == 'edit_area_mapping' || $current_page == 'area_mapping' || $current_page == 'area_status'
) {

	$current_module = 'master';
} else if (
	$current_page == 'edit_director_creation' || $current_page == 'director_creation' || $current_page == 'edit_agent_creation' || $current_page == 'agent_creation' ||
	$current_page == 'edit_staff_creation' || $current_page == 'staff_creation' || $current_page == 'edit_manage_user' || $current_page == 'manage_user' || $current_page == 'edit_doc_mapping'
	|| $current_page == 'doc_mapping' || $current_page == 'edit_bank_creation' || $current_page == 'bank_creation'
) {

	$current_module = 'admin';
} else if ($current_page == 'edit_request' || $current_page == 'request') {

	$current_module = 'request';
} else if ($current_page == 'verification_list' || $current_page == 'verification') {

	if ($verif_check != '' && $verif_check == '2') { //Due to same page for two screens, first check pge number to verify it is for approval or verification
		$current_module = 'approval';
	} else {
		$current_module = 'verification';
	}
} else if ($current_page == 'approval_list' || $current_page == 'approval') {

	$current_module = 'approval';
} else if ($current_page == 'edit_acknowledgement_list' || $current_page == 'acknowledgement_creation') {

	$current_module = 'acknowledgement';
} else if ($current_page == 'edit_loan_issue' || $current_page == 'loan_issue') {

	$current_module = 'loanissue';
} else if ($current_page == 'edit_collection' || $current_page == 'collection') {

	$current_module = 'collection';
} else if ($current_page == 'edit_closed' || $current_page == 'closed') {

	$current_module = 'closed';
} else if ($current_page == 'edit_noc' || $current_page == 'noc') {

	$current_module = 'noc';
} else if ($current_page == 'edit_update' || $current_page == 'update' || $current_page == 'document_track' || $current_page == 'update_customer_status') {

	$current_module = 'update';
} //else if($current_page == 'document_track'){

//$current_module = 'doctrack';

//}
else if ($current_page == 'edit_concern_creation' || $current_page == 'edit_concern_solution' || $current_page == 'concern_creation' || $current_page == 'concern_solution' || $current_page == 'concern_solution_view' || $current_page == 'edit_concern_feedback' || $current_page == 'concern_feedback') {

	$current_module = 'concerncreation';
} else if ($current_page == 'cash_tally' || $current_page == 'bank_clearance' || $current_page == 'edit_bank_clearance' || $current_page == 'finance_insight') {

	$current_module = 'accounts';
} else if ($current_page == 'promotion_activity' || $current_page == 'loan_followup' || $current_page == 'confirmation_followup' || $current_page == 'due_followup' || $current_page == 'edit_due_followup') {

	$current_module = 'followup';
} else if (
	$current_page == 'ledger_report' || $current_page == 'request_report' || $current_page == 'cus_profile_report' || $current_page == 'loan_issue_report'
	|| $current_page == 'collection_report' || $current_page == 'balance_report' || $current_page == 'due_list_report' || $current_page == 'closed_report' || $current_page == 'agent_report'
) {

	$current_module = 'report';
} else if ($current_page == 'search_module') {

	$current_module = 'search_module';
} else if ($current_page == 'bulk_upload') {

	$current_module = 'bulk_upload';
}else if ($current_page == 'loan_track') {

	$current_module = 'loan_track';
} else if ($current_page == 'sms_generation') {

	$current_module = 'sms_generation';
} else {
	$current_module = '';
}
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script>
	setTimeout(() => {
		var currentPage = "<?php echo $current_page; ?>"; // set the current page value to local variable
		var verif_check = "<?php echo $verif_check; ?>"; // set the verification page pge value to local variable

		var sidebarLinks = document.querySelectorAll('.page-wrapper .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu ul li a');

		sidebarLinks.forEach(function(link) {
			var href = link.getAttribute('href');
			if (href === currentPage || href.includes(currentPage)) {
				link.style.backgroundColor = '#646969d9';
			}
		});
		if (currentPage == 'dashboard') {
			$('.dashboard').css('backgroundColor', '#646969d9');
		}
	}, 1000);
</script>

<?php
$user_id        = '';
$full_name      = '';
$user_name      = '';
$password       = '';
$role           = '';
$role_type           = '';
$dir_name           = '';
$ag_name           = '';
$staff_name           = '';
$company_id           = '';
$branch_id           = '';
$line_id           = '';
$group_id           = '';
$mastermodule    = '';
$company_creation      = '';
$branch_creation = '';
$loan_category = '';
$loan_calculation   = '';
$loan_scheme   = '';
$area_creation        = '';
$area_mapping        = '';
$area_status        = '';
$adminmodule = '';
$director_creation = '';
$agent_creation = '';
$staff_creation = '';
$manage_user = '';
$doc_mapping = '';
$bank_creation = '';
$requestmodule = '';
$request = '';
$verificationmodule = '';
$verification = '';
$approvalmodule = '';
$approval = '';
$acknowledgementmodule = '';
$acknowledgement = '';
$loanissuemodule = '';
$loan_issue = '';
$collectionmodule = '';
$collection = '';
$closedmodule = '';
$closed = '';
$nocmodule = '';
$noc = '';
$doctrackmodule = '';
$doctrack = '';
$doc_rec_access = '';
$updatemodule = '';
$update_screen = '';
$concernmodule = '';
$concern_creation = '';
$concern_solution = '';
$concern_feedback = '';
$accountsmodule = '';
$cash_tally = '';
$bank_clearance = '';
$finance_insight = '';
$followupmodule = '';
$promotion_activity = '';
$loan_followup = '';
$confirmation_followup = '';
$due_followup = '';
$reportmodule = '';
$ledger_report = '';
$request_report = '';
$cus_profile_report = '';
$loan_issue_report = '';
$collection_report = '';
$balance_report = '';
$due_list_report = '';
$closed_report = '';
$agent_report = '';
$search_module = '';
$search = '';
$bulk_upload_module = '';
$bulk_upload = '';
$loan_track_module = '';
$loan_track = '';
$sms_module = '';
$sms_generation = '';

$getUser = $userObj->getUser($mysqli, $userid);
if (sizeof($getUser) > 0) {
	for ($i = 0; $i < sizeof($getUser); $i++) {
		$user_id                 	 = $getUser['user_id'];
		$fullname          		     = $getUser['fullname'];
		$user_name          		     = $getUser['user_name'];
		$user_password          		     = $getUser['user_password'];
		$role          		     = $getUser['role'];
		$role_type          		     = $getUser['role_type'];
		$dir_id          		     = $getUser['dir_id'];
		$ag_id          		     = $getUser['ag_id'];
		$staff_id          		     = $getUser['staff_id'];
		$company_id          		     = $getUser['company_id'];
		$branch_id          		     = $getUser['branch_id'];
		$line_id          		     = $getUser['line_id'];
		$group_id          		     = $getUser['group_id'];
		$mastermodule          		     = $getUser['mastermodule'];
		$company_creation          		     = $getUser['company_creation'];
		$branch_creation          		     = $getUser['branch_creation'];
		$loan_category          		     = $getUser['loan_category'];
		$loan_calculation          		     = $getUser['loan_calculation'];
		$loan_scheme          		     = $getUser['loan_scheme'];
		$area_creation          		     = $getUser['area_creation'];
		$area_mapping          		     = $getUser['area_mapping'];
		$area_status          		     = $getUser['area_approval'];
		$adminmodule          		     = $getUser['adminmodule'];
		$director_creation          		     = $getUser['director_creation'];
		$agent_creation          		     = $getUser['agent_creation'];
		$staff_creation          		     = $getUser['staff_creation'];
		$manage_user          		     = $getUser['manage_user'];
		$doc_mapping          		     = $getUser['doc_mapping'];
		$bank_creation          		     = $getUser['bank_creation'];
		$requestmodule          		     = $getUser['requestmodule'];
		$request          		     = $getUser['request'];
		$verificationmodule          		     = $getUser['verificationmodule'];
		$verification          		     = $getUser['verification'];
		$approvalmodule          		     = $getUser['approvalmodule'];
		$approval          		     = $getUser['approval'];
		$acknowledgementmodule          		     = $getUser['acknowledgementmodule'];
		$acknowledgement          		     = $getUser['acknowledgement'];
		$loanissuemodule          		     = $getUser['loanissuemodule'];
		$loan_issue          		     = $getUser['loan_issue'];
		$collectionmodule          		     = $getUser['collectionmodule'];
		$collection          		     = $getUser['collection'];
		$closedmodule          		     = $getUser['closedmodule'];
		$closed          		     = $getUser['closed'];
		$nocmodule          		     = $getUser['nocmodule'];
		$noc          		     = $getUser['noc'];
		$doctrackmodule          		     = $getUser['doctrackmodule'];
		$doctrack          		     = $getUser['doctrack'];
		$doc_rec_access          		     = $getUser['doc_rec_access'];
		$updatemodule          		     = $getUser['updatemodule'];
		$update_screen          		     = $getUser['update_screen'];
		$concernmodule          		     = $getUser['concernmodule'];
		$concern_creation          		     = $getUser['concern_creation'];
		$concern_solution          		     = $getUser['concern_solution'];
		$concern_feedback          		     = $getUser['concern_feedback'];
		$accountsmodule          		     = $getUser['accountsmodule'];
		$cash_tally          		     = $getUser['cash_tally'];
		$bank_clearance          		     = $getUser['bank_clearance'];
		$finance_insight          		     = $getUser['finance_insight'];
		$followupmodule          		     = $getUser['followupmodule'];
		$promotion_activity          		     = $getUser['promotion_activity'];
		$loan_followup          		     = $getUser['loan_followup'];
		$confirmation_followup          		     = $getUser['confirmation_followup'];
		$due_followup          		     = $getUser['due_followup'];

		$reportmodule          		     = $getUser['reportmodule'];
		$ledger_report          		     = $getUser['ledger_report'];
		$request_report          		     = $getUser['request_report'];
		$cus_profile_report          		     = $getUser['cus_profile_report'];
		$loan_issue_report          		     = $getUser['loan_issue_report'];
		$collection_report          		     = $getUser['collection_report'];
		$balance_report          		     = $getUser['balance_report'];
		$due_list_report          		     = $getUser['due_list_report'];
		$closed_report          		     = $getUser['closed_report'];
		$agent_report          		     = $getUser['agent_report'];

		$search_module          		     = $getUser['search_module'];
		$search          		     = $getUser['search'];
		$bulk_upload_module          		     = $getUser['bulk_upload_module'];
		$bulk_upload          		     = $getUser['bulk_upload'];
		$loan_track_module          		     = $getUser['loan_track_module'];
		$loan_track          		     = $getUser['loan_track'];
		$sms_module          		     = $getUser['sms_module'];
		$sms_generation          		     = $getUser['sms_generation'];
	}
}
?>

<style>
	body {
		font-family: "Lato", sans-serif;
	}

	.svg-icon {
		width: 24px;
		/* Set the desired width */
		height: 24px;
		/* Set the desired height */
		fill: white;
	}

	/* Fixed sidenav, full height */
	.sidenav {
		height: 100%;
		width: 200px;
		position: fixed;
		z-index: 1;
		top: 0;
		left: 0;
		background-color: #111;
		overflow-x: hidden;
		padding-top: 20px;
	}

	/* Style the sidenav links and the dropdown button */
	.sidenav a,
	.dropdown-btn1 {
		padding: 6px 8px 6px 16px;
		text-decoration: none;

		color: #818181;
		display: block;
		border: none;
		background: none;
		width: 100%;
		text-align: left;
		cursor: pointer;
		outline: none;
	}

	/* On mouse-over */
	.sidenav a:hover,
	.dropdown-btn1:hover {
		color: #2f958bd9;
	}

	.sidenav a,
	.dropdown-btn {
		padding: 6px 8px 6px 16px;
		text-decoration: none;

		color: #818181;
		display: block;
		border: none;
		background: none;
		width: 100%;
		text-align: left;
		cursor: pointer;
		outline: none;
	}

	/* On mouse-over */
	.sidenav a:hover,
	.dropdown-btn:hover {
		color: #2f958bd9;
	}

	/* On mouse-over */
	.sidenav a:hover,
	.dropdown-btn1:hover {
		color: #2f958bd9;
	}

	/* Main content */
	.main {
		margin-left: 200px;
		/* Same as the width of the sidenav */

		padding: 0px 10px;
	}

	/* Add an active class to the active dropdown button */
	.active {

		color: white;
	}

	/* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
	.dropdown-container {
		display: none;

		padding-left: 8px;
	}

	.dropdown-container1 {
		display: none;

		padding-left: 8px;
	}

	/* Optional: Style the caret down icon */
	.fa-caret-down {
		float: right;
		padding-right: 8px;
	}

	/* Some media queries for responsiveness */
	@media screen and (max-height: 450px) {
		.sidenav {
			padding-top: 15px;
		}

		.sidenav a {
			font-size: 18px;
		}
	}
</style>

<!-- Sidebar wrapper start -->
<nav id="sidebar" class="sidebar-wrapper" style="background-color:#009688;">

	<!-- Sidebar brand start  -->
	<div class="sidebar-brand" style="background-color: #009688">
		<a href="dashboard" class="logo">
			<h2 class="ml-1" style="color: white; font-family: 'Maiandra GD', sans-serif;">MARUDHAM CAPITALS</h2>
			<!-- <img src="img/logo.png" alt="Auction Dashboard" /> -->
		</a>
	</div>

	<div class="sidebar-content">

		<!-- sidebar menu start -->
		<div class="sidebar-menu">
			<ul>
				<li class="dashboard">
					<!-- <a href="javascript:void(0)"> -->
					<!-- <span class="menu-text">Dashboard</span> -->
					<a href="dashboard"><i class='icon-developer_board'></i>&nbsp;Dashboard</a>
					<!-- </a> -->
					<!-- <div class="sidebar-submenu">
						<ul>
							<li>
								<a href="dashboard"><i class='icon-credit-card'></i>Dashboard</a>
							</li>
						</ul>
					</div> -->
				</li>
				<?php if ($mastermodule == 0) { ?>
					<li class="sidebar-dropdown master">
						<a href="javascript:void(0)">
							<i class="icon-globe"></i>
							<span class="menu-text">Master</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'master') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($company_creation == 0) { ?>
									<li>
										<a href="edit_company_creation"><i class="icon-assignment"></i>Company Creation</a>
									</li>
								<?php  }
								if ($branch_creation == 0) { ?>
									<li>
										<a href="edit_branch_creation"><i class="icon-format_list_bulleted"></i>Branch Creation</a>
									</li>
								<?php  }
								if ($loan_category == 0) { ?>
									<li>
										<a href="edit_loan_category"><i class="icon-package"></i>Loan Category</a>
									</li>
								<?php  }
								if ($loan_calculation == 0) { ?>
									<li>
										<a href="edit_loan_calculation"><i class="icon-percent"></i>Loan Calculation</a>
									</li>
								<?php  }
								if ($loan_scheme == 0) { ?>
									<li>
										<a href="edit_loan_scheme"><i class="icon-credit-card"></i>Loan Scheme</a>
									</li>
								<?php  }
								if ($area_creation == 0) { ?>
									<li>
										<a href="edit_area_creation"><i class="icon-octagon"></i>Area Creation</a>
									</li>
								<?php  }
								if ($area_mapping == 0) { ?>
									<li>
										<a href="edit_area_mapping"><i class="icon-documents"></i>Area Mapping</a>
									</li>
								<?php  }
								if ($area_status == 0) { ?>
									<li>
										<a href="area_status"><i class="icon-check"></i>Area Approval</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($adminmodule == 0) { ?>
					<li class="sidebar-dropdown administration">
						<a href="javascript:void(0)">
							<i class='icon-layers'></i>
							<span class="menu-text">Administration</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'admin') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($director_creation == 0) { ?>
									<li>
										<a href="edit_director_creation"><i class="icon-event_note"></i>Director Creation</a>
									</li>
								<?php  }
								if ($agent_creation == 0) { ?>
									<li>
										<a href="edit_agent_creation"><i class="icon-users"></i>Agent Creation</a>
									</li>
								<?php  }
								if ($staff_creation == 0) { ?>
									<li>
										<a href="edit_staff_creation"><i class="icon-user-plus"></i>Staff Creation</a>
									</li>
								<?php  }
								if ($bank_creation == 0) { ?>
									<li>
										<a href="edit_bank_creation"><i class='icon-save'></i>Bank Creation</a>
									</li>
								<?php  }
								if ($manage_user == 0) { ?>
									<li>
										<a href="edit_manage_user"><i class="icon-cog"></i>Manage User</a>
									</li>
								<?php  }
								if ($doc_mapping == 0) { ?>
									<!-- <li>
										<a href="edit_doc_mapping"><i class="icon-briefcase"></i>Documentation Mapping</a>
									</li> -->
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($requestmodule == 0) { ?>
					<li class="sidebar-dropdown request">
						<a href="javascript:void(0)">
							<i class='icon-upload-to-cloud'></i>
							<span class="menu-text">Request</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'request') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($request == 0) { ?>
									<li>
										<a href="edit_request"><i class='icon-upload-to-cloud'></i>Request</a>
									</li>
								<?php  } ?>



							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($verificationmodule == 0) { ?>
					<li class="sidebar-dropdown request">
						<a href="javascript:void(0)">
							<i class='icon-archive'></i>
							<span class="menu-text">Verification</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'verification') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($verification == 0) { ?>
									<li>
										<a href="verification_list"><i class='icon-archive'></i>Verification</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($approvalmodule == 0) { ?>
					<li class="sidebar-dropdown approve">
						<a href="javascript:void(0)">
							<i class='icon-check'></i>
							<span class="menu-text">Approval</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'approval') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($approval == 0) { ?>
									<li>
										<a href="approval_list"><i class='icon-check'></i>Approval</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($acknowledgementmodule == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-cw'></i>
							<span class="menu-text">Acknowledgement</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'acknowledgement') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($acknowledgement == 0) { ?>
									<li>
										<a href="edit_acknowledgement_list"><i class='icon-cw'></i>Acknowledgement</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($loanissuemodule == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-wallet'></i>
							<span class="menu-text">Loan Issue</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'loanissue') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($loan_issue == 0) { ?>
									<li>
										<a href="edit_loan_issue"><i class='icon-wallet'></i>Loan Issue</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($collectionmodule == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-credit'></i>
							<span class="menu-text">Collection</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'collection') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($collection == 0) { ?>
									<li>
										<a href="edit_collection"><i class='icon-credit'></i>Collection</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($closedmodule == 0) { ?>
					<li class="sidebar-dropdown closed">
						<a href="javascript:void(0)">
							<i class='icon-uninstall'></i>
							<span class="menu-text">Closed</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'closed') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($closed == 0) { ?>
									<li>
										<a href="edit_closed"><i class='icon-uninstall'></i>Closed</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($nocmodule == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-export'></i>
							<span class="menu-text">NOC</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'noc') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($noc == 0) { ?>
									<li>
										<a href="edit_noc"><i class='icon-export'></i>NOC</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($doctrackmodule == 0) { ?>
					<!-- <li class="sidebar-dropdown ">
                        <a href="javascript:void(0)">
							<i class='icon-credit-card'></i>
                            <span class="menu-text">Document Track</span>
                        </a>
                        <div class="sidebar-submenu" <?php if ($current_module == 'doctrack') echo 'style="display:block" '; ?>>
                            <ul>
                                <?php if ($doctrack == 0) { ?>
                                    <li>
                                        <a href="document_track"><i class='icon-credit-card'></i>Document Track</a>
                                    </li>
                                <?php  } ?>
                            </ul>
                        </div>
                    </li> -->
				<?php  } ?>
				<?php if ($updatemodule == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-arrow_upward'></i>
							<span class="menu-text">Update</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'update') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($update_screen == 0) { ?>
									<li>
										<a href="edit_update"><i class='icon-arrow_upward'></i>Update</a>
									</li>
								<?php  } ?>
								<?php if ($doctrack == 0) { ?>
									<li>
										<a href="document_track"><i class='icon-broken_image'></i>Document Track</a>
									</li>
								<?php  } ?>

								<?php if ($doctrack == 0) { ?>
									<li>
										<a href="update_customer_status"><i class='icon-broken_image'></i>Update Customer Status</a>
									</li>
								<?php  } ?>

							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($concernmodule == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-help-with-circle'></i>
							<span class="menu-text">Concern</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'concerncreation') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($concern_creation == 0) { ?>
									<li>
										<a href="edit_concern_creation"><i class='icon-bug_report'></i>Concern Creation</a>
									</li>
								<?php  } ?>
								<?php if ($concern_solution == 0) { ?>
									<li>
										<a href="edit_concern_solution"><i class='icon-center_focus_strong'></i>Concern Solution</a>
									</li>
								<?php  } ?>
								<?php if ($concern_feedback == 0) { ?>
									<li>
										<a href="edit_concern_feedback"><i class='icon-redeem'></i>Concern Feedback</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($accountsmodule == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-domain'></i>
							<span class="menu-text">Accounts</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'accounts') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($cash_tally == 0) { ?>
									<li>
										<a href="cash_tally"><i class='icon-shareable'></i>Cash Tally</a>
									</li>
								<?php  } ?>
								<?php if ($bank_clearance == 0) { ?>
									<li>
										<a href="edit_bank_clearance"><i class='icon-business_center'></i>Bank Clearance</a>
									</li>
								<?php  } ?>
								<?php if ($finance_insight == 0) { ?>
									<li>
										<a href="finance_insight"><i class='icon-card_travel'></i>Financial Insights</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($followupmodule == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-cycle'></i>
							<span class="menu-text">Follow Up</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'followup') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($promotion_activity == 0) { ?>
									<li>
										<a href="promotion_activity"><i class='icon-change_history'></i>Promotion Activity</a>
									</li>
								<?php  } ?>
								<?php if ($loan_followup == 0) { ?>
									<li>
										<a href="loan_followup"><i class='icon-chat_bubble_outline'></i>Loan Follow Up</a>
									</li>
								<?php  } ?>
								<?php if ($confirmation_followup == 0) { ?>
									<li>
										<a href="confirmation_followup"><i class='icon-laptop'></i>Confirmation Follow Up</a>
									</li>
								<?php  } ?>
								<?php if ($due_followup == 0) { ?>
									<li>
										<a href="edit_due_followup"><i class='icon-confirmation_number'></i>Due Follow Up</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($reportmodule == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-area-graph'></i>
							<span class="menu-text">Reports</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'report') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($ledger_report == 0) { ?>
									<li>
										<a href="ledger_report"><i class='icon-area-graph'></i>Ledger View</a>
									</li>
								<?php  } ?>
								<?php if ($request_report == 0) { ?>
									<li>
										<a href="request_report"><i class='icon-area-graph'></i>Request</a>
									</li>
								<?php  } ?>
								<?php if ($request_report == 0) { ?>
									<li>
										<a href="cancel_revoke_report"><i class='icon-area-graph'></i>Cancel / Revoke</a>
									</li>
								<?php  } ?>
								<?php if ($cus_profile_report == 0) { ?>
									<li>
										<a href="cus_profile_report"><i class='icon-area-graph'></i>Customer Profile</a>
									</li>
								<?php  } ?>
								<?php if ($loan_issue_report == 0) { ?>
									<li>
										<a href="loan_issue_report"><i class='icon-area-graph'></i>Loan Issue</a>
									</li>
								<?php  } ?>
								<?php if ($collection_report == 0) { ?>
									<li>
										<a href="collection_report"><i class='icon-area-graph'></i>Collection</a>
									</li>
								<?php  } ?>
								<?php if ($balance_report == 0) { ?>
									<li>
										<a href="balance_report"><i class='icon-area-graph'></i>Balance</a>
									</li>
								<?php  } ?>
								<?php if ($closed_report == 0) { ?>
									<li>
										<a href="closed_report"><i class='icon-area-graph'></i>Closed</a>
									</li>
								<?php  } ?>
								<?php if ($agent_report == 0) { ?>
									<li>
										<a href="agent_report"><i class='icon-area-graph'></i>Agent</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($search_module == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-search'></i>
							<span class="menu-text">Search</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'search_module') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($search == 0) { ?>
									<li>
										<a href="search_module"><i class='icon-search'></i>Search</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($bulk_upload_module == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-upload-cloud'></i>
							<span class="menu-text">Bulk Upload</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'bulk_upload') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($bulk_upload == 0) { ?>
									<li>
										<a href="bulk_upload"><i class='icon-upload-cloud'></i>Bulk Upload</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if ($loan_track_module == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-target'></i>
							<span class="menu-text">Loan Track</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'loan_track') echo 'style="display:block" '; ?>>
							<ul>
								<?php if ($loan_track == 0) { ?>
									<li>
										<a href="loan_track"><i class='icon-target'></i>Loan Track</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } if ($sms_module == 0) { ?>
				<li class="sidebar-dropdown">
					<a href="javascript:void(0)">
						<i class='icon-mail'></i>
						<span class="menu-text">SMS</span>
					</a>
					<div class="sidebar-submenu" <?php if ($current_module == 'sms_generation') echo 'style="display:block" '; ?>>
						<ul>
						<?php if ($sms_generation == 0) { ?>
							<li>
								<a href="sms_generation"><i class="icon-message"></i>SMS Generation</a>
							</li>
						<?php  } ?>
						</ul>
					</div>
				</li>
				<?php } ?>

			</ul>
		</div>
		<!-- sidebar menu end -->
	</div>
</nav>
<!-- Sidebar wrapper end -->

<?php //$current_page = $_GET[''];
?>
<!-- <input type="hidden" id='current_page' name='current_page' value="<?php //echo $current_page; 
																		?>" -->

<?php
$user_id        = '';
$full_name      = '';
$user_name      = '';
$password       = '';
$role           = '';
$role_type           = '';
$dir_name           = '';
$ag_name           = '';
$staff_id           = '';
$staff_name           = '';
$company_id           = '';
$branch_id           = '';
$line_id           = '';
$group_id           = '';
$mastermodule    = '';
$company_creation      = '';
$branch_creation = '';
$loan_category = '';
$loan_calculation   = '';
$loan_scheme   = '';
$area_creation        = '';
$area_mapping        = '';
$area_status        = '';
$adminmodule = '';
$director_creation = '';
$agent_creation = '';
$staff_creation = '';
$manage_user = '';
$doc_mapping = '';
$bank_creation = '';
$requestmodule = '';
$request = '';
$verificationmodule = '';
$verification = '';
$approvalmodule = '';
$approval = '';
$acknowledgementmodule = '';
$acknowledgement = '';
$loanissuemodule = '';
$loan_issue = '';
$collectionmodule = '';
$collection = '';
$closedmodule = '';
$closed = '';
$nocmodule = '';
$noc = '';
$doctrackmodule = '';
$doctrack = '';
$doc_rec_access = '';
$updatemodule = '';
$update_screen = '';
$concernmodule = '';
$concern_creation = '';
$concern_solution = '';
$concern_feedback = '';
$accountsmodule = '';
$cash_tally = '';
$bank_clearance = '';
$finance_insight = '';
$followupmodule = '';
$promotion_activity = '';
$loan_followup = '';
$confirmation_followup = '';
$due_followup = '';
$reportmodule = '';
$ledger_report = '';
$request_report = '';
$cus_profile_report = '';
$loan_issue_report = '';
$collection_report = '';
$balance_report = '';
$due_list_report = '';
$closed_report = '';
$agent_report = '';
$search_module = '';
$search = '';
$bulk_upload_module = '';
$bulk_upload = '';
$loan_track_module = '';
$loan_track = '';
$sms_module = '';
$sms_generation = '';
?>