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
    let iconType = 'info';
    if (response.includes('Successfully')) {
        iconType = 'success';
    } else if (response.includes('Error')) {
        iconType = 'error';
    }

    return Swal.fire({
        title: response,
        icon: iconType,
        showConfirmButton: true,
        confirmButtonColor: '#009688',
        confirmButtonText: 'OK'
    });
}
