	<?php
	require_once('header.php');
	if(permission("Cus","Edit",$permission_string)===0 || permission("Cus","View",$permission_string)=== -1){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}
	?>

	<?php

	foreach($customers as $customer_indiv):
		$net_user_name       			 = $customer_indiv['net_user_name'];
		$net_user_phone      			 = $customer_indiv['net_user_phone'];
		$net_user_email      			 = $customer_indiv['net_user_email'];
		$net_user_password   			 = $customer_indiv['net_user_password'];
		$net_user_username   			 = $customer_indiv['net_user_username'];
		$id_package          			 = $customer_indiv['id_package'];
		$net_user_address    			 = $customer_indiv['net_user_address'];
		$net_user_mrc_price  			 = $customer_indiv['net_user_mrc_price'];
		$id_net_user         			 = $customer_indiv['id_net_user'];
		$repeat_every        			 = $customer_indiv['repeat_name'];
		$id_payment_term     			 = $customer_indiv['id_payment_term'];
		$package_name        			 = $customer_indiv['package_name'];
		$net_user_ip_address_block = $customer_indiv['net_user_ip_address_block'];
		$net_user_ip_address       = $customer_indiv['net_user_ip_address'];
		$net_user_mac              = $customer_indiv['net_user_mac'];
		$net_user_type             = $customer_indiv['net_user_type'];
		$id_net_user_type          = $customer_indiv['id_net_user_type'];
		$id_package                = $customer_indiv['id_package'];
		$id_repeat_every           = $customer_indiv['id_repeat_every'];
		$service_type              = $customer_indiv['service_type'];
		$billing_amount            = $customer_indiv['net_user_billing_amount'];
		$radio_flag                = $customer_indiv['radio_flag'];
		$discount                  = $customer_indiv['discount'];
		$id_zone                   = $customer_indiv['id_zone'];
		$id_router                 = $customer_indiv['id_router'];
		$is_active                 = $customer_indiv['is_active'];
		$net_user_nid              = $customer_indiv['net_user_nid'];
	endforeach;
	?>

	<?php
		$prev_net_user_mac = $net_user_mac;
		$profiles = $this->router_model->get_all_profiles_by_router_id($id_router);
		$ip_pools = $this->router_model->get_all_ip_pools_by_router_id($id_router);

	?>

	<style>
	.wizard-card .nav-pills {
		background-color: #3c4858 !important;
	}
	</style>

	<div class="container-fluid">
		<div class="col-sm-8 col-sm-offset-2">
			<!--      Wizard container        -->
			<div class="wizard-container">
				<div class="card wizard-card" data-color="blue" id="wizardProfile">
					<form method="post"  id="form_admin" enctype="multipart/form-data">
						<!--        You can switch " data-color="purple" "  with one of the next bright colors: "green", "orange", "red", "blue"       -->
						<div class="wizard-header">
							<h3 class="wizard-title">
								Edit Customer
							</h3>
						</div>
						<div class="wizard-navigation">
							<ul style="color:white !important;">
								<li>
									<a href="#con_info" data-toggle="tab" style="color:white !important;">Connection Information</a>
								</li>
								<li>
									<a href="#cus_info" data-toggle="tab" style="color:white !important;">Customer Information</a>
								</li>
								<li>
									<a href="#bill_info" data-toggle="tab" style="color:white !important;">Billing Information</a>
								</li>
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane" id="cus_info">
								<div class="  row">
									<div class="card-content">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group label-floating">
												<label class="control-label">Name</label>
												<input type="text" class="form-control" id="net_user_name" required  name="net_user_name" value="<?=$net_user_name?>">
											</div>

											<div class="form-group label-floating">
												<label class="control-label">Address</label>
												<input type="text" class="form-control" id="net_user_address" required  name="net_user_address"  value="<?=$net_user_address?>">
											</div>

											<div class="form-group label-floating">
												<label class="control-label">Phone Number</label>
												<input type="text" class="form-control" id="net_user_phone"   name="net_user_phone"  value="<?=$net_user_phone?>" minLength="6">
											</div>

											<div class="form-group label-floating">
												<label class="control-label">NID Number</label>
												<input type="text" class="form-control" id="net_user_nid"   name="net_user_nid"  value="<?=$net_user_nid?>" >
											</div>

											<div class="form-group label-floating">
												<label class="control-label">Email</label>
												<input type="text" class="form-control" id="net_user_email"   name="net_user_email"  value="<?=$net_user_email?>">
											</div>

											<div class="form-group label-floating">
												<label class="control-label">Select Zone</label>
												<select  name="id_zone" id='id_zone' class="form-control" required >
													<option disabled="" selected=""></option>
													<?php foreach ($zones as $zone):
														if($id_zone==$zone['id_zone']) { ?>
															<option selected value="<?= $zone['id_zone']?>"><?= $zone['zone_name'] ?></option>
														<?php }
														else { ?>
															<option  value="<?= $zone['id_zone']?>"><?= $zone['zone_name'] ?></option>
														<?php } ?>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

									</div>


								</div>
							</div>
							<div class="tab-pane" id="con_info">

								<div class="row">
									<div class="card-content">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group label-floating">
												<label class="control-label">User Name</label>
												<input type="text" class="form-control" id="net_user_username"  value="<?= $net_user_username ?>"  name="net_user_username"required>
											</div>

											<div class="form-group label-floating">
												<label class="control-label">Connection Type</label>
												<select  name="id_net_user_type" id='id_net_user_type' class="form-control" required>
													<option disabled="" selected=""></option>
													<?php foreach ($net_user_types as $category):
														if($id_net_user_type==$category['id_net_user_type']) { ?>
															<option selected value="<?= $category['id_net_user_type']?>"><?= $category['net_user_type'] ?></option>
														<?php }
														else { ?>
															<option value="<?= $category['id_net_user_type']?>"><?= $category['net_user_type'] ?></option>
														<?php } ?>
													<?php endforeach; ?>
												</select>
											</div>

											<div class="form-group label-floating ">
												<label class="control-label">Select Router</label>
												<select  name="id_router" id='id_router' class="form-control" required>
													<option disabled="" value="-1" selected></option>
													<?php foreach ($routers as $router):
														if($id_router == $router['id_router'] ){
															?>
															<option selected value="<?=$router['id_router']?>"><?=$router['name']?></option>
														<?php }else { ?>
															<option  value="<?=$router['id_router']?>"><?=$router['name']?></option>
														<?php }
														endforeach; ?>

													</select>
												</div>


													<div class="form-group label-floating section_profile hide ">
														<label class="control-label">Select profile</label>
														<select  name="id_profile" id='id_profile' class="form-control"  >
															<option disabled="" value="-1" selected></option>
															<?php foreach ($profiles as $profile):
																if($id_profile == $profile['id_profile'] ){
																	?>
																	<option selected value="<?=$profile['id_profile']?>"><?=$profile['profile_name']?></option>
																<?php }else { ?>
																	<option  value="<?=$profile['id_profile']?>"><?=$profile['profile_name']?></option>
																<?php }
															endforeach; ?>
														</select>
													</div>


													<div class="form-group label-floating section_ip_pool hide ">
														<label class="control-label">Select pool</label>
														<select  name="id_ip_pool" id='id_ip_pool' class="form-control"  >
															<option disabled="" value="-1" selected></option>
															<?php foreach ($ip_pools as $pool):
																if($id_ip_pool == $pool['id_ip_pool'] ){
																	?>
																	<option selected value="<?=$pool['id_ip_pool']?>"><?=$pool['ip_pool_name']?></option>
																<?php }else { ?>
																	<option  value="<?=$pool['id_ip_pool']?>"><?=$pool['ip_pool_name']?></option>
																<?php }
															endforeach; ?>
														</select>
													</div>

													<div class="form-group label-floating section_mac">
														<label class="control-label">Mac Address</label>
														<input type="text" class="form-control" id="net_user_mac"  name="net_user_mac"  value="<?=$net_user_mac?>">
													</div>

													<div class="form-group label-floating section_ip_address">
														<label class="control-label">IP Address</label>
														<input type="text" class="form-control" id="net_user_ip_address"  name="net_user_ip_address"  value="<?=$net_user_ip_address?>">
													</div>

													<div class="form-group label-floating section_ip_block hide">
														<label class="control-label">IP Address Block</label>
														<input type="text" class="form-control" id="net_user_ip_address_block"  name="net_user_ip_address_block"  value="<?=$net_user_ip_address_block?>">
													</div>

													<div class="form-group label-floating">
														<label class="control-label">Password</label>
														<input type="password" class="form-control" id="net_user_password"   name="net_user_password" minLength="6" >
													</div>

													<div class="form-group label-floating">
														<label class="control-label">Confirm Password</label>
														<input type="password" class="form-control" id="net_user_confirm_pwd"   name="net_user_confirm_pwd"  equalTo="#net_user_password">
													</div>

													<div class="togglebutton form-group ">
														<label> <span style="color:black;margin-right:10px;" id="active_inactive"> <?=(($is_active == 0)? 'Active': 'Disable')?> </span>
															<input type="checkbox" id="is_active" name="is_active" <?=(($is_active == 0)? 'checked': '')?> value="<?= $is_active ?>" />
														</label>
													</div>

												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="bill_info">
										<div class="row">
											<div class="card-content">
												<div class="col-md-10 col-md-offset-1">
													<div class="form-group label-floating">
														<label class="control-label">Package</label>
														<select  name="id_package" id='id_package' class="form-control" required>
															<option disabled="" selected=""></option>
															<?php foreach ($packages as $package):
																if($id_package==$package['id_package']) { ?>
																	<option  selected value="<?= $package['id_package']?>" price="<?= $package['package_price']?>" bandwidth="<?= $package['package_speed']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
																<?php }
																else { ?>
																	<option  value="<?= $package['id_package']?>" price="<?= $package['package_price']?>" bandwidth="<?= $package['package_speed']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
																<?php } ?>
															<?php endforeach; ?>
														</select>
													</div>


													<div  class="form-group label-floating" id="assigned_bandwidth_id">
														<label class="control-label">MRC Price</label>
														<input type="text" class="form-control" id="net_user_mrc_price" required  readonly name="net_user_mrc_price" value="<?= $net_user_mrc_price?>" required>
													</div>

													<div class="radio" style="margin-top:-4px;" id="discount_div" >
														<label style="margin-left:-36px;color: #434143 !important;"for="full_name">Discount :</label>

														<?php if($radio_flag==2)
														{?>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio"  value="none" id="none">none </label>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio" checked value="percentage" id="percentage"> percentage(%)</label>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio" value="amount" id="amount"> fixed amount</label>
															<input style="margin-top:-20px;" type="text" hidden class="form-control" value="<?=$discount?>" id="discount"   placeholder="Discount" name="discount" >

														<?php	}else if($radio_flag==3){
															?>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio"  value="none" id="none">none </label>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio"  value="percentage" id="percentage"> percentage(%)</label>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio" value="amount"checked id="amount"> fixed amount</label>
															<input style="margin-top:-20px;" type="text" hidden class="form-control" value="<?=$discount?>" id="discount"   placeholder="Discount" name="discount" >
															<?php
														}
														else if($radio_flag==1){
															?>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio" checked value="none" id="none">none </label>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio"  value="percentage" id="percentage"> percentage(%)</label>
															<label style="margin-top:10px;color: #434143 !important;"><input type="radio" name="discount_radio" value="amount" id="amount"> fixed amount</label>
															<input style="margin-top:-20px;display:none;" type="text" hidden class="form-control" value=0 id="discount"   placeholder="Discount" name="discount" >
															<?php
														}
														?>




													</div>

													<div style="margin-top:-30px;" class="form-group " id="cal_price_div">
														<label class="control-label">Billing Amount</label>
														<input  type="text" class="form-control" id="discount_" value="<?=$billing_amount?>" required placeholder="discount" name="discount_"required>
													</div>
													<input  type="hidden" id="radio_flag" value="<?= $radio_flag ?>" placeholder="radio_flag" name="radio_flag">
													<div class="form-group label-floating">
														<label class="control-label">Service Duration</label>
														<select  name="id_repeat_every" id='id_repeat_every' class="form-control" required>
															<option disabled="" selected=""></option>
															<?php foreach ($repeat_everys as $category):
																if($id_repeat_every==$category['id_repeat_every']) { ?>
																	<option selected value="<?= $category['id_repeat_every']?>"><?= $category['repeat_name'] ?></option>
																<?php }
																else { ?>
																	<option  value="<?= $category['id_repeat_every']?>"><?= $category['repeat_name'] ?></option>
																<?php } ?>
															<?php endforeach; ?>
														</select>
													</div>



													<div class="form-group label-floating">
														<label class="control-label">Payment Mode</label>
														<div class="radio">

															<?php if($service_type=="Recurring")
															{?>
																<label style="margin-left:20px;margin-top:10px;color: #434143 !important;"><input type="radio" name="optionsRadios" value="Recurring" checked="true" > Recurring </label>
																<label style="margin-left:20px;margin-top:10px;color: #434143 !important;"><input type="radio" name="optionsRadios" value="One Time"  > One Time </label>

															<?php	}else if($service_type=="One Time"){
																?>
																<label style="margin-left:20px;margin-top:10px;color: #434143 !important;"><input type="radio" name="optionsRadios" value="Recurring"  > Recurring </label>
																<label style="margin-left:20px;margin-top:10px;color: #434143 !important;"><input type="radio" name="optionsRadios"  value="One Time"checked="true"  > One Time </label>
																<?php
															}
															?>
														</div>
													</div>

													<!-- <button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button> -->
													<?php	if(permission("Cus","View",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1){ ?>
														<!-- <a href="<?php echo base_url();?>ppp" class="btn btn-fill btn-info" >Back</a> -->
													<?php } ?>
												</div>
											</div>

										</div>
									</div>
								</div>
								<input type="hidden" value="<?php echo $id_net_user; ?>" id="id_net_user" name="id_net_user" >
								<input type="hidden" value="<?php echo $net_user_username; ?>" id="prv_username" name="prv_username" >
								<input type="hidden" value="<?php echo $prev_net_user_mac; ?>" id="prv_mac" name="prv_mac" >
								<input type="hidden" value="<?php echo $net_user_password; ?>" id="prv_password" name="prv_password" >
								<div class="wizard-footer">
									<div class="pull-right">
										<input type='button' class='btn btn-next btn-fill btn-info btn-wd' name='next' value='Next' />
										<button type="submit" name="submit"  class='btn btn-finish btn-fill btn-info btn-wd' value="submit">Submit</button>

									</div>
									<div class="pull-left">
										<input type='button' class='btn btn-previous btn-fill btn-info btn-wd' name='previous' value='Previous' />
									</div>
									<div class="clearfix"></div>
								</div>
							</form>
						</div>
					</div>
					<!-- wizard container -->
				</div>
			</div>




			<?php
			require_once('footer.php');
			?>


<script type="text/javascript">


$('input[type=radio][name=discount_radio]').change(function() {
	if (this.value == 'percentage') {
		document.getElementById("cal_price_div").style.marginTop = "-5px";
		var a 				= parseFloat($("#discount").val())/100;
		var mrc_price	= $('#net_user_mrc_price').val();
		var cal_price	= mrc_price-mrc_price*a;
		$("#discount_").val(cal_price);
		$("#discount").show(500);
		$("#discount").prop('readonly', false);
		$("#radio_flag").val(2);

		if(!a){
			$("#discount_").val(mrc_price);
		}
	}
	else if (this.value == 'amount') {
		document.getElementById("cal_price_div").style.marginTop = "-5px";
		var a 				=parseFloat($("#discount").val());
		var mrc_price	=$('#net_user_mrc_price').val();
		var cal_price	=mrc_price-a;
		$("#discount_").val(cal_price);
		$("#radio_flag").val(3);
		$("#discount").show(500);
		$("#discount").prop('readonly', false);

		if(!a){
			$("#discount_").val(mrc_price);
		}

	}
	else if (this.value == 'none') {
		document.getElementById("cal_price_div").style.marginTop = "-40px";
		var mrc_price=$('#net_user_mrc_price').val();
		$("#discount").hide(500);
		$("#discount_").val(mrc_price);
		$("#discount").val(0);
		$("#radio_flag").val(1);
	}
});

$("#discount").bind("change paste keyup", function() {

	var a =parseFloat($("#discount").val());

	if($('input[name=discount_radio]:checked', '#form_admin').val()=='percentage')
	{
		var a =parseFloat($("#discount").val())/100;
		var mrc_price=$('#net_user_mrc_price').val();
		var cal_price=mrc_price-mrc_price*a;
		$("#discount_").val(cal_price);
		$("#radio_flag").val(2);

		if(!a){
			$("#discount_").val(mrc_price);
		}
	}
	if($('input[name=discount_radio]:checked', '#form_admin').val()=='amount')
	{
		var a =parseFloat($("#discount").val());
		var mrc_price=$('#net_user_mrc_price').val();
		var cal_price=mrc_price-a;
		$("#discount_").val(cal_price);
		$("#radio_flag").val(3);
		if(!a){
			$("#discount_").val(mrc_price);
		}
	}
	if($('input[name=discount_radio]:checked', '#form_admin').val()=='none')
	{
		$("#discount_").val(0);
		$("#radio_flag").val(1);
	}
});

$('select[name="id_package"]').on('change',function(){
	$("#discount_div").show(500);
	$("#cal_price_div").show(500);

  document.getElementById("none").checked = true;
	$("#radio_flag").val(1);
$("#discount").hide(500);
	$("#assigned_bandwidth_id").show(500);
		var price=$('option:selected', this).attr("price");
	  var bandwidth=$('option:selected', this).attr("bandwidth");
		$('#net_user_mrc_price').val(price);
		$("#discount_").val(price);
			$('#net_user_assigned_bandwidth').val(bandwidth);
				document.getElementById("cal_price_div").style.marginTop = "-40px";
});


$(document).ready(function(){
				initWizard();

				$("#is_active").click( function(){
					if( $(this).is(':checked') ){
						$('#active_inactive').text('Active');
					}else{
						$('#active_inactive').text('Disable');
					}
				});


				$('#net_user_ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
					translation: {
						'Z': {
							pattern: /[0-9]/, optional: true
						}
					}
				});

				$('#net_user_phone').mask('ASY00000000', {'translation': {
					A: {pattern: /[0]/},
					S: {pattern: /[1]/},
					Y: {pattern: /[5-9]/}
				}
			});
			var id_net_user_type=<?=$id_net_user_type?>;
		//at_page_loading
		if(  id_net_user_type == 2 || id_net_user_type == 4) {
			$('.section_profile').addClass('hide');
			$('.section_ip_pool').addClass('hide');
		} else {
			$('.section_profile').removeClass('hide');
			$('.section_ip_pool').removeClass('hide');
		}

		 if(id_net_user_type == 4) {
			 $('.section_ip_block').removeClass('hide');
 			 $('.section_mac').addClass('hide');
 			 $('.section_ip_address').addClass('hide');
 		} else {
 		   $('.section_ip_block').addClass('hide');
 		 	 $('.section_mac').removeClass('hide');
 			 $('.section_ip_address').removeClass('hide');
 		}

		$('#id_net_user_type').on('change', function(){
			// alert("Net Type " + $('#id_net_user_type').val());
			if($('#id_net_user_type').val() == 2 || $('#id_net_user_type').val() == 4) {
				$('.section_profile').addClass('hide');
				$('.section_ip_pool').addClass('hide');
			} else {
				$('.section_profile').removeClass('hide');
				$('.section_ip_pool').removeClass('hide');
			}

			 if($('#id_net_user_type').val() == 4) {
				 $('.section_ip_block').removeClass('hide');
 				 $('.section_mac').addClass('hide');
 				 $('.section_ip_address').addClass('hide');
 			} else {
 				 $('.section_ip_block').addClass('hide');
 				 $('.section_mac').removeClass('hide');
 				 $('.section_ip_address').removeClass('hide');
 			}
		 });


		 $('#id_router').on('change', function() {

			 $.ajax({
				 type:"POST",
				 contentType: "application/x-www-form-urlencoded",
				 dataType:"json",
				 data: {'router_id': $(this).val()},
				 url: "<?php echo base_url() ?>ppp/get_all_profile_by_router",

				 success:function(response) {
					 if(response.status === 'passed') {
						 console.log("profile Data Length" + response.profile.length);
						 // now updating profile comboboxd.
						 $('#id_profile').find('option').remove().end();
						 $('#id_profile').append(new Option('', '-1'));
						 for(var i = 0; i < response.profile.length; i++) {
							 $('#id_profile').append(new Option(response.profile[i]['profile_name'], response.profile[i]['id_profile']));
						 }
					 } else if(response.status === 'failed') {
						 showNotification(3,response.msg);
					 }
				 },
				 error: function (result) {
					 showNotification(3, "Error " + JSON.stringify(result));
				 }
			 }),
			 $.ajax({
				 type:"POST",
				 contentType: "application/x-www-form-urlencoded",
				 dataType:"json",
				 data: {'router_id': $(this).val()},
				 url: "<?php echo base_url() ?>ppp/get_all_ip_pools_by_router_id",

				 success:function(response) {
					 if(response.status === 'passed') {
						 // now updating profile comboboxd.
						 $('#id_ip_pool').find('option').remove().end();
						 $('#id_ip_pool').append(new Option('', '-1'));
						 for(var i = 0; i < response.ip_pools.length; i++) {
							 $('#id_ip_pool').append(new Option(response.ip_pools[i]['ip_pool_name'], response.ip_pools[i]['id_ip_pool']));
						 }
					 } else if(response.status === 'failed') {
						 showNotification(3,response.msg);
					 }
				 },
				 error: function (result) {
					 showNotification(3, "Error " + JSON.stringify(result));
				 }
			 });

		 });

			$('#form_admin').submit(function (e) {
				e.preventDefault();
				if( $("#id_package").val() != null &&  $("#id_repeat_every").val() != null ) {
					var reqData = $("#form_admin").serialize();
					showProgressBar();
					$.ajax({
						type:"POST",
						contentType: "application/x-www-form-urlencoded",
						dataType:"json",
						data: reqData,
						url: "<?php echo base_url() ?>ppp/edit_customer_now",

						success:function(response) {
							if(response.status === 'success') {
								showNotification(2,response.msg);
								var username=$( "#net_user_username" ).val();
								var mac=$( "#net_user_mac" ).val();
								var password=$( "#net_user_password" ).val();
								$('#prv_username').val(username);
								$('#prv_mac').val(mac);

								if(password.length != 0){
									$('#prv_password').val(password);
								}
								closeProgressBar();
							} else if(response.status === 'failed') {
								showNotification(3,response.msg);
								closeProgressBar();
							}
						},
						error: function (result) {
							showNotification(3,"Error " + JSON.stringify(result));
						}
					});

				}

			});
});

</script>
