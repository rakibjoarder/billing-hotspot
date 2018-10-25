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
		text-align:  left;
		padding: 15px !important;
	}

	table.dataTable > thead > tr > th:last-child,
	table.dataTable > tbody > tr > th:last-child,
	table.dataTable > tfoot > tr > th:last-child,
	table.dataTable > thead > tr > td:last-child,
	table.dataTable > tbody > tr > td:last-child,
	table.dataTable > tfoot > tr > td:last-child {
		text-align:  right;
		padding: 15px !important;
	}

	table.dataTable > tbody tr:nth-child(odd){
	  background-color: #F5F5F5;
	}
	table.dataTable > tbody tr:nth-child(even){
		background-color: #EEEEEE;
    border-bottom: solid #EEEEEE;
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
							<span class="nav-tabs-title"><h1 class="card-title">All IP Addr - <?= $router_name?></h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<li  style="float:right; padding-left:8px;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>router/routers">
										<i class="material-icons">exit_to_app</i>BACK
										<div class="ripple-container"></div></a>
									</a>
								</li>

							<?php if( permission("Rou","Add",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round btn-load-ip" idrouter="<?=$router_id?>" >
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
									<th>IP Addr</th>
									<th>Network</th>
									<th>Interface</th>
									<th>Status</th>
								</tr>
							</thead>

							<tbody>
								<?php
									if( !empty($router_ips) ) {
										foreach ($router_ips as $address) {
								?>
									<tr>
										<td><?=$address['ip_addr'];?></td>
										<td><?=$address['network'];?></td>
										<td><?=$address['interface'];?></td>
										<td><?=$address['status'];?></td>
									</tr>
								<?php
										}
									}
								?>
							</tbody>

							<tfoot>
								<tr>
									<th>IP Addr</th>
									<th>Network</th>
									<th>Interface</th>
									<th>Status</th>
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

	showPageLoader();

	$(document).ready(function() {
		closePageLoader();

		oTable = $('#data_grid').DataTable({
			// data:[],
			columns: [
				{ "data": "ip_addr" },
				{ "data": "network" },
				{ "data": "interface" },
				{ "data": "status" }
			],
			rowCallback: function (row, data) {
				if($('td:last', row).html() === '1') {
					$('td:last', row).html('');
					$('td:last', row).append('Enabled');
				} else {
					$('td:last', row).html('');
					$('td:last', row).append('Disabled');
				}
			},
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true
		});

		$(".btn-load-ip").on('click', function(e) {
			e.preventDefault();
			showProgressBar();
			var router_id = $(this).attr('idrouter');
			console.log("Router ID " + router_id);
			$.ajax({
				type:"POST",
				dataType:"json",
				data:'router_id=' + router_id,
				url: '<?php echo base_url() ?>router/load_ips',

				success:function(response) {
					console.log("Response Status " + response.status);
					closeProgressBar();
					if(response.status === 'passed') {
						showNotification(2, response.msg);
						refresh_datatable(response.data, 'success', response.msg);
					} else {
						showNotification(4, response.msg);
					}
				}
			}); // end of ajax call.
		});

	});

</script>
