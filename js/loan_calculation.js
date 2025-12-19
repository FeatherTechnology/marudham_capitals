$(document).ready(function () {

	// Sort loan_category dropdown
    sortDropdownAlphabetically("#loan_category");

	// $('#due_type').on('change', function () {
	// 	var due_type = $(this).val();

	// 	if (due_type == 'emi') {
	// 		$(".intrest_method").hide();
	// 		$(".emi_method").show();
	// 		$('#overdue').val("");
	// 	} else if (due_type == 'intrest') {
	// 		$(".intrest_method").show();
	// 		$(".emi_method").hide();
	// 		$('#overdue').val("");
	// 	} else {
	// 		$(".intrest_method").hide();
	// 		$(".emi_method").hide();
	// 		$('#overdue').val("");
	// 	}
	// });

	//change sub category based on Loan category
	$('#loan_category').change(function () {
		var loan_cat = $('#loan_category').val();
		getSubCategory(loan_cat);
	})

	$('#submitloan_calculation').click(function (e) {
		var due_type = $('#due_type').val();
		var isValid = true;

		if (due_type === 'intrest') { // corrected spelling
			// Validate calculate method
			$('#profit_method').prop('required', false);

			if ($("#calculate_method").val() === '') {
				swalError("Warning", "Please select Calculate Method");
				$("#calculate_method").focus();
				isValid = false;
			}

			// Validate overdue type radio buttons
			var overdueType = $("input[name='overdue_type']:checked").val();
			if (!overdueType) {
				swalError("Warning", "Please select Overdue Penalty type (₹ or %)");
				isValid = false;
			}

		} else if (due_type === 'emi') {
			// Validate overdue % field
			$('#profit_method').prop('required', true);

			if ($("#overdue").val() === '') {
				swalError("Warning", "Please enter Overdue Penalty %");
				$("#overdue").focus();
				isValid = false;
			}

			// Clear Interest-specific fields only if form is valid
			if (isValid) {
				$("#calculate_method").val('');
				$("input[name='overdue_type']").prop('checked', false);
			}
		}

		// Prevent form submit if not valid
		if (!isValid) {
			e.preventDefault();
			return false;
		}else{
		let confirmAction = confirm("Are you sure you want to submit Loan Calculation ?");
        if (!confirmAction) {
            event.preventDefault(); // Stop form submission if canceled
            return false;
        }
	}
	});

	$(' #docamt,#docpercentage').click(function () {
		var doc_charge_type = $('input[name=doc_charge_type]:checked').val();
		if (doc_charge_type == 'amt') {
			changeAmtinput('docmin', 'docmax', 'document_charge_min', 'document_charge_max');
		}
		if (doc_charge_type == 'percentage') {
			changePercentinput('docmin', 'docmax', 'document_charge_min', 'document_charge_max');
		}
	})

	// Amount or percentage change on fields
	$('#procamt,#procpercentage').click(function () {
		var proc_fee_type = $('input[name=proc_fee_type]:checked').val();
		if (proc_fee_type == 'amt') {
			changeAmtinput('procmin', 'procmax', 'processing_fee_min', 'processing_fee_max');
		}
		if (proc_fee_type == 'percentage') {
			changePercentinput('procmin', 'procmax', 'processing_fee_min', 'processing_fee_max');
		}
	})
});

//on page load for Edit
$(function () {
	getLoanCategory();
	var loan_id_upd = $('#loan_id_upd').val()
	var doc_charge_type = $('#doc_charge_type_upd').val();
	var proc_fee_type = $('#pro_fees_type_upd').val();
	if (loan_id_upd > 0) {
		var loan_category_upd = $('#loan_category_upd').val()
		getSubCategory(loan_category_upd);
	}

	if (doc_charge_type == 'amt') {
		changeAmtinput('docmin', 'docmax', 'document_charge_min', 'document_charge_max');
	} else if (doc_charge_type == 'percentage') {
		changePercentinput('docmin', 'docmax', 'document_charge_min', 'document_charge_max');
	}

	//for processing fee
	if (proc_fee_type == 'amt') {
		changeAmtinput('procmin', 'procmax', 'processing_fee_min', 'processing_fee_max');
	} else if (proc_fee_type == 'percentage') {
		changePercentinput('procmin', 'procmax', 'processing_fee_min', 'processing_fee_max');
	}
})

// function toggleOverdueField() {
// 	var dueType = $('#due_type').val();

// 	if (dueType === 'emi') {
// 		$('.interest_only').hide();    // hide radio buttons
// 		$('#emi_symbol').show();       // show % symbol in label
// 	} else if (dueType === 'intrest') {
// 		$('.interest_only').show();    // show radio buttons
// 		$('#emi_symbol').hide();       // hide % symbol
// 	} else {
// 		$('.intrest_only').hide();
// 		$('#emi_symbol').hide();
// 	}
// }

// toggleOverdueField(); // run on page load

// $('#due_type').change(function () {
// 	toggleOverdueField();
// });


//Fetch Loan Category Based on loan category
function getLoanCategory() {
	var loan_category_upd = $('#loan_category_upd').val()
	$.ajax({
		url: 'loanCalculationFile/getLoanCategory.php',
		type: 'POST',
		dataType: 'json',
		cache: false,
		data: {},
		success: function (response) {
			$('#loan_category').empty();
			$('#loan_category').append(`<option value=''>Select Loan Category</option>`);
			for (var i = 0; i < response.length; i++) {
				if (response[i]['loan_category_name_id'] != '' && response[i]['loan_category_name_id'] != null) {
					var selected = '';
					if (loan_category_upd == response[i]['loan_category_name_id']) {
						selected = 'selected';
						response[i]['disabled'] = '';
					}
					$('#loan_category').append("<option value= '" + response[i]['loan_category_name_id'] + "' " + selected + " " + response[i]['disabled'] + " > " + response[i]['loan_category_name'] + " </option>")
				}
			}
			// Sort loan_category dropdown
            sortDropdownAlphabetically("#loan_category");
		}
	})
}

//Fetch Sub Category Based on loan category
function getSubCategory(loan_cat) {
	var sub_category_upd = $('#sub_category_upd').val()
	$.ajax({
		url: 'loanCalculationFile/getLoanSubCategory.php',
		type: 'POST',
		dataType: 'json',
		cache: false,
		data: { 'loan_cat': loan_cat },
		success: function (response) {
			$('#sub_category').empty();
			$('#sub_category').append(`<option value=''>Select Sub Category</option>`);
			for (var i = 0; i < response.length; i++) {
				if (response[i]['sub_category_name'] != '' && response[i]['sub_category_name'] != null) {
					var selected = '';
					if (sub_category_upd == response[i]['sub_category_name']) {
						selected = 'selected';
						response[i]['disabled'] = '';
					}
					$('#sub_category').append("<option value= '" + response[i]['sub_category_name'] + "' " + selected + " " + response[i]['disabled'] + " > " + response[i]['sub_category_name'] + " </option>")
				}
			}
			// Sort sub_category dropdown
            sortDropdownAlphabetically("#sub_category");
		}
	})
}

//Change Document charge & Processing fee input field not readonly
function changeAmtinput(docmin, docmax, document_charge_min, document_charge_max) {
	$('#' + docmin).text('Min ₹');
	$('#' + docmax).text('Max ₹');
	$('#' + document_charge_min).attr('readonly', false);
	$('#' + document_charge_max).attr('readonly', false);
}
//Change Document charge & Processing fee input field not readonly
function changePercentinput(docmin, docmax, document_charge_min, document_charge_max) {
	$('#' + docmin).text('Min %');
	$('#' + docmax).text('Max %');
	$('#' + document_charge_min).attr('readonly', false);
	$('#' + document_charge_max).attr('readonly', false);
}

function swalError(title, text) {
	Swal.fire({
		icon: 'warning',
		title: title,
		text: text,
		confirmButtonColor: '#009688',
	});
}