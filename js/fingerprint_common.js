/**
 * Centralized logic for Mantra Fingerprint Scanner
 */
function commonCaptureFinger(successCallback, errorCallback) {

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

                        const tempRes = GetTemplate("ANSI");
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
                              
                                alert("ANSI Template not received");

                                if (typeof errorCallback === "function") {
                                    errorCallback("NO_TEMPLATE");
                                }
                            }


                        } else {
                            alert("GetTemplate failed");
                            if (typeof errorCallback === "function") {
                                errorCallback("GET_TEMPLATE_FAILED");
                            }
                        }

                        hideOverlay();

                    }, 1200);
                }

            } else {

                handleMantraError(
                    res.data.ErrorCode,
                    res.data.ErrorDescription
                );

                hideOverlay();

                if (typeof errorCallback === "function") {
                    errorCallback(res.data.ErrorCode);
                }
            }

        } else {

            alert("Device service not responding");

            hideOverlay();

            if (typeof errorCallback === "function") {
                errorCallback("SERVICE_DOWN");
            }
        }

    }, 500);
}

function handleMantraError(errorCode, errorDescription) {
    const errorMessages = {
        "-2027": 'Connect Your Device',
        "-1140": 'Timeout',
        "700": 'Timeout',
        "720": 'Reconnect Device',
        "2038": 'Capture Finger Again'
    };
    alert(errorMessages[errorCode] || `Error: ${errorDescription} Error Code: ${errorCode}`);
}

function commonStoreFingerprint(fdata, hand, id, name, successCallback) {
    $.post('updateFile/storeFingerprints.php', { 'fdata': fdata, 'hand': hand, 'cus_id': id, 'cus_name': name }, function (response) {
        if (response.includes('Successfully')) {
            Swal.fire({ title: response, icon: 'success', confirmButtonColor: '#009688' });
            if (typeof successCallback === 'function') successCallback();
        }
    }, 'json');
}

function commonMatchFinger(compare_template, successCallback, errorCallback) {
    const quality = 60;
    const timeout = 10;
    const matchResult = MatchFinger(quality, timeout, compare_template, "ANSI");
    if (matchResult.httpStaus) {
        if (matchResult.data.Status) {
            Swal.fire({ title: 'Fingerprint Matching', icon: 'success', showConfirmButton: true, confirmButtonColor: '#009688' });
            if (typeof successCallback === 'function') successCallback();
        } else {
            if (matchResult.data.ErrorCode != "0") {
                alert(matchResult.data.ErrorDescription);
            } else {
                Swal.fire({ title: 'Fingerprint Not Matching', icon: 'error', showConfirmButton: true, confirmButtonColor: '#009688' });
                if (typeof errorCallback === 'function') errorCallback();
            }
        }
    } else {
        alert(matchResult.err);
    }
}