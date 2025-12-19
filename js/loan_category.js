// Document is ready
$(document).ready(function () {

    // remove delete option for last child
    $('#delete_row:last').filter(':last').removeClass("deleterow");

    // Sort loan_category_name dropdown
    sortDropdownAlphabetically("#loan_category_name");

    // Modal Box for Category Name
    {
        $("#loancategorynameCheck").hide();
        $(document).on("click", "#submitLoanCategoryModal", function () {
            var loan_category_creation_id = $("#loan_category_creation_id").val();
            var loan_category_creation_name = $("#loan_category_creation_name").val();
            if (loan_category_creation_name != "") {
                $.ajax({
                    url: 'loancategoryFile/ajaxInsertLoanCategory.php',
                    type: 'POST',
                    data: { "loan_category_creation_name": loan_category_creation_name, "loan_category_creation_id": loan_category_creation_id },
                    cache: false,
                    success: function (response) {
                        var insresult = response.includes("Exists");
                        var updresult = response.includes("Updated");
                        if (insresult) {
                            $('#categoryInsertNotOk').show();
                            setTimeout(function () {
                                $('#categoryInsertNotOk').fadeOut('fast');
                            }, 2000);
                        } else if (updresult) {
                            $('#categoryUpdateOk').show();
                            setTimeout(function () {
                                $('#categoryUpdateOk').fadeOut('fast');
                            }, 2000);
                            $("#coursecategoryTable").remove();
                            resetloancategoryTable();
                            $("#loan_category_creation_name").val('');
                            $("#loan_category_creation_id").val('');
                        }
                        else {
                            $('#categoryInsertOk').show();
                            setTimeout(function () {
                                $('#categoryInsertOk').fadeOut('fast');
                            }, 2000);
                            $("#coursecategoryTable").remove();
                            resetloancategoryTable();
                            $("#loan_category_creation_name").val('');
                            $("#loan_category_creation_id").val('');
                        }
                    }
                });
            }
            else {
                $("#loancategorynameCheck").show();
            }
        });


        function resetloancategoryTable() {
            $.ajax({
                url: 'loancategoryFile/ajaxResetLoanCategoryTable.php',
                type: 'POST',
                data: {},
                cache: false,
                success: function (html) {
                    $("#updatedloancategoryTable").empty();
                    $("#updatedloancategoryTable").html(html);
                }
            });
        }

        $("#loan_category_creation_name").keyup(function () {
            var CTval = $("#loan_category_creation_name").val();
            if (CTval.length == '') {
                $("#loancategorynameCheck").show();
                return false;
            } else {
                $("#loancategorynameCheck").hide();
            }
        });

        $("body").on("click", "#edit_category", function () {
            var loan_category_creation_id = $(this).attr('value');
            $("#loan_category_creation_id").val(loan_category_creation_id);
            $.ajax({
                url: 'loancategoryFile/ajaxEditLoanCategory.php',
                type: 'POST',
                data: { "loan_category_creation_id": loan_category_creation_id },
                cache: false,
                success: function (response) {
                    $("#loan_category_creation_name").val(response);
                }
            });
        });

        $("body").on("click", "#delete_category", function () {
            var isok = confirm("Do you want delete course category?");
            if (isok == false) {
                return false;
            } else {
                var loan_category_creation_id = $(this).attr('value');
                var c_obj = $(this).parents("tr");
                $.ajax({
                    url: 'loancategoryFile/ajaxDeleteLoanCategory.php',
                    type: 'POST',
                    data: { "loan_category_creation_id": loan_category_creation_id },
                    cache: false,
                    success: function (response) {
                        var delresult = response.includes("Rights");
                        if (delresult) {
                            $('#categoryDeleteNotOk').show();
                            setTimeout(function () {
                                $('#categoryDeleteNotOk').fadeOut('fast');
                            }, 2000);
                        }
                        else {
                            c_obj.remove();
                            $('#categoryDeleteOk').show();
                            setTimeout(function () {
                                $('#categoryDeleteOk').fadeOut('fast');
                            }, 2000);
                        }
                    }
                });
            }
        });
    }

    $(function () {
        var coursecategoryTable = $('#coursecategoryTable').DataTable({
            ...getStateSaveConfig('coursecategoryTable'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function (row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function (settings) {
                this.api().column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            },
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Loan_Category'); // or any base
                    config.title = dynamic;      // for versions that use title as filename
                    config.filename = dynamic;   // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }],
            'drawCallback': function () {
                searchFunction('coursecategoryTable');
            }
        });

        initColVisFeatures(coursecategoryTable, 'coursecategoryTable');
    });


    // add module 
    var k = 30;
    $(document).on("click", '.add_category_ref', function () {

        validateLoanCategoryTable();

        if (loanCategoryTableError == true) {
            var appendTxt = "<tr><td><input type='text' tabindex='" + k + "' class='chosen-select form-control loan_category_ref_name' id='loan_category_ref_name' name='loan_category_ref_name[]' /></td>" +
                "<td><button type='button' tabindex='" + k + "' id='add_category_ref' name='add_category_ref' value='Submit' class='btn btn-primary add_category_ref'>Add</button></td>" +
                "<td><span class='deleterow icon-trash-2' tabindex='" + k + "'></span></td>" +
                "</tr>";
        }
        else {
            return false;
        }

        $('#moduleTable').find('tbody').append(appendTxt);
        k++;
    });

    // Delete unwanted Rows
    $(document).on("click", '.deleterow', function () {
        $(this).parent().parent().remove();
    });

    function validateLoanCategoryTable() {
        let loanCategoryTableError = true;

        $("#moduleTable tbody tr").each(function () {
            // get the first td text/value (trim spaces)
            let firstTdText = $(this).find("td:first").text().trim();
            let firstTdInputVal = $(this).find("td:first input").val()?.trim() || "";

            // check if empty (either text cell or input cell)
            if (firstTdText === "" && firstTdInputVal === "") {
                $('#loanCategoryTableCheck').show();
                loanCategoryTableError = false;
                return false; // break out of .each()
            }
        });

        if (loanCategoryTableError) {
            $('#loanCategoryTableCheck').hide();
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }

    // Submit Button 
    $('#submitLoanCategory').click(function (event) {
        if (validation() && validateLoanCategoryTable()) {
            let confirmAction = confirm("Are you sure you want to submit Loan Category?");
            if (!confirmAction) {
                event.preventDefault();
                return false;
            }
        } else {
            event.preventDefault();
            return false;
        }
    });
    $('#loan_limit').on('input', function () {
        let value = $(this).val();
        $(this).val(formatIndianNumber(value));
    });
});

function validation() {
    let isValid = true;

    let sub_category_name = $('#sub_category_name').val().trim();
    let loancategoryValue = $('#loan_category_name').val().trim();
    let loanlimit = $('#loan_limit').val().trim();

    if (sub_category_name === '') {
        $('#subCategoryCheck').show();
        isValid = false;
    } else {
        $('#subCategoryCheck').hide();
    }

    if (loancategoryValue === '') {
        $('#loanCategoryCheck').show();
        isValid = false;
    } else {
        $('#loanCategoryCheck').hide();
    }

    if (loanlimit === '') {
        $('#loan_limitCheck').show();
        isValid = false;
    } else {
        $('#loan_limitCheck').hide();
    }
    return isValid; // return true if all fields are valid
}


function DropDownCourse() {
    $.ajax({
        url: 'loancategoryFile/ajaxGetLoanCategory.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#loan_category_name").empty();
            $("#loan_category_name").append("<option value=''>" + 'Select Loan Category' + "</option>");
            for (var i = 0; i < len; i++) {
                var loan_category_creation_id = response[i]['loan_category_creation_id'];
                var loan_category_creation_name = response[i]['loan_category_creation_name'];
                $("#loan_category_name").append("<option value='" + loan_category_creation_id + "'>" + loan_category_creation_name + "</option>");

            }
            // Sort loan_category_name dropdown
            sortDropdownAlphabetically("#loan_category_name");
        }
    });
}
