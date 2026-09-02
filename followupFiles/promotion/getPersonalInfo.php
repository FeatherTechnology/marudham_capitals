<?php
include('../../ajaxconfig.php');
$cus_id = $_POST['cus_id'];

$sql = $connect->query("SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name AS cus_name, cr.mobile1, cr.pic as cus_pic, al.area_name, sl.sub_area_name, alm.line_name as area_line, bc.branch_name, agm.group_name, adm.duefollowup_name
    FROM customer_register cr 
    JOIN area_list_creation al ON COALESCE(NULLIF(cr.area_confirm_area, ''), cr.area) = al.area_id
    JOIN sub_area_list_creation sl ON COALESCE(NULLIF(cr.area_confirm_subarea, ''), cr.sub_area) = sl.sub_area_id
    LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sl.sub_area_id
    LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sl.sub_area_id
    LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id 
    LEFT JOIN area_duefollowup_mapping_area adma ON al.area_id = adma.area_id
    LEFT JOIN area_duefollowup_mapping adm ON adma.duefollowup_map_id = adm.map_id
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    WHERE cr.cus_id = '$cus_id'");
$row = $sql->fetch();
?>
<div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
    <div class="row">

        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_cus_id">Aadhaar Number</label>
                <input type="text" name="info_cus_id" id="info_cus_id" class='form-control' tabindex="1" readonly value="<?php echo $row['cus_id']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_autogen_cus_id">Customer ID</label>
                <input type="text" name="info_autogen_cus_id" id="info_autogen_cus_id" class='form-control' tabindex="2" readonly value="<?php echo $row['autogen_cus_id']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_cus_name">Customer Name</label>
                <input type="text" name="info_cus_name" id="info_cus_name" class='form-control' tabindex="3" readonly value="<?php echo $row['cus_name']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_cus_mob">Mobile Number</label>
                <input type="number" name="info_cus_mob" id="info_cus_mob" class='form-control' tabindex="4" readonly value="<?php echo $row['mobile1']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_area">Area</label>
                <input type="text" name="info_area" id="info_area" class='form-control' tabindex="5" readonly value="<?php echo $row['area_name']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_sub_area">Sub Area</label>
                <input type="text" name="info_sub_area" id="info_sub_area" class='form-control' tabindex="6" readonly value="<?php echo $row['sub_area_name']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_line">Region</label>
                <input type="text" name="info_line" id="info_line" class='form-control' tabindex="7" readonly value="<?php echo $row['area_line']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_group">Sector</label>
                <input type="text" name="info_group" id="info_group" class='form-control' tabindex="8" readonly value="<?php echo $row['group_name']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_zone">Zone</label>
                <input type="text" name="info_zone" id="info_zone" class='form-control' tabindex="9" readonly value="<?php echo $row['duefollowup_name']; ?>">
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="form-group">
                <label for="info_branch">Branch</label>
                <input type="text" name="info_branch" id="info_branch" class='form-control' tabindex="10" readonly value="<?php echo $row['branch_name']; ?>">
            </div>
        </div>

    </div>
</div>

<div class="col-xl-4 col-lg-10 col-md-12 col-sm-12">
    <div class="col-xl-8 col-lg-10 col-md-6 ">
        <div class="form-group">
            <label for="info_photo">Photo</label><br>
            <img src='<?php echo 'uploads/request/customer/' . $row['cus_pic']; ?>' class='img-show' name="info_photo" id="info_photo">
        </div>
    </div>
</div>

<?php
// Close the database connection
$connect = null;
?>