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

.btn.btn-sm,
.btn-group-sm .btn,
.navbar .navbar-nav > li > a.btn.btn-sm,
.btn-group-sm .navbar
.navbar-nav > li > a.btn
{
	background-color: #26C6DA;
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
							<span class="nav-tabs-title"><h1 class="card-title">Invoice Statement</h1></span>
							<div  class="pull-right" style=" background-color:rgba(0, 188, 212, 0.9); color:#FFFFFF;cursor: pointer;margin-top:5px; padding: 10px 20px; font-size:15px; border-radius:20px; font-weight:400; width: auto">
								<p class="card-title " style="font-size:19px;" >Total :<span id="billing_amount"></span></p>
							</div>
							<div id="reportrange" class="pull-right" style=" margin-right:10px; background-color:rgba(0, 188, 212, 0.9); color:#FFFFFF;cursor: pointer;margin-top:5px; padding: 10px 20px; font-size:15px; border-radius:20px; font-weight:400; width: auto">
							    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							    <span></span> <b class="caret"></b>
							</div>




							<ul class="nav nav-tabs" data-tabs="tabs" >

							</ul>
						</div>
					</div>
				</div>

					<div class="card-content">
						<div class="row">

						</div>
								<div class="material-datatables table-responsive">
									<div id="result"></div>
										<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
											<thead>
												<tr>
													<th class="text-center">Date</th>
													<th class="text-center">Customer ID</th>
													<th class="text-center">Invoice ID</th>
													<th class="text-center">Billing Amount (Taka)</th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach($all_invoices as $invoice){
													?>
													<tr>
														<td class="text-center"><?php echo $invoice['invoice_date'] ?></td>
														<td class="text-center"><?php echo $invoice['id_net_user'] ?></td>
														<td class="text-center"><?php echo $invoice['id_invoice'] ?></td>
														<td class="text-center"><?php echo $invoice['invoice_original_amount'] ?></td>
													</tr>
													<?php
												}
												?>
											</tbody>
												<tr>
													<th class="text-center">Date</th>
													<th class="text-center">Customer ID</th>
													<th class="text-center">Invoice ID</th>
													<th class="text-center">Billing Amount (Taka)</th>
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

$(function() {

	var start =  moment().startOf('month');
	var end = moment();

	function cb(start, end) {

		if(start.format('YYYY-MM-DD').toString()==end.format('YYYY-MM-DD').toString()){
			$('#reportrange span').html(start.format('MMM D, YYYY') );
		}else{
			$('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
		}

		var startdate=start.format('YYYY-MM-DD');
		var enddate  =end.format('YYYY-MM-DD');

		$.ajax({
			type:"POST",
			dataType:"json",
			data:{'start_date':startdate,'end_date':enddate},
			url: '<?php echo base_url() ?>billing/get_all_invoice_statement_by_date_range',

			success:function(response) {
				if(response.status === 'passed') {
					refresh_datatable(response.data, 'success', response.msg);
					var table = $('#search_grid').DataTable();
					var sum=table.column( 3 ).data().sum();
					$('#billing_amount').html(' '+sum.toFixed(2));
					$('#billing_amount').attr('style','font-weight:bold');
					$('#billing_amount').attr('style','font-size:22px');
				}
			}
		});
	}

	$('#reportrange').daterangepicker({
		startDate: start,
		endDate: end,
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	}, cb);

	cb(start, end);

});

$(document).ready(function() {

	$('#spinner').hide();

	oTable = $('#search_grid').DataTable({

		columns: [
			{ "data": "invoice_date" },
			{ "data": "id_net_user" },
			{ "data": "id_invoice"},
			{ "data": "invoice_original_amount" }

		],
		rowCallback: function (row, data) {

			if (data.invoice_status == 1) {
				$(row).css({'background-color': 'rgba(29, 247, 17, 0.06)'});
			}else if(data.invoice_status == 0){
				$(row).css({'background-color': 'rgba(253, 0, 0, 0.06)'});
			}
		},
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true,
		iDisplayLength: 30
	});

});

jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
	return this.flatten().reduce( function ( a, b ) {
		if ( typeof a === 'string' ) {
			a = a.replace(/[^\d.-]/g, '') * 1;
		}
		if ( typeof b === 'string' ) {
			b = b.replace(/[^\d.-]/g, '') * 1;
		}

		return a + b;
	}, 0 );
} );

</script>
