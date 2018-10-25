<?php
require_once('header.php');
if( (permission("Pay","View",$permission_string)===0  && permission("Pay","ADD",$permission_string)===0) || permission("Pay","View",$permission_string)=== -1){
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
		padding: 10px !important;

	}

	.btn.btn-sm,
	.btn-group-sm .btn,
	.navbar .navbar-nav > li > a.btn.btn-sm,
	.btn-group-sm .navbar
	.navbar-nav > li > a.btn {
		background-color: #26C6DA;
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
							<span class="nav-tabs-title"><h1 class="card-title">All Payments</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<li style="float:right; margin: 10px 5px 5px 5px;" >
									<a class="btn btn-info btn-round pull-right btn-datepicker" href="#">
										<div id="datepicker" class="pull-right" style="background-color:rgba(0, 188, 212, 0.9); color:#FFFFFF; cursor: pointer; font-size:15px; border-radius:20px; font-weight:400; width: auto;">
									    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									    <span></span> <b class="caret"></b>
										</div>
									</a>
								</li>
								<?php if (permission("Pay","Add",$permission_string)!= 0) { ?>
									<li style="float:right;  margin: 10px 5px 5px 5px;" >
										<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>payment">
											<i class="material-icons">add</i>Add&nbsp;
											<div class="ripple-container"></div>
										</a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="card-content">
					<div class="material-datatables table-responsive">
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th  class="text-center">Invoice #</th>
									<th class="text-center">User Name</th>
									<th class="text-center">Email</th>
									<th class="text-center">Phone Number</th>
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
										<td class="text-center"><?php echo  $payment['net_user_username'] ?></td>
										<td class="text-center"><?php echo  $payment['net_user_email'] ?></td>
										<td class="text-center"><?php echo  $payment['net_user_phone'] ?></td>
										<td class="text-center"><?php echo  $payment['invoice_original_amount']?></td>
										<td class="text-center"><?php echo  $payment['paid_amount']?></td>
										<td class="text-center"><?php echo  $payment['invoice_date']?></td>
										<td class="text-center"><?php echo  $payment['payment_date']?></td>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>
							<tfoot>
                <tr>
									<th  class="text-center">Invoice #</th>
									<th class="text-center">User Name</th>
									<th class="text-center">Email</th>
									<th class="text-center">Phone Number</th>
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
$(function() {

    var start = moment().startOf('month');
    var end = moment();

    function cb(start, end) {

			if(start.format('YYYY-MM-DD').toString()==end.format('YYYY-MM-DD').toString()){
				$('#datepicker span').html(start.format('MMM D, YYYY') );
			}else{
				$('#datepicker span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
			}

				var startdate=start.format('YYYY-MM-DD');
				var enddate  =end.format('YYYY-MM-DD');

				$.ajax({
					type:"POST",
					dataType:"json",
					data:{'start_date':startdate,'end_date':enddate},
					url: '<?php echo base_url() ?>payment/get_payments_by_date_range',

					success:function(response) {
						if(response.status === 'passed') {
							 refresh_datatable(response.data, 'success', response.msg);
						}
					}
				});
    }
		$('.btn-datepicker').on('click', function(e) {
			console.log("report click");
			e.preventDefault();
		});

		$('#datepicker').daterangepicker({
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
</script>


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


		 columns: [
		 	{ "data": "id_invoice" },
			{ "data": "net_user_username" },
			{ "data": "net_user_email" },
			{ "data": "net_user_phone" },
		 	{ "data": "invoice_original_amount" },
		 	{ "data": "paid_amount"},
		 	{ "data": "invoice_date" },
		 	{ "data": "payment_date" }
		 ],
 		columnDefs:[

 			{
 				"targets": [ 2 ],
 				"visible": false,
 				"searchable": true
 			},
 			{
 				"targets": [ 3 ],
 				"visible": false,
 				"searchable": true
 			}
 		],
			rowCallback: function (row, data) {

			},drawCallback: function( settings ) {

	    },
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true,
		});
});
</script>
