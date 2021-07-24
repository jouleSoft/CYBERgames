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
		<link rel="stylesheet" type="text/css" href="styleSheets/clientesVisor.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_clientesVisor.js"></script>
        <?php
			# Comprobamos la existencia de parámetros GET.
			if(!isset($_GET['idCli'])){ 
				print "Error en parámetro GET";
			}else{
				# Datos $_GET para preparar el contenido del formulario.
				
				$IDCLI = $_GET['idCli'];
				
				# Creación de conexión contra la base de datos
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				# Evaluación de error de conexión
				if(!$mysqli->connect_error){
					
					$sql = "SELECT * FROM CLIENTES WHERE ID = '$IDCLI'";
					
					if($res = $mysqli->query($sql)){
						$fila = $res->fetch_assoc();
						
						$NOMBRE		= utf8_encode($fila['NOMBRE']);
						$APELLIDOS	= utf8_encode($fila['APELLIDOS']);
						$TELEFONO1	= $fila['TELEFONO1'];
						$TELEFONO2	= $fila['TELEFONO2'];
						$EMAIL		= $fila['EMAIL'];
						$DIRECCION	= utf8_encode($fila['DIRECCION']);
						$CPOSTAL	= $fila['CPOSTAL'];
					}
				}else{
					die("Error al conectar con la base de datos. Error: " . $mysqli->connect_error);
				}
				$mysqli->close();
			}
        ?>
		<style>
			
		</style>
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
					<form name="editor" id="editor" method="POST" autocomplete="on" action="clientesEditor.php">
						<!--INPUTS DEL EDITOR-->
						<div class="divDataInput" id="formEditorData">
							<fieldset id="formEditorDatafield">
								<legend>DATOS PERSONALES</legend>
								<!--COLUMNA IZQUIERDA (ID CLIENTE)-->
								<div class="divColumna" id="columnaIzquierda">
									<label class="label" for="idCli" id="labelDNI">DNI</label>
									<label id="labelDNI" for="idCli" id="labelDNI"><?=$IDCLI?></label><br>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID CLIENTE)-->
								<!--COLUMNA DERECHA (DATOS CLIENTE)-->
								<div class="divColumna" id="columnaDerecha">
									<div class="divColumna" id="columnaNomApe">
										<label class="label" for="nombre">NOMBRE</label>
										<label id="labelNombre" for="nombre" id="labelNombre"><?=$NOMBRE?></label><br>
									</div>
									<div class="divColumna" id="columnaNomApe">
										<label class="label" for="apellidos">APELLIDOS</label>
										<label id="labelApellidos" for="nombre" id="labelApellidos"><?=$APELLIDOS?></label><br>
									</div>
								</div>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							</fieldset>
							<fieldset id="formEditorCampoContacto">
								<legend>DATOS DE CONTACTO</legend>
								<div class="divFilaContacto">
									<label for="telefono1" >TELEFONO1</label>
									<label for="telefono1" id="labelTelefono1" ><?=$TELEFONO1?></label><br>
								</div>
								<div class="divFilaContacto">
									<label for="telefono2" >TELEFONO2</label>
									<label id="labelTelefono2" ><?=$TELEFONO2?></label><br>
								</div>
								<div class="divFilaContacto">
									<label for="email" >EMAIL</label>
									<label for="email" id="labelEmail"><?=$EMAIL?></label><br>
								</div>
							</fieldset>
							<fieldset id="formEditorCampoDomicilio">
								<legend>DOMICILIO</legend>
								<div class="divFilaDomicilio">
									<label for="dirección" >DIRECCIÓN</label>
									<label for="dirección" id="labelDireccion"><?=$DIRECCION?></label><br>
								</div>
								<div class="divFilaDomicilio">
									<label for="cpostal" >CPOSTAL</label>
									<label for="cpostal" id="labelCpostal"><?=$CPOSTAL?></label><br>
								</div>
							</fieldset>
							<div class="divFilaBotones">
								<input class="botónCancelar" type="button" onclick="cancelar()" value="VOLVER"/>
								<input class="botónEliminar" type="button" value="ELIMINAR" onclick="eliminar('<?=$IDCLI?>')"/>
								<input class="botónEdicion" type="button" value="EDITAR" onclick="editar('<?=$IDCLI?>')"/>
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