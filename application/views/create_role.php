<?php
	require_once('header.php');
	if(permission("Rol","Add",$permission_string)===0 || permission("Rol","View",$permission_string)=== -1){
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
				<h4 class="card-title">Create Role</h4>
				<div id="result"></div>
				<form method="post"  id="form_admin" enctype="multipart/form-data">

					<div class="form-group label-floating">
						<label class="control-label">Name</label>
						<input type="text" class="form-control" id="role_name" name="role_name" required>
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Description</label>
						<input type="text" class="form-control" id="role_desc" name="role_desc">
					</div>

					<div class="form-group label-floating">
							<label class="control-label">Select Default Module</label>
							<select name="id_module" id='id_module' class="form-control" required>
								<option disabled="" selected=""></option>
								<?php foreach ($modules as $module): ?>
									 <option value="<?= $module['id_module']?>"><?= $module['module_name'] ?></option>
								<?php endforeach; ?>
							</select>
					</div>


					<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
					<?php	if(permission("Rol","View",$permission_string)===1 || permission("Rol","Delete",$permission_string)===1 || permission("Rol","Edit",$permission_string)===1){ ?>
							<a href="<?php echo base_url();?>role" class="btn btn-fill btn-info">Back</a>
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
				url: "<?php echo base_url() ?>role/create_role_now",

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
