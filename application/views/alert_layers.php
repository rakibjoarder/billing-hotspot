<?php
	require_once('header.php');
	if( (permission("Ale","Edit",$permission_string)===0 && permission("Ale","Delete",$permission_string)===0 &&
	permission("Ale","View",$permission_string)===0 ) || permission("Ale","View",$permission_string)=== -1){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}
?>
<?php
$indv_id_alert='';
$indv_alert_name='';
 foreach($alert_info as $alert_indiv):
	 $indv_id_alert=$alert_indiv["id_alert"];
	 $indv_alert_name=$alert_indiv["alert_name"];
	 break;
 endforeach;

?>

<div id="spinner" class="loader"></div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">assignment</i></div>
				<div class="card-content">
					<h4 class="card-title">All Layers of <?php echo $indv_alert_name ?></h4>
					<div class="toolbar" >
						<?php if(permission("Ale","Add",$permission_string)===1){ ?>
						<a style="float:right;" href="<?php echo base_url(); ?>alert/create_alerts_layer/<?=$indv_id_alert?>" id="dt_create_alert" class="btn btn-info"><i class="fa fa-plus fa-fw"></i></a></label>
					<?php } ?>
					</div>
						<div class="col-sm-8 col-sm-offset-2">
							<?php
							foreach($alert_layers_all as $alert_indiv):
								$selected_tags="";
							?>
							<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingTwo">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $alert_indiv['id_alert_layers'] ?>" aria-expanded="false" aria-controls="collapseTwo">
													<h4 class="panel-title">
															<?php echo $alert_indiv['alert_layers_name'] ?>
															<i class="material-icons">keyboard_arrow_down</i>
													</h4>
											</a>
									</div>
									<div id="collapse<?php echo $alert_indiv['id_alert_layers'] ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">

										<div class="panel-body">

										<?php
										foreach($users as $item):
											$select_flag=0;
										?>
											<div class="checkbox">

														<?php
														foreach($selected_users as $info):
															if( ($info['id_alert_layers'] == $alert_indiv['id_alert_layers']) && ($info['id_user'] == $item['id'])){
																$select_flag=1;
																$selected_tags=$selected_tags.",".$item['name'];
															}
														endforeach; ?>
														<?php
														if($select_flag==1){ ?>
															<label>
																<?php if( (permission("Ale","View",$permission_string)===1 ) && (permission("Ale","Add",$permission_string)===0 && permission("Ale","Edit",$permission_string)===0 && permission("Ale","Delete",$permission_string)===0) ){ ?>
																	<input disabled checked class="input_check" type="checkbox" name="optionsCheckboxes" id_alert_layers="<?= $alert_indiv['id_alert_layers']?>" id_user="<?= $item['id']?>" user_name=<?php echo $item['name'] ?>> <?php echo $item['name'] ?>(<?php echo $item['email'] ?>)
																<?php }
																else{ ?>
																	<input checked class="input_check" type="checkbox" name="optionsCheckboxes" id_alert_layers="<?= $alert_indiv['id_alert_layers']?>" id_user="<?= $item['id']?>" user_name=<?php echo $item['name'] ?>> <?php echo $item['name'] ?>(<?php echo $item['email'] ?>)
																<?php } ?>
															</label>
														<?php
													 }
													 else{ ?>
															<label>
																<?php if( (permission("Ale","View",$permission_string)===1 ) && (permission("Ale","Add",$permission_string)===0 && permission("Ale","Edit",$permission_string)===0 && permission("Ale","Delete",$permission_string)===0) ){ ?>
																	<input disabled  class="input_check" type="checkbox" name="optionsCheckboxes" id_alert_layers="<?= $alert_indiv['id_alert_layers']?>" id_user="<?= $item['id']?>" user_name=<?php echo $item['name'] ?>> <?php echo $item['name'] ?>(<?php echo $item['email'] ?>)
																<?php }
																else{ ?>
																	<input  class="input_check" type="checkbox" name="optionsCheckboxes" id_alert_layers="<?= $alert_indiv['id_alert_layers']?>" id_user="<?= $item['id']?>" user_name=<?php echo $item['name'] ?>> <?php echo $item['name'] ?>(<?php echo $item['email'] ?>)
																<?php } ?>
															</label>
														<?php
													} ?>

											</div>
										<?php
										endforeach;
										?>
										<input id="tag<?php echo $alert_indiv['id_alert_layers'] ?>" type="text" disabled value="<?=$selected_tags?>" class="tagsinput" data-role="tagsinput" data-color="default" />
										<?php if(permission("Ale","Delete",$permission_string)===1){ ?>
											<a type="button" style="float:right;" id_alert_layers="<?= $alert_indiv['id_alert_layers']?>" href="#" class="btn btn-simple btn-danger btn-icon delete_item"><i class="material-icons">close</i></a>
										<?php } ?>
										<?php if(permission("Ale","Edit",$permission_string)===1){ ?>
											<a type="button" style="float:right;" href="<?php echo base_url(); ?>alert/edit_alert_layer/<?= $indv_id_alert ?>/<?= $alert_indiv['id_alert_layers']?>" class="btn btn-simple btn-warning btn-icon edit"><i class="material-icons">create</i></a>
										<?php } ?>
									</div>
									</div>
							</div>
						<?php
						endforeach;
						?>
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

	var id_alert_layers = "";
	var oTable = "";

$(document).ready(function() {
	$('#spinner').hide();

	$( ".input_check" ).click(function() {

			var check_state;
			if ($(this).is(":checked")){
				check_state=1;
			}
			else{
				check_state=0;
			}
		 var id_alert_layers = $(this).attr('id_alert_layers');
		 var id_user = $(this).attr('id_user');
		 var user_name=$(this).attr('user_name');

		 $.ajax({
			 type:"POST",
			 contentType: "application/x-www-form-urlencoded",
			 dataType:"json",
			 data: {'id_user':id_user,'id_alert_layers':id_alert_layers,'check_state':check_state},
			 url: "<?php echo base_url() ?>alert/change_alerts_layers_users",

			 success:function(response) {
				 if(response.status === 'success') {
					 if(check_state===1){
						 showNotification(2,response.msg);
						 $('#tag'+id_alert_layers).tagsinput('add', user_name);
					 }
					 else{
						 showNotification(4,response.msg);
						 $('#tag'+id_alert_layers).tagsinput('remove', user_name);
					 }



				 } else if(response.status === 'error') {
					 showNotification(3,response.msg);
				 }
			 },
			 error: function (result) {
				 showNotification(3,"Error " + JSON.stringify(result));
			 }
		});
 	});

	$( ".delete_item" ).click(function() {
     var id_alert_layers = $(this).attr('id_alert_layers');
		swal({
           type:'warning',
           title: 'Are you sure to Delete Alert Layer?',
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
						data:{'id_alert_layers' : id_alert_layers,'id_alert' : <?= $indv_id_alert?>},
					 	url: '<?php echo base_url() ?>alert/delete_alert_layer_now',

					 	success:function(response) {
					 		console.log("Response Status " + response.status);

					 		if(response.status === 'passed') {
                showNotification(2,response.msg);
								location.reload();
					 		} else {
					 			showNotification(4,response.msg);
					 		}
					 	}
					 });
    });
	});
});

</script>
