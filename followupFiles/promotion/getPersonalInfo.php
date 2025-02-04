<?php

include('../../ajaxconfig.php');

$cus_id = $_POST['cus_id'];

$sql = '';

$query1 = $connect->query("SELECT cp.cus_id,cp.cus_name,cp.cus_pic,cp.mobile1,al.area_name,sl.sub_area_name,alm.line_name as area_line,bc.branch_name from customer_profile cp 
    LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id 
    LEFT JOIN sub_area_list_creation sl ON cp.area_confirm_subarea = sl.sub_area_id 
    LEFT JOIN area_line_mapping alm ON FIND_IN_SET( sl.sub_area_id, alm.sub_area_id ) 
    LEFT JOIN area_group_mapping agm ON FIND_IN_SET( sl.sub_area_id, agm.sub_area_id ) 
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    WHERE cp.cus_id = " . $cus_id . " ORDER BY cp.id DESC LIMIT 1");

$query2 = $connect->query("SELECT rc.cus_id, rc.cus_name, rc.mobile1, rc.pic as cus_pic, al.area_name,sl.sub_area_name,alm.line_name as area_line,bc.branch_name FROM request_creation rc
    JOIN area_list_creation al ON rc.area = al.area_id
    JOIN sub_area_list_creation sl ON rc.sub_area = sl.sub_area_id
    LEFT JOIN area_line_mapping alm ON FIND_IN_SET( rc.sub_area, alm.sub_area_id ) 
    LEFT JOIN area_group_mapping agm ON FIND_IN_SET( sl.sub_area_id, agm.sub_area_id ) 
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    WHERE rc.cus_id = '$cus_id' ORDER BY rc.req_id DESC LIMIT 1");

if ($query1->rowCount() > 0) {
    $sql = $query1;
} else {
    $sql = $query2;
}
$row = $sql->fetch();
?>
<div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_cus_id">Customer ID</label>
            <input type="text" name="info_cus_id" id="info_cus_id" class='form-control' tabindex="1" readonly value="<?php echo $row['cus_id']; ?>">
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_cus_name">Customer Name</label>
            <input type="text" name="info_cus_name" id="info_cus_name" class='form-control' tabindex="2" readonly value="<?php echo $row['cus_name']; ?>">
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_cus_mob">Mobile Number</label>
            <input type="number" name="info_cus_mob" id="info_cus_mob" class='form-control' tabindex="3" readonly value="<?php echo $row['mobile1']; ?>">
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_area">Area</label>
            <input type="text" name="info_area" id="info_area" class='form-control' tabindex="4" readonly value="<?php echo $row['area_name']; ?>">
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_sub_area">Sub Area</label>
            <input type="text" name="info_sub_area" id="info_sub_area" class='form-control' tabindex="5" readonly value="<?php echo $row['sub_area_name']; ?>">
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_line">Line</label>
            <input type="text" name="info_line" id="info_line" class='form-control' tabindex="4" readonly value="<?php echo $row['area_line']; ?>">
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <label for="info_branch">Branch</label>
            <input type="text" name="info_branch" id="info_branch" class='form-control' tabindex="5" readonly value="<?php echo $row['branch_name']; ?>">
        </div>
    </div>
</div>
<div class="col-xl-4 col-lg-10 col-md-12 col-sm-12">
    <div class="col-xl-8 col-lg-10 col-md-6 ">
        <label for="info_photo">Photo</label><br>
        <img src='<?php echo 'uploads/request/customer/' . $row['cus_pic']; ?>' class='img-show' name="info_photo" id="info_photo">
    </div>
</div>

<?php
// Close the database connection
$connect = null;
?>