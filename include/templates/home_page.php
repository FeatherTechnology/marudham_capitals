
<?php
include "ajaxconfig.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 1. Require an authenticated user.
$userid = $_SESSION['userid'] ?? null;

if (empty($userid)) {
    http_response_code(401);
    exit("Unauthorized. Please log in again.");
}

// 2. Fetch this user's upload permission safely.
$userStmt = $connect->prepare(
    "SELECT home_access FROM user WHERE user_id = :userid LIMIT 1"
);
$userStmt->execute([':userid' => $userid]);
$userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$userRow) {
    http_response_code(403);
    exit("User not authorized.");
}

// Preserve your current behavior:
// home_access = 1 means the Upload button is hidden.
$canUpload = ((int)$userRow['home_access'] !== 1);

// 3. Fetch the latest media uploaded within the last 24 hours.
$mediaStmt = $connect->prepare("
    SELECT media_path, media_type
    FROM home_upload
    WHERE upload_date >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
      AND upload_date <= NOW()
    ORDER BY upload_date DESC
    LIMIT 1
");
$mediaStmt->execute();
$media = $mediaStmt->fetch(PDO::FETCH_ASSOC);

$mediaPath = '';
$mediaType = '';
$mediaUrl = '';
$mediaExt = '';

if ($media && !empty($media['media_path'])) {
    // Only accept a plain filename from the database.
    $storedName = $media['media_path'];

    if (
        is_string($storedName) &&
        $storedName !== '' &&
        basename($storedName) === $storedName
    ) {
        $extension = strtolower(
            pathinfo($storedName, PATHINFO_EXTENSION)
        );

        $allowedExtensions = [
            'jpg', 'jpeg', 'png', 'gif',
            'mp4', 'mov', 'mkv',
            'mp3', 'wav'
        ];

        if (in_array($extension, $allowedExtensions, true)) {
            $mediaPath = $storedName;
            $mediaExt = $extension;
            $mediaType = $media['media_type'] ?? '';

            // Encode the filename for use in a URL.
            $mediaUrl = 'uploads/home_upload/' .
                        rawurlencode($mediaPath);
        }
    }
}

// MIME types for correct browser rendering.
$mimeTypes = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'mp4'  => 'video/mp4',
    'mov'  => 'video/quicktime',
    'mkv'  => 'video/x-matroska',
    'mp3'  => 'audio/mpeg',
    'wav'  => 'audio/wav'
];

$mediaMime = $mimeTypes[$mediaExt] ?? '';
?>

<style>
    .media-style {
        width: 75%;
        max-width: 700px;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    #home_upload {
        height: auto;
    }

    #uploadMessage {
        margin-top: 10px;
    }
</style>

<!-- Page header spacing -->
<br><br><br><br><br><br>

<!-- Upload button -->
<div class="container-fluid">
    <div class="d-flex justify-content-end align-items-center w-100">
        <?php if ($canUpload) { ?>
            <div class="dash-input-div">
                <button
                    type="button"
                    class="btn btn-primary"
                    style="width: 100px;"
                    onclick="$('.uploadModal').modal('show');">
                    Upload
                </button>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Display uploaded media -->
<div style="text-align:center; margin-top:15px;">

    <?php if ($mediaUrl !== '') { ?>

        <?php if (in_array($mediaExt, ['jpg', 'jpeg', 'png', 'gif'], true)) { ?>

            <img
                src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8') ?>"
                class="media-style"
                alt="Home poster">

        <?php } elseif (in_array($mediaExt, ['mp4', 'mov', 'mkv'], true)) { ?>

            <video class="media-style" controls playsinline>
                <source
                    src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8') ?>"
                    type="<?= htmlspecialchars($mediaMime, ENT_QUOTES, 'UTF-8') ?>">
                Your browser does not support this video format.
            </video>

        <?php } elseif (in_array($mediaExt, ['mp3', 'wav'], true)) { ?>

            <audio controls style="width:60%;">
                <source
                    src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8') ?>"
                    type="<?= htmlspecialchars($mediaMime, ENT_QUOTES, 'UTF-8') ?>">
                Your browser does not support this audio format.
            </audio>

        <?php } ?>

    <?php } else { ?>

        <img
            src="img/logo.png"
            class="media-style"
            alt="Marudham Capitals logo">

    <?php } ?>

</div>

<!-- Upload modal -->
<?php if ($canUpload) { ?>

<div class="modal fade uploadModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="homeUploadModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color:white;">

            <div class="modal-header">
                <h5 class="modal-title" id="homeUploadModalLabel">
                    Add Upload
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="home_upload">Upload</label>
                            <span class="required">&nbsp;*</span>

                            <input
                                type="file"
                                class="form-control"
                                id="home_upload"
                                name="home_upload"
                                accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.mkv,.mp3,.wav">

                            <span class="text-danger"
                                id="home_uploadcheck"
                                style="display:none;">
                                Please select a file.
                            </span>

                            <div id="uploadMessage" role="status"></div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
                        <button
                            type="button"
                            id="submit_upload"
                            class="btn btn-primary" style="margin-top: 18px;">
                            Submit
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                    onclick="closeChartsModal()">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
$(function () {
    $('#submit_upload').on('click', function () {

        const button = $(this);
        const fileInput = $('#home_upload')[0];
        const file = fileInput.files[0];

        $('#home_uploadcheck').hide();
        $('#uploadMessage')
            .removeClass('text-danger text-success')
            .text('');

        if (!file) {
            $('#home_uploadcheck').show();
            return;
        }

        const formData = new FormData();
        formData.append('home_upload', file);

        button.prop('disabled', true).text('Uploading...');

        $.ajax({
            url: 'ajaxFetch/home_upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'text',
            timeout: 120000,

            success: function (response) {
                const result = response.trim();

                if (result === 'success') {
                    $('.uploadModal').modal('hide');
                    window.location.href = 'home_page';
                    return;
                }

                $('#uploadMessage')
                    .addClass('text-danger')
                    .text('Upload failed: ' + result);
            },

            error: function (xhr, status) {
                let message = 'Upload failed. Please try again.';

                if (status === 'timeout') {
                    message = 'Upload timed out. Please check the server and try again.';
                } else if (xhr.status === 401) {
                    message = 'Your session has expired. Please log in again.';
                } else if (xhr.status === 403) {
                    message = 'You do not have permission to upload.';
                } else if (xhr.status === 413) {
                    message = 'The file is too large for the server.';
                }

                $('#uploadMessage')
                    .addClass('text-danger')
                    .text(message);

                // Keep detailed errors in the browser console for diagnosis.
                console.error(
                    'Home upload failed:',
                    xhr.status,
                    xhr.responseText
                );
            },

            complete: function () {
                button.prop('disabled', false).text('Submit');
            }
        });
    });
});

function closeChartsModal() {
    $('.uploadModal').modal('hide');
}
</script>

<?php } ?>