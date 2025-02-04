<?php 
@session_start();
if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
}

$id=0;
$loanCategoryList = $userObj->getloanCategoryList($mysqli);
if(isset($_POST['submit_doc_mapping']) && $_POST['submit_doc_mapping'] != '')
{
    if(isset($_POST['id']) && $_POST['id'] >0 && is_numeric($_POST['id'])){		
        $id = $_POST['id']; 	
		$updateDocumentMapping = $userObj->updateDocumentMapping($mysqli,$id, $userid);  
    ?>
	<script>location.href='<?php echo $HOSTPATH;  ?>edit_doc_mapping&msc=2';</script>
    <?php	}
    else{   
		$addDocumentMapping = $userObj->addDocumentMapping($mysqli, $userid);   
        ?>
    <script>location.href='<?php echo $HOSTPATH;  ?>edit_doc_mapping&msc=1';</script>
        <?php
    }
}

$del=0;
$costcenter=0;
if(isset($_GET['del']))
{
$del=$_GET['del'];
}
if($del>0)
{
	$deleteDocumentMapping = $userObj->deleteDocumentMapping($mysqli,$del, $userid); 
	//die;
	?>
	<script>location.href='<?php echo $HOSTPATH;  ?>edit_doc_mapping&msc=3';</script>
<?php	
}

if(isset($_GET['upd']))
{
$idupd=$_GET['upd'];
}
$status =0;
if($idupd>0)
{
	$getDocumentMapping = $userObj->getDocumentMapping($mysqli,$idupd); 
	if (sizeof($getDocumentMapping)>0) {
        for($i=0;$i<sizeof($getDocumentMapping);$i++)  {			
			$doc_map_id						= $getDocumentMapping['doc_map_id'];
			$loan_category					= $getDocumentMapping['loan_category'];
			$sub_category					= $getDocumentMapping['sub_category'];
			$doc_creation					= $getDocumentMapping['doc_creation'];
		}
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
    <div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals -  Documentation Mapping 
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
    <a href="edit_doc_mapping">
        <button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
    <!-- <button type="button" class="btn btn-primary"><span class="icon-border_color"></span>&nbsp Edit Employee Master</button> -->
    </a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id = "doc_mapping" name="doc_mapping" action="" method="post" enctype="multipart/form-data"> 
		<input type="hidden" class="form-control" value="<?php if(isset($idupd)) echo $idupd; ?>"  id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($doc_map_id)) echo $doc_map_id; ?>"  id="doc_map_id_upd" name="doc_map_id_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($loan_category)) echo $loan_category; ?>"  id="loan_category_upd" name="loan_category_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($sub_category)) echo $sub_category; ?>"  id="sub_category_upd" name="sub_category_upd" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="<?php if(isset($doc_creation)) echo $doc_creation; ?>"  id="doc_creation_upd" name="doc_creation_upd" aria-describedby="id" placeholder="Enter id">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">General Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 "> 
								<div class="row">
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label class="label">Loan Category</label><span class="required">&nbsp;*</span>
												<select tabindex="1" type="text" class="form-control" id="loan_category" name="loan_category">
													<option value="">Select Loan Category</option> 
													<?php if (sizeof($loanCategoryList)>0) { 
														for($j=0;$j<count($loanCategoryList);$j++) { ?>
															<option <?php if(isset($loan_category)) { if($loanCategoryList[$j]['loan_category_name_id'] == $loan_category )  echo 'selected'; }  ?> value="<?php echo $loanCategoryList[$j]['loan_category_name_id']; ?>">
															<?php echo $loanCategoryList[$j]['loan_category_name'];?></option>
														<?php }} ?>  
												</select> 
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Sub Category</label><span class="required">&nbsp;*</span>
											<select tabindex="2" type="text" class="form-control" id="sub_category" name="sub_category" >
												<option value="">Select Sub Category</option> 
												</select> 
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12"></div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Documentation Creation</label><span class="required">&nbsp;*</span>
											<input type="hidden" class="form-control" id="doc_creation" name="doc_creation" value='<?php if(isset($doc_creation)) echo $doc_creation; ?>'>
											<select tabindex="3" type="text" class="form-control" id="doc_creation1" name="doc_creation1" multiple>
												<option value="">Select Documentation</option> 
												</select> 
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submit_doc_mapping" id="submit_doc_mapping" class="btn btn-primary" value="Submit" tabindex="19"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="20" >Clear</button>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>

