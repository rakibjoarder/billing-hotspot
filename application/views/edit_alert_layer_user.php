<?php
	require_once('header.php');
?>

<?php
$id_alert_layers='';
$alert_layers_name='';
foreach($alerts_layers_info as $alerts_layers_indiv):
  $id_alert_layers=$alerts_layers_indiv["id_alert_layers"];
  $alert_layers_name=$alerts_layers_indiv["alert_layers_name"];
  break;
endforeach;

$id_user='';
foreach($alerts_layers_user as $alerts_layers_indiv):

  $id_alert_layers_users=$alerts_layers_indiv["id_alert_layers_users"];
  $id_user=$alerts_layers_indiv["id_user"];
  break;
endforeach;
?>

<div class="row">
	<div class="col-lg-12">
			<h1 class="page-header">Add User to <?php echo $alert_layers_name ?></h1>
	</div>
</div>


<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div id="result"></div>
  	<form action="<?php echo base_url() ?>alert/edit_alerts_layers_user_now" method="post"  id="form_admin" enctype="multipart/form-data">

    <input type="hidden" id="id_alert_layers" name="id_alert_layers" value="<?= $id_alert_layers ?>" />
    <input type="hidden" id="id_alert_layers_users" name="id_alert_layers_users" value="<?= $id_alert_layers_users ?>" />

    <div class="form-group">
			<label for="name">User:</label>
			<select class="form-control" name="id_user" id='id_user'>
				<option value='#'>Select user</option>
        <?php foreach ($users as $info):

          if($info['id'] == $id_user){ ?>
              <option selected value='<?=$info['id']?>'><?=$info['name']?></option>
          <?php }
          else{ ?>
              <option value='<?=$info['id']?>'><?=$info['name']?></option>
        <?php }
        endforeach; ?>
			</select>
		</div>

    <button type="submit" name="submit" class="btn btn-default" value="submit">Submit</button>
    <a href="<?php echo base_url();?>alert/alert_layers_users/<?= $id_alert_layers ?>" class="btn btn-default" style="color: #333 !important;">Back</a>
  </form>

  </div><!-- end of class form_area -->
</div><!-- end of class container  -->


<script type="text/javascript">

$(document).ready(function(){

	$("#form_admin").validate({
			rules: {
				id_user: "number"
			},
			messages: {
				id_user: "Please Select a User"
			},
			submitHandler: function (form) {
				window.scrollTo(0,0);
				$("#result").html('<span class="wait">Please wait ...</span>');

        $(form).ajaxSubmit({
					target: "#result"
				});
      }
		});

});

</script>
<?php
	require_once('footer.php');
?>
