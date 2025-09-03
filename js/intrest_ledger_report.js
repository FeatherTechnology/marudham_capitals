$(document).ready(function () {

    //Closed Report Table
    $('#ledger_view_report_btn').click(function () {
        let toDate = $('#to_date').val();
        let loan_category = $('#loan_category').val();

        if (!toDate || !loan_category) {
            swalError('Missing Fields', 'Both Date and Loan Category are required.');
            return;
        }

        url = 'api/report_files/get_ledger_view_report.php';

        $.ajax({
            type: "POST",
            url: url,
            data: { toDate: toDate, loan_category: loan_category },
            success: function (data) {
                $('.reportDiv').empty().html(data);
            }
        });
    });

    // <------------------------------------------------------------------------ Due Chart Start --------------------------------------------------------------->

    $(document).on('click', '.due-chart', async function () {

        var cus_id = $(this).data('cusid');
        var le_id = $(this).data('loanid');

        // Wait for chart to be rendered
        await dueChartList(le_id, cus_id);

        // Bind print click event after content is loaded
        $('.print_due_coll').off('click').on('click', async function () {
            const collection_id = $(this).attr('value');
            const result = await Swal.fire({
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
            });

            if (result.isConfirmed) {
                try {
                    const html = await new Promise((resolve, reject) => {
                        $.ajax({
                            url: 'api/collection_files/print_collection.php',
                            data: { 'collection_id': collection_id },
                            type: 'POST',
                            cache: false,
                            success: resolve,
                            error: reject
                        });
                    });

                    $('#printcollection').html(html);
                    const content = $("#printcollection").html(); // Optional usage
                } catch (err) {
                    console.error('Print load failed:', err);
                }
            }
        });
    });


    $(document).on('click', '.due-chart', function () {
        $('#due_chart_model').modal('show');
    });

});

// <------------------------------------------------------------------------ Due Chart Function Start --------------------------------------------------------------->

function dueChartList(le_id, cus_id) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'api/collection_files/get_due_chart_list.php',
            data: { 'le_id': le_id, 'cus_id': cus_id },
            type: 'POST',
            cache: false,
            success: function (response) {
                $('#due_chart_table_div').empty().html(response);

                // Now get the method name
                $.post('api/collection_files/get_due_method_name.php', { le_id }, function (res) {
                    $('#dueChartTitle').text('Due Chart ( ' + res['cus_name'] + ' - ' + res['cus_id'] + ' - ' + res['loan_id'] + ' - ' + res['due_method'] + ' - ' + res['loan_type'] + ' )');
                    resolve();
                }, 'json').fail(reject);
            },
            error: reject
        });
    });
}

function closeChartsModal() {
    $('#due_chart_model').modal('hide');
}

// <------------------------------------------------------------------------ Loan Category Function Start --------------------------------------------------------------->

$(function () {
    getLoanCategoryDropdown();
});

function getLoanCategoryDropdown() {
    return new Promise((resolve, reject) => {
        $.post('loanCalculationFile/getLoanCategory.php', function (response) {

            let loan_category2 = $('#loan_category2').val();
            $('#loan_category').empty();
			$('#loan_category').append(`<option value=''>Select Loan Category</option>`);
			for (var i = 0; i < response.length; i++) {
				if (response[i]['loan_category_name_id'] != '' && response[i]['loan_category_name_id'] != null) {
					var selected = '';
					if (loan_category2 == response[i]['loan_category_name_id']) {
						selected = 'selected';
					}
					$('#loan_category').append("<option value= '" + response[i]['loan_category_name_id'] + "' " + selected + " > " + response[i]['loan_category_name'] + " </option>")
				}
			}
			{//To Order Alphabetically
				var firstOption = $("#loan_category option:first-child");
				$("#loan_category").html($("#loan_category option:not(:first-child)").sort(function (a, b) {
					return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
				}));
				$("#loan_category").prepend(firstOption);
			}
        }, 'json').fail(function (err) {
            reject(err); // Reject on error
        });
    });
}