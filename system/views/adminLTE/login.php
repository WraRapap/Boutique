<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this -> information -> title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="css/ionicons-2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/AdminLTE/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="css/AdminLTE/skins/_all-skins.min.css">

  <link rel="stylesheet" href="js/plugins/iCheck/all.css">

  <link rel="stylesheet" href="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/animsition/css/animsition.min.css">

  <!-- Develop Define CSS -->
  <link rel="stylesheet" href="css/define.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- jQuery 2.2.3 -->
  <script src="js/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body class="hold-transition login-page">

<div class="login-container">
<div class="login-box">
  <div class="login-logo">
    <b><?php echo $this -> information -> title; ?></b>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg" style="display:none;"><b style="color:red;">帳號 或 密碼 錯誤</b></p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="account" placeholder="帳號">
        <span class="fa fa-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="密碼">
        <span class="fa fa-lock form-control-feedback"></span>
      </div>
      
      <div class="row">
        <!--
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        -->
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="button" class="login btn btn-primary btn-block btn-flat">登入</button>
        </div>
        <!-- /.col -->
      </div>
      
    </form>
<!--
    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    <!-- /.social-auth-links -->
<!--
    <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>
-->
  </div>
  <!-- /.login-box-body -->
</div>
</div>
<!-- /.login-box -->


<!-- Bootstrap 3.3.6 -->
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/plugins/iCheck/icheck.min.js"></script>

<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/jquery-ui-1.12.1/jquery-ui.js" type="text/javascript"></script>


<script>
  $("document").ready(function(){

    /*
    $("body").animsition({
        // inClass: 'fade-in-up-lg',
        outClass: 'fade-out-up-lg',
        outDuration: 800,
        onLoadEvent: true,
        loading: true,
        loadingParentElement: 'body', //animsition wrapper element
        loadingClass: 'animsition-loading',
        overlayClass : 'animsition-overlay-slide',
        overlayParentElement : 'body',
    });
    */
    $("input[name='password']").keyup(function(event){
      var code = event.which; // recommended to use e.which, it's normalized across browsers
      if(code==13){
        event.preventDefault();
        $(".login").click();
      }
    });
    $(".login").click(function(){
    
      var account = $("input[name='account']").val();
      var password = $("input[name='password']").val();
      $.post("<?php echo $this -> config_env -> baseUrl; ?>/index.php/<?php echo $this -> route; ?>/doLogin",{"account":account, "password":password},function(data){
        var obj = JSON.parse(data);
        if(obj.result == "yes"){
            $(".login-container").slideUp();
            setTimeout(function(){
              location.href="<?php echo $this -> config_env -> baseUrl; ?>/index.php/<?php echo $this -> route; ?>/page";
            },1000);
            
        }
        else{
          $(".login-container").effect( "shake" );
          $(".login-box-msg").show();
        }
        
      });
    });
    
  });
</script>
</body>
</html>
