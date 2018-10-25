<?php
	require_once('header.php');
	if(permission("Rou","View",$permission_string)===0 || permission("Rou","View",$permission_string)=== -1){
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
padding: 15px!important;
}
</style>

<div id="spinner" class="loader"></div>
<!-- <div class="page_heading">All Users</div> -->
<div id="result"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">Router Details</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<li  style="float:right; padding-left:8px;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>router/routers">
										<i class="material-icons">exit_to_app</i>BACK
										<div class="ripple-container"></div></a>
									</a>
								</li>
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
									<th>Name</th>
									<th>IP Address</th>
									<th>Router Type</th>
									<th>Login Name</th>
									<?php if(permission("Rou","Edit",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 ){ ?>
									<th>Action</th>
									<?php }else {?>
									<th class="disabled-sorting"></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($routers as $router_indiv):
									?>
									<tr>
										<td><?php echo $router_indiv['name'] ?></td>
										<td><?php echo $router_indiv['ip_address'] ?></td>
										<td><?php echo $router_indiv['router_type_name'] ?></td>
										<td><?php echo $router_indiv['login'] ?></td>
										<?php if(permission("Rou","Edit",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 ){ ?>
											<td><?php echo $router_indiv['id'] ?></td>
										<?php } ?>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>IP Address</th>
									<th>Router Type</th>
									<th>Login Name</th>
									<?php if(permission("Rou","Edit",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1 ){ ?>
										<th>Action</th>
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

	var id_router = "";
	var oTable = "";

$(document).ready(function() {
	$('#spinner').hide();

	oTable = $('#data_grid').DataTable({
		// data:[],
		columns: [
			{ "data": ".id" },
			{ "data": "name" },
			{ "data": "type" }
		],
		rowCallback: function (row, data) {

		},
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true
	});


	$("#data_grid").on('click', '.delete_item', function(){
     var id_router = $(this).attr('id_router');
		swal({
           type:'warning',
           title: 'Are you sure to Delete Router?',
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
					 	data:'id_router=' + id_router,
					 	url: '<?php echo base_url() ?>router/delete_router_now',

					 	success:function(response) {
					 		console.log("Response Status " + response.status);

					 		if(response.status === 'passed') {
					 			refresh_datatable(response.data, 'success', response.msg);
                showNotification(2,response.msg);
							//location.reload();
					 		} else {
					 			showNotification(4,response.msg);
					 		}
					 	}
					 });
    });
		console.log(' id_router  = ' + id_router );
	});
});

</script>
