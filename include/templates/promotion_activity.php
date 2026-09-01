<link rel="stylesheet" type="text/css" href="css/promotion_activity.css" />
<?php

$getUser = $userObj->getUser($mysqli, $_SESSION['userid']);
if (sizeof($getUser) > 0) {
	$user_name = $getUser['fullname'];
	$user_type = $getUser['role'];
	if ($user_type == '1') {
		$user_type = 'Director';
	} elseif ($user_type == '2') {
		$user_type = 'Agent';
	} elseif ($user_type == '3') {
		$user_type = 'Staff';
	}
}
?>
<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Promotion Activity
	</div>

</div><br>

<div class="text-right" style="margin-right: 25px;">
	<button class="btn btn-primary" id='close_history_card' style="display: none;">&times;&nbsp;&nbsp;Cancel</button>
</div>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="promotion_activity_form" name="promotion_activ_form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />

		<div class="row gutters">
			<div class="toggle-container col-12">
				<input type="button" class="toggle-button" value='Enquiry' id="enquiry_button">
				<input type="button" class="toggle-button" value='Renewal' id="renewal_button">
				<input type="button" class="toggle-button" value='Re-active' id="reactive_button">
				<input type="button" class="toggle-button" value='New' id="new_button">
				<input type="button" class="toggle-button" value='Events' id="events_button">
			</div>
		</div>

		<div class="card filter_card" style="display: none;">
			<div class="card-body">
				<div class="row">
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="follow_up_sts">Followup status</label>
							<select class="form-control" name="follow_up_sts" id="follow_up_sts">
								<option value="">Select Followup status</option>
								<option value="tofollow">To Follow</option>
								<option value="Interested">Interested</option>
								<option value="NotInterested">Not Interested</option>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="date_type">Date</label>
							<select class="form-control" name="date_type" id="date_type">
								<option value="">Select Date</option>
								<option value="1">Closed Date</option>
								<option value="2">Followup Date</option>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="follow_up_fromdate">From Date</label>
							<input type="date" class="form-control" name="follow_up_fromdate" id="follow_up_fromdate">
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="follow_up_todate">To Date</label>
							<input type="date" class="form-control" name="follow_up_todate" id="follow_up_todate">
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="followuptype">Follow up Type</label>
							<select class="form-control" name="followuptype" id="followuptype">
								<option value="0">Select Followup Type</option>
								<option value="1">Field</option>
								<option value="2">Telecalling</option>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="branch">Branch</label>
							<select class="form-control" name="branch" id="branch">
								<option value="">Select Branch </option>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="sector">Sector</label>
							<select class="form-control" name="sector" id="sector">
								<option value="">Select Sector</option>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
						<div class="form-group">
							<label for="area">Area</label>
							<select class="form-control" name="area" id="area">
								<option value="">Select Area</option>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-top:20px">
						<div class="form-group">
							<button class="btn btn-primary" name="followup_search" id="followup_search">Search</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row gutters enquiry_card" style="display: none;">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Enquiry
						<button type="button" class="btn btn-primary add-enquiry-btn" id="add_enquiry_cus" name="add_enquiry_cus" data-toggle="modal" data-target="#addnewcus" value="Enquiry"><span class="icon-add"></span></button>
					</div>
					<div class="card-body">
						<div id="enquiry_div" class="table-responsive">

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row gutters renewal_card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Renewal Customer</div>
					<div class="card-body" style="overflow-x: auto;">
						<table class="table custom-table" id='expromotion_list' data-id="renewal" style="width: 100%;">
							<thead>
								<th width='20'>S.No</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Status</th>
								<th>Sub Status</th>
								<th>Closed Date</th>
								<th>NOC Status</th>
								<th>View</th>
								<th>Action</th>
								<th>Personal Info</th>
								<th>Follow up status</th>
								<th>Follow Date</th>
								<th>Follow up Type</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row gutters re_active_card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Re-active Customer</div>
					<div class="card-body" style="overflow-x: auto;">
						<table class="table custom-table" id='re_active_promotion_list' data-id="re_active" style="width: 100%;">
							<thead>
								<th width='20'>S.No</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Status</th>
								<th>Sub Status</th>
								<th>Closed Date</th>
								<th>NOC Status</th>
								<th>View</th>
								<th>Action</th>
								<th>Personal Info</th>
								<th>Follow up status</th>
								<th>Follow Date</th>
								<th>Follow up Type</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Customer Status START -->
		<div class="row gutters customer-status-card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header"> Customer Status </div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table custom-table" id="cusHistoryTable">
								<thead>
									<tr>
										<th width="25">S. No</th>
										<th>Date</th>
										<th>Loan Category</th>
										<th>Sub Category</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Sub Status</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Customer Status END -->

		<!-- Loan History START -->
		<div class="row gutters loan-history-card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header"> Loan History </div>
					<div class="card-body">
						<div id="loanHistoryDiv" class="table-responsive">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Loan History END -->

		<!-- Document History START -->
		<div class="row gutters doc-history-card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header"> Document History </div>
					<div class="card-body">
						<div id="docHistoryDiv" class="table-responsive">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Document History END -->


		<div class="row gutters new_card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Promotion</div>
					<div class="col-12">
						<div class="alert alert-danger" role="alert" style="display: none;">
							<div class="alert-text">Customer Already Existing!</div>
						</div>
						<div class="alert alert-success" role="alert" style="display: none;">
							<div class="alert-text">Customer is New to Promotion!</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for="cus_id_search">Aadhaar Number</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="cus_id_search" name="cus_id_search" value='' placeholder='Enter Aadhaar Number' onKeyPress="if(this.value.length==14) return false;">
									<span class="searchDetailsCheck text-danger" style="display: none;">Please enter any of these fields!</span>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for="cus_name_search">Customer Name</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="cus_name_search" name="cus_name_search" value='' placeholder='Enter Customer Name'>
									<span class="searchDetailsCheck text-danger" style="display: none;">Please enter any of these fields!</span>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<label for="cus_mob_search">Mobile</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="cus_mob_search" name="cus_mob_search" value='' placeholder='Enter Mobile Number' onKeyPress="if(this.value.length==10) return false;" oninput="validateInputNumber(this,'withOutDot')">
									<span class="searchDetailsCheck text-danger" style="display: none;">Please enter any of these fields!</span>
								</div>
							</div> <!-- Use input type='text' for numeric validation, because type='number' always resets the cursor when you block invalid characters-->
							<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
								<div class="form-group">
									<button class="" id='search_cus' name='search_cus'>Search&nbsp;<i class="fa fa-search"></i>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row gutters new_promo_card" style="display: none;">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">New Promotion
						<button type="button" class="btn btn-primary add-new-btn" id="add_new_cus" name="add_new_cus" data-toggle="modal" data-target="#addnewcus" value="New Promotion"><span class="icon-add"></span></button>
					</div>
					<div class="card-body">
						<div id="new_promo_div" class="table-responsive">

						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row gutters repromotion_card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Repromotion</div>
					<div class="card-body" style="overflow-x: auto;">
						<table class="table custom-table" id='repromotion_list' data-id="repromotion" style="width:100%">
							<thead>
								<th width='20'>S.No</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Sector</th>
								<th>Region</th>
								<th>Mobile</th>
								<th>Status</th>
								<th>Sub Status</th>
								<th>Customer Data</th>
								<th>Closed Date</th>
								<th>NOC Status</th>
								<th>View</th>
								<th>Action</th>
								<th>Follow up status</th>
								<th>Follow Date</th>
								<th>Follow up Type</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row gutters event_card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Events</div>
					<div style="display:flex ; align-items: center; justify-content: right; margin-right: 25px;"><button type="button" class="btn btn-primary" id="add_event"><span class="icon-add"></span>&nbsp; Add Events</button></div>
					<div class="card-body eventsdiv" style="overflow-x: auto;">
						<table class="table custom-table" id='event_list' data-id="event" style="width: 100%;">
							<thead>
								<th>S.No</th>
								<th>Date</th>
								<th>Area Name</th>
								<th>Total Customer</th>
								<th>Action</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row gutters add_event_card" style="display:none">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<span>Add Events</span>
						<button type="button" class="btn btn-primary" id="back">Back</button>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="form-group">
									<label for="area_name">Area Name</label><span class="required">&nbsp;*</span>
									<input type="hidden" name="event_area_id" id="event_area_id">
									<input type="hidden" name="event_hidden_id" id="event_hidden_id">
									<select type="text" class="form-control" id="area_name" name="area_name">
										<option value="">Select Area Name</option>
									</select>
								</div>
							</div>
						</div><br>

						<div class="row">
							<div class="col-12">
								<table id="moduleTable" class="table custom-table">
									<thead>
										<tr>
											<th>Date</th>
											<th>Name</th>
											<th>Mobile</th>
											<th>Sub Area</th>
											<th>User</th>
											<th colspan="2">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="current_date"></td>
											<td>
												<input type="text" name="cus_name" id="cus_name" class="form-control cus_name" value="" placeholder="Enter Customer Name">
											</td>
											<td>
												<input type="text" class="form-control cus_mobile_num" id="cus_mobile_num" name="cus_mobile_num" value='' placeholder="Enter Mobile Number" oninput="validateInputNumber(this,'withOutDot')">
											</td>
											<td>
												<select type="text" class="form-control sub_area_name" id="sub_area_name" name="sub_area_name">
													<option value="">Select Sub Area Name</option>
												</select>
											</td>
											<td class="user"></td>
											<td>
												<button type="button" id="add_event_mem" name="add_event_mem" value="Submit" class="btn btn-primary add_event_mem">Add</button>
											</td>
											<td>
												<span class='icon-trash-2 delet_event'></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div style="display:flex ; align-items: center; justify-content: right; margin-right: 25px;"><button type="button" class="btn btn-primary" id="submit_event">Submit</button></div>
					</div>
				</div>
			</div>

		</div>

	</form>
</div>

<!-- Modal for New promotion Customer -->
<div class="modal fade" id="addnewcus" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="new_enquiry_modal_title">New Promotion</h5>
				<button type="button" class="close" id="closeNewPromotionModal" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row">

					<div class="col-12">
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_id">Aadhaar Number</label><span class="required">&nbsp;*</span>
									<input type="text" name="cus_id" id="cus_id" class='form-control' placeholder="Enter Aadhaar Number" tabindex="1" onKeyPress="if(this.value.length==14) return false;">
									<span class="text-danger" id='cus_idCheck' style="display: none;">Please Enter Aadhaar Number</span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_data">Customer Data</label><span class="required">&nbsp;*</span>
									<input class='form-control' name="cus_data" id="cus_data" tabindex="2" readonly>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_name">Customer Name</label><span class="required">&nbsp;*</span>
									<input type="text" name="new_cus_name" id="new_cus_name" class='form-control' placeholder="Enter Customer Name" tabindex="3">
									<span class="text-danger" id='cus_nameCheck' style="display: none;">Please Enter Customer Name</span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_mob">Mobile Number</label><span class="required">&nbsp;*</span>
									<input type="text" name="cus_mob" id="cus_mob" class='form-control' placeholder="Enter Mobile Number" tabindex="4" onKeyPress="if(this.value.length==10) return false;" oninput="validateInputNumber(this,'withOutDot')">
									<span class="text-danger" id='cus_mobCheck' style="display: none;">Please Enter Mobile Number </span>
								</div>
							</div> <!-- Use input type='text' for numeric validation, because type='number' always resets the cursor when you block invalid characters-->

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="area">Area</label>&nbsp;<span class="text-danger">*</span>
									<select tabindex="5" type="text" class="form-control" id="area" name="area">
										<option value="">Select Area</option>
									</select>
									<span class="text-danger" style='display:none' id='areaCheck'>Please Select Area</span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="sub_area">Sub Area</label>&nbsp;<span class="text-danger">*</span>
									<select tabindex="6" type="text" class="form-control" id="sub_area" name="sub_area">
										<option value=''>Select Sub Area</option>
									</select>
									<span class="text-danger" style='display:none' id='subareaCheck'>Please Select Sub Area</span>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 enq_loan_amt" style="display: none;">
								<div class="form-group">
									<label for="enquiry_loan_amt">Loan amount</label><span class="required">&nbsp;*</span>
									<input type="text" class="form-control" name="enquiry_loan_amt" id="enquiry_loan_amt" placeholder="Enter Loan Amount" tabindex="7" oninput="validateInputNumber(this,'withOutDot')">
									<span class="text-danger" id='enquiryloanamtCheck' style="display: none;">Please Enter Loan amount</span>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="screen_name"/>
				<button class='btn btn-primary' name="submit_new_cus" id="submit_new_cus" tabindex="8">Submit</button>
				<button class="btn btn-secondary modalCloseBtn" data-dismiss="modal" tabindex="9">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for promotion add -->
<div class="modal fade" id="addPromotion" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add Promotion</h5>
				<button type="button" class="close closeModal" id="closeAddPromotionModal" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row">

					<div class="col-12">
						<div class="row">
							<input type="hidden" name="orgin_table" id="orgin_table"><!-- this is to reset the table contents -->
							<input type="hidden" name="promo_cus_id" id="promo_cus_id">
							<input type="hidden" name="promo_screen" id="promo_screen">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_date">Date</label><span class="required">&nbsp;*</span>
								<input type="text" class='form-control' readonly name="promo_date" id="promo_date" tabindex="1" value='<?php echo date('d-m-Y'); ?>' />
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_type">Promotion Type</label><span class="required">&nbsp;*</span>
								<select class="form-control" name="promo_type" id="promo_type">
									<option value="">Select Promotion Type</option>
									<option value="1">Direct</option>
									<option value="2">Mobile</option>
								</select>
								<span class="text-danger" id='promo_typeCheck' style="display: none;">Please Select Promotion Type</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_status">Status</label><span class="required">&nbsp;*</span>
								<input type="text" name="promo_status" id="promo_status" class='form-control' placeholder="Enter Status" tabindex="2" readonly>
								<span class="text-danger" id='promo_statusCheck' style="display: none;">Please Enter Status</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_label">Label</label><span class="required">&nbsp;*</span>
								<input type="text" name="promo_label" id="promo_label" class='form-control' placeholder="Enter Label" tabindex="3">
								<span class="text-danger" id='promo_labelCheck' style="display: none;">Please Enter Label </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_remark">Remark</label><span class="required">&nbsp;*</span>
								<input type="text" name="promo_remark" id="promo_remark" class='form-control' placeholder="Enter Remark" tabindex="4">
								<span class="text-danger" id='promo_remarkCheck' style="display: none;">Please Enter Remark</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_user_type">User Type</label><span class="required">&nbsp;*</span>
								<input type="text" name="promo_user_type" id="promo_user_type" class='form-control' value='<?php echo $user_type; ?>' tabindex="5" readonly>
								<span class="text-danger" id='promo_user_typeCheck' style="display: none;">Please Enter User Type </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_user">User</label><span class="required">&nbsp;*</span>
								<input type="text" name="promo_user" id="promo_user" class='form-control' value="<?php echo $user_name; ?>" tabindex="6" readonly>
								<span class="text-danger" id='promo_userCheck' style="display: none;">Please Enter User </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="promo_fdate">Follow Date</label><span class="required">&nbsp;*</span>
								<input type="date" name="promo_fdate" id="promo_fdate" class='form-control' placeholder="Enter Follow Date" tabindex="7">
								<span class="text-danger" id='promo_fdateCheck' style="display: none;">Please Choose Follow Date </span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<label for="followup_type">Follow up Type</label>
								<select class="form-control" name="followup_type" id="followup_type">
									<option value="0">Select Followup Type</option>
									<option value="1">Field</option>
									<option value="2">Telecalling</option>
								</select>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button class='btn btn-primary' name="sumit_add_promo" id="sumit_add_promo" tabindex="8">Submit</button>
				<button class="btn btn-secondary closeModal" data-dismiss="modal" tabindex="9">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for Closed -->
<div class="modal fade" id="addClosedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Update Closed Status</h5>
				<button type="button" class="close addcloseModal" data-dismiss="modal" aria-label="Close" id="closedModal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<input type="hidden" name="orgin_closed_table" id="orgin_closed_table"><!-- this is to reset the table contents -->
						<input type="hidden" name="close_cus_id" id='close_cus_id'>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<label for="aadhar_num">Aadhar Number</label><span class="required">&nbsp;*</span>
							<input type="text" name="aadhar_num" id="aadhar_num" class='form-control' readonly>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<label for="customer_id">Customer ID</label><span class="required">&nbsp;*</span>
							<input type="text" name="customer_id" id="customer_id" class='form-control' readonly>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<label for="customer_name">Customer Name</label><span class="required">&nbsp;*</span>
							<input type="text" name="customer_name" id="customer_name" class='form-control' readonly>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div class="form-group">
								<label for="closed_Sts"> Closed Status </label> <span class="required">*</span>
								<select type="text" class="form-control" name="closed_Sts" id="closed_Sts">
									<option value=""> Select Closed Status </option>
									<option value="2"> Waiting List </option>
									<option value="3"> Block List </option>
								</select>
								<span class="text-danger" id="closedStatusCheck" style="display:none;">Please Select Closed Status </span>
							</div>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4 d-flex align-items-end">
							<div class="form-group mb-3">
								<button name="submit_closed" id="submit_closed" class="btn btn-primary" tabindex="1">&nbsp;Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary addcloseModal" data-dismiss="modal" tabindex="1">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for promotion Chart just view table   -->
<div class="modal fade" id="promoChartModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Promotion Chart</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

					<div class="col-12">
						<div class="row">
							<div class="col-12 table-responsive" id='promoChartDiv'></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="7">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for Personal Info   -->
<div class="modal fade" id="personalInfoModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Personal Info</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row" id='personalInfoDiv'>


				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="7">Close</button>
			</div>
		</div>
	</div>
</div>