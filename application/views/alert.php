<?php
	require_once('header.php');
	if( (permission("Ale","Edit",$permission_string)===0 && permission("Ale","Delete",$permission_string)===0 &&
	permission("Ale","View",$permission_string)===0 ) || permission("Ale","View",$permission_string)=== -1){
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

<div  id="container" class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All Alerts</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
							<?php if( permission("Ale","Add",$permission_string)===1 || permission("Ale","View",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1|| permission("Ale","Del",$permission_string)===1){ ?>
							 <li  style="float:right; margin-right:10px;" >
								 <a class="btn btn-info btn-round" href="<?php echo base_url(); ?>alert/configure_alert">
									 <i class="material-icons">settings</i>Configure&nbsp;
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
						<div id="result"></div>
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>Alert Message</th>
									<th>User ID</th>
									<th>Generate Time</th>
									<th>Stop time</th>
									<th>Stopped by</th>
									<?php if(permission("Ale","Edit",$permission_string)===1){ ?>
									<th>Action</th>
									<?php }else {?>
									<th class="disabled-sorting"></th>
										<?php }?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($alerts as $alert_indiv):
									?>
									<tr>
										<td><?php echo $alert_indiv['alert_message'] ?></td>
										<td><?php echo $alert_indiv['id_user'] ?></td>
										<td><?php echo $alert_indiv['generate_time'] ?></td>
										<td><?php echo $alert_indiv['stop_time'] ?></td>
										<td><?php echo $alert_indiv['stopped_by'] ?></td>
										<?php if(permission("Ale","Edit",$permission_string)===1){ ?>
										<td><?php echo $alert_indiv['id_system_alert'] ?></td>
										<?php } ?>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>

							<tfoot>
								<tr>
									<th>Alert Message</th>
									<th>User ID</th>
									<th>Generate Time</th>
									<th>Stop time</th>
									<th>Stopped by</th>
									<?php if(permission("Ale","Edit",$permission_string)===1){ ?>
									<th>Action</th>
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

	var alert_id = "";
	var oTable = "";

$(document).ready(function() {
	$('#spinner').hide();

	oTable = $('#data_grid').DataTable({
		// data:[],

		columns: [
			{ "data": "alert_message" },
			{ "data": "id_user" },
			{ "data": "generate_time" },
			{ "data": "stop_time" },
			{ "data": "stopped_by" },
			{ "data": "id_system_alert" }
		],
		columnDefs:[
			{
				"targets": [ 1 ],
				"visible": false,
				"searchable": true
			}],
		rowCallback: function (row, data) {


	<?php if(permission("Ale","Edit",$permission_string)===1){ ?>
			$('td:last', row).html('');
			if(data.id_user == 0){
				tmp = $('<a id="edit_btn" class="btn btn-simple btn-danger btn-icon stop_alert"><i class="material-icons">stop</i></a>').attr('id_system_alert', data.id_system_alert);
				$('td:last', row).append(tmp);
			}else{
				tmp = $('<a id="edit_btn" class="btn btn-simple btn-success btn-icon "><i class="material-icons">done</i></a>');
				$('td:last', row).append(tmp);
			}
				<?php } ?>
		},
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true
	});


	$("#data_grid").on('click', '.stop_alert', function(){
     var id_system_alert = $(this).attr('id_system_alert');
		swal({
           type:'warning',
           title: 'Are you sure to Stop Alert?',
           text: 'You will not be able to recover the data ',
           showCancelButton: true,
           confirmButtonColor: '#049F0C',
           cancelButtonColor:'#ff0000',
           confirmButtonText: 'Yes, Stop it!',
           cancelButtonText: 'Cancel'
         }).then(function (res) {
					 $.ajax({
					 	type:"POST",
					 	dataType:"json",
					 	data:'id_system_alert=' + id_system_alert,
					 	url: '<?php echo base_url() ?>alert/stop_alert_now',

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
	});
});

</script>
