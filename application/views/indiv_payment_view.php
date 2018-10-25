<?php
require_once('header.php');
if( (permission("Cus","Vi_ew P@yment",$permission_string)===0 ) || permission("Cus","View",$permission_string)=== -1){
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
  padding: 15px !important;
  outline: 0;
}
</style>

<div id="spinner" class="loader"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All Payment</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
									<li  style="float:right;" >
										<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>ppp">
											<i class="material-icons">exit_to_app</i>BACK
											<div class="ripple-container"></div></a>
										</a>
									</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="card-content">
					<div class="toolbar"><!--Here you can write extra buttons/actions for the toolbar--></div>
					<div class="material-datatables table-responsive">
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th  class="text-center">Invoice #</th>
									<th class="text-center">Invoice Amount</th>
									<th class="text-center">Amount Paid</th>
									<th class="text-center">Invoice Date</th>
									<th class="disabled-sorting text-center">Payment Date</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($payment as $payment):
									?>
									<tr>
										<td class="text-center">#<?php echo $payment['id_invoice']?></td>
										<td class="text-center"><?php echo $payment['invoice_original_amount']?></td>
										<td class="text-center"><?php echo $payment['paid_amount']?></td>
										<td class="text-center"><?php echo $payment['invoice_date']?></td>
										<td class="text-center"><?php echo $payment['payment_date']?></td>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>
							<tfoot>
                <tr>
									<th  class="text-center">Invoice #</th>
									<th class="text-center">Invoice Amount</th>
									<th class="text-center">Amount Paid</th>
									<th class="text-center">Invoice Date</th>
									<th class="disabled-sorting text-center">Payment Date</th>
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

	var superuser_id = "";
	var oTable = "";

	$(document).ready(function() {
		$('#spinner').hide();
    oTable = $('#data_grid').DataTable();
	});

</script>
