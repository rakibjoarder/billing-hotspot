<?php

require_once('header.php');

if(permission("Cus","Create In_voice",$permission_string)===0 || permission("Cus","View",$permission_string)=== -1){
echo "<script>
alert('You do not have permission to access this page. Contact your admin to get access !');
window.location.href='/login/logout';
</script>";

}
  ?>

	<?php
	 foreach($customers as $info):
		 $id_net_user=$info["id_net_user"];
		 $net_user_username=$info["net_user_username"];
     $id_package=$info["id_package"];
	 endforeach;
	?>
	<div class="row">
	<div _ngcontent-c6="" class="col-sm-6 col-sm-offset-3">
		<div _ngcontent-c6="" class="card">
			<div _ngcontent-c6="" class="card-header" data-background-color="blue">
				<h3 style="color:white;" _ngcontent-c6="" class="title">Create Invoice - <?=$net_user_username?></h3>
			</div>
			<div id="result"></div>
			<form  method="post"  id="form_admin" enctype="multipart/form-data">

        <div class="col-sm-12 col-sm-offset-.5">
          <div class="card">
            <div class="card-content">
              <div class="form-group label-floating">
                <label class="control-label">Package</label>
                <select  name="id_package" id='id_package' class="form-control" required>
                  <option disabled="" selected=""></option>
                  <?php foreach ($packages as $package):
                    if($id_package==$package['id_package']) { ?>
                      <option  selected value="<?= $package['package_name']?>" price="<?= $package['package_price']?>" bandwidth="<?= $package['package_speed']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
                    <?php }
                    else { ?>
                      <option  value="<?= $package['package_name']?>" price="<?= $package['package_price']?>" bandwidth="<?= $package['package_speed']?>"><?= $package['package_name'] ?> (<?= $package['package_price']?> Taka)</option>
                    <?php } ?>
                  <?php endforeach; ?>


                </select>
              </div>

              <div  class="form-group " id="price_id_div" style="margin-top:-14px">
                <label class="control-label">Assigned Bandwidth</label>
                <input type="text" class="form-control" id="net_user_assigned_bandwidth" required  name="net_user_assigned_bandwidth" disabled>
              </div>

              <div  class="form-group " style="margin-top:-14px">
                <label class="control-label">MRC Price</label>
                <input type="text" class="form-control" id="net_user_package_price" disabled >
              </div>

              <div class="form-group label-floating">
                <label class="control-label">Billing Options </label>
                <div class="radio">
                  <label style="margin-left:20px;margin-top:10px;"><input type="radio" name="optionsRadios" value="rest_of_the_month" checked="true" > Rest of the month </label>
                  <label style="margin-left:20px;margin-top:10px;"><input type="radio" name="optionsRadios" value="select_date_range"  > Select Date Range </label>
                </div>


              </div>
              <div  hidden id="datepicker">
                <div class="form-group label-floating col-md-6 col-md">
                  <label class="control-label">Start Date</label>
                  <input type="text" class="form-control" id="start_date" name="event_start_date" required >
                </div>

                <div class="form-group label-floating col-md-6 col-md">
                  <label class="control-label">End Date</label>
                  <input type="text" class="form-control" id="end_date" name="event_end_date"  required>
                </div>
                <button class=" btn btn-info btn-block" style="color:#fff; font-family: 'Roboto'; " type="button"   id="btnclick">Calculate Bill</button>
              </div>
              <div style="margin-top:-16px" class="form-group " id="assigned_bandwidth_id">
                <label class="control-label">Billing Amount</label>
                <input type="text" class="form-control" id="net_user_mrc_price"   name="net_user_mrc_price" required>
              </div>
              <button type="submit" name="submit" class="btn btn-fill btn-info" value="submit" >Submit</button>
              <a href="<?php echo base_url();?>ppp" class="btn btn-fill btn-info">Back</a>
            </div>
          </div>






						<input type="hidden"  id="id_net_user" required   name="id_net_user" value="<?=$id_net_user?>">
					</form>
				</div>
			</div>

		</div><!-- end of class form_area -->
	</div><!-- end of class container  -->
</div>
</div>



<?php
	require_once('footer.php');
?>



<script type="text/javascript">

$("#start_date, #end_date").flatpickr({
  enableTime: false,
  dateFormat: "m-d-Y",
  minDate: new Date().fp_incr(-(new Date(moment()).getDate()-1)),
  maxDate: new Date().fp_incr(new Date(moment().endOf('month')).getDate()-new Date(moment()).getDate()) // 14 d
});


var start_date;
var end_date;
var date = new Date();
var current_day=date.getDate();

$(document).ready(function(){

  var bandwidth=$('option:selected', this).attr("bandwidth");
  var price=$('option:selected', this).attr("price");

  var last_day_of_month=moment().endOf('month');
  var last_day_Date = new Date(last_day_of_month);
  var last_day=last_day_Date.getDate();
  var d=1+last_day-current_day;
  var total_price=(price/last_day)*d;

  $('#net_user_mrc_price').val(total_price.toFixed(2));
  $('#net_user_assigned_bandwidth').val(bandwidth);
  $('#net_user_package_price').val(price);

  $("#start_date").on("change",function(){
    start_date= $(this).val();
  });

  $("#end_date").on("change",function(){
    end_date = $(this).val();
  });

  $("input[name='optionsRadios']").click(function(){

    if($('input[name=optionsRadios]:checked').val()=="rest_of_the_month"){
      $("#datepicker").hide(500);
      var price=$('#net_user_package_price').val();

      var d=1+last_day-current_day;
      var total_price=(price/last_day)*d;
      $('#net_user_mrc_price').val(total_price.toFixed(2));

    }else if($('input[name=optionsRadios]:checked').val()=="select_date_range"){
      $("#datepicker").show(500);
      if($("#start_date").val().length =! 0 && $("#end_date").val().length ){
        calculate_bill_by_date_range();
      }
    }
  });

  $("#btnclick").on("click",function(){
    calculate_bill_by_date_range();
  });
  $('select[name="id_package"]').on('change',function(){
    var price=$('option:selected', this).attr("price");
    var bandwidth=$('option:selected', this).attr("bandwidth");
    var d=1+last_day-current_day;
    var total_price=(price/last_day)*d;
    $('#net_user_package_price').val(price);
    $('#net_user_mrc_price').val(total_price.toFixed(2));
    $('#net_user_assigned_bandwidth').val(bandwidth);
  });


function calculate_bill_by_date_range(){
  var price=$('#net_user_package_price').val();
  var date1 = new Date(start_date);
  var date2 = new Date(end_date);
  var timeDiff = Math.abs(date2.getTime() - date1.getTime());
  var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
  var total_price=(price/last_day)*(diffDays+1);
  $('#net_user_mrc_price').val(total_price.toFixed(2));
}


	$("#form_admin").validate({

    submitHandler: function (form) {
      var reqData = $("#form_admin").serialize();

      $.ajax({
        type:"POST",
        contentType: "application/x-www-form-urlencoded",
        dataType:"json",
        data: reqData,
        url: "<?php echo base_url() ?>ppp/create_invoice_now",

        success:function(response) {
          if(response.status === 'success') {
            var invoice_id=response.invoice_id;
            showNotification(2,response.msg);
            $.ajax({
              type:"POST",
              dataType:"json",
              data:'id_invoice=' + invoice_id,
              url: '<?php echo base_url() ?>email/send_email/'+invoice_id,

              success:function(response) {
                if(response.status === 'success') {
                }
              }
            }),
            $.ajax({
              type:"POST",
              dataType:"json",
              data:'id_invoice=' + invoice_id,
              url: '<?php echo base_url() ?>email/send_sms/'+invoice_id,

              success:function(response) {
                if(response.status === 'success') {
                }
              }
            });
          } else if(response.status === 'failed') {
            showNotification(3,response.msg);
          }
        },
        error: function (result) {
          showNotification(3,"Error " + JSON.stringify(result));
        }
      });
    }
  });


});

</script>
