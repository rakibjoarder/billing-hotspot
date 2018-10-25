<?php
	require_once('header.php');
	if( (permission("Use","Edit",$permission_string) === 0
	&& permission("Use","Delete",$permission_string) === 0
	&& permission("Use","View",$permission_string) === 0
	&& permission("Use","Acc_essz_one",$permission_string) === 0
	&& permission("Use","Access_R_outer",$permission_string) === 0) || permission("Use","View",$permission_string)=== -1){
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
</style>
<div id="fakeLoader"></div>
<div hidden id="container" class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All User</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<?php if( permission("Use","Add",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>user/create_users">
										<i class="material-icons">add</i>ADD&nbsp;
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
					<div class="material-datatables " id="div">
						<div id="result"></div>
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>User Name</th>
									<th>E-mail</th>
									<th>Phone</th>
									<th>Address</th>
									<th>Designation</th>
									<th>Role</th>
									<?php if(permission("Use","Edit",$permission_string)===1 || permission("Use","Delete",$permission_string)===1 || permission("Use","Acc_essz_one",$permission_string)===1 || permission("Use","Access_R_outer",$permission_string)===1){ ?>
									<th>Action</th>
									<?php }else {?>
									<th class="disabled-sorting"></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($users as $user_indiv):
									?>
									<tr>
										<td><?php echo $user_indiv['name'] ?></td>
										<td><?php echo $user_indiv['username'] ?></td>
										<td><?php echo $user_indiv['email'] ?></td>
										<td><?php echo $user_indiv['phone'] ?></td>
										<td><?php echo $user_indiv['address'] ?></td>
										<td><?php echo $user_indiv['designation'] ?></td>
										<td><?php echo $user_indiv['role_name']?></td>
										<?php if(permission("Use","Edit",$permission_string)===1 || permission("Use","Delete",$permission_string)===1 || permission("Use","Acc_essz_one",$permission_string)===1 || permission("Use","Access_R_outer",$permission_string)===1 ){ ?>
											<td><?php echo $user_indiv['id'] ?></td>
										<?php } ?>
									</tr>
									<?php
									endforeach;
									?>
								</tbody>
								<tfoot>
									<tr>
										<th>Name</th>
										<th>User Name</th>
										<th>E-mail</th>
										<th>Phone</th>
										<th>Address</th>
										<th>Role</th>
										<th>Designation</th>
										<?php if(permission("Use","Edit",$permission_string)===1 || permission("Use","Delete",$permission_string)===1 || permission("Use","Acc_essz_one",$permission_string)===1 || permission("Use","Access_R_outer",$permission_string)===1 ){ ?>
											<th>Action</th>
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



	$("#fakeLoader").fakeLoader({
		spinner:"spinner4",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
		bgColor:"#EEEEEE"
	});

	var user_id = "";
	var oTable = "";

	$(document).ready(function() {

		$("#fakeLoader").hide();
		$("#container").show();
		oTable = $('#data_grid').DataTable({
			// data:[],
			columns: [
				{ "data": "name" },
				{ "data": "username" },
				{ "data": "email" },
				{ "data": "phone" },
				{ "data": "address" },
				{ "data": "designation" },
				{ "data": "role_name" },
				{ "data": "id" }
			],
			rowCallback: function (row, data) {
				$('td:last', row).html('');


				actionBtn = $('<button class="btn btn-round btn-info dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="true"></button>');
				actionBtn.append('<i class="material-icons">view_list</i>');
				actionBtn.append('<span class="caret"></span>');
				actionBtn.append('<div class="ripple-container"></div>');

				actionUl = $('<ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>');
				actionLi = $('<li></li>');
				actionLink  = $('<a ><i style="color:orange;font-size:18px;padding:3px;"class="material-icons">create</i>Edit User<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>user/edit_users/"+data.id);
				actionLink1 = $('<a  class="delete_item"><i style="color:red;font-size:18px;padding:3px;" class="material-icons">delete</i>Delete User<div class="ripple-container"></div></a>').attr('user_id', data.id);
				actionLink2 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>Access Router<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>user/access_router/"+data.id);
				actionLink3 = $('<a ><i style="color:purple;font-size:18px;padding:3px;"class="material-icons">visibility</i>Access Zone<div class="ripple-container"></div></a>').attr('href', "<?php echo base_url(); ?>user/access_zone/"+data.id);

				<?php if(permission("Use","Edit", $permission_string)===1){ ?>
					actionLi.append(actionLink);
				<?php } ?>

				<?php if(permission("Use","Delete", $permission_string)===1){ ?>
					actionLi.append(actionLink1);
				<?php } ?>

				<?php if(permission("Use", "Access_R_outer", $permission_string)===1){ ?>
					actionLi.append(actionLink2);
				<?php } ?>

				<?php if(permission("Use", "Acc_essz_one", $permission_string)===1){ ?>
					actionLi.append(actionLink3);
				<?php } ?>

				actionUl.append(actionLi);
				actionDiv = $('<div class="dropdown"></div>');
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
			retrieve: true
		});


		$("#data_grid").on('click', '.delete_item', function(){
	     var id_user = $(this).attr('user_id');
			swal({
	           type:'warning',
	           title: 'Are you sure to Delete User?',
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
						 	data:'userid=' + id_user,
						 	url: '<?php echo base_url() ?>user/delete_user_now',

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
			console.log(' id_user  = ' + id_user );
		});
	});

</script>
