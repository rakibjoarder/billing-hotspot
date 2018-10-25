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

								<li  style="float:right; padding-left:8px;" >
  								<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>alert">
  									<i class="material-icons">exit_to_app</i>BACK
  									<div class="ripple-container"></div></a>
  								</a>
  							</li>

								<?php if( permission("Ale","Add",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>alert/create_alert">
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
						<div id="result"></div>
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Alert Type</th>
									<th>Alert Time Interval</th>
									<?php if(permission("Ale","View",$permission_string)===1 || permission("Ale","Add",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1 || permission("Ale","Delete",$permission_string)===1){ ?>
									<th>Action</th>
									<?php }else {?>
									<th class="disabled-sorting"></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($alerts as $alert_indiv):
									?>
									<tr>
										<td><?php echo $alert_indiv['alert_name'] ?></td>
										<td><?php echo $alert_indiv['alert_type_name'] ?></td>
										<td><?php echo $alert_indiv['alert_time_interval'] ?></td>
										<?php if(permission("Ale","View",$permission_string)===1 || permission("Ale","Add",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1 || permission("Ale","Delete",$permission_string)===1){ ?>
										<td><?php echo $alert_indiv['id_alert'] ?></td>
									<?php } ?>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>

							<tfoot>
								<tr>
									<th>Name</th>
									<th>Alert Type</th>
									<th>Alert Time Interval</th>
									<?php if(permission("Use","Edit",$permission_string)===1 || permission("Use","Delete",$permission_string)===1 ){ ?>
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

	var alert_id = "";
	var oTable = "";

$(document).ready(function() {
	$('#spinner').hide();

	oTable = $('#data_grid').DataTable({
		// data:[],
		columns: [
			{ "data": "alert_name" },
			{ "data": "alert_type_name" },
			{ "data": "alert_time_interval" },
			{ "data": "id_alert" }
		],
		rowCallback: function (row, data) {

			$('td:last', row).html('');
			<?php if(permission("Ale","Edit",$permission_string)===1){ ?>
				var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>alert/edit_alert/"+data.id_alert);
				$('td:last', row).append(tmp);
			<?php } ?>
			<?php if(permission("Ale","View",$permission_string)===1 || permission("Ale","Add",$permission_string)===1 || permission("Ale","Edit",$permission_string)===1 || permission("Ale","Delete",$permission_string)===1){ ?>
				var tmp = $('<a id="edit_btn" style="color:red;" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">add_box</i></a>').attr('href', "<?php echo base_url(); ?>alert/alert_layers/"+data.id_alert);
				$('td:last', row).append(tmp);
			<?php } ?>
			<?php if(permission("Ale","Delete",$permission_string)===1){ ?>
				tmp = $('<a id="edit_btn" href="#" class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>').attr('alert_id', data.id_alert);
				$('td:last', row).append(tmp);
			<?php } ?>

		},
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true
	});


	$("#data_grid").on('click', '.delete_item', function(){
     var alert_id = $(this).attr('alert_id');
		swal({
           type:'warning',
           title: 'Are you sure to Delete Alert?',
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
					 	data:'alert_id=' + alert_id,
					 	url: '<?php echo base_url() ?>alert/delete_alert_now',

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