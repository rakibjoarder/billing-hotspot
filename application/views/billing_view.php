<?php
	require_once('header.php');
?>

<div class="row">
	<div class="col-lg-12">
			<h1 class="page-header">Bill and Invoice</h1>
	</div>
</div>


<div class="row">
	<div class="col-md-12 ">
		<div id="result"></div>
  	<form role="form" action="<?php echo base_url() ?>billing/generate_invoices_now" method="post"  id="form_admin" name="form_admin" enctype="multipart/form-data">


    <center><button type="submit" name="submit" class="btn btn-default" value="submit">Generate Bills</button></center>

  </form>

  </div><!-- end of class form_area -->
</div><!-- end of class container  -->

<script type="text/javascript">
$(document).ready(function(){
	$("#form_admin").validate({

		submitHandler: function (form) {
			var toast = new ax5.ui.toast();
			var reqData = $("#form_admin").serialize();

			toast.setConfig({
					icon: '<i class="fa fa-bell"></i>',
					containerPosition: "top-right",
					closeIcon: '<i class="fa fa-times"></i>'
			});

			toast.push({
					theme: 'info',
					msg: "Sending data..."
			}, function () {
					console.log(this);
			});

			$.ajax({
				type:"POST",
				contentType: "application/x-www-form-urlencoded",
				dataType:"json",
				data: reqData,
				url: "<?php echo base_url() ?>billing/generate_invoices_now",

				success:function(response) {
					// response = JSON.parse(response);
					if(response.status === 'success') {
						toast.push({
								theme: 'success',
								msg: response.msg
						}, function () {
								console.log(this);
						});
					} else {
						toast.push({
								theme: 'error',
								msg: response.msg
						}, function () {
								console.log(this);
						});
					}
				},
				error: function (result) {
					toast.push({
							theme: 'error',
							msg: "Error " + JSON.stringify(result)
					}, function () {
							console.log(this);
					});
				}
			});
		}
	});
	});

</script>
<?php
	require_once('footer.php');
?>
