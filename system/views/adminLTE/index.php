<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this -> information -> title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
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

  <link rel="stylesheet" href="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/sweetalert/sweetalert.css">

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
<body class="hold-transition skin-purple-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <layout name="logo"/>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <layout name="headerMenu"/>
    </nav>

  </header>

  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <layout name="userInfo"/>
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <layout name="menu"/>
    </section>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <layout name="contentTitle"/>
      
      <!--
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
      -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    
      <layout name="content"/>
    

    </section>
    <!-- /.content -->
    <div style="clear:both;"></div>
  </div>
  
  <layout name="footer"/>
  <layout name="controlPanel"/>

</div>
<!-- ./wrapper -->




<!-- Bootstrap 3.3.6 -->
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="js/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="js/adminLTE/app.min.js"></script>

<script src="js/plugins/jQueryUI/jquery-ui.min.js"></script>

<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/sweetalert/sweetalert-dev.js"></script>

<script src="js/cs_widget.js"></script>
</body>
</html>
