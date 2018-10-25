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
				<i class="material-icons">add_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Network Settings</h4>
				<div id="result"></div>
	<form  method="post"  id="form_admin" enctype="multipart/form-data">
					<div class="form-group">
						<label>IP Address:</label>
						<input type="text" class="form-control" placeholder="IP Address" name="ip_address" id="ip_address" value='<?=$ipaddr?>'>
					</div>

					<div class="form-group">
						<label>Net Mask:</label>
						<input type="text" class="form-control" required placeholder="Net Mask" name="net_mask" id="net_mask" value='<?=$netmask?>'>
					</div>


					<div class="form-group">
						<label>Gateway IP:</label>
						<input type="text" class="form-control" required placeholder="Gateway IP" name="gateway_ip" id="gateway_ip" value='<?=$gateway?>'>
					</div>

					<div class="form-group">
						<label>DNS 1:</label>
						<input type="text" class="form-control" required placeholder="DNS 1" name="dns_1" id="dns_1" value='<?=$dns1?>'>
					</div>

					<div class="form-group">
						<label>DNS 2:</label>
						<input type="text" class="form-control" required placeholder="DNS 2" name="dns_2" id="dns_2" value='<?=$dns2?>'>
					</div>


					<button type="submit" name="submit" class="btn btn-info" value="submit">Submit</button>
				</form>
			</div>
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
					url: "<?php echo base_url() ?>sysnet/add_ip_settings_now",

					success:function(response) {
						if(response.status === 'success') {
								showNotification(2,response.msg);
						} else if(response.status === 'failed') {
								showNotification(3,response.msg);
						}
					}
					// ,
					// error: function (result) {
					// 	   showNotification(3,"Error " + JSON.stringify(result));
					// }
				});
			}
		});
});

</script>
