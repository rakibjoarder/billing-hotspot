<?php
	require_once('header.php');
	if( (permission("Zon","Add",$permission_string)===0 && permission("Zon","Edit",$permission_string)===0 && permission("Zon","Delete",$permission_string)===0 &&
		permission("Zon","View",$permission_string)===0 ) || permission("Zon","View",$permission_string)=== -1){
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
							<span class="nav-tabs-title"><h1 class="card-title">All Zones</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<?php if( permission("Zon","Add",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>zone/create_zone">
										<i class="material-icons">add</i>ADD&nbsp;
										<div class="ripple-container"></div></a>
									</a>
								</li>
							<?php }?>
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
									<th>Zone Name</th>
									<?php if(permission("Zon","Edit",$permission_string)===1 || permission("Zon","Delete",$permission_string)===1 ){ ?>
									<th class="disabled-sorting">Action</th>
								<?php  }?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($zones as $zone_indiv):
									?>
									<tr>
										<td><?php echo $zone_indiv['zone_name'] ?></td>
										<?php if(permission("Zon","Edit",$permission_string)===1 || permission("Zon","Delete",$permission_string)===1 ){ ?>
											<td><?php echo $zone_indiv['id_zone'] ?></td>
										<?php } ?>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>
							<tfoot>
								<tr>
									<th>Zone Name</th>
									<?php if(permission("Zon","Edit",$permission_string)===1 || permission("Zon","Delete",$permission_string)===1 ){ ?>
									<th class="disabled-sorting">Action</th>
								<?php  }?>
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


	var oTable = "";

$(document).ready(function() {
	$("#fakeLoader").hide();
	$("#container").show();
	oTable = $('#data_grid').DataTable({
		// data:[],
		columns: [
			{ "data": "zone_name" },
			{ "data": "id_zone" }
		],
		rowCallback: function (row, data) {
			$('td:last', row).html('');
			<?php if(permission("Zon","Edit",$permission_string)===1){ ?>
				var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>zone/edit_zone/"+data.id_zone);
				$('td:last', row).append(tmp);
					<?php } ?>
				<?php if(permission("Zon","Delete",$permission_string)===1){ ?>
				tmp = $('<a id="edit_btn"  class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>').attr('id_zone', data.id_zone);
				<?php } ?>
				$('td:last', row).append(tmp);
		},
		drawCallback: function( settings ) {

    },
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true
	});


	$("#data_grid").on('click', '.delete_item', function(){
		var id_zone = $(this).attr('id_zone');
		swal({
			type:'warning',
			title: 'Are you sure to Delete Zone?',
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
				data:'id_zone=' + id_zone,
				url: '<?php echo base_url() ?>zone/delete_zone_now',

				success:function(response) {
					if(response.status === 'passed') {
						refresh_datatable(response.data, 'success', response.msg);
						showNotification(2,response.msg);
					} else {
						showNotification(4,response.msg);
					}
				}
			});
		});
	});
});

</script>
