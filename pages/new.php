 <?php
 
	$val1 = "";
	$idpdv=0;
	$mapzoom = 8;
	
	$UserSave = isset($_POST['select1']) ? $_POST['select1'] : '';
	
	require_once("../conexion/conexion.php");

	//Se crea nuevo objeto de la clase conexion
	$cnn = new conexion();
	$conn=$cnn->conectar();
	
	if (isset($_POST['submit1'] ))
	{
		$UserSave = $_POST['select1'];
	}

	$sql2= mysqli_query($conn,"SELECT IdPdV, NombrePdV, LocationGPS, NombreUsuario UserSave FROM puntosdeventa INNER JOIN usuario on usuario.idUsuario = puntosdeventa.UserSave WHERE UserSave LIKE '%$UserSave%'");
	
	if (isset($_GET['clicked'])){
		$idpdv = $_GET['clicked'];
		if ($idpdv > 0){
			$sql2= mysqli_query($conn,"SELECT IdPdV, NombrePdV, LocationGPS, NombreUsuario UserSave FROM puntosdeventa INNER JOIN usuario on usuario.idUsuario = puntosdeventa.UserSave WHERE IdPdV =$idpdv");
			$mapzoom = 17;
		}
		else{
			$sql2= mysqli_query($conn,"SELECT IdPdV, NombrePdV, LocationGPS, NombreUsuario UserSave FROM puntosdeventa INNER JOIN usuario on usuario.idUsuario = puntosdeventa.UserSave WHERE UserSave LIKE '%$UserSave%'");
		}
		
	}
	
		
    $z = array();
    $n=0;
    

    while( $row2 =mysqli_fetch_array($sql2)){
      $y=array(); 

	  $coordenada = $row2["LocationGPS"];
	  $myArray = explode(',', $coordenada);
	  
      $y[]=$row2["UserSave"];
      $y[]=$myArray[0];
      $y[]=$myArray[1];
      $y[]=$row2["IdPdV"];
      $y[]=utf8_encode($row2["NombrePdV"]);
	  $y[]= $mapzoom;
      $z[$n]=$y;
      $n++;
     }

   ?>

<!DOCTYPE html>
<html>
<head>

	<style type="text/css">
        html, body, form
        {
            height: 100%;
            overflow: hidden;
        }
		 body
            {
                margin: 0;
                padding: 0;
            }
	</style>
	
</head>
<body>

<form action="verpdv.php" method="post">
	
<table width="100%" height="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000">
<tr> 
<td >
	<div id="map" style="width:100%;height:100%;" ></div>

<script>
function myMap() {
	var locationsb = <?php echo json_encode($z);?>;
	var n=0;
	var myCenter = new google.maps.LatLng(locationsb[n][1], locationsb[n][2]);
	var mapCanvas = document.getElementById("map");
	var mapOptions = {center: myCenter, zoom: locationsb [n][5]};
	var map = new google.maps.Map(mapCanvas, mapOptions);
  
	var image = {
		url: 'pin.png',
		// This marker is 20 pixels wide by 32 pixels high.
		size: new google.maps.Size(20, 32),
		// The origin for this image is (0, 0).
		origin: new google.maps.Point(0, 0),
		// The anchor for this image is the base of the flagpole at (0, 32).
		anchor: new google.maps.Point(0, 32)
	};
	// Shapes define the clickable region of the icon. The type defines an HTML
	// <area> element 'poly' which traces out a polygon as a series of X,Y points.
	// The final coordinate closes the poly by connecting to the first coordinate.
	var shape = {
		coords: [1, 1, 1, 20, 18, 20, 18, 1],
		type: 'poly'
	};
	
	var marker1;
	for (n = 0; n < locationsb.length; n++) {
		var ur = ''; 
		if (locationsb[n][0].includes("1")){
			ur='pin1.png';
		} 
		else{
			ur='pin.png';
		}
		var img={
			url : ur,
			size: new google.maps.Size(20, 32),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(0, 32)
		};
		marker1 = new google.maps.Marker({
			position: new google.maps.LatLng(locationsb[n][1], locationsb[n][2]),
			title: locationsb[n][4],
			map:map,
			draggable:true,
			icon: img,
			shape: shape
		});

		google.maps.event.addListener(marker1, 'click', (function(marker1, n) {
			return function() {
				infowindow.setContent("id:"+locationsb[n][3] + ".<br/>" + locationsb[n][4] + ".<br/>Guardó: " + locationsb[n][0]);
				infowindow.open(map, marker1);
			}
		})(marker1, n));
    
		google.maps.event.addListener(marker1, 'click', function (event) {
    document.getElementById("latbox").value = event.latLng.lat();
    document.getElementById("lngbox").value = event.latLng.lng();
});
		
		google.maps.event.addListener(marker1, 'drag', function (event) {
    document.getElementById("latbox").value = this.getPosition().lat();
    document.getElementById("lngbox").value = this.getPosition().lng();
});
		//marker1.setMap(map);
	}
	var infowindow = new google.maps.InfoWindow({
		content: "Puntos de ventas registrados."
	});
	infowindow.open(map,marker1);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeAPkXd6qY2mg2QJ0bdMWbqO7Wjgs3diM&callback=myMap" async defer></script>
</td>
</tr>
<tr style="height:5%;">
<td>
 <div id="latlong">
    Latitude: <input size="20" type="text" id="latbox" name="lat" >
    Longitude: <input size="20" type="text" id="lngbox" name="lng" >
  </div>
  </td>
</tr>
</table>

</body>
</html>