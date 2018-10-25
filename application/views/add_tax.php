<?php
	require_once('header.php');
?>


<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="rose"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Add Tax</h4>
						<div id="result"></div>
						<form  method="post"  id="form_admin" enctype="multipart/form-data">

							<div class="form-group label-floating">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" id="tax_name" required  name="tax_name" >
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Tax Percentage</label>
								<input type="text" class="form-control" id="tax_ratio" required  name="tax_ratio" >
							</div>

							<button type="submit" name="submit" class="btn btn-rose" value="submit">Submit</button>
							<a href="<?php echo base_url();?>settings/view_tax" class="btn btn-rose" >Back</a>

						</form>

						</div><!-- end of class form_area -->
					</div><!-- end of class container  -->
				</div>
		</div>

</script>
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
					url: "<?php echo base_url() ?>settings/add_tax_now",

					success:function(response) {
						if(response.status === 'success') {
								showNotification(2,response.msg);
						} else if(response.status === 'failed') {
								showNotification(3,response.msg);
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
