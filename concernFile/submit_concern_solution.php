
<?php
@session_start();
include('../ajaxconfig.php');
$userid = $_SESSION['userid'] ?? '';

$solution_date = $_POST['solution_date'] ?? '';
$Com_for_solution = $_POST['Com_for_solution'] ?? '';
$role_type = $_POST['role_type'] ?? '';
$staff_assign_to = $_POST['staff_assign_to'] ?? '';
$pass_to = $_POST['pass_to'] ?? '';
$location = $_POST['location'] ?? '';
$sol_participants = $_POST['sol_participants'] ?? '';
$pg_id = $_POST['pg_id'] ?? '';
$solution_remark = $_POST['solution_remark'] ?? '';
$id = $_POST['id'] ?? '';

$status = 0;

if ($pg_id != '1') {
    $status = 1;

    if (empty($pass_to)) {
        $role_type = '';
        $staff_assign_to = '';
    }
}

$uploadDir = __DIR__ . '/../uploads/concern/concern_solution/';
$uploadedFiles = [];
$concern_upload = '';

try {

    if (empty($id)) {
        throw new Exception('Concern ID is missing.');
    }

    $connect->beginTransaction();

    // Upload concern solution files
    if (
        isset($_FILES['concern_upload']) &&
        $Com_for_solution == '1' &&
        !empty($_FILES['concern_upload']['name'][0])
    ) {

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filesArr = $_FILES['concern_upload'];

        foreach ($filesArr['name'] as $key => $fileName) {

            if ($filesArr['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid(true) . '.' . $extension;

            if (move_uploaded_file(
                $filesArr['tmp_name'][$key],
                $uploadDir . $uniqueFileName
            )) {
                $uploadedFiles[] = $uniqueFileName;
            }
        }

        $concern_upload = implode(',', $uploadedFiles);
    }

    // Update concern solution
    $updConcern = "UPDATE `concern_creation` SET
        `pass_role` = '$role_type',
        `pass_to` = '$staff_assign_to',
        `status` = '$status',
        `solution_date` = '" . strip_tags($solution_date) . "',
        `communication` = '" . strip_tags($Com_for_solution) . "',
        `uploads` = '" . strip_tags($concern_upload) . "',
        `location` = '" . strip_tags($location) . "',
        `sol_participants` = '" . strip_tags($sol_participants) . "',
        `solution_remark` = '" . strip_tags($solution_remark) . "',
        `update_user_id` = '" . strip_tags($userid) . "',
        `updated_date` = NOW()
        WHERE `id` = '" . strip_tags($id) . "'";

    $connect->exec($updConcern);

    $connect->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Concern solution updated successfully'
    ]);

} catch (Throwable $e) {

    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    // Remove uploaded files if update fails
    foreach ($uploadedFiles as $file) {
        $filePath = $uploadDir . $file;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
