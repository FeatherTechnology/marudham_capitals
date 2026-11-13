<?php
include "../../ajaxconfig.php";

if (isset($_POST['loan_req_id'])) {
    $req_id = $_POST['loan_req_id'];
}

if (isset($_POST['category_info'])) {
    $category_info = $_POST['category_info'];
}

if (isset($_POST['loan_cal_id'])) { //To check Whether it is for update 
    $loan_cal_id = $_POST['loan_cal_id'];
}

if (isset($_POST['Communitcation_to_cus'])) {
    $Communitcation_to_cus = $_POST['Communitcation_to_cus'];
}

$verify_audio ='';
if (!empty($_FILES['verification_audio'])) {
    
    $filesArr3 = $_FILES['verification_audio'];
    $uploadDir = "../../uploads/verification/verifyInfo_audio/";

    foreach ($filesArr3['name'] as $key => $val) {

        $fileName = basename($filesArr3['name'][$key]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //get the file extention

        $uniqueFileName = uniqid() . '.' . $fileType;

        //this loop will continue until it generates a unique file name
        while (file_exists($uploadDir . $uniqueFileName)) {
            $uniqueFileName = uniqid() . '.' . $fileType;
        }

        if (
            move_uploaded_file(
                $filesArr3['tmp_name'][$key],
                $uploadDir . $uniqueFileName
            )
        ) {
            $verify_audio .= $uniqueFileName . ',';
        }
    }
}

$verify_audio = implode(',', array_filter([
    rtrim($verify_audio, ','),
    trim($_POST['verification_audio_upd'] ?? '', ',')
]));

if (isset($_POST['verifyPerson'])) {
    $verifyPerson = $_POST['verifyPerson'];
}

if (isset($_POST['verification_location'])) {
    $verification_location = $_POST['verification_location'];
}

if (isset($_POST['verify_remark'])) {
    $verify_remark = $_POST['verify_remark'];
}

try {

    if ($loan_cal_id > 0 and $loan_cal_id != '') {
        $insresult = $connect->query("UPDATE acknowlegement_loan_calculation SET communication = '$Communitcation_to_cus', com_audio = '$verify_audio', verification_person = '$verifyPerson', verification_location = '$verification_location', verify_remark = '$verify_remark' WHERE req_id = $req_id ");

        $connect->query("DELETE FROM acknowledgement_loan_cal_category WHERE req_id = '$req_id'");

        for ($i = 0; $i < sizeof($category_info); $i++) {
            $insertCategory = $connect->query("INSERT INTO acknowledgement_loan_cal_category(req_id, loan_cal_id, category) VALUES ('$req_id', '$loan_cal_id', '$category_info[$i]' )");
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($insresult) {
    $response['info'] = 'Successfully Updated';
} else {
    $response['info'] = 'Error';
}

echo json_encode($response);

// Close the database connection
$connect = null;

?>