<?php
	require_once('header.php');
	if(permission("Pac","Edit",$permission_string)===0 || permission("Pac","View",$permission_string)=== -1){
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
				<div class="card-content"><h4 class="card-title">Edit Package</h4>
					<div id="result"></div>
						<form  method="post"  id="form_admin" enctype="multipart/form-data">
							<?php
								foreach($packages as $package):
									$id_package=$package["id_package"];
									$package_name=$package["package_name"];
									$package_speed=$package["package_speed"];
									$package_price=$package["package_price"];
									$package_type=$package["package_type"];
								endforeach;
							?>

							<input type="hidden" value="<?php echo $id_package; ?>" id="id_package" name="id_package" >

							<div class="form-group label-floating">
								<label class="control-label">Name</label>
								<input type="text" class="form-control"  value="<?php echo $package_name; ?>" id="package_name" required name="package_name">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Speed</label>
								<input type="text" class="form-control"  value="<?php echo $package_speed; ?>" id="package_speed" required  name="package_speed">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Price</label>
								<input  type="number" step="any"  class="form-control"  value="<?php echo $package_price; ?>" id="package_price" required  name="package_price">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">User Type</label>
									<select name="package_type" id='package_type' class="form-control">
										<option disabled="" selected=""></option>
										<?php if($package_type=='Prepaid'){ ?>
										<option selected="selected" value='Prepaid'>Prepaid</option>
										<?php }
										else { ?>
										<option  value='Prepaid'>Prepaid</option>
										<?php }

										if($package_type=='Postpaid'){ ?>
										<option selected="selected" value='Postpaid'>Postpaid</option>
										<?php }
										else { ?>
										<option  value='Postpaid'>Postpaid</option>
									<?php } ?>
									</select>
							</div>

							<button type="submit" name="submit" class="btn btn-info" value="submit">Submit</button>
							<a href="<?php echo base_url();?>package" class="btn btn-info" >Back</a>
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


			showProgressBar();

			$.ajax({
				type:"POST",
				contentType: "application/x-www-form-urlencoded",
				dataType:"json",
				data: reqData,
				url: "<?php echo base_url() ?>package/edit_package_now",

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
