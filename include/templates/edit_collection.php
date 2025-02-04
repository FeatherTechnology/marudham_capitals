<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Collection
	</div>
</div><br>
<!-- <div class="text-right" style="margin-right: 25px;">
    <a href="verification">
        <button type="button" class="btn btn-primary"><span class="icon-add"></span>&nbsp; Add verification</button>
    </a>
</div><br><br> -->
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container">
				<div class="row">
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 sub_status">

						<label for="sub_status_mapping">Customer Status</label><span class="required">&nbsp;*</span>
						<input type="hidden" class="" id="customer_status" name="customer_status" value="<?php echo isset($_GET['CustomerStatus']) ? $_GET['CustomerStatus'] : '';?>">
						<select tabindex="10" type="text" class="form-control" id="sub_status_mapping" name="sub_status_mapping" multiple>
							<option value="">Select Customer Status</option>
						</select>
						<span class='text-danger subStatusCheck' style="display:none">Please Select Customer Status </span>

					</div>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 sub_status" style="margin-top:24px">
						<button type="button" class="btn btn-primary" id="get_cus_sts_btn">Proceed</button>
					</div>
				</div>

				<div class="table-responsive" style="display:none">
					<?php
					$mscid = 0;
					$id = 0;
					if (isset($_GET['msc'])) {
						$mscid = $_GET['msc'];
						$id = $_GET['id'];
						if ($mscid == 1 and $id != '') { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Collection Submitted Successfully! </div>
								<!-- To show print page and assign id value as collection id from collection.php -->
								<input type="hidden" id='id' name='id' value=<?php echo $id; ?>>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Collection Removed Successfully! </div>
							</div>
						<?php
						}
					} else { //for print page not to show define id as 0
						?>
						<input type="hidden" id='id' name='id' value=<?php echo $id; ?>>
					<?php
					}
					?>
					<table id="collection_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Line</th>
								<th>Mobile</th>
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
<div id="printcollection" style="display: none"></div>


<script>
	var id = $('#id').val();
	if (id != 0) {
		setTimeout(() => {
			Swal.fire({
				title: 'Print',
				text: 'Do you want to print this collection?',
				imageUrl: 'img/printer.png',
				imageWidth: 300,
				imageHeight: 210,
				imageAlt: 'Custom image',
				showCancelButton: true,
				confirmButtonColor: '#009688',
				cancelButtonColor: '#d33',
				cancelButtonText: 'No',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: 'collectionFile/print_collection.php',
						data: {
							'coll_id': id
						},
						type: 'post',
						cache: false,
						success: function(html) {
							$('#printcollection').html(html)
							// Get the content of the div element
							var content = $("#printcollection").html();

							// Create a new window
							var w = window.open();

							// Write the content to the new window
							$(w.document.body).html(content);

							// Print the new window
							w.print();

							// Close the new window
							w.close();
						}
					})
				}
			})
		}, 2000)
	}
</script>