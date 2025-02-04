$(document).ready(function () {
    $('#cus_id').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    $('#search').click(function () {
        let cus_id = $('#cus_id').val(); let cus_name = $('#cus_name').val(); let area = $('#cus_area').val();
        let sub_area = $('#cus_sub_area').val(); let mobile = $('#mobile').val();
        cus_id = cus_id.replace(/\s+/g, '');//removes spaces in adhar number
        if (validate()) {
            $.ajax({
                url: 'searchModule/search_customer.php',
                type: 'POST',
                data: { cus_id, cus_name, area, sub_area, mobile },
                dataType: 'json',
                success: function (data) {
                    let appendData;
                    $('#custListTable tbody,#famlistTable tbody').empty()
                    if (Array.isArray(data.customer_data) && data.customer_data.length > 0) {
                        $.each(data.customer_data, function (key, val) {
                            appendData += `<tr><td>${val.sno}</td>
                                <td>${val.cus_id}</td>
                                <td>${val.cus_name}</td>
                                <td>${val.area}</td>
                                <td>${val.sub_area}</td>
                                <td>${val.branch}</td>
                                <td>${val.line}</td>
                                <td>${val.group}</td>
                                <td>${val.mobile1}</td>
                                <td>${val.mobile2}</td>
                                <td>${val.action}</td>
                                </tr>`;
                        })
                    } else {
                        appendData = `<tr><td colspan='11'>No Records available</td></tr>`;
                    }
                    $('#custListTable tbody').html(appendData);

                    if (Array.isArray(data.family_data) && data.family_data.length > 0) {
                        appendData = '';
                        $.each(data.family_data, function (key, val) {
                            appendData += `<tr><td>${val.sno}</td>
                                <td>${val.name}</td>
                                <td>${val.relationship}</td>
                                <td>${val.adhaar}</td>
                                <td>${val.mobile}</td>
                                <td>${val.under_cus}</td>
                                <td>${val.under_cus_id}</td>
                                </tr>`;
                        })
                    } else {
                        appendData = `<tr><td colspan='7'>No Records available</td></tr>`;
                    }
                    $('#famlistTable tbody').html(appendData);

                    $('.radio-container,#search_container').show();
                }
            }).then(function () {
                viewCusOnClick();
            })
        } else {
            $('.radio-container,#search_container').hide();
        }
    })
    $('input[name="search_radio"]').change(function () {
        let selectedValue = $('input[name="search_radio"]:checked').attr('id');
        if (selectedValue == 'cus_list_radio') {
            $('#customer_list_card').show();
            $('#family_list_card').hide();
        } else if (selectedValue == 'fam_list_radio') {
            $('#family_list_card').show();
            $('#customer_list_card').hide();

        }
    })

    $('#searchbox').keyup(function () {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
        // Loop through the comment list
        $("#custListTable tbody tr").each(function () {
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        })
    })
})


function validate() {
    let response = true;
    let cus_id = $('#cus_id').val(); let cus_name = $('#cus_name').val(); let area = $('#cus_area').val(); let sub_area = $('#cus_sub_area').val(); let mobile = $('#mobile').val();

    if (cus_id == '' && cus_name == '' && area == '' && sub_area == '' && mobile == '') {
        response = false;
        event.preventDefault();
        alert('Please fill any one field to search!')
    }

    return response;
}

function viewCusOnClick() {
    $('.view_cust').off('click').click(function () {
        $('#customerStatusDiv').empty();
        let cus_id = $(this).data('cusid');
        callresetCustomerStatus(cus_id, function () {
            showOverlay(); //loader start
            var pending_sts = $('#pending_sts').val();
            var od_sts = $('#od_sts').val();
            var due_nil_sts = $('#due_nil_sts').val();
            var closed_sts = $('#closed_sts').val()
            var bal_amt = $('#bal_amt').val()
            $.post("searchModule/getCustomerStatus.php", { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts, bal_amt }, function (response) {
                $('#customerStatusDiv').html(response);
                hideOverlay();
            });

        }); //this function will give the customer's status like pending od current
    });
}

function callresetCustomerStatus(cus_id, callback) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];
    $.ajax({
        url: 'collectionFile/resetCustomerStatus.php',
        data: {
            'cus_id': cus_id
        },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {

                for (var i = 0; i < response['pending_customer'].length; i++) {
                    pending_arr[i] = response['pending_customer'][i]
                    od_arr[i] = response['od_customer'][i]
                    due_nil_arr[i] = response['due_nil_customer'][i]
                    closed_arr[i] = response['closed_customer'][i]
                    balAmnt[i] = response['balAmnt'][i]
                }
                var pending_sts = pending_arr.join(',');
                $('#pending_sts').val(pending_sts);
                var od_sts = od_arr.join(',');
                $('#od_sts').val(od_sts);
                var due_nil_sts = due_nil_arr.join(',');
                $('#due_nil_sts').val(due_nil_sts);
                var closed_sts = closed_arr.join(',');
                $('#closed_sts').val(closed_sts);
                var bal_amt = balAmnt.join(',');
                $('#bal_amt').val(bal_amt);
            };
        }.then(callback())
    });
}

function customerStatusOnClickEvents() {
    $('.dropdown').off('click').click(function (event) {
        event.preventDefault();
        $('.dropdown').not(this).removeClass('active');
        $(this).toggleClass('active');
    });

    $(document).off('click').click(function (event) {
        var target = $(event.target);
        if (!target.closest('.dropdown').length) {
            $('.dropdown').removeClass('active');
        }
    });

    $('.personal-info').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/promotion/getPersonalInfo.php', {
            cus_id
        }, function (html) {
            $('#personalInfoDiv').empty().html(html);
        })
    })
    $('.cust-profile').off('click').click(function () {
        let req_id = $(this).data('reqid');
        // window.location.href = 'due_followup_info&upd='+req_id+'&pgeView=1';
        window.open('due_followup_info&upd=' + req_id + '&pgeView=1', '_blank');
    })
    //Documentaion
    $('.documentation').off('click').click(function () {
        let req_id = $(this).data('reqid');
        // window.location.href = 'due_followup_info&upd='+req_id+'&pgeView=2';
        window.open('due_followup_info&upd=' + req_id + '&pgeView=2', '_blank');
    })
    //Loan Calculation
    $('.loan-calc').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.open('due_followup_info&upd=' + req_id + '&pgeView=3', '_blank');
    })

    $('.due-chart').off('click').click(function () {
        let req_id = $(this).attr('value');
        let cus_id = $(this).data('cusid');
        dueChartList(req_id, cus_id); // To show Due Chart List.
        setTimeout(() => {
            $('.print_due_coll').off('click').click(function () {
                var id = $(this).attr('value');
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
                            success: function (html) {
                                $('#printcollection').html(html)
                            }
                        })
                    }
                })
            })
        }, 1000)
    })
    $('.penalty-chart').off('click').click(function () {
        let req_id = $(this).attr('value');
        let cus_id = $(this).data('cusid');
        $.ajax({
            //to insert penalty by on click
            url: 'collectionFile/getLoanDetails.php',
            data: { req_id, cus_id },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                penaltyChartList(req_id, cus_id); //To show Penalty List.
            }
        })
    })

    $('.coll-charge-chart').off('click').click(function () {
        var req_id = $(this).attr('value');
        collectionChargeChartList(req_id) //To Show Fine Chart List
    })
    //Commitment chart
    $('.commitment-chart').off('click').click(function () {
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/dueFollowup/getCommitmentChart.php', { cus_id, req_id }, function (html) {
            $('#commChartDiv').empty().html(html);
        })
    })

    $('.noc-summary').off('click').click(function () {
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        var cus_name = $(this).data('cusname');

        $.ajax({
            url: 'verificationFile/documentation/getNOCSummary.php',
            data: { 'req_id': req_id, 'cus_id': cus_id },
            type: 'post',
            cache: false,
            success: function (html) {
                $('#nocsummaryModal').empty();
                $('#nocsummaryModal').html(html);
            }
        }).then(function () {

            // To get the Signed Document List on Checklist
            $.ajax({
                url: 'nocFile/getSignedDocList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {

                    $('#signDocDiv').empty()
                    $('#signDocDiv').html(response);

                }
            }).then(function () { remove4columns('signDocTable'); })


            // To get the unused Cheque List on Checklist
            $.ajax({
                url: 'nocFile/getChequeDocList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {

                    $('#chequeDiv').empty()
                    $('#chequeDiv').html(response);
                }
            }).then(function () { remove4columns('chequeTable'); })

            // To get the Mortgage List on Checklist
            $.ajax({
                url: 'nocFile/getMortgageList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {

                    $('#mortgageDiv').empty()
                    $('#mortgageDiv').html(response);
                }
            }).then(function () { remove4columns('mortgageTable'); })

            // To get the Endorsement List on Checklist
            $.ajax({
                url: 'nocFile/getEndorsementList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {

                    $('#endorsementDiv').empty()
                    $('#endorsementDiv').html(response);
                }
            }).then(function () { remove4columns('endorsementTable'); })

            // To get the Gold List on Checklist
            $.ajax({
                url: 'nocFile/getGoldList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {

                    $('#goldDiv').empty()
                    $('#goldDiv').html(response);
                }
            }).then(function () { remove4columns('goldTable'); })

            // To get the Document List on Checklist
            $.ajax({
                url: 'nocFile/getDocumentList.php',
                data: { 'req_id': req_id, 'cus_name': cus_name },
                type: 'post',
                cache: false,
                success: function (response) {

                    $('#documentDiv').empty()
                    $('#documentDiv').html(response);
                }
            }).then(function () { remove4columns('documentTable'); })
        })
    })
    function remove4columns(tablename) {
        $('input[type=checkbox]').attr('disabled', true)
    }

    $('.loansummary-chart').off('click').click(function () {
        var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid');
        loanSummaryList(req_id, cus_id);
    })
}

//Due Chart List
function dueChartList(req_id, cus_id) {
    // var req_id = $('#idupd').val()
    // const cus_id = $('#cusidupd').val()
    $.ajax({
        url: 'collectionFile/getDueChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#dueChartTableDiv').empty()
            $('#dueChartTableDiv').html(response)
        }
    }).then(function () {

        $.post('collectionFile/getDueMethodName.php', { req_id }, function (response) {
            $('#dueChartTitle').text('Due Chart ( ' + response['due_method'] + ' - ' + response['loan_type'] + ' )');
        }, 'json');
    })

}
//Penalty Chart List
function penaltyChartList(req_id, cus_id) {
    $.ajax({
        url: 'collectionFile/getPenaltyChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#penaltyChartTableDiv').empty()
            $('#penaltyChartTableDiv').html(response)
        }
    });//Ajax End.
}
//Fine Chart
function collectionChargeChartList(req_id) {
    $.ajax({
        url: 'collectionFile/getCollectionChargeList.php',
        data: { 'req_id': req_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collectionChargeDiv').empty()
            $('#collectionChargeDiv').html(response)
        }
    });//Ajax End.
}

//Loan Summary Chart List
function loanSummaryList(req_id, cus_id) {
    $.ajax({
        url: 'closedFile/loan_summary_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#loanSummaryDiv").empty();
            $("#loanSummaryDiv").html(html);
        }
    });
}
