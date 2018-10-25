<?php
	require_once('header.php');
?>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="rose"><i class="material-icons">add_box</i></div>
				<div class="card-content">
					<h4 class="card-title">Create Recurring Invoice</h4>
					<div id="result"></div>
						<form action="<?php echo base_url() ?>billing/create_recurring_invoice_now" method="post"  id="form_admin" enctype="multipart/form-data">
									<div class="card">
										<div class="card-content">
											<center><h4 class="card-title">Connection</h4></center>
											<div class="form-group label-floating">
												<label class="control-label">Select Customer</label>
													<select name="id_net_user" id='id_net_user' required class="form-control">
														<option disabled="" selected=""></option>
														<?php foreach ($net_user as $user): ?>
														<option value="<?= $user['id_net_user']?>"><?= $user['net_user_name'] ?></option>
														<?php endforeach; ?>
													</select>
										 </div>
											<div style="display:none;"class="form-group label-floating" style="display:block;" id="connection_div">
												<label style="font-size:12px;">Select Connection</label>
												<select name="connection" id='selectconnection' required class="form-control">
												</select>
										</div>
						    </div>
			       </div>


						<div class="card">
							<div class="card-content">
								<center><h4 class="card-title">Package</h4></center>
									<div class="form-group label-floating">
										<label class="control-label">Select Package</label>
											<select name="id_package" id='id_package' required class="form-control">
											<option disabled="" selected=""></option>
											<?php foreach ($packages as $package): ?>
											<option  value="<?= $package['id_package']?>" price="<?= $package['package_price']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
											<?php endforeach; ?>
											</select>
									</div>

									<div style="display:none;" class="form-group label-floating" id="price_id_div">
										<label style="font-size:12px;">Pacakge Price</label>
										<input type="text" class="form-control" id="package_price" required  name="package_price">
									</div>
								</div>
						</div>

						<div class="card">
							<div class="card-content">
								<div class="form-group label-floating">
									<label class="control-label">Select Tax</label>
										<select name="id_tax" id='id_tax' required class="form-control">
										<option disabled="" selected=""></option>
										<?php foreach ($tax as $taxes): ?>
										<option  taxid="<?= $taxes['id_tax']?>" value="<?= $taxes['tax_ratio']?>" ><?= $taxes['tax_ratio'] ?> %</option>
										<?php endforeach; ?>
										</select>
										<input type="hidden" class="form-control" id="cal_tax" required placeholder="cal_tax" name="cal_tax">
								</div>


								<div class="form-group label-floating">
									<label class="control-label">Select Payment Terms</label>
										<select name="payment_term" id='payment_term' required class="form-control">
										<option disabled="" selected=""></option>
										<?php foreach ($payment_term as $payment): ?>
										<option  value="<?= $payment['id_payment_term']?>"  ><?= $payment['days'] ?>+ days</option>
										<?php endforeach; ?>
										</select>
								</div>

								<div class="form-group label-floating">
									<label class="control-label">Select Repeat Every</label>
										<select name="repeat_every" id='repeat_every'  required class="form-control">
											<option disabled="" selected=""></option>
											<?php foreach ($repeat_every as $repeat): ?>
											<option  value="<?= $repeat['id_repeat_every']?>"  ><?= $repeat['repeat_name'] ?></option>
											<?php endforeach; ?>
										</select>
								</div>


								<div class="form-group col-md-12 col-md">
										<center><h4 class="card-title">Discount</h4></center></br>
									<div class="radio">
										<label for="full_name">Type :</label>
											<label>
												<input type="radio" name="discount_radio" checked value="none" id="percentage">none
										 </label>

											<label>
												<input type="radio" name="discount_radio" value="percentage" id="percentage"> percentage(%)
											</label>

											<label>
												<input type="radio" name="discount_radio" value="amount" id="amount"> fixed amount<br>
											</label>
								</div>
										<div class="form-group label-floating">
											<label class="control-label">Discount</label>
											<input type="text" readonly value=0 class="form-control" id="discount" required  name="discount">
										</div>
										<input type="hidden" class="form-control" id="cal_discount" required placeholder="cal_discount" name="cal_discount">
								</div>

							<div class="form-group col-md-12 col-md">
								<button type="submit" name="submit" class="btn btn-fill btn-rose" value="submit" >Submit</button>
								<a href="<?php echo base_url();?>ppp" class="btn btn-fill btn-rose">Back</a>
							</div>
						</form>
					</div>
				</div><!-- end of class form_area -->
			</div><!-- end of class container  -->
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


	$('select[name="id_net_user"]').on('change',function(){
			$("#connection_div").show(500);
	});

	$('select[name="id_package"]').on('change',function(){
			$("#price_id_div").show(500);
	});

	$('select[name="id_package"]').on('change',function(){
			var price=$('option:selected', this).attr("price");
	    $('#package_price').val(price);
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
		$("#selectconnection").empty();
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
						$("#selectconnection").append("<option value="+id_connection+">"+connectionname+"</option>");
					}

			}});
	});


	$("#form_admin").validate({

		submitHandler: function (form) {
			var reqData = $("#form_admin").serialize();

			$.ajax({
				type:"POST",
				contentType: "application/x-www-form-urlencoded",
				dataType:"json",
				data: reqData,
				url: "<?php echo base_url() ?>billing/create_recurring_invoice_now",

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
