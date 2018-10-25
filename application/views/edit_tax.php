<?php
	require_once('header.php');
?>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="rose">
				<i class="material-icons">add_box</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Edit Tax</h4>
				<div id="result"></div>
  	<form action="<?php echo base_url() ?>settings/edit_tax_now" method="post"  id="form_admin" enctype="multipart/form-data">
 <?php
      foreach($taxes as $tax):
        $id_tax=$tax["id_tax"];
        $tax_name=$tax["tax_name"];
        $tax_ratio=$tax["tax_ratio"];
      endforeach;
?>
		 <input type="hidden" id="id_tax" name="id_tax" value="<?php echo $id_tax; ?>" />

		<div class="form-group">
			<label for="name">Name:</label>
			<input type="text" class="form-control" id="tax_name" required placeholder="Enter Tax Name" name="tax_name" value="<?php echo $tax_name?>" >
		</div>

		<div class="form-group">
			<label for="name">Tax Percentage:</label>
			<input type="text" class="form-control" id="tax_ratio" required placeholder="Enter Tax Percentage" name="tax_ratio" value="<?php echo $tax_ratio?>">
		</div>


  	<button type="submit" name="submit" class="btn btn-rose" value="submit">Submit</button>
    <a href="<?php echo base_url();?>settings/view_tax" class="btn btn-rose" >Back</a>
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
					url: "<?php echo base_url() ?>settings/edit_tax_now",
					success:function(response) {
						// response = JSON.parse(response);
						if(response.status === 'success') {
							showNotification(2,response.msg);
						} else {
							showNotification(3,response.msg);
						}
					},
					error: function (result) {
						  showNotification(3,response.msg);
					}
				});
			}
		});

	});


</script>
