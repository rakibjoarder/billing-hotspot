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
							<span class="nav-tabs-title"><h1 class="card-title">Firewall Of <?=$router_name?></h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<li  style="float:right; padding-left:8px;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>router/routers">
										<i class="material-icons">exit_to_app</i>BACK
										<div class="ripple-container"></div></a>
									</a>
								</li>

							<?php if( permission("Rou","Add",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round btn-load-firewall" idrouter="<?=$router_id?>" >
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
									<th>Chain</th>
									<th>Src Address</th>
									<th>Src Mac Address</th>
									<th>action</th>
									<th>Order</th>
								</tr>
							</thead>

							<tbody>
								<?php
									if( !empty($firewalls) ) {
										foreach ($firewalls as $item) {
								?>
									<tr>
										<td><?=$item['chain'];?></td>
										<td><?=$item['src-address'];?></td>
										<td><?=$item['src-mac-address'];?></td>
										<td><?=$item['action'];?></td>
										<td><?=$item['entry_order'];?></td>
									</tr>
								<?php
										}
									}
								?>
							</tbody>

							<tfoot>
								<tr>
									<th>Chain</th>
									<th>Src Address</th>
									<th>Src Mac Address</th>
									<th>action</th>
									<th>Order</th>
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
				{ "data": "chain" },
				{ "data": "src-address" },
				{ "data": "src-mac-address" },
				{ "data": "action" },
				{ "data": "entry_order"}
			],
			rowCallback: function (row, data) {},
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true
		});

		$(".btn-load-firewall").on('click', function(e) {
			e.preventDefault();
			showProgressBar();
			var router_id = $(this).attr('idrouter');
			console.log("Router ID " + router_id);
			$.ajax({
				type:"POST",
				dataType:"json",
				data:'router_id=' + router_id,
				url: '<?php echo base_url() ?>router/load_firewall',

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

	});

</script>
