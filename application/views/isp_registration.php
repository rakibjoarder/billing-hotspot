<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Nib CRM</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="<?=base_url()?>assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="<?=base_url()?>assets/css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
</head>

<!--   Core JS Files   -->
<body>
  <nav class="navbar navbar-primary navbar-transparent navbar-absolute">
    <div class="container">
      <div class="navbar-header">
        <!-- <a class="navbar-brand" href="/#/dashboard"></a> -->
        <h2 style="color:#343434;" class="text-center"></h2>
      </div>
    </div>
  </nav>
  <div style="background-image: url('<?=base_url(); ?>assets/img/bill.jpg');" class="wrapper wrapper-full-page">
    <div style="background-image: url('<?=base_url(); ?>assets/img/bill.jpg');"class=" register-page" filter-color="black" >
      <div class="container">
        <div class="row">
          <div  id="reg_form"class="col-md-8 col-md-offset-2">
            <div class="card card-signup">
              <div  id="reg_form" class=" ">
                <img class ="col-md-offset-4 "style="height:65%;width:30%;"src="/assets/img/2.jpg"/>
                <h3  class="card-title text-center">Isp Registration</h3>
              </div>

              <div class="row">
                <div class="col-md-offset-1 col-md-10">
                  <form  method="post"  id="form_admin" enctype="multipart/form-data">
                    <div class="card-content">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">data_usage</i>
                        </span>
                        <input type="text" class="form-control" placeholder="Isp Name..." id="isp_name" required="true"  name="isp_name">
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">location_city</i>
                        </span>
                        <input type="text" class="form-control" placeholder="Isp Address..." id="address" ="true"  name="address">
                      </div>

                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">face</i>
                        </span>
                        <input type="text" class="form-control" placeholder="Name..." id="name" ="true"  name="name">
                      </div>

                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">perm_identity</i>
                        </span>
                        <input type="text" class="form-control" placeholder="User Name..." id="username" ="true"  name="username">
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">email</i>
                        </span>
                        <input type="email" class="form-control" placeholder="Email..." id="email" required="true"  name="email">
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">stay_primary_portrait</i>
                        </span>
                        <input type="text" class="form-control" placeholder="Phone Number..." id="phone_number" required  name="phone_number" number="true">
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">lock_outline</i>
                        </span>
                        <input type="password" class="form-control" placeholder="Password..." id="pwd" required="true"  name="pwd" minLength="6">
                      </div>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons">lock_outline</i>
                        </span>
                        <input type="password" placeholder="Confirm Password..." class="form-control"  id="confirm_pwd" required="true"  name="confirm_pwd" equalTo="#pwd"   />
                      </div>

                    </div>
                    <div class="footer text-center">
                      <button type="submit" name="submit" class="btn btn-round btn-info" value="submit">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
              <!-- <div  id="reg_form" class="col-md-offset-2"> -->
                <div hidden id ="warningmsg" class="alert alert-danger col-md-6 col-md-offset-3">
                  <strong class="col-md-10 col-md-offset-1"><P style="color:white;" id="message"></P></strong>
                </div>
              <!-- </div> -->
            </div>
          </div>

          <div  hidden id="succes_form"  class="col-md-6 col-md-offset-3">
            <div  class="card card-pricing card-raised">
              <div class="content">
                <h3 >You have Successfully Registered !!!</h3>
                <div class="icon icon-danger">
                  <i class="material-icons">done</i>
                </div>
                <h3 class="card-title " id="msg"></h3>
                <a href="http://www.lightcubetech.com/" class="btn btn-info btn-round">Okay</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container">
          <p class="copyright text-center">
            Copyright © 2018 LightCube Technology
          </p>
        </div>
      </footer>
    </div>
  </div>
</body>

<script src="<?=base_url()?>assets/js/core/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/core/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/core/material.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/core/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="<?=base_url()?>assets/js/core/jquery.validate.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="<?=base_url()?>assets/js/plugins/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="<?=base_url()?>assets/js/plugins/chartist.min.js"></script>
<!--  Plugin for the Wizard -->
<script src="<?=base_url()?>assets/js/plugins/jquery.bootstrap-wizard.js"></script>
<!--  Notifications Plugin    -->
<script src="<?=base_url()?>assets/js/plugins/bootstrap-notify.js"></script>
<!-- DateTimePicker Plugin -->
<script src="<?=base_url()?>assets/js/plugins/bootstrap-datetimepicker.js"></script>
<!-- Vector Map plugin -->
<script src="<?=base_url()?>assets/js/plugins/jquery-jvectormap.js"></script>
<!-- Sliders Plugin -->
<script src="<?=base_url()?>assets/js/plugins/nouislider.min.js"></script>
<!-- Select Plugin -->
<script src="<?=base_url()?>assets/js/plugins/jquery.select-bootstrap.js"></script>
<!--  DataTables.net Plugin    -->
<script src="<?=base_url()?>assets/js/plugins/jquery.datatables.js"></script>
<!-- Sweet Alert 2 plugin -->
<script src="<?=base_url()?>assets/js/plugins/sweetalert2.min.js"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="<?=base_url()?>assets/js/plugins/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin    -->
<script src="<?=base_url()?>assets/js/plugins/fullcalendar.min.js"></script>
<!-- TagsInput Plugin -->
<script src="<?=base_url()?>assets/js/plugins/jquery.tagsinput.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="<?=base_url()?>assets/js/material-dashboard-angular.js"></script>
<!-- Material Dashboard Init Off Canvas Menu -->
<script src="<?=base_url()?>assets/js/init/initMenu.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="<?=base_url()?>assets/js/demo.js"></script>
<!-- jquery mask -->
<script type="text/javascript" src="<?=base_url()?>assets/js/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>
<script type="text/javascript">
    $().ready(function() {
        demo.checkFullPageBackgroundImage();

        setTimeout(function() {
            // after 1000 ms we add the class animated to the login/register card
            $('.card').removeClass('card-hidden');
        }, 700)
    });
</script>


<script type="text/javascript">

$(document).ready(function(){

  $('#phone_number').mask('ASY00000000', {'translation': {
    A: {pattern: /[0]/},
    S: {pattern: /[1]/},
    Y: {pattern: /[5-9]/}
  }
});


function remove_message(){
    setTimeout(function() {
      // after 1000 ms we add the class animated to the login/register card
      $("#message").text('');
      $("#warningmsg").hide(500);
    }, 1500)
  }


  $("#form_admin").validate({

    submitHandler: function (form) {
      var reqData = $("#form_admin").serialize();

      $.ajax({
        type:"POST",
        contentType: "application/x-www-form-urlencoded",
        dataType:"json",
        data: reqData,
        url: "<?=base_url()?>registration/isp_registration_now",

        success:function(response) {
          if(response.status === 'success') {
            $('#msg').html(response.msg);
            $('#reg_form').hide(500);
            $('#succes_form').show(500);
            $.ajax({
              type:"Post",
              contentType: "application/x-www-form-urlencoded",
              dataType:"json",
              data: reqData,
              url: "<?=base_url()?>email/send_isp_regisration_email"
            });
          } else if(response.status === 'failed') {
            $('#warningmsg').show(500);
            $('#message ').html(response.msg);
            remove_message();
          } else if(response.status === 'warning') {
            $('#warningmsg').show(500);
            $('#message ').html(response.msg);
            remove_message();
          }
        },
        error: function (result) {
        }
      });
    }
  });
});

</script>

</html>
