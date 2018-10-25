<?php
	require_once('header.php');
	if(permission("Rou","View",$permission_string)===0 || permission("Rou","View",$permission_string)=== -1){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}
	$id_router = $this->uri->segment(3);
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

<div id="spinner" class="loader"></div>
<div id="result"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">Radius Of <?=$router_name?></h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<li  style="float:right; padding-left:8px;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>router/routers">
										<i class="material-icons">exit_to_app</i>BACK
										<div class="ripple-container"></div></a>
									</a>
								</li>

								<?php if( permission("Rou","Add",$permission_string)===1){ ?>
									<li  style="float:right;padding-left:8px;" >
										<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>router/add_radius_mk/<?=$id_router?>">
											<i class="material-icons">add</i>Add&nbsp;
											<div class="ripple-container"></div></a>
										</a>
									</li>
								<?php } ?>


							<?php if( permission("Rou","Add",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round btn-load-radius" idrouter="<?=$router_id?>" >
										<i class="material-icons">refresh</i>Load&nbsp;
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
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>Service</th>
									<th>Address</th>
									<th>Accounting Port</th>
									<th>Authentication Port</th>
									<th>Action</th>
								</tr>
							</thead>

							<tbody>
								<?php
									if( !empty($radius) ) {
										foreach ($radius as $item) {
								?>
									<tr>
										<td><?=$item['service'];?></td>
										<td><?=$item['address'];?></td>
										<td><?=$item['accounting-port'];?></td>
										<td><?=$item['authentication-port'];?></td>
										<td><?=$item['id_radius_mk'];?></td>
									</tr>
								<?php
										}
									}
								?>
							</tbody>

							<tfoot>
								<tr>
									<th>Service</th>
									<th>Address</th>
									<th>Accounting Port</th>
									<th>Authentication Port</th>
									<th>Action</th>
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
	var oTable = "";

	$(document).ready(function() {
		$('#spinner').hide();

		oTable = $('#data_grid').DataTable({
			// data:[],
			columns: [
				{ "data": "service" },
				{ "data": "address" },
				{ "data": "accounting-port" },
				{ "data": "authentication-port"},
				{ "data": "id_radius_mk"}
			],
			rowCallback: function (row, data) {
				$('td:last', row).html('');
				<?php if(permission("Rou","Edit",$permission_string)===1){ ?>
					var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>router/edit_radius/"+data.id_radius_mk);
					$('td:last', row).append(tmp);
				<?php } ?>
				<?php if(permission("Rou","Delete",$permission_string)===1){ ?>
					tmp = $('<a id="edit_btn" class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>').attr({id_radius_mk:data.id_radius_mk,id_router: <?=$id_router?>});
					$('td:last', row).append(tmp);
				<?php } ?>



			},
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true
		});

		$(".btn-load-radius").on('click', function(e) {
			e.preventDefault();
			showProgressBar();
			var router_id = $(this).attr('idrouter');
			console.log("Router ID " + router_id);
			$.ajax({
				type:"POST",
				dataType:"json",
				data:'router_id=' + router_id,
				url: '<?php echo base_url() ?>router/load_radius',

				success:function(response) {
					console.log("Response Status " + response.status);
					closeProgressBar();
					if(response.status === 'passed') {
						showNotification(2, response.msg);
						refresh_datatable(response.data, 'success', response.msg);
					} else if(response.status === 'failed') {
						showNotification(4, response.msg);
					}
				},
				error: function(response, error) {
					closeProgressBar();
					// showNotification(4, "Communication Error");
						showNotification(3,"Error " + JSON.stringify(result));
				}
			}); // end of ajax call.
		});


		$("#data_grid").on('click', '.delete_item', function(){

				var id_radius_mk = $(this).attr('id_radius_mk');
				var id_router =  $(this).attr('id_router');
					swal({
								 type:'warning',
								 title: 'Are you sure you want to delete Radius?',
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
									data:{'id_radius_mk' : id_radius_mk,'id_router' : id_router},
									url: '<?php echo base_url() ?>router/delete_radius',

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
