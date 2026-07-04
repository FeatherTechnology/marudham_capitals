/**
 * Centralized logic for Mantra Fingerprint Scanner
 */

$(document).on('click','.scanBtn', function () {
    let hand = $(this).prev().val();
    let aadhaarprint = $(this).parent().prev().prev().prev().find('input[id="adhar_print"]');
    let aadhaar = aadhaarprint.attr('data-no');
    let name = aadhaarprint.val();
    
    if (hand == '') { //prevent if hand is not selected
        $(this).prev().css('border-color', 'red');
    } else {
        $(this).prev().css('border-color', '#009688');
        let btn = $(this);
        commonCaptureFinger((ansi) => {
            btn.next().val(ansi);
            commonStoreFingerprint(ansi, hand, aadhaar, name);
        });
    }
});

function commonCaptureFinger(successCallback) {

    const quality = 60;
    const timeout = 10000;

    showOverlay();

    setTimeout(() => {

        const res = CaptureFinger(quality, timeout);

        if (res.httpStaus) {

            if (res.data.ErrorCode == "0") {

                // If template returned directly
                if (res.data.AnsiTemplate) {

                    successCallback(res.data.AnsiTemplate);
                    hideOverlay();

                } else {

                    // Fetch template separately
                    setTimeout(() => {

                        const tempRes = GetTemplate(2);
                        if (
                            tempRes &&
                            tempRes.httpStaus &&
                            tempRes.data &&
                            tempRes.data.ErrorCode == "0"
                        ) {

                            let tpl =
                                tempRes.data.AnsiTemplate ||
                                tempRes.data.Template ||
                                tempRes.data.template ||
                                tempRes.data.ImgData;

                            if (tpl) {
                                successCallback(tpl);
                            } else {
                                handleMantraError(0,"ANSI Template not received");
                            }

                        } else {
                            handleMantraError(0,"GetTemplate failed");
                        }

                        hideOverlay();

                    }, 1200);
                }

            } else {
                handleMantraError(res.data.ErrorCode,res.data.ErrorDescription);

                hideOverlay();
            }

        } else {
            handleMantraError(0,"Device service not responding");

            hideOverlay();
        }

    }, 500);
}

function handleMantraError(errorCode, errorDescription) {
    const errorMessages = {
        "-2027": 'Connect Your Device',
        "-1140": 'Timeout',
        "700": 'Timeout',
        "720": 'Reconnect Device',
        "-2023": 'Capture Already Started',
        "-2025": 'Device not initialized',
        "-2027": 'Device not connected',
        "2038": 'Capture Finger Again'
    };
    swalError('Warning', errorMessages[errorCode] || `Error: ${errorDescription}, Error Code: ${errorCode}`);
}

function commonStoreFingerprint(ansi, hand, aadhaar, name) {
    $.post('updateFile/storeFingerprints.php', { ansi, hand, aadhaar, name }, function (response) {
        if (response.includes('Successfully')) {
            Swal.fire({ 
                title: response, 
                icon: 'success', 
                confirmButtonColor: '#009688' 
            }).then((result) => {
                if(result.isConfirmed){
                    fingerprintTable(); //Call fingerprint data after stored to get updated data.
                }
            });
        } else {
            swalError('Warning', 'Fingerprint submission failed.')
        }
    }, 'json');
}

function commonMatchFinger(compare_template, successCallback, errorCallback) {
    const quality = 60;
    const timeout = 10;
    const matchResult = MatchFinger(quality, timeout, compare_template, 2);
    if (matchResult.httpStaus) {
        if (matchResult.data.Status) {
            Swal.fire({ title: 'Fingerprint Matching', icon: 'success', showConfirmButton: true, confirmButtonColor: '#009688' });
            if (typeof successCallback === 'function') successCallback();
        } else {
            if (matchResult.data.ErrorCode != "0") {
                swalError('Warning', matchResult.data.ErrorDescription);
            } else {
                Swal.fire({ title: 'Fingerprint Not Matching', icon: 'error', showConfirmButton: true, confirmButtonColor: '#009688' });
                if (typeof errorCallback === 'function') errorCallback();
            }
        }
    } else {
        swalError('Warning', matchResult.err);
    }
}

function getMatchFingerDetails(){
    let btn = $(this);
    btn.attr('disabled', true);
    let compare_finger = $('#compare_finger').val();
    commonCaptureFinger((ansi) => {
        $('#ack_fingerprint').val(ansi);
        commonMatchFinger(compare_finger, () => {
            $('#fingerValidation').val('1');
            btn.attr('disabled', true);
            $("#hand_type").text('Done').attr('class', 'text-success');
        }, () => {
            $('#fingerValidation').val('');
            btn.removeAttr('disabled');
        });
    });
}