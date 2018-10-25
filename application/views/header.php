<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if(!$this->session->userdata('user_id')){
	redirect('/login');
}
?>
<!DOCTYPE html>
<html class="perfect-scrollbar">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/img/apple-icon.png" />
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/logo.png" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>NIB ISP</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<!-- Bootstrap core CSS     -->
	<link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" />
	<!--  Material Dashboard CSS    -->
	<link href="<?=base_url()?>assets/css/material-dashboard.css" rel="stylesheet" />
	<!--  CSS for Demo Purpose, don't include it in your project     -->
	<link href="<?=base_url()?>assets/css/demo.css" rel="stylesheet" />
	<!-- Custom Css  -->
	<link href="<?=base_url()?>assets/css/customcss.css" rel="stylesheet" />
	<!--     Fonts and icons     -->
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
	<!-- daterangepicker.css -->
	<link href="<?=base_url()?>assets/css/daterangepicker.css" rel="stylesheet" />
	<!--     Date Picker      -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/flatpickr-master/dist/flatpickr.min.css" media="screen" title="no title" charset="utf-8">
	<!-- fakeLoader Css-->
	<link rel="stylesheet" href="<?=base_url()?>assets/css/fakeLoader.css">
	<!-- Morris Charts CSS -->
	<link href="<?php echo base_url(); ?>assets/vendor/morrisjs/morris.css" rel="stylesheet">
	<!-- Lightbox master CSS -->
	<link href="<?php echo base_url(); ?>assets/vendor/lightbox-master/ekko-lightbox.css" rel="stylesheet">
	<!-- flatpickr css  -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/flatpickr-master/dist/flatpickr.min.css" media="screen" title="no title" charset="utf-8">

<style media="screen">
	.nav-container > .nav > li > a {
		padding: 2px !important;
	}

	.sidebar .user, .off-canvas-sidebar .user {
    padding-bottom: 8px !important;
    margin: 5px auto 0 !important;
	}

	.nav > li > a {
    padding: 5px 15px !important;
	}

	.wrapper {
			overflow-y: hidden !important;
	}
</style>

</head>

<!-- 3. Display the application -->
<body>
  <div class="wrapper">
    <div class="sidebar" data-active-color="white" data-background-color="blue" data-image="<?php echo base_url(); ?>assets/img/billing.jpg">
			<div class="logo">
				<div class="logo-normal">
					<a style="margin-left:47px;margin-top:-6px; color:red;"href="" class="simple-text">
						<p style="color:#5D5D5D;">NIB - <?=substr($this->session->userdata('isp_name'), 0, 6).".."  ?></p>
					</a>
				</div>
				<div class="logo-img">
					<img  style="height:25px;width:25px;margin-left:10px;margin-top:5px;"src="<?php echo base_url(); ?>assets/img/logo.png"/>
				</div>
			</div>

      <div class="sidebar-wrapper">
				<?php
					$permission_string = $this->session->userdata('permission_string');
				?>
				<div class="user">
					<div class="photo">
						<img src="<?php echo base_url(); ?>assets/img/4.png" />
					</div>
					<div class="info">
						<a data-toggle="collapse" href="#collapseExample" class="collapsed">
							<span>
								<?php echo $this->session->userdata('username'); ?>
								<b class="caret"></b>
							</span>
						</a>
						<div class="collapse" id="collapseExample">
							<ul class="nav">
								<?php if (permission("Use","Edit",$permission_string)===1) { ?>
									<li>
										<a href="<?php echo base_url(); ?>user/edit_users/<?php echo $this->session->userdata('user_id') ?>">
											<span class="sidebar-mini">EP</span>
											<span class="sidebar-normal">Edit Profile</span>
										</a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>

				<div class="nav-container">
          <ul class="nav">

						<?php
						//Dashboard
						if (permission("Das","View",$permission_string)!= -1) { ?>
							<li >
								<a data-tab="dashboard" href="<?php echo base_url();?>dashboard">
									<i class="material-icons">dashboard</i>
									<p>Dashboard</p>
								</a>
							</li>
						<?php	} ?>

						<?php if(	permission("Cus","Create In_voice",$permission_string)===1 || permission("Cus","Add",$permission_string)===1 || permission("Cus","V_iew I_nvoice",$permission_string)===1 || permission("Cus","Vi_ew P@yment",$permission_string)===1  || permission("Cus","View",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1) { ?>
							<li >
								<a data-tab="ppp" href="<?php echo base_url();?>ppp">
									<i class="material-icons">people</i>
									<p>Customer</p>
								</a>
							</li>
						<?php } ?>


						<?php
							if (permission("Inv","View",$permission_string) != -1 ||
									permission("Pay","View",$permission_string)	!= -1 ||
									permission("Zon","View",$permission_string) != -1 ||
									permission("Pac","View",$permission_string) != -1 ) { ?>
									<li routerLinkActive="active">
										<a data-toggle="collapse" href="#customer">
											<i class="material-icons">attach_money</i>
											<p>Billing
												<b class="caret"></b>
											</p>
										</a>
										<div class="collapse" id="customer">
											<ul class="nav">
												<?php if (permission("Inv","View",$permission_string)=== 1 || permission("Inv","Email",$permission_string)=== 1 || permission("Inv","Sms",$permission_string)=== 1 ) { ?>
													<li >
														<a data-tab="billing" href="<?php echo base_url();?>billing">
															<span class="sidebar-mini">I</span>
															<span class="sidebar-normal">Invoice</span>
														</a>
													</li>
												<?php } ?>
												<?php if (permission("Pay","View",$permission_string)=== 1 ||permission("Pay","Add",$permission_string)=== 1) { ?>
													<li class="active">
														<a data-tab="payment" href="<?php echo base_url();?>payment/payment_view">
															<span class="sidebar-mini">P</span>
															<span class="sidebar-normal">Payment</span>
														</a>
													</li>
												<?php } ?>
												<?php if (permission("Zon","Add",$permission_string)=== 1 || permission("Zon","View",$permission_string)=== 1 || permission("Zon","Edit",$permission_string)=== 1 || permission("Zon","Delete",$permission_string)=== 1) { ?>
													<li>
														<a  data-tab="zone" href="<?php echo base_url();?>zone">
															<span class="sidebar-mini">Z</span>
															<span class="sidebar-normal">Zone</span>
														</a>
													</li>
												<?php } ?>
												<?php if (permission("Pac","Add",$permission_string)===1  || permission("Pac","View",$permission_string)===1 || permission("Pac","Edit",$permission_string)===1 || permission("Pac","Delete",$permission_string)===1) { ?>

													<li >
														<a  data-tab="package" href="<?php echo base_url();?>package">
															<span class="sidebar-mini">P</span>
															<span class="sidebar-normal">Package</span>
														</a>
													</li>
												<?php } ?>

											</ul>
										</div>
									</li>
						<?php } ?>

						<!-- Network -->
						<?php //TODO
						if (permission("Rou","View",$permission_string) != -1 || permission("Ale","View",$permission_string) != -1 || permission("Mig","View",$permission_string) != -1) { ?>
						<li routerLinkActive="active">
							<a data-toggle="collapse" href="#router">
								<i class="material-icons">router</i>
								<p>Network
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="router">
								<ul class="nav">
									<?php if (permission("Rou","View",$permission_string)===1 || permission("Rou","Edit",$permission_string)===1 || permission("Rou","Delete",$permission_string)===1) { ?>

										<li >
											<a  data-tab="router" href="<?php echo base_url();?>router/routers">
												<span class="sidebar-mini">R</span>
												<span class="sidebar-normal">Router</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Ale","View",$permission_string)=== 1 || permission("Ale","Edit",$permission_string)=== 1 || permission("Ale","Delete",$permission_string)=== 1) { ?>
										<li class="active">
											<a  data-tab="alert"  href="<?php echo base_url();?>alert">
												<span class="sidebar-mini">A</span>
												<span class="sidebar-normal">Alerts</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Mig","View",$permission_string)=== 1 ) { ?>
									<li routerLinkActive="active">
										<a data-tab="settings"  href="<?php echo base_url();?>settings/migration">
											<span class="sidebar-mini">M</span>
											<span class="sidebar-normal">Migration</span>
										</a>
									</li>
								<?php } ?>
								</ul>
							</div>
						</li>
					<?php } ?>

					<!-- Logger -->
					<?php
					if (permission("lo","View",$permission_string)!= -1 || permission("Sea","View",$permission_string) != -1) { ?>
						<li routerLinkActive="active">
							<a data-toggle="collapse" href="#log">
								<i class="material-icons">data_usage</i>
								<p>Logger
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="log">
								<ul class="nav">
									<?php
									if (permission("Sea","View",$permission_string) != -1) { ?>
										<li routerLinkActive="active" >
											<a data-tab="search" href="<?php echo base_url();?>search">
												<span class="sidebar-mini">S</span>
												<span class="sidebar-normal">Search</span>
											</a>
										</li>
									<?php	} ?>
									<?php if (permission("lo","View",$permission_string) === 1) { ?>
										<li>
											<a data-tab="log/nib" href="<?php echo base_url();?>log/nib">
												<span class="sidebar-mini">N</span>
												<span class="sidebar-normal">NIB Trace</span>
											</a>
										</li>
									<?php	} ?>
									<?php if (permission("lo","View",$permission_string) === 1) { ?>
										<li>
											<a data-tab="syslog" href="<?php echo base_url();?>syslog">
												<span class="sidebar-mini">L</span>
												<span class="sidebar-normal">Log Files</span>
											</a>
										</li>
									<?php	} ?>
								</ul>
							</div>
						</li>
					<?php	} ?>

							<?php //TODO
								//User
								if (permission("Use","View",$permission_string)!= -1 || permission("Rol","View",$permission_string)!= -1) { ?>
									<li routerLinkActive="active">
										<a data-toggle="collapse" href="#user">
											<i class="material-icons">person</i>
											<p>User
												<b class="caret"></b>
											</p>
										</a>
										<div class="collapse" id="user">
											<ul class="nav">
												<?php if (permission("Use","View",$permission_string)===1 || permission("Use","Edit",$permission_string)===1 || permission("Use","Delete",$permission_string)===1 || permission("Use","Acc_essz_one",$permission_string)===1 || permission("Use","Access_R_outer",$permission_string)===1) { ?>
													<li >
														<a data-tab="user" href="<?php echo base_url();?>user/users">
															<span class="sidebar-mini">U</span>
															<span class="sidebar-normal">User</span>
														</a>
													</li>
												<?php } ?>
												<?php if ( (permission("Rol","Permission",$permission_string)===1) || permission("Rol","View",$permission_string)===1 || permission("Rol","Edit",$permission_string)===1 || permission("Rol","Delete",$permission_string)===1) { ?>
													<li >
														<a data-tab="role" href="<?php echo base_url();?>role">
															<span class="sidebar-mini">R</span>
															<span class="sidebar-normal">Role</span>
														</a>
													</li>
												<?php } ?>

											</ul>
										</div>
									</li>
								<?php } ?>



					<!-- Reports -->
					<?php //TODO
						if (permission("Rep","View",$permission_string)!= -1) { ?>
						<li>
							<a data-toggle="collapse" href="#reports">
								<i class="material-icons">account_balance_wallet</i>
								<p>Reports
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="reports">
								<ul class="nav">
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a data-tab="accounting/customer_statement"  href="<?php echo base_url();?>accounting/customer_statement">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Customer Statement</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a data-tab="accounting/accounts_summary" href="<?php echo base_url();?>accounting/accounts_summary">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Accounts Summary</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a data-tab="accounting/invoice_statement"  href="<?php echo base_url();?>accounting/invoice_statement">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Invoice Statement</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a data-tab="accounting/payment_statement" href="<?php echo base_url();?>accounting/payment_statement">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Payment Statement</span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php	} ?>

					<?php


					//Settings
					if (permission("Set","View",$permission_string) != -1) { ?>
						<li routerLinkActive="active">
							<a data-toggle="collapse" href="#setting">
								<i class="material-icons">settings</i>
								<p>Settings
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="setting">
								<ul class="nav">
									<?php if (permission("Set","Edit",$permission_string)=== 1) { ?>
										<li routerLinkActive="active">
											<a data-tab="settings/Isp_info"  href="<?php echo base_url();?>settings/Isp_info">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">General</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Set","Edit",$permission_string)=== 1) { ?>
										<li routerLinkActive="active">
											<a data-tab="settings/message_template"  href="<?php echo base_url();?>settings/message_template">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Message</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Set","View",$permission_string)=== 1) { ?>
										<li routerLinkActive="active">
											<a data-tab="settings/version"  href="<?php echo base_url();?>settings/version">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Version</span>
											</a>
										</li>
									<?php } ?>

								</ul>
							</div>
						</li>
					<?php	} ?>


					<!-- System -->

					<?php
					//TODO
					if (permission("Sys","View",$permission_string) != -1) { ?>
					<li routerLinkActive="active">
						<a data-toggle="collapse" href="#system">
						<i class="material-icons">computer</i>
							<p>System
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse" id="system">
							<ul class="nav">
									<li >
										<a  data-tab="sysnet" href="<?php echo base_url();?>sysnet">
											<span class="sidebar-mini"></span>
											<span class="sidebar-normal">Interface</span>
										</a>
									</li>
                  <li >
										<a  data-tab="sysnet/confirm_radius_restart" href="<?php echo base_url();?>sysnet/confirm_radius_restart">
											<span class="sidebar-mini"></span>
											<span class="sidebar-normal">Radius</span>
										</a>
									</li>
									<li >
										<a  data-tab="sysnet/confirm_reboot" href="<?php echo base_url();?>sysnet/confirm_reboot">
											<span class="sidebar-mini"></span>
											<span class="sidebar-normal">Reboot</span>
										</a>
									</li>
									<li >
										<a  data-tab="sysnet/confirm_shut_down" href="<?php echo base_url();?>sysnet/confirm_shut_down">
											<span class="sidebar-mini"></span>
											<span class="sidebar-normal">Shutdown</span>
										</a>
									</li>
							</ul>
						</div>
					</li>
				<?php	} ?>

					<!-- Logout -->
					<li routerLinkActive="active">
						<a data-tab="login/logout" href="<?php echo base_url();?>login/logout">
							<i class="material-icons">exit_to_app</i>
							Logout
						</a>
					</li>
          </div>
      </div>
    </div>
    <!-- End of .sidebar -->

    <div class="main-panel">
      <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container-fluid">
            <div class="navbar-minimize">
                <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon" >
                    <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                    <i class="material-icons visible-on-sidebar-mini">view_list</i>
                </button>
            </div>
            <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href=""> <?php echo $page_heading; ?> </a>
            </div>

						<div class="collapse navbar-collapse">

				<!-- <ul class="nav navbar-nav navbar-right">

						<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<i class="material-icons">notifications</i>
										<span class="notification">5</span>
										<p class="hidden-lg hidden-md">
												Notifications
												<b class="caret"></b>
										</p>
								</a>
								<ul class="dropdown-menu">
										<li>
												<a href="#">Router Name 1111 edited into 1111 by Admin</a>
										</li>
										<li>
												<a href="#">Invoice #259 payment recieved by Admin</a>
										</li>
										<li>
												<a href="#">Invoice #249 payment recieved by Admin</a>
										</li>
										<li>
												<a href="#">Invoice #219 payment recieved by Admin</a>
										</li>
										<li>
												<a href="#">Invoice #229 payment recieved by Admin</a>
										</li>
								</ul>
						</li>

						<li class="separator hidden-lg hidden-md"></li>
				</ul> -->

		</div>

        </div>
      </nav>
      <!-- End of top navigation bar  -->
			<div class="main-content">
						<!-- All the dynamic content will be here. -->
				<div class="container-fluid">
					<script>
						function refresh_datatable(data, msgType, msg) {
							oTable.clear();
							oTable.rows.add(data).draw();
						}

						function refresh_datatable_multiple(Table,data, msgType, msg) {
							Table.clear();
							Table.rows.add(data).draw();

						}

						function	showNotification(ind,msg){
							type = ['','info','success','warning','danger','rose','primary'];
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

						function showProgressBar(){
							swal({
								imageUrl: "<?php echo base_url() ?>assets/img/pleasewaitloader.gif",
								showConfirmButton: false,
								allowOutsideClick: false
							});
						}

						function closeProgressBar(){
							swal.close();
						}

						function showPageLoader() {
							$("#fakeLoader").fakeLoader({
								spinner:"spinner4",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
								bgColor:"#EEEEEE"
							});

						}

						function closePageLoader() {
							$("#fakeLoader").hide();
							$("#container").show();
						}

					</script>
