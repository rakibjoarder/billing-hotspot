<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets-md/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets-md/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>NIB CRM</title>
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
                            <form class="frm-login" action="<?php echo base_url(); ?>login/verify_user" method="POST">
                                <div class="card card-login card-hidden">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">NIB CRM</h4>
                                        <div class="social-line">
                                            <h3>Login</h3>
																						<?php echo @$login_error; ?>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">face</i>
                                            </span>
                                            <div class="form-group label-floating">
																							<label class="control-label">E-mail/Phone</label>
																							<input name='email_phone' id='email_phone' type="text" class="form-control">
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
                            </form>
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
    $().ready(function() {
			$("#form_login").validate({
					rules: {
						email_phone: {
							required: true
						},
						password: {
							required: true,
							minlength: 5
						}
					},
					messages: {
						email_phone: {
							required: "Please enter a Email/Phone Number"
						},
						password: {
							required: "Please provide a password",
							minlength: "Your password must be at least 5 characters long"
						}
					}
				});
        demo.checkFullPageBackgroundImage();

        setTimeout(function() {
            // after 1000 ms we add the class animated to the login/register card
            $('.card').removeClass('card-hidden');
        }, 700)
    });
</script>

</html>
