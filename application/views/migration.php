<?php
	require_once('header.php');
	if( (permission("Mig","View",$permission_string)===0) || permission("Mig","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>

<style>
div#connection_window{
	background-color: #232622;
	color: #ffffff;
	max-height: 350px;
	padding: 10px;
	overflow:auto;
	font-family: "Courier New", monospace;
	font-size:10pt;
}
div#tail_window {
	background-color: #232622;
	color: #ffffff;
	max-height: 350px;
	min-height: 100px;
	padding: 10px;
	overflow:auto;
	font-family: "Courier New", monospace;
	font-size:10pt;
}

.wizard-card .nav-pills {
		background-color: #3c4858 !important;
	}

	table.table > tbody tr:nth-child(odd){
	  /*background-color: #F5F5F5;*/
		background-color: #FFFFFF;
	}
	table.table > tbody tr:nth-child(even){
		/*background-color: #EEEEEE;*/
		background-color: #FFFFFF;
	}

</style>

<div class="container-fluid">
	<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 ">
		<div class="wizard-container">
			<div class="card wizard-card" data-color="blue" id="wizardProfile">
				<form method="post"  id="form_admin" enctype="multipart/form-data">
					<div class="wizard-header">
						<h3 class="wizard-title">
							Migration
						</h3>
					</div>
					<div class="wizard-navigation">
						<ul style="color:white !important;" >
							<li>
								<a href="#pppoe_user" data-toggle="tab" style="color:white !important;" >Pppoe Customer</a>
							</li>
							<li>
								<a href="#static_user" data-toggle="tab" style="color:white !important;" >Static User</a>
							</li>
							<li>
								<a href="#migration_panel" data-toggle="tab" style="color:white !important;">Migration</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="tab-pane" id="pppoe_user">
							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">
										<table id="data_grid" class="table table-striped table-no-bordered " cellspacing="0" width="100%" style="width:100%">
											<tbody>
													<tr>
														<td class="text-left">IP Pool </td>
														<td class="text-right"><div class="togglebutton"><label><input type="checkbox" name="pool_check" id='pool_check' ></label></div></td>
													</tr>
													<tr>
														<td class="text-left">Profile </td>
														<td class="text-right"><div class="togglebutton"><label><input type="checkbox" name="profile_check" id='profile_check' ></label></div></td>
													</tr>
													<tr>
														<td class="text-left">Customer </td>
														<td class="text-right"><div class="togglebutton"><label><input type="checkbox" name="customer_check" id='customer_check' ></label></div></td>
													</tr>
										</tbody>
									</table>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="static_user">
							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">
										<table id="data_grid" class="table table-striped table-no-bordered " cellspacing="0" width="100%" style="width:100%">
											<tbody>
													<tr>
														<td class="text-left">Firewall </td>
														<td class="text-right"><div class="togglebutton"><label><input type="checkbox" name="firewall_check" id='firewall_check' ></label></div></td>
													</tr>
													<!-- <tr>
														<td class="text-left">Arp </td>
														<td class="text-right"><div class="togglebutton"><label><input type="checkbox" name="arp_check" id='arp_check' ></label></div></td>
													</tr>
													<tr>
														<td class="text-left">Queue </td>
														<td class="text-right"><div class="togglebutton"><label><input type="checkbox" name="queue_check" id='queue_check' ></label></div></td>
													</tr> -->
										</tbody>
									</table>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="migration_panel">
							<div class="row">
								<div class="card-content">
									<div class="col-md-10 col-md-offset-1">
										<div class="form-group label-floating">
											<label class="control-label">Select Router</label>
											<select name="id_router" id='id_router' class="form-control" required >
												<?php foreach ($routers as $router): ?>
													<option value="<?= $router['id']?>" routername="<?= $router['name']?>"><?= $router['name'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class=""id="connection_window" class="panel-body"></div>
										<div class="" id="tail_window" class="panel-body"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="wizard-footer">
						<div class="pull-right">
							<input type='button' class='btn btn-next btn-fill btn-info btn-wd' name='next' value='Next' />
							<button type="submit" name="submit"  class='btn  btn-fill btn-info btn-wd btn-migration' value="submit"><i style="margin-right:2px;" class="fa fa-cog"></i>Start</button>
						</div>
						<div class="pull-left">
							<input type='button' class='btn btn-previous btn-fill btn-info btn-wd' name='previous' value='Previous' />
						</div>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
require_once('footer.php');
?>

<script type="text/javascript">
initWizard();


	var routername="";
	$('.btn-migration i').removeClass('fa fa-cog fa-spin');


$(document).ready(function(){




	$('#id_router').change(function() {
			routername=$('option:selected', this).attr("routername");
		});

		$( ".btn-migration" ).click(function() {

			$('.btn-migration i').addClass('fa fa-cog fa-spin');
			$('.btn-migration').attr('disabled', true);
			$('.btn-migration').css("background-color", "red");
			$('#id_router option:not(:selected)').prop('disabled', true);


			var reqData = $("#form_admin").serialize();
			 $("#connection_window").html("Connection attempt to "+ routername +" router...");
			 $("#tail_window").html("</Br>");

			$.ajax({
				type:"POST",
				contentType: "application/x-www-form-urlencoded",
				dataType:"json",
				data: reqData,
				url: "<?php echo base_url() ?>settings/add_mikrotik_to_db",

				success:function(response) {
					if(response.status === 'success') {
						showNotification(2,response.msg);
						$("#tail_window").html(response.data);
						$('.btn-migration i').removeClass('fa fa-cog fa-spin');
						$('.btn-migration').attr('disabled', false);
						$('.btn-migration').removeAttr( 'style' );
						$('#id_router option:not(:selected)').prop('disabled', false);
					} else if(response.status === 'failed') {
						showNotification(3,response.msg);
						$("#tail_window").html(response.msg);
						$('.btn-migration i').removeClass('fa fa-cog fa-spin');
						$('.btn-migration').attr('disabled', false);
						$('.btn-migration').removeAttr( 'style' );
						$('#id_router option:not(:selected)').prop('disabled', false);
					}
				},
				error: function (result) {
					showNotification(3,"Error " + JSON.stringify(result));
				}
			});
		});
});

</script>
