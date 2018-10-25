<?php
	require_once('header.php');

	if(permission("Pay","Add",$permission_string)===0 || permission("Pay","View",$permission_string)=== -1){
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
  outline: 0;
	text-align:  center;
}

</style>


<div id="spinner" class="loader"></div>

		<div class="container-fluid">


			<div  class="row">
				<div class="col-md-12 ">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">

							<ul class="nav nav-tabs" data-tabs="tabs" style="background-color:#00bcd4 !important;" >

								<form id="form_search"  action="<?php echo base_url(); ?>payment/search_invoice_now"  method="POST" class="form-horizontal" role="form">

									<div class="col-sm-3 ">
										<input class="form-control" id="id_invoice" name="id_invoice" type="text" placeholder="Invoice ID" style="background-color:#FFFFFF !important; padding:4px;"/>
									</div>

									<div class="col-sm-3 ">
										<input class="form-control" id="net_user_email" name="net_user_email" type="email" placeholder="Customer Email" style="background-color:#FFFFFF !important; padding:4px;" />
									</div>

									<div class="col-sm-3 ">
										<input class="form-control" id="phone" name="phone" type="text" placeholder="Customer Phone"  style="background-color:#FFFFFF !important; padding:4px;"/>
									</div>

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


		<div hidden id= "div" class="row" style="margin-top:30px;">
			<div class="col-md-12 ">
				<div class="card">
					<div class="card-header card-header-icon" data-background-color="blue">
						<i class="material-icons">assignment</i>
					</div>
					<div class="card-content">
						<h4 class="card-title">Invoices</h4>
						<div class="toolbar">
							<!--        Here you can write extra buttons/actions for the toolbar              -->
						</div>
						<div class="material-datatables table-responsive">
							<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
								<thead>
									<tr>
										<th>Invoice Num</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Date</th>
										<th>Amount </th>
										<th>Action</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Invoice Num</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Date</th>
										<th>Amount </th>
										<th>Action</th>
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

	$(document).ready(function() {


		$('#spinner').hide();



		oTable = $('#search_grid').DataTable({

			columns: [
 			 { "data": "id_invoice" },
 			 { "data": "net_user_email" },
 			 { "data": "net_user_phone" },
 			 { "data": "invoice_date" },
 			 { "data": "invoice_amount" },
 			 { "data": "id_invoice" }
 		 ],
 			rowCallback: function (row, data) {
				$('td:last', row).html('');

			<?php if( (permission("Pay","Add",$permission_string)===1 )){ ?>
				var tmp = $('<a id="edit_btn" class="btn btn-simple btn-danger btn-icon edit"><i class="material-icons">note_add</i></a>').attr('href', "<?php echo base_url(); ?>payment/indiv_payment/"+data.id_invoice);
				$('td:last', row).append(tmp);
			<?php } ?>
 			},
			filter: true,
		 info: true,
		 ordering: true,
		 processing: true,
		 retrieve: true
 		});



		$("#form_search").validate({

			submitHandler: function (form) {
				var reqData = $("#form_search").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>payment/search_invoice_now",

					success:function(response) {

						if(response.status === 'passed') {
						 refresh_datatable(response.data, 'success', response.msg);
						 $('#div').show(500);
						} else if(response.status === 'failed') {
							showNotification(3,response.msg);
							$('#div').hide(500);
						}
					},
					error: function (result) {
							 showNotification(4,"Error " + JSON.stringify(result));

					}
				});
			}
		});


		// $('#form_search').submit(function(e) {
		// 	e.preventDefault();
		// 	$('#spinner').show();
		// 	oTable.clear().draw();
		//
		// 	$.ajax({
		// 		type: "POST",
		// 		url: $(this).attr('action'),
		// 		data: $(this).serialize(),
		// 		dataType: "json",
		// 		success: function(data, text) {
		// 			$('#spinner').hide();
		// 			console.log("ajax success response found.");
	  //       oTable.rows.add(data).draw();
		// 		},
		// 		error: function(request, status, error) {
		// 			$('#spinner').hide();
		// 		}
		// 	});
		//
		// });

	});

</script>
