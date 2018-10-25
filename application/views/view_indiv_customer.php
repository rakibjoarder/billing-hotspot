<?php
	require_once('header.php');

	if(( permission("Cus","View",$permission_string)===0 ) || permission("Cus","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>

<?php

foreach($customers as $customer_indiv):
	$net_user_name=$customer_indiv['net_user_name'];
	$net_user_phone=$customer_indiv['net_user_phone'];
	$net_user_address=$customer_indiv['net_user_address'];
	$net_user_email=$customer_indiv['net_user_email'];
	$net_user_username=$customer_indiv['net_user_username'];
	$id_package=$customer_indiv['id_package'];
	$net_user_mrc_price=$customer_indiv['net_user_mrc_price'];
	$id_net_user=$customer_indiv['id_net_user'];
	$repeat_every=$customer_indiv['repeat_name'];
	$id_payment_term=$customer_indiv['id_payment_term'];
	$package_name=$customer_indiv['package_name'];
	$net_user_ip_address_block=$customer_indiv['net_user_ip_address_block'];
	$net_user_ip_address=$customer_indiv['net_user_ip_address'];
	$net_user_mac=$customer_indiv['net_user_mac'];
	$net_user_type=$customer_indiv['net_user_type'];
	$net_user_password=$customer_indiv['net_user_password'];
	$service_type=$customer_indiv['service_type'];
	$zone_name=$customer_indiv['zone_name'];
	$net_user_billing_amount=$customer_indiv['net_user_billing_amount'];
endforeach;
?>


<div id="result"></div>
<div class="container-fluid">
	<div class="col-md-6  col-sm-offset-3">
<div class="card">
		<div class=" card-testimonial">

			<div class="card-avatar">
				<a href="#">
					<img class="img" src="<?php echo base_url();?>assets/img/4.png" />">
				</a>

			</div>
				<!-- <h1  style=" margin-bottom:30px; color:purple;"> Customer Information</h1> -->
		</div>



					<div class="col-sm-12 col-sm-offset-.5">
						<div class="card">
							<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">people</i></div>
							<div class="card-content">
								<h4 class="card-title">Customer Information </h4>
								<div class="list-group">
									<a  class="list-group-item list-group-item-action">Name 						: <?php echo $net_user_name ?></a>
									<a  class="list-group-item list-group-item-action">Address 						: <?php echo $net_user_address ?></a>
									<a  class="list-group-item list-group-item-action">Phone 						: <?php echo $net_user_phone ?></a>
									<a  class="list-group-item list-group-item-action">Email 						: <?php echo $net_user_email ?></a>
									<a  class="list-group-item list-group-item-action">User Zone 	: <?php echo  $zone_name ?></a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12 col-sm-offset-.5">
						<div class="card">
							<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">public</i></div>
							<div class="card-content">
								<h4 class="card-title">Connection Information</h4>
								<div class="list-group">
									<a  class="list-group-item list-group-item-action">User Name 				: <?php echo $net_user_username ?></a>
									<a  class="list-group-item list-group-item-action">Mac Address 			: <?php echo  $net_user_mac ?></a>
									<a  class="list-group-item list-group-item-action">Ip Address 			: <?php echo  $net_user_ip_address?></a>
									<a  class="list-group-item list-group-item-action">Ip Address Block : <?php echo  $net_user_ip_address_block?></a>
									<a  class="list-group-item list-group-item-action">User Type 	: <?php echo  $net_user_type ?></a>
									<a  class="list-group-item list-group-item-action">Password 				: <?php echo $net_user_password ?></a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12 col-sm-offset-.5">
						<div class="card">
							<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">attach_money</i></div>
							<div class="card-content">
								<h4 class="card-title">Billing Information</h4>
								<a  class="list-group-item list-group-item-action">Package Name		: <?php echo $package_name  ?></a>
								<a class="list-group-item list-group-item-action">Mrc Price	: <?php echo $net_user_mrc_price ?></a>
								<a  class="list-group-item list-group-item-action">Billing Amount	: <?php echo $net_user_billing_amount ?></a>
								<a class="list-group-item list-group-item-action">Service Duration	: <?php echo $repeat_every ?></a>
								<a  class="list-group-item list-group-item-action">Service Type 	: <?php echo  $service_type ?></a>
								<div class="col-md-6 col-md-offset-5">
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>ppp">Back </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>





<?php
	require_once('footer.php');
?>
