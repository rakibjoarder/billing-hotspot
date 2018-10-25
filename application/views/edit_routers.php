<?php
	require_once('header.php');
?>

<?php
foreach($routers_all as $router_indiv):
	$router_name	  	=$router_indiv["name"];
	$router_ip		 	  =$router_indiv["ip_address"];
	$router_login 		=$router_indiv["login"];
	$router_password  =$router_indiv["password"];
	$router_type_id   =$router_indiv["id_router_type"];
	$sync_router_flag =$router_indiv["sync_router_flag"];
	$is_active        =$router_indiv["is_active"];
	$radius_flag      =$router_indiv["radius_flag"];
endforeach;
$router_id_current = $this->uri->segment(3);
?>


<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue">
				<i class="material-icons">add_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Edit Router</h4>
				<div id="result"></div>
				<form  method="post"  id="form_admin" enctype="multipart/form-data">

					<div class="form-group label-floating">
						<label class="control-label">Router Name</label>
						<input type="text" class="form-control" id="router_name" required  name="router_name"  value="<?php echo $router_name; ?>">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Router Ip</label>
						<input type="text" class="form-control" id="router_ip" required  name="router_ip" value="<?php echo $router_ip; ?>">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Select Router Type</label>
						<select name="id_router_type" id='id_router_type' class="form-control">
							<option disabled="" selected=""></option>
							<?php  foreach ($routers_type as $type):
								if($router_type_id== $type['id_router_type'] ){?>
									<option selected value="<?= $type['id_router_type']?>"><?= $type['router_type_name'] ?></option>
								<?php }else { ?>
									<option value="<?= $type['id_router_type']?>"><?= $type['router_type_name'] ?></option>
								<?php }
							endforeach; ?>
						</select>
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Login</label>
						<input type="text" class="form-control" id="router_login" required  name="router_login" value="<?php echo $router_login; ?>">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Password</label>
						<input type="password" class="form-control" id="router_password"   name="router_password"  minlength="6">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Confirm Password</label>
						<input type="password" class="form-control" id="confirm_pwd"   name="confirm_pwd"  equalTo="#router_password">
					</div>

					<!-- <div class="checkbox">
						<label>
							<input type="checkbox" <?php if($sync_router_flag==1) echo 'checked' ?> id="sync_router_flag" name="sync_router_flag"> <span style="color:#191818;">Sync Router</span>
						</label>
					</div> -->

					<div class="togglebutton form-group ">
						<label> <span style="color:black;margin-right:10px;" id="active_inactive"> <?=(($is_active == 0)? 'Active': 'Disable')?> </span>
							<input type="checkbox" id="is_active" name="is_active" <?=(($is_active == 0)? 'checked': '')?> value="<?= $is_active ?>" />
						</label>
					</div>

					<!-- <div class="togglebutton form-group ">
						<label> <span style="color:black;margin-right:10px;" id="radius_flag_text"> <?=(($radius_flag == 1)? 'Radius Enabled': 'Radius Disabled')?> </span>
							<input type="checkbox" id="radius_flag" name="radius_flag" <?=(($radius_flag == 1)? 'checked': '')?> value="<?= $radius_flag ?>" />
						</label>
					</div> -->

					<input type="hidden" id="router_id" name="router_id" value="<?php echo $router_id_current; ?>" />


					<button type="submit" name="submit" class="btn btn-info" value="submit">Submit</button>
					<a href="<?php echo base_url();?>router/routers" class="btn btn-info">Back</a>
					<a type="text" name="check_connection" class="btn btn-fill btn-info check_connection">Test Connection</a>


				</form>
			</div>
		</div>
	</div>
</div>

<?php
	require_once('footer.php');
?>

<script type="text/javascript">


$("#radius_flag").click( function(){
	if( $(this).is(':checked') ){
		$('#radius_flag_text').text('Radius Enabled');
	}else{
		$('#radius_flag_text').text('Radius Disabled');
	}
});


$("#is_active").click( function(){
	if( $(this).is(':checked') ){
		$('#active_inactive').text('Active');
	}else{
		$('#active_inactive').text('Disable');
	}
});


$('.check_connection').on('click', function() {

  var login = $('#router_login').val();
	var password = $('#router_password').val();
	var router_ip =  $('#router_ip').val();

showProgressBar();

		$.ajax({
			type:"POST",
			dataType:"json",
			data:{'login' : login,'password' : password,'router_ip' : router_ip},
			url: '<?php echo base_url() ?>router/check_router_connection',

			success:function(response) {
				if(response.status === 'success') {
					closeProgressBar();
					swal({
						title: response.msg,
						type: 'success',
						button: "Okay",
					});

				} else {
					closeProgressBar();
					swal({
						title: response.msg,
						type:'error',
						button: "Okay",
					});
				}

			}
		});
	});




	$(document).ready(function(){
		$("#form_admin").validate({
			submitHandler: function (form) {

				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>router/edit_router_now",
					success:function(response) {
						// response = JSON.parse(response);
						if(response.status === 'success') {
							showNotification(2,response.msg);
						} else {
							showNotification(3,response.msg);
						}
					},
					error: function (result) {
						  showNotification(3,response.msg);
					}
				});
			}
		});
	});

</script>
