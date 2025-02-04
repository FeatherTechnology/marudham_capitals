<?php
@session_start();
if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

function callReminderFunction($userObj, $HOSTPATH){
    $getResponse = $userObj->sendMonthlyReminder();
    if($getResponse == '1'){ ?>
        <script> location.href = '<?php echo $HOSTPATH; ?>sms_generation&msc=1';</script>

    <?php }else{ ?>
        <script> location.href = '<?php echo $HOSTPATH; ?>sms_generation&msc=2';</script>

    <?php }
}
//birthday SMS
if(isset($_POST['send_birthdayWishes_SMS']) AND $_POST['send_birthdayWishes_SMS'] !=''){
    $getbirthdayResponse = $userObj->sendbirthdaySMS();
    if($getbirthdayResponse == '1'){ ?>
        <script> location.href = '<?php echo $HOSTPATH; ?>sms_generation&msc=1';</script>

    <?php }else{ ?>
        <script> location.href = '<?php echo $HOSTPATH; ?>sms_generation&msc=2';</script>

    <?php }
} 
//Festival SMS
if(isset($_POST['send_festivalWishes_SMS']) AND $_POST['send_festivalWishes_SMS'] !=''){
    $getfestivalResponse = $userObj->sendfestivalSMS($mysqli, $userid);
    if($getfestivalResponse == '1'){ ?>
        <script> location.href = '<?php echo $HOSTPATH; ?>sms_generation&msc=1';</script>

    <?php }else{ ?>
        <script> location.href = '<?php echo $HOSTPATH; ?>sms_generation&msc=2';</script>

    <?php }
} 
//Monthly Loan
if(isset($_POST['send_monthly_loan_reminder']) AND $_POST['send_monthly_loan_reminder'] !=''){
    callReminderFunction($userObj, $HOSTPATH);
} 
//Scehme Monthly Loan
if(isset($_POST['send_scheme_monthly_reminder']) AND $_POST['send_scheme_monthly_reminder'] !=''){
    callReminderFunction($userObj, $HOSTPATH);
}
//Scheme Weekly Loan 
if(isset($_POST['send_scheme_weekly_reminder']) AND $_POST['send_scheme_weekly_reminder'] !=''){
    callReminderFunction($userObj, $HOSTPATH);
} 
//scheme Daily Loan
if(isset($_POST['send_scheme_daily_reminder']) AND $_POST['send_scheme_daily_reminder'] !=''){
    callReminderFunction($userObj, $HOSTPATH);
} ?>

<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - SMS Generation
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
<?php
    $mscid=0;
    if(isset($_GET['msc']))
    {
    $mscid=$_GET['msc'];
    if($mscid==1)
    {?>
    <div class="alert alert-success" role="alert">
        <div class="alert-text">SMS Sent Successfully!</div>
    </div> 
    <?php
    }
    if($mscid==2)
    {?>
    <div class="alert alert-danger" role="alert">
        <div class="alert-text">SMS Failed!</div>
    </div>
    <?php
    }
    }
?>
    <!-- Form start -->
    <form id="sms_generation_screen" name="sms_generation_screen" method="post" enctype="multipart/form-data">
        <div class="row gutters">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-12">
                <!-- Birthday list START -->
                <form name="customer_birthdayList" method="POST">
                    <div class="card">
                        <div class="card-header">Today's Birthday List of Customer
                        <button type="submit" class="btn btn-primary" id="send_birthdayWishes_SMS" name="send_birthdayWishes_SMS" style="padding: 5px 10px; float: right;" value="SendSMS">Send SMS</button> 
                        <span id="b_alert" class="required" style="display: none;">*There is no Customer to send SMS</span>
                        </div>
                        <div class="card-body">
                            <table id="customer_birthday_table" class="table custom-table" >
                                <thead>
                                    <tr>
                                        <th width="50">S.No.</th>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>Mobile No</th>
                                        <th>Area</th>
                                        <th>Branch</th>
                                        <th>Line</th>
                                        <th>Group</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </form>
                <!-- Birthday list END -->

                <!-- Festival list START -->
                <div class="card">
                    <div class="card-header">Festival Info
                        <button type="submit" class="btn btn-primary" id="send_festivalWishes_SMS" name="send_festivalWishes_SMS" style="padding: 5px 10px; float: right;" value="SendSMS">Send SMS</button> 

                        <button type="button" class="btn btn-primary" id="add_festival" name="add_festival" data-toggle="modal" data-target=".addFestival" style="padding: 5px 35px; float: right; margin-right: 10px;" tabindex='19'><span class="icon-add"></span></button>
                    </div>
                    <span id="f_alert" class="required" style="display: none;">*There is no Customer to send SMS</span>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group" id="festivalList">
                                    <table class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="50">S.No</th>
                                                <th>Holiday Date</th>
                                                <th>Holiday</th>
                                                <th>Comments</th>
                                                <!-- <th>ACTION</th> -->
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Festival list END -->

                <!-- Due Reminder START -->
                <div class="card">
                    <div class="card-header">Due Reminder</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group" id="due_reminder_table">
                                    <table class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="50">S.No.</th>
                                                <th>Customer ID</th>
                                                <th>Customer Name</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Due Reminder END -->
            </div>
        </div>
    </form>
    <!-- Form end -->

    <!-- Add Festival Details Modal -->
<div class="modal fade addFestival" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Add Festival Info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeFestivalModal()">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert messages -->
				<div id="FestivalInsertNotOk" class="unsuccessalert"> Name Already Exists, Please Enter a Different Name!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FestivalInsertOk" class="successalert">Festival Info Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FestivalUpdateok" class="successalert">Festival Info Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="NotOk" class="unsuccessalert">Please Retry!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FestivalDeleteNotOk" class="unsuccessalert"> Please Retry to Delete Festival Info!
					<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<div id="FestivalDeleteOk" class="unsuccessalert"> Festival Info Has been Deleted!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
				</div>

				<br/>

				<div class="row">

					<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-12">
						<div class="form-group">
							<label class="label"> Holiday Date </label>&nbsp;<span class="text-danger">*</span>
							<input type="date" class="form-control" name="holiday_date" id="holiday_date" tabindex='1'>
							<span class="text-danger" id="holidayDateCheck" style="display: none;">Select Date</span>
						</div>
					</div>

					<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-12">
						<div class="form-group">
							<label for="holiday"> Holiday </label> &nbsp;<span class="text-danger">*</span>
							<input type="text" class="form-control" id="holiday" name="holiday" tabindex='2'>
							<span class="text-danger" id="holidayCheck" style="display: none;">Enter Holiday</span>
						</div>
					</div>

					<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-12">
						<div class="form-group">
							<label class="label" for="holiday_comment"> Comments </label>&nbsp;
							<input type="text" class="form-control" name="holiday_comment" id="holiday_comment" tabindex='3'>
						</div>
					</div>

					<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-12">
						<input type="hidden" name="festivalID" id="festivalID">
						<button type="button" name="submitFestivalInfoBtn" id="submitFestivalInfoBtn" class="btn btn-primary" style="margin-top: 19px;" tabindex='4'>Submit</button>
					</div>

				</div>
				</br>

				<div id="updatedFestivalTable">
					<table class="table custom-table modalTable">
						<thead>
							<tr>
								<th width="50">S.No</th>
								<th>Holiday Date</th>
								<th>Holiday</th>
								<th>Comments</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeFestivalModal()" tabindex='5'>Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END  Add Festival Details Modal -->

</div>
<!-- Main container END -->
