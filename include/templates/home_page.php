<style>
    .media-style {
        width: 75%;
        /* adjust 60% → 80% or 100% as you like */
        max-width: 700px;
        /* limit max size */
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
</style>
<!-- Page header start -->
<br><br>
<br><br>
<br><br>
<!-- Page header end -->

<?php

include "ajaxconfig.php";
$userid = $_SESSION['userid'];
$q = $connect->query("SELECT home_access FROM user WHERE user_id='$userid'");
$row = $q->fetch();
$access = $row['home_access']; // user access for upload


$today = date("Y-m-d");
$m = $connect->query("SELECT media_path FROM home_upload WHERE  DATE(upload_date)='$today'");
$media_path = ($m->rowCount() > 0) ? $m->fetch()['media_path'] : "";
?>
<!-- upload button -->
<div class="container-fluid">
    <div class="d-flex justify-content-end align-items-center w-100">

        <div class="dash-input-div" 
             <?php if ($access == 1) { ?> style="display:none" <?php } ?>>
            
            <button type="button" 
                    class="btn btn-primary"  style="width: 100px;"
                    onclick="$('.uploadModal').modal('show');">
                Upload
            </button>

        </div>

    </div>
</div>


<!-- uploaded media show -->

<div style="text-align:center; margin-top:15px;">
    <?php if ($media_path != "") { ?>
        <?php
        $media_file_path = "uploads/home_upload/" . $media_path;
        $ext = pathinfo($media_path, PATHINFO_EXTENSION);

        if (in_array($ext, ['jpg', 'png', 'jpeg'])) {
            echo "<img src='$media_file_path' class='media-style'>";
        } elseif (in_array($ext, ['mp4', 'mov'])) {
            echo "<video class='media-style' controls>
                    <source src='$media_file_path'>
                  </video>";
        } elseif (in_array($ext, ['mp3', 'wav'])) {
            echo "<audio controls style='width:60%;'>
                    <source src='$media_file_path'>
                  </audio>";
        }
        ?>
    <?php } else { ?>
        <img src='img/logo.png' class='media-style'>
    <?php } ?>
</div>



<!-- Add Upload Modal  START -->
<div class="modal fade uploadModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Add Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- alert messages -->
                <div class="row">
                     <div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="home_upload"> Upload </label> <span class="required">&nbsp;*</span>
                            <input type="hidden" name="home_upload_hid" id="home_upload_hid">
                            <input type="file" class="form-control" id="home_upload" name="home_upload" tabindex='7'>
                            <span class="text-danger" id="home_uploadcheck" style="display:none"> Please Upload file </span>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
                        <button name="submit_upload" id="submit_upload" class="btn btn-primary" tabindex="1" style="margin-top: 18px;">&nbsp;Submit</button>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex='5'>Close</button>
            </div>
        </div>
    </div>
</div>
<!-- // submit upload -->
<script>
    $('#submit_upload').click(function() {

        // validation
        let fileInput = $('#home_upload')[0].files[0];
        if (!fileInput) {
            $("#home_uploadcheck").show();
            return false;
        } else {
            $("#home_uploadcheck").hide();
        }

        var fd = new FormData();
        fd.append('home_upload', fileInput);

        $.ajax({
            url: 'ajaxFetch/home_upload.php',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.trim() == "success") {
                    $('.uploadModal').modal('hide');
                    window.location.href = "home_page";
                } else {
                    alert("Error: " + res);
                }
            }
        });

    });
// close Modal
    function closeChartsModal() {
        $('.uploadModal').modal('hide');

    }
</script>