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
		<link rel="stylesheet" type="text/css" href="styleSheets/facturasVisor.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type = "text/javascript">
			var cancelar = function (){ //redirige a 'facturas.php'
				window.location.assign("facturas.php");
			}
		</script>
        <?php
			# Comprobamos la existencia de parámetros GET.
			if(!isset($_GET['idFact'])){ 
				print "Error en parámetro GET";
			}else{
				# Datos $_GET para preparar el contenido del formulario.
				
				$IDFACT = $_GET['idFact'];
				
				# Creación de conexión contra la base de datos
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				# Evaluación de error de conexión
				if(!$mysqli->connect_error){
					
					# SELECCIONAMOS TODOS LOS CAMPOS, ADEMÁS CREAMOS LA VARIABLE SQL 'FECHA_FORM' PARA MOSTRAR FECHA FORMATEADA.
					
					$sql = "SELECT *,DATE_FORMAT(FECHA,'%d-%m-%Y') AS FECHA_FORM FROM FACTURAS WHERE IDFACT = '$IDFACT'";
					
					if($res = $mysqli->query($sql)){
						$fila = $res->fetch_assoc();
						
						$IDFACT 	= $fila['IDFACT'];
						$fecha 		= $fila['FECHA_FORM'];
						$idcli 		= strtoupper($fila['IDCLI']);
						$subtotal  	= "€ " . number_format($fila['SUBTOTAL'],2,'.',' ');
						$iva		= "€ " . number_format($fila['IVA'],2,'.',' ');
						$total		= "€ " . number_format($fila['TOTAL'],2,'.',' ');
					}else{
						print "Inesperado número de filas devuelto";
					}
				}else{
					die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
				}
				$mysqli->close();
			}
        ?>
    </head>
    <body>
				<!--BLOQUE GENERAL-->
        <div class="core">
			<!--CABECERA-->
            <div id="header">
                <div id="logo">
                    <p><a>CYBER</a>Games</p>
                </div>
            </div>
			<!--|FIN| CABECERA-->
			<!--CONTENIDO PRINCIPAL DE LA PÁGINA-->
            <div id="main" style="padding-top: 25px">
                <div style="width: auto; height: auto; margin: auto;">
					<!--BARRA DE BOTONES-->
					<div id="button_bar">

					</div>
					<!--|FIN| BARRA DE BOTONES-->
                </div>
				<!--CUERPO DEL EDITOR DE ARTÍCULOS-->
				 <div id="formVisor">
					<form name="editor" id="editor" method="POST" autocomplete="on" action="clientesEditor.php">
						<!--INPUTS DEL EDITOR-->
						<div class="divDataInput" id="formEditorData">
							<fieldset id="formEditorDatafield">
								<legend>DATOS DE FACTURACIÓN</legend>
								<!--COLUMNA IZQUIERDA (ID FACTURA)-->
								<div class="divColumna" id="columnaIzquierda">
									<label class="label" for="idFact">FACTURA</label>
									<label id="labelidfact" for="idFact"><?=zeroIzquierda($IDFACT)?></label><br>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID CLIENTE)-->
								<!--COLUMNA DERECHA (DATOS CLIENTE)-->
								<div class="divColumna" id="columnaDerecha">
									<div class="divColumna" id="columnaDatFactIzq">
										<label class="label" for="fecha">DNI</label>
										<label id="labeldni" for="fecha"><?=$idcli?></label><br>
									</div>
									<div class="divColumna" id="columnaDatFactDcha">
										<label class="label" for="fecha">FECHA</label>
										<label id="labelfecha" for="fecha"><?=$fecha?></label><br>
									</div>
								</div>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							</fieldset>
							<fieldset id="formEditorCampoContacto">
								<legend>FACTURADO</legend>
								<div class="divFila">
									<div style="display:inline-block" id="divFilaIzq">
										<label for="subtotal" >SUBTOTAL</label>
									</div>
									<div style="display:inline-block" id="divFilaDcha">
										<label for="subtotal" id="labelsubtotal" ><?=$subtotal?></label><br>
									</div>
								</div>
								<div class="divFila">
									<div style="display:inline-block" id="divFilaIzq">
										<label for="iva" >IVA</label>
									</div>
									<div style="display:inline-block" id="divFilaDcha">
										<label id="labeliva" ><?=$iva?></label><br>
									</div>
								</div>
								<div class="divFila">
									<div style="display:inline-block" id="divFilaIzq">
										<label for="total" >TOTAL</label>
									</div>
									<div style="display:inline-block" id="divFilaDcha">
										<label for="total" id="labeltotal"><?=$total?></label><br>
									</div>
								</div>
							</fieldset>
							<div class="divFilaBotones">
								<input class="botónCancelar" id="btncancelar" type="button" onclick="cancelar()" value="VOLVER"/>
							</div>
						</div>
						<!--|FIN| INPUTS DEL EDITOR-->
					</form>
				</div>
				<!--PIE DE PÁGINA-->
				<div id="footer">
					<p>Powered by Julio Jiménez Delgado. Copyright (C) 2018 - 2019</p>
				</div>
				<!--|FIN| PIE DE PÁGINA-->
			</div>
			<!--|FIN| CONTENIDO PRINCIPAL DE LA PÁGINA-->
        </div>
		<!--|FIN| BLOQUE GENERAL-->
    </body>
</html>