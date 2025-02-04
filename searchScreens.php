<?PHP
session_start();
$user_id = $_SESSION["userid"];

include("ajaxconfig.php");

$search_content = $_POST["search_content"];

$sql = $connect->query("SELECT * from modules where screens LIKE '%".$search_content."%' ");
$response['status'] = '';
$i=0;

if($sql->rowCount() > 0){

    while ($row = $sql->fetch()) {
        $user_col_name = $row['access'];
        $qry = $connect->query("SELECT * From user where user_id = $user_id ");
        $access_from_user = $qry->fetch()[$user_col_name];
        
        if($access_from_user == 0){
            $response['status'] = 'Fetched';
            $response[$i]['display_name'] = $row['screens'];
            $response[$i]['module_name'] = $row['modules'];
            $i++;
        }else{
            if($response['status'] != 'Fetched'){
                $response['status'] = 'No Result Found';
            }
        }
    }
}else{
    $response['status'] = 'No Result Found';
}

echo json_encode($response);

// Close the database connection
$connect = null;
?>