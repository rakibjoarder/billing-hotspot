<?php
	require_once('header.php');
	if( (permission("Rou","Edit",$permission_string)===0 && permission("Rou","Delete",$permission_string)===0 &&
	permission("Rou","View",$permission_string)===0 ) || permission("Rou","View",$permission_string)=== -1){
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
							<span class="nav-tabs-title"><h1 class="card-title">All Routers</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<?php if( permission("Rou","Add",$permission_string)===1){ ?>
									<li  style="float:right;" >
										<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>router/create_router">
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
					<div class="material-datatables " id="div">
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>ST</th>
									<th>Sync Router</th>
									<th>Radius Flag</th>
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
										<td><?php echo $router_indiv['is_active'] ?></td>
										<td><?php echo $router_indiv['sync_router_flag'] ?></td>
										<td><?php echo $router_indiv['radius_flag'] ?></td>
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
									<th>ST</th>
									<th>Sync Router</th>
									<th>Radius Flag</th>
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

$("#fakeLoader").fakeLoader({
spinner:"spinner4",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
bgColor:"#EEEEEE"
});

/* JavaScript Media Queries */
if (matchMedia) {
	const mq = window.matchMedia("(min-width: 1100px)");
	mq.addListener(WidthChange);
	WidthChange(mq);
}

// media query change
function WidthChange(mq) {

if(mq.matches){
	$('#div').removeClass('table-responsive');
}else{
	$('#div').addClass('table-responsive');
}

}


	var id_router = "";
	var oTable = "";

$(document).ready(function() {
	$("#fakeLoader").hide();
	$("#container").show();

	$('[data-toggle="tooltip"]').tooltip();

	$('#data_grid').on('draw.dt', function () {
		$('[data-toggle="tooltip"]').tooltip();
	});

	oTable = $('#data_grid').DataTable({
		// data:[],
		columns: [
			{ "data": "is_active" },
			{ "data": "sync_router_flag" },
			{ "data": "radius_flag" },
			{ "data": "name" },
			{ "data": "ip_address" },
			{ "data": "router_type_name" },
			{ "data": "login" },
			{ "data": "id" }
		],
		columnDefs:[
			{
				"targets": [ 1 ],
				"visible": false,
				"searchable": false
			},
			{
				"targets": [ 2 ],
				"visible": false,
				"searchable": false
			}
		],
		rowCallback: function (row, data) {
			$('td:last', row).html('');
			$('td:first', row).html('');

			if (data.is_active == 0) {
				$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Router Active' class='material-icons' style='color: #3ba550; margin-right: 5px; font-size: 15px;'>check_circle </i>");
			}else{
				$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Router Disable' class='material-icons' style='color: #ff0000; margin-right: 5px; font-size: 15px;'>cancel</i>");
			}

			// if (data.sync_router_flag == 1) {
			// 	$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Sync Active' class='material-icons' style='color: #3ba550; font-size: 15px;'>sync</i>");
			// }else{
			// 	$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Sync Disable' class='material-icons' style='color: #ff0000; font-size: 15px;'>sync_disabled </i>");
			// }
			//
			// if (data.radius_flag == 1) {
			// 	$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Radius Enable' class='material-icons' style='margin-left :5px; color: #3ba550; font-size: 15px;'>wifi_tethering</i>");
			// }else{
			// 	$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Radius Disable' class='material-icons' style='margin-left :5px; color: #ff0000; font-size: 15px;'>portable_wifi_off </i>");
			// }

			actionBtn = $('<button class="btn btn-round btn-info dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="true"></button>');
			actionBtn.append('<i class="material-icons">view_list</i>');
			actionBtn.append('<span class="caret"></span>');
			actionBtn.append('<div class="ripple-container"></div>');

			actionUl = $('<ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>');
			actionLi = $('<li></li>');
			actionLink  = $('<a ><i style="color:orange;font-size:18px;padding:3px;"class="material-icons">create</i>Edit Router<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/edit_routers/"+data.id);
			actionLink1 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>View Details<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/view_routers_details/"+data.id);
			actionLink2 = $('<a  class="delete_item"><i style="color:red;font-size:18px;padding:3px;" class="material-icons">delete</i>Delete Router<div class="ripple-container"></div></a>').attr('id_router', data.id);
			actionLink3 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>IP Pools<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/ip_pool/"+data.id);
			actionLink4 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>Profile<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/router_profile/"+data.id);
			actionLink5 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>Firewall<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/router_firewall/" + data.id);
			// actionLink6 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>Queue<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/router_queue_simple/" + data.id);
			actionLink7 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>IP Address<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/router_ipaddr/" + data.id);
			actionLink8 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>Radius<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>router/router_radius/" + data.id);

			<?php if(permission("Rou","Edit",$permission_string)===1){ ?>
				actionLi.append(actionLink);
			<?php } ?>

			<?php if(permission("Rou","View",$permission_string)===1){ ?>
				actionLi.append(actionLink1);
			<?php } ?>

			<?php if(permission("Rou","Delete",$permission_string)===1){ ?>
				actionLi.append(actionLink2);
			<?php } ?>

			<?php if(permission("Rou","View",$permission_string)===1){ ?>
				actionLi.append(actionLink3);
			<?php } ?>

			<?php if(permission("Rou","View",$permission_string)===1){ ?>
				actionLi.append(actionLink4);
			<?php } ?>

			<?php if(permission("Rou","View",$permission_string) === 1) { ?>
				actionLi.append(actionLink5);
			<?php } ?>

			<?php if(permission("Rou", "View", $permission_string) === 1) { ?>
				actionLi.append(actionLink7);
			<?php } ?>

			<?php if(permission("Rou", "View", $permission_string) === 1) { ?>
				actionLi.append(actionLink8);
			<?php } ?>

			actionUl.append(actionLi);
			actionDiv = $('<div class="dropdown"></div>');
			actionDiv.append(actionBtn);
			actionDiv.append(actionUl);

			$('td:last', row).append(actionDiv);

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
