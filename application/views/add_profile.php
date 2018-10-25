<?php
	require_once('header.php');
	if(permission("Rou","Add",$permission_string)===0 || permission("Rou","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
$id_router = $this->uri->segment(3);

?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Add Profile</h4>
						<div id="result"></div>
							<form method="post"  id="form_admin" enctype="multipart/form-data">

								<div class="form-group label-floating">
									<label class="control-label">Profile Name</label>
									<input type="text" class="form-control" id="profile_name" required name="profile_name">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Local Address</label>
									<input type="text" class="form-control" id="local_address" name="local_address">
								</div>


								<div class="form-group label-floating">
									<label class="control-label">Select Pool</label>
									<select  name="remote_address" id='remote_address' class="form-control" >
									<option disabled="" selected=""></option>
										<?php foreach ($ip_pools as $ip_pool): ?>
											<option value="<?=$ip_pool['ip_pool_name']?>"><?=$ip_pool['ip_pool_name']?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Bridge</label>
									<input type="text" class="form-control" id="bridge" name="bridge">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Rate Limit(rx/tx)</label>
									<input type="text" class="form-control" id="rate_limit" name="rate_limit">
								</div>

								<input type="hidden"  id="id_router" required name="id_router" value="<?=$id_router?>">

								<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
								<?php	if(permission("Rou","View",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 || permission("Rou","Edit",$permission_string)===1){ ?>
									<a href="<?php echo base_url();?>router/router_profile/<?=$id_router?>" class="btn btn-fill btn-info">Back</a>
								<?php } ?>

							</form>
					</div><!-- end of class form_area -->
			 </div><!-- end of class container  -->
		</div>
	</div>

<?php
	require_once('footer.php');
?>

<script type="text/javascript">

$(document).ready(function(){

	$('#local_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
		translation: {
			'Z': {
				pattern: /[0-9]/, optional: true
			}
		}
	});

		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();
				showProgressBar();
				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>router/add_profile_now",
					success:function(response) {
						if(response.status === 'success') {
								showNotification(2,response.msg);
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
