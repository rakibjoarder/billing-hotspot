<?php
	require_once('header.php');
?>


<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="rose"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Edit Connection</h4>
						<div id="result"></div>
							<form  action="<?php echo base_url() ?>connection/edit_connection_now" method="post"  id="form_admin" enctype="multipart/form-data">
								<?php
									$id_connection=$address=$connectionname=$id_net_user_type=$id_router=$id_net_user=$mac=$ip_addr=$town=$district_name='';
										foreach ($connection as $connection_indiv):
												$id_connection		=$connection_indiv['id_connection'];
												$address 					=$connection_indiv['address'];
												$connectionname		=$connection_indiv['connectionname'];
												$id_net_user_type = $connection_indiv['id_net_user_type'];
												$id_router  			= $connection_indiv['id_router'];
												$id_net_user 			= $connection_indiv['id_net_user'];
												$mac       				=$connection_indiv['mac'];
												$ip_addr          =$connection_indiv['ip_addr'];
												$town							=$connection_indiv['town'];
												$district_name		=$connection_indiv['district_name'];
										endforeach;
									$router_id_current = $this->uri->segment(3);
								?>
									<input type="hidden" value="<?php echo $id_connection; ?>" id="id_connection" name=" id_connection" >

									<div class="form-group col-md-12 col-md label-floating">
										<label  class="control-label" for="full_name">Connection Name</label>
										<input type="text" class="form-control" id="connectioname"  value="<?php echo $connectionname ?>" required  name="connectionname">
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label  class="control-label" for="full_name">Address</label>
										<input type="text" class="form-control" id="address"  value="<?php echo $address ?>"   required  name="address">
									</div>


									<div class="form-group col-md-6 label-floating">
										<label class="control-label">District</label>
											<select name="district_name" id='district_name' class="form-control">
												<option disabled="" selected=""></option>
												<?php  foreach ($district as $dis):
												if($district_name== $dis['district_name'] ){
												?>
												<option selected value="<?= $dis['district_name']?>"><?= $dis['district_name'] ?></option>
												<?php }else { ?>
												<option value="<?= $dis['district_name']?>"><?= $dis['district_name'] ?></option>
												<?php }
												endforeach; ?>
											</select>
									</div>

									<div class="form-group col-md-6 label-floating">
										<label class="control-label">Town</label>
											<select name="town" id='town' class="form-control">
												<option disabled="" selected=""></option>
												<option selected value="<?= $town ?>"><?= $town ?></option>
											</select>
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Net User Type</label>
											<select name="id_net_user_type" id='id_net_user_type' class="form-control">
												<option disabled="" selected=""></option>
												<?php  foreach ($net_user_type as $type):
												if($id_net_user_type== $type['id_net_user_type'] ){
												?>
												<option selected value="<?= $type['id_net_user_type']?>"><?= $type['net_user_type'] ?></option>
												<?php }else { ?>
												<option value="<?= $type['id_net_user_type']?>"><?= $type['net_user_type'] ?></option>
												<?php }
												endforeach; ?>
											</select>
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label class="control-label">Select Router</label>
											<select name="id_router" id='id_router' class="form-control">
												<option disabled="" selected=""></option>
												<?php foreach ($routers as $router):
												if($id_router==$router['id'] ){
												?>
												<option selected value="<?= $router['id']?>"><?= $router['name'] ?> (<?= $router['router_type_name']?>)</option>
												<?php }else { ?>
												<option  value="<?= $router['id']?>"><?= $router['name'] ?> (<?= $router['router_type_name']?>)</option>
												<?php }
												endforeach; ?>
											</select>
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label  class="control-label" for="full_name">Mac Address</label>
										<input type="text" class="form-control" id="mac"  value="<?php echo $mac ?>" required  name="mac">
									</div>

									<div class="form-group col-md-12 col-md label-floating">
										<label  class="control-label" for="full_name">Ip Address</label>
										<input type="text" class="form-control" id="ip_addr"  value="<?php echo $ip_addr ?>" required  name="ip_addr">
									</div>

									<div class="form-group col-md-12 col-md">
										<button type="submit" name="submit" class="btn btn-rose" value="submit">Submit</button>
										<a href="<?php echo base_url();?>connection" class="btn btn-rose ">Back</a>
									</div>
							</form>
				</div><!-- end of class form_area -->
		 </div>
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
					url: "<?php echo base_url() ?>connection/edit_connection_now",

					success:function(response) {
							if(response.status === 'success') {
								showNotification(2,response.msg);
							} else {
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
