<?php
require_once('header.php');
if(permission("Pay","Add",$permission_string)===0  || permission("Pay","View",$permission_string)===-1){
echo "<script>
alert('You do not have permission to access this page. Contact your admin to get access !');
window.location.href='/login/logout';
</script>";
}
?>

<?php
foreach($invoice as $indiv_invoice):
	$id_invoice=$indiv_invoice["id_invoice"];
	$invoice_original_amount=$indiv_invoice["invoice_original_amount"];
	$invoice_amount=$indiv_invoice["invoice_amount"];
	$invoice_date=$indiv_invoice["invoice_date"];
	$invoice_status=$indiv_invoice["invoice_status"];
  $email=$indiv_invoice["net_user_email"];
	$id_net_user=$indiv_invoice["id_net_user"];
endforeach;
?>
<?php if($invoice_amount >0){?>
	<div class="row">
		<div _ngcontent-c6="" class="col-sm-6 col-sm-offset-3">
			<div _ngcontent-c6="" class="card">
				<div _ngcontent-c6="" class="card-header" data-background-color="blue">
					<h3 style="color:white;"_ngcontent-c6="" class="title">Payment</h3>
					<p _ngcontent-c6="" class="category"><h1 class="card-title">Invoice #<?php echo $id_invoice ?></p>
					</div>
					<div _ngcontent-c6="" class="card-content table-responsive">

						<form method="post"  id="form_admin" name="form_admin" enctype="multipart/form-data">
							<div class="list-group">
								<a  class="list-group-item list-group-item-action">Email : <?php echo $email ?></a>
                <a  class="list-group-item list-group-item-action">Invoice Date : <?php echo $invoice_date ?></a>
								<a  class="list-group-item list-group-item-action">Original Amount : <?php echo $invoice_original_amount ?></a>
								<a  class="list-group-item list-group-item-action">Due Amount : <?php echo $invoice_amount ?></a>

							</div>
							<div class="form-group">
								<label >Amount Paying:</label>
								<input type="text"  class="form-control" id="pay_amount" required  name="pay_amount" value="<?=$invoice_amount?>">
							</div>
							<input type="hidden" value="<?php echo $id_invoice;     ?>" id="id_invoice" name="id_invoice" >
							<input type="hidden" value="<?php echo $id_net_user;    ?>" id="id_net_user" name="id_net_user" >
							<input type="hidden" value="<?php echo $invoice_amount; ?>" id="due_amount" name="due_amount" >
							<a onclick="javascript:void(0)" type="submit" name="submit" id="submit" class="btn btn-info  delete_user col-md-5 " value="submit" >Payment</a>
							<!-- <a style="float:right;"onclick="javascript:void(0)"  class="btn btn-info  delete_user col-md-5 " href="<?php echo base_url(); ?>payment/indiv_payment_view/<?=$id_net_user?>">Back</a> -->
							<?php if( permission("Pay","View",$permission_string)===1 ){ ?>
							<a style="float:right;"onclick="javascript:void(0)"  class="btn btn-info  delete_user col-md-5 " href="<?php echo base_url(); ?>payment/payment_view">Back</a>
						<?php } ?>
						</form>

					</div><!-- end of class form_area -->
				</div>
			</div>
		</div>
	<?php }else{ ?>
		<div class="row">
			<div id="succes_form"  class="col-md-6 col-md-offset-3">
				<div  class="card card-pricing card-raised">
					<div class="content">
						<div _ngcontent-c6="" class="card-header" data-background-color="blue">
							<p _ngcontent-c6="" class="category"><h1 class="card-title">Invoice # <?php echo $id_invoice ?></p>
							</div>
							<h1 >Payment Complete !!!</h1>
							<div class="icon icon-danger">
								<i class="material-icons">done</i>
							</div>
							<h3 class="card-title " id="visitor_key"></h3>
							<?php if( permission("Inv","View",$permission_string)===1 ){ ?>
							<a href="<?php echo base_url(); ?>billing" class="btn btn-info btn-round">View Invoice</a>
<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php
	require_once('footer.php');
?>
<script type="text/javascript">

$(document).ready(function() {
	id_invoice ="";

	$("#submit").click(function(){
		swal({
					 type:'warning',
					 title: '',
					 text: 'The invocie will be added to paid list and cannot be recovered. Are you sure?',
					 showCancelButton: true,
					 confirmButtonColor: '#049F0C',
					 cancelButtonColor:'#ff0000',
					 confirmButtonText: 'Yes',
					 cancelButtonText: 'No'
				 }).then(function (res) {
					 $.ajax({
						 type:"POST",
						 dataType:"json",
						 data: $("#form_admin").serialize(),
						 url: '<?php echo base_url() ?>payment/add_payment_now',
						 success:function(response) {
							 if(response.status === 'success') {
								 swal({
												type:'success',
												title: 'Invoice #<?php echo $id_invoice ?>',
												text: response.msg,
												confirmButtonColor: '#049F0C',
												confirmButtonText: 'Okay',
											}).then(function (res) {
												location.reload();
											});
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
});



</script>
