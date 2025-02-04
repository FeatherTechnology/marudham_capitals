<link rel="stylesheet" type="text/css" href="css/promotion_activity.css" />
<?php

if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
}

$getUser = $userObj->getUser($mysqli,$_SESSION['userid']); 
if (sizeof($getUser)>0) {
	$user_name = $getUser['fullname'];
	$user_type = $getUser['role'];
	if($user_type == '1'){$user_type = 'Director';}elseif($user_type == '2'){$user_type = 'Agent';}elseif($user_type == '3'){$user_type = 'Staff';}
}

$idupd=0;
if(isset($_GET['upd']))
{
$idupd=$_GET['upd'];
$cusidupd=$_GET['cusidupd'];
$cus_sts = $_GET['cussts'];  
}
if($idupd>0)
{
	$getLoanList = $userObj->getLoanList($mysqli,$idupd); 
	// print_r($getLoanList);
	if (sizeof($getLoanList)>0) {
			$cus_id						= $getLoanList['cus_id'];
			$cus_name					= $getLoanList['cus_name'];
			$area_id					= $getLoanList['area_confirm_area'];
			$area_name					= $getLoanList['area_name'];
			$sub_area_id				= $getLoanList['area_confirm_subarea'];
			$sub_area_name				= $getLoanList['sub_area_name'];
			$branch_id					= $getLoanList['branch_id'];
			$branch_name				= $getLoanList['branch_name'];
			$line_id					= $getLoanList['line_id'];
			$line_name					= $getLoanList['area_line'];
			$mobile1					= $getLoanList['mobile1'];
			$cus_pic					= $getLoanList['cus_pic'];
	}
}


?>


<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Due Follow Up
	</div>
</div>
<br>
<div class="text-right" style="margin-right: 25px;">
	<button type="button" class="btn btn-primary back-button"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	<button class="btn btn-primary" id='close_collection_card' style="display: none;">&times;&nbsp;&nbsp;Cancel</button>
</div><br><br>
<!-- Page header end -->



<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="cus_Profiles" name="cus_Profiles" action="" method="post" enctype="multipart/form-data">
		<!-- for js purposes -->
		<input type="hidden" name="cus_sts" id="cus_sts" value="<?php if (isset($cus_sts)) {echo $cus_sts;} ?>" />
		<input type="hidden" name="idupd" id="idupd" value="<?php if (isset($idupd)) {echo $idupd;} ?>" />
		<input type="hidden" name="req_id" id="req_id" value="<?php if (isset($req_id)) {echo $req_id;} ?>" />
		<input type="hidden" name="cusidupd" id="cusidupd" value="<?php if (isset($cusidupd)) {echo $cusidupd;} ?>" />
		<input type="hidden" name="cus_name" id="cus_name" value="<?php if (isset($cus_name)) {echo $cus_name;} ?>" />
		<input type="hidden" name="pending_sts" id="pending_sts" value="" />
		<input type="hidden" name="od_sts" id="od_sts" value="" />
		<input type="hidden" name="due_nil_sts" id="due_nil_sts" value="" />
		<input type="hidden" name="closed_sts" id="closed_sts" value="" />

		<!-- Row start -->
		<div class="row gutters">
			<!-- Request Info -->
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				
				<!-- Loan List Start -->
				<div class="card loanlist_card">
					<div class="card-header">
						<div class="card-title">Loan List</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="form-group table-responsive" id='loanListTableDiv'>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Loan List End -->

				<!-- Loan History START -->
				<div class="card loan_history_card" style="display: none;">
					<div class="card-header"> Loan History </div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="loanHistoryDiv">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Loan History END -->

				<!-- Document History START -->
				<div class="card doc_history_card" style="display: none;">
					<div class="card-header"> Documents History </div>
					<div class="card-body">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="form-group table-responsive" id="docHistoryDiv">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Document History END -->				
			</div>
		</div>
	</form>
	<!-- Form End -->
</div>
<div id="printcollection" style="display: none"></div>

<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal START ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade DueChart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <input type="hidden" name="req_id" id="req_id" value="<?php if(isset($idupd)){echo $idupd;} ?>" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle"> Due Chart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="dueChartTableDiv">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th> S.No </th>
                                <th> Due Month </th>
                                <th> Month </th>
                                <th> Due Amount </th>
                                <th> Pending </th>
                                <th> Payable </th>
                                <th> Collection  Date </th>
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
    <input type="hidden" name="req_id" id="req_id" value="<?php if(isset($idupd)){echo $idupd;} ?>" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"> Penalty Chart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="penaltyChartTableDiv">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th> S.No </th>
                                <th> Penalty Date </th>
                                <th> Penalty  </th>
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
    <input type="hidden" name="req_id" id="req_id" value="<?php if(isset($idupd)){echo $idupd;} ?>" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"> Fine Chart </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="collectionChargeDiv">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th> S.No </th>
                                <th> Date </th>
                                <th> Fine  </th>
                                <th> Purpose </th>
                                <th> Paid Date </th>
                                <th> Paid  </th>
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

<!-- /////////////////////////////////////////////////////////////////// Commitment Add Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade" id='addCommitment' tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add Commitment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" >
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid row">

					<div class="col-12">
						<div class="row">
							<input type="hidden" class="form-control" id="comm_req_id" name="comm_req_id">
							
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<label for="comm_fdate">Follow Up Date</label> <span class="required">&nbsp;*</span>
									<input type="text" class="form-control" id="comm_fdate" name="comm_fdate" tabindex="1" value="<?php echo date('d-m-Y');?>" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<label for="comm_ftype">Follow Type</label> <span class="required">&nbsp;*</span>
									<select class="form-control" id="comm_ftype" name="comm_ftype" tabindex="2" >
										<option value="">Select Follow Type</option>
										<option value="1">Direct</option>
										<option value="2">Mobile</option>
									</select>
									<span class="text-danger" id="comm_ftypeCheck" style="display:none">Please Select Follow Type</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<label for="comm_fstatus">Follow Up Status</label> <span class="required">&nbsp;*</span>
									<select class="form-control" id="comm_fstatus" name="comm_fstatus" tabindex="3" >
										<option value="">Select Follow Up Status</option>
									</select>
									<span class="text-danger" id="comm_fstatusCheck" style="display:none">Please Select Follow Up Status</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
									<label for="comm_person_type">Follow Person Type</label><span class="required">&nbsp;*</span>
									<select name="comm_person_type" id="comm_person_type" class='form-control' tabindex="4">
										<option value="">Select Person Type</option>
										<option value="1">Customer</option>
										<option value="2">Guarentor</option>
										<option value="3">Family Member</option>
									</select>
									<span class="text-danger" id="comm_person_typeCheck" style="display:none">Please Select Person Type</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
									<label for="comm_person_name">Person Name</label><span class="required">&nbsp;*</span>
									<input type="text" name="comm_person_name" id="comm_person_name" class='form-control' tabindex="5" readonly>
									<select name="comm_person_name1" id="comm_person_name1" class='form-control' tabindex="5" style="display: none;"></select>
									<span class="text-danger" id="comm_person_nameCheck" style="display:none">Please Select Person Name</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div" style="display:none">
									<label for="comm_relationship">Relationship</label><span class="required">&nbsp;*</span>
									<input type="text" name="comm_relationship" id="comm_relationship" class='form-control' tabindex="6" readonly>
									<span class="text-danger" id="comm_relationshipCheck" style="display:none">Please Select Relationship</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" >
									<label for="comm_remark">Remark</label><span class="required">&nbsp;*</span>
									<input type="text" name="comm_remark" id="comm_remark" class='form-control' tabindex="7" placeholder="Enter Remark">
									<span class="text-danger" id='comm_remarkCheck' style="display: none;">Please Enter Remark</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 person-div"  style="display:none">
									<label for="comm_date">Commitment Date</label><span class="required">&nbsp;*</span>
									<input type="date" name="comm_date" id="comm_date" class='form-control' tabindex="8" >
									<span class="text-danger" id='comm_dateCheck' style="display: none;">Please Enter Commitment Date</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<label for="comm_user_type">User Type</label><span class="required">&nbsp;*</span>
									<input type="text" name="comm_user_type" id="comm_user_type" class='form-control' value='<?php echo $user_type;?>' tabindex="9" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
									<label for="comm_user">User Name</label><span class="required">&nbsp;*</span>
									<input type="text" name="comm_user" id="comm_user" class='form-control' value="<?php echo $user_name;?>" tabindex="10" readonly>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" >
									<label for="comm_hint">Hint</label><span class="required">&nbsp;*</span>
									<input type="text" name="comm_hint" id="comm_hint" class='form-control' tabindex="11" placeholder="Enter Hint">
									<span class="text-danger" id='comm_hintCheck' style="display: none;">Please Enter Hint</span>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" >
									<label for="comm_err">Communication Status</label><span class="required">&nbsp;*</span>
									<select name="comm_err" id="comm_err" class='form-control' tabindex="12" >
										<option value="">Select Communication Status</option>
										<option value="1">Error</option>
										<option value="2">Clear</option>
									</select>
									<span class="text-danger" id='comm_errCheck' style="display: none;">Please Enter Communication Status</span>
							</div>

						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button class='btn btn-primary' name="sumit_add_comm" id="sumit_add_comm" tabindex="13">Submit</button>
				<button class="btn btn-secondary closeModal" data-dismiss="modal" tabindex="14">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Commitment Add Modal END ////////////////////////////////////////////////////////////////////// -->

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
				<div class="table-responsive" id='commChartDiv'></div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal" tabindex="2">Close</button>
			</div>
		</div>
	</div>
</div>