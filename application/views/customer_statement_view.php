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
	<div  class="row">
		<div class="col-md-12 ">


					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">

<ul class="nav nav-tabs" data-tabs="tabs" style="background-color:#00bcd4 !important;" >

							<form id="form_search"  method="POST" class="form-horizontal" role="form">

									<div class="col-sm-3 ">
											<input class="form-control" id="id_customer" name="id_customer" type="text" placeholder="Customer ID" style="background-color:#FFFFFF !important; padding:4px;"/>
									</div>

									<div class="col-sm-3 ">
											<input class="form-control" id="net_user_email" name="net_user_email" type="email" placeholder="Customer Email" style="background-color:#FFFFFF !important; padding:4px;" />
									</div>

					        <div class="col-sm-3 ">
					            <input class="form-control" id="phone" name="phone" type="text" placeholder="Customer Phone"  style="background-color:#FFFFFF !important; padding:4px;"/>
					        </div>

									<input hidden id="start_date" name="start_date" type="text" />
									<input hidden id="end_date" name="end_date" type="text" />
									<input hidden id="return_cutomer_id" name="return_cutomer_id" type="text" />
									<div class="col-md-3 ">
										<div class="form-group">

											<button style="margin-top:-4px;background-color:purple !important; width:100%;"type="submit" id="btn_search" class="btn btn-info " ><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;Search</button>
										</div>
									</div>
							 </form>


							</ul>

					</div>
				</div>

	</div>
	</div>





	<div  hidden  id="div"class="row" style="margin-top:40px;">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title"><span style="font-weight:400;font-size:22px;">Customer UserName</span> - <span style="font-weight:200;font-size:22px;"id="customername"></span></h1></span>
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
													<th class="text-center">Particular </th>
													<th class="text-center">Billing Amount</th>
													<th class="text-center">Recieved Amount</th>
													<th class="text-center">Balance</th>
												</tr>
											</thead>
										<tfoot>
												<tr>
													<th class="text-center">Date</th>
													<th class="text-center">Particular </th>
													<th class="text-center">Billing Amount</th>
													<th class="text-center">Recieved Amount</th>
													<th class="text-center">Balance</th>
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

    var start =  moment().subtract(11, 'month').startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {

			if(start.format('YYYY-MM-DD').toString()==end.format('YYYY-MM-DD').toString()){
				$('#reportrange span').html(start.format('MMMM D, YYYY') );
			}else{
				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}

				var startdate=start.format('YYYY-MM-DD');
				var enddate  =end.format('YYYY-MM-DD');
				$('#start_date').val(startdate);
			  $('#end_date').val(enddate);
  			var idcustomer=$('#return_cutomer_id').val();

				$.ajax({
					type:"POST",
					dataType:"json",
					data:{'start_date':startdate,'end_date':enddate,'id_customer':idcustomer},
					url: '<?php echo base_url() ?>accounting/customer_statement_by_date',

					success:function(response) {
						if(response.status === 'passed') {
							refresh_datatable(response.data, 'success', response.msg);
						}
					}
				});
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          //  'Today': [moment(), moment()],
          //  'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          //  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          //  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					 'Last Six Month': [moment().subtract(5, 'month').startOf('month'), moment().endOf('month')],
					 'Last One Year': [moment().subtract(11, 'month').startOf('month'), moment().endOf('month')],
					 'Last Two Year': [moment().subtract(23, 'month').startOf('month'), moment().endOf('month')],
        }
    }, cb);

    cb(start, end);

});
$(document).ready(function() {



	var startdate =  moment().subtract(11, 'month').startOf('month').format('YYYY-MM-DD');
	var enddate = moment().endOf('month').format('YYYY-MM-DD');

  	$('#start_date').val(startdate);
	  $('#end_date').val(enddate);

	 $('#spinner').hide();

	 $("#form_search").validate({

		 submitHandler: function (form) {
			 var reqData = $("#form_search").serialize();

			 $.ajax({
				 type:"POST",
				 contentType: "application/x-www-form-urlencoded",
				 dataType:"json",
				 data: reqData,
				 url: "<?php echo base_url() ?>accounting/search_customer_statement",

				 success:function(response) {

					 if(response.status === 'passed') {
 					  $('#return_cutomer_id').val(response.id_customer);
						$('#customername').html(response.customer_name);
				   	refresh_datatable(response.data, 'success', response.msg);
            $('#div').show(600);

					 } else if(response.status === 'failed') {
						 $('#div').hide(600);
							 showNotification(3,response.msg);
					 }
				 },
				 error: function (result) {
							showNotification(4,"Error " + JSON.stringify(result));

				 }
			 });
		 }
	 });




	 oTable = $('#search_grid').DataTable({

		 columns: [
		 	{ "data": "date" },
		 	{ "data": "particular" },
		 	{ "data": "billing_amount"},
		 	{ "data": "recieved_amount" },
			{ "data": "balance" }

		 ],
			rowCallback: function (row, data) {

				if (data.billing_amount == 0) {
					$(row).css({'background-color': 'rgba(29, 247, 17, 0.06)'});
				}else if(data.recieved_amount == 0){
					 $(row).css({'background-color': 'rgba(253, 0, 0, 0.06)'});
				}

			},
			filter: false,
			info: false,
			ordering: false,
			processing: false,
			retrieve: false,
			paging:false
		});

});






</script>
