<?php
	require_once('header.php');
	if(permission("Sys","Edit",$permission_string)===0 || permission("Sys","View",$permission_string)=== -1){
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
				<i class="material-icons">info</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Confirm Radius Restart</h4>
				<div id="result"></div>
				<form  method="post"  id="form_admin" enctype="multipart/form-data">
					<div class="form-group label-floating">
						<label class="control-label">Enter Password</label>
						<input type="text" class="form-control" id="password" required  name="password">
					</div>
						<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
				</form>
			</div>
		</div>
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
  					url: "<?php echo base_url() ?>sysnet/radius_restart_now",

  					success:function(response) {
  						if(response.status === 'passed') {
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
