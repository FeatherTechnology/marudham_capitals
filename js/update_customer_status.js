$(document).ready(function () {

    $('#update_sts_btn').click(function (event) {
        event.preventDefault();
        window.open('update_customer_status&upd_value=1', '_blank');
    });
});

$(function () {
    let updVal = $('#upd_value').val();

    if (updVal == '1') {
        getCustomerIDs();
    }
});

function getCustomerIDs(){    
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'updateCustomerStatusFile/ajaxCusIdFetch.php',
        success: function (response) {
            let reqIDArr = Object.values(response); // Assuming response is an array of IDs.
            processBatch(reqIDArr, 0, 2); // Start processing the first 10 records
            showOverlay();
        }
    });
}

//need to run 10 object from the total because total is more than 9k. if load at time surely database timeout exceed.
// Recursive function to process a batch of records
function processBatch(reqID, start, batchSize) {
    const batch = reqID.slice(start, start + batchSize); // Get the current batch (e.g., 10 objects)
    console.log('start from: ' + start);

    if (batch.length === 0) {
        hideOverlay();
        console.log("All batches processed");
        return; // Exit the recursion when all batches are processed
    }

    let promises = [];
    batch.forEach(element => {
        // Declare these variables outside the success callback to avoid scoping issues
        let pendingSts, odSts, dueNilSts, closedSts, balAmnt, payableAmnt;

        // Push each AJAX request to the promises array
        promises.push(
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'collectionFile/resetCustomerStatus.php',
                data: { reqID: element },
                cache: false,
                success: function (response) {
                    //To get loan sub Status
                    if (response.length != 0) {
                        pendingSts = response['pending_customer'];
                        odSts = response['od_customer'];
                        dueNilSts = response['due_nil_customer'];
                        closedSts = response['closed_customer'];
                        balAmnt = response['balAmnt'];
                        payableAmnt = response['payable_as_req'];
                    }
                }
            }).then(function () {
                return $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'updateCustomerStatusFile/updateCustomerStatus.php',
                    data: { 'req_id': element, 'pending_sts': pendingSts, 'od_sts': odSts, 'due_nil_sts': dueNilSts, 'closed_sts': closedSts, 'bal_amt': balAmnt, 'payable': payableAmnt },
                    cache: false
                });
            })
        );
    });

    // Wait for all promises in the current batch to complete
    $.when.apply($, promises).then(function () {
        // Move to the next batch after the current batch finishes
        setTimeout(function () {
            processBatch(reqID, start + batchSize, batchSize);
        }, 500);

    }).fail(function (jqXHR, textStatus, errorThrown) {
        hideOverlay();
        console.error("Error processing batch: ", jqXHR);
        console.error("Status: ", textStatus);
        console.error("Error: ", errorThrown);
        // Log the raw responseText to inspect the response from the server
        console.error("Response Text: ", jqXHR.responseText);
    });
}