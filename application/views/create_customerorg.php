<?php
	require_once('header.php');
	if(permission("Cus","Add",$permission_string)===0 || permission("Cus","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>



<div class="row">
	<div _ngcontent-c6="" class="col-sm-12 ">
		<div _ngcontent-c6="" class="card">
			<div _ngcontent-c6="" class="card-header" data-background-color="blue">
				<h3 style="color:white;" _ngcontent-c6="" class="title">Create Customer</h3>
			</div>
			<div id="result"></div>
			<input type="hidden" class="form-control" >
			<form method="post"  id="form_admin" enctype="multipart/form-data">

				<div class="col-sm-4 col-sm-offset-.5">
					<div class="card">
						<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">people</i></div>
						<div class="card-content">
							<h4 class="card-title">Customer Information</h4>
							<div class="form-group label-floating">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" id="net_user_name" required  name="net_user_name">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Address</label>
								<input type="text" class="form-control" id="net_user_address" required  name="net_user_address">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Phone Number</label>
								<input type="text" class="form-control" id="net_user_phone" required  name="net_user_phone">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="text" class="form-control" id="net_user_email" required  name="net_user_email">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Select Zone</label>
								<select  name="id_zone" id='id_zone' class="form-control" required>
									<option disabled="" selected=""></option>
									<?php foreach ($zones as $zone): ?>
										<option value="<?= $zone['id_zone']?>"><?= $zone['zone_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>


				<div class="col-sm-4 col-sm-offset-.5">
					<div class="card">

						<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">public</i></div>
						<div class="card-content">
							<h4 class="card-title">Connection Information</h4>

							<div class="form-group label-floating">
								<label class="control-label">User Name</label>
								<input type="text" class="form-control" id="net_user_username" required  name="net_user_username">
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Mac Address</label>
								<input type="text" class="form-control" id="net_user_mac"  name="net_user_mac" >
							</div>

							<div class="form-group label-floating">
								<label class="control-label">IP Address</label>
								<input type="text" class="form-control" id="net_user_ip_address"  name="net_user_ip_address" >
							</div>

							<div class="form-group label-floating">
								<label class="control-label">IP Address Block</label>
								<input type="text" class="form-control" id="net_user_ip_address_block"  name="net_user_ip_address_block" >
							</div>
							<div class="form-group label-floating">
								<label class="control-label">Connection Type</label>
								<select  name="id_net_user_type" id='id_net_user_type' class="form-control" required>
									<option disabled="" selected=""></option>
									<?php foreach ($net_user_types as $category): ?>
										<option value="<?= $category['id_net_user_type']?>"><?= $category['net_user_type'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Password</label>
								<input type="password" class="form-control" id="net_user_password" required  name="net_user_password" minLength="6" required>
							</div>

							<div class="form-group label-floating">
								<label class="control-label">Confirm Password</label>
								<input type="password" class="form-control" id="net_user_confirm_pwd" required  name="net_user_confirm_pwd"  equalTo="#net_user_password">
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-4 col-sm-offset-.5">
					<div class="card">
						<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">attach_money</i></div>
						<div class="card-content">
							<h4 class="card-title">Billing Information</h4>
							<div class="form-group label-floating">
								<label class="control-label">Package</label>
								<select  name="id_package" id='id_package' class="form-control" required>
									<option disabled="" selected=""></option>
									<?php foreach ($packages as $package): ?>
										<option  value="<?= $package['id_package']?>" price="<?= $package['package_price']?>" bandwidth="<?= $package['package_speed']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div style="margin-top:-2%;display:none;" class="form-group " id="price_id_div">
								<label class="control-label">Assigned Bandwidth</label>
								<input type="text" class="form-control" id="net_user_assigned_bandwidth" required  name="net_user_assigned_bandwidth" required>
							</div>
							<div style="margin-top:-2%;display:none;" class="form-group " id="assigned_bandwidth_id">
								<label class="control-label">MRC Price</label>
								<input type="text" class="form-control" id="net_user_mrc_price"  readonly  name="net_user_mrc_price" required>
							</div>
							<div class="radio" style="margin-top:-4px;display:none;" id="discount_div" >
								<label style="margin-left:-36px;"for="full_name">Discount :</label>
								<label style="margin-top:10px;"><input type="radio" name="discount_radio" checked value="none" id="none">none </label>
								<label style="margin-top:10px;"><input type="radio" name="discount_radio"  value="percentage" id="percentage"> percentage(%)</label>
								<label style="margin-top:10px;"><input type="radio" name="discount_radio" value="amount" id="amount"> fixed amount</label>

								<input style="margin-top:-20px;display:none;" type="text" hidden class="form-control" value=0 id="discount"   placeholder="Discount" name="discount" >


							</div>
							<div style="margin-top:-40px;display:none;" class="form-group " id="cal_price_div">
								<label class="control-label">Billing Amount</label>
								<input  type="text" class="form-control" id="discount_" value=0 required placeholder="discount" name="discount_">
							</div>
							<input  type="hidden" id="radio_flag"  placeholder="radio_flag" name="radio_flag">
							<div class="form-group label-floating">
								<label class="control-label">Service Duration</label>
								<select  name="id_repeat_every" id='id_repeat_every' class="form-control" required>
									<option disabled="" selected=""></option>
									<?php foreach ($repeat_everys as $category): ?>
										<option value="<?= $category['id_repeat_every']?>"><?= $category['repeat_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>



							<div class="form-group label-floating">
								<label class="control-label">Payment Mode</label>
								<div class="radio">
									<label style="margin-left:20px;margin-top:10px;"><input type="radio" name="optionsRadios" value="Recurring" checked="true" > Recurring </label>
									<label style="margin-left:20px;margin-top:10px;"><input type="radio" name="optionsRadios" value="One Time"  > One Time </label>
								</div>
							</div>

							<button type="submit" name="submit" class="btn btn-fill btn-info" value="submit">Submit</button>
							<?php	if(permission("Cus","View",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1){ ?>
								<a href="<?php echo base_url();?>ppp" class="btn btn-fill btn-info" >Back</a>
							<?php } ?>

						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<?php
	require_once('footer.php');
?>


<script type="text/javascript">



$('input[type=radio][name=discount_radio]').change(function() {
	if (this.value == 'percentage') {
		document.getElementById("cal_price_div").style.marginTop = "-5px";
		var a =parseFloat($("#discount").val())/100;
		var mrc_price=$('#net_user_mrc_price').val();
		var cal_price=mrc_price-mrc_price*a;
		$("#discount_").val(cal_price);
		$("#discount").show(500);
		$("#discount").prop('readonly', false);
		$("#radio_flag").val(2);

		if(!a){
			$("#discount_").val(mrc_price);
		}
	}
	else if (this.value == 'amount') {
		document.getElementById("cal_price_div").style.marginTop = "-5px";
		var a =parseFloat($("#discount").val());
		var mrc_price=$('#net_user_mrc_price').val();
		var cal_price=mrc_price-a;
		$("#discount_").val(cal_price);
		$("#radio_flag").val(3);
		$("#discount").show(500);
		$("#discount").prop('readonly', false);

		if(!a){
			$("#discount_").val(mrc_price);
		}

	}
	else if (this.value == 'none') {
		document.getElementById("cal_price_div").style.marginTop = "-40px";
		var mrc_price=$('#net_user_mrc_price').val();
		$("#discount").hide(500);
		$("#discount_").val(mrc_price);
		$("#discount").val(0);
		$("#radio_flag").val(1);
	}
});

$("#discount").bind("change paste keyup", function() {

	var a =parseFloat($("#discount").val());

	if($('input[name=discount_radio]:checked', '#form_admin').val()=='percentage')
	{
		var a =parseFloat($("#discount").val())/100;
		var mrc_price=$('#net_user_mrc_price').val();
		var cal_price=mrc_price-mrc_price*a;
		$("#discount_").val(cal_price);
		$("#radio_flag").val(2);

		if(!a){
			$("#discount_").val(mrc_price);
		}
	}
	if($('input[name=discount_radio]:checked', '#form_admin').val()=='amount')
	{
		var a =parseFloat($("#discount").val());
		var mrc_price=$('#net_user_mrc_price').val();
		var cal_price=mrc_price-a;
		$("#discount_").val(cal_price);
		$("#radio_flag").val(3);
		if(!a){
			$("#discount_").val(mrc_price);
		}
	}
	if($('input[name=discount_radio]:checked', '#form_admin').val()=='none')
	{
		$("#discount_").val(0);
		$("#radio_flag").val(1);
	}
});

$('select[name="id_package"]').on('change',function(){
	$("#price_id_div").show(500);
	$("#discount_div").show(500);
	$("#cal_price_div").show(500);

  document.getElementById("none").checked = true;
	$("#radio_flag").val(1);
$("#discount").hide(500);
	$("#assigned_bandwidth_id").show(500);
		var price=$('option:selected', this).attr("price");
	  var bandwidth=$('option:selected', this).attr("bandwidth");
		$('#net_user_mrc_price').val(price);
		$("#discount_").val(price);
			$('#net_user_assigned_bandwidth').val(bandwidth);
				document.getElementById("cal_price_div").style.marginTop = "-40px";
});
$(document).ready(function(){

		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "<?php echo base_url() ?>ppp/create_ppp_user_now",

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
