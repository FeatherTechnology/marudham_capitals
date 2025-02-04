<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Concern Creation
	</div>
</div><br>

<?php
@session_start();
include('ajaxconfig.php');

if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$userQry = $connect->query("SELECT 1 FROM USER WHERE user_id = '$userid' && role ='3'"); // Check Whether the user is staff or not ,if not means concern screen will not be show.
$rowuser = $userQry->rowCount();
if ($rowuser > 0) {
?>

	<div class="text-right" style="margin-right: 25px;">
		<a href="concern_creation">
			<button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Concern Creation</button>
		</a>
	</div><br><br>
	<!-- Page header end -->

	<!-- Main container start -->
	<div class="main-container">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="table-container">

					<div class="table-responsive">
						<?php
						$mscid = 0;
						$id = 0;
						if (isset($_GET['msc'])) {
							$mscid = $_GET['msc'];
							if ($mscid == 1) { ?>
								<div class="alert alert-success" role="alert">
									<div class="alert-text"> Concern Submitted Successfully! </div>
								</div>
							<?php
							}
							if ($mscid == 2) { ?>
								<div class="alert alert-success" role="alert">
									<div class="alert-text"> Collection Removed Successfully! </div>
								</div>
						<?php
							}
						}
						?>
						<table id="concern_table" class="table custom-table">
							<thead>
								<tr>
									<th width="50">S.No.</th>
									<th>Concern Code</th>
									<th>Concern Date</th>
									<th>Branch Name</th>
									<th>Staff Assign</th>
									<th>Subject</th>
									<th>Status</th>
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
<?php } else { ?>

	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header" style="text-align: center;"> </div>
			<div class="card-body">
				<div class="row">

					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<h4 style="display: flex; justify-content: center; align-items: center; font-weight: bold;"> Concern Creation is only for Staffs </h4>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

<?php } 
// Close the database connection
$connect = null;
?>