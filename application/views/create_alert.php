<?php
	require_once('header.php');
	if(permission("Ale","Add",$permission_string)===0 || permission("Ale","View",$permission_string)=== -1){
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
				<h4 class="card-title">Create Alert</h4>
				<div id="result"></div>
				<form  method="post"  id="form_admin" enctype="multipart/form-data">

					<div class="form-group label-floating">
						<label class="control-label">Alert Name</label>
						<input type="text" class="form-control" id="alert_name"  name="alert_name">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Select Alert Type</label>
						<select class="form-control" name="id_alert_type" id='id_alert_type' required>
							<option disabled="" selected=""></option>
							<?php foreach ($alert_types as $info): ?>
								<option value='<?=$info['id_alert_type']?>'><?=$info['alert_type_name']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Alert Time Inverval</label>
						<input type="text" class="form-control" id="alert_time_interval"  name="alert_time_interval" value="">
					</div>

					<button type="submit" name="submit" class="btn btn-info" value="submit">Submit</button>
					<?php	if(permission("Ale","View",$permission_string)===1 || permission("Ale","Delete",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1){ ?>
    	<a href="<?php echo base_url();?>alert" class="btn btn-info">Back</a>
		<?php } ?>
				</form>
			</div><!-- end of col-md-6 -->
		</div><!-- end of class row  -->
	</div>
</div>


<?php
	require_once('footer.php');
?>

<script type="text/javascript">

$(document).ready(function(){

		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>alert/create_alert_now",

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
