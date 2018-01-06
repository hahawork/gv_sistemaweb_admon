<?php

require_once("conexion/conexion.php");

    //Se crea nuevo objeto de la clase conexion
    $cnn = new conexion();
    $conn=$cnn->conectar();


    $sql = "SELECT CatCanales.NombreCanal,Departamentos.NombreDepto, puntosdeventa.NombrePdV, 
                                                    planpromo_marcas.Descripcion as Marca, planpromo_presentaciones.Descripcion as Presentacion, 
                                                    date_format(FechaVencimientoDet.fechaVenc, '%b-%Y') as fechaVenc, FechaVencimientoDet.CantidadExist, FechaVencimientoDet.CantidadBandeo, 
                                                    FechaVencimientoDet.TipoBandeo, planpromo_presentaciones.CodCafeSoluble, planpromo_presentaciones.CodCasaMantica, usuario.NombreUsuario, date_format(FechaVencimientoEnc.fechaReg,'%d/%b/%Y %l:%i %p') as fechaReg
                                                    FROM  
                                                    FechaVencimientoDet INNER JOIN 
                                                    FechaVencimientoEnc ON FechaVencimientoEnc.idFVE = FechaVencimientoDet.idFVE INNER JOIN 
                                                    planpromo_presentaciones ON FechaVencimientoEnc.idPresentacion = planpromo_presentaciones.IdPresentacion INNER JOIN  
                                                    planpromo_marcas ON planpromo_presentaciones.IdMarca =  planpromo_marcas.IdMarca INNER JOIN 
                                                    CatCanales ON FechaVencimientoEnc.idCanal = CatCanales.IdCanal INNER JOIN
                                                    Departamentos ON FechaVencimientoEnc.idDepto = Departamentos.IdDepartamento INNER JOIN
                                                    puntosdeventa ON FechaVencimientoEnc.idpdv = puntosdeventa.IdPdV INNER JOIN 
                                                    usuario ON usuario.idUsuario = FechaVencimientoEnc.idUsuario";
                                            

                                            $result = mysqli_query($conn, $sql);


$filename = "Webinfopen.xls"; // File Name
// Download file
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");
// Write data to file
$flag = false;
while ($row = mysqli_fetch_array($result)) {
    if (!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    echo implode("\t", array_values($row)) . "\r\n";
}
?>