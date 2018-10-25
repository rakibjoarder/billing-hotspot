<?php
	require_once('header.php');
	if( ((permission("Inv","View",$permission_string)===0 ) && (permission("Inv","Email",$permission_string)===0 ) && (permission("Inv","Sms",$permission_string)===0 ))|| permission("Inv","View",$permission_string)=== -1){
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

.btn.btn-sm,
.btn-group-sm .btn,
.navbar .navbar-nav > li > a.btn.btn-sm,
.btn-group-sm .navbar
.navbar-nav > li > a.btn
{
	background-color: #26C6DA;
}


</style>




<div id="fakeLoader"></div>

<div hidden id="container" class="row">
	<div class="col-md-12 col-sm-12">
		<div class="card">
			<div class="card-header card-header-tabs" data-background-color="blue">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper">
						<span class="nav-tabs-title"><h1 class="card-title">All Invoices</h1></span>
						<div id="reportrange" class="pull-right" style=" background-color:rgba(0, 188, 212, 0.9); color:#FFFFFF;cursor: pointer;margin-top:5px; padding: 10px 20px; font-size:15px; border-radius:20px; font-weight:400; width: auto">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span></span> <b class="caret"></b>
						</div>
						<ul class="nav nav-tabs" data-tabs="tabs" >
						</ul>
					</div>
				</div>
			</div>

			<div class="card-content">
				<div class="material-datatables" id="div">
					<div id="result"></div>
					<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
						<thead>
							<tr>
								<th class="text-center">Invoice Num</th>
								<th class="text-center">Email</th>
								<th class="text-center">Phone Number</th>
								<th class="text-center">User Name</th>
								<th class="text-center">Date</th>
								<th class="text-center">Billing Amount (Taka)</th>
								<th class="text-center">Amount Due (Taka)</th>
								<th class="text-center">Invoice Status</th>
								<?php if (permission("Pay","Add",$permission_string)=== 1 || permission("Inv","View",$permission_string)=== 1 || permission("Inv","Email",$permission_string)=== 1 || permission("Inv","Sms",$permission_string)=== 1 ) { ?>
									<th class="disabled-sorting text-center">Action</th>
								<?php } else {?>
									<th class="disabled-sorting"></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($all_invoice as $invoice){
								?>
								<tr>
									<td class="text-center">#<?php echo $invoice['id_invoice'] ?></td>
									<td class="text-center"><?php echo $invoice['net_user_email'] ?></td>
									<td class="text-center"><?php echo $invoice['net_user_phone'] ?></td>
									<td class="text-center"><?php echo $invoice['net_user_username'] ?></td>
									<td class="text-center"><?php echo $invoice['invoice_date'] ?></td>
									<td class="text-center"><?php echo $invoice['invoice_original_amount'] ?></td>
									<td class="text-center"><?php echo $invoice['invoice_amount'] ?></td>
									<td class="text-center"><?php echo $invoice['invoice_status'] ?></td>
									<?php if (permission("Pay","Add",$permission_string)=== 1 || permission("Inv","View",$permission_string)=== 1 || permission("Inv","Email",$permission_string)=== 1 || permission("Inv","Sms",$permission_string)=== 1) { ?>
										<td class="text-center"><?php echo $invoice['id_invoice'] ?></td>
									<?php } ?>
								</tr>
								<?php
							}
							?>
						</tbody>
						<tr>
							<th class="text-center">Invoice Num</th>
							<th class="text-center">Email</th>
							<th class="text-center">Date</th>
							<th class="text-center">Billing Amount (Taka)</th>
							<th class="text-center">Amount Due (Taka)</th>
							<th class="text-center">Invoice Status</th>
							<?php if (permission("Pay","Add",$permission_string)=== 1 || permission("Inv","View",$permission_string)=== 1 || permission("Inv","Email",$permission_string)=== 1 || permission("Inv","Sms",$permission_string)=== 1) { ?>
								<th class="disabled-sorting text-center">Action</th>
							<?php } else {?>
								<th class="disabled-sorting"></th>
							<?php } ?>
						</tr>
					</tfoot>
				</table>
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
					url: '<?php echo base_url() ?>billing/get_invoices_by_date_range',

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

/* JavaScript Media Queries */
if (matchMedia) {
	const mq = window.matchMedia("(min-width: 1100px)");
	mq.addListener(WidthChange);
	WidthChange(mq);
}

// media query change
function WidthChange(mq) {

if(mq.matches){
	$('#div').removeClass('table-responsive');
}else{
	$('#div').addClass('table-responsive');
}

}



$(document).ready(function() {


	$("#fakeLoader").hide();
	$("#container").show();


	 oTable = $('#search_grid').DataTable({


		 columns: [
		 	{ "data": "id_invoice" },
		 	{ "data": "net_user_email" },
			{ "data": "net_user_phone"},
			{ "data": "net_user_username"},
		 	{ "data": "invoice_date"},
		 	{ "data": "invoice_original_amount" },
		 	{ "data": "invoice_amount" },
			{ "data": "invoice_status" },
			{ "data": "id_invoice" }
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
				$('td:last', row).html('');
				$('td:nth-child(6)', row).html('');

				actionBtn = $('<button class="btn btn-round btn-info dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="true"></button>');
				actionBtn.append('<i class="material-icons">view_list</i>');
				actionBtn.append('<span class="caret"></span>');
				actionBtn.append('<div class="ripple-container"></div>');

				actionUl = $('<ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>');
				actionLi = $('<li></li>');
				actionLink  = $('<a  ><i style="color:orange;font-size:18px;padding:3px;"class="material-icons">create</i>Payment<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>payment/indiv_payment/"+data.id_invoice);
				actionLink1 = $('<a target="_blank"><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">receipt</i>View Invoice<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>Billingpdf/createinvoicepdf/"+data.id_invoice);
				actionLink2 = $('<a  class="send_email"><i style="color:red;font-size:18px;padding:3px;" class="material-icons">email</i>Send Email<div class="ripple-container"></div></a>').attr('id_invoice', data.id_invoice);
				actionLink3 = $('<a  class="send_sms"><i style="color:green;font-size:18px;padding:3px;" class="material-icons">sms</i>Send Sms<div class="ripple-container"></div></a>').attr('id_invoice', data.id_invoice);



				divider=$('<li class="divider"></li>');
				<?php if (permission("Pay","Add",$permission_string)=== 1 ) { ?>
					actionLi.append(actionLink);
					<?php } ?>
					actionLi.append(divider);

								<?php if (permission("Inv","Sms",$permission_string)=== 1 ) { ?>
						actionLi.append(actionLink3);
								<?php } ?>
						<?php if (permission("Inv","Email",$permission_string)=== 1 ) { ?>
					actionLi.append(actionLink2);
						<?php } ?>
					<?php if (permission("Inv","View",$permission_string)=== 1 ) { ?>
					actionLi.append(actionLink1);
					<?php } ?>

					actionUl.append(actionLi);

				actionDiv = $('<div class="dropdown pull-left"></div>');
				actionDiv.append(actionBtn);
				actionDiv.append(actionUl);
					 $('td:last', row).append(actionDiv);


					 if (data.invoice_status == 1) {
						 var tmp = $('<span style="color:green;text-align:right;">paid</span>');
						 	$('td:nth-child(6)', row).append(tmp);
					 }else if(data.invoice_status == 0){
						 var tmp = $('<span style="color:red;text-align:right;">unpaid</span>');
						 	$('td:nth-child(6)', row).append(tmp);
					 }


						// var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>ppp/view_indiv_customer/"+data.id_net_user);
						// $('td:last', row).append(tmp);
						// tmp = $('<a id="edit_btn" href="#" class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>').attr('id_net_user', data.id_net_user);
						// $('td:last', row).append(tmp);




				// $('td:last', row).html('');
				// <?php if (permission("Pay","Add",$permission_string)=== 1 ) {?>
				// 	var tmp = $('<a id="edit_btn" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>').attr('href', "<?php echo base_url(); ?>payment/indiv_payment/"+data.id_invoice);
				// 	$('td:last', row).append(tmp);
				// <?php } ?>
				// <?php if (permission("Pay","View",$permission_string)=== 1 ) {?>
				// 	var tmp = $('<a id="edit_btn" target="_blank" style="color:red;" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">receipt</i></a>').attr('href', "<?php echo base_url(); ?>Billingpdf/createinvoicepdf/"+data.id_invoice);
				// 	$('td:last', row).append(tmp);
				// <?php } ?>
				//
				// var tmp = $('<a id="edit_btn" target="_blank" style="color:red;" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">email</i></a>').attr('href', "<?php echo base_url(); ?>email/"+data.id_invoice);
				// $('td:last', row).append(tmp);

			},
			drawCallback: function( settings ) {

	    },
			filter: true,
			info: true,
			ordering: true,
			processing: true,
			retrieve: true,

		});

		$("#search_grid").on('click', '.send_email', function(){

			var id_invoice = $(this).attr('id_invoice');
			swal({
				imageUrl: "<?php echo base_url() ?>assets/img/email1.gif",
				showConfirmButton: false,
				allowOutsideClick: false
			});


			setTimeout(function() {
				$.ajax({
					type:"POST",
					dataType:"json",
					data:'id_invoice=' + id_invoice,
					url: '<?php echo base_url() ?>email/send_email/'+id_invoice,

					success:function(response) {
						if(response.status === 'success') {
							swal.close();
							swal("Sent!",response.msg, "success");
						} else {
							swal.close();
							swal("Failed!",response.msg, "error");
						}
					}
				});
			}, 100)

		});


		$("#search_grid").on('click', '.send_sms', function(){

			var id_invoice = $(this).attr('id_invoice');
			swal({
				type:'success',
				title: 'SMS',
				text: 'Sending Sms',
				showConfirmButton: false,
				allowOutsideClick: false
			});


			setTimeout(function() {
				$.ajax({
					type:"POST",
					dataType:"json",
					data:'id_invoice=' + id_invoice,
					url: '<?php echo base_url() ?>email/send_sms/'+id_invoice,

					success:function(response) {
						if(response.status === 'success') {
							swal.close();
							swal("Sent!","Sms Sent Successfully", "success");
						} else {
							swal.close();
							swal("Failed!",response.msg, "error");
						}
					}
				});
			}, 100)

		});

		$(".nav").on('click', '#today', function(){
			var currentDate = new Date()
			var month =currentDate.getMonth() + 1
			var invoice_date = currentDate.getFullYear() + "-" + month + "-" + currentDate.getDate()

			$.ajax({
			 type:"POST",
			 dataType:"json",
			 data:'invoice_date=' + invoice_date,
			 url: '<?php echo base_url() ?>billing/todays_invoice',

			 success:function(response) {
				 console.log("Response Status " + response.status);

				 if(response.status === 'passed') {
					 refresh_datatable(response.data, 'success', response.msg);
					 showNotification(2,response.msg);
				 //location.reload();
				 } else {
					 showNotification(3,response.msg);
				 }
			 }
 });
		});


		$(".nav").on('click', '#yesterday', function(){
			var currentDate = new Date()
			currentDate.setDate(currentDate.getDate()-1);
			var month =currentDate.getMonth() + 1
			var invoice_date = currentDate.getFullYear() + "-" + month + "-" + currentDate.getDate()

			$.ajax({
				type:"POST",
				dataType:"json",
				data:'invoice_date=' + invoice_date,
				url: '<?php echo base_url() ?>billing/yesterdays_invoice',

				success:function(response) {
					console.log("Response Status " + response.status);

					if(response.status === 'passed') {
						refresh_datatable(response.data, 'success', response.msg);
						showNotification(2,response.msg);
						//location.reload();
					} else {
						showNotification(3,response.msg);
					}
				}
			});
		});


		$(".nav").on('click', '#cur_month', function(){

			$.ajax({
				type:"POST",
				dataType:"json",
				url: '<?php echo base_url() ?>billing/current_month_invoice',

				success:function(response) {
					console.log("Response Status " + response.status);

					if(response.status === 'passed') {
						refresh_datatable(response.data, 'success', response.msg);
						showNotification(2,response.msg);
						//location.reload();
					} else {
						showNotification(3,response.msg);
					}
				}
			});
		});


		$(".nav").on('click', '#previous_month', function(){

			$.ajax({
				type:"POST",
				dataType:"json",
				url: '<?php echo base_url() ?>billing/previous_month_invoice',

				success:function(response) {
					console.log("Response Status " + response.status);

					if(response.status === 'passed') {
						refresh_datatable(response.data, 'success', response.msg);
						showNotification(2,response.msg);
						//location.reload();
					} else {
						showNotification(3,response.msg);
					}
				}
			});
		});
});
</script>
