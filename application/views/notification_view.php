<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('header.php');
?>
<style>
table.dataTable > thead > tr > th,
table.dataTable > tbody > tr > th,
table.dataTable > tfoot > tr > th,
table.dataTable > thead > tr > td,
table.dataTable > tbody > tr > td,
table.dataTable > tfoot > tr > td {
text-align:  center;
padding: 15px !important;
}
</style>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header card-header-tabs" data-background-color="blue">
					<div class="nav-tabs-navigation">
						<div class="nav-tabs-wrapper">
							<span class="nav-tabs-title"><h1 class="card-title">All Notifications</h1></span>
							<ul class="nav nav-tabs" data-tabs="tabs">
								<li  style="float:right;" >
									<a class="btn btn-info btn-round" href="<?php echo base_url(); ?>dashboard">
										<i class="material-icons">exit_to_app</i>Back&nbsp;
										<div class="ripple-container"></div></a>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="card-content">
					<div class="toolbar"><!--        Here you can write extra buttons/actions for the toolbar              --></div>
					<div class="material-datatables table-responsive">
						<div id="result"></div>
						<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
    			<thead>
    				<tr>
              <th>Title</th>
    					<th>Details</th>
              <th>Type</th>
              <th>Date</th>
    				</tr>
    			</thead>
    			<tbody>
    				<?php foreach($notifications as $item):	?>
      					<tr>
      						<td><?php echo $item['notification_title'] ?></td>
      						<td><?php echo $item['notification_body'] ?></td>
                  <td><?php echo $item['notification_type'] ?></td>
                  <td><?php echo $item['notification_time'] ?></td>

      					</tr>

    				<?php
    				  endforeach;
    				?>
    			</tbody>
    			<tfoot>
    				<tr>
              <th>Title</th>
    					<th>Details</th>
              <th>Type</th>
              <th>Date</th>
    				</tr>
    			</tfoot>
    		</table>
    	</div>
    </div>
</div>
</div>
</div>

  <!-- /.container-fluid -->



<?php
require_once('footer.php');
?>


<script>
$(document).ready(function() {

  oTable= $('#search_grid').DataTable({});
});

</script>
