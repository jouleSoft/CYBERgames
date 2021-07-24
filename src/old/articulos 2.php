<?php
# Datos de sesión
# ---------------

# Si la sesión no está iniciada, redirige a index.php,
# si lo está recoge los datos del usuario logado actualmente

session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}else{
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
}

# Módulos de funciones
# --------------------

require('modules/mod_general.php');
# -- variables importadas --

# $active: valores de estilo css para indicar página actual en la barra de navegación.

# -- funciones importadas --

# zeroIzquierda($entrada[string]): añade ceros a la izquierda de un valor para su representación.

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/articulos.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/tablas2.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_articulos_comun.js"></script>
		<script type="text/javascript" src="scripts/mod_articulos.js"></script>
		<script type="text/javascript">
			operacionRealizada(window.location.href);
		</script>
        <?php
			
			$array_plat = [];	# Array contenedor para listado de nombre de plataformas disponiibles
			$array_list = [];	# Array contenedor para listado de resultados
			
			# Plataforma seleccionada para el listado de previsualización de artículos mediante $_GET
			# podremos visualizar la lista de artículos agrupados por plataforma.
			
			if(!isset($_GET['plat'])){ 
				$plataforma = "TODAS";
			}else{
				$plataforma = $_GET['plat'];
				
				if($plataforma == "[BUSCAR]" && isset($_GET['search'])){
					$Buscar = strtoupper($_GET['search']);
				}
			}
			
			### --Listado de plataformas disponibles-- ###
			
			# Creación de conexión contra la base de datos
			$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
			
			# Evaluación de error de conexión
			if(!$mysqli->connect_error){
				
				# Sistema de etiquetas y evento javaScript para la selección de plataforma, con ello construiremos las
				# filas de cada tabla
				#
				# Recogeremos el valor de la etiqueta con la referencia indicada para pasar dicho valor mediante $_GET
				# de forma reflexiva a la misma página
				
				# Construimos el id: id="referenciaN". Cada fila llevará la suya, por lo que deben de ser únicas. De ahí el contador.
				$contador = 0;
				$ref			= "referencia" . $contador;
				$tagID			= "id=\"$ref\"";
				
				
				# Evento 'onclick' para usar la función 'cargarRef()'
				$event			= "onclick=\"cargarRef(document.getElementById('$ref').innerText)\"";
				
				# Indicar la plataforma seleccionada. Si la plataforma coincide, formatear con CSS mediante '$p_active'
				$p_active = "style=\"background-color: gray; color: whitesmoke;\"";
				
				if ($plataforma == 'TODAS'){
					$array_plat[] = "<tr $event><td $p_active ><a $tagID>TODAS</a></td></tr>";
				}else{
					$array_plat[] = "<tr $event><td><a $tagID>TODAS</a></td></tr>";
				}
				
				# Ejecutamos query y evaluamos. Se agruparán los resultados por plataforma para que no haya duplicados.
				if($res=$mysqli->query("SELECT PLATAFORMA FROM ARTICULOS GROUP BY PLATAFORMA")){
					if($res->num_rows > 0){
						
						$contador++; # Preparamos el contador para iniciar el proceso desde el número 1.
						
						# Obtenemos el resto de resultados de la misma forma que en la anterior inserción.
						while($fila = $res->fetch_assoc()){
							$ref			= "referencia" . $contador;
							$tagID			= "id=\"$ref\"";
							$event			= "onclick=\"cargarRef(document.getElementById('$ref').innerText)\"";
							
							$plat			= $fila['PLATAFORMA'];
							
							if($plataforma == $plat){
								$array_plat[] = "<tr $event><td $p_active ><a $tagID>$plat</a></td></tr>";
							}else{
								$array_plat[] = "<tr $event><td><a $tagID>$plat</a></td></tr>";
							}
							
							$contador++;
						}
					}
					$res->close();	# Cerramos buffer de la query actual.
				}
				
				### --Listado de artículos-- ###
				
				# La query dependerá de la plataforma: Todas las plataformas / Plataforma específica
				
				switch($plataforma){
					case "TODAS":
						$sql = "SELECT ID,NOMBRE,PVENTA,STOCK,PLATAFORMA FROM ARTICULOS ORDER BY NOMBRE ASC";
						break;
					case "[BUSCAR]":
						$sql = "SELECT ID,NOMBRE,PVENTA,STOCK,PLATAFORMA FROM ARTICULOS WHERE NOMBRE LIKE '%$Buscar%' ORDER BY NOMBRE ASC";
						break;
					default:
						$sql = "SELECT ID,NOMBRE,PVENTA,STOCK,PLATAFORMA FROM ARTICULOS WHERE PLATAFORMA='$plataforma' ORDER BY NOMBRE ASC";
				}
				
				# Ejecutamos query con evaluación de resultado
				if($res=$mysqli->query($sql)){
					if($res->num_rows > 0){
						
						$idRow = 0;
						
						while($fila = $res->fetch_assoc()){
							$id 	 = zeroIzquierda($fila['ID']);
							$titulo  = utf8_encode($fila['NOMBRE']);
							$precio  = $fila['PVENTA'];
							$stock	 = $fila['STOCK'];
							$plat	 = $fila['PLATAFORMA'];
							
							$idTag	 = "id=\"IDART" . $idRow . "\"";
							$onclick = "onclick=\"abrirVisor(document.getElementById('IDART$idRow').innerText)\"";
							
							$array_list[] = "<tr id=\"prev_art_row\" $onclick><td id=\"artID\"><a $idTag>$id</a></td><td id=\"artTITULO\"><a>$titulo</a></td><td id=\"artPRECIO\"><a>€ $precio</a></td><td id=\"artSTOCK\"><a>$stock</a></td><td id=\"artPLAT\"><a>$plat</a></td></tr>";
							
							$idRow++;
						}
					}
				}
			}
			$mysqli->close(); # Cerramos conexión con base de datos.
        ?>
    </head>
    <body>
        <div class="core">
            <div id="nav"><!--Barra de navegación-->
                 <ul>
                    <li><a href="venta.php">Venta</a></li>
                    <li><a <?=$active?> href="articulos.php">Artículos</a></li>
                    <li><a href="clientes.php">Clientes</a></li>
                    <li><a href="facturas.php">Facturas</a></li>
					<!--
					scripts/mod_general.js:1
					------------------------
					logout(): cerrar sesión actual y volver al documento de login.
					-->
                    <li style="float:right; background-color:red; color:whitesmoke;"><a onclick="logout()">Salir</a></li>
                    <li style="float:right;"><a href="adminCheck.php?">Preferencias</a></li>
                </ul>
            </div>
            <div id="header"><!--Cabecera-->
                <div id="logo">
                    <p><a>CYBER</a>Games</p>
                </div>
                <div id="usuario"> <!--Datos del usuario logado-->
                    <?="<a>$ape_usr, $nombre_usr</a>"?>
                </div>
            </div>
            <div id="main" style="padding-top: 25px"><!--Contenido principal de la página-->
                <div style="width: auto; height: auto; margin: auto;">
					<div id="button_bar">
						<ul>
							<li><a onclick="buscarArticulo()">Buscar artículo</a></li>
							<li style="float:right"><a style="background-color: red" onclick="openEditor('nuevo','')">Nuevo artículo</a></li>
						</ul>
					</div>
                </div>
				 <div id="table_list">
                        <p>Plataforma</p>
                        <table><!--Tabla de plataformas-->
							<?php
								foreach($array_plat as $row){
									echo $row;
								}
							?>
                        </table>
                    </div>
                    <div id="prev_art"><!--Tabla de listado de artículos-->
                        <p id="prev_art_tit"><a>Plataforma</a> <?=$plataforma?></p>
                        <table>
                            <tr><th id="artID"><a>ID</a></th><th id="artTITULO"><a>TÍTULO</a></th><th id="artPRECIO"><a>PRECIO</a></th><th id="artSTOCK"><a>STOCK</a></th><th id="artPLAT"><a>PLATAFORMA</a></th></tr>
							<?php
								foreach($array_list as $row){
									echo $row;
								}
							?>
                        </table>
                    </div>
            </div>
            <div id="footer">
                <p>Powered by Julio Jiménez Delgado. Copyright (C) 2018 - 2019</p>
            </div>
        </div>
    </body>
</html>