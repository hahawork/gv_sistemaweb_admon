<?php
$FechaIni = isset($_POST['txtFechaDesde']) ? $_POST['txtFechaDesde'] : null;
$FechaFin = isset($_POST['txtFechaHasta']) ? $_POST['txtFechaHasta'] : null;

$indicesServer = array('PHP_SELF',
    'argv',
    'argc',
    'GATEWAY_INTERFACE',
    'SERVER_ADDR',
    'SERVER_NAME',
    'SERVER_SOFTWARE',
    'SERVER_PROTOCOL',
    'REQUEST_METHOD',
    'REQUEST_TIME',
    'REQUEST_TIME_FLOAT',
    'QUERY_STRING',
    'DOCUMENT_ROOT',
    'HTTP_ACCEPT',
    'HTTP_ACCEPT_CHARSET',
    'HTTP_ACCEPT_ENCODING',
    'HTTP_ACCEPT_LANGUAGE',
    'HTTP_CONNECTION',
    'HTTP_HOST',
    'HTTP_REFERER',
    'HTTP_USER_AGENT',
    'HTTPS',
    'REMOTE_ADDR',
    'REMOTE_HOST',
    'REMOTE_PORT',
    'REMOTE_USER',
    'REDIRECT_REMOTE_USER',
    'SCRIPT_FILENAME',
    'SERVER_ADMIN',
    'SERVER_PORT',
    'SERVER_SIGNATURE',
    'PATH_TRANSLATED',
    'SCRIPT_NAME',
    'REQUEST_URI',
    'PHP_AUTH_DIGEST',
    'PHP_AUTH_USER',
    'PHP_AUTH_PW',
    'AUTH_TYPE',
    'PATH_INFO',
    'ORIG_PATH_INFO');

echo '<table>';
foreach ($indicesServer as $arg) {
    if (isset($_SERVER[$arg])) {
        echo '<tr><td>' . $arg . '</td><td>' . $_SERVER[$arg] . '</td></tr>';
    } else {
        echo '<tr><td>' . $arg . '</td><td>-</td></tr>';
    }
}
echo '</table>';

/*

  That will give you the result of each variable like (if the file is server_indices.php at the root and Apache Web directory is in E:\web) :

  PHP_SELF    /server_indices.php
  argv    -
  argc    -
  GATEWAY_INTERFACE    CGI/1.1
  SERVER_ADDR    127.0.0.1
  SERVER_NAME    localhost
  SERVER_SOFTWARE    Apache/2.2.22 (Win64) PHP/5.3.13
  SERVER_PROTOCOL    HTTP/1.1
  REQUEST_METHOD    GET
  REQUEST_TIME    1361542579
  REQUEST_TIME_FLOAT    -
  QUERY_STRING
  DOCUMENT_ROOT    E:/web/
  HTTP_ACCEPT    text/html,application/xhtml+xml,application/xml;q=0.9, ;
q = 0.8
HTTP_ACCEPT_CHARSET ISO-8859-1, utf - 8;
q = 0.7, *;
q = 0.3
HTTP_ACCEPT_ENCODING gzip, deflate, sdch
HTTP_ACCEPT_LANGUAGE fr-FR, fr;
q = 0.8, en - US;
q = 0.6, en;
q = 0.4
HTTP_CONNECTION keep-alive
HTTP_HOST localhost
HTTP_REFERER http://localhost/ 
HTTP_USER_AGENT Mozilla/5.0 (Windows NT 6.1;
WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17
HTTPS -
REMOTE_ADDR 127.0.0.1
REMOTE_HOST -
REMOTE_PORT 65037
REMOTE_USER -
REDIRECT_REMOTE_USER -
SCRIPT_FILENAME E:/web/server_indices.php
SERVER_ADMIN myemail@personal.us
SERVER_PORT 80
SERVER_SIGNATURE
PATH_TRANSLATED -
SCRIPT_NAME /server_indices.php
REQUEST_URI /server_indices.php
PHP_AUTH_DIGEST -
PHP_AUTH_USER -
PHP_AUTH_PW -
AUTH_TYPE -
PATH_INFO -
ORIG_PATH_INFO -

*/
?>

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <div>
            <?php
            require_once("/conexion/conexion.php");

            //Se crea nuevo objeto de la clase conexion
            $cnn = new conexion();
            $conn = $cnn->conectar();

            $ConsultaPdV = "select distinct FechaVencimientoEnc.idpdv, puntosdeventa.NombrePdV from FechaVencimientoEnc inner join puntosdeventa on FechaVencimientoEnc.idpdv = puntosdeventa.IdPdV";

            $result = mysqli_query($conn, $ConsultaPdV);
            mysqli_query($conn, "SET NAMES 'utf8'");
            if ($result) {

                while ($row = mysqli_fetch_array($result)) {

                    echo "<table id='ReportTable' class='myclass' style='border: 2px solid #000; ' width='100%'>
							<thead>
								<tr>
									<td style='background: #8db4e2; color: #000;'>MARCAS</td>
									<td style='background: #8db4e2; color: #000;'>DESCRIPCIÓN</td>
									<td style='background: #8db4e2; color: #000;' colspan='3'>" . utf8_encode($row['NombrePdV']) . "</td>
								</tr>
							</thead>
							<tbody>					
								<tr>
									<td colspan='2'></td>
									<td>P. Anterior</td>
									<td>P. Actual</td>
									<td>Variacion</td>
								</tr>
								<tr>
									<td style='text-align: center;' bgcolor='#fcb3b3' colspan='5'>Culinario</td>						
								</tr>
								<tr>
									<td>Culinarios</td>
									<td>Carne de Soya en trocitos</td>
									<td style='text-align: right;padding-right: 5px;'>34.75</td>
									<td style='text-align: right;padding-right: 5px;'>43.75</td>
									<td style='text-align: right;padding-right: 5px;'>0.0</td>
								</tr>
							</tbody>
								
						</table>";
                }
            }
            ?>


            <input type="hidden" id="datatodisplay" name="datatodisplay">  
            <input type="submit" value="Export to Excel"> 

        </div>
    </body>
</html>