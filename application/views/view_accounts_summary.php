<?php
require_once('header.php');
if( (permission("Rep","View",$permission_string)===0 ) || permission("Rep","View",$permission_string)=== -1){
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
	text-align:  center;
}
.card [data-background-color] a {
    color: #000000;
}

.dropdown-menu li a:hover, .dropdown-menu li a:focus, .dropdown-menu li a:active {
    background-color: #26C6DA;
    color: #FFFFFF;
}
</style>

							</div>
							<div id="spinner" class="loader"></div>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header card-header-tabs" data-background-color="blue">
												<div class="nav-tabs-navigation">
													<div class="nav-tabs-wrapper">
														<span class="nav-tabs-title"><h1 class="card-title" id="title">Accounts Summary</h1></span>
														<ul class="nav nav-tabs" data-tabs="tabs">
															<div class="nav nav-tabs" data-tabs="tabs" style="width:100px;float:right;font-size:25px;">
																	<select class="selectpicker" data-style="btn btn-info btn-round" title="Single Select" name="year_select"data-size="17">
																			<?php foreach ($years as $year): ?>
																			<option value="<?= $year['year']?>"><?= $year['year'] ?></option>
																			<?php endforeach; ?>
																	</select>
															</div>
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
									<th  class="text-center disabled-sorting">Month</th>
									<th class="text-center disabled-sorting">Total No. Invoice </th>
									<th class="text-center disabled-sorting">Total Billing Amount</th>
									<th class="text-center disabled-sorting">Total No. Payment</th>
									<th class="text-center disabled-sorting">Total Recieved Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($accounts_summary as $summary):
									?>
									<tr>
										<td class="text-center"><?php echo $summary['invoice_date']?></td>
										<td class="text-center"><?php echo $summary['total_invoice']?></td>
										<td class="text-center"><?php echo $summary['total_billing_amount']?></td>
										<td class="text-center"><?php echo $summary['total_payment']?></td>
										<td class="text-center"><?php echo $summary['total_recieved_amount']?></td>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>
							<tfoot>
                <tr>
									<th  class="text-center disabled-sorting">Month</th>
									<th class="text-center disabled-sorting">Total No. Invoice </th>
									<th class="text-center disabled-sorting">Total Billing Amount</th>
									<th class="text-center disabled-sorting">Total No. Payment</th>
									<th class="text-center disabled-sorting">Total Recieved Amount</th>
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

</script>
<script type="text/javascript">

var package_id = "";
var oTable = "";

$(document).ready(function() {




	 $('#spinner').hide();

	 oTable = $('#data_grid').DataTable({


		 columns: [
		 	{ "data": "invoice_date" },
		 	{ "data": "total_invoice" },
		 	{ "data": "total_billing_amount"},
			{ "data": "total_payment"},
			{ "data": "total_recieved_amount"}
		 ],
			rowCallback: function (row, data) {
				$('td:first', row).html('');

					 if (data.invoice_date == 01) {
						 var tmp = $('<span >January</span>');
					 }else if(data.invoice_date == 02){
						 var tmp = $('<span >February</span>');
					 }else if(data.invoice_date == 03){
						 var tmp = $('<span >March</span>');
					 }else if(data.invoice_date == 04){
						 var tmp = $('<span >April</span>');
					 }else if(data.invoice_date == 05){
						 var tmp = $('<span>May</span>');
					 }else if(data.invoice_date == 06){
						 var tmp = $('<span>June</span>');
					 }else if(data.invoice_date == 07){
						 var tmp = $('<span>July</span>');
					 }else if(data.invoice_date == 08){
						 var tmp = $('<span >August</span>');
					 }else if(data.invoice_date == 09){
						 var tmp = $('<span >September</span>');
					 }else if(data.invoice_date == 10){
						 var tmp = $('<span >October</span>');
					 }else if(data.invoice_date == 11){
						 var tmp = $('<span >November</span>');
					 }else if(data.invoice_date == 12){
						 var tmp = $('<span >December</span>');
					 }
 	$('td:first', row).append(tmp);


			},
			info: true,
			ordering: true,
			processing: true,
			retrieve: true,
 			paging: false
		});



$('select[name="year_select"]').val("2018").change();

		$('select[name="year_select"]').on('change',function(){

				var year=$('option:selected', this).val();
				$.ajax({
				 type:"POST",
				 dataType:"json",
				 data:'year=' + year,
				 url: '<?php echo base_url() ?>Accounting/get_summary_by_year',

				 success:function(response) {

					 if(response.status === 'passed') {
						 refresh_datatable(response.data, 'success', response.msg);
					 }
				 }
				});

		});
});
</script>
