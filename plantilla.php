<?php
session_start();

$Servidor = 'http://' . $_SERVER['HTTP_HOST'] . "/admin/"; //$_SERVER['PHP_SELF'] . "/admin/";// obtiene la ruta del DNS : http://grupovalor.com.ni/

if (!$_SESSION["sUserId"]) {
    $_SESSION["sRedireccionar"] = $_SERVER["SCRIPT_NAME"];
    header("Location: " . $Servidor . "login/index.php");
}
require_once("conexion/conexion.php");

//Se crea nuevo objeto de la clase conexion
$cnn = new conexion();
$conn = $cnn->conectar();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" type="image/png" href="<?php echo "$Servidor"; ?>favicon.png">	
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo "$Servidor"; ?>bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo "$Servidor"; ?>dist/css/AdminLTE.css">
        <link rel="stylesheet" href="<?php echo "$Servidor"; ?>dist/css/skins/_all-skins.min.css">

        <title></title>

        <style type="text/css">
            .modal.and.carousel {
                position: fixed; // Needed because the carousel overrides the position property
            }
        </style>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="<?php echo "$Servidor"; ?>index.php" class="logo">
                    <span class="logo-mini"><b>G</b>V</span>
                    <span class="logo-lg"><b>Grupo</b>Valor sa.</span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo $Servidor . $_SESSION['simgFotoPerfilUrl']; ?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $_SESSION["sUserName"]; ?></p>
                            <a href="<?php echo "$Servidor"; ?>login/logout.php"><i class="fa fa-circle text-success"></i> Cerrar Sesión</a>
                        </div>
                    </div>

                    <ul class="sidebar-menu">
                        <li class="header">Panel de navegación</li>
                        <li class="active treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Sitios</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="active"><a href="index.php"><i class="fa fa-circle-o"></i> Panel de control.</a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Supervisión<small>Panel de Control</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo "$Servidor"; ?>index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Panel de control</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">

                            <div class="container">

                                <ul class="nav nav-pills nav-stacked">
                                    <li><a href="#lightbox" data-toggle="modal">Open Lightbox</a></li>
                                    <li><a href="#lightbox" data-toggle="modal" data-slide-to="1">2nd Image</a></li>
                                    <li><a href="#lightbox" data-toggle="modal" data-slide-to="2">3rd Image</a></li>
                                    <li><a href="#lightbox" data-toggle="modal" data-slide-to="15">Open non existing Image</a></li>
                                </ul>

                                <div class="modal fade and carousel slide" id="lightbox">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <ol class="carousel-indicators">
                                                    <li data-target="#lightbox" data-slide-to="0" class="active"></li>
                                                    <li data-target="#lightbox" data-slide-to="1"></li>
                                                    <li data-target="#lightbox" data-slide-to="2"></li>
                                                </ol>
                                                <div class="carousel-inner">
                                                    <div class="item active">
                                                        <img src="http://placehold.it/900x500/777/" alt="First slide">
                                                    </div>
                                                    <div class="item">
                                                        <img src="http://placehold.it/900x500/666/" alt="Second slide">
                                                    </div>
                                                    <div class="item">
                                                        <img src="http://placehold.it/900x500/555/" alt="Third slide">
                                                        <div class="carousel-caption"><p>even with captions...</p></div>
                                                    </div>
                                                </div><!-- /.carousel-inner -->
                                                <a class="left carousel-control" href="#lightbox" role="button" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                </a>
                                                <a class="right carousel-control" href="#lightbox" role="button" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                </a>
                                            </div><!-- /.modal-body -->
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                            </div><!-- /.container -->

                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12">

                        </div>
                    </div> <!-- /.row (main row) -->
                </section> <!-- /.content -->
            </div> <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>.</b>
                </div>
                <strong>&copy; 2017 <a href="<?php echo "$Servidor"; ?>index.php">Grupo Valor sa.</a>.</strong> Todos los derechos reservados.
            </footer>
        </div>
        <!-- ./wrapper -->

        <script src="<?php echo "$Servidor"; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <script src="<?php echo "$Servidor"; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo "$Servidor"; ?>dist/js/app.min.js"></script>

    </body>
</html>
