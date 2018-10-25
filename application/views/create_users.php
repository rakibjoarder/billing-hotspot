<?php
	require_once('header.php');
	if(permission("Use","Add",$permission_string)===0 || permission("Use","View",$permission_string)=== -1){
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
					<h4 class="card-title">Create User</h4>
					<div id="result"></div>
							<form  method="post"  id="form_admin" enctype="multipart/form-data">

								<div class="form-group label-floating">
									<label class="control-label">Name</label>
									<input type="text" class="form-control" id="full_name" required  name="full_name">
								</div>

									<div class="form-group  label-floating">
										<label class="control-label">User Name</label>
										<input type="text" class="form-control" id="user_name" required  name="user_name">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">E-mail</label>
										<input type="email" class="form-control" id="email" required  name="email">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Phone Number</label>
										<input type="text" class="form-control" id="phone" required  name="phone" number="true" minLength="11">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Address</label>
										<input type="text" class="form-control" id="address" required  name="address">
									</div>
									<div class="form-group label-floating">
										<label class="control-label">Designation</label>
										<input type="text" class="form-control" id="designation" required  name="designation">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Select Role</label>
										<select name="id_role" id='id_role' class="form-control" required>
											<option disabled="" selected=""></option>
											<?php foreach ($roles as $role): ?>
												<option value="<?= $role['id_role']?>"><?= $role['role_name'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Password</label>
								 		<input type="password" class="form-control" id="pwd" required  name="pwd" minLength="6">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Confirm Password</label>
									 <input type="password" class="form-control" id="confirm_pwd" required  name="confirm_pwd"  equalTo="#pwd">
									</div>

									<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
									<?php	if(permission("Use","View",$permission_string)===1 || permission("Use","Delete",$permission_string)===1 || permission("Use","Edit",$permission_string)===1 || permission("Use","Acc_essz_one",$permission_string)===1 || permission("Use","Access_R_outer",$permission_string)===1){ ?>
										<a href="<?php echo base_url();?>user/users" class="btn btn-fill btn-info">Back</a>
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

	$('#phone').mask('ASY00000000', {'translation': {
		A: {pattern: /[0]/},
		S: {pattern: /[1]/},
		Y: {pattern: /[5-9]/}
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
					url: "<?php echo base_url() ?>user/create_user_now",

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
