<?php

require_once ("../conexion/conexion.php");
$cnn = new conexion();
$conn = $cnn->conectar();

if (isset($_REQUEST['idUsuario'])) {
    try {

        $idUsuario = $_REQUEST['idUsuario'];
        $Mensaje = $_REQUEST['Mensaje'];
        $URLimage = $_REQUEST['URLimage'];
        $Fecha = $_REQUEST['Fecha'];
        $EstadoVisto = $_REQUEST['EstadoVisto'];

        $Guardado = 0;
        $error = "";
        $query="";

        //verifica si ya exisate el usuario para guardar o actualizar
        $sql = "select * from appmensajeUsuario where idUsuario = $idUsuario";
        $resUser = mysqli_query($conn, $sql);
        if (mysqli_num_rows($resUser) > 0) {
            
            $query = "update appmensajeUsuario set Mensaje = '$Mensaje', URLimage = '$URLimage', Fecha = '$Fecha', EstadoVisto = '$EstadoVisto')";
            
        } else {

            $query = "INSERT INTO  appmensajeUsuario()"
                    . " VALUES ('null','$idUsuario', '$Mensaje','$URLimage','$Fecha','$EstadoVisto')";
        }

        $resultado = mysqli_query($conn, $query);

        $error = $error . mysqli_error($conn);

        if ($resultado) {

            $Guardado = 1;
            $arrayName = array('Guardado' => $Guardado, 'error' => "$error");
            echo json_encode($arrayName);
        }
    } catch (Exception $e) {
        $arrayName = array('Guardado' => 0, 'error' => "error: " . $e);
        echo json_encode($arrayName);
    }
} else {
    $arrayName = array('Guardado' => 0, 'error' => "no parametros ");
    echo json_encode($arrayName);
}
?>
