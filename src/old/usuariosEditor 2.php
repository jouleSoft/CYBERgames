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

require('modules/mod_usuarios.php');

# -- variables importadas --

# [ninguna]

# -- funciones importadas --

# existeUsername($username[string]): devuelve true si el username ya está en uso en la tabla USUARIOS.

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>·CYBER·Games·</title>
        <link rel="stylesheet" type="text/css" href="styleSheets/general.css"/>
		<link rel="stylesheet" type="text/css" href="styleSheets/usuariosEditor.css"/>
        <script type="text/javascript" src="scripts/mod_general.js"></script>
		<script type="text/javascript" src="scripts/mod_usuariosEditor.js"></script>
        <?php
			# --Se recogen datos--
			if(!isset($_GET['idUsr']) || !isset($_GET['op'])){ 
				print "Error en parámetro GET";
			}else{
				# Datos $_GET para preparar el contenido del formulario.
				
				$OP		= $_GET['op'];
				
				# OPERACIONES: nuevo usuario / editar usuario.
				
				# NUEVO: Se prepara el formulario
				switch($OP){
					case "nuevo":
						if(isset($_GET['existe'])){
							$IDUSR 		= strtoupper($_GET['idUsr']);
							$u			= strtoupper($_GET['username']);
							$NOMBRE		= strtoupper($_GET['nombre']);
							$APELLIDOS	= strtoupper($_GET['apellidos']);
						}else{
							$IDUSR 		= "";
							$u 			= $IDUSR;
							$NOMBRE		= "";
							$APELLIDOS	= "";
						}
						$ADMIN		= 0;
						
						$submitBtnValue		= "ACEPTAR";
						
						break;
					
					case "editar":
						
						if(isset($_GET['existe'])){
							$IDUSR 		= strtoupper($_GET['idUsr']);
							$u			= strtoupper($_GET['username']);
							$NOMBRE		= strtoupper($_GET['nombre']);
							$APELLIDOS	= strtoupper($_GET['apellidos']);
							$ADMIN		= 0;
						}else{
							$mysqli = new mysqli("localhost","cybergames","cybergames","cybergames");
							
							if(!$mysqli->connect_error){
								
								$IDUSR 		= strtoupper($_GET['idUsr']);
								$sql = "SELECT USERNAME,NOMBRE,APELLIDOS,ADMIN FROM USUARIOS WHERE USERNAME = '$IDUSR'";
								
								if($res = $mysqli->query($sql)){
									$fila = $res->fetch_assoc();
									
									$u			= $IDUSR;
									$NOMBRE		= utf8_encode($fila['NOMBRE']);
									$APELLIDOS	= utf8_encode($fila['APELLIDOS']);
									$ADMIN		= $fila['ADMIN'];
								}
							}else{
								die("No es posible conectar con la base de datos. Err: " . $mysqli->connect_error);
							}
							$mysqli->close();
						}
						
						$submitBtnValue		= "ACTUALIZAR";
						
						break;
						
					case "existe": # En caso de que el usuario exista.
						
						if(!isset($_GET['nombre']) || !isset($_GET['apellidos']) || !isset($_GET['username'])){
							print "Error en parámetro GET";
						}else{
							
							$IDUSR 		= strtoupper($_GET['idUsr']);
							$u			= strtoupper($_GET['username']);
							$NOMBRE		= strtoupper($_GET['nombre']);
							$APELLIDOS	= strtoupper($_GET['apellidos']);
							$ADMIN		= 0;
							
							
						}
						break;
				}	
				
				$usernameInput	= "<input type=\"text\" name=\"username\" value=\"$u\" maxlength=\"15\" autofocus required />";
				$nombreInput	= "<input type=\"text\" name=\"nombre\" value=\"$NOMBRE\" maxlength=\"25\" required />";
				$apeInput		= "<input type=\"text\" name=\"apellidos\" value=\"$APELLIDOS\" maxlength=\"35\" required />";
				
				if($ADMIN == 1){
					$admChkBox 	= "<input type=\"checkbox\" name=\"admin\" value=\"admin\" checked>";
				}else{
					$admChkBox 	= "<input type=\"checkbox\" name=\"admin\" value=\"admin\">";
				}
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
					<form name="editor" id="editor" method="POST" autocomplete="on" action="usuariosCheck.php?op=<?=$OP?>&idUsr=<?=$IDUSR?>">
						<!--INPUTS DEL EDITOR-->
						<div class="divDataInput" id="formEditorData">
							<fieldset id="formEditorDatafield">
								<legend>DATOS DEL USUARIO</legend>
								<!--COLUMNA IZQUIERDA (ID USUARIO)-->
								<div class="divColumna" id="columnaIzquierda">
									<?=$usernameInput?>
								</div>
								<!--|FIN| COLUMNA IZQUIERDA (ID USUARIO)-->
								<!--COLUMNA DERECHA (DATOS USUARIO)-->
								<div class="divColumna" id="columnaDerecha">
									<div class="divFila" id="divFilaNombre">
										<div>
											<label for="nombre" id="nombre">NOMBRE:</label>
										</div>
										<div style="margin-left: 102px">
											<?=$nombreInput?>
										</div>
									</div>
									<div class="divFila" id="divFilaApe">
										<div>
											<label for="apellidos" id="apellidos">APELLIDOS:</label>
										</div>
										<div style="margin-left: 88px">
											<?=$apeInput?>
										</div>
									</div>
									<div class="divFila" id="divFilaAdmin">
										<div>
											<label for="admin" id="admin">ADMINISTRADOR:</label>
										</div>
										<div style="margin-left: 50px">
											<?=$admChkBox?>
										</div>
									</div>
								</div>
							</fieldset>
								<!--|FIN| COLUMNA DERECHA (DATOS ARTÍCULO)-->
							<div class="divFilaBotones">
								<input class="botónCancelar" type="button" onclick="cancelar('<?=$IDUSR?>','<?=$OP?>')" value="VOLVER"/>
								<input class="botónAceptar" type="submit" value="<?=$submitBtnValue?>" />
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