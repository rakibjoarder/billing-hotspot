<?php
	require_once('header.php');
?>
<?php
	foreach($net_user as $user):
		$id_net_user=$user["id_net_user"];
		$net_user_name=$user["net_user_name"];
		$email=$user["email"];
		$phone=$user["phone"];
		$net_user_addr=$user["net_user_addr"];
		$net_user_status=$user["net_user_status"];
	endforeach;
?>

	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="rose"><i class="material-icons">add_box</i></div>
					<div class="card-content">
						<h1 class="card-title">Add Link (<?php echo $net_user_name ?>)</h1>
							<div id="result"></div>
								<form  method="post"  id="form_admin" enctype="multipart/form-data">

									<div class="form-group">
										<input type="hidden" class="form-control" id="id_net_user"  required placeholder="Name" value="<?php echo $id_net_user ?>" name="id_net_user">
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Connection Name</label>
										<input type="text" class="form-control" id="connectioname"   required name="connectionname">
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Address</label>
										<input type="text" class="form-control" id="address"   required  name="address">
									</div>

									<div class="form-group col-md-6 label-floating">
										<label class="control-label">District</label>
											<select name="district_name" id='district_name' class="form-control">
												<option disabled="" selected=""></option>
												<?php foreach ($district as $dis): ?>
												<option value="<?= $dis['district_name']?>"><?= $dis['district_name'] ?></option>
												<?php endforeach; ?>
											</select>
									</div>

									<div class="form-group col-md-6 label-floating">
										<label class="control-label">Town</label>
											<select required class="form-control" name="town" id='town'>
											</select>
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Net User Type</label>
											<select class="form-control" name="id_net_user_type" id='id_net_user_type'>
												<option disabled="" selected=""></option>
												<?php foreach ($net_user_type as $type): ?>
												<option value="<?= $type['id_net_user_type']?>"><?= $type['net_user_type'] ?></option>
											<?php endforeach; ?>
											</select>
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Router</label>
											<select class="form-control" name="id_router" id='id_router'>
												<option disabled="" selected=""></option>
												<?php foreach ($routers as $router): ?>
												<option value="<?= $router['id']?>"><?= $router['name'] ?> (<?= $router['router_type_name']?>)</option>
												<?php endforeach; ?>
											</select>
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Mac Address</label>
										<input type="text" class="form-control" id="mac" required  name="mac">
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Ip Address</label>
										<input type="text" class="form-control" id="ip_addr" required name="ip_addr">
									</div>

									<div class="form-group col-md-12 col-md">
										<button type="submit" name="submit" class="btn btn-rose" value="submit" >Submit</button>
										<a href="<?php echo base_url();?>ppp" class="btn btn-rose">Back</a>
									</div>
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

		$('#district_name').click(function() {
			$("#town").empty();
			 	var district_name = $(this).val();
					$.ajax({
						type:"POST",
						contentType: "application/x-www-form-urlencoded",
						dataType:"json",
						url: "<?php echo base_url() ?>connection/get_town",
						data: {'district_name':district_name},
						success: function(data){
							for (i = 0; i < data.length; i++) {
								var id_location=data[i].id_location;
								var location_name=data[i].location_name;
								$("#town").append("<option value="+location_name+">"+location_name+"</option>");
							}

					}});
		});


		$("#form_admin").validate({
			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();
				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>connection/create_connection_now",

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
