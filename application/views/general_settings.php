<?php
	require_once('header.php');
?>
<?php
	foreach($settings_config as $settings_config_indiv):
		if($settings_config_indiv['key']=='currency'){
			$currency = $settings_config_indiv['value'];
		}
	endforeach;
?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">General Setting</h4>
						<div id="result"></div>
							<form  method="post"  id="form_admin" enctype="multipart/form-data">
								<div class="form-group label-floating">
									<label class="control-label">Currency</label>
									<input type="text" class="form-control" id="currency" name="currency" required value="<?php echo $currency;  ?>" >
									</div>
								<button type="submit" name="submit" class="btn btn-info" value="submit">Submit</button>
							</form>
						</div><!-- end of class form_area -->
					</div>
				</div>
			</div>

<?php
	require_once('footer.php');
?>

<script type="text/javascript">

$(document).ready(function(){

		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>settings/edit_general_settings_now",

					success:function(response) {
						if(response.status === 'success') {
								showNotification(2,response.msg);
						}
					},
					error: function (result) {
						    showNotification(3,"Error " + JSON.stringify(result));
					}
				});
			}
		});
});

</script>
