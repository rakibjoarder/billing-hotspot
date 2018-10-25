<?php
	require_once('header.php');
	if(permission("Set","Edit",$permission_string)===0 || permission("Set","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>
<?php
  foreach($ip_pools as $ip_pool):
    $id_ip_pool=$ip_pool["id_ip_pool"];
    $ip_pool_name=$ip_pool["ip_pool_name"];
    $ip_pool_start=$ip_pool["ip_pool_start"];
    $ip_pool_end=$ip_pool["ip_pool_end"];
		$id_router=$ip_pool["id_router"];
  endforeach;
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">create</i></div>
				<div class="card-content">
					<h4 class="card-title">Edit IP Pool</h4>
						<div id="result"></div>
							<form  method="post"  id="form_admin" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $id_ip_pool; ?>" id="id_ip_pool" name="id_ip_pool" >

								<div class="form-group label-floating">
									<label class="control-label">IP Pool Name</label>
									<input type="text" class="form-control" value="<?php echo $ip_pool_name; ?>" id="ip_pool_name" required name="ip_pool_name">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Starting IP</label>
									<input type="text" class="form-control" value="<?php echo $ip_pool_start; ?>" id="ip_pool_start" required  name="ip_pool_start">
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Ending IP</label>
									<input type="text" class="form-control" value="<?php echo $ip_pool_end; ?>" id="ip_pool_end"  name="ip_pool_end">
								</div>
									<input type="hidden"  value="<?php echo $ip_pool_name; ?>" id="ip_pool_prv_name"  name="ip_pool_prv_name">
								<input type="hidden"  value="<?php echo $id_router; ?>" id="id_router"  name="id_router">
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
					url: "<?php echo base_url() ?>router/edit_ip_pool_now",

					success:function(response) {
						if(response.status === 'success') {
								showNotification(2,response.msg);
								$('input[name="ip_pool_prv_name"]').val(response.name);
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
