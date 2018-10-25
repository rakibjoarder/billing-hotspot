<?php
	require_once('header.php');

	if((permission("Use","Acc_essz_one",$permission_string)===0) || permission("Use","View",$permission_string)=== -1){
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
							<span class="nav-tabs-title"><h1 class="card-title">Allow Zone <?=$user_info[0]['name']?></h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<?php if( permission("Use","View",$permission_string)===1){ ?>
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>user/users">
										<i class="material-icons">exit_to_app</i>Back
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
					<div class="material-datatables">
						<table id="data_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Access</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($zones as $zone):
								?>
									<tr>
										<td><?php echo $zone['zone_name'] ?></td>
										<td>
											<div class="togglebutton">
												<label>
													<input class="chkzone" type="checkbox" value="" data-tag='<?php echo $zone['id_zone'] ?>' <?=($zone['allowed']? 'checked': '')?>/>
												</label>
											</div></td>
									</tr>
									<?php
								endforeach;
								?>
							</tbody>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Access</th>
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

	var user_id = "<?=$user_id?>";
	var oTable = "";

	$(document).ready(function() {

		$("#fakeLoader").hide();
		$("#container").show();

		$(".chkzone").on('click', function(){
			// alert('Item ID ' + $(this).attr('data-tag') + " Value is " + $(this).is(':checked'));

			$.ajax({
			 type:"POST",
			 dataType:"json",
			 data: {'zone_id': $(this).attr('data-tag'), 'user_id': user_id},
			 url: '<?=base_url() ?>' + ($(this).is(':checked')? 'user/allow_zone_access' : 'user/remove_zone_access'),

			 success:function(response) {
				 console.log("Response Status " + response.status);

				 if(response.status === 'passed') {
					 showNotification(2, response.msg);
				 } else {
					 showNotification(4, response.msg);
				 }
			 }
			});

		});

	});

</script>
