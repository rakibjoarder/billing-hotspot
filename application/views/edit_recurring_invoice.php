<?php
	require_once('header.php');
?>
<?php
	$id_recurring_invoice='';
	$id_net_user='';
	$id_package='';
	$id_connection= '';
	$id_tax= '';
	$invoice_original_amount='';
	$invoice_amount= '';
	$id_payment_term='';
	$id_repeat_every='';
	$discount='';
	$packageprice='';
	$calculated_tax='';
	$net_user_name='';
	$discount_type='';
	$discount_percentage ='';

	foreach ($recurring_invoice as $rec_invoice):
			$id_recurring_invoice=$rec_invoice['id_recurring_invoice'];
			$id_net_user =$rec_invoice['id_net_user'];
			$net_user_name =$rec_invoice['net_user_name'];
			$id_package=$rec_invoice['id_package'];
			$id_connection   = $rec_invoice['id_connection'];
			$connectionname   = $rec_invoice['connectionname'];
			$id_tax  = $rec_invoice['id_tax'];
			$invoice_original_amount  = $rec_invoice['invoice_original_amount'];
			$invoice_amount = $rec_invoice['invoice_amount'];
			$id_payment_term       =$rec_invoice['id_payment_term'];
			$id_repeat_every            =$rec_invoice['id_repeat_every'];
			$discount=$rec_invoice['discount'];
			$discount_type=$rec_invoice['discount_type'];
			$packageprice=$rec_invoice['packageprice'];
			$calculated_tax=$rec_invoice['calculated_tax'];
			$discount_percentage=$rec_invoice['discount_percentage'];
	endforeach;
?>




<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="rose"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Edit Recurring Invoice</h4>
						<div id="result"></div>
	 						<form  method="post"  id="form_admin" enctype="multipart/form-data">

    						<input type="hidden" value="<?php echo $id_recurring_invoice; ?>" id="id_recurring_invoice" name=" id_recurring_invoice" >

									<div class="card">
										<div class="card-content">
											<center><h4 class="card-title">Connection</h4></center>
												<div class="form-group col-md-12 col-md label-floating">
													<label class="control-label">Select Customer</label>
														<select name="id_net_user" id='id_net_user' class="form-control">
															<option disabled="" selected=""></option>
															<?php  foreach ($net_user as $user):
														 	if($net_user_name== $user['net_user_name'] ){
														 	?>
														 	<option selected value="<?= $user['id_net_user']?>"><?= $user['net_user_name'] ?></option>
														 	<?php }else { ?>
														 	<option value="<?= $user['id_net_user']?>"><?= $user['net_user_name'] ?></option>
														 	<?php }
															endforeach; ?>
														</select>
												</div>

												<div class="form-group col-md-12 col-md label-floating">
													<label class="control-label">Select Connection</label>
														<select class="form-control" name="connection" id='connection'>
															<option disabled="" selected=""></option>
																<?php  foreach ($connections as $connection):
															  if($connectionname== $connection['connectionname'] ){
															  ?>
															  <option selected value="<?= $connection['id_connection']?>"><?= $connection['connectionname'] ?></option>
															  <?php }else { ?>
															  <option  value="<?= $connection['id_connection']?>"><?= $connection['connectionname'] ?></option>
															  <?php }
															 endforeach; ?>
															</select>
													</div>
											</div>
										</div>

										<div class="card">
											<div class="card-content">
												<center><h4 class="card-title">Package</h4></center>
													<div class="form-group col-md-12 col-md label-floating">
														<label class="control-label">Select Package</label>
															<select  class="form-control" name="id_package" id='id_package'>
																<option disabled="" selected=""></option>
																<?php foreach ($packages as $package):
																			if($id_package==$package['id_package'] ){
																			?>
																			<option selected value="<?= $package['id_package']?>" price="<?= $package['package_price']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
																			<?php }else { ?>
																			<option value="<?= $package['id_package']?>" price="<?= $package['package_price']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
																			<?php }
																		 endforeach; ?>
															</select>
														</div>

											      <div class="form-group col-md-12 col-md label-floating">
											        <label class="control-label">Package Price</label>
											        <input type="text" class="form-control" id="package_price" required  value=<?= $packageprice ?> name="package_price">
											      </div>
											</div>
										</div>

									   <div class="card">
											<div class="card-content">
											 <div class="form-group col-md-12 col-md label-floating">
					 							<label class="control-label">Select Tax</label>
												<select class="form-control" name="id_tax" id='id_tax' required>
														<option disabled="" selected=""></option>
														<?php foreach ($tax as $taxes):
															if($id_tax==$taxes['id_tax'] ){
															?>
															<option selected  taxid="<?= $taxes['id_tax']?>" value="<?= $taxes['tax_ratio']?>" ><?= $taxes['tax_ratio'] ?></option>
															<?php }else { ?>
															<option  taxid="<?= $taxes['id_tax']?>" value="<?= $taxes['tax_ratio']?>" ><?= $taxes['tax_ratio'] ?></option>
															<?php }
														 endforeach; ?>
													</select>
													<input type="hidden" class="form-control" id="cal_tax" value="<?=$id_tax?>" required placeholder="cal_tax" name="cal_tax">
										</div>

										<div class="form-group col-md-12 col-md label-floating">
										 <label class="control-label">Payment Terms</label>
											<select class="form-control"  name="payment_term" id='payment_term'>
												<option disabled="" selected=""></option>
												<?php foreach ($payment_term as $payment):
												if($id_payment_term==$payment['id_payment_term'] ){?>
												<option  selected value="<?= $payment['id_payment_term']?>"  ><?= $payment['days'] ?>+ days</option>
												<?php }else { ?>
												<option  value="<?= $payment['id_payment_term']?>"  ><?= $payment['days'] ?>+ days</option>
												<?php }
												endforeach; ?>
											</select>
										</div>



										<div class="form-group col-md-12 col-md label-floating">
										 <label class="control-label">Repeat Every</label>
											<select class="form-control"   name="repeat_every" id='repeat_every'>
												<?php foreach ($repeat_every as $repeat):
													if($id_repeat_every==$repeat['id_repeat_every'] ){?>
													<option selected value="<?= $repeat['id_repeat_every']?>"  ><?= $repeat['repeat_name'] ?></option>
													<?php }else { ?>
													<option  value="<?= $repeat['id_repeat_every']?>"  ><?= $repeat['repeat_name'] ?></option>
													<?php }
												 endforeach; ?>
											</select>
										</div>

										<div class="form-group col-md-12 col-md">
											<center><h4 class="card-title">Discount</h4></center></br>
											<label for="full_name">Type :</label>
												<?php if($discount_type=="percentage")
												{?>
												<input type="radio" name="discount_radio"  value="none" id="none"> none
												<input checked type="radio" name="discount_radio" checked value="percentage" id="percentage"> percentage(%)
												<input type="radio" name="discount_radio" value="amount" id="amount"> Fixed Amount<br>
												<input readonly type="text" class="form-control" id="discount" required placeholder="Discount" value="<?= $discount_percentage ?>" name="discount">

												<?php	}else if($discount_type=="amount"){
												?>
												<input type="radio" name="discount_radio"  value="none" id="none"> none
												<input type="radio" name="discount_radio" checked value="percentage" id="percentage"> percentage(%)
												<input checked type="radio" name="discount_radio" value="amount" id="amount"> Fixed Amount<br>
												<input readonly type="text" class="form-control" id="discount" required placeholder="Discount" value="<?= $discount_percentage ?>" name="discount">
												<?php
												}
												else if($discount_type=="none"){
												?>
												<input type="radio" name="discount_radio" checked value="none" id="none"> none
												<input type="radio" name="discount_radio"  value="percentage" id="percentage"> percentage(%)
												<input  type="radio" name="discount_radio" value="amount" id="amount"> fixed amount<br>
												<input type="text" readonly class="form-control" id="discount" required placeholder="Discount" value="<?= $discount_percentage ?>" name="discount">
												<?php
												}
												?>
												<input type="hidden" class="form-control" id="cal_discount" value=<?= $discount ?> required placeholder="cal_discount" name="cal_discount">
										</div>

										 <div class="form-group col-md-12 col-md">
										      <button type="submit" name="submit" class="btn btn-rose" value="submit">Submit</button>
										      <a href="<?php echo base_url();?>connection" class="btn btn-rose" >Back</a>
										</div>
						  	</form>
							</div><!-- end of class form_area -->
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
	$(document).ready(function(){
			$('#id_tax').change(function() {
				var idtax=$('option:selected', this).attr("taxid");
				 $("#cal_tax").val(idtax);

				var a =parseFloat($(this).val());
				var b =parseFloat($("#total").val());
				var c=a*(b/100);
				$("#tax_").val(c);
			});


			$("#discount,#package_price").bind("change paste keyup", function() {

				if($('input[name=discount_radio]:checked', '#form_admin').val()=='percentage')
				{
						var a =parseFloat($("#discount").val())/100;
						var b =parseFloat($("#package_price").val());
					   $("#cal_discount").val(b*a);
				}
				if($('input[name=discount_radio]:checked', '#form_admin').val()=='amount')
				{
						var a =parseFloat($("#discount").val());
					    $("#cal_discount").val(a);
				}
			});


			$('input[type=radio][name=discount_radio]').change(function() {
					if (this.value == 'percentage') {
						   $("#discount").prop('readonly', false);
							 $("#discount").attr("placeholder", "%");
							 var a =parseFloat($("#discount").val())/100;
							 var b =parseFloat($("#package_price").val());

							 $('#cal_discount').val(a*b);
					}
					else if (this.value == 'amount') {
						 $("#discount").prop('readonly', false);
						 $("#discount").attr("placeholder", "Taka");
							var a =parseFloat($("#discount").val());
					 $('#cal_discount').val(a);
					}
					else if (this.value == 'none') {
					 $("#discount").prop('readonly', true);
					 $('#cal_discount').val(0);
		       $('#discount').val(0);

					}
			});

		$('#id_net_user').click(function() {
		$("#connection").empty();
		 var id_net_user = $(this).val();
		$.ajax({
			type:"POST",
			contentType: "application/x-www-form-urlencoded",
			dataType:"json",
			url: "<?php echo base_url() ?>billing/get_individual_connection",
			data: {'id_net_user':id_net_user},
			success: function(data){
				for (i = 0; i < data.length; i++) {
					var id_connection=data[i].id_connection;
					var connectionname=data[i].connectionname;

					$("#connection").append("<option value="+id_connection+">"+connectionname+"</option>");
				}

		}});
		});
		$('select[name="id_package"]').on('change',function(){
				var price=$('option:selected', this).attr("price");
		    $('#package_price').val(price);
		});



		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
          url: "<?php echo base_url() ?>billing/edit_recurring_invoice_now",
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
