<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>NIB ISP</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="<?php echo base_url(); ?>assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="<?php echo base_url(); ?>assets/css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />

</head>

<body>
	<div class="wrapper wrapper-full-page">
		<div class="full-page login-page" filter-color="black" data-image="<?=base_url(); ?>assets/img/bill.jpg">
			<!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
			<div class="content">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
							<form class="frm-login" id = "form_admin"  method="POST">
								<div class="card card-login card-hidden">
									<div class="card-header text-center" data-background-color="blue">
										<h4 class="card-title">NIB CRM</h4>
										<div class="social-line">
											<h3>ISP Login</h3>
											<div id = "message"></div>
										</div>
									</div>
									<div  class="logincard">
										<div  class="card-content logincard">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="material-icons">face</i>
												</span>
												<div class="form-group label-floating">
													<label class="control-label">E-mail/Phone</label>
													<input name='username' id='username' type="text" class="form-control">
												</div>
											</div>
											<div class="input-group">
												<span class="input-group-addon">
													<i class="material-icons">lock_outline</i>
												</span>
												<div class="form-group label-floating">
													<label class="control-label">Password</label>
													<input name='password' id='password' type="password" class="form-control">
												</div>
											</div>
										</div>
										<div class="footer text-center">
											<button type="submit" class="btn btn-info btn-simple btn-wd btn-lg">Let's go</button>
										</div>
									</div>
									<div hidden class="footer text-center msgdiv">
										<h3 class="card-title" style = "color :red;">Insufficient Balance</h4>
											<p class="card-title">Please Contact With The IIG </p>
											<button  class="btn btn-info btn-simple btn-wd btn-lg backbtn">Back</button>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</body>
<!--   Core JS Files   -->
<script src="<?php echo base_url(); ?>assets/js/core/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/core/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/core/material.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/core/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="<?php echo base_url(); ?>assets/js/core/jquery.validate.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="<?php echo base_url(); ?>assets/js/plugins/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/chartist.min.js"></script>
<!--  Plugin for the Wizard -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.bootstrap-wizard.js"></script>
<!--  Notifications Plugin    -->
<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-notify.js"></script>
<!-- DateTimePicker Plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-datetimepicker.js"></script>
<!-- Vector Map plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery-jvectormap.js"></script>
<!-- Sliders Plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/nouislider.min.js"></script>
<!--  Google Maps Plugin    -->
<script src="https://maps.googleapis.com/maps/api/js"></script>

<!-- Select Plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.select-bootstrap.js"></script>
<!--  DataTables.net Plugin    -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.datatables.js"></script>
<!-- Sweet Alert 2 plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert2.min.js"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin    -->
<script src="<?php echo base_url(); ?>assets/js/plugins/fullcalendar.min.js"></script>
<!-- TagsInput Plugin -->
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.tagsinput.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="<?php echo base_url(); ?>assets/js/material-dashboard-angular.js"></script>
<!-- Material Dashboard Init Off Canvas Menu -->
<script src="<?php echo base_url(); ?>assets/js/init/initMenu.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="<?php echo base_url(); ?>assets/js/demo.js"></script>


<script type="text/javascript">

$('.backbtn').on('click',function(event){
	event.preventDefault(); // cancel default behavior
	$('.logincard').show();
	$('.msgdiv').hide();
});

	$(document).ready(function(){



		demo.checkFullPageBackgroundImage();

		setTimeout(function() {
			// after 1000 ms we add the class animated to the login/register card
			$('.card').removeClass('card-hidden');
		}, 700)

		function remove_message(){
			setTimeout(function() {
				// after 1000 ms we add the class animated to the login/register card
				$("#message").text('');
			}, 1200)
		}

		$("#form_admin").validate({

			submitHandler: function (form) {
				var reqData = $("#form_admin").serialize();

				$.ajax({
					type:"POST",
					contentType: "application/x-www-form-urlencoded",
					dataType:"json",
					data: reqData,
					url: "/login/verify_user",

					success:function(response) {
						console.log(response);
						if(response.status === 'success') {
							window.location.href = '/'+response.msg+'/';
						} else if(response.status === 'balance') {
							$('.logincard').hide();
							$('.msgdiv').show();
						}else if(response.status === 'failed') {
							$("#message").text(response.msg);
							remove_message();
						}
					},
					error: function (result) {
						showNotification(3,"Error " + JSON.stringify(result));
					}
				});
			}
		});
	});



	function	showNotification(ind,msg){
		type = ['','info','success','warning','danger','rose','primary'];

		//color = Math.floor((Math.random() * 6) + 1);

		$.notify({
			icon: "notifications",
			message: msg

		},{
			type: type[ind],
			timer: 1000,
			placement: {
				from: 'top',
				align: 'right'
			}
		});
	}

</script>

</html>
