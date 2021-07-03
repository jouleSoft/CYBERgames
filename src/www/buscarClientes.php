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
		<link rel="stylesheet" type="text/css" href="styleSheets/tablas1.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_buscarClientes.js"></script>
		<script type="text/javascript">
			operacionRealizada(window.location.href);
		</script>
		<style>
			table td a{
				font-size: 12px;
			}
		</style>
    </head>
    <body>
        <div class="core">
            <div id="nav"><!--Barra de navegación-->
                <ul>
                    <li><a <?=$active?> href="venta.php">Venta</a></li>
                    <li><a href="articulos.php">Artículos</a></li>
                    <li><a href="clientes.php">Clientes</a></li>
                    <li><a href="facturas.php">Facturas</a></li>
                    <li style="float:right; background-color:red; color:whitesmoke;"><a onclick="logout()">Salir</a></li>
                    <li style="float:right;"><a href="preferencias.php">Preferencias</a></li>
                </ul>
            </div>
            <div id="header"><!--Cabecera-->
                <div id="logo">
                    <p><a>CYBER</a>Games</p>
                </div>
                <div id="usuario">
                    <?="<a>$ape_usr, $nombre_usr</a>"?> <!--USUARIO LOGADO-->
                </div>
            </div>
            <div id="main" style="padding-top: 25px"><!--Contenido principal de la página-->
                <div id="button_bar">
                    <ul>
                        <li><a onclick="buscarCliente()">Buscar cliente</a></li> <!--scripts/mod_buscarClientes:22-->
                        <li style="float:right"><a style="background-color: red" onclick="cancelar()">Cancelar</a></li><!--scripts/mod_buscarClientes:1-->
                    </ul>
                </div>
                <div id="prev">
                    <p>Búsqueda avanzada de <a>Clientes</a></p>
                    <table>
                        <tr><th id="cliID"><a>DNI</a></th><th><a>NOMBRE</a></th><th><a>APELLIDOS</a></th><th><a>TELÉFONO1</a></th><th><a>TELÉFONO2</a></th><th><a>EMAIL</a></th></tr>
						<?php
							# --Creación de conexión contra la base de datos-------------------------
							$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
							
							# En caso de detectar una búsqueda, variaremos el mandato de la query.
							if(isset($_GET['search'])){
								$BUSCAR = $_GET['search'];
								
								$sql = "SELECT ID,NOMBRE,APELLIDOS,TELEFONO1,TELEFONO2,EMAIL FROM CLIENTES WHERE NOMBRE LIKE '%$BUSCAR%' OR APELLIDOS LIKE '%$BUSCAR%'";
							}else{
								$sql = "SELECT ID,NOMBRE,APELLIDOS,TELEFONO1,TELEFONO2,EMAIL FROM CLIENTES";
							}
							
							if($mysqli->connect_error){
								die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
							}else{
								# --Ejecutamos query
								if($res=$mysqli->query($sql)){
									if($res->num_rows > 0){ # Si el número de resultados es mayor que cero, mostramos resultados en tabla.
										while($fila = $res->fetch_assoc()){
											
											$id 		= $fila['ID'];
											$nombre 	= utf8_encode($fila['NOMBRE']); # Codificar string a uft8
											$apellidos 	= utf8_encode($fila['APELLIDOS']); # Codificar string a uft8
											# Mostrar 'N/D' en caso de NULL para TELEFONO1 y TELEFONO2
											if($fila['TELEFONO1']==NULL){$telefono1='N/D';}else{$telefono1 = $fila['TELEFONO1'];}
											if($fila['TELEFONO2']==NULL){$telefono2='N/D';}else{$telefono2 = $fila['TELEFONO2'];}
											$email 		= $fila['EMAIL'];
											
											
											# Imprimimos fila de tabla.
											
											# scripts/mod_buscarClientes.js:22
											# --------------------------------
											# El mediante el listener 'onclick' ejecutaremos 'seleccionarCliente(dato[string])'
											# la función añadirá el cliente seleccionado al documento 'ventas.php'
											
											print "<tr id=\"resultado\" onclick=\"seleccionarCliente('$id')\"><td><a>$id</a></td><td><a>$nombre</a></td><td><a>$apellidos</a></td><td><a>$telefono1</a></td><td><a>$telefono2</a></td><td><a>$email</a></td></tr>";
										}										
									}
								}else{ # --En caso de no poder realizarse la query--
									print "Error en la ejecución del mandato sql";
								}
							}
							$mysqli->close();
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