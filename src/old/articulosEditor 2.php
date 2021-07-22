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

require('modules/mod_articulos.php');
# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# existeArticulo($nombre[string],$plataforma[string]): devuelve true si el artículo existe para una plataforma determinada.
# nuevoID(): genera un nuevo id para añadir un nuevo artículo. El algoritmo rellenará espacios generados al eliminar registros.

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/articulosEditor.css"/>
		<script type="text/javascript" src="scripts/mod_articulos_comun.js"></script>
		<script type="text/javascript" src="scripts/mod_articulosEditor.js"></script>
		<script type = "text/javascript">
			comprobarSelect(window.location.href);
		</script>
        <?php
			# Comprobamos la existencia de parámetros GET.
			if(!isset($_GET['idArt'])){ 
				print "Error en parámetro GET";
			}else{
				# Datos $_GET para preparar el contenido del formulario.
				if(isset($_GET['op'])){
					$op 	= $_GET['op'];
				}
				
				# Creación de conexión contra la base de datos
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				# Evaluación de error de conexión
				if(!$mysqli->connect_error){
					
					switch($op){
						case "editar": # En caso de edición, hay que recuperar los datos del artículo a editar.
							
							$idArt	= $_GET['idArt'];
								
							if($res = $mysqli->query("SELECT * FROM ARTICULOS WHERE ID=$idArt")){
								if($res->num_rows == 1){
									# Recuperamos datos, al buscarse por ID, el resultado será de una fila.
									$fila = $res->fetch_assoc();
									
									$IDART 		= zeroIzquierda($idArt);
									$TITULO		= utf8_encode($fila['NOMBRE']);
									$TIPO		= utf8_encode($fila['TIPO']);
									$PLATAFORMA	= utf8_encode($fila['PLATAFORMA']);
									$STOCK		= $fila['STOCK'];
									$PCOMPRA	= $fila['PCOMPRA'];
									$PVENTA		= $fila['PVENTA'];
								}else{
									print "Inesperado número de filas devuelto";
								}
							}
							
							break;
							
						case "nuevo": # En caso de nuevo artículo, inicializamos el array delimitando los índices.
						
							if($res = $mysqli->query("SELECT COUNT(*)+1 FROM ARTICULOS")){ # Crear un nuevo ID.
								if($res->num_rows == 1){
									# Recuperamos datos, al realizar un conteo de filas en tabla (+1), el resultado será
									# de una sola fila.
									$arrayArt['ID'] = $res->fetch_assoc(); 
								}else{
									print "Inesperado número de filas devuelto";
								}
							}
							
							$idArt 					= "";
							
							$arrayArt['ID']			= nuevoID();
							$arrayArt['NOMBRE'] 	= "";
							$arrayArt['TIPO'] 		= "";
							$arrayArt['PLATAFORMA'] = "";
							$arrayArt['STOCK'] 		= "";
							$arrayArt['PCOMPRA']	= "";
							$arrayArt['PVENTA']		= "";
							
							break;
					}
					
					$res->close();
					
					# RECUPERAR EL CONTENIDO EN ARRAYS PARA LOS ELEMENTOS SELECT: GÉNERO Y PLATAFORMA
					
					# SELECT GÉNERO
					$arrayGenero = array('GÉNERO...');

					if($res = $mysqli->query("SELECT TIPO FROM TIPOS ORDER BY TIPO ASC")){
						if($res->num_rows > 0){
							# Recuperamos datos para crear lista de géneros disponibles
							while($fila = $res->fetch_assoc()){
								$arrayGenero[] = $fila['TIPO'];
							}
						}else{
							print "Inesperado número de filas devuelto";
						}
					}
					
					# SELECT PLATAFORMA
					$arrayPlataforma = array('PLATAFORMA...');
					
					if($res = $mysqli->query("SELECT PLATAFORMA FROM PLATAFORMAS ORDER BY PLATAFORMA ASC")){
						if($res->num_rows > 0){
							# Recuperamos datos para crear lista de géneros disponibles
							while($fila = $res->fetch_assoc()){
								$arrayPlataforma[] = $fila['PLATAFORMA'];
							}
						}else{
							print "Inesperado número de filas devuelto";
						}
					}

					if($op == 'editar'){
						$labelIDValue	= $IDART;
						$tituloValue 	= $TITULO;
						$tipoValue		= $TIPO;
						$platValue		= $PLATAFORMA;
						$stockValue		= $STOCK;
						$pcompraValue	= $PCOMPRA;
						$pventaValue	= $PVENTA;
						$actionValue	= '/articulosUpdate.php?idArt=' . $idArt;
						$submitBtnValue = 'ACTUALIZAR';
					}else{
						$labelIDValue	= zeroIzquierda(nuevoID());
						$tituloValue 	= 'TÍTULO';
						$stockValue		= '0';
						$pcompraValue	= '0';
						$pventaValue	= '0';
						$actionValue	= '/articulosCheck.php';
						$submitBtnValue = 'ACEPTAR';
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
					<form name="editor" id="editor" method="POST" autocomplete="on" action="<?=$actionValue?>">
						<!--INPUTS DEL EDITOR-->
						<div class="divDataInput" id="formEditorData">
							<fieldset id="formEditorDatafield">
								<legend>DATOS ARTÍCULO</legend>
								<!--COLUMNA IZQUIERDA (ID ARTÍCULO)-->
								<div class="divColumna" id="columnaIzquierda">
									<label class="label" for="idArt" id="labelIdArt">ID <?=$labelIDValue?></label>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID ARTÍCULOS)-->
								<!--COLUMNA DERECHA (DATOS ARTÍCULO)-->
								<div class="divColumna" id="columnaDerecha">
									<input class="inputTexto" id="inputTítulo" type="text" name="titulo" value="<?=$tituloValue?>" onfocus="if(this.value=='TÍTULO') this.value='';" onblur="if(this.value=='') this.value='TÍTULO';" maxlength="60" autofocus required /><br>
									<select class="selector" id="selectorGénero" name="tipo" form="editor">
									<?php
										$contador = 0;
										
										foreach($arrayGenero as $g){
											$inputTag = "<option id=\"g$contador\" value=\"$g\">$g</option>";
											print $inputTag;
											
											$contador++;
										}
									?>
									</select>
									<!--
									scripts/mod_articulosEditor.js:24
									------------------------
									añadirGenero(op,idArt): añadir un nuevo elemento a la lista de selección de géneros.
									-->
									<a class="añadirElemento" onclick="añadirGenero('<?=$op?>','<?=$idArt?>')">+</a>
									
									<select class="selector" id="selectorPlataforma" name="plataforma" form="editor">
									<?php
										$contador = 0;
										
										foreach($arrayPlataforma as $p){
											$inputTag = "<option id=\"p$contador\" value=\"$p\">$p</option>";
											print $inputTag;
											
											$contador++;
										}
									?>
									</select>
									<!--
									scripts/mod_articulosEditor.js:16
									------------------------
									añadirPlataforma(op,idArt): añadir un nuevo elemento a la lista de selección de plataformas.
									-->
									<a class="añadirElemento" onclick="añadirPlataforma('<?=$op?>','<?=$idArt?>')">+</a>
									<?php 
										echo "<script type=\"text/javascript\">";
										# scripts/mod_articulosEditor.js:9
										# --------------------------------
										# valoresEditar(genero,plataforma,op): si op == 'editar', selecciona los valores del artículo a editar
										# en ambos <select>.
										echo "valoresEditar('$TIPO','$PLATAFORMA','$op');";
										echo "</script>";
									?>
									
								</div>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							</fieldset>
							<fieldset id="formEditorBusinessfield">
								<legend>DATOS DE NEGOCIO</legend>
								<div class="divFilaNegocio">
									<label for="stock" >STOCK</label>
									<input class="inputTexto" id="inputStock" type="text" name="stock" value="<?=$stockValue?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" maxlength="4" required /><br>
								</div>
								<div class="divFilaNegocio">
									<label for="pcompra" >PRECIO DE COMPRA</label>
									<input class="inputTexto" id="inputPCompra" type="text" name="pcompra" value="<?=$pcompraValue?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" maxlength="7" required /><br>
								</div>
								<div class="divFilaNegocio">
									<label for="pventa" >PRECIO DE VENTA</label>
									<input class="inputTexto" id="inputPVenta" type="text" name="pventa" value="<?=$pventaValue?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" maxlength="7" required /><br>
								</div>
							</fieldset>
							<div class="divFilaBotones">
								<input class="botónCancelar" type="button" onclick="cancelar()" value="CANCELAR"/>
								<input class="botónAceptar" type="submit" value="<?=$submitBtnValue?>"/>
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