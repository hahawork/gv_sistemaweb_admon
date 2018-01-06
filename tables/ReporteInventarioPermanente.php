 <?php

  session_start();

  if (!$_SESSION["sUserId"]) { 
	header('Location: ../login/index.php');
  }

  $admin = 'disabled';


  if ($_SESSION["sessionRol"] == "Admin") {
      $admin = '';
  }

  require_once("../conexion/conexion.php");

	//Se crea nuevo objeto de la clase conexion
	$cnn = new conexion();
	$conn=$cnn->conectar();

	$FechaIni = isset($_POST['txtFechaDesde']) ? $_POST['txtFechaDesde'] : null;
  	$FechaFin = isset($_POST['txtFechaHasta']) ? $_POST['txtFechaHasta'] : null;  

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="icon" type="image/png" href="../favicon.png">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="../dist/css/AdminLTE.css">
	<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

	<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="buttons.dataTables.min.css">

    <title>Inventario permanente de <?php echo $_SESSION["sNombCliente"]; ?> del: <?php echo $FechaIni." al ". $FechaFin; ?></title>

    <style type="text/css">
        .disabled {
            pointer-events:none; //This makes it not clickable
            opacity:0.6;         //This grays it out to look disabled
        }
    </style>

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
						<img src="<?php echo "../" . $_SESSION['simgFotoPerfilUrl']; ?>" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?php echo $_SESSION["sUserName"]; ?></p>
						<a href="../login/logout.php"><i class="fa fa-circle text-success"></i> Cerrar Sesión</a>
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
							<li class="active"><a href="../index.php"><i class="fa fa-circle-o"></i> Panel de control.</a></li>
							<li><a href="../pages/verpdv.php"><i class="fa fa-map"></i> Puntos de venta gráfico</a></li>
							<li><a href="../pages/verpdvlist.php"><i class="fa fa-map-pin"></i> Puntos de venta listado</a></li>
							<li><a href="asistencia-imprimir.php"><i class="fa fa-user"></i> Asistencia de las supervisoras</a></li>
							<li><a href="asistenciaoficinaimprimir.php"><i class="fa fa-certificate"></i> Asistencia de Oficinas</a></li>
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
				<h1>Supervisión<small>Reporte de Precios</small></h1>
				<ol class="breadcrumb">
					<li><a href="../index.php"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Reporte Precio</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">					

					<div class="col-lg-12 col-md-12 col-xs-12">
			
						<div class="box box-solid bg-light-blue-gradient">
							<div class="box-header with-border">
								<h3 class="box-title">Precios</h3>								
							</div>

							<form method="post" action="ReporteInventarioPermanente.php">
	                            <div class=" col-xl-12 col-md-12 col-xs-12">
	                                <div class=" col-xl-3 col-md-3 col-xs-1"></div>
	                                <div class="col-xl-6 col-md-9 col-xs-12" style="color: #000;">
	                                    <span>Desde: </span>
	                                    <input type="date" width="50px" name="txtFechaDesde" placeholder="dd/mm/yyyy" value="<?php echo $FechaIni; ?>" min="2017-04-15" max="<?php echo  date("Y-m-d") ?>">
	                                    <span>Hasta: </span>
	                                    <input type="date" width="50px" name="txtFechaHasta" placeholder="dd/mm/yyyy" value="<?php echo $FechaFin; ?>" min="2017-04-15" max="<?php echo  date("Y-m-d") ?>">
	                                    <input type="submit" name="btnConsultar" value="Consultar" class="btn btn-primary">
	                                </div>
	                                <div class=" col-xl-3 col-md-1 col-xs-1"></div>
	                            </div>
                        	</form>

							<div class="box-body">
								<table style="color: #000; border: 1px solid #000;" id="example" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Item</th>
											<th>Cód. Barra</th>
											<th>Presentación</th>
											<th>Marca</th>
											<th>Punto de Venta</th>
											<th>Cant. IP</th>
											<th>Cant. Física</th>
											<th>Observación</th>
											<th>Cant. Averia</th>
											<th>Cant. Vencido</th>
											<th>Cant. etiqRasg</th>
											<th>Cant. Chopiado</th>
											<th>Usuario</th>
										</tr>
									</thead>
									<tbody>
										<?php 

											if (isset($_POST['btnConsultar'])) {

												$idUsu = $_SESSION["sIdUsuario"];
												$idCliente = $_SESSION["sIdCliente"];

											
												$sql = "SELECT cod_presentacion, cod_barra, presentacion, marca, NombrePdV, cantidad_ip, cantidad_apta, observaciones, 
												cantidad_averiado, cantidad_vencido, cant_etiq_rasgada, cant_sucio_chop, usuario.NombreUsuario
												FROM 
												tbl_inv_permanente INNER JOIN 
												puntosdeventa on tbl_inv_permanente.id_pto_venta = puntosdeventa.IdPdV INNER JOIN usuario on tbl_inv_permanente.id_usuario = usuario.idUsuario
												WHERE cantidad_apta > 0 and (fecha_subido BETWEEN '$FechaIni' AND '$FechaFin') AND `id_cliente` = '$idCliente'";

												$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
										        mysqli_query($conn, "SET NAMES 'utf8'");
										        if ($result){
										            
										        	while ($row = mysqli_fetch_array($result)){
														echo '<tr>
															  <td>'. strtoupper(utf8_encode($row['cod_presentacion'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cod_barra'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['presentacion'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['marca'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['NombrePdV'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cantidad_ip'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cantidad_apta'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['observaciones'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cantidad_averiado'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cantidad_vencido'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cant_etiq_rasgada'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['cant_sucio_chop'])) .'</td>
															  <td>'. strtoupper(utf8_encode($row['NombreUsuario'])) .'</td>
															</tr>';
													}
												}																						
											}
										 ?>
									</tbody>
									<tfoot>
										<tr>
											<th>Item</th>
											<th>Cód. Barra</th>
											<th>Presentación</th>
											<th>Marca</th>
											<th>Punto de Venta</th>
											<th>Cant. IP</th>
											<th>Cant. Física</th>
											<th>Observación</th>
											<th>Cant. Averia</th>
											<th>Cant. Vencido</th>
											<th>Cant. etiqRasg</th>
											<th>Cant. Chopiado</th>
										</tr>
									</tfooot>
								</table>
							</div>
							</div>
						</div>
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

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>

<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>

    	$(document).ready(function() {
		    $('#example').DataTable( {
		        "columnDefs": [
		            {
		                "targets": [ 2 ],
		                "visible": false,
		                "searchable": false
		            },
		            {
		                "targets": [ 3 ],
		                "visible": false
		            }
		        ]
		    } );
		} );

      $(function () {
        $('#example').DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
      });

      
    </script>

	<script src="jquery-1.12.4.js"></script>
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );
        } );
    </script>

</body>
</html>