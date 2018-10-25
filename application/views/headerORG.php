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
	<title>Nib-Billing</title>
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
	<!-- fakeLoader Css-->
	<link rel="stylesheet" href="<?=base_url()?>assets/css/fakeLoader.css">
</head>

<!-- 3. Display the application -->
<body>
  <div class="wrapper">
    <div class="sidebar" data-active-color="white" data-background-color="blue" data-image="<?php echo base_url(); ?>assets/img/billing.jpg">

			<div class="logo">
				<div class="logo-normal">
					<a style="margin-left:47px;margin-top:-6px; color:red;"href="" class="simple-text">
						<p style="color:#5D5D5D;">NIB Billing</p>
					</a>
				</div>
				<div class="logo-img">

					<img  style="height:25px;width:25px;margin-left:10px;margin-top:5px;"src="<?php echo base_url(); ?>assets/img/logo.png"/>

				</div>
			</div>

      <div class="sidebar-wrapper">
				<?php
					$permission_string=$this->session->userdata('permission_string');
				?>
				<div class="user">
					<div class="photo">
						<img src="<?php echo base_url(); ?>assets/img/4.png" />
					</div>
					<div class="info">
						<a data-toggle="collapse" href="#collapseExample" class="collapsed">
							<span>
								<?php echo $this->session->userdata('username')
								?>
								<b class="caret"></b>
							</span>
						</a>
						<div class="collapse" id="collapseExample">
							<ul class="nav">
								<?php if ( permission("Use","Edit",$permission_string)===1) { ?>
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
									<li class="active">
										<a href="<?php echo base_url();?>dashboard">
											<i class="material-icons">dashboard</i>
											<p>Dashboard</p>
										</a>
									</li>
								<?php	} ?>

								<?php
								//Role
								if (permission("Rol","View",$permission_string)!= -1) { ?>
									<li >
										<a data-toggle="collapse" href="#role">
											<i class="material-icons">accessibility</i>
											<p>Role
												<b class="caret"></b>
											</p>
										</a>
										<div class="collapse" id="role">
											<ul class="nav">
												<?php if ( (permission("Rol","Permission",$permission_string)===1) || permission("Rol","View",$permission_string)===1 || permission("Rol","Edit",$permission_string)===1 || permission("Rol","Delete",$permission_string)===1) { ?>
													<li>
														<a  href="<?php echo base_url();?>role">
															<span class="sidebar-mini"></span>
															<span class="sidebar-normal">View Role</span>
														</a>
													</li>
												<?php } ?>
												<?php if (permission("Rol","Add",$permission_string)===1) { ?>
													<li>
														<a href="<?php echo base_url();?>role/create_role">
															<span class="sidebar-mini"></span>
															<span class="sidebar-normal">Add Role</span>
														</a>
													</li>
												<?php } ?>
											</ul>
										</div>
									</li>
								<?php	} ?>

								<?php
								//User
								if (permission("Use","View",$permission_string)!= -1) { ?>
									<li routerLinkActive="active">
										<a data-toggle="collapse" href="#user">
											<i class="material-icons">people_outline</i>
											<p>User
												<b class="caret"></b>
											</p>
										</a>
										<div class="collapse" id="user">
											<ul class="nav">
												<?php if (permission("Use","View",$permission_string)===1 || permission("Use","Edit",$permission_string)===1 || permission("Use","Delete",$permission_string)===1) { ?>
													<li class="active">
														<a href="<?php echo base_url();?>user/users">
															<span class="sidebar-mini"></span>
															<span class="sidebar-normal">View User</span>
														</a>
													</li>
												<?php } ?>
												<?php if (permission("Use","Add",$permission_string)===1) { ?>
													<li class="active">
														<a href="<?php echo base_url();?>user/create_users">
															<span class="sidebar-mini"></span>
															<span class="sidebar-normal">Add User</span>
														</a>
													</li>
												<?php } ?>

											</ul>
										</div>
									</li>
								<?php } ?>

								<?php
								//Package
								if (permission("Pac","View",$permission_string)!= -1) { ?>
									<li routerLinkActive="active">
										<a data-toggle="collapse" href="#formsExamples">
											<i class="material-icons">device_hub</i>
											<p>Package
												<b class="caret"></b>
											</p>
										</a>
										<div class="collapse" id="formsExamples">
											<ul class="nav">
												<?php if (permission("Pac","View",$permission_string)===1 || permission("Pac","Edit",$permission_string)===1 || permission("Pac","Delete",$permission_string)===1) { ?>

													<li class="active">
														<a  href="<?php echo base_url();?>package">
															<span class="sidebar-mini"></span>
															<span class="sidebar-normal">View Package</span>
														</a>
													</li>
												<?php } ?>
												<?php if (permission("Pac","Add",$permission_string)===1) { ?>
													<li class="active">
														<a  href="<?php echo base_url();?>package/create_package">
															<span class="sidebar-mini"></span>
															<span class="sidebar-normal">Add Package</span>
														</a>
													</li>
												<?php } ?>
											</ul>
										</div>
									</li>
								<?php } ?>

								<?php
								// Customer
								if (permission("Cus","Add",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1 || permission("Cus","View",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1 || permission("Cus","Create In_voice",$permission_string)===1 || permission("Cus","V_iew I_nvoice",$permission_string)===1 || permission("Cus","Vi_ew P@yment",$permission_string)===1 ) { ?>
									<li routerLinkActive="active">
									<a data-toggle="collapse" href="#customer">
									<i class="material-icons">people</i>
									<p>Customer
									<b class="caret"></b>
									</p>
									</a>
									<div class="collapse" id="customer">
									<ul class="nav">

									<?php if ( permission("Cus","V_iew I_nvoice",$permission_string)===1 || permission("Cus","Vi_ew P@yment",$permission_string)===1 || permission("Cus","Create In_voice",$permission_string)===1 || permission("Cus","View",$permission_string)===1 || permission("Cus","Edit",$permission_string)===1 || permission("Cus","Delete",$permission_string)===1) { ?>
										<li class="active">
											<a href="<?php echo base_url();?>ppp">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">View Customer </span>
											</a>
										</li>
									<?php } ?>

									<?php if ( permission("Cus","Add",$permission_string)===1) { ?>
										<li class="active">
											<a href="<?php echo base_url();?>ppp/create_customer">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Add Customer </span>
											</a>
										</li>
									<?php } ?>

								</ul>
							</div>
						</li>
					<?php } ?>


					<?php
					//Invoice
					if (permission("Inv","View",$permission_string)!= -1) { ?>
						<li routerLinkActive="active">
							<a data-toggle="collapse" href="#sales">
								<i class="material-icons">monetization_on</i>
								<p>Invoice
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="sales">
								<ul class="nav">

									<?php if (permission("Inv","View",$permission_string)=== 1 || permission("Inv","Email",$permission_string)=== 1 || permission("Inv","Sms",$permission_string)=== 1 ) { ?>
										<li class="active">
											<a  href="<?php echo base_url();?>billing">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">View Invoice</span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php } ?>

					<?php
					// Payment
					if (permission("Pay","View",$permission_string)!= -1) { ?>
						<li routerLinkActive="active">
							<a data-toggle="collapse" href="#payment">
								<i class="material-icons">payment</i>
								<p>Payment
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="payment">
								<ul class="nav">
									<?php if (permission("Pay","View",$permission_string)=== 1) { ?>
										<li class="active">
											<a href="<?php echo base_url();?>payment/payment_view">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">View Payment</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Pay","Add",$permission_string)=== 1) { ?>
										<li class="active">
											<a  href="<?php echo base_url();?>payment">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Add Payment</span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php } ?>

					<!-- zone -->
					<?php     if (permission("Zon","View",$permission_string)!= -1) { ?>
						<li>
							<a data-toggle="collapse" href="#zone">
								<i class="material-icons">place</i>
								<p>Zone
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="zone">
								<ul class="nav">
									<?php if (permission("Zon","View",$permission_string)=== 1 || permission("Zon","Edit",$permission_string)=== 1 || permission("Zon","Delete",$permission_string)=== 1) { ?>
										<li>
											<a  href="<?php echo base_url();?>zone">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">View Zone</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Zon","Add",$permission_string)=== 1) { ?>
										<li>
											<a href="<?php echo base_url();?>zone/create_zone">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Add Zone</span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php } ?>
					<!-- Reports -->
					<?php
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
											<a  href="<?php echo base_url();?>accounting/customer_statement">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Customer Statement</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a  href="<?php echo base_url();?>accounting/accounts_summary">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Accounts Summary</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a  href="<?php echo base_url();?>accounting/invoice_statement">
												<span class="sidebar-mini"></span>
												<span class="sidebar-normal">Invoice Statement</span>
											</a>
										</li>
									<?php } ?>
									<?php if (permission("Rep","View",$permission_string)=== 1) { ?>
										<li>
											<a  href="<?php echo base_url();?>accounting/payment_statement">
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
									<li routerLinkActive="active">
										<a  href="<?php echo base_url();?>settings/ip_pool">
											<span class="sidebar-mini"></span>
											<span class="sidebar-normal">IP Pools</span>

										</a>
									</li>
									<li routerLinkActive="active">
										<a  href="<?php echo base_url();?>settings/Isp_info">
											<span class="sidebar-mini"></span>
											<span class="sidebar-normal">Invoice Information</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
					<?php	} ?>

					<li routerLinkActive="active">
						<a href="<?php echo base_url();?>login/logout">
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

			
					</script>
