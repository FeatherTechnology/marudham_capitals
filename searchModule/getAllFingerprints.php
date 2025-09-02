<?php
include('../ajaxconfig.php');

    // Fetch only the fingerprint templates
    $sql = "SELECT adhar_num, name, ansi_template FROM fingerprints";
    $runSql = $connect->query($sql);
    $data = array();
    while ($row = $runSql->fetch()) {
        $data['fingerprints'][] = [
            'cus_id' => $row['adhar_num'],
            'cus_name' => $row['name'],
            'template' => $row['ansi_template']
        ];
    }

    
echo json_encode($data);

// Close the database connection
$connect = null;
?>