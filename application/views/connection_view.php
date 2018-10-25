<?php
	require_once('header.php');
?>
<div id="spinner" class="loader"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple"><i class="material-icons">assignment</i></div>
					<div class="card-content">
						<h4 class="card-title">All Links</h4>
							<div class="toolbar"><!--        Here you can write extra buttons/actions for the toolbar              --></div>
								<div class="material-datatables">
									<div id="result"></div>
										<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
											<thead>
												<tr>
													<th>Customer Name</th>
													<th>Phone</th>
													<th>Connection Name</th>
													<th>Address</th>
								          <th>Customer Type</th>
													<th>Router Name</th>
								          <th>Ip Address</th>
								          <th>MAC</th>
								          <th class="disabled-sorting">Action</th>
												</tr>
											</thead>
												<tbody>
													<?php
													foreach($connections as $connection_indiv):
														?>
														<tr>
															<td><?php echo $connection_indiv['net_user_name'] ?></td>
															<td><?php echo $connection_indiv['phone'] ?></td>
															<td><?php echo $connection_indiv['connectionname'] ?></td>
															<td><?php echo $connection_indiv['address'] ?>,<?php echo $connection_indiv['town'] ?>,<?php echo $connection_indiv['district_name'] ?></td>
															<td><?php echo $connection_indiv['net_user_type'] ?></td>
															<td><?php echo $connection_indiv['name'] ?></td>
															<td><?php echo $connection_indiv['ip_addr'] ?></td>
															<td><?php echo $connection_indiv['mac'] ?></td>
															<td><?php echo $connection_indiv['id_connection'] ?></td>
														</tr>
														<?php
													endforeach;
													?>
												</tbody>
											<tfoot>
												<tr>
								          <th>Customer Name</th>
								          <th>Phone</th>
													<th>Connection Name</th>
													<th>Adress</th>
								          <th>Customer Type</th>
								          <th>Router Name</th>
								          <th>Ip Address</th>
								          <th>MAC</th>
								          <th class="disabled-sorting">Action</th>
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

var id_connection = "";
var oTable = "";

$(document).ready(function() {
	$('#spinner').hide();

	oTable = $('#data_grid').DataTable({
		// data:[],
		columns: [
			{ "data": "net_user_name" },
			{ "data": "phone" },
			{ "data": "connectionname" },
			{ "data": "address" },
			{ "data": "net_user_type" },
			{ "data": "name" },
			{ "data": "ip_addr" },
			{ "data": "mac" },
			{ "data": "id_connection" }
		],
		rowCallback: function (row, data) {

			$('td:last', row).html('');

			var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>connection/edit_connection/"+data.id_connection);
			$('td:last', row).append(tmp);

			tmp = $('<a id="edit_btn" href="#" class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>').attr('connection_id', data.id_connection);
			$('td:last', row).append(tmp);
		},
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true
	});


	$("#data_grid").on('click', '.delete_item', function(){

		var id_connection = $(this).attr('connection_id');
			swal({
	           type:'warning',
	           title: 'Are you sure you want to Delete this Link?',
	           text: 'You will not be able to recover the data',
	           showCancelButton: true,
	           confirmButtonColor: '#049F0C',
	           cancelButtonColor:'#ff0000',
	           confirmButtonText: 'Yes, delete it!',
	           cancelButtonText: 'No, keep it'
	         }).then(function (res) {
							 $.ajax({
							 	type:"POST",
							 	dataType:"json",
							 	data:'connection_id=' + id_connection,
							 	url: '<?php echo base_url() ?>connection/delete_connection',

							 	success:function(response) {
							 		console.log("Response Status " + response.status);

							 		if(response.status === 'passed') {
										showNotification(2,response.msg);
										refresh_datatable(response.data, 'success', response.msg);
							 		} else {
							 			showNotification(3,response.msg);
							 		}
							 	}
							 });
				    });
				});
});

</script>
