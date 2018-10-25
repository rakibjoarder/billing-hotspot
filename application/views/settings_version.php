<?php
	require_once('header.php');

	if( (permission("Set","View",$permission_string)===0) || permission("Set","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}


	$NIB_VERSION = "";
	$PPPOE_VERSION = "";
  $NIBUPDATER_VERSION = "";
	foreach($version as $settings_config_indiv) {

		if($settings_config_indiv['key'] == 'NIB_VERSION') {
			$NIB_VERSION = $settings_config_indiv['value'];
		}

		if($settings_config_indiv['key'] == 'PPPOE_VERSION') {
			$PPPOE_VERSION = $settings_config_indiv['value'];
		}

		if($settings_config_indiv['key'] == 'NIBUPDATER_VERSION') {
			$NIBUPDATER_VERSION = $settings_config_indiv['value'];
		}
	}
?>


<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-tabs" data-background-color="blue">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper">
						<span class="nav-tabs-title"><h1 class="card-title">Version Information</h1></span>
						<ul class="nav nav-tabs" data-tabs="tabs"></ul>
					</div>
				</div>
			</div>

			<div class="card-content">
				<div class="toolbar"><!--        Here you can write extra buttons/actions for the toolbar              --></div>
				<div class="material-datatables table-responsive">
					<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Application</th>
								<th class="text-center">Version</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">1</td>
								<td class="text-center">Core</td>
								<td class="text-center"><?php echo $NIB_VERSION ?></td>
							</tr>
							<tr>
								<td class="text-center">2</td>
								<td class="text-center">User Management</td>
								<td class="text-center"><?php echo $PPPOE_VERSION ?></td>
							</tr>
							<tr>
								<td class="text-center">3</td>
								<td class="text-center">Version</td>
								<td class="text-center"><?php echo $NIBUPDATER_VERSION ?></td>
							</tr>

						</tbody>

						<tfoot>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Application</th>
								<th class="text-center">Version</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>






<?php
	require_once('footer.php');
?>
