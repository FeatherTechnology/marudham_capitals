$(document).ready(function () {

	{//To Order Alphabetically
		var firstOption = $("#loan_category option:first-child");
		$("#loan_category").html($("#loan_category option:not(:first-child)").sort(function (a, b) {
			return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
		}));
		$("#loan_category").prepend(firstOption);
	}

	//Show Reference No  Field
	// $('#due_type').on('change', function () {

	// 	var due_type = $('#due_type').val();
	// 	if (due_type == 'emi') {
	// 		$("#intrest_method").hide();
	// 		$("#emi_method").show();
	// 	} else if (due_type == 'intrest') {
	// 		$("#emi_method").hide();
	// 		$("#intrest_method").show();
	// 	} else if (due_type == 'emi,intrest') {
	// 		$("#emi_method").show();
	// 		$("#intrest_method").show();
	// 	} else if (due_type == null) {
	// 		$("#emi_method").hide();
	// 		$("#intrest_method").hide();
	// 	}
	// });

	//change sub category based on Loan category
	$('#loan_category').change(function () {
		var loan_cat = $('#loan_category').val();
		getSubCategory(loan_cat);
	})

	// $('#submitloan_calculation').click(function () {
	// 	//Validations
	// 	var due_type = $('#due_type').val();
	// 	if (due_type == 'emi') {
	// 		$("#calculate_method").val('');
	// 	} else if (due_type == 'intrest') {
	// 		$("#profit_method").val('');
	// 	}
	// })
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
			console.log(response);
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
			{//To Order Alphabetically
				var firstOption = $("#loan_category option:first-child");
				$("#loan_category").html($("#loan_category option:not(:first-child)").sort(function (a, b) {
					return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
				}));
				$("#loan_category").prepend(firstOption);
			}
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
			{//To Order Alphabetically
				var firstOption = $("#sub_category option:first-child");
				$("#sub_category").html($("#sub_category option:not(:first-child)").sort(function (a, b) {
					return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
				}));
				$("#sub_category").prepend(firstOption);
			}
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