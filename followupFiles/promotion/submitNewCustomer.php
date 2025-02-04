<?php
session_start();
$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');


if(isset($_POST['cus_id'])){
    $cus_id = preg_replace('/\D/', '',$_POST['cus_id']);
}
if(isset($_POST['cus_name'])){
    $cus_name = $_POST['cus_name'];
}
if(isset($_POST['cus_mob'])){
    $cus_mob = $_POST['cus_mob'];
}
if(isset($_POST['area'])){
    $area = $_POST['area'];
}
if(isset($_POST['sub_area'])){
    $sub_area = $_POST['sub_area'];
}
if(isset($_POST['update'])){
    //this update will be yes when user confirmed updating customer details when submitting the form
    $update = $_POST['update'];
}else{$update = '';}



$sql = $connect->query("SELECT * FROM customer_register WHERE cus_id = '$cus_id'");
// insert only if the customer id is not present in the customer register table
if($sql->rowCount() == 0){

    $sql = $connect->query("SELECT * FROM new_cus_promo WHERE cus_id = '$cus_id'");

    if($sql->rowCount() > 0){

        if($update == 'yes'){
            //this update query will run only when user confirmed updating 

            $sql = $connect->query("UPDATE new_cus_promo SET cus_name = '$cus_name', mobile = '$cus_mob', area = '$area', sub_area = '$sub_area', update_login_id = '$userid' WHERE cus_id = '$cus_id' ");
            //update customer details if customer id is already present in the table
            if($sql){
                $response = 'Customer Updated Successfully';
            }else{
                $response = 'Error While Updating';
            }
        }else{
            //if update is not 'yes' then ask confirmation from user to update the customer details or not
            $response = "Customer Already Added!";
            echo json_encode($response);
            return;
        }

    }else{

        $sql = $connect->query("INSERT INTO new_cus_promo(cus_id, cus_name, mobile, area, sub_area, insert_login_id,created_date) 
            VALUES('$cus_id', '$cus_name', '$cus_mob', '$area', '$sub_area','$userid',now())");
        //insert customer details if customer id is not present in the table
        if($sql){
            $response = 'Customer Added Successfully';
        }else{
            $response = 'Error While Inserting';
        }

    }

}else{
    $response = "Error! Customer Exists";
}

echo $response;

// Close the database connection
$connect = null;
?>