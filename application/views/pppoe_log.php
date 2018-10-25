<?php
	require_once('header.php');
	if(permission("lo","View",$permission_string)===0 || permission("lo","View",$permission_string)=== -1){
	echo "<script>
	alert('You do not have permission to access this page. Contact your admin to get access !');
	window.location.href='/login/logout';
	</script>";
}
?>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-tabs" data-background-color="blue">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper">
						<span class="nav-tabs-title"><h1 class="card-title">Log View</h1></span>
						<ul class="nav nav-tabs" data-tabs="tabs">
							<li  style="float:right;margin-left:5px; margin-top:10px;" >
								<a style="padding:9px;" class="btn btn-primary" id="download" href="<?php echo base_url();?>log/download?file=pppoe">
									<i class="material-icons">file_download</i>
									<div class="ripple-container"></div></a>
								</a>
							</li>

							<li  style="float:right;" >
								<button  style="background:#0368c6;" id="start_pause" type="button" class="btn btn-primary" onclick="start_pause()">Pause</button>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="card-content">
				<div class="toolbar"></div>
			</h4>
		</div>
		<div id="tail_window" class="panel-body">
		</div>
	</div>
</div>
</div>


<script type="text/javascript">
	var start=1;
	var count = 0;

	function start_pause() {
		if(start==1)
		{
			document.getElementById("start_pause").innerHTML = "Start";
			document.getElementById("start_pause").style.background = "#ea3838";
			start=0;
		}
		else if (start==0) {
			document.getElementById("start_pause").innerHTML = "Pause";
			document.getElementById("start_pause").style.background = "#1a6ebc";
			start=1;
			connectToServer(count);
		}


	}

	$(document).ready(function() {
		connectToServer(0);
	});

	function connectToServer(linenum) {
		if(start==1){
		$.ajax({
			dataType: "json",
			url: "<?=base_url()?>log/pppoe_log",
			data: { num:linenum },
			timeout: 120000, // in milliseconds
			success: function(data) {
				var count = 0;

				if (data == null){
					console.log('ajax failed. reloading...');
					connectToServer(0);
					$("#tail_window").html("Error, reloading...");
				} else {
					if(data.status == 'updated') {
						var items = [];
						count = parseInt(data.count);

						var loglines = data.loglines;
						loglines.reverse();
						var l = 0;
						$.each( loglines, function( key, val ) {
							l = l+1;
							if(l == 0) {
								items.push(val.toString());
							} else {
								items.push("<br/>"+val.toString());
							}

						});// end each

						var newlines = items.join( "" );
						$("#tail_window").prepend(newlines);

						setTimeout(function(){
							connectToServer(count);
						}, 2000);
					} else {
						count = parseInt(data.count);
						setTimeout(function(){
							connectToServer(count);
						}, 2000);
					}
				} // end of else

			}, // end success
			error: function(request, status, err) {
				if(status == "timeout") {
					console.log('ajax failed. reloading...');
					connectToServer(0);
					$("#tail_window").html("Local timeout, reloading...");
				}// end if
			} // end error
		}); // end ajax
	} // end start_pause if
	} // end function connectToServer

</script>
<?php
	require_once('footer.php');
?>
