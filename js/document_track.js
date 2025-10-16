function getAllDocumentList(req_id, cus_name, cus_id) {
    // To get the Signed Document List on Checklist
    $.ajax({
        url: 'documentTrackFile/getSignedDocList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {

            $('#signDocDiv').empty()
            $('#signDocDiv').html(response);

        }
    });


    // To get the unused Cheque List on Checklist
    $.ajax({
        url: 'documentTrackFile/getChequeDocList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {

            $('#chequeDiv').empty()
            $('#chequeDiv').html(response);
        }
    });

    // To get the Mortgage List on Checklist
    $.ajax({
        url: 'documentTrackFile/getMortgageList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {

            $('#mortgageDiv').empty()
            $('#mortgageDiv').html(response);
        }
    });

    // To get the Endorsement List on Checklist
    $.ajax({
        url: 'documentTrackFile/getEndorsementList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {

            $('#endorsementDiv').empty()
            $('#endorsementDiv').html(response);
        }
    });

    // To get the Gold List on Checklist
    $.ajax({
        url: 'documentTrackFile/getGoldList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {

            $('#goldDiv').empty()
            $('#goldDiv').html(response);
        }
    });

    // To get the Document List on Checklist
    $.ajax({
        url: 'documentTrackFile/getDocumentList.php',
        data: { 'req_id': req_id, 'cus_name': cus_name },
        type: 'post',
        cache: false,
        success: function (response) {

            $('#documentDiv').empty()
            $('#documentDiv').html(response);
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
