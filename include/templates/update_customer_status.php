<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Update Customer Status
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<input type="hidden" id="upd_value" value='<?php if(isset($_GET['upd_value'])) echo $_GET['upd_value'];?>'>
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            
			<div class="card" id="request_card" >
				<div class="card-header" id="req_title">
					<div class="card-title" style="display:flex;justify-content:center;align-items: center;font-size:1.5rem;cursor:pointer">Customer Status</div>
				</div>
				<div class="card-body" id="req_body">
					<div class="row cards-row" style="display:flex;justify-content:center;">
						<div class="col-sm-12 col-md-6 col-lg-4 col-xl-1">
							<button class="btn btn-primary" id="update_sts_btn">Update Status</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->