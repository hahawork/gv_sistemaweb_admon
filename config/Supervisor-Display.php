<?php
session_start();

$Servidor = 'http://' . $_SERVER['HTTP_HOST'] . "/admin/"; //$_SERVER['PHP_SELF'] . "/admin/";// obtiene la ruta del DNS : http://grupovalor.com.ni/

if (!$_SESSION["sUserId"]) {
    $_SESSION["sRedireccionar"] = $_SERVER["SCRIPT_NAME"];
    header('Location: ../login/index.php');
    exit;
}

$idUsuario = $_SESSION["sIdUsuario"];
$NivelAcceso = $_SESSION["sNivelAcceso"];
$idCliente = $_SESSION["sIdCliente"];

$admin = 'disabled';


if ($NivelAcceso == 1) {
    $admin = '';
}

require_once("../conexion/conexion.php");
//Se crea nuevo objeto de la clase conexion
$cnn = new conexion();
$conn = $cnn->conectar();

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
    $y[] = $row2["LabelPin"];
    $y[] = $row2["idUsuario"];
    $z[$indice] = $y;
    $indice++;

    if ($row2["IdPdV"] == 27) {
        $VariarLong = $VariarLong - 0.00003;
    }
}

function setValue($value) {

    return $value;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" type="image/png" href="../favicon.png">
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link href="../dist/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
        <link href="../dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css"/>


        <title>Simple Polylines</title>
    </head>
    <body class="hold-transition skin-blue">

        <!-- Main content -->
        <section class="content">
            <div class="row">                       

                <div class="col-sm-12 col-md-4">
                    <div class="box box-default" >
                        <div class="box-header with-border">
                            <h3 class="box-title">Usuarios</h3>
                            <div class="box-tools pull-right"> 
                                <button type="button" class="btn btn-box-tool" onclick="window.location.reload();"><i class="fa fa-refresh"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body" style="overflow: auto; height: 768px;">

                            <select id="usuarios" name="usuario" multiple="multiple" style="height: 100%;">
                                <?php
                                $sqlUsuario = "SELECT * FROM `usuario` ORDER BY `usuario`.`NombreUsuario` ASC";
                                $ResultUsuario = mysqli_query($conn, $sqlUsuario);
                                if ($ResultUsuario && mysqli_num_rows($ResultUsuario)) {
                                    while ($row = mysqli_fetch_array($ResultUsuario)):
                                        ?>
                                        <option onclick="moveToLocation(<?php echo $row["idUsuario"]; ?>,<?php echo $row["idUsuario"]; ?>);" value="<?php echo $row["idUsuario"]; ?>"><?php echo utf8_encode($row["idUsuario"] . " - " . $row["NombreUsuario"]); ?></option>
                                        <?php
                                    endwhile;
                                }
                                ?>
                            </select>


                        </div>
                    </div>

                </div>
                <div class="col-md-8 col-xs-12">

                    <div class="box box-default" >
                        <div class="box-header with-border">
                            <h3 class="box-title">Recorrido</h3>
                            <div class="box-tools pull-right"> 
                                <button type="button" class="btn btn-box-tool" onclick="window.location.reload();"><i class="fa fa-refresh"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body" style="height: 768px;">
                            <div id="map" style="width:100%;height:100%;" ></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->

        </section>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>.</b>
            </div>
            <strong>&copy; 2017 <a href="#">Grupo Valor sa.</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>
    <!-- ./wrapper -->

    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
                                    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../dist/js/app.min.js"></script>

    <script>

                                    // This example creates a 2-pixel-wide red polyline showing the path of William
                                    // Kingsford Smith's first trans-Pacific flight between Oakland, CA, and
                                    // Brisbane, Australia.

                                    var map;
                                    function initMap() {

                                        var latlng = new google.maps.LatLng(12.454554, -86.453544);

                                        map = new google.maps.Map(document.getElementById('map'), {
                                            zoom: 3,
                                            center: latlng,
                                            mapTypeId: 'terrain'
                                        });

                                        var flightPlanCoordinates = [
                                            {lat: 37.772, lng: -122.214},
                                            {lat: 21.291, lng: -157.821},
                                            {lat: -18.142, lng: 178.431},
                                            {lat: -27.467, lng: 153.027}
                                        ];

                                        var flightPath = new google.maps.Polyline({
                                            path: flightPlanCoordinates,
                                            geodesic: true,
                                            strokeColor: '#FF0000',
                                            strokeOpacity: 1.0,
                                            strokeWeight: 2
                                        });

                                        flightPath.setMap(map);
                                    }
                                    function moveToLocation(lat, lng) {
                                        var center = new google.maps.LatLng(lat, lng);
                                        map.panTo(center);
                                        
                                        $.ajax({
                                            type: 'post',
                                            dataType: 'json',
                                            cache: 'false',
                                            data: {IdUsuario: IdUsuario},
                                            url: 'Supervisor-Display_get_ruta_diaria.php',
                                            beforeSend: function () {
                                                console.log("Enviando....");
                                            },
                                            success: function (res) {
                                                if (res.success == 1) {                                                   
                                                    console.log("guardado. " + res.error);                                                    
                                                } else {
                                                    console.log("Fallo al guardar. " + res.error);
                                                }
                                            },
                                            error: function (res) {},
                                            always: function (res) {}
                                        });
                                    }
    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeAPkXd6qY2mg2QJ0bdMWbqO7Wjgs3diM&callback=initMap">
    </script>

</body>
</html>