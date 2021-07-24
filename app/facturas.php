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
		<script type="text/javascript" src="scripts/mod_facturas.js"></script>
		<style>
			table td, table th{
				text-align: center;
			}
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
                    <li><a <?=$active?> href="facturas.php">Facturas</a></li>
					<!--
					scripts/mod_general.js:1
					------------------------
					logout(): cerrar sesión actual y volver al documento de login.
					-->
                    <li style="float:right; background-color:red; color:whitesmoke;"><a onclick="logout()">Salir</a></li>
                    <li style="float:right;"><a href="adminCheck.php">Preferencias</a></li>
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
						<!--
						scripts/mod_facturas.js:1
						------------------------
						buscarFactura(): buscar facturas por dni de cliente.
						-->
                        <li><a onclick="buscarFactura()">Buscar factura</a></li><!--scripts/mod_facturas.js:1-->
                    </ul>
                </div>
                <div id="prev">
                    <p><a>Facturas</a></p>
                    <table>
                        <tr><th id="FactID"><a>ID</a></th><th><a>FECHA</a></th><th><a>DNI</a></th><th><a>SUBTOTAL</a></th><th><a>IVA</a></th><th><a>TOTAL</a></th></tr>
						<?php
							# Comprobamos la existencia de parámetros GET.
							# El parámetros 'search' conllevará una búsqueda por dni.
							if(isset($_GET['search']) && $_GET['search'] != 'null'){
								$BUSCAR = $_GET['search'];
								
								$sql = "SELECT *,DATE_FORMAT(FECHA,'%d-%m-%Y') AS FECHA_FORM FROM FACTURAS WHERE IDCLI LIKE '%$BUSCAR%'";
							}else{
								$sql = "SELECT *,DATE_FORMAT(FECHA,'%d-%m-%Y') AS FECHA_FORM FROM FACTURAS";
							}
							
							# Crear conexión con la base de datos.
							$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
							
							if($mysqli->connect_error){
								echo "<tr><td><a>CONN_ERR</a></td><td><a></a></td><td><a></a></td><td><a></a></td><td><a></a></td></tr>";
							}else{
								# --Ejecutamos query y usammos el valor binario de la operación para evaluar su ejecución-------------------
								if($res=$mysqli->query($sql)){
									# --Comprobar si hay resultados--
									if($res->num_rows > 0){
										# --Introducimos resultado en variable $fila--
										while($fila = $res->fetch_assoc()){
											
											$id 		= $fila['IDFACT'];
											$fecha 		= $fila['FECHA_FORM'];
											$idcli 		= strtoupper($fila['IDCLI']);
											$subtotal  	= (float)$fila['SUBTOTAL'];
											$iva		= (float)$fila['IVA'];
											$total		= (float)$fila['TOTAL'];
											
											print "<tr id=\"resultado\" onclick=\"abrirVisor('$id')\"><td><a>$id</a></td><td><a>$fecha</a></td><td><a>$idcli</a></td><td><a>€ " . number_format($subtotal,2,'.',' ') . "</a></td><td><a>€ " . number_format($iva,2,'.',' ') . "</a></td><td><a>€ " . number_format($total,2,'.',' ') . "</a></td></tr>";
										}										
									}
								}else{ # --En caso de no poder realizarse la query--
									die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
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