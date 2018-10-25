<?php
	require_once('header.php');

	// if(permission("Per","View",$permission_string)===0 || permission("Per","Add",$permission_string)===0 || permission("Per","View",$permission_string)=== -1){
	// 	echo "<script>
	// 	alert('You do not have permission to access this page. Contact your admin to get access !');
	// 	window.location.href='/login/logout';
	// 	</script>";
	// }
?>



<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue">
				<i class="material-icons">assignment</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Permission</h4>
				<div class="panel panel-default" id="div_module">
				</div>
			</div>
		</div>
	</div>
</div>




<?php
require_once('footer.php');
?>

<script type="text/javascript">

$(document).ready(function(){

		var id=<?= $id_role ?>;

		$.ajax({
			type:"POST",
			contentType: "application/x-www-form-urlencoded",
			dataType:"json",
			data: {'id_role':id},
			url: "<?php echo base_url() ?>role/get_ind_module_operations",

			success:function(response) {
				var id_inserted=[];
				var selected_operations=[];
				var selected_modules=[];

				var div_change;
				$('#div_module').fadeOut();
				//Get Already selected operations
				for(var j=0;j<response['indv_role_operations'].length;j++){
					selected_operations.push(response['indv_role_operations'][j].id_operation);
					selected_modules.push(response['indv_role_operations'][j].id_module);
				}
				console.log("selected_modules:::"+selected_modules);
				console.log("selected_operations:::"+selected_operations);

				div_change ='<div class="card">';
				div_change ='<div class="card-content">';
				div_change ='<div class="panel panel-default" id="div_module">';
				for(var i=0;i<response['all_modules'].length;i++){
					div_change=div_change+'<div class="panel-heading" role="tab" id="headingThree">';
					div_change=div_change+'<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#'+response['all_modules'][i].module_code+'" aria-expanded="false" aria-controls="'+response['all_modules'][i].module_name+'">';
					div_change=div_change+'<h4 class="panel-title">';
					div_change=div_change+response['all_modules'][i].module_name;
					div_change=div_change+'<i class="material-icons">keyboard_arrow_down</i>';
					div_change=div_change+'</h4>';
					div_change=div_change+'</a>';
					div_change=div_change+'</div>';
						div_change=div_change+'<div id="'+response['all_modules'][i].module_code+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">';
              console.log("MODULE:"+response['all_modules'][i].module_name);
							div_change=div_change+'<div class="panel-body">';
								for(var j=0;j<response['all_operations'].length;j++){
                  if(response['all_operations'][j].id_module == response['all_modules'][i].id_module){
                    var flag=0;
                    //checkbox
                    div_change=div_change+'<div class="checkbox">';
                    div_change=div_change+'<label>';
                    for(var k=0;k<response['indv_role_operations'].length;k++){
                      if( (response['indv_role_operations'][k].id_module == response['all_modules'][i].id_module ) && (response['indv_role_operations'][k].id_operation == response['all_operations'][j].id_operation )){
                          div_change=div_change+'<input type="checkbox" checked name="optionsCheckboxes" id="sub'+response['all_operations'][j].operation_name_show+'"id_operation="'+response['all_operations'][j].id_operation+'"id_module="'+response['all_modules'][i].id_module+'">';
                          flag=1;
                          break;
                      }
                    }
                    if(flag!=1){
                      div_change=div_change+'<input type="checkbox" name="optionsCheckboxes" id="sub'+response['all_operations'][j].operation_name_show+'"id_operation="'+response['all_operations'][j].id_operation+'"id_module="'+response['all_modules'][i].id_module+'">';
                    }
                    console.log("Ope:"+response['all_operations'][j].operation_name_show);


                    div_change=div_change+response['all_operations'][j].operation_name_show;
                    div_change=div_change+'</label>';
                    div_change=div_change+'</div>';
                    //checkbox
                  }

								}
								div_change=div_change+'</div>';
							div_change=div_change+'</div>';

				}
				div_change=div_change+'</div>';
				div_change=div_change+'</div>';
				div_change=div_change+'</div>';
				document.getElementById('div_module').innerHTML  = div_change;
				$('#div_module').fadeIn();

			},
			error: function (result) {
				showNotification(3,"Error " + JSON.stringify(result));
			}
			});


			$(document).on("click", "input[id^='sub']", function(){
				var check_state;
				if ($(this).is(":checked")){
					check_state=1;
				}
				else{
					check_state=0;
				}
				var id_operation=$(this).attr('id_operation');
				var id_module=$(this).attr('id_module');
				var id_role=<?= $id_role ?>;

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: {'id_role':id_role,'id_operation':id_operation,'id_module':id_module,'check_state':check_state},
					url: "<?php echo base_url() ?>role/add_permission_now",

					success:function(response) {
						if(response.status === 'success') {
							if(check_state===1){
								showNotification(2,response.msg);
							}
							else{
								showNotification(4,response.msg);
							}

						} else if(response.status === 'failed') {
							showNotification(3,response.msg);
						}
					},
					error: function (result) {
						showNotification(3,"Error " + JSON.stringify(result));
					}
				});
			});
		});



</script>
