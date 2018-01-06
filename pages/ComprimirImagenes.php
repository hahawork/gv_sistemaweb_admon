<?php

try {

    $pathApiCompress = "https://process.filestackapi.com/AhTgLagciQByzXpFGRI0Az/resize=w:700/compress=metadata:true/";
    $pathImage = $pathApiCompress . "http://www.grupovalor.com.ni/ws/";
    $ImageName = "";

    makedirs("uploads");
    
    if (isset($_POST['imagen'])) {

        $ImageName = $_POST['imagen'];

        copy($pathImage . $ImageName, $ImageName);
        $arrayNameupd = array('success' => 1, 'error' => "", 'mensaje' => "Se ha descaergado con exito", 'pathImage' => $ImageName);
        echo json_encode($arrayNameupd);
    } else {
        $arrayNameupd = array('success' => 0, 'error' => "no parametros", 'mensaje' => "");
        echo json_encode($arrayNameupd);
        exit();
    }
} catch (Exception $exc) {

    $arrayNameupd = array('success' => 0, 'error' => $exc->getTraceAsString(), 'mensaje' => "");
    echo json_encode($arrayNameupd);
}

function makedirs($dirpath, $mode = 0777) {
    return is_dir($dirpath) || mkdir($dirpath, $mode, true);
}

?>