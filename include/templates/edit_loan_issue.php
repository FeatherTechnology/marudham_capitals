<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Loan Issue
	</div>
</div><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container">

				<div class="table-responsive">
					<?php
					//for loan limit access, checking if user has approval screen access
					$approvalaccess = $userObj->getuser($mysqli, $userid)['approval'];

					$mscid = 0;
					if (isset($_GET['msc'])) {
						$mscid = $_GET['msc'];
						if ($mscid == 1) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Loan Issued Details Submitted Successfully! </div>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Approval Removed Successfully! </div>
							</div>
					<?php
						}
					}
					?>
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="branch_filter">Branch</label>
							<select class="" id="branch_filter" name="branch_filter" multiple>
								<option value=''>Select Branch name</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="sector_filter">Sector</label>
							<select class="" id="sector_filter" name="sector_filter" multiple>
								<option value=''>Select Sector</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="loan_cat_filter">Loan Category</label>
							<select class="" id="loan_cat_filter" name="loan_cat_filter" multiple>
								<option value=''>Select Loan Category</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<button type="button" class="btn btn-primary" id="search_loan" style="margin-top:20px;">Search</button>
						</div>
					</div>
					<hr>
					<table id="loanIssue_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Requested Date</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Branch</th>
								<th>Sector</th>
								<th>Region</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
								<th>Loan Amount</th>
								<th>User Type</th>
								<th>User</th>
								<th>Agent Name</th>
								<th>Responsible</th>
								<th>Customer Data</th>
								<th>Customer Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->

<!-- Add Course Category Modal -->
<div class="modal fade customerstatus" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Customer Status</h5>
				<button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<input type="hidden" name="req_id" id="req_id">
							<!-- <label class="label">Existing Type</label>
							<input type="text" name="exist_type" id="exist_type" class="form-control" readonly > -->
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12"></div>
				</div>
				<div id="updatedcusHistoryTable">
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
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Customer Summary Modal -->
<div class="modal fade customersummary" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="summaryTitle">Customer Summary</h5>
				<button type="button" class="close closeCusModal" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<br />

				<input type="hidden" id="summary_cus_id">

				<div id="cus_summary_div"> <!-- Customer Summary Div START-->
					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_how_know"> How to Know </label>
								<input type="text" class="form-control" name="cus_how_know" id="cus_how_know" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_loan_count"> Loan Counts </label>
								<input type="text" class="form-control" name="cus_loan_count" id="cus_loan_count" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_frst_loanDate"> First Loan Date </label>
								<input type="text" class="form-control" name="cus_frst_loanDate" id="cus_frst_loanDate" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_travel_cmpy"> Travel with Company </label>
								<input type="text" class="form-control" name="cus_travel_cmpy" id="cus_travel_cmpy" readonly>
							</div>
						</div>
					</div>

					<hr>

					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_monthly_income"> Monthly Income </label>
								<input type="text" class="form-control" name="cus_monthly_income" id="cus_monthly_income" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_other_income"> Other Income </label>
								<input type="text" class="form-control" name="cus_other_income" id="cus_other_income" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_support_income"> Support Income </label>
								<input type="text" class="form-control" name="cus_support_income" id="cus_support_income" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_Commitment"> Commitment </label>
								<input type="text" class="form-control" name="cus_Commitment" id="cus_Commitment" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_monDue_capacity"> Monthly Due Capacity </label>
								<input type="text" class="form-control" name="cus_monDue_capacity" id="cus_monDue_capacity" readonly>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_loan_limit"> Customer Limit </label>
								<input type="text" class="form-control" name="cus_loan_limit" id="cus_loan_limit" readonly>
							</div>
						</div>
					</div>

					<hr>
					<div class="row">
						<div class="col-12">
							<button class="btn btn-primary" id="add_cus_label" style="padding: 5px 35px; float: right;" onclick="resetfeedback();getFeedbackLable();"><span class="icon-add"></span></button>
						</div>
					</div> <br>

					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="form-group" id="feedbackListTable">
								<table class="table custom-table modalTable">
									<thead>
										<tr>
											<th width="50"> S.No </th>
											<th> User Name </th>
											<th> Created Date </th>
											<th> Feedback Label </th>
											<th> Feedback </th>
											<th> Remarks </th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>

					<hr>

					<div class="row">
						<div class="col-xl-4 col-lg-6 col-md-8 col-sm-12 col-12">
							<div class="form-group">
								<label for="about_cus"> About Customer </label>
								<textarea class="form-control" name="about_cus" id="about_cus" readonly></textarea>
							</div>
						</div>
					</div>
				</div><!-- Customer Summary Div END-->

				<div id="cus_feedback_div" style="display: none;"> <!-- Customer Feedback Div START-->
					<!-- alert messages -->
					<div id="feedbackInsertOk" class="successalert"> Feedback Added Successfully <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span></div>

					<div id="feedbackUpdateok" class="successalert"> Feedback Updated Succesfully! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span></div>

					<div id="feedbackNotOk" class="unsuccessalert"> Something Went Wrong! <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span></div>

					<div id="feedbackDeleteOk" class="unsuccessalert"> Feedback Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span></div>

					<div id="feedbackDeleteNotOk" class="unsuccessalert"> Feedback not Deleted <span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span></div>

					<div class="row">
						<div class="col-sm-11 col-md-11 col-lg-11 col-xl-11 col-12"></div>
						<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 col-12">
							<button class="btn btn-primary" id='close_cus_label' onclick="feedbackList()">Back</button>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group" style="display: flex; align-items: center;">
								<div>
									<label for="feedback_label"> Feedback Label </label> <span class="required">&nbsp;*</span>
									<select type="text" class="form-control" id="feedback_label" style="width: 330px;" name="feedback_label" tabindex='1'>
										<option value=""> Select Feedback Label</option>
									</select>
									<span class="text-danger" id="feedbacklabelCheck" style='display:none'> Select Feedback Label</span>
								</div>
								<div style="padding: 20px 0px 0px 10px;  ">
									<button class="btn btn-primary" id="add_cus_feedback" onclick="cusfeedbacklist()" style="display: <?= ($approvalaccess == 0 ? 'inline-block' : 'none'); ?>;" tabindex="2"><span class="icon-add"></span></button>
								</div>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_feedback_department"> Department </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="cus_feedback_department" name="cus_feedback_department" tabindex='3'>
									<option value=""> Select Feedback </option>
									<option value="1"> Front Office </option>
									<option value="2"> Back Office </option>
									<option value="3"> Sales </option>
									<option value="4"> Verification </option>
									<option value="5"> Refine </option>
									<option value="6"> Other </option>
								</select>
								<span class="text-danger" id="departmentCheck" style='display:none'> Select Department </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="cus_feedback"> Feedback </label> <span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="cus_feedback" name="cus_feedback" tabindex='4'>
									<option value=""> Select Feedback </option>
									<option value="1"> Bad </option>
									<option value="2"> Poor </option>
									<option value="3"> Average </option>
									<option value="4"> Good </option>
									<option value="5"> Excellent </option>
								</select>
								<span class="text-danger" id="feedbackCheck" style='display:none'> Select Feedback </span>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<div class="form-group">
								<label for="customer_summary_uploads">Uploads</label>
								<input type="file" class="form-control" name="customer_summary_uploads[]" id="customer_summary_uploads" tabindex="5" multiple>
								<input type="hidden" id="cus_summary_upload">
							</div>
						</div>

						<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
							<div class="form-group">
								<label for="feedback_remark"> Remarks </label>
								<textarea class="form-control" name="feedback_remark" id="feedback_remark" tabindex='6'></textarea>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
							<input type="hidden" name="feedbackID" id="feedbackID">
							<button type="button" name="feedbackBtn" id="feedbackBtn" class="btn btn-primary" style="margin-top: 5px;" tabindex='7'> Submit </button>
						</div>
					</div>
					</br>

					<div id="feedbackTable" class="table-responsive">
						<table class="table custom-table">
							<thead>
								<tr>
									<th width="50"> S.No </th>
									<th > User Name </th>
									<th> Created Date </th>
									<th> Feedback Label </th>
									<th> Department </th>
									<th> Feedback </th>
									<th> Upload </th>
									<th> ACTION </th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div> <!-- Customer Feedback Div END-->
				
				<div id="feedback_label_div" style="display: none;"> <!-- Feedback label Div START-->
					<div class="row">
						<div class="col-sm-11 col-md-11 col-lg-11 col-xl-11 col-12"></div>
						<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 col-12">
							<button class="btn btn-primary" id='close_feedback_label' onclick="getFeedbackLable()">Back</button>
						</div>

						<div class="col-md-12">
							<div class="row">
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-10 col-12"></div>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-10 col-12">
									<div class="form-group">
										<label for="feedbackname">Feedback Label</label>&nbsp;<span class="text-danger"></span>
										<input type="hidden" name="fedbackname_id" id="fedbackname_id">
										<input type="text" tabindex="4" class="form-control" id="feedbackname" name="feedbackname" placeholder="Enter Feedback Label">
									</div>
								</div>

								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-10 col-12 d-flex align-items-center" style="margin-top: 20px;">
									<div class="form-group">
										<button type="submit" name="submit_feedback_lable" id="submit_feedback_lable" class="btn btn-primary" value="Submit" tabindex="5"><span class="icon-check"></span>&nbsp;Submit</button>
									</div>
								</div>
							</div>
							<br>
						</div>
						<div class="col-md-12" id="cus_feedbackListTable_div">
							<table class="table custom-table" id="cus_feedbackListTable">
								<thead>
									<tr>
										<th width="50"> S.No </th>
										<th> Feedback Label  </th>
										<th> ACTION </th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div> <!-- Feedback label Div END-->
				
			</div> <!-- Modal box modal-body END -->
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary closeCusModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- ///////////////////////////////////////////////  Customer Summary  END /////////////////////////////////////////////////////////// -->

<!-- Loan Summary Modal -->
<div class="modal fade loansummary" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Loan Summary</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeLoanModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<input type="hidden" name="req_id" id="req_id">
							<!-- <label class="label">Existing Type</label>
							<input type="text" name="exist_type" id="exist_type" class="form-control" readonly > -->
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12"></div>
				</div>
				<div id="updatedloanSummaryTable">
					<table class="table custom-table" id="loanSummaryTable">
						<thead>
							<tr>
								<th width="25">S. No</th>
								<th>Feedback Label</th>
								<th>Feedback Rating</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeLoanModal()">Close</button>
			</div>
		</div>
	</div>
</div>