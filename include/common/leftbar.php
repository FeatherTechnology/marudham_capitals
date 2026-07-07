<?php
$userid = $_SESSION["userid"] ?? null;

$current_page = $_GET['page'] ?? null;
$verif_check  = $_GET['pge'] ?? null;

// Module mapping
$modules = [

    'master' => [
        'edit_company_creation','company_creation','edit_branch_creation','branch_creation',
        'edit_loan_category','loan_category','edit_loan_calculation','loan_calculation',
        'edit_loan_scheme','loan_scheme','edit_area_creation','area_creation',
        'edit_area_mapping','area_mapping','area_status'
    ],

    'admin' => [
        'edit_director_creation','director_creation','edit_agent_creation','agent_creation',
        'edit_staff_creation','staff_creation','edit_manage_user','manage_user',
        'edit_doc_mapping','doc_mapping','edit_bank_creation','bank_creation'
    ],

    'request' => ['edit_request','request'],

    'approval' => ['approval_list','approval'],

    'acknowledgement' => ['edit_acknowledgement_list','acknowledgement_creation'],

    'loanissue' => ['edit_loan_issue','loan_issue'],

    'collection' => ['edit_collection','collection'],

    'closed' => ['edit_closed','closed'],

    'noc' => ['edit_noc','noc','edit_noc_handover','noc_handover'],

    'update' => ['edit_update','update','update_customer_status'],

    'doctrack' => ['document_track'],

    'concerncreation' => [
        'edit_concern_creation','edit_concern_solution','concern_creation',
        'concern_solution','concern_solution_view','edit_concern_feedback','concern_feedback'
    ],

    'accounts' => [
        'cash_tally','bank_clearance','edit_bank_clearance','finance_insight',
        'hand_cash_balance_sheet','edit_accounts_loan_issue','accounts_loan_issue'
    ],

    'followup' => [
        'promotion_activity','loan_followup','confirmation_followup','due_followup',
        'edit_due_followup','ecs_followup','ecs_edit_followup'
    ],

    'report' => [
        'ledger_report','request_report','verification_report','approval_report','cancel_revoke_report','cus_profile_report','loan_issue_report',
        'collection_report','principal_interest_report','balance_report','due_list_report',
        'noc_handover_report','in_closed_report','closed_report','confirmation_followup_report',
        'agent_report','no_due_pay_report','other_transaction_report','due_followup_customer_count_report',
        'day_end_report','cash_tally_activity_report','commitment_report','customer_status_report',
        'promotion_activity_report','cleared_report','events_report','area_loan_count_report',
        'confirmation_count_report','concern_report','partners_report','request_count_report',
        'verification_count_report','approval_count_report','loan_issue_count_report',
        'promotion_count_report','duefollowup_count_report','back_office_count_report','branch_request_count_report','location_track_report','outstanding_report'
    ],

    'interest_report' => [
        'intrest_ledger_report','intrest_loan_issue_report','intrest_balance_report',
        'intrest_collection_report','intrest_closed_report'
    ],

    'search_module' => ['search_module'],
    'bulk_upload'   => ['bulk_upload'],
    'loan_track'    => ['loan_track'],
    'sms_generation'=> ['sms_generation'],
];

$current_module = '';

// Special case (verification / approval split)
//Due to same page for two screens, first check pge number to verify it is for approval or verification
if ($current_page == 'verification_list' || $current_page == 'verification') {
    $current_module = ($verif_check == '2') ? 'approval' : 'verification';
} else {
    foreach ($modules as $module => $pages) {
        if (in_array($current_page, $pages)) {
            $current_module = $module;
            break;
        }
    }
}

$getUser = $userObj->getUser($mysqli, $userid);

if (!empty($getUser) && is_array($getUser)) {
    extract($getUser, EXTR_PREFIX_ALL, 'leftbar'); //it is linked to common page set prefix in variable so it avoid the conflict with other page.
}
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script>
	setTimeout(() => {
		var currentPage = "<?php echo $current_page; ?>"; // current page
		var verif_check = "<?php echo $verif_check; ?>"; // verification page

		var sidebarLinks = document.querySelectorAll('.page-wrapper .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu ul li a');

		sidebarLinks.forEach(function(link) {
			var href = link.getAttribute('href');
			if (href === currentPage) { 
				link.style.backgroundColor = '#646969d9';

				// Open 2nd-level menu if inside sidebar-dropdown-2
				var secondLevel = link.closest('.sidebar-dropdown-2');
				if (secondLevel) {
					secondLevel.classList.add('active');
					var submenu2 = secondLevel.querySelector('.sidebar-submenu-2');
					if (submenu2) submenu2.style.display = 'block';
				}

				// Open 1st-level menu
				var firstLevel = link.closest('.sidebar-submenu')?.closest('.sidebar-dropdown');
				if (firstLevel) {
					firstLevel.classList.add('active');
					var submenu1 = firstLevel.querySelector('.sidebar-submenu');
					if (submenu1) submenu1.style.display = 'block';
				}
			}
		});

		// Highlight dashboard or home_page specifically
		if (currentPage == 'dashboard') {
			$('.dashboard').css('backgroundColor', '#646969d9');
		}
		if (currentPage == 'home_page') {
			$('.home_page').css('backgroundColor', '#646969d9');
		}
	}, 1000);
</script>

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
		<a href="home_page" class="logo">
			<h2 class="ml-1" style="color: white; font-family: 'Maiandra GD', sans-serif;">MARUDHAM CAPITALS</h2>
		</a>
	</div>

	<div class="sidebar-content">

		<!-- sidebar menu start -->
		<div class="sidebar-menu">
			<ul>
				<li class="home_page">
					<a href="home_page"><i class='icon-home'></i>&nbsp;Home</a>
				</li>
				<!-- <li class="dashboard">
					<a href="dashboard"><i class='icon-developer_board'></i>&nbsp;Dashboard</a>
				</li> -->
				<?php if (($leftbar_mastermodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown master">
						<a href="javascript:void(0)">
							<i class="icon-globe"></i>
							<span class="menu-text">Master</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'master') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_company_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_company_creation"><i class="icon-assignment"></i>Company Creation</a>
									</li>
								<?php  }
								if (($leftbar_branch_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_branch_creation"><i class="icon-format_list_bulleted"></i>Branch Creation</a>
									</li>
								<?php  }
								if (($leftbar_loan_category ?? 1) == 0) { ?>
									<li>
										<a href="edit_loan_category"><i class="icon-package"></i>Loan Category</a>
									</li>
								<?php  }
								if (($leftbar_loan_calculation ?? 1) == 0) { ?>
									<li>
										<a href="edit_loan_calculation"><i class="icon-percent"></i>Loan Calculation</a>
									</li>
								<?php  }
								if (($leftbar_loan_scheme ?? 1) == 0) { ?>
									<li>
										<a href="edit_loan_scheme"><i class="icon-credit-card"></i>Loan Scheme</a>
									</li>
								<?php  }
								if (($leftbar_area_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_area_creation"><i class="icon-octagon"></i>Area Creation</a>
									</li>
								<?php  }
								if (($leftbar_area_mapping ?? 1) == 0) { ?>
									<li>
										<a href="edit_area_mapping"><i class="icon-documents"></i>Area Mapping</a>
									</li>
								<?php  }
								if (($leftbar_area_approval ?? 1) == 0) { ?>
									<li>
										<a href="area_status"><i class="icon-check"></i>Area Approval</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_adminmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown administration">
						<a href="javascript:void(0)">
							<i class='icon-layers'></i>
							<span class="menu-text">Administration</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'admin') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_director_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_director_creation"><i class="icon-event_note"></i>Director Creation</a>
									</li>
								<?php  }
								if (($leftbar_agent_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_agent_creation"><i class="icon-users"></i>Agent Creation</a>
									</li>
								<?php  }
								if (($leftbar_staff_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_staff_creation"><i class="icon-user-plus"></i>Staff Creation</a>
									</li>
								<?php  }
								if (($leftbar_bank_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_bank_creation"><i class='icon-save'></i>Bank Creation</a>
									</li>
								<?php  }
								if (($leftbar_manage_user ?? 1) == 0) { ?>
									<li>
										<a href="edit_manage_user"><i class="icon-cog"></i>Manage User</a>
									</li>
								<?php  }
								if (($leftbar_doc_mapping ?? 1) == 0) { ?>
									<!-- <li>
										<a href="edit_doc_mapping"><i class="icon-briefcase"></i>Documentation Mapping</a>
									</li> -->
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_requestmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown request">
						<a href="javascript:void(0)">
							<i class='icon-upload-to-cloud'></i>
							<span class="menu-text">Request</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'request') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_request ?? 1) == 0) { ?>
									<li>
										<a href="edit_request"><i class='icon-upload-to-cloud'></i>Request</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_verificationmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown request">
						<a href="javascript:void(0)">
							<i class='icon-archive'></i>
							<span class="menu-text">Verification</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'verification') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_verification ?? 1) == 0) { ?>
									<li>
										<a href="verification_list"><i class='icon-archive'></i>Verification</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_approvalmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown approve">
						<a href="javascript:void(0)">
							<i class='icon-check'></i>
							<span class="menu-text">Approval</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'approval') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_approval ?? 1) == 0) { ?>
									<li>
										<a href="approval_list"><i class='icon-check'></i>Approval</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_acknowledgementmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-cw'></i>
							<span class="menu-text">Acknowledgement</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'acknowledgement') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_acknowledgement ?? 1) == 0) { ?>
									<li>
										<a href="edit_acknowledgement_list"><i class='icon-cw'></i>Acknowledgement</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_loanissuemodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-wallet'></i>
							<span class="menu-text">Loan Issue</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'loanissue') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_loan_issue ?? 1) == 0) { ?>
									<li>
										<a href="edit_loan_issue"><i class='icon-wallet'></i>Loan Issue</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>

				<?php  } 
				 if (($leftbar_doctrackmodule ?? 1) == 0) { ?>
				 
					<li class="sidebar-dropdown ">
                        <a href="javascript:void(0)">
							<i class='icon-credit-card'></i>
                            <span class="menu-text">Document Track</span>
                        </a>
                        <div class="sidebar-submenu" <?php if ($current_module == 'doctrack') echo 'style="display:block" '; ?>>
                            <ul>
                                <?php if (($leftbar_doctrack ?? 1) == 0) { ?>
                                    <li>
                                        <a href="document_track"><i class='icon-credit-card'></i>Document Track</a>
                                    </li>
								 <?php  } 
								 if (($leftbar_noc_replace ?? 1) == 0) { ?>
                                    <li>
                                        <a href="noc_replace"><i class='icon-unlock'></i>DOC Replace</a>
                                    </li>
                                <?php  } ?>
                            </ul>
                        </div>
                    </li>

				<?php  } 
				 if (($leftbar_collectionmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-credit'></i>
							<span class="menu-text">Collection</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'collection') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_collection ?? 1) == 0) { ?>
									<li>
										<a href="edit_collection"><i class='icon-credit'></i>Collection</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_closedmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown closed">
						<a href="javascript:void(0)">
							<i class='icon-uninstall'></i>
							<span class="menu-text">Closed</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'closed') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_closed ?? 1) == 0) { ?>
									<li>
										<a href="edit_closed"><i class='icon-uninstall'></i>Closed</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_nocmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown acknowledge">
						<a href="javascript:void(0)">
							<i class='icon-export'></i>
							<span class="menu-text">NOC</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'noc') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_noc ?? 1) == 0) { ?>
									<li>
										<a href="edit_noc"><i class='icon-export'></i>NOC</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_noc_handover ?? 1) == 0) { ?>
									<li>
										<a href="edit_noc_handover"><i class='icon-assistant'></i>NOC Handover</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_followupmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-cycle'></i>
							<span class="menu-text">Follow Up</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'followup') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_promotion_activity ?? 1) == 0) { ?>
									<li>
										<a href="promotion_activity"><i class='icon-change_history'></i>Promotion Activity</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_loan_followup ?? 1) == 0) { ?>
									<!-- <li>
										<a href="loan_followup"><i class='icon-chat_bubble_outline'></i>Loan Follow Up</a>
									</li> -->
								<?php  } ?>
								<?php if (($leftbar_confirmation_followup ?? 1) == 0) { ?>
									<li>
										<a href="confirmation_followup"><i class='icon-laptop'></i>Confirmation Follow Up</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_due_followup ?? 1) == 0) { ?>
									<li>
										<a href="edit_due_followup"><i class='icon-confirmation_number'></i>Due Follow Up</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_ecs_followup ?? 1) == 0) { ?>
									<li>
										<a href="ecs_edit_followup"><i class='icon-confirmation_number'></i>ECS Follow Up</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				
				<?php if (($leftbar_updatemodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-arrow_upward'></i>
							<span class="menu-text">Update</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'update') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_updatemodule ?? 1) == 0) { ?>
									<li>
										<a href="edit_update"><i class='icon-arrow_upward'></i>Update</a>
									</li>
								<?php  } ?>

									<!-- <li>
										<a href="update_customer_status"><i class='icon-broken_image'></i>Update Customer Status</a>
									</li> -->

							</ul>
						</div>
					</li>
				<?php  } ?>

				<?php if (($leftbar_concernmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-help-with-circle'></i>
							<span class="menu-text">Concern</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'concerncreation') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_concern_creation ?? 1) == 0) { ?>
									<li>
										<a href="edit_concern_creation"><i class='icon-bug_report'></i>Concern Creation</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_concern_solution ?? 1) == 0) { ?>
									<li>
										<a href="edit_concern_solution"><i class='icon-center_focus_strong'></i>Concern Solution</a>
									</li>
								<?php  } ?>
								<!-- <?php if (($leftbar_concern_feedback ?? 1) == 0) { ?>
									<li>
										<a href="edit_concern_feedback"><i class='icon-redeem'></i>Concern Feedback</a>
									</li>
								<?php  } ?> -->
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_accountsmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-domain'></i>
							<span class="menu-text">Accounts</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'accounts') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_cash_tally ?? 1) == 0) { ?>
									<li>
										<a href="cash_tally"><i class='icon-shareable'></i>Cash Tally</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_bank_clearance ?? 1) == 0) { ?>
									<li>
										<a href="edit_bank_clearance"><i class='icon-business_center'></i>Bank Clearance</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_finance_insight ?? 1) == 0) { ?>
									<li>
										<a href="finance_insight"><i class='icon-card_travel'></i>Financial Insights</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_hand_cash_balance_sheet ?? 1) == 0) { ?>
									<li>
										<a href="hand_cash_balance_sheet"><i class='icon-dollar-sign'></i>Hand Cash Balance Sheet</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_accounts_loan_issue ?? 1) == 0) { ?> 
									<li>
										<a href="edit_accounts_loan_issue"><i class='icon-wallet'></i>Loan Issue</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<!-- <?php #if ($loan_track_module == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-target'></i>
							<span class="menu-text">Loan Track</span>
						</a>
						<div class="sidebar-submenu" <?php # if ($current_module == 'loan_track') echo 'style="display:block" '; ?>>
							<ul>
								<?php #if ($loan_track == 0) { ?>
									<li>
										<a href="loan_track"><i class='icon-target'></i>Loan Track</a>
									</li>
								<?php # } ?>
							</ul>
						</div>
					</li>
				<?php # } ?> -->
				<?php if (($leftbar_reportmodule ?? 1) == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-area-graph'></i>
							<span class="menu-text">Reports - EMI</span>
						</a>
						<div class="sidebar-submenu">
							<ul>
								<?php if (($leftbar_work_report_module ?? 1) == 0) { ?>
								<li class="sidebar-dropdown-2">
									<a href="javascript:void(0)"><i class="icon-briefcase"></i><span class="menu-text">Work Reports</span></a>

									<div class="sidebar-submenu-2" style="display:none; margin-left: 25px;">
										<ul>
											<?php if (($leftbar_request_report ?? 1) == 0) { ?>
												<li>
													<a href="request_report"><i class='icon-area-graph'></i>Request</a>
												</li>
											<?php  } ?>

											<?php if (($leftbar_verification_report ?? 1) == 0) { ?>
												<li>
													<a href="verification_report"><i class='icon-area-graph'></i>Verification</a>
												</li>
											<?php  } ?>

											<?php if (($leftbar_approval_report ?? 1) == 0) { ?>
												<li>
													<a href="approval_report"><i class='icon-area-graph'></i>Approval</a>
												</li>
											<?php  } ?>

											<?php if (($leftbar_cancel_revoke_report ?? 1) == 0) { ?>
												<li>
													<a href="cancel_revoke_report"><i class='icon-area-graph'></i>Cancel / Revoke</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_loan_issue_report ?? 1) == 0) { ?>
												<li>
													<a href="loan_issue_report"><i class='icon-area-graph'></i>Loan Issue</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_collection_report ?? 1) == 0) { ?>
												<li>
													<a href="collection_report"><i class='icon-area-graph'></i>Collection</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_in_closed_report ?? 1) == 0) { ?>
												<li>
													<a href="in_closed_report"><i class='icon-area-graph'></i>In Closed</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_closed_report ?? 1) == 0) { ?>
												<li>
													<a href="closed_report"><i class='icon-area-graph'></i>Closed</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_due_list_report ?? 1) == 0) { ?>
												<li>
													<a href="due_list_report"><i class='icon-area-graph'></i>Due List</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_noc_handover_report ?? 1) == 0) { ?>
												<li>
													<a href="noc_handover_report"><i class='icon-area-graph'></i>NOC Handover</a>
												</li>
											<?php  } ?>
										</ul>
									</div>
								</li>
								<?php  } ?>
								<?php if (($leftbar_monitor_report_module ?? 1) == 0) { ?>
								<li class="sidebar-dropdown-2">
									<a href="javascript:void(0)"><i class="icon-monitor"></i><span class="menu-text">Monitor Reports</span></a>

									<div class="sidebar-submenu-2" style="display:none; margin-left: 25px;">
										<ul>
											<?php if (($leftbar_promotion_activity_report ?? 1) == 0) { ?>
												<li>
													<a href="promotion_activity_report"><i class='icon-area-graph'></i>Promotion Activity </a>
												</li>
											<?php  } ?> 

											<?php if (($leftbar_events_report ?? 1) == 0) { ?>
												<li>
													<a href="events_report"><i class='icon-area-graph'></i>Events Activity</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_confirmation_followup_report ?? 1) == 0) { ?>
												<li>
													<a href="confirmation_followup_report"><i class='icon-area-graph'></i>Confirmation Follow Up</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_commitment_report ?? 1) == 0) { ?>
												<li>
													<!-- name changes commitment_report to Due Followup Activity -->
													<a href="commitment_report"><i class='icon-area-graph'></i>Due Followup Activity</a>  
												</li>
											<?php  } ?> 
											<!-- <?php #if (($work_count_report ?? 1) == 0) { ?>
												<li>
													<a href="work_count_report"><i class='icon-area-graph'></i>Work Count</a>
												</li>
											<?php  #} ?>  -->
											<?php if (($leftbar_concern_report ?? 1)  == 0) { ?>
												<li>
													<a href="concern_report"><i class='icon-area-graph'></i>Concern</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_loan_track ?? 1) == 0) { ?>
												<li>
													<a href="loan_track"><i class='icon-target'></i>Loan Track</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_customer_status_report ?? 1) == 0) { ?>
												<li>
												<!-- name changes customer_status_report to Collection Status -->
													<a href="customer_status_report"><i class='icon-area-graph'></i>Collection Status</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_due_followup_customer_count_report ?? 1) == 0) { ?>
												<li>
													<!-- name changes due_followup_customer_count_report to Followup Summary to Due Summary to Back Office Summary-->
													<a href="due_followup_customer_count_report"><i class='icon-area-graph'></i>Back Office Summary</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_location_track_report ?? 1) == 0) { ?>
												<li>
													<a href="location_track_report"><i class='icon-area-graph'></i>Location Track</a>
												</li>
											<?php  } ?>
										</ul>
									</div>
								</li>
								<?php } ?>
								<?php if (($leftbar_count_report_module ?? 1) == 0) { ?>
								<li class="sidebar-dropdown-2">
									<a href="javascript:void(0)"><i class="icon-layers"></i><span class="menu-text">Count Reports</span></a>

									<div class="sidebar-submenu-2" style="display:none; margin-left: 25px;">
										<ul>
											<?php if (($leftbar_request_count_report ?? 1) == 0) { ?>
												<li>
													<a href="request_count_report"><i class='icon-area-graph'></i>Request Count</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_verification_count_report ?? 1) == 0) { ?>
												<li>
													<a href="verification_count_report"><i class='icon-area-graph'></i>Verification Count</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_approval_count_report ?? 1) == 0) { ?>
												<li>
													<a href="approval_count_report"><i class='icon-area-graph'></i>Approval Count</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_loan_issue_count_report ?? 1) == 0) { ?>
												<li>
													<a href="loan_issued_count_report"><i class='icon-area-graph'></i>Loan Issued Count</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_promotion_count_report ?? 1) == 0) { ?>
												<li>
													<a href="promotion_count_report"><i class='icon-area-graph'></i>Promotion Count</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_confirmation_count_report ?? 1) == 0) { ?>
												<li>
													<a href="confirmation_count_report"><i class='icon-area-graph'></i>Confirmation Count</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_due_followup_count_report ?? 1) == 0) { ?>
												<li>
													<a href="duefollowup_count_report"><i class='icon-area-graph'></i>Due Followup Count</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_back_office_count_report ?? 1) == 0) { ?>
												<li>
													<a href="back_office_count_report"><i class='icon-area-graph'></i>Back Office Count</a>
												</li>
											<?php  } ?>
										</ul>
									</div>
								</li>
								<?php } ?>
								<?php if (($leftbar_analysis_report_module ?? 1) == 0) { ?>
								<li class="sidebar-dropdown-2">
									<a href="javascript:void(0)"><i class="icon-layers"></i><span class="menu-text">Analysis Reports</span></a>

									<div class="sidebar-submenu-2" style="display:none; margin-left: 25px;">
										<ul>
											<?php if (($leftbar_principal_interest_report ?? 1) == 0) { ?>
												<li>
													<a href="principal_interest_report"><i class='icon-area-graph'></i>Principal / Interest</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_balance_report ?? 1) == 0) { ?>
												<li>
													<a href="balance_report"><i class='icon-area-graph'></i>Balance</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_area_loan_count_report ?? 1) == 0) { ?>
												<li>
													<a href="area_loan_count_report"><i class='icon-area-graph'></i>Area Loan Count</a>
												</li>
											<?php  } ?> 
											<?php if (($leftbar_no_due_pay_report ?? 1) == 0) { ?>
												<li>
													<a href="no_due_pay_report"><i class='icon-area-graph'></i>No Due Pay</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_branch_request_count_report ?? 1) == 0) { ?>
												<li>
													<a href="branch_request_count_report"><i class='icon-area-graph'></i>Branch Request Count</a>
												</li>
											<?php  } ?>
										</ul>
									</div>
								</li>
								<?php } ?>
								<?php if (($leftbar_accounts_report_module ?? 1) == 0) { ?>
								<li class="sidebar-dropdown-2">
									<a href="javascript:void(0)"><i class="icon-domain"></i><span class="menu-text">Accounts Reports</span></a>

									<div class="sidebar-submenu-2" style="display:none; margin-left: 25px;">
										<ul>
											<?php if (($leftbar_agent_report ?? 1) == 0) { ?>
												<li>
													<a href="agent_report"><i class='icon-area-graph'></i>Agent</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_other_trans_report ?? 1) == 0) { ?>
												<li>
													<a href="other_transaction_report"><i class='icon-area-graph'></i>Other Transaction</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_day_end_report ?? 1) == 0) { ?>
												<li>
													<a href="day_end_report"><i class='icon-area-graph'></i>Day End</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_cash_tally_activity_report ?? 1) == 0) { ?>
												<li>
													<a href="cash_tally_activity_report"><i class='icon-area-graph'></i>Cash Tally Activity</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_partners_report ?? 1) == 0) { ?>
												<li>
													<a href="partners_report"><i class='icon-area-graph'></i>Partners</a>
												</li>
											<?php  } ?>
											<?php if (($leftbar_cleared_report ?? 1) == 0) { ?>
												<li>
													<a href="cleared_report"><i class='icon-area-graph'></i>Cleared</a>
												</li>
											<?php  } ?> 									
											<?php if (($leftbar_outstanding_report ?? 1) == 0) { ?>
												<li>
													<a href="outstanding_report"><i class='icon-area-graph'></i>Outstanding</a>
												</li>
											<?php  } ?> 									
										</ul>
									</div>
								</li>
								<?php } ?>                
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_reportmodule_intrest ?? 1) == 0) { ?>
					<li class="sidebar-dropdown ">
						<a href="javascript:void(0)">
							<i class='icon-area-graph'></i>
							<span class="menu-text">Reports - Interest</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'interest_report') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_intrest_ledger_report ?? 1) == 0) { ?>
									<li>
										<a href="intrest_ledger_report"><i class='icon-area-graph'></i>Ledger View</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_intrest_loan_issue_report ?? 1) == 0) { ?>
									<li>
										<a href="intrest_loan_issue_report"><i class='icon-area-graph'></i>Loan Issue</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_intrest_collection_report ?? 1) == 0) { ?>
									<li>
										<a href="intrest_collection_report"><i class='icon-area-graph'></i>Collection</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_intrest_balance_report ?? 1) == 0) { ?>
									<li>
										<a href="intrest_balance_report"><i class='icon-area-graph'></i>Balance</a>
									</li>
								<?php  } ?>
								<?php if (($leftbar_intrest_closed_report ?? 1) == 0) { ?>
									<li>
										<a href="intrest_closed_report"><i class='icon-area-graph'></i>Closed</a>
									</li>
								<?php  } ?>                
							</ul>
						</div>
					</li>
				<?php  } ?>
				<?php if (($leftbar_search_module ?? 1) == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-search'></i>
							<span class="menu-text">Search</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'search_module') echo 'style="display:block" '; ?>>
							<ul>
								<?php if (($leftbar_search ?? 1) == 0) { ?>
									<li>
										<a href="search_module"><i class='icon-search'></i>Search</a>
									</li>
								<?php  } ?>
							</ul>
						</div>
					</li>
				<?php  } ?>
				<!-- <?php #if (($leftbar_bulk_upload_module ?? 1) == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-upload-cloud'></i>
							<span class="menu-text">Bulk Upload</span>
						</a>
						<div class="sidebar-submenu" <?php #if ($current_module == 'bulk_upload') echo 'style="display:block" '; ?>>
							<ul>
								<?php #if (($leftbar_bulk_upload ?? 1) == 0) { ?>
									<li>
										<a href="bulk_upload"><i class='icon-upload-cloud'></i>Bulk Upload</a>
									</li>
								<?php  #} ?>
							</ul>
						</div>
					</li>
				<?php  #} ?> -->

				<?php  if (($leftbar_sms_module ?? 1) == 0) { ?>
					<li class="sidebar-dropdown">
						<a href="javascript:void(0)">
							<i class='icon-mail'></i>
							<span class="menu-text">SMS</span>
						</a>
						<div class="sidebar-submenu" <?php if ($current_module == 'sms_generation') echo 'style="display:block" '; ?>>
							<ul>
							<?php if (($leftbar_sms_generation ?? 1) == 0) { ?>
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