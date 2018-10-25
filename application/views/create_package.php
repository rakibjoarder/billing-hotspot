<?php
	require_once('header.php');
	if(permission("Pac","Add",$permission_string)===0 || permission("Pac","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Add Package</h4>
						<div id="result"></div>
							<form action="<?php echo base_url() ?>package/create_package_now" method="post"  id="form_admin" enctype="multipart/form-data">
								<div class="form-group label-floating">
									<label class="control-label">Package Name</label>
									<input type="text" class="form-control" id="package_name" required name="package_name">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Package Speed</label>
									<input type="text" class="form-control" id="package_speed" required  name="package_speed">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Package Price</label>
									<input type="number" step="any" class="form-control" id="package_price" required name="package_price" >
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Package Type</label>
										<select name="package_type" id='package_type' required class="form-control">
											<option disabled="" selected=""></option>
											<option value='Prepaid'>Prepaid</option>
											<option value='Postpaid'>Postpaid</option>
										</select>
								</div>

								<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
								<?php	if(permission("Pac","View",$permission_string)===1 || permission("Pac","Delete",$permission_string)===1 || permission("Pac","Edit",$permission_string)===1){ ?>
									<a href="<?php echo base_url();?>package" class="btn btn-fill btn-info">Back</a>
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

		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>package/create_package_now",

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
