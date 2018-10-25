<?php
	require_once('header.php');
	if(permission("Rou","Add",$permission_string)===0 || permission("Rou","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue">
				<i class="material-icons">add_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Add Router</h4>
				<div id="result"></div>
				<form  method="post"  id="form_admin" enctype="multipart/form-data">

					<div class="form-group label-floating">
						<label class="control-label">Router Name</label>
						<input type="text" class="form-control" id="router_name" required  name="router_name">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Router Ip</label>
						<input type="text" class="form-control" id="router_ip" required  name="router_ip">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Select Router</label>
						<select name="id_router_type" id='id_router_type' class="form-control" required>
							<option disabled="" selected=""></option>
							<?php foreach ($routers_type as $type): ?>
								<option value="<?= $type['id_router_type']?>"><?= $type['router_type_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Login</label>
						<input type="text" class="form-control" id="router_login" required  name="router_login">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Password</label>
						<input type="password" class="form-control" id="router_password" required  name="router_password" minlength="3">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Confirm Password</label>
						<input type="password" class="form-control" id="confirm_pwd" required  name="confirm_pwd"  equalTo="#router_password">
					</div>

					<!-- <div class="checkbox">
						<label>
							<input type="checkbox" id="sync_router_flag" name="sync_router_flag"> <span style="color:#191818;">Sync Router</span>
						</label>
					</div>

					<div class="togglebutton form-group ">
						<label> <span style="color:black;margin-right:10px;" id="radius_flag_text">Radius Enabled </span>
							<input type="checkbox" id="radius_flag" name="radius_flag"  />
						</label>
					</div> -->


					<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
					<?php	if(permission("Rou","View",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 || permission("Rou","Edit",$permission_string)===1){ ?>
					<a href="<?php echo base_url();?>router/routers" class="btn btn-fill btn-info" >Back</a>
					<a type="text" name="check_connection" class="btn btn-fill btn-info check_connection">Test Connection</a>
				<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
	require_once('footer.php');
?>

<script type="text/javascript">

$(document).ready(function(){


	$("#radius_flag").click( function(){
		if( $(this).is(':checked') ){
			$('#radius_flag_text').text('Radius Enabled');
		}else{
			$('#radius_flag_text').text('Radius Disabled');
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


	$('#router_ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
		translation: {
			'Z': {
				pattern: /[0-9]/, optional: true
			}
		}
	});


		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>router/create_router_now",

					success:function(response) {
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
			}
		});
});

</script>
