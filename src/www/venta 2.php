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

require('modules/mod_ventas.php');
# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# nuevaFactura(): indica el número de la nueva factura a emitir.
# listaPlataformasVenta($plataforma[string],$cliente[string]): devuelve un array con la lista de plataformas para el documento ventas.php
# listaArticulosVentas($PLAT[string],IDCLI[string]): devuelve un array con la lista de artículos filtrados por plataforma para el documento ventas.php
# ventaIniciada(): en caso de cambiar de documento, si la venta se ha iniciado nos mostrará su estado (ver variable $sistema)
# añadirArtVentas($PLAT[string],$IDCLI[string],$IDART[int]): añade el artículo a tabla VENTAS, si ya existe, +1 al campo CANTIDAD. Los artículos serán restados de ARTÍCULOS.
# eliminarArtVentas($PLAT[string],$IDCLI[string],$IDART[int]): operación inversa a 'añadirArtVentas()'.
# listaVentas($IDCLI[string],$PLAT[string]): muestra el listado de la lista de ventas en función de la plataforma.
# valoresSubtotal(): devuelve un array con los valores de SUBTOTAL para documento venta.php.
# finVenta($FACTURA[string],$IDCLI[string]: añade una nueva factura a tabla FACTURAS y elimina el contenide de tabla VENTAS.

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/tablasVentas.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/ventas.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_ventas.js"></script>
        <?php
			
			
			# Funcionamiento secuencial mediante variables:
			# ---------------------------------------------
			# Al cargar el formulario, el sistema esperará a que haya un cliente seleccionado, entonces el sistema ($sistema) pasará
			# a estado verdadero para que se cargue la lísta de plataformas disponibles.
			#
			# Una vez seleccionada una plataforma, se activará el listado de artículos relacionados con la misma ($listado). De esta forma
			# no mezclaremos los artículos con el mismo título que están publicados para diferentes plataformas.
			
			$sistema = false;
			$listado = false;
			
			# Plataforma seleccionada
			if(isset($_GET['plat'])){
				$PLAT = $_GET['plat'];
			}else{
				$PLAT = '';
			}
        ?>
		<script type="text/javascript">
			//scripts/mod_ventas.js:39
			//-----------------------------------
			//Mediante la url, nos indicará con un evento 'alert' si la venta se ha realizado correctamente.
			//Ver documento: ventaFin.php
			
			comprobarVenta(window.location.href);
		</script>
    </head>
    <body>
        <div class="core">
            <div id="nav"><!--Barra de navegación-->
                <ul>
                    <li><a <?=$active?> href="venta.php">Venta</a></li>
                    <li><a href="articulos.php">Artículos</a></li>
                    <li><a href="clientes.php">Clientes</a></li>
                    <li><a href="facturas.php">Facturas</a></li>
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
                    <?="<a>$ape_usr, $nombre_usr</a>"?><!--Datos del usuario logado-->
                </div>
            </div>
            <div id="main" style="padding-top: 25px"><!--Contenido principal de la página-->
                <div id="DivSuperior">
                    <div id="DivDatos">
						<fieldset id="fsCliente">
							<legend>Datos del cliente</legend>
							<div id="DivAñadirElemento">
								<!--
								scripts/mod_ventas.js:1
								-----------------------
								Usamos la función buscarCliente(cliente[string]): añade un cliente por DNI o abre la búsqueda avanzada en caso de fallo.
								-->
								<a class="añadirElemento" id="nuevoElemento" onclick="buscarCliente('<?=$PLAT?>')">+</a>
							</div>
							<div id="DivDNI">
								<?php
									# Comprobamos la existencia de parámetros GET.
									# Condición: $_GET['idCli'] está inicializado y es diferente al valor (string) 'null' devuelto al cancelar un evento 'prompt' javaScript.
									if(isset($_GET['idCli']) && $_GET['idCli'] != 'null'){
										$IDCLI	= strtoupper($_GET['idCli']); # contenido en mayúsculas

										$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
										
										if($mysqli->connect_error){ #Ralizar una nueva conexión con la base de datos.
											die('fallo en la conexión con la base de datos' . $mysqli->connect_error);
										}else{
											# Buscar cliente por DNI (valor único en la tabla CLIENTES).
											# Ejecutamos query: el número de filas será como máximo 1.
											if($res = $mysqli->query("SELECT NOMBRE, APELLIDOS, TELEFONO1, TELEFONO2, EMAIL, DIRECCION, CPOSTAL FROM CLIENTES WHERE ID LIKE '$IDCLI'")){
												if($res->num_rows == 1){
													$fila = $res->fetch_assoc();
													
													$NOMBRE			= utf8_encode($fila['NOMBRE']);
													$APELLIDOS		= utf8_encode($fila['APELLIDOS']);
													$TELEFONO1		= $fila['TELEFONO1'];
													$TELEFONO2		= $fila['TELEFONO2'];
													$EMAIL			= $fila['EMAIL'];
													$DIRECCION		= utf8_encode($fila['DIRECCION']);
													$CPOSTAL		= $fila['CPOSTAL'];
													
													$labelTelefono1 = "T. PRINCIPAL: ";
													$labelTelefono2 = "T. ALTERNATIVO: ";
													
													$sistema		= true;
												}else{ # Si el número de columnas es 0 y se cancela la búsqueda avanzada.
													$IDCLI			= "X";
													$NOMBRE			= "CLIENTE";
													$APELLIDOS		= "NO ENCONTRADO";
													$TELEFONO1		= "";
													$TELEFONO2		= "";
													$EMAIL			= "";
													$DIRECCION		= "";
													$CPOSTAL		= "";
													
													$labelTelefono1 = "";
													$labelTelefono2 = "";
													
													# scripts/mod_ventas.js:51
													# ------------------------
													# En caso de aceptar, cargará el documento 'buscarClientes.php'
													print "<script>";
													print "buscarClienteAvanzado();";
													print "</script>";
												}
											}
										}
										$mysqli->close();
											
										print "<label id=\"labelDNI\" for=\"idCli\">[$IDCLI]</label>";
									}else{ # dejamos los elementos LABEL vacíos para una mejor estética.
										$IDCLI			= "";
										$NOMBRE			= "";
										$APELLIDOS		= "";
										$TELEFONO1		= "";
										$TELEFONO2		= "";
										$EMAIL			= "";
										$DIRECCION		= "";
										$CPOSTAL		= "";
										
										$labelTelefono1 = "";
										$labelTelefono2 = "";
									}
								?>
							</div>
							<!--MOSTRAR DATOS DEL CLIENTE-->
							<div id="DivNombreApellidos">
								<?php
									print "<label id=\"labelNombre\" for=\"idCli\">$NOMBRE </label>";
									print "<label id=\"labelApellidos\" for=\"idCli\">$APELLIDOS </label>";
								?>
							</div>
							<div id="DivEmail">
								<?php
									print "<label id=\"labelEmail\" for=\"idCli\">$EMAIL </label>";
								?>
							</div>
							<div id="DivTelefonos">
								<?php
									print "<label for=\"idCli\">$labelTelefono1</label>";
									print "<label id=\"labelTelefono1\" for=\"idCli\">$TELEFONO1 </label><br>";
									print "<label for=\"idCli\">$labelTelefono2</label>";
									print "<label id=\"labelTelefono2\" for=\"idCli\">$TELEFONO2 </label>";
								?>
							</div>
							<!--|FIN| MOSTRAR DATOS DEL CLIENTE-->
						</fieldset>
					</div>
					<!--MOSTRAR DATOS DE LA SIGUIENTE FACTURA A EMITIR-->
					<div id="DivFacturacion">
						<?php
							$FACTURA = nuevaFactura();
							
							print "<label for=\"idCli\">FACTURA: </label>";
							print "<label id=\"labelFactura\" for=\"idCli\">" . zeroIzquierda($FACTURA) . " </label><br>";
						?>
					</div>
                </div>
				<div id="DivInferior">
					<!--LISTADO DE PLATAFORMAS-->
					<div id="DivSeleccionPlat">
						<p>Plataforma</p>
						<table>
							<?php
								if($sistema){
									$arrayPlataforma = [];
								
									$arrayPlataforma = listaPlataformasVenta($PLAT,$IDCLI);
									
									foreach($arrayPlataforma as $row){
										print $row;
									}
									
									if($PLAT != ""){
										$listado = true;
									}
								}
							?>
						</table>
					</div>
					<!--LISTADO DE ARTÍCULOS-->
					<div id="DivSeleccionArt">
						<p>Títulos</p>
						<table>
							<?php
								if($listado){
									$arrayArticulos = [];
									
									$arrayArticulos = listaArticulosVentas($PLAT,$IDCLI);
									
									print "<tr><th id=\"artID\"><a>ID</a></th><th id=\"artTITULO\"><a>TÍTULO</a></th><th id=\"artPRECIO\"><a>PRECIO</a></th><th id=\"artSTOCK\"><a>STOCK</a></th></tr>";
									
									foreach($arrayArticulos as $row){
										print $row;
									}
								}
							?>
						</table>
					</div>
					<!--LISTADO DE VENTA EN CURSO-->
					<div id="DivSeleccionVenta">
						<table>
							<p>Añadido</p>
							<?php
								if(ventaIniciada() && $sistema){
									$arrayVentas = [];
									
									$arrayVentas = listaVentas($IDCLI,$PLAT);
									
									print "<tr><th id=\"ventaARTID\"><a>ID</a></th><th id=\"ventaTITULO\"><a>TÍTULO</a></th><th id=\"ventaCANTIDAD\"><a>CANTIDAD</a></th><th id=\"ventaPRECIO\"><a>PRECIO</a></th><th id=\"ventaSUB\"><a>SUB</a></th></tr>";
									
									foreach($arrayVentas as $row){
										print $row;
									}
								}
							?>
						</table>
					</div>
					<!--SUMARIO DE VENTA: NÚMERO DE ARTÍCULOS, SUBTOTAL, IVA Y TOTAL-->
					<div id="DivSumarioVenta">
						<p>Sumario</p>
						<?php
							$arraySumario = valoressubtotal(); # devuelve array con valores
							
							$NUM_ART	= $arraySumario['NUM_ART'];
							$SUBTOTAL	= $arraySumario['SUBTOTAL'];
							$IVA		= $arraySumario['IVA'];
							$TOTAL		= $arraySumario['TOTAL'];
							
							if($NUM_ART == ""){$NUM_ART = 0;}
							if($SUBTOTAL == ""){$SUBTOTAL = 0;}
							if($IVA == ""){$IVA = 0;}
							if($TOTAL == ""){$TOTAL = 0;}
							
						?>
						<div id="DivSumarioVentaIzquierda">
							<div id="DivSumarioVentaLinea1">
								<div id="DivSumarioVentaLinea1Izq">
									<label for="totalArticulos" id="LabelTotalArticulos1">NUM. ARTÍCULOS: </label>
								</div>
								<div id="DivSumarioVentaLinea1Dcha">
									<label for="totalArticulos" id="LabelTotalArticulos2"><?=$NUM_ART?></label>
								</div>
							</div>
							<div id="DivSumarioVentaLinea2">
								<div id="DivSumarioVentaLinea2Izq">
									<label for="subtotal" id="LabelSubtotal1">SUBTOTAL: </label>
								</div>
								<div id="DivSumarioVentaLinea2Dcha">
									<label for="subtotal" id="LabelSubtotal2"><?=$SUBTOTAL. " €"?></label>
								</div>
							</div>
							<div id="DivSumarioVentaLinea3">
								<div id="DivSumarioVentaLinea3Izq">
									<label for="IVA" id="LabelIVA1">IVA (21%): </label>
								</div>
								<div id="DivSumarioVentaLinea3Dcha">
									<label for="IVA" id="LabelIVA2"><?=$IVA . " €"?></label>
								</div>
							</div>
							<div id="DivSumarioVentaLinea4">
								<div id="DivSumarioVentaLinea4Izq">
									<label for="total" id="LabelTotal1">TOTAL: </label>
								</div>
								<div id="DivSumarioVentaLinea4Dcha">
									<label for="total" id="LabelTotal2"><?=$TOTAL . " €"?></label>
								</div>
							</div>
						</div>
						<div id="DivSumarioVentaDerecha">
							<input type="button" value="FINALIZAR" onclick="finalizarVenta(<?=$FACTURA?>,'<?=$IDCLI?>')" />
						</div>
					</div>
				</div>
			</div>
            <div id="footer">
                <p>Powered by Julio Jiménez Delgado. Copyright (C) 2018 - 2019</p>
            </div>
        </div>
    </body>
</html>