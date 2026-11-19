
<?php
@session_start();
include('../ajaxconfig.php');
$userid = $_SESSION['userid'] ?? '';

$raising_for = $_POST['raising_for'] ?? '';
$self_name = $_POST['self_name'] ?? '';
$self_code = $_POST['self_code'] ?? '';
$staff_name = $_POST['staff_name'] ?? '';
$staff_dept_name = $_POST['staff_dept_name'] ?? '';
$staff_team_name = $_POST['staff_team_name'] ?? '';
$ag_name = $_POST['ag_name'] ?? '';
$ag_grp = $_POST['ag_grp'] ?? '';
$cus_id = $_POST['cus_id'] ?? '';
$cus_name = $_POST['cus_name'] ?? '';
$cus_area = $_POST['cus_area'] ?? '';
$cus_sub_area = $_POST['cus_sub_area'] ?? '';
$cus_group = $_POST['cus_group'] ?? '';
$cus_line = $_POST['cus_line'] ?? '';
$com_date = $_POST['com_date'] ?? '';
$to_dept_name = $_POST['to_dept_name'] ?? '';
$com_sub = $_POST['com_sub'] ?? '';
$com_remark = $_POST['com_remark'] ?? '';
$role_type = $_POST['role_type'] ?? '';
$staff_assign_to = $_POST['staff_assign_to'] ?? '';

$uploadPath = __DIR__ . '/../uploads/concern/concern_creation/';

if (!is_dir($uploadPath)) {
    mkdir($uploadPath, 0777, true);
}

$uploadedFiles = [];

try {
    $connect->beginTransaction();

    // Generate concern code
    $myStr = 'CC';

    $codeQry = "SELECT `com_code`
            FROM `concern_creation`
            WHERE `com_code` != ''
            ORDER BY CAST(SUBSTRING_INDEX(`com_code`, '-', -1) AS UNSIGNED) DESC
            LIMIT 1
            FOR UPDATE";

    $codeResult = $connect->query($codeQry);
    $codeRow = $codeResult->fetch(PDO::FETCH_ASSOC);

    if ($codeRow) {
        $ac2 = $codeRow['com_code'];
        $appno2 = (int) ltrim(strstr($ac2, '-'), '-') + 1;
        $com_code = $myStr . '-' . $appno2;
    } else {
        $com_code = $myStr . '-101';
    }

    // Upload concern files
    $concerncreation_upload = '';

    if (isset($_FILES['concern_creation_upload']) && !empty($_FILES['concern_creation_upload']['name'][0])) {

        $fileNames = $_FILES['concern_creation_upload']['name'];
        $fileTemps = $_FILES['concern_creation_upload']['tmp_name'];
        $fileErrors = $_FILES['concern_creation_upload']['error'];

        foreach ($fileNames as $key => $fileName) {

            if ($fileErrors[$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $extension;

            if (move_uploaded_file($fileTemps[$key], $uploadPath . $newFileName)) {
                $uploadedFiles[] = $newFileName;
            }
        }

        $concerncreation_upload = implode(',', $uploadedFiles);
    }

    // Insert concern details
    $insertQry = "INSERT INTO `concern_creation`(
        `raising_for`,
        `self_name`,
        `self_code`,
        `staff_name`,
        `staff_dept_name`,
        `staff_team_name`,
        `ag_name`,
        `ag_grp`,
        `cus_id`,
        `cus_name`,
        `cus_area`,
        `cus_sub_area`,
        `cus_group`,
        `cus_line`,
        `com_date`,
        `com_code`,
        `to_dept_name`,
        `com_sub`,
        `com_remark`,
        `role_type`,
        `staff_assign_to`,
        `concern_creation_uploads`,
        `insert_user_id`
    ) VALUES (
        '$raising_for',
        '$self_name',
        '$self_code',
        '$staff_name',
        '$staff_dept_name',
        '$staff_team_name',
        '$ag_name',
        '$ag_grp',
        '$cus_id',
        '$cus_name',
        '$cus_area',
        '$cus_sub_area',
        '$cus_group',
        '$cus_line',
        '$com_date',
        '$com_code',
        '$to_dept_name',
        '$com_sub',
        '$com_remark',
        '$role_type',
        '$staff_assign_to',
        '$concerncreation_upload',
        '$userid'
    )";

    $connect->exec($insertQry);

    $connect->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Added',
        'com_code' => $com_code
    ]);
} catch (Throwable $e) {

    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    // Remove uploaded files if insert fails
    foreach ($uploadedFiles as $file) {
        $filePath = $uploadPath . $file;

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
