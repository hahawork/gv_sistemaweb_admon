<?php
session_start();

if (!$_SESSION["sUserId"]) {
    header('Location: login/index.php');
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
        <link rel="icon" type="image/png" href="favicon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="dist/css/AdminLTE.css">
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

        <title></title>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="index.php" class="logo">
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
                            <img src="<?php echo $_SESSION['simgFotoPerfilUrl']; ?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $_SESSION["sUserName"]; ?></p>
                            <a href="login/logout.php"><i class="fa fa-circle text-success"></i> Cerrar Sesión</a>
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
                                <li><a href="pages/verpdv.php"><i class="fa fa-map"></i> Puntos de venta gráfico</a></li>
                                <li><a href="pages/verpdvlist.php"><i class="fa fa-map-pin"></i> Puntos de venta listado</a></li>
                                <li><a href="tables/asistencia-imprimir.php"><i class="fa fa-user"></i> Asistencia de las supervisoras</a></li>
                                <li><a href="tables/asistenciaoficinaimprimir.php"><i class="fa fa-certificate"></i> Asistencia de Oficinas</a></li>
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
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Panel de control</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <style type="text/css">
                                td{
                                    width: 200px;
                                    align-content: center;
                                }
                                tr{
                                    margin: 10px 0px 10px 0px;

                                }
                            </style>
                            <table>
                                <tr><td>IdAsistencia</td><td><input type="number" id="txtidasistencia" name="IdAsistencia"></td></tr>
                                <tr><td>Usuario</td>
                                    <td>
                                        <input type="text" id="txtusuario" required name="example" class="form-control" style="" list="listUser">
                                        <datalist id="listUser">
                                            <?php
                                            require_once("conexion/conexion.php");
                                            $cnn = new conexion();
                                            $conn = $cnn->conectar();

                                            $sql = "SELECT idUsuario, NombreUsuario FROM usuario";
                                            $result = mysqli_query($conn, $sql);
                                            while ($row2 = mysqli_fetch_array($result)) {
                                                echo "<option value='" . utf8_encode($row2['idUsuario']) . "'>" . $row2['NombreUsuario'] . "</option>";
                                            }
                                            ?>
                                        </datalist>
                                    </td>
                                </tr>
                                <tr><td>Punto de Venta</td>
                                    <td>
                                        <input type="text" id="txtpdv" required name="example" class="form-control" style="" list="listProducto">
                                        <datalist id="listProducto">
                                            <?php
                                            require_once("conexion/conexion.php");
                                            $cnn = new conexion();
                                            $conn = $cnn->conectar();

                                            $sql = "SELECT NombrePdV, IdPdV FROM puntosdeventa";
                                            $result = mysqli_query($conn, $sql);
                                            while ($row2 = mysqli_fetch_array($result)) {
                                                echo "<option value='" . utf8_encode($row2['IdPdV']) . "'>" . $row2['NombrePdV'] . "</option>";
                                            }
                                            ?>
                                        </datalist>
                                    </td>
                                </tr>
                                <tr><td>Bus</td><td><input id="txtbus" type="number" name="bus"></td></tr>
                                <tr><td>Taxi</td><td><input id="txttaxi" value="0.00" type="number" name="taxi"></td></tr>
                                <tr><td>Alimento</td><td><input id="txtalimento" value="0.00" type="number" name="alimento"></td></tr>
                                <tr><td>Hospedaje</td><td><input id="txthospedaje" value="0.00" type="number" name="hosdpedaje"></td></tr>
                                <tr><td>Otros</td><td><input id="txtotros" value="0.00" type="number" name="otros"></td></tr>
                                <tr><td>Fecha</td><td><input id="txtfecha" value="" type="datetime" name="fecha"></td></tr>
                                <tr><td>Comentario</td><td><input type="text" id="txtcomentario"  name="comentario"></tr>
                                <tr><td>Km Actual</td><td><input type="number" id="txtkmactual"  name="txtkmactual"></tr>
                                <tr><input type="button" id="insertar" class="btn btn-primary" onclick="Insertar()" name="insertar" value="insertar"></tr>
                            </table>
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
                <strong>&copy; 2017 <a href="#">Grupo Valor sa.</a>.</strong> Todos los derechos reservados.
            </footer>
        </div>
        <!-- ./wrapper -->

        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script>
                                    $.widget.bridge('uibutton', $.ui.button);
        </script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="dist/js/app.min.js"></script>

        <script type="text/javascript">
                                    function Insertar() {

                                        console.log("entrando");


                                        var idAsistencia = document.getElementById("txtidasistencia").value;
                                        var idUsuario = document.getElementById("txtusuario").value;
                                        var IdPdV = document.getElementById("txtpdv").value;
                                        var CantGastosMovim = document.getElementById("txtbus").value;
                                        var CantGastosMovimTaxi = document.getElementById("txttaxi").value;
                                        var CantGastosAlim = document.getElementById("txtalimento").value;
                                        var CantGastosHosped = document.getElementById("txthospedaje").value;
                                        var CantGastosVario = document.getElementById("txtotros").value;
                                        var FechaRegistro = document.getElementById("txtfecha").value;
                                        var Observacion = document.getElementById("txtcomentario").value;
                                        var kmactual = document.getElementById("txtkmactual").value;

                                        $.ajax({
                                            type: 'post',
                                            dataType: 'json',
                                            cache: false,
                                            data: {IdAsistencia: idAsistencia, 
                                                idUsuario: idUsuario,
                                                IdPdV: IdPdV, 
                                                CantGastosMovim: CantGastosMovim,
                                                CantGastosMovimTaxi: CantGastosMovimTaxi, 
                                                CantGastosAlim: CantGastosAlim,
                                                CantGastosAlim: CantGastosAlim, 
                                                CantGastosHosped: CantGastosHosped,
                                                CantGastosVario: CantGastosVario, 
                                                FechaRegistro: FechaRegistro,
                                                Observacion: Observacion, 
                                                kmactual: kmactual},
                                            url: 'aphp.php',
                                            
                                            beforeSend: function () {
                                                console.log("enviando");
                                            },
                                            success: function (res)
                                            {
                                                console.log("enviado");
                                                if (res.Guardado == 1) {
                                                    console.log("Guardado");
                                                    document.getElementById("txtpdv").value = "";
                                                    document.getElementById("txtbus").value = "";
                                                    document.getElementById("txtcomentario").value = "";
                                                    //document.getElementById("txtfecha").value = "";

                                                    console.log("error: " + res.error);

                                                } else {
                                                    console.log("no Guardado" + res.Guardado);

                                                    console.log("error: " + res.error);
                                                }

                                            }
                                        });
                                        console.log("fin");
                                    }
        </script>
    </body>
</html>
