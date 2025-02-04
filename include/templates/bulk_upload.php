<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Bulk Upload
	</div>
</div><br>


<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form name="bulk_upload_form" method="post" enctype="multipart/form-data">
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Bulk Upload</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label for="upload_btn">Upload Excel Here</label>
											<input type="file" class="form-control" id="upload_btn" name="upload_btn" accept=".csv,.xls,.xlsx,.xml">
											<span class="text-danger" style="display: none;" id="upload_btnCheck">Please Upload Excel File </span>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
										<div class="form-group">
											<label style="visibility: hidden;" class="form-control"></label>
											<input type="button" class="btn btn-primary" value="Submit" name="bk_submit" id="bk_submit">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="card">
					<div class="card-header">Bulk Upload Response</div>
					<div class="card-body">
						<div class="row" >
							<div class="col-md-12">
								<div class="row">
									<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="responseCard">
									
									</div>

								</div>
							</div>
						</div>
					</div>
				</div> -->

			</div>
		</div>
	</form>
</div>