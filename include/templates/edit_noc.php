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
	 <input type="hidden" id="pending_sts">
<input type="hidden" id="od_sts">
<input type="hidden" id="due_nil_sts">
<input type="hidden" id="closed_sts">
<input type="hidden" id="bal_amt">
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
								<th>NOC Date</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Line</th>
								<th>Mobile</th>
								<th>Receive Status</th>
								<th>Customer Status</th>
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

<!-- Add Course Category Modal -->
<div class="modal fade customerstatus" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="myLargeModalLabel">Customer Status</h5>
				<button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<br />
				<div class="row">
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
						<div class="form-group">
							<input type="hidden" name="req_id" id="req_id">
							<!-- <label class="label">Existing Type</label>
							<input type="text" name="exist_type" id="exist_type" class="form-control" readonly > -->
						</div>
					</div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12"></div>
				</div>
				<div id="updatedcusHistoryTable">
					<table class="table custom-table" id="cusHistoryTable">
						<thead>
							<tr>
								<th width="25">S. No</th>
								<th>Date</th>
								<th>Loan Category</th>
								<th>Sub Category</th>
								<th>Amount</th>
								<th>Status</th>
								<th>Sub Status</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
	function callOnClickEvents() {

		$('.remove-noc').click(function() {
			event.preventDefault();
			let req_id = $(this).data('reqid');
			let cus_id = $(this).data('cusid');
			Swal.fire({
				title: 'Are your sure to send this NOC to NOC Handover?',
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
							title: 'Send Successfully!',
							icon: 'success',
							showConfirmButton: true,
							confirmButtonColor: '#009688',
							confirmButtonText: 'OK'
						}).then((result) => {
							// Redirect only when OK is clicked
							if (result.isConfirmed) {
								window.location = 'edit_noc';
							}
						});
					}
				}
			})
		}
	
		$('a.customer-status').click(async function() {
			try {
				var cus_id = $(this).data('value');
				var req_id = $(this).data('value1');
				showOverlay();

				// Wait here until the function COMPLETES
				let status = await callresetCustomerStatus(cus_id);

				let {
					pending_sts,
					od_sts,
					due_nil_sts,
					closed_sts,
					bal_amt
				} = status;

				$.ajax({
					url: 'requestFile/getCustomerStatus.php',
					type: 'POST',
					data: {
						cus_id,
						pending_sts,
						od_sts,
						due_nil_sts,
						closed_sts,
						bal_amt
					},
					cache: false,
					success: function(response) {
						$('#cusHistoryTable').empty().html(response);

						$('#cusHistoryTable tbody tr').each(function() {
							var val = $(this).find('td:nth-child(6)').text().trim();

							if (['Request', 'Verification', 'Approval', 'Acknowledgement', 'Issue'].includes(val)) {
								$(this).find('td:nth-child(6)').css({
									backgroundColor: 'rgba(240,0,0,0.8)',
									color: 'white',
									fontWeight: 'bolder'
								});
							} else if (val === 'Present') {
								$(this).find('td:nth-child(6)').css({
									backgroundColor: 'rgba(0,160,0,0.8)',
									color: 'white',
									fontWeight: 'bolder'
								});
							} else if (val === 'Closed') {
								$(this).find('td:nth-child(6)').css({
									backgroundColor: 'rgba(0,0,255,0.8)',
									color: 'white',
									fontWeight: 'bolder'
								});
							}
						});
					},
					complete: function() {
						hideOverlay();
					}
				});

			} catch (err) {
				console.error(err);
				hideOverlay();
			}
		});

		function callresetCustomerStatus(cus_id) {
			//To get loan sub Status
			return new Promise((resolve, reject) => {
				$.ajax({
					url: 'collectionFile/resetCustomerStatus.php',
					type: 'POST',
					data: {
						'cus_id': cus_id
					},
					dataType: 'json',
					cache: false,
					success: function(response) {
						if (!response || response.length == 0) {
							resolve({
								pending_sts: "",
								od_sts: "",
								due_nil_sts: "",
								closed_sts: "",
								bal_amt: ""
							});
							return;
						}

						let pending_arr = response['pending_customer'] || [];
						let od_arr = response['od_customer'] || [];
						let due_nil_arr = response['due_nil_customer'] || [];
						let closed_arr = response['closed_customer'] || [];
						let balance_arr = response['balAmnt'] || [];

						let pending_sts = pending_arr.join(',');
						let od_sts = od_arr.join(',');
						let due_nil_sts = due_nil_arr.join(',');
						let closed_sts = closed_arr.join(',');
						let bal_amt = balance_arr.join(',');

						$('#pending_sts').val(pending_sts);
						$('#od_sts').val(od_sts);
						$('#due_nil_sts').val(due_nil_sts);
						$('#closed_sts').val(closed_sts);
						$('#bal_amt').val(bal_amt);

						resolve({
							pending_sts,
							od_sts,
							due_nil_sts,
							closed_sts,
							bal_amt
						});
					},
					error: function(xhr, status, error) {
						reject(error);
					}
				});
			});
		}

	};
</script>