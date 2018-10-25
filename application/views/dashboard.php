<?php
	require_once('header.php');

	if(permission("Das","View",$permission_string)=== 0|| permission("Das","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>


<style >
.ct-label {
  fill: rgb(255, 255, 255);
  color: rgba(0, 0, 0, 0.4);
  font-size: 1.6rem;
  line-height: 1;
}
.category{
	color: #434143 !important;
}
</style>

<?php
//current_month_pie
$current_invoice_pie = str_replace(",", "", $total_invoice_amount_current_month);
$current_due_pie = str_replace(",", "", $total_due_current_month);
$total = $current_invoice_pie+$current_due_pie;
if($total != 0){
	$current_invoice_pie_per = number_format(($current_invoice_pie/$total)*100,2);
	$current_due_pie_per = number_format(($current_due_pie/$total)*100,2);
}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- MetisMenu CSS -->
<link href="<?php echo base_url(); ?>assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet"></link>

<script src="<?php echo base_url(); ?>assets/lib/jquery-1.10.2.min.js" charset="utf-8"></script>



<div class="row">
	  <div class="container-fluid">
	<div class="col-md-12">
				<div style ="background-color: #EEEEEE !important;"class="card">
						<div class="card-header card-header-tabs" data-background-color="blue">
								<div class="nav-tabs-navigation">
										<div class="nav-tabs-wrapper">
												<span class="nav-tabs-title"></span>
												<ul class="nav nav-tabs" data-tabs="tabs">
														<li class="active">
																<a href="#sms" data-toggle="tab">
																	<i class="material-icons">dashboard</i> Logger
																		<div class="ripple-container"></div>
																</a>
														</li>
														<li class="">
																<a id ="billingTab" href="#email" data-toggle="tab" >
																	<i class="material-icons">dashboard</i> Billing
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

										<div class="row">

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="purple">
															<i class="material-icons">router</i>
														</div>
														<div class="card-content">
															<p class="category">Router</p>
															<h3 class="card-title"><?php echo $router ?></h3>
														</div>
														<div class="card-footer">
															<div class="stats">
																<i class="material-icons text-danger">visibility</i>
															<a href="<?php echo base_url();?>router/routers">View Routers</a>
															</div>
														</div>
													</div>
												</div>




											<div class="col-lg-3 col-md-6 col-sm-6">
												<div class="card card-stats">
													<div class="card-header card-header-icon" data-background-color="green">
														<i class="material-icons">people</i>
													</div>
													<div class="card-content">
														<p class="category">Customer</p>
														<h3 class="card-title"><?php echo $ppoe ?></h3>
													</div>
													<div class="card-footer">
														<div class="stats">
															<i class="material-icons text-danger">visibility</i>
															<a href="<?php echo base_url(); ?>ppp">View Customers</a>
														</div>
													</div>
												</div>
											</div>

											<div class="col-lg-3 col-md-6 col-sm-6">
												<div class="card card-stats">
													<div class="card-header card-header-icon" data-background-color="blue">
														<i class="material-icons">add</i>
													</div>
													<div class="card-content">
														<p class="category">Entries</p>
														<h3 class="card-title"><?php echo $total_rows_inserted ?></h3>
													</div>
													<div class="card-footer">
														<div class="stats">
															<i class="material-icons text-danger">visibility</i>
															<a href="<?php echo base_url(); ?>search">View Log</a>
														</div>
													</div>
												</div>
											</div>

											<div class="col-lg-3 col-md-6 col-sm-6">
												<div class="card card-stats">
													<div class="card-header card-header-icon" data-background-color="red">
														<i class="material-icons">error</i>
													</div>
													<div class="card-content">
														<p class="category">Error !</p>
														<h3 class="card-title"><?php echo $error ?></h3>
													</div>
													<div class="card-footer">
														<div class="stats">
															<i class="material-icons text-danger">visibility</i>
															<a href="<?php echo base_url();?>dashboard/view_notifications">View Errors</a>
														</div>
													</div>
												</div>
											</div>


										</div>


											<div class="row">
													<div class="col-lg-12 col-sm-12">
														<div class="card">
															<div class="card-header card-header-icon" data-background-color="blue">
																<i class="material-icons">computer</i>
															</div>
															<div class="card-content">
																<h4 class="card-title">System Status</h4>

															<div class="netdata-chart-row">
																<div class="netdata-container-easypiechart" style="margin-right: 10px; width: 9%;" data-netdata="system.swap" data-dimensions="used" data-append-options="percentage" data-chart-library="easypiechart" data-title="Used Swap" data-units="%" data-easypiechart-max-value="100" data-width="9%" data-before="0" data-after="-420" data-points="420" data-colors="#DD4400" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 10.5px; padding-top: 44.75px;">
																		<i class="fa fa-refresh"></i> nib
																	</div>
																	<div id="easypiechart-b88dbf38-2dc5-4b0b-6315-64e1ffd5c586-chart" class=" netdata-chart netdata-easypiechart-chart">
																		<span class="easyPieChartLabel" style="font-size: 14px; top: 44px;">3.31</span>
																		<span class="easyPieChartTitle" style="font-size: 7px; line-height: 7px; top: 27px;">Used Swap</span>
																		<span class="easyPieChartUnits" style="font-size: 6px; top: 67px;">%</span>
																		<canvas height="105" width="105"></canvas>
																	</div>
																</div>

																<div class="netdata-container-easypiechart" style="margin-right: 10px; width: 11%;" data-netdata="system.io" data-dimensions="in" data-chart-library="easypiechart" data-title="Disk Read" data-width="11%" data-before="0" data-after="-420" data-points="420" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 12.8px; padding-top: 55.1px;">
																		<i class="fa fa-refresh"></i> nib
																	</div>
																	<div id="easypiechart-3d9599e1-a77f-3c15-96e9-4a71c0dfbeed-chart" class=" netdata-chart netdata-easypiechart-chart">
																		<span class="easyPieChartLabel" style="font-size: 17px; top: 54px;">31.1</span>
																		<span class="easyPieChartTitle" style="font-size: 9px; line-height: 9px; top: 33px;">Disk Read</span>
																		<span class="easyPieChartUnits" style="font-size: 8px; top: 82px;">kilobytes/s</span>
																		<canvas height="128" width="128"></canvas>
																	</div>
																</div>

																<div class="netdata-container-easypiechart" style="margin-right: 10px; width: 11%;" data-netdata="system.io" data-dimensions="out" data-chart-library="easypiechart" data-title="Disk Write" data-width="11%" data-before="0" data-after="-420" data-points="420" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 12.9px; padding-top: 55.05px;">
																		<i class="fa fa-refresh"></i> nib
																	</div>
																	<div id="easypiechart-4903080c-253a-260a-2e4a-e43cf99e915a-chart" class=" netdata-chart netdata-easypiechart-chart">
																		<span class="easyPieChartLabel" style="font-size: 17px; top: 54px;">46.1</span>
																		<span class="easyPieChartTitle" style="font-size: 9px; line-height: 9px; top: 33px;">Disk Write</span>
																		<span class="easyPieChartUnits" style="font-size: 8px; top: 82px;">kilobytes/s</span>
																		<canvas height="129" width="129"></canvas>
																	</div>
																</div>

																<div class="netdata-container-gauge" style="margin-right: 10px; width: 20%;" data-netdata="system.cpu" data-chart-library="gauge" data-title="CPU" data-units="%" data-gauge-max-value="100" data-width="20%" data-after="-420" data-points="420" data-colors="#994499" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 23.3px; padding-top: 55.85px;">
																		<i class="fa fa-refresh"></i> cpu
																	</div>
																	<div id="gauge-21d6ac5c-9d2e-df14-23f9-fb9726732308-chart" class=" netdata-chart netdata-gauge-chart">
																		<canvas id="gauge-21d6ac5c-9d2e-df14-23f9-fb9726732308-canvas" class="gaugeChart" width="233" height="140"></canvas>
																		<span class="gaugeChartLabel" style="font-size: 28px; top: 35px;">51.8</span>
																		<span class="gaugeChartTitle" style="font-size: 13px; line-height: 13px; top: 0px;">CPU</span>
																		<span class="gaugeChartUnits" style="font-size: 12px;">%</span>
																		<span class="gaugeChartMin" style="font-size: 21px;">0</span>
																		<span class="gaugeChartMax" style="font-size: 21px;">100</span>
																	</div>
																</div>

																<div class="netdata-container-easypiechart" style="margin-right: 10px; width: 11%;" data-netdata="system.ipv4" data-dimensions="received" data-chart-library="easypiechart" data-title="IPv4 Inbound" data-width="11%" data-before="0" data-after="-420" data-points="420" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 12.9px; padding-top: 55.05px;">
																		<i class="fa fa-refresh"></i> ipv4
																	</div>
																	<div id="easypiechart-4a79ea87-0812-63d4-b171-930500c64f47-chart" class=" netdata-chart netdata-easypiechart-chart">
																		<span class="easyPieChartLabel" style="font-size: 17px; top: 54px;">312.3</span>
																		<span class="easyPieChartTitle" style="font-size: 9px; line-height: 9px; top: 33px;">IPv4 Inbound</span>
																		<span class="easyPieChartUnits" style="font-size: 8px; top: 82px;">kilobits/s</span>
																		<canvas height="129" width="129"></canvas>
																	</div>
																</div>

																<div class="netdata-container-easypiechart" style="margin-right: 10px; width: 11%;" data-netdata="system.ipv4" data-dimensions="sent" data-chart-library="easypiechart" data-title="IPv4 Outbound" data-width="11%" data-before="0" data-after="-420" data-points="420" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 12.8px; padding-top: 55.1px;">
																		<i class="fa fa-refresh"></i> ipv4
																	</div>
																	<div id="easypiechart-2a7d6cfe-13e4-7726-0089-7af8ba577abd-chart" class=" netdata-chart netdata-easypiechart-chart">
																		<span class="easyPieChartLabel" style="font-size: 17px; top: 54px;">317.9</span>
																		<span class="easyPieChartTitle" style="font-size: 9px; line-height: 9px; top: 33px;">IPv4 Outbound</span>
																		<span class="easyPieChartUnits" style="font-size: 8px; top: 82px;">kilobits/s</span>
																		<canvas height="128" width="128"></canvas>
																	</div>
																</div>

																<div class="netdata-container-easypiechart" style="margin-right: 10px; width: 9%;" data-netdata="system.ram" data-dimensions="used|buffers|active|wired" data-append-options="percentage" data-chart-library="easypiechart" data-title="Used RAM" data-units="%" data-easypiechart-max-value="100" data-width="9%" data-after="-420" data-points="420" data-colors="#66AA00" role="application">
																	<div class="netdata-message icon hidden" style="font-size: 10.5px; padding-top: 44.75px;">
																		<i class="fa fa-refresh"></i> ram
																	</div>
																	<div id="easypiechart-eafa596e-592e-b787-4a6c-9d1f759a1a9a-chart" class=" netdata-chart netdata-easypiechart-chart">
																		<span class="easyPieChartLabel" style="font-size: 14px; top: 44px;">61</span>
																		<span class="easyPieChartTitle" style="font-size: 7px; line-height: 7px; top: 27px;">Used RAM</span>
																		<span class="easyPieChartUnits" style="font-size: 6px; top: 67px;">%</span>
																		<canvas height="105" width="105"></canvas>
																	</div>
																</div>

															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
													<div class="col-lg-12 col-sm-12">
														<div class="card">
															<div class="card-header card-header-icon" data-background-color="blue">
																<i class="material-icons">insert_chart</i>
															</div>
															<div class="card-content">
																<h4 class="card-title">Log By Router</h4>
															<div id="bar_chart"></div>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
													<div class="col-lg-12 col-sm-12">
														<div class="card">
															<div class="card-header card-header-icon" data-background-color="blue">
																<i class="material-icons">insert_chart</i>
															</div>
															<div class="card-content">
																<h4 class="card-title">Log Per Day </h4>
															<div id="bar_chart_file_trace_row_insert"></div>
														</div>
													</div>
												</div>
											</div>





											<div class="row">
													<div class="col-lg-12 col-sm-12">
														<div class="card">
															<div class="card-header card-header-icon" data-background-color="blue">
																<i class="material-icons">history</i>
															</div>
															<div class="card-content">
																<h4 class="card-title">Service Status</h4>
														<div class="panel-body">
															<div class="col-sm-4">
																<div class="list-group">
																	<a class="list-group-item">
																		<i class="fa fa-database fa-fw"></i> Database
																		<span class="pull-right text-muted small">
																			<i class="fa fa-check-circle-o fa-2x success service_status_indicator status_mysql"></i>
																		</span>
																	</a>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="list-group">
																	<a  class="list-group-item">
																		<i class="fa fa-square fa-fw"></i> Syslog
																		<span class="pull-right text-muted small">
																			<i class="fa fa-check-circle-o fa-2x success service_status_indicator status_syslog"></i>
																		</span>
																	</a>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="list-group">
																	<a class="list-group-item">
																		<i class="fa fa-th fa-fw"></i> NIB
																		<spans class="pull-right text-muted small">
																			<i class="fa fa-check-circle-o fa-2x success service_status_indicator status_nib"></i>
																		</span>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											</div>

												<div class="row">
													<div class="col-lg-12">

														<div class="col-lg-4 ">
															<div class="card">
																<div class="card-header card-header-icon" data-background-color="blue">
																	<i class="material-icons">pie_chart</i>
																</div>
																<div class="card-content">
																	<h4 class="card-title">System Usage</h4>
																	<div id="piechart_disk" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto;" ></div>
																</div>

															</div>
														</div>

														<div class="col-lg-4 ">
															<div class="card">
																<div class="card-header card-header-icon" data-background-color="blue">
																	<i class="material-icons">pie_chart</i>
																</div>
																<div class="card-content">
																	<h4 class="card-title">Log PartitionDB Partition</h4>
																	<div id="piechart_log" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto;" ></div>
																</div>

															</div>
														</div>

														<div class="col-lg-4 ">
															<div class="card">
																<div class="card-header card-header-icon" data-background-color="blue">
																	<i class="material-icons">pie_chart</i>
																</div>
																<div class="card-content">
																	<h4 class="card-title">DB Partition</h4>
																	<div id="piechart_database" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto;" ></div>
																</div>
															</div>
														</div>
													</div>
												</div>


												<div class="row">
													<div class="col-md-12">
														<div class="card">
															<div class="card-header card-header-icon" data-background-color="blue">
																<i class="material-icons">notifications</i>
															</div>
															<div class="card-content">
																<h4 class="card-title">Notifications Panel</h4>
																<div class="panel-body">
																	<div class="list-group">
																		<div class="panel-body">
																			<?php foreach($notification as $item):
																				$notification_title=$item['notification_title'];
																				$notification_body=$item['notification_body'];
																				$notification_time=$item['notification_time'];
																				$notification_type=$item['notification_type'];

																				?>
																				<a  class="list-group-item">
																					<?php  if($notification_type=="warning") {?>
																						<i class="fa fa-exclamation-triangle"></i>
																					<?php }
																					elseif($notification_type=="error") {?>
																						<i class="fa fa-window-close"></i>
																					<?php }
																					elseif($notification_type=="debug") {?>
																						<i class="fa fa-bug"></i>
																					<?php }
																					elseif($notification_type=="message") {?>
																						<i class="fa fa-comment fa-fw"></i>
																					<?php } ?>
																					<strong><?php echo $notification_title ?></strong><br><?php echo $notification_body ?>
																					<span class="pull-right text-muted small"><em> <?php echo $notification_time ?></em>
																					</span>
																				</a>
																			<?php endforeach; ?>
																		</div>
																		<a href="dashboard/view_notifications" class="btn btn-info btn-block">View All Alerts</a>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
									</div>

										<div class="tab-pane" id="email">

											<div class="row">
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="purple">
															<i class="material-icons">people</i>
														</div>
														<div class="card-content">
															<p class="category">Total Customer</p>
															<h3 class="card-title"><?=$total_customer?></h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">
																<i class="material-icons text-danger">people</i>
																<a href="<?php echo base_url(); ?>ppp">View Customer</a>
															</div>
														</div> -->
													</div>
												</div>

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="green">
															<i class="material-icons">people</i>
														</div>
														<div class="card-content">
															<p class="category">Customer This Month</p>
															<h3 class="card-title"><?= ($recently_added_customer==0) ? 0 : $recently_added_customer ;?></h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">
																<i class="material-icons text-danger">people</i>
																<a href="<?php echo base_url(); ?>ppp">View Customer</a>
															</div>
														</div> -->
													</div>
												</div>
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="blue">
															<i class="material-icons">attach_money</i>
														</div>
														<div class="card-content">
															<p class="category">Invoice This Month</p>
															<h3 class="card-title"><?= ($total_invoice_amount_current_month==0) ? 0 : $total_invoice_amount_current_month ;?> tk</h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">

															</div>
														</div> -->
													</div>
												</div>

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="orange">
															<i class="material-icons">attach_money</i>
														</div>
														<div class="card-content">
															<p class="category">Due This Month</p>
															<h3 class="card-title"><?= ($total_due_current_month==0) ? 0 : $total_due_current_month ;?> tk</h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">

															</div>
														</div> -->
													</div>
												</div>


											</div>

											<div class="row">

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="blue">
															<i class="material-icons">attach_money</i>
														</div>
														<div class="card-content">
															<p class="category">Invoice Prev Month</p>
															<h3 class="card-title"><?= ($previous_month_total_invoice_amount==0) ? 0 : $previous_month_total_invoice_amount ;?> tk</h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">
															</div>
														</div> -->
													</div>
												</div>

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="orange">
															<i class="material-icons">attach_money</i>
														</div>
														<div class="card-content">
															<p class="category">Total Due Prev Month</p>
															<h3 class="card-title"><?= ($previous_month_total_due==0) ? 0 : $previous_month_total_due ;?> tk</h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">

															</div>
														</div> -->
													</div>
												</div>
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="red">
															<i class="material-icons">attach_money</i>
														</div>
														<div class="card-content">
															<p class="category">Total Due Amount</p>
															<h3 class="card-title"><?= ($total_due==0) ? 0 : $total_due ;?> tk</h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">

															</div>
														</div> -->
													</div>
												</div>
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="card card-stats">
														<div class="card-header card-header-icon" data-background-color="green">
															<i class="material-icons">payment</i>
														</div>
														<div class="card-content">
															<p class="category">Payment This Month</p>
															<h3 class="card-title"><?= ($current_payment==0) ? 0 : $current_payment ;?> tk</h3>
														</div>
														<!-- <div class="card-footer">
															<div class="stats">

															</div>
														</div> -->


													</div>
												</div>
											</div>


											<div class="row">
												<div class="row">
														<div class="col-lg-12 col-sm-12">
															<div class="card">
																<div class="card-header card-header-icon" data-background-color="blue">
																	<i class="material-icons">insert_chart</i>
																</div>
																<div class="card-content">
																	<h4 class="card-title">Payment Bar Chart</h4>
																<div id="payment_bar_chart_"></div>
															</div>
														</div>
													</div>
												</div>


												<div class="row">
														<div class="col-lg-12 col-sm-12">
															<div class="card">
																<div class="card-header card-header-icon" data-background-color="blue">
																	<i class="material-icons">insert_chart</i>
																</div>
																<div class="card-content">
																	<h4 class="card-title">Invoice Bar Chart</h4>
																<div id="invoice_bar_chart_"></div>
															</div>
														</div>
													</div>
												</div>



											<!-- //current month pie -->
											<!-- <div  class="col-md-6 col-md-offset-3">
																<div class="card">
																		<div class="card-header card-header-icon" data-background-color="red">
																				<i class="material-icons">pie_chart</i>
																		</div>
																		<div class="card-content">
																				<h4 class="card-title">Pie Chart (Current Month)</h4>
																		</div>
																		<div id="chartPreferences" class="ct-chart"></div>
																		<div class="card-footer">
																				<h6>Legend</h6>
																				<i class="fa fa-circle text-info"></i> Total Invoice
																				<i class="fa fa-circle text-danger"></i> Total Due
																		</div>
																</div>
														</div> -->

											</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</div><!-- end of class container  -->
</div><!-- end of class container  -->


<?php
	require_once('footer.php');

?>


<script type="text/javascript">
$(document).ready(function(){
var flag = 0;
$('#billingTab').on('click', function() {
	if(flag == 0)
	{
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: "<?php echo base_url(); ?>dashboard/get_payment_by_month",
			success: function(result) {
						var json_arr=[];
						var month_arr=[];
						var month_name=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
						var column_colors=[];

						for(var i=0;i<result.length;i++){
							month_arr.push(result[i].month);
						}
						//console.log("month_arr:"+ month_arr);


						uniqueMonthArrayr = month_arr.filter(function(item, pos) {
							return month_arr.indexOf(item) == pos;
						})

					 // console.log("uniqueMonthArrayr:"+uniqueMonthArrayr);

						column_colors.push("#183326");

						for(var i=0;i<uniqueMonthArrayr.length;i++){
							var ob = {};
							ob["x"] = month_name[uniqueMonthArrayr[i]-1] +". "+result[i].year;
							for(var j=0;j<result.length;j++){
								if(result[j].month === uniqueMonthArrayr[i]){
									ob["y"] = result[j].paid_amount_;
								}
							}
							json_arr.push(ob);
						}
					 Morris.Bar({
						element: 'payment_bar_chart_',
						data: json_arr,
						xkey: "x",
						ykeys: "y",
						labels: "T",
						barColors: column_colors
					});
			}
		}),
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: "<?php echo base_url(); ?>dashboard/get_invoice_by_month",
			success: function(result) {
						var json_arr=[];
						var month_arr=[];
						var month_name=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
						var column_colors=[];

						for(var i=0;i<result.length;i++){
							month_arr.push(result[i].month);
						}
						//console.log("month_arr:"+ month_arr);


						uniqueMonthArrayr = month_arr.filter(function(item, pos) {
							return month_arr.indexOf(item) == pos;
						})

					//  console.log("uniqueMonthArrayr:"+uniqueMonthArrayr);

						column_colors.push("#6666ff");

						for(var i=0;i<uniqueMonthArrayr.length;i++){
							var ob = {};
							ob["x"] = month_name[uniqueMonthArrayr[i]-1] +". "+result[i].year;
							for(var j=0;j<result.length;j++){
								if(result[j].month === uniqueMonthArrayr[i]){
									ob["y"] = result[j].invoice_amount_;
								}
							}
							json_arr.push(ob);
						}
					 Morris.Bar({
						element: 'invoice_bar_chart_',
						data: json_arr,
						xkey: "x",
						ykeys: "y",
						labels: "T",
						barColors: column_colors
					});
			}
		});

		flag=1;
	}

	});


	function randomIntFromInterval(min,max)
	{
			return Math.floor(Math.random()*(max-min+1)+min);
	}


$.ajax({
	type: 'POST',
	dataType: 'json',
	url: "<?php echo base_url(); ?>dashboard/get_row_inserts",
	success: function(result) {
				var json_array=[];
				var router_date_arr=[];
				var router_ip_arr=[];
				var router_name_arr=[];
				var y_keys =[];
				var column_colors=[];
				var last_date="";

				for(var i=0;i<result.length;i++){
					router_date_arr.push(result[i].date_);
					router_ip_arr.push(result[i].router_ip);
					router_name_arr.push(result[i].name);
				}
				//console.log("DATE:"+ router_date_arr);
				uniqueArray = router_date_arr.filter(function(item, pos) {
					return router_date_arr.indexOf(item) == pos;
				})
			  console.log("uniqueArray:"+uniqueArray);

				unique_IP_Array = router_ip_arr.filter(function(item, pos) {
					var hex = randomIntFromInterval(111111,999999);
					// var r=255,g=255,b=255;
					// while((r>10) && (g>10) && (b>10)){
					//   r = randomIntFromInterval(0,255);
					// 	g = randomIntFromInterval(0,255);
					//   b = randomIntFromInterval(0,255);
					// 	var rgb = '!!!!!!!!rgb(' + r + ',' + g + ',' + b + ')';// Collect all to a string
					// 	console.log(rgb);
					// }
					// r = r.toString();
					// g = g.toString();
					// b = b.toString();
					// var rgb = 'rgb(' + r + ',' + g + ',' + b + ')';// Collect all to a string
					if ( !(hex in column_colors) ) {
						column_colors.push("#"+hex);
						console.log(hex);
					}

					return router_ip_arr.indexOf(item) == pos;
				})
				unique_NAME_Array = router_name_arr.filter(function(item, pos) {
					return router_name_arr.indexOf(item) == pos;
				})

				console.log("unique_NAME_Array: "+unique_NAME_Array);

				for(var i=0;i<uniqueArray.length;i++){
					var obj={};
					obj["x"]=uniqueArray[i];
					for(var j=0;j<result.length;j++){
						if(result[j].date_ === uniqueArray[i]){
							//console.log("Result date :"+result[j].date_+ "== uniqueArray Date :"+uniqueArray[i]);
							var ip  = result[j].router_ip;
							obj[ip] = result[j].row_inserts_;
							y_keys.push(ip);
							//console.log("IP- "+ip+" ROW- "+result[j].row_inserts_);
						}
					}
					json_array.push(obj);
					//console.log("OBJ:"+JSON.stringify(obj));
				}
				//console.log("JSON-ARR:"+JSON.stringify(json_array));
				//console.log("Y-KEYS:"+JSON.stringify(y_keys));


			 Morris.Bar({
				element: 'bar_chart',
				data: json_array,
				xkey: "x",
				ykeys: unique_IP_Array,
				labels: unique_NAME_Array,
				barColors: column_colors
			});
	}
}),
$.ajax({
	type: 'POST',
	dataType: 'json',
	url: "<?php echo base_url(); ?>dashboard/get_row_inserts_from_file_trace",
	success: function(result) {
				var json_array=[];
				var router_date_arr=[];
				var y_keys =[];
				var column_colors=[];

				for(var i=0;i<result.length;i++){
					router_date_arr.push(result[i].date_);
				}
				//console.log("DATE:"+ router_date_arr);

				uniqueArray = router_date_arr.filter(function(item, pos) {
					return router_date_arr.indexOf(item) == pos;
				})

			  // console.log("uniqueArray:"+uniqueArray);
				column_colors.push("#11233f");

				for(var i=0;i<uniqueArray.length;i++){
					var obj = {};
					obj["x"] = uniqueArray[i];
					for(var j=0;j<result.length;j++){
						if(result[j].date_ === uniqueArray[i]){
							obj["y"] = result[j].row_inserts_;
						}
					}
					json_array.push(obj);
				}
			 Morris.Bar({
				element: 'bar_chart_file_trace_row_insert',
				data: json_array,
				xkey: "x",
				ykeys: "y",
				labels: "T",
				barColors: column_colors
			});
	}
});




	Highcharts.setOptions({
	colors: ['#ff0000', '#00d103'] //, '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'
	});

	Highcharts.chart('piechart_disk', {
	  chart: {
	    plotBackgroundColor: null,
	    plotBorderWidth: null,
	    plotShadow: false,
	    type: 'pie'
	  },
		credits: {
      enabled: false
  },
	  title: {
	    text: 'System Usage'
	  },
	  tooltip: {
	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	  },
	  plotOptions: {
	    pie: {
	      allowPointSelect: true,
	      cursor: 'pointer',
	      dataLabels: {
	        enabled: true,
	        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
	        style: {
	            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
	        }
	      }
	    }
	  },
	  series: [{
	   // name: 'Brands',
	    colorByPoint: true,

	    data: [{
	      name: 'Used',
	      y: <?=disk_total_space("/") - disk_free_space("/") ?>
	    }, {
	      name: 'Unused',
	      y: <?=disk_free_space("/");?>,
	      sliced: true,
	      selected: true
	    }]
	  }]
	});

	Highcharts.setOptions({
	colors: ['#ff0000', '#0278ff'] //, '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'
	});

	Highcharts.chart('piechart_log', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		credits: {
      enabled: false
  },
		title: {
			text: 'Log Partition'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %',
					style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					}
				}
			}
		},
		series: [{
		 // name: 'Brands',
			colorByPoint: true,

			data: [{
				name: 'Used',
				y: <?=disk_total_space("/log/") - disk_free_space("/log/") ?>
			}, {
				name: 'Unused',
				y: <?=disk_free_space("/log/");?>,
				sliced: true,
				selected: true
			}]
		}]
	});

	Highcharts.setOptions({
	colors: ['#ff0000', '#fbff19'] //, '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'
	});


	Highcharts.chart('piechart_database', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		credits: {
      enabled: false
  },
		title: {
			text: 'DB Partition'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %',
					style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					}
				}
			}
		},
		series: [{
		 // name: 'Brands',
			colorByPoint: true,

			data: [{
				name: 'Used',
				y: <?=disk_total_space("/var/lib/mysql") - disk_free_space("/var/lib/mysql") ?>
			}, {
				name: 'Unused',
				y: <?=disk_free_space("/var/lib/mysql");?>,
				sliced: true,
				selected: true
			}]
		}]
	});


	function check_status(){

		$.ajax({
	    type: 'POST',
	    dataType: 'json',
	    // url: '/echo/json/',
	    url: "<?php echo base_url(); ?>dashboard/fetch_status_all",
	    // data : { json: JSON.stringify( jsonData ) },
	    success: function(data) {
	      $(".service_status_indicator").removeClass('success, error');

	      if(data.status_syslog==1){
	      	$(".status_syslog").addClass('fa-check-circle-o success');
			  }else{
			  	$(".status_syslog").addClass('fa-exclamation-triangle error');
			  }

			  if(data.status_application==1){
	      	$(".status_nib").addClass('fa-check-circle-o success');
		    }else{
			  	$(".status_nib").addClass('fa-exclamation-triangle error');
			  }

			  if(data.status_mysql==1){
	      	$(".status_mysql").addClass('fa-check-circle-o success');
			  }else{
			  	$(".status_mysql").addClass('fa-exclamation-triangle error');
			  }

			  if(data.status_logfile==1){
	      	$(".status_logfile").addClass('fa-check-circle-o success');
			  }else{
			  	$(".status_logfile").addClass('fa-exclamation-triangle error');
			  }

				$('.active-devices').html(data.today_user);
				$('.active-pppoe').html(data.today_pppoe);
	      if(window.console){
	      	// console.log(" data = " + data.today_user);
			  }

	    }
	  });

	}// end of function check_status

	setInterval(function(){
		check_status();
	}, 1000*300);

	check_status();
});
</script>
