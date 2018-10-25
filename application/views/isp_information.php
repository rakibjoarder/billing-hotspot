<?php
	require_once('header.php');
	if( (permission("Set","Edit",$permission_string)===0) || permission("Set","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>


<style>
.wizard-card .nav-pills {
		background-color: #3c4858 !important;
	}

	.ekko-lightbox-nav-overlay a:last-child span {
    display: none !important;
}
.ekko-lightbox-nav-overlay a span {
    display: none !important;
}

.carousel-control.right {
    background-image: none !important;
}

.carousel-control.left {
    background-image: none !important;
}

.glyphicon-chevron-right:before {
    color: #000000 !important;
}
.glyphicon-chevron-left:before {
    color: #000000 !important;
}

</style>

<?php


	foreach($settings_config as $settings_config_indiv):
		if($settings_config_indiv['key']=='isp_image'){

			$isp_image = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='isp_name'){
			$isp_name = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='isp_address'){
			$isp_address = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='isp_description'){
			$isp_description = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='id_isp_info'){
			$id_isp_info = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='isp_email'){
			$isp_email = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='isp_password'){
			$isp_password = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='smtp_host'){
			$smtp_host = $settings_config_indiv['value'];
		}if($settings_config_indiv['key']=='smtp_port'){
			$smtp_port= $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='sys_update'){
			$sys_update = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='query_limit'){
			$query_limit = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='default_zone'){
			$default_zone = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='sms_client_code'){
			$sms_client_code = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='invoice_template'){
			$invoice_template = $settings_config_indiv['value'];
		}
		if($settings_config_indiv['key']=='max_allowed_due'){
			 $max_allowed_due = $settings_config_indiv['value'];
		}

	endforeach;
?>


<div class="container-fluid">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="wizard-container">
			<div class="card wizard-card" data-color="blue" id="wizardProfile">
				<form method="post"  id="form_admin" enctype="multipart/form-data">
					<div class="wizard-header">
						<h3 class="wizard-title">
							General Settings
						</h3>
					</div>
					<div class="wizard-navigation">
						<ul style="color:white !important;" >
							<li>
								<a href="#invoice_temp" data-toggle="tab" style="color:white !important;" >Invoice Template</a>
							</li>
							<li>
								<a href="#invoice_info" data-toggle="tab" style="color:white !important;" >Invoice Information</a>
							</li>
							<li>
								<a href="#system_info" data-toggle="tab" style="color:white !important;">System Information</a>
							</li>
							<li>
								<a href="#smtp_info" data-toggle="tab" style="color:white !important;">SMTP Information</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">

						<div class="tab-pane" id="invoice_temp">

							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">

										<div class="row">

											<div id="myCarousel" class="carousel slide " data-ride="carousel" data-interval="false">
												<div class="carousel-inner">

													<?php
													foreach($invoice_templates as $template){?>
													<div class="item <?=( $template['id_invoice_template'] == $invoice_template  ? 'active': '')?>">
														<div class="col-lg-6 cl-sm-12 col-sm-offset-3">
															<div class="card card-pricing card-raised temp_select_div" id ='temp_select_<?=$template['id_invoice_template']?>_div' <?=( $template['id_invoice_template'] == $invoice_template  ? 'style="background-color:#4CAF50"': '')?>>
																<div class="content">
																	<h6 class="category"><span style="color:black;">  <?=$template['template_name']?> </span></h6>
																	<div class="icon icon-rose">
																		<a href="<?php echo base_url();?><?=$template['template_image']?>" data-toggle="lightbox" data-title="<?=$template['template_name']?>" >
																			<img src="<?php echo base_url();?><?=$template['template_image']?>" class="img-fluid" style="height :150px !important;">
																		</a>
																	</div>
																	<a class="btn btn-info btn-round temp_btn" val="<?=$template['id_invoice_template']?>" id="temp_select_<?=$template['id_invoice_template']?>"><span  class="temp_select_text" id="temp_select_<?=$template['id_invoice_template']?>_text"><?=( $template['id_invoice_template'] == $invoice_template  ? 'Selected': 'Select')?></span> </a>
																</div>
															</div>
														</div>
													</div>
													<?php
												}
												?>

												</div>
												<!-- Left and right controls -->
												<a class="left carousel-control" href="#myCarousel" data-slide="prev">
													<span class="glyphicon glyphicon-chevron-left"></span>
													<span class="sr-only">Previous</span>
												</a>
												<a class="right carousel-control" href="#myCarousel" data-slide="next">
													<span class="glyphicon glyphicon-chevron-right"></span>
													<span class="sr-only">Next</span>
												</a>
											</div>

										</div>
											<input type="hidden" id="invoice_template" name="invoice_template"   value="<?php echo $invoice_template;?>" />
										</div>
									</div>
								</div>
							</div>


						<div class="tab-pane" id="invoice_info">
							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">

										<div class="fileinput fileinput-new " data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img src="<?php echo base_url();?>assets/img/<?=$isp_image?>" alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new">Select image</span>
													<span class="fileinput-exists">Change</span>
													<input type="file"  name="file" />
												</span>
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
											</div>
										</div>

										<div class="form-group label-floating">
											<label class="control-label">ISP Name</label>
											<input type="text" class="form-control" id="isp_name" maxlength="20" required  name="isp_name" value="<?= $isp_name?>">
										</div>

										<div class="form-group  label-floating">
											<label class="control-label">Invoice Foot Note</label>
											<input type="text" class="form-control" id="isp_description" required  name="isp_description"  maxlength="90" value="<?= $isp_description?>">
										</div>

										<div class="form-group label-floating">
											<label class="control-label">ISP Address</label>
											<input type="text" class="form-control" id="isp_address" required  name="isp_address"  maxlength="26" value="<?= $isp_address?>">
										</div>



										<div class="form-group label-floating">
											<label class="control-label">Maximum Allowed Due</label>
											<input type="text" class="form-control" id="max_allowed_due" required  name="max_allowed_due"  maxlength="26" value="<?= $max_allowed_due?>">
										</div>


									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="system_info">
							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">

										<div class="form-group label-floating">

											<label class="control-label">System Update:</label>
											<select class="form-control" name="sys_update" id='sys_update'>
												<?php if($sys_update=="auto") { ?>
													<option selected="selected" value='auto'>Automatic</option>
													<option  value='manual'>Manual</option>
												<?php	} ?>
												<?php if($sys_update=="manual") { ?>
													<option value='auto'>Automatic</option>
													<option selected="selected" value='manual'>Manual</option>
												<?php	} ?>
											</select>
										</div>

										<div class="form-group label-floating">
											<label class="control-label">Select Default Zone</label>
											<select  name="id_zone" id='id_zone' class="form-control" >
												<option disabled="" selected=""></option>
												<?php foreach ($zones as $zone):
													if($default_zone==$zone['id_zone']) { ?>
														<option selected value="<?= $zone['id_zone']?>"><?= $zone['zone_name'] ?></option>
													<?php }
													else { ?>
														<option  value="<?= $zone['id_zone']?>"><?= $zone['zone_name'] ?></option>
													<?php } ?>
												<?php endforeach; ?>
											</select>
										</div>

										<div class="form-group label-floating">
											<label class="control-label">Max Search Data</label>
											<input type="text" id="query_limit" name="query_limit" class="form-control" required  value="<?php echo $query_limit;?>" />
										</div>

										<div class="form-group label-floating">
											<label class="control-label">Sms Client Code</label>
											<input type="text" id="sms_client_code" name="sms_client_code" class="form-control" required  value="<?php echo $sms_client_code;?>" />
										</div>

									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="smtp_info">
							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">
										<div class="form-group label-floating">
											<label class="control-label">Smtp Host Name</label>
											<input type="text" class="form-control" id="smtp_host" required  name="smtp_host"   value="<?= $smtp_host?>">
										</div>

										<div class="form-group label-floating">
											<label class="control-label">Smtp Port Number</label>
											<input type="text" class="form-control" id="smtp_port" required  name="smtp_port"   value="<?= $smtp_port?>">
										</div>


										<div class="form-group label-floating">
											<label class="control-label">Email</label>
											<input type="email" class="form-control" id="isp_email" required  name="isp_email"   value="<?= $isp_email?>">
										</div>

										<div class="form-group label-floating">
											<label class="control-label">Password</label>
											<input type="password" class="form-control" id="isp_password" required  name="isp_password"   value="<?= $isp_password?>">
											<div class="checkbox">
												<label>
													<input type="checkbox" onclick="myFunction()">Show Password
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="wizard-footer">
						<div class="pull-right">

							<!-- <input type='button' class='btn btn-next btn-fill btn-info btn-wd' name='next' value='Next' /> -->
							<button type="submit" name="submit"  class='btn btn-fn btn-fill btn-info btn-wd' value="submit">Submit</button>
						</div>
						<div class="pull-left">
							<!-- <input type='button' class='btn btn-previous btn-fill btn-info btn-wd' name='previous' value='Previous' /> -->
						</div>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	require_once('footer.php');
?>

<script type="text/javascript">
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});


$('.temp_btn').on('click',function(event){

 $(".temp_select_div").css("background-color", "");
 $(".temp_select_text").text('Select');

 var div_id="#"+$(this).attr('id')+"_div";
 var select_text_id="#"+$(this).attr('id')+"_text";
 $(div_id).css("background-color", "#4CAF50");
 $(select_text_id).text('Selected');
 $("#invoice_template").val($(this).attr('val'));

});


	initWizard();




function myFunction() {
    var x = document.getElementById("isp_password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
$(document).ready(function(){
	$("#form_admin").on('submit',(function(e){
		e.preventDefault();
		$.ajax({
			url: "<?php echo base_url() ?>settings/add_isp_information",
			type: "POST",
			data:  new FormData(this),
			dataType:"json",
			contentType: false,
			cache: false,
			processData:false,
			success: function(response){
				if(response.status === 'success') {
						showNotification(2,response.msg);
				} else if(response.status === 'failed') {
						showNotification(3,response.msg);
				}
			},
			error: function (result) {
					 showNotification(3,"Error " + JSON.stringify(result));
			}
			});
		}));

		// $("#form_admin").validate({
		//
		// 	submitHandler: function (form) {
		// 		var reqData = $("#form_admin").serialize();
		//
		// 		$.ajax({
		// 			type:"POST",
		// 			contentType: false,
		// 			dataType:"json",
		// 			data: reqData,
		// 			cache:false,
		// 			contentType: false,
		// 			processData: false,
		// 			url: "<?php echo base_url() ?>settings/add_isp_information",
		//
		// 			success:function(response) {
		// 				if(response.status === 'success') {
		// 						showNotification(2,response.msg);
		// 				} else if(response.status === 'failed') {
		// 						showNotification(3,response.msg);
		// 				}
		// 			},
		// 			error: function (result) {
		// 				   showNotification(3,"Error " + JSON.stringify(result));
		// 			}
		// 		});
		// 	}
		// });
});

</script>
