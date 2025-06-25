<?php
$cnt = $_POST['count'];
if(isset($_POST['cheque_nos'])){
    $nos = json_decode($_POST['cheque_nos'], true);
}else{
    $nos = [];
}

for ($i = 0; $i < $cnt; $i++) {
?>

    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <?php if ($i == 0) { ?> <label for="no"> Cheque No </label> <span class="required">&nbsp;*</span>  <span class="text-danger" id="chequeNoCheck" style="display: none;"> &nbsp;&nbsp; Enter Cheque No </span><?php } ?>
            <label for="no"> </label>
            <input type="number" class="form-control chequeno" id="cheque_upd_no" name="cheque_upd_no[]" value="<?php echo $nos[$i] ?? ''; ?>">
        </div>
    </div>

<?php }
?>