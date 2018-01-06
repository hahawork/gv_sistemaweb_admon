<?php
session_start();

$Servidor = 'http://' . $_SERVER['HTTP_HOST'] . "/admin/"; //$_SERVER['PHP_SELF'] . "/admin/";// obtiene la ruta del DNS : http://grupovalor.com.ni/

require_once ("funciones_varias.php");
require_once("conexion/conexion.php");
//Se crea nuevo objeto de la clase conexion
$cnn = new conexion();
$conn = $cnn->conectar();

$idUsuario = $_SESSION["sIdUsuario"];
$NivelAcceso = $_SESSION["sNivelAcceso"];
$idCliente = $_SESSION["sIdCliente"];


if (!$_SESSION["sUserId"]) {
    $_SESSION["sRedireccionar"] = $_SERVER["SCRIPT_NAME"];
    header('Location: login/index.php');
    exit;
}

$admin = 'disabled';


if ($NivelAcceso == 1) {
    $admin = '';
    echo "<a href='" . $Servidor . "pages/posicion-supervisores.php'><i class='fa fa-map'></i> Ultima Ubicación Supervisiones</a>";
}

$mapzoom = 8;

//$sql = mysqli_query($conn, "CREATE TEMPORARY TABLE nombreTablaTemporal SELECT usuario.idUsuario,usuario.NumTelefono, usuario.NombreUsuario, puntosdeventa.IdPdV, puntosdeventa.NombrePdV, FechaRegistro, Observacion, puntosdeventa.LocationGPS, usuario.LabelPin, usuario.IdCliente FROM `usuario_asistencia` INNER JOIN usuario on usuario_asistencia.idUsuario = usuario.idUsuario INNER JOIN puntosdeventa on puntosdeventa.IdPdV = usuario_asistencia.IdPdV WHERE usuario.EstadoActivo = 1 ORDER BY FechaRegistro DESC");

$consulta = ""; // "SELECT * FROM nombreTablaTemporal WHERE idUsuario NOT IN (6,20) and IdCliente = '$idCliente' GROUP BY idUsuario ORDER BY FechaRegistro DESC";

mysqli_query($conn, "CREATE TEMPORARY TABLE nombreTablaTemporal SELECT usuario.idUsuario,usuario.NumTelefono, usuario.NombreUsuario, puntosdeventa.IdPdV, puntosdeventa.NombrePdV, FechaRegistro, Observacion, puntosdeventa.LocationGPS, usuario.LabelPin, usuario.IdCliente FROM `usuario_asistencia` INNER JOIN usuario on usuario_asistencia.idUsuario = usuario.idUsuario INNER JOIN puntosdeventa on puntosdeventa.IdPdV = usuario_asistencia.IdPdV  WHERE usuario.EstadoActivo = 1 ORDER BY FechaRegistro DESC;");
if ($NivelAcceso == 1) {

    $consulta = "SELECT * FROM nombreTablaTemporal GROUP BY idUsuario ORDER BY FechaRegistro DESC;";
}

if ($NivelAcceso == 3) {

    $consulta = "SELECT * FROM nombreTablaTemporal WHERE IdCliente = $idCliente GROUP BY idUsuario ORDER BY FechaRegistro DESC;";
}

if ($NivelAcceso == 4) {

    $consulta = "SELECT * FROM nombreTablaTemporal WHERE idUsuario = $idUsuario GROUP BY idUsuario ORDER BY FechaRegistro DESC;";
}
//$consulta = "CALL spObtenerUltimoPdVMarcadoMapa($NivelAcceso, $idCliente, $idUsuario)";

$sql2 = mysqli_query($conn, $consulta) or die(mysqli_error($conn));

$z = array();
$indice = 0;

$VariarLong = 0.00006;

while ($row2 = mysqli_fetch_array($sql2)) {
    $y = array();

    $coordenada = $row2["LocationGPS"];
    $myArray = explode(',', $coordenada);

    if ($row2["IdPdV"] == 29) {
        $coordenadaepe = explode(":", $row2["Observacion"]);
        $myArray = explode(",", $coordenadaepe[1]);
    }

    $y[] = utf8_encode($row2["NombreUsuario"]);
    $y[] = $myArray[0];
    $y[] = $myArray[1] + $VariarLong;
    $y[] = $row2["FechaRegistro"];
    $y[] = utf8_encode($row2["NombrePdV"]);
    $y[] = $mapzoom;
    $y[] = $row2["LabelPin"];
    $y[] = $row2["idUsuario"];
    $z[$indice] = $y;
    $indice++;

    if ($row2["IdPdV"] == 27) {
        $VariarLong = $VariarLong - 0.00003;
    }
}

function getDateDiff($dateSave) {
    $managua = new DateTimeZone("America/Managua");
    $datetime1 = new DateTime($dateSave);
    $datetime2 = new DateTime("now", $managua);
    $difference = $datetime1->diff($datetime2);

    return $difference;

    /* echo 'Difference: '.$difference->y.' years, ' 
      .$difference->m.' months, '
      .$difference->d.' days, '
      .$difference->h.' hours, '
      .$difference->i.' minutes, '
      .$difference->s.' seconds<br>';
      print_r($difference);

      echo $datetime2 -> format("Y-m-d H:i"). ",---" . $difference ->d;//->format('%R%a días'); */
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">        
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">


        <link rel="icon" type="image/png" href="<?php echo $Servidor; ?>favicon.png">
        <link rel="stylesheet" href="<?php echo $Servidor; ?>bootstrap/css/bootstrap.min.css">        
        <link rel="stylesheet" href="<?php echo $Servidor; ?>dist/css/AdminLTE.css">
        <link rel="stylesheet" href="<?php echo $Servidor; ?>dist/css/skins/_all-skins.min.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

        <title>Supervisión Grupo Valor sa.</title>

        <!-- para tablas -->
        <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

        <script type="text/javascript" src="floatingdiv/floating-1.12.js"></script>

        <style type="text/css">
            .disabled {
                pointer-events:none; /*This makes it not clickable*/
                opacity:0.6;         /*This grays it out to look disabled*/
            }

            @media only screen and (max-width: 999px) {
                /* rules that only apply for canvases narrower than 1000px */
                .modal {
                    width: 420px;
                    height: 400px;
                    position: absolute;
                    left: 0%;
                    top: 0%; 
                    margin-top: -210px;
                }
            }

            @media only screen and (device-width: 768px) and (orientation: landscape) {
                /* rules for iPad in landscape orientation */
                .modal {
                    width: 768px;
                    height: 400px;
                    position: absolute;
                    left: 0%;
                    top: 0%; 
                    margin-top: -80px;
                }
            }

            @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
                /* iPhone, Android rules here */
                .modal {
                    width: 320px;
                    height: 400px;
                    position: absolute;
                    left: 0%;
                    top: 0%; 
                    margin-top: -50px;
                }

            }

            .wait-modal{
                display: none;
                position: fixed;
                z-index: 1;
                top: 0;
                margin: 15% auto;
                padding: 2%;
                border: 1px solid #888;
                width: 20px;
            }

            /* Modal Header */
            .modal-header {
                padding: 2px 16px;
                background-color: #3c8dbc;
                color: white;
                height: 15%;
            }

            /* Modal Body */
            .modal-body {padding: 2px 16px;}

            /* Modal Footer */
            .modal-footer {
                padding: 2px 16px;
                background-color: #3c8dbc;
                color: white;
            }

            /* Modal Content */
            .modal-content {
                background-color: #fefefe;
                position: fixed; /* se posiciona sobre los elementos*/
                z-index: 1;
                top: 0;			   
                margin: 15% auto; /* 15% from the top and centered */
                padding: 10px;
                border: 1px solid #888;
                /* Could be more or less, depending on screen size */

                display: none;
                /*position: relative;*/  /* se mete enmedio de los otros elementos*/
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
                -webkit-animation-name: animatetop;
                -webkit-animation-duration: 0.9s;
                animation-name: animatetop;
                animation-duration: 0.9s
            }

            /* Add Animation */
            @-webkit-keyframes animatetop {
                from {top: -100px; opacity: 0} 
                to {top: 0; opacity: 1}
            }

            @keyframes animatetop {
                from {top: -100px; opacity: 0}
                to {top: 0; opacity: 1}
            }

            /* The Close Button */
            .close {
                color: #333;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>

        <!-- Smartsupp Live Chat script -->
        <script type="text/javascript">
            var _smartsupp = _smartsupp || {};
            _smartsupp.key = 'af066abb7fe6a518877dcd867093b989941571d8';
            window.smartsupp || (function (d) {
                var s, c, o = smartsupp = function () {
                    o._.push(arguments)
                };
                o._ = [];
                s = d.getElementsByTagName('script')[0];
                c = d.createElement('script');
                c.type = 'text/javascript';
                c.charset = 'utf-8';
                c.async = true;
                c.src = '//www.smartsuppchat.com/loader.js?';
                s.parentNode.insertBefore(c, s);
            })(document);
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div id="floatdiv" style="  
             position:absolute;  
             width:200px;height:100px;top:10px;right:10px;  
             padding:16px;background:#FFFFFF;  
             border:2px solid #2266AA;  
             z-index:100">  
            <div>
                <p>Fecha de Vencimiento.<br><a href="#">Dar click para saber que hacer.</a></p>

            </div>

        </div>  

        <script type="text/javascript">

            var date = new Date();
            if (date.getDate() == 15 || date.getDate() == 30) {

                floatingMenu.add('floatdiv',
                        {
                            // Represents distance from left or right browser window  
                            // border depending upon property used. Only one should be  
                            // specified.  
                            // targetLeft: 0,  
                            targetRight: 10,

                            // Represents distance from top or bottom browser window  
                            // border depending upon property used. Only one should be  
                            // specified.  
                            targetTop: 50,
                            // targetBottom: 0,  

                            // Uncomment one of those if you need centering on  
                            // X- or Y- axis.  
                            // centerX: true,  
                            // centerY: true,  

                            // Remove this one if you don't want snap effect  
                            snap: true
                        });
            } else {
                document.getElementById('floatdiv').style.display = "none";
            }
        </script>  

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
                            <img src="<?php echo $Servidor . $_SESSION['simgFotoPerfilUrl']; ?>" class="img-circle" alt="User Image">
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
                                <li class="active <?php echo $admin; ?>"><a href="index.php"><i class="fa fa-circle-o"></i> Panel de control.</a></li>                                
                                <li><a href="<?php echo $Servidor; ?>pages/mis_productos.php"><i class="fa fa-cubes"></i>Mis Productos</a></li>
                                <li><a href="<?php echo $Servidor; ?>planpromocional/planpromocional.php"><i class="fa fa-certificate"></i>Plan Promocional</a></li>
                            </ul>			            
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-file-excel-o"></i> <span>Reportes</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>			          	
                            <ul class="treeview-menu">                                
                                <?php if ($NivelAcceso == 1) : ?>
                                    <li><a href="<?php echo $Servidor; ?>tables/asistenciaoficinaimprimir.php"><i class="glyphicon glyphicon-briefcase"></i> Asistencia de Oficinas</a></li>  
                                    <li><a href="<?php echo $Servidor; ?>tables/ReporteAsistenciaPersonal.php"><i class="glyphicon glyphicon-tags"></i>Asistencia de Personal Externo</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo $Servidor; ?>tables/asistencia-imprimir.php"><i class="glyphicon glyphicon-user"></i> Asistencia de las supervisoras</a></li>
                                <!-- Esto es porque el formato de fechas de vencimiento cambia por marca-->
                                <li><a href="<?php echo getURLFechasVencimiento($idCliente) ?>"><i class="glyphicon glyphicon-exclamation-sign"></i>Fechas de Vencimiento</a></li>
                                <li><a href="<?php echo getURLPrecios($idCliente); ?>"><i class="fa fa-money"></i>Reporte de Precios</a></li>
                                <li><a href="<?php echo $Servidor; ?>tables/ReporteInventarioPermanente.php"><i class="glyphicon glyphicon-tags"></i>Inventario Permanente</a></li>	                                
                            </ul>
                        </li>

                        <?php if ($NivelAcceso == 1) : ?>

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-television" aria-hidden="true"></i> <span>Monitores</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo $Servidor; ?>pages/verpdv.php"><i class="fa fa-map"></i> Puntos de venta gráfico</a></li>
                                    <li><a href="<?php echo $Servidor; ?>pages/verpdvlist.php"><i class="fa fa-map-pin"></i> Puntos de venta listado</a></li>
                                    <li><a href="<?php echo $Servidor; ?>tables/ReporteAsistenciaDiaria.php"><i class="fa fa-pie-chart"></i>Asistencia Diaria</a></li>
                                    <li><a href="<?php echo $Servidor; ?>config/Supervisor-Display_get_ruta_diaria.php"><i class="fa  fa-check-square"></i>Ver Recorrido por día</a></li>	
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cogs" aria-hidden="true"></i> <span>Configuración</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>			          	
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo $Servidor; ?>config/frmUsuarioPantallas.php"><i class="fa  fa-check-square"></i>Usuario-Formularios</a></li>	
                                    <li><a href="<?php echo $Servidor; ?>config/Formato_registro.php"><i class="glyphicon glyphicon-user"></i>Registro-Usuarios</a></li>
                                    <li><a href="<?php echo $Servidor; ?>config/respaldar_base_datos.php"><i class="fa fa-database"></i>Respaldar Base De Datos</a></li>
                                    <li><a href="<?php echo $Servidor; ?>config/Pedir_numero_celular.php"><i class="fa fa-database"></i>Pedir Número Celular de un Usuario.</a></li>
                                    <li><a href="<?php echo $Servidor; ?>config/EnviarMensajeAUsuario.php"><i class="glyphicon glyphicon-phone"></i>Enviar mensaje al usuario</a></li>
                                    
                                </ul>
                            </li>
                        <?php endif; ?>

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
                        <li><a href="#"><i class="fa fa-sitemap"></i> Home</a></li>
                        <li class="active">Panel de control</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-danger" >
                                <div class="box-header with-border">
                                    <h3 class="box-title">Supervisores</h3>
                                    <div class="box-tools pull-right">
                                        <span class="label label-danger"> Miembros Activos</span>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" style="overflow: auto;">
                                    <?php
                                    $sql = "CALL spListSupervisorasIndexMain($NivelAcceso, $idCliente, $idUsuario)";

                                    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                    if ($result) {
                                        while ($row = mysqli_fetch_array($result)) :
                                            ?>
                                            <div class="col-xs-1" style="border: 1px solid #999; min-width: 80px; display: inline-block; padding: 2px; text-align: center; overflow: hidden; text-overflow:ellipsis;">
                                                <a href="pages/asistencia-detalles.php?idSupervisor=<?php echo $row['idUsuario'] . "&NombreSupervisor=" . $row['NombreUsuario']; ?>"> 
                                                    <img class="hvr-pulse-grow" style='width: 100%; min-width: 70px; height: auto;' src="<?php echo $row['Foto_URL']; ?>"  alt='Image'>
                                                    <style type="text/css">
                                                        /* Pulse Grow */
                                                        @-webkit-keyframes hvr-pulse-grow {
                                                            to {
                                                                -webkit-transform: scale(1.1);
                                                                transform: scale(1.1);
                                                            }
                                                        }
                                                        @keyframes hvr-pulse-grow {
                                                            to {
                                                                -webkit-transform: scale(1.1);
                                                                transform: scale(1.1);
                                                            }
                                                        }
                                                        .hvr-pulse-grow {
                                                            display: inline-block;
                                                            vertical-align: middle;
                                                            -webkit-transform: perspective(1px) translateZ(0);
                                                            transform: perspective(1px) translateZ(0);
                                                            box-shadow: 0 0 1px transparent;
                                                        }
                                                        .hvr-pulse-grow:hover, .hvr-pulse-grow:focus, .hvr-pulse-grow:active {
                                                            -webkit-animation-name: hvr-pulse-grow;
                                                            animation-name: hvr-pulse-grow;
                                                            -webkit-animation-duration: 0.3s;
                                                            animation-duration: 0.3s;
                                                            -webkit-animation-timing-function: linear;
                                                            animation-timing-function: linear;
                                                            -webkit-animation-iteration-count: infinite;
                                                            animation-iteration-count: infinite;
                                                            -webkit-animation-direction: alternate;
                                                            animation-direction: alternate;
                                                        }
                                                    </style>
                                                    <span class="users-list-name"><?php echo utf8_encode($row['NombreUsuario']); ?></span>
                                                </a>
                                            </div>
                                            <?php
                                        endwhile;
                                    }
                                    ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-7 col-md-8 col-sm-12">
                            <div class="box box-default" >
                                <div class="box-header with-border">
                                    <h3 class="box-title">Última Ubicación</h3>
                                    <div class="box-tools pull-right"> 
                                        <button type="button" class="btn btn-box-tool" onclick="window.location.reload();"><i class="fa fa-refresh"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>

                                <div class="box-body" style="height: 500px;">
                                    <div id="map" style="width:100%;height:100%;" ></div>

                                    <script type="text/javascript">

                                        function myMap() {
                                            var arrDataPHP = <?php echo json_encode($z); ?>;
                                            var n = 0;
                                            var myCenter = new google.maps.LatLng(arrDataPHP[n][1], arrDataPHP[n][2]);
                                            var mapCanvas = document.getElementById("map");
                                            var mapOptions = {
                                                center: myCenter,
                                                /*mapTypeId: google.maps.MapTypeId.SATELLITE,
                                                 scrollwheel: true,*/
                                                zoom: arrDataPHP [n][5],
                                                /*heading : 90,
                                                 tilt : 45*/
                                            };
                                            var map = new google.maps.Map(mapCanvas, mapOptions);
                                            var image = {
                                                url: 'dist/pina.png',
                                                // This marker is 20 pixels wide by 32 pixels high.
                                                size: new google.maps.Size(23, 35),
                                                // The origin for this image is (0, 0).
                                                origin: new google.maps.Point(0, 0),
                                                // The anchor for this image is the base of the flagpole at (0, 32).
                                                anchor: new google.maps.Point(0, 35)
                                            };
                                            // Shapes define the clickable region of the icon. The type defines an HTML
                                            // <area> element 'poly' which traces out a polygon as a series of X,Y points.
                                            // The final coordinate closes the poly by connecting to the first coordinate.
                                            var shape = {
                                                coords: [1, 1, 1, 23, 18, 35, 18, 10],
                                                type: 'poly'
                                            };

                                            var marker1 = [];

                                            for (n = 0; n < arrDataPHP.length; n++) {
                                                marker1[n] = new google.maps.Marker({
                                                    position: new google.maps.LatLng(arrDataPHP[n][1], arrDataPHP[n][2]),
                                                    title: arrDataPHP[n][4],
                                                    map: map,
                                                    label: arrDataPHP[n][6],
                                                    draggable: false,
                                                    icon: image,
                                                    shape: shape,
                                                    id: arrDataPHP[n][7]
                                                });

                                                google.maps.event.addListener(marker1[n], 'click', (function (marker1, n) {
                                                    return function () {

                                                        modal.style.display = "block";
                                                        /*infowindow.setContent("usuario: "+arrDataPHP[n][0] + ".<br/>Fecha: " + arrDataPHP[n][3] + ".<br/>Lugar: " + arrDataPHP[n][4]);
                                                         infowindow.open(map, marker1);
                                                         modal.style.display = "block";
                                                         
                                                         document.getElementById("NombreUsuarioModal").textContent = arrDataPHP[n][0];
                                                         document.getElementById("enlaceVerCompleto").innerHTML = "<a style='color: #fff;' href='pages/asistencia-detalles.php?idSupervisor="+ arrDataPHP[n][7] + "'>Ver Todo</a>";*/

                                                        $.ajax({
                                                            type: 'post',
                                                            dataType: 'json',
                                                            cache: false,
                                                            data: {IdSupervisor: arrDataPHP[n][7], Fecha: arrDataPHP[n][3]},
                                                            url: 'modal-json.php',
                                                            success: function (res)
                                                            {
                                                                document.getElementById("NombreUsuarioModal").textContent = arrDataPHP[n][0];
                                                                document.getElementById("enlaceVerCompleto").innerHTML = "<a style='color: #fff;' href='pages/asistencia-detalles.php?idSupervisor=" + arrDataPHP[n][7] + "'>Ver Todo</a>";
                                                                //if( screen.width <= 800 ) {     
                                                                // is mobile.. 
                                                                //}else{								
                                                                document.getElementById("modalbody").innerHTML = res.mensaje;
                                                                //alert(res.mensaje);
                                                                modal.style.display = "block";
                                                                // }
                                                                // modal.style.display = "none";
                                                            }
                                                        });
                                                    }
                                                })(marker1[n], n));

                                                console.log(marker1[n].id);

                                                google.maps.event.addListener(marker1[n], 'click', function (event) {
                                                });
                                                google.maps.event.addListener(marker1[n], 'drag', function (event) {
                                                });
                                                //marker1.setMap(map);
                                            }


                                            /*var infowindow = new google.maps.InfoWindow({
                                             content: ""
                                             });
                                             infowindow.open(map,marker1);*/
                                        }
                                    </script>

                                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeAPkXd6qY2mg2QJ0bdMWbqO7Wjgs3diM&callback=myMap" async defer></script>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4 col-lg-5">

                            <div class="box box-info" >
                                <div class="box-header with-border">
                                    <h3 class="box-title">Hora entrada de Hoy</h3>                                    
                                    <div class="box-tools pull-right">  
                                        <button onclick="selectElementContents(document.getElementById('tablaAsistencia'))">Copiar</button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" style="height: 500px; overflow-y: scroll;">

                                    <table id="tablaAsistencia" class="table-condensed table-bordered table table-striped table-hover">
                                        <thead style="background: rgb(146,208,80);">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Hora</th>
                                            </tr>
                                        </thead>
                                        <tbody style="overflow: auto" id="tbodyAsistenciaDiaria">                                                                                              
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (main row) -->

                </section>

                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>.</b>
                </div>
                <strong>&copy; 2017 <a href="#">Grupo Valor sa.</a>.</strong> Todos los derechos reservados.
            </footer>
        </div>
        <!-- ./wrapper -->

        <div class=" modal modal-content col col-lg-6 col-md-6 col-xs-12" id="modal">
            <div class="vertical-align-center">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2 id="NombreUsuarioModal">Claudia Portocarrero</h2>
                    <div class="wait-modal box box-danger" id="wait-modal">
                        <div class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="box box-danger">
                        <div class="box-header with-border">

                        </div>
                        <div id="modalbody" class="box-body" style="background: #ccc; height: 200px; overflow-y: scroll;">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <h3 id="enlaceVerCompleto">Ver Completo. <?php echo $idUsuario; ?></h3>
                </div>
            </div>
        </div>

        <!-- Modal content *¨***************************************************** -->

        <script type="text/javascript">
            // Get the modal
            var modal = document.getElementById('modal');
            var modalwait = document.getElementById('wait-modal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
                modalwait.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    modalwait.style.display = "none";
                }
            }
        </script>
        <!-- ********************************************************************* -->


        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="dist/js/app.min.js"></script>


        <!-- para tablas -->
        <!-- DataTables -->
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>       
        <script type="text/javascript">

            var ultimo = 0;

            function notificacionURLImagen() {

                var notification = null;

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    url: 'NotificInformeUltmReg.php',
                    success: function (res) {
                        if (ultimo < res.maxId) {

                            ultimo = res.maxId;


                            if (!('Notification' in window)) {
                                // el navegador no soporta la API de notificaciones
                                alert('Su navegador no soporta la API de Notificaciones :(');
                                return;
                            } else if (Notification.permission === "granted") {
                                // Se puede emplear las notificaciones
                                notification = new Notification(
                                        res.NombreUsuario, {
                                            body: 'Marcó en ' + res.NombrePdV + ' el ' + res.Hora,
                                            dir: 'ltr',
                                            icon: '' + res.Foto_URL
                                        });

                                //notification.onshow = function(){setTimeout(notification.close(), 20000); };
                                notification.onclick = function () {
                                    window.focus();
                                    this.cancel();
                                };

                            } else if (Notification.permission !== 'denied') {
                                // se pregunta al usuario para emplear las notificaciones
                                Notification
                                        .requestPermission(function (permission) {
                                            if (permission === "granted") {
                                                notification = new Notification(
                                                        res.NombreUsuario, {
                                                            body: 'Marcó en ' + res.NombrePdV + ' el ' + res.Hora + ' (Esta notif. solo muestra el último registro.)',
                                                            dir: 'ltr',
                                                            icon: '' + res.Foto_URL
                                                        });
                                                notification.onclick = function () {
                                                    window.focus();
                                                    this.cancel();
                                                };
                                            }
                                        });
                            }

                        }
                    }
                });


            }

            setInterval("notificacionURLImagen()", 10000);


            function moveMarker(map, marker) {
                marker.setPosition(new google.maps.LatLng(Lat, Lng));
                map.panTo(new google.maps.LatLng(Lat, Lng));
            }
            ;
        </script>

        <script type="text/javascript">

            function selectElementContents(el) {
                var body = document.body, range, sel;
                if (document.createRange && window.getSelection) {
                    range = document.createRange();
                    sel = window.getSelection();
                    sel.removeAllRanges();
                    try {
                        range.selectNodeContents(el);
                        sel.addRange(range);
                    } catch (e) {
                        range.selectNode(el);
                        sel.addRange(range);
                    }
                } else if (body.createTextRange) {
                    range = body.createTextRange();
                    range.moveToElementText(el);
                    range = body.createTextRange();
                    range.moveToElementText(el);
                    range.select();
                }

                document.execCommand("Copy");

                alert("Se ha copiado la tabla con éxito, En excel seleccione una celda y péguelo!");
            }


            (function getAsistenciaDiaria() {

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    url: 'getAsistenciadelDia.php',
                    success: function (res) {

                        console.log(res.success);

                        if (res.success == '1') {


                            document.getElementById("tbodyAsistenciaDiaria").innerHTML = res.table;

                        } else {

                        }

                    }

                });
            })();

            setInterval("getAsistenciaDiaria()", (1000 * 60 * 10));
        </script>

    </body>
</html>
