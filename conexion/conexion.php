<?php

include ('define.php');

class conexion
{
    public function conectar(){
        return mysqli_connect(HOSt,USEr,PASSWd,BDMysql);
    }
}
?>
