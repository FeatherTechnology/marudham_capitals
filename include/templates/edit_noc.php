<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - NOC
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

				<div class="table-responsive">
					<?php
					$mscid = 0;
					if (isset($_GET['msc'])) {
						$mscid = $_GET['msc'];
						if ($mscid == 1) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> NOC Submitted Successfully! </div>
								<!-- To show print page and assign id value as collection id from collection.php -->
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> NOC Removed Successfully! </div>
							</div>
					<?php
						}
					}
					?>
					<table id="noc_table" class="table custom-table">
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
					<div id="printnocletter" style="display: none"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->


<script>
	function callOnClickEvents() {

		$('.remove-noc').click(function() {
			event.preventDefault();
			let req_id = $(this).data('reqid');
			let cus_id = $(this).data('cusid');
			Swal.fire({
				title: 'Are your sure to remove this NOC?',
				text: 'This action cannot be reverted!',
				icon: 'question',
				showConfirmButton: true,
				showCancelButton: true,
				confirmButtonColor: '#009688',
				cancelButtonColor: '#cc4444',
				cancelButtonText: 'No',
				confirmButtonText: 'Yes'
			}).then(function(result) {
				if (result.isConfirmed) {
					removeNOCFromList(req_id, cus_id);
				}
			})
		})

		function removeNOCFromList(req_id, cus_id) {
			$.ajax({
				url: 'nocFile/removeNOCFromList.php',
				data: {
					'req_id': req_id,
					'cus_id': cus_id
				},
				dataType: 'json',
				type: 'post',
				cache: false,
				success: function(response) {
					if (response.includes('Successfully')) {
						Swal.fire({
							title: 'Removed Successfully!',
							icon: 'success',
							showConfirmButton: false,
							timer: 2000,
							timerProgressBar: true,
						})
						setTimeout(() => {
							window.location = 'edit_noc'
						}, 2000);
					}
				}
			})
		}
		$('.noc-letter').click(function() {
			event.preventDefault();
			let req_id = $(this).data('reqid');
			let cus_id = $(this).data('cusid');
			$.post('nocFile/nocLetter.php', {
				req_id: req_id,
				cus_id: cus_id
			}, function(html) {
				$('#printnocletter').html(html)
			})
		})
	};
</script>