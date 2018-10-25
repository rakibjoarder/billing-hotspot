<?php
	require_once('header.php');
	if( (permission("Sea","View",$permission_string)===0 || permission("Sea","View",$permission_string)=== -1)){
		echo "<script>
		alert('You do not have permission to access this page. Contact your admin to get access !');
		window.location.href='/login/logout';
		</script>";
	}
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


<div  class="col-md-12">
	<div class="card">
		<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">search</i></div>
		<div class="card-content">
			<h4 class="card-title">Search</h4>
			<div class="toolbar"></div>
			<div id="result"></div>
			<form id="form_search" action="#" method="POST" class="form-horizontal" role="form">
				<div class="col-md-12 col-sm-12">

					<div class="col-md-2">
						<div class="form-group label-floating">
							<label class="control-label">Dest. IP</label>
							<input class="form-control" id="dest_ip" name="dest_ip" type="text"/>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group label-floating">
							<label class="control-label">Port</label>
							<input class="form-control" id="dest_port" name="dest_port" type="text"/>
						</div>
					</div>

					<div class="col-md-1">
					</div>

					<div class="col-md-2">
						<div class="form-group label-floating">
							<label class="control-label">Src Ip</label>
							<input class="form-control" id="src_ip" name="src_ip" type="text"/>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group label-floating">
							<label class="control-label">Port</label>
							<input class="form-control" id="src_port" name="src_port" type="text" />
						</div>
					</div>

					<div class="col-md-1">

					</div>
					<div class="col-md-2">
						<div class="form-group label-floating">
							<label class="control-label">Mac</label>
							<input class="form-control" id="mac_address" name="mac_address" type="text" />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group label-floating">
							<label class="control-label">Select Router</label>
							<select class="form-control" id="router_ip" name="router_ip">
								<option selected value="-1">All Routers</option>
								<?php
									foreach($routers_all as $routers_indiv){
								?>
										<option value="<?php echo $routers_indiv['ip_address']; ?>"><?php echo $routers_indiv['name']; ?></option>
								<?php
									}
								?>
							</select>
						</div>


					</div>
					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">From Date & Time</label>
								<input class="form-control" id="start_date_time" name="start_date_time" type="text" />
							</div>
						</div>

						<div class="col-md-1">

						</div>
						<div class="col-md-3">
							<div class="form-group label-floating">
								<label class="control-label">To Date & Time</label>
								<input class="form-control" id="end_date_time" name="end_date_time" type="text"/>
							</div>
						</div>

						<div class="col-md-1">
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<button type="submit" id="btn_search" class="btn btn-info" style='width:100%;'>
									<i class="fa fa-search "></i>
									Search
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div hidden id= "div" class="row" >
<div class="col-md-12">
	<div class="card">
		<div class="card-header card-header-icon" data-background-color="blue"><i class="material-icons">assignment</i></div>
		<div class="card-content">
			<h4 class="card-title">All Users</h4>
			<div class="toolbar"></div>
			<div class="material-datatables table-responsive">
				<table id="search_grid" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
					<thead>
						<tr>
							<th>MAC</th>
							<th>User</th>
							<th>Router IP</th>
							<th>Source IP</th>
							<th>Source Port</th>
							<th>Dest. IP</th>
							<th>Dest. Port</th>
							<th>Domain</th>
							<th>Date &amp; Time</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>MAC</th>
							<th>User</th>
							<th>Router IP</th>
							<th>Source IP</th>
							<th>Source Port</th>
							<th>Dest. IP</th>
							<th>Dest. Port</th>
							<th>Domain</th>
							<th>Date &amp; Time</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
</div>





<?php
	require_once('footer.php');
?>








<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" charset="utf-8"></script>
<style media="screen">
.dt-buttons {
	display: relative;
	float: left;

}

.dataTables_length {
	display: relative;
	float: left;
	margin-left: 30px;

}

.search_grid_filter {

}

.dt-button {
	display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -ms-touch-action: manipulation;
  touch-action: manipulation;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-image: none;
  border: 1px solid transparent !important;
  border-radius: 4px !important;

	background: #286090 !important;
	background-color: #286090 !important;
	color: #ffffff !important;
}

</style>

<script type="text/javascript">

	$(document).ready(function() {




    $("#start_date_time, #end_date_time").flatpickr({

		});

		var Table = $('#search_grid').DataTable({
			dom: 'Blfrtip',
			buttons: ['csv', 'excel', 'pdf', 'print'],
	  	data:[],
	    columns: [
	      { "data": "mac" },
	      { "data": "user_id" },
	      { "data": "router_ip" },
				{ "data": "src_ip" },
	      { "data": "src_port" },
	      { "data": "dest_ip" },
	      { "data": "dest_port" },
	      { "data": "domain" },
	      { "data": "access_time" }
	    ],
	    rowCallback: function (row, data) {},
	    filter: true,
	    info: true,
	    ordering: true,
	    processing: true,
	    retrieve: true,
			pageLength: 50
		});

		$('#form_search').submit(function(e) {
			e.preventDefault();
			console.log("form search submit function.");
			Table.clear().draw();

			$('#btn_search i').addClass('fa-spinner fa-spin');
			$('#btn_search').attr('disabled', true);

			var last_response_len = false;
      $.ajax({
				type: "POST",
				url: '<?php echo base_url(); ?>search/incremental_search',
				dataType: 'text',
				data: $(this).serialize(),
        xhrFields: {
          onprogress: function(e, text) {
            var this_response, data = e.currentTarget.response;
            if(last_response_len === false) {
              this_response = JSON.parse(data);
              last_response_len = data.length;
							console.log("> last response len " + last_response_len);
							Table.rows.add(this_response).draw();
            } else {
              this_response = JSON.parse(data.substring(last_response_len));
              last_response_len = data.length;
							console.log(">> last response len " + last_response_len);
							Table.rows.add(this_response).draw();
            }
            console.log(data);
          }
        }
      })
      .done(function(data) {
				$('#btn_search i').removeClass('fa-spinner fa-spin');
				$('#btn_search').attr('disabled', false);
				$('#div').show(500);
      })
      .fail(function(data) {
				$('#btn_search i').removeClass('fa-spinner fa-spin');
				$('#btn_search').attr('disabled', false);
				$('#div').hide(500);
        console.log('Error: ', data);
				// $('#spinner').hide();
      });
      console.log('Request Sent');

		});

		$("#btn_search12").on("click", function (event) {
	  	$.ajax({
	      url: "<?php echo base_url(); ?>search/search_ajax",
	      type: "post",
	      dataType:"json",

	    }).done(function (result) {
	        Table.clear().draw();
	        Table.rows.add(result).draw();
	        }).fail(function (jqXHR, textStatus, errorThrown) {

	        });
		});
	});

</script>
