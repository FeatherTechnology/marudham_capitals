<link rel="stylesheet" type="text/css" href="css/finance_insights.css" />
<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#009688; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Marudham Capitals - Agent Balance Sheet
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="agent_balance_sheet_form" name="agent_balance_sheet_form" action="" method="post" enctype="multipart/form-data">
		<div class="row gutters" style="margin-left: 0;margin-right: 2px;">

			<div class="toggle-container col-12">
				<select type="text" class="toggle-button" id='ag_view_type' name='ag_view_type'>
					<option value=''>Select View type</option>
					<option value='1'>Overall</option>
					<option value='2'>Agent wise</option>
				</select>

				<select class="toggle-button ag_namewiseDiv" id='ag_namewise' name='ag_namewise' style="display:none;">
					<option value=''>Select Agent</option>
				</select>
			</div>

			<div class="card ag_card col-sm-12 col-md-12 col-lg-12 col-xl-12 col-12">
				<div class="card-header">
					<div class="card-title">Agent Balance Sheet</div>
				</div>
				<div class="card-body blncSheetDiv">
				</div>
			</div>

		</div>

	</form>
</div>