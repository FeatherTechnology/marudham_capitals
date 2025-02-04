<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals -  Bank Clearance List 
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
    <a href="bank_clearance">
        <button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add Bank Clearance</button>
    <!-- <button type="button" class="btn btn-primary"><span class="icon-border_color"></span>&nbsp Edit Employee Master</button> -->
    </a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" >
			<div class="card">
				<div class="card-header">
					<div class="card-title">Transaction Details</div>
				</div>
				<div class="card-body">
					<div class="row ">
						<!--Fields -->
						<div class="col-12"> 
							
							<div class="row">
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
									<div class="form-group">
										<label for='bank_name'>Bank Name</label><span class="text-danger">&nbsp;*</span>
										<select id='bank_name' name='bank_name' class="form-control" tabindex='1'>
											<option value="">Select Bank Name</option>
										</select>
										<span class="text-danger" style='display:none' id='bank_nameCheck'>Please Select Bank Name</span>
									</div>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
									<div class="form-group">
										<label for='from_date'>From Date</label><span class="text-danger">&nbsp;*</span>
										<input type='date' id='from_date' name='from_date' class="form-control" tabindex='2'>
										<span class="text-danger" style='display:none' id='from_dateCheck'>Please Select From Date</span>
									</div>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
									<div class="form-group">
										<label for='to_date'>To Date</label><span class="text-danger">&nbsp;*</span>
										<input type='date' id='to_date' name='to_date' class="form-control" tabindex='3'>
										<span class="text-danger" style='display:none' id='to_dateCheck'>Please Select To Date</span>
									</div>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
									<div class="form-group">
										<label for='to_date' style="visibility:hidden">To Date</label><br>
										<button type="button" name="view_table" id="view_table" class="btn btn-primary" tabindex="4">View</button>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 bank_clr_table" style="display:none">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Bank Statement</div>
				</div>
				<div class="card-body">
					<div class="row ">
						<!-- <div class="col-9 "></div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 ">
							<div class="form-group">
								<label for='search_table'>Search</label>
								<input type="search" class="form-control" id='search_table' name='search_table' >
							</div>
						</div> -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="table-container">

								<div class="table-responsive">
									<?php
									$mscid=0;
									if(isset($_GET['msc']))
									{
									$mscid=$_GET['msc'];
									if($mscid==1)
									{?>
									<div class="alert alert-success" role="alert">
										<div class="alert-text">Bank Clearance Added Successfully!</div>
									</div> 
									<?php
									}
									if($mscid==2)
									{?>
										<div class="alert alert-success" role="alert">
										<div class="alert-text">Bank Clearance Updated Successfully!</div>
									</div>
									<?php
									}
									if($mscid==3)
									{?>
									<div class="alert alert-danger" role="alert">
										<div class="alert-text">Bank Clearance Inactive Successfully!</div>
									</div>
									<?php
									}
									}
									?>
									<table id="bank_clearance_list" class="table custom-table">
										
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->

	

<script>
	var sortOrder = 1; // 1 for ascending, -1 for descending

	document.querySelectorAll('th').forEach(function(th) {
	th.addEventListener('click', function() {
		var columnIndex = this.cellIndex;
		document.querySelector('tbody').innerHTML = '';
		dT();
		setTimeout(function() {
		var tableRows = Array.prototype.slice.call(document.querySelectorAll('tbody tr'));

		tableRows.sort(function(a, b) {
			var textA = a.querySelectorAll('td')[columnIndex].textContent.toUpperCase();
			var textB = b.querySelectorAll('td')[columnIndex].textContent.toUpperCase();

			if (textA < textB) {
			return -1 * sortOrder;
			}
			if (textA > textB) {
			return 1 * sortOrder;
			}
			return 0;
		});

		tableRows.forEach(function(row) {
			document.querySelector('tbody').appendChild(row);
		});

		sortOrder = -1 * sortOrder;

		// update the serial numbers
		document.querySelectorAll('tbody tr').forEach(function(row, index) {
			row.querySelectorAll('td')[0].textContent = index + 1;
		});
		}, 1000);
	});
	});


</script>
