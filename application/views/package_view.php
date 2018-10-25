<?php
	require_once('header.php');
	if( (permission("Pac","Add",$permission_string)===0 && permission("Pac","Edit",$permission_string)===0 && permission("Pac","Delete",$permission_string)===0 &&
			 permission("Pac","View",$permission_string)===0 ) || permission("Pac","View",$permission_string)=== -1){
				 echo "<script>
				 alert('You do not have permission to access this page. Contact your admin to get access !');
				 window.location.href='/login/logout';
				 </script>";
}

?>
<style>
table.dataTable > thead > tr > th,
table.dataTable > tbody > tr > th,
table.dataTable > tfoot > tr > th,
table.dataTable > thead > tr > td,
table.dataTable > tbody > tr > td,
table.dataTable > tfoot > tr > td {
text-align:  center;
padding: 15px !important;
}
</style>

<div id="fakeLoader"></div>
<div hidden id="container" class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All Packages</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">

							</ul>
						</div>
					</div>
				</div>

				<div class="card-content">
					<div class="toolbar"><!--        Here you can write extra buttons/actions for the toolbar              --></div>
					<div class="material-datatables table-responsive">
										<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
											<thead>
												<tr>
													<th>Name</th>
													<th>Speed</th>
													<th>Price ( <?php echo $currency ?> )</th>
													<th>Type</th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach($packages as $package){
													?>
													<tr>
														<td><?php echo $package['package_name'] ?></td>
														<td><?php echo $package['package_speed'] ?></td>
														<td><?php echo $package['package_price'] ?></td>
														<td><?php echo $package['package_type'] ?></td>
													</tr>

													<?php
												}
												?>
											</tbody>
												<tr>
								          <th>Name</th>
													<th>Speed</th>
													<th>Price ( <?php echo $currency ?> )</th>
													<th>Type</th>
												</tr>
											</tfoot>
										</table>
							</div>
					</div>
			 </div>
		 </div>
	 </div>
</div>

<?php
	require_once('footer.php');
?>


<script type="text/javascript">

$("#fakeLoader").fakeLoader({
spinner:"spinner4",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
bgColor:"#EEEEEE"
});

var package_id = "";
var oTable = "";

$(document).ready(function() {
	$("#fakeLoader").hide();
	$("#container").show();

	 oTable = $('#search_grid').DataTable({

			// data:[],
			columns: [
				{ "data" : "package_name" },
				{ "data" : "package_speed" },
				{ "data" : "package_price" },
				{ "data" : "package_type" }
			],
			rowCallback: function (row, data) {

			},
			drawCallback: function( settings ) {

	    },
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true
		});

});
</script>
