function callOnClickEvents() {
    $('a.customer-status').click(async function() {
        try {
            var cus_id = $(this).data('value');
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

    $('.view-track').click(function() {
        var cus_id = $(this).data('cusid');
        var cus_name = $(this).data('cusname');
        var req_id = $(this).data('reqid');
        $.ajax({
            url: 'documentTrackFile/viewTrack.php',
            type: 'post',
            data: {
                'cus_id': cus_id,
                "req_id": req_id
            },
            cache: false,
            success: function(html) {
                $('#viewTrackDiv').empty();
                $('#viewTrackDiv').html(html);
            }
        }).then(function() {
            getAllDocumentList(req_id, cus_name);
        }); //then function end
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

function getAllDocumentList(req_id, cus_name) {
    // To get the Customer details.
    $.post('collectionFile/getDueMethodName.php', { req_id }, function (response) {
        $('#myLargeModalLabel').text(`View Document ( Aadhaar Number : ${response.cus_id} | Cus ID : ${response.autogen_cus_id}  | Cus Name : ${response.cus_name}  | Loan ID : ${response.loan_id}  | DOC ID : ${response.doc_id} | Loan Category : ${response.loan_category} )`);
    }, 'json');

    // To get the Signed Document List on Checklist
    $.ajax({
        url: 'documentTrackFile/getSignedDocList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {
            let noData = $(response).find("tbody tr").length === 0;
            if (noData) {
                $("#sign_div").hide();
                $("#sign_hr").hide();
            } else {
                $("#sign_div").show();
                $('#signDocDiv').empty().html(response);
            }
        }
    });

    // To get the unused Cheque List on Checklist
    $.ajax({
        url: 'documentTrackFile/getChequeDocList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {
            let noData = $(response).find("tbody tr").length === 0;
            if (noData) {
                $("#cheque_div").hide();
                $("#sign_hr").hide();
            } else {
                $("#cheque_div").show();
                $('#chequeDiv').empty().html(response);
            }
        }
    });

    // To get the Mortgage List on Checklist
    $.ajax({
        url: 'documentTrackFile/getMortgageList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {
            let noData = $(response).find("tbody tr").length === 0;
            if (noData) {
                $("#mort_div").hide();
                $("#cheque_hr").hide();
            } else {
                $("#mort_div").show();
                $('#mortgageDiv').empty().html(response);
            }
        }
    });

    // To get the Endorsement List on Checklist
    $.ajax({
        url: 'documentTrackFile/getEndorsementList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {
            let noData = $(response).find("tbody tr").length === 0;
            if (noData) {
                $("#endorse_div").hide();
                $("#mort_hr").hide();
            } else {
                $("#endorse_div").show();
                $('#endorsementDiv').empty().html(response);
            }
        }
    });

    // To get the Gold List on Checklist
    $.ajax({
        url: 'documentTrackFile/getGoldList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {
            let noData = $(response).find("tbody tr").length;
            if (noData <= 1) {
               $("#gold_div").hide();
               $("#endo_hr").hide();
            } else {
                $("#gold_div").show();
                $('#goldDiv').empty().html(response);
            }
        }
    });

    // To get the Document List on Checklist
    $.ajax({
        url: 'documentTrackFile/getDocumentList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {
            let noData = $(response).find("tbody tr").length === 0;
            if (noData) {
                $("#doc_div").hide();
                $("#gold_hr").hide();
            } else {
                $("#doc_div").show();
                $('#documentDiv').empty().html(response);
            }
        }
    });

}
