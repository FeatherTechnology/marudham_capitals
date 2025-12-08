function getAllDocumentList(req_id, cus_name, cus_id) {
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
                $('#signDocDiv').empty()
                $('#signDocDiv').html(response);
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
                $('#chequeDiv').empty()
                $('#chequeDiv').html(response);
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
                $('#mortgageDiv').empty()
                $('#mortgageDiv').html(response);
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
                $('#endorsementDiv').empty()
                $('#endorsementDiv').html(response);
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
                $('#goldDiv').empty()
                $('#goldDiv').html(response);
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
                $('#documentDiv').empty()
                $('#documentDiv').html(response);
            }
        }
    });

}

function swalAlert(response) {
    if (response.includes('Successfully')) {
        return Swal.fire({
            title: response,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009688',
            showConfirmButton: true
        });
    } else if (response.includes('Error')) {
        return Swal.fire({
            title: response,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009688',
            showConfirmButton: true
        });
    }
}
