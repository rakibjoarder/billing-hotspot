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
foreach($radius as $item):
	$id_radius_mk = $item["id_radius_mk"];
	$service=$item["service"];
	$accounting_port=$item["accounting-port"];
	$authentication_port=$item["authentication-port"];
	$address=$item["address"];
	$secret=$item["secret"];
	$id_router = $item["id_router"];
	$id_mk_radius_mk = $item["id_mk_radius_mk"];
endforeach;
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">create</i></div>
			<div class="card-content">
				<h4 class="card-title">Edit Radius</h4>
				<div id="result"></div>
				<form  method="post"  id="form_admin" enctype="multipart/form-data">

					<label class="control-label">Service</label>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="ppp_check" name="ppp_check" <?=( ($service == 'ppp' || $service == 'ppp, hotspot') ? 'checked': '')?> > <span style="color:#191818;">PPP</span>
						</label>
						<label>
							<input type="checkbox" id="hotspot_check" name="hotspot_check" <?=( ($service == 'hotspot' || $service == 'ppp, hotspot') ? 'checked': '')?>> <span style="color:#191818;">HotSpot</span>
						</label>
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Address</label>
						<input type="text" class="form-control" id="address" required  name="address" value ="<?= $address ?>">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Authentication Port</label>
						<input type="text" class="form-control" id="authentication-port" required value ="<?= $authentication_port ?>" name="authentication-port">
					</div>

					<div class="form-group label-floating">
						<label class="control-label">Accounting Port</label>
						<input type="text" class="form-control" id="accounting-port"required value ="<?= $accounting_port ?>" name="accounting-port">
					</div>
					<div class="form-group label-floating">
						<label class="control-label">Secret</label>
						<input type="password" class="form-control" id="secret" required  value ="<?= $secret ?>" name="secret">
					</div>
					<input type="hidden"  id="id_mk_radius_mk"  name="id_mk_radius_mk" value="<?=$id_mk_radius_mk?>">
					<input type="hidden"  id="id_radius_mk"     name="id_radius_mk" value="<?=$id_radius_mk?>">
					<input type="hidden"  id="id_router"        name="id_router"    value="<?=$id_router?>">
					<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
					<?php	if(permission("Rou","View",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 || permission("Rou","Edit",$permission_string)===1){ ?>
						<a href="<?php echo base_url();?>router/router_radius/<?=$id_router?>" class="btn btn-fill btn-info">Back</a>
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
				showProgressBar();
				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>router/edit_radius_now",

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
