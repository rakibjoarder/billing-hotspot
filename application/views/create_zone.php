<?php
	require_once('header.php');

	if(permission("Zon","Add",$permission_string)===0 || permission("Zon","View",$permission_string)=== -1){
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
				<h4 class="card-title">Create Zone</h4>
				<div id="result"></div>
				<form method="post"  id="form_admin" enctype="multipart/form-data">
					<div class="form-group label-floating">
						<label class="control-label">Zone Name</label>
						<input type="text" class="form-control" id="zone_name" name="zone_name" required>
					</div>
					<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
					<?php	if(permission("Zon","View",$permission_string)===1 || permission("Zon","Delete",$permission_string)===1 || permission("Zon","Edit",$permission_string)===1){ ?>

					<a href="<?php echo base_url();?>zone" class="btn btn-fill btn-info">Back</a>
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
				url: "<?php echo base_url() ?>zone/create_zone_now",

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
