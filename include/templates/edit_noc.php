<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - NOC
	</div>
</div><br>

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
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="branch_filter">Branch</label>
							<select class="" id="branch_filter" name="branch_filter" multiple>
								<option value=''>Select Branch name</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<label for="sector_filter" id="sector_label">Sector</label>
							<select id="sector_filter" name="sector_filter" multiple>
								<option value="">Select Sector</option>
							</select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
							<button type="button" class="btn btn-primary" id="search_loan" style="margin-top:20px;">Search</button>
						</div>
					</div>
					<hr>
					<table id="noc_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Closed Date</th>
								<th>Aadhaar Number</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Region</th>
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
						<tbody></tbody>
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
	$(function() {
		loadNotifications();
	})

	$(document).ready(function() {
		const branchChoices = new Choices('#branch_filter', {
			removeItemButton: true,
			noChoicesText: 'No branches available',
			allowHTML: true,
		});
		const sectorChoices = new Choices('#sector_filter', {
			removeItemButton: true,
			noChoicesText: 'No sector available',
			allowHTML: true,
		});

		 setSectorLabel('noc');

		$('#search_loan').on('click', function() {

			let branch = $("#branch_filter").val();
			let sector = $("#sector_filter").val();

			if ((!branch || branch.length === 0) && (!sector || sector.length === 0)) {
				swalError('Warning', 'Please select at least one filter');
				return;
			}

			$('#noc_table').DataTable().ajax.reload();
		});
		$('#branch_filter').on('change', function() {
			let branch = $(this).val();

			getSectorDropdown('noc', branch);
		});

		// load each dropdown only when the user actually opens/clicks it.
		let branchLoaded = false;
		let sectorLoaded = false;

		branchChoices.passedElement.element.addEventListener('showDropdown', function() {
			if (!branchLoaded) {
				branchLoaded = true;
				getBranchDropdown();
			}
		});

		sectorChoices.passedElement.element.addEventListener('showDropdown', function() {
			if (!sectorLoaded) {
				sectorLoaded = true;
				getSectorDropdown('noc');
			}
		});

		$(document).on('click', '.remove-noc', function(event) {
			event.preventDefault();
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
					removeNOCFromList(cus_id);
				}
			});
		});

		function removeNOCFromList(cus_id) {
			$.ajax({
				url: 'nocFile/removeNOCFromList.php',
				data: {
					cus_id
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
							if (result.isConfirmed) {
								window.location = 'edit_noc';
							}
						});
					}
				}
			});
		}

		function getBranchDropdown() {
			$.post('common_files/user_mapped_branches.php', {}, function(response) {
				branchChoices.clearStore();
				$.each(response, function(index, val) {
					let items = [{
						value: val.branch_id,
						label: val.branch_name,
					}];
					branchChoices.setChoices(items); // Add choices

				});
			}, 'json');
		}

		function getSectorDropdown(module, branch = []) {
			sectorChoices.clearStore();
			$.ajax({
				url: 'common_files/get_sector_name.php',
				type: 'POST',
				data: {
					module: module,
					branch: branch
				},
				dataType: 'json',
				success: function(response) {

					let items = [];

					$.each(response, function(i, val) {
						items.push({
							value: val.id,
							label: val.name
						});
					});

					sectorChoices.setChoices(items, 'value', 'label', true);
				}
			});
		}


		function setSectorLabel(screen) {
			$.ajax({
				url: 'common_files/get_label.php',
				type: 'POST',
				data: {
					screen: screen
				},
				dataType: 'json',
				success: function(response) {
					// Update the label text
					$('#sector_label').text(response.label);

					// Update underlying select option (for consistency)
					$('#sector_filter option:first').text('Select ' + response.label);

					// Update the Choices.js placeholder input
					const placeholderText = 'Select ' + response.label;

					// For Choices.js, set placeholder on the search input/container:
					const input = sectorChoices.input.element;
					if (input) {
						input.placeholder = placeholderText;
					}
				}
			});
		}
	});
</script>