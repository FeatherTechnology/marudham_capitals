<?php	
//this screen is only for login page*****
date_default_timezone_set('Asia/Calcutta');
@session_start();

//below code is for redirecting user to dashboard if already logged in, even directly changes url
$userid  = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
if($userid!=""){
	echo "<script>/*alert('Please Dont Change the URL!');*/location.href='dashboard'</script>"; 
}


$id=0;
include "./api/config-file.php";
include "ajaxconfig.php";
$msg="";

/* Log In Start  */

if(isset($_POST['lusername'])) {  

	$username  = $_POST['lusername'];
	$password  =  $_POST['lpassword'];

	$qry     = "SELECT * FROM user WHERE user_name = '".$username."' AND user_password = '".$password."' and status=0"; 
	
	$res = ($connect->query($qry)) or die("Error in Get All Records"); 
	if ($res->rowCount() > 0){  
		$result = $res->fetch();
		$_SESSION['username']    = $result['user_name']; 
		$_SESSION['userid']      = $result['user_id']; 
		$_SESSION['fullname']    = $result['fullname']; 
		$_SESSION['request_list_access']    = $result['request_list_access']; 
		?>
		<script>location.href='<?php echo $HOSTPATH; ?>dashboard';</script>  
		<?php
	}	
	else
	{ 
		$msg="Enter Valid Email Id and Password";
	} 
	// Close the database connection
	$connect = null;
}

?>

		<?php include("include/common/accounthead.php"); ?>
			<form  id="loginform" name="loginform" action="" method="post">
				<div class="row justify-content-md-center">
					<div class="col-xl-5 col-lg-4 col-md-6 col-sm-12">
						<div class="login-screen">
							<div class="login-box">
								<a href="#" class="login-logo">
									<h3 style="color: #009688; position: relative;left: 41px;font-weight: bolder;">MARUDHAM CAPITALS</h3>
									<!-- <img src="img/logo.png" alt="Auction Dashboard" /> -->
								</a>
								<span class="text-danger" id="cinnocheck">
									<!-- <input type="hidden" id="err_msg" value=' -->
									<?php echo $msg;?>
									<!-- '> -->
								</span>
								<h5>Welcome back,<br />Please Login to your Account.</h5>
								<div class="form-group mt-4">
									<label for="lusername">User Name</label>
									<input type="text" name="lusername" id="lusername"  tabindex="1"  class="form-control" value="" placeholder="Enter Email" style="padding: 10px;border-radius:6px;"/>
									<span id="usernamecheck" class="text-danger" style="display:none">Enter Email</span>    
								</div>
								<div class="form-group mt-4">
									<label for="lpassword">Password</label>
									<input type="password" name="lpassword" id="lpassword"  tabindex="2"  class="form-control" value="" placeholder="Enter Password" style="padding: 10px;border-radius:6px;"/>
									<span id="passwordcheck" class="text-danger" style="display:none">Enter Password</span>    
								</div>		
	
								<div class="actions" style="padding-top: 40px;">
									<button type="submit"  id="lbutton"  tabindex="6" name="lbutton" class="form-control btn btn-primary" style="font-size: 1rem;font-weight: bolder;color: white;padding: 10px;border-radius:6px;">Login</button>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</form>

<?php #$current_page = isset($_GET['page']) ? $_GET['page'] : null; ?>
	 
<?php #include("include/common/dashboardfooter.php"); ?>
		