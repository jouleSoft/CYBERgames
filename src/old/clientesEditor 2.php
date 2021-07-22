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
		<link rel="stylesheet" type="text/css" href="styleSheets/clientesEditor.css"/>
		<script type="text/javascript" src="scripts/mod_clientesEditor.js"></script>
		<script type = "text/javascript">
			comprobarCampos(window.location.href);
		</script>
        <?php
			
			if(!isset($_GET['idCli'])){ 
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
						case "editar": # En caso de edición, hay que recuperar los datos del cliente a editar.
							
							$idCli	= $_GET['idCli'];
								
							if($res = $mysqli->query("SELECT * FROM CLIENTES WHERE ID LIKE '$idCli'")){
								if($res->num_rows == 1){
									# Recuperamos datos, al buscarse por ID, el resultado será de una fila.
									$fila = $res->fetch_assoc();
									
									$IDCLI 		= utf8_encode($fila['ID']);
									$NOMBRE		= utf8_encode($fila['NOMBRE']);
									$APELLIDOS	= utf8_encode($fila['APELLIDOS']);
									$TELEFONO1	= utf8_encode($fila['TELEFONO1']);
									$TELEFONO2	= utf8_encode($fila['TELEFONO2']);
									$EMAIL		= utf8_encode($fila['EMAIL']);
									$DIRECCION	= utf8_encode($fila['DIRECCION']);
									$CPOSTAL	= utf8_encode($fila['CPOSTAL']);
								}else{
									print "Inesperado número de filas devuelto";
								}
								$res->close();
							}
							
							break;
					}
					
					# FORMULARIOS: Preparemmos el formulario en función del parámetro 'op' devuelto por GET.
					#
					# $actionValue: será valor del parámetro 'action' del elemento HTML FORM.
					# $submitBtnValue: valor del elemento HTML INPUT[type="submit"]
					if($op == 'editar'){
						$dniValue		= $IDCLI;
						$nombreValue 	= $NOMBRE;
						$apellidosValue	= $APELLIDOS;
						$telefono1Value	= $TELEFONO1;
						$telefono2Value	= $TELEFONO2;
						$emailValue		= $EMAIL;
						$direccionValue	= $DIRECCION;
						$cpostalValue	= $CPOSTAL;
						$actionValue	= '/clientesUpdate.php?idCli=' . strtoupper($idCli);
						$submitBtnValue = 'ACTUALIZAR';
					}else{
						$dniValue		= "";
						$nombreValue 	= "";
						$apellidosValue	= "";
						$telefono1Value	= "";
						$telefono2Value	= "";
						$emailValue		= "";
						$direccionValue	= "";
						$cpostalValue	= "";
						$actionValue	= '/clientesCheck.php';
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
								<legend>DATOS PERSONALES</legend>
								<!--COLUMNA IZQUIERDA (ID CLIENTE)-->
								<div class="divColumna" id="columnaIzquierda">
									<label class="label" for="idCli" id="labelDNI">DNI*</label>
									<input class="inputTexto" id="inputDNI" type="text" name="dni" value="<?=$dniValue?>" maxlength="9" autofocus required /><br>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID CLIENTE)-->
								<!--COLUMNA DERECHA (DATOS CLIENTE)-->
								<div class="divColumna" id="columnaDerecha">
									<div class="divColumna" id="columnaNomApe">
										<label class="label" for="idCli" id="labelNombre">NOMBRE*</label>
										<input class="inputTexto" id="inputNombre" type="text" name="nombre" value="<?=$nombreValue?>" maxlength="25" required /><br>									
									</div>
									<div class="divColumna" id="columnaNomApe">
										<label class="label" for="idCli" id="labelApellidos">APELLIDOS*</label>
										<input class="inputTexto" id="inputApellidos" type="text" name="apellidos" value="<?=$apellidosValue?>" maxlength="35" required /><br>									
									</div>
								</div>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							</fieldset>
							<fieldset id="formEditorCampoContacto">
								<legend>DATOS DE CONTACTO</legend>
								<div class="divFilaContacto">
									<label for="stock" >TELEFONO1*</label>
									<input class="inputTexto" id="inputTelefono1" type="text" name="telefono1" value="<?=$telefono1Value?>" maxlength="9" required /><br>
								</div>
								<div class="divFilaContacto">
									<label for="pcompra" >TELEFONO2</label>
									<input class="inputTexto" id="inputTelefono2" type="text" name="telefono2" value="<?=$telefono2Value?>" maxlength="9" /><br>
								</div>
								<div class="divFilaContacto">
									<label for="pventa" >EMAIL*</label>
									<input class="inputTexto" id="inputEmail" type="text" name="email" value="<?=$emailValue?>" maxlength="35" required /><br>
								</div>
							</fieldset>
							<fieldset id="formEditorCampoDomicilio">
								<legend>DOMICILIO</legend>
								<div class="divFilaDomicilio">
									<label for="dirección" >DIRECCIÓN</label>
									<input class="inputTexto" id="inputDireccion" type="text" name="direccion" value="<?=$direccionValue?>" maxlength="50" /><br>
								</div>
								<div class="divFilaDomicilio">
									<label for="cpostal" >CPOSTAL*</label>
									<input class="inputTexto" id="inputCpostal" type="text" name="cpostal" value="<?=$cpostalValue?>" maxlength="5" required /><br>
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