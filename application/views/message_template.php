<?php
	require_once('header.php');
	if( (permission("Set","Edit",$permission_string)===0) || permission("Set","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>



<div class="row">
	  <div class="container-fluid">
	<div class="col-sm-8 col-sm-offset-2">
				<div class="card">
						<div class="card-header card-header-tabs" data-background-color="blue">
								<div class="nav-tabs-navigation">
										<div class="nav-tabs-wrapper">
												<span class="nav-tabs-title"></span>
												<ul class="nav nav-tabs" data-tabs="tabs">
														<li class="active">
																<a href="#sms" data-toggle="tab">
																		<i class="material-icons">sms</i> Sms Template
																		<div class="ripple-container"></div>
																</a>
														</li>
														<li class="">
																<a href="#email" data-toggle="tab">
																		<i class="material-icons">email</i> Email Template
																		<div class="ripple-container"></div>
																</a>
														</li>
												</ul>
										</div>
								</div>
						</div>
						<div class="card-content">
								<div class="tab-content">
									<div class="tab-pane active" id="sms">
										<div class="col-md-12">
											<div class="card" style="background-color:#f2f2f2 !important;">
												<div class="card-content">
													<div class="timeline-heading">
														<span class="label label-info" style="font-size:14px;"> Billing Sms Template </span>
													</div>

													<p id="sms_msg" style="font-size:18px; margin-left:2%;"><?=$billing_sms_template?></p>

													<div class="pull-right">
														<button type="button" class="btn btn-round btn-info add_sms" >
															<i class="material-icons">edit</i>
														</button>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="card" >
												<div class="card-content">
													<div class="timeline-heading">
														<span class="label label-info" style="font-size:14px;"> HotSpot OTP Template </span>
													</div>
													<p id="otp" style="font-size:18px; margin-left:2%;"><?=$otp_template?></p>
													<div class="pull-right">
														<button type="button" class="btn btn-round btn-info otp" >
															<i class="material-icons">edit</i>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>

										<div class="tab-pane" id="email">
											<div class="col-md-12">
												<div class="card" >
													<div class="card-content">
														<div class="timeline-heading">
															<span class="label label-info" style="font-size:14px;">Billing Email Template </span>
														</div>

														<p id="billing_email" style="font-size:18px; margin-left:2%;"><?=$billing_email_template?></p>

														<div class="pull-right">
															<button type="button" class="btn btn-round btn-info billing_email" >
																<i class="material-icons">edit</i>
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</div><!-- end of class container  -->
</div><!-- end of class container  -->
<style>
.swal-wide{
    width:40% !important;
}

</style>

<?php
	require_once('footer.php');
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.js"></script>
<script type="text/javascript">


	$("#sms").on('click', '.add_sms', function(){
		var sms_msg = document.getElementById("sms_msg").innerHTML;
		swal({
			title: 'Sms Template',
			input: 'textarea',
			inputValue: sms_msg,
			customClass: 'swal-wide',
			showCancelButton: true,
		}).then(function(text) {
			if (text) {

				$.ajax({
				 type:"POST",
				 dataType:"json",
				 data:'message=' + text,
				 url: '<?php echo base_url() ?>settings/add_sms_template_now',

				 success:function(response) {
					 if(response.status === 'success') {
						 showNotification(2,response.msg);
						 	document.getElementById("sms_msg").innerHTML=response.data;
					 }
				 }
				});
			}
		})

});


$("#sms").on('click', '.otp', function(){
	var otp = document.getElementById("otp").innerHTML;
	swal({
		title: 'Otp Template',
		input: 'textarea',
		inputValue: otp,
		customClass: 'swal-wide',
		showCancelButton: true,
	}).then(function(text) {
		if (text) {

			$.ajax({
			 type:"POST",
			 dataType:"json",
			 data:'otp=' + text,
			 url: '<?php echo base_url() ?>settings/add_otp_template_now',

			 success:function(response) {
				 if(response.status === 'success') {
					 showNotification(2,response.msg);
						document.getElementById("otp").innerHTML=response.data;
				 }
			 }
			});
		}
	})

});


$("#email").on('click', '.billing_email', function(){
	var billing_email = document.getElementById("billing_email").innerHTML;
	swal({
		title: 'Email Template',
		input: 'textarea',
		inputValue: billing_email,
		customClass: 'swal-wide',
		showCancelButton: true,
	}).then(function(text) {
		if (text) {

			$.ajax({
			 type:"POST",
			 dataType:"json",
			 data:'billing_email=' + text,
			 url: '<?php echo base_url() ?>settings/add_billing_email_template_now',

			 success:function(response) {
				 if(response.status === 'success') {
					 showNotification(2,response.msg);
						document.getElementById("billing_email").innerHTML=response.data;
				 }
			 }
			});
		}
	})

});
</script>
