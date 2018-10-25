<?php
	require_once('header.php');
	if(permission("lo","View",$permission_string)===0 || permission("lo","View",$permission_string)=== -1){
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
padding: 15px !important;
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
						<span class="nav-tabs-title"><h1 class="card-title">Syslog</h1></span>
						<ul class="nav nav-tabs" data-tabs="tabs">

							<?php
							$syslog_path_previous=$this->session->userdata('syslog_path_previous');
							error_log("path "  .$syslog_path_previous);
							if(!empty($syslog_path_previous) && $first_page!=1  ){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url();?>syslog/open_folder?folder=<?= $syslog_path_previous ?>">
										<i class="material-icons">arrow_back</i>Back
										<div class="ripple-container"></div></a>
									</a>
								</li>
								<?php
							}
							?>

						</ul>
					</div>
				</div>
			</div>

			<div class="card-content">
				<div class="toolbar"></div>
				<div class="material-datatables table-responsive">
					<div id="result"></div>
					<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
						<thead>
							<tr>
								<th>File/Folder</th>
								<?php if($first_page==1){ ?>
									<th>Router Name</th>
									<?php
								}
								?>
								<th>Download</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for ($i=0; $i < count($dic); $i++) {
								// this is the list to hide folder or file name.
								$hide_name = Array('mysql');
								if(in_array($dic[$i], $hide_name)) {
									continue;
								}

								// here we are allowing only the file that we want to show.
								if (!is_dir($path.'/'.$dic[$i])) {
									$file_parts = pathinfo($path.'/'.$dic[$i]);
									$allow_extensions = Array('log','gz');
									if (!in_array($file_parts['extension'], $allow_extensions)){
										continue;
									}
								}
								?>
								<tr>
									<!-- FOLDER -->
									<?php	if (is_dir($path.'/'.$dic[$i]))	{ ?>
										<td> <a style="cursor:pointer;" href="<?php echo base_url();?>syslog/open_folder?folder=<?= $this->session->userdata('syslog_path') ?>/<?= $dic[$i] ?>"><?php echo $dic[$i] ?></a></td>
									<?php }	else { ?>
										<td> <a style="cursor:pointer;" href="<?php echo base_url();?>syslog/download_file?file=<?php echo $dic[$i]; ?>"  ><?php echo $dic[$i] ?></a></td>
									<?php } ?>
									<?php if($first_page==1){ ?>
										<td><?php echo $router_name[$i] ?></td>
									<?php } ?>
									<td>
										<!-- FILE -->
										<?php if (!is_dir($path.'/'.$dic[$i])) { ?>
											<a style="cursor:pointer;"  href="<?php echo base_url();?>syslog/download_file?file=<?php echo $dic[$i]; ?>" class="btn btn-primary btn-sm">
												<span class="glyphicon glyphicon-download-alt"></span>
											</a>
										<?php	}	else { echo "-"; }?>
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>

						<tfoot>
							<tr>
								<th>File/Folder</th>
								<?php if($first_page==1){ ?>
									<th>Router Name</th>
									<?php
								}
								?>
								<th>Download</th>
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

<style>
#dt_create_user
{
	display: none;
}

</style>


<!-- href="<?php echo base_url();?>syslog/open_folder?folder=<?php echo $dic[$i]; ?>" -->
<script type="text/javascript">
$("#fakeLoader").fakeLoader({
spinner:"spinner4",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
bgColor:"#EEEEEE"
});


$(document).ready(function() {
	$("#fakeLoader").hide();
	$("#container").show();
	var user_id = "";
	function show_dialog() {
    $( "#dialog-confirm" ).dialog({
    //  resizable: false,
      height: "auto",
     // width: 400,
      modal: true,
      responsive: true,
      autoOpen: true,
      buttons: {
        "Delete the user": function() {
        	$.ajax({
        		type:"POST",
        		dataType:"text",
        		data:'user_id='+user_id,
        		url: '<?php echo base_url() ?>admin/delete_user_now',

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


	// initially it should be hidden

  $('#search_grid').DataTable();

	$('<label>&nbsp;&nbsp;<a href="<?php echo base_url(); ?>admin/create_users" id="dt_create_user" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i></a></label></a></label>').appendTo('div.dataTables_filter');



	$(".delete_user").click(function(){

		var delete_elem = $(this);

		user_id = delete_elem.attr('data-id');
		if(window.console){
			console.log(' user_id  = '+user_id );
		}

		show_dialog();

	});

	});

</script>
