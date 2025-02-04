<link rel="stylesheet" type="text/css" href="css/promotion_activity.css" />
<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Loan Track
	</div>
</div>
<br>
<div class="text-right" style="margin-right: 25px;">
	<button class="btn btn-primary" id='close_btn' style="display: none;">&times;&nbsp;&nbsp;Cancel</button>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="loan_track" name="loan_track" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />

		<!-- Row start -->
		<div class="row gutters">

			<div class="col-md-12" id='search_card'>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Search Customer</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_id">Customer ID</label>
									<input type="text" class="form-control" id="cus_id" name="cus_id" placeholder="Enter Customer ID" maxlength="14">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="cus_name">Customer Name</label>
									<input type="text" class="form-control" id="cus_name" name="cus_name" placeholder="Enter Customer Name">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="mobile">Mobile Number</label>
									<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="loan_id">Loan ID</label>
									<input type="text" class="form-control" id="loan_id" name="loan_id" placeholder="XXXX">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" style="text-align:center">
								<div class="form-group">
									<!-- <input type="button" class="" id="search_cus" name="search_cus" value="Search" style="float: left;"> -->
									<button class="" id='search_cus' name='search_cus' style="float: left;" onclick="event.preventDefault();">Search&nbsp;<i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Customer List Start -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id='customer_list_card'>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Customer List</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group table-responsive" id='customer_list'>
											<table class="table custom-table" id="custListTable">
												<thead>
													<tr>
														<th>S.No</th>
														<th>Customer ID</th>
														<th>Customer Name</th>
														<th>Area</th>
														<th>Sub Area</th>
														<th>Branch</th>
														<th>Line</th>
														<th>Group</th>
														<th>Mobile 1</th>
														<th>Mobile 2</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td colspan='11'>No Records available</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Customer List END -->

			<!-- Loan List Start -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 loanlist_card" style="display: none;">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Loan List</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group" id='loanListTableDiv'>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Loan List End -->
			<!-- Loan Track Start -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 loanlist_card" style="display: none;">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Loan Track</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group" id='loanTrackDiv'>
											<table class="table table-bordered">
												<thead>
													<th width="10%">S.No</th>
													<th>Loan Stage</th>
													<th>Date</th>
													<th>User Type</th>
													<th>User Name</th>
													<th>Name</th>
													<th>Branch</th>
													<th>Details</th>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Loan Track End -->
		</div>
	</form>
	<!-- Form End -->
</div>
<div id="printcollection" style="display: none"></div>

<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade DueChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="dueChartTitle"> Due Chart</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="dueChartTableDiv">
					<table class="table custom-table">
						<thead>
							<tr>
								<th> S.No </th>
								<th> Due Month </th>
								<th> Month </th>
								<th> Due Amount </th>
								<th> Pending </th>
								<th> Payable </th>
								<th> Collection Date </th>
								<th> Collection Amount </th>
								<th> Balance Amount </th>
								<th> Collection Track </th>
								<th> Role </th>
								<th> User ID </th>
								<th> Collection Location </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Penalty Char Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade PenaltyChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Penalty Chart</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="penaltyChartTableDiv">
					<table class="table custom-table">
						<thead>
							<tr>
								<th> S.No </th>
								<th> Penalty Date </th>
								<th> Penalty </th>
								<th> Paid Date </th>
								<th> Paid Amount </th>
								<th> Balance Amount </th>
								<th> Waiver Amount </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Penalty Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Fine Chart Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade collectionChargeChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel"> Fine Chart </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="collectionChargeDiv">
					<table class="table custom-table">
						<thead>
							<tr>
								<th> S.No </th>
								<th> Date </th>
								<th> Fine </th>
								<th> Purpose </th>
								<th> Paid Date </th>
								<th> Paid </th>
								<th> Balance </th>
								<th> Waiver </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Fine Chart Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Commitment Chart Modal Start ////////////////////////////////////////////////////////////////////// -->

<!-- Modal for Commitment Chart just view table   -->
<div class="modal fade" id="commitmentChart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Commitment Chart</h5>
				<button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

					<div class="col-12">
						<div class="row">
							<div class="col-12" id='commChartDiv'></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="2">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Commitment Chart Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Stage Details Modal Start ////////////////////////////////////////////////////////////////////// -->

<!-- Modal for Details based on stages - just view table   -->
<div class="modal fade" id="stageDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Stage Details</h5>
				<button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">

					<div id='stageDetailsDiv'></div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="2">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Stage Details Modal END ////////////////////////////////////////////////////////////////////// -->