<?php
session_start();

$Servidor = 'http://' . $_SERVER['HTTP_HOST'] . "/admin/"; //$_SERVER['PHP_SELF'] . "/admin/";// obtiene la ruta del DNS : http://grupovalor.com.ni/

if (!$_SESSION["sUserId"]) {
    //header("Location: " . $Servidor . "login/index.php");
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
                            <div class="input-group">
                                <label for="txtw" class="input-group-addon label-info">Width:</label>
                                <input id="txtw" class="form-control" type="number">
                            </div>
                            <div class="input-group">
                                <label for="txth" class="input-group-addon label-info">Height:</label>
                                <input id="txth" class="form-control" type="number">
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="button" value="Calcular Radio" class="btn btn-success" onclick="ratio(document.getElementById('txtw').value, document.getElementById('txth').value);"/>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="input-group">
                                <label for="gcd" class="input-group-addon label-info">GCD:</label>
                                <input id="gcd" class="form-control" type="text">
                            </div>
                            <div class="input-group">
                                <label for="ratio" class="input-group-addon label-info">RATIO:</label>
                                <input id="ratio" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <p>Cual es el nombre de tu navegador?</p>
                            <button onclick="myFunction()">Quien soy?</button>
                            <p id="demo"></p>
                            <script>

                                function myFunction() {
                                    if ((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1)
                                    {
                                        alert('Opera');
                                    } else if (navigator.userAgent.indexOf("Chrome") != -1)
                                    {
                                        alert('Chrome');
                                    } else if (navigator.userAgent.indexOf("Safari") != -1)
                                    {
                                        alert('Safari');
                                    } else if (navigator.userAgent.indexOf("Firefox") != -1)
                                    {
                                        alert('Firefox');
                                    } else if ((navigator.userAgent.indexOf("MSIE") != -1) || (!!document.documentMode == true)) //IF IE > 10
                                    {
                                        alert('IE');
                                    } else
                                    {
                                        alert('unknown');
                                    }
                                }
                            </script>

                        </div>
                        
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

<script>

    function gcd(a, b) {
        if (b > a) {
            var temp = a;
            a = b;
            b = temp
        }
        while (b != 0) {
            var m = a % b;
            a = b;
            b = m;
        }
        return a;
    }

    /* ratio is to get the gcd and divide each component by the gcd, then return a string with the typical colon-separated value */
    function ratio(x, y) {
        var c = gcd(x, y);
        document.getElementById("gcd").value = c;
        document.getElementById("ratio").value = "" + (x / c) + ":" + (y / c);
        //return "" + (x / c) + ":" + (y / c)
    }

</script>
</body>
</html>
