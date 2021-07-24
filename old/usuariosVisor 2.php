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
	$usr		= $_SESSION['username'];
    $nombre_usr = $_SESSION['nombre'];
	$ape_usr	= $_SESSION['apellidos'];
	$admin		= $_SESSION['admin'];
	
	if($admin == 0){
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
		<link rel="stylesheet" type="text/css" href="styleSheets/usuariosVisor.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_usuariosVisor.js"></script>
        <?php
			# Comprobar parámetro GET
			# la existencia del pará
			if(!isset($_GET['idUsr'])){ 
				print "Error en parámetro GET";
			}else{
				
				$IDUSR = strtoupper($_GET['idUsr']);
				
				# CAMBIO DE CONTRASEÑA: el cambio administrativo de contraseña se ejecuta si existen $_POST['pwNew1'] y $_POST['pwNew2']
				if(isset($_POST['pwNew1']) && isset($_POST['pwNew2'])){
					
					$PWNEW1	= (string)$_POST['pwNew1'];
					$PWNEW2	= (string)$_POST['pwNew2'];
					
					if($PWNEW1 == $PWNEW2 && $PWNEW1 != ""){ # Si coincide la nueva contraseña con su verificación. No válida contraseña en blanco.
						
						$CYPH_PW	= password_hash($PWNEW1,PASSWORD_DEFAULT); # Creamos hash para nueva contraseña.
						
						#Crear conexión con la base de datos.
						$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
						if(!$mysqli->connect_error){ # Actualizamos registro en tabla USUARIOS.
							$sql	= "UPDATE USUARIOS SET PASS = '$CYPH_PW' WHERE USERNAME = '$IDUSR'";
							if($res = $mysqli->query($sql)){
								print "<script>alert('Contraseña cambiada correctamente');</script>";
							}else{
								print "<script>alert('Se ha producido un error al cambiar la contraseña. Por favor, póngase en contacto con su administrador');</script>";
							}
						}else{
							die("Conexión fallida: " . $mysqli->connect_error);
						}
						$mysqli->close();
					}else{
						print "<script>alert('Error: La nueva contraseña debe de coincidir con su verificación');</script>";
					}
				}

				# DATOS PARA FORMULARIO
				
				# Creación de conexión contra la base de datos
				$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
				
				# Evaluación de error de conexión
				if(!$mysqli->connect_error){
					
					$sql = "SELECT USERNAME,NOMBRE,APELLIDOS,ADMIN FROM USUARIOS WHERE USERNAME = '$IDUSR'";
					
					if($res = $mysqli->query($sql)){
						$fila = $res->fetch_assoc();
						
						$NOMBRE		= utf8_encode($fila['NOMBRE']);
						$APELLIDOS	= utf8_encode($fila['APELLIDOS']);
						if($fila['ADMIN']==0){$ADMIN='ESTÁNDAR';}else{$ADMIN = 'ADMINISTRADOR';}
					}
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
				<!--CUERPO DEL EDITOR DE USUARIOS-->
				 <div id="formEditor">
					<form name="editor" id="editor" method="POST" autocomplete="on" action="usuariosVisor.php?idUsr=<?=$IDUSR?>">
						<!--INPUTS DEL EDITOR-->
						<div class="divDataInput" id="formEditorData">
							<fieldset id="formEditorDatafield">
								<legend>DATOS DEL USUARIO</legend>
								<!--COLUMNA IZQUIERDA (ID USUARIO)-->
								<div class="divColumna" id="columnaIzquierda">
									<label id="labelDNI" for="idUsr" id="labelDNI"><?=$IDUSR?></label><br>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID USUARIO)-->
								<!--COLUMNA DERECHA (DATOS USUARIO)-->
								<div class="divColumna" id="columnaDerecha">
									<div class="divFila" id="divFilaNombre">
										<div>
											<label for="nombre" id="nombre">NOMBRE:</label>
										</div>
										<div style="margin-left: 102px">
											<label for="nombre" id="nombre"><?=$NOMBRE?></label>
										</div>
									</div>
									<div class="divFila" id="divFilaApe">
										<div>
											<label for="apellidos" id="apellidos">APELLIDOS:</label>
										</div>
										<div style="margin-left: 88px">
											<label for="apellidos" id="apellidos"><?=$APELLIDOS?></label>
										</div>
									</div>
									<div class="divFila" id="divFilaAdmin">
										<div>
											<label for="admin" id="admin">TIPO DE USUARIO:</label>
										</div>
										<div style="margin-left: 50px">
											<label for="admin" id="admin"><?=$ADMIN?></label>
										</div>
									</div>
								</div>
							</fieldset>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							<fieldset>
								<legend>RESTABLECER CONTRASEÑA</legend>
								<div style="inline-block; width: 63%; float: left">
									<div class="divFila" id="filaPW" style="padding-top: 15px">
										<div>
											<label for="cambiarPW">CONTRASEÑA NUEVA</label>
										</div>
										<div style="margin-left: 112px">
											<input type="password" name="pwNew1" required />
										</div>
									</div>
									<div class="divFila" id="filaPW" style="padding-bottom: 15px">
										<div>
											<label for="cambiarPW">VERIFICAR CONTRASEÑA NUEVA</label>
										</div>
										<div style="margin-left: 47px">
											<input type="password" name="pwNew2" required />
										</div>
									</div>
								</div>
								<div style="width: 20%; margin: 3% 0; float: right;">
									<input type="submit" value="CAMBIAR">
								</div>
							</fieldset>
							<div class="divFilaBotones">
								<input class="botónCancelar" type="button" onclick="cancelar()" value="VOLVER"/>
								<input id="btnEliminar" class="botónEliminar" type="button" value="ELIMINAR" onclick="eliminar('<?=$IDUSR?>')"/>
								<input class="botónEdicion" type="button" value="EDITAR" onclick="editar('<?=$IDUSR?>')"/>
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
	<script type="text/javascript">
		// Si el usuario logado es al mismmo que se vusualiza. desactivar botón 'Eliminar'
		var botonEliminar = function(currentUser,username){
			if (currentUser == username){
				document.getElementById('btnEliminar').disabled = true;
			}
		}
		
		//Ejecutar función
		botonEliminar('<?=$usr?>','<?=$IDUSR?>');
	</script>
</html>