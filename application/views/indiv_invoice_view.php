<?php
	require_once('header.php');
	if(( (permission("Cus","V_iew I_nvoice",$permission_string)===0 )) || permission("Cus","View",$permission_string)=== -1){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}

?>

<div id="fakeLoader"></div>
<div hidden id="container" class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All Invoices</h1></span>
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
							<div class="toolbar"><!--        Here you can write extra buttons/actions for the toolbar              --></div>
								<div class="material-datatables" id="div">
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
													<?php if (permission("Cus","S_ms",$permission_string)=== 1 || permission("Cus","ad_ P@_yment",$permission_string)=== 1 || permission("Cus","se_nd Ema_il",$permission_string)=== 1 || permission("Cus","V_iew I_nvoice",$permission_string)=== 1) { ?>
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
														<td class="text-center">
															<?php if($invoice['invoice_status']==1) { ?>
																						 <span style="color:green;text-align:right;">paid</span>
																								<?php }
																								else { ?>
																							 <span style="color:red;text-align:right;">unpaid</span>
																								<?php } ?>

														</td>
														<?php if (permission("Cus","S_ms",$permission_string)=== 1 || permission("Cus","ad_ P@_yment",$permission_string)=== 1 || permission("Cus","se_nd Ema_il",$permission_string)=== 1 || permission("Cus","V_iew I_nvoice",$permission_string)=== 1 ) { ?>
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
													<?php if (permission("Cus","S_ms",$permission_string)=== 1 || permission("Cus","ad_ P@_yment",$permission_string)=== 1 || permission("Cus","se_nd Ema_il",$permission_string)=== 1 || permission("Cus","V_iew I_nvoice",$permission_string)=== 1 ) { ?>
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
					actionBtn = $('<button class="btn btn-round btn-info dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="true"></button>');
					actionBtn.append('<i class="material-icons">view_list</i>');
					actionBtn.append('<span class="caret"></span>');
					actionBtn.append('<div class="ripple-container"></div>');

					actionUl = $('<ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>');
					actionLi = $('<li></li>');
					actionLink  = $('<a  ><i style="color:orange;font-size:18px;padding:3px;"class="material-icons">create</i>Payment<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>ppp/indiv_payment/"+data.id_invoice);
					actionLink1 = $('<a  target="_blank"><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">receipt</i>View Invoice<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>Billingpdf/createinvoicepdf/"+data.id_invoice);
					actionLink2 = $('<a  class="send_email"><i style="color:red;font-size:18px;padding:3px;" class="material-icons">email</i>Send Email<div class="ripple-container"></div></a>').attr('id_invoice', data.id_invoice);
					actionLink3 = $('<a  class="send_sms"><i style="color:green;font-size:18px;padding:3px;" class="material-icons">sms</i>Send Sms<div class="ripple-container"></div></a>').attr('id_invoice', data.id_invoice);


				  divider=$('<li class="divider"></li>');
				<?php if (permission("Cus","ad_ P@_yment",$permission_string)=== 1) { ?>
					actionLi.append(actionLink);
					<?php } ?>
					actionLi.append(divider);
					<?php if (permission("Cus","V_iew I_nvoice",$permission_string)=== 1) { ?>
					actionLi.append(actionLink1);
					<?php } ?>
					<?php if (permission("Cus","se_nd Ema_il",$permission_string)=== 1) { ?>
					actionLi.append(actionLink2);
					<?php } ?>
					<?php if (permission("Cus","S_ms",$permission_string)=== 1 ) { ?>
						actionLi.append(actionLink3);
					<?php } ?>
					actionUl.append(actionLi);

				  actionDiv = $('<div class="dropdown pull-left"></div>');
				  actionDiv.append(actionBtn);
				  actionDiv.append(actionUl);
					$('td:last', row).append(actionDiv);

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




});
</script>
