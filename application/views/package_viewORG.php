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
								<?php if (permission("Pac","Add",$permission_string)===1) { ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>package/create_package">
										<i class="material-icons">add</i>ADD&nbsp;
										<div class="ripple-container"></div></a>
									</a>
								</li>
								<?php } ?>
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
													<?php if(permission("Pac","Edit",$permission_string)===1 || permission("Pac","Delete",$permission_string)===1 ){ ?>
													<th class="disabled-sorting" >Action</th>
													<?php }else {?>
													<th class="disabled-sorting"></th>
													<?php } ?>
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
														<?php if(permission("Pac","Edit",$permission_string)===1 || permission("Pac","Delete",$permission_string)===1 ){ ?>
															<td><?php echo $package['id_package'] ?></td>
														<?php } ?>
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
													<?php if(permission("Pac","Edit",$permission_string)===1 || permission("Pac","Delete",$permission_string)===1 ){ ?>
														<th class="disabled-sorting" >Action</th>
														<?php }else {?>
														<th class="disabled-sorting"></th>
														<?php } ?>
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
				{ "data" : "package_type" },
				{ "data" : "id_package" }
			],
			rowCallback: function (row, data) {
				$('td:last', row).html('');
				<?php if(permission("Pac","Edit",$permission_string)===1){ ?>
					var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>package/edit_package/"+data.id_package);
					$('td:last', row).append(tmp);
				<?php } ?>
				<?php if(permission("Pac","Delete",$permission_string)===1){ ?>
					tmp = $('<a id="edit_btn"  class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>').attr('package_id', data.id_package);
					$('td:last', row).append(tmp);
				<?php } ?>
			},
			drawCallback: function( settings ) {

	    },
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true
		});


	$("#search_grid").on('click', '.delete_item', function(){

			var package_id = $(this).attr('package_id');
				swal({
		           type:'warning',
		           title: 'Are you sure you want to delete this package?',
		           text: 'You will not be able to recover the data ',
		           showCancelButton: true,
		           confirmButtonColor: '#049F0C',
		           cancelButtonColor:'#ff0000',
		           confirmButtonText: 'Yes, delete it!',
		           cancelButtonText: 'No, keep it'
		         }).then(function (res) {
							 $.ajax({
							 	type:"POST",
							 	dataType:"json",
							 	data:'package_id=' + package_id,
							 	url: '<?php echo base_url() ?>package/delete_package_now',

							 	success:function(response) {
							 		console.log("Response Status " + response.status);

							 		if(response.status === 'passed') {
										showNotification(2,response.msg);
							 			refresh_datatable(response.data, 'success', response.msg);
							 		//location.reload();
							 		} else {
										showNotification(3,response.msg);

							 		}
							 	}
							 });
		    });

	  });
});
</script>
