<?php
include('../../ajaxconfig.php');
@session_start();
$user_id = $_SESSION['userid'];

$rows = []; // Initialize array

if ($user_id != '') {
    $sql = $connect->query("
        SELECT 
            event_name,
            event_created_date as created_date, 
            GROUP_CONCAT(area SEPARATOR ', ') as area_names,
            COUNT(*) as total_customer
        FROM event_promotion
        WHERE insert_login_id = '$user_id'
        GROUP BY event_name
    ");

    if ($sql->rowCount() > 0) {
        $i = 1;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $formattedDate = date('d-m-Y', strtotime($row['created_date']));
            $action = '<div class="dropdown">
                        <button class="btn btn-outline-secondary"><i class="fa">&#xf107;</i></button>
                        <div class="dropdown-content">
                            <a class="edit_event" data-event="'. $row['event_name'] .'"><span>Edit</span></a>
                            <a class="delete_event" data-event="'. $row['event_name'] .'"><span>Delete</span></a>
                        </div>
                      </div>';
            $rows[] = [
                $i++,
                $formattedDate,
                $row['event_name'],
                $row['area_names'],
                $row['total_customer'],
                $action
            ];
        }
    }
}

// Always return JSON array (empty if no records)
echo json_encode($rows);
