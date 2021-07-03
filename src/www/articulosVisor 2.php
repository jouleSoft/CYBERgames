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
		<link rel="stylesheet" type="text/css" href="styleSheets/articulosVisor.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_articulos_comun.js"></script>
		<script type="text/javascript" src="scripts/mod_articulosVisor.js"></script>
        <?php
			# Comprobamos la existencia de parámetros GET.
			if(!isset($_GET['idArt'])){ 
				print "Error en parámetro GET";
			}else{
				
				$IDART = $_GET['idArt'];
				
				# Creación de conexión contra la base de datos
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				# Evaluación de error de conexión
				if(!$mysqli->connect_error){
					
					$sql = "SELECT * FROM ARTICULOS WHERE ID =$IDART";
					
					if($res = $mysqli->query($sql)){
						$fila = $res->fetch_assoc();
						
						$IDReal		= (int)$IDART;
						
						$IDART 		= zeroIzquierda($IDART);
						$TITULO		= utf8_encode($fila['NOMBRE']);
						$TIPO		= utf8_encode($fila['TIPO']);
						$PLATAFORMA	= utf8_encode($fila['PLATAFORMA']);
						$STOCK		= $fila['STOCK'];
						$PCOMPRA	= $fila['PCOMPRA'];
						$PVENTA		= $fila['PVENTA'];
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
				 <div id="formEditor">
					<form name="editor" id="editor" method="POST" autocomplete="on" action="/articulosCheck.php">
						<!--INPUTS DEL EDITOR-->
						<div class="divDataInput" id="formEditorData">
							<fieldset id="formEditorDatafield">
								<legend>DATOS ARTÍCULO</legend>
								<!--COLUMNA IZQUIERDA (ID ARTÍCULO)-->
								<div class="divColumna" id="columnaIzquierda">
									<label class="label" for="idArt" id="labelIdArt">ID <?=$IDART?></label>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID ARTÍCULOS)-->
								<!--COLUMNA DERECHA (DATOS ARTÍCULO)-->
								<div class="divColumna" id="columnaDerecha">
									<div class="divEtiquetaDatosSuperior">
										<label for="titulo" class="etiquetaTitulo"><?=$TITULO?></label><br>
									</div>
									<div class="divEtiquetaDatosInferior">
										<label for="genero" class="etiquetaDatosInferior" id="etiquetaInferiorIzq"><?="<a>GÉNERO: </a>" . $TIPO?></label>
										
										<label for="plataforma" class="etiquetaDatosInferior" id="etiquetaInferiorDcha"><?="<a>PLATAFORMA: </a>" . $PLATAFORMA?></label><br>
									</div>
								</div>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							</fieldset>
							<fieldset id="formEditorBusinessfield">
								<legend>DATOS DE NEGOCIO</legend>
								<div class="divFilaNegocio">
									<label for="stock" >STOCK</label>
									<label for="stockvalor" class="etiquetaNegocio" id="etiquetaStock"><?=$STOCK?></label><br>
								</div>
								<div class="divFilaNegocio">
									<label for="pcompra" >PRECIO DE COMPRA</label>
									<label for="pcompravalor" class="etiquetaNegocio" id="etiquetaPCompra"><?=$PCOMPRA . " €"?></label><br>
								</div>
								<div class="divFilaNegocio">
									<label for="pventa" >PRECIO DE VENTA</label>
									<label for="pventavalor" class="etiquetaNegocio" id="etiquetaPVenta"><?=$PVENTA . " €"?></label><br>
								</div>
							</fieldset>
							<div class="divFilaBotones">
								<input class="botónCancelar" type="button" onclick="cancelar()" value="VOLVER"/>
								<input class="botónEliminar" type="button" value="ELIMINAR" onclick="eliminar(<?=$IDReal?>)"/>
								<input class="botónEdicion" type="button" value="EDITAR" onclick="editar(<?=$IDReal?>)"/>
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