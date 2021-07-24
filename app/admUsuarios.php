<?php
# Datos de sesión
# ---------------

# Si la sesión no está iniciada, redirige a index.php,
# si lo está recoge los datos del usuario logado actualmente

# En este documento se identifica el tipo de usuario: administrador o estándar.

session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}else{
	$username 	= $_SESSION['username'];
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
	$admin		= $_SESSION['admin'];
	
	if($admin == 0){ # Si el usuario no es administrador, redirecciona a index.php.
		header('location: index.php');
	}
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
		<script type="text/javascript" src="scripts/mod_admUsuarios.js"></script>
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
                    <li><a href="venta.php">Venta</a></li>
                    <li><a href="articulos.php">Artículos</a></li>
                    <li><a href="clientes.php">Clientes</a></li>
                    <li><a href="facturas.php">Facturas</a></li>
                    <li style="float:right; background-color:red; color:whitesmoke;"><a onclick="logout()">Salir</a></li>
                    <li style="float:right;"><a href="adminCheck.php">Preferencias</a></li>
                </ul>
            </div>
            <div id="header"><!--Cabecera-->
                <div id="logo">
                    <p><a>CYBER</a>Games</p>
                </div>
                <div id="usuario">
                    <?="<a>$ape_usr, $nombre_usr</a>"?>
                </div>
            </div>
            <div id="main" style="padding-top: 25px"><!--Contenido principal de la página-->
                <div id="button_bar">
                    <ul>
                        <li><a onclick="buscarCliente()">Buscar usuario</a></li>
                        <li style="float:right"><a style="background-color: red" onclick="openEditor('nuevo','')">Nuevo usuario</a></li>
                    </ul>
                </div>
                <div id="prev">
                    <p><a>Usuarios</a></p>
                    <table>
                        <tr><th id="cliID"><a>USERNAME</a></th><th><a>NOMBRE</a></th><th><a>APELLIDOS</a></th><th><a>TIPO DE USUARIO</a></th></tr>
						<?php
							# --Creación de conexión contra la base de datos-------------------------
							$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
							if(isset($_GET['search'])){
								$BUSCAR = $_GET['search'];
								
								$sql = "SELECT USERNAME,NOMBRE,APELLIDOS,ADMIN FROM USUARIOS WHERE USERNAME LIKE '%$BUSCAR%' OR APELLIDOS LIKE '%$BUSCAR%' OR NOMBRE LIKE '%$BUSCAR%'";
							}else{
								$sql = "SELECT USERNAME,NOMBRE,APELLIDOS,ADMIN FROM USUARIOS";
							}
								
							# --Evaluación de error de conexión----------------
							if($mysqli->connect_error){
								echo "<tr><td><a>CONN_ERR</a></td><td><a></a></td><td><a></a></td><td><a></a></td><td><a></a></td></tr>";
							}else{
								# --Ejecutamos query y usammos el valor binario de la operación para evaluar su ejecución-------------------
								if($res=$mysqli->query($sql)){
									# --Comprobar si hay resultados--
									if($res->num_rows > 0){
										# --Introducimos resultado en variable $fila--
										while($fila = $res->fetch_assoc()){
											
											$USERNAME 		= $fila['USERNAME'];
											$NOMBRE 		= strtoupper(utf8_encode($fila['NOMBRE']));
											$APELLIDOS 		= strtoupper(utf8_encode($fila['APELLIDOS']));
											if($fila['ADMIN']==0){$ADMIN='ESTÁNDAR';}else{$ADMIN = 'ADMINISTRADOR';}
											
											print "<tr id=\"resultado\" onclick=\"abrirVisor('$USERNAME')\"><td><a>$USERNAME</a></td><td><a>$NOMBRE</a></td><td><a>$APELLIDOS</a></td><td><a>$ADMIN</a></td></tr>";
										}										
									}
								}else{ # --En caso de no poder realizarse la query--
									echo "<tr><td><a>QRY_ERR</a></td><td><a></a></td><td><a></a></td><td><a></a></td><td><a></a></td></tr>";
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