<?php 
@session_start();
if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
}

$id=0;
$typeofaccount;
$companyName = $userObj->getCompanyName($mysqli);
if(isset($_POST['submit_director_creation']) && $_POST['submit_director_creation'] != '')
{
    if(isset($_POST['id']) && $_POST['id'] >0 && is_numeric($_POST['id'])){		
        $id = $_POST['id']; 	
		$userObj->updateDirectorCreation($mysqli,$id, $userid);  
    ?>
	<script>location.href='<?php echo $HOSTPATH;  ?>edit_director_creation&msc=2';</script>
    <?php	}
    else{   
		$userObj->addDirectorCreation($mysqli, $userid);   
        ?>
    <script>location.href='<?php echo $HOSTPATH;  ?>edit_director_creation&msc=1';</script>
        <?php
    }
}

$del=0;
$costcenter=0;
if(isset($_GET['del']))
{
$del=$_GET['del'];
}
if($del>0)
{
	$userObj->deleteDirectorCreation($mysqli,$del, $userid); 
	//die;
	?>
	<script>location.href='<?php echo $HOSTPATH;  ?>edit_director_creation&msc=3';</script>
<?php	
}

if(isset($_GET['upd']))
{
$idupd=$_GET['upd'];
}
$status =0;
if($idupd>0)
{
	$getDirectorCreation = $userObj->getDirectorCreation($mysqli,$idupd); 
	// print_r($getDirectorCreation);die;
	if (sizeof($getDirectorCreation)>0) {
        for($i=0;$i<sizeof($getDirectorCreation);$i++)  {			
			$dir_id						= $getDirectorCreation['dir_id'];
			$dir_code					= $getDirectorCreation['dir_code'];
			$dir_name					= $getDirectorCreation['dir_name'];
			$dir_type					= $getDirectorCreation['dir_type'];
			$company_id					= $getDirectorCreation['company_id'];
			$branch_id					= $getDirectorCreation['branch_id'];
			$address1					= $getDirectorCreation['address1'];
			$address2					= $getDirectorCreation['address2'];
			$state						= $getDirectorCreation['state'];
			$district					= $getDirectorCreation['district'];
			$taluk						= $getDirectorCreation['taluk'];
			$place						= $getDirectorCreation['place'];
			$pincode					= $getDirectorCreation['pincode'];
			$mail_id					= $getDirectorCreation['mail_id'];
			$mobile_no					= $getDirectorCreation['mobile_no'];
			$whatsapp_no				= $getDirectorCreation['whatsapp_no'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals -  Director Creation 
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
    <a href="edit_director_creation">
        <button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
    <!-- <button type="button" class="btn btn-primary"><span class="icon-border_color"></span>&nbsp Edit Employee Master</button> -->
    </a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id = "director_creation" name="director_creation" action="" method="post" enctype="multipart/form-data"> 
		<input type="hidden" class="form-control" value="<?php if(isset($idupd)) echo $idupd; ?>"  id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($dir_id)) echo $dir_id; ?>"  id="dir_id_upd" name="dir_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($company_id)) echo $company_id; ?>"  id="company_id_upd" name="company_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($branch_id)) echo $branch_id; ?>"  id="branch_id_upd" name="branch_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($state)) echo $state; ?>"  id="state_upd" name="state_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($district)) echo $district; ?>"  id="district_upd" name="district_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($taluk)) echo $taluk; ?>"  id="taluk_upd" name="taluk_upd" aria-describedby="id" placeholder="Enter id">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">General Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 "> 
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Company Name</label>&nbsp;<span class="text-danger">*</span>
												<input type="hidden" id='company_id' name="company_id" value='<?php echo $companyName[0]['company_id'] ?>' >
												<input type="text" class="form-control" id='company_id1' name="company_id1" value='<?php echo $companyName[0]['company_name'] ?>' readonly tabindex='1'>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group" >
											<label for="disabledInput">Director Type</label>&nbsp;<span class="text-danger">*</span>
											<select  class='form-control' type="text" id="dir_type" name="dir_type" tabindex="1">
												<option value="">Select Director Type</option>
												<option value="1" <?php if(isset($dir_type) and $dir_type == '1' ) echo 'selected';?> >Director</option>
												<option value="2" <?php if(isset($dir_type) and $dir_type == '2' ) echo 'selected';?> >Executive Director</option>
											</select>
										</div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Director ID</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="dir_code" name="dir_code" value="<?php if(isset($dir_code)) echo $dir_code; ?>"  readonly tabindex="2">
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Director Name</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="dir_name" name="dir_name" value="<?php if(isset($dir_name)) echo $dir_name; ?>"placeholder="Enter Director Name" pattern="[a-zA-Z\s]+" tabindex="3">
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Address</label>
                                            <input type="text" class="form-control" id="address1" name="address1" value="<?php if(isset($address1)) echo $address1; ?>" placeholder="Enter Address" tabindex="4">
                                        </div>
                                    </div>
									<!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Address Line 2</label>
                                            <input type="text" class="form-control" id="address2" name="address2" value="<?php if(isset($address2)) echo $address2; ?>" placeholder="Enter Address Line 2"  tabindex="5">
                                        </div>
                                    </div> -->
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">State</label>&nbsp;<span class="text-danger">*</span>
                                            <select type="text" class="form-control" id="state" name="state" tabindex="6" >
												<option value="SelectState">Select State</option>
												<option value="TamilNadu" <?php if(isset($state) && $state == 'TamilNadu') echo 'selected' ?>>Tamil Nadu</option>
												<option value="Puducherry"  <?php if(isset($state) && $state == 'Puducherry') echo 'selected' ?> >Puducherry</option>
											</select>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
											<label for="disabledInput">District</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="hidden" class="form-control" id="district1" name="district1" >
                                            <select type="text" class="form-control" id="district" name="district" tabindex="7">
												<option value="Select District">Select District</option>
											</select>	
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Taluk</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="hidden" class="form-control" id="taluk1" name="taluk1" >
                                            <select type="text" class="form-control" id="taluk" name="taluk" tabindex="8">
												<option value="Select Taluk">Select Taluk</option>
											</select>
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Place</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="place" name="place" value="<?php if(isset($place)) echo $place; ?>" pattern="[a-zA-Z\s]+" placeholder="Enter Place" tabindex="9">
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Pincode</label>&nbsp;<span class="text-danger">*</span>
                                            <input type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control" id="pincode" name="pincode" value="<?php if(isset($pincode)) echo $pincode; ?>" placeholder="Enter Pincode" tabindex="10">
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Communication Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Mail ID</label>
                                            <input type="email" class="form-control" id="mailid" name="mailid" value="<?php if(isset($mail_id)) echo $mail_id; ?>" placeholder="Enter Mail ID" tabindex="11">
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Mobile No.</label>
                                            <input type="number" class="form-control" id="mobile" name="mobile" value="<?php if(isset($mobile_no)) echo $mobile_no; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="12">
                                        </div>
                                    </div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="disabledInput">Whatsapp No.</label>
                                            <input type="number" class="form-control" id="whatsapp" name="whatsapp" value="<?php if(isset($whatsapp_no)) echo $whatsapp_no; ?>" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Whatsapp Number" tabindex="13">
                                        </div>
                                    </div>	
								</div>
							</div>
						</div> 
					</div>
				</div>
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_director_creation" id="submit_director_creation" class="btn btn-primary" value="Submit" tabindex="14"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="15" >Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>


