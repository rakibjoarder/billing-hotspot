<?php
	require_once('header.php');
	if((permission("Cus","V_iew I_nvoice",$permission_string)===0
			&& permission("Cus","Vi_ew P@yment",$permission_string)===0
			&& permission("Cus","Create In_voice",$permission_string)===0
			&& permission("Cus","Add",$permission_string)===0
			&& permission("Cus","Edit",$permission_string)===0
			&& permission("Cus","Delete",$permission_string)===0
			&& permission("Cus","View",$permission_string)===0 )
			|| permission("Cus","View",$permission_string)=== -1){
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
		text-align:  center !important;
	}
</style>


<div id="fakeLoader"></div>
<div hidden id="container" class="container-fluid">
	<div  class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All Customer</h1></span>
							<ul class="nav nav-tabs menu" data-tabs="tabs">
								<?php if (permission("Cus","Add",$permission_string)!= 0) { ?>
									<li  style="float:right;" >
										<!-- <a class="btn btn-info btn-round" href="<?php echo base_url(); ?>ppp/create_customer">
											<i class="material-icons">add</i>Add&nbsp;
											<div class="ripple-container"></div>
										</a> -->

										<a class="btn btn-info btn-round" >
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
					<div class="toolbar"></div>
					<div class="material-datatables " id="div">
						<div id="result"></div>
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>ST</th>
									<th>Pppoe Id</th>
									<th>Id Mk Static</th>
									<th>Net user type</th>
									<th>Name</th>
									<th>Phone</th>
									<th>E-mail</th>
									<th>User Name</th>
									<th>Package Name</th>
									<th>Package Price</th>
									<?php if(permission("Cus","View",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1 || permission("Cus","Create In_voice",$permission_string)===1 || permission("Cus","V_iew I_nvoice",$permission_string)===1 || permission("Cus","Vi_ew P@yment",$permission_string)===1 ){ ?>
									<th class="disabled-sorting">Action</th>
									<?php }else {?>
									<th class="disabled-sorting"></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($customers as $customer_indiv):
									?>
									<tr>
										<td><?php echo $customer_indiv['is_active'] ?></td>
										<td><?php echo $customer_indiv['id_mk_pppoe'] ?></td>
										<td><?php echo $customer_indiv['id_mk_static'] ?></td>
										<td><?php echo $customer_indiv['id_net_user_type'] ?></td>
										<td><?php echo $customer_indiv['net_user_name'] ?></td>
										<td><?php echo $customer_indiv['net_user_phone'] ?></td>
										<td><?php echo $customer_indiv['net_user_email'] ?></td>
										<td><?php echo $customer_indiv['net_user_username'] ?></td>
										<td><?php echo $customer_indiv['package_name'] ?></td>
										<td><?php echo $customer_indiv['net_user_mrc_price'] ?></td>
										<?php if(permission("Cus","View",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1 || permission("Cus","Create In_voice",$permission_string)===1 || permission("Cus","V_iew I_nvoice",$permission_string)===1 || permission("Cus","Vi_ew P@yment",$permission_string)===1 ){ ?>
										<td><?php echo $customer_indiv['id_net_user'] ?></td>
										<?php } ?>
									</tr>
									<?php
									endforeach;
									?>
								</tbody>
								<tfoot>
									<tr>
										<th>ST</th>
										<th>Pppoe Id</th>
										<th>Id Mk Static</th>
										<th>Net user type</th>
										<th>Name</th>
										<th>Phone</th>
										<th>E-mail</th>
										<th>User Name</th>
										<th>Package Name</th>
										<th>Package Price</th>
										<?php if(permission("Cus","View",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1 || permission("Cus","Create In_voice",$permission_string)===1 || permission("Cus","V_iew I_nvoice",$permission_string)===1 || permission("Cus","Vi_ew P@yment",$permission_string)===1 ){ ?>
										<th class="disabled-sorting">Action</th>
										<?php }else {?>
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


	<div hidden class="msgdiv">
		<div class="row">
			<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
					<div class="card card-login card-hidden">
						<div class="card-header text-center" data-background-color="blue">
							<h4 class="card-title">NIB CRM</h4>
						</div>

						<div  class="footer text-center msgdiv">
							<h3 class="card-title" style = "color :red; padding-top :40px;">Insufficient Balance</h4>
								<p class="card-title">Please Contact With The IIG </p>
								<button  class="btn btn-info btn-simple btn-wd btn-lg backbtn">Back</button>
							</div>
					</div>
				</div>
			</div>
		</div>


<?php
	require_once('footer.php');
?>

<script type="text/javascript">

$('.backbtn').on('click',function(event){
	event.preventDefault(); // cancel default behavior
	$('#container').show();
	$('.msgdiv').hide();
});

$('ul.menu li').click(function(e){
	$.ajax({
		type:"POST",
		dataType:"json",
		url: '<?php echo base_url() ?>ppp/check_isp_balance',

		success:function(response) {
			console.log("Response Status " + response.status);

			if(response.status === 'success') {
				window.location.href = '<?php echo base_url(); ?>ppp/create_customer';
			} else {
				$('#container').hide();
				$('.msgdiv').show();
			}
		}
	});
});

showPageLoader();

  var net_user_username="r";
	var id_net_user = "";
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
	$('[data-toggle="tooltip"]').tooltip();
	 closePageLoader();

	 $('#data_grid').on('draw.dt', function () {
		 $('[data-toggle="tooltip"]').tooltip();
	 });


	oTable = $('#data_grid').DataTable({
		// data:[],
		columns: [
			{ "data": "is_active" },
			{ "data": "id_mk_pppoe" },
			{ "data": "id_mk_static" },
			{ "data": "id_net_user_type" },
			{ "data": "net_user_name" },
			{ "data": "net_user_phone" },
			{ "data": "net_user_email" },
			{ "data": "net_user_username" },
			{ "data": "package_name" },
			{ "data": "net_user_mrc_price" },
			{ "data": "id_net_user" }
		],
		columnDefs:[
			{
				"targets": [ 1 ],
				"visible": false,
				"searchable": false
			},
			{
				"targets": [ 2 ],
				"visible": false,
				"searchable": false
			},
			{
				"targets": [ 3 ],
				"visible": false,
				"searchable": false
			}
		],
		rowCallback: function (row, data) {
			$('td:last', row).html('');

			$('td:first',row).html('');

			if(data.id_net_user_type == 1 || data.id_net_user_type == 3 ){

				if(data.id_net_user_type == 1){
					$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='PPPOE Customer ' class='material-icons' style='color: #6141f4; font-size: 15px;'>person </i>");
				}else{
					$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='hotspot Customer ' class='material-icons' style='color: #f4b541; font-size: 15px;'>person </i>");

				}

				if(data.id_mk_pppoe == '-1' || data.id_mk_pppoe == '0'){
					$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Sync Disable ' class='material-icons' style='color: #ff0000; font-size: 15px;'>sync_disabled </i>");
				}else{
					$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Sync Enable' class='material-icons' style='color: #3ba550; font-size: 15px;'>sync </i>");
				}
			}else{
				$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Static Customer ' class='material-icons' style='color: #42c2f4; font-size: 15px;'>person </i>");
				if(data.id_mk_static == '-1' || data.id_mk_static == '0'){
					$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Sync Disable ' class='material-icons' style='color: #ff0000; font-size: 15px;'>sync_disabled </i>");
				}else{
					$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Sync Enable' class='material-icons' style='color: #3ba550; font-size: 15px;'>sync </i>");
				}
			}

			if(data.is_active == 0){
				$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Connection Active' class='material-icons' style='color: #3ba550; margin-right: 5px; font-size: 15px;'>check_circle </i>");
			}else{
				$('td:first', row).append("<i data-toggle='tooltip' data-placement='top' title='Connection Disable' class='material-icons' style='color: #ff0000; margin-right: 5px; font-size: 15px;'>cancel</i>");
			}

			actionBtn = $('<button class="btn btn-round btn-info dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="true"></button>');
			actionBtn.append('<i class="material-icons">view_list</i>');
			actionBtn.append('<span class="caret"></span>');
			actionBtn.append('<div class="ripple-container"></div>');

			actionUl = $('<ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>');
			actionLi = $('<li></li>');
			actionLink  = $('<a ><i style="color:orange;font-size:18px;padding:3px;"class="material-icons">create</i>Edit Customer<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>ppp/edit_customer/"+data.id_net_user);
			actionLink1 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>View Details<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>ppp/view_indiv_customer/"+data.id_net_user);
			actionLink2 = $('<a  class="delete_item"><i style="color:red;font-size:18px;padding:3px;" class="material-icons">delete</i>Delete Customer<div class="ripple-container"></div></a>').attr({id_net_user :data.id_net_user,net_user_username: data.net_user_username});
			actionLink3 = $('<a ><i style="color:blue;font-size:18px;padding:3px;" class="material-icons">note_add</i>Create Invoice<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>ppp/create_invoice/"+data.id_net_user);
			actionLink4 = $('<a ><i style="color:#9C312F;font-size:18px;padding:3px;" class="material-icons">monetization_on</i>View Invoice<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>ppp/view_indiv_invoice/"+data.id_net_user);
			actionLink5 = $('<a ><i style="color:#F19A14;font-size:18px;padding:3px;" class="material-icons">payment</i>View Payment<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>payment/indiv_user_payment_view/"+data.id_net_user);


			divider=$('<li class="divider"></li>');

			<?php if(permission("Cus","Edit",$permission_string)===1){ ?>
				actionLi.append(actionLink);
			<?php } ?>

			<?php if(permission("Cus","Delete",$permission_string)===1){ ?>
				actionLi.append(actionLink2);
			<?php } ?>

			<?php if(permission("Cus","View",$permission_string)===1){ ?>
				actionLi.append(actionLink1);
			<?php } ?>
				actionLi.append(divider);

			<?php if(permission("Cus","Create In_voice",$permission_string)===1){ ?>
					actionLi.append(actionLink3);
			<?php } ?>

			<?php if(permission("Cus","V_iew I_nvoice",$permission_string)===1  ){ ?>
				  actionLi.append(actionLink4);
			<?php } ?>

			<?php if(permission("Cus","Vi_ew P@yment",$permission_string)===1 ){ ?>
					actionLi.append(actionLink5);
			<?php } ?>

					actionUl.append(actionLi);
					actionDiv = $('<div class="dropdown pull-left"></div>');
					actionDiv.append(actionBtn);
					actionDiv.append(actionUl);

					$('td:last', row).append(actionDiv);

		},drawCallback: function( settings ) {

    },
		filter: true,
		info: true,
		ordering: true,
		processing: true,
		retrieve: true
	});


	$("#data_grid").on('click', '.delete_item', function(){
     var id_net_user = $(this).attr('id_net_user');
		 var net_user_username=$(this).attr('net_user_username');
		swal({
           type:'warning',
           title: 'Are you sure to Delete Customer?',
           text: 'You will not be able to recover the data ',
           showCancelButton: true,
           confirmButtonColor: '#049F0C',
           cancelButtonColor:'#ff0000',
           confirmButtonText: 'Yes, delete it!',
           cancelButtonText: 'No, keep it'
         }).then(function (res) {
					 $.ajax({
					 	type:"POST",
					 	dataType:"json",
					 	data:{'id_net_user' : id_net_user,'net_user_username' : net_user_username},
					 	url: '<?php echo base_url() ?>ppp/delete_net_user_now',

					 	success:function(response) {
					 		console.log("Response Status " + response.status);

					 		if(response.status === 'passed') {
					 			refresh_datatable(response.data, 'success', response.msg);
                showNotification(2,response.msg);
							//location.reload();
					 		} else {
					 			showNotification(4,response.msg);
					 		}
					 	}
					 });
    });
	});
});

</script>
