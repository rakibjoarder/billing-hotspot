<?php
	require_once('header.php');
	if(permission("Ale","Add",$permission_string)===0 || permission("Ale","View",$permission_string)=== -1){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}
?>

<?php
$indv_id_alert='';
$indv_alert_name='';
 foreach($alert_info as $alert_indiv):
   $indv_id_alert=$alert_indiv["id_alert"];
   $indv_alert_name=$alert_indiv["alert_name"];
   break;
 endforeach;
?>


<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue">
				<i class="material-icons">add_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Create Layer of <?php echo $indv_alert_name ?></h4>
				<div id="result"></div>
				<form action="<?php echo base_url() ?>alert/create_alert_layer_now" method="post"  id="form_admin" enctype="multipart/form-data">

					<input type="hidden" id="id_alert" name="id_alert" value="<?= $indv_id_alert ?>" />

					<div class="form-group label-floating">
						<label class="control-label">Layer Name</label>
						<input type="text" class="form-control" id="alert_layers_name" required name="alert_layers_name">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Layer Priority</label>
						<input type="text" class="form-control" id="alert_layers_priority" required number="true"  name="alert_layers_priority">
					</div>

					<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
					<?php if(permission("Ale","View",$permission_string)===1 || permission("Ale","Add",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1 || permission("Ale","Delete",$permission_string)===1){ ?>
					<a href="<?php echo base_url();?>alert/alert_layers/<?= $indv_id_alert ?>" class="btn btn-fill btn-info" >Back</a>
					<?php } ?>
				</form>
			</div><!-- end of col-md-6 -->
		</div><!-- end of class row  -->

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
				url: "<?php echo base_url() ?>alert/create_alert_layer_now",

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
