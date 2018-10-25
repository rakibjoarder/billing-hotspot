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
					<h4 class="card-title">Add IP Pool</h4>
						<div id="result"></div>
							<form action="<?php echo base_url() ?>ip_pool/add_ip_pool_now" method="post"  id="form_admin" enctype="multipart/form-data">
								<div class="form-group label-floating">
									<label class="control-label">IP Pool Name</label>
									<input type="text" class="form-control" id="ip_pool_name" required name="ip_pool_name">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Starting IP</label>
									<input type="text" class="form-control" id="ip_pool_start" required  name="ip_pool_start">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Ending IP</label>
									<input type="text" class="form-control" id="ip_pool_end"  name="ip_pool_end">
								</div>
								<input type="hidden"  id="id_router" required name="id_router" value="<?=$id_router?>">
									<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
									<?php	if(permission("Rou","View",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 || permission("Rou","Edit",$permission_string)===1){ ?>
										<a href="<?php echo base_url();?>router/ip_pool/<?=$id_router?>" class="btn btn-fill btn-info">Back</a>
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

	// $('#ip_pool_start').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
	// 	translation: {
	// 		'Z': {
	// 			pattern: /[0-9]/, optional: true
	// 		}
	// 	}
	// });
	//
	// $('#ip_pool_end').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
	// 	translation: {
	// 		'Z': {
	// 			pattern: /[0-9]/, optional: true
	// 		}
	// 	}
	// });



		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();
				showProgressBar();
				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>router/add_ip_pool_now",

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
