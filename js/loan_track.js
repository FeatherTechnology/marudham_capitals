$(document).ready(function () {

    $('#cus_id').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    $('#search_cus').click(function (event) {

        let cus_id = $('#cus_id').val(); cus_id = cus_id.replace(/\s+/g, '');//removes spaces in adhar number
        let cus_name = $('#cus_name').val(); let mobile = $('#mobile').val(); let loan_id = $('#loan_id').val();
        if (!validate()) {
            event.preventDefault();
            alert('Please fill any one field to search!')
        } else {
            $.ajax({
                url: 'searchModule/search_customer.php',
                type: 'POST',
                dataType: 'JSON',
                data: { cus_id, cus_name, mobile, loan_id },
                success: function (data) {
                    console.log("🚀 ~ data:", data)
                    let appendData;
                    $('#custListTable tbody').empty()
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
                    $('#customer_list tbody').html(appendData);
                }
            }).then(function () {
                viewCusOnClick();
            })
        }
    })

    $('#close_btn').click(function () {
        $('.loanlist_card, #close_btn').hide(); $('#loanTrackDiv table tbody').empty()
        $('#customer_list_card, #search_card').show();
    })
})

function validate() {
    let cus_id = $('#cus_id').val(); cus_id = cus_id.replace(/\s+/g, '');//removes spaces in adhar number
    let cus_name = $('#cus_name').val(); let mobile = $('#mobile').val(); let loan_id = $('#loan_id').val();
    let response = true; let pattern = /\d{3,}$/; // '\d' matches any digit, '{3,}' matches at least 3 digits

    if (cus_id == '' && cus_name == '' && mobile == '' && loan_id == '') {
        response = false;
    } else if (cus_id != '' && cus_id.length != 12) {
        response = false;
    } else if (mobile != '' && mobile.length != 10) {
        response = false;
    } else if (loan_id != '' && !loan_id.match(pattern)) {
        response = false;
    }

    return response;
}

function viewCusOnClick() {
    $('.view_cust').off('click').click(function () {
        $('#loanListTableDiv').empty();
        let cus_id = $(this).data('cusid');
        callresetCustomerStatus(cus_id, function () {
            showOverlay(); //loader start
            var pending_sts = $('#pending_sts').val();
            var od_sts = $('#od_sts').val();
            var due_nil_sts = $('#due_nil_sts').val();
            var closed_sts = $('#closed_sts').val()
            var bal_amt = $('#bal_amt').val()
            $.post("loanTrackFile/getCustomerLoanList.php", { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts, bal_amt }, function (response) {
                $('#loanListTableDiv').html(response);
                $('.loanlist_card, #close_btn').show();
                $('#customer_list_card, #search_card').hide();
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
        }
    }).then(callback());
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

    $('.track-btn').off('click');
    $(document).on('click', '.track-btn', function () {
        let req_id = $(this).attr('data-req_id');
        $.post('loanTrackFile/getTrackDetails.php', { 'req_id': req_id }, function (response) {
            $('#loanTrackDiv').empty().html(response);
        })
    });

    $('.stage-detail').off('click');
    $(document).on('click', '.stage-detail', function () {
        let req_id = $(this).attr('data-req_id');
        let stage = $(this).attr('data-stage');
        $.post('loanTrackFile/getStageDetails.php', { 'req_id': req_id, 'stage': stage }, function (res) {
            $('#stageDetailsDiv').empty().html(res);
        })
    });
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
