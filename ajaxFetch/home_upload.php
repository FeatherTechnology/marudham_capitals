<?php
session_start();
require_once "../ajaxconfig.php";

header('Content-Type: text/plain; charset=utf-8');

// 1. Require an authenticated session.
// Add your application's actual admin-role check here too.
if (empty($_SESSION['userid'])) {
    http_response_code(401);
    exit("unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("method_not_allowed");
}

if (!isset($_FILES['home_upload'])) {
    http_response_code(400);
    exit("no_file");
}

$file = $_FILES['home_upload'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit("upload_error");
}

// Adjust this limit to suit your poster/video requirements.
$maxSize = 100 * 1024 * 1024; // 100 MB

if ($file['size'] <= 0 || $file['size'] > $maxSize) {
    http_response_code(400);
    exit("invalid_file_size");
}

// 2. Validate extension and detected MIME type.
$extension = strtolower(
    pathinfo($file['name'], PATHINFO_EXTENSION)
);

$allowed = [
    'jpg'  => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png'  => ['image/png'],
    'gif'  => ['image/gif'],
    'mp4'  => ['video/mp4'],
    'mov'  => ['video/quicktime'],
    'mkv'  => ['video/x-matroska'],
    'mp3'  => ['audio/mpeg'],
    'wav'  => [
        'audio/wav',
        'audio/x-wav',
        'audio/vnd.wave'
    ],
];

if (!isset($allowed[$extension])) {
    http_response_code(400);
    exit("invalid_file_type");
}

if (!is_uploaded_file($file['tmp_name'])) {
    http_response_code(400);
    exit("invalid_upload");
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);

if (!in_array($mime, $allowed[$extension], true)) {
    http_response_code(400);
    exit("file_content_type_mismatch");
}

// 3. Save using a random filename, never the supplied filename.
$uploadDir = __DIR__ . "/../uploads/home_upload/";

if (!is_dir($uploadDir) &&
    !mkdir($uploadDir, 0750, true) &&
    !is_dir($uploadDir)) {
    http_response_code(500);
    exit("upload_directory_error");
}

if (!is_writable($uploadDir)) {
    http_response_code(500);
    exit("upload_directory_not_writable");
}

$filename = bin2hex(random_bytes(16)) . "." . $extension;
$destination = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(500);
    exit("upload_failed");
}

$mediaType = str_starts_with($mime, 'image/')
    ? 'image'
    : (str_starts_with($mime, 'video/') ? 'video' : 'audio');

$today = date("Y-m-d H:i:s");
$userid = $_SESSION['userid'];

try {
    // Keep the old poster until the new upload and DB operation succeed.
    $qry = $connect->query(
        "SELECT media_path FROM home_upload"
    );
    $oldRows = $qry->fetchAll(PDO::FETCH_COLUMN);

    $connect->beginTransaction();

    $delete = $connect->prepare("DELETE FROM home_upload");
    $delete->execute();

    $stmt = $connect->prepare("
        INSERT INTO home_upload
            (user_id, media_path, media_type, upload_date)
        VALUES
            (:userid, :path, :type, :today)
    ");

    $stmt->execute([
        ':userid' => $userid,
        ':path'   => $filename,
        ':type'   => $mediaType,
        ':today'  => $today
    ]);

    $connect->commit();

    // Remove old files only after the DB commit succeeds.
    foreach ($oldRows as $oldFile) {
        // Only allow a plain filename, not a path.
        if (
            is_string($oldFile) &&
            basename($oldFile) === $oldFile &&
            $oldFile !== $filename
        ) {
            $oldPath = $uploadDir . $oldFile;

            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }
    }

    echo "success";

} catch (Throwable $e) {
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    if (is_file($destination)) {
        @unlink($destination);
    }

    error_log("home_upload failure: " . $e->getMessage());

    http_response_code(500);
    echo "upload_failed";
}
?>
