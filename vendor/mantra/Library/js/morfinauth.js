// Mantra MFS500 typically uses 8030 for HTTP and 8031 for HTTPS
var protocol = window.location.protocol === 'https:' ? 'https:' : 'http:';
var uri = protocol + "//localhost:" + (window.location.protocol === 'https:' ? "8031" : "8030") + "/morfinauth/";

function GetMorFinAuthInfo(connectedDvc , clientKey) {
    var MorFinAuthRequest = {
        "ConnectedDvc": connectedDvc,
        "ClientKey": clientKey
    };
    var jsondata = JSON.stringify(MorFinAuthRequest);
    return PostMorFinAuthClient("info", jsondata);
}
function IsDeviceConnected(connectedDvc) {
     var MorFinAuthRequest = {
         "ConnectedDvc": connectedDvc
    };
    var jsondata = JSON.stringify(MorFinAuthRequest);
    return PostMorFinAuthClient("checkdevice", jsondata);
}
function InitDevice(connectedDvc ,clientKey) {
    var MorFinAuthRequest = {
        "ConnectedDvc": connectedDvc,
        "ClientKey": clientKey
    };
    var jsondata = JSON.stringify(MorFinAuthRequest);
    return PostMorFinAuthClient("initdevice", jsondata);
}
function UninitDevice() {
    return PostMorFinAuthClient("uninitdevice", "", 0);
} 
function GetSupportedDeviceList() {
    return PostMorFinAuthClient("supporteddevicelist", "", 0);
} 
function GetConnectedDeviceList() {
    return PostMorFinAuthClient("connecteddevicelist", "", 0);
}
function GetMorFinAuthKeyInfo(key) {
    var MorFinAuthRequest = {
        "Key": key,
    };
    var jsondata = JSON.stringify(MorFinAuthRequest);
    return PostMorFinAuthClient("keyinfo", jsondata);
}
function CaptureFinger(quality, timeout) {
    var MorFinAuthRequest = {
        "Quality": quality,
        "TimeOut": timeout,
        "TemplateFormat": "ANSI",
        "ImageFormat": "BMP"
    };
    return PostMorFinAuthClient("capture", JSON.stringify(MorFinAuthRequest));

    // Normalize template field names across service versions.
    // Many MorFinAuth builds return ANSI template under Template / Biometrics[0].BiometricData, etc.
    try {
        if (res && res.httpStaus && res.data && String(res.data.ErrorCode) === "0") {
            var d = res.data;
            if (!d.AnsiTemplate) {
                var candidate =
                    d.Template ||
                    d.template ||
                    d.ANSITemplate ||
                    d.ansiTemplate ||
                    (d.Biometrics && d.Biometrics.length ? (d.Biometrics[0].BiometricData || d.Biometrics[0].Template || d.Biometrics[0].Data) : null) ||
                    (d.Biometric && (d.Biometric.BiometricData || d.Biometric.Template || d.Biometric.Data)) ||
                    (d.Data && (d.Data.AnsiTemplate || d.Data.Template || d.Data.BiometricData));

                if (candidate) {
                    d.AnsiTemplate = candidate;
                }
            }

            // Template fetching is handled server-side by the proxy (capture + gettemplate).
        }
    } catch (e) { /* keep backward compatible behavior */ }

    return res;
}
function VerifyFinger(ProbFMR, GalleryFMR, tmpFormat) {
    if (!tmpFormat) { tmpFormat = "ANSI"; }
    var MorFinAuthRequest = {
        "ProbTemplate": ProbFMR,
        "GalleryTemplate": GalleryFMR,
        "TmpFormat": tmpFormat
    };
    return PostMorFinAuthClient("verify", JSON.stringify(MorFinAuthRequest));
}
function MatchFinger(quality, timeout, GalleryFMR, tmpFormat) {
    if (!tmpFormat) { tmpFormat = "ANSI"; }
    var MorFinAuthRequest = {
        "Quality": quality,
        "TimeOut": timeout,
        "GalleryTemplate": GalleryFMR,
        "TemplateFormat": tmpFormat   // ✅ FIXED
    };
    return PostMorFinAuthClient("match", JSON.stringify(MorFinAuthRequest));
}
function GetImage(imgformat) {
    var MorFinAuthRequest = {
        "ImgFormat": imgformat
    };
    var jsondata = JSON.stringify(MorFinAuthRequest);
    return PostMorFinAuthClient("getimage", jsondata);
}
function GetTemplate(tmpFormat) {
    // MorFinAuth `gettemplate` commonly supports POST only and may NOT handle CORS preflight (OPTIONS).
    // If we POST with `application/json`, browsers will preflight and you'll see 405 + CORS blocked.
    // Use a "simple" POST (text/plain) to avoid preflight.
    var res;
    $.support.cors = true;
    var httpStaus = false;

    $.ajax({
        type: "POST",
        async: false,
        crossDomain: true,
        url: uri + "gettemplate",
        // "simple" request => no preflight; MorFinAuth ignores body for many builds
        contentType: "text/plain; charset=utf-8",
        data: "",
        dataType: "json",
        processData: false,
        success: function (data) {
            httpStaus = true;
            res = { httpStaus: httpStaus, data: data };
        },
        error: function (jqXHR, ajaxOptions, thrownError) {
            // Fallback for non-browser / services that DO accept JSON body
            // (kept to maximize compatibility across different MorFinAuth builds)
            try {
                var fallback = PostMorFinAuthClient("gettemplate", JSON.stringify({ "TmpFormat": tmpFormat }));
                if (fallback && fallback.httpStaus) {
                    res = fallback;
                    return;
                }
            } catch (e) { }
            res = { httpStaus: httpStaus, err: getHttpError(jqXHR, thrownError) };
        },
    });

    return res;
}
function PostMorFinAuthClient(method, jsonData, isBodyAvailable) {
    var res;
    if (isBodyAvailable == 0) {
        $.support.cors = true;
        var httpStaus = false;
        $.ajax({
            type: "POST",
            async: false,
            crossDomain: true,
            url: uri + method,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            processData: false,
            success: function (data) {
                httpStaus = true;
                res = { httpStaus: httpStaus, data: data };
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                res = { httpStaus: httpStaus, err: getHttpError(jqXHR, thrownError) };
            },
        });
    }
    else {
        $.support.cors = true;
        var httpStaus = false;
        $.ajax({
            type: "POST",
            async: false,
            crossDomain: true,
            url: uri + method,
            contentType: "application/json; charset=utf-8",
            data: jsonData,
            dataType: "json",
            processData: false,
            success: function (data) {
                httpStaus = true;
                res = { httpStaus: httpStaus, data: data };
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                res = { httpStaus: httpStaus, err: getHttpError(jqXHR, thrownError) };
            },
        });
    }
    return res;
}
function GetMorFinAuthClient(method) {
    var res;
    $.support.cors = true;
    var httpStaus = false;
    $.ajax({
        type: "GET",
        async: false,
        crossDomain: true,
        url: uri + method,
        contentType: "application/json; charset=utf-8",
        processData: false,
        success: function (data) {
            httpStaus = true;
            res = { httpStaus: httpStaus, data: data };
        },
        error: function (jqXHR, ajaxOptions, thrownError) {
            res = { httpStaus: httpStaus, err: getHttpError(jqXHR, thrownError) };
        },
    });
    return res;
}
function getHttpError(jqXHR, thrownError) {
    var err = "Unhandled Exception";
    if (jqXHR.status === 0) {
        err = 'Service Unavailable';
    } else if (jqXHR.status == 404) {
        err = 'Requested page not found';
    } else if (jqXHR.status == 500) {
        err = 'Internal Server Error';
    } else if (thrownError === 'parsererror') {
        err = 'Requested JSON parse failed';
    } else if (thrownError === 'timeout') {
        err = 'Time out error';
    } else if (thrownError === 'abort') {
        err = 'Ajax request aborted';
    } else {
        err = 'Unhandled Error';
    }
    return err;
}