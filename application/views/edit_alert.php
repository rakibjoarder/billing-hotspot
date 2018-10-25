<?php
	require_once('header.php');
	if(permission("Ale","Edit",$permission_string)===0 || permission("Ale","View",$permission_string)=== -1){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}
?>

<?php
 foreach($alerts_all as $alert_indiv):
	 $id_alert=$alert_indiv["id_alert"];
	 $alert_name=$alert_indiv["alert_name"];
	 $id_alert_type=$alert_indiv["id_alert_type"];
	 $alert_time_interval=$alert_indiv["alert_time_interval"];
 endforeach;
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue">
				<i class="material-icons">add_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Edit Alert</h4>
				<div id="result"></div>
				<form  method="post"  id="form_admin" enctype="multipart/form-data">

					<input type="hidden" id="id_alert" name="id_alert" value="<?php echo $id_alert; ?>" />

					<div class="form-group label-floating">
						<label class="control-label">Alert Name</label>
						<input type="text" class="form-control" id="alert_name"  name="alert_name" value="<?= $alert_name ?>">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Select Alert Type</label>
						<select class="form-control" name="id_alert_type" id='id_alert_type' required>
							<option disabled="" selected=""></option>
							<?php foreach ($alert_types as $info):
								if($info['id_alert_type'] == $id_alert_type){ ?>
									<option selected value='<?=$info['id_alert_type']?>'><?=$info['alert_type_name']?></option>
								<?php }
								else{ ?>
									<option value='<?=$info['id_alert_type']?>'><?=$info['alert_type_name']?></option>
								<?php   }
							endforeach; ?>
						</select>
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Alert Time Inverval</label>
						<input type="text" class="form-control" id="alert_time_interval" required  name="alert_time_interval" value="<?= $alert_time_interval ?>">
					</div>

					<button type="submit" name="submit" class="btn btn-info" value="submit">Submit</button>
					<?php	if(permission("Ale","View",$permission_string)===1 || permission("Ale","Delete",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1){ ?>
						<a href="<?php echo base_url();?>alert" class="btn btn-info" >Back</a>
					<?php } ?>
				</form>

			</div><!-- end of class form_area -->
		</div><!-- end of class container  -->

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
					url: "<?php echo base_url() ?>alert/edit_alert_now",

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
