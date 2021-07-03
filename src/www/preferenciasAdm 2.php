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

require('modules/mod_preferencias.php');
# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# passwordCheck($username[string],$$pwOld[string],$pwNew1[string],$pwNew2[string]): Si la contraseña antigua es correcta y la nueva y su verificación coinciden, devuelve true.
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/preferencias.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_preferenciasAdm.js"></script>
        <?php
			# Comprobar parámetros POST
			if(isset($_POST['pwOld']) || isset($_POST['pwNew1']) || isset($_POST['pwNew2'])){
				
				$PWOLD	= (string)$_POST['pwOld'];
				$PWNEW1	= (string)$_POST['pwNew1'];
				$PWNEW2	= (string)$_POST['pwNew2'];
				
				if(passwordCheck($username,$PWOLD,$PWNEW1,$PWNEW2)){ # Comprobación de credenciales: antiguo y nuevo.
					
					$CYPH_PW	= password_hash($PWNEW1,PASSWORD_DEFAULT); # Creamos hash para nueva contraseña.
					
					# Actualizamos el registro del usuario
					$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
					if(!$mysqli->connect_error){
						$sql	= "UPDATE USUARIOS SET PASS = '$CYPH_PW' WHERE USERNAME = '$username'";
						if($res = $mysqli->query($sql)){
							print "<script>alert('Contraseña cambiada correctamente');</script>";
						}else{ # Fallo en la ejecución del mandato SQL.
							print "<script>alert('Se ha producido un error al cambiar la contraseña. Por favor, póngase en contacto con su administrador');</script>";
						}
					}else{
						die("Conexión fallida: " . $mysqli->connect_error);
					}
				}else{ # En caso de que no coincidan las contraseñas: alerta javaScript.
					print "<script>alert('Error: La contraseña antigua o la verificación de la nueva no coinciden.');</script>";
				}
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
					<form name="editor" id="editor" method="POST" autocomplete="on" action="preferenciasAdm.php">
						<!--ETIQUETAS DEL VISOR-->
						<div class="divFila" id="divUsername">
							<div style="display: inline-block; margin-left: 15px">
								<label for ="nombreApellidos" id="nombreApellidos"><?=strtoupper($ape_usr) . ", " . strtoupper($nombre_usr)?></label>
							</div>
							<div style="display: inline-block; margin-left: 15px">
								<label for="username" id="bracketIzq">[</label>
								<label for="username" id="username"><?=strtoupper($username)?></label>
								<label for="username" id="bracketDcha">]</label>
							</div>
							<div style="display: inline-block; margin-right: 15px; float: right">
								<label for="tipoVentana" id="tipoVentana">ADM</label>
							</div>
						</div>
						<div class="divFila" id="filaPW" style="margin-top: 100px; border-top: 1px solid gray; padding-top: 15px">
							<div>
								<label for="cambiarPW">CONTRASEÑA ANTIGUA</label>
							</div>
							<div style="margin-left: 100px">
								<input type="password" name="pwOld" required />
							</div>
						</div>
						<div class="divFila" id="filaPW">
							<div>
								<label for="cambiarPW">CONTRASEÑA NUEVA</label>
							</div>
							<div style="margin-left: 112px">
								<input type="password" name="pwNew1" required />
							</div>
						</div>
						<div class="divFila" id="filaPW" style="border-bottom: 1px solid gray; padding-bottom: 15px">
							<div>
								<label for="cambiarPW">VERIFICAR CONTRASEÑA NUEVA</label>
							</div>
							<div style="margin-left: 47px">
								<input type="password" name="pwNew2" required />
							</div>
						</div>
						<div class="divFilaBotones2" id="filaBotones">
							<input type="button" id="volverBtn" value="VOLVER" onclick="volver()" /><!--Ir a index.php-->
							<!--
							scripts/mod_preferenciasAdm.js:5
							--------------------------------
							admUsuarios(): abre el documento php para la administración de usuarios.
							-->
							<input type="button" id="userAdmBtn" value="ADMINISTRAR USUARIOS" onclick="admUsuarios()" />
							<input style="background-color: red; color: white" type="submit" id="saveBtn" value="GUARDAR CAMBIOS" />
						</div>
						<!--|FIN| ETIQUETAS DEL VISOR-->
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