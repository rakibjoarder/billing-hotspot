<?php
	require_once('header.php');
?>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="rose"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Add Customer</h4>
						<div id="result"></div>
							<form  method="post"  id="form_admin" enctype="multipart/form-data">
									<div class="form-group label-floating">
										<label class="control-label">Name</label>
										<input type="text" class="form-control" id="net_user_name" required  name="net_user_name">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Email</label>
										<input type="text" class="form-control" id="email" required  name="email">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Phone Number</label>
										<input type="text" class="form-control" id="phone" required  name="phone">
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Customer Category</label>
										<select  name="id_net_user_category" id='id_net_user_category' class="form-control">
												<option disabled="" selected=""></option>
												<?php foreach ($net_user_category as $category): ?>
												<option value="<?= $category['id_net_user_category']?>"><?= $category['category_name'] ?></option>
												<?php endforeach; ?>
										</select>
									</div>

									<div class="form-group label-floating">
										<label class="control-label">Net User Address</label>
										<input type="text" class="form-control" id="net_user_addr" required  name="net_user_addr">
									</div>

									<div class="checkbox">
										<label><input type="checkbox" id="net_user_status" name="net_user_status" > Status(Active or Inactive)</label>
									</div>

									<button type="submit" name="submit" class="btn btn-fill btn-rose" value="submit">Submit</button>
									<a href="<?php echo base_url();?>ppp" class="btn btn-fill btn-rose" >Back</a>
							</form>
				</div>
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
					showProgressBar();
				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>ppp/create_ppp_user_now",

					success:function(response) {
						if(response.status === 'success') {
								showNotification(2,response.msg);
									closeProgressBar();
						} else if(response.status === 'failed') {
								showNotification(3,response.msg);
									closeProgressBar();
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
