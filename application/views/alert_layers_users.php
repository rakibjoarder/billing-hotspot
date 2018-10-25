<?php
	require_once('header.php');
?>

<?php
$indv_id_alert='';
$indv_alert_name='';
 foreach($alerts_layers_info as $alert_indiv):
   $id_alert_layers=$alert_indiv["id_alert_layers"];
   $alert_layers_name=$alert_indiv["alert_layers_name"];
   break;
 endforeach;
?>

<div class="row">
	<div class="col-lg-12">
			<h1 class="page-header">All Users of <?php echo $alert_layers_name ?></h1>
	</div>
	<!-- /.col-lg-12 -->
</div>

<div id="spinner" class="loader"></div>

<div class="row">
	<div class="col-md-12">
		<div id="result"></div>
		<table id="search_grid" class="table table-striped table-bordered" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>User Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($alert_layers_users_all as $alert_indiv):
					?>
					<tr>
						<td><?php echo $alert_indiv['name'] ?></td>
						<td>
							<a href="<?php echo base_url(); ?>alert/edit_alert_layer_user/<?=$id_alert_layers ?>/<?php echo $alert_indiv['id_alert_layers_users']; ?>" class="btn btn-primary btn-sm">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>

							<a href="javascript:void(0)" class="btn btn-danger btn-sm">
								<span class="glyphicon glyphicon-remove delete_alert" user-id="<?php  echo $alert_indiv['id']?>" alert-layer-id="<?php  echo $alert_indiv['id_alert_layers']?>"></span>
							</a>
						</td>
					</tr>
					<?php
				endforeach;
				?>
			</tbody>

			<tfoot>
				<tr>
          <th>User Name</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div id="dialog-confirm" title="Empty the recycle bin?">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The user will be permanently deleted from this layer and cannot be recovered. Are you sure?</p>
	</div>
</div>

<?php
	require_once('footer.php');
?>

<script type="text/javascript">

id_alert_layers ="";
id_user="";

delete_elem="";
oTable="";
function show_dialog() {
    $( "#dialog-confirm" ).dialog({
    //  resizable: false,
      height: "auto",
     // width: 400,
      modal: true,
      responsive: true,
      autoOpen: true,
      buttons: {
        "Delete the layer": function() {
        	$.ajax({
        		type:"POST",
        		dataType:"text",
        		data:{'id_alert_layers':id_alert_layers , 'id_user':id_user},
        		url: '<?php echo base_url() ?>alert/delete_alert_layer_user_now',

        		success:function(response) {
	      			$("#result").html(response);
	    			  $( "#dialog-confirm" ).dialog( "close" );
	  					location.reload();
						}
        	});
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
  }// end of function show_dialog


	$(document).ready(function() {
		$('#spinner').hide();

    oTable= $('#search_grid').DataTable();

		//$('#search_grid').DataTable({dom : 'l<"#add">frtip'});

		$('<label>&nbsp;&nbsp;<a href="<?php echo base_url(); ?>alert/create_alerts_layers_user/<?=$id_alert_layers?>" id="dt_create_alert" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i></a></label>').appendTo('div.dataTables_filter');

		$(".delete_alert").click(function(){
			 delete_elem = $(this);
			 id_alert_layers = delete_elem.attr('alert-layer-id');
       id_user = delete_elem.attr('user-id');
			if(window.console){

				console.log(' id_alert_layers  = '+id_alert_layers );

			}

			show_dialog();

		});

	});

</script>
